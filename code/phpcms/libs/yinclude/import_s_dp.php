<?php
/**
 * 导入 hdp 接口到 通用接口数据库
 */
header('Content-type: text/html; charset=utf-8');

define('PHPCMS_PATH',dirname(__FILE__). '/../') ;
define('RUN_IN_CMD',true) ;

//error_reporting(0) ;



require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_dp_common.php' ;

	yzy_sooner_db() ;
	
	global $db ;
	
	
	
	//更新视频接口
	if(!empty($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'upv' ){
		
		$db->r('import_dp_v','','*',array('func_row'=>'do_up_a_dp_v_res')) ;
		
		print 'down!' ;
		exit ;
	}
	
	
	
	//采集一个详情（多线程使用）
	if(!empty($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'doa' ){
		ignore_user_abort(true) ;
		$rkey =  $_SERVER['argv'][2] ;
		
		if(empty($rkey)) die('empty rkey') ;
		
		$tofile = dirname(__FILE__) . '/sdpdata/' .$rkey ;
		if(!file_exists($tofile)) die('file error') ;
		
		$a = json_decode(file_get_contents($tofile),true) ;
		if(empty($a['rkey'])) dir('json error') ;
		
		foreach($a as $k=>$v) $a[$k] = $v . '' ;

		doa($a) ;

		unlink($tofile) ;
		print 'down!' ;
		exit ;
	}
	
		
	$vcolumnss = array('电影','电视剧','综艺','动漫') ;
	if(isset($_SERVER['argv'][1]) && strpos($_SERVER['argv'][1],'a') === 0){
		$tmpn = substr($_SERVER['argv'][1],1) ; 
		if(isset($vcolumnss[$tmpn]))  $vcolumnss = array($vcolumnss[$tmpn]) ;
	}
	
	$f1 = 'http://tv1.hdpfans.com/~rss.get.channel/site/hdp/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e' ;
	$sxml = afget($f1) ;
	$sxml = simplexml_load_string($sxml) ;
	$sxml = json_decode(json_encode($sxml),true) ;
	
	$types = $sxml['foobar'] ;
	
	//每个类型的类别
	$cates = array() ;
	foreach($types as $k=>$v){
		$sxml = afget($v['url']) ;
		$sxml = simplexml_load_string($sxml) ;
		
		$sxml = json_decode(json_encode($sxml),true) ;
		
		$cates[$k] = array('t'=>$v,'l'=>$sxml['foobar']) ;
		
	}

	
	foreach($cates as $k=>$v){
		$vcolumn = $v['t']['name'] ;
	
		//if(!in_array($vcolumn,array('电视剧','综艺','动漫'))) continue ;
		if(!in_array($vcolumn,$vcolumnss)) continue ;
	
		foreach($v['l'] as $iv){
			$aiv = array() ;
			$aiv['vcolumn'] = $vcolumn ;
			$aiv['vcate'] = $iv['name'] ;
			$aiv['url'] = $iv['url'] ;
			
			//采集列表
			dolist($aiv) ;
		}
	}
	
	
//采集一个列表
function dolist($list){
	$sxml = afget($list['url']) ; 
	//计算出要采集多少页
	
	$sxml = simplexml_load_string($sxml) ;
	$sxml = json_decode(json_encode($sxml),true) ;
	
	//第一页采集
	$page_count = $sxml['INFO']['PAGECOUNT'] ;
	
	//$page_count = 1 ;
	
	for($p=1;$p<=$page_count;$p++){
	
		if($p != 1){
			$sxml = afget($list['url'] . '/page/' . $p) ; 
			$sxml = simplexml_load_string($sxml) ;
			$sxml = json_decode(json_encode($sxml),true) ;
		}
		
		
		$alist = array() ;
		foreach($sxml['ROWS']['foobar'] as $v){
			$iv = array() ;
			$iv['url'] = $v['link'] ;
			$iv['title'] = $v['name'] ;
			$iv['rkey'] = md5($v['link']) ;
			
			$iv['vcolumn'] = $list['vcolumn'] ;
			$iv['vcate'] = $list['vcate'] ;
			
			$alist[] = $iv ;
		}
		
		doalist($alist) ;
		
		vplog("{$list['vcolumn']}\t{$list['vcate']}\t$p\tdown !") ;
	}
}

//专门采集一个url的各项资源，包含判断是否采集
function doalist($listu){
	global $db ;
	
	foreach($listu as $v){
		//判断有没有采集过
		$tmp = $db->r1('import_dp',array('rkey'=>$v['rkey']),'*') ;
		if(isset($tmp['id'])){
			$v['id'] = $tmp['id'] ;
			
			//continue ;
		}
		
		//根据栏目，来判断是否重复
		$atmp = $db->r1('import_dp',array('title'=>$v['title'],'vcolumn'=>$v['vcolumn'])) ;
		
		//有重复的，那么增加tag
		if(isset($atmp['id'])){
			$tags = explode(',',$atmp['tag']) ;
			if(!in_array($v['vcate'],$tags)){
				$tags[] = $v['vcate'] ;
				$tags = join(',',$tags) ;
				$db->w('import_dp',array('tag'=>$tags),array('id'=>$atmp['id'])) ;
			}
			
			
			$tmpxml = $db->r1('import_dp_xml',array('id'=>$atmp['id'])) ;
			
			$v['tag'] = $tags ;
			$v['id'] = $atmp['id'] ;
			$v['url'] = $tmpxml['apiurl'] ;
			//continue ;
		}
		//$db->w('import_dp',array('rkey'=>$v['rkey'],'title'=>$v['title'],'vcolumn'=>$v['vcolumn'],'vcate'=>$v['vcate'],'tag'=>$v['vcate'])) ;
		
		//$todir = dirname(__FILE__) . '/sdpdata/' ;
		//if(!is_dir($todir)) mkdir($todir,0777,true) ;
		
		//file_put_contents($todir . $v['rkey'] , json_encode($v)) ;

		//print "php " . __FILE__  . " doa {$v['rkey']} &" ;
		//pclose(popen("php " . __FILE__  . " doa {$v['rkey']} &",'r')) ; 

		//vplog("popen {$v['url']}") ;
		doa($v) ;
	}
}


//专门采集一个url资源
function doa($a){
	global $db ;

	$s = afget($a['url']) ;
	
	if(!$s){
		vplog("----sorry empty {$a['url']}") ;
	}
	
	$sxxml = simplexml_load_string($s) ;
	$sxml = json_decode(json_encode($sxxml),true) ;
	
	foreach($sxml as $k=>$v){
		if(is_array($v) && count($v) == 1 && isset($v[0])){
			$v[0] = trim($v[0]) ;
			if($v[0] == '')$sxml[$k] = '' ;
		}
	}
	
	if(empty($a['tag'])) $a['tag'] = $a['vcate'] ;
	
	$vi = array() ;
	$vi['title'] = $a['title'] ;
	$vi['rkey'] = $a['rkey'] ;
	$vi['vcolumn'] = $a['vcolumn'] ;
	$vi['vcate'] = $a['vcate'] ;
	$vi['tag'] = $a['tag'] ;
	$vi['runtime'] = '' ;
	
	$vi['director'] =  str_replace('|',',',$sxml['director']) ;
	$vi['actor'] = str_replace('|',',',$sxml['actor']) ;
	$vi['description'] = $sxml['desc'] ;
	
	if(substr($vi['director'],-1,1) == ',') $vi['director'] = substr($vi['director'],0,-1) ;
	if(substr($vi['actor'],-1,1) == ',') $vi['actor'] = substr($vi['actor'],0,-1) ;
	
	$vi['year'] = '' ;
	$vi['area'] = '' ;
	
	$vi['total_episodes'] = '' ;
	$vi['latest_episode_num'] = '' ;
	$vi['rating'] = '' ;
	
	if(isset($a['id'])) $vi['id'] = $a['id'] ;

	
	if(!isset($vi['id'])){
		$vi['created'] = time() ;
		$vi['id'] = $db->w('import_dp',$vi) ;
		$db->w('import_dp_xml',array('id'=>$vi['id'])) ;
	}
	
	$vi['changed'] = time() ;
	$vi['ishow'] = 0 ;
	$db->w('import_dp',$vi,array('id'=>$vi['id'])) ;
	
	$vixml = array() ;
	$vixml['xml'] = $s ;
	$vixml['apiurl'] = $a['url'] ;
	$db->w('import_dp_xml',$vixml,array('id'=>$vi['id'])) ;
	
	//先检测
	$more_url = false ;
	if($vi['vcolumn'] == '电影') $more_url = true ;
	
	
	//再次修改 video 的内容
	$video = array() ;
	
	//视频资源
	$vpaths = array() ;
	
	//分集资源
	$cvis = array() ;
	
	//照片资源
	$ppaths = array() ;
	
	
	
	$ap = array() ;
	$ap['vctype'] = 'v' ;
	$ap['vid'] = $vi['id'] ;
	$ap['pictype'] = '' ;
	$ap['path'] = get_dp_pic_path($sxml['img']) ;
	
	$ppaths[] = $ap ;
	
	//电影
	
	while($more_url){
		if(isset($sxml['url']['foobar']['url'])){
			$v = $sxml['url']['foobar'] ;
			$iiv = array() ;
			$iiv['vctype'] = 'v' ;
			$iiv['vid'] = $vi['id'] ;
			
			$iiv['apiurl'] = $v['url'] ;
			
			//如果存在视频链接，则不继续更新
			$tmp = $db->r1('import_dp_v',$iiv) ;
			if(isset($tmp['id'])){
				vplog("{$iiv['vid']} \t {$iiv['vctype']} \t in db") ;
				break ;
			}
			
			$aas = get_a_video_res_arr($v['url'],$v['name']) ;
			
			foreach($aas as $tmp){
				$tmpiiv = $iiv ;
				foreach($tmp as $tmpk=>$tmpv){
					$tmpiiv[$tmpk] = $tmpv ;
				}
				$vpaths[] = $tmpiiv ;
			}
			
			
		}else{
			foreach($sxml['url']['foobar'] as $k=>$v){
				$iiv = array() ;
				$iiv['vctype'] = 'v' ;
				$iiv['vid'] = $vi['id'] ;
				
				$iiv['apiurl'] = $v['url'] ;
				
				$aas = get_a_video_res_arr($v['url'],$v['name']) ;
				foreach($aas as $tmp){
					$tmpiiv = $iiv ;
					foreach($tmp as $tmpk=>$tmpv){
						$tmpiiv[$tmpk] = $tmpv ;
					}
					$vpaths[] = $tmpiiv ;
				}
				
			}
		}
		
		
		break ;
	}
	
	
	//非电影
	if(!$more_url){
		$stitle = str_replace(array('全集','1','2','3','4','5','6'),'',$vi['title']) ;
		
		foreach($sxml['url']['foobar'] as $k=>$v){
			if(is_array($v)){
				
				$tmpvname = $v['name'] ;
				if(strpos($v['name'],$stitle) === false) $v['name'] = $vi['title'] . ' - ' . $v['name'] ;
				
				$aep = array() ;
				$aep['title'] = $v['name'] ;
				$aep['runtime'] = '' ;
				$aep['episode_number'] = $k + 1 ;
				$aep['url'] = $v['url'] ;
				$aep['parent_id'] = $vi['id'] ;
				
				
				if($vi['vcolumn'] == '综艺') $aep['episode_number'] = trim(str_replace(array($stitle),'',$tmpvname)) ;
				
				$cvis[] = $aep ;
				
				if($video['total_episodes'] == 0){
					$video['total_episodes'] = count($sxml['url']['foobar']) ;
				}
			}
			
		}
		
	}
	
	
	//分集资源导入
	
	//先清空
	//$db->d('import_dp_c',array('parent_id'=>$vi['id'])) ;
	foreach($cvis as $k=>$v){
		$av = $v ;
		unset($av['url']) ;
		
		$av['rkey'] = md5($v['url']) ;
		$tmp = $db->r1('import_dp_c',array('rkey'=>$av['rkey'])) ;
		if(!isset($tmp['id'])) $v['id'] = $db->w('import_dp_c',$av) ;
		else $v['id'] = $tmp['id'] ;
		
		$db->w('import_dp_c',$av,array('id'=>$v['id'])) ;
		
		$axml = array() ;
		$axml['apiurl'] = $v['url'] ;
		$axml['id'] = $v['id'] ;
		$axml['xml'] = afget($v['url']) ;
		$db->w('import_dp_c_xml',$axml) ;
		 
		
		$iiv = array() ;
		$iiv['vctype'] = 'c' ;
		$iiv['vid'] = $v['id'] ;
		$iiv['apiurl'] = $v['url'] ;
		
		//如果存在视频链接，则不继续更新
		$tmp = $db->r1('import_dp_v',$iiv) ;
		if(isset($tmp['id'])){
			vplog("{$iiv['vid']} \t {$iiv['vctype']} \t in db") ;
			continue ;
		}
	
		$aas = get_a_video_res_arr($v['url'],'',trim($axml['xml'])) ;
		foreach($aas as $tmp){
			$tmpiiv = $iiv ;
			foreach($tmp as $tmpk=>$tmpv){
				$tmpiiv[$tmpk] = $tmpv ;
			}
			$vpaths[] = $tmpiiv ;
		}
		
	}
	
	//视频资源导入
	$invpaths = array() ;
	foreach($vpaths as $v){
		if(!isset($invpaths[$v['vctype']][$v['vid']])) $invpaths[$v['vctype']][$v['vid']] = array() ;
		$invpaths[$v['vctype']][$v['vid']][] = $v ;
	}
	
	foreach($invpaths as $vk=>$vss){
		foreach($vss as $k=>$vs){
			$db->d('import_dp_v',array('vid'=>$k,'vctype'=>$vk)) ;
			foreach($vs as $v){
				$db->w('import_dp_v',$v) ;
			}
		}
	}
	
	
	//图片资源导入
	$inppaths = array() ;
	foreach($ppaths as $v){
		if(!is_array($invpaths[$v['vctype']][$v['vid']])) $inppaths[$v['vctype']][$v['vid']] = array() ;
		$inppaths[$v['vctype']][$v['vid']][] = $v ;
	}
	
	foreach($inppaths as $vk=>$vss){
		foreach($vss as $k=>$vs){
			$db->d('import_dp_p',array('vid'=>$k,'vctype'=>$vk)) ;
			foreach($vs as $v){
				$db->w('import_dp_p',$v) ;
			}
		}
	}
	
	$video['ishow'] = 1 ;
	if(count($video) > 0){
		$db->w('import_dp',$video,array('id'=>$vi['id'])) ;
	}
	
	
	//视频资源完整性检查
	$trv = array() ;
	if($more_url){
		$trv['vctype'] = 'v' ;
		$trv['vid'] = $vi['id'] ;
	}else{
		$trv['vctype'] = 'c' ;
		$trv['vid'] = array_keys($db->r('import_dp_c',array('parent_id'=>$vi['id']),'id',array('r_key'=>'id'))) ;
	}
	
	$trs = $db->r('import_dp_v',$trv) ;
	foreach($trs as $v){
		$v['path'] = trim($v['path']) ;
		if($v['path']) continue ;
		vplog('retry video res') ;
		do_up_a_dp_v_res($v) ;
	}
	
	//视频资源完整性再次检查
	foreach($trs as $v){
		$v['path'] = trim($v['path']) ;
		if($v['path']) continue ;
		
		vplog("video res {$v['id']}\t{$v['vctype']}\tishow=4") ;
		$db->w('import_dp',array('ishow'=>'4'),array('id'=>$vi['id'])) ;
		break ;
	}
	
}


