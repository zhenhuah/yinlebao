<?php

	define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
	define('CACHE_ROOT',dirname(__FILE__). '/../caches/') ;
	
	require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;

	$db = yzy_go3c_db() ;

	$channel_id = request('cid','GET','int') ;
	$date = request('d','GET') ;
	
	//是否严格检查，默认是，否则按照小时检查
	$nostrict = request('nostrict','GET') ;
	
	if(!$date) $date = date('Y-m-d') ;
	
	$date = strtotime($date) ;
	if(!$date) return false ;
	
	$endate = date('Y-m-d',$date+86400) ;
	$date = date('Y-m-d',$date) ;
	
	$sql = '' ;
	if($channel_id) $sql = "and channel_id = $channel_id" ;
	$rs = $db->r('epg',"where start_time >= '$date' and end_time < '$endate' $sql",'*',array('sql'=>'order by start_time asc','r_group_key'=>'channel_id')) ;

	$re = array() ;
	foreach($rs as $kk=>$vs){
		
		$lastv = false ;
		foreach($vs as $k=>$v){
			if(!$k){
				$lastv = $v ;
				continue ;
			}
			
			//严格检查
			if(!$nostrict){
				if($v['start_time'] != $lastv['end_time']){
					$re[] = $kk ;
					
					$lastv = $v ;
					break ;
				}
				
				$lastv = $v ;
			}else{
			//非严格检查
				if(date('H',strtotime($v['start_time'])) != date('H',strtotime($lastv['end_time']))){
					$re[] = $kk ;
				
					$lastv = $v ;
					break ;
				}
				$lastv = $v ;
			}
		}
	}
	
	if(count($re) < 1) die('yes') ;
	
	print 'no|' . join(',',$re) ;