<?php

namespace controllers;

class static_pages extends base_controller {

    function render_page($page,$session_type = null) {

        $session = $this->check_login_session();

        if (!is_null($session_type)) {
            if ($session['is_logged'] === false) {
                return $this->return_redirection(CONF_base_url);
            }
        }

        return $this->return_html($page . '.html', []);

    }

    function render_public_page($page,$session_type = null) {
        
        $session = $this->check_login_session();
        
        if (!is_null($session_type)) {
            if ($session['is_logged'] === false) {
                return $this->return_redirection(CONF_base_url);
            }
        }
        
        return $this->return_html('brands/' . CONF_public_brand . '/' . $page . '.html', []);
        
    }
    
    function render_manifest() {

        return $this->return_custom_content('site.webmanifest.json', [], 'application/manifest+json');

    }

    function render_global() {
        
        return $this->return_custom_content('global.js', [], 'application/javascript');
        
    }
    
}