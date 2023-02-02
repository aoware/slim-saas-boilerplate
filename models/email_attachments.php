<?php

// This is generated code from model/generateModel.php
// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve
// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file
// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)

namespace models;

class email_attachments_record {

    public $id;
    public $email_id;
    public $sequence;
    public $mime_type;
    public $filename;
    public $encoding;
    public $content;

}

class email_attachments {

    public $id;
    public $email_id;
    public $sequence;
    public $mime_type;
    public $filename;
    public $encoding;
    public $content;

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

        $sql = "SELECT `id`,`email_id`,`sequence`,`mime_type`,`filename`,`encoding`,`content` FROM `email_attachments` WHERE `id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->email_id,$this->sequence,$this->mime_type,$this->filename,$this->encoding,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new email_attachments_record;
            $record->id        = $this->id;
            $record->email_id  = $this->email_id;
            $record->sequence  = $this->sequence;
            $record->mime_type = $this->mime_type;
            $record->filename  = $this->filename;
            $record->encoding  = $this->encoding;
            $record->content   = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByEmail_id_sequence($email_id,$sequence) {

        $sql = "SELECT `id`,`email_id`,`sequence`,`mime_type`,`filename`,`encoding`,`content` FROM `email_attachments` WHERE `email_id` = ? and `sequence` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("ii",$email_id,$sequence);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->email_id,$this->sequence,$this->mime_type,$this->filename,$this->encoding,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new email_attachments_record;
            $record->id        = $this->id;
            $record->email_id  = $this->email_id;
            $record->sequence  = $this->sequence;
            $record->mime_type = $this->mime_type;
            $record->filename  = $this->filename;
            $record->encoding  = $this->encoding;
            $record->content   = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByEmail_id($key) {

        $sql = "SELECT `id`,`email_id`,`sequence`,`mime_type`,`filename`,`encoding`,`content` FROM `email_attachments` WHERE `email_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->email_id,$this->sequence,$this->mime_type,$this->filename,$this->encoding,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new email_attachments_record;
            $record->id        = $this->id;
            $record->email_id  = $this->email_id;
            $record->sequence  = $this->sequence;
            $record->mime_type = $this->mime_type;
            $record->filename  = $this->filename;
            $record->encoding  = $this->encoding;
            $record->content   = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByEmail_id($key,$orderBy = "") {

        $sql = "SELECT `id`,`email_id`,`sequence`,`mime_type`,`filename`,`encoding`,`content` FROM `email_attachments` WHERE `email_id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->email_id,$this->sequence,$this->mime_type,$this->filename,$this->encoding,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new email_attachments_record;
            $record->id        = $this->id;
            $record->email_id  = $this->email_id;
            $record->sequence  = $this->sequence;
            $record->mime_type = $this->mime_type;
            $record->filename  = $this->filename;
            $record->encoding  = $this->encoding;
            $record->content   = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByEmail_id($key) {

        $sql = "delete from `email_attachments` WHERE `email_id` = ?";

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

        $sql = "SELECT `id`,`email_id`,`sequence`,`mime_type`,`filename`,`encoding`,`content` FROM `email_attachments`";

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

        $bind = $stmt->bind_result($this->id,$this->email_id,$this->sequence,$this->mime_type,$this->filename,$this->encoding,$this->content);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new email_attachments_record;
            $record->id        = $this->id;
            $record->email_id  = $this->email_id;
            $record->sequence  = $this->sequence;
            $record->mime_type = $this->mime_type;
            $record->filename  = $this->filename;
            $record->encoding  = $this->encoding;
            $record->content   = $this->content;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function saveRecord() {

        $sql = "INSERT INTO `email_attachments` (`email_id`,`sequence`,`mime_type`,`filename`,`encoding`,`content`) values (?,?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("iissss",$this->email_id,$this->sequence,$this->mime_type,$this->filename,$this->encoding,$this->content);
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

        $sql = "UPDATE `email_attachments` SET `email_id` = ?,`sequence` = ?,`mime_type` = ?,`filename` = ?,`encoding` = ?,`content` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("iissssi",$this->email_id,$this->sequence,$this->mime_type,$this->filename,$this->encoding,$this->content,$key);
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

        $sql = "delete from `email_attachments` WHERE `id` = ?";

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

        $sql = "truncate table `email_attachments`";

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
        $ea->id        = $ea_record->id;
        $ea->email_id  = $ea_record->email_id;
        $ea->sequence  = $ea_record->sequence;
        $ea->mime_type = $ea_record->mime_type;
        $ea->filename  = $ea_record->filename;
        $ea->encoding  = $ea_record->encoding;
        $ea->content   = $ea_record->content;
*/

?>