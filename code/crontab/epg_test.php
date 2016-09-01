<?php

define('IN_CRONTAB',true) ; 

//是否使用在线XML
define('USE_ONLINE_API',true) ;

define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
define('APP_PATH','http://localhost/svn/go3ccms/') ;

error_reporting(~E_NOTICE) ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/crontab_task_censor.php' ;

$status = $_GET['status'];
$ikey = $_GET['ikey'];

echo $status."\n";
echo $ikey."\n";

$crontab = crontab_censor($ikey) ;
var_dump($crontab);
if(!$crontab) return ;
crontab_import_log($ikey,$status,0,0);
$i=0;
for($i=0;$i++;$i<100){
	echo $i."\n";
}
$snum =$i;
$fnum =1;
crontab_import_log($ikey,$status,0,0);

