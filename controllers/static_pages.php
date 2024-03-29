<?php

namespace controllers;

class static_pages extends base_controller {

    function render_page($page,$session_type = null) {

        return $this->return_html($page . '.html', []);

    }

    function render_public_page($page,$session_type = null) {

        return $this->return_html('brands/' . CONF_public_brand . '/' . $page . '.html', []);

    }

    function render_manifest() {

        return $this->return_custom_content('site.webmanifest.json', [], 'application/manifest+json');

    }

    function render_global() {

        return $this->return_custom_content('global.js', [], 'application/javascript');

    }

}