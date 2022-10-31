<?php

namespace helpers;

class http_headers {

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
            if ($header_name == 'HTTP_CF_CONNECTING_IP') {
                $ip_address = $header_values[0];
            }

        }

        return $ip_address;

    }

    function get_user_agent() {

        $user_agent = null;
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $user_agent = $_SERVER["HTTP_USER_AGENT"];
        }

        foreach($this->headers as $header_name => $header_values) {
            if ($header_name == 'HTTP_USER_AGENT') {
                $user_agent = $header_values[0];
            }

        }

        return $user_agent;

    }

    function get_http_host() {

        $http_post = null;
        if (isset($_SERVER["HTTP_HOST"])) {
            $http_post = $_SERVER["HTTP_HOST"];
        }

        foreach($this->headers as $header_name => $header_values) {
            if ($header_name == 'HTTP_HOST') {
                $http_post = $header_values[0];
            }

        }

        return $http_post;

    }

}