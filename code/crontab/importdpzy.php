<?php
/**
 * 导入 hdp 接口到 通用接口数据库(综艺自动导入)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_sooner_db ();

global $db;

$f1 = 'http://tv4.hdpfans.com/~rss.get.category/site/hdp/channel/zy/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
$sxmly = afget ( $f1 );
$sxmly = simplexml_load_string ( $sxmly );
$sxmly = json_decode ( json_encode ( $sxmly ), true );

foreach ($sxmly['foobar'] as $yu){
	$sxml = afget ( $yu['url'] );
	$sxml = simplexml_load_string ( $sxml );
	$sxml = json_decode ( json_encode ( $sxml ), true );
	
	//获取要采集多少页(10页前面最热/最新的综艺)
	$page_count = $sxml['INFO']['PAGECOUNT'] ;
	
	for($p=265;$p>=250;$p--){
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
		$tmp = $phpcmsdb->r1('v9_video',array('asset_id'=>$v['id'],'title'=>$v['title']),'*') ;
		if(isset($tmp['id'])){
			$v['id'] = $tmp['id'] ;
		}
		//有重复的，那么增加tag
		if(isset($tmp['id'])){
			//continue ;
		}
		doa($v) ;
	}
}

//专门采集一个url资源
function doa($a){
	var_dump($a);
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
		
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
	if(substr($vi['director'],-1,1) == ',') $vi['director'] = substr($vi['director'],0,-1) ;
	if(substr($vi['actor'],-1,1) == ',') $vi['actor'] = substr($vi['actor'],0,-1) ;
	
	$vi['title'] = substr($sxml['name'], 0, -6);
	$vi['asset_id'] = $a['id'] ;
	$count = count($sxml['url']['foobar']) ;    //获取分集的集数
	$ispackage = 0 ;
	if(!empty($count)) $ispackage = 1 ;
	//处理tag为空
	$tag_arr=array('时尚','财经','文艺 ','娱乐','访谈','新闻','生活','综艺');
	$rand_keys = array_rand($tag_arr, 2);
	$tags = $tag_arr[$rand_keys[0]];
	//总集数据导入
	$d = array(
			'title'   	    => $vi['title'],
			'asset_id'      => $vi['asset_id'],
			'director'      => empty($vi['directors'])?'':$vi['directors'],
			'actor' 	    => empty($vi['actors'])?'':$vi['actors'],
			'short_name'	=> $vi['title'],
			'tag'   	    => $tags ,
			'year_released' => '' ,
			'run_time'      => '3000',
			'column_id' 	=> '3',
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
			'surl' 			=> $a['url'] ,
			'spid'			=> 'ddssp'
	);
	if($ispackage){
		$d['total_episodes'] = $count ;
		$d['latest_episode_num'] = $count ;
	}
	$thisv = $phpcmsdb->r1('v9_video',array('asset_id'=>$vi['asset_id'],'column_id'=>'3'),'id,published,online_status') ;
	$thisd = $phpcmsdb->r1('v9_video',array('title'=>$vi['title'],'column_id'=>'3'),'id,published,online_status') ;
	if((isset($thisv['published']) && strcmp($thisv['published'] ,'1') == 0)){
		//已经更新过了
		//return ;
	}
	
	$dd = array(
			'short_desc' => $vi['title'],
			'long_desc'  => $vi['description']
	);
	$thisv_id = 0 ;
	if(isset($thisv['id'])||isset($thisd['id'])){
		if(isset($thisv['id'])){
			if(in_array($thisv['online_status'],array('3','0','1','99'))){
				//3 编辑未通过，0或1 导入，99 错误  这三种状态的数据会在重新导入后被覆盖
				$thisv_id = $thisv['id'] ;
				$d['online_status'] = '1';
				$phpcmsdb->w('v9_video',$d,array('id'=>$thisv_id)) ;
				$phpcmsdb->w('v9_video_data',$dd,array('id'=>$thisv_id)) ;
			}
		}
		if(isset($thisd['id'])){
			if(in_array($thisd['online_status'],array('3','0','1','99'))){
				//3 编辑未通过，0或1 导入，99 错误  这三种状态的数据会在重新导入后被覆盖
				$thisv_id = $thisd['id'] ;
				$d['online_status'] = '1';
				$phpcmsdb->w('v9_video',$d,array('id'=>$thisv_id)) ;
				$phpcmsdb->w('v9_video_data',$dd,array('id'=>$thisv_id)) ;
			}
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
		if(!strpos($a['img'],'.')) return ;
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
	//主视频的数据已经导入完毕
	/*
	 * 视频自动上线
	*/
	//查询总集的信息,并上线
	//$video = $phpcmsdb->r1('v9_video',array('asset_id'=>$vi['asset_id']),'*') ;
	//importgo3c($video);
	//开始导入分集
	$i = 0;
	if(count($sxml['url']['foobar'])>2){
		foreach($sxml['url']['foobar'] as $v){
			sleep(1);
			importall($v,$vi);
			$i++;
			echo 'fj:'.$i.'\n';
		}
	}else{
		$v = array();
		$v['name'] = $sxml['url']['foobar']['name'];
		$v['url'] = $sxml['url']['foobar']['url'];
		var_dump(222);		
		importall($v,$vi);
	}
	/*
	//数据导完,检查是否有分集在线,没有分集则下线已上线的总集数据
	$gov = $go3cdb->r1('video',array('parent_id'=>$vi['asset_id']),'vid') ;
	if(empty($gov)){
		$phpcmsdb->w('v9_video',array('published'=>0,'offline_status'=>1,'online_status'=>2), array('asset_id'=>$vi['asset_id'])) ;
	
		$dwhere = array('vid'=>$vi['asset_id']) ;
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
	*/
	exit;
}

//获取单个分集的数据
function importall($v,$vi){
	$phpcmsdb = yzy_phpcms_db() ;
	$go3cdb = yzy_go3c_db() ;
	vplog("fjurl is {$v['url']}") ;
	$s = afget($v['url']) ;
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;
	$vid = 'hdp'.substr(md5($v['url']),0,15);
	$arrt=explode('|',$v['name']);
	preg_match_all('/\d+/is',$arrt['0'],$arr);
	$episode_number = $arr['0']['0'].$arr['0']['1'].$arr['0']['2'];
	$d = array(
			'title'   	    => $vi['title'].$arrt['0'], 
			'asset_id'      => $vid, 
			'director'      => empty($vi['directors'])?'':$vi['directors'],
			'actor' 	    => empty($vi['actors'])?'':$vi['actors'],
			'short_name'	=> $vi['title'].$arrt['0'],
			'tag'   	    => '' , 
			'year_released' => '' ,
			'run_time'      => '3000',
			'column_id' 	=> '4',
			'active' 	 	=> '1',
			'area_id' 	 	=> '1',
			'is_free' 	    => '1',
			'total_episodes'=> '' ,
			'parent_id' 	=> $vi['asset_id'],
			'episode_number' => $episode_number ,
			'latest_episode_num' => '' ,
			'rating'      	=> '6' ,
			'ispackage'   	=> '0' ,
			'catid'   	 	=> '54', 
			'status'     	=> '99',
			'sysadd'     	=> '1',
			'username'   	=> 'system' ,
			'updatetime' 	=> time(),
			'surl' 			=> $arrt['1'] ,
			'spid'			=> 'ddssp'
		);
	$thisv = $phpcmsdb->r1('v9_video',array('asset_id'=>$vid),'id,published,online_status','id') ;
	if(isset($thisv['published']) && strcmp($thisv['published'] ,'1') == 0){
		//已经更新过了
		return ;
	}
	$dd = array(
			'short_desc' => $vi['title'],
			'long_desc'  => '' ,
	);
	$thisv_id = 0 ;
	if(!empty($thisv['id'])){
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
	//视频链接入库
	if(!empty($sxml['m3u8'])){
		$url = $sxml['m3u8'];
	}else{
		$url = $sxml['url']['foobar'];
	}
	if(empty($url)) return ;
	$sourceurl = $v['url'];
	$source_id = 10;
	if((strpos($url,'xunlei')!== false)||(strpos($url,'http://.flv')!== false)||(strpos($url,'pptv.vod')!== false)||(strpos($url,'m1905')!== false)) return ;
	if((strpos($url,'g3.letv.com')!== false)||(strpos($url,'g3.letv.cn')!== false)) $source_id=27 ;
	if((strpos($url,'video.qiyi')!== false)) $source_id=17 ;
	if((strpos($url,'youku')!== false)) $source_id=14 ;
	if((strpos($url,'sohu')!== false)) $source_id=19 ;
	if((strpos($url,'qq')!== false)) $source_id=21 ;
	$cd = array(
			'title'   	=> $vi['title'].$arrt['0'], 
			'asset_id'  => $vid,
			'path'   	=> $url ,
			'clarity'   => '3',
			'catid'   	=> '64',
			'status'    => '99',
			'sysadd'    => '1',
			'username'  => 'system' ,
			'updatetime'=> time(),
			'source_id' => $source_id ,
			'sourceurl' => $sourceurl
	);
	$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$vid,'path'=>$url,'source_id'=>$source_id),'id') ;
	if(!empty($tmp['id'])) {
		$phpcmsdb->w('v9_video_content',$cd,array('id'=>$tmp['id'])) ;
	}else{
		$cd['inputtime'] = time() ;
		$video_content_id = $phpcmsdb->w('v9_video_content',$cd) ;
		$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
	}
	//插入区域内容
	$phpcmsdb->d('v9_video_area',array('asset_id'=>$vid)) ;
	$dare = array(
			'asset_id' => $vid,
			'area_id' => '0',
			'isextend' => '0'
	);
	$phpcmsdb->w('v9_video_area',$dare) ;
	
	//综艺视屏的分集自动上线
	//$video = $phpcmsdb->r1('v9_video',array('asset_id'=>$vid),'*') ;
	//importgo3c($video);
	//检查分集是否有链接,没有则删除
	
}

//综艺节目上线数据程序
function importgo3c($video){
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
		if($video['column_id'] == '3'){
			//针对电视栏目更新总集的total_episodes
			$total_episodes_sql  = "SELECT COUNT(*)  FROM video WHERE 1 and parent_id = '$video[parent_id]'";
			$total_episodes 	 = $go3cdb->rs($total_episodes_sql) ;
		
			$d['total_episodes'] = $total_episodes[0]?($total_episodes[0]['COUNT(*)'] ? (1+$total_episodes[0]['COUNT(*)']) : 0):0;
			//修改本地phpcms的数据
			$phpcmsdb->w('v9_video',array('total_episodes'=>$d['total_episodes']), array('asset_id'=>$video['parent_id'])) ;
			$go3cdb->w('video',array('total_episodes'=>$d['total_episodes']), array('vid'=>$video['parent_id'])) ;
		}
		$d['parent_id'] = $video['parent_id'];
	}
	if($video['episode_number']){
		$d['episode_number'] =  $video['episode_number'];
	}
	$go3cdb->w('video',$d) ;
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
	$phpcmsdb->w('v9_video',array('published'=>1), array('id'=>$video['id'])) ;
}
