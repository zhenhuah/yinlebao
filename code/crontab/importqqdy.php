<?php
/**
 * 导入 腾讯 接口到 通用接口数据库(电影)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_sooner_db ();

global $db;

//  http://tv.video.qq.com/fcgi-bin/dlib/dout_tv?auto_id=4&platform=10&version=10005

$year = array('2014','2013','2012','2011','2010','2009','2008','2007','2006','2005','2004','2003','2002');
foreach($year as $vy){
	$f1 = 'http://tv.video.qq.com/fcgi-bin/dlib/dout_tv?auto_id=14&platform=10&version=10005&itype=-1&iarea=-1&itrailer=-1&iedition=-1&sort=1&pagesize=20&iyear='.$vy.'&page=0';
	$sxmln = afget ( $f1 );
	$sxmln = simplexml_load_string ( $sxmln );
	$sxmln = json_decode ( json_encode ( $sxmln ), true );
	//获取循环的页数
	$num = $sxmln['total']/$sxmln['psize'];
	for($i=0;$i<=$num;$i++){
		$f1 = 'http://tv.video.qq.com/fcgi-bin/dlib/dout_tv?auto_id=14&platform=10&version=10005&itype=-1&iarea=-1&itrailer=-1&iedition=-1&sort=1&pagesize=20&iyear='.$vy.'&page='.$i;
		$sxmly = afget ( $f1 );
		$sxmly = simplexml_load_string ( $sxmly );
		$sxmly = json_decode ( json_encode ( $sxmly ), true );
		foreach($sxmly['cover'] as $v){
			doalist($v);
		}
	}
}
//判断需要导入的视频
function doalist($v){
	global $db ;
	$phpcmsdb = yzy_phpcms_db() ;
	//先判断是否已经采集过
	$tmp = $phpcmsdb->r1('v9_video',array('title'=>$v['c_title']),'*') ;
	//有重复的，那么增加tag
	if(isset($tmp['id'])){
		dovtag($v) ;
	}else{
		dov($v) ;
	}
}

//导入完整视频
function dov($v){	
	global $db ;
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	$c_actor = implode(",",$v['c_actor']);
	$c_tag = implode(",",$v['c_subtype']);
	$rating = $v['rating'];
	$ispackage = 0 ;
	//生成标题首字母
	$zh = preg_replace('((?=[\x21-\x7e]+)[^A-Za-z0-9])', '', $v['c_title']);
	$ret = "";
	$s1 = iconv("UTF-8","gb2312", $zh);
	$s2 = iconv("gb2312","UTF-8", $s1);
	if($s2 == $zh){$zh = $s1;}
	for($i = 0; $i < strlen($zh); $i++){
		$s1 = substr($zh,$i,1);
		$p = ord($s1);
		if($p > 160){
			$s2 = substr($zh,$i++,2);
			$ret .= getfirstchar($s2);
		}else{
			$ret .= $s1;
		}
	}
	$tarea = $phpcmsdb->r1('v9_column_content_area',array('title'=>$v['c_area'],'col_id'=>'5',),'id') ;
	$d = array(
			'title'   	    => $v['c_title'],
			'asset_id'      => $v['c_cover_id'],
			'director'      => empty($v['c_director'])?'':$v['c_director'],
			'actor' 	    => empty($c_actor)?'':$c_actor,
			'short_name'	=> $v['c_brief'],
			'tag'   	    => $c_tag,
			'year_released' => $v['c_year'] ,
			'run_time'      => '7200',
			'column_id' 	=> '5',
			'active' 	 	=> '1',
			'area_id' 	 	=> $tarea['id'],
			'is_free' 	    => '1',
			'total_episodes'=> '',
			'parent_id' 	=> '',
			'episode_number' => '',
			'latest_episode_num' => '' ,
			'rating'      	=> $rating ,
			'ispackage'   	=> $ispackage ,
			'catid'   	 	=> '54',
			'status'     	=> '99',
			'sysadd'     	=> '1',
			'username'   	=> 'system' ,
			'updatetime' 	=> time() ,
			'spid'		=> 'ddssp',
			'spells'	=> $ret
	);
	$thisv = $phpcmsdb->r1('v9_video',array('asset_id'=>$v['c_cover_id']),'id,published,online_status') ;
	$thist = $phpcmsdb->r1('v9_video',array('title'=>$v['c_title']),'id,published,online_status') ;
	if(isset($thisv['published']) && strcmp($thisv['published'] ,'1') == 0){
		//已经更新过了
		return ;
	}
	if(isset($thist['published']) && strcmp($thist['published'] ,'1') == 0){
		//已经更新过了
		return ;
	}
	$dd = array(
			'short_desc' => $v['c_title'],
			'long_desc'  => $v['c_brief']
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
		if(in_array($thist['online_status'],array('3','0','1','99'))){
			//3 编辑未通过，0或1 导入，99 错误  这三种状态的数据会在重新导入后被覆盖
			$thisv_id = $thist['id'] ;
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
	vplog("id {$v['c_cover_id']}") ;
	//视频图片入库
	$picty = array('1'=>'102','2'=>'202','3'=>'302','4'=>'402','5'=>'103','6'=>'203','7'=>'303','8'=>'403','9'=>'101','10'=>'204','11'=>'304');
	$phpcmsdb->d('v9_video_poster',array('asset_id'=>$v['c_cover_id'])) ;
	foreach($picty as $pictypes){
		$picty1 = array('1'=>'102','2'=>'202','3'=>'302','4'=>'402') ;    //树图
		$picty2 = array('1'=>'103','2'=>'203','3'=>'303','4'=>'403') ;    //横图
		$picty3 = array('1'=>'101','2'=>'204','3'=>'304') ;				  //大图
		$cd = array(
				'title'   	=> $v['c_title'].'-'.$pictypes ,
				'asset_id'  => $v['c_cover_id'],
				'type'   	=> $pictypes ,
				'catid'   	=> '65',
				'status'    => '99',
				'sysadd'    => '1',
				'username'  => 'system' ,
				'updatetime'=> time()
		);
		if(in_array($pictypes,$picty1)){
			//坏图片
			if(empty($v['c_pic_url'])) return;
			if(!strpos($v['c_pic_url'],'.')) return ;
			list($width,$height,$type,$attr) = getimagesize($v['c_pic_url']);
			$size = $width.'x'.$height;
			$ext = str_replace('.','',strrchr($v['c_pic_url'],'.'));
			$cd['path'] = $v['c_pic_url'];
			$cd['size'] = $size;
			$cd['format'] = $ext;
			$tmp = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$v['c_cover_id'],'type'=>$pictypes,'path'=>$v['c_pic_url']),'id') ;
		}elseif(in_array($pictypes,$picty2)){
			//坏图片
			if(empty($v['c_pic2_url'])) return;
			if(!strpos($v['c_pic2_url'],'.')) return ;
			list($width,$height,$type,$attr) = getimagesize($v['c_pic2_url']);
			$size = $width.'x'.$height;
			$ext = str_replace('.','',strrchr($v['c_pic2_url'],'.'));
			$cd['path'] = $v['c_pic2_url'];
			$cd['size'] = $size;
			$cd['format'] = $ext;
			$tmp = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$v['c_cover_id'],'type'=>$pictypes,'path'=>$v['c_pic2_url']),'id') ;
		}elseif(in_array($pictypes,$picty3)){
			//坏图片
			if(empty($v['c_pic2_498_280'])) return;
			if(!strpos($v['c_pic2_498_280'],'.')) return ;
			list($width,$height,$type,$attr) = getimagesize($v['c_pic2_498_280']);
			$size = $width.'x'.$height;
			$ext = str_replace('.','',strrchr($v['c_pic2_498_280'],'.'));
			$cd['path'] = $v['c_pic2_498_280'];
			$cd['size'] = $size;
			$cd['format'] = $ext;
			$tmp = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$v['c_cover_id'],'type'=>$pictypes,'path'=>$v['c_pic2_498_280']),'id') ;
		}
		if(isset($tmp['id'])){
			$phpcmsdb->w('v9_video_poster',$cd,array('id'=>$tmp['id'])) ;
		}else{
			$cd['inputtime'] = time() ;
			$tmpid = $phpcmsdb->w('v9_video_poster',$cd) ;
			$phpcmsdb->w('v9_video_poster_data',array('id'=>$tmpid)) ;
		}
	}
	//视频链接入库
	$source_id = 21;
	$phpcmsdb->d('v9_video_content',array('asset_id'=>$v['c_cover_id'])) ;
	$cd = array(
				'title'   	=> $v['c_title'] ,
				'asset_id'  => $v['c_cover_id'],
				'path'   	=> $v['c_cover_id'] ,
				'clarity'   => '3',
				'catid'   	=> '64',
				'status'    => '99',
				'sysadd'    => '1',
				'username'  => 'system' ,
				'updatetime'=> time(),
				'source_id' => $source_id ,
				'sourceurl' => $v['c_cover_id']
		);
		$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$v['c_cover_id'],'path'=>$v['c_cover_id'],'source_id'=>$source_id),'id') ;
		if(isset($tmp['id'])) {
			$phpcmsdb->w('v9_video_content',$cd,array('id'=>$tmp['id'])) ;
		}else{
			$cd['inputtime'] = time() ;
			$video_content_id = $phpcmsdb->w('v9_video_content',$cd) ;
			$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
		}
	//插入区域内容
	$dare = array(
			'asset_id' => $v['c_cover_id'],
			'area_id' => '0',
			'isextend' => '0'
	);
	$phpcmsdb->w('v9_video_area',$dare) ;
	//主视频的数据已经导入完毕

	//把视频的数据自动发布上线
	//查询此视频是否已经在线
	$gtmp = $go3cdb->r1('video',array('vid'=>$v['c_cover_id']));
	if(empty($gtmp)){	//视频没有上线
		
	}
	var_dump($d);exit;
}

