<?php

// This is generated code from model/generateModel.php
// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve
// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file
// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)

namespace models;

class users_record {

    public $id;
    public $oauth_provider;
    public $oauth_uid;
    public $password;
    public $name;
    public $username;
    public $email;
    public $location;
    public $picture;
    public $link;
    public $type;
    public $created;
    public $modified;
    public $last_login;
    public $registration_ip;
    public $verification_token;
    public $verification_date;
    public $verification_ip;
    public $login_token;

}

class users {

    public $id;
    public $oauth_provider;
    public $oauth_uid;
    public $password;
    public $name;
    public $username;
    public $email;
    public $location;
    public $picture;
    public $link;
    public $type;
    public $created;
    public $modified;
    public $last_login;
    public $registration_ip;
    public $verification_token;
    public $verification_date;
    public $verification_ip;
    public $login_token;

    public static $enums = array(
        "oauth_provider" => array(
            "enums" => array(
                "",
                "github",
                "facebook",
                "google",
                "linkedin",
                "email",
            ),
            "default" => ""
        ),
        "type" => array(
            "enums" => array(
                "agent",
                "client",
            ),
            "default" => ""
        ),
    );

    public $recordSet;

    public $inserted_id;

    private $mysqli;

    public function __construct($mysqli = null) {

        if ($mysqli === null) {
            global $db;
            $this->mysqli = $db;
        }
        else {
            $this->mysqli = $mysqli;
        }

        $this->recordSet = array();
        $this->inserted_id = 0;

    }

    function getRecordById($key) {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `id` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByOauth_provider_oauth_uid($oauth_provider,$oauth_uid,$orderBy = "") {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `oauth_provider` = ? and `oauth_uid` = ?";

        if ($orderBy != "") {
            $sql .= " order by " . $orderBy;
        }

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

       $bind = $stmt->bind_param("ss",$oauth_provider,$oauth_uid);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            return "MYSQL EXECUTE ERROR : " . $stmt->error;
        }

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordByEmail($key) {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `email` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByEmail($key,$orderBy = "") {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `email` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByEmail($key) {

        $sql = "delete from `users` WHERE `email` = ?";

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

    function getRecordByLogin_token($key) {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `login_token` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByLogin_token($key,$orderBy = "") {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `login_token` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByLogin_token($key) {

        $sql = "delete from `users` WHERE `login_token` = ?";

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

    function getRecordByVerification_token($key) {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `verification_token` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        if ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function getRecordsByVerification_token($key,$orderBy = "") {

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users` WHERE `verification_token` = ?";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function deleteRecordByVerification_token($key) {

        $sql = "delete from `users` WHERE `verification_token` = ?";

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

        $sql = "SELECT `id`,`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token` FROM `users`";

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

        $bind = $stmt->bind_result($this->id,$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
        if ($bind === false) {
            return "MYSQL BIND ERROR : " . $stmt->error;
        }

        while ($stmt->fetch()) {
            $record = new users_record;
            $record->id                 = $this->id;
            $record->oauth_provider     = $this->oauth_provider;
            $record->oauth_uid          = $this->oauth_uid;
            $record->password           = $this->password;
            $record->name               = $this->name;
            $record->username           = $this->username;
            $record->email              = $this->email;
            $record->location           = $this->location;
            $record->picture            = $this->picture;
            $record->link               = $this->link;
            $record->type               = $this->type;
            $record->created            = $this->created;
            $record->modified           = $this->modified;
            $record->last_login         = $this->last_login;
            $record->registration_ip    = $this->registration_ip;
            $record->verification_token = $this->verification_token;
            $record->verification_date  = $this->verification_date;
            $record->verification_ip    = $this->verification_ip;
            $record->login_token        = $this->login_token;
            array_push($this->recordSet, $record);
        }

        $stmt->close();

        return true;

    }

    function saveRecord() {

        $sql = "INSERT INTO `users` (`oauth_provider`,`oauth_uid`,`password`,`name`,`username`,`email`,`location`,`picture`,`link`,`type`,`created`,`modified`,`last_login`,`registration_ip`,`verification_token`,`verification_date`,`verification_ip`,`login_token`) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("ssssssssssssssssss",$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token);
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

        $sql = "UPDATE `users` SET `oauth_provider` = ?,`oauth_uid` = ?,`password` = ?,`name` = ?,`username` = ?,`email` = ?,`location` = ?,`picture` = ?,`link` = ?,`type` = ?,`created` = ?,`modified` = ?,`last_login` = ?,`registration_ip` = ?,`verification_token` = ?,`verification_date` = ?,`verification_ip` = ?,`login_token` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;
        }

        $bind = $stmt->bind_param("ssssssssssssssssssi",$this->oauth_provider,$this->oauth_uid,$this->password,$this->name,$this->username,$this->email,$this->location,$this->picture,$this->link,$this->type,$this->created,$this->modified,$this->last_login,$this->registration_ip,$this->verification_token,$this->verification_date,$this->verification_ip,$this->login_token,$key);
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

        $sql = "delete from `users` WHERE `id` = ?";

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

        $sql = "truncate table `users`";

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
    
    function email_register($name,$email,$password,$type,$registration_ip) {
        
        $sm = new \helpers\string_manipulation;
        $verification_token = $sm->generateRandomCode(32);
        
        $this->getRecordsByVerification_token($verification_token);
        
        while(count($this->recordSet) > 0) {
            $verification_token = $sm->generateRandomCode(32);
            $this->getRecordsByVerification_token($verification_token);
        }
        
        // Trying to retrieve picture url from gravater
        $picture = "";
        $g = new \apis\gravatar();
        $g->email = $email;
        $g->getDetails();
        if ($g->error === false) {
            $picture = $g->urlImage . "?s=250";
        }
        
        $this->oauth_provider     = 'email';
        $this->oauth_uid          = $email;
        $this->password           = md5($password);
        $this->name               = $name;
        $this->username           = "";
        $this->email              = $email;
        $this->location           = "";
        $this->picture            = $picture;
        $this->link               = "";
        $this->type               = $type;
        $this->created            = date('Y-m-d H:i:s');
        $this->modified           = null;
        $this->last_login         = null;
        $this->registration_ip    = $registration_ip;
        $this->verification_token = $verification_token;
        $this->verification_date  = null;
        $this->verification_ip    = null;
        $this->login_token        = null;
        
        $result = $this->saveRecord();
        
        if ($result !== true) {
            throw new \Exception($result);
            return false;
        }
        
        return [
            'user_id'            => $this->inserted_id,
            'verification_token' => $verification_token
        ];
        
    }
    // =-=- Custom Code End -=-=

}

/*
        $u->id                 = $u_record->id;
        $u->oauth_provider     = $u_record->oauth_provider;
        $u->oauth_uid          = $u_record->oauth_uid;
        $u->password           = $u_record->password;
        $u->name               = $u_record->name;
        $u->username           = $u_record->username;
        $u->email              = $u_record->email;
        $u->location           = $u_record->location;
        $u->picture            = $u_record->picture;
        $u->link               = $u_record->link;
        $u->type               = $u_record->type;
        $u->created            = $u_record->created;
        $u->modified           = $u_record->modified;
        $u->last_login         = $u_record->last_login;
        $u->registration_ip    = $u_record->registration_ip;
        $u->verification_token = $u_record->verification_token;
        $u->verification_date  = $u_record->verification_date;
        $u->verification_ip    = $u_record->verification_ip;
        $u->login_token        = $u_record->login_token;
*/

?>