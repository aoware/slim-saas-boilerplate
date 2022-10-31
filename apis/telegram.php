<?php

namespace apis;

class telegram {

    private $bot_id;
    private $bot_token;
    
    public function __construct($bot_id,$bot_token) {
        
        $this->bot_id    = $bot_id;
        $this->bot_token = $bot_token;
        
    }
    
    public function getMe() {

        $path = "/getMe";

        $response = $this->callEndpointUsingGet($path);
        if ($response === false) {
              return false;
        }

        return $response;

    }

    public function getUpdates() {

        $path = "/getUpdates";

        $response = $this->callEndpointUsingGet($path);
        if ($response === false) {
              return false;
        }

        return $response;

    }

    public function sendMessage($chat_id,$message) {

    	$payload = "&parse_mode=html&disable_web_page_preview=false&text=" . urlencode($message);

        $result = true;
        $recipients = explode(',', $chat_id);
        foreach ($recipients as $recipient) {
            $path = "/sendmessage?chat_id=" . $recipient . $payload;
            if (!$this->callEndpointUsingGet($path)) {
                $result = false;
            }
        }

        return $result;

    }

    public function sendWebhookMessage($chat_id,$message) {

        $path = "/sendMessage?chat_id=$chat_id&text=" . urlencode($message);

        $response = $this->callEndpointUsingGet($path);
        if ($response === false) {
            return false;
        }

        return $response;

    }

    public function sendWebhookHtmlMessage($chat_id,$message,$reply_markup = "") {

        $path = "/sendMessage?chat_id=$chat_id&parse_mode=html&disable_web_page_preview=false&text=" . urlencode($message);

        if ($reply_markup != "") {
            $path .= "&reply_markup=" . urlencode($reply_markup);
        }

        $response = $this->callEndpointUsingGet($path);
        if ($response === false) {
            return false;
        }

        return $response;

    }

    public function sendLocation($chat_id,$latitude,$longitude) {

        $path = "/sendLocation?chat_id=$chat_id&latitude=$latitude&longitude=$longitude";

        $response = $this->callEndpointUsingGet($path);
        if ($response === false) {
            return false;
        }

        return $response;

    }

    public function sendPhoto($chat_id,$photo,$caption) {

        if (substr($photo,0,1) == "@") {
            $photo = substr($photo,1);
        }

        $path = "/sendPhoto?chat_id=$chat_id&caption=$caption&photo=$photo";

        $response = $this->callEndpointUsingGet($path);
        if ($response === false) {
            return false;
        }

        return $response;

    }

    public function sendLocalPhoto($chat_id,$photo,$caption = "") {

        $path = "/sendPhoto?chat_id=" . $chat_id;

        $fields = array();
        $fields['chat_id']  = $chat_id;
        if ($caption != "") {
            $fields['caption']  = $caption;
        }

        $fields['photo'] = $this->curlFile($photo);

        $response = $this->callEndpointUsingPost($path,$fields);

        if ($response === false) {
            return false;
        }

        return $response;

    }

    // To send Attachment
    public function sendLocalDocument($chat_id,$document) {

        $path = "/sendDocument?chat_id=" . $chat_id;

        $fields = array();
        $fields['chat_id']  = $chat_id;

        $fields['document'] = $this->curlFile($document);

        $response = $this->callEndpointUsingPost($path,$fields);

        if ($response === false) {
            return false;
        }

        return $response;

    }

    // To send MP3
    public function sendLocalAudio($chat_id,$audio) {

        $path = "/sendAudio?chat_id=" . $chat_id;

        $fields = array();
        $fields['chat_id']  = $chat_id;

        $fields['audio'] = $this->curlFile($audio);

        $response = $this->callEndpointUsingPost($path,$fields);

        if ($response === false) {
            return false;
        }

        return $response;

    }

    // To send ogg file
    public function sendLocalVoice($chat_id,$voice) {

        $path = "/sendVoice?chat_id=" . $chat_id;

        $fields = array();
        $fields['chat_id']  = $chat_id;

        $fields['voice'] = $this->curlFile($voice);

        $response = $this->callEndpointUsingPost($path,$fields);

        if ($response === false) {
            return false;
        }

        return $response;

    }

    private function callEndpointUsingGet($path) {

        $url = "https://api.telegram.org/bot" . $this->bot_id . ":" . $this->bot_token . $path;

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

    private function callEndpointUsingPost($path,$fields) {

        $url = "https://api.telegram.org/bot" . $this->bot_id . ":" . $this->bot_token . $path;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL           , $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST          , true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER    , array("Content-Type:multipart/form-data"));
        curl_setopt($ch, CURLOPT_POSTFIELDS    , $fields);

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

    private function curlFile($fileName) {

        // set realpath
        $filename = realpath($fileName);

        // check file
        if (!is_file($filename))
            throw new Exception('File does not exists');
            // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
            // See: https://wiki.php.net/rfc/curl-file-upload
            if (function_exists('curl_file_create'))
                return curl_file_create($filename);

            // Use the old style if using an older version of PHP
            return "@$filename";
    }

}