<?php
/**
 * 导入 hdp 接口到 通用接口数据库(7poapk)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_shop_db ();

global $db;

$f1 = 'http://tv4.hdpfans.com/~rss.get.channel/site/7po/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
$sxmly = afget ( $f1 );
$sxmly = simplexml_load_string ( $sxmly );
$sxmly = json_decode ( json_encode ( $sxmly ), true );

	$sxml = afget ( $sxmly['foobar']['url'] );
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
			var_dump($a);
			doalist($alist) ;
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
	var_dump($a['nametype']);
	foreach($sxml as $k=>$v){
		if(is_array($v) && count($v) == 1 && isset($v[0])){
			$v[0] = trim($v[0]) ;
			if($v[0] == '')$sxml[$k] = '' ;
		}
	}
	
	$vi['name'] = $sxml['name'] ;
	$vi['director'] =  str_replace('|',',',$sxml['director']) ;
	$vi['actors'] = $sxml['actor'] ;
	$vi['img'] = str_replace('|',',',$sxml['img']) ;
	$vi['description'] = $sxml['desc'] ;
	
	$b = explode(",",$vi['director']);

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
	$vi['file_size'] = $b['0'];
	$vi['version'] = $b['1'];
	$vi['os_ver'] = $b['2'];
	$type = 'app';
	
	if($a['nametype'] == '影视音乐'){
			$channel_id = '2';
	}elseif($a['nametype'] == '游戏娱乐'){
			$channel_id = '11';
	}elseif($a['nametype'] == '教育学习'){
			$channel_id = '3';
	}elseif($a['nametype'] == '日常生活'){
			$channel_id = '5';
	}elseif($a['nametype'] == '系统工具'){
			$channel_id = '19';
	}elseif($a['nametype'] == '网络社交'){
			$channel_id = '4';
	}else{
			$channel_id = '9';
	}
	$cha = $shopdb->r1('app_channel_category',array('cat_id'=>$channel_id),'cat_name') ;
	if(($vi['os_ver']&&strpos($vi['os_ver'],'Android')!== false)){
		$vi['os_type']='1';
	}elseif(($vi['os_ver']&&strpos($vi['os_ver'],'Ios')!== false)){
		$vi['os_type']='2';
	}
			
	//apk资源导入
	$d = array(
			'sid'   	   		=> $a['id'], 
			'app_name'   	    => $vi['name'], 
			'app_desc'      	=> $vi['description'],
			'channel_cat_id'    => $channel_id, 
			'os_ver'			=> $vi['os_ver'],
			'create_time'   	=> date('Y-m-d H:i:s',time()), 
			'update_time' 		=> date('Y-m-d H:i:s',time()), 
			'channel'      		=> $cha['cat_name'],
			'type' 				=> $channel_id==11?'game':'app',
			'version' 	 		=> $vi['version'],
			'file_size' 	 	=> $vi['file_size'],
			'source' 	   		=> 'shafa',
			'ANDROID '			=> $vi['ANDROID'],
			'surl'				=> $a['url']
		);
	$thisv = $shopdb->r1('app',array('app_name'=>$vi['name']),'app_id') ;
	$thisv_id = 0 ;
	if(isset($thisv['app_id'])){
		$thisv_id = $thisv['app_id'] ;
		$d['update_time'] = date('Y-m-d H:i:s',time());
		$shopdb->w('app',$d,array('app_id'=>$thisv_id)) ;
		return;
	}else{
		//新增吧...
		$shopdb->w('app',$d) ;
	}
	//主要apk信息入库完毕
	vplog("id {$a['id']}") ;
	//apk下载链接入库
	$yurl = $sxml['url']['foobar']['url'];
	vplog("url is {$yurl}") ;
	$s = afget($yurl) ;
	$sxxmc = simplexml_load_string($s) ;
	$sxxmc = json_decode(json_encode($sxxmc),true) ;
		
	$thisv = $shopdb->r1('app',array('app_name'=>$d['name']),'app_id') ;
	$down_url = $yurl;
	if(!empty($sxxmc['url'])){
		//$shopdb->d('app_download_info',array('app_id'=>$thisv['app_id'])) ;
		$tmp = $shopdb->r1('app_download_info',array('app_id'=>$thisv['app_id'],'install_file'=>$sxxmc['url']['foobar'])) ;				
			$cd = array(
				'app_id'  	=> $thisv['app_id'], 
				'term_id'   => $vi['term_id'] ,  
				'os_type'   => '1',
				'install_file' => $sxxmc['url']['foobar'],
				'down_url'  => $down_url
			);
			if(!empty($sxxmc['url']['foobar'])){
				if($tmp){
					$shopdb->w('app_download_info',$cd,array('app_id'=>$thisv['app_id'])) ;
				}else{
					$shopdb->w('app_download_info',$cd) ;
				}
			}
	}
	//apk图片入库
	//$shopdb->d('app_image',array('app_id'=>$thisv['app_id'])) ;
	//坏图片
	if(!strpos($sxml['img'],'.')) return ;
	$cd0 = array(
		'app_id'   	 => $thisv['app_id'],
		'image_file' => $vi['img'],
		'image_type' => '123'
	);
	$tmp0 = $shopdb->r1('app_image',array('app_id'=>$thisv['app_id'],'image_file'=>$vi['img']),'app_id') ;
	if(isset($tmp0['app_id'])){
		$shopdb->w('app_image',$cd0,array('app_id'=>$thisv['app_id'],'image_file'=>$vi['img'])) ;
	}else{
		$shopdb->w('app_image',$cd0) ;
	}
}

