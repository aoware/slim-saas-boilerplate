<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config.php');

$o = new \apis\owa(CONF_tracking_api_key,CONF_tracking_url . 'api/',CONF_tracking_api_auth);

$result = $o->report_last_seven_days(CONF_tracking_code);
print_r($result);