<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class infor extends admin {

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
	 * 资讯类型列表
	 *
	 */
	public function type_list() {
		$admin_username = param::get_cookie('admin_username');
		$this->db   = pc_base::load_model('cms_information_type_model');
		$type_name   	= trim($_GET['type_name']);
		$title_filt = $type_name ? "WHERE 1 AND type_name LIKE '%$type_name%'" : '';
		$field    	= '*';
		$sql     	= "v9_information_type ".$title_filt;
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('infor_type_list');			
	}
	/*
	 * 添加资讯类型
	 */
	public function addinfortype(){
		include $this->admin_tpl('infor_type_add');
	}
	/*
	 * 添加资讯类型
	 */
	public function addinfortypedo(){
		$type_name = $_POST['type_name'];
		$listorder = $_POST['listorder'];
		$this->db   = pc_base::load_model('cms_information_type_model');
		if(empty($type_name)){
			showmessage('操作失败,资讯类型不能为空!',HTTP_REFERER);
		}else{
			$data_type = array(
					'type_name' => $type_name,
					'listorder' => $listorder,
					'inputtime' => time()
			);
			$this->db->insert($data_type);
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=infor&a=type_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	/*
	 * 编辑资讯类型
	*/
	public function editinfortype(){
		$id = $_REQUEST['id'];
		$this->db   = pc_base::load_model('cms_information_type_model');
		$aKey = "id = '".$id."'";
		$limitInfo = $this->db->get_one($aKey);
		include $this->admin_tpl('infor_type_edit');
	}
	/*
	 * 编辑资讯类型
	*/
	public function editinfortypedo(){
		$id = $_REQUEST['id'];
		$type_name = $_POST['type_name'];
		$listorder = $_POST['listorder'];
		$this->db   = pc_base::load_model('cms_information_type_model');
		if(empty($id)||empty($type_name)){
			showmessage('操作失败!',HTTP_REFERER);
		}else{
			$data_type = array(
					'type_name' => $type_name,
					'listorder' => $listorder,
					'updatetime' => time()
			);
			$this->db->update($data_type,array('id'=>$id));
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=infor&a=type_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	/*
	 * 删除资讯类型
	 */
	public function delete_type(){
		$id = $_REQUEST['id'];
		$this->db   = pc_base::load_model('cms_information_type_model');
		$this->db->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	/**
	 * 资讯列表
	 *
	 */
	public function infor_list() {
		$admin_username = param::get_cookie('admin_username');
		$this->information_type = pc_base::load_model('cms_information_type_model');
		$type_name_list = $this->information_type->select('', 'id, type_name', '', 'id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['id']]=$gvalue['type_name'];
		}
		
		$this->information = pc_base::load_model('cms_information_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND title LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "v9_information WHERE 1 ".$title_filt;
		//查询处理 start 
		$type = 0;
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$online_status = trim($_GET['online_status']);
			if(!empty($type) && $type)
			{
				$sql .= " AND type = '".$type."'";
			}
			if(!empty($online_status) && $online_status!='全部')
			{
				$sql .= " AND online_status = '".$online_status."'";
			}
		}
		$order  	= 'ORDER BY updatetime DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->information->mynum($sql);
		$totalpage	= $this->information->mytotalpage($sql, $perpage);
		$data 		= $this->information->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->information->pages;
		include $this->admin_tpl('infor_list');		
	}
	/*
	 * 删除资讯
	 */
	public function deleteinfor(){
		$id = $_REQUEST['id'];
		$this->information = pc_base::load_model('cms_information_model');
		$this->information->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	/*
	 * 资讯申请审核
	 */
	public function infor_pass(){
		$id = $_REQUEST['id'];
		$this->information = pc_base::load_model('cms_information_model');
		$this->information->update(array('online_status'=>4), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/*
	 * 资讯通过审核
	 */
	public function infor_passdo(){
		$this->information = pc_base::load_model('cms_information_model');
		$id = intval($_GET['id']);
		$this->information->update(array('online_status'=>11), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/*
	 * 资讯拒接通过审核
	*/
	public function infor_passdo_refuse(){
		$this->information = pc_base::load_model('cms_information_model');
		$id = intval($_GET['id']);
		$this->information->update(array('online_status'=>1), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/*
	 * 资讯申请下线
	 */
	public function infor_off(){
		$this->information = pc_base::load_model('cms_information_model');
		$id = intval($_GET['id']);
		$this->information->update(array('online_status'=>5), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/*
	 * 资讯通过申请下线
	 */
	public function infor_offdo(){
		$this->information = pc_base::load_model('cms_information_model');
		$id = intval($_GET['id']);
		$this->information->update(array('online_status'=>6), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/*
	 * 资讯拒接通过下线审核
	 */
	public function infor_off_refuse(){
		$this->information = pc_base::load_model('cms_information_model');
		$id = intval($_GET['id']);
		$this->information->update(array('online_status'=>14), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	/*
	 * 资讯推荐
	 */
	public function infor_pad_list(){
		$this->cms_position = pc_base::load_model('cms_position_model');
		$where = " type_id in (901,902)";
		$posid_list = $this->cms_position->select($where);    //获取资讯推荐位的列表
		foreach ($posid_list as $k=>$vp){
			$type[$k] = $vp['posid'];
		}
		$comma_separated = implode(",", $type);
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$where1 = " posid in ($comma_separated)";
		$task_list = $this->task_db->select($where1);
		include $this->admin_tpl('infor_pad_list');
	}
	/*
	 * 添加推荐任务
	 */
	public function addTask(){
		$this->cms_position = pc_base::load_model('cms_position_model');
		$this->cms_information_type= pc_base::load_model('cms_information_type_model');
		$where = " type_id in (901,902)";
		$posid_list = $this->cms_position->select($where);    //获取游戏推荐位的列表
		$taskType = 'addTask';
		$game_type_list = $this->cms_information_type->select();
		include $this->admin_tpl('infor_task_info');
	}
	/*
	 * 添加资讯推荐任务
	 */
	public function addinforTask(){
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$posid = trim($_POST['task_posid']);
		$task_imgType_live = trim($_POST['task_imgType_live']);
		$taskDate = trim($_POST['task_taskDate']);
		$spid = $this->current_spid['spid'];
		$posidInfo = trim($_POST['task_posidInfo']);
		//审核审核状态、等待上线 在线状态 这三种状态同一日期必须只能有一个存在
		$aKey = " taskStatus > '0' AND posid = '".$posid."' AND taskDate = '".strtotime($taskDate)."'";
		$limitInfo = $this->task_db->get_one($aKey);
		$this->cms_position = pc_base::load_model('cms_position_model');
		//获取推荐位基本信息
		$pkey="posid ='".$posid."'";
		$gtaskInfo = $this->cms_position->get_one($pkey);
		if (empty($limitInfo)){
			$insert_data = array(
					'term_id' => $gtaskInfo['term_id'],
					'posid' => $posid,
					'spid' => $spid,
					'posidInfo' => $gtaskInfo['name'],
					'start_end_nums' => $gtaskInfo['minnum'].'-'.$gtaskInfo['maxnum'],
					'videoSource' => '4',
					'imgType' => $task_imgType_live,	//默认图片类型
					'taskDate' => strtotime($taskDate),
					'taskStatus' => '1',	//默认编辑状态
					'posttime' => time()
			);
			var_dump($insert_data);
			$this->task_db->insert($insert_data);
			showmessage('提交成功',HTTP_REFERER);
		}else{
			showmessage('该条件下有一条任务己经存在,请不要重复添加！',HTTP_REFERER);
		}
	}
	/*
	 * 修改推荐资讯任务
	 */
	public function editTask(){
		$taskId = $_GET['taskId'];	//任务ID
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$pkey="taskId ='".$taskId."'";
		$gtaskInfo = $this->task_db->get_one($pkey);
		include $this->admin_tpl('infor_edit_info');
	}
	/*
	 * 修改推荐资讯任务
	 */
	public function editTaskdo(){
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$task_taskDate = trim($_POST['task_taskDate']);
		$taskId = trim($_POST['taskId']);
		$insert_data = array(
				'taskDate' => strtotime($task_taskDate),
				'taskStatus' => '1',	//默认编辑状态
				'posttime' => time()
		);
		$update_where = array('taskId' => $taskId);
		$this->task_db->update($insert_data,$update_where);
		showmessage('修改成功',HTTP_REFERER);
	}
	/*
	 * 列出某推荐的资讯
	 */
	public function viewinfor(){
		$taskId   	= trim($_GET['taskId']);
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');
		$field    = isset($_GET['field']) ? $_GET['field'] : 'posttime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		
		$where = " taskId =$taskId";
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$infor_list = $this->task_video_db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->task_video_db->pages;
		include $this->admin_tpl('infor_view_list');
	}
	/*
	 * 显示所有可以推荐的资讯
	 */
	public function showinfor(){
		$this->information_type = pc_base::load_model('cms_information_type_model');
		$taskId   	= trim($_GET['taskId']);
		$type_name_list = $this->information_type->select('', 'id, type_name', '', 'id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['id']]=$gvalue['type_name'];
		}
		$this->db   = pc_base::load_model('cms_information_model');
		$title   	= trim($_GET['title']);
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$type_name  = intval($_GET['type']);
				
		$where = "online_status='4'";
		$title  != '' ? $where.= " AND `title` LIKE '%$title%'" : '';
		$type_name  != '' ? $where.=" AND type = '$type_name'" : '';
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('infor_task_list');	
	}
	/*
	 * 	//删除该推荐位的此资讯
	 */
	public function deleteTaskinfor(){
		$preId   	= trim($_GET['preId']);
		$taskId   	= trim($_GET['taskId']);
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');
		if(empty($preId)||empty($taskId)){
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$where_data = array(
					'taskId'=> $taskId,
					'preId' => $preId
			);
			$this->task_video_db->delete($where_data);
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
	 * 申请审核该推荐位
	 */
	public function infor_task(){
		$taskId = trim($_GET['taskId']);
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');
		$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$update_where = array(
					'spid' => $this->current_spid['spid'],
					'taskId' => $taskInfo['taskId']
			);
			$update_data = array(
					'taskStatus' => '4',	//审核状态
					'posttime' => time()
			);
			$this->task_db->update($update_data,$update_where);
			showmessage('提交成功!',HTTP_REFERER);
		}
	}
	
	/*
	 * 下线资讯推荐位
	 */
	public function infor_task_off(){
		$taskId = trim($_GET['taskId']);
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');
		$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$update_where = array(
					'spid' => $this->current_spid['spid'],
					'taskId' => $taskInfo['taskId']
			);
			$update_data = array(
					'taskStatus' => '1',	//审核状态
					'posttime' => time()
			);
			$this->task_db->update($update_data,$update_where);
			showmessage('提交成功!',HTTP_REFERER);
		}
	}
	/*
	 * 批量下线应用
	*/
	public function delete_allto(){
		$this->information = pc_base::load_model('cms_information_model');
		$ids=explode(',', $_GET['id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->information->update(array('online_status'=>1), array('id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	/*
	 * 批量上线应用
	*/
	public function online_pass_all(){
		$this->information = pc_base::load_model('cms_information_model');
		$ids=explode(',', $_GET['id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->information->update(array('online_status'=>4), array('id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	/*
	 * 批量删除应用
	*/
	public function online_error(){
		$this->information = pc_base::load_model('cms_information_model');
		$ids=explode(',', $_GET['id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$info = $this->information->get_one(array('id'=>$v));
				if($info['online_status']==4){
					showmessage('id为'.$v.'的资讯已经发布上线,请先下线在删除！',base64_decode($_GET['goback']),$ms = 800);
				}
				$this->information->delete(array('id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']),$ms = 800);
		}
	}
}
?>
