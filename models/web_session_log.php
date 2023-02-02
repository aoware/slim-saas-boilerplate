<?php

// This is generated code from model/generateModel.php
// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve
// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file
// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)

namespace models;

class web_session_log_record {

    public $id;
    public $session_token;
    public $user_id;
    public $ip;
    public $endpoint;
    public $method;
    public $content_type;
    public $payload;
    public $http_return_code;
    public $created_timestamp;
    public $amended_timestamp;

}

class web_session_log {

    public $id;
    public $session_token;
    public $user_id;
    public $ip;
    public $endpoint;
    public $method;
    public $content_type;
    public $payload;
    public $http_return_code;
    public $created_timestamp;
    public $amended_timestamp;

    public static $enums = array(
        "method" => array(
            "enums" => array(
                "GET",
                "POST",
            ),
            "default" => ""
        ),
    );

    public $recordSet;

    public $inserted_id;

    private $mysqli;

    public function __construct($mysqli = null) {

        if ($mysqli === null) {
            $this->mysqli = new \mysqli(CONF_mysql_host,CONF_mysql_user,CONF_mysql_password,CONF_mysql_database);
        }
        else {
            $this->mysqli = $mysqli;
        }

        $this->recordSet = array();
        $this->inserted_id = 0;

    }

    function getRecordById($key) {

        $sql = "SELECT `id`,`session_token`,`user_id`,`ip`,`endpoint`,`method`,`content_type`,`payload`,`http_return_code`,`created_timestamp`,`amended_timestamp` FROM `web_session_log` WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("i", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->session_token,$this->user_id,$this->ip,$this->endpoint,$this->method,$this->content_type,$this->payload,$this->http_return_code,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new web_session_log_record;
            $record->id                = $this->id;
            $record->session_token     = $this->session_token;
            $record->user_id           = $this->user_id;
            $record->ip                = $this->ip;
            $record->endpoint          = $this->endpoint;
            $record->method            = $this->method;
            $record->content_type      = $this->content_type;
            $record->payload           = $this->payload;
            $record->http_return_code  = $this->http_return_code;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByUser_id($key) {

        $sql = "SELECT `id`,`session_token`,`user_id`,`ip`,`endpoint`,`method`,`content_type`,`payload`,`http_return_code`,`created_timestamp`,`amended_timestamp` FROM `web_session_log` WHERE `user_id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("i", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->session_token,$this->user_id,$this->ip,$this->endpoint,$this->method,$this->content_type,$this->payload,$this->http_return_code,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new web_session_log_record;
            $record->id                = $this->id;
            $record->session_token     = $this->session_token;
            $record->user_id           = $this->user_id;
            $record->ip                = $this->ip;
            $record->endpoint          = $this->endpoint;
            $record->method            = $this->method;
            $record->content_type      = $this->content_type;
            $record->payload           = $this->payload;
            $record->http_return_code  = $this->http_return_code;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByUser_id($key,$orderBy = "") {

        $sql = "SELECT `id`,`session_token`,`user_id`,`ip`,`endpoint`,`method`,`content_type`,`payload`,`http_return_code`,`created_timestamp`,`amended_timestamp` FROM `web_session_log` WHERE `user_id` = ?";

        if ($orderBy != "") {
            $sql .= " order by " . $orderBy;
        }

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("i", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->session_token,$this->user_id,$this->ip,$this->endpoint,$this->method,$this->content_type,$this->payload,$this->http_return_code,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new web_session_log_record;
            $record->id                = $this->id;
            $record->session_token     = $this->session_token;
            $record->user_id           = $this->user_id;
            $record->ip                = $this->ip;
            $record->endpoint          = $this->endpoint;
            $record->method            = $this->method;
            $record->content_type      = $this->content_type;
            $record->payload           = $this->payload;
            $record->http_return_code  = $this->http_return_code;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByUser_id($key) {

        $sql = "delete from `web_session_log` WHERE `user_id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("i",$key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $stmt->close();

        return true;

    }

    function getAllRecords($orderBy = "") {

        $sql = "SELECT `id`,`session_token`,`user_id`,`ip`,`endpoint`,`method`,`content_type`,`payload`,`http_return_code`,`created_timestamp`,`amended_timestamp` FROM `web_session_log`";

        if ($orderBy != "") {
            $sql .= " order by " . $orderBy;
        }

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->session_token,$this->user_id,$this->ip,$this->endpoint,$this->method,$this->content_type,$this->payload,$this->http_return_code,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new web_session_log_record;
            $record->id                = $this->id;
            $record->session_token     = $this->session_token;
            $record->user_id           = $this->user_id;
            $record->ip                = $this->ip;
            $record->endpoint          = $this->endpoint;
            $record->method            = $this->method;
            $record->content_type      = $this->content_type;
            $record->payload           = $this->payload;
            $record->http_return_code  = $this->http_return_code;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function saveRecord() {

        $sql = "INSERT INTO `web_session_log` (`session_token`,`user_id`,`ip`,`endpoint`,`method`,`content_type`,`payload`,`http_return_code`,`created_timestamp`,`amended_timestamp`) values (?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("sisssssiss",$this->session_token,$this->user_id,$this->ip,$this->endpoint,$this->method,$this->content_type,$this->payload,$this->http_return_code,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $this->inserted_id = $this->mysqli->insert_id;

        $stmt->close();

        return true;

    }

    function updateRecord($key) {

        $sql = "UPDATE `web_session_log` SET `session_token` = ?,`user_id` = ?,`ip` = ?,`endpoint` = ?,`method` = ?,`content_type` = ?,`payload` = ?,`http_return_code` = ?,`created_timestamp` = ?,`amended_timestamp` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("sisssssissi",$this->session_token,$this->user_id,$this->ip,$this->endpoint,$this->method,$this->content_type,$this->payload,$this->http_return_code,$this->created_timestamp,$this->amended_timestamp,$key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $stmt->close();

        return true;

    }

    function deleteRecord($key) {

        $sql = "delete from `web_session_log` WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("i",$key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $stmt->close();

        return true;

    }

    function deleteAllRecords() {

        $sql = "truncate table `web_session_log`";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $stmt->close();

        return true;

    }

    function return_enums() {

        return self::$enums;

    }

    // =-=- Custom Code Start -=-=
    // =-=- Custom Code End -=-=

}

/*
        $wsl->id                = $wsl_record->id;
        $wsl->session_token     = $wsl_record->session_token;
        $wsl->user_id           = $wsl_record->user_id;
        $wsl->ip                = $wsl_record->ip;
        $wsl->endpoint          = $wsl_record->endpoint;
        $wsl->method            = $wsl_record->method;
        $wsl->content_type      = $wsl_record->content_type;
        $wsl->payload           = $wsl_record->payload;
        $wsl->http_return_code  = $wsl_record->http_return_code;
        $wsl->created_timestamp = $wsl_record->created_timestamp;
        $wsl->amended_timestamp = $wsl_record->amended_timestamp;
*/

?>