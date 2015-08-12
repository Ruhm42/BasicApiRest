<?php

require_once 'Api.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
try
{
	$cur_api = new Api($_REQUEST['request']);
	echo $cur_api->processAPI() . "\n";
}
catch (Exception $e) {
	echo json_encode(Array('error' => $e->getMessage()));
}
?>