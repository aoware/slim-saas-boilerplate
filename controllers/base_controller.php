<?php

namespace controllers;

class base_controller {

    function __construct($app, $request, $response, $args) {
        
        $this->app                      = $app;
        $this->request                  = $request;
        $this->response                 = $response;
        $this->args                     = $args;
        $this->db                       = $this->app->get('db');
        $this->view                     = $this->app->get('twig');
        $this->template_options         = $this->app->get('template_options');
        $this->email_template_processor = $this->app->get('email_template');

    }

    function return_html($view,$content) {
               
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
        
        $final_content = array_merge($this->app->get('template_options'),$content);
    
        $this->app->response->headers->set('Content-Type', $mime_type);
        
        return $this->view->render($this->response, $view, $final_content);
        
    }
    
    function check_login_session() {
        
        $this->template_options['is_logged'] = false;
        $this->template_options['user_type'] = null;
        $this->template_options['user_id']   = null;
        $this->template_options['user_name'] = null;
        
        if (isset($_SESSION['login_token'])) {
            $u = new \models\users;
            $u->getRecordsByLogin_token($_SESSION['login_token']);
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
                $this->template_options['user_name'] = $u_record->email;
                
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