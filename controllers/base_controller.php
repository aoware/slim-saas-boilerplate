<?php

namespace controllers;

class base_controller {

    function __construct($app, $request, $response, $args) {
        
        $this->app      = $app;
        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;
        $this->db       = $this->app->get('db');

    }

    function return_html($view,$content) {
               
        $final_content = array_merge($this->app->get('template_options'),$content);
        
        return $this->app->get('twig')->render($this->response, $view, $final_content);
        
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
    
}