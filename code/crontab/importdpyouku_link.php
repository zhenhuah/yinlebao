<?php
/**
 * 导入 hdp 接口到 通用接口数据库(优酷高清--更新接口链接和原始链接)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_sooner_db ();

global $db;

$f1 = 'http://tv4.hdpfans.com/~rss.get.category/site/youku/channel/movie/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
$sxmly = afget ( $f1 );
$sxmly = simplexml_load_string ( $sxmly );
$sxmly = json_decode ( json_encode ( $sxmly ), true );

foreach ($sxmly['foobar'] as $yu){
	$sxml = afget ( $yu['url'] );
	$sxml = simplexml_load_string ( $sxml );
	$sxml = json_decode ( json_encode ( $sxml ), true );
	
	//获取要采集多少页
	$page_count = $sxml['INFO']['PAGECOUNT'] ;
	
	for($p=1;$p<=$page_count;$p++){
	//for($p=$page_count;$p>=1;$p--){
		if($p != 1){
			$sxml = afget($yu['url'] . '/page/' . $p) ;
			$sxml = simplexml_load_string($sxml) ;
			$sxml = json_decode(json_encode($sxml),true) ;
		}
		$alist = array() ;
		foreach($sxml['ROWS']['foobar'] as $v){
			$iv = array() ;
			$iv['title'] = $v['name'] ;
			$iv['id'] = 'hdp'.substr(md5($v['link']),0,15);
			$iv['img'] = $v['img'] ;
			$iv['url'] = $v['link'] ;
				
			$alist[] = $iv ;
		}
		doalist($alist) ;
	}
}


//专门采集一个url的各项资源，包含判断是否采集
function doalist($alist){
	global $db ;
	$phpcmsdb = yzy_phpcms_db() ;
	foreach($alist as $v){
		doa($v) ;
	}
}

//专门采集一个url资源
function doa($a){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	//vplog("{$a['url']}\t\tdown !") ;	
	$s = afget($a['url']) ;	
	if(!$s){
		vplog("----sorry empty {$a['url']}") ;
	}
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;
	foreach($sxml as $k=>$v){
		if(is_array($v) && count($v) == 1 && isset($v[0])){
			$v[0] = trim($v[0]) ;
			if($v[0] == '')$sxml[$k] = '' ;
		}
	}
	$title = $sxml['name'] ;
	var_dump($title);
	$att = explode('|',$sxml['url']['foobar']['name']);
	$thist = $phpcmsdb->r1('v9_video',array('title'=>$title,'column_id'=>'5'),'asset_id') ;
	if(!empty($thist)){//已经存在的电影则更新接口链接和原始链接		
		$d['surl'] = $a['url'];
		$phpcmsdb->w('v9_video',$d,array('asset_id'=>$thist['asset_id'])) ;
		
		$yurl = $sxml['url']['foobar']['url'];
		vplog("url is {$yurl}") ;
		$s = afget($yurl) ;
		$sxxmc = simplexml_load_string($s) ;
		$sxxmc = json_decode(json_encode($sxxmc),true) ;
		$url = empty($sxxmc['m3u8'])?'':$sxxmc['m3u8'];
		if(strpos($url,'youku')!== false) $source_id=14;
		//查询此视频链接是否存在
		if((strpos($url,'flv.vodfile.m1905.com')!== false)||(strpos($url,'data1.hdpfans.com:80/xunlei.php')!== false)||(strpos($url,'http://.flv')!== false)||(strpos($url,'vodipad.wasu.cn')!== false)) continue ;
		$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$thist['asset_id'],'path'=>$url,'source_id'=>$source_id),'id') ;
		if(empty($tmp)){		
			$cd = array(
				'title'   	=> $title ,
				'asset_id'  => $thist['asset_id'],
				'path'   	=> $url ,
				'clarity'   => '3',
				'catid'   	=> '64',
				'status'    => '99',
				'sysadd'    => '1',
				'username'  => 'system' ,
				'updatetime'=> time(),
				'source_id' => $source_id ,
				'sourceurl' => $att['1']
			);
			$cd['inputtime'] = time() ;
			$video_content_id = $phpcmsdb->w('v9_video_content',$cd) ;
			$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
			echo "one ok!";
		}else{
			if(empty($url)){
				$dc['sourceurl'] = $att['1'];
			}else{
				$dc['path'] = $url;
				$dc['sourceurl'] = $att['1'];
			}
			$phpcmsdb->w('v9_video_content',$dc,array('asset_id'=>$thist['asset_id'])) ;
			echo "one ok!";
		}
	}else{
		echo "last one...";
	}

}

