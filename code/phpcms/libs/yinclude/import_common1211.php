<?php

if(!defined('PHPCMS_PATH')) die('define PHPCMS_PATH') ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;
yzy_sooner_db() ;

//imglib 
define('IMG_LIB_PATH',PHPCMS_PATH.'phpcms/libs/imglib/') ;

require_once IMG_LIB_PATH . 'HproseHttpClient.php' ;
require_once IMG_LIB_PATH . 'RpcCore.php' ;
require_once IMG_LIB_PATH . 'SynUpdateImageClient.php';

$getServerInstance = new HproseHttpClient(SYN_ROUTE_SERVER_ONE);//启用API路由
$imgClient = null;

if(is_object($getServerInstance))
{	
	global $imgClient;
	$imgClient =  new SynUpdateImage($getServerInstance);//更新
}

function get_img_url($path){
	
	global $getServerInstance,$imgClient,$err_info;

	if(is_object($imgClient) && !empty($path))
	{
		$imgUrl = "http://111.208.56.207/poster".$path;
		$imgClient->runSynUpdateImg($imgUrl);//更新方法调用

		return "http://image.bigtv.com.cn/images/poster".$path;
		//return $imgUrl;
	}else{
		return "error path";	//路径不合法
	}

}

function is_Date1($str,$format="Ymd"){
    if(strstr($str,"-")) return 0;
    $unixTime_1 = strtotime($str);
    if ( !is_numeric($unixTime_1) ) return 0;
    $checkDate = date($format, $unixTime_1);
    $unixTime_2 = strtotime($checkDate);;
    if($unixTime_1 == $unixTime_2){
        return 1;
    }else{
        return 0;
    }
}

function is_Date2($str,$format="Y-m-d"){
    $unixTime_1 = strtotime($str);
    if ( !is_numeric($unixTime_1) ) return 0;
    $checkDate = date($format, $unixTime_1);
    $unixTime_2 = strtotime($checkDate);;
    if($unixTime_1 == $unixTime_2){
        return 1;
    }else{
        return 0;
    }
}

//导入频道子函数 sooner -> phpcms
function __bigtvm_import_doiscript_imchannel(){
	global $db ;
	global $imlog_id ;
	$db->r('go3c_channel',array('imlog_id'=>$imlog_id),'*',array('func_row'=>'__bigtvm_import_doiscript_imchannel_funcr')) ;
	
	return __bigtvm_import_doiscript_imchannel_funcr('recount') ;
}

function __bigtvm_import_doiscript_imchannel_funcr($r){
	static $scount ;
	if(!isset($scount)) $scount = array('total'=>0,'upsum'=>0) ;
	if($r == 'recount') return $scount ;
	$phpcmsdb = yzy_phpcms_db() ;

	$thisc = $phpcmsdb->r1('v9_channel',array('channel_id'=>$r['id']),'id,published,online_status') ;
	//logError($thisc);
	if(isset($thisc['published']) && strcmp($thisc['published'] ,'1') == 0){
		return ;
	}

	$d = array(
		'channel_id' 	   => $r['id'],
		'title' 	 	   => $r['text'],
		'img' 	  	  	   => $r['img'],
		'imgpath' 	  	   => $r['imgpath'],
		'channel_category' => $r['channel_category'],
		'iswidth' 	  	   => $r['iswidth'],
		'uuidPC_HIGH' 	   => get_live_url('PC_HIGH', $r['uuidPC_HIGH']),
		'uuidIOS_HIGH'     => get_live_url('IOS_HIGH',$r['uuidIOS_HIGH']),
		'uuidSTB_HIGH' 	   => get_live_url('STB_HIGH',$r['uuidSTB_HIGH']),
		'uuidPC_MEDIUM'    => get_live_url('PC_MEDIUM',$r['uuidPC_MEDIUM']),
		'uuidIOS_MEDUIM'   => get_live_url('IOS_MEDUIM',$r['uuidIOS_MEDUIM']),
		'uuidSTB_MEDIUM'   => get_live_url('STB_MEDIUM',$r['uuidSTB_MEDIUM']),
		'updatetime'  	   => time(),
		'username'    	   => get_uname_by_sid(get_my_sid()) ,
		'catid'            => '63' ,
		'status'           => '99' ,
		'sysadd'           => '1' ,
		'published'	   => '0',
		'online_status'    => '1'
	) ;
	
	//先更新
	$tmp = null;
	$uuidPC_HIGH = get_live_url('PC_HIGH', $r['uuidPC_HIGH']);
	$uuidIOS_HIGH = get_live_url('IOS_HIGH',$r['uuidIOS_HIGH']);
	$uuidSTB_HIGH = get_live_url('STB_HIGH',$r['uuidSTB_HIGH']);
	$uuidPC_MEDIUM = get_live_url('PC_MEDIUM',$r['uuidPC_MEDIUM']);
	$uuidIOS_MEDUIM = get_live_url('IOS_MEDUIM',$r['uuidIOS_MEDUIM']);
	$uuidSTB_MEDIUM = get_live_url('STB_MEDIUM',$r['uuidSTB_MEDIUM']);
	$play = array('uuidPC_HIGH'=>$uuidPC_HIGH,'uuidIOS_HIGH'=>$uuidIOS_HIGH,'uuidSTB_HIGH'=>$uuidSTB_HIGH,'uuidPC_MEDIUM'=>$uuidPC_MEDIUM,'uuidIOS_MEDUIM'=>$uuidIOS_MEDUIM,'uuidSTB_MEDIUM'=>$uuidSTB_MEDIUM);
	if(isset($thisc['id'])){
		if(in_array($thisc['online_status'],array('3','0','1','99'))){    
			$tmp = $phpcmsdb->w('v9_channel',$d,array('channel_id'=>$d['channel_id'])) ;
		}
	}else{	
		$d['inputtime'] = time() ;
		$id = $phpcmsdb->w('v9_channel',$d) ;
		$tmp = $phpcmsdb->w('v9_channel_data',array('id'=>$id)) ;
	}	
	//写入直播链接
	$phpcmsdb->d('v9_channel_play_info',array('channel_id'=>$d['channel_id']));
		foreach ($play as $vp){
			$dplay = array(
				'channel_id'	=> $d['channel_id'],
				'aspect'	    => $r['iswidth'],
				'uuid'	    	=> $vp,
				'inputtime'	    => time()
			);
			$phpcmsdb->w('v9_channel_play_info',$dplay) ;
		}
	if($tmp) $scount['upsum']++ ;
	$scount['total']++ ;
}


//导入频道子函数 sooner -> phpcms
function __bigtvm_import_doiscript_imepg(){
	global $db ;
	global $imlog_id ;
	$db->r('go3c_program',array('imlog_id'=>$imlog_id),'*',array('func_row'=>'__bigtvm_import_doiscript_imepg_funcr')) ;
	
	return __bigtvm_import_doiscript_imepg_funcr('recount') ;
}

function __bigtvm_import_doiscript_imepg_funcr($r){
	static $scount ;
	if(!isset($scount)) $scount = array('total'=>0,'upsum'=>0) ;
	if($r == 'recount') return $scount ;
	
	
	$channel = __bigtvm_import_doiscript_imepg_get_channel($r['channel_id']) ;
	$d = array(
		'title'   	  => $channel['text'], 
		'epgid'  	  => $r['id'], 
		'channel_id'  => $r['channel_id'], 
		'systemdate'  => $r['systemdate'], 
		'text'   	  => $r['text'], 
		'starttime'   => $r['starttime'], 
		'endtime'     => $r['endtime'], 
		'uuidPC_HIGH' 	   => get_timeshift_url('PC', $r['uuidPC_HIGH']),
		//'uuidIOS_HIGH'     => get_timeshift_url('IOS',$r['uuidIOS_HIGH']),
		//'uuidSTB_HIGH' 	   => get_timeshift_url('STB',$r['uuidSTB_HIGH']),
		//'uuidPC_MEDIUM'    => get_timeshift_url('PC',$r['uuidPC_MEDIUM']),
		//'uuidIOS_MEDUIM'   => get_timeshift_url('IOS',$r['uuidIOS_MEDUIM']),
		//'uuidSTB_MEDIUM'   => get_timeshift_url('STB',$r['uuidSTB_MEDIUM']),
		'catid'   	  => '9', 
		'status'      => '99',
		'sysadd'      => '1',
		'username'    => get_uname_by_sid(get_my_sid()) ,
		'updatetime'  => time(),
	);
	
	
	//先更新
	$phpcmsdb = yzy_phpcms_db() ;
	$tmp = $phpcmsdb->w('v9_channelepg',$d,array('epgid'=>$d['epgid'])) ;
	
	
	//更新不成功，则新增
	if(!$tmp){
		$d['inputtime'] = time() ;
		$id = $phpcmsdb->w('v9_channelepg',$d) ;
		//$phpcmsdb->w('v9_channelepg_data',array('id'=>$id)) ;
	}
	
	if($tmp) $scount['upsum']++ ;
	$scount['total']++ ;
	
}

//获得频道信息
function __bigtvm_import_doiscript_imepg_get_channel($id){
	static $s ;
	if(!isset($s)) $s = array() ;
	if(isset($s[$id])) return $s[$id] ;
	
	global $db ;
	$s[$id] = $db->r1('go3c_channel',array('id'=>$id)) ;
	
	return $s[$id] ;
}


//导入频道子函数 sooner -> phpcms
function __bigtvm_import_doiscript_imasset(){
	global $db ;
	global $imlog_id ;
	$phpcmsdb = yzy_phpcms_db() ;
	//导入栏目分类等信息
	$rs = $db->r('go3c_categoryasset') ;
	foreach($rs as $cat){
		$catid = intval($cat['id']);
		$type = 0;
		if($catid>10000 && $catid<20000){
			$type = 1;
		}else if($catid>20000 && $catid<30000){
			$type = 2;
		}else if($catid>30000 && $catid<40000){
			$type = 3;
		}
		if($type > 0){
			$tmps = $phpcmsdb->r1('v9_tags',array('title'=>$cat['name'],'type'=>$type),'id,title');
			if(!isset($tmps['id'])){
				$d = array() ;
				$d['title'] = $cat['name'] ;
				$d['catid'] = '4' ;
				$d['status'] = '99' ;
				$d['sysadd'] = '1' ;
				$d['username'] = get_uname_by_sid(get_my_sid()) ;
				$d['inputtime'] = time() ;
				$d['updatetime'] = time() ;
				$d['type'] = $type ;
				$d['online_status'] = '1' ;
				$d['belong'] = '3,4,5,6' ;

				$k = $phpcmsdb->w('v9_tags',$d) ;
				$phpcmsdb->w('v9_tags_data',array('id'=>$k)) ;
			}
		}
	}


	$db->r('go3c_asset',array('imlog_id'=>$imlog_id),'*',array('func_row'=>'__bigtvm_import_doiscript_imasset_funcr')) ;
	
	return __bigtvm_import_doiscript_imasset_funcr('recount') ;
}

function __bigtvm_import_doiscript_imasset_funcr($r){
	static $scount ;
	if(!isset($scount)) $scount = array('as_total'=>0,'as_upsum'=>0,'tag_total'=>0,'tag_upsum'=>0) ;
	if($r == 'recount') return $scount ;
	
	global $spid;
	global $db ;
	$phpcmsdb = yzy_phpcms_db() ;
	
	$categoryasset_ids = $db->r('go3c_categoryasset_asset',array('asset_id'=>$r['id'])) ;
	
	static $cates ;
	static $ptags ;
	if(!isset($cates)){
		$cates = array() ;
		$ptags = array() ;
	}
/*	
	$ncateids = array() ;
	foreach($categoryasset_ids as $k=>$v){
		if(isset($cates[$v['categoryasset_id']])) continue ;
		$ncateids[] = $v['categoryasset_id'] ;
	}
	
	
	//对 分类进行整理
	if(count($ncateids) > 0){
		$rs = $db->r('go3c_categoryasset',array('id'=>$ncateids),'id,name',array('key_value'=>'id,name')) ;
		
		$pcates = array() ;
		$notindbc = array() ;
		foreach($rs as $k=>$v){
			$cates[$k] = $v ;
			$notindbc[$v] = $v ;
			$pcates[$v] = $k ;
		}
		
		
		$tmps = $phpcmsdb->r('v9_tags',array('title'=>$notindbc),'id,title',array('key_value'=>'id,title'));
		foreach($tmps as $k=>$v){
			unset($notindbc[$v]) ;
			$ptags[$v] = $k ;
		}

		
		foreach($notindbc as $v){
			$d = array() ;
			$d['title'] = $v ;
			$d['catid'] = '4' ;
			$d['status'] = '99' ;
			$d['sysadd'] = '1' ;
			$d['username'] = get_uname_by_sid(get_my_sid()) ;
			$d['inputtime'] = time() ;
			$d['updatetime'] = time() ;
			$d['type'] = $pcates[$v] ;
			
			
			$scount['tag_total']++ ;
	
			$k = $phpcmsdb->w('v9_tags',$d) ;
			$phpcmsdb->w('v9_tags_data',array('id'=>$k)) ;
	
			$ptags[$v] = $k ;
		}
	}
*/
	
	//这个是...
	$column_names  = array('电影','电视剧','电视栏目','乐酷');
	$column_id_mapping = array();
	foreach($column_names as $column){
		$field = $db->r1('go3c_categoryasset',array('name'=>$column),'id');
		$col = $phpcmsdb->r1('v9_column',array('title'=>$column),'id');
		if(!$field['id'] || !$col['id']){
		}else{
			$column_id_mapping[$field['id']]=$col['id'];
		}
	}
	
	$column_id = '0' ;
	$tags = array() ;
	foreach($categoryasset_ids as $v){
		$vid = $v['categoryasset_id'] ;
		if($vid > 40000 && $vid < 50000){
			$column_id = (array_key_exists($vid,$column_id_mapping)) ? $column_id_mapping[$vid]:'0';
			continue ;
		}else if($vid > 10000 && $vid < 20000){
			//地区忽略
			
			continue ;
		}else if($vid > 30000 && $vid < 40000){
			//年代忽略
			
			continue ;
		}
		
		
		$tags[] = $cates[$vid] ;
	}
	
	$tags = join(',',$tags) ;
	
	//没有栏目
	if(intval($column_id) < 1) return ;
	
	$d = array(
		'title'   	    => $r['name'], 
		'asset_id'      => $r['id'], 
		'director'      => $r['director'], 
		'actor' 	    => $r['actor'],
		'short_name'		=> $r['shortName'],
		'tag'   	    => $tags , 
		'year_released' => $r['year'],
		'run_time'      => intval($r['runTime'])?$r['runTime']:'60',
		'column_id' 	=> $column_id,
		'active' 	 	=> '1',
		'area_id' 	 	=> '1',
		//'source_id' 	=> '1', //移出到v9_video_content
		'is_free' 	    => '0',
//		'total_episodes'=> '',
//		'parent_id' 	=> '',
//		'episode_number' => '',
//		'latest_episode_num' => '',
		'rating'      	=> intval($r['rating'])?$r['rating']:'1',
		'ispackage'   	=> $r['ispackage'],
		'catid'   	 	=> '54', 
		'status'     	=> '99',
		'sysadd'     	=> '1',
		'username'   	=> get_uname_by_sid(get_my_sid()) ,
		'updatetime' 	=> strtotime($r['updateTime']),
		'spid'		=> $spid,
	);
	
	if($r['channel']) $d['channel'] = $r['channel'] ;
	if($r['topspeaker']) $d['director'] = $r['topspeaker'] ;

	if(intval($r['ispackage']) == 1){
		$subassets = $db->r1('go3c_assetpackage_asset',array('assetpackage_id'=>$r['id']),'count(asset_id) as c') ;
		$d['total_episodes'] = $subassets['c'];
		$d['latest_episode_num'] = '';
	}else{
		$parentassets = $db->r1('go3c_assetpackage_asset',array('asset_id'=>$r['id']),'assetpackage_id,listorder');
		if(isset($parentassets['assetpackage_id'])){
			$d['parent_id'] = $parentassets['assetpackage_id'];
			if(intval($column_id) ==3){			
				$qishu = substr($r['name'],-8);
				if(is_Date1($qishu)){
					//echo $qishu."\n";
					//$go3cdb->w('video',array('episode_number'=>$qishu),array('vid'=>$vv['vid']));
					$d['episode_number'] = $qishu;
				}else{
					$qishu = substr($r['name'],-10);
					if(is_Date2($qishu)){
						//echo $qishu." ".str_replace("-","",$qishu)."\n";
						$qishu = str_replace("-","",$qishu);
						//$go3cdb->w('video',array('episode_number'=>$qishu),array('vid'=>$vv['vid']));
						$d['episode_number'] = $qishu;
					}else{
						//echo $r['vid'].":".$r['name']."------------".$qishu."\n";	
					}
				}			
			}else{
				$d['episode_number'] = $parentassets['listorder'];
			}
		}
	}

	//shared 网友分享默认处理 start
	//soooner导入的电影，电视剧默认shared=1；soooner导入的电视栏目，乐酷默认shared=0
	if(intval($column_id)==4 || intval($column_id)==5|| intval($column_id)==6){
		$d['shared'] = 1;		
	}else{
		$d['shared'] = 0;
	}
	//网友分享默认处理 end
	
	//附属的data表
	$dd = array(
		'short_desc' => $r['title'], 
		'long_desc'  => $r['description'] 
	);
	
	
	//先查询，虽然这是不合理的，但是数据库主键不唯一，所以只能这样干了
	$thisv = $phpcmsdb->r1('v9_video',array('asset_id'=>$r['id']),'id,published,online_status') ;
	//logError($thisv);
	if(isset($thisv['published']) && strcmp($thisv['published'] ,'1') == 0){
		return ;
	}
	
	//更新吧..
	$thisv_id = 0 ;
	if(isset($thisv['id'])){
		if(in_array($thisv['online_status'],array('3','0','1','99'))){    
			//3 编辑未通过，0或1 导入，99 错误  这三种状态的数据会在重新导入后被覆盖
			$thisv_id = $thisv['id'] ;
			$d['online_status'] = '1';
			$phpcmsdb->w('v9_video',$d,array('id'=>$thisv_id)) ;
			$phpcmsdb->w('v9_video_data',$dd,array('id'=>$thisv_id)) ;
		
		$scount['as_upsum']++ ;
		}
	}else{
	//新增吧...
		$d['inputtime'] = strtotime($r['createTime']) ;
		$thisv_id =  $phpcmsdb->w('v9_video',$d) ;
		$dd['id'] = $thisv_id ;
		$phpcmsdb->w('v9_video_data',$dd) ;
	}

	$scount['as_total']++ ;
	
	$rs = $db->r('go3c_asset_content',array('asset_id'=>$r['id'])) ;
	$cids = array() ;
	foreach($rs as $v){
		//
		$cd = array(
			'title'   	=> $r['id'].'-'.$r['name'].'-'.$v['clarity'], 
			'asset_id'  => $r['id'], 
			'path'   	=> get_vod_url('STB' ,$v['path']),  
			'clarity'   => $v['clarity'],  
			'catid'   	=> '64', 
			'status'    => '99',
			'sysadd'    => '1',
			'username'  => get_uname_by_sid(get_my_sid()) ,
			'updatetime'=> time(),
			'source_id' => '1',
		);
		
		$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$cd['asset_id'],'clarity'=>$cd['clarity'],'path'=>$cd['path']),'id') ;
		if(isset($tmp['id'])){
			$k = $tmp['id'] ;
			$phpcmsdb->w('v9_video_content',$cd,array('id'=>$k)) ;
		}else{
			$cd['inputtime'] = time() ;
			$k = $phpcmsdb->w('v9_video_content',$cd) ;
			$phpcmsdb->w('v9_video_content_data',array('id'=>$k)) ;
		}
		
		$cids[] = $k ;
	}
	
	//清理更新之后，多余的内容
	$rs = $phpcmsdb->r('v9_video_content',array('asset_id'=>$r['id']),'id') ;
	foreach($rs as $v){
		if(in_array($v['id'],$cids)) continue ;
		$phpcmsdb->d('v9_video_content',array('id'=>$v['id'])) ;
		$phpcmsdb->d('v9_video_content_data',array('id'=>$v['id'])) ;
	}
	
	$rs = $db->r('go3c_asset_poster',array('asset_id'=>$r['id'])) ;
	
	//如果没有读到，尝试读取父级poster...
	//if(count($rs) < 1 && !empty($d['parent_id'])){
	//	$rs = $db->r('go3c_asset_poster',array('asset_id'=>$d['parent_id'])) ;
	//}
	
	$pids = array() ;

	////20130704 赛维安讯媒资系统用一横一竖一瀑布流 三种基本图分别替换之前同尺寸的多种图格式
	$newrs = array();
	$specialtype = array('501'=>array('102','202','302','305','402'),
			    '601'=>array('103','203','303','403'),
			    '701'=>array('206','306','406')
			);
	foreach($rs as $p){
		if(is_array($specialtype[$p['type']])){
			//var_dump($specialtype[$p['type']]);
			foreach($specialtype[$p['type']] as $type){
				//echo $p['type']." replace ".$type.' to '.$p['path']."\n";
				$p['type'] = $type;			
				$newrs[] = $p;	
			}
		}else{
			$newrs[] = $p;	
		}
	}
	/////
	foreach($newrs as $v){
		//
		$cd = array(
			'title'   	=> $r['id'].'-'.$r['name'].'-'.$v['type'], 
			'asset_id'  => $r['id'], 
			'path'   	=> get_img_url($v['path']),  
			'type'   	=> $v['type'],  
			'catid'   	=> '65', 
			'status'    => '99',
			'sysadd'    => '1',
			'username'  => get_uname_by_sid(get_my_sid()) ,
			'updatetime'=> time(),
		);
		
		
		$tmp = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$cd['asset_id'],'type'=>$cd['type'],'path'=>$cd['path']),'id') ;
		if(isset($tmp['id'])){
			$k = $tmp['id'] ;
			$phpcmsdb->w('v9_video_poster',$cd,array('id'=>$k)) ;
		}else{
			$cd['inputtime'] = time() ;
			$k = $phpcmsdb->w('v9_video_poster',$cd) ;
			$phpcmsdb->w('v9_video_poster_data',array('id'=>$k)) ;
		}
		
		$pids[] = $k ;
	}
	
	
	//清理更新之后，多余的图片
	$rs = $phpcmsdb->r('v9_video_poster',array('asset_id'=>$r['id']),'id') ;
	foreach($rs as $v){
		if(in_array($v['id'],$pids)) continue ;
		$phpcmsdb->d('v9_video_poster',array('id'=>$v['id'])) ;
		$phpcmsdb->d('v9_video_poster_data',array('id'=>$v['id'])) ;
	}

	return ;
}




//导入频道子函数 xml -> sooner
function __bigtvm_import_doscript_imchannel($alloradd=false,$rexml=false){
	$iurl = APP_PATH.'xml/channel.xml' ;
	if(USE_ONLINE_API) $iurl = 'http://111.208.56.197:8088/interface/GetChannel.php' ;
	
	$s = ifile_get_contents($iurl) ;
	
	if(empty($s)) return array('result'=>'no') ;
	
	$xml = simplexml_load_string($s) ;
	
	
	//单独获取xml ，导入 epg 的时候使用
	if($rexml) return $xml ;

	$imgpath = trim($xml->imgpath) ;
	$systemdate = trim($xml->systemdate) ;

	global $db ;
	
	$re = array('total'=>0,'upsum'=>0) ;
	
	foreach($xml->channels->channel as $c){
		$d = array() ;
		$d['systemdate'] = $systemdate ;
		$d['imgpath'] = $c['imgurl'] ;
		$d['id'] = $c['id'] ;
		$d['text'] = $c['text'] ;
		$d['img'] = $c['img'] ;
		$d['imgurl'] = $c['imgurl'] ;
		$d['iswidth'] = $c['iswidth'] ;
		
		$cdata = json_decode(json_encode($c->data),true) ;
		
		$carray = array('uuidPC-HIGH','uuidPC-MEDIUM','uuidIOS-HIGH','uuidIOS-MEDUIM','uuidSTB-HIGH','uuidSTB-MEDIUM') ;
		foreach($carray as $ik){
			if(!isset($cdata[$ik])) continue ;
			if(is_array($cdata[$ik])){
				if(count($cdata[$ik]) < 1){
					$cdata[$ik] = '' ;
				}else{
					$cdata[$ik] = $cdata[$ik][0] ;
				}
			}
			$d[str_replace('-','_',$ik)] = $cdata[$ik] ;
		}
		
		go3c_fix_d($d) ;
		
		
		//增加导入时间和状态
		$d['created'] = time() ;
		$d['istatus'] = 1 ;
		$d['last_sid'] = '0' ;
		
		global $imlog_id ;
		$d['imlog_id'] = $imlog_id ;
		
		$r = $db->w('go3c_channel',$d,array('id'=>$d['id'])) ;
		if(!$r){
			$db->w('go3c_channel',$d) ;
		}
		
		if($r) $re['upsum']++ ;
		$re['total']++ ;
	}
	
	$re['result'] = 'ok' ;
	return $re ;
}


//导入epg子函数  xml -> sooner
function __bigtvm_import_doscript_imepg($systemdate,$channel_id){
	$iurl = APP_PATH.'xml/'.$channel_id.'_20121112.xml' ;
	if(USE_ONLINE_API) $iurl = 'http://111.208.56.197:8088/interface/GetListEpg.php?id='.$channel_id.'&date='.$systemdate;
	
	$s = ifile_get_contents($iurl) ;

	if(empty($s)) return array('result'=>'no') ;
	
	$ixml = simplexml_load_string($s) ;
	if($ixml['epgtype'] == 'false') return array('result'=>'false') ;
	
	//if($systemdate != trim($ixml->systemdate)) return array('result'=>'no') ;
	
	global $db ;
	
	$ii = 0 ;
	$re = array('total'=>0,'upsum'=>0) ;
	foreach($ixml->programs->program as $p){
		$ii++ ;
		if(empty($p['id'])) $p['id'] = str_replace('-','',$systemdate) .$channel_id .'_' . $ii ;
		$pd = array() ;
		$pd['systemdate'] = $systemdate ;
		$pd['id'] = $p['id'] ;
		$pd['channel_id'] = $channel_id ;
		$pd['text'] = $p['text'] ;
		$pd['starttime'] = date('Y-m-d H:i:s',strtotime($p['starttime'])) ;
		$pd['endtime'] = date('Y-m-d H:i:s',strtotime($p['endtime'])) ; 
		
		
		$pdata = json_decode(json_encode($p->data),true) ;

		$pd['uuidPC_HIGH'] = $pdata['uuidPC-HIGH'] ;
		$pd['uuidIOS_HIGH'] = $pdata['uuidIOS-HIGH'] ;
		$pd['uuidIOS_MEDUIM'] = $pdata['uuidIOS-MEDUIM'] ;
		$pd['uuidSTB_HIGH'] = $pdata['uuidSTB-HIGH'] ;
		$pd['uuidPC_MEDIUM'] = $pdata['uuidPC-MEDIUM'] ;
		
		go3c_fix_d($pd) ;
		
		//增加导入时间和状态
		$pd['created'] = time() ;
		$pd['istatus'] = 1 ;
		$pd['last_sid'] = '0' ;
		
		global $imlog_id ;
		$pd['imlog_id'] = $imlog_id ;
		
		$r = $db->w('go3c_program',$pd,array('id'=>$pd['id'])) ;
		if(!$r){
			$db->w('go3c_program',$pd) ;
		}
		
		if($r) $re['upsum']++ ;
		$re['total']++ ;
	}
	
	$re['result'] = 'ok' ;
	return $re ;	
}

function testvideo(){
	$s = '';
	if(empty($s)) return array('result'=>'no') ;
}
//导入视频资源子函数  xml -> sooner
function __bigtvm_import_doscript_imasset($imasset,$s=false){
	global $spid;
	$iurl = APP_PATH.'xml/Export.xml' ;
	if(USE_ONLINE_API) $iurl = 'http://111.208.56.196:8028/bsmproo/export2portal/export?spid='.$spid;
	
	if(!$s)	$s = ifile_get_contents($iurl) ;
	
	if(empty($s)) return array('result'=>'no') ;
	
	$xml = simplexml_load_string($s) ;
	
	if(!isset($xml->publishinfo)) return array('result'=>'no') ;
	
	$re = array() ;
	$re['result'] = 'ok' ;
	
	$re['as_t'] = 0 ;
	$re['as_u'] = 0 ;
	$re['asp_t'] = 0 ;
	$re['asp_u'] = 0 ;
	$re['pro_t'] = 0 ;
	$re['pro_u'] = 0 ;
	$re['prop_t'] = 0 ;
	$re['prop_u'] = 0 ;
	
	
	//导入 assets
	if(!empty($xml->publishinfo->assetCount)){
		foreach($xml->assets->asset as $a){
			$tmp = go3c_import_asset($a) ;
			
			$re['as_t']++ ;
			if(!empty($tmp['this_update'])) $re['as_u']++ ;
		}
	}
	
	//导入 assetpackage
	if(!empty($xml->assetpackage)){
		foreach($xml->assetpackage->asset as $a){
			$tmp = go3c_import_assetpackage($a) ;
			
			$re['asp_t']++ ;
			if(!empty($tmp['this_update'])) $re['asp_u']++ ;
		}
	}
	
	//导入 product
	if(!empty($xml->publishinfo->productCount)){
		foreach($xml->products->product as $p){
			$tmp = go3c_import_product($p) ;
			$re['pro_t']++ ;
			if(!empty($tmp['this_update'])) $re['pro_u']++ ;
		}
	}
	
	//导入 productpackage
	if(!empty($xml->productpackage)){
		foreach($xml->productpackage->product as $p){
			$tmp = go3c_import_productackage($p) ;
			
			$re['prop_t']++ ;
			if(!empty($tmp['this_update'])) $re['prop_u']++ ;
		}
	}
	
	//分类 category
	foreach($xml->category as $c){
		go3c_import_category($c) ;
	}
	
	$tmp = go3c_import_category('getcsum') ;
	
	$re = array_merge($re,$tmp) ;
	
	return $re ;
}




//--- 以下为导入资源的子函数 ----

//导入资源
function go3c_import_asset($a,$ispackage=0){
	$d = array() ;

	//限定的字段名称
	$dsf = array('assetName'=>'name','shortName','title','director','actor','year','runTime','displayRunTime','description','rating','createTime','topSpeaker'=>'topspeaker','channel') ;
	go3c_fix_dsf($dsf) ;
	foreach($a->metadatas->metadata as $m){
		$k = trim($m['name']) ;
		if(!isset($dsf[$k])) continue ;
		$d[$dsf[$k]] = trim($m['value']) ;
	}
	

	$d['id'] = $a['assetId'] ;
	$d['updateTime'] = $a['updateTime'] ;
	$d['category'] = $a['category'] ;
	
	$d['category'] = explode(',',$d['category']) ;
	foreach($d['category'] as $ik=>$iv){
		if($iv == '') unset($d['category'][$ik]) ;
	}
	$d['category'] = join(',',$d['category']) ;
	
	$d['ispackage'] = $ispackage ;
	
	go3c_fix_d($d) ;
	
	global $db ;
	$this_update = 1 ;
	
	
	//增加导入时间和状态
	$d['created'] = time() ;
	$d['istatus'] = 1 ;
	$d['last_sid'] = '0' ;
	
	global $imlog_id ;
	$d['imlog_id'] = $imlog_id ;

	$r = $db->w('go3c_asset',$d,array('id'=>$d['id'])) ;
	if(!$r){
		$this_update = 0 ;
		$db->w('go3c_asset',$d) ;
	}
	
	//posters
	$db->d('go3c_asset_poster',array('asset_id'=>$d['id'])) ;
	if($a->posters){
		foreach($a->posters->poster as $v){
			$pd = array() ;
			$pd['path'] = $v['path'] ;
			$pd['type'] = $v['type'] ;
			$pd['asset_id'] = $d['id'] ;
			
			go3c_fix_d($pd) ;
			
			$db->w('go3c_asset_poster',$pd) ;
		}
	}
	
	//contents
	$db->d('go3c_asset_content',array('asset_id'=>$d['id'])) ;
	
	if($a->contents){
		foreach($a->contents->content as $v){
			$pd = array() ;
			$pd['path'] = $v['path'] ;
			$pd['clarity'] = $v['clarity'] ;
			$pd['asset_id'] = $d['id'] ;
			go3c_fix_d($pd) ;
			
			$db->w('go3c_asset_content',$pd) ;
		}
	}

	
	//assetpackage_asset ;
	if($ispackage){
		//product_asset
		$db->d('go3c_assetpackage_asset',array('assetpackage_id'=>$d['id'])) ;
		foreach($a->assetIds->assetId as $ass){
			if(empty($ass[0])) continue ;
			
			$pa = array() ;
			$pa['assetpackage_id'] = $d['id'] ;
			$pa['asset_id'] = trim($ass[0]) ;
			$pa['listorder'] = trim($ass['order']) ;
			
			$db->w('go3c_assetpackage_asset',$pa) ;
			
		}
		
	}
	
	
	//del category
	$db->d('go3c_categoryasset_asset',array('asset_id'=>$d['id'])) ;
	
	
	return array('this_update'=>$this_update) ;
}

//导入资源包
function go3c_import_assetpackage($a){
	return go3c_import_asset($a,1) ;
}


function go3c_import_product($p,$ispackage=0){
	$d = array() ;
	
	//限定的字段名称
	$dsf = array('id','name','description','type','bizId','price','url','billingModelName','billingModelId','billingModelType','spId','posterUrl','demoUrl','startTime','endTime','updateTime') ;	
	go3c_fix_dsf($dsf) ;

	foreach($p->attributes() as $k=>$v){
		$k = trim($k) ;
		if(!isset($dsf[$k])) continue ;
		$d[$dsf[$k]] = trim($v) ;
	}
	
	$d['ispackage'] = $ispackage ;
	
	go3c_fix_d($d) ;

	global $db ;
	
	
	$this_update = 1 ;
	
	//增加导入时间和状态
	$d['created'] = time() ;
	$d['istatus'] = 1 ;
	$d['last_sid'] = '0' ;
	
	$r = $db->w('go3c_product',$d,array('id'=>$d['id'])) ;
	if(!$r){
		$this_update = 0 ;
		$db->w('go3c_product',$d) ;
	}
	
	//product_asset
	$db->d('go3c_product_asset',array('product_id'=>$d['id'])) ;
	foreach($p->assetIds->assetId as $ass){
		if(empty($ass[0])) continue ;
		
		$pa = array() ;
		$pa['product_id'] = $d['id'] ;
		$pa['asset_id'] = trim($ass[0]) ;
		$pa['listorder'] = trim($ass['order']) ;
		
		$db->w('go3c_product_asset',$pa) ;
	}
	
	//del category
	$db->d('go3c_categoryproduct_product',array('product_id'=>$d['id'])) ;
	
	return array('this_update'=>$this_update) ;
}

function go3c_import_productackage($p){
	return go3c_import_product($p,1) ;
}

//导入分类
function go3c_import_category($c,$parent=false){
	//计数器
	static $csum ;
	if(!isset($csum)) $csum = array() ;
	
	if($c == 'getcsum') return $csum ;
	
	
	$d = array() ;
	$d['id'] = $c['id'] ;
	$d['name'] = $c['name'] ;
	if($parent) $d['parentId'] = $parent['id'] ;
	
	if(empty($c['type']) && !empty($parent['type'])) $c['type'] = $parent['type'] ;
	if(empty($c['type'])) return false ;
	
	go3c_fix_d($d) ;
	
	$dbtype = trim($c['type']) ;
	$db_table = 'go3c_category'.$dbtype ;
	
	global $db ;
	
	//增加计数
	$csumtype = 'cat_' . $dbtype ;
	if(!isset($csum[$csumtype.'_t'])){
		$csum[$csumtype.'_t'] = 0 ;
		$csum[$csumtype.'_u'] = 0 ;
	}

	$csum[$csumtype.'_t']++ ;
	
	$r = $db->w($db_table,$d,array('id'=>$d['id'])) ;
	if(!$r){
		$db->w($db_table,$d) ;
	}
	
	//更新计数器加加
	if($r) $csum[$csumtype.'_u']++ ;

	if(!empty($c->assetIds)){
		foreach($c->assetIds->assetId as $v){
			$pa = array() ;
			$pa['categoryasset_id'] = $d['id'] ;
			$pa['asset_id'] = trim($v[0]) ;
			$pa['listorder'] = trim($v['order']) ;
			
			$db->w('go3c_categoryasset_asset',$pa) ;
		}
	}
	
	if(!empty($c->productIds)){
		foreach($c->productIds->productId as $v){
			$pa = array() ;
			$pa['categoryasset_id'] = $d['id'] ;
			$pa['asset_id'] = trim($v[0]) ;
			$pa['listorder'] = trim($v['order']) ;
			
			$db->w('go3c_categoryproduct_product',$pa) ;
		}
	}
	
	
	
	if($c['hasCategories'].'' == 'false') return ;


	foreach($c->category as $cc){
		//导入 category.
		go3c_import_category($cc,$c) ;
	}
	
	return true ;
}




/**
*   uuid转直播链接
*/
function get_live_url($uuidtype,$uuid){
	if($uuid){
		if(strstr($uuidtype,"IOS")){
			return "http://ipad.vsdn.new.bigtv.com.cn/".$uuid."/1.m3u8";		
		}else if(strstr($uuidtype,"STB")){
			return "http://biz.vsdn.new.bigtv.com.cn/playlive.js?uuid=".$uuid;
		}else if(strstr($uuidtype,"PC")){
			return "http://biz.vsdn.new.bigtv.com.cn/playlive.do?uuid=".$uuid;
		}else{
			return $uuid;
		}
	}
}

/**
*   uuid转时移链接
*/
function get_timeshift_url($uuidtype,$uuid){
	if($uuid){
		if(strstr($uuidtype,"IOS")){
			return "http://ipad.vsdn.new.bigtv.com.cn/".$uuid."/1.m3u8";		
		}else if(strstr($uuidtype,"STB")){
			return "http://biz.vsdn.new.bigtv.com.cn/ts.js?uuid=".$uuid;
		}else if(strstr($uuidtype,"PC")){
			return "http://biz.vsdn.new.bigtv.com.cn/ts.do?uuid=".$uuid;
		}else{
			return $uuid;
		}
	}
}

/**
*   相对路径转点播链接
*/
function get_vod_url($type,$path){
	if(strstr($type,"IOS")){
		return "http://biz.vsdn.new.bigtv.com.cn/playvod.do?url=http://111.208.56.207/".$path;	
	}else if(strstr($type,"STB")){
		global $spid;
		if(strstr($spid,"quanwangsp"))
		return "http://111.208.56.194/playvod.js?url=http://111.208.56.207/".$path;
		else
		return "http://biz.vsdn.new.bigtv.com.cn/playvod.js?url=http://111.208.56.207/".$path;
	}else if(strstr($type,"PC")){
		return "http://biz.vsdn.new.bigtv.com.cn/playvod.do?url=http://111.208.56.207/".$path;
	}else{
		return $path;
	}
}



/*
*     根据视频来源名称，链接类型 获取source id
*/

function get_source_id($name,$linktype){
	if(empty($name)) return false;
	if(!in_array($linktype,array('web','redirect','segment','raw'))) return false;

	$realname = '';

	$vss = array(
		'youku'=>array('youku','优酷') ,
		'tudou'=>array('tudou','土豆') ,
		'xunlei'=>array('xunlei','迅雷') , 
		'sohu'=>array('sohu','搜狐'),
		'letv'=>array('letv','乐视'),
		'm1905'=>array('m1905','电影网'),
		'qiyi'=> array('qiyi','奇艺','iqiyi','爱奇艺') ,
		'qq'=> array('qq','腾讯') ,
		'bigtv'=> array('bigtv','soooner','大电视') ,
		'wasu'=> array('wasu','华数') ,
		'pptv'=> array('pptv') ,
		'pps'=> array('pps') ,
	) ;

	foreach($vss as $k=>$vs){
		foreach($vs as $v){
			if(strpos($name,$v) === false) continue ;
			$realname = $k ;
			break;
		}
	}
	
	static $ss ;
	if(!isset($ss)) $ss = array() ;
	if(isset($ss[$realname][$linktype])) return $ss[$realname][$linktype] ;

	$phpcmsdb = yzy_phpcms_db() ;
	$rs = $phpcmsdb->rs("select * from v9_video_source where title like '%".$realname."%' and type ='".$linktype."'");
	if(count($rs)>0){
		$ss[$realname][$linktype] = $rs[0]['id'] ;
	}else{
		$d = array() ;
		$d['title'] = $realname.'-'.$linktype ;
		$d['catid'] = '72' ;
		$d['status'] = '99' ;
		$d['sysadd'] = '1' ;
		$d['username'] = 'autorun' ;
		$d['inputtime'] = time() ;
		$d['updatetime'] = time() ;
		$d['type'] = $linktype ;

		$k = $phpcmsdb->w('v9_video_source',$d) ;
		$phpcmsdb->w('v9_video_source_data',array('id'=>$k)) ;
		$ss[$realname][$linktype] = $k ;
	}
	
	return $ss[$realname][$linktype] ;
}

