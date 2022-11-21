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
                    array('label' => 'Delete'      ,'icon' => 'trash-alt' ,'action' => CONF_base_url . '/backoffice/administrator/' . $u_record->id . '/delete')
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
}