<?php
/**
 * 导入电视频道
 */

//自动执行脚本
define('IN_CRONTAB',true) ; 

define('PHPCMS_PATH',dirname(__FILE__). '/../') ;


error_reporting(~E_NOTICE) ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/publish_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/crontab_common.php' ;

	$ikey = 'publish' ;
	$crontab = crontab_is_now_run($ikey) ;
	
	if(!$crontab) return ;

	$tinfo = explode(',',$crontab['tinfo']) ;
	$phpcmsdb = yzy_phpcms_db() ;
	
	$d = array() ;
	$d['atlist'] = ypublish_get_atlist() ;
	$d['atlist_id'] = ypublish_get_atlist_id() ;
	
	extract($d) ;
	
	$sumall = array() ;
	
	print_r($tinfo);
	echo $tinfo;
	echo "111";
	foreach($tinfo as $mtype){
		
		$func = '__do_publish_to_'.$mtype ;
		
		$table = ypublish_get_off_or_online_table($mtype) ;
		$order = ypublish_get_off_or_online_order($mtype) ;
		$page_size = 100 ;
		
		$where = ypublish_get_off_or_online_where($mtype) ;
		
		
		//新添加 _tags 的表没有 spid 字段
		if(strpos($table,'_tags') && isset($where['spid'])) unset($where['spid']) ;
		
		
		//发布
		$phpcmsdb->r($table,$where,'*',array('func_row'=>$func,'sql'=>$order)) ;
		
		//添加日志
		$sumcount = $func('getsumcount') ;
		
		
		if(!$sumcount) continue ;
		
		$sumall[$mtype] = $sumcount ;
		
		$dd = array() ;
		$dd['sid'] = get_my_sid() ;
		$dd['created'] = time() ;
		$dd['content'] = $mtype .  '|' . $sumcount ;
		
		$phpcmsdb->w('v9_publishlog',$dd) ;
		
		print "down!{$mtype}|{$sumcount}!".date('Y-m-d H:i:s')  ;
	}
	
	$sms_text = array() ;
	foreach($sumall as $k=>$v){
		$sms_text[] = $atlist[$k] . $v . '个' ;
	}
	if(count($sms_text) > 0){
		y_send_message('自动【发布】报告','报告！自动发布了以下内容：'. join('；',$sms_text) ,'autorun',$crontab['us']) ;
		
		//更新回 crontab
		crontab_finish($ikey) ;
	}
	