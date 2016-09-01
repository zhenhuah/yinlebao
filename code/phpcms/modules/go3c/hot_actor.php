<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class hot_actor extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	/**
	 * 热门演员
	 *
	 */
	public function init() {
		
		$this->belong_type = pc_base::load_model('cms_column_model');
		
		$belong_type_list = $this->belong_type->select('', 'id,title', '', 'id ASC');
		$belong_type_array = array();
		foreach($belong_type_list as $pvalue){
			if(intval($pvalue['id']) > 2 )
			$belong_type_array[$pvalue['id']]=$pvalue['title'];
		}
		
		$this->db   = pc_base::load_model('tv_actor_model');
		$name 		= trim($_GET['name']);
		$name_filt  = $name ? " AND s.name LIKE '%$name%'" : '';
		$col_id 	= intval($_GET['col_id']);
		$col_filt   = $col_id ? " AND t.col_id LIKE '%$col_id%'" : '';
		$field    	= '*';
		$sql     	= "actor s LEFT JOIN phpcms.v9_hot_actor t ON t.hot_actor_id = s.id WHERE `name` !=''".$name_filt.$col_filt;
		$order  	= 'ORDER BY t.col_id DESC, s.name asc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('hot_actor');			
	}
	
	/**
	 * 更新信息
	 *
	 */
	public function update() {
		$this->db = pc_base::load_model('cms_hot_actor_model');
		$id   	  = intval($_GET['id']);
		$col_id   = trim($_GET['belong']);
		
		//检查目标表
		$target = $this->db->get_one(array('hot_actor_id' => $id),'hot_actor_id');
		
		if($col_id){
			if($target['hot_actor_id']){
				$data_array = array(
					'col_id' 	   => $col_id,
					'dateline'     => time(),
				);
				$this->db->update($data_array, array('hot_actor_id'=>$id));
			}else{
				$data_array = array(
					'hot_actor_id' => $id,
					'col_id' 	   => $col_id,
					'dateline'     => time(),
				);
				$this->db->insert($data_array, true);
			}
		}else{
			if($target['hot_actor_id']){
				$this->db->delete(array('hot_actor_id'=>$target['hot_actor_id']));
			}
		}
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);

	}
	
	/**
	 * 添加
	 *
	 */
	public function add(){
	
		$id 	= intval($_GET['id']);
		$name	= trim($_GET['name']);
		$data_array = array(
			'hot_actor_id' => $id,
			'dateline'     => time(),
		);
		//echo '<pre>';print_r($data_array);exit;
		$this->db = pc_base::load_model('cms_hot_actor_model');	
		$insert_id = $this->db->insert($data_array, true);
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/**
	 * 删除
	 *
	 */
	public function del() {
		$ha_id 		= intval($_GET['ha_id']);
		$this->db   = pc_base::load_model('cms_hot_actor_model');	
		//echo '<pre>';print_r($ha_id);exit;
		$this->db->delete(array('ha_id'=>$ha_id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/**
	 * 数据同步前清空老数据
	 *
	 */
	public function pre_sync() {
		$this->db = pc_base::load_model('tv_hot_actors_model');
		$this->db->query("truncate table hot_actors");
		showmessage('正在清空老数据，下面开始同步数据到前端','?m=go3c&c=hot_actor&a=sync',$ms = 500);
	}

	
	/**
	 * 数据同步
	 *
	 */
	public function sync() {

		$this->db  = pc_base::load_model('cms_hot_actor_model');		
		$this->db2 = pc_base::load_model('tv_hot_actors_model');		
		$field    	= '*';
		$sql     	= 'v9_hot_actor '; 
		$order  	= 'ORDER BY ha_id ASC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 1;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		
		if($page < $totalpage+1){
			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $multipage.' ';
			
			$actor_array = array();
			$col_id_array = explode(',', $data_array[0]['col_id']);
			foreach ($col_id_array AS $col_value){
				$actor_array[] = array('seq_number'=>$data_array[0]['ha_id'], 'actor_id'=>$data_array[0]['hot_actor_id'], 'column_id'=>$col_value);
			}
			
			/**
			 * 插入前端代码开始
			 */
			foreach ($actor_array as $value) {
				if($value['column_id']){
					$this->db2->insert($value, true);
				}
			}
			/**
			 * 插入前端代码结束
			 */
			//echo '<pre>';print_r($actor_array);
			echo '正在同步...';
			//exit;
			
			$next_page = $page + 1;
			page_jump('go3c', 'hot_actor', 'sync', $next_page);
		}else{
			showmessage(L('operation_success'),'?m=go3c&c=hot_actor&a=init',$ms = 500);
		}
		
	}
	
	
}
?>