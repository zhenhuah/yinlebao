<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_app_func('admin');
class admins extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		$this->db = pc_base::load_model('admin_model');
		$this->role_db = pc_base::load_model('admin_role_model');
		$this->op = pc_base::load_app_class('admin_op');
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	public function init() {
		$userid = $_SESSION['userid'];
		$admin_username = param::get_cookie('admin_username');
		$page = $_GET['page'] ? intval($_GET['page']) : '1';
		$infos = $this->db->listinfo('', '', $page, 20);
		$pages = $this->db->pages;
		$roles = getcache('role','commons');
		include $this->admin_tpl('admin_list');
	}
	
	/**
	 * 角色管理
	 *
	 */
	public function group() {
		
	}
	
	/**
	 * 用户管理
	 *
	 */
	public function member() {
		
	}
	

}
?>