<?php


//更新一个视频链接
function do_up_a_dp_v_res($a){

	$a['apiurl'] = trim($a['apiurl']) ;
	global $db ;
	if(empty($a['apiurl'])){
		$db->d('import_dp_v',array('id'=>$a['id'])) ;
		return false ;
	}
	
	$s = afget($a['apiurl']) ;
	$aas = get_a_video_res_arr($a['apiurl'],$a['sourcename'],$s) ;

	$rs = $db->r('import_dp_v',array('apiurl'=>$a['apiurl']),'*',array('sql'=>'order by id asc')) ;
	

	if($a['vctype'] == 'c'){
		$db->w('import_dp_c_xml',array('apiurl'=>$a['apiurl'],'xml'=>$s),array('id'=>$tmp['id'])) ;
	}

	foreach($rs as $k=>$v){
		if(count($aas) < 1) break ;
		$aa = array_pop($aas) ;
		$upd = array() ;
		foreach($aa as $ik=>$iv){
			if($a[$ik] == $iv) continue ;
			if(!$iv) continue ;
			$upd[$ik] = $iv ;
		}
		
		if(count($upd) > 0){
			$db->w('import_dp_v',$upd,array('id'=>$v['id'])) ;
			vplog("!!up {$v['id']}") ;
		}else{
			vplog("skip {$v['id']}") ;
		}
		
		unset($rs[$k]);
	}
	
	
	foreach($aas as $aa){
		$upd = $a ;
		foreach($aa as $ik=>$iv){
			if($a[$ik] == $iv) continue ;
			if(!$iv) continue ;
			$upd[$ik] = $iv ;
		}
		
		unset($upd['id']) ;
		
		$db->w('import_dp_v',$upd) ;
	}
	
	foreach($rs as $k=>$v){
		$db->d('import_dp_v',array('id'=>$v['id'])) ;
	}
	
	
	return false ;
}


function get_a_video_res_arr($url,$sourcename='',$sxml=''){
	$aas = array() ;
	$tmp = get_dp_video_path($url,$sxml) ;
	if($tmp){
		foreach($tmp as $v){
			$a = array() ;
			$a['path'] = $v ;
			
			if(!$sourcename) $a['sourcename'] = dp_guess_sourcename($a['path']) ;
			else $a['sourcename'] = $sourcename ;
			$a['videotype'] = get_dp_videotype($a['path']) ;
			
			$aas[] = $a ;
		}
	}
	
	return $aas ;
}


function get_dp_pic_path($url){
	return $url ;
	parse_str($url,$tmp) ;
	if(!empty($tmp['url'])){
		$url = $tmp['url'] ;
	}
	
	return $url ;
}


function dp_guess_sourcename($url){
	$vss = array(
		'优酷'=>array('youku') ,
		'迅雷'=>array('xunlei') , 
		'搜狐'=>array('sohu'),
		'乐视'=>array('letv'),
		'电影网'=>array('m1905'),
		'奇艺'=> array('qiyi') ,
		'腾讯' => array('qq.com') ,
	) ;

	foreach($vss as $k=>$vs){
		foreach($vs as $v){
			if(strpos($url,$v) === false) continue ;
			return $k ;
		}
	}
	
	return '' ;
}


function get_dp_videotype($path){
	$vss = array(
		'flv'=>array('.flv') ,
		'm3u8'=>array('.m3u8') , 
		'mp4'=>array('.mp4'),
		'segment'=>array('segment'),
		'hdpfans'=>array('hdpfans'),
	) ;
	
	foreach($vss as $k=>$vs){
		foreach($vs as $v){
			if(strpos($path,$v) === false) continue ;
			return $k ;
		}
	}
	
	return 'other' ;
}



function get_dp_video_path($url,$s=''){
	if(!$s) $s = afget($url) ;
	$sxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxml),true) ;

	
	$re = array() ;
	
	if(isset($sxml['m3u8']) && !empty($sxml['m3u8']) && strpos($sxml['m3u8'],'m3u8') !== false) $re[] = $sxml['m3u8'] ;
	if(isset($sxml['url']['foobar']) && is_string($sxml['url']['foobar']))  $re[] = $sxml['url']['foobar'] ;
	if(isset($sxml['url']['foobar']) && is_array($sxml['url']['foobar']) && count($sxml['url']['foobar']) > 1)  $re[] = 'segment' ;
	
	return $re ;
}

function afget($u){
	vplog("get url $u") ;
	
	$r = '' ;
	$i = 0 ;
	while(empty($r)){
		$r = afile_get_contents($u);
		if($r) return $r ;
		sleep(2) ;
		$i++ ;
		if($i > 5) return false ;
		vplog("retry $i | $u ") ;
	}
}

function afile_get_contents($u){
	set_time_limit(999) ;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $u);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function vplog($n){
	if(defined('RUN_IN_CMD') && RUN_IN_CMD) print "{$n} \n" ;
}
