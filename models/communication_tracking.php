<?php

// This is generated code from model/generateModel.php
// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve
// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file
// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)

namespace models;

class communication_tracking_record {

    public $id;
    public $communication_id;
    public $event;
    public $email;
    public $call_datetime;
    public $event_datetime;
    public $event_id;
    public $ip_address;
    public $user_agent;
    public $payload;

}

class communication_tracking {

    public $id;
    public $communication_id;
    public $event;
    public $email;
    public $call_datetime;
    public $event_datetime;
    public $event_id;
    public $ip_address;
    public $user_agent;
    public $payload;

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

        $sql = "SELECT `id`,`communication_id`,`event`,`email`,`call_datetime`,`event_datetime`,`event_id`,`ip_address`,`user_agent`,`payload` FROM `communication_tracking` WHERE `id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new communication_tracking_record;
            $record->id               = $this->id;
            $record->communication_id = $this->communication_id;
            $record->event            = $this->event;
            $record->email            = $this->email;
            $record->call_datetime    = $this->call_datetime;
            $record->event_datetime   = $this->event_datetime;
            $record->event_id         = $this->event_id;
            $record->ip_address       = $this->ip_address;
            $record->user_agent       = $this->user_agent;
            $record->payload          = $this->payload;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByEvent_id($key) {

        $sql = "SELECT `id`,`communication_id`,`event`,`email`,`call_datetime`,`event_datetime`,`event_id`,`ip_address`,`user_agent`,`payload` FROM `communication_tracking` WHERE `event_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new communication_tracking_record;
            $record->id               = $this->id;
            $record->communication_id = $this->communication_id;
            $record->event            = $this->event;
            $record->email            = $this->email;
            $record->call_datetime    = $this->call_datetime;
            $record->event_datetime   = $this->event_datetime;
            $record->event_id         = $this->event_id;
            $record->ip_address       = $this->ip_address;
            $record->user_agent       = $this->user_agent;
            $record->payload          = $this->payload;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByEvent_id($key,$orderBy = "") {

        $sql = "SELECT `id`,`communication_id`,`event`,`email`,`call_datetime`,`event_datetime`,`event_id`,`ip_address`,`user_agent`,`payload` FROM `communication_tracking` WHERE `event_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new communication_tracking_record;
            $record->id               = $this->id;
            $record->communication_id = $this->communication_id;
            $record->event            = $this->event;
            $record->email            = $this->email;
            $record->call_datetime    = $this->call_datetime;
            $record->event_datetime   = $this->event_datetime;
            $record->event_id         = $this->event_id;
            $record->ip_address       = $this->ip_address;
            $record->user_agent       = $this->user_agent;
            $record->payload          = $this->payload;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByEvent_id($key) {

        $sql = "delete from `communication_tracking` WHERE `event_id` = ?";

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

    function getRecordByCommunication_id($key) {

        $sql = "SELECT `id`,`communication_id`,`event`,`email`,`call_datetime`,`event_datetime`,`event_id`,`ip_address`,`user_agent`,`payload` FROM `communication_tracking` WHERE `communication_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new communication_tracking_record;
            $record->id               = $this->id;
            $record->communication_id = $this->communication_id;
            $record->event            = $this->event;
            $record->email            = $this->email;
            $record->call_datetime    = $this->call_datetime;
            $record->event_datetime   = $this->event_datetime;
            $record->event_id         = $this->event_id;
            $record->ip_address       = $this->ip_address;
            $record->user_agent       = $this->user_agent;
            $record->payload          = $this->payload;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByCommunication_id($key,$orderBy = "") {

        $sql = "SELECT `id`,`communication_id`,`event`,`email`,`call_datetime`,`event_datetime`,`event_id`,`ip_address`,`user_agent`,`payload` FROM `communication_tracking` WHERE `communication_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new communication_tracking_record;
            $record->id               = $this->id;
            $record->communication_id = $this->communication_id;
            $record->event            = $this->event;
            $record->email            = $this->email;
            $record->call_datetime    = $this->call_datetime;
            $record->event_datetime   = $this->event_datetime;
            $record->event_id         = $this->event_id;
            $record->ip_address       = $this->ip_address;
            $record->user_agent       = $this->user_agent;
            $record->payload          = $this->payload;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByCommunication_id($key) {

        $sql = "delete from `communication_tracking` WHERE `communication_id` = ?";

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

        $sql = "SELECT `id`,`communication_id`,`event`,`email`,`call_datetime`,`event_datetime`,`event_id`,`ip_address`,`user_agent`,`payload` FROM `communication_tracking`";

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

        $bind = $stmt->bind_result($this->id,$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new communication_tracking_record;
            $record->id               = $this->id;
            $record->communication_id = $this->communication_id;
            $record->event            = $this->event;
            $record->email            = $this->email;
            $record->call_datetime    = $this->call_datetime;
            $record->event_datetime   = $this->event_datetime;
            $record->event_id         = $this->event_id;
            $record->ip_address       = $this->ip_address;
            $record->user_agent       = $this->user_agent;
            $record->payload          = $this->payload;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function saveRecord() {

        $sql = "INSERT INTO `communication_tracking` (`communication_id`,`event`,`email`,`call_datetime`,`event_datetime`,`event_id`,`ip_address`,`user_agent`,`payload`) values (?,?,?,?,?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("issssssss",$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload);
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

        $sql = "UPDATE `communication_tracking` SET `communication_id` = ?,`event` = ?,`email` = ?,`call_datetime` = ?,`event_datetime` = ?,`event_id` = ?,`ip_address` = ?,`user_agent` = ?,`payload` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("issssssssi",$this->communication_id,$this->event,$this->email,$this->call_datetime,$this->event_datetime,$this->event_id,$this->ip_address,$this->user_agent,$this->payload,$key);
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

        $sql = "delete from `communication_tracking` WHERE `id` = ?";

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

        $sql = "truncate table `communication_tracking`";

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
        $ct->id               = $ct_record->id;
        $ct->communication_id = $ct_record->communication_id;
        $ct->event            = $ct_record->event;
        $ct->email            = $ct_record->email;
        $ct->call_datetime    = $ct_record->call_datetime;
        $ct->event_datetime   = $ct_record->event_datetime;
        $ct->event_id         = $ct_record->event_id;
        $ct->ip_address       = $ct_record->ip_address;
        $ct->user_agent       = $ct_record->user_agent;
        $ct->payload          = $ct_record->payload;
*/

?>