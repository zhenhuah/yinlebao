<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
class position_app extends admin {
	private $db, $db_data, $db_content;
	function __construct() {
		parent::__construct();
		
		$this->sites = pc_base::load_app_class('sites');
		$this->db = pc_base::load_model('position_app_model');
		$this->db_data = pc_base::load_model('position_data_model');
		$this->db_content = pc_base::load_model('content_model');	
		
		//NJPHP 加载新增的终端类型表
		$this->term_type = pc_base::load_model('term_type_model');	
		//NJPHP 加载新增的推荐位类型表
		$this->postion_type = pc_base::load_model('shop_type_model');
		//NJPHP 加载新增的SPID类型表
		$this->spidsp_db = pc_base::load_model('cms_spid_model');
		
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}
	
	public function init() {
			$admin_username = param::get_cookie('admin_username');
			//NJPHP 获取终端类型和推荐位类型
			$term_type_list = array();
			$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
			//获取项目代号
			if($_SESSION['roleid']=='1'){
				$spid_list = $this->spidsp_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
				$board_list = $this->spidsp_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' board_type', $key='');
			}else{
				$spid_list = $this->spidsp_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
				$board_list = $this->spidsp_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' board_type', $key='');
			}
			$infos = array();
			$where = ' 1 ';
			$current_siteid = self::get_siteid();
			$spid = $_GET['spid'];
			$board_type = $_GET['board_type'];
			if($_SESSION['roleid']=='1'){	//超级管理员
				if($spid){
					$where.= " AND spid = '".$spid."'";
				}else{
					$where.= "";
				}
				if($board_type){
					$where.= " AND board_type = '".$board_type."'";
				}
			}else{
				if($spid){
					$where.= " AND spid = '".$spid."'";
				}else{
					$where.= " AND spid in ('".$_SESSION['spid']."') ";
				}
				if($board_type){
					$where.= " AND board_type = '".$board_type."'";
				}
			}
			//构造SQL
			$name = strip_tags(trim($_GET['name']));
			$name ? $where.= " AND `name` LIKE '%$name%'" : '';
			//查询处理 start 
			$term_id = 0;
			if(!empty($_GET['search']))
			{
				//任务(推荐位)			
				$term_id = trim($_GET['term']);
				if(!empty($term_id))
				{
					$where .= " AND term_id = '".$term_id."'";
				}
			}
			$page = $_GET['page'] ? $_GET['page'] : '1';
			
			//设置每页显示数量，如果不设置默认每页10个
			$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';
			
			$infos = $this->db->listinfo($where, $order = 'posid DESC', $page, $perpage);
			$pages = $this->db->pages;
			$show_dialog = true;
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=go3c&c=position_app&a=add\', title:\'添加推荐位\', width:\'500\', height:\'360\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加推荐位');
			include $this->admin_tpl('position_list_app');
	}
	
	/**
	 * 推荐位添加
	 */
	public function add() {
		
		//NJPHP 获取终端类型和推荐位类型
		$term_type_list = $postion_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$postion_type_list = $this->postion_type->select($where = 'level=2 and ctype!="edu"', $data = 'cat_id,cat_name');
		foreach($term_type_list as $tvalue){
			$term_type_array[$tvalue['id']]=$tvalue['title'];
		}
		foreach($postion_type_list as $pvalue){
			$postion_type_array[$pvalue['cat_id']]=$pvalue['cat_name'];
		}
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->spidsp_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
			$board_list = $this->spidsp_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' board_type', $key='');
		}else{
			$spid_list = $this->spidsp_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
			$board_list = $this->spidsp_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' board_type', $key='');
		}
		
		if(isset($_POST['dosubmit'])) {
			if(!is_array($_POST['info'])){
				showmessage(L('operation_failure'));
			}
			$_POST['info']['siteid'] = intval($_POST['info']['modelid']) ? get_siteid() : 0;
			$_POST['info']['listorder'] = intval($_POST['info']['listorder']);
			$_POST['info']['minnum'] = intval($_POST['info']['minnum']);
			$_POST['info']['maxnum'] = intval($_POST['info']['maxnum']);
			$_POST['info']['type_id'] = intval($_POST['info']['type_id']);
			$_POST['info']['updatetime'] = time();
			$_POST['info']['spid'] = $_POST['info']['spid'];
			$_POST['info']['board_type'] = $_POST['info']['board_type'];
			
			//NJPHP获取设备类型和栏目类型
			$term_name = $term_type_array[$_POST['info']['term_id']];
			$type_name = $postion_type_array[$_POST['info']['type_id']];
			//自动组合出推荐位名称
			$_POST['info']['name'] = $term_name.'-'.$_POST['info']['name'].'-'.$_POST['info']['spid'];
			//echo '<pre>';print_r($_POST['info']);exit;
			var_dump($_POST['info']);
			$insert_id = $this->db->insert($_POST['info'],true);
			$this->_set_cache();
			if($insert_id){
				showmessage(L('operation_success'), '', '', 'add');
			}
		} else {
			pc_base::load_sys_class('form');
			$this->sitemodel_db = pc_base::load_model('sitemodel_model');
			$sitemodel = $sitemodel = array();
			$sitemodel = getcache('model','commons');
			foreach($sitemodel as $value){
				if($value['siteid'] == get_siteid()){
					$modelinfo[$value['modelid']]=$value['name'];
				}
			}			
			$show_header = $show_validator = true;
			include $this->admin_tpl('position_add_app');
		}	
	}
	
	/**
	 * 推荐位编辑
	 */
	public function edit() {
		
		//NJPHP 获取终端类型和推荐位类型
		$term_type_list = $postion_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$postion_type_list = $this->postion_type->select($where = 'level=2 and ctype!="edu"', $data = 'cat_id,cat_name');
		$spid_array = $this->spid_db->select();
		foreach($term_type_list as $tvalue){
			$term_type_array[$tvalue['id']]=$tvalue['title'];
		}
		foreach($postion_type_list as $pvalue){
			$postion_type_array[$pvalue['cat_id']]=$pvalue['cat_name'];
		}
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->spidsp_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
			$board_list = $this->spidsp_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' board_type', $key='');
		}else{
			$spid_list = $this->spidsp_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
			$board_list = $this->spidsp_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'id ASC', $group = ' board_type', $key='');
		}
		if(isset($_POST['dosubmit'])) {
			$_POST['posid'] = intval($_POST['posid']);
			if(!is_array($_POST['info'])){
				showmessage(L('operation_failure'));
			}
			$_POST['info']['siteid'] = intval($_POST['info']['modelid']) ? get_siteid() : 0;
			$_POST['info']['listorder'] = intval($_POST['info']['listorder']);
			$_POST['info']['minnum'] = intval($_POST['info']['minnum']);			
			$_POST['info']['maxnum'] = intval($_POST['info']['maxnum']);	
			$_POST['info']['type_id'] = intval($_POST['info']['type_id']);	
			$_POST['info']['updatetime'] = time();
			$_POST['info']['spid'] = $_POST['info']['spid'];
			$_POST['info']['board_type'] = $_POST['info']['board_type'];
			
			//NJPHP获取设备类型和栏目类型
			$term_name = $term_type_array[$_POST['info']['term_id']];
			$type_name = $postion_type_array[$_POST['info']['type_id']];
			//自动组合出推荐位名称
			//$_POST['info']['name'] = $term_name.'-'.$type_name.'-'.$_POST['info']['spid'];
			var_dump($_POST['info']);
			$this->db->update($_POST['info'],array('posid'=>$_POST['posid']));
			$this->_set_cache();
			showmessage(L('operation_success'), '', '', 'edit');
		} else {
			$info = $this->db->get_one(array('posid'=>intval($_GET['posid'])));
			extract($info);
			pc_base::load_sys_class('form');
			$this->sitemodel_db = pc_base::load_model('sitemodel_model');
			$sitemodel = $sitemodel = array();
			$sitemodel = getcache('model','commons');
			foreach($sitemodel as $value){
				if($value['siteid'] == get_siteid())$modelinfo[$value['modelid']]=$value['name'];
			}
			$show_validator = $show_header = $show_scroll = true;
			include $this->admin_tpl('position_edit_app');
		}

	}
	
	/**
	 * 推荐位删除
	 */
	public function delete() {
		$posid = intval($_GET['posid']);
		$this->db->delete(array('posid'=>$posid));
		$this->_set_cache();
		showmessage('删除成功','?m=go3c&c=position_app');
	}
	
	/**
	 * 推荐位排序
	 */
	public function listorder() {
		if(isset($_POST['dosubmit'])) {
			foreach($_POST['listorders'] as $posid => $listorder) {
				$this->db->update(array('listorder'=>$listorder),array('posid'=>$posid));
			}
			$this->_set_cache();
			showmessage(L('operation_success'),'?m=admin&c=position');
		} else {
			showmessage(L('operation_failure'),'?m=admin&c=position');
		}
	}
	
	/**
	 * 推荐位文章统计
	 * @param $posid 推荐位ID
	 */
	public function content_count($posid) {
		$posid = intval($posid);
		$where = array('posid'=>$posid);
		$infos = $this->db_data->get_one($where, $data = 'count(*) as count');
		return $infos['count'];
	}
	
	/**
	 * 推荐位文章列表
	 */
	public function public_item() {	
		
		//NJPHP 获取终端类型和推荐位类型
		$term_type_list = $postion_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$postion_type_list = $this->postion_type->select($where = '', $data = 'cat_id,cat_name');
		foreach($term_type_list as $tvalue){
			$term_type_array[$tvalue['id']]=$tvalue['title'];
		}
		foreach($postion_type_list as $pvalue){
			$postion_type_array[$pvalue['cat_id']]=$pvalue['cat_name'];
		}
		//print_r($postion_type_array);
		
		if(isset($_POST['dosubmit'])) {
			$items = count($_POST['items']) > 0  ? $_POST['items'] : showmessage(L('posid_select_to_remove'),HTTP_REFERER);
			if(is_array($items)) {
				$sql = array();
				foreach ($items as $item) {
					$_v = explode('-', $item);
					$sql['id'] = $_v[0];
					$sql['modelid']= $_v[1];
					$sql['posid'] = intval($_POST['posid']);
					$this->db_data->delete($sql);
					$this->content_pos($sql['id'],$sql['modelid']);		
				}
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		} else {
			$posid = intval($_GET['posid']);
			$MODEL = getcache('model','commons');
			$siteid = $this->get_siteid();
			$CATEGORY = getcache('category_content_'.$siteid,'commons');
			$page = $_GET['page'] ? $_GET['page'] : '1';
			$pos_arr = $this->db_data->listinfo(array('posid'=>$posid,'siteid'=>$siteid),'listorder DESC', $page, $pagesize = 20);
			$pages = $this->db_data->pages;
			$infos = array();
			foreach ($pos_arr as $_k => $_v) {
				$r = string2array($_v['data']);
				$r['catname'] = $CATEGORY[$_v['catid']]['catname'];
				$r['modelid'] = $_v['modelid'];
				$r['posid'] = $_v['posid'];
				$r['id'] = $_v['id'];
				$r['listorder'] = $_v['listorder'];
				$r['catid'] = $_v['catid'];
				//NJPHP
				$go3c_term_postion  = $this->db->get_one(array('posid'=>$posid));
				$go3c_term_type     = $this->term_type->get_one(array('id'=>$go3c_term_postion['term_id']));
				$go3c_position_type = $this->postion_type->get_one(array('id'=>$go3c_term_postion['type_id']));
				//NJPHP
				$r['url'] = go($_v['catid'], $_v['id']);
				$key = $r['modelid'].'-'.$r['id'];
				$infos[$key] = $r;
				
			}
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=admin&c=position&a=add\', title:\''.L('posid_add').'\', width:\'500\', height:\'300\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('posid_add'));			
			include $this->admin_tpl('position_items');			
		}
	}
	/**
	 * 推荐位文章管理
	 */
	public function public_item_manage() {
		
		//NJPHP 获取终端类型和推荐位类型
		$term_type_list = $postion_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$postion_type_list = $this->postion_type->select($where = '', $data = 'cat_id,cat_name');
		foreach($term_type_list as $tvalue){
			$term_type_array[$tvalue['id']]=$tvalue['title'];
		}
		foreach($postion_type_list as $pvalue){
			$postion_type_array[$pvalue['cat_id']]=$pvalue['cat_name'];
		}
		//print_r($postion_type_array);
		
		if(isset($_POST['dosubmit'])) {
			$posid = intval($_POST['posid']);
			$modelid = intval($_POST['modelid']);	
			$id= intval($_POST['id']);
			$pos_arr = $this->db_data->get_one(array('id'=>$id,'posid'=>$posid,'modelid'=>$modelid));
			$array = string2array($pos_arr['data']);
			$array['inputtime'] = strtotime($_POST['info']['inputtime']);
			$array['title'] = trim($_POST['info']['title']);
			$array['thumb'] = trim($_POST['info']['thumb']);
			$array['description'] = trim($_POST['info']['description']);
			$thumb = $_POST['info']['thumb'] ? 1 : 0;
			$array = array('data'=>array2string($array),'synedit'=>intval($_POST['synedit']),'thumb'=>$thumb);
			$this->db_data->update($array,array('id'=>$id,'posid'=>$posid,'modelid'=>$modelid));
			showmessage(L('operation_success'),'','','edit');
		} else {
			$posid = intval($_GET['posid']);
			
			//NJPHP
			$go3c_term_postion  = $this->db->get_one(array('posid'=>$posid));
			$go3c_term_type     = $this->term_type->get_one(array('id'=>$go3c_term_postion['term_id']));
			$go3c_position_type = $this->postion_type->get_one(array('id'=>$go3c_term_postion['type_id']));
			//NJPHP
			
			$modelid = intval($_GET['modelid']);	
			$id = intval($_GET['id']);		
			if($posid == 0 || $modelid == 0) showmessage(L('linkage_parameter_error'), HTTP_REFERER);
			$pos_arr = $this->db_data->get_one(array('id'=>$id,'posid'=>$posid,'modelid'=>$modelid));
			extract(string2array($pos_arr['data']));
			$synedit = $pos_arr['synedit'];
			$show_validator = true;
			$show_header = true;		
			include $this->admin_tpl('position_item_manage');			
		}
	
	}
	/**
	 * 推荐位文章排序
	 */
	public function public_item_listorder() {
		if(isset($_POST['posid'])) {
			foreach($_POST['listorders'] as $_k => $listorder) {
				$pos = array();
				$pos = explode('-', $_k);
				$this->db_data->update(array('listorder'=>$listorder),array('id'=>$pos[1],'catid'=>$pos[0],'posid'=>$_POST['posid']));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
			
		} else {
			showmessage(L('operation_failure'),HTTP_REFERER);
		}
	}
	/**
	 * 推荐位添加栏目加载
	 */
	public function public_category_load() {
		$modelid = intval($_GET['modelid']);
		pc_base::load_sys_class('form');
		$category = form::select_category('','','name="info[catid]"',L('please_select_parent_category'),$modelid);
		echo $category;
	}
	
	private function _set_cache() {
		$infos = $this->db->select('','*',1000,'listorder DESC');
		$positions = array();
		foreach ($infos as $info){
			$positions[$info['posid']] = $info;
		}
		setcache('position', $positions,'commons');
		return $infos;
	}
	
	private function content_pos($id,$modelid) {
		$id = intval($id);
		$modelid = intval($modelid);
		$MODEL = getcache('model','commons');
		$this->db_content->table_name = $this->db_content->db_tablepre.$MODEL[$modelid]['tablename'];		
		$posids = $this->db_data->get_one(array('id'=>$id,'modelid'=>$modelid)) ? 1 : 0;
		return $this->db_content->update(array('posids'=>$posids),array('id'=>$id));
	}	
}
?>