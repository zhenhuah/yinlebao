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
echo '111'."\n";die();

	//导入今天之外的几天数据 如2,即导入明天、后天两天的数据
	$import_days_N = 2 ;

	//类型
	$ikey = 'epg' ;
	
	
	//查询是否当前执行
	$crontab = crontab_is_now_run($ikey) ;
	if(!$crontab) return ;

	//类型
	$mtype = 'im' . $ikey ;
	
	$db = yzy_sooner_db() ;
			
	$imlog_id = $db->w('go3c_imlog',array('sid'=>get_my_sid(),'created'=>time())) ;
	
	
	$total = 0 ;
	
	
	$todayt = strtotime(date('Y-m-d')) ;
	for($day_i=1;$day_i<=$import_days_N;$day_i++){
	
		$imepg = date('Y-m-d',strtotime("+{$day_i} days",$todayt)) ;
		
		$d = array('total'=>0,'upsum'=>0,'t'=>$imepg) ;
		$d['t'] = $imepg ;
		$d['result'] = 'ok' ;
		
		$chxml = __bigtvm_import_doscript_imchannel(false,true) ;

		foreach($chxml->channels->channel as $c){
			$cid = trim($c['id']) ;
			$tmp = __bigtvm_import_doscript_imepg($imepg,$cid) ;
			if($tmp['result'] == 'no') continue ;
			if($tmp['result'] == 'false') continue ;
			
			$d['total'] += $tmp['total'] ;
			$d['upsum'] += $tmp['upsum'] ;
			
			$total += $tmp['total'] ;
		}
		
		//更新到日志
		$db->w('go3c_imlog',array('imepg'=>iencode_arr($d)),array('id'=>$imlog_id)) ;
		
		//sooner -> phpcms 
		$func = '__bigtvm_import_doiscript_' . $mtype ;
		$d = $func($imlog_id) ;
		
		if(is_array($d)) $d['result'] = 'ok' ;
		else $d['result'] = 'fail' ;
		
		//更新到日志
		$db->w('go3c_imlog',array('i'.$mtype=>iencode_arr($d)),array('id'=>$imlog_id)) ;
		
	}
	
	print 'ok!'. date('Y-m-d H:i:s') . "\n" ;
	
	//更新回 crontab
	crontab_finish($ikey) ;
	
	//发送短信
	y_send_message('自动导入【EPG】数据报告','报告！自动EPG数据：'. $total ,'autorun',$crontab['us']) ;
	
	
