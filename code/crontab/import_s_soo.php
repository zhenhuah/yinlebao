<?php
/**
 * 导入 soo 接口到 通用接口数据库
 */
header('Content-type: text/html; charset=utf-8');

define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
define('RUN_IN_CMD',true) ;

//error_reporting(0) ;



require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_s_soo_common.php' ;

	yzy_sooner_db() ;
	
	global $db ;
	
	
	//设置每页采集数量
	$page_size = 10 ;
	
	//
	$page_count = 999 ;
	
	for($i=1;$i<=$page_count;$i++){
		$f1 = "http://service.chinasoo.com/Mobile/moviesearch/PS/{$page_size}/p/{$i}/http/1/httpcheck/m3u8" ;
		$sxml = afget($f1) ;
		$sxml = simplexml_load_string($sxml) ;
		$sxml = json_decode(json_encode($sxml),true) ;
		
		//重新设置 页码总数
		if($i == 1){
			$count = $sxml['item_title']['total'] ;
			
			$page_count = (int)($count /$page_size ) ;
			if($count % $page_size) $page_count++ ;
		}

		doalist($sxml['matches']['matches_item']) ;
	
	}

	
function doalist($list){
	foreach($list as $a){
		vplog('/----------doing ' . $a['MovieID']) ;
		do_a_soo_video($a['MovieID']) ;
		vplog('ok ' . $a['MovieID'] . '------------/') ;
	}
}

