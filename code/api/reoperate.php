<?php
	define('IN_CRONTAB',true) ;
	
	define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
	define('APP_PATH','http://localhost/svn/go3ccms/') ;
	
	require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_inc_common.php' ;
	require_once PHPCMS_PATH . 'phpcms/libs/yinclude/publish_common.php' ;
	//$iurl = 'http://111.208.56.60/go3ccms/xml/zsExport.xml' ;
	//$s = file_get_contents($iurl);
	$s = file_get_contents('php://input'); 
	$db = yzy_sooner_db() ;
	$phpcmsdb = yzy_phpcms_db() ;
	if(isset($_GET['spid'])){
		$spid=$_GET['spid'];
	}else{
		$spid='ddssp';
	}
	//$phpcmsdb = yzy_phpcms_db() ; 
	//$spids = $phpcmsdb->r('v9_spid') ;
	
	if(empty($s)){
		$re['code'] ='-1';
		$re['description'] = 'error' ;
		re($re) ;
	}
	 
	$xml = simplexml_load_string($s) ;
	$operate = $xml->publishinfo->operate ;
	$type = $xml->publishinfo->type ; 
	//删除
	if($operate == '3'){
		
		//删除资源/资源包
		if($type == '1' || $type == '4'){
			$asset_id = $xml->publishinfo->keyId ;
			
			$r = $phpcmsdb->r1('v9_video',array('asset_id'=>$asset_id)) ;
			//先下线
			__do_publish_to_off_video($r) ;
			
			//再删除
			__do_publish_to_del_video($r);
			$re['code'] ='0';
		    $re['description'] = 'ok' ;
			re($re) ;
		}
		//资源和资源包解绑定
		if($type=='2'){
			__bigtvm_import_doscript_imasset('1',$s) ;
			$re['code'] ='0';
			$re['description'] = 'ok' ;
			re($re) ;
		}
		
	}elseif ($operate == '2'){ //资源/资源包修改
		//资源包分类修改
		if($type=='2'||$type=='1'){
			if(!empty($xml->catalogs)){
				$r = $xml->catalogs;
				$asset_id = $xml->publishinfo->keyId ;
				$re = __do_publish_category_video($r,$asset_id) ;
				re($re) ;
			}
		}
		//先删除在添加
		if(!empty($xml->assets->assetId)){
			foreach($xml->assets->assetId as $a){
				$r = $phpcmsdb->r1('v9_video',array('asset_id'=>$a)) ;
				__do_publish_to_update_video($r) ;
				
				$d1=__bigtvm_import_doscript_imasset('1',$s) ;
				$func = '__bigtvm_import_doiscript_imasset' ;
				$d = $func($imlog_id) ;
				re($d1) ;
			}
		}
	}else{
			$d1=__bigtvm_import_doscript_imasset('1',$s) ;
			$func = '__bigtvm_import_doiscript_imasset' ;
			$d = $func($imlog_id) ;
			re($d1) ;
	}
	
	
	
function re($s){
	echo json_encode($s);
	//print_r($s);
	exit ;
}
	

