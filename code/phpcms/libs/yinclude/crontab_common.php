<?php

function crontab_is_now_run($ikey){
	//导入时间查询
	$phpcmsdb = yzy_phpcms_db() ;
	$crontab = $phpcmsdb->r1('v9_crontab',array('ikey'=>$ikey)) ;
	//return $crontab ;
	//从不执行
	if(empty($crontab['cday'])) return ;
		
	$atime = array() ;
	$atime['hour'] = intval(date('H')) ;
	$atime['minute'] = intval(date('i')) ;
	$atime['cday'] = date('w') ;
	
	
	//星期天兼容
	if(!$atime['cday']) $atime['cday'] = 7 ;
	
	//不在当前小时
	if($atime['hour'] != $crontab['hour']) return false ;
	
	//不在当前分钟
	if($atime['minute'] != $crontab['minute']) return false;
	
	//不在当前天并且不是每天执行
	if($crontab['cday'] != 8 && $atime['cday'] != $crontab['cday']) return false;

	return $crontab ;
}


function crontab_finish($ikey){
	//导入时间查询
	
	$phpcmsdb = yzy_phpcms_db() ;
	$phpcmsdb->w('v9_crontab',array('lastrun'=>date('Y-m-d H:i:s')),array('ikey'=>$ikey)) ;
	
	return true ;
}