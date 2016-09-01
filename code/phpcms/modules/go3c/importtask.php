<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
define('TASK_IMG_PATH','http://image.bigtv.com.cn/images/go3ccms/');
class importtask extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	/**
	 * 导入设置
	 *
	 */
	public function tasklist() {
		include $this->admin_tpl('import_tasklist');			
	}
	
}
?>