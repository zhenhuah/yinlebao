<?php
if(!defined('PHPCMS_PATH')) die('define PHPCMS_PATH') ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;
yzy_sooner_db() ;

//发布菜单
function ypublish_get_atlist(){
	return array(
	'video'			 =>'视频上线',
	'off_video'		 =>'视频下线',
	'del_video'		 =>'视频删除',
	'channel'		 =>'频道上线',
	'channelepg'	 =>'EPG上线',
	'tags_area'		 =>'地区',
	'tags_cate'		 =>'栏目分类',
	'tags_year'		 =>'年代',
	'client_online'  =>'客户端上线',
	'client_delete'  =>'客户端删除',
	'pre_adverts' 	 =>'广告位',
	'pre_task'       =>'推荐位',
	'information'    =>'资讯上线',
	'off_information'=>'资讯下线',
	) ;
}

//发布菜单对应的主键
function ypublish_get_atlist_id(){
	return array(
	'video'			 =>'asset_id',
	'off_video'		 =>'asset_id',
	'del_video'		 =>'asset_id',
	'channel'		 =>'channel_id',
	'channelepg'	 =>'epgid',
	'tags_area'		 =>'id',
	'tags_cate'		 =>'id',
	'tags_year'		 =>'id',
	'client_online'  =>'id',
	'client_delete'  =>'id',
	'pre_adverts'    =>'adId',
	'pre_task' 	 	 =>'taskId',
	'information' 	 =>'id',
	'off_information'=>'id',
	) ;
}

//获取数据来源表
function ypublish_get_off_or_online_table($mtype){
	//针对视频属性三个操作同一张表的特殊情况做处理
	if( in_array($mtype, array('tags_cate','tags_area','tags_year')) ){
		return 'v9_tags';
		//针对客户端上线和删除操作同一张表的特殊情况做处理
	}elseif( in_array($mtype, array('client_online','client_delete')) ){
		return 'v9_client_version';
		//针对推荐视频
	}else{
		if(ypublish_get_off_del_online_s($mtype)) return 'v9_' . substr($mtype,4) ;
		return 'v9_' . $mtype;
	}
}

//排序
function ypublish_get_off_or_online_order($mtype){
	if(strpos($mtype,'pre_adverts') === 0){
		return 'ORDER BY adId DESC';
	}elseif (strpos($mtype,'pre_task') === 0){
		return 'ORDER BY taskId DESC';
	}else{
		return 'ORDER BY id DESC' ;
	}
}

//上线下线where
function ypublish_get_off_or_online_where($mtype){
	$tmp = ypublish_get_off_del_online_s($mtype) ;
	$where = array() ;

	if($tmp == 0 && $mtype != 'adverts' ){
		$where = array('online_status'=>11,'published'=>'0') ;
	}else if($tmp == 1){
		$where = array('offline_status'=>2,'published'=>'1') ;
	}else if($tmp == 2){
		$where = array('online_status'=>21) ;
	}

	if(strpos($mtype,'channel') === 0) {
		return $where ;
	}

	if(strpos($mtype,'pre_task') === 0) {
		$where = array('taskStatus'=>'4') ;
	}

	//控制是否具有 spid
	if( !in_array($mtype, array('tags_area', 'tags_cate', 'tags_year', 'client_online', 'client_delete'))){
		$where['spid'] = get_spid_by_sid() ;
	}

	//为视频属性 地区、栏目、年份 增加属性
	if( in_array($mtype, array('tags_area', 'tags_cate', 'tags_year'))){
		$where['type'] = get_tags_type_by_mtype($mtype);
	}

	if(defined('IN_CRONTAB') && IN_CRONTAB) {
		return $where ;
	}
	//资讯上线
	if(strpos($mtype,'information') === 0) {
		$where = array('online_status'=>'11') ;
	}
	//资讯下线
	if(strpos($mtype,'off_information') === 0) {
		$where = array('online_status'=>'6') ;
	}
	if(strpos($mtype,'pre_adverts') === 0) {
		if($_SESSION['roleid']=='1'){
			$where = array('adStatus'=>'4') ;
		}else{
			$where['type'] = get_tags_type_by_mtype($mtype);
		}
	}
	return $where ;

}

//处理视频属性栏目、地域、年份的type
function get_tags_type_by_mtype($mtype){
	if($mtype == 'tags_area'){
		$type = '1';
	}else if($mtype == 'tags_cate'){
		$type = '2';
	}else if($mtype == 'tags_year'){
		$type = '3';
	}
	return $type;
}


//上下线取消where
function ypublish_get_canc_off_or_online_where($mtype){

	//处理特殊的推荐位情况
	if($mtype == 'pre_task') return array('taskStatus'=>'3');

	$tmp = ypublish_get_off_del_online_s($mtype) ;

	//上线申请取消
	if($tmp == 0) return array('online_status'=>'12') ;

	//下线申请取消
	if($tmp == 1) return array('offline_status'=>'0') ;

	//删除申请取消
	if($tmp == 2) return array('online_status'=>'2') ;
}


//日志操作中的文字
function ypublish_get_off_or_online_txt($mtype){
	$s = array('上线','下线','删除') ;
	return $s[ypublish_get_off_del_online_s($mtype)] ;
}

//上下线状态定义
function ypublish_get_off_del_online_s($mtype){
	if(strpos($mtype,'off_') === 0) return 1 ;
	if(strpos($mtype,'del_') === 0) return 2 ;
	if(strpos($mtype,'client_online') === 0) return 1 ;
	if(strpos($mtype,'client_delete') === 0) return 2 ;
	return 0 ;
}

//发布频道
function __do_publish_to_channel($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;

	//已发布状态
	if(!in_array($r['published'],array('0','3'))) return ;

	//增加计数器
	$sumcount++ ;

	$r['channel_id'] = intval($r['channel_id']) ;
	$rch = $go3cdb->r1('channel',array('channel_id'=>$r['channel_id'])) ;
	if(empty($rch)){
		$d = array(
				'channel_name' => $r['title'],
				'aspect_ratio'      => ($r['iswidth']=='1600:900' || $r['iswidth']=='16:9')?'16:9':'4:3'
			);
		$tmpr = $go3cdb->w('channel',$d,array('channel_id'=>$r['channel_id'])) ;
		if(!$tmpr){
			$d['channel_id'] = $r['channel_id'];
		$go3cdb->w('channel',$d);
		}
	}
	

	$h_uuids = array(1 => "uuidSTB_HIGH",2 => "uuidIOS_HIGH",3 => "uuidIOS_HIGH",4 => "uuidPC_HIGH");
	$m_uuids = array(1 => "uuidSTB_MEDIUM",2 => "uuidIOS_MEDUIM",3 => "uuidIOS_MEDUIM",4 => "uuidPC_MEDIUM");
	//插入频道支持的区域
	$go3cdb->d('channel_area_mapping',array('channel_id'=>$r['channel_id'])) ;
	$char = $phpcmsdb->r('v9_channel_area',array('channel_id'=>$r['channel_id'])) ;
	foreach ($char as $av){
		if (strpos($av['category_id'], ',')!==false){  //判断频道是否一对多的情况
			$categorys = explode(',',$av['category_id']);
			if(count($categorys)){
				foreach($categorys as $c){	
					$dar = array(
						'channel_id' => $r['channel_id'],
						'area_id' => $av['area_id'],
						'category_id' => $c,
						'seq_number' => $av['seq_number'],
						'isextend' => '0'
					);
					$go3cdb->w('channel_area_mapping',$dar);		
				}
			}
		}else{
			$dar = array(
				'channel_id' => $r['channel_id'],
				'area_id' => $av['area_id'],
				'category_id' => $av['category_id'],
				'seq_number' => $av['seq_number'],
				'isextend' => '0'
			);
			$go3cdb->w('channel_area_mapping',$dar);
		}
	}
	$go3cdb->w('channel_area_mapping',array('seq_number'=>$r['listorder'],'category_id'=>$r['channel_category']), array('channel_id'=>$r['channel_id'])) ;
	//插入频道支持的终端
	$go3cdb->d('channel_play_control',array('channel_id'=>$r['channel_id'])) ;
	if($r['STB'] == '1'){
		$ctd = array(
			'channel_id' => $r['channel_id'],
			'term_id' => '1',
			'allow_play' => '0'
			);
			$go3cdb->w('channel_play_control',$ctd);
			$chplay = $phpcmsdb->r('v9_channel_play_info',array('channel_id'=>$r['channel_id'],'isplay'=>'1'));
			foreach ($chplay as $vp){
				$uuid = $vp['uuid'];
				$quality = $vp['quality'];
				$dplay = array(
				'channel_id' => $r['channel_id'],
				'term_id' => '1',
				'quality' => $quality,
				'uuid' => $uuid
				);
				$go3cdb->w('channel_play_info',$dplay) ;
			}
	}
	if($r['PAD'] == '1'){
		$ctd = array(
			'channel_id' => $r['channel_id'],
			'term_id' => '2',
			'allow_play' => '0'
			);
			$go3cdb->w('channel_play_control',$ctd);
			$chplay = $phpcmsdb->r('v9_channel_play_info',array('channel_id'=>$r['channel_id'],'isplay'=>'1'));
			foreach ($chplay as $vp){
				$uuid = $vp['uuid'];
				$quality = $vp['quality'];
				$dplay = array(
				'channel_id' => $r['channel_id'],
				'term_id' => '2',
				'quality' => $quality,
				'uuid' => $uuid
				);
				$go3cdb->w('channel_play_info',$dplay) ;
			}
	}
	if($r['PHONE'] == '1'){
		$ctd = array(
			'channel_id' => $r['channel_id'],
			'term_id' => '3',
			'allow_play' => '0'
			);
			$go3cdb->w('channel_play_control',$ctd);
			$chplay = $phpcmsdb->r('v9_channel_play_info',array('channel_id'=>$r['channel_id'],'isplay'=>'1'));
			foreach ($chplay as $vp){
				$uuid = $vp['uuid'];
				$quality = $vp['quality'];
				$dplay = array(
				'channel_id' => $r['channel_id'],
				'term_id' => '3',
				'quality' => $quality,
				'uuid' => $uuid
				);
				$go3cdb->w('channel_play_info',$dplay) ;
			}
	}
	if($r['PC'] == '1'){
		$ctd = array(
			'channel_id' => $r['channel_id'],
			'term_id' => '4',
			'allow_play' => '0'
			);
			$go3cdb->w('channel_play_control',$ctd);
			$chplay = $phpcmsdb->r('v9_channel_play_info',array('channel_id'=>$r['channel_id'],'isplay'=>'1'));
			foreach ($chplay as $vp){
				$uuid = $vp['uuid'];
				$quality = $vp['quality'];
				$dplay = array(
				'channel_id' => $r['channel_id'],
				'term_id' => '4',
				'quality' => $quality,
				'uuid' => $uuid
				);
				$go3cdb->w('channel_play_info',$dplay) ;
			}
	}
	foreach($h_uuids as $k=>$v){
		$go3cdb->d('channel_image',array('channel_id'=>$r['channel_id'],'term_id'=> $k)) ;
		$dd = array(
		'list_img_url' => $r['img'],
		'details_img_url' => $r['imgpath']
		);
		//$tmpr = $go3cdb->w('channel_image',$dd,array('channel_id'=>$r['channel_id'],'term_id'=> $k)) ;
		//if(!$tmpr){
		$dd['channel_id'] = $r['channel_id'] ;
		$dd['term_id'] = $k;
		$go3cdb->w('channel_image',$dd) ;
		//}
		/*
		if($r[$h_uuids[$k]]){
			$dd = array(
			'channel_id' => intval($r['channel_id']),
			'term_id' => $k,
			'quality' => '3',
			'uuid' => $r[$h_uuids[$k]],
			);
			$tmpr = $go3cdb->w('channel_play_info',array(
			'uuid' => $r[$h_uuids[$k]]),array(
			'channel_id' => intval($r['channel_id']),
			'term_id' => $k,
			'quality' => '3'
			)
			);
			if(!$tmpr){
				$go3cdb->w('channel_play_info',$dd) ;
			}
		}

		if($r[$m_uuids[$k]]){
			$dd = array(
			'channel_id' =>  $r['channel_id'] ,
			'term_id' => $k,
			'quality' => '2',
			'uuid' => $r[$m_uuids[$k]],
			);
			$tmpr = $go3cdb->w('channel_play_info',array(
			'uuid' => $r[$h_uuids[$k]]),array(
			'channel_id' => intval($r['channel_id']),
			'term_id' => $k,
			'quality' => '2'
			)
			);
			if(!$tmpr){
				$go3cdb->w('channel_play_info',$dd) ;
			}
		}*/
	}

	$phpcmsdb->w('v9_channel',array('published'=>1), array('id'=>$r['id'])) ;

	//20130815 luolei 直播频道分类改为1对多	
	//'category_id'  => $r['channel_category'],
	$categorys = explode(',',$r['channel_category']);
	if(count($categorys)){
		$go3cdb->d('channel_category_mapping',array('channel_id'=>$r['channel_id'])) ;
		foreach($categorys as $c){
			$dd = array(
				'channel_id'=>$r['channel_id'],
				'category_id'=>$c
			);
		$go3cdb->w('channel_category_mapping',$dd);		
		}	
	}
	//增加频道的零时表
	$go3cdb->d('live_channel_list',array('channel_id'=>$r['channel_id'])) ;
	$chan_sql  = "select c2.category_id,c6.category_name,c6.icon_img_url category_img,c1.channel_id,c1.channel_name,c1.aspect_ratio,c2.area_id,c2.seq_number channel_area_seq_number,c3.term_id,c4.list_img_url,c4.details_img_url,c6.seq_number category_seq_number from channel c1,channel_area_mapping c2,channel_play_control c3,channel_image c4,channel_category c6 where c1.channel_id=c2.channel_id and c1.channel_id=c3.channel_id and c1.channel_id=c4.channel_id and c3.term_id=c4.term_id and c6.category_id=c2.category_id and c1.channel_id='$r[channel_id]'";
	$chand= $go3cdb->rs($chan_sql) ;
	foreach ($chand as $vc){
		$chd = array(
				'category_id'=>$vc['category_id'],
				'category_name'=>$vc['category_name'],
				'catgory_img'=>$vc['category_img'],
				'channel_id'=>$vc['channel_id'],
				'channel_name'=>$vc['channel_name'],
				'aspect_ratio'=>$vc['aspect_ratio'],
				'area_id'=>$vc['area_id'],
				'channel_area_seq_number'=>$vc['channel_area_seq_number'],
				'term_id'=>$vc['term_id'],
				'list_img_url'=>$vc['list_img_url'],
				'details_img_url'=>$vc['details_img_url'],
				'category_seq_number'=>$vc['category_seq_number']
		);
		$go3cdb->w('live_channel_list',$chd);
	}	
}

//发布EPG
function __do_publish_to_channelepg($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;

	//已发布状态
	if(!in_array($r['published'],array('0','3'))) return ;

	//增加计数器
	$sumcount++ ;

	$d = array(
	'channel_id'  => intval($r['channel_id']),
	'title' 	  => $r['text'],
	'img' 	      => $r['url'],
	'description' => '',
	'start_time'  => $r['starttime'],
	'end_time'    => $r['endtime']
	);
	$go3cdb->w('epg',$d);
	$phpcmsdb->w('v9_channelepg',array('published'=>1), array('id'=>$r['id'])) ;
}


//发布视频
function __do_publish_to_video($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;

	/**
	 * 调试代码 无效
	 */
	/*	$total_episodes_sql = "SELECT MAX(episode_number) AS episode_number, name FROM all_episode_list WHERE 1 and parent_id = '$r[parent_id]'";
	$total_episodes 	= $go3cdb->rs($total_episodes_sql) ;
	$d['parent_id'] 	=  $r['parent_id'];
	echo $total_episodes_sql;
	print_r($total_episodes);
	exit;*/

	//算出发布日期的时间戳
	$r['pub_date_time'] = strtotime($r['pub_date']);

	//如果发布日期大于等于当前日期的时间戳，允许发布 未指定时间的都发布
	if( $r['pub_date'] == '0000-00-00' || $r['pub_date_time'] <= time()){

		static $sumcount ;
		if(!isset($sumcount)) $sumcount = 0 ;
		if($r == 'getsumcount') return $sumcount ;

		//已发布状态
		if(!in_array($r['published'],array('0','3'))) return ;

		//增加计数器
		$sumcount++ ;

		$rd = $phpcmsdb->r1('v9_video_data',array('id'=>$r['id'])) ;

		//随机算出一个播放数
		$play_count = rand(50, 200);
		
		//生成名称的首字母
		if(empty($r['spells'])&&((in_array($r['column_id'],array('3','4','7','8','9'))&&$r['ispackage']==1)||$r['column_id']==5||$r['column_id']==6)){
			$zh = preg_replace('((?=[\x21-\x7e]+)[^A-Za-z0-9])', '', $r['title']);
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
			$r['spells'] = $ret;
		}
		$d = array(
			'vid' =>   $r['asset_id'],
			//取消video表的清晰度字段 2013-08-05
			//'aspect' =>   $r['aspect'],
			'name' =>  $r['title'],
			'uploader' =>  trim($r['uploader']),
			'column_id' => intval($r['column_id']),
			'short_desc' => $rd['long_desc'],
			'long_desc' => $rd['long_desc'],
			'active' => $r['active'],
			'time_created'=> date('Y-m-d H:i:s',$r['inputtime']),
			'time_updated'=> date('Y-m-d H:i:s',time()),
			'created_by' => 'root',
			'play_count' => $play_count,
			'area_id' => $r['area_id'],
			'director' =>  $r['director'],
			//'source_id' =>  $r['source_id'],
			'is_free'  =>  $r['is_free'],
			'run_time' =>  $r['run_time'],
			'year_released' =>  $r['year_released'],
			//'total_episodes' =>  $r['total_episodes'],
			//'parent_id' =>  $r['parent_id'],
			//'episode_number' =>  $r['episode_number'],
			//'latest_episode_number' =>  $r['latest_episode_num']
			'spid'	=> $r['spid'],
			'channel_id' =>$r['channel'],
			//新增的网友是否分享 2013-08-03
			'shared'	=> $r['shared'],
			'spell'	=> $r['spells']
		);

		/**
		 * 当前是发布的总集的时候，那就 latest_episode_number编辑写什么值就发布什么值
		 * 当前发布的是一个分集的时候，首先把分集的总集id查出来，然后根据总集id去查总集下最新的那个集数（max），然后再去更新总集的latest_episode_number
		 */
		if($r['parent_id']){

			//更新总集的latest_episode_number
			//$latest_episode_number_sql  = "SELECT MAX(episode_number) AS episode_number, name FROM all_episode_list WHERE 1 and parent_id = '$r[parent_id]'";
			$latest_episode_number_sql  = "SELECT MAX(episode_number) AS episode_number,name,MIN(episode_number) as minepisode_number FROM video where 1 and parent_id = '$r[parent_id]'";
			$latest_episode_number 	    = $go3cdb->rs($latest_episode_number_sql) ;
			if(!empty($latest_episode_number)){
				foreach($latest_episode_number as  $v){
					$d['latest_episode_number'] = $v['episode_number'];
					$d['oldest_episode_number'] = $v['minepisode_number'];
				}
			}else{
				$d['latest_episode_number'] = 0;
				$d['oldest_episode_number'] = 0;
			}
			if($r['episode_number']){
				$d['latest_episode_number'] = max(intval($r['episode_number']),intval($d['latest_episode_number']));
			}
			//修改本地phpcms的数据
			$phpcmsdb->w('v9_video',array('latest_episode_number'=>$d['latest_episode_number']), array('asset_id'=>$r['parent_id'])) ;
			$go3cdb->w('video',array('latest_episode_number'=>$d['latest_episode_number'],'oldest_episode_number'=>$d['oldest_episode_number']), array('vid'=>$r['parent_id'])) ;

			if($r['column_id'] == '3'){
				//针对电视栏目更新总集的total_episodes
				$total_episodes_sql  = "SELECT COUNT(*)  FROM video WHERE 1 and parent_id = '$r[parent_id]'";
				$total_episodes 	 = $go3cdb->rs($total_episodes_sql) ;

				$d['total_episodes'] = $total_episodes[0]?($total_episodes[0]['COUNT(*)'] ? (1+$total_episodes[0]['COUNT(*)']) : 0):0;
				//修改本地phpcms的数据
				$phpcmsdb->w('v9_video',array('total_episodes'=>$d['total_episodes']), array('asset_id'=>$r['parent_id'])) ;
				$go3cdb->w('video',array('total_episodes'=>$d['total_episodes']), array('vid'=>$r['parent_id'])) ;
			}

			$d['parent_id'] = $r['parent_id'];

		}else{

			$d['latest_episode_number'] =  $r['latest_episode_number'];
			$d['total_episodes'] 	 	=  $r['total_episodes'];

		}

		if($r['episode_number']){
			$d['episode_number'] =  $r['episode_number'];
		}

		$go3cdb->w('video',$d) ;


		//增加插入评分
		$r['rating'] = intval($r['rating']) ? intval($r['rating']) : rand(2,6);
		$dd_rating = array(
		'user_id' 	  => 'system',
		'vid' 		  => $r['asset_id'],
		'rating'	  => $r['rating'],
		'rating_time' => date('Y-m-d H:i:s', time()),
		);
		$go3cdb->w('rating',$dd_rating) ;
		//增加插入临时表
		$rat_sql  = "select vid,count(*) AS rating_count,avg(rating) AS avg_rating from rating where vid = '$r[asset_id]'";
		$rat = $go3cdb->rs($rat_sql) ;
		//检查是否存在
		$go3cdb->d('video_rating_list',array('vid'=>$r['asset_id'])) ;
		$rating = array(
			'vid' 	  		=> $rat[0]['vid'],
			'rating_count' 	=> $rat[0]['rating_count'],
			'avg_rating'    => $rat[0]['avg_rating']
		);
		$go3cdb->w('video_rating_list',$rating) ;
		//更新最新集数video_episode_source
		$sc = $phpcmsdb->r('v9_video_content',array('asset_id'=>$r['asset_id'])) ;
		foreach ($sc as $v){
			$source_id = $v['source_id'];
			$episode = $go3cdb->r1('video_episode_source',array('vid'=>$r['parent_id'],'source'=>$source_id)) ;
			if(!empty($episode)){
				$total_episodes =$episode['total_episodes']+1;
				$min_num = $episode['min_num']>$r['episode_number']?$r['episode_number']:$episode['min_num'];
				$max_num = $episode['max_num']>$r['episode_number']?$episode['max_num']:$r['episode_number'];
				$ar_ep = array(
					'total_episodes'=>$total_episodes,
					'min_num'=>$min_num,
					'max_num'=>$max_num
				);
				$go3cdb->w('video_episode_source',$ar_ep,array('vid'=>$r['parent_id'],'source'=>$source_id)) ;
			}else{
				$ar_ep = array(
					'total_episodes'=>1,
					'min_num'=>$r['episode_number'],
					'max_num'=>$r['episode_number'],
					'source'=>$source_id,
					'vid' =>$r['parent_id']
				);
				$go3cdb->w('video_episode_source',$ar_ep) ;
			}
		}
		//插入字幕
		$sub = $phpcmsdb->r('v9_video_subtitle',array('asset_id'=>$r['asset_id'])) ;
		foreach($sub as $iv){
			$su = array(
				'vid' => $r['asset_id'],
				'url' => $iv['url'],
				'source' => $iv['source'],
				'run_time' => $iv['run_time'],
				'format' => $iv['format'],
				'language' => $iv['language']
			);
			$go3cdb->w('video_subtitle',$su);
		}
		//插入视频支持的区域
		$go3cdb->d('video_area_mapping',array('vid'=>$r['asset_id'])) ;
		$videoarea = $phpcmsdb->r('v9_video_area',array('asset_id'=>$r['asset_id'])) ;
		foreach ($videoarea as $va){
			$sar = array(
				'vid' => $r['asset_id'],
				'area_id' => $va['area_id'],
				'isextend' => '0'
			);
			$go3cdb->w('video_area_mapping',$sar);
		}
		//视频支持终端控制STB 先清理，再添加
		$go3cdb->d('video_play_control',array('vid'=>$r['asset_id'], 'term_id'=>'1')) ;
		$STB = array(
		'vid' => $r['asset_id'],
		'term_id' => '1',
		'allow_play' => intval($r['STB']),
		'parent_id' => $r['parent_id']
		);
		$go3cdb->w('video_play_control',$STB) ;

		//视频支持终端控制PHONE
		$go3cdb->d('video_play_control',array('vid'=>$r['asset_id'], 'term_id'=>'2')) ;
		$PHONE = array(
		'vid' => $r['asset_id'],
		'term_id' => '2',
		'allow_play' => intval($r['PHONE']),
		'parent_id' => $r['parent_id']
		);
		$go3cdb->w('video_play_control',$PHONE) ;

		//视频支持终端控制PAD
		$go3cdb->d('video_play_control',array('vid'=>$r['asset_id'], 'term_id'=>'3')) ;
		$PAD = array(
		'vid' => $r['asset_id'],
		'term_id' => '3',
		'allow_play' => intval($r['PAD']),
		'parent_id' => $r['parent_id']
		);
		$go3cdb->w('video_play_control',$PAD) ;

		//视频支持终端控制PC
		$go3cdb->d('video_play_control',array('vid'=>$r['asset_id'], 'term_id'=>'4')) ;
		$PC = array(
		'vid' => $r['asset_id'],
		'term_id' => '4',
		'allow_play' => intval($r['PC']),
		'parent_id' => $r['parent_id']
		);
		$go3cdb->w('video_play_control',$PC) ;

		//增加tag
		$r['tag'] = str_replace('，',',',$r['tag']) ;
		$tags = explode(',',$r['tag']) ;
		$atags = $go3cdb->r('tag',array('tag_name'=>$tags),'tag_id,tag_name',array('key_value'=>'tag_name,tag_id')) ;

		//先清空
		$go3cdb->d('video_tags',array('vid'=>$r['asset_id'])) ;

		$i = 1 ;
		foreach($tags as $iv){
			if(!isset($atags[$iv])){
				$tagd = array() ;
				$tagd['tag_name'] = $iv ;
				$tagd['type'] = 2 ;
				$atags[$iv] = $go3cdb->w('tag',$tagd) ;
			}
			$tdd = array() ;
			$tdd['vid'] = $r['asset_id'] ;
			$tdd['tag_id'] = $atags[$iv] ;
			$tdd['seq_number'] = $i ;
			$go3cdb->w('video_tags',$tdd) ;
			$i++ ;
			//判断tag是否是搜狐美剧(总集添加到stb-搜狐美剧推荐位)
			if(strpos($iv,'搜狐')!== false&&empty($r['parent_id'])&&$r['ispackage']==1){
				$taskid = '26';
				//addtaskvideo($taskid,$r);
			}elseif(strpos($iv,'优酷')!== false){
				$taskid = '27';
				//addtaskvideo($taskid,$r);
			}
		}
		//增加actor
		$r['actor'] = str_replace('，',',',$r['actor']) ;
		$actor = explode(',',$r['actor']) ;
		$aactors = $go3cdb->r('actor',array('name'=>$actor),'id,name',array('key_value'=>'name,id')) ;

		//先清空
		$go3cdb->d('video_actors',array('vid'=>$r['asset_id'])) ;

		$i = 1 ;
		foreach($actor as $iv){
			if(!isset($aactors[$iv])){
				$tagd = array() ;
				$tagd['name'] = $iv ;
				$aactors[$iv] = $go3cdb->w('actor',$tagd) ;
			}
			$tdd = array() ;
			$tdd['vid'] = $r['asset_id'] ;
			$tdd['actor_id'] = $aactors[$iv] ;
			$tdd['seq_number'] = $i ;

			$go3cdb->w('video_actors',$tdd) ;

			$i++ ;
		}
		//增加演员到零时表
		$act_sql  = "SELECT `v`.`vid` AS `vid` , `v`.`actor_id` AS `actor_id` , `v`.`seq_number` AS `seq_number` , `a`.`name` AS `name` FROM (`video_actors` `v` JOIN `actor` `a` ON ( (`v`.`actor_id` = `a`.`id`) )) where vid='$r[asset_id]' ORDER BY `v`.`vid` , `v`.`seq_number`";
		$act = $go3cdb->rs($act_sql) ;
		//检查是否存在
		$go3cdb->d('video_actor_list',array('vid'=>$r['asset_id'])) ;
		foreach ($act as $va){
			$ac = array(
				'vid' 		=> $va['vid'],
				'actor_id'  => $va['actor_id'],
				'seq_number' => $va['seq_number'],
				'name' 		=> $va['name']
			);
			$go3cdb->w('video_actor_list',$ac) ;
		}
		//如果是分集，则检查已上线的总集的图片；如果总集图片的类型多于自己的，则自动将总集其他类型图片变成自己图片
		if($r['parent_id']){
			$vvpi = $go3cdb->r('video_image',array('vid'=>$r['parent_id'])) ;
			foreach($vvpi as $vi){
				$vi['vid']=$r['asset_id'];
				$go3cdb->w('video_image',$vi);
			}
			$tt = array(
				'time_created'=> date('Y-m-d H:i:s',time()),
				'time_updated'=> date('Y-m-d H:i:s',time())
			);
			$go3cdb->w('video',$tt,array('vid'=>$r['parent_id'])) ;
			/*
			//$vvvi = $go3cdb->r('video_image',array('vid'=>$r['asset_id'])) ;
			//$vvvi_types = array();
			//foreach($vvvi as $vi){
			//	$vvvi_types[] = $vi['img_type'];
			//}
				foreach($vvpi as $vi){
					//if(in_array($vi['img_type'],$vvvi_types)){
					//}else{
						$vi['vid']=$r['asset_id'];
						$go3cdb->w('video_image',$vi);
					//}
				}
				*/
		}else{
			$rs = $phpcmsdb->r('v9_video_poster',array('asset_id'=>$r['asset_id'])) ;
			foreach($rs as $iv){
				$dd = array(
				'vid' => $iv['asset_id'],
				'img_id' => intval($iv['type']),
				'img_url' => $iv['path'],
				'img_type' => intval($iv['type'])
				);
				$go3cdb->w('video_image',$dd) ;
			}
		}
		//结束
		//增加图片到零时表
		$go3cdb->d('video_image_list',array('vid'=>$r['asset_id'])) ;
		$go3cdb->d('video_short_list',array('vid'=>$r['asset_id'])) ;
		$go3cdb->d('video_tag_list',array('vid'=>$r['asset_id'])) ;
		$go3cdb->d('all_episode_list',array('vid'=>$r['asset_id'])) ;
		$im_sql  = "SELECT `vi`.`vid` AS `vid` , `vi`.`img_id` AS `img_id` , `vi`.`img_url` AS `img_url` , `vi`.`img_type` AS `img_type` , `it`.`term_id` AS `term_id` , `it`.`usage_type` AS `usage_type` , `it`.`description` AS `description` , `it`.`file_format` AS `file_format` , `it`.`img_size` AS `img_size` FROM (`video_image` `vi` LEFT JOIN `image_type` `it` ON ( (`vi`.`img_type` = `it`.`id`) )) where vid='$r[asset_id]'";
		$im = $go3cdb->rs($im_sql) ;
		foreach ($im as $iv){
			$img = array(
				'vid' => $iv['vid'],
				'img_id' => $iv['img_id'],
				'img_url' => $iv['img_url'],
				'img_type' => $iv['img_type'],
				'term_id' => $iv['term_id'],
				'usage_type' => $iv['usage_type'],
				'description' => $iv['description'],
				'file_format' => $iv['file_format'],
				'img_size' => $iv['img_size']
			);
			$go3cdb->w('video_image_list',$img) ;
			if(empty($r['parent_id'])&&$r['ispackage']=='1'){
				//增加主信息到零时表(总集)
				$vd = array(
						'vid' => $r['asset_id'],
						'name' => $r['title'],
						'column_id' => intval($r['column_id']),
						'short_desc' => $rd['long_desc'],
						'time_updated' => date('Y-m-d H:i:s',time()),
						'run_time' => $r['run_time'],
						'year_released' => $r['year_released'],
						'area_id' => $r['area_id'],
						'play_count' => $play_count,
						'channel_id' => $r['channel'],
						'spid' => $r['spid'],
						'shared' => $r['shared'],
						'col_type' => $r['col_type'],
						'term_id' => $iv['term_id'],
						'img_size' => $iv['img_size'],
						'img_url' => $iv['img_url'],
						'area_id_sign' => '0',
						'total_episodes' => $r['total_episodes'],
						'latest_episode_number' => $r['latest_episode_number']
				);
				$go3cdb->w('video_short_list',$vd) ;
			}
			//增加标签到临时表
			if(!empty($tags)){
				foreach($tags as $iv){
					$tgv = array(
							'vid' => $r['asset_id'],
							'name' => $r['title'],
							'tag_id' => $atags[$iv],
							'tag_name' => $iv,
							'seq_number' => $i,
							'column_id' => $r['column_id'],
							'short_desc' => $rd['long_desc'],
							'time_updated' => date('Y-m-d H:i:s',time()),
							'run_time' => $r['run_time'],
							'year_released' => $r['year_released'],
							'area_id' => $r['area_id'],
							'play_count' => $play_count,
							'term_id' => $iv['term_id'],
							'channel_id' => $r['channel'],
							'area_id_sign' => '0',
							'img_size' => $iv['img_size'],
							'img_url' => $iv['img_url'],
							'spid' => $r['spid'],
							'col_type' => $r['col_type'],
							'shared' => $r['shared']
					);
					if($r['parent_id']){
						$tgv['total_episodes'] = $d['total_episodes'];
						$tgv['latest_episode_number'] = $d['latest_episode_number'];
					}
					$go3cdb->w('video_tag_list',$tgv) ;
				}
			}
		}
		//增加临时信息all_episode_list
		if($r['episode_number']&&$r['parent_id']){
			foreach ($im as $ims){
				$alle = array(
						'vid' => $r['asset_id'],
						'name' => $r['title'],
						'column_id' => intval($r['column_id']),
						'short_desc' => $rd['long_desc'],
						'run_time' => $r['run_time'],
						'year_released' => $r['year_released'],
						'play_count' => $play_count,
						'parent_id' => $r['parent_id'],
						'episode_number' => $r['episode_number'],
						'term_id' => $ims['term_id'],
						'img_size' => $ims['img_size'],
						'img_url' => $ims['img_url']
				);
				$go3cdb->w('all_episode_list',$alle) ;
				//顺带更新零时表总集的时间
				$dv =array(
					'time_updated' => date('Y-m-d H:i:s',time()),
					'latest_episode_number' => $r['episode_number'],
					'total_episodes' => $d['total_episodes']
				);
				$go3cdb->w('video_short_list',$dv,array('vid'=>$r['parent_id'])) ;
			}
		}
		$rs = $phpcmsdb->r('v9_video_content',array('asset_id'=>$r['asset_id'])) ;
		foreach($rs as $iv){
			$dd = array(
				'vid' => $iv['asset_id'],
				'quality' => $iv['clarity'],
				'source' => empty($iv['source_id'])?'1':$iv['source_id'],
				'play_url' => $iv['path'],
				//加入视频清晰度 2013-08-05
				'aspect' => $iv['aspect'],
				'ratio' => ($iv['ratio']=='16:9')?'16:9':'4:3',
				'format' => $iv['videoformat'],
				'protocol' => $iv['videoprotocol']
			);
			$go3cdb->w('video_play_info',$dd) ;
		}
		
		$phpcmsdb->w('v9_video',array('published'=>1), array('id'=>$r['id'])) ;
	}
}
//添加一个视频到推荐位
function addtaskvideo($taskid,$r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	global $db ;
	//先判断此视频是否已经添加到此推荐位
	$rt = $phpcmsdb->r1('v9_pre_task_video',array('taskId'=>$taskid,'videoId'=>$r['asset_id'])) ;
	//查询此推荐位的信息
	$taskInfo = $phpcmsdb->r1('v9_pre_task',array('taskId'=>$taskid,'spid'=>'ddssp')) ;
	if(empty($rt)){   //不存在则可以添加到推荐位
		//查询此视频的详细信息
		$videoInfo = $phpcmsdb->r1('v9_video',array('asset_id'=>$r['asset_id'])) ;
		$videodataInfo = $phpcmsdb->r1('v9_video_data',array('id'=>$r['id'])) ;
		if(!empty($videodataInfo)){
			$long_desc = empty($videodataInfo['long_desc'])?$videodataInfo['short_desc']:$videodataInfo['long_desc'];
		}
		$videoDesc = empty($long_desc)?$r['short_name']:$long_desc;     //过滤推荐位的描述
		//查询此视频的图片
		$image = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$r['asset_id'],'type'=>$taskInfo['imgType'])) ;
		$insert_data = array(
				'taskId' => $taskid,						//任务ID
				'term_id' => $taskInfo['term_id'],			//终端类型
				'posid' => $taskInfo['posid'],				//推荐位类型
				'spid' => $taskInfo['spid'],				//运营商
				'posidInfo' => $taskInfo['posidInfo'],		//任务名称
				'videoId' => $r['asset_id'],				//视频ID
				'videoSource' => $taskInfo['videoSource'],	//来自那个表类型
				'videoTitle' => $r['title'],				//名称
				'videoDesc' => $videoDesc,					//视频简介
				'imgType' => $taskInfo['imgType'],			//图片类型
				'videoImg' => $image['path'],				//图片类型
				'videoSort' => '0',							//排序
				'status' => 'Y',							//可用状态
				'crontab_date' => $taskInfo['taskDate'],	//任务时间
				'online_date' => strtotime($videoInfo['pub_date'])?strtotime($videoInfo['pub_date']):'0',
				'online_status' => $videoInfo['online_status'],
				'offline_date' => strtotime($videoInfo['offline_date'])?strtotime($videoInfo['offline_date']):'0',
				'offline_status' => $videoInfo['offline_status'],
				'posttime' => time()
		);
		$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
		$updateVideNums = $taskInfo['videoNums'] + 1;
		if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1]))
		{
			$phpcmsdb->w('v9_pre_task_video',$insert_data) ;
			//回滚推荐位到编辑状态
			$phpcmsdb->w('v9_pre_task',array('videoNums'=>$updateVideNums,'taskStatus'=>1,'taskDate'=>$taskInfo['taskDate']), array('taskId'=>$taskInfo['taskId'])) ;
		}
	}
}
//资源/资源包修改
function __do_publish_to_update_video($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	global $db ;

	$db->d('go3c_asset', array('id'=>$r['id'])) ;
	$db->d('go3c_assetpackage_asset', array('asset_id'=>$r['id'])) ;
	$db->d('go3c_asset_content', array('asset_id'=>$r['id'])) ;
	$db->d('go3c_asset_poster', array('asset_id '=>$r['id'])) ;

	__do_publish_to_off_video($r);
	__do_publish_to_del_video($r);

}
//视频下线
function __do_publish_to_off_video($r){
	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;

	//不是上线线状态
	if(!in_array($r['published'],array('1'))) return ;

	//增加计数器
	$sumcount++ ;

	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	$phpcmsdb->w('v9_video',array('published'=>0,'offline_status'=>1,'online_status'=>2), array('id'=>$r['id'])) ;

	$dwhere = array('vid'=>$r['asset_id']) ;
	$go3cdb->d('video',$dwhere) ;
	$go3cdb->d('video_image',$dwhere) ;
	$go3cdb->d('video_play_info',$dwhere) ;
	$go3cdb->d('video_play_control',$dwhere) ;
	$go3cdb->d('video_tags',$dwhere) ;
	$go3cdb->d('video_actors',$dwhere) ;
	$go3cdb->d('bookmark',$dwhere) ;
	$go3cdb->d('view_history',$dwhere) ;
	$go3cdb->d('video_subtitle',$dwhere) ;
	$go3cdb->d('video_area_mapping',$dwhere) ;
}

//视频删除
function __do_publish_to_del_video($r){
	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//不是待删除状态
	if(!in_array($r['online_status'],array('21'))) return ;
	//增加计数器
	$sumcount++ ;
	$phpcmsdb = yzy_phpcms_db() ;
	$phpcmsdb->d('v9_video_content', array('id'=>$r['id'])) ;
	$phpcmsdb->d('v9_video_content_data', array('id'=>$r['id'])) ;
	$phpcmsdb->d('v9_video_poster', array('asset_id'=>$r['asset_id'])) ;
	$phpcmsdb->d('v9_video_poster_data', array('asset_id'=>$r['asset_id'])) ;
	$phpcmsdb->d('v9_video', array('id'=>$r['id'])) ;
	$phpcmsdb->d('v9_video_data', array('id'=>$r['id'])) ;
}

//发布地区
function __do_publish_to_tags_area($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb   = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//已发布状态
	if(!in_array($r['published'],array('0','3'))) return ;
	//增加计数器
	$sumcount++ ;

	//删除原有的信息
	$go3cdb->d('column_content_area', array('area_id'=>$r['id'])) ;
	$go3cdb->d('column_area_list', array('area_id'=>$r['id'])) ;
	//拆分belong字段为数组
	$col_id_array = explode(',', $r['belong']);

	//循环插入
	foreach ($col_id_array AS $col_value){
		$d = array(
		'col_id' 	=> $col_value,
		'area_id'	=> $r['id'],
		'area_name' => $r['title']
		);
		$go3cdb->w('column_content_area',$d);
		$column = $go3cdb->r1('column',array('col_id'=>$col_value),'*');
		$cd = array(
			'col_id' 	=> $col_value,
			'col_name' 	=> $column['col_name'],
			'area_id' 	=> $r['id'],
			'area_name' => $r['title']
		);
		$go3cdb->w('column_area_list',$cd);
	}

	//往go3c tag表插入
	$go3c_tag = $go3cdb->r('tag', array('tag_name'=>$r['title']), 'tag_id,tag_name', array()) ;
	if(!isset($go3c_tag)){
		$tagd = array() ;
		$tagd['tag_name'] = $r['title'];
		$tagd['type'] = 1;
		$go3cdb->w('tag',$tagd) ;
	}

	$phpcmsdb->w('v9_tags',array('published'=>1), array('id'=>$r['id'])) ;
}

//发布栏目
function __do_publish_to_tags_cate($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb   = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//已发布状态
	if(!in_array($r['published'],array('0','3'))) return ;
	//增加计数器
	$sumcount++ ;

	//删除原有的信息
	$go3cdb->d('column_content_category', array('cat_id'=>$r['id'])) ;

	//拆分belong字段为数组
	$col_id_array = explode(',', $r['belong']);

	//循环插入
	foreach ($col_id_array AS $col_value){
		$d = array(
		'col_id' 	=> $col_value,
		'cat_id'	=> $r['id'],
		'cat_name' => $r['title']
		);
		$go3cdb->w('column_content_category',$d);
	}

	//往go3c tag表插入
	$go3c_tag = $go3cdb->r('tag', array('tag_name'=>$r['title']), 'tag_id,tag_name', array()) ;
	if(!isset($go3c_tag)){
		$tagd = array() ;
		$tagd['tag_name'] = $r['title'];
		$tagd['type'] = 2;
		$go3cdb->w('tag',$tagd) ;
	}

	$phpcmsdb->w('v9_tags',array('published'=>1), array('id'=>$r['id'])) ;
}

//发布年份
function __do_publish_to_tags_year($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb   = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//已发布状态
	if(!in_array($r['published'],array('0','3'))) return ;
	//增加计数器
	$sumcount++ ;

	//删除原有的信息
	$go3cdb->d('column_content_year', array('seq_number'=>$r['id'])) ;

	//拆分belong字段为数组
	$col_id_array = explode(',', $r['belong']);

	//循环插入
	foreach ($col_id_array AS $col_value){
		$d = array(
		'col_id' 		=> $col_value,
		'seq_number'	=> $r['id'],
		'year_released' => $r['title']
		);
		$go3cdb->w('column_content_year',$d);
	}
	$phpcmsdb->w('v9_tags',array('published'=>1), array('id'=>$r['id'])) ;
}

//发布客户端
function __do_publish_to_client_online($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb   = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//已发布状态
	if(!in_array($r['published'],array('0','3'))) return ;
	//增加计数器
	$sumcount++ ;
	$d = array(
	'term_id' 		  => $r['term_id'],
	'os_type' 		  => $r['os_type'],
	'version_number'  => $r['title'],
	'features' 		  => $r['features'],
	'release_date' 	  => $r['release_date'],
	'force_update'	  => $r['force_update'],
	'date_tested' 	  => $r['date_tested'],
	'last_edit_time'  => date('Y-m-d H:i:s', $r['updatetime']),
	'last_edit_by' 	  => $r['username'],
	'update_location' => $r['update_location'],
	);
	//插入信息
	$go3cdb->w('client_update',$d);
	$phpcmsdb->w('v9_client_version',array('published'=>1), array('id'=>$r['id'])) ;
}

//删除客户端
function __do_publish_to_client_delete($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb   = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//已发布状态
	if(!in_array($r['published'],array('0','3'))) return ;
	//增加计数器
	$sumcount++ ;
	$d = array(
	'term_id' 		  => $r['term_id'],
	'os_type' 		  => $r['os_type'],
	'version_number'  => $r['title'],
	);
	//删除客户端
	$go3cdb->d('client_update',$d);
	$phpcmsdb->d('v9_client_version', array('id'=>$r['id'])) ;
	$phpcmsdb->d('v9_client_version_data', array('id'=>$r['id'])) ;
}

//发布广告
function __do_publish_to_pre_adverts($r){

	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb   = yzy_go3c_db() ;

	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;

	//echo time();exit;

	//已发布状态
	if(!in_array($r['adStatus'],array('4'))) return ;

	//老的根据三个主键来判断的方法
	//$arr_conf = array('type_id' =>$r['type_id'], 'term_id' => $r['term_id'], 'spid' => $r['spid']);
	//新的通过广告id判断的方法
	$arr_conf = array('ad_id' =>$r['adId']);

	//检查广告位是否存在
	$adverts_data = $go3cdb->r('adverts', $arr_conf);
	$adverts_num  = count($adverts_data);

	$r['time_start'] = date('Y-m-d H:i:s', $r['taskDate']);

	/*	
	echo '<pre>';print_r($r);
	echo '<pre>';print_r($arr_conf);
	echo $adverts_num;
	*/

	//增加计数器
	$sumcount++ ;
	
	$tupdated = date('Y-m-d H:i:s');
	
	//如果当前时间大于等于预发布时间
	//if( time() >= $r['taskDate']){
		
	//如果不存在就新增，存在就更新
	if(!$adverts_num){
		$d = array(
			'ad_id'        => $r['adId'],
			'type_id'      => $r['type_id'],
			'ad_type' 	   => $r['adType'],
			'position' 	   => $r['position'],
			'display_type' => $r['viewType'],
			'term_id' 	   => $r['term_id'],
			'spid' 	  	   => $r['spid'],
			'text' 	  	   => $r['adDesc'],
			'img_url' 	   => $r['imgUrl'],
			'link_url' 	   => $r['linkUrl'],
			'time_start'   => $r['time_start'],
			'duration'     => $r['duration'],
			'time_updated' => $tupdated,
			'area_id'      => '0'
		);
		//echo '<pre>';print_r($d);exit;
		$go3cdb->w('adverts',$d);
	}else{
		$d = array(
			'type_id'      => $r['type_id'],
			'ad_type' 	   => $r['adType'],
			'position' 	   => $r['position'],
			'display_type' => $r['viewType'],
			'term_id' 	   => $r['term_id'],
			'spid' 	  	   => $r['spid'],
			'text' 	  	   => $r['adDesc'],
			'img_url' 	   => $r['imgUrl'],
			'link_url' 	   => $r['linkUrl'],
			'time_start'   => $r['time_start'],
			'duration'     => $r['duration'],
			'time_updated' => $tupdated,
			'area_id'      => '0'
		);
		//echo '<pre>';print_r($d);exit;
		$go3cdb->w('adverts', $d, $arr_conf);
	}
	
	//}
	
	$phpcmsdb->w('v9_pre_adverts', array('adStatus'=>'100'), array('adId'=>$r['adId'])) ;
}


//发布推荐内容
function __do_publish_to_pre_task($r){

	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb   = yzy_go3c_db() ;

	//查询type_id
	$asset_type_id   = $phpcmsdb->r1('v9_position', array('posid' => $r['posid']), 'type_id');
	$r['type_id']    = $asset_type_id['type_id'];

	$task_video = $phpcmsdb->r('v9_pre_task_video', array('taskId' => $r['taskId']), '*');
	//资讯推荐位数组
	$infor_array = array('901','902');
	if(!empty($r['type_id'])&&in_array($r['type_id'],$infor_array)){
		//处理推荐的资讯
		foreach ($task_video as $tvalue){
			$asset_column_id = $phpcmsdb->r1('v9_information', array('id' => $tvalue['videoId']), 'thumb');
			$tvalue['thumb'] = $asset_column_id['thumb'];
			//构造img_id
			if($tvalue['imgType'] == 'img'){
				$tvalue['img_id']   = '1';
			}elseif ($tvalue['imgType'] == 'imgpath'){
				$tvalue['img_id'] = '2';
			}elseif ($tvalue['imgType'] == '0'){
				$tvalue['img_id'] = '1';
			}else{
				$tvalue['img_id'] = $tvalue['imgType'];
			}
			//其他需要传递过来的变量
			$tvalue['type_id']    = $r['type_id'];
			$tvalue['taskStatus'] = $r['taskStatus'];
			$tvalue['taskDate']   = $r['taskDate'];
			
			$task_video_array[] = $tvalue;
		}
		static $sumcount ;
		if(!isset($sumcount)) $sumcount = 0 ;
		if($r == 'getsumcount') return $sumcount ;
		
		//已发布状态
		if(!in_array($r['taskStatus'],array('4'))) return ;
		
		//增加计数器
		$sumcount++ ;
		//根据三个主键删除所有的老的数据
		$dwhere = array('term_id' =>$r['term_id'], 'type_id' => $r['type_id']);
		//$go3cdb->d('',$dwhere);
		//循环处理推荐位内的视频
		foreach ($task_video_array as $value) {
			$value['preId'] = $value['videoSort'] ? $value['videoSort'] : $value['preId'];
			$data_video = array(
					'term_id' 	 => $value['term_id'],
					'type_id'    => $value['type_id'],
					'type_name'    => $value['posidInfo'],
					'seq_number' => $value['preId'],
					'img_url' 	 => $value['videoImg']
			);
			$go3cdb->w('',$data_video);
		}
	}else{
		if(empty($r['type_id'])) return;
		foreach ($task_video as $tvalue) {
		
			//只有是video的时候才需要查询column_id，其他情况下column_id = 2
			if($tvalue['videoSource'] == '2'){
				$asset_column_id = $phpcmsdb->r('v9_video', array('asset_id' => $tvalue['videoId']), 'column_id');
				$tvalue['column_id'] = $asset_column_id[0]['column_id'];
			}else {
				$tvalue['column_id'] = '2';
			}
		
			//处理videoID
			if($tvalue['videoSource'] == '1'){
				$tvalue['videoId'] = 'Channel'.$tvalue['videoId'];
			}elseif($r['videoSource'] == '3'){
				$tvalue['videoId'] = 'Epg'.$tvalue['videoId'];
			}
		
			//构造img_id
			if($tvalue['imgType'] == 'img'){
				$tvalue['img_id']   = '1';
			}elseif ($tvalue['imgType'] == 'imgpath'){
				$tvalue['img_id'] = '2';
			}elseif ($tvalue['imgType'] == '0'){
				$tvalue['img_id'] = '1';
			}else{
				$tvalue['img_id'] = $tvalue['imgType'];
			}
		
			//其他需要传递过来的变量
			$tvalue['type_id']    = $r['type_id'];
			$tvalue['taskStatus'] = $r['taskStatus'];
			$tvalue['taskDate']   = $r['taskDate'];
		
			$task_video_array[] = $tvalue;
		}
		
		static $sumcount ;
		if(!isset($sumcount)) $sumcount = 0 ;
		if($r == 'getsumcount') return $sumcount ;
		
		//echo '<pre>';print_r($task_video_array);
		
		//已发布状态
		if(!in_array($r['taskStatus'],array('4'))) return ;
		
		//如果是今天需要发布的内容
		//if( date('Y-m-d',$r['taskDate']) == date('Y-m-d',time()) ){
		
		//增加计数器
		$sumcount++ ;
		
		//根据三个主键删除所有的老的数据
		$dwhere = array('spid' =>$r['spid'],  'term_id' =>$r['term_id'], 'type_id' => $r['type_id']);
		$go3cdb->d('recommended_video',$dwhere);
		//根据三个主键删除所有此推荐位的老的零时表的数据
		$go3cdb->d('recomm_video_list', array('spid' => $r['spid'],'type_id'=>$r['type_id'],'term_id'=>$r['term_id']));
		//循环处理推荐位内的图片
		foreach ($task_video_array as $value) {
			//检查有没有
			$data_image = array(
				'vid' 	   => $value['videoId'],
				'img_id'   => $value['img_id'],
				'img_url'  => $value['videoImg'],
				'img_type' => $value['img_id']
			);
			//echo '<pre>';print_r($data_image);
			 $video_image_exist = $go3cdb->r1('video_image', array('vid'=> $value['videoId'],'img_id'=> $value['img_id']),'*');
			//echo '<pre>';print_r($video_image_exist);
			if(!$video_image_exist['vid']){
				$go3cdb->w('video_image',$data_image);
			}else{
				if($value['imgType'] == '0'){
					$go3cdb->w('video_image',$data_image,array('vid'=> $value['videoId'],'img_id'=> $value['img_id']));
				}
			}
		
		}	
		//循环处理推荐位内的视频
		foreach ($task_video_array as $value) {
			
			$value['preId'] = $value['videoSort'] ? $value['videoSort'] : $value['preId'];
			if($value[isout]==1){
				$value['column_id'] = '99';   //外链栏目id定为99
			}
			$data_video = array(
				'spid' 	     => $value['spid'],
				'term_id' 	 => $value['term_id'],
				'type_id'    => $value['type_id'],
				'seq_number' => $value['preId'],
				'column_id'  => $value['column_id'],
				'vid' 	  	 => $value['videoId'],
				'img_id' 	 => $value['imageid'],
				'title' 	 => $value['videoTitle'],
				'subtitle' 	 => $value['videoDesc'],
				'imgurl' 	 => $value['videoImg'],
				'img_seq'    => $value['imageid']
			);
			//echo '<pre>';print_r($data_video);
			$go3cdb->w('recommended_video',$data_video);
			//推荐位的内容写入零时表
			$data_vem = array(
				'spid' 	     	=> $value['spid'],
				'term_id' 	    => $value['term_id'],
				'type_id' 	    => $value['type_id'],
				'description' 	=> $value['posidInfo'],
				'seq_number' 	=> $value['preId'],
				'vid' 	     	=> $value['videoId'],
				'title' 	    => $value['videoTitle'],
				'subtitle' 	    => $value['videoDesc'],
				'img_id' 	    => $value['imageid'],
				'column_id' 	=> $value['column_id'],
				'linkurl' 	    => $value['videoPlayUrl'],
				'img_url' 	    => $value['videoImg'],
				'area_id_sign'  => '0',
				'img_seq'    	=> $value['imageid']
			);
			$go3cdb->w('recomm_video_list',$data_vem);
		}				
	}
	//}
	//echo $sumcount;
	//exit;
	//同一个推荐位的其他任务打回编辑状态
	$phpcmsdb->w('v9_pre_task', array('taskStatus' =>'1'), array('spid' =>$r['spid'],  'term_id' =>$r['term_id'], 'posid' => $r['posid']));
	//该推荐位设置为已经发布状态
	$phpcmsdb->w('v9_pre_task', array('taskStatus' =>'100'), array('taskId' =>$r['taskId']));
}

//发布资讯新闻上线
function __do_publish_to_information($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//增加计数器
	$sumcount++ ;
	if(!empty($r['id'])){
		
	}
	//更新资讯新闻上线的状态
	$phpcmsdb->w('v9_information',array('online_status'=>4), array('id'=>$r['id'])) ;
}
//资讯新闻下线
function __do_publish_to_off_information($r){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	static $sumcount ;
	if(!isset($sumcount)) $sumcount = 0 ;
	if($r == 'getsumcount') return $sumcount ;
	//增加计数器
	$sumcount++ ;
	var_dump($r);
	$phpcmsdb ->w('v9_information',array('online_status'=>1),array('id'=>$r['id']));
}
//获取视频名称的首字母
function getfirstchar($s0){
	$fchar = ord($s0{0});
	if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
	$s1 = iconv("UTF-8","gb2312", $s0);
	$s2 = iconv("gb2312","UTF-8", $s1);
	if($s2 == $s0){$s = $s1;}else{$s = $s0;}
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if ($asc >= -20319 and $asc <= -20284) return "a";
	if ($asc >= -20283 and $asc <= -19776) return "b";
	if ($asc >= -19775 and $asc <= -19219) return "c";
	if ($asc >= -19218 and $asc <= -18711) return "d";
	if ($asc >= -18710 and $asc <= -18527) return "e";
	if ($asc >= -18526 and $asc <= -18240) return "f";
	if ($asc >= -18239 and $asc <= -17923) return "g";
	if ($asc >= -17922 and $asc <= -17418) return "h";
	if ($asc >= -17417 and $asc <= -16475) return "j";
	if ($asc >= -16474 and $asc <= -16213) return "k";
	if ($asc >= -16212 and $asc <= -15641) return "l";
	if ($asc >= -15640 and $asc <= -15166) return "m";
	if ($asc >= -15165 and $asc <= -14923) return "n";
	if ($asc >= -14922 and $asc <= -14915) return "o";
	if ($asc >= -14914 and $asc <= -14631) return "p";
	if ($asc >= -14630 and $asc <= -14150) return "q";
	if ($asc >= -14149 and $asc <= -14091) return "r";
	if ($asc >= -14090 and $asc <= -13319) return "s";
	if ($asc >= -13318 and $asc <= -12839) return "t";
	if ($asc >= -12838 and $asc <= -12557) return "w";
	if ($asc >= -12556 and $asc <= -11848) return "x";
	if ($asc >= -11847 and $asc <= -11056) return "y";
	if ($asc >= -11055 and $asc <= -10247) return "z";
	return null;
}
