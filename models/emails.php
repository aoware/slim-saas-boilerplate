<?php

// This is generated code from model/generateModel.php
// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve
// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file
// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)

namespace models;

class emails_record {

    public $id;
    public $view_online_id;
    public $recipient_email;
    public $recipient_name;
    public $creation_date;
    public $trigger_date;
    public $sent_date;
    public $status;
    public $status_description;
    public $subject;
    public $content;

}

class emails {

    public $id;
    public $view_online_id;
    public $recipient_email;
    public $recipient_name;
    public $creation_date;
    public $trigger_date;
    public $sent_date;
    public $status;
    public $status_description;
    public $subject;
    public $content;

    public static $enums = array(
        "status" => array(
            "enums" => array(
                "awaiting",
                "processing",
                "sent",
                "error",
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

        $sql = "SELECT `id`,`view_online_id`,`recipient_email`,`recipient_name`,`creation_date`,`trigger_date`,`sent_date`,`status`,`status_description`,`subject`,`content` FROM `emails` WHERE `id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->view_online_id,$this->recipient_email,$this->recipient_name,$this->creation_date,$this->trigger_date,$this->sent_date,$this->status,$this->status_description,$this->subject,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new emails_record;
            $record->id                 = $this->id;
            $record->view_online_id     = $this->view_online_id;
            $record->recipient_email    = $this->recipient_email;
            $record->recipient_name     = $this->recipient_name;
            $record->creation_date      = $this->creation_date;
            $record->trigger_date       = $this->trigger_date;
            $record->sent_date          = $this->sent_date;
            $record->status             = $this->status;
            $record->status_description = $this->status_description;
            $record->subject            = $this->subject;
            $record->content            = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByView_online_id($key) {

        $sql = "SELECT `id`,`view_online_id`,`recipient_email`,`recipient_name`,`creation_date`,`trigger_date`,`sent_date`,`status`,`status_description`,`subject`,`content` FROM `emails` WHERE `view_online_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->view_online_id,$this->recipient_email,$this->recipient_name,$this->creation_date,$this->trigger_date,$this->sent_date,$this->status,$this->status_description,$this->subject,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new emails_record;
            $record->id                 = $this->id;
            $record->view_online_id     = $this->view_online_id;
            $record->recipient_email    = $this->recipient_email;
            $record->recipient_name     = $this->recipient_name;
            $record->creation_date      = $this->creation_date;
            $record->trigger_date       = $this->trigger_date;
            $record->sent_date          = $this->sent_date;
            $record->status             = $this->status;
            $record->status_description = $this->status_description;
            $record->subject            = $this->subject;
            $record->content            = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByView_online_id($key) {

        $sql = "delete from `emails` WHERE `view_online_id` = ?";

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

    function getAllRecords($orderBy = "") {

        $sql = "SELECT `id`,`view_online_id`,`recipient_email`,`recipient_name`,`creation_date`,`trigger_date`,`sent_date`,`status`,`status_description`,`subject`,`content` FROM `emails`";

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

        $bind = $stmt->bind_result($this->id,$this->view_online_id,$this->recipient_email,$this->recipient_name,$this->creation_date,$this->trigger_date,$this->sent_date,$this->status,$this->status_description,$this->subject,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new emails_record;
            $record->id                 = $this->id;
            $record->view_online_id     = $this->view_online_id;
            $record->recipient_email    = $this->recipient_email;
            $record->recipient_name     = $this->recipient_name;
            $record->creation_date      = $this->creation_date;
            $record->trigger_date       = $this->trigger_date;
            $record->sent_date          = $this->sent_date;
            $record->status             = $this->status;
            $record->status_description = $this->status_description;
            $record->subject            = $this->subject;
            $record->content            = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function saveRecord() {

        $sql = "INSERT INTO `emails` (`view_online_id`,`recipient_email`,`recipient_name`,`creation_date`,`trigger_date`,`sent_date`,`status`,`status_description`,`subject`,`content`) values (?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("ssssssssss",$this->view_online_id,$this->recipient_email,$this->recipient_name,$this->creation_date,$this->trigger_date,$this->sent_date,$this->status,$this->status_description,$this->subject,$this->content);
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

        $sql = "UPDATE `emails` SET `view_online_id` = ?,`recipient_email` = ?,`recipient_name` = ?,`creation_date` = ?,`trigger_date` = ?,`sent_date` = ?,`status` = ?,`status_description` = ?,`subject` = ?,`content` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("ssssssssssi",$this->view_online_id,$this->recipient_email,$this->recipient_name,$this->creation_date,$this->trigger_date,$this->sent_date,$this->status,$this->status_description,$this->subject,$this->content,$key);
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

        $sql = "delete from `emails` WHERE `id` = ?";

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

        $sql = "truncate table `emails`";

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
        $e->id                 = $e_record->id;
        $e->view_online_id     = $e_record->view_online_id;
        $e->recipient_email    = $e_record->recipient_email;
        $e->recipient_name     = $e_record->recipient_name;
        $e->creation_date      = $e_record->creation_date;
        $e->trigger_date       = $e_record->trigger_date;
        $e->sent_date          = $e_record->sent_date;
        $e->status             = $e_record->status;
        $e->status_description = $e_record->status_description;
        $e->subject            = $e_record->subject;
        $e->content            = $e_record->content;
*/

?>