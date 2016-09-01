<?php
/**
 * 导入 pptv 接口到 通用接口数据库
 */
header('Content-type: text/html; charset=utf-8');

define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
define('RUN_IN_CMD',true) ;

//error_reporting(0) ;



require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_s_pptv_common.php' ;

	yzy_sooner_db() ;
	
	global $db ;
	
	
	//设置要采集的类型 $ftypes = array('movie'=>'电影','tv'=>'电视剧','cartoon'=>'动漫','show'=>'综艺') ;
	$ftypes = array('movie'=>'电影','tv'=>'电视剧') ;
	
	
	$fftypes = array('movie','tv') ;
	if(isset($_SERVER['argv'][1]) && strpos($_SERVER['argv'][1],'a') === 0){
		$tmpn = substr($_SERVER['argv'][1],1) ; 
		if(isset($fftypes[$tmpn]))  $ftypes = array($fftypes[$tmpn]=>'') ;
	}
	
	$list = array() ;
	foreach($ftypes as $ftype=>$ftypename){
		$s = file_get_contents("http://spider.api.pptv.com/open/commonMobile/{$ftype}/{$ftype}.xml") ;
		$sxml = simplexml_load_string($s) ;
		foreach($sxml->video as $video){
			$v = json_decode(json_encode($video),true) ;
			$iv = array() ;
			$iv['id'] = $v['@attributes']['id'] ;
			$iv['status'] = $v['@attributes']['status'] ;
			$iv['type'] = $v['@attributes']['type'] ;
			$iv['name'] = $v['name'] ;
			
			vplog('/----------doing ' . $iv['id']) ;
			do_a_pptv_video($iv['id'],$ftype) ;
			vplog('ok ' . $iv['id'] . '------------/') ;
		}
	}
	

