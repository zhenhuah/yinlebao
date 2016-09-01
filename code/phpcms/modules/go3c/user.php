<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/db_list.php' ;

class video extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		pc_base::load_sys_class('form','',0);
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	public function init() {
	}
	
	/**
	 * 用户反馈列表
	 *
	 */
	public function user_feedback(){
		$this->db = pc_base::load_model('tv_issue_report_model');
		$issue_type = trim($_GET['issue_type']);
		$issue_type ? $where.= " `issue_type` LIKE '%$issue_type%'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';

		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';

		$data  = $this->db->listinfo($where, $order = '`time_added` DESC', $page, $perpage);
		//echo '<pre>'; print_r($data);
		$pages = $this->db->pages;
		include $this->admin_tpl('tvuser_user_feedback');
	}
}
?>
