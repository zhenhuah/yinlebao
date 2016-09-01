<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class server extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
	}   	
	
	public function init() {
		$this->db = pc_base::load_model('cms_pic_server_model');
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $pagesize = 15);
		$pages = $this->db->pages;
		include $this->admin_tpl('pic_server');
	}
	
}
?>
