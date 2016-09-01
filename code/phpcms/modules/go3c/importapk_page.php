<?php
/**
 * 导入 hdp 接口到 通用接口数据库(apk)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_shop_db ();

global $db;

$f1 = 'http://tv4.hdpfans.com/~rss.get.channel/site/shafa/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
$sxmly = afget ( $f1 );
$sxmly = simplexml_load_string ( $sxmly );
$sxmly = json_decode ( json_encode ( $sxmly ), true );

foreach ($sxmly['foobar'] as $yu){
	$sxml = afget ( $yu['url'] );
	$sxml = simplexml_load_string ( $sxml );
	$sxml = json_decode ( json_encode ( $sxml ), true );
	
	foreach($sxml['foobar'] as $a){
		$sxmla = afget ( $a['url'] );
		$sxmla = simplexml_load_string ( $sxmla );
		$sxmla = json_decode ( json_encode ( $sxmla ), true );
		//获取要采集多少页
		$page_count = $sxmla['INFO']['PAGECOUNT'] ;
		for($p=1;$p<=$page_count;$p++){
			if($p != 1){
				$sxmla = afget($a['url'] . '/page/' . $p) ;
				$sxmla = simplexml_load_string($sxmla) ;
				$sxmla = json_decode(json_encode($sxmla),true) ;
			}
			$alist = array() ;
			foreach($sxmla['ROWS']['foobar'] as $v){
				$iv = array() ;
				$iv['title'] = $v['name'] ;
				$iv['id'] = 'hdp'.substr(md5($v['link']),0,15);
				$iv['img'] = $v['img'] ;
				$iv['url'] = $v['link'] ;
				$iv['nametype'] = $a['name'] ;
				$alist[] = $iv ;
			}
			doalist($alist) ;
		}
	}
}
//循环采集一个专门分类url的各项资源
function doalist($alist){
	foreach($alist as $v){
		doa($v) ;
	}
}
//专门采集一个url资源
function doa($a){
	$shopdb = yzy_shop_db() ;
	sleep(1);
	$s = afget($a['url']) ;		
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;
	foreach($sxml as $k=>$v){
		if(is_array($v) && count($v) == 1 && isset($v[0])){
			$v[0] = trim($v[0]) ;
			if($v[0] == '')$sxml[$k] = '' ;
		}
	}
	
	$arrpage=explode('|',$sxml['url']['foobar']['name']);
	$paurl = substr($arrpage['5'],0,strrpos($arrpage['5'],'.apk?'));
	$parr2=explode('/',$paurl);
	$num = strrpos($parr2['4'],".");
	$vi['packagename'] = substr($parr2['4'],0,$num);
	//$vi['packagename'] =$arr[5] ;
	$vi['actors'] = str_replace('|',',',$sxml['actor']) ;
	
	if(strpos($vi['actors'],'遥控器')!== false){
		$vi['ANDROID']='1' ;
		$vi['term_id'] = $vi['term_id'].','.'1';
	}	
	if(strpos($vi['actors'],'鼠标')!== false){
		$vi['ANDROID']='1' ;
		$vi['term_id'] = $vi['term_id'].','.'2';
	}
	if(strpos($vi['actors'],'手柄')!== false){
		$vi['ANDROID']='1' ;
		$vi['term_id'] = $vi['term_id'].','.'4';
	}				
	$thisv = $shopdb->r('app',array('packagename'=>$vi['packagename']),'app_id') ;
	if(!empty($thisv['app_id'])){
		$numm = count($thisv['app_id']);
		echo $numm."....ok!\n";
		if($numm>1){
			foreach($thisv['app_id'] as $v){
				$dd = array(
					'term_id'   => $vi['term_id']
				);
				$shopdb->w('app_download_info',$dd,array('app_id'=>$v)) ;
				echo $v."....ok!\n";
			}
		}else{
			$dd = array(
				'term_id'   => $vi['term_id']
			);
			$shopdb->w('app_download_info',$dd,array('app_id'=>$thisv['app_id'])) ;
			echo $thisv['app_id']."....ok!\n";
		}
	}else{
		echo "net one ...\n";
	}
}

