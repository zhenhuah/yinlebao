<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

//导入HDP的apk资源
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/pager.php' ;

define('YTEMPLATE_ROOT',dirname(__FILE__).'/templates/y/') ;

error_reporting(0) ;
set_time_limit(60000) ;

class importgapk extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
	}

	public function init() {

	}

	public function showOK(){
		$r = array() ;
		$r['tp'] = 'template' ;
		$r['tpl'] = 'showok.tpl' ;
		$r['data'] = $video ;
		return y_do_r($r) ;
	}

	/**
	 * 资源列表
	 */
	public function apklist() {
		$r = array() ;
		$r['tp'] = 'template' ;
		$r['tpl'] = 'importgapk_list.tpl' ;
		$r['data'] = &$d ;


		$apiurl = request('url','GET') ;
		$w = request('w','GET','array') ;
		
		$shopdb = yzy_shop_db() ;
		$base_data = $this->getHDPBaseData();
		//$base_data[type] = array_slice($base_data['type'], 0, 3);
		$datatype = array_slice($base_data['cate'], 0, 1);

		foreach ($datatype as $v){
			$data = array_slice($v['l'], 0, 1);
			if(!$apiurl) $apiurl = $data['0']['url'];
			if(!$w) $w['type'] = $data['0']['name'];
		}
		
		$d = array() ;
		$d['w'] = $w ;
		$d['apiurl'] = $apiurl ;
		$d['base_data'] = $base_data ;

		if(empty($apiurl)) return y_do_r($r) ; ;

		$p = request('p','GET','int') ;
		if(!$p) $p = 1 ;

		$w['p'] = $p ;
		$apiurl .= '/page/' . $p ;

		$sxml = simplexml_load_file($apiurl) ;
		$sxml = json_decode(json_encode($sxml),true) ;

		$link = $_GET ;
		if(isset($link['p'])) unset($link['p']);
		$link = str_replace('/index.php','/',$_SERVER['SCRIPT_NAME']).(http_build_query($link) == '' ? '' : '?'.http_build_query($link) );


		$pager_args = array('link'=>$link,'count'=>$sxml['INFO']['COUNT'],'pagesize'=>$sxml['INFO']['PAGESIZE'],'index'=>$p,'get_name'=>'p') ;
		$d['pager'] = get_pager($pager_args);


		$d['list'] = array() ;
		foreach($sxml['ROWS']['foobar'] as $v){
			$iv = array() ;
			$iv['sid'] = $this->getVID($v) ;
			$iv['title'] = $v['name'] ;
			$iv['image'] = $v['img'] ;
			$iv['url'] = $v['link'] ;
			$iv['type'] = $this->getclum($v['link']);
			$d['list'][] = $iv ;
		}

		$ids = array() ;
		foreach($d['list'] as $v) $ids[] = $v['sid'] ;
		//是否已导入
		$d['indb_list'] = $shopdb->r('app',array('sid'=>$ids),'sid',array('r_key'=>'sid')) ;


		$d['base_data'] = $base_data ;
		$d['w'] = $w ;

		return y_do_r($r) ;
	}

	//详细显示
	public function hdpdetail() {
		$shopdb = yzy_shop_db() ;

		$url = request('url','GET') ;
		if(!$url) return false ;

		$d = array() ;
		$d['video'] = $this->getAVideo($url) ;

		$r = array() ;
		$r['tp'] = 'template' ;
		$r['tpl'] = 'importgapk_detail.tpl' ;
		$r['data'] = $d ;

		return y_do_r($r) ;
	}

	//资源导入
	public function hdpimportdetail() {
		$shopdb = yzy_shop_db() ;

		//$phpcmsdb->setDebug('write',true) ;

		$id = request('url','GET') ;
		if(!$id) return false ;

		$video = $this->getAVideo($id) ;
		$post_d = request('d','POST','array',false) ;
		if($post_d){
			foreach($post_d as $k=>$v){
				$video[$k] = $v ;
			}
		}
		$column_id = $this->getColumnId($video['type']) ;

		$d = array(
			'app_name'   	    => $video['name'], 
			'sid'      			=> $video['id'], 
			'app_desc'      	=> $video['description'],
			'channel_cat_id'    => $video['channel_cat_id'], 
			'packagename' 	    => $video['packagename'],
			'os_ver'			=> $video['os_ver'],
			'create_time'   	=> date('Y-m-d H:i:s',time()), 
			'update_time' 		=> date('Y-m-d H:i:s',time()), 
			'channel'      		=> $video['channel'],
			'type' 				=> $video['channel_cat_id']=13?'game':'app',
			'version' 	 		=> $video['version'],
			'file_size' 	 	=> $video['file_size'],
			'source' 	   		=> 'shafa',
			'status' 	   		=> '1',
			'ANDROID '			=> $video['ANDROID']
		);

		$thisv = $shopdb->r1('app',array('packagename'=>$d['packagename']),'app_id') ;
		$thisv_id = 0 ;
		if(isset($thisv['app_id'])){
				$thisv_id = $thisv['app_id'] ;
				$d['update_time'] = date('Y-m-d H:i:s',time());
				$shopdb->w('app',$d,array('app_id'=>$thisv_id)) ;
		}else{
			//新增吧...
			$shopdb->w('app',$d) ;
		}
		//主要apk信息入库完毕

		//apk下载链接入库
		$thisv = $shopdb->r1('app',array('packagename'=>$d['packagename']),'app_id') ;
		if(!empty($video['urls'])){
			$shopdb->d('app_download_info',array('app_id'=>$thisv['app_id'])) ;
			foreach($video['urls'] as $aurl){
				$down_url = $aurl['url'];
				$aurl['url'] = $this->getVideoRURL($aurl['url']) ;
				$tmp = $shopdb->r1('app_download_info',array('app_id'=>$thisv['app_id'],'install_file'=>$aurl['url'])) ;
				
					$cd = array(
						'app_id'  	=> $thisv['app_id'], 
						'term_id'   => $video['term_id'] ,  
						'os_type'   => '1', 
						'install_file' => $aurl['url'],
						'down_url' => $down_url
					);
					if(!empty($aurl['url'])){
						if($tmp){
							$shopdb->w('app_download_info',$cd,array('app_id'=>$thisv['app_id'])) ;
						}else{
							$shopdb->w('app_download_info',$cd) ;
						}
					}
			}
		}
		//apk图片入库
		$shopdb->d('app_image',array('app_id'=>$thisv['app_id'])) ;
			//坏图片
			if(!strpos($video['image'],'.')) continue ;			
			$im = explode("|",$video['image']);
			$im = array_slice($im, 0, 2);
			$cd0 = array(
				'app_id'   	 => $thisv['app_id'],
				'image_file' => $im['0'],
				'image_type' => '122'
			);
			$cd1 = array(
					'app_id'   	 => $thisv['app_id'],
					'image_file' => $im['1'],
					'image_type' => '103'
			);
			$tmp0 = $shopdb->r1('app_image',array('app_id'=>$thisv['app_id'],'image_file'=>$im['0']),'app_id') ;
			if(isset($tmp0['app_id'])){
				$shopdb->w('app_image',$cd0,array('app_id'=>$thisv['app_id'],'image_file'=>$im['0'])) ;
			}else{
				$shopdb->w('app_image',$cd0) ;
			}
			$tmp1 = $shopdb->r1('app_image',array('app_id'=>$thisv['app_id'],'image_file'=>$im['1']),'app_id') ;
			if(isset($tmp1['app_id'])){
				$shopdb->w('app_image',$cd1,array('app_id'=>$thisv['app_id'],'image_file'=>$im['1'])) ;
			}else{
				$shopdb->w('app_image',$cd1) ;
			}				
			//主视频资源内容全部导入
			if(request('showok','POST')){
				$r = array() ;
				$r['tp'] = 'template' ;
				$r['tpl'] = 'showok.tpl' ;
				$r['data'] = $video ;
				return y_do_r($r) ;
			}
			return y_do_r('ok') ;
	}

	//获得分类
	function getColumnId($name){

		if($name == '影视') $name = '影视音乐' ;
		if($name == '游戏') $name = '棋牌休闲' ;
		if($name == '软件') $name = '系统工具' ;

		if($name) 	return $name ;
		return 0 ;
	}

	public function getAVideo($url){
		static $sdata ;
		if(!isset($sdata)) $sdata = array() ;
		if(isset($sdata[$url])) return $sdata[$url] ;


		$s = $this->fget($url) ;
		$sxxml = simplexml_load_string($s) ;
		$sxml = json_decode(json_encode($sxxml),true) ;
		
		foreach($sxml as $k=>$v){
			if(is_array($v) && count($v) == 1 && isset($v[0])){
				$v[0] = trim($v[0]) ;
				if($v[0] == '')$sxml[$k] = '' ;
			}
		}

		$sxml['link'] = $url ;

		$video = array() ;
		$arr=explode('|',$sxml['name']);
		$video['name'] =$arr[0] ;		
		$arrpage=explode('|',$sxml['url']['foobar']['name']);
		$paurl = substr($arrpage['5'],0,strrpos($arrpage['5'],'.apk?'));
		$parr2=explode('/',$paurl);
		$num = strrpos($parr2['4'],".");
		$video['packagename'] = substr($parr2['4'],0,$num);
		//$video['packagename'] =$arr[5] ;
		$video['id'] = $this->getVID($sxml) ;
		$video['type_id'] = '' ;
		$video['tags'] = '' ;
		$video['run_time'] = '' ;

		$video['directors'] =  str_replace('|',',',$sxml['director']) ;
		$video['actors'] = str_replace('|',',',$sxml['actor']) ;
		$video['description'] = $sxml['desc'] ;
		$b = explode(",",$video['directors']);

		if(strpos($video['actors'],'遥控器')!== false){
			$video['ANDROID']='1' ;
			$video['term_id'] = $video['term_id'].','.'1';
		}	
		if(strpos($video['actors'],'鼠标')!== false){
			$video['ANDROID']='1' ;
			$video['term_id'] = $video['term_id'].','.'2';
		}
		if(strpos($video['actors'],'手柄')!== false){
			$video['ANDROID']='1' ;
			$video['term_id'] = $video['term_id'].','.'4';
		}
		$video['term_id'] = substr($video['term_id'],1,strlen($video['term_id']));
		$video['file_size'] = $b['0'];
		$video['version'] = $b['1'];
		$video['os_ver'] = $b['2'];
		$video['image'] = $sxml['img'] ;
		$video['poster'] = $sxml['img'] ;
		$video['link'] = $sxml['link'] ;
		$video['url'] = $sxml['link'] ;
		if(strpos($video['link'],'video')!== false){
			$video['channel']='影视音乐' ;
			$video['channel_cat_id']='2';
		}
		if(strpos($video['link'],'game')!== false){
			$video['channel']='棋牌休闲' ;
			$video['channel_cat_id']='13';
		}
		if(strpos($video['link'],'app')!== false){
			$video['channel']='系统工具' ;
			$video['channel_cat_id']='19';
		}
		$tmp = request('t','GET') ;
		if($tmp){
			$video['type'] = $tmp  ;
			$video['column_id'] = $this->getColumnId($tmp) ;
		}
		if(($video['os_ver']&&strpos($video['os_ver'],'Android')!== false)){
			$video['os_type']='1';
		}elseif(($video['os_ver']&&strpos($video['os_ver'],'Ios')!== false)){
			$video['os_type']='2';
		}
		//先检测
		$more_url = false ;
		$video['count'] = 0 ;
		$video['urls'] = array() ;
		$video['eps'] = array() ;

		if(!$more_url){
			foreach($sxml['url']['foobar'] as $k=>$v){
				if(is_array($v)){						
					$v['link'] = $v['url'] ;						
					$aep = array() ;
					$aep['name'] = $v['name'] ;
					$aep['id'] = $this->getVID($v) ;
					$aep['series'] = $v['name'] ;
					$aep['url'] = $v['link'] ;
												
					$aep['urls'] = array() ;						
					$iiv = array() ;
					$iiv['url'] = $v['url'] ;
					$iiv['id'] = '' ;
						
					$aep['urls'][] = $v ;

					$video['eps'][] = $aep ;

					if($video['count'] == 0){
						$video['count'] = count($sxml['url']['foobar']) ;
					}
				}else{
					$iiv = array() ;
					$iiv['url'] = $sxml['url']['foobar']['url'] ;
					$iiv['id'] = '' ;
						
					$video['urls'][] = $iiv ;						
					break ;
				}
			}				
		}else{
			foreach($sxml['url']['foobar'] as $k=>$v){
				$v['link'] = $v['url'] ;

				$iiv = array() ;
				$iiv['url'] = $v['url'] ;
				$iiv['id'] = $this->getVID($v) ;
				$iiv['stagname'] = $v['name'] ;

				$video['urls'][] = $iiv ;
			}
		}
		$sdata[$url] = $video ;
		return $video ;
	}

	function getVideoRURL($url){
		set_time_limit(1200) ;
		$sxml = simplexml_load_file($url) ;
		$sxml = json_decode(json_encode($sxml),true) ;

		return $sxml['url']['foobar'] ;
	}

	function getVID($v){
		$vid = 'hdp'.substr(md5($v['link']),0,15);
		return  $vid;
	}

	function getHDPBaseData(){
		//type
		$shopdb = yzy_shop_db() ;
		$m_key = 'hdpgapka_base_data' ;
		$d = $shopdb->getM($m_key) ;
		if(!empty($d)) return $d ;

		$d = array() ;

		//类型(应用商店http://app.shafa.com/list/tv)
		$f1 = 'http://tv4.hdpfans.com/~rss.get.channel/site/shafa/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e' ;
		$sxml = $this->fget($f1) ;
		$sxml = simplexml_load_string($sxml) ;

		$sxml = json_decode(json_encode($sxml),true) ;

		$d['type'] = $sxml['foobar'] ;
		//每个类型的类别
		$d['cate'] = array() ;
		foreach($d['type'] as $k=>$v){
			$sxml = $this->fget($v['url']) ;
			$sxml = simplexml_load_string($sxml) ;
				
			$sxml = json_decode(json_encode($sxml),true) ;	
			$d['cate'][$k] = array('t'=>$v,'l'=>$sxml['foobar']) ;
				
		}
		$shopdb->setM($m_key,$d,3600) ;
		return $d ;
	}


	function fget($u){
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>6000,
		)
		);

		$context = stream_context_create($opts);
		return file_get_contents($u, false, $context);
	}
	function getclum($u) {
		if (strpos($u, '/video')!==false) {
			$type = 'video';
		}elseif (strpos($u, 'game')!==false) {
			$type = 'game';
		}elseif (strpos($u, 'app')!==false) {
			$type = 'app';
		}else{
			$type = '';
		}
		return $type;
	}
}


