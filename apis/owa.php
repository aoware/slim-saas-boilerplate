<?php

namespace apis;

class owa {

    private $api_key;
    private $api_url;
    private $auth_key;
    
    public function __construct($owa_api_key,$owa_api_url,$owa_auth_key) {
        
        $this->api_key  = $owa_api_key;
        $this->api_url  = $owa_api_url;
        $this->auth_key = $owa_auth_key;        

    }
    
    public function users() {

        $parameters = "?owa_module=base&owa_version=v1&owa_do=users&owa_apiKey=" . $this->api_key;

        $url = $this->api_url . $parameters;
        
        $url .= "&owa_signature=" . $this->create_signing_string($url);
        
        $response = $this->callEndpointUsingGet($url);
        
        if ($response === false) {
            return false;
        }
        
        return $response;

    }

    public function sites() {
        
        $parameters = "?owa_module=base&owa_version=v1&owa_do=sites&owa_apiKey=" . $this->api_key;
        
        $url = $this->api_url . $parameters;
        
        $url .= "&owa_signature=" . $this->create_signing_string($url);
        
        $response = $this->callEndpointUsingGet($url);
        
        if ($response === false) {
            return false;
        }
                
        return $response;
        
    }
    
    public function report_last_seven_days($site_id) {
        
        $parameters = "?owa_module=base&owa_version=v1&owa_do=reports&owa_period=last_seven_days&owa_metrics=visits,uniqueVisitors,pageViews,bounceRate,pagesPerVisit,visitDuration&owa_dimensions=date&owa_sort=date&owa_format=json&owa_siteId=" . $site_id . "&owa_apiKey=" . $this->api_key;
        
        $url = $this->api_url . $parameters;
        
        $url .= "&owa_signature=" . $this->create_signing_string($url);
        
        $response = $this->callEndpointUsingGet($url);
        
        if ($response === false) {
            return false;
        }
        
        return $response;
        
    }
    
    private function create_signing_string($url) {
        
        $signing_string = "OWASIGNATURE" . $this->api_key . $url . date( 'Ymd' ) . $this->auth_key;
        $signing_string = hash('sha256',$signing_string);
        $signing_string = base64_encode($signing_string);
        
        return $signing_string;
    }
    
    private function callEndpointUsingGet($url) {

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