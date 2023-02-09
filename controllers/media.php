<?php

namespace controllers;

class media extends base_controller {

    function list_folders() {

        return $this->return_html('media_folders.html', []);

    }

}