<?php
/**
 * 导入 腾讯 接口到 通用接口数据库(综艺)
 */
header ( 'Content-type: text/html; charset=utf-8' );

define ( 'PHPCMS_PATH', dirname ( __FILE__ ) . '/../' );
define ( 'RUN_IN_CMD', true );

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php';

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php';

yzy_sooner_db ();

global $db;

//  http://tv.video.qq.com/fcgi-bin/dlib/dout_tv?auto_id=4&platform=10&version=10005

$year = array('2014');
foreach($year as $vy){
	$f1 = 'http://tv.video.qq.com/fcgi-bin/dlib/dout_tv?auto_id=17&platform=10&version=10005&itype=-1&iarea=-1&sort=1&pagesize=20&iyear='.$vy.'&page=0';
	echo $f1.'\n';
	$sxmln = afget ( $f1 );
	$sxmln = simplexml_load_string ( $sxmln );
	$sxmln = json_decode ( json_encode ( $sxmln ), true );
	//获取循环的页数
	$num = $sxmln['total']/$sxmln['psize'];
	for($i=738;$i<=$num;$i++){
		$f1 = 'http://tv.video.qq.com/fcgi-bin/dlib/dout_tv?auto_id=17&platform=10&version=10005&itype=-1&iarea=-1&sort=1&pagesize=20&iyear='.$vy.'&page='.$i;
		echo $f1.'\n';
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
	$go3cdb = yzy_go3c_db() ;
	//先判断是否已经采集过
	$tmp = $phpcmsdb->r1('v9_video',array('title'=>$v['c_title'],'column_id'=>'3','ispackage'=>'1'),'*') ;
	if(!empty($tmp)){   //视频已入库则更新tag等数据
		if($v['c_subtype']) $c_tag = implode(",",$v['c_subtype']);
		if($v['c_actor']) $actor = implode(",",$v['c_actor']);
		$rating = $v['c_score'];
		$year = $v['c_year'] ;
		$free = $v['c_pay_status'];
		$area = $v['c_area'];
		if($free=='8'){
			$free = '0';
		}else{
			$free = '1';
		}
		if($area=='内地'){
			$area = '1';
		}elseif($area=='香港'||$area=='台湾'){
			$area = '2';
		}elseif($area=='日本'){
			$area = '6';
		}elseif($area=='韩国'){
			$area = '5';
		}elseif($area=='美国'){
			$area = '3';
		}elseif($area=='欧洲'){
			$area = '4';
		}else{
			$area = '0';
		}
		//更新视频内容
		$up_data = array(
			'rating'		=> $rating,	
			'tag'			=> $c_tag,	
			'year_released' => $year,	
			'is_free'	    => $free,
			'actor'			=> $actor,
			'area_id'       => $area
		);
		$phpcmsdb->w('v9_video',$up_data,array('id'=>$tmp['id'])) ;

		//查询视频是否已经上线
		$tgo3c = $go3cdb->r1('video',array('vid'=>$tmp['asset_id']),'*') ;
		if(!empty($tgo3c)){
			$dg = array(
				'is_free'  =>  $free,
				'area_id'  =>  $area,
				'year_released' =>  $year
					);
			$go3cdb->w('video',$dg,array('vid'=>$tmp['asset_id'])) ;
			//更新视频的评分
			$go3cdb->d('rating',array('vid'=>$tmp['asset_id'])) ;
			//插入评分
			$dd_rating = array(
					'user_id' 	  => 'system',
					'vid' 		  => $tmp['asset_id'],
					'rating'	  => $rating,
					'rating_time' => date('Y-m-d H:i:s', time()),
				);
			$go3cdb->w('rating',$dd_rating) ;
			//更新tag
			$go3cdb->d('video_tags',array('vid'=>$tmp['asset_id'])) ;
			$arr = explode(",",$c_tag);
			foreach($arr as $vv){
				$taggo3c = $go3cdb->r1('tag',array('tag_name'=>$vv)) ;
				if(empty($taggo3c)){
					$tag_id = '3';
				}else{
					$tag_id = $taggo3c['tag_id'];
				}
				$tdd = array(
						'vid'=>$tmp['asset_id'],
						'tag_id'=>$tag_id,
						'seq_number'=>'1'
				) ;
				$go3cdb->w('video_tags',$tdd) ;
			}
			//更新演员
			$actor = explode(',',$actor) ;
			$aactors = $go3cdb->r('actor',array('name'=>$actor),'id,name',array('key_value'=>'name,id')) ;
			//先清空
			$go3cdb->d('video_actors',array('vid'=>$tmp['asset_id'])) ;
			$i = 1 ;
			foreach($actor as $iv){
				if(!isset($aactors[$iv])){
					$tagd = array() ;
					$tagd['name'] = $iv ;
					$aactors[$iv] = $go3cdb->w('actor',$tagd) ;
				}
				$tdd = array() ;
				$tdd['vid'] = $tmp['asset_id'] ;
				$tdd['actor_id'] = $aactors[$iv] ;
				$tdd['seq_number'] = $i ;
				$go3cdb->w('video_actors',$tdd) ;
				$i++ ;
			}
			if($tmp['asset_id']=='5kle3eck676klug'){
				echo $tmp['asset_id'].'\n';
			}else{
				echo $tmp['asset_id'].'\n';
			}
		}
	}
}
