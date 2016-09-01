<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;
class config extends admin {
	public $siteid,$categorys;
	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		$this->siteid = $this->get_siteid();
		$this->categorys = getcache('category_content_'.$this->siteid,'commons');
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	public function init() {
		
	}
	
	/**
	 * 终端类型
	 *
	 */
	public function term() {
		$this->db = pc_base::load_model('cms_term_type_model');
		$where = "";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` ASC', $page, $pagesize = 15);
		$pages = $this->db->pages;
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=content&c=content&a=add&catid=1\', title:\''.L('添加').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('添加'));
		include $this->admin_tpl('config_term_list');
	}
	
	/**
	 * 一级栏目
	 *
	 */
	public function column() {
		$this->db = pc_base::load_model('cms_column_model');
		$where = "";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` ASC', $page, $pagesize = 15);
		$pages = $this->db->pages;
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=content&c=content&a=add&catid=63\', title:\''.L('添加').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('添加'));
		include $this->admin_tpl('config_column_list');
	}
	
	/**
	 * 直播频道分类
	 *
	 */
	public function channel_category() {
		$this->db = pc_base::load_model('cms_channel_category_model');
		$where = "";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` ASC', $page, $pagesize = 15);
		$pages = $this->db->pages;
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=content&c=content&a=add&catid=63\', title:\''.L('添加').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('添加'));
		include $this->admin_tpl('config_channel_category_list');
	}
	
	/**
	 * 终端栏目映射
	 *
	 */
	public function column_mapping() {
		$this->db = pc_base::load_model('cms_column_mapping_model');
		$where = "";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` ASC', $page, $pagesize = 15);
		$pages = $this->db->pages;
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=content&c=content&a=add&catid=63\', title:\''.L('添加').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('添加'));
		include $this->admin_tpl('config_column_mapping_list');
	}
/**
	 * 排序
	 */
	public function listorder() {
		$phpcmsdb = yzy_phpcms_db() ;
		if(isset($_GET['dosubmit'])) {
			foreach($_POST['listorders'] as $id => $listorder) {
				$d = array(
					'listorder'  => $listorder
				);
				$phpcmsdb->w('v9_channel_category',$d,array('id'=>$id)) ;
			}
			showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 60);
		} else {
			showmessage('操作失败',$_SERVER['HTTP_REFERER'], $ms = 60);
		}
	}

	/**
	 * 频道分类同步前清空老数据
	 *
	 */
	public function channel_category_pre_sync() {
		$this->db2 = pc_base::load_model('tv_channel_category_model');
		$this->db2->query("truncate table channel_category");
		showmessage('正在清空go3c.channel_category老数据，下面开始同步频道分类到前端','?m=go3c&c=config&a=channel_category_sync',$ms = 500);
	}
	
	/**
	 * 频道分类数据同步
	 *
	 */
	public function channel_category_sync() {

		$this->db  = pc_base::load_model('cms_channel_category_model');
		$this->db2 = pc_base::load_model('tv_channel_category_model');
		
		$infos = $this->db->select('','*','',$order = 'id ASC');
		foreach ($infos as $value) {
			$data_array = array(
				'category_id' => $value['id'], 
				'category_name' => $value['title'], 
				'icon_img_url' => $value['icon_img_url'],
				'seq_number' => $value['listorder']			
			);
			$this->db2->insert($data_array);
		}
		showmessage('同步成功','?m=go3c&c=config&a=channel_category',$ms = 500);
	}	
	
	/**
	 * 获取菜单深度
	 * @param $id
	 * @param $array
	 * @param $i
	 */
	public function get_level($id,$array=array(),$i=0) {
		foreach($array as $n=>$value){
			if($value['id'] == $id)
			{
				if($value['parentid']== '0') return $i;
				$i++;
				return $this->get_level($value['parentid'],$array,$i);
			}
		}
	}
	//添加栏目
	public function addcolumn(){
		$this->cms_area = pc_base::load_model('cms_area_model');
					
		$menu = pc_base::load_sys_class('tree');
		$this->op = pc_base::load_app_class('role_op');
		$menu->icon = array('│ ','├─ ','└─ ');
		$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
		//根据管理员权限筛选区域
		$this->admin_model = pc_base::load_model('admin_model');
		$userid = $_SESSION['userid'];
		$ad_area = $this->admin_model->get_one(array('userid'=>$userid));
		$area_id = $ad_area['area_id']?$ad_area['area_id']:'01';
		$ad_area_le = $this->cms_area->get_one(array('id'=>$area_id)); //查询管理员区域级别(省市县)
		$arkey = " 1 ";
			if($ad_area['roleid']!='1' || $area_id!='01'){  //区域筛选排除超级管理员和全网
				if($ad_area_le['m']=='1' || $ad_area_le['c']=='1'){  	//省级  //市级
					$arkey ="`id` = '$area_id' OR `parentid` LIKE '%$area_id%'";
				}elseif($ad_area_le['a']=='1'){  //区县级
					$arkey ="`id` = '".$area_id."'";
				}
			}
			$result = $this->cms_area->select($arkey);
			foreach ($result as $n=>$t) {
				$result[$n]['cname'] = $t['name'];
				$result[$n]['checked'] = '';
				$result[$n]['level'] = $this->get_level($t['id'],$result);
				$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
			}
			$str  = "<tr id='node-\$id' \$parentid_node>
					<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
				</tr>";
		$menu->init($result);
		$categorys = $menu->get_tree(0, $str);
		include $this->admin_tpl('addcolumn');
	}
	public function addcolumndo(){
		$this->cms_column = pc_base::load_model('cms_column_model');
		$this->tv_column = pc_base::load_model('tv_column_model');
		$menuid=$_POST['menuid'];//一个数组;
		$id=$_POST['id'];
		$name=$_POST['name'];		
		$info = $this->cms_column->get_one(array('title'=>$name));
		if(!empty($info)){
			showmessage('栏目名称重复',base64_decode($_GET['goback']));
		}
		$dacl = array(
				'id'=>$id,
				'catid'=>'2',
				'title'=>$name,
				'status'=>'99',
				'updatetime'=>time(),
				'username'=>'system',
				'active'=>'1'
			);
		$this->cms_column->insert($dacl, true);
		foreach ($menuid as $vc){
				$area_id = $vc;
				if($area_id=='01'){
						$area_id = '0';
				}
				$datacl = array(
					'col_id'=>$id,
					'col_name'=>$name,
					'area_id'=>$area_id
					);
			//循环插入栏目区域记录
			$this->tv_column->insert($datacl, true);
		}
		showmessage('新增成功',base64_decode($_GET['goback']));
	}
	//删除栏目
	public function deleteto(){
		$id = intval($_GET['id']);
		if(empty($id)){
			showmessage('数据异常',base64_decode($_GET['goback']));
		}
		$this->cms_column = pc_base::load_model('cms_column_model');
		$this->tv_column = pc_base::load_model('tv_column_model');
		$this->cms_column->delete(array('id'=>$id));
		$this->tv_column->delete(array('col_id'=>$id));
		showmessage('操作成功!',base64_decode($_GET['goback']));
	}
	//修改栏目
	public function editcolumn(){
		$id = intval($_GET['id']);
		$this->cms_column = pc_base::load_model('cms_column_model');
		$this->cms_area = pc_base::load_model('cms_area_model');
		$this->tv_column = pc_base::load_model('tv_column_model');			
		$menu = pc_base::load_sys_class('tree');
		$this->op = pc_base::load_app_class('role_op');
		$menu->icon = array('│ ','├─ ','└─ ');
		$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
		//根据管理员权限筛选区域
		$this->admin_model = pc_base::load_model('admin_model');
		$userid = $_SESSION['userid'];
		$ad_area = $this->admin_model->get_one(array('userid'=>$userid));
		$area_id = $ad_area['area_id']?$ad_area['area_id']:'01';
		$ad_area_le = $this->cms_area->get_one(array('id'=>$area_id)); //查询管理员区域级别(省市县)
		$arkey = " 1 ";
			if($ad_area['roleid']!='1' || $area_id!='01'){  //区域筛选排除超级管理员和全网
				if($ad_area_le['m']=='1' || $ad_area_le['c']=='1'){  	//省级  //市级
					$arkey ="`id` = '$area_id' OR `parentid` LIKE '%$area_id%'";
				}elseif($ad_area_le['a']=='1'){  //区县级
					$arkey ="`id` = '".$area_id."'";
				}
			}
			$result = $this->cms_area->select($arkey);
			$columnda = $this->tv_column->select(array('col_id'=>$id));
			foreach ($result as $n=>$t) {
				$result[$n]['cname'] = $t['name'];
				$result[$n]['checked'] = '';
				foreach ($columnda as $v){
					if($v['area_id']=='0'){
						$v['area_id'] = '01';
					}
					if ($t['id']==$v['area_id']){
						$result[$n]['checked'] = 'checked';
					}
				}
				$result[$n]['level'] = $this->get_level($t['id'],$result);
				$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
			}
			$str  = "<tr id='node-\$id' \$parentid_node>
					<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
				</tr>";
		$menu->init($result);
		$categorys = $menu->get_tree(0, $str);
		$limitInfo = $this->cms_column->get_one(array('id'=>$id));
		include $this->admin_tpl('columnedit');
	}
	public function editcolumndo(){
		$this->cms_column = pc_base::load_model('cms_column_model');
		$this->tv_column = pc_base::load_model('tv_column_model');
		$menuid=$_POST['menuid'];//一个数组;
		$id=$_POST['id'];
		$name=$_POST['name'];
//		$info = $this->cms_column->get_one(array('title'=>$name));
//		if(!empty($info)){
//			showmessage('栏目名称重复',base64_decode($_GET['goback']));
//		}
		if(empty($menuid)){
			showmessage('栏目区域不能为空',base64_decode($_GET['goback']));
		}
		$dacl = array(
				'catid'=>'2',
				'title'=>$name,
				'status'=>'99',
				'updatetime'=>time(),
				'username'=>'system',
				'active'=>'1'
			);
		$this->cms_column->update($dacl,array('id'=>$id));
		$this->tv_column->delete(array('col_id'=>$id));
		foreach ($menuid as $vc){
				$area_id = $vc;
				if($area_id=='01'){
						$area_id = '0';
				}
				$datacl = array(
					'col_id'=>$id,
					'col_name'=>$name,
					'area_id'=>$area_id
					);
			//循环插入栏目区域记录
			$this->tv_column->insert($datacl, true);
		}
		showmessage('新增成功',base64_decode($_GET['goback']));
	}
}
?>
