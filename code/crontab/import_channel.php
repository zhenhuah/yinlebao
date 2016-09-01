<?php
/**
 * 导入电视频道
 */

//自动执行脚本
define('IN_CRONTAB',true) ; 

//是否使用在线XML
define('USE_ONLINE_API',true) ;

define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
define('APP_PATH','http://localhost/svn/go3ccms/') ;

error_reporting(~E_NOTICE) ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/crontab_common.php' ;

	//类型
	$ikey = 'channel' ;
	
	
	//查询是否当前执行
	$crontab = crontab_is_now_run($ikey) ;
	if(!$crontab) return ;

	//类型
	$mtype = 'im' . $ikey ;
	
	$db = yzy_sooner_db() ;
			
	$imlog_id = $db->w('go3c_imlog',array('sid'=>get_my_sid(),'created'=>time())) ;
	
	$imchannel = 9 ;
	$d = __bigtvm_import_doscript_imchannel($imchannel) ;
	$d['t'] = $imchannel ;
	
	//更新到日志
	$db->w('go3c_imlog',array('imchannel'=>iencode_arr($d)),array('id'=>$imlog_id)) ;
	
	
	//sooner -> phpcms 
	$func = '__bigtvm_import_doiscript_' . $mtype ;
	$d = $func($imlog_id) ;
	
	if(is_array($d)) $d['result'] = 'ok' ;
	else $d['result'] = 'fail' ;
	

	//更新到日志
	$db->w('go3c_imlog',array('i'.$mtype=>iencode_arr($d)),array('id'=>$imlog_id)) ;
	
	print 'ok!'. date('Y-m-d H:i:s') . "\n" ;
	
	//更新回 crontab
	crontab_finish($ikey) ;
	
	//发送短信
	y_send_message('自动导入【频道】数据报告','报告！自动导入频道数据：'. $d['total'] ,'autorun',$crontab['us']) ;
		

	
	
