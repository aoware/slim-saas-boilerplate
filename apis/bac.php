<?php

namespace apis;

class bac {

    private $bac_key;
    private $bac_password;
    
    public function __construct($bac_key,$bac_password) {
        
        $this->bac_key      = $bac_key;
        $this->bac_password = $bac_password;
        
    }
    
    public function validate_email($email) {

        $parameters = "type=email&email=" . urlencode($email);

        $response = $this->callEndpointUsingGet($parameters);
        if ($response === false) {
              return false;
        }

        return $response;

    }


    private function callEndpointUsingGet($parameters) {

        $url = "https://tls.bankaccountchecker.com/listener.php?&output=json&key=" . CONF_bac_key . "&password=" . CONF_bac_password . "&" . $parameters;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        // Return errors if any:
        if ($response === FALSE) {
        	echo "Error connecting to Telegram API, error: => " . curl_error($ch);
            error_log("Error connecting to Telegram API, error: => " . curl_error($ch));
            return false;
        }

        curl_close ($ch);

        $responseArray = json_decode($response,true);

        return $responseArray;

    }
    
}