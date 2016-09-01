<?php
/**
 * 导入 hdp 接口到 通用接口数据库(hdp综合电影--获取接口链接和原始链接)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_sooner_db ();

global $db;

$f1 = 'http://tv4.hdpfans.com/~rss.get.category/site/hdp/channel/movie/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
$sxmly = afget ( $f1 );
$sxmly = simplexml_load_string ( $sxmly );
$sxmly = json_decode ( json_encode ( $sxmly ), true );
//$sxmly['foobar'] = array_slice($sxmly['foobar'], 1, 10);

foreach ($sxmly['foobar'] as $yu){
	$sxml = afget ( $yu['url'] );
	$sxml = simplexml_load_string ( $sxml );
	$sxml = json_decode ( json_encode ( $sxml ), true );	
	//获取要采集多少页
	$page_count = $sxml['INFO']['PAGECOUNT'] ;	
	for($p=1;$p<=$page_count;$p++){
	//for($p=1088;$p>=1;$p--){
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
			$iv['type_name'] = $yu['name'] ;
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
	$thist = $phpcmsdb->r1('v9_video',array('title'=>$title,'column_id'=>'5'),'asset_id,surl') ;
	if(!empty($thist)){//已经存在的电影则更新接口链接和原始链接	
		if(empty($thist['surl'])){
			$phpcmsdb->w('v9_video',array('surl'=>$a['url']),array('asset_id'=>$thist['asset_id'])) ;
		}
		//电影的播放链接(多链接循环)
		$num = count($sxml['url']['foobar']);
		var_dump($num);echo "**************************";
		if($num<=2){  //单个来源链接
			$yurl = $sxml['url']['foobar']['url'];
			$att = explode('|',$sxml['url']['foobar']['name']);
			vplog("urlss is {$yurl}") ;
			$s = afget($yurl) ;
			$sxxmc = simplexml_load_string($s) ;
			$sxxmc = json_decode(json_encode($sxxmc),true) ;
			//视频链接入库
			$sourceurl = $yurl;
			if(empty($sxxmc['m3u8'])){
				$url = $sxxmc['url']['foobar'];
			}else{
				$url = $sxxmc['m3u8'];
			}
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
			//查询此视频链接是否存在
			if((strpos($url,'vodipad.wasu.cn')!== false)||(strpos($url,'data1.hdpfans.com:80/xunlei.php')!== false)||(strpos($url,'http://.flv')!== false)) return ;
			if(empty($url)){
				$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$thist['asset_id'],'source_id'=>$source_id),'id') ;
			}else{
				$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$thist['asset_id'],'path'=>$url,'source_id'=>$source_id),'id') ;
			}
			if(empty($tmp)){
				echo "1\n";
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
				echo "2\n";
				if(empty($url)){
					$dc['sourceurl'] = $att['1'];
				}else{
					$dc['path'] = $url;
					$dc['sourceurl'] = $att['1'];
				}
				$phpcmsdb->w('v9_video_content',$dc,array('asset_id'=>$thist['asset_id'],'id'=>$tmp['id'])) ;
				echo "one ok!!";
			}	
		}else{ //多个来源链接
			foreach($sxml['url']['foobar'] as $ssv){
				$yurl = $ssv['url'];
				$att = array();
				$att = explode('|',$ssv['name']);
				vplog("urlds is {$yurl}") ;
				$s = afget($yurl) ;
				$sxxmc = simplexml_load_string($s) ;
				$sxxmc = json_decode(json_encode($sxxmc),true) ;
				//视频链接入库
				$sourceurl = $yurl;
				$url = '';
				if(empty($sxxmc['m3u8'])){
					$url = $sxxmc['url']['foobar'];
				}else{
					$url = $sxxmc['m3u8'];
				}
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
				//查询此视频链接是否存在
				if((strpos($url,'vodipad.wasu.cn')!== false)||(strpos($url,'data1.hdpfans.com:80/xunlei.php')!== false)||(strpos($url,'http://.flv')!== false)) return ;
				if(empty($url)){
					$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$thist['asset_id'],'source_id'=>$source_id),'id') ;
				}else{
					$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$thist['asset_id'],'path'=>$url,'source_id'=>$source_id),'id') ;
				}
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
					echo "one ok!!!";
				}else{
					if(empty($url)){
						$dc['sourceurl'] = $att['1'];
					}else{
						$dc['path'] = $url;
						$dc['sourceurl'] = $att['1'];
					}
					$phpcmsdb->w('v9_video_content',$dc,array('asset_id'=>$thist['asset_id'],'id'=>$tmp['id'])) ;
					echo "one ok!1!!!";
				}
			}
		}
	}else{
		echo "last one...";
	}
}

function link_type($url){
	if(strpos($url,'youku')!== false){
		$source_id=14;
	}elseif(strpos($url,'sohu')!== false){
		$source_id=19;
	}elseif(strpos($url,'qq.com')!== false){
		$source_id=21;
	}elseif(strpos($url,'letv.com')!== false){
		$source_id=16;
	}elseif(strpos($url,'m1905')!== false){
		$source_id=20;
	}elseif(strpos($url,'iqiyi')!== false){
		$source_id=17;
	}elseif(strpos($url,'pptv.vod')!== false){
		$source_id=18;
	}
	return $source_id;
}
