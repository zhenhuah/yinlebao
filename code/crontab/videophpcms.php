<?php
define('HOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '86985773');
define('DATABASE_phpcms', 'phpcms');
define('DATABASE_go3c', 'go3c');

function connectsqlphpcms() {
	$connect = mysql_connect(HOST, USERNAME, PASSWORD) or die('Mysql connect error: ' . mysql_error());
	if (!$connect)
		return 'Mysql connect error';
	else {
		mysql_select_db(DATABASE_phpcms);
		mysql_query("set names 'utf8'");
		return $connect;
	}
}

function connectsqlgo3c() {
	$connect = mysql_connect(HOST, USERNAME, PASSWORD) or die('Mysql connect error: ' . mysql_error());
	if (!$connect)
		return 'Mysql connect error';
	else {
		mysql_select_db(DATABASE_go3c);
		mysql_query("set names 'utf8'");
		return $connect;
	}
}
