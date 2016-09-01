<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class playcount extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	/**
	 * 已发布视频列表
	 *
	 */
	 /*
	public function init() {
		
		$this->db   = pc_base::load_model('tv_video_model');
		$vid 		= trim($_GET['vid']);
		$vid_filt   = $vid  ? " AND vid  LIKE '%$vid%'" : '';
		$name 		= trim($_GET['name']);
		$name_filt  = $name ? " AND name LIKE '%$name%'" : '';
		$field    	= '*';
		$spidstr= " AND spid = '".$this->current_spid[spid]."'";
		$sql     	= " video WHERE 1 ".$vid_filt.$name_filt.$spidstr;
		$order  	= 'ORDER BY vid DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('playcount_list');			
	}
	*/
	public function init(){
		$this->db   = pc_base::load_model('tv_video_model');
		$vid = $_GET['vid'];
		$name = $_GET['name'];
		$field    = isset($_GET['field']) ? $_GET['field'] : 'vid';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$where = " 1 ";
		$vid ? $where.= " AND `vid` = '$vid' " : '';
		$name != '' ? $where.= " AND `name` LIKE '%$name%'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('playcount_list');
	}
	/**
	 * 更新信息
	 *
	 */
	public function edit() {
		$this->db   = pc_base::load_model('tv_video_model');
		$vid   	    = trim($_GET['vid']);
		$play_count = intval($_GET['play_count']);
		$data_conf  = array('vid' => $vid);
		$data_array = array('play_count' => $play_count);
		$this->db->update($data_array, $data_conf);
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
}
?>
