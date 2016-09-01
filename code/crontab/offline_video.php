<?php
/**
 * 定时自动运行下线视频脚本
 */
header ( 'Content-type: text/html; charset=utf-8' );
define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';
yzy_shop_db ();
global $db;

$phpcmsdb = yzy_phpcms_db() ;
$go3cdb = yzy_go3c_db() ;

$sql1="select * from issue_report where isoff='1' and `issue_type` LIKE '%播放故障%'";
$chand= $go3cdb->rs($sql1) ;
foreach($chand as $v){
	$arr = explode("|",$v['description']);
	$vid= $arr['1'];
	$url= $arr['3'];
	$id = $v['id'];
	echo $vid."\n";
	$rvideo = $go3cdb->r1('video',array('vid'=>$vid)) ;
	if(empty($rvideo)){ //视频不在线
		echo time().'|'.$vid."\n";
	}else{		//视频在线
		echo time().'|'.$vid."\n";
		$sql2="select * from video_play_info where vid='$vid'";
		$play_info= $go3cdb->rs($sql2) ;
		$num = count($play_info);
		if($num>1){ //多链接删除链接
			$go3cdb->d('video_play_info',array('vid'=>$vid,'play_url'=>$url)) ;
			//修改状态
			$go3cdb->w('issue_report',array('isoff'=>2), array('id'=>$id)) ;
		}else{  //单链接下线视频
			$go3cdb->d('video',array('vid'=>$vid)) ;
			$go3cdb->d('video_image',array('vid'=>$vid)) ;
			$go3cdb->d('video_play_info',array('vid'=>$vid)) ;
			$go3cdb->d('video_play_control',array('vid'=>$vid)) ;
			$go3cdb->d('video_tags',array('vid'=>$vid)) ;
			$go3cdb->d('video_actors',array('vid'=>$vid)) ;
			$go3cdb->d('bookmark',array('vid'=>$vid)) ;
			$go3cdb->d('view_history',array('vid'=>$vid)) ;
			$go3cdb->d('video_subtitle',array('vid'=>$vid)) ;
			$go3cdb->d('video_area_mapping',array('vid'=>$vid)) ;
			if($rvideo['column_id']=='5'||$rvideo['column_id']=='6'){
				$go3cdb->d('video_short_list',array('vid'=>$vid)) ;
			}else{//分集
				$go3cdb->d('all_episode_list',array('vid'=>$vid)) ;
			}
			//修改状态
			$go3cdb->w('issue_report',array('isoff'=>2), array('id'=>$id)) ;
			//删除了分集检查该总集下还在线的分集数
			$fjvideo = $go3cdb->r1('video',array('parent_id'=>$rvideo['parent_id'])) ;
			if(empty($fjvideo)){  //没有分集则下线相应的总集
				$go3cdb->d('video',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_image',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_play_info',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_play_control',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_tags',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_actors',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('bookmark',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('view_history',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_subtitle',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_area_mapping',array('vid'=>$rvideo['parent_id'])) ;
				$go3cdb->d('video_short_list',array('vid'=>$rvideo['parent_id'])) ;
				$phpcmsdb->w('v9_video',array('published'=>0,'offline_status'=>1,'online_status'=>2), array('asset_id'=>$rvideo['parent_id'])) ;
			}else{
				echo 'next one ....';
			}
			$phpcmsdb->w('v9_video',array('published'=>0,'offline_status'=>1,'online_status'=>2), array('asset_id'=>$vid));
		}
		$url2='http://www.go3c.tv:8062/go3cci/cache.api?m=clear';
		$tmp2 = file_get_contents($url2);
	}
echo "clear one!...";
}


