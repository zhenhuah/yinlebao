<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class go3capi_log_livechcount_model extends model {
	public function __construct() {
		//��ȡ���ݿ�����
		$this->db_config  = pc_base::load_config('database');
		//ָ�����ݿ������о�������ݿ�����
		$this->db_setting = 'go3capi';
		//ָ�����Ӿ������ű�
		$this->table_name = 'log_livechcount';
		//�������ݿ�
		parent::__construct();
	}
}
?>