<?php

namespace controllers;

class your_profile extends base_controller {
    
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
        
        return $this->return_html($user_area . '_your_profile.html',[
            'user' => $u_record
        ]);
        
    }
    
}