<?php
/**
 * 自动下线
 */

//自动执行脚本
define('IN_CRONTAB',true) ; 

define('PHPCMS_PATH',dirname(__FILE__). '/../') ;


error_reporting(~E_NOTICE) ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/publish_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/crontab_common.php' ;


	//视频下线
	$ikey = 'off_video' ;
	$crontab = crontab_is_now_run($ikey) ;
	
	if(!$crontab) return ;
	
	
	//推荐位中的视频下线
	$pikey = 'off_position_video' ;
	$pcrontab = crontab_is_now_run($pikey) ;
	
	
	//下线视频几天前
	$tdays = $crontab['tinfo'] ;

	$phpcmsdb = yzy_phpcms_db() ;
	
	$d = array() ;
	$d['atlist'] = ypublish_get_atlist() ;
	$d['atlist_id'] = ypublish_get_atlist_id() ;
	
	extract($d) ;
	
	$thists = array($ikey,$pikey) ;
	foreach($thists as $thist){
	
	
		//将要下线的视频
		$aofflinev = array() ;
		
		$notinid = array('0') ;
		for($i=0;$i<=$tdays;$i++){
			//检查的下线时间
			$offline_time = $i * 84600 + time() ;
			
			$wheref = '' ;
			
			if($thist == $pikey){
				$wheref = ' and id in (select vid from v9_position_video)' ;
			}
			
			//检查视频下线时间
			$vs = $phpcmsdb->r('v9_video',array('published'=>'1'),'*',array('sql'=>"and UNIX_TIMESTAMP(offline_date) < $offline_time and id not in(".join(',',$notinid).')'.$wheref,'r_key'=>'id')) ;
			$notinid = array_merge($notinid,array_keys($vs)) ;
		
			$aofflinev[$i] = array() ;
			foreach($vs as $iv){
				$aofflinev[$i][] = $iv ;
			}
			
		}
		
		
		$mtype = $ikey ;
		//下线一天内的视频

		$func = '__do_publish_to_'.$ikey ;
		foreach($aofflinev[0] as $v){
			$func($v) ;
		}
		
		
		if(count($aofflinev[0]) > 0){
			$sumcount = $func('getsumcount') ;
			$sumall[$mtype] = $sumcount ;
			
			$dd = array() ;
			$dd['sid'] = get_my_sid() ;
			$dd['created'] = time() ;
			$dd['content'] = $mtype .  '|' . $sumcount ;
			
			$phpcmsdb->w('v9_publishlog',$dd) ;
			
			print "down!{$mtype}|{$sumcount}!".date('Y-m-d H:i:s')  ;
		}
		
		
		
		$sms_text = array() ;
		foreach($aofflinev as $k=>$vs){
			if(count($vs) < 1) continue ;

			$tmp = array() ;
			foreach($vs as $iv){
				$tmp[] = $iv['title'] ;
			}
			$tmp = join(',',$tmp) ;
			
			if($k)	$sms_text[] = "以下视频将在".$k."天内下线：\n" . $tmp ;
			else 	$sms_text[] = "以下视频已下线：\n" . $tmp ;
		}
		
		if(count($sms_text) > 0){
			$sms_text = join("\n\n",$sms_text) ;
			
			if($thist == $pikey){
				$msfix = '推荐位' ;
			}
			
			//发短信
			y_send_message("自动【{$msfix}下线视频】数据报告",'报告！'. $sms_text ,'autorun',$crontab['us']) ;
		}
		crontab_finish($thist) ;
		
		print "down!{$thist}!".date('Y-m-d H:i:s') . "\n"  ;
	}
	
	

	
	