<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class hot_tag extends admin {

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
		
		$this->db   = pc_base::load_model('tv_column_content_category_model');
		$col_id 	= intval($_GET['col_id']);
		$col_filt   = $col_id ? " AND s.col_id = '$col_id'" : '';
		$tag_name 	= trim($_GET['tag_name']);
		$name_filt  = $tag_name ? " AND s.tag_name LIKE '%$tag_name%'" : '';
		$field    	= '*';
		$sql     	= "
			column_content_category s
			LEFT JOIN tag tg ON tg.tag_name = s.cat_name
			LEFT JOIN phpcms.v9_hot_tag t ON t.hot_tag_id = tg.tag_id 
			WHERE `tag_name` !='' ".$name_filt.$col_filt;
		//echo $sql;
		$order  	= 'ORDER BY t.hot_tag_name DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('hot_tag');			
	}
	
	/**
	 * 添加
	 *
	 */
	public function add(){
	
		$col_id   = intval($_GET['col_id']);
		$tag_id   = intval($_GET['tag_id']);
		$tag_name = trim($_GET['tag_name']);
		$data_array = array(
			'hot_col_id' 	=> $col_id,
			'hot_tag_id' 	=> $tag_id,
			'hot_tag_name'  => $tag_name,
			'dateline'      => time(),
		);
		//echo '<pre>';print_r($data_array);exit;
		$this->db = pc_base::load_model('cms_hot_tag_model');	
		$insert_id = $this->db->insert($data_array, true);
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/**
	 * 删除
	 *
	 */
	public function del() {
		$ht_id 		= intval($_GET['ht_id']);
		$this->db   = pc_base::load_model('cms_hot_tag_model');	
		//echo '<pre>';print_r($ht_id);exit;
		$this->db->delete(array('ht_id'=>$ht_id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	
	
	/**
	 * 数据同步前清空老数据
	 *
	 */
	public function pre_sync() {
		$this->db = pc_base::load_model('tv_hot_tags_model');
		$this->db->query("truncate table hot_tags");
		showmessage('正在清空老数据，下面开始同步数据到前端','?m=go3c&c=hot_tag&a=sync',$ms = 500);
	}

	
	/**
	 * 数据同步
	 *
	 */
	public function sync() {

		$this->db  = pc_base::load_model('cms_hot_tag_model');		
		$this->db2 = pc_base::load_model('tv_hot_tags_model');		
		$field    	= '*';
		$sql     	= 'v9_hot_tag '; 
		$order  	= 'ORDER BY ht_id ASC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 1;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		
		if($page < $totalpage+1){
			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $multipage.' ';
			
			$actor_array = array();
			$col_id_array = explode(',', $data_array[0]['hot_col_id']);
			foreach ($col_id_array AS $col_value){
				$actor_array[] = array('seq_number'=>$data_array[0]['ht_id'], 'tag_id'=>$data_array[0]['hot_tag_id'], 'column_id'=>$col_value);
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
			page_jump('go3c', 'hot_tag', 'sync', $next_page);
		}else{
			showmessage(L('operation_success'),'?m=go3c&c=hot_tag&a=init',$ms = 500);
		}
		
	}
	public function impot_tag(){
		$this->db  = pc_base::load_model('cms_tags_model');
		$this->hot_tag  = pc_base::load_model('cms_hot_tag_model');
		$this->g_tag  = pc_base::load_model('tv_tag_model');
		$url = 'http://www.go3c.tv:8060/hotsearch.php';
		$s = file_get_contents($url);
		$s = json_decode($s);
		foreach ($s->tv as $v){  		//电视剧关键词
			self::add_hot_tag($v,4);
		}
		foreach ($s->movie as $v){  	//电影关键词
			self::add_hot_tag($v,5);
		}
		foreach ($s->variety as $v){  	//综艺关键词
			self::add_hot_tag($v,3);
		}
		foreach ($s->comic as $v){  	//动漫关键词
			self::add_hot_tag($v,7);
		}
		showmessage(L('succes!'),HTTP_REFERER);
	}
	
	//添加单个tag
	private function add_hot_tag($v,$cumlan){
		$tag = array(
				'catid' => '4',
				'typeid' => '0',
				'title' => $v,
				'posids' => '0',
				'status' => '99',
				'sysadd' => '1',
				'username' => 'system',
				'inputtime' => time(),
				'type' => '2',
				'belong' => $cumlan,
				'online_status' => '11',
				'offline_status' => '0',
				'published' => '1'
		);
		$ad_tag = $this->db->get_one(array('title'=>$v));
		if(empty($ad_tag)){
			$this->db->insert($tag);
			$atag = $this->db->get_one(array('title'=>$v));
			$tid = $atag['id'];
		}else{
			$tid = $ad_tag['id'];
		}
		$hot_tag = $this->hot_tag->get_one(array('hot_tag_name'=>$v,'hot_col_id'=>$cumlan));
		if(empty($hot_tag)){
			$hot = array(
				'hot_col_id' => $cumlan,
				'hot_tag_id' => $tid,
				'hot_tag_name' => $v,
				'dateline' => time()
			);
			$this->hot_tag->insert($hot);
		}
		$hot_tag = $this->g_tag->get_one(array('tag_id'=>$tid,'type'=>'2'));
		if(empty($hot_tag)){
			$g_tag=array(
					'tag_id' => $tid,
					'tag_name' => $v,
					'type' => '2'
			);
			$this->g_tag->insert($g_tag);
		}
	}
}
?>