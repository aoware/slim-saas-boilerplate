<?php

namespace controllers;

class base_controller {

    protected $is_logged;
    protected $current_user_id;
    protected $current_user_name;
    protected $current_user_picture;
    protected $current_user_type;
    protected $current_account_id;
    protected $current_account_name;
    protected $web_session_log_id;

    function __construct($app, $request, $response, $args) {

        $this->is_logged            = $request->getAttribute("is_logged");
        $this->current_user_id      = $request->getAttribute("current_user_id");
        $this->current_user_name    = $request->getAttribute("current_user_name");
        $this->current_user_picture = $request->getAttribute("current_user_picture");
        $this->current_user_type    = $request->getAttribute("current_user_type");
        $this->current_account_id   = $request->getAttribute("current_account_id");
        $this->current_account_name = $request->getAttribute("current_account_name");
        $this->web_session_log_id   = $request->getAttribute("web_session_log_id");

        $this->app                      = $app;
        $this->request                  = $request;
        $this->response                 = $response;
        $this->args                     = $args;
        $this->db                       = $this->app->get('db');
        $this->dbal                     = $this->app->get('dbal');
        $this->view                     = $this->app->get('twig');
        $this->template_options         = $this->app->get('template_options');
        $this->email_template_processor = $this->app->get('email_template');

        $this->template_options = array_merge($this->app->get('template_options'),
            [
                'is_logged'            => $this->is_logged,
                'current_user_id'      => $this->current_user_id,
                'current_user_name'    => $this->current_user_name,
                'current_user_picture' => $this->current_user_picture,
                'current_user_type'    => $this->current_user_type,
                'current_account_id'   => $this->current_account_id,
                'current_account_name' => $this->current_account_name,
                'web_session_log_id'   => $this->web_session_log_id
            ]
            );

    }

    function return_html($view,$content = []) {

        $final_content = array_merge($this->template_options,$content);

        return $this->view->render($this->response, $view, $final_content);

    }

    function return_json($success,$message,$response_data = null) {

        $result = [
            "success" => $success,
            "message" => $message,
            "data"    => $response_data
        ];

        $payload = json_encode($result);

        $this->response->getBody()->write($payload);

        return $this->response->withHeader('Content-Type', 'application/json');

    }

    function return_redirection($url,$http_status = 302) {

        return $this->response->withHeader('Location', $url)
                              ->withStatus($http_status);

    }

    function return_custom_content($view,$content,$mime_type) {

        $loader = new \Twig\Loader\FilesystemLoader('../views');

        $custom_twig = new \Twig\Environment($loader, [
            'cache' => false
        ]);

        $custom_template = $custom_twig->load($view);
        $final_content   = array_merge($this->app->get('template_options'), $content);
        $final_rendered  = $custom_template->render($final_content);

        $this->response->getBody()->write($final_rendered);

        return $this->response->withHeader('Content-Type', $mime_type);

    }

    function return_file($file_path,$file_name,$mime_type) {

        $stream = new \Slim\Psr7\Factory\StreamFactory;

        $response = $this->response->withHeader('Content-Type', $mime_type)
        ->withHeader('Content-Disposition', 'attachment; filename="' . $file_name . '"');

        return $response->withBody($stream->createStreamFromFile($file_path, 'r'));

    }

    function check_login_session() {

        $this->template_options['is_logged']    = false;
        $this->template_options['user_type']    = null;
        $this->template_options['user_id']      = null;
        $this->template_options['user_name']    = null;
        $this->template_options['user_picture'] = null;
        $this->template_options['account_id']   = null;
        $this->template_options['account_name'] = null;

        if (isset($_SESSION['login_token'])) {
            $u = new \models\users;
            $u->getRecordByLogin_token($_SESSION['login_token']);
            if (count($u->recordSet) == 0) {
                $result = [
                    'is_logged' => false,
                    'user_type' => null,
                    'user_id'   => null,
                    'user_name' => null
                ];
            }
            else {
                $u_record = $u->recordSet[0];

                $result = [
                    'is_logged' => true,
                    'user_type' => $u_record->type,
                    'user_id'   => $u_record->id,
                    'user_name' => $u_record->email
                ];

                $this->template_options['is_logged'] = true;
                $this->template_options['user_type'] = $u_record->type;
                $this->template_options['user_id']   = $u_record->id;
                $this->template_options['user_name'] = trim($u_record->first_name . ' ' . $u_record->last_name);

                if ($u_record->picture == '') {
                    $u_record->picture = "https://eu.ui-avatars.com/api/?background=0D8ABC&color=fff&size=250&name=" . urlencode(trim($u_record->first_name . ' ' . $u_record->last_name));
                }

                $this->template_options['user_picture'] = $u_record->picture;

                if ($u_record->type == 'client') {

                    $query = "SELECT *
                              FROM account_users as a , accounts as b
                              WHERE a.account_id = b.id
                              and a.user_id = %i";
                    $query_result = $this->dbal->query($query, $u_record->id);
                    $agent = $query_result->fetch();

                    $this->template_options['account_id']   = $agent->id;
                    $this->template_options['account_name'] = $agent->name;
                }

            }
        }
        else {
            $result = [
                'is_logged' => false,
                'user_type' => null,
                'user_id'   => null,
                'user_name' => null
            ];
        }

        return $result;

    }
}