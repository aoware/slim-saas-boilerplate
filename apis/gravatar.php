<?php

namespace apis;

class gravatar {

    public $email;

    public $hash;

    public $urlImage;
    public $urlProfile;
    public $image;

    public $id;
    public $username;

    public $error;
    public $error_description;

    public function __construct() {

        $this->error = false;

    }

    public function getDetails() {

        $hash = md5(strtolower(trim($this->email)));
        
        // Get the image
        $curl_connection = curl_init("https://www.gravatar.com/avatar/$hash");
        curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36");
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_TRANSFERTEXT, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection, CURLOPT_POST, false);

        $result = curl_exec($curl_connection);

        if (!$result) {
            $this->error = true;
            $this->error_description = "ERROR Detail Call : " . curl_errno($curl_connection) . "(". curl_error($curl_connection) . ")";
        }
        else {
            if (md5($result) == "d5fe5cbcc31cff5f8ac010db72eb000c") {
                $this->urlImage = null;
                $this->image    = false;
            }
            else {
                $this->urlImage = "https://www.gravatar.com/avatar/$hash";
                $this->image    = $this->grab_image($this->urlImage);
                if ($this->image === false) {
                	$this->urlImage = null;
                }
            }
        }

        // Ensuring the image exists
        if ($this->image === false) {
        	$this->error = true;
        	$this->error_description = "Problem with the image";
        }

        // Get the profile
        $curl_connection = curl_init("https://en.gravatar.com/" . md5(strtolower(trim($this->email))) . ".xml");
        curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36");
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_TRANSFERTEXT, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection, CURLOPT_POST, false);

        $result = curl_exec($curl_connection);

        if (!$result) {
            $this->error = true;
            $this->error_description = "ERROR Detail Call : " . curl_errno($curl_connection) . "(". curl_error($curl_connection) . ")";
        }
        else {
            $xml = simplexml_load_string($result);
            $error = $xml->xpath("//error");
            if (is_array($error)  && isset($error[0])) {
                $this->error             = true;
                $this->error_description = (string) $error[0];
                return;
            }
            $this->id         = (string) $xml->entry->id;
            $this->username   = (string) $xml->entry->preferredUsername;
            $this->urlProfile = (string) $xml->entry->profileUrl;
        }
    }

    private function grab_image($url) {

        // Forcing the resolution to 250 pixels square
        $ch = curl_init ($url . "?s=250");

        curl_setopt($ch, CURLOPT_HEADER        , false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

        $result = curl_exec($ch);

        curl_close ($ch);

        //  Check if the image returned is different than the standard gravatar one
        if ((md5($result) == "b3123e29311cc5fc1d1bbd573c04ff93") or (md5($result) == "86cc15520ba29871546e408c8b1be01a")) {
            return false;
        }
        else {
            return $result;
        }
    }

}