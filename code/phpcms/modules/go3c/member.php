<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class member extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	

	public function init() {
		
	}
	
	/**
	 * 用户锁定
	 *
	 */
	public function lock() {
		
	}
	
	/**
	 * 用户解锁
	 *
	 */
	public function unlock() {
		
	}
	
	/**
	 * 修改密码
	 *
	 */
	public function unlock() {
		
	}

}
?>