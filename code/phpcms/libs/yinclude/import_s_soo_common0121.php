<?php

function afget($u){
	vplog("get url $u") ;
	
	$r = '' ;
	$i = 0 ;
	while(empty($r)){
		$r = afile_get_contents($u);
		if($r) return $r ;
		sleep(1) ;
		$i++ ;
		if($i > 3) return false ;
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




function get_soo_v_types(){
	static $TYPES ;
	if(isset($TYPES)) return $TYPES ;
	
	$s = afget('http://service.chinasoo.com/Mobile/movietype/type/type') ;
	$sxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxml),true) ;

	$TYPES = array() ;
	foreach($sxml['type']['type_item'] as $v){
		$TYPES[$v['TypeID']] = $v['Type'] ;
	}
	return $TYPES ;
}


function do_a_soo_video($id){
	$a = get_a_soo_video($id) ;
	
	if(empty($a['title'])) return ;
	
	$TYPES = get_soo_v_types() ;
	
	$d = array() ;
	$d['rkey'] = $a['id'] ;
	$d['title'] = $a['title'] ;
	$d['director'] = $a['directors'] ;
	$d['actor'] = $a['actors'] ;
	$d['tag'] = $a['tags'] ;
	$d['year'] = $a['year'] ;
	$d['runtime'] = $a['run_time'] ;
	
	$d['vcolumn'] = $TYPES[$a['type_id']] ;
	$d['vcate'] = $TYPES[$a['type_id']] ;
	
	$d['area'] = $a['area'] ;
	$d['total_episodes'] = $a['count'] ;
	$d['latest_episode_num'] = count($a['eps']) ;
	
	$d['rating'] = $a['mark'] ;
	$d['description'] = $a['description'] ;
	
	
	$d['created'] = time() ;
	$d['changed'] = time() ;
	$d['ishow'] = 1 ;

	
	global $db ;
	
	$tmp = $db->r1('import_soo',array('rkey'=>$d['rkey']),'id') ;
	if(!isset($tmp['id'])){
		$id = $d['id'] = $db->w('import_soo',$d) ;
	}else{
		$id = $d['id'] = $tmp['id']  ;
		$db->w('import_soo',$d,array('id'=>$id)) ;
	}
	
	
	//xml
	$xd = array() ;
	$xd['id'] = $id ;
	$xd['apiurl'] = $a['apiurl'] ;
	$xd['xml'] = $a['xml'] ;
	
	$tmp = $db->r1('import_soo_xml',array('id'=>$id),'id') ;
	if(!isset($tmp['id'])){
		$db->w('import_soo_xml',$xd) ;
	}else{
		$db->w('import_soo_xml',$xd,array('id'=>$id)) ;
	}
	
	
	//图片
	$pd = array() ;
	$pd['vctype'] = 'v' ;
	$pd['vid'] = $id ;
	$pd['pictype'] = '1' ;
	$pd['path'] = $a['poster'] ;
	
	$tmppd = $pd ;
	unset($tmppd['path']) ;
	$tmp = $db->r1('import_soo_p',$tmppd,'id') ;
	if(!isset($tmp['id'])){
		$db->w('import_soo_p',$pd) ;
	}
	
	$pd['pictype'] = '2' ;
	$pd['path'] = $a['poster2'] ;
	
	$tmppd = $pd ;
	unset($tmppd['path']) ;
	$tmp = $db->r1('import_soo_p',$tmppd,'id') ;
	if(!isset($tmp['id'])){
		$db->w('import_soo_p',$pd) ;
	}

	//视频
	foreach($a['urls'] as $iv){
		$av = array() ;
		$av['vctype'] = 'v' ;
		$av['vid'] = $id ;
		$av['path'] = $iv['url'] ;
		$av['apiurl'] = $iv['swfurl'] ;
		$av['sourcename'] = $iv['stagname'] ;
		$av['videotype'] = get_soo_videotype($av['path']) ;
		
		$tmp = $db->r1('import_soo_v',$av,'id') ;
		if(!isset($tmp['id'])){
			$db->w('import_soo_v',$av) ;
		}
	}
	
	
	//新增视频连续剧
	if(!empty($a['eps']) && count($a['eps']) > 0){
	
		foreach($a['eps'] as $v){
			$ad = array() ;
			$ad['rkey'] = $v['id'] ;
			$ad['title'] = $v['title'] ;
			$ad['runtime'] = $d['runtime'] ;
			$ad['episode_number'] = $v['series'] ;
			$ad['parent_id'] = $id ;
			
			$tmp = $db->r1('import_soo_c',array('rkey'=>$ad['rkey']),'id') ;
			if(!isset($tmp['id'])){
				$ad_id = $ad['id'] = $db->w('import_soo_c',$ad) ;
			}else{
				$ad_id = $ad['id'] = $tmp['id'] ;
				$db->w('import_soo_c',$ad,array('id'=>$ad['id'])) ;
			}
			
			//他的视频增加
			foreach($v['urls'] as $iv){
				$av = array() ;
				$av['vctype'] = 'c' ;
				$av['vid'] = $ad_id ;
				$av['path'] = $iv['url'] ;
				$av['apiurl'] = $iv['swfurl'] ;
				$av['sourcename'] = $iv['stagname'] ;
				$av['videotype'] = get_soo_videotype($av['path']) ;
				
				$tmp = $db->r1('import_soo_v',$av,'id') ;
				if(!isset($tmp['id'])){
					$db->w('import_soo_v',$av) ;
				}else{
					$db->w('import_soo_v',$av,array('id'=>$tmp['id'])) ;
				}
			}
		}
		
	}
	
	
}





function get_a_soo_video($id){
	static $sdata ;
	if(!isset($sdata)) $sdata = array() ;
	if(isset($sdata[$id])) return $sdata[$id] ;
	
	
	$apiurl = "http://service.chinasoo.com/Mobile/moviedetail/mid/{$id}/isgz/n/http/m3u8" ;
	
	$s = afget($apiurl) ;
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;

	$axml = $sxml['movie_list']['movie_item'] ;
	
	foreach($axml as $k=>$v){
		if(is_array($v) && count($v) < 1) $v = '' ;
		$axml[$k] = $v;
	}

	$video = array() ;
	$video['apiurl'] = $apiurl ;
	$video['title'] = $axml['MovieName'] ;
	$video['subtitle'] = $axml['Alias'] ;
	$video['id'] = $axml['MovieID'] ;
	$video['type_id'] = $axml['TypeID'] ;
	$video['tags'] = $axml['Drama'] ;
	$video['run_time'] = intval($axml['TimeSpan']) * 60 ;
	
	$video['directors'] = str_replace(';',',',substr($axml['Director'],1,-1)) ;
	$video['actors'] = str_replace(';',',',substr($axml['Actor'],1,-1)) ;
	$video['area'] = $axml['AreaName'] ;
	$video['description'] = $axml['Description'] ;
	
	$video['image'] = $axml['Poster'] ;
	$video['poster'] = $axml['Poster'] ;
	$video['poster2'] = $axml['Poster2'] ;
	
	$video['year'] = $axml['PublishAge'] ;
	$video['xml'] = $s ;
	
	$video['mark'] = round($axml['Mark']) ;
	
	$video['count'] = $axml['Count'] ;
	
	$video['urls'] = array() ;
	
	$video['eps'] = array() ;
	
	$url_key = 'm3u8' ;
	
	if($sxml['http_list']['@attributes']['Count'] == 1){
		$tmp = $sxml['http_list']['source_item'] ;
		$sxml['http_list']['source_item'] = array($tmp) ;
	}
	
	if(is_array($sxml['http_list']['source_item'])){
		
		$aeps = array() ;
		foreach($sxml['http_list']['source_item'] as $vs){
			$vsa = $vs['@attributes'] ;
			if($vsa['Count'] == 1){
				$tmp = $vs['http'] ;
				$vs['http'] = array($tmp) ;
			}
			
			$doinportfirsturl = false ;
			
			foreach($vs['http'] as $v){
				if(is_array($v['series']) && count($v['series']) < 1){
					$doinportfirsturl = true ;
					$v['series'] = '0' ;
				}
				$iv = array() ;
				
				if($v['series'] == '0'){
					$iv['title'] = $v['name'] ;
				}else{
					$iv['title'] = $video['title'] . '-' . $v['name'] ;
				}
				$iv['playurl'] = $v['playurl'] ;
				$iv['url'] = $v[$url_key] ;
				$iv['stag'] = $vsa['Tag'] ;
				$iv['stagname'] = $vsa['Title'] ;
				$iv['swfurl'] = $v['swfurl'] ;
				$iv['id'] = $v['id'] ;
				$iv['series'] = $v['series'] ;
				
				foreach($iv as $iik=>$iiv){
					if(is_array($iiv) && count($iiv) < 1) $iiv = '' ;
					$iv[$iik] = $iiv ;
				}
				
				if(empty($aeps[$iv['series']]) || !is_array($aeps[$iv['series']])) $aeps[$iv['series']] = array() ;
				$aeps[$iv['series']][] = $iv ;
			}
		}
		
		foreach($aeps as $k=>$vs){
			$aep = array() ;
			foreach($vs as $v){
				$aep['title'] = $v['title'] ;
				$aep['id'] = $video['id'] . 'v' . $v['series'] ;
				$aep['series'] = $v['series'] ;
				break ;
			}
			
			$aep['urls'] = array() ;
			foreach($vs as $v){
				$iv = array() ;
				$iv['url'] = $v['url'] ;
				$iv['stag'] = $v['stag'] ;
				$iv['stagname'] = $v['stagname'] ;
				$iv['swfurl'] = $v['swfurl'] ;
				$iv['id'] = $v['id'] ;
				
				$aep['urls'][] = $iv ;
			}
			
			$video['eps'][] = $aep ;
		}
		
		if($doinportfirsturl){
			foreach($video['eps'] as $k=>$v){
				$video['urls'] = $v['urls'] ;
				unset($video['eps'][$k]) ;
				break ;
			}
		}
	}
	
	$tmp = count($video['eps']) ;
	if(empty($video['count']) && $tmp > 0) $video['count'] = $tmp ;

	$sdata[$id] = $video ;
	return $video ;
}


function get_soo_videotype($path){
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
