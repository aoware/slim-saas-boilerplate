<?php

namespace helpers;

class headers {

    private $headers;

    function __construct($request) {

        $this->headers = $request->getHeaders();

    }

    function get_ip() {

        $ip_address = null;

        if (isset($_SERVER["REMOTE_ADDR"])) {
            $ip_address = $_SERVER["REMOTE_ADDR"];
        }

        foreach($this->headers as $header_name => $header_values) {
            if ($header_name == 'Cf-Connecting-Ip') {
                $ip_address = $header_values[0];
            }
        }

        return $ip_address;

    }

    function get_user_agent() {

        $user_agent = null;

        foreach($this->headers as $header_name => $header_values) {
            if ($header_name == 'User-Agent') {
                $user_agent = $header_values[0];
            }
        }

        return $user_agent;

    }

    function get_http_host() {

        $http_host = null;

        foreach($this->headers as $header_name => $header_values) {
            if ($header_name == 'Host') {
                $http_host = $header_values[0];
            }
        }

        return $http_host;

    }

    function get_content_type() {

        $content_type = null;

        foreach($this->headers as $header_name => $header_values) {
            if ($header_name == 'Content-Type') {
                $content_type = $header_values[0];
            }
        }

        return $content_type;

    }

    function get_accept() {

        $content_type = null;

        foreach($this->headers as $header_name => $header_values) {
            if ($header_name == 'Accept') {
                $content_type = $header_values[0];
            }
        }

        return $content_type;

    }

}