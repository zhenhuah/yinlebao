<?php
/**
 * 发短信
 *
 */
class msg {
	public function init() {
		$this->db = pc_base::load_model('message_model');
		$this->db->add_message('editor', 'system', '测试短消息', '测试短消息', true);
	}	
}
?>