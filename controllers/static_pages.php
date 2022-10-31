<?php

namespace controllers;

class static_pages extends base_controller {

    function render($page) {
       
        return $this->return_html($page . '.html', []);
        
    }
    
}