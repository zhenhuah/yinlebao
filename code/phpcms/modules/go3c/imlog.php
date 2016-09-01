<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;
yzy_sooner_db() ;

class imlog extends admin {

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
	 * 日志显示
	 */
	public function show() {
		$id = request('id','GET','int') ;
		if(!$id) return false ;
		
		global $db ;
		$d = array() ;
		$d['imlog'] = $db->r1('go3c_imlog',array('id'=>$id)) ;
		
		$d['imlog'] = fix_imlog($d['imlog']) ;
		
		extract($d) ;
		include $this->admin_tpl('imlog_show');
	}

}

