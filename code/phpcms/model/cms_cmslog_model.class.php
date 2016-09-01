<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class cms_cmslog_model extends model {
	public function __construct() {
		//获取数据库配置
		$this->db_config  = pc_base::load_config('database');
		//指定数据库配置中具体的数据库连接
		$this->db_setting = 'default';
		//指定连接具体哪张表
		$this->table_name = 'cmslog';
		//连接数据库
		parent::__construct();
	}
}
?>