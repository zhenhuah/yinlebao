<?php

function crontab_censor($ikey){
	echo $ikey;
	//导入时间查询
	$phpcmsdb = yzy_phpcms_db() ;
	var_dump($phpcmsdb);
	$crontab = $phpcmsdb->r1('t_import_tasks',array('f_template_id'=>$ikey)) ;
	//return $crontab ;
	//从不执行
	if(empty($crontab['f_time'])) return ;
	if($crontab['f_auto']=='N'||$crontab['f_status']=='importing'){
		return ;
	}
	
	$Servertime = time();
	if($Servertime!=$crontab['f_time']) return false ;
	
	return $crontab ;
}

//导入时记录相应日志
function crontab_import_log($task_id,$status,$snum,$fnum){

	$phpcmsdb = yzy_phpcms_db() ;
	if($status=='Y'){
		$d = array(
			'f_task_id' 	 => $task_id,
			'f_starttime' 	 => time(),
			'f_responser' 	 => 'system',
			'f_success' 	 => $snum,
			'f_failed' 	     => $fnum
			) ;
		$phpcmsdb->w('t_import_log',$d) ;
	}else{
		$tlog = $phpcmsdb->r1('t_import_log',array('f_task_id'=>$task_id)) ;
		$f_duration = (time()-$tlog['f_starttime'])+30;
		$d = array(
			'f_duration' 	 => $f_duration,
			'f_success' 	 => $snum,
			'f_failed' 	     => $fnum
			) ;
	$phpcmsdb->w('t_import_log',$d,array('f_task_id'=>$task_id)) ;
	}
	return true ;
}

?>
