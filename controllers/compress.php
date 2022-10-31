<?php

namespace controllers;

class compress extends base_controller {

    function post() {
       
        $data = $this->request->getParsedBody();
        
        print_r($data);
        
        $uploadedFiles = $this->request->getUploadedFiles();
        print_r($uploadedFiles);
        die();
        
        $success = true;
        $message = 'File uploaded';
        
        return $this->return_json($success,$message);
        
    }
    
}