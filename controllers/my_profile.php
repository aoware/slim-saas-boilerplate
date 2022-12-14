<?php

namespace controllers;

class my_profile extends base_controller {
    
    function display($user_area) {
        
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_redirection(CONF_base_url);
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_redirection(CONF_base_url);
        }
        
        $u = new \models\users();
        $u->getRecordById($session['user_id']);
        $u_record = $u->recordSet[0];
        
        return $this->return_html($user_area . '_my_profile.html',[
            'user' => $u_record
        ]);
        
    }
    
    function update() {
        
        $post_variables = $this->request->getParsedBody();
        
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        
        $ev = new \helpers\email_validation;
        $result_ev = $ev->validate($post_variables['email']);
        
        // If email is valid, let's check if already in the DB
        if ($result_ev['validation'] === false) {
            return $this->return_json(false,"Invalid email");
        }
        
        $u = new \models\users();
        $u->getRecordByEmail($post_variables['email']);
        if (count($u->recordSet) == 1) {
            if ($u->recordSet[0]->id != $session['user_id']) {
                return $this->return_json(false,"User already registered");
            }
        }
        
        $result_read = $u->getRecordById($session['user_id']);
        
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
        $u->login_token        = $u_record->login_token;
        
        $result_update = $u->updateRecord($session['user_id']);
        
        if ($result_update !== true) {
            throw new \Exception($result_update);
        }
        
        return $this->return_json(true,'My profile updated');
        
    }
    
    function update_password() {
        
        $post_variables = $this->request->getParsedBody();
        
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_json(false,'Session expired. Please login again.');
        }
               
        $u = new \models\users();       
        $result_read = $u->getRecordById($session['user_id']);
        
        if ($result_read !== true) {
            throw new \Exception($result_read);
        }
        
        $u_record = $u->recordSet[0];

        if ($u_record->password != md5($post_variables['old_password'])) {
            return $this->return_json(false,'Invalid Password');
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
        $u->login_token        = $u_record->login_token;
        
        $result_update = $u->updateRecord($session['user_id']);
        
        if ($result_update !== true) {
            throw new \Exception($result_update);
        }
        
        return $this->return_json(true,'Password updated');
        
    }
    
}