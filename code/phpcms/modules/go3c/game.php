<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class game extends admin {

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
	 * 游戏类型列表
	 *
	 */
	public function type_list() {
		$this->db   = pc_base::load_model('cms_game_type_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? "WHERE 1 AND title LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "v9_game_type ".$title_filt;
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('game_type_list');			
	}
	
	/**
	 * 游戏列表
	 *
	 */
	public function game_list() {
		$this->game_type = pc_base::load_model('cms_game_type_model');
		$game_type_list = $this->game_type->select('', 'id, title', '', 'id ASC');
		$game_type_array[0] = '请选择';
		foreach($game_type_list as $gvalue){
			$game_type_array[$gvalue['id']]=$gvalue['title'];
		}
		$this->db   = pc_base::load_model('cms_game_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND title LIKE '%$title%'" : '';
		
		$game_type  = intval($_GET['game_type']);
		$type_filt  = $game_type ? " AND game_type = '$game_type'" : '';
		$field    	= '*';
		$sql     	= "v9_game WHERE 1 ".$title_filt.$type_filt;
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('game_list');		
	}
	
}
?>
