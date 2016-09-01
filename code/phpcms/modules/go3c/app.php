<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

//定义SP i
//define('SPID','ddssp') ;

//定义使用线上接口还是本地接口
define('USE_ONLINE_API',true) ;


require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;



class app extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		global $spid;
		$spid = $this->current_spid['spid'];
	}

	public function init() {

	}

	/**
	 * 资源导入
	 */
	public function app() {
		$url1 = "http://apps.wasu.com.cn:8120/Interface/app/format/json";
		$sf = file_get_contents($url1) ;
		$dataf = json_decode($sf,true);
		foreach ( $dataf as $var ) {
			$appid = $var['id'];
			$url2 = "http://apps.wasu.com.cn:8120/Interface/details/format/json/id/1322";
			$sx = file_get_contents($url2) ;
			$obj=json_decode($sx);
			$title =  $obj->title;
			var_dump($title);
			exit;
		}
	}
	
}
