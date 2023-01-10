<?php

// This is generated code from model/generateModel.php
// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve
// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file
// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)

namespace models;

class api_access_tokens_record {

    public $id;
    public $token;
    public $api_access_key_id;
    public $expires_at;
    public $created_timestamp;
    public $amended_timestamp;

}

class api_access_tokens {

    public $id;
    public $token;
    public $api_access_key_id;
    public $expires_at;
    public $created_timestamp;
    public $amended_timestamp;

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

        $sql = "SELECT `id`,`token`,`api_access_key_id`,`expires_at`,`created_timestamp`,`amended_timestamp` FROM `api_access_tokens` WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("s", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new api_access_tokens_record;
            $record->id                = $this->id;
            $record->token             = $this->token;
            $record->api_access_key_id = $this->api_access_key_id;
            $record->expires_at        = $this->expires_at;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByToken($key) {

        $sql = "SELECT `id`,`token`,`api_access_key_id`,`expires_at`,`created_timestamp`,`amended_timestamp` FROM `api_access_tokens` WHERE `token` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("s", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new api_access_tokens_record;
            $record->id                = $this->id;
            $record->token             = $this->token;
            $record->api_access_key_id = $this->api_access_key_id;
            $record->expires_at        = $this->expires_at;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByToken($key,$orderBy = "") {

        $sql = "SELECT `id`,`token`,`api_access_key_id`,`expires_at`,`created_timestamp`,`amended_timestamp` FROM `api_access_tokens` WHERE `token` = ?";

        if ($orderBy != "") {
            $sql .= " order by " . $orderBy;
        }

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("s", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new api_access_tokens_record;
            $record->id                = $this->id;
            $record->token             = $this->token;
            $record->api_access_key_id = $this->api_access_key_id;
            $record->expires_at        = $this->expires_at;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByToken($key) {

        $sql = "delete from `api_access_tokens` WHERE `token` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("s",$key);
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

    function getRecordByApi_access_key_id($key) {

        $sql = "SELECT `id`,`token`,`api_access_key_id`,`expires_at`,`created_timestamp`,`amended_timestamp` FROM `api_access_tokens` WHERE `api_access_key_id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("s", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new api_access_tokens_record;
            $record->id                = $this->id;
            $record->token             = $this->token;
            $record->api_access_key_id = $this->api_access_key_id;
            $record->expires_at        = $this->expires_at;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByApi_access_key_id($key,$orderBy = "") {

        $sql = "SELECT `id`,`token`,`api_access_key_id`,`expires_at`,`created_timestamp`,`amended_timestamp` FROM `api_access_tokens` WHERE `api_access_key_id` = ?";

        if ($orderBy != "") {
            $sql .= " order by " . $orderBy;
        }

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("s", $key);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new api_access_tokens_record;
            $record->id                = $this->id;
            $record->token             = $this->token;
            $record->api_access_key_id = $this->api_access_key_id;
            $record->expires_at        = $this->expires_at;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByApi_access_key_id($key) {

        $sql = "delete from `api_access_tokens` WHERE `api_access_key_id` = ?";

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

        $sql = "SELECT `id`,`token`,`api_access_key_id`,`expires_at`,`created_timestamp`,`amended_timestamp` FROM `api_access_tokens`";

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

        $bind = $stmt->bind_result($this->id,$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new api_access_tokens_record;
            $record->id                = $this->id;
            $record->token             = $this->token;
            $record->api_access_key_id = $this->api_access_key_id;
            $record->expires_at        = $this->expires_at;
            $record->created_timestamp = $this->created_timestamp;
            $record->amended_timestamp = $this->amended_timestamp;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function saveRecord() {

        $sql = "INSERT INTO `api_access_tokens` (`token`,`api_access_key_id`,`expires_at`,`created_timestamp`,`amended_timestamp`) values (?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("sisss",$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp);
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

        $sql = "UPDATE `api_access_tokens` SET `token` = ?,`api_access_key_id` = ?,`expires_at` = ?,`created_timestamp` = ?,`amended_timestamp` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("sisssi",$this->token,$this->api_access_key_id,$this->expires_at,$this->created_timestamp,$this->amended_timestamp,$key);
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

        $sql = "delete from `api_access_tokens` WHERE `id` = ?";

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

        $sql = "truncate table `api_access_tokens`";

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

    // =-=- Custom Code Start -=-=
    // =-=- Custom Code End -=-=

}

/*
        $aat->id                = $aat_record->id;
        $aat->token             = $aat_record->token;
        $aat->api_access_key_id = $aat_record->api_access_key_id;
        $aat->expires_at        = $aat_record->expires_at;
        $aat->created_timestamp = $aat_record->created_timestamp;
        $aat->amended_timestamp = $aat_record->amended_timestamp;
*/

?>