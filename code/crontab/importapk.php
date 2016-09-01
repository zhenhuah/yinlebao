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
			var_dump($a);
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
	var_dump($a['nametype']);
	foreach($sxml as $k=>$v){
		if(is_array($v) && count($v) == 1 && isset($v[0])){
			$v[0] = trim($v[0]) ;
			if($v[0] == '')$sxml[$k] = '' ;
		}
	}
	
	$arr=explode('|',$sxml['name']);
	$vi['name'] =$arr[0] ;
	$vi['packagename'] =$arr[5] ;
	$vi['director'] =  str_replace('|',',',$sxml['director']) ;
	$vi['actors'] = str_replace('|',',',$sxml['actor']) ;
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
	if(strpos($sxml['url']['foobar']['url'],'video')!== false){
		$vi['channel']='影视音乐' ;
		$vi['channel_cat_id']='2';
	}
	if($a['nametype']=='动作冒险'){
		$vi['channel']='动作冒险' ;
		$vi['channel_cat_id']='11';
		$type = 'game';
	}elseif($a['nametype']=='角色扮演'){
		$vi['channel']='角色扮演' ;
		$vi['channel_cat_id']='12';
		$type = 'game';
	}elseif($a['nametype']=='益智休闲'||$a['nametype']=='棋牌游戏'){
		$vi['channel']='棋牌休闲' ;
		$vi['channel_cat_id']='13';
		$type = 'game';
	}elseif($a['nametype']=='飞行射击'){
		$vi['channel']='射击益智' ;
		$vi['channel_cat_id']='16';
		$type = 'game';
	}elseif($a['nametype']=='经营策略'){
		$vi['channel']='策略经营' ;
		$vi['channel_cat_id']='14';
		$type = 'game';
	}elseif($a['nametype']=='竞速体育'){
		$vi['channel']='赛车竞速' ;
		$vi['channel_cat_id']='15';
		$type = 'game';
	}elseif($a['nametype']=='模拟器'||$a['nametype']=='飞行射击'||$a['nametype']=='网络游戏'){
		$vi['channel']='其他' ;
		$vi['channel_cat_id']='24';
		$type = 'game';
	}
	if($a['nametype']=='影音图像'){
		$vi['channel']='影视音乐' ;
		$vi['channel_cat_id']='2';
	}elseif($a['nametype']=='网络社交'){
		$vi['channel']='网络社区' ;
		$vi['channel_cat_id']='4';
	}elseif($a['nametype']=='系统工具'){
		$vi['channel']='系统工具' ;
		$vi['channel_cat_id']='19';
	}elseif($a['nametype']=='桌面主题'){
		$vi['channel']='桌面主题' ;
		$vi['channel_cat_id']='17';
	}elseif($a['nametype']=='阅读图书'){
		$vi['channel']='阅读图书' ;
		$vi['channel_cat_id']='3';
	}elseif($a['nametype']=='旅行地图'){
		$vi['channel']='财经旅游' ;
		$vi['channel_cat_id']='6';
	}elseif($a['nametype']=='新闻资讯'){
		$vi['channel']='新闻资讯' ;
		$vi['channel_cat_id']='18';
	}elseif($a['nametype']=='办公财经'){
		$vi['channel']='办公工具' ;
		$vi['channel_cat_id']='8';
	}elseif($a['nametype']=='生活购物'){
		$vi['channel']='生活应用' ;
		$vi['channel_cat_id']='23';
	}
	if(($vi['os_ver']&&strpos($vi['os_ver'],'Android')!== false)){
		$vi['os_type']='1';
	}elseif(($vi['os_ver']&&strpos($vi['os_ver'],'Ios')!== false)){
		$vi['os_type']='2';
	}
			
	//apk资源导入
	$d = array(
			'sid'   	   		=> $a['id'], 
			'app_name'   	    => $vi['name'], 
			'channel_cat_id'    => $vi['channel_cat_id'], 
			'packagename' 	    => $vi['packagename'],
			'os_ver'			=> $vi['os_ver'],
			'create_time'   	=> date('Y-m-d H:i:s',time()), 
			'update_time' 		=> date('Y-m-d H:i:s',time()), 
			'channel'      		=> $vi['channel'],
			'type' 				=> $type,
			'version' 	 		=> $vi['version'],
			'file_size' 	 	=> $vi['file_size'],
			'source' 	   		=> 'shafa',
			'ANDROID '			=> $vi['ANDROID'],
			'surl'				=> $a['url']
		);
	$thisv = $shopdb->r1('app',array('packagename'=>$vi['packagename']),'app_id') ;
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
		
	$thisv = $shopdb->r1('app',array('packagename'=>$d['packagename']),'app_id') ;
	$down_url = $yurl;
	if(!empty($sxxmc['url'])){
		$shopdb->d('app_download_info',array('app_id'=>$thisv['app_id'])) ;
		$tmp = $shopdb->r1('app_download_info',array('app_id'=>$thisv['app_id'])) ;				
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
	$shopdb->d('app_image',array('app_id'=>$thisv['app_id'])) ;
	//坏图片
	if(!strpos($sxml['img'],'.')) return ;
	$im = explode(",",$vi['img']);
	$im = array_slice($im, 0, 2);
	$cd0 = array(
		'app_id'   	 => $thisv['app_id'],
		'image_file' => $im['0'],
		'image_type' => '122'
	);
	$cd1 = array(
		'app_id'   	 => $thisv['app_id'],
		'image_file' => $im['1'],
		'image_type' => '103'
	);
	$tmp0 = $shopdb->r1('app_image',array('app_id'=>$thisv['app_id'],'image_type'=>'122'),'app_id') ;
	if(isset($tmp0['app_id'])){
		$shopdb->w('app_image',$cd0,array('app_id'=>$thisv['app_id'],'image_type'=>'122')) ;
	}else{
		$shopdb->w('app_image',$cd0) ;
	}
	$tmp1 = $shopdb->r1('app_image',array('app_id'=>$thisv['app_id'],'image_type'=>'103'),'app_id') ;
	if(isset($tmp1['app_id'])){
		$shopdb->w('app_image',$cd1,array('app_id'=>$thisv['app_id'],'image_type'=>'103')) ;
	}else{
		$shopdb->w('app_image',$cd1) ;
	}
}

