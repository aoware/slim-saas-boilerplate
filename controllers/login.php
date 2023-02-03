<?php

namespace controllers;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class login extends base_controller {

    function login_google() {

        $get_variables = $this->request->getQueryParams();

        if (isset($get_variables['code'])) {

            $gClient = new \Google_Client();
            $gClient->setApplicationName(CONF_google_login_application_name);
            $gClient->setClientId(CONF_google_login_client_id);
            $gClient->setClientSecret(CONF_google_login_client_secret);
            $gClient->setRedirectUri(CONF_google_login_redirect_url);

            $gClient->fetchAccessTokenWithAuthCode($get_variables['code']);

            // Exchange the auth code for a token
            $_SESSION['google_access_token'] = $gClient->getAccessToken();

            $google_oauthV2 = new \Google_Service_Oauth2($gClient);

            $gUserProfile = $google_oauthV2->userinfo->get();

            $u = new \models\users;
            $result = $u->google_login($gUserProfile);

            if (!is_null($result['already_registered'])) {
                header("Location: " . CONF_base_url . "/travel-talent/register?already_registered=" . $result['already_registered']);
            }
            else {
                $gClient->setAccessToken($_SESSION['google_access_token']);
                $_SESSION['login_token'] = $result['login_token'];

                $redirection_url = $this->talent_redirection_url($result['user_id']);

                header("Location: " . $redirection_url);
            }

            exit;
        }
        else {
            header("Location: " . CONF_base_url . "/login/google/error");
            exit;
        }

    }

    function login_facebook() {

        $get_variables = $this->request->getQueryParams();

        if (isset($get_variables['error'])) {

            $t = new \apis\telegram(CONF_telegram_bot_id,CONF_telegram_bot_token);
            $t->sendMessage(CONF_telegram_admin_id, "FB login error " . $get_variables['error'] . " with description " . $get_variables['error_description'] . " - " . $get_variables['error_reason']);

            $result = [
                'type' => 'redirection',
                'path' => CONF_base_url . "/travel-talent/register"
            ];

            return $result;
        }

        // Call Facebook API
        $fClient = new Facebook(array(
            'app_id' => CONF_facebook_login_client_id,
            'app_secret' => CONF_facebook_login_client_secret,
            'default_graph_version' => 'v3.2',
        ));

        // Get redirect login helper
        $fHelper = $fClient->getRedirectLoginHelper();

        $accessToken = $fHelper->getAccessToken();

        // Put short-lived access token in session
        $_SESSION['facebook_access_token'] = (string) $accessToken;

        // OAuth 2.0 client handler helps to manage access tokens
        $oAuth2Client = $fClient->getOAuth2Client();

        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

        // Set default access token to be used in script
        $fClient->setDefaultAccessToken($_SESSION['facebook_access_token']);

        // Getting user's profile info from Facebook
        $graphResponse      = $fClient->get('/me?fields=name,first_name,last_name,email,link,gender,picture');
        $facebook_user_data = $graphResponse->getGraphUser();

        $u = new \models\users;
        $result = $u->facebook_login($facebook_user_data);

        if (!is_null($result['already_registered'])) {

            $result = [
                'type' => 'redirection',
                'path' => CONF_base_url . "/travel-talent/register?already_registered=" . $result['already_registered']
            ];
        }
        else {
            $_SESSION['login_token'] = $result['login_token'];

            $result = [
                'type' => 'redirection',
                'path' => $this->talent_redirection_url($result['user_id'])
            ];
        }

        return $result;
    }

    function login_email() {

        $post_variables = $this->request->getParsedBody();

        $u = new \models\users;
        $result = $u->getRecordByOauth_provider_oauth_uid('email',$post_variables['login_email']);

        if ($result !== true) {
            throw new \Exception($result);
        }

        if (count($u->recordSet) == 0) {
            return $this->return_json(false,"You have entered an invalid email or password");
        }

        $u_record = $u->recordSet[0];

        if (md5($post_variables['login_password']) != $u_record->password) {
            return $this->return_json(false,"You have entered an invalid email or password");
        }

        if ($u_record->active == 0) {
            return $this->return_json(false,"You have entered an invalid email or password");
        }

        unset($u);

        $u = new \models\users;

        $sm = new \helpers\string_manipulation;
        $login_token = $sm->generate_random_code(32);

        $u->getRecordByLogin_token($login_token);

        while(count($u->recordSet) > 0) {
            $login_token = $sm->generate_random_code(32);
            $u->getRecordByLogin_token($login_token);
        }

        $u->oauth_provider     = $u_record->oauth_provider;
        $u->oauth_uid          = $u_record->oauth_uid;
        $u->password           = $u_record->password;
        $u->first_name         = $u_record->first_name;
        $u->last_name          = $u_record->last_name;
        $u->email              = $u_record->email;
        $u->location           = $u_record->location;
        $u->picture            = $u_record->picture;
        $u->link               = $u_record->link;
        $u->type               = $u_record->type;
        $u->active             = $u_record->active;
        $u->created            = $u_record->created;
        $u->modified           = $u_record->modified;
        $u->registration_ip    = $u_record->registration_ip;
        $u->last_login         = date('Y-m-d H:i:s');
        $u->verification_token = $u_record->verification_token;
        $u->verification_date  = $u_record->verification_date;
        $u->verification_ip    = $u_record->verification_ip;
        $u->mfa_token          = $u_record->mfa_token;
        $u->login_token        = $login_token;

        $u->updateRecord($u_record->id);

        $_SESSION['login_token'] = $login_token;

        switch($u_record->type) {
            case 'agent' :
                $redirection = '/backoffice';
                break;
            case 'client' :
                $redirection = '/dashboard';
                break;
        }

        $response = [
            "redirection" => $redirection
        ];

        return $this->return_json(true,"Log in Success",$response);

    }

    function sign_up() {

        $post_variables = $this->request->getParsedBody();

        $ev = new \helpers\email_validation;
        $result_ev = $ev->validate($post_variables['email']);

        if ($result_ev['validation'] === false) {
            return $this->return_json(false,"Invalid email");
        }

        $u = new \models\users;
        $u->getRecordByOauth_provider_oauth_uid('email',$post_variables['email']);
        if (count($u->recordSet) > 0) {
            return $this->return_json(false,"Email already registered");
        }

        $passwordReg = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";

        if (!preg_match($passwordReg, $post_variables['password'])) {
            return $this->return_json(false,"Invalid password");
        }

        $h = new \helpers\http_headers($this->request);
        $ip_address = $h->get_ip();

        $result_registration = $u->email_register(
            $post_variables['first_name'],
            $post_variables['last_name'],
            $post_variables['email'],
            $post_variables['password'],
            'client',
            $ip_address);

        $sm = new \helpers\string_manipulation;

        $a = new \models\accounts();
        $a->name     = $post_variables['name'];
        $a->slug     = $sm->slugify($post_variables['name']);
        $a->created  = date('Y-m-d H:i:s');
        $a->modified = null;

        $result_account = $a->saveRecord();

        if ($result_account !== true) {
            throw new \Exception($result_account);
            return false;
        }

        $au = new \models\account_users();
        $au->account_id = $a->inserted_id;
        $au->user_id    = $result_registration['user_id'];
        $au->created  = date('Y-m-d H:i:s');
        $au->modified = null;

        $result_account_user = $au->saveRecord();

        if ($result_account_user !== true) {
            throw new \Exception($result_account);
            return false;
        }

        $recipient_name  = trim($post_variables['first_name'] . " " . $post_variables['last_name']);
        $recipient_email = $post_variables['email'];
        $params = array_merge($this->template_options,[
            "verification_token" => $result_registration['verification_token']
        ]);
        $e = new \helpers\email;
        $result_email = $e->send_email(
            'user_verification',
            $recipient_name,
            $recipient_email,
            $params,
            $this->email_template_processor
        );

        return $this->return_json(true,"Sign up Success");
    }

    function verify($token) {

        $h = new \helpers\http_headers($this->request);
        $ip_address = $h->get_ip();

        $u = new \models\users;
        $u->getRecordByVerification_token($token);

        if (count($u->recordSet) == 1) {

            $u_record = $u->recordSet[0];

            if (is_null($u_record->verification_date)) {

                $u->id                 = $u_record->id;
                $u->oauth_provider     = $u_record->oauth_provider;
                $u->oauth_uid          = $u_record->oauth_uid;
                $u->password           = $u_record->password;
                $u->first_name         = $u_record->first_name;
                $u->last_name          = $u_record->last_name;
                $u->email              = $u_record->email;
                $u->location           = $u_record->location;
                $u->picture            = $u_record->picture;
                $u->link               = $u_record->link;
                $u->type               = $u_record->type;
                $u->active             = 1;
                $u->created            = $u_record->created;
                $u->modified           = $u_record->modified;
                $u->last_login         = $u_record->last_login;
                $u->registration_ip    = $u_record->registration_ip;
                $u->verification_token = $u_record->verification_token;
                $u->verification_date  = date('Y-m-d H:i:s');
                $u->verification_ip    = $ip_address;
                $u->login_token        = $u_record->login_token;

                $result_update = $u->updateRecord($u_record->id);

                if ($result_update !== true) {
                    throw new \Exception($result_update);
                }

            }

            return $this->return_html('brands/' . CONF_public_brand . '/verify_confirmed.html');
        }
        else {
            return $this->return_html("log_inx.html");
        }

    }



    function reset_password() {

        $post_variables = $this->request->getParsedBody();

        $h = new \helpers\http_headers;
        $ip_address = $h->get_ip($this->request->getHeaders());

        // ignore if email not known in our DB
        // ALWAYS return a positive outcome to not disclose email is in our DB

        $u = new \models\users;
        $u->getRecordByOauth_provider_oauth_uid('email',$post_variables['email']);
        if (count($u->recordSet) == 0) {
            $result = [
                "type" => "json",
                "success" => true,
                "message" => null,
                "response" => null
            ];

            $t = new \apis\telegram(CONF_telegram_bot_id,CONF_telegram_bot_token);
            $t->sendMessage(CONF_telegram_admin_id, "Someone with IP $ip_address is trying to reset password for email '" . $post_variables['email'] . "' which is not in our database.");

            return $result;
        }

        $u_record = $u->recordSet[0];

        // ignore if user is not a talent
        // ALWAYS return a positive outcome to not disclose email is in our DB

        if ($u_record->type != 'talent') {
            $result = [
                "type" => "json",
                "success" => true,
                "message" => null,
                "response" => null
            ];

            return $result;
        }

        $pr = new \models\password_reset;

        $g = new \classes\genericHelper;
        $password_token = $g->generate_random_code(32);

        $pr->getRecordByPassword_token($password_token);

        while(count($pr->recordSet) > 0) {
            $password_token = $g->generate_random_code(32);
            $pr->getRecordByPassword_token($password_token);
        }

        $pr->user_id        = $u_record->id;
        $pr->password_token = $password_token;
        $pr->request_ip     = $ip_address;
        $pr->create_date    = date('Y-m-d H:i:s');
        $pr->expiry_date    = date('Y-m-d H:i:s', strtotime ( $pr->create_date . ' + 3 hours' ));
        $pr->reset_date     = null;
        $pr->reset_ip       = null;

        $result_save = $pr->saveRecord();

        if ($result_save === true) {

            $template_id     = 4;
            $recipient_name  = null;
            $recipient_email = $post_variables['email'];
            $params          = [
                "token"      => $password_token,
                "ip_address" => $ip_address,
            ];

            $e = new \classes\email;
            $result_email = $e->sendEmail($u_record->id,$template_id,$recipient_name,$recipient_email,$params);
        }

        $result = [
            "type" => "json",
            "success" => true,
            "message" => null,
            "response" => null
        ];

        return $result;

    }

    function verify_password_token($token) {

        $pr = new \models\password_reset;
        $pr->getRecordByPassword_token($token);

        if (count($pr->recordSet) == 0) {
            $result = [
                'type'      => 'redirection',
                'path'      => CONF_base_url . "/travel-talent/reset-password?token_error=true",
                'http_code' => 303
            ];
        }
        else {
            $pr_record = $pr->recordSet[0];

            if ($pr_record->expiry_date < date('Y-m-d H:i:s') or (!is_null($pr_record->reset_date))) {
                $result = [
                    'type'      => 'redirection',
                    'path'      => CONF_base_url . "/travel-talent/reset-password?token_error=true",
                    'http_code' => 303
                ];
            }
            else {

                $result = [
                    'type' => 'html',
                    'response' => $this->app->view->render($this->response, 'talent_set_password.html',
                        array_merge($this->app->template_options,
                            [
                                'token' => $token
                            ])
                        )
                ];
            }

        }

        return $result;
    }

    function set_password() {

        $post_variables = $this->request->getParsedBody();

        $h = new \helpers\http_headers;
        $ip_address = $h->get_ip($this->request->getHeaders());

        $pr = new \models\password_reset;
        $pr->getRecordByPassword_token($post_variables['token']);

        if (count($pr->recordSet) == 0) {
            $result = [
                "type" => "json",
                "success" => false,
                "message" => "Error",
                "response" => null
            ];
        }
        else {
            $pr_record = $pr->recordSet[0];

            if ($pr_record->expiry_date < date('Y-m-d H:i:s') or (!is_null($pr_record->reset_date))) {
                $result = [
                    "type" => "json",
                    "success" => false,
                    "message" => "Error",
                    "response" => null
                ];
            }
            else {

                $u = new \models\users;
                $u->getRecordById($pr_record->user_id);

                $u_record = $u->recordSet[0];

                $u->oauth_provider     = $u_record->oauth_provider;
                $u->oauth_uid          = $u_record->oauth_uid;
                $u->password           = md5($post_variables['password']);
                $u->first_name         = $u_record->first_name;
                $u->last_name          = $u_record->last_name;
                $u->email              = $u_record->email;
                $u->location           = $u_record->location;
                $u->picture            = $u_record->picture;
                $u->link               = $u_record->link;
                $u->type               = $u_record->type;
                $u->active             = $u_record->active;
                $u->created            = $u_record->created;
                $u->modified           = date('Y-m-d H:i:s');
                $u->last_login         = $u_record->last_login;
                $u->registration_ip    = $u_record->registration_ip;
                $u->verification_token = $u_record->verification_token;
                $u->verification_date  = $u_record->verification_date;
                $u->verification_ip    = $u_record->verification_ip;
                $u->mfa_token          = $u_record->mfa_token;
                $u->login_token        = null;

                $u->updateRecord($u_record->id);

                $pr->user_id        = $pr_record->user_id;
                $pr->password_token = $pr_record->password_token;
                $pr->request_ip     = $pr_record->request_ip;
                $pr->create_date    = $pr_record->create_date;
                $pr->expiry_date    = $pr_record->expiry_date;
                $pr->reset_date     = date('Y-m-d H:i:s');
                $pr->reset_ip       = $ip_address;

                $pr->updateRecord($pr_record->id);

                $result = [
                    "type" => "json",
                    "success" => true,
                    "message" => "Success",
                    "response" => null
                ];
            }

        }

        return $result;
    }

    function log_out() {

        // Remove Google Access
        // unset($_SESSION['google_access_token']);
        // $gClient = new \Google_Client();
        // $gClient->setApplicationName(CONF_google_login_application_name);
        // $gClient->setClientId(CONF_google_login_client_id);
        // $gClient->setClientSecret(CONF_google_login_client_secret);
        // $gClient->setRedirectUri(CONF_google_login_redirect_url);
        // $gClient->revokeToken();

        // Remove Facebook Access
        // unset($_SESSION['facebook_access_token']);

        // Remove global login token
        unset($_SESSION['login_token']);

        return $this->return_redirection(CONF_base_url);

    }

    function email_validation() {

        $email = strtolower($this->request->getBody());

        $ev = new \helpers\email_validation;
        $result_ev = $ev->validate($email);

        // If email is valid, let's check if already in the DB
        if ($result_ev['validation'] === true) {
            $u = new \models\users;
            $u->getRecordByEmail($email);
            if (count($u->recordSet) > 0) {
                $result_ev['validation'] = false;
                $result_ev['message']    = 'User already registered';
            }
        }

        return $this->return_json($result_ev['validation'],$result_ev['message']);
    }

    private function talent_redirection_url($user_id) {

        $p = new \models\profiles;
        $p->getRecordByUser_id($user_id);
        if (count($p->recordSet) == 0) {
            $redirection_url = CONF_base_url . "/quick-profile/name";
        }
        else {
            $p_record = $p->recordSet[0];
            if ($p_record->quick_profile_completed == 0) {
                $redirection_url = CONF_base_url . "/quick-profile/name";
            }
            else {
                $redirection_url = CONF_base_url . "/my-profile";
            }
        }

        return $redirection_url;
    }

}