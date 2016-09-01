<?php
/**
 * 导入 hdp 接口到 通用接口数据库(搞笑短片---乐酷)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_sooner_db ();

global $db;

$f1 = 'http://tv4.hdpfans.com/~rss.get.category/site/qiyi/channel/fun/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
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
		//先判断是否已经采集过
		$tmp = $phpcmsdb->r1('v9_video',array('asset_id'=>$v['id']),'*') ;
		$tmp2 = $phpcmsdb->r1('v9_video',array('title'=>$v['title']),'*') ;
		if(isset($tmp['id'])){
			$v['id'] = $tmp['id'] ;
		}
		//有重复的,不操作
		if(isset($tmp['id'])||isset($tmp2['id'])){
			continue ;
		}
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
		//vplog("----sorry empty {$a['url']}") ;
	}
	
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;
	foreach($sxml as $k=>$v){
		if(is_array($v) && count($v) == 1 && isset($v[0])){
			$v[0] = trim($v[0]) ;
			if($v[0] == '')$sxml[$k] = '' ;
		}
	}
	//先总集的数据
	$vi['director'] =  str_replace('|',',',$sxml['director']) ;
	$vi['actor'] = str_replace('|',',',$sxml['actor']) ;
	$vi['description'] = $sxml['desc'] ;
	
	$vi['title'] = $sxml['name'] ;
	$vi['asset_id'] = $a['id'] ;
	$count = count($sxml['url']['foobar']) ;    //获取分集的集数
	$ispackage = 0 ;
	$column_id = 6;
	$tag_arr=array('搞笑','雷人');
	$tags = array_rand($tag_arr, 1);
	//总集数据导入
	$d = array(
			'title'   	    => $vi['title'],
			'asset_id'      => $vi['asset_id'],
			'director'      => empty($vi['directors'])?'':$vi['directors'],
			'actor' 	    => empty($vi['actors'])?'':$vi['actors'],
			'short_name'	=> $vi['title'],
			'tag'   	    => $tags ,
			'year_released' => '' ,
			'run_time'      => '600',
			'column_id' 	=> $column_id,
			'active' 	 	=> '1',
			'area_id' 	 	=> '1',
			'is_free' 	    => '1',
			'total_episodes'=> '',
			'parent_id' 	=> '',
			'episode_number' => '',
			'latest_episode_num' => '' ,
			'rating'      	=> 6 ,
			'ispackage'   	=> $ispackage ,
			'catid'   	 	=> '54',
			'status'     	=> '99',
			'sysadd'     	=> '1',
			'username'   	=> 'system' ,
			'updatetime' 	=> time() ,
			'spid'			=> 'ddssp',
			'surl'			=> $a['url']
	);
	$thisv = $phpcmsdb->r1('v9_video',array('asset_id'=>$vi['asset_id']),'id,published,online_status') ;
	$thist = $phpcmsdb->r1('v9_video',array('title'=>$vi['title']),'id,published,online_status') ;
	if((isset($thisv['published']) && strcmp($thisv['published'] ,'1') == 0)||(isset($thist['published']) && strcmp($thist['published'] ,'1') == 0)){
		//已经更新过了
		return ;
	}
	
	$dd = array(
			'short_desc' => $vi['title'],
			'long_desc'  => $vi['description']
	);
	$thisv_id = 0 ;
	if(isset($thisv['id'])||isset($thist['id'])){
		if(in_array($thisv['online_status'],array('3','0','1','99'))){
			//3 编辑未通过，0或1 导入，99 错误  这三种状态的数据会在重新导入后被覆盖
			$thisv_id = $thisv['id'] ;
			$d['online_status'] = '1';
			$phpcmsdb->w('v9_video',$d,array('id'=>$thisv_id)) ;
			$phpcmsdb->w('v9_video_data',$dd,array('id'=>$thisv_id)) ;
		}
	}else{
		//新增吧...
		$d['inputtime'] = time() ;
		$thisv_id =  $phpcmsdb->w('v9_video',$d) ;
		$dd['id'] = $thisv_id ;
		$phpcmsdb->w('v9_video_data',$dd) ;
	}
	//主要视频信息入库完毕
	vplog("id {$vi['asset_id']}") ;
	//总集图片入库
	$picty1 = array('1'=>'102','2'=>'202','3'=>'302','4'=>'402') ;
	$phpcmsdb->d('v9_video_poster',array('asset_id'=>$vi['asset_id'])) ;
	foreach($picty1 as $pictypes){
		//坏图片
		if(!strpos($a['img'],'.')) continue ;
		list($width,$height,$type,$attr) = getimagesize($a['img']);
		$size = $width.'x'.$height;
		$ext = str_replace('.','',strrchr($a['img'],'.'));
		$cd = array(
				'title'   	=> $vi['title'].'-'.$pictypes ,
				'asset_id'  => $vi['asset_id'],
				'path'   	=> $a['img'] ,      //列表页图片
				'type'   	=> $pictypes ,
				'catid'   	=> '65',
				'status'    => '99',
				'sysadd'    => '1',
				'username'  => 'system' ,
				'updatetime'=> time(),
				'size'		=> $size,
				'format'	=> $ext
		);
		$tmp = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$vi['asset_id'],'type'=>$pictypes,'path'=>$a['img']),'id') ;
		if(isset($tmp['id'])){
			$phpcmsdb->w('v9_video_poster',$cd,array('id'=>$tmp['id'])) ;
		}else{
			$cd['inputtime'] = time() ;
			$tmpid = $phpcmsdb->w('v9_video_poster',$cd) ;
			$phpcmsdb->w('v9_video_poster_data',array('id'=>$tmpid)) ;
		}
	}
	//插入区域内容
	$phpcmsdb->d('v9_video_area',array('asset_id'=>$d['asset_id'])) ;
	$dare = array(
			'asset_id' => $vi['asset_id'],
			'area_id' => '0',
			'isextend' => '0'
	);
	$phpcmsdb->w('v9_video_area',$dare) ;
	
	sleep(1);
	//开始导入电影的播放链接
		$yurl = $sxml['url']['foobar']['url'];
		vplog("url is {$yurl}") ;
		$s = afget($yurl) ;
		$sxxmc = simplexml_load_string($s) ;
		$sxxmc = json_decode(json_encode($sxxmc),true) ;
		//视频链接入库
		$url = $sxxmc['m3u8'];
		$source_id = 17;
		$att = explode('|',$sxml['url']['foobar']['name']);
		if(empty($url)) continue;
		$cd = array(
				'title'   	=> $vi['title'] ,
				'asset_id'  => $vi['asset_id'],
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
		$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$vi['asset_id'],'path'=>$url,'source_id'=>$source_id),'id') ;
		if(!empty($tmp['id'])) {
			$video_content_id = $phpcmsdb->w('v9_video_content',$cd,array('id'=>$tmp['id'])) ;
		}else{
			$cd['inputtime'] = time() ;
			$video_content_id = $phpcmsdb->w('v9_video_content',$cd) ;
			$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
		}
	//主视频的数据已经导入完毕
	//exit;
	
	//把视频的数据自动发布上线
	//查询此视频是否已经在线
	$gtmp = $go3cdb->r1('video',array('vid'=>$vi['asset_id']));
	if(empty($gtmp)){	//视频没有上线
		//随机算出一个播放数
		$play_count = rand(50, 200);
		$dg = array(
			'vid' =>   $vi['asset_id'],
			'name' =>  $vi['title'],
			'column_id' => $column_id,
			'short_desc' => $vi['title'],
			'long_desc' => $vi['description'],
			'active' => '1',
			'time_created'=> date('Y-m-d H:i:s',time()),
			'time_updated'=> date('Y-m-d H:i:s',time()),
			'created_by' => 'root',
			'play_count' => $play_count,
			'area_id' => '1',
			'director' =>  empty($vi['directors'])?'':$vi['directors'],
			'is_free'  =>  '1',
			'run_time' =>  '7200',
			'year_released' =>  '2014',
			'spid'	=> 'ddssp',
			'channel_id' =>'NULL',
			'shared'	=> '0'
		);
		$go3cdb->w('video',$dg) ;
		//插入评分
		$dd_rating = array(
		'user_id' 	  => 'system',
		'vid' 		  => $vi['asset_id'],
		'rating'	  => '6',
		'rating_time' => date('Y-m-d H:i:s', time()),
		);
		$go3cdb->w('rating',$dd_rating) ;
		//插入视频支持的区域
		$go3cdb->d('video_area_mapping',array('vid'=>$vi['asset_id'])) ;
		$sar = array(
			'vid' => $vi['asset_id'],
			'area_id' => '0',
			'isextend' => '0'
		);
		$go3cdb->w('video_area_mapping',$sar);
		//视频支持终端控制先清理，再添加
		$go3cdb->d('video_play_control',array('vid'=>$vi['asset_id'])) ;
		for($i=1;$i<5;$i++){
			$STB = array(
				'vid' => $vi['asset_id'],
				'term_id' => $i,
				'allow_play' => '1',
				'parent_id' => 'NULL'
			);
			$go3cdb->w('video_play_control',$STB) ;
		}
		//增加视频的tag
		$tdd = array(
				'vid'=>$vi['asset_id'],
				'tag_id'=>'449',
				'seq_number'=>'1'
			) ;
			$go3cdb->w('video_tags',$tdd) ;
		//插入图片
		$rs = $phpcmsdb->r('v9_video_poster',array('asset_id'=>$vi['asset_id'])) ;
		foreach($rs as $iv){
			$dd = array(
				'vid' => $iv['asset_id'],
				'img_id' => intval($iv['type']),
				'img_url' => $iv['path'],
				'img_type' => intval($iv['type'])
			);
			$go3cdb->w('video_image',$dd) ;
		}
		//插入播放链接
		$rc = $phpcmsdb->r('v9_video_content',array('asset_id'=>$vi['asset_id'])) ;
		foreach($rc as $iv){
			$ddp = array(
				'vid' => $iv['asset_id'],
				'quality' => $iv['clarity'],
				'source' => empty($iv['source_id'])?'1':$iv['source_id'],
				'play_url' => $iv['path'],
				'aspect' => $iv['aspect'],
				'ratio' => ($iv['ratio']=='16:9')?'16:9':'4:3',
				'format' => $iv['videoformat'],
				'protocol' => $iv['videoprotocol']
			);
			$go3cdb->w('video_play_info',$ddp) ;
		}
		$phpcmsdb->w('v9_video',array('published'=>1), array('asset_id'=>$vi['asset_id'])) ;
	}
	
}

