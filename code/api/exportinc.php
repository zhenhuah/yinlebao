<?php
	define('IN_CRONTAB',true) ;
	
	define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
	define('APP_PATH','http://localhost/svn/go3ccms/') ;
	
	
	error_reporting(~E_NOTICE) ;

	require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_inc_common.php' ;
	if(isset($_GET['spid'])){
		$spid=$_GET['spid'];
	}else{
		$spid='ddssp';
	}
	$db = yzy_sooner_db() ;
	//$phpcmsdb = yzy_phpcms_db() ; 
	//$spids = $phpcmsdb->r('v9_spid') ;
	
	$s = file_get_contents('php://input'); 
	$d1 = __bigtvm_import_doscript_imasset('1',$s) ;
	//__bigtvm_import_imasset('1',$s) ;
	
	$func = '__bigtvm_import_doiscript_imasset' ;
	$d = $func($imlog_id) ;

	print_r($d1);
