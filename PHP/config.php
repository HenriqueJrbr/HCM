<?php
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'].'/HCM/PHP');
define('LOG_PATH', BASE_PATH.'/logs');
define("URL", 'http://'. str_replace('//','/', $_SERVER['HTTP_HOST']).'/HCM/PHP');

global $config;
global $db;

// $config['dbname'] = 'wwapps_hcm';
// $config['host'] = '162.214.92.26';
// $config['dbuser'] = 'wwapps_root';
// $config['dbpass'] = 'bitistech@2020!';

$config['dbname'] = 'camil_hm';
$config['host'] = 'localhost';
$config['dbuser'] = 'root';
$config['dbpass'] = 'q1w2e3r4';


try{
	$db = new PDO("mysql:dbname=".$config['dbname'].";charset=utf8;host=".$config['host'], $config['dbuser'], $config['dbpass']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(EXCEPTION $e){
	die($e->getMessage());
}
