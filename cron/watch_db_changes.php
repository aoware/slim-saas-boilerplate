<?php

require_once(dirname(__FILE__) . '/_cron_framework_start.php');

// Identifying the source location (Live and test are different)

$source_location = "/GIT_DEPLOYMENT";
if (file_exists($source_location)) {
    $source_location = "/GIT_DEPLOYMENT/blocksonline_demo/";
    $telegram_report = true;
}
else {
    $source_location = dirname(__FILE__) . "/../";
    $telegram_report = false;
}

// Getting list of objects from the Disk

$functions_git  = [];
$procedures_git = [];
$tables_git     = [];
$views_git      = [];

$dir = $source_location . "db_source/functions";
$files = scandir($dir);
foreach ($files as $file) {
    $pos = strpos($file,".sql");
    if ($pos !== false) {
        $functions_git[str_replace(".sql","",$file)] = file_get_contents($dir . "/" . $file);
    }
}

$dir = $source_location . "db_source/procedures";
$files = scandir($dir);
foreach ($files as $file) {
    $pos = strpos($file,".sql");
    if ($pos !== false) {
        $procedures_git[str_replace(".sql","",$file)] = file_get_contents($dir . "/" . $file);
    }
}

$dir = $source_location . "db_source/tables";
$files = scandir($dir);
foreach ($files as $file) {
    $pos = strpos($file,".sql");
    if ($pos !== false) {
        $tables_git[str_replace(".sql","",$file)] = file_get_contents($dir . "/" . $file);
    }
}

$dir = $source_location . "db_source/views";
$files = scandir($dir);
foreach ($files as $file) {
    $pos = strpos($file,".sql");
    if ($pos !== false) {
        $views_git[str_replace(".sql","",$file)] = file_get_contents($dir . "/" . $file);
    }
}

// Getting list of objects from the DB

$mysql = new \psr4_classes\mysql($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);

$functions  = $mysql->list_functions();
$procedures = $mysql->list_procedures();
$tables     = $mysql->list_tables();
$views      = $mysql->list_views();

unset($mysql);

// Now let's start checking differences

$email_content = set_email_header();

foreach($functions as $function) {

    if (!array_key_exists($function,$functions_git)) {
        $email_content .= "<h4>Function `$function` is in DB but not under GIT</h4>\r\n";
    }
    else {

        $mysql = new \psr4_classes\mysql($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);
        $function_code = $mysql->generate_function_code($function);
        unset($mysql);

        $d =  new \psr4_classes\diff;
        $result = $d->compare($functions_git[$function],$function_code);

        if (($result['deleted'] > 0) or ($result['inserted'] > 0)) {
            $styled_diff = $d->toTable($result['diff'],'',"");
            $email_content .= "<h4>Function `$function` has difference between GIT and <a onclick=\"display_code('DB','" . present_code($function_code) . "');\">DB</a> - +" . $result['inserted'] . " line(s) -" . $result['deleted'] . " line(s)</h4>\r\n";
            $email_content .= $styled_diff;
        }

        unset($d);

    }

}

foreach($functions_git as $function => $filename) {

    if (!in_array($function,$functions)) {
        $email_content .= "<h4>Function `$function` is under GIT but not in DB</h4>\r\n";
    }

}

foreach($procedures as $procedure) {

    if (!array_key_exists($procedure,$procedures_git)) {
        $email_content .= "<h4>Procedure `$procedure` is in DB but not under GIT</h4>\r\n";
    }
    else {

        $mysql = new \psr4_classes\mysql($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);
        $procedure_code = $mysql->generate_procedure_code($procedure);
        unset($mysql);

        $d =  new \psr4_classes\diff;
        $result = $d->compare($procedures_git[$procedure],$procedure_code);

        if (($result['deleted'] > 0) or ($result['inserted'] > 0)) {
            $styled_diff = $d->toTable($result['diff'],'',"");
            $email_content .= "<h4>Procedure `$procedure` has difference between GIT and DB - +" . $result['inserted'] . " line(s) -" . $result['deleted'] . " line(s)</h4>\r\n";
            $email_content .= $styled_diff;
        }

        unset($d);

    }
}

foreach($procedures_git as $procedure => $filename) {

    if (!in_array($procedure,$procedures)) {
        $email_content .= "<h4>Procedure `$procedure` is under GIT but not in DB</h4>\r\n";
    }

}

foreach($tables as $table) {

    if (!array_key_exists($table,$tables_git)) {
        $email_content .= "<h4>Table `$table` is in DB but not under GIT</h4>\r\n";
    }
    else {

        $mysql = new \psr4_classes\mysql($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);
        $table_code = $mysql->generate_table_code($table);
        unset($mysql);

        $d =  new \psr4_classes\diff;
        $result = $d->compare($tables_git[$table],$table_code);

        if (($result['deleted'] > 0) or ($result['inserted'] > 0)) {
            $styled_diff = $d->toTable($result['diff'],'',"");
            $email_content .= "<h4>Table `$table` has difference between GIT and DB - +" . $result['inserted'] . " line(s) -" . $result['deleted'] . " line(s)</h4>\r\n";
            $email_content .= $styled_diff;
        }

        unset($d);
    }
}

foreach($tables_git as $table => $filename) {

    if (!in_array($table,$tables)) {
        $email_content .= "<h4>Table `$table` is under GIT but not in DB</h4>\r\n";
    }

}

foreach($views as $view) {

    if (!array_key_exists($view,$views_git)) {
        $email_content .= "<h4>View `$view` is in DB but not under GIT</h4>\r\n";
    }
    else {

        $mysql = new \psr4_classes\mysql($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);
        $view_code = $mysql->generate_view_code($view);
        unset($mysql);

        $d =  new \psr4_classes\diff;
        $result = $d->compare($views_git[$view],$view_code);

        if (($result['deleted'] > 0) or ($result['inserted'] > 0)) {
            $styled_diff = $d->toTable($result['diff'],'',"");
            $email_content .= "<h4>View `$view` has difference between GIT and DB - +" . $result['inserted'] . " line(s) -" . $result['deleted'] . " line(s)</h4>\r\n";
            $email_content .= $styled_diff;
        }

        unset($d);
    }
}

foreach($views_git as $view => $filename) {

    if (!in_array($view,$views)) {
        $email_content .= "<h4>View `$view` is under GIT but not in DB</h4>\r\n";
    }

}

if ($telegram_report) {
    $filename = dirname(__FILE__) . "/watch_db_changes_" . date("Y-m-d_H-i-s"). ".html";
    file_put_contents($filename,$email_content);
    $t = new \apis\telegram();
    $t->sendMessage(CONF_telegram_admin_id,"DB GIT Sync issue on " . CONF_server_name);
    $t->sendLocalDocument(CONF_telegram_admin_id,$filename);
    unlink($filename);
}
else {
    file_put_contents(dirname(__FILE__) . "/watch_db_changes.html",$email_content);
}

function set_email_header() {

    $result = '<!DOCTYPE html>
<html lang="en-gb">
  <head>
    <title>DB / GIT Sync issue(s) ' . CONF_server_name . ' generated @ ' . date("Y-m-d H:i:s") . '</title>
    <style type="text/css">

      .diff td, alert_code {
        padding:0 0.667em;
        vertical-align:top;
        white-space:pre;
        white-space:pre-wrap;
        font-family:Consolas,"Courier New",Courier,monospace;
        font-size:0.75em;
        line-height:1;
      }

      .diff span{
        display:block;
        min-height:1em;
        margin-top:-1px;
        padding:0 3px;
      }

      * html .diff span{
        height:1em;
      }

      .diff span:first-child{
        margin-top:0;
      }

      .diffDeleted span{
        border:1px solid rgb(255,192,192);
        background:rgb(255,224,224);
      }

      .diffInserted span{
        border:1px solid rgb(192,255,192);
        background:rgb(224,255,224);
      }

      #toStringOutput{
        margin:0 2em 2em;
      }

    </style>
    <link rel="shortcut icon" href="//www.myblockonline.co.uk/img/favicon_branding.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
    <script>
      function display_code(source,code) {
        var code_displayed = "<div class=\'alert_code\'>" + atob(code) + "</div>";
        alertify.alert().set({"startMaximized":true, "message":code_displayed}).show();
      }
    </script>
  </head>
  <body>
    <h2>DB / GIT Sync issue(s) on ' . CONF_server_name . ' generated @ ' . date("Y-m-d H:i:s") . '</h2>';

    return $result;

}

function set_email_footer() {

    $result = '  </body>
</html>';

    return $result;

}

function present_code($code) {

    $code = str_replace(" ","<i class='fas fa-long-arrow-alt-right'></i>",$code);
    $code = str_replace("\t","<i class='fas fa-angle-double-left'></i><i class='fas fa-minus'></i><i class='fas fa-minus'></i><i class='fas fa-angle-double-right'></i>",$code);
    $code = str_replace("\r\n","<i class='fas fa-arrow-down'></i><br />",$code);

    return base64_encode($code);

}