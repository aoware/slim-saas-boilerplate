<?php

namespace controllers;

class administrators extends base_controller {

    function list() {
       
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_redirection(CONF_base_url);
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_redirection(CONF_base_url);
        }
        
        $u = new \models\users();
        $u->getRecordsByType('agent');
        
        $recordSet = array();
        foreach($u->recordSet as $u_record) {
            
            $record = array(
                'email'        => $u_record->email,
                'first_name'   => $u_record->first_name,
                'last_name'    => $u_record->last_name,
                'date_created' => $u_record->created,
                'date_updated' => $u_record->modified,
                'last_login'   => $u_record->last_login,
                'actions' => array(
                    array('label' => 'View/Update' ,'icon' => 'edit'      ,'action' => CONF_base_url . '/backoffice/administrator/' . $u_record->id . '/update') ,
                    array('label' => 'Delete'      ,'icon' => 'trash-alt' ,'action' => 'delete_administrator(' . $u_record->id . ');')
                )
                
            );
            array_push($recordSet,$record);
            
        }
        
        $table = [
            'columns' => [
                ['name' => 'Email'     ,'sortable' => true],
                ['name' => 'First Name','sortable' => true],
                ['name' => 'Last Name' ,'sortable' => true],
                ['name' => 'Created'   ,'sortable' => true],
                ['name' => 'Updated'   ,'sortable' => true],
                ['name' => 'Last Login','sortable' => true],
                ['name' => 'Actions'   ,'sortable' => false]
            ],
            'recordset'      => $recordSet,
            'new_record_url' => '/backoffice/administrator/new'
        ];
        
        return $this->return_html('administrators_list.html',[
            'users_table'  => $table
        ]);
        
    }
    
    function display($administrator_id = null) {
        
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_redirection(CONF_base_url);
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_redirection(CONF_base_url);
        }
        
        if (is_null($administrator_id)) {
            $u_record = null;
        }
        else {
            $u = new \models\users();
            $u->getRecordById($administrator_id);
            $u_record = $u->recordSet[0];
        }
        
        return $this->return_html('administrator_display.html',[
            'administrator_id'  => $administrator_id,
            'administrator'     => $u_record
        ]);
        
    }

    function insert() {
        
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
        if (count($u->recordSet) > 0) {
            return $this->return_json(false,"User already registered");
        }
        
        $u->oauth_provider     = 'email';
        $u->oauth_uid          = $post_variables['email'];
        $u->password           = '';
        $u->first_name         = $post_variables['first_name'];
        $u->last_name          = $post_variables['last_name'];
        $u->email              = $post_variables['email'];
        $u->location           = null;
        $u->picture            = null;
        $u->link               = null;
        $u->type               = 'agent';
        $u->created            = date('Y-m-d H:i:s');
        $u->modified           = null;
        $u->last_login         = null;
        $u->registration_ip    = null;
        $u->verification_token = null;
        $u->verification_date  = null;
        $u->verification_ip    = null;
        $u->login_token        = null;
        
        $result_save = $u->saveRecord();
        
        if ($result_save !== true) {
            throw new \Exception($result_save);
        }
        
        $data = [
            'redirection' => CONF_base_url . "/backoffice/administrators"
        ];
        
        return $this->return_json(true,'Administrator created',$data);
        
    }
    
    function update($administrator_id) {
        
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
            if ($u->recordSet[0]->id != $administrator_id) {
                return $this->return_json(false,"User already registered");
            }
        }
        
        $result_read = $u->getRecordById($administrator_id);
        
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
        $u->created            = $u_record->created;
        $u->modified           = date('Y-m-d H:i:s');
        $u->last_login         = $u_record->last_login;
        $u->registration_ip    = $u_record->registration_ip;
        $u->verification_token = $u_record->verification_token;
        $u->verification_date  = $u_record->verification_date;
        $u->verification_ip    = $u_record->verification_ip;
        $u->login_token        = $u_record->login_token;

        $result_update = $u->updateRecord($administrator_id);
        
        if ($result_update !== true) {
            throw new \Exception($result_update);
        }
        
        $data = [
            'redirection' => CONF_base_url . "/backoffice/administrators"
        ];
        
        return $this->return_json(true,'Administrator updated',$data);
        
    }
    
    function delete($administrator_id) {
        
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        
        $u = new \models\users();
        $result_delete = $u->deleteRecord($administrator_id);
        
        if ($result_delete !== true) {
            throw new \Exception($result_delete);
        }
        
        return $this->return_json(true,'Administrator deleted');
        
    }
    
}