<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config.php');

$file_path = dirname(__FILE__) . "/process_tape.php";
$file_name = "process_tape.php";

$aws = new \apis\aws_s3(CONF_aws_region,CONF_aws_key,CONF_aws_secret,"aoware.avt.media");
$aws->add_file_to_bucket('TEST', 'FOLDER', $file_name, $file_path, 'private');

