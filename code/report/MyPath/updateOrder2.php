<?php
define("DATATABLES", true, true);
require_once("../config.php");

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
	
$fromPosition = is_array($_REQUEST['fromPosition']) ? $_REQUEST['fromPosition'][0] : $_REQUEST['fromPosition'];
$toPosition   = $_REQUEST['toPosition'];
$direction    = $_REQUEST['direction'];
$aPosition    = ($direction === "back") ? $toPosition+1 : $toPosition-1;
$table        = $_REQUEST['table'];

/*
$sql5="select * from $table where id=1";
echo $sql5."\n";
$res = mysql_fetch_assoc(mysql_query($sql5));
$tt = $res['name_en'];
var_dump($tt);
die();
*/
if($fromPosition==$toPosition){
	echo 'ok';die();
}

if(empty($table)||empty($fromPosition)||empty($toPosition)){
	echo 'error';die();
}
	
//update toPosition to 0
$sql1="update $table set listorder='0' where listorder='$toPosition';";
//echo $sql1."\n";
mysql_query($sql1);

//update fromPosition to toPosition
$sql2="update $table set listorder='$toPosition' where listorder='$fromPosition';";
//echo $sql2."\n";
mysql_query($sql2);

//update Move or Down
if(($direction === "back")&&($fromPosition>$toPosition)){   //Move
	$sql4="update $table set listorder=listorder+1 where (listorder>$toPosition and listorder<$fromPosition) and listorder != 0 ORDER BY listorder DESC ;";
	//echo $sql4."\n";
	mysql_query($sql4);
}

if(($direction === "forward")&&($fromPosition<$toPosition)){
	$sql4="update $table set listorder=listorder-1 where (listorder>$fromPosition and listorder<$toPosition) and listorder != 0 ORDER BY listorder ASC;";
	//echo $sql4."\n";
	mysql_query($sql4);
}

$sql3="update $table set listorder='$aPosition' where listorder='0';";
mysql_query($sql3);
	
echo 'ok';
?>	
