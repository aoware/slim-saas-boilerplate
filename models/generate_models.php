<?php

require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/../vendor/autoload.php');

echo "Starting @ " . date('Y-m-d H:i:s') . "\r\n";

$mysql_major_version = get_mysql_version();

$mysqli = new mysqli(CONF_mysql_host,CONF_mysql_user,CONF_mysql_password,CONF_mysql_database);

$mh = new \helpers\mysql(CONF_mysql_host,CONF_mysql_user,CONF_mysql_password,CONF_mysql_database);

// Identifying all tables
$sql = "show tables";
$stmt = $mysqli->prepare($sql)  or die ("unable to prepare sql " . $sql);
$stmt->execute() or die ("unable to execute " . $mysqli->error);
$stmt->bind_result($table) or die ("no binding here " . $mysqli->error);

while ($stmt->fetch()) {
    generateModel($table,CONF_mysql_database,$mysql_major_version);
    $table_create = $mh->generate_table_code($table);
    file_put_contents(dirname(__FILE__) . '/../db_source/tables/' . $table . ".sql",$table_create);
}

$stmt->close();

echo "Ending @ " . date('Y-m-d H:i:s') . "\r\n";

exit;

function generateModel($table,$schema,$mysql_major_version) {

    $sm = new \helpers\string_manipulation();

    $customCodeStart = "    // =-=- Custom Code Start -=-=\r\n";
    $customCodeEnd   = "    // =-=- Custom Code End -=-=\r\n";

    // Identify if the file exists already, if it exists, is there some custom codes
    $customCodeFlag = false;
    $customCode     = "";
    if (file_exists($table.".php")) {
        $currentModel = file_get_contents($table.".php");
        $customCode = $sm->returnBetweenWords($currentModel, $customCodeStart, $customCodeEnd);
        if (!is_null($customCode)) {
            $customCodeFlag = true;
        }
    }

    // Fetch table definition
    $mysqli = new mysqli(CONF_mysql_host,CONF_mysql_user,CONF_mysql_password,$schema);

    // Identifying columns for the table
    $sql = "show columns from `$table`";
    $stmt = $mysqli->prepare($sql)  or die ("unable to prepare sql " . $sql);
    $stmt->execute() or die ("unable to execute " . $mysqli->error);

    $stmt->bind_result($field,$type,$null,$key,$default,$extra) or die ("no binding here possible 1 | " . $mysqli->error);

    // table acronym
    $table_acronym = "";
    $table_words = explode("_",$table);
    foreach($table_words as $word) {
        $table_acronym .= substr($word,0,1);
    }

    $columnsType      = array();
    $enumsDefinitions = array();

    $columns          = [];

    while ($stmt->fetch()) {

        $crypted = false;
        if (substr($field,-8,8) == '_crypted') {
            $field   = substr($field,0,strlen($field) - 8);
            $crypted = true;
        }

        $columns[] = [
            'field'   => $field,
            'type'    => $type,
            'key'     => $key,
            'default' => $default,
            'crypted' => $crypted
        ];
    }

    $stmt->close();

    $columnsProperties             = "";
    $columnsMysqlList              = "";
    $columnsPropertiesList         = "";
    $columnsRecordMoveList         = "";
    $columnsRecordMoveList_crypted = "";
    $columnsPropertiesSample       = "/*\r\n";

    $columnsMysqlUpdateList        = "";
    $columnsMysqlUpdateType        = "";
    $columnsMysqlUpdateBind        = "";

    $columnsMysqlInsertList        = "";
    $columnsMysqlInsertQues        = "";

    // Identify some properties for the rest of the logic
    $column_length = 0;
    $crypted_columns = false;
    foreach($columns as $column) {
        if (strlen($column['field']) > $column_length) {
            $column_length = strlen($column['field']);
        }
        if ($column['crypted']) {
            $crypted_columns = true;
        }
    }

    // Looping through the columns and building code
    foreach($columns as $column) {

        $columnsProperties       .= "    public \$" . $column['field'] . ";\r\n";
        if ($column['crypted']) {
            $columnsMysqlList    .= "`" . $column['field'] . "_crypted`,";
        }
        else {
            $columnsMysqlList    .= "`" . $column['field'] . "`,";
        }
        $columnsPropertiesList   .= '$this->' . $column['field'] . ',';
        $columnsRecordMoveList   .= '            $record->' . str_pad($column['field'],$column_length) . ' = $this->' . $column['field'] . ";\r\n";
        if ($column['crypted']) {
            $columnsRecordMoveList_crypted .= '            $this->' . str_pad($column['field'],$column_length + 2) . ' = $ed->decrypt($this->' . $column['field'] . ");\r\n";
        }
        $columnsPropertiesSample .= "        \$$table_acronym->" . str_pad($column['field'],$column_length) . ' = $' . $table_acronym . '_record->' . $column['field'] . ";\r\n";

        $columnsType[$column['field']] = $column['type'];

        if ($column['key'] != "PRI") {
            if ($column['crypted']) {
                $columnsMysqlUpdateList .= '`' . $column['field'] . '_crypted` = ?,';
                $columnsMysqlInsertList .= '`' . $column['field'] . '_crypted`,';
            }
            else {
                $columnsMysqlUpdateList .= '`' . $column['field'] . '` = ?,';
                $columnsMysqlInsertList .= '`' . $column['field'] . '`,';
            }
            $columnsMysqlInsertQues .= '?,';
            if ((substr($column['type'],0,3) == 'int') or (substr($column['type'],0,6) == 'bigint')) {
                $columnsMysqlUpdateType .= 'i';
            }
            else {
                $columnsMysqlUpdateType .= 's';
            }
            if (substr($column['type'],0,4) == 'enum') {
                $enums = str_replace("enum(","",$column['type']);
                $enums = str_replace(")","",$enums);
                $enums = str_replace("'","",$enums);
                $enums = explode(',',$enums);
                $enumsDefinitions[$column['field']] = array('enum' => $enums,'default' => $column['default']);
            }
            $columnsMysqlUpdateBind .= '$this->' . $column['field'] . ',';
        }
        else {
            $keyName = $column['field'];
            if ((substr($column['type'],0,3) == 'int') or (substr($column['type'],0,6) == 'bigint')) {
                $keyType = 'i';
            }
            else {
                $keyType = 's';
            }
        }
    }

    $columnsPropertiesSample .= "*/\r\n";

    $columnsMysqlList       = substr($columnsMysqlList,0,strlen($columnsMysqlList)-1);
    $columnsMysqlUpdateList = substr($columnsMysqlUpdateList,0,strlen($columnsMysqlUpdateList)-1);
    $columnsMysqlUpdateBind = substr($columnsMysqlUpdateBind,0,strlen($columnsMysqlUpdateBind)-1);
    $columnsMysqlInsertList = substr($columnsMysqlInsertList,0,strlen($columnsMysqlInsertList)-1);
    $columnsMysqlInsertQues = substr($columnsMysqlInsertQues,0,strlen($columnsMysqlInsertQues)-1);
    $columnsPropertiesList  = substr($columnsPropertiesList,0,strlen($columnsPropertiesList)-1);

    // Build the model files
    $modelTemplate  = addStart($table,$columnsProperties,$enumsDefinitions);

    // Identifying indexes for the table
    $array_index = array();
    $sql = "show indexes from `$table`";
    $stmt2 = $mysqli->prepare($sql)  or die ("unable to prepare sql " . $sql);
    $stmt2->execute() or die ("unable to execute " . $mysqli->error);

    switch($mysql_major_version) {
        case 5 :
            $stmt2->bind_result($ind_table,$ind_nonUnique,$ind_keyName,$ind_seqInIndex,$ind_columnName,$ind_collation,$ind_cardinality,$ind_subPart,$ind_packed,$ind_null,$ind_indexType,$ind_comment,$ind_indexComment) or die ("no binding here possible 2 | " . $mysqli->error);
            break;
        case 8 :
            $stmt2->bind_result($ind_table,$ind_nonUnique,$ind_keyName,$ind_seqInIndex,$ind_columnName,$ind_collation,$ind_cardinality,$ind_subPart,$ind_packed,$ind_null,$ind_indexType,$ind_comment,$ind_indexComment,$ind_visible,$ind_expression) or die ("no binding here possible 2 | " . $mysqli->error);
            break;
    }

    while ($stmt2->fetch()) {
        if (isset($array_index[$ind_keyName])) {
            $array_index[$ind_keyName]['columns'] .= "," . $ind_columnName;
        }
        else {
            $array_index[$ind_keyName] = [
                'non_unique' => $ind_nonUnique,
                'columns'    => $ind_columnName
            ];
        }
    }
    echo $table . " - " . $crypted_columns . "\r\n";

    foreach($array_index as $ind_keyName => $ind_data) {

        $ind_nonUnique   = $ind_data['non_unique'];
        $ind_columnNames = $ind_data['columns'];

        $array_columnNames = explode(",",$ind_columnNames);
        if ($ind_keyName == "PRIMARY") {
            $modelTemplate .= addGet(ucfirst($array_columnNames[0]),$columnsMysqlList,$table,$array_columnNames[0],$columnsType[$array_columnNames[0]],$columnsPropertiesList,$columnsRecordMoveList,$crypted_columns,$columnsRecordMoveList_crypted,$ind_nonUnique);
        }
        else {
            switch(count($array_columnNames)) {
                case 0 :
                    echo "Error in table $table for index '$ind_keyName' and columns '$ind_columnNames'\r\n";
                    break;
                case 1 :
                    $wordCountInTable = count(explode("_",$table)) + 1;
                    $index_name_array = explode("_",$ind_keyName);
                    $ind_keyName = "";
                    for ($i = $wordCountInTable; $i < count($index_name_array);$i++) {
                        $ind_keyName .= $index_name_array[$i] . "_";
                    }
                    $ind_keyName = substr($ind_keyName,0,strlen($ind_keyName)-1);
                    $modelTemplate .= addGet(ucfirst($ind_keyName),$columnsMysqlList,$table,$array_columnNames[0],$columnsType[$array_columnNames[0]],$columnsPropertiesList,$columnsRecordMoveList,$crypted_columns,$columnsRecordMoveList_crypted,$ind_nonUnique);
                    $modelTemplate .= addDelete($table,$array_columnNames[0],$columnsType[$array_columnNames[0]],ucfirst($ind_keyName));
                    break;
                default :
                    $wordCountInTable = count(explode("_",$table)) + 1;
                    $index_name_array = explode("_",$ind_keyName);
                    $ind_keyName = "";
                    for ($i = $wordCountInTable; $i < count($index_name_array);$i++) {
                        $ind_keyName .= $index_name_array[$i] . "_";
                    }
                    $ind_keyName = substr($ind_keyName,0,strlen($ind_keyName)-1);
                    $modelTemplate .= addGetMultiple(ucfirst($ind_keyName),$columnsMysqlList,$table,$array_columnNames,$columnsType,$columnsPropertiesList,$columnsRecordMoveList,$crypted_columns,$columnsRecordMoveList_crypted,$ind_nonUnique);
                    break;
            }

        }
    }

    $modelTemplate .= addGetAllRecords($columnsMysqlList,$table,$columnsPropertiesList,$columnsRecordMoveList,$crypted_columns,$columnsRecordMoveList_crypted);
    $modelTemplate .= addCreate($table,$columnsMysqlInsertList,$columnsMysqlInsertQues,$columnsMysqlUpdateType,$columnsMysqlUpdateBind,$crypted_columns,$columns);
    $modelTemplate .= addUpdate($table,$keyName,$keyType,$columnsMysqlUpdateList,$columnsMysqlUpdateType,$columnsMysqlUpdateBind,$crypted_columns,$columns);
    $modelTemplate .= addDelete($table,$keyName,$keyType);
    $modelTemplate .= addDeleteAllRecords($table);
    $modelTemplate .= addEnd($customCodeFlag,$customCode,$customCodeStart,$customCodeEnd,$columnsPropertiesSample,$enumsDefinitions);

    // Save the model
    file_put_contents($table.".php",$modelTemplate);

    $mysqli->close();

}

function addStart($table,$columnsProperties,$enumsDefinitions) {

    $code  = '<?php' . "\r\n";
    $code .= "\r\n";
    $code .= "// This is generated code from model/generateModel.php\r\n";
    $code .= "// This is a work in progress as the CRUD classes created are not supporting all operations, but it is slowly going to improve\r\n";
    $code .= "// It is possible to add Custom code that will not be wiped during the next generation. Just place your code between the marker at the end of this file\r\n";
    $code .= "// When regenerating the code, compare with the previous version to ensure that nothing is lost (which it shouldn't)\r\n";
    $code .= "\r\n";
    $code .= 'namespace models;' . "\r\n";
    $code .= "\r\n";
    $code .= 'class ' . $table . '_record {' . "\r\n";
    $code .= "\r\n";
    $code .= $columnsProperties;
    $code .= "\r\n";
    $code .= '}' . "\r\n";
    $code .= "\r\n";
    $code .= 'class ' . $table . ' {' . "\r\n";
    $code .= "\r\n";
    $code .= $columnsProperties;
    $code .= "\r\n";
    if (count($enumsDefinitions) > 0) {
        $code .= '    public static $enums = array(' . "\r\n";
        foreach($enumsDefinitions as $enumField => $enumsDefinition) {
            $code .= '        "' . $enumField . '" => array(' . "\r\n";
            $code .= '            "enums" => array(' . "\r\n";
            foreach($enumsDefinition['enum'] as $enum) {
                $code .= '                "' . $enum . '",' . "\r\n";
            }
            $code .= '            ),' . "\r\n";
            $code .= '            "default" => "' . $enumsDefinition['default'] . '"' . "\r\n";
            $code .= '        ),' . "\r\n";
        }
        $code .= '    );' . "\r\n";
        $code .= "\r\n";
    }
    $code .= '    public $recordSet;' . "\r\n";
    $code .= "\r\n";
    $code .= '    public $inserted_id;' . "\r\n";
    $code .= "\r\n";
    $code .= '    private $mysqli;' . "\r\n";
    $code .= "\r\n";
    $code .= '    public function __construct($mysqli = null) {' . "\r\n";
    $code .= "\r\n";
    $code .= '        if ($mysqli === null) {' . "\r\n";
    $code .= '            $this->mysqli = new \mysqli(CONF_mysql_host,CONF_mysql_user,CONF_mysql_password,CONF_mysql_database);' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= '        else {' . "\r\n";
    $code .= '            $this->mysqli = $mysqli;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $this->recordSet = array();' . "\r\n";
    $code .= '        $this->inserted_id = 0;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    return $code;

}

function addGet($keyNameCamelCase,$columnsMysqlList,$table,$keyName,$keyType,$columnsPropertiesList,$columnsRecordMoveList,$crypted_columns,$columnsRecordMoveList_crypted,$non_unique) {

    $sm = new \helpers\string_manipulation();

    if (!is_null($sm->returnBeforeWord($keyType, "("))) {
        $keyType = $sm->returnBeforeWord($keyType, "(");
    }

    switch($keyType) {
        case "int" :
        case "bigint" :
            $type = "i";
            break;
        default :
            $type = "s";
            break;
    }

    $code  = '    function getRecordBy' . $keyNameCamelCase . '($key) {' . "\r\n";
    $code .= "\r\n";
    $code .= '        $sql = "SELECT ' . $columnsMysqlList . ' FROM `' . $table . '` WHERE `' . $keyName . '` = ?";' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
    $code .= '        if ($stmt === false) {' . "\r\n";
    $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '       $bind = $stmt->bind_param("' . $type . '", $key);' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $execute = $stmt->execute();' . "\r\n";
    $code .= '        if ($execute === false) {' . "\r\n";
    $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $bind = $stmt->bind_result(' . $columnsPropertiesList . ');' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";

    if ($crypted_columns) {
        $code .= '        $ed = new \helpers\encrypt_decrypt;' . "\r\n";
        $code .= "\r\n";
    }

    $code .= '        if ($stmt->fetch()) {' . "\r\n";

    if ($crypted_columns) {
        $code .= "\r\n";
        $code .= $columnsRecordMoveList_crypted;
        $code .= "\r\n";
    }

    $code .= '            $record = new ' . $table . '_record;' . "\r\n";
    $code .= $columnsRecordMoveList;
    $code .= '            array_push($this->recordSet, $record);' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt->close();' . "\r\n";
    $code .= "\r\n";
    $code .= '        return true;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    // if the key is not id, than let's assume than more than one record can be returned
    if (($keyNameCamelCase != 'Id') and ($non_unique == 1)) {
        $code .= '    function getRecordsBy' . $keyNameCamelCase . '($key,$orderBy = "") {' . "\r\n";
        $code .= "\r\n";
        $code .= '        $sql = "SELECT ' . $columnsMysqlList . ' FROM `' . $table . '` WHERE `' . $keyName . '` = ?";' . "\r\n";
        $code .= "\r\n";
        $code .= '        if ($orderBy != "") {' . "\r\n";
        $code .= '            $sql .= " order by " . $orderBy;' . "\r\n";
        $code .= '        }' . "\r\n";
        $code .= "\r\n";
        $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
        $code .= '        if ($stmt === false) {' . "\r\n";
        $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
        $code .= '        }' . "\r\n";
        $code .= "\r\n";
        $code .= '       $bind = $stmt->bind_param("' . $type . '", $key);' . "\r\n";
        $code .= '        if ($bind === false) {' . "\r\n";
        $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
        $code .= '        }' . "\r\n";
        $code .= "\r\n";
        $code .= '        $execute = $stmt->execute();' . "\r\n";
        $code .= '        if ($execute === false) {' . "\r\n";
        $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
        $code .= '        }' . "\r\n";
        $code .= "\r\n";
        $code .= '        $bind = $stmt->bind_result(' . $columnsPropertiesList . ');' . "\r\n";
        $code .= '        if ($bind === false) {' . "\r\n";
        $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
        $code .= '        }' . "\r\n";
        $code .= "\r\n";

        if ($crypted_columns) {
            $code .= '        $ed = new \helpers\encrypt_decrypt;' . "\r\n";
            $code .= "\r\n";
        }

        $code .= '        while ($stmt->fetch()) {' . "\r\n";

        if ($crypted_columns) {
            $code .= "\r\n";
            $code .= $columnsRecordMoveList_crypted;
            $code .= "\r\n";
        }

        $code .= '            $record = new ' . $table . '_record;' . "\r\n";
        $code .= $columnsRecordMoveList;
        $code .= '            array_push($this->recordSet, $record);' . "\r\n";
        $code .= '        }' . "\r\n";
        $code .= "\r\n";
        $code .= '        $stmt->close();' . "\r\n";
        $code .= "\r\n";
        $code .= '        return true;' . "\r\n";
        $code .= "\r\n";
        $code .= '    }' . "\r\n";
        $code .= "\r\n";
    }

    return $code;

}

function addGetMultiple($keyNameCamelCase,$columnsMysqlList,$table,$array_keyName,$array_keyType,$columnsPropertiesList,$columnsRecordMoveList,$crypted_columns,$columnsRecordMoveList_crypted,$non_unique) {

    $selection = '';
    $keys      = '';
    $types     = '';
    foreach($array_keyName as $columnSelection) {
        $selection .= "`" . $columnSelection . "` = ? and ";
        $keys      .= "\$" . $columnSelection . ",";

        $sm = new \helpers\string_manipulation();

        $keyType = $array_keyType[$columnSelection];
        if (!is_null($sm->returnBeforeWord($array_keyType[$columnSelection], "("))) {
            $keyType = $sm->returnBeforeWord($array_keyType[$columnSelection], "(");
        }

        switch($keyType) {
            case "int" :
            case "bigint" :
                $types .= "i";
                break;
            default :
                $types .= "s";
                break;
        }
    }
    $selection = substr($selection,0,strlen($selection) - 5);
    $keys      = substr($keys     ,0,strlen($keys)      - 1);

    $plural = '';
    if ($non_unique == 1) {
        $plural = 's';
    }

    $code  = '    function getRecord' . $plural . 'By' . $keyNameCamelCase . '(' . $keys;
    if ($non_unique == 1) {
        $code .= ',$orderBy = ""';
    }
    $code .= ') {' . "\r\n";
    $code .= "\r\n";
    $code .= '        $sql = "SELECT ' . $columnsMysqlList . ' FROM `' . $table . '` WHERE ' . $selection . '";' . "\r\n";
    $code .= "\r\n";
    if ($non_unique == 1) {
        $code .= '        if ($orderBy != "") {' . "\r\n";
        $code .= '            $sql .= " order by " . $orderBy;' . "\r\n";
        $code .= '        }' . "\r\n";
        $code .= "\r\n";
    }
    $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
    $code .= '        if ($stmt === false) {' . "\r\n";
    $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '       $bind = $stmt->bind_param("' . $types . '",' . $keys . ');' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $execute = $stmt->execute();' . "\r\n";
    $code .= '        if ($execute === false) {' . "\r\n";
    $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $bind = $stmt->bind_result(' . $columnsPropertiesList . ');' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";

    if ($crypted_columns) {
        $code .= '        $ed = new \helpers\encrypt_decrypt;' . "\r\n";
        $code .= "\r\n";
    }

    $code .= '        while ($stmt->fetch()) {' . "\r\n";

    if ($crypted_columns) {
        $code .= "\r\n";
        $code .= $columnsRecordMoveList_crypted;
        $code .= "\r\n";
    }

    $code .= '            $record = new ' . $table . '_record;' . "\r\n";
    $code .= $columnsRecordMoveList;
    $code .= '            array_push($this->recordSet, $record);' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt->close();' . "\r\n";
    $code .= "\r\n";
    $code .= '        return true;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    return $code;

}

function addGetAllRecords($columnsMysqlList,$table,$columnsPropertiesList,$columnsRecordMoveList,$crypted_columns,$columnsRecordMoveList_crypted) {

    $code  = '    function getAllRecords($orderBy = "") {' . "\r\n";
    $code .= "\r\n";
    $code .= '        $sql = "SELECT ' . $columnsMysqlList . ' FROM `' . $table . '`";' . "\r\n";
    $code .= "\r\n";
    $code .= '        if ($orderBy != "") {' . "\r\n";
    $code .= '            $sql .= " order by " . $orderBy;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
    $code .= '        if ($stmt === false) {' . "\r\n";
    $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $execute = $stmt->execute();' . "\r\n";
    $code .= '        if ($execute === false) {' . "\r\n";
    $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $bind = $stmt->bind_result(' . $columnsPropertiesList . ');' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";

    if ($crypted_columns) {
        $code .= '        $ed = new \helpers\encrypt_decrypt;' . "\r\n";
        $code .= "\r\n";
    }

    $code .= '        while ($stmt->fetch()) {' . "\r\n";

    if ($crypted_columns) {
        $code .= "\r\n";
        $code .= $columnsRecordMoveList_crypted;
        $code .= "\r\n";
    }

    $code .= '            $record = new ' . $table . '_record;' . "\r\n";
    $code .= $columnsRecordMoveList;
    $code .= '            array_push($this->recordSet, $record);' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt->close();' . "\r\n";
    $code .= "\r\n";
    $code .= '        return true;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    return $code;

}

function addUpdate($table,$keyName,$keyType,$columnsMysqlUpdateList,$columnsMysqlUpdateType,$columnsMysqlUpdateBind,$crypted_columns,$columns) {

    $code  = '    function updateRecord($key) {' . "\r\n";
    $code .= "\r\n";
    $code .= '        $sql = "UPDATE `' . $table . '` SET ' . $columnsMysqlUpdateList . ' WHERE `' . $keyName . '` = ?";' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
    $code .= '        if ($stmt === false) {' . "\r\n";
    $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";

    if ($crypted_columns) {
        $code .= '        $ed = new \helpers\encrypt_decrypt;' . "\r\n";
        foreach($columns as $column) {
            if ($column['crypted']) {
                $code .= '        $this->' . $column['field'] . ' = $ed->encrypt($this->' . $column['field'] . ');' . "\r\n";
            }
        }
        $code .= "\r\n";
    }

    $code .= '        $bind = $stmt->bind_param("' . $columnsMysqlUpdateType . $keyType . '",' . $columnsMysqlUpdateBind . ',$key);' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $execute = $stmt->execute();' . "\r\n";
    $code .= '        if ($execute === false) {' . "\r\n";
    $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt->close();' . "\r\n";
    $code .= "\r\n";
    $code .= '        return true;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    return $code;

}

function addDelete($table,$keyName,$keyType,$keyNameCamelCase = "") {

    if ($keyNameCamelCase == "") {
        $code  = '    function deleteRecord($key) {' . "\r\n";
    }
    else {
        $code  = '    function deleteRecordBy' . $keyNameCamelCase . '($key) {' . "\r\n";
        switch(substr($keyType,0,3)) {
            case "int" :
                $keyType = "i";
                break;
            default :
                $keyType = "s";
                break;
        }
    }

    $code .= "\r\n";
    $code .= '        $sql = "delete from `' . $table . '` WHERE `' . $keyName . '` = ?";' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
    $code .= '        if ($stmt === false) {' . "\r\n";
    $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $bind = $stmt->bind_param("' . $keyType . '",$key);' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $execute = $stmt->execute();' . "\r\n";
    $code .= '        if ($execute === false) {' . "\r\n";
    $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt->close();' . "\r\n";
    $code .= "\r\n";
    $code .= '        return true;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    return $code;

}

function addDeleteAllRecords($table) {

    $code  = '    function deleteAllRecords() {' . "\r\n";
    $code .= "\r\n";
    $code .= '        $sql = "truncate table `' . $table . '`";' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
    $code .= '        if ($stmt === false) {' . "\r\n";
    $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $execute = $stmt->execute();' . "\r\n";
    $code .= '        if ($execute === false) {' . "\r\n";
    $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt->close();' . "\r\n";
    $code .= "\r\n";
    $code .= '        return true;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    return $code;

}

function addCreate($table,$columnsMysqlInsertList,$columnsMysqlInsertQues,$columnsMysqlInsertType,$columnsMysqlInsertBind,$crypted_columns,$columns) {

    $code  = '    function saveRecord() {' . "\r\n";
    $code .= "\r\n";
    $code .= '        $sql = "INSERT INTO `' . $table . '` (' . $columnsMysqlInsertList . ') values (' . $columnsMysqlInsertQues . ')";' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt = $this->mysqli->prepare($sql);' . "\r\n";
    $code .= '        if ($stmt === false) {' . "\r\n";
    $code .= '            return "MYSQL PREPARE ERROR : " . $this->mysqli->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";

    if ($crypted_columns) {
        $code .= '        $ed = new \helpers\encrypt_decrypt;' . "\r\n";
        foreach($columns as $column) {
            if ($column['crypted']) {
                $code .= '        $this->' . $column['field'] . ' = $ed->encrypt($this->' . $column['field'] . ');' . "\r\n";
            }
        }
        $code .= "\r\n";
    }

    $code .= '        $bind = $stmt->bind_param("' . $columnsMysqlInsertType . '",' . $columnsMysqlInsertBind . ');' . "\r\n";
    $code .= '        if ($bind === false) {' . "\r\n";
    $code .= '            return "MYSQL BIND ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $execute = $stmt->execute();' . "\r\n";
    $code .= '        if ($execute === false) {' . "\r\n";
    $code .= '            return "MYSQL EXECUTE ERROR : " . $stmt->error;' . "\r\n";
    $code .= '        }' . "\r\n";
    $code .= "\r\n";
    $code .= '        $this->inserted_id = $this->mysqli->insert_id;' . "\r\n";
    $code .= "\r\n";
    $code .= '        $stmt->close();' . "\r\n";
    $code .= "\r\n";
    $code .= '        return true;' . "\r\n";
    $code .= "\r\n";
    $code .= '    }' . "\r\n";
    $code .= "\r\n";

    return $code;

}

function addEnd($customCodeFlag,$customCode,$customCodeStart,$customCodeEnd,$columnsPropertiesSample,$enumsDefinitions) {

    $code = "";

    if (count($enumsDefinitions) > 0) {
        $code .= "    function return_enums() {\r\n";
        $code .= "\r\n";
        $code .= "        return self::\$enums;\r\n";
        $code .= "\r\n";
        $code .= "    }\r\n";
        $code .= "\r\n";
    }

    $code .= $customCodeStart;
    if ($customCodeFlag) {
        $code .= $customCode;
    }
    $code .= $customCodeEnd;
    $code .= "\r\n";
    $code .= '}' . "\r\n";
    $code .= "\r\n";
    $code .= $columnsPropertiesSample;
    $code .= "\r\n";
    $code .= '?>';

    return $code;

}

function get_mysql_version() {

    $version = null;

    $mysqli = new mysqli(CONF_mysql_host,CONF_mysql_user,CONF_mysql_password);

    $sql = "select version()";
    $stmt2 = $mysqli->prepare($sql)  or die ("unable to prepare sql " . $sql);
    $stmt2->execute() or die ("unable to execute " . $mysqli->error);

    $stmt2->bind_result($version) or die ("no binding here possible 3 | " . $mysqli->error);

    $stmt2->fetch();

    $sm = new \helpers\string_manipulation;

    return $sm->returnBeforeWord($version,'.');

}