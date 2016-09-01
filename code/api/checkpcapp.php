<?php
	define('IN_CRONTAB',true) ;
	
	define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
	define('APP_PATH','http://localhost/svn/go3ccms/') ;
	
	require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;

	$phpcmsdb = yzy_phpcms_db() ;
	$data = unserialize(base64_decode($_GET["m"]));
	$log = array();
	if(!empty($data)){
			$d = array(
				'tast_running'      => $data['0'],
				'cpu_usage' 	   => $data['1'],
				'ngnix_running' 	   => $data['2'],
				'mem_usage' => $data['3'],
				'hd_avail' 	   => $data['4'],
				'freeMemory' 	  	   => $data['5'],
				'ip' 	  	   => $data['6'],
				'go3cci' 	   => $data['7'],
				'image' 	   => $data['8'],
				'created' 	   => time(),
			);
		$phpcmsdb->w('v9_publishlogpc',$d);
		echo "222";
	}else{
		echo "error";
	}

