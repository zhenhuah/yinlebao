<?php
/**
 * 导入 hdp 接口到 通用接口数据库(hdp最热电视剧)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_sooner_db ();

global $db;

$f1 = 'http://tv4.hdpfans.com/~rss.get.film_xml/site/hdp/channel/tv/category/%25E6%259C%2580%25E7%2583%25AD/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
$sxml = afget ( $f1 );
$sxml = simplexml_load_string ( $sxml );
$sxml = json_decode ( json_encode ( $sxml ), true );

//获取要采集多少页
$page_count = $sxml['INFO']['PAGECOUNT'] ;

for($p=1;$p<=$page_count;$p++){
	if($p != 1){
		$sxml = afget($f1 . '/page/' . $p) ;
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
	vplog("{$a['url']}\t\tdown !") ;	
	$s = afget($a['url']) ;	
	if(!$s){
		vplog("----sorry empty {$a['url']}") ;
	}
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;
	$title = $sxml['name'] ;
	var_dump($title);
	$thist = $phpcmsdb->r1('v9_video',array('title'=>$title,'column_id'=>'4'),'asset_id') ;
	if(!empty($thist)){		//已经入库的电视剧
		$d['surl'] = $a['url'];
		$phpcmsdb->w('v9_video',$d,array('asset_id'=>$thist['asset_id'])) ;
		//开始更新分集
		foreach($sxml['url']['foobar'] as $v){
			$att = array();
			$att = explode('|',$v['name']);
			$vid = 'hdp'.substr(md5($v['url']),0,15);
			$source_id = '';
			if(strpos($att['1'],'youku')!== false){
				$source_id=14;
			}elseif(strpos($att['1'],'sohu')!== false){
				$source_id=19;
			}elseif(strpos($att['1'],'qq.com')!== false){
				$source_id=21;
			}elseif(strpos($att['1'],'letv.com')!== false){
				$source_id=16;
			}elseif(strpos($att['1'],'m1905')!== false){
				$source_id=20;
			}elseif(strpos($att['1'],'iqiyi')!== false){
				$source_id=17;
			}elseif(strpos($att['1'],'pptv.vod')!== false){
				$source_id=18;
			}
			echo "source_id is ".$source_id."\n";
			$s = afget($v['url']) ;
			$sxxmfj = simplexml_load_string($s) ;
			$sxxmfj = json_decode(json_encode($sxxmfj),true) ;
			$url = $sxxmfj['m3u8'];
			if((strpos($url,'xunlei')!== false)||(strpos($url,'http://.flv')!== false)||(strpos($url,'pptv.vod')!== false)||(strpos($url,'m1905')!== false)) return ;
			//查询此分集是否已经入库
			$thvideo = $phpcmsdb->r1('v9_video',array('asset_id'=>$vid,'column_id'=>'4'),'id') ;
			if(!empty($thvideo)){
				if(empty($url)){
					$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$vid,'source_id'=>$source_id),'id') ;
				}else{
					$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$vid,'path'=>$url,'source_id'=>$source_id),'id') ;
				}
				if(empty($tmp['id'])){
					echo "1\n";
					$cd = array(
						'title'   	=> $title.$att['0'],
						'asset_id'  => $vid,
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
						$dc['updatetime'] = time() ;
						$dc['sourceurl'] = $att['1'];
					}else{
						$dc['updatetime'] = time() ;
						$dc['path'] = $sxxmfj['m3u8'];
						$dc['sourceurl'] = $att['1'];
					}
					$phpcmsdb->w('v9_video_content',$dc,array('id'=>$tmp['id'])) ;
					echo "one ok!!";
				}
			}else{
				echo "last one...";
			}
		}
	}else{
		echo "last one...";
	}
}
