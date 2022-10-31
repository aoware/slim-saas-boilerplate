<?php

namespace helpers;

class mysql {

    private $hostname;
    private $username;
    private $password;
    private $database;

    private $connection;

    public function __construct($hostname,$username,$password,$database) {

        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connection = new \mysqli($this->hostname,$this->username,$this->password,$this->database);
        if ($this->connection->connect_errno > 0) {
            die('Unable to connect to database [' . $this->connection->connect_error . ']');
        }

    }

    public function list_functions() {

        $functions = [];

        $query  = "SHOW FUNCTION STATUS WHERE Db = '" . $this->database . "'";

        $result = $this->connection->query($query);

        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $functions[] = $row['Name'];

            }

            return $functions;
        }
        else {

            return false;

        }

    }

    public function list_procedures() {

        $procedures = [];

        $query  = "SHOW PROCEDURE STATUS WHERE Db = '" . $this->database . "'";

        $result = $this->connection->query($query);

        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $procedures[] = $row['Name'];

            }

            return $procedures;
        }
        else {

            return false;

        }

    }

    public function list_tables() {

        $tables = [];

        $query  = "SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = '" . $this->database . "' and TABLE_TYPE LIKE 'BASE TABLE'";

        $result = $this->connection->query($query);

        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $tables[] = $row['TABLE_NAME'];

            }

            return $tables;
        }
        else {

            return false;

        }

    }

    public function list_views() {

        $views = [];

        $query  = "SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = '" . $this->database . "' and TABLE_TYPE LIKE 'VIEW'";

        $result = $this->connection->query($query);

        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $views[] = $row['TABLE_NAME'];

            }

            return $views;
        }
        else {

            return false;

        }

    }

    public function generate_function_code($function) {

        $query  = "SHOW CREATE FUNCTION `$function`";

        $result = $this->connection->query($query);
        $function_body = $result->fetch_assoc();

        $query  = "
            SELECT *
            FROM INFORMATION_SCHEMA.ROUTINES
            WHERE ROUTINE_SCHEMA = '" . $this->database . "'
            AND   ROUTINE_TYPE   = 'FUNCTION'
            AND   ROUTINE_NAME   = '$function'";

        $result = $this->connection->query($query);
        $function_details = $result->fetch_assoc();

        $query  = "
            SELECT *
            FROM INFORMATION_SCHEMA.PARAMETERS
            WHERE SPECIFIC_SCHEMA = '" . $this->database . "'
            AND   SPECIFIC_NAME   = '$function'
            ORDER BY ordinal_position;";

        $result = $this->connection->query($query);
        $function_parameters = $result->fetch_all(MYSQLI_ASSOC);

        // Clean the Function Body

        $original_code_body = $function_body['Create Function'];
        $original_code_body = str_replace("\r\n","\n",$original_code_body);
        $original_code_body = str_replace("\n","\r\n",$original_code_body);

        $code_body = "";
        $pos = strpos($original_code_body,"BEGIN\r\n");
        if ($pos !== false) {
            $code_body = substr($original_code_body,$pos);
        }
        else {
            $pos = strpos($original_code_body,"begin\r\n");
            if ($pos !== false) {
                $code_body = substr($original_code_body,$pos);
            }
        }

        // Generate the code

        $function_returns   = array_shift($function_parameters);
        $data_type_returned = "";
        switch($function_returns['DATA_TYPE']) {
            case 'char' :
            case 'varchar' :
                $data_type_returned = strtolower($function_returns['DTD_IDENTIFIER']) . ' CHARSET ' . $function_returns['CHARACTER_SET_NAME'];
                break;
            case 'int' :
                $data_type_returned = strtolower($function_returns['DTD_IDENTIFIER']);
                $pos = strpos($data_type_returned,"(");
                if ($pos === false) {
                    $data_type_returned .= "(11)";
                }
                break;
            case 'tinyint' :
            case 'decimal' :
                $data_type_returned = strtolower($function_returns['DTD_IDENTIFIER']);
                break;
        }

        $code  = "DELIMITER \$\$\r\n";
        $code .= "\r\n";
        $code .= "CREATE FUNCTION `" . $function_details['ROUTINE_NAME'] . "`(";
        if (count($function_parameters) == 0) {
            $code .= ")\r\n";
        }
        else {
            $code .= "\r\n";
            foreach($function_parameters as $parameter) {
                $data_type = strtolower($parameter['DATA_TYPE']);
                switch($parameter['DATA_TYPE']) {
                    case 'char' :
                    case 'varchar' :
                        $data_type .= "(" . $parameter['CHARACTER_MAXIMUM_LENGTH'] . ")";
                        break;
                    case 'int' :
                        $data_type = strtolower($parameter['DTD_IDENTIFIER']);
                        $pos = strpos($data_type,"(");
                        if ($pos === false) {
                            $data_type .= "(11)";
                        }
                        break;
                    case 'tinyint' :
                        $data_type = strtolower($parameter['DTD_IDENTIFIER']);
                        break;
                    case 'decimal' :
                        $data_type .= "(" . $parameter['NUMERIC_PRECISION'] . "," . $parameter['NUMERIC_SCALE'] .")";
                        break;
                }
                $code .= "    `" . $parameter['PARAMETER_NAME'] . "` $data_type,\r\n";
            }
            $code = substr($code,0,strlen($code) - 3) . "\r\n";
            $code .= ")\r\n";
        }
        $code .= "RETURNS " . $data_type_returned . "\r\n";
        $code .= "LANGUAGE " . $function_details['PARAMETER_STYLE'] . "\r\n";
        $code .= "DETERMINISTIC\r\n";
        $code .= $function_details['SQL_DATA_ACCESS'] . "\r\n";
        $code .= "SQL SECURITY " . $function_details['SECURITY_TYPE'] . "\r\n";
        $code .= "COMMENT '" . $function_details['ROUTINE_COMMENT'] . "'\r\n";
        $code .= "\r\n";
        $code .= $code_body . "\$\$\r\n";
        $code .= "\r\n";
        $code .= "DELIMITER ;";

        return $code;

    }

    public function generate_procedure_code($procedure) {

        $query  = "SHOW CREATE PROCEDURE `$procedure`";

        $result = $this->connection->query($query);
        $procedure_body = $result->fetch_assoc();

        $query  = "
            SELECT *
            FROM INFORMATION_SCHEMA.ROUTINES
            WHERE ROUTINE_SCHEMA = '" . $this->database . "'
            AND   ROUTINE_TYPE   = 'PROCEDURE'
            AND   ROUTINE_NAME   = '$procedure'";

        $result = $this->connection->query($query);
        $procedure_details = $result->fetch_assoc();

        $query  = "
            SELECT *
            FROM INFORMATION_SCHEMA.PARAMETERS
            WHERE SPECIFIC_SCHEMA = '" . $this->database . "'
            AND   SPECIFIC_NAME   = '$procedure'
            AND   PARAMETER_MODE  = 'IN'
            ORDER BY ordinal_position;";

        $result = $this->connection->query($query);
        $procedure_parameters = $result->fetch_all(MYSQLI_ASSOC);

        // Clean the Procedure Body

        $original_code_body = $procedure_body['Create Procedure'];
        $original_code_body = str_replace("\r\n","\n",$original_code_body);
        $original_code_body = str_replace("\n","\r\n",$original_code_body);

        $code_body = "";
        $pos = strpos($original_code_body,"BEGIN\r\n");
        if ($pos !== false) {
            $code_body = substr($original_code_body,$pos);
        }
        else {
            $pos = strpos($original_code_body,"begin\r\n");
            if ($pos !== false) {
                $code_body = substr($original_code_body,$pos);
            }
        }

        // Generate the code

        $code  = "DELIMITER \$\$\r\n";
        $code .= "\r\n";
        $code .= "CREATE PROCEDURE `" . $procedure_details['ROUTINE_NAME'] . "`(";
        if (count($procedure_parameters) == 0) {
            $code .= ")\r\n";
        }
        else {
            $code .= "\r\n";
            foreach($procedure_parameters as $parameter) {
                $data_type = strtolower($parameter['DATA_TYPE']);
                switch($parameter['DATA_TYPE']) {
                    case 'char' :
                    case 'varchar' :
                        $data_type .= "(" . $parameter['CHARACTER_MAXIMUM_LENGTH'] . ")";
                        break;
                    case 'int' :
                        $data_type = strtolower($parameter['DTD_IDENTIFIER']);
                        $pos = strpos($data_type,"(");
                        if ($pos === false) {
                            $data_type .= "(11)";
                        }
                        break;
                    case 'tinyint' :
                        $data_type = strtolower($parameter['DTD_IDENTIFIER']);
                        break;
                    case 'decimal' :
                        $data_type .= "(" . $parameter['NUMERIC_PRECISION'] . "," . $parameter['NUMERIC_SCALE'] .")";
                        break;
                }
                $code .= "  IN `" . $parameter['PARAMETER_NAME'] . "` $data_type,\r\n";
            }
            $code = substr($code,0,strlen($code) - 3) . "\r\n";
            $code .= ")\r\n";
        }
        $code .= "LANGUAGE " . $procedure_details['PARAMETER_STYLE'] . "\r\n";
        $code .= "NOT DETERMINISTIC\r\n";
        $code .= $procedure_details['SQL_DATA_ACCESS'] . "\r\n";
        $code .= "SQL SECURITY " . $procedure_details['SECURITY_TYPE'] . "\r\n";
        $code .= "COMMENT '" . str_replace("'","\\'",$procedure_details['ROUTINE_COMMENT']) . "'\r\n";
        $code .= "\r\n";
        $code .= $code_body . "\$\$\r\n";
        $code .= "\r\n";
        $code .= "DELIMITER ;";

        return $code;

    }

    public function generate_table_code($table) {

        $query  = "SHOW CREATE TABLE `$table`";

        $result = $this->connection->query($query);
        $table_body = $result->fetch_assoc();

        $lines_body = explode("\n",$table_body['Create Table']);

        $code_body = "";

        foreach($lines_body as $line_index => $line) {
            if ($line_index == (count($lines_body) - 1)) {
                $pos = strpos($line," AUTO_INCREMENT=");
                if ($pos !== false) {
                    $autoincrement = substr($line,$pos);
                    $pos = strpos($autoincrement," ",1);
                    if ($pos !== false) {
                        $autoincrement = substr($autoincrement,0,$pos);
                    }
                    $line = str_replace($autoincrement,"",$line);
                }
            }
            else {
                $words = explode(" ",$line);
                $key = array_search('int', $words);
                if ($key !== false) {
                    $line = str_replace(" int "," int(11) ",$line);
                }
                $key = array_search('bigint', $words);
                if ($key !== false) {
                    $line = str_replace(" bigint "," bigint(20) ",$line);
                }
                $key = array_search('smallint', $words);
                if ($key !== false) {
                    $line = str_replace(" smallint "," smallint(6) ",$line);
                }
                $key = array_search('tinyint', $words);
                if ($key !== false) {
                    $line = str_replace(" tinyint "," tinyint(4) ",$line);
                }
            }

            $code_body .= $line . "\r\n";
        }

        return trim($code_body);

    }

    public function generate_view_code($view) {

        $query  = "SHOW CREATE VIEW `$view`";

        $result = $this->connection->query($query);
        $view_body = $result->fetch_assoc();

        $code_body = "";

        $pos = strpos($view_body['Create View']," AS ");
        if ($pos !== false) {
            $code_body = "CREATE VIEW `$view` AS\r\n" . substr($view_body['Create View'],$pos + 4);
        }
        else {
            $code_body = $view_body['Create View'];
        }

        return trim($code_body);

    }
}