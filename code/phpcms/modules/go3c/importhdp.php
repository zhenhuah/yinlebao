<?php 	
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

//导入HDP的资源
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/pager.php' ;

define('YTEMPLATE_ROOT',dirname(__FILE__).'/templates/y/') ;

error_reporting(0) ;
set_time_limit(60000) ;

class importhdp extends admin {

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
	public function hdplist() {
		$r = array() ;
		$r['tp'] = 'template' ;
		$r['tpl'] = 'importhdp_list.tpl' ;
		$r['data'] = &$d ;
		
		
		$apiurl = request('url','GET') ;
		$w = request('w','GET','array') ;
		if(!$apiurl) $apiurl='http://tv1.hdpfans.com/~rss.get.film_xml/site/hdp/channel/tv/category/%25E6%259C%2580%25E7%2583%25AD/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e';
		$phpcmsdb = yzy_phpcms_db() ; 
		$base_data = $this->getHDPBaseData();
		$base_data[type] = array_slice($base_data['type'], 1, 3);
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
			$iv['id'] = $this->getVID($v) ;
			$iv['title'] = $v['name'] ;
			$iv['poster'] = $v['img'] ; 
			$iv['url'] = $v['link'] ;
			$iv['image'] = $iv['poster'] ;
			$iv['type'] = $this->getclum($v['link']);
			$d['list'][] = $iv ;
		}
		
		$ids = array() ;
		foreach($d['list'] as $v) $ids[] = $v['id'] ;
		//是否已导入
		$d['indb_list'] = $phpcmsdb->r('v9_video',array('asset_id'=>$ids),'asset_id',array('r_key'=>'asset_id')) ;
		

		$d['base_data'] = $base_data ;
		$d['w'] = $w ;
		
		return y_do_r($r) ;
	}
	
	//详细显示
	public function hdpdetail() {
		$phpcmsdb = yzy_phpcms_db() ; 
		
		$url = request('url','GET') ;
		if(!$url) return false ;

		$d = array() ;
		$d['video'] = $this->getAVideo($url) ;
		
		$r = array() ;
		$r['tp'] = 'template' ;
		$r['tpl'] = 'importhdp_detail.tpl' ;
		$r['data'] = $d ;
		
		return y_do_r($r) ;
	}
	
	
	//分集显示
	public function hdpdetaillist() {
		$phpcmsdb = yzy_phpcms_db() ; 
		
		$url = request('url','GET') ;
		if(!$url) return false ;

		$d = array() ;
		$d['video'] = $this->getAVideo($url) ;

		$ids = array() ;
		foreach($d['video']['eps'] as $v) $ids[] = $v['id'] ;
		//是否已导入
		$d['indb_list'] = $phpcmsdb->r('v9_video',array('asset_id'=>$ids),'asset_id',array('r_key'=>'asset_id')) ;
		
		$r = array() ;
		$r['tp'] = 'template' ;
		$r['tpl'] = 'importhdp_detaillist.tpl' ;
		$r['data'] = $d ;
		
		return y_do_r($r) ;
	}
	
	
	//资源导入
	public function hdpimportdetail() {
		$phpcmsdb = yzy_phpcms_db() ; 
		
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
		//$column_id = $video['column_id'] ;
		if($column_id=='0') $column_id=5;
		$ispackage = 0 ;
		if(!empty($video['count'])) $ispackage = 1 ;
		//处理tag为空
		$tag_arr=array('记录','时尚','戏曲','法制','财经','军事 ','时尚 ','文艺 ','体育','娱乐','访谈','新闻','生活','科教 ','综艺');
		$rand_keys = array_rand($tag_arr, 2);
		if(empty($video['tags'])&&$column_id==3) $video['tags'] = $tag_arr[$rand_keys[0]];
		//spid to do ..
		$spid = $this->getSPId() ;		
		$d = array(
			'title'   	    => $video['title'], 
			'asset_id'      => $video['id'], 
			'director'      => $video['directors'], 
			'actor' 	    => $video['actors'],
			'short_name'	=> $video['title'],
			'tag'   	    => $video['tags'] , 
			'year_released' => '' ,
			'run_time'      => intval($video['run_time']) ? $video['run_time']:'60',
			'column_id' 	=> $column_id,
			'active' 	 	=> '1',
			'area_id' 	 	=> '1',
			'is_free' 	    => '1',
			'total_episodes'=> '',
			'parent_id' 	=> '',
			'episode_number' => '',
			'latest_episode_num' => '' ,
			'rating'      	=> 6 ,
			'ispackage'   	=> $ispackage ,
			'catid'   	 	=> '54', 
			'status'     	=> '99',
			'sysadd'     	=> '1',
			'username'   	=> get_uname_by_sid(get_my_sid()) ,
			'updatetime' 	=> time() ,
			'spid'		=> $spid,
		);
		
		
		if($ispackage){
			$d['total_episodes'] = $video['count'] ;
			$d['latest_episode_num'] = $video['count'] ;
		}
		
		$thisv = $phpcmsdb->r1('v9_video',array('asset_id'=>$d['asset_id']),'id,published,online_status') ;
		if(isset($thisv['published']) && strcmp($thisv['published'] ,'1') == 0){
			//已经更新过了
			return ;
		}
		
		$dd = array(
			'short_desc' => $video['title'], 
			'long_desc'  => $video['description'] 
		);
		
		$thisv_id = 0 ;
		if(isset($thisv['id'])){
			if(in_array($thisv['online_status'],array('3','0','1','99'))){    
				//3 编辑未通过，0或1 导入，99 错误  这三种状态的数据会在重新导入后被覆盖
				$thisv_id = $thisv['id'] ;
				$d['online_status'] = '1';
				$phpcmsdb->w('v9_video',$d,array('id'=>$thisv_id)) ;
				$phpcmsdb->w('v9_video_data',$dd,array('id'=>$thisv_id)) ;
			}
		}else{
			//新增吧...
			$d['inputtime'] = time() ;
			$thisv_id =  $phpcmsdb->w('v9_video',$d) ;
			$dd['id'] = $thisv_id ;
			$phpcmsdb->w('v9_video_data',$dd) ;
		}		
		//主要视频信息入库完毕
				
		//视频链接入库
		if(!empty($video['urls'])){

			$phpcmsdb->d('v9_video_content',array('asset_id'=>$video['id'])) ;
			foreach($video['urls'] as $aurl){
				$sourceurl = $aurl['url'];
				$source_id = $this->getSourceId($aurl['stagname']) ;
				$aurl['url'] = $this->getVideoRURL($aurl['url']) ;

				$video['clarity'] = 3 ;

				$video_content_id = 0 ;
				$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$video['id'],'path'=>$aurl['url'],'clarity'=>$video['clarity'])) ;
				if(isset($tmp['id'])) $video_content_id = $tmp['id'] ;
				if(is_array($aurl['url'])){    //分段播放链接处理
					foreach ($aurl['url'] as $vurl){
					if((strpos($vurl,'xunlei')!== false)||(strpos($vurl,'http://.flv')!== false)||(strpos($vurl,'pptv.vod')!== false)||(strpos($vurl,'m1905')!== false)) continue ;
					if((strpos($vurl,'g3.letv.com/vod')!== false)||(strpos($vurl,'g3.letv.cn/vod')!== false)) $source_id=27 ;
					$cd = array(
						'title'   	=> $video['id'].'-'.$video['title'].'-'.$video['clarity'] , 
						'asset_id'  => $video['id'], 
						'path'   	=> $vurl ,
						'clarity'   => $video['clarity'],  
						'catid'   	=> '64', 
						'status'    => '99',
						'sysadd'    => '1',
						'username'  => get_uname_by_sid(get_my_sid()) ,
						'updatetime'=> time(),
						'source_id' => $source_id ,
						'section' => '2' ,
						'sourceurl' => $sourceurl
					);
						if(!empty($vurl)){
							if($video_content_id){
								$phpcmsdb->w('v9_video_content',$cd,array('id'=>$video_content_id)) ;
							}else{
								$cd['inputtime'] = time() ;
								$phpcmsdb->w('v9_video_content',$cd) ;
								//$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
							}
						}
					}
				}else{
					if((strpos($aurl['url'],'xunlei')!== false)||(strpos($aurl['url'],'http://.flv')!== false)||(strpos($aurl['url'],'pptv.vod')!== false)||(strpos($aurl['url'],'m1905')!== false)) continue ;
					if((strpos($aurl['url'],'g3.letv.com/vod')!== false)||(strpos($aurl['url'],'g3.letv.cn/vod')!== false)) $source_id=27 ;
					$cd = array(
						'title'   	=> $video['id'].'-'.$video['title'].'-'.$video['clarity'] , 
						'asset_id'  => $video['id'], 
						'path'   	=> $aurl['url'] ,  
						'clarity'   => $video['clarity'],  
						'catid'   	=> '64', 
						'status'    => '99',
						'sysadd'    => '1',
						'username'  => get_uname_by_sid(get_my_sid()) ,
						'updatetime'=> time(),
						'source_id' => $source_id ,
						'section' => '1' ,
						'sourceurl' => $sourceurl
					);
					if(!empty($aurl['url'])){
						if($video_content_id){
							$phpcmsdb->w('v9_video_content',$cd,array('id'=>$video_content_id)) ;
						}else{
							$cd['inputtime'] = time() ;
							$video_content_id = $phpcmsdb->w('v9_video_content',$cd) ;
							$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
						}
					}
				}
			}
		}
		
		
		//视频图片入库
		//总集，共存入图片
		$pictypess = array('poster'=>array('102','202','302','402')) ;
		
		$phpcmsdb->d('v9_video_poster',array('asset_id'=>$video['id'])) ;
		$keyword = substr($video['title'],0,-4);
		/*
		//从bigtv获取图片
		$keyword1 = '激战';
		$bigurl1 = 'http://www.go3c.tv:8062/go3cci/search.api?m=search&termid=2&keyword='.$keyword1;
		$sxmlb = file_get_contents($bigurl1);
		$sxmlb = json_decode($sxmlb,true);
		$vid = $sxmlb[0][ID];
		//通过详细接口获取所有的图片
		$bigurl2 = 'http://www.go3c.tv:8062/go3cci/video.api?m=videodetails&termid=2&vid='.$vid;
		$sxmlv = file_get_contents($bigurl2);
		$sxmlv = json_decode($sxmlv,true);
		var_dump($sxmlv[images]);
		foreach ($sxmlv[images] as $vb){
			if(in_array($vb['imageType'],$pictypess)){   //在导入的类型则记录到v9_pic
				list($width,$height,$type,$attr) = getimagesize($vb['imageURL']);
				$name = strrchr($vb['imageURL'],'/');
				$size = $width.'x'.$height;
				$ext = str_replace('.','',strrchr($vb['imageURL'],'.'));
				$cp=array(
					'asset_id' => $video['id'],
					'path'     => $vb['imageURL'],
					'width'    => $width,
					'height'   => $height,
					'name' 	   => $name
				);
				$tmp = $phpcmsdb->r1('v9_pic',array('asset_id'=>$video['id'],'path'=>$vb['imageURL']),'id') ;
				if(empty($tmp['id'])){
					$phpcmsdb->w('v9_pic',$cp);
				}
			}else{
				$cd = array(
						'title'   	=> $video['id'].'-'.$keyword.'-'.$vb['imageType'] ,
						'asset_id'  => $video['id'],
						'path'   	=> $vb['imageURL'] ,
						'type'   	=> $vb['imageType'] ,
						'catid'   	=> '65',
						'status'    => '99',
						'sysadd'    => '1',
						'username'  => get_uname_by_sid(get_my_sid()) ,
						'updatetime'=> time(),
						'size'		=> $size,
						'format'	=> $ext
				);
				$tmp = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$cd['asset_id'],'type'=>$cd['type'],'path'=>$cd['path']),'id') ;
				if(isset($tmp['id'])){
					$phpcmsdb->w('v9_video_poster',$cd,array('id'=>$tmp['id'])) ;
				}else{
					$cd['inputtime'] = time() ;
					$tmpid = $phpcmsdb->w('v9_video_poster',$cd) ;
					$phpcmsdb->w('v9_video_poster_data',array('id'=>$tmpid)) ;
				}
			}
		}
		*/
		//获取高清图片(去掉名称的直播或全集)
		//$p = 'http://api.hdpfans.com/poster.php?keyword='.$keyword;
		//$sxmlp = file_get_contents($p);
		//$sxmlp = json_decode($sxmlp,true);
		foreach($pictypess as $vk=>$pictypes){
			
			$tmp_image = $video[$vk] ;
			parse_str($tmp_image,$tmp) ;
			if(!empty($tmp['url'])){
				$tmp_image = $tmp['url'] ;
			}

			//坏图片
			if(!strpos($tmp_image,'.')) continue ;
			
			$tmp_image = !empty($video['image'])?$video['image']:$tmp_image;
			list($width,$height,$type,$attr) = getimagesize($tmp_image);
			$size = $width.'x'.$height;
			$ext = str_replace('.','',strrchr($tmp_image,'.'));
				foreach($pictypes as $pictype){
					$cd = array(
							'title'   	=> $video['id'].'-'.$keyword.'-'.$pictype ,
							'asset_id'  => $video['id'],
							'path'   	=> $tmp_image ,
							'type'   	=> $pictype ,
							'catid'   	=> '65',
							'status'    => '99',
							'sysadd'    => '1',
							'username'  => get_uname_by_sid(get_my_sid()) ,
							'updatetime'=> time(),
							'size'		=> $size,
							'format'	=> $ext
					);
					$tmp = $phpcmsdb->r1('v9_video_poster',array('asset_id'=>$cd['asset_id'],'type'=>$cd['type'],'path'=>$cd['path']),'id') ;
					if(isset($tmp['id'])){
						$phpcmsdb->w('v9_video_poster',$cd,array('id'=>$tmp['id'])) ;
					}else{
						$cd['inputtime'] = time() ;
						$tmpid = $phpcmsdb->w('v9_video_poster',$cd) ;
						$phpcmsdb->w('v9_video_poster_data',array('id'=>$tmpid)) ;
					}
			}			
		}
		//插入区域内容
		$phpcmsdb->d('v9_video_area',array('asset_id'=>$d['asset_id'])) ;
		$dare = array(
			'asset_id' => $d['asset_id'],
			'area_id' => '0',
			'isextend' => '0'
		);
		$phpcmsdb->w('v9_video_area',$dare) ;
		//主视频资源内容全部导入
		
		
		
		
		//分集视频资源导入
		//视频分集资源导入
		if(count($video['eps']) > 0){
			//$this->hdpimportdetaill(true,false) ;
			if(request('showok','POST')){
				$html = "<script>window.location.href='?m=go3c&c=importhdp&doimport=1&a=hdpdetaillist&url=" . urlencode($video['url']) . "&t={$video['type']}&pc_hash={$_SESSION['pc_hash']}'</script>" ;
				return y_do_r($html) ;
			}
			return y_do_r('dourl') ;
		}
		
		if(request('showok','POST')){
			$r = array() ;
			$r['tp'] = 'template' ;
			$r['tpl'] = 'showok.tpl' ;
			$r['data'] = $video ;
			return y_do_r($r) ;
		}
		return y_do_r('ok') ;
	}
	
	
	//分集资源导入
	public function hdpimportdetaill($allvid=false,$showok=true) {
		$url = request('url','GET') ;
		if(!$url) return false ;
		
		
		$vurl = request('vurl','GET','array') ;
		if($vurl) $allvid = false ;
		
		//导入全部
		if(request('vurl','GET') == 'all') $allvid = true ;
		
		$video = $this->getAVideo($url) ;
		
		foreach($video['eps'] as $v){
			if(!$allvid && !in_array($v['url'],$vurl)) continue ;
			$this->importADetaill($video,$v) ;
		}
		
		if(!$showok) return 'ok' ;
		
		return y_do_r('ok') ;
	}
	
	
	//导入一个分集资源
	public function importADetaill($video,$ep){
		$phpcmsdb = yzy_phpcms_db() ;

		$spid = $this->getSPId() ;
		$column_id = $video['column_id'] ;
		$ispackage = 0 ;

		if(strpos($ep['title'],$video['title']) === false) $ep['title'] = $video['title'] . '-' . $ep['title'] ;
		//preg_match("/[0-9]+/",$ep['series'],$a);    //episode_number集数取全部为数字
		$attep = explode('|',$ep['series']);
		$att = explode('|',$ep['title']);
		preg_match_all('/\d+/is',$attep['0'],$arr);
		if(count($arr['0'])>1){
			$episode_number = $arr['0']['1'];
		}else{
			$episode_number = $arr['0']['0'];
		}
		if($column_id=='3'){
			$episode_number = $arr['0']['0'].$arr['0']['1'].$arr['0']['2'];
		}
		$d = array(
			'title'   	    => $att['0'],
			'asset_id'      => $ep['id'], 
			'director'      => $video['directors'], 
			'actor' 	    => $video['actors'],
			'short_name'	=> $video['title'],
			'tag'   	    => str_replace('/',',',$video['tags']) , 
			'year_released' => '' ,
			'run_time'      => intval($video['run_time']) ? $video['run_time']:'60',
			'column_id' 	=> $column_id,
			'active' 	 	=> '1',
			'area_id' 	 	=> '1',
			'is_free' 	    => '1',
			'total_episodes'=> '' ,
			'parent_id' 	=> $video['id'],
			'episode_number' => $episode_number ,
			'latest_episode_num' => '' ,
			'rating'      	=> '6' ,
			'ispackage'   	=> $ispackage ,
			'catid'   	 	=> '54', 
			'status'     	=> '99',
			'sysadd'     	=> '1',
			'username'   	=> get_uname_by_sid(get_my_sid()) ,
			'updatetime' 	=> time(),
			'spid'			=> $spid,
			'surl'			=> $ep['url']
		);
		
		$thisv = $phpcmsdb->r1('v9_video',array('asset_id'=>$d['asset_id']),'id,published,online_status') ;
		if(isset($thisv['published']) && strcmp($thisv['published'] ,'1') == 0){
			//已经更新过了
			return ;
		}
		
		$dd = array(
			'short_desc' => $ep['title'], 
			'long_desc'  => '' , 
		);
		
		$thisv_id = 0 ;
		if(isset($thisv['id'])){
			if(in_array($thisv['online_status'],array('3','0','1','99'))){    
				//3 编辑未通过，0或1 导入，99 错误  这三种状态的数据会在重新导入后被覆盖
				$thisv_id = $thisv['id'] ;
				$d['online_status'] = '1';
				$phpcmsdb->w('v9_video',$d,array('id'=>$thisv_id)) ;
				$phpcmsdb->w('v9_video_data',$dd,array('id'=>$thisv_id)) ;
			}
		}else{
			//新增吧...
			$d['inputtime'] = time() ;
			$thisv_id =  $phpcmsdb->w('v9_video',$d) ;
			$dd['id'] = $thisv_id ;
			$phpcmsdb->w('v9_video_data',$dd) ;
		}


		$ep['clarity'] = 3 ;

		$phpcmsdb->d('v9_video_content',array('asset_id'=>$ep['id'],'clarity'=>$ep['clarity'])) ;
		foreach($ep['urls'] as $aurl){
			$source_id = $this->getSourceId($aurl) ;
			$aurl['url'] = $this->getVideoRURL($aurl['url']) ;
			if($source_id==19){
				$source_id = $this->getSourceLink($aurl['url']);
			}
			$video_content_id = 0 ;
			$tmp = $phpcmsdb->r1('v9_video_content',array('asset_id'=>$ep['id'],'path'=>$aurl['url'],'clarity'=>$ep['clarity'])) ;
			if(isset($tmp['id'])) $video_content_id = $tmp['id'] ;
			if((strpos($aurl['url'],'xunlei')!== false)||(strpos($aurl['url'],'http://.flv')!== false)||(strpos($aurl['url'],'pptv.vod')!== false)||(strpos($aurl['url'],'m1905')!== false)) continue ;
					if((strpos($aurl['url'],'g3.letv.com/vod')!== false)||(strpos($aurl['url'],'g3.letv.cn/vod')!== false)) $source_id=27 ;
			$cd = array(
				'title'   	=> $ep['id'].'-'.$att['0'].'-'.$ep['clarity'] , 
				'asset_id'  => $ep['id'], 
				'path'   	=> $aurl['url'] ,  
				'clarity'   => $ep['clarity'],  
				'catid'   	=> '64', 
				'status'    => '99',
				'sysadd'    => '1',
				'username'  => get_uname_by_sid(get_my_sid()) ,
				'updatetime'=> time(),
				'source_id' => $source_id ,
				'sourceurl' => $att['1']
			);	
			if($video_content_id){
				$phpcmsdb->w('v9_video_content',$cd,array('id'=>$video_content_id)) ;
			}else{
				$cd['inputtime'] = time() ;
				$video_content_id = $phpcmsdb->w('v9_video_content',$cd) ;
				$phpcmsdb->w('v9_video_content_data',array('id'=>$video_content_id)) ;
			}
			
		}
		//插入区域内容
		$phpcmsdb->d('v9_video_area',array('asset_id'=>$d['asset_id'])) ;
		$dare = array(
			'asset_id' => $d['asset_id'],
			'area_id' => '0',
			'isextend' => '0'
		);
		$phpcmsdb->w('v9_video_area',$dare) ;
		return true ;
	}
	
	
	//获得 栏目id
	function getColumnId($name){
		$column_names  = array('电影','电视剧','综艺','乐酷');

		if($name == '动漫') $name = '电视剧' ;
		if($name == '综艺') $name = '综艺' ;

		static $col_key ;
		if(!isset($col_key)){
			$phpcmsdb = yzy_phpcms_db() ; 
			$col_key = $phpcmsdb->r('v9_column',array('title'=>$column_names),'id,title',array('key_value'=>'title,id')) ;
		}
		
		if(isset($col_key[$name] )) 	return $col_key[$name] ;
		return 0 ;
	}
	
	//获取 spid
	function getSPId(){
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		
		return $this->current_spid['spid'];
	}
	
	
	//获取 sourceid
	function getSourceId($stagname){
		$phpcmsdb = yzy_phpcms_db() ;
		$source_names  = array('迅雷'=>'xunlei-raw','奇艺'=>'iqiyi-raw','PPTV'=>'pptv-raw','优酷'=>'youku-raw','乐视'=>'letv-raw','腾讯'=>'qq-raw','电影网'=>'m1905-raw');
		foreach ($source_names as $key=>$v){
			if($stagname==$key){
				$tmp = $phpcmsdb->r1('v9_video_source',array('title'=>$v)) ;
				$source_id = $tmp['id'] ; break;
			}else{
				$source_id = 19 ;
			}
		}
		return $source_id ;
	}
	function getSourceLink($url){
		if(strpos($url,'qq')!== false){
			$source_id = 21;
		}elseif(strpos($url,'qiyi')!== false){
			$source_id = 17 ;
		}elseif(strpos($url,'youku')!== false){
			$source_id = 14 ;
		}elseif(strpos($url,'.html')!== false){
			$source_id = 1 ;
		}else {
			$source_id = 19 ;
		}
		return $source_id ;
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
		$video['title'] = $sxml['name'] ;
		$video['id'] = $this->getVID($sxml) ;
		$video['type_id'] = '' ;
		$video['tags'] = '' ;
		$video['run_time'] = '' ;
		
		$video['directors'] =  str_replace('|',',',$sxml['director']) ;
		$video['actors'] = str_replace('|',',',$sxml['actor']) ;
		$video['description'] = $sxml['desc'] ;
		
		if(substr($video['directors'],-1,1) == ',') $video['directors'] = substr($video['directors'],0,-1) ;
		if(substr($video['actors'],-1,1) == ',') $video['actors'] = substr($video['actors'],0,-1) ;
		
		$video['image'] = $sxml['img'] ;
		$video['poster'] = $sxml['img'] ;
		$video['link'] = $sxml['link'] ;
		$video['url'] = $sxml['link'] ;
		
		$tmp = request('t','GET') ;
		if($tmp){
			$video['type'] = $tmp  ;
			$video['column_id'] = $this->getColumnId($tmp) ;
		}
		
		
		//先检测
		$more_url = false ;
		foreach($sxml['url']['foobar'] as $k=>$v){
			if(!is_array($v)) continue ;
			
			if(in_array($v['name'],array('迅雷','奇艺','PPTV','优酷','乐视','腾讯'))){
				$more_url = true ;
				break ;
			}

		}
		
		
		$video['count'] = 0 ;
		
		$video['urls'] = array() ;
		$video['eps'] = array() ;
	
		if(!$more_url){
			foreach($sxml['url']['foobar'] as $k=>$v){
				if(is_array($v)){
					
					$v['link'] = $v['url'] ;
					
					$aep = array() ;
					$aep['title'] = $v['name'] ;
					$aep['id'] = $this->getVID($v) ;
					$aep['series'] = $v['name'] ;
					$att = explode('|',$v['name']);
					$aep['episode_number'] = $att['0'];
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
					$aep = array() ;
					$aep['url'] = $sxml['url']['foobar']['url'] ;
					$aep['id'] = $this->getVID($v) ;
					$aep['title'] = $sxml['name'].'-'.$sxml['url']['foobar']['name'] ;
					$aep['series'] = $sxml['url']['foobar']['name'] ;
					$att = explode('|',$sxml['url']['foobar']['name']);
					$aep['episode_number'] = $att['0'];
					$aep['urls'] = array() ;
					$v = array();
					$v['url'] = $sxml['url']['foobar']['url'] ;
					$v['id'] = $this->getVID($v) ;
					$v['title'] = $sxml['name'] ;

					$aep['urls'][] = $v ;
				
					$video['eps'][] = $aep ;

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

		if(isset($sxml['m3u8'])&&strpos($sxml['m3u8'],'metal.video.qiyi.com')=== false) return $sxml['m3u8'] ;
		if(isset($sxml['url']['foobar'])) return $sxml['url']['foobar'] ;
		return '' ;
	}
	
	function getVID($v){
		$vid = 'hdp'.substr(md5($v['link']),0,15);
		return  $vid;
	}
	
	function getHDPBaseData(){
		//type
		$phpcmsdb = yzy_phpcms_db() ;
		
		$m_key = 'hdp_base_data' ;
		$d = $phpcmsdb->getM($m_key) ;

		if(!empty($d)) return $d ;
		
		$d = array() ;
		
		//类型
		$f1 = 'http://tv4.hdpfans.com/~rss.get.channel/site/hdp/rss/1/xml/1/ajax/1/key/ba3cd61e4953cecff627b1401588fb1e' ;
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
		
		$phpcmsdb->setM($m_key,$d,600) ;
		
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
		if (strpos($u, 'movie')!==false) {
			$type = 'movie';
		}elseif (strpos($u, 'zy')!==false) {
			$type = 'zy';
		}elseif (strpos($u, 'comic')!==false) {
			$type = 'comic';
		}else{
			$type = 'tv';
		}
		return $type;
	}
}


