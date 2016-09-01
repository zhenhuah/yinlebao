<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class publishlog extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		//print_r($this->current_spid);
	}   	
	
	public function init() {
		
		$action_content = array(
			'video'			=>'上线视频',
			'off_video'		=>'下线视频',
			'del_video'		=>'删除视频',
			'channel'		=>'上线频道',
			'channelepg'	=>'上线EPG',
			//'position'	=>'上线推荐位',
			'tags_area'		=>'上线地区',
			'tags_cate'		=>'上线栏目分类',
			'tags_year'		=>'上线年代',
			'client_online' =>'客户端上线',
			'client_delete' =>'客户端删除',
			'pre_adverts' 	=>'广告位',
			'pre_task'      =>'推荐位',
		) ;
		
		$this->db   = pc_base::load_model('cms_publishlog_model');
		
		//超级管理员可以搜索，其他人员自己看自己
		if($this->current_spid['roleid'] == '1'){
			$username   = trim($_GET['username']);
			$username_filt = $username ? " AND username LIKE '%$username%'" : '';
		}else{
			$username_filt =  " AND username = '$this->current_spid[username]' ";
		}
		
		$field    	= '*';
		$sql     	= "v9_publishlog WHERE 1 ".$username_filt;
		$order  	= 'ORDER BY id DESC';
		//echo 'SELECT '.$field.' FROM '.$sql.' '.$order;
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('publishlog_list');		
	}

}
?>
