<?php

namespace controllers;

class my_profile extends base_controller {

    function display($user_area) {

        $u = new \models\users();
        $u->getRecordById($this->current_user_id);
        $u_record = $u->recordSet[0];

        // 2FA data
        $google_2fa        = new \PragmaRX\Google2FA\Google2FA();
        $google_2fa_secret = $google_2fa->generateSecretKey(32);
        $google_2fa_url    = $google_2fa->getQRCodeUrl($this->template_options['brand_name'], $u_record->email, $google_2fa_secret);

        $qr_code           = new \chillerlan\QRCode\QRCode();
        $google_2fa_url_qr = $qr_code->render($google_2fa_url);

        return $this->return_html($user_area . '_my_profile.html',[
            'user'              => $u_record,
            'user_area'         => $user_area,
            'google_2fa_secret' => $google_2fa_secret,
            'google_2fa_url_qr' => $google_2fa_url_qr
        ]);

    }

    function update($user_area) {

        $post_variables = $this->request->getParsedBody();

        $ev = new \helpers\email_validation;
        $result_ev = $ev->validate($post_variables['email']);

        // If email is valid, let's check if already in the DB
        if ($result_ev['validation'] === false) {
            return $this->return_json(false,"Invalid email");
        }

        $u = new \models\users();
        $u->getRecordByEmail($post_variables['email']);
        if (count($u->recordSet) == 1) {
            if ($u->recordSet[0]->id != $this->current_user_id) {
                return $this->return_json(false,"User already registered");
            }
        }

        $result_read = $u->getRecordById($this->current_user_id);

        if ($result_read !== true) {
            throw new \Exception($result_read);
        }

        $u_record = $u->recordSet[0];
        $u->oauth_provider     = $u_record->oauth_provider;
        $u->oauth_uid          = $u_record->oauth_uid;
        $u->password           = $u_record->password;
        $u->first_name         = $post_variables['first_name'];
        $u->last_name          = $post_variables['last_name'];
        $u->email              = $post_variables['email'];
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
        $u->login_token        = $u_record->login_token;

        $result_update = $u->updateRecord($this->current_user_id);

        if ($result_update !== true) {
            throw new \Exception($result_update);
        }

        return $this->return_json(true,'My profile updated');

    }

    function update_password($user_area) {

        $post_variables = $this->request->getParsedBody();

        $u = new \models\users();
        $result_read = $u->getRecordById($this->current_user_id);

        if ($result_read !== true) {
            throw new \Exception($result_read);
        }

        $u_record = $u->recordSet[0];

        if ($u_record->password != md5($post_variables['old_password'])) {
            return $this->return_json(false,'Invalid Password ' . $u_record->password . ' - ' . md5($post_variables['old_password']));
        }

        if ($post_variables['new_password'] != $post_variables['confirmed_password']) {
            return $this->return_json(false,"Passwords don't match");
        }

        $u->oauth_provider     = $u_record->oauth_provider;
        $u->oauth_uid          = $u_record->oauth_uid;
        $u->password           = md5($post_variables['new_password']);
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
        $u->login_token        = $u_record->login_token;

        $result_update = $u->updateRecord($this->current_user_id);

        if ($result_update !== true) {
            throw new \Exception($result_update);
        }

        return $this->return_json(true,'Password updated');

    }

    function update_2fa_enable($user_area) {

        $post_variables = $this->request->getParsedBody();

        $google_2fa = new \PragmaRX\Google2FA\Google2FA();
        $valid = $google_2fa->verifyKey($post_variables["secret"], $post_variables["code"]);
        if ($valid == false) {
            return $this->return_json(false,"The code is invalid and cannot be verified");
        }

        $u = new \models\users();
        $result_read = $u->getRecordById($this->current_user_id);

        if ($result_read !== true) {
            throw new \Exception($result_read);
        }

        $u_record = $u->recordSet[0];

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
        $u->modified           = date('Y-m-d H:i:s');
        $u->last_login         = $u_record->last_login;
        $u->registration_ip    = $u_record->registration_ip;
        $u->verification_token = $u_record->verification_token;
        $u->verification_date  = $u_record->verification_date;
        $u->verification_ip    = $u_record->verification_ip;
        $u->mfa_token          = $post_variables["secret"];
        $u->login_token        = $u_record->login_token;

        $result_update = $u->updateRecord($this->current_user_id);

        if ($result_update !== true) {
            throw new \Exception($result_update);
        }

        return $this->return_json(true,'Mfa updated');

    }

    function update_2fa_disable($user_area) {
                
        $u = new \models\users();
        $result_read = $u->getRecordById($this->current_user_id);
        
        if ($result_read !== true) {
            throw new \Exception($result_read);
        }
        
        $u_record = $u->recordSet[0];
        
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
        $u->modified           = date('Y-m-d H:i:s');
        $u->last_login         = $u_record->last_login;
        $u->registration_ip    = $u_record->registration_ip;
        $u->verification_token = $u_record->verification_token;
        $u->verification_date  = $u_record->verification_date;
        $u->verification_ip    = $u_record->verification_ip;
        $u->mfa_token          = null;
        $u->login_token        = $u_record->login_token;
        
        $result_update = $u->updateRecord($this->current_user_id);
        
        if ($result_update !== true) {
            throw new \Exception($result_update);
        }
        
        return $this->return_json(true,'Mfa updated');
        
    }
    
}