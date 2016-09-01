<?php
/**
 * 合并电视剧
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

global $db;
$vodstb = yzy_vodstb_db() ;
$phpcmsdb = yzy_phpcms_db() ;
$go3cdb = yzy_go3c_db() ;

$sql1  = "select * from v9_video where id>=190452 and column_id='4' and ispackage='1' and active='3'";
$vod_stb = $vodstb->rs($sql1) ;
foreach($vod_stb as $v){
	//总集数据导入
	$d = array(
			'title'   	    => $v['title'],
			'asset_id'      => $v['asset_id'],
			'director'      => empty($v['directors'])?'':$v['directors'],
			'actor' 	    => empty($v['actors'])?'':$v['actors'],
			'short_name'	=> $v['title'],
			'tag'   	    => $v['tag'] ,
			'year_released' => 2014 ,
			'run_time'      => '3600',
			'column_id' 	=> '4',
			'active' 	 	=> '1',
			'area_id' 	 	=> $v['area_id'],
			'is_free' 	    => '1',
			'total_episodes'=> $v['total_episodes'],
			'parent_id' 	=> $v['parent_id'],
			'episode_number' => $v['episode_number'],
			'latest_episode_num' => $v['latest_episode_num'] ,
			'rating'      	=> $v['rating'] ,
			'ispackage'   	=> $v['ispackage'] ,
			'catid'   	 	=> '54',
			'status'     	=> '99',
			'sysadd'     	=> '1',
			'username'   	=> 'system' ,
			'updatetime' 	=> time() ,
			'spid'			=> 'ddssp',
			'surl'			=> $v['surl']
	);
	$thisd = $phpcmsdb->r1('v9_video',array('title'=>$v['title'],'column_id'=>'4'),'id') ;
	if(!empty($thisd)){ //存在则退出
		echo $v['asset_id'].'\n';
		continue;
	}else{  //不存在则写入数据库
		$videod = $vodstb->r1('v9_video_data',array('id'=>$v['id']),'*') ;
		$dd = array(
			'short_desc' => $videod['short_desc'],
			'long_desc'  => $videod['long_desc']
		);
		echo "2";
		//新增吧...
		$d['inputtime'] = time() ;
		$sql3="";
		$thisv_id =  $phpcmsdb->w('v9_video',$d) ;
		$dd['id'] = $thisv_id ;
		$phpcmsdb->w('v9_video_data',$dd) ;
	}
	//视频图片入库
	$poster = $vodstb->r('v9_video_poster',array('asset_id'=>$v['asset_id']),'*') ;
	if(!empty($poster)){
		foreach($poster as $vp){
			$cd = array(
				'title'   	=> $vp['title'],
				'asset_id'  => $vp['asset_id'],
				'path'   	=> $vp['path'] ,
				'type'   	=> $vp['type'] ,
				'catid'   	=> $vp['catid'],
				'status'    => $vp['status'],
				'sysadd'    => $vp['sysadd'],
				'username'  => $vp['username'] ,
				'updatetime'=>$vp['updatetime'],
				'size'		=> $vp['size'],
				'format'	=> $vp['format']
			);
			echo "3";
			$cd['inputtime'] = time() ;
			$tmpid = $phpcmsdb->w('v9_video_poster',$cd) ;
			$phpcmsdb->w('v9_video_poster_data',array('id'=>$tmpid)) ;
		}
	}
	//插入区域内容
	$dare = array(
			'asset_id' => $v['asset_id'],
			'area_id' => '0',
			'isextend' => '0'
	);
	$phpcmsdb->w('v9_video_area',$dare) ;
	echo $v['asset_id'].'\n';
	//查询总集视频的信息,并上线
	$video = $phpcmsdb->r1('v9_video',array('asset_id'=>$v['asset_id']),'*') ;
	importgo3c($video);   //上线数据
	echo "b";
	//复制电视剧的分集到phpcms
	$fjvideo = $vodstb->r('v9_video',array('parent_id'=>$v['asset_id']),'*') ;
	//var_dump($fjvideo);
	foreach($fjvideo as $vf){
		echo $vf['asset_id'].'\n';
		importfj($vf);   //分集函数
	}
	
	//数据导完,检查是否有分集在线,没有分集则下线已上线的总集数据
	$gov = $go3cdb->r1('video',array('parent_id'=>$video['asset_id']),'vid') ;
	if(empty($gov)){
		$phpcmsdb->w('v9_video',array('published'=>0,'offline_status'=>1,'online_status'=>2), array('id'=>$video['id'])) ;
		
		$dwhere = array('vid'=>$video['asset_id']) ;
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
	$vodstb->w('v9_video',array('active'=>0),array('id'=>$v['id'])) ;
}
//分集函数
function importfj($vf){
	global $db ;
	$vodstb = yzy_vodstb_db() ;
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	$d = array(
			'title'   	    => $vf['title'], 
			'asset_id'      => $vf['asset_id'], 
			'director'      => $vf['director'],
			'actor' 	    => $vf['actor'],
			'short_name'	=> $vf['short_name'],
			'tag'   	    => $vf['tag'] , 
			'year_released' => $vf['year_released'],
			'run_time'      => $vf['run_time'],
			'column_id' 	=> '4',
			'active' 	 	=> '1',
			'area_id' 	 	=> $vf['area_id'],
			'is_free' 	    => '1',
			'total_episodes'=> $vf['total_episodes'] ,
			'parent_id' 	=> $vf['parent_id'],
			'episode_number' => $vf['episode_number'] ,
			'latest_episode_num' => $vf['latest_episode_num'] ,
			'rating'      	=> $vf['rating'] ,
			'ispackage'   	=> '0' ,
			'catid'   	 	=> '54', 
			'status'     	=> '99',
			'sysadd'     	=> '1',
			'username'   	=> 'system' ,
			'updatetime' 	=> $vf['updatetime'],
			'spid'		=> 'ddssp'
		);
		//新增吧...
		$d['inputtime'] = time() ;
		$thisv_id =  $phpcmsdb->w('v9_video',$d) ;
		$dd['id'] = $thisv_id ;
		$phpcmsdb->w('v9_video_data',$dd) ;
	//链接入库
	$conten = $vodstb->r('v9_video_content',array('asset_id'=>$vf['asset_id']),'*') ;
	if(!empty($conten)){
		foreach($conten as $vc){
			$cd = array(
				'title'   	=> $vc['title'] ,
				'asset_id'  => $vc['asset_id'],
				'path'   	=> $vc['path'] ,
				'clarity'   => $vc['clarity'],
				'catid'   	=> $vc['catid'],
				'status'    => $vc['status'],
				'sysadd'    => $vc['sysadd'],
				'username'  => $vc['username'] ,
				'updatetime'=> $vc['updatetime'],
				'source_id' => $vc['source_id'] ,
				'sourceurl' => $vc['sourceurl']
			);
			$cd['inputtime'] = time() ;
			$video_content_id = $phpcmsdb->w('v9_video_content',$cd) ;
			$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
		}
	}
	//插入区域内容
	$dare = array(
			'asset_id' => $vf['asset_id'],
			'area_id' => '0',
			'isextend' => '0'
	);
	$phpcmsdb->w('v9_video_area',$dare) ;
	$video = $phpcmsdb->r1('v9_video',array('asset_id'=>$vf['asset_id']),'*') ;
	importgo3c($video);   //上线数据
	echo "xx";
}
//上线数据程序
function importgo3c($video){
	global $db ;
	$vodstb = yzy_vodstb_db() ;
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	$play_count = rand(50, 1000);
	$rd = $phpcmsdb->r1('v9_video_data',array('id'=>$video['id'])) ;
	$d = array(
		'vid' =>   $video['asset_id'],
		'name' =>  $video['title'],
		'uploader' =>  trim($video['uploader']),
		'column_id' => intval($video['column_id']),
		'short_desc' => $rd['long_desc'],
		'long_desc' => $rd['long_desc'],
		'active' => $video['active'],
		'time_created'=> date('Y-m-d H:i:s',$video['inputtime']),
		'time_updated'=> date('Y-m-d H:i:s',time()),
		'created_by' => 'root',
		'play_count' => $play_count,
		'area_id' => $video['area_id'],
		'director' =>  $video['director'],
		'is_free'  =>  $video['is_free'],
		'run_time' =>  $video['run_time'],
		'year_released' => $video['year_released'],
		'spid'	=> $video['spid'],
		'channel_id' =>$video['channel'],
		'shared'	=> $video['shared']
	);
	echo "4";
	if($video['parent_id']){
		$latest_episode_number_sql  = "SELECT MAX(episode_number) AS episode_number,name,MIN(episode_number) as minepisode_number FROM video where 1 and parent_id = '$video[parent_id]'";
		$latest_episode_number 	    = $go3cdb->rs($latest_episode_number_sql) ;
		if(!empty($latest_episode_number)){
			foreach($latest_episode_number as $v){
				$d['latest_episode_number'] = $v['episode_number'];
				$d['oldest_episode_number'] = $v['minepisode_number'];
			}
		}else{
			$d['latest_episode_number'] = 0;
			$d['oldest_episode_number'] = 0;
		}
		if($video['episode_number']){
			$d['latest_episode_number'] = max(intval($video['episode_number']),intval($d['latest_episode_number']));
		}
		//修改本地phpcms的数据
		$phpcmsdb->w('v9_video',array('latest_episode_number'=>$d['latest_episode_number']), array('asset_id'=>$video['parent_id'])) ;
		$go3cdb->w('video',array('latest_episode_number'=>$d['latest_episode_number'],'oldest_episode_number'=>$d['oldest_episode_number']), array('vid'=>$video['parent_id'])) ;
		
		$d['parent_id'] = $video['parent_id'];
	}
	if($video['episode_number']){
		$d['episode_number'] =  $video['episode_number'];
	}
	$go3cdb->w('video',$d) ;
	echo "5";
	//更新最新集数video_episode_source
	$sc = $phpcmsdb->r('v9_video_content',array('asset_id'=>$video['asset_id'])) ;
		foreach ($sc as $v){
			$source_id = $v['source_id'];
			$episode = $go3cdb->r1('video_episode_source',array('vid'=>$video['parent_id'],'source'=>$source_id)) ;
			if(!empty($episode)){
				$total_episodes =$episode['total_episodes']+1;
				$min_num = $episode['min_num']>$video['episode_number']?$video['episode_number']:$episode['min_num'];
				$max_num = $episode['max_num']>$video['episode_number']?$episode['max_num']:$video['episode_number'];
				$ar_ep = array(
					'total_episodes'=>$total_episodes,
					'min_num'=>$min_num,
					'max_num'=>$max_num
				);
				$go3cdb->w('video_episode_source',$ar_ep,array('vid'=>$video['parent_id'],'source'=>$source_id)) ;
			}else{
				$ar_ep = array(
					'total_episodes'=>1,
					'min_num'=>$video['episode_number'],
					'max_num'=>$video['episode_number'],
					'source'=>$source_id,
					'vid' =>$video['parent_id']
				);
				$go3cdb->w('video_episode_source',$ar_ep) ;
			}
		}
		echo "6";
	//增加插入评分
	$rating = intval($video['rating']) ? intval($video['rating']) : rand(2,8);
	$dd_rating = array(
			'user_id' 	  => 'system',
			'vid' 		  => $video['asset_id'],
			'rating'	  => $rating,
			'rating_time' => date('Y-m-d H:i:s', time()),
	);
	$go3cdb->w('rating',$dd_rating) ;
	//插入视频支持的区域
	$go3cdb->d('video_area_mapping',array('vid'=>$video['asset_id'])) ;
	$sar = array(
		'vid' => $video['asset_id'],
		'area_id' => '0',
		'isextend' => '0'
	);
	$go3cdb->w('video_area_mapping',$sar);
	//视频支持终端控制STB 先清理，再添加
	$go3cdb->d('video_play_control',array('vid'=>$video['asset_id'])) ;
	for ($i=1;$i<5;$i++){
		$STB = array(
				'vid' => $video['asset_id'],
				'term_id' => $i,
				'allow_play' => '1',
				'parent_id' => $video['parent_id']
		);
		$go3cdb->w('video_play_control',$STB) ;
	}
	echo "7";
	//增加tag
	$r['tag'] = str_replace('，',',',$video['tag']) ;
	$tags = explode(',',$r['tag']) ;
	$atags = $go3cdb->r('tag',array('tag_name'=>$tags),'tag_id,tag_name',array('key_value'=>'tag_name,tag_id')) ;
	//先清空
	$go3cdb->d('video_tags',array('vid'=>$video['asset_id'])) ;
	$i = 1 ;
	foreach($tags as $iv){
		if(!isset($atags[$iv])){
			$tagd = array() ;
			$tagd['tag_name'] = $iv ;
			$tagd['type'] = 2 ;
			$atags[$iv] = $go3cdb->w('tag',$tagd) ;
		}
		$tdd = array() ;
		$tdd['vid'] = $video['asset_id'] ;
		$tdd['tag_id'] = $atags[$iv] ;
		$tdd['seq_number'] = $i ;
		$go3cdb->w('video_tags',$tdd) ;
		$i++ ;
	}
	//增加actor
	$r['actor'] = str_replace('，',',',$video['actor']) ;
	$actor = explode(',',$r['actor']) ;
	$aactors = $go3cdb->r('actor',array('name'=>$actor),'id,name',array('key_value'=>'name,id')) ;	
	//先清空
	$go3cdb->d('video_actors',array('vid'=>$video['asset_id'])) ;
	$i = 1 ;
	foreach($actor as $iv){
		if(!isset($aactors[$iv])){
			$tagd = array() ;
			$tagd['name'] = $iv ;
			$aactors[$iv] = $go3cdb->w('actor',$tagd) ;
		}
		$tdd = array() ;
		$tdd['vid'] = $video['asset_id'] ;
		$tdd['actor_id'] = $aactors[$iv] ;
		$tdd['seq_number'] = $i ;	
		$go3cdb->w('video_actors',$tdd) ;	
		$i++ ;
	}
	echo "8";
	//上线图片
	if($video['parent_id']){
		$vvpi = $go3cdb->r('video_image',array('vid'=>$video['parent_id'])) ;
		foreach($vvpi as $vi){
			$vi['vid']=$video['asset_id'];
			$go3cdb->w('video_image',$vi);
		}
		$tt = array(
				'time_created'=> date('Y-m-d H:i:s',time()),
				'time_updated'=> date('Y-m-d H:i:s',time())
		);
		$go3cdb->w('video',$tt,array('vid'=>$video['parent_id'])) ;
		//上线链接(分集播放链接)
		$rs = $phpcmsdb->r('v9_video_content',array('asset_id'=>$video['asset_id'])) ;
		if(empty($rs)){
			$phpcmsdb->w('v9_video',array('published'=>0,'offline_status'=>1,'online_status'=>2), array('asset_id'=>$video['asset_id'])) ;
		
			$dwhere = array('vid'=>$video['asset_id']) ;
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
		}else{
			foreach($rs as $iv){
				$dd = array(
					'vid' => $iv['asset_id'],
					'quality' => $iv['clarity'],
					'source' => empty($iv['source_id'])?'1':$iv['source_id'],
					'play_url' => $iv['path'],
					'aspect' => $iv['aspect'],
					'ratio' => ($iv['ratio']=='16:9')?'16:9':'4:3',
					'format' => $iv['videoformat'],
					'protocol' => $iv['videoprotocol']
				);
				$go3cdb->w('video_play_info',$dd) ;
			}
		}
	}else{
		$rs = $phpcmsdb->r('v9_video_poster',array('asset_id'=>$video['asset_id'])) ;
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
	echo "a";
	$phpcmsdb->w('v9_video',array('published'=>1), array('id'=>$video['id'])) ;
}

