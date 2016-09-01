<?php
/**
 * 导入数据
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



	$db = yzy_sooner_db() ;

	$phpcmsdb = yzy_phpcms_db() ; 
	$spids = $phpcmsdb->r('v9_spid') ;
	

	//读取任务
//	while(1){
		$doim = $phpcmsdb->r1('v9_doimport',array('status'=>'0')) ; 
		
		
		//没有任务
		if(empty($doim['ikey'])){
//			sleep(30) ;
//			continue ;
		exit;
		}
		
		
		//更新状态,接收任务
		//0未领取,1开始,4错误,9完成
		$phpcmsdb->w('v9_doimport',array('status'=>'1'),array('id'=>$doim['id'])) ; 
		
		
		
		try{
			
			print 'start!' . $doim['ikey'] . '!'. date('Y-m-d H:i:s') . "\n" ;
			
			//导入资源的任务
			if($doim['ikey'] == 'asset'){
				//类型
				$ikey = 'asset' ;
				
				//类型
				$mtype = 'im' . $ikey ;
			

				global $spid ;
				$spid = $doim['p1'] ;
				
				$imlog_id = $db->w('go3c_imlog',array('sid'=>get_my_sid(),'created'=>time())) ;
				
				//1 - 导入更新 ;  9 -  导入全部
				$imasset = $doim['p'] ;
				
				$d = __bigtvm_import_doscript_imasset($imasset) ;
				$d['t'] = $imasset ;
				
				//更新到日志
				$db->w('go3c_imlog',array('imasset'=>iencode_arr($d)),array('id'=>$imlog_id)) ;
				
				
				//sooner -> phpcms 
				$func = '__bigtvm_import_doiscript_' . $mtype ;
				$d = $func($imlog_id) ;
				
				if(is_array($d)) $d['result'] = 'ok' ;
				else $d['result'] = 'fail' ;

				//更新到日志
				$db->w('go3c_imlog',array('i'.$mtype=>iencode_arr($d)),array('id'=>$imlog_id)) ;
				
				print 'ok!'. date('Y-m-d H:i:s') . "\n" ;
			

				//完成
				$phpcmsdb->w('v9_doimport',array('status'=>'9','finished'=>time(),'imlog_id'=>$imlog_id),array('id'=>$doim['id'])) ;
		
			}else if($doim['ikey'] == 'epg'){
			
				//类型
				$ikey = 'epg' ;
				
				//类型
				$mtype = 'im' . $ikey ;
				

				$imlog_id = $db->w('go3c_imlog',array('sid'=>get_my_sid(),'created'=>time())) ;
				
				
				$total = 0 ;
				
	
				$imepg = $doim['p'] ;
				
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
			
				
				print 'ok!'. date('Y-m-d H:i:s') . "\n" ;
			
			
				//完成
				$phpcmsdb->w('v9_doimport',array('status'=>'9','finished'=>time(),'imlog_id'=>$imlog_id),array('id'=>$doim['id'])) ;
			}else if($doim['ikey'] == 'channel'){
				
				
				//类型
				$ikey = 'channel' ;

				//类型
				$mtype = 'im' . $ikey ;
						
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
				
				
				//完成
				$phpcmsdb->w('v9_doimport',array('status'=>'9','finished'=>time(),'imlog_id'=>$imlog_id),array('id'=>$doim['id'])) ;
			}
			
			
		}catch (Exception $e){
			$phpcmsdb->w('v9_doimport',array('status'=>'4'),array('id'=>$doim['id'])) ; 	
		}
		
//	}
