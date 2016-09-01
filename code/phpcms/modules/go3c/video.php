<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/db_list.php' ;
define('TASK_IMG_PATH','http://www.go3c.tv:8060/images/go3ccms/');
class video extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		pc_base::load_sys_class('form','',0);
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		$this->db_comment = pc_base::load_model('cms_comment_model');
	}
	
	public function init() {
	}
	
	/**
	 * Delete video comments.
	 */
	public function deleteVideoComment() {
		$ids = explode(',', $_GET['vid']);
		foreach ($ids as $id) {
			$this->db_comment->delete(array('c_id' => $id));
		}
		showmessage('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	/**
	 * Show video comments.
	 * 
	 */
	public function showVideoComment() {
		$videoID = $_GET['id'];
		//$comments = $this->db_comment->select("c_vid = '$videoID'");
		$where = " c_vid = '$videoID' ";
		if (isset($_GET['user']) && $_GET['user'])
			$where .= "AND c_user LIKE '%" . $_GET['user'] . "%'";
		if (isset($_GET['field']))
			$field = $_GET['field'];
		else 
			$field = 'c_time';
		if (isset($_GET['order']))
			$order = ' ' . $_GET['order'];
		else 
			$order = ' DESC';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db_comment->listinfo($where, $field . $order, $page, $perpage);
		$pages = $this->db_comment->pages;
		include $this->admin_tpl('video_comment');
	}
	
	/**
	 * 上线管理
	 *
	 */
	public function online() {
		
		$this->db = pc_base::load_model('cms_video_model');
		$online_status   = isset($_GET['online_status']) ? intval($_GET['online_status']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		$asset_id = strip_tags(trim($_GET['asset_id']));
		$column_id = isset($_GET['column_id']) ? intval($_GET['column_id']) : '';
		$ispackage = isset($_GET['ispackage']) ? intval($_GET['ispackage']) : 0;
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		unset($_SESSION['asset_id']);
		
		//根据cms用户权限筛选区域
		$this->admin_model = pc_base::load_model('admin_model');
		$this->cms_area = pc_base::load_model('cms_area_model');
		$this->cms_video_area_model = pc_base::load_model('cms_video_area_model');
		$userid = $_SESSION['userid'];
		$ad_area = $this->admin_model->get_one(array('userid'=>$userid));
		$area_id = $ad_area['area_id']?$ad_area['area_id']:'01';
		$ad_area_le = $this->cms_area->get_one(array('id'=>$area_id)); //查询管理员区域级别(省市县)
		$arkey = " 1 ";
		if($ad_area['roleid']!='1' || $area_id!='01'){  //区域筛选排除超级管理员和全网
			$area_id = $ad_area_le['id'];
			$video_id = $this->cms_video_area_model->select("area_id like '%$area_id%'", 'distinct(asset_id)', '', 'asset_id ASC');
			//$channel = self::unique_arr($channel,true);
			foreach($video_id as $key=>$value)
			{
  				$b[$key]=$value['asset_id'];
			}
			$video_id = join(',',$b);
			//$where = "asset_id in ('$video_id') ";
			$where = " 1 ";
		}else{
			$where = " 1 ";
		}
		$where.= "AND published = 0 AND `online_status` not in(21,3) ";
		$where.= "AND spid = '".$this->current_spid[spid]."'";
		$ispackage ? $where.= " AND `ispackage` = '1' " : '';
		$online_status   != '' ? $where.= " AND `online_status` = '$online_status'" : '';
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		$asset_id != '' ? $where.= " AND `asset_id` LIKE '%$asset_id%'" : '';
		$column_id   != '' ? $where.= " AND `column_id` = '$column_id'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$this->db_poster = pc_base::load_model('cms_video_poster_model');
		foreach ($data as $key=>$v){
			$poster = $this->db_poster->select(array('asset_id'=>$v['asset_id']));
			if(empty($poster)){
				$data[$key]['pic'] = '1';
			}
		}
		$pages = $this->db->pages;
		//获取栏目类型
		$this->cms_column = pc_base::load_model('cms_column_model');
		$cms_column = $this->cms_column->select(array('active'=>1));
		include $this->admin_tpl('video_online_list');
	}
	
	/**
	 * 下线管理
	 *
	 */
	public function offline() {
		$this->db = pc_base::load_model('cms_video_model');
		$filter   = isset($_GET['filter']) ? intval($_GET['filter']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		$asset_id = strip_tags(trim($_GET['asset_id']));
		$column_id = isset($_GET['column_id']) ? intval($_GET['column_id']) : '';
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';

		$ispackage = isset($_GET['ispackage']) ? intval($_GET['ispackage']) : 0;

		$where = " 1 AND spid = '".$this->current_spid[spid]."'";
		if($filter == 1){
			$where.= " AND offline_status = '0' AND published = 1 ";
		}elseif($filter == 2){
			$where.= " AND offline_status = '1' AND published = 1 ";
		}elseif($filter == 3){
			$where.= " AND offline_status = '1' AND published = 0 ";
		}else{
			$where.= " AND published = 1 ";
		}
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		$asset_id != '' ? $where.= " AND `asset_id` LIKE '%$asset_id%'" : '';
		$column_id   != '' ? $where.= " AND `column_id` = '$column_id'" : '';
		$ispackage ? $where.= " AND `ispackage` = '1' " : '';
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db->pages;
		//check if video has comments
		foreach ($data as &$v) {
			$vid = $v['id'];
			$comments = $this->db_comment->select(array('c_vid' => $vid));
			if (!empty($comments))
				$v['hasComment'] = 1;
			else 
				$v['hasComment'] = 0;
		}
		//获取栏目类型
		$this->cms_column = pc_base::load_model('cms_column_model');
		$cms_column = $this->cms_column->select(array('active'=>1));
		include $this->admin_tpl('video_offline_list');
	}
	
	/**
	 * 删除
	 *
	 */
	public function delete() {
		$this->db = pc_base::load_model('cms_video_model');
		//过滤条件
		$online_status   = isset($_GET['online_status']) ? intval($_GET['online_status']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		$asset_id = strip_tags(trim($_GET['asset_id']));
		$column_id = isset($_GET['column_id']) ? intval($_GET['column_id']) : '';

		$ispackage = isset($_GET['ispackage']) ? intval($_GET['ispackage']) : 0;
		
		//构造SQL
		$where = " 1 AND published = 0 AND spid = '".$this->current_spid[spid]."' ";
		$online_status   != '' ? $where.= " AND `online_status` = '$online_status'" : '';
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		$asset_id != '' ? $where.= " AND `asset_id` LIKE '%$asset_id%'" : '';
		$column_id   != '' ? $where.= " AND `column_id` = '$column_id'" : '';
		$ispackage ? $where.= " AND `ispackage` = '1' " : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->db->pages;
		//获取栏目类型
		$this->cms_column = pc_base::load_model('cms_column_model');
		$cms_column = $this->cms_column->select(array('active'=>1));
		include $this->admin_tpl('video_delete_list');
	}
	
	/**
	 * 海报列表
	 *
	 */
	public function video_poster_list() {
	/*	
		$this->db = pc_base::load_model('cms_video_poster_model');
		$where 	   = " 1 ";
		$asset_id  = strip_tags(trim($_GET['asset_id']));
		$asset_id != '' ? $where.= " AND `asset_id` = '$asset_id'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '20';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->db->pages;
	*/
	$videoId = $_GET['asset_id'];
	if(!empty($videoId))
		{
			
			//海报尺寸名称翻译 v9_dbmover_poster_type
			$this->cms_poster_type = pc_base::load_model('dbmover_poster_type_model');
			$dbImgTypeData = $this->cms_poster_type->select();
			if(!empty($dbImgTypeData))	//归整数据
			{
				foreach($dbImgTypeData as $k => $v)
				{
					$v['path'] = '';
					$v['asset_id'] = $videoId;
					$imgTypeList[$v['id']] = $v;
				}
			}
			//海报类型数据 video_poster
			$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');

			$ImgTypeData = $this->cms_video_poster->select(array('asset_id'=>$videoId));

			if(!empty($ImgTypeData))	//归整数据
			{
				foreach($ImgTypeData as $k => $v)
				{
					$imgTypeList[$v['type']]['path']=$v['path'];
				}
			}
		//	return $imgTypeList;
		}else{
		//	return '';
		}
		$this->db = pc_base::load_model('cms_video_model');
		$aKey = "asset_id = '".$videoId."'";
		$limitInfo = $this->db->get_one($aKey);
		session_start();
		$_SESSION['asset_id']=$limitInfo['asset_id'];
		$data = $imgTypeList;
		include $this->admin_tpl('video_poster_list');
	}
	
	/**
	 * 播放链接列表
	 *
	 */
	public function video_content_list() {
		
		
		$this->db   = pc_base::load_model('cms_video_content_model');	
		$asset_id   = strip_tags(trim($_GET['asset_id']));
		$asset_filt = $asset_id != '' ?  " AND `asset_id` = '$asset_id'" : '';	
		$field    	= 'vc.*, vs.title as source';
		$sql     	= "v9_video_content AS vc LEFT JOIN v9_video_source AS vs ON vs.id = vc.source_id WHERE 1 ".$asset_filt; 
		//echo $sql;
		$order  	= 'ORDER BY vc.id DESC';
		$perpage 	= 20;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('video_content_list');
	}
	
	
	/**
	 * 海报删除
	 *
	 */
	public function video_poster_delete() {
		
		$this->db  = pc_base::load_model('cms_video_poster_model');
		$this->db2 = pc_base::load_model('cms_video_poster_data_model');
		$id = intval($_GET['id']);
		$this->db->delete(array('id'=>$id));
		$this->db2->delete(array('id'=>$id));
		//添加操作日志
		$type = 'delete_poster';
		$this->go3ccms_changlog($id,$type);
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 60);
	}
	
	/**
	 * 播放链接删除
	 *
	 */
	public function video_content_delete() {
		
		$this->db  = pc_base::load_model('cms_video_content_model');
		$this->db2 = pc_base::load_model('cms_video_content_data_model');
		$id = intval($_GET['id']);
		$this->db->delete(array('id'=>$id));
		$this->db2->delete(array('id'=>$id));
		//添加操作日志
		$type = 'delete_content';
		$this->go3ccms_changlog($id,$type);
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 60);
	}
	
	
	/**
	 * 视频来源
	 *
	 */
	public function video_source_list() {
		
		$this->db = pc_base::load_model('cms_video_source_model');
		$where 	   = " 1 ";
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '20';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('video_source_list');
	}
	
	/**
	 * 视频来源数据同步前清空老数据
	 *
	 */
	public function video_source_pre_sync() {
		$this->db2 = pc_base::load_model('tv_video_source_model');
		$this->db2->query("truncate table video_source");
		//添加操作日志
		$userid = $_SESSION['userid'];
		$type = 'source_sync';
		$this->go3ccms_changlog($userid,$type,'');
		showmessage('正在清空go3c.video_source老数据，下面开始同步视频来源到前端','?m=go3c&c=video&a=video_source_sync',$ms = 500);
	}
	
	/**
	 * 视频来源数据同步
	 *
	 */
	public function video_source_sync() {

		$this->db  = pc_base::load_model('cms_video_source_model');
		$this->db2 = pc_base::load_model('tv_video_source_model');
		
		$infos = $this->db->select('','*','',$order = 'id ASC');
		foreach ($infos as $value) {
			$data_array = array(
				'id' 	=> $value['id'], 
				'icon' 	=> $value['icon_url'], 
				'name'  => $value['title'], 
				'type' 	=> $value['type'], 				
			);
			$this->db2->insert($data_array);
		}
		showmessage('同步成功','?m=go3c&c=video&a=video_source_list',$ms = 500);
	}
	
	
	
	/**
	 * 栏目设置
	 *
	 */
	public function category() {
		$this->belong_type = pc_base::load_model('cms_column_model');
		$belong_type_list = $this->belong_type->select('', 'id,title', '', 'id ASC');
		$belong_type_array = array();
		foreach($belong_type_list as $pvalue){
			if(intval($pvalue['id']) > 2 )
			$belong_type_array[$pvalue['id']]=$pvalue['title'];
		}
		
		$this->db = pc_base::load_model('cms_tags_model');
		//过滤条件
		$type   = isset($_GET['type']) ? intval($_GET['type']) : 1 ;
		//构造SQL
		$where  = " 1 AND `type` = '$type'";
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` ASC', $page, $pagesize = 15);
		$pages = $this->db->pages;
		include $this->admin_tpl('video_category_list');
	}
	/**
	 * 新增栏目设置
	 *
	 */
	public function category_add() {
		$type   = isset($_GET['type']) ? intval($_GET['type']) : '';
		$keyword  = trim($_GET['word']);
		$phpcmsdb = yzy_phpcms_db() ;
		$tmps = $phpcmsdb->r1('v9_tags',array('title'=>$cat['name'],'type'=>$type),'id,title');
		if(!isset($tmps['id'])){
			$d = array() ;
			$d['title'] = $keyword ;
			$d['catid'] = '4' ;
			$d['status'] = '99' ;
			$d['sysadd'] = '1' ;
			$d['username'] = get_uname_by_sid(get_my_sid()) ;
			$d['inputtime'] = time() ;
			$d['updatetime'] = time() ;
			$d['type'] = $type ;
			$d['online_status'] = '1' ;
			$d['belong'] = '3,4,5,6,7,8,9' ;

			$k = $phpcmsdb->w('v9_tags',$d) ;
			$phpcmsdb->w('v9_tags_data',array('id'=>$k)) ;
			//添加操作日志
			$type = 'tags_add';
			$this->go3ccms_changlog($id,$type,json_encode($d));
			showmessage('新增成功',base64_decode($_GET['goback']));
		}else{
			showmessage('名称有重复项，新増失败',base64_decode($_GET['goback']));
		}
	}

	/**
	 * 栏目申请审核
	 *
	 */
	public function category_pass() {
		$this->db = pc_base::load_model('cms_tags_model');
		$id = intval($_GET['id']);
		$belongs = $_GET['belong'];
		$this->db->update(array('online_status'=>10,'belong'=>$belongs), array('id'=>$id));
		//添加操作日志
		$type = 'tags_online';
		$this->go3ccms_changlog($id,$type);
		//showmessage('操作成功',base64_decode($_GET['goback']));
		$r = array() ;
		$r['tp'] = 'json' ;
		$r['data'] = $id ;
		
		y_do_r($r) ;
	}

	/**
	 * 申请下线
	 *
	 */
	public function offline_appy() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$go3cdb = yzy_go3c_db() ;
		$video = $this->db->get_one(array('id'=>$id));

		//检查phpcms recommend表中有没有		
		$this->recommend_video_db = pc_base::load_model('cms_pre_task_video_model');
		$tasksData = $this->recommend_video_db->select(array('videoId'=>$video['asset_id']),'posidInfo');
    	if($video['ispackage']=='1'){  //查询该总集下在线的分集
			$videoinfo = $this->db->select(array('parent_id'=>$video['asset_id'],'online_status'=>'11','published'=>'1'),'id');
			$i = 0;
				$count = count($videoinfo);
				foreach($videoinfo as $k=>$v){
					if(is_array($v)){
						$v = join(',', $v);
					}
						$str.= $v;
					if($i!=$count-1){
						$str.=',';
					}
					$i++;
				}
			if(!empty($str)){
				$this->db->update(array('offline_status'=>1),'id in ('.$str.')');
			}
			$this->db->update(array('offline_status'=>1), array('asset_id'=>$video['asset_id']));
		}
		//如果有查询推荐位名称 返回
		if(count($tasksData)>0){
			$positionstr = "";
			foreach($tasksData as $data){
				$positionstr.=$data['posidInfo']."  ";	
			}
			$go3cdb->d('recommended_video',array('vid'=>$video['asset_id'])) ;
			$go3cdb->d('video_image',array('vid'=>$video['asset_id'])) ;
			$this->recommend_video_db->delete(array('videoId'=>$video['asset_id']));
			$this->db->update(array('offline_status'=>1), array('id'=>$id));
			showmessage('该视频正在以下推荐位使用,已申请下线:'.$positionstr,base64_decode($_GET['goback']),600000);
		}else{		
			$this->db->update(array('offline_status'=>1), array('id'=>$id));
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}

	/**
	 * 申请删除
	 *
	 */
	public function delete_appy() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>20), array('id'=>$id));
		//添加操作日志
		$type = 'delete_vid';
		$this->go3ccms_changlog($id,$type);
		showmessage('操作成功',base64_decode($_GET['goback']));

	}
	
	/**
	* 视频容错检查
	*/

	public function checkDataValid($video){
		$this->db = pc_base::load_model('cms_video_model');
		$this->db_poster = pc_base::load_model('cms_video_poster_model');
		$this->db_video_content = pc_base::load_model('cms_video_content_model');

		//视频ID项为必填项，数字字母横线下划线外其他字符不能使用
		if(empty($video['asset_id']) || !preg_match('/^[0-9a-zA-Z\_\-\+\=\=]+$/i',$video['asset_id']) ){
			return '视频ID项为必填项，数字字母横线下划线外其他字符不能使用!';
		}
		
		//视频时长、评分必须为数字
		if(empty($video['run_time'])|| !preg_match('/^[0-9-]+$/',$video['run_time'])){
			 return '视频时长、评分必须为非空，只能是数字!' ;
		}
		
		//总集数、必须为数字或者不填
		if(!empty($video['total_episodes']) ){
			if(!preg_match('/^[0-9-]+$/',$video['total_episodes'])){
				 return '总集数、必须为数字或者不填!' ;
			}
		}
		/*
		//电视栏目值为日期（如20121101），表示第几期
		if($video['column_id'] == '3'){
			if(!empty($video['episode_number']) ){
				if(!preg_match('/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/',$video['episode_number'])){
					 return '电视栏目值为日期（如20121101），表示第几期!' ;
				}
			}else{
				return '电视栏目值为日期（如20121101），表示第几期!' ;
			}
		}
		*/
		//电视剧值为数字，表示第几集
		if($video['column_id'] == '4' || $video['column_id'] == '7' || $video['column_id'] == '8'){
			if(!empty($video['episode_number']) ){
				if(!preg_match('/^[0-9-]+$/',$video['episode_number'])){
					 return '电视剧,动漫,纪录片值为数字，表示第几集！' ;
				}
			}
		}
		
		
		//如果是总集 判断是否有海报
		if($video['ispackage'] == '1'){
			$poster = $this->db_poster->select(array('asset_id'=>$video['asset_id']));
			//echo '<pre>';print_r($poster); exit;
			if(!count($poster)){
				 return '总集必须先上传海报,才能申请发布!' ;
			}
		}
		//检测链接的source_id
		$content = $this->db_video_content->select(array('asset_id'=>$video['asset_id']));
		if(($video['column_id']=='3'||$video['column_id']=='4'||$video['column_id']=='7')&&$video['ispackage'] == '0'){
			if(count($content)<1){
				return '视频播放链接不能为空!' ;
			}
		}elseif($video['column_id']=='5'){
			if(count($content)<1){
				return '视频播放链接不能为空!' ;
			}
		}
			foreach ($content as $vt){
				if(empty($vt['source_id'])){
					return '视频链接的类型异常!' ;
				}elseif ($vt['source_id']=='12'){
					if($video['PC']=='1'){
						return 'pptv客户端的链接不能在pc播放，请去掉pc支持或 至少添加一个外链!' ;
					}
				}
			}
		//如果是总集，没有加分集，不允许上线
		if($video['ispackage'] == '1'&&$video['column_id']!='5'){
			$video_other = $this->db->select(array('parent_id'=>$video['asset_id']));
			$content = $this->db_video_content->select(array('asset_id'=>$video['asset_id']));
			//echo '<pre>';print_r($poster); exit;
			if(!count($video_other)&&!count($content)){
				  return '该视频是总集,没有加分集,且没有播放链接,不允许上线!' ;
			}
		}
		//如果是分集则检查集数字段不能为空
		if($video['ispackage'] == '0'&&($video['column_id'] == '3'||$video['column_id'] == '4')){
			if(empty($video['episode_number'])){
				return '电视栏目和电视剧的分集集数不能为空!' ;
			}
		}
		//如果是分集,没有填写总集id,不允许上线(除乐酷、电影);如果总集不真实存在也不允许上线;
		if($video['ispackage'] == '0'&&($video['column_id'] == '3'||$video['column_id'] == '4'||$video['column_id'] == '7'||$video['column_id'] == '8')){
			if(empty($video['parent_id'])){
				 return '该视频是分集,没有填写总集id,不允许上线!' ;
			}else{
				$pvideo = $this->db->select(array('asset_id'=>$video['parent_id']));
				if(!count($pvideo)){
				 return '该视频是分集,它的总集('.$video['parent_id'].')不是真实存在,不允许上线!' ;			
				}else {
					$aKey = "asset_id = '".$video['parent_id']."'";
					$limitInfo = $this->db->get_one($aKey);
					if($limitInfo['published']!='1'){
						return '分集所属总集未上线，不允许审核。请先将总集先上线，然后请及时上线至少一个分集!' ;	
					}
				}
			}
		}
		
		//如果是电影 乐酷 至少有一张海报
		if(in_array($video['column_id'],array('5','6'))){
			$poster = $this->db_poster->select(array('asset_id'=>$video['asset_id']));
			//echo '<pre>';print_r($poster); exit;
			if(!count($poster)){
				 return ' 电影、乐酷必须先上传至少1张海报,才能申请发布!' ;
			}
		}
		//检查区域筛选
		$this->video_area = pc_base::load_model('cms_video_area_model');
		$area = $this->video_area->select(array('asset_id'=>$video['asset_id']));
		if(!count($area)){
			return '视频必须选择区域,才能申请发布!' ;
		}
		//编辑未通过则不能申请审核
		if($video['online_status']=='3'){
			return '此视频编辑未通过,则不能申请审核!' ;
		}
		return true;
	}

	/**
	 * 视频申请审核
	 *
	 */
	public function online_pass() {
		$this->db = pc_base::load_model('cms_video_model');
		$this->db_poster = pc_base::load_model('cms_video_poster_model');
		$id = intval($_GET['id']);
		$video = $this->db->select(array('id'=>$id));
		
		//echo '<pre>';print_r($video);
		//echo $video[0]['asset_id'];
		$ret = self::checkDataValid($video[0]);
		if($ret===true){
			$this->db->update(array('online_status'=>10), array('id'=>$id));
			//添加操作日志
			$type = 'online_vid';
			foreach($video as $v){
				$vid = $v['asset_id'];
				$this->go3ccms_changlog($vid,$type,json_encode($video));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}else{
			showmessage($ret, $HTTP_REFERER, $ms = 600);
		}
	}

	/**
	 * 问题标记
	 *
	 */
	public function online_refuse() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>3), array('id'=>$id));
		//添加操作日志
		$type = 'data_error';
		$this->go3ccms_changlog($id,$type);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	/**
	 * 申请删除 批量
	 *
	 */
	public function delete_all() {
		if(empty($_POST['ids'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_video_model');
		$ids = $_POST['ids'] ;
		if(!is_array($ids))  showmessage('没有任何需要改变的') ;
		
		$this->db->update(array('online_status'=>20),'id in ('. join(',',$ids).')');
		showmessage('操作成功');

	}

	/**
	 * ajax 申请审核
	 */
	public function ajax_online_pass() {
		$id = request('id','GET','int') ;
		if(!$id) return false ;
		
		$this->db = pc_base::load_model('cms_video_model');
		$aKey = "id = '".$id."'";
		$limitInfo = $this->db->get_one($aKey);
		
		$ret = self::checkDataValid($limitInfo);

		if($ret === true){
			$this->db->update(array('online_status'=>10), array('id'=>$id));
			$ret = '已提交审核';
			showmessage($ret,base64_decode($_GET['goback']),$ms = 60);
		}else
			showmessage($ret, base64_decode($_GET['goback']), $ms = 600);
		/*
		$r = array() ;
		$r['tp'] = 'json' ;
		$r['data'] = $ret ;
		$r['fun'] = 'showresult';

		y_do_r($r) ;
		*/
	}

	/**
	 * ajax 申请审核
	 */
	public function ajax_online_save() {
		$id = request('id','GET','int') ;
		if(!$id) return false ;
		
		$this->db = pc_base::load_model('cms_video_model');
		$this->db->update(array('online_status'=>2), array('id'=>$id));
		
		$r = array() ;
		$r['tp'] = 'json' ;
		$r['data'] = $id ;
	
		y_do_r($r) ;
		
	}
	/**
	 * ajax 浏览图片
	 * 
	 */
	public  function selectpp(){
		$asset_id = request('asset_id','GET');
		$id = request('id','GET');
		$this->db = pc_base::load_model('cms_pic_model');
		$where = " 1 ";
		$where.= "AND asset_id = '$asset_id' ";
		$data  = $this->db->select($where);
		include $this->admin_tpl('selectpic');	
	}
	
	public function editpic(){
		$id = trim($_POST['id']);
		$asset_id = trim($_POST['asset_id']);
		$path = trim($_POST['path']);
		if(!empty($id)&&!empty($asset_id)&&!empty($path)){
			//海报类型数据 video_poster
			$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');
			$aKey = "type = '".$id."' AND asset_id = '".$asset_id."'";
			$limitInfo = $this->cms_video_poster->get_one($aKey);
			if(empty($limitInfo)){
				$this->poster_db = pc_base::load_model('cms_video_poster_model');
				$data_poster = array(
						'catid'      => '65',
						'status'     => '99',
						'sysadd'     => '1',
						'username'   => $this->current_spid['username'],
						'inputtime'  => time(),
						'updatetime' => time(),
						'path'   	 => $path,
						'type'   	 => $id,
						'asset_id'   => $asset_id,
					);
				$this->poster_db->insert($data_poster);	
				 showmessage('操作成功',HTTP_REFERER);
			}else{
				$this->cms_video_poster->update(array('path'=>$path), array('type'=>$id,'asset_id'=>$asset_id));
				showmessage('操作成功',HTTP_REFERER);
			}
		}else{
			showmessage('操作错误或数据不存在!',$HTTP_REFERER);
		}
	}

	public function deletepic(){
		$id = request('id');
		if(!empty($id)){
			$this->cms_pic = pc_base::load_model('cms_pic_model');
			$aKeyv = "id = '".$id."'";
			$limitInfov = $this->cms_pic->get_one($aKeyv);
			$path = $limitInfov['path'];
			$asset_id = $limitInfov['asset_id'];
			$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');
			if(!empty($path)&&!empty($asset_id)){
				$where_data = array(
					'asset_id'=> $asset_id,
					'path' => $path
					);
				$sql = "DELETE FROM `v9_video_poster` WHERE `path` = '$path' and asset_id = '$asset_id'";
				$this->cms_video_poster->delete($where_data);
				//添加操作日志
				$type = 'delete_poster';
				$this->go3ccms_changlog($asset_id,$type,json_encode($where_data));
			}
			$this->cms_pic->delete(array('id'=>$id));
			showmessage(L('operation_success'),HTTP_REFERER);
		}else{
			showmessage('操作错误或数据不存在!',$HTTP_REFERER);
		}
	}
	/*	
	public function btnpipei(){
		$asset_id = request('asset_id');
		
	}
*/
	public function btnpipei(){
		$asset_id = request('asset_id');
		$this->cms_pic = pc_base::load_model('cms_pic_model');
		$aKeypic = "asset_id = '".$asset_id."'";
		$limitInfopic = $this->cms_pic->get_one($aKeyv);
		if(!empty($asset_id)&&!empty($limitInfopic)){
			//海报尺寸名称翻译 v9_dbmover_poster_type
			$this->cms_poster_type = pc_base::load_model('dbmover_poster_type_model');
			$ImgType = $this->cms_poster_type->select();
			foreach ($ImgType as $v){
				list($width,$height) = split("x",$v['resolution_ratio']); 
				$type = $v['id'];
				$aKey = "asset_id = '".$asset_id."' AND width = '".$width."' AND height = '".$height."'";
				$limitInfo = $this->cms_pic->get_one($aKey);
				$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');
				$aKeyv = "type = '".$type."' AND asset_id = '".$asset_id."'";
				$limitInfov = $this->cms_video_poster->get_one($aKeyv);
				if(!empty($limitInfo)&&empty($limitInfov)){
					//如果有相应的图片就自动匹配
					$data_poster = array(
						'catid'      => '65',
						'status'     => '99',
						'sysadd'     => '1',
						'username'   => $this->current_spid['username'],
						'inputtime'  => time(),
						'updatetime' => time(),
						'path'   	 => $limitInfo['path'],
						'type'   	 => $type,
						'asset_id'   => $asset_id,
					);
					$this->cms_video_poster->insert($data_poster);
					//添加操作日志
					$type = 'btnpipei_poster';
					$this->go3ccms_changlog($asset_id,$type,json_encode($data_poster));
				}
			}
			showmessage('操作成功',HTTP_REFERER);
		}else{
			showmessage('操作错误或数据不存在!',$HTTP_REFERER);
		}
	}

	public function videodelete(){
		$asset_id = request('asset_id');
		$id = request('id');
		$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');
		//$aKeyv = "type = '".$id."' AND asset_id = '".$asset_id."'";
		if(!empty($asset_id) && !empty($id))
		{
			$where_data = array(
					'asset_id'=> $asset_id,
					'type' => $id
					);
			$sql = "DELETE FROM `v9_video_poster` WHERE `type` = '$id' and asset_id = '$asset_id'";
			$this->cms_video_poster->delete($where_data);
			//添加操作日志
			$type = 'delete_poster';
			$this->go3ccms_changlog($asset_id,$type,json_encode($where_data));
			showmessage('操作成功',HTTP_REFERER);
		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}
	}

	public function poster_upload(){
		include $this->admin_tpl('poster_upload_list');	
	}
	
	//批量审核
	public function online_pass_all(){
		$ids=explode(',', $_GET['asset_id']);
		$this->db = pc_base::load_model('cms_video_model');
		$this->db_poster = pc_base::load_model('cms_video_poster_model');
		$this->db_video_content = pc_base::load_model('cms_video_content_model');
		foreach ($ids as $v){
			$video = $this->db->select(array('asset_id'=>$v));
			//视频ID项为必填项，数字字母横线下划线外其他字符不能使用
			if(empty($video[0]['asset_id']) || !preg_match('/^[0-9a-zA-Z\_\-\+\=\=]+$/i',$video[0]['asset_id']) ){
				showmessage('ID为'.$video[0]['asset_id'].'视频ID项为必填项，数字字母横线下划线外其他字符不能使用!', $HTTP_REFERER, $ms = 60);
			}
			//视频时长、评分必须为数字
			if(empty($video[0]['run_time']) || empty($video[0]['rating']) || !preg_match('/^[0-9-]+$/',$video[0]['run_time']) || !preg_match('/^[0-9-]+$/',$video[0]['rating']) ){
				showmessage('ID为'.$video[0]['asset_id'].'视频时长、评分必须为非空，只能是数字!', $HTTP_REFERER, $ms = 60);
			}
		
			//总集数、必须为数字或者不填
			if(!empty($video[0]['total_episodes']) ){
				if(!preg_match('/^[0-9-]+$/',$video[0]['total_episodes'])){
					showmessage('ID为'.$video[0]['asset_id'].'总集数、必须为数字或者不填!', $HTTP_REFERER, $ms = 60);
				}
			}
			/*
			//电视栏目值为日期（如20121101），表示第几期
			if($video[0]['column_id'] == '3'){
				if(!empty($video[0]['episode_number']) ){
					if(!preg_match('/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])$/',$video[0]['episode_number'])){
						showmessage('ID为'.$video[0]['asset_id'].'电视栏目值为日期（如20121101），表示第几期!', $HTTP_REFERER, $ms = 60);
					}
				}else{
					showmessage('ID为'.$video[0]['asset_id'].'电视栏目值为日期（如20121101），表示第几期!', $HTTP_REFERER, $ms = 60);
				}
			}
			*/
			//电视剧值为数字，表示第几集
			if($video[0]['column_id'] == '4'||$video[0]['column_id'] == '7'||$video[0]['column_id'] == '8'){
				if(!empty($video[0]['episode_number']) ){
					if(!preg_match('/^[0-9-]+$/',$video[0]['episode_number'])){
						showmessage('ID为'.$video[0]['asset_id'].'电视剧,动漫,纪录片值为数字，表示第几集！', $HTTP_REFERER, $ms = 60);
					}
				}
			}
		
		
			//如果是总集 判断是否有海报
			if($video[0]['ispackage'] == '1'){
				$poster = $this->db_poster->select(array('asset_id'=>$video[0]['asset_id']));
				//echo '<pre>';print_r($poster); exit;
				if(!count($poster)){
					showmessage('ID为'.$video[0]['asset_id'].'总集必须先上传海报,才能申请发布!', $HTTP_REFERER, $ms = 60);
				}
			}
		//检测链接的source_id
		$content = $this->db_video_content->select(array('asset_id'=>$video[0]['asset_id']));
		if(($video[0]['column_id']=='3'||$video[0]['column_id']=='4'||$video[0]['column_id']=='7')&&$video[0]['ispackage'] == '0'){
			if(count($content)<1){
				showmessage('视频播放链接不能为空!', $HTTP_REFERER, $ms = 60);
			}
		}elseif($video[0]['column_id']=='5'){
			if(count($content)<1){
				showmessage('视频播放链接不能为空!', $HTTP_REFERER, $ms = 60);
			}
		}
			foreach ($content as $vt){
				if(empty($vt['source_id'])){
					showmessage('视频链接的类型异常!', $HTTP_REFERER, $ms = 60);
				}elseif ($vt['source_id']=='12'){
					if($video[0]['PC']=='1'){
						showmessage('pptv客户端的链接不能在pc播放，请去掉pc支持或 至少添加一个外链!', $HTTP_REFERER, $ms = 60);
					}
				}
			}
		//如果是分集则检查集数字段不能为空
		if($video[0]['ispackage'] == '0'&&($video[0]['column_id'] == '3'||$video[0]['column_id'] == '4')){
			if(empty($video[0]['episode_number'])){
				showmessage('电视栏目和电视剧的分集集数不能为空!', $HTTP_REFERER, $ms = 60);
			}
		}
			//如果是总集，没有加分集，不允许上线
			if($video[0]['ispackage'] == '1'&&($video[0]['column_id'] !='9'||$video[0]['column_id'] !='5'||$video[0]['column_id'] !='6')){
				$video_other = $this->db->select(array('parent_id'=>$video[0]['asset_id']));
				$content = $this->db_video_content->select(array('asset_id'=>$video['asset_id']));
				//echo '<pre>';print_r($poster); exit;
				if(!count($video_other)&&!count($content)){
					showmessage('ID为'.$video[0]['asset_id'].'如果是总集，没有加分集,且没有播放链接,不允许上线!', $HTTP_REFERER, $ms = 60);
				}
			}
			//如果是分集,没有填写总集id,不允许上线(除乐酷、电影)
			if($video[0]['ispackage'] == '0'&&($video[0]['column_id'] == '3'||$video[0]['column_id'] == '4'||$video[0]['column_id'] == '7'||$video[0]['column_id'] == '8')){
				if(empty($video[0]['parent_id'])){
					showmessage('ID为'.$video[0]['asset_id'].'如果是分集,没有填写总集id,不允许上线!', $HTTP_REFERER, $ms = 60);
				}
			}
		
			//如果是电影 乐酷 至少有一张海报
			if(in_array($video[0]['column_id'],array('5','6'))){
				$poster = $this->db_poster->select(array('asset_id'=>$video[0]['asset_id']));
				//echo '<pre>';print_r($poster); exit;
				if(!count($poster)){
					showmessage('ID为'.$video[0]['asset_id'].'电影、乐酷必须先上传至少1张海报,才能申请发布!', $HTTP_REFERER, $ms = 60);
				}
			}
			//检查区域筛选
			$this->video_area = pc_base::load_model('cms_video_area_model');
			$area = $this->video_area->select(array('asset_id'=>$v));
			if(!count($area)){
				showmessage('视频必须选择区域,才能申请发布!', $HTTP_REFERER, $ms = 300);
			}
		//编辑未通过则不能申请审核
		if($video[0]['online_status']=='3'){
			showmessage('此视频'.$video[0]['asset_id'].'编辑未通过,则不能申请审核!', $HTTP_REFERER, $ms = 300);
		}
			$this->db->update(array('online_status'=>10), array('asset_id'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//批量申请下线
	public function upline_pass_all(){
		$ids=explode(',', $_GET['asset_id']);
		$go3cdb = yzy_go3c_db() ;
		$this->db = pc_base::load_model('cms_video_model');
		foreach ($ids as $v){
			//检查phpcms recommend表中有没有		
			$this->recommend_video_db = pc_base::load_model('cms_pre_task_video_model');
			$tasksData = $this->recommend_video_db->select(array('videoId'=>$v),'posidInfo');
			$video = $this->db->get_one(array('asset_id'=>$v));
			if ($video['ispackage']=='1'){  //查询该总集下在线的分集
				$videoinfo = $this->db->select(array('parent_id'=>$video['asset_id'],'online_status'=>'11','published'=>'1'),'id');
				$i = 0;
				$count = count($videoinfo);
				foreach($videoinfo as $k=>$v){
					if(is_array($v)){
						$v = join(',', $v);
					}
						$str.= $v;
					if($i!=$count-1){
						$str.=',';
					}
					$i++;
				}
				if(!empty($str)){
					$this->db->update(array('offline_status'=>1),'id in ('.$str.')');
				}
				$this->db->update(array('offline_status'=>1), array('asset_id'=>$video['asset_id']));
			}
			//如果有查询推荐位名称 返回
			if(count($tasksData)>0){
				$positionstr = "";
				foreach($tasksData as $data){
					$positionstr.=$data['posidInfo']."  ";
				}
				$go3cdb->d('recommended_video',array('vid'=>$v)) ;
				$go3cdb->d('video_image',array('vid'=>$video['asset_id'])) ;
				$this->recommend_video_db->delete(array('videoId'=>$v));
				$this->db->update(array('offline_status'=>1), array('asset_id'=>$v));
			}else{
				$this->db->update(array('offline_status'=>1), array('asset_id'=>$v));
			}
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/**
	 * 批量删除视频
	 *
	 */
	public function delete_allto() {
		$this->db = pc_base::load_model('cms_video_model');
		$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');
		$this->video_content  = pc_base::load_model('cms_video_content_model');
		$this->video_area  = pc_base::load_model('cms_video_area_model');
		$ids=explode(',', $_GET['asset_id']);
		foreach ($ids as $v){
			$this->db->delete(array('asset_id'=>$v));
			$this->video_content->delete(array('asset_id'=>$v));
			$this->cms_video_poster->delete(array('asset_id'=>$v));
			$this->video_area->delete(array('asset_id'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

/**
	 * 字幕列表
	 *
	 */
	public function video_sub_list() {
				
		$this->cms_video_subtitle = pc_base::load_model('cms_video_subtitle_model');	
		$asset_id   = strip_tags(trim($_GET['asset_id']));
		$video_subtitle = $this->cms_video_subtitle->select(array('asset_id'=>$asset_id));
		include $this->admin_tpl('video_sub_list');
	}
/**
	 * 排序
	 */
	public function listorder() {
		if(!empty($_POST['listorders'])) {
			$this->db   = pc_base::load_model('cms_video_content_model');
			foreach($_POST['listorders'] as $id => $listorder) {
				$this->db->update(array('listorder'=>$listorder),array('id'=>$id));
			}
			showmessage(L('operation_success'));
		} else {
			showmessage(L('operation_failure'));
		}
	}
	/**
	 * 在线视频的播放链接
	 */
	public function offline_content_list(){
		$this->video_play_info = pc_base::load_model('tv_video_play_info_model');	
		$asset_id   = strip_tags(trim($_GET['asset_id']));
		$play_info = $this->video_play_info->select(array('vid'=>$asset_id));
		include $this->admin_tpl('video_offline_play');
	}
	/**
	 * 删除在线链接
	 */
	public function video_play_delete(){
		$vid   = strip_tags(trim($_GET['vid']));
		$play_url   = $_GET['play_url'];
		$this->video_play_info = pc_base::load_model('tv_video_play_info_model');
		if(!empty($vid)&&!empty($play_url)){
			$this->video_play_info->delete(array('play_url'=>$play_url));
			showmessage(L('operation_success'));
		}else{
			showmessage(L('operation_failure'));
		}
	}
	/**
	 * 批量设置数据有误
	 */
	public function online_error(){
		$ids=explode(',', $_GET['asset_id']);
		$this->db = pc_base::load_model('cms_video_model');
		foreach ($ids as $v){
			$this->db->update(array('online_status'=>3), array('asset_id'=>$v));
		}		
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/**
	 * 删除数据
	 */
	public function delete_video(){
		$this->db = pc_base::load_model('cms_video_model');
		$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');
		$this->video_content  = pc_base::load_model('cms_video_content_model');
		$this->video_area  = pc_base::load_model('cms_video_area_model');
		$asset_id=$_GET['asset_id'];
		if(!empty($asset_id)){
			$this->db->delete(array('asset_id'=>$asset_id));
			$this->video_content->delete(array('asset_id'=>$asset_id));
			$this->cms_video_poster->delete(array('asset_id'=>$asset_id));
			$this->video_area->delete(array('asset_id'=>$asset_id));
			showmessage(L('operation_success'));
		}else{
			showmessage(L('operation_failure'));
		}
	}
	/**
	 * 在线视频的图片
	 */
	public function offline_poster_list(){
		$this->video_image = pc_base::load_model('tv_video_image_model');
		$asset_id = $_GET['asset_id'];
		$image_info = $this->video_image->select(array('vid'=>$asset_id));
		include $this->admin_tpl('video_image');
	}
	/**
	 * 在线删除图片
	 */
	public function video_image_delete(){
		$this->video_image = pc_base::load_model('tv_video_image_model');
		$vid = $_GET['vid'];
		$img_type = $_GET['img_type'];
		if(!empty($vid)){
			$this->video_image->delete(array('vid'=>$vid,'img_type'=>$img_type));
			showmessage(L('operation_success'));
		}else{
			showmessage(L('operation_failure'));
		}
	}
	//预告片列表
	public function video_trailer_list(){
		$asset_id = $_GET['asset_id'];
		$this->video_trailer = pc_base::load_model('cms_video_trailer_model');
		$video_trailer = $this->video_trailer->select(array('asset_id'=>$asset_id));
		include $this->admin_tpl('video_trailer_list');
	}
	//添加预告片模型
	public function traileradd(){
		$asset_id = $_GET['asset_id'];
		$this->video_source = pc_base::load_model('cms_video_source_model');
		$subtitlePathDB = $this->video_source->select();
		include $this->admin_tpl('trailer_add');
	}
	//添加预告片
	public function traileradddo(){
		$tid = trim($_POST['tid']);
		$asset_id = trim($_POST['asset_id']);
		$quality = trim($_POST['quality']);
		$aspect = trim($_POST['aspect']);
		$source = trim($_POST['source']);
		$play_url = trim($_POST['play_url']);
		$ratio = trim($_POST['ratio']);
		$format = trim($_POST['format']);
		$protocol = trim($_POST['protocol']);
		$language = trim($_POST['language']);
		$ad_imgUrl = trim($_POST['ad_imgUrl']);
	
		if(!empty($asset_id)&&!empty($play_url)&&!empty($tid)){
			$this->video_trailer = pc_base::load_model('cms_video_trailer_model');
			$data_trailer = array(
					'tid' => $tid,
					'asset_id' => $asset_id,
					'play_url' => $play_url,
					'source' => $source,
					'quality' => $quality,
					'aspect' => $aspect,
					'ratio' => $ratio,
					'protocol' => $protocol,
					'format' => $format,
					'language' => $language,
					'img_url' => $ad_imgUrl,
					'addtime' => time()
			);
			$this->video_trailer->insert($data_trailer);
			//添加操作日志
			$type = 'traileradd';
			$this->go3ccms_changlog($asset_id,$type,json_encode($data_trailer));
			showmessage('操作成功!',base64_decode($_GET['goback']),2000);
		}else{
			showmessage('操作失败,视频id或链接不能为空!',HTTP_REFERER);
		}
	}
	//修改预告片模型
	public function traileredit(){
		$id = $_REQUEST['id'];
		$this->video_trailer = pc_base::load_model('cms_video_trailer_model');
		$aKey = "id = '".$id."'";
		$limitInfo = $this->video_trailer->get_one($aKey);
		$this->video_source = pc_base::load_model('cms_video_source_model');
		$subtitlePathDB = $this->video_source->select();
		include $this->admin_tpl('video_traileredit');
	}
	//修改预告片
	public function trailereditdo(){
		$id = trim($_POST['id']);
		$tid = trim($_POST['tid']);
		$asset_id = trim($_POST['asset_id']);
		$quality = trim($_POST['quality']);
		$aspect = trim($_POST['aspect']);
		$source = trim($_POST['source']);
		$play_url = trim($_POST['play_url']);
		$ratio = trim($_POST['ratio']);
		$format = trim($_POST['format']);
		$protocol = trim($_POST['protocol']);
		$language = trim($_POST['language']);
		$ad_imgUrl = trim($_POST['ad_imgUrl']);
	
		if(!empty($id)&&!empty($play_url)&&!empty($tid)){
			$this->video_trailer = pc_base::load_model('cms_video_trailer_model');
			$data_trailer = array(
					'tid' => $tid,
					'asset_id' => $asset_id,
					'play_url' => $play_url,
					'source' => $source,
					'quality' => $quality,
					'aspect' => $aspect,
					'ratio' => $ratio,
					'protocol' => $protocol,
					'format' => $format,
					'language' => $language,
					'img_url' => $ad_imgUrl,
					'addtime' => time()
			);
			$this->video_trailer->update($data_trailer,array('id'=>$id));
			//添加操作日志
			$type = 'traileredit';
			$this->go3ccms_changlog($asset_id,$type,json_encode($data_trailer));
			showmessage('操作成功!',base64_decode($_GET['goback']),2000);
		}else{
			showmessage('操作失败,视频id或链接不能为空!',HTTP_REFERER);
		}
	}
	//删除预告片
	public function trailer_delete(){
		$id = $_GET['id'];
		$asset_id = $_GET['asset_id'];
		if(empty($id)) showmessage('操作失败,视频id不能为空!',HTTP_REFERER);
		$this->video_trailer = pc_base::load_model('cms_video_trailer_model');
		$this->video_trailer->delete(array('id'=>$id));
		//添加操作日志
		$type = 'trailer_delete';
		$this->go3ccms_changlog($asset_id,$type);
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	//电影从豆瓣获取导演,演员等信息
	public function douban(){
		$this->db = pc_base::load_model('cms_video_model');
		$title = request('title');
		if(empty($title)){
			showmessage('名称不能为空',$_SERVER['HTTP_REFERER'], $ms = 500);
		}
		$urlt='http://api.douban.com/v2/movie/search?q='.$title;
		$sx = file_get_contents($urlt) ;
		$datati = json_decode($sx,true);
		if(count($datati['subjects'])>1){
			$datati['subjects'] = array_slice($datati['subjects'], 0, 1);
		}	
		foreach($datati['subjects'] as $v){
			//根据获取到的id获取视频的相关信息(评分,导演)
			$id = $v['id'];
			$urlv='http://api.douban.com/v2/movie/subject/'.$id;
			$sxv = file_get_contents($urlv) ;
			$objx = json_decode($sxv,true);
			$tag = implode(',',$objx['genres']);
			$area = implode(',',$objx['countries']);
			$area=explode(",",$area);
			$average=$objx['rating']['average'];
			foreach($objx['casts'] as $vv){
				$actor =$actor.','. $vv['name'];
			}
			foreach($objx['directors'] as $vd){
				$directors=$directors.','.$vd['name'];
			}
		}
		$actor = substr($actor,1,strlen($actor));
		$directors = substr($directors,1,strlen($directors));
		if(strpos($area['0'],'中国大陆')!== false){
			$area_id = 1;
		}elseif(strpos($area['0'],'香港')!== false){
			$area_id = 2;
		}elseif(strpos($area['0'],'台湾')!== false){
			$area_id = 2;
		}elseif(strpos($area['0'],'美国')!== false){
			$area_id = 3;
		}elseif(strpos($area['0'],'韩国')!== false){
			$area_id = 5;
		}else{
			$area_id = 4;
		}
		$dou = array(
				'area_id' 	=> $area_id,
				'director' 	=> $directors,
				'actor' 	=> $actor,
				'tag' 		=> $tag,
				'rating'	=>$average
			);
		$this->db->update($dou,array('title'=>$title,'column_id'=>5));
		showmessage('操作成功!',base64_decode($_GET['goback']),2000);
	}
}
?>
