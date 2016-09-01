<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class games extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		$this->db   = pc_base::load_model('shop_type_model');
		$this->game_db = pc_base::load_model('games_model');
		$this->game_url_db = pc_base::load_model('games_url_model');
		$this->game_image_db = pc_base::load_model('games_image_model');
	}   	
	
	public function init() {
		
	}
	
	/**
	 * 游戏类型列表
	 *
	 */
	public function type() {
		$admin_username = param::get_cookie('admin_username');
		$type_name   	= trim($_GET['type_name']);
		$title_filt = $type_name ? " AND cat_name LIKE '%$type_name%' " : '';
		$field    	= '*';
		$sql     	= "app_channel_category WHERE 1 ".$title_filt;
		$sql .= " AND pid = 10 ";
		$order  	= 'ORDER BY cat_id';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('games_type');			
	}
	
	/*
	 * 添加游戏类型
	 */
	public function add_shop_type(){
		//$type_list = $this->db->select('', 'cat_id, cat_name');
		include $this->admin_tpl('games_type_add');
	}
	
	/*
	 * 添加游戏类型
	 */
	public function add_shop_type_do(){
		$type_name = $_POST['type_name'];
		$sort = $_POST['sort'] == '' ? '0' : $_POST['sort'];
		$count = $_POST['count'] == '' ? '0' : $_POST['count'];
		$status = $_POST['status'] == '' ? '1' : $_POST['status'];
		$url = $_POST['url'];
		$description = $_POST['description'];
		$remark = $_POST['remark'];
		$data_type = array(
			'cat_name' => $type_name,
			'description' => $description,
			'url' => $url,
			'status' => $status,
			'remark' => $remark,
			'sort' => $sort,
			'pid' => 10,
			'level' => 2,
			'count' => $count
		);
		$this->db->insert($data_type);
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=games&a=type&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 删除游戏类型
	 */
	public function delete_type(){
		$id = $_REQUEST['id'];
		$this->db->delete(array('cat_id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/*
	 * 编辑游戏类型
	*/
	public function edit_shop_type(){
		$id = $_REQUEST['id'];
		$type_list = $this->db->select('', 'cat_id, cat_name');
		$aKey = "cat_id = '".$id."'";
		$data = $this->db->get_one($aKey);
		include $this->admin_tpl('games_type_edit');
	}
	
	/*
	 * 编辑游戏类型
	*/
	public function edit_shop_type_do(){
		$id = $_REQUEST['id'];
		$type_name = $_POST['type_name'];
		$sort = $_POST['sort'] == '' ? '0' : $_POST['sort'];
		$count = $_POST['count'] == '' ? '0' : $_POST['count'];
		$status = $_POST['status'] == '' ? '1' : $_POST['status'];
		$url = $_POST['url'];
		$description = $_POST['description'];
		$remark = $_POST['remark'];
		$data_type = array(
			'cat_name' => $type_name,
			'description' => $description,
			'url' => $url,
			'status' => $status,
			'remark' => $remark,
			'sort' => $sort,
			'pid' => 10,
			'level' => 2,
			'count' => $count
		);
		$this->db->update($data_type,array('cat_id'=>$id));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=games&a=type&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/**
	 * 游戏列表
	 *
	 */
	public function shop_list() {
		$admin_username = param::get_cookie('admin_username');
		$type_name_list = $this->db->select('pid = 10', 'cat_id, cat_name', '', 'cat_id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND title LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "game WHERE status!=2 ".$title_filt;
		//查询处理 start 
		$type = '请选择';
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$status = trim($_GET['status']);
			if(!empty($type) && $type != '请选择')
			{
				$sql .= " AND channel = '".$type."'";
			}
			if(!empty($status) && $type != '全部')
			{
				$sql .= " AND status = '".$status."'";
			}
		}
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->game_db->mynum($sql);
		$totalpage	= $this->game_db->mytotalpage($sql, $perpage);
		$data 		= $this->game_db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->game_db->pages;
		include $this->admin_tpl('games_list');		
	}
	
	/*
	 * 删除游戏
	 */
	public function delete_app(){
		$id = $_REQUEST['id'];
		//$this->app_image = pc_base::load_model('app_image_model');
		//$this->app_image->delete(array('app_id'=>$id));
		//$this->game_db = pc_base::load_model('app_model');
		$this->game_db->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/*
	 * 游戏上线发布
	 */
	public function verify_on(){
		$id = $_REQUEST['id'];
		$this->game_db->update(array('status'=>4), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	/*
	 * 游戏下线
	 */
	public function verify_off(){
		$id = intval($_GET['id']);
		$this->game_db->update(array('status'=>1), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	/**
	 * 游戏链接列表
	 *
	 */
	public function games_link_list() {
		$gameid = $_GET['id'];
		$admin_username = param::get_cookie('admin_username');
		$url   	= trim($_GET['url']);
		$url_filt = $url ? " AND url LIKE '%$url%'" : '';
		$field    	= '*';
		$sql     	= "game_url WHERE game_id = $gameid ".$url_filt;
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->game_url_db->mynum($sql);
		$totalpage	= $this->game_url_db->mytotalpage($sql, $perpage);
		$data 		= $this->game_url_db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->game_url_db->pages;
		include $this->admin_tpl('games_link_list');		
	}
	
	/*
	 * 添加游戏链接
	 */
	public function add_game_url(){
		$gameid = $_GET['id'];
		include $this->admin_tpl('games_url_add');
	}
	
	public function add_game_url_do() {
		$gameid = $_POST['gameid'];
		$url = $_POST['url'];
		$version = $_POST['version'];
		$size = $_POST['size'];
		$release_date = $_POST['release_date'];
		$sort = $_POST['sort'];
		$data = array(
			'game_id'		=>	$gameid,
			'url'			=>	$url,
			'version'		=>	$version,
			'size'			=>	$size,
			'release_date'	=>	$release_date,
			'sort'			=>	$sort
		);
		$this->game_url_db->insert($data);
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=games&a=games_link_list&id='.$gameid.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 删除游戏链接
	 */
	public function delete_game_url(){
		$id = $_REQUEST['id'];
		$this->game_url_db->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/*
	 * 编辑游戏链接
	*/
	public function edit_game_url(){
		$id = $_REQUEST['id'];
		$aKey = "id = '".$id."'";
		$data = $this->game_url_db->get_one($aKey);
		include $this->admin_tpl('games_url_edit');
	}
	
	public function edit_game_url_do() {
		$id = $_REQUEST['id'];
		$game_id = $_REQUEST['game_id'];
		$url = $_POST['url'];
		$version = $_POST['version'];
		$size = $_POST['size'];
		$release_date = $_POST['release_date'];
		$sort = $_POST['sort'];
		$data = array(
			'url'			=>	$url,
			'version'		=>	$version,
			'size'			=>	$size,
			'release_date'	=>	$release_date,
			'sort'			=>	$sort
		);
		$this->game_url_db->update($data, array('id' => $id));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=games&a=games_link_list&id='.$game_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/**
	 * 游戏图片列表
	 *
	 */
	public function games_image_list() {
		$gameid = $_GET['id'];
		$admin_username = param::get_cookie('admin_username');
		$field    	= '*';
		$sql     	= "game_image WHERE game_id = $gameid ";
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->game_image_db->mynum($sql);
		$totalpage	= $this->game_image_db->mytotalpage($sql, $perpage);
		$data 		= $this->game_image_db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->game_image_db->pages;
		include $this->admin_tpl('games_image_list');		
	}
	/*
	 * 编辑游戏图片
	*/
	public function edit_game_image(){
		$id = $_REQUEST['id'];
		$aKey = "id = '".$id."'";
		$data = $this->game_image_db->get_one($aKey);
		include $this->admin_tpl('games_image_edit');
	}
	/*
	 * 删除游戏图片
	 */
	public function delete_game_image(){
		$id = $_REQUEST['id'];
		$gameid = $_REQUEST['game_id'];
		if(empty($id)){
			$msg = '提交失败!,id不能为空!';
		}else{
			$msg = '提交成功!';
			$this->game_image_db->delete(array('id'=>$id));
		}
		showmessage($msg,'?m=go3c&c=games&a=games_image_list&id='.$gameid.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	/*
	 * 显示所有在线的游戏,可以添加入推进位
	 */
	public function showgame(){
		$taskId   	= trim($_GET['taskId']);
		$admin_username = param::get_cookie('admin_username');
		$type_name_list = $this->db->select('pid = 10', 'cat_id, cat_name', '', 'cat_id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND title LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "game WHERE status=4 ".$title_filt;
		//查询处理 start
		$type = '请选择';
		if(!empty($_GET['search']))
		{
			//任务(推荐位)
			$type = trim($_GET['game_type']);
			if(!empty($type) && $type != '请选择')
			{
				$sql .= " AND channel = '".$type."'";
			}
		}
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->game_db->mynum($sql);
		$totalpage	= $this->game_db->mytotalpage($sql, $perpage);
		$data 		= $this->game_db->mylistinfo($field, $sql, $order, $page, $perpage);
		//echo '<pre>';print_r($data);
		$multipage  = $this->game_db->pages;
		include $this->admin_tpl('game_show');
	}
	/*
	 * 列出某推荐位的游戏应用
	*/
	public function viewGame(){
		$this->game_task_game = pc_base::load_model('game_task_game_model');//应用任务数据表连接
		$taskId   	= trim($_GET['taskId']);
		$where = " taskId =$taskId";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$infor_list = $this->game_task_game->listinfo($where, $order = "", $page, $perpage);
		$pages = $this->game_task_game->pages;
		include $this->admin_tpl('game_view_list');
	}
	/*
	 * 	//删除该推荐位的此应用
	*/
	public function deleteTaskinfor(){
		$preId   	= trim($_GET['preId']);
		$taskId   	= trim($_GET['taskId']);
		$this->task_db = pc_base::load_model('game_task_model');
		$this->game_task_game = pc_base::load_model('game_task_game_model');
		if(empty($preId)||empty($taskId)){
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$where_data = array(
					'taskId'=> $taskId,
					'preId' => $preId
			);
			$this->game_task_game->delete($where_data);
			$gkey="taskId = $taskId";
			$taskInfo = $this->task_db->get_one($gkey);
			$videoNums = $taskInfo['videoNums']-1;
			$taskUpdate = array(
					'videoNums'=>$videoNums
			);
			$taskWhere =array(
					'taskId'=> $taskId
			);
			$this->task_db->update($taskUpdate,$taskWhere);
			showmessage(L('operation_success'),HTTP_REFERER);
		}
	}
	/*
	 * 批量下线游戏
	 */
	public function delete_allto(){
		$ids=explode(',', $_GET['id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->game_db->update(array('status'=>1), array('id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	/*
	 * 批量上线游戏
	 */
	public function online_pass_all(){
		$ids=explode(',', $_GET['id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->game_db->update(array('status'=>4), array('id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	/*
	 * 批量删除游戏
	 */
	public function online_error(){
		$ids=explode(',', $_GET['id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->game_db->delete(array('id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	/*
	 * 游戏申请审核
	 */
	public function verify_pass(){
		$id = $_REQUEST['id'];
		$this->game_db->update(array('status'=>2), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/*
	*批量审核游戏
	*/
	public function verify_pass_all(){
		$ids=explode(',', $_GET['id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->game_db->update(array('status'=>2), array('id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	
	/*
	*审核列表
	*/
	public function verify(){
		$type_name_list = $this->db->select('pid = 10', 'cat_id, cat_name', '', 'cat_id ASC');
		
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND title LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "game WHERE status =2 ".$title_filt;
		//查询处理 start 
		$type = '请选择';
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$status = trim($_GET['status']);
			if(!empty($type) && $type != '请选择')
			{
				$sql .= " AND channel = '".$type."'";
			}
			if(!empty($status) && $type != '全部')
			{
				$sql .= " AND status = '".$status."'";
			}
		}
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->game_db->mynum($sql);
		$totalpage	= $this->game_db->mytotalpage($sql, $perpage);
		$data 		= $this->game_db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->game_db->pages;
		include $this->admin_tpl('games_verify_list');		
	}
	
}