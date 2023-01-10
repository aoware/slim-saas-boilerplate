<?php

// This is generated code from model/generateModel.php
// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve
// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file
// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)

namespace models;

class config_value_record {

    public $id;
    public $config_definition_id;
    public $profile;
    public $effective_start_date;
    public $effective_end_date;
    public $key;
    public $value;

}

class config_value {

    public $id;
    public $config_definition_id;
    public $profile;
    public $effective_start_date;
    public $effective_end_date;
    public $key;
    public $value;

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

        $sql = "SELECT `id`,`config_definition_id`,`profile`,`effective_start_date`,`effective_end_date`,`key`,`value` FROM `config_value` WHERE `id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->config_definition_id,$this->profile,$this->effective_start_date,$this->effective_end_date,$this->key,$this->value);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new config_value_record;
            $record->id                   = $this->id;
            $record->config_definition_id = $this->config_definition_id;
            $record->profile              = $this->profile;
            $record->effective_start_date = $this->effective_start_date;
            $record->effective_end_date   = $this->effective_end_date;
            $record->key                  = $this->key;
            $record->value                = $this->value;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByConfig_definition_id($key) {

        $sql = "SELECT `id`,`config_definition_id`,`profile`,`effective_start_date`,`effective_end_date`,`key`,`value` FROM `config_value` WHERE `config_definition_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->config_definition_id,$this->profile,$this->effective_start_date,$this->effective_end_date,$this->key,$this->value);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new config_value_record;
            $record->id                   = $this->id;
            $record->config_definition_id = $this->config_definition_id;
            $record->profile              = $this->profile;
            $record->effective_start_date = $this->effective_start_date;
            $record->effective_end_date   = $this->effective_end_date;
            $record->key                  = $this->key;
            $record->value                = $this->value;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByConfig_definition_id($key,$orderBy = "") {

        $sql = "SELECT `id`,`config_definition_id`,`profile`,`effective_start_date`,`effective_end_date`,`key`,`value` FROM `config_value` WHERE `config_definition_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->config_definition_id,$this->profile,$this->effective_start_date,$this->effective_end_date,$this->key,$this->value);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new config_value_record;
            $record->id                   = $this->id;
            $record->config_definition_id = $this->config_definition_id;
            $record->profile              = $this->profile;
            $record->effective_start_date = $this->effective_start_date;
            $record->effective_end_date   = $this->effective_end_date;
            $record->key                  = $this->key;
            $record->value                = $this->value;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByConfig_definition_id($key) {

        $sql = "delete from `config_value` WHERE `config_definition_id` = ?";

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

        $sql = "SELECT `id`,`config_definition_id`,`profile`,`effective_start_date`,`effective_end_date`,`key`,`value` FROM `config_value`";

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

        $bind = $stmt->bind_result($this->id,$this->config_definition_id,$this->profile,$this->effective_start_date,$this->effective_end_date,$this->key,$this->value);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new config_value_record;
            $record->id                   = $this->id;
            $record->config_definition_id = $this->config_definition_id;
            $record->profile              = $this->profile;
            $record->effective_start_date = $this->effective_start_date;
            $record->effective_end_date   = $this->effective_end_date;
            $record->key                  = $this->key;
            $record->value                = $this->value;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function saveRecord() {

        $sql = "INSERT INTO `config_value` (`config_definition_id`,`profile`,`effective_start_date`,`effective_end_date`,`key`,`value`) values (?,?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("isssss",$this->config_definition_id,$this->profile,$this->effective_start_date,$this->effective_end_date,$this->key,$this->value);
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

        $sql = "UPDATE `config_value` SET `config_definition_id` = ?,`profile` = ?,`effective_start_date` = ?,`effective_end_date` = ?,`key` = ?,`value` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("isssssi",$this->config_definition_id,$this->profile,$this->effective_start_date,$this->effective_end_date,$this->key,$this->value,$key);
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

        $sql = "delete from `config_value` WHERE `id` = ?";

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

        $sql = "truncate table `config_value`";

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
        $cv->id                   = $cv_record->id;
        $cv->config_definition_id = $cv_record->config_definition_id;
        $cv->profile              = $cv_record->profile;
        $cv->effective_start_date = $cv_record->effective_start_date;
        $cv->effective_end_date   = $cv_record->effective_end_date;
        $cv->key                  = $cv_record->key;
        $cv->value                = $cv_record->value;
*/

?>