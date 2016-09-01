<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
class position_game extends admin {
	private $db, $db_data, $db_content;
	function __construct() {
		parent::__construct();
		
		$this->sites = pc_base::load_app_class('sites');
		$this->db = pc_base::load_model('position_game_model');
		$this->db_data = pc_base::load_model('position_data_model');
		$this->db_content = pc_base::load_model('content_model');	
		
		//NJPHP 加载新增的终端类型表
		$this->term_type = pc_base::load_model('term_type_model');	
		//NJPHP 加载新增的推荐位类型表
		$this->postion_type = pc_base::load_model('shop_type_model');
		//NJPHP 加载新增的SPID类型表
		$this->spid_db = pc_base::load_model('cms_spid_model');
		
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
			$infos = array();
			$where = '';
			$current_siteid = self::get_siteid();
			$where = "1 AND spid = '".$this->current_spid[spid]."'";
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
			$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=go3c&c=position_game&a=add\', title:\'添加推荐位\', width:\'500\', height:\'360\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加推荐位');
			include $this->admin_tpl('position_list_game');
	}
	//添加推荐位
	public function add(){
		//NJPHP 获取终端类型和推荐位类型
		$term_type_list = $postion_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$postion_type_list = $this->postion_type->select($where = 'pid=10', $data = 'cat_id,cat_name');
		$spid_array = $this->spid_db->select();
		foreach($term_type_list as $tvalue){
			$term_type_array[$tvalue['id']]=$tvalue['title'];
		}
		foreach($postion_type_list as $pvalue){
			$postion_type_array[$pvalue['cat_id']]=$pvalue['cat_name'];
		}
		//print_r($postion_type_array);
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
				
			//NJPHP获取设备类型和栏目类型
			$term_name = $term_type_array[$_POST['info']['term_id']];
			$type_name = $postion_type_array[$_POST['info']['type_id']];
			//自动组合出推荐位名称
			$_POST['info']['name'] = $term_name.'-'.$_POST['info']['name'].'-'.$_POST['info']['spid'];
			//echo '<pre>';print_r($_POST['info']);exit;
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
			include $this->admin_tpl('position_add_game');
		}
	}	
	/**
	 * 推荐位编辑
	 */
	public function edit() {
	
		//NJPHP 获取终端类型和推荐位类型
		$term_type_list = $postion_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$postion_type_list = $this->postion_type->select($where = '', $data = 'cat_id,cat_name');
		$spid_array = $this->spid_db->select();
		foreach($term_type_list as $tvalue){
			$term_type_array[$tvalue['id']]=$tvalue['title'];
		}
		foreach($postion_type_list as $pvalue){
			$postion_type_array[$pvalue['cat_id']]=$pvalue['cat_name'];
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
				
			//NJPHP获取设备类型和栏目类型
			$term_name = $term_type_array[$_POST['info']['term_id']];
			$type_name = $postion_type_array[$_POST['info']['type_id']];
			//自动组合出推荐位名称
			//$_POST['info']['name'] = $term_name.'-'.$type_name.'-'.$_POST['info']['spid'];
				
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
			include $this->admin_tpl('position_edit_game');
		}
	
	}
	/**
	 * 推荐位删除
	 */
	public function delete() {
		$posid = intval($_GET['posid']);
		$this->db->delete(array('posid'=>$posid));
		$this->_set_cache();
		showmessage('删除成功','?m=go3c&c=position_game');
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