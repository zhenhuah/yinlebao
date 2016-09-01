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

function get_pptv_ftypes(){
	return array('tv'=>'电视剧','movie'=>'电影','cartoon'=>'动漫','show'=>'综艺') ;
}


function do_a_pptv_video($id,$ftype){
	$a = get_a_pptv_video($id,$ftype) ;
	
	if(empty($a['title'])) return ;

	$d = array() ;
	$d['rkey'] = $a['id'] ;
	$d['title'] = $a['title'] ;
	$d['director'] = str_replace('/',',',$a['directors']) ;
	$d['actor'] =  str_replace('/',',',$a['actors']) ;
	$d['tag'] =  str_replace('/',',',$a['tags']) ;
	$d['year']=substr($a['year'],0,4);
	$d['runtime'] = intval($a['duration']) ? $a['duration']:'60' ;
	$d['rating'] = '6' ;
	
	$TYPES = get_pptv_ftypes() ;
	
	$d['vcolumn'] = $TYPES[$a['type']] ;
	$d['vcate'] = $a['type'] ;
	
	$d['area'] = $a['area'] ;
	if(!empty($a['episodes_count'])) $d['total_episodes'] = $a['episodes_count'] ;
	$d['latest_episode_num'] = count($a['eps']) ;
	
	//$d['rating'] = $a['mark'] ;
	$d['description'] = $a['description'] ;
	
	
	$d['created'] = time() ;
	$d['changed'] = time() ;
	$d['ishow'] = 1 ;

	
	global $db ;
	

	$tmp = $db->r1('import_pptv',array('rkey'=>$d['rkey']),'id') ;
	if(!isset($tmp['id'])){
		$id = $d['id'] = $db->w('import_pptv',$d) ;
	}else{
		$id = $d['id'] = $tmp['id']  ;
		$db->w('import_pptv',$d,array('id'=>$id)) ;
	}
	
	
	//xml
	$xd = array() ;
	$xd['id'] = $id ;
	$xd['apiurl'] = $a['apiurl'] ;
	$xd['xml'] = $a['xml'] ;
	
	$tmp = $db->r1('import_pptv_xml',array('id'=>$id),'id') ;
	if(!isset($tmp['id'])){
		$db->w('import_pptv_xml',$xd) ;
	}else{
		$db->w('import_pptv_xml',$xd,array('id'=>$id)) ;
	}
	
	
	//图片
	$pd = array() ;
	$pd['vctype'] = 'v' ;
	$pd['vid'] = $id ;
	$pd['pictype'] = '1' ;
	$pd['path'] = $a['image'] ;
	
	$tmppd = $pd ;
	unset($tmppd['path']) ;
	$tmp = $db->r1('import_pptv_p',$tmppd,'id') ;
	if(!isset($tmp['id'])){
		$db->w('import_pptv_p',$pd) ;
	}
	
	
	if(!empty($a['url'])){
		$tmp = array() ;
		$tmp['url'] = $a['url'] ;
		$tmp['web_address'] = $a['web_address'] ;
		$a['urls'] = array($tmp) ;

	
		//他的视频增加
		foreach($a['urls'] as $iv){
			$av = array() ;
			$av['vctype'] = 'v' ;
			$av['vid'] = $id ;
			$av['path'] = $iv['url'] ;
			$av['apiurl'] = $iv['web_address'] ;
			$av['sourcename'] = 'pptv' ;
			$av['videotype'] = 'pptv' ;
			
			$tmp = $db->r1('import_pptv_v',$av,'id') ;
			if(!isset($tmp['id'])){
				$db->w('import_pptv_v',$av) ;
			}else{
				$db->w('import_pptv_v',$av,array('id'=>$tmp['id'])) ;
			}
		}
	}
	
	
	//新增视频连续剧
	if(!empty($a['eps']) && count($a['eps']) > 0){
	
		foreach($a['eps'] as $v){
			$ad = array() ;
			$ad['rkey'] = $v['id'] ;
			$ad['title'] = $v['title'] ;
			$ad['runtime'] = $d['runtime'] ;
			$ad['episode_number'] = $v['seq'] ;
			$ad['parent_id'] = $id ;
			
			$tmp = $db->r1('import_pptv_c',array('rkey'=>$ad['rkey']),'id') ;
			if(!isset($tmp['id'])){
				$ad_id = $ad['id'] = $db->w('import_pptv_c',$ad) ;
			}else{
				$ad_id = $ad['id'] = $tmp['id'] ;
				$db->w('import_pptv_c',$ad,array('id'=>$ad['id'])) ;
			}
			
			
			//图片
			$pd = array() ;
			$pd['vctype'] = 'c' ;
			$pd['vid'] = $ad_id ;
			$pd['pictype'] = '1' ;
			$pd['path'] = $v['image'] ;
			
			$tmppd = $pd ;
			unset($tmppd['path']) ;
			$tmp = $db->r1('import_pptv_p',$tmppd,'id') ;
			if(!isset($tmp['id'])){
				$db->w('import_pptv_p',$pd) ;
			}
			
			$v['urls'] = array($v) ;
			
			//他的视频增加
			foreach($v['urls'] as $iv){
				$av = array() ;
				$av['vctype'] = 'c' ;
				$av['vid'] = $ad_id ;
				$av['path'] = $iv['url'] ;
				$av['apiurl'] = $iv['web_address'] ;
				$av['sourcename'] = 'pptv' ;
				$av['videotype'] = 'pptv' ;
				
				$tmp = $db->r1('import_pptv_v',$av,'id') ;
				if(!isset($tmp['id'])){
					$db->w('import_pptv_v',$av) ;
				}else{
					$db->w('import_pptv_v',$av,array('id'=>$tmp['id'])) ;
				}
			}
		}
		
	}
	
	
}





function get_a_pptv_video($id,$ftype){
	static $sdata ;
	if(!isset($sdata)) $sdata = array() ;
	if(isset($sdata[$id])) return $sdata[$id] ;
	
	$s2id = substr($id,-2,1) ;
	$apiurl = "http://spider.api.pptv.com/open/commonMobile/{$ftype}/{$s2id}/{$id}.xml" ;
	
	$s = file_get_contents($apiurl) ;
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;
	$sxml['id'] = $sxml['@attributes']['id'] ;
	$sxml['type'] = $sxml['@attributes']['type'] ;
	$sxml['image'] =  $sxxml->image  . '' ;
	$sxml['web_address']= $sxxml->web_address  . '' ;
	$sxml['pptv_play_link']= $sxxml->pptv_play_link  . '' ;
	$sxml['directors']= $sxxml->directors  . '' ;
	$sxml['tags']= $sxxml->tags  . '' ;
	$sxml['area']= $sxxml->area  . '' ;
	$sxml['title']= $sxxml->title  . '' ;
	$sxml['year']= $sxxml->pubdate  . '' ;
	
	$sxml['url'] = $sxml['pptv_play_link'] ;
	
	$sxml['subtitle'] = '' ;
	if($sxxml->subTitle) $sxml['subtitle']= $sxxml->subTitle . '' ;
	
	
	$sxml['ftype']= $ftype ;
	
	$sxxml->tv_station = '' ;
	if(!empty($sxxml->tv_station)) $sxml['tv_station']= $sxxml->tv_station . '' ;
	
	$sxml['pubdate'] = '' ;
	if(!empty($sxxml->pubdate)){
		$sxml['pubdate']= $sxxml->pubdate . '' ;
		
		//换算成秒
		$sxml['pubdate'] = $sxml['pubdate'] * 60 ;
	}
	
	$sxml['duration'] = '' ;
	if(!empty($sxxml->duration)) $sxml['duration']= $sxxml->duration . '' ;
	
	if(!empty($sxxml->episodes_count)) $sxml['episodes_count']= $sxxml->episodes_count . '' ;
	if(!empty($sxxml->episodes_updated_count)) $sxml['episodes_updated_count']= $sxxml->episodes_updated_count . '' ;

	unset($sxml['@attributes']) ;
	
	
	$sxml['eps'] = array() ;
	if(!empty($sxxml->episodes)){
		foreach($sxxml->episodes->episode as $ep){
			$aep = array() ;
			$aep['id'] = $ep['id'] . '' ;
			$aep['seq'] = $ep['seq'] . '' ;
			
			$aep['image'] =  $ep->image . '' ;
			$aep['web_address']= $ep->web_address . '' ;
			$aep['pptv_play_link']= $ep->pptv_play_link . '' ;
			$aep['description'] = $ep->description . '' ;
			
			$aep['url'] = $aep['pptv_play_link'] ;
			
			if(!empty($ep->pubdate)){
				$aep['pubdate'] = $ep->pubdate . '' ;
			}
			
			if(!empty($ep->title)){
				$aep['title'] = $ep->title . '' ;
			}else{
				$aep['title'] = $sxml['title'] .  " - 第{$aep['seq']}集" ;
			}
			
			$sxml['eps'][] = $aep ;
		}
	}
	
	$sxml['xml'] = $s ;
	$sxml['apiurl'] = $apiurl ;
	
	$sdata[$id] = $sxml ;
	return $sxml ;
}


function get_pptv_videotype($path){
	$vss = array(
		'flv'=>array('.flv') ,
		'm3u8'=>array('.m3u8') , 
		'mp4'=>array('.mp4'),
		'segment'=>array('segment'),
		'hdpfans'=>array('hdpfans'),
		'pptv' => array('pptv') ,
	) ;
	
	foreach($vss as $k=>$vs){
		foreach($vs as $v){
			if(strpos($path,$v) === false) continue ;
			return $k ;
		}
	}
	
	return 'other' ;
}
