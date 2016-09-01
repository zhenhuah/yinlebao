<?php
define("DATATABLES", true, true);
require_once("config.php");

$content = $sql_details;
$host = $content['host'];
$db = $content['db'];
$user = $content['user'];
$pass = $content['pass'];

function connectsqlo2o($host,$db,$user,$pass) {
	$connect = mysql_connect($host, $user, $pass) or die('Mysql connect error: ' . mysql_error());
	if (!$connect)
		return 'Mysql connect error';
	else {
		mysql_select_db($db);
		mysql_query("set names 'utf8'");
		return $connect;
	}
}

$con = connectsqlo2o($host,$db,$user,$pass);

?>	
