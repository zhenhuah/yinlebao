<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
define('TASK_IMG_PATH','http://www.go3c.tv:8060/images/go3ccms/');
class area extends admin {
	function __construct() {
		parent::__construct();
		$this->op = pc_base::load_app_class('role_op');
	}
	/**
	 * 区域管理列表树形结构
	 */
	public function index(){
		//$this->cms_area = pc_base::load_model('cms_area_model');
		$this->cms_area = pc_base::load_model('go3capi_area_model');
		$menu = pc_base::load_sys_class('tree');
		$menu->icon = array('│ ','├─ ','└─ ');
		$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
		$result = $this->cms_area->select();
		foreach ($result as $n=>$t) {
				$result[$n]['cname'] = $t['name'];
				$result[$n]['level'] = $this->op->get_level($t['id'],$result);
				$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
			}
			$str  = "<tr id='node-\$id' \$parentid_node>
							<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
						</tr>";
		$menu->init($result);
		$categorys = $menu->get_tree(0, $str);
		$show_header = true;
		$show_scroll = true;
		$name = $_GET['name'];
		$fname = $_GET['fname'];
		$field    = isset($_GET['field']) ? $_GET['field'] : 'id';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'ASC';
		$where = " 1 ";
		$name != '' ? $where.= " AND `name` LIKE '%$name%'" : '';
		$fname != '' ? $where.= " AND `fname` LIKE '%$fname%'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';
		$data  = $this->cms_area->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->cms_area->pages;
		include $this->admin_tpl('area_tree');
	}
	/**
	 * 添加区域
	 */
	public function addarea(){
		include $this->admin_tpl('area_add');
	}
	public function addareado(){
		$id = trim($_POST['id']);
		$name = trim($_POST['name']);
		$mf = trim($_POST['mf']);
		$fname = trim($_POST['fname']);
		//$this->cms_area = pc_base::load_model('cms_area_model');
		$this->cms_area = pc_base::load_model('go3capi_area_model');
		$iKey = "id = '$id'";
		$Info = $this->cms_area->get_one($iKey);
		if(!empty($Info)){
			showmessage('操作失败,区域id重复错误','index.php?m=admin&c=area&a=index');
		}
		if($mf!='m'){
			$aKey = "name LIKE '%".$fname."%'";
			$limitInfo = $this->cms_area->get_one($aKey);
			if (strpos($id, $limitInfo['id'])=== false){
				showmessage('操作失败,区域id错误,没有包含父级区域','index.php?m=admin&c=area&a=index');
			}
			if(!empty($limitInfo)){
				if($mf=='c'){   //市级
					$data = array(
						'id' => $id,
						'parentid' => $limitInfo['id'],
						'c' => '1',
						'name' => $name,
						'fname' => $fname
					);
					$gda = array(
						'area_id' => $id,
						'pid' => $limitInfo['id'],
						'name' => $name,
						'fname' => $fname,
						'sort' => '0'
					);
				}else{   //县区级
					$data = array(
						'id' => $id,
						'parentid' => $limitInfo['id'],
						'a' => '1',
						'name' => $name,
						'fname' => $fname
					);
					$gda = array(
						'area_id' => $id,
						'pid' => $limitInfo['id'],
						'name' => $name,
						'fname' => $fname,
						'sort' => '0'
					);
				}
			}else{
				showmessage('操作失败,上级区域错误','index.php?m=admin&c=area&a=index');
			}
		}else{   //省级
			$parentid = 'null';
			$m = '1';
			$data = array(
				'id' => $id,
				'parentid' => '01',
				'm' => '1',
				'name' => $name,
				'fname' => '全网'
			);
			$gda = array(
				'area_id' => $id,
				'pid' => '01',
				'name' => $name,
				'fname' => $fname,
				'sort' => '0'
			);
		}
		$this->cms_area->insert($data);
		showmessage('操作成功','index.php?m=admin&c=area&a=index');
	}
	/**
	 * 修改区域
	 */
	public function editarea(){
		$id = $_GET['id'];
		//$this->cms_area = pc_base::load_model('cms_area_model');
		$this->cms_area = pc_base::load_model('go3capi_area_model');
		$aKey = "id = '".$id."'";
		$limitInfo = $this->cms_area->get_one($aKey);
		if($limitInfo['m']=='1'){
			$where = " m=1";
		}elseif($limitInfo['c']=='1'){
			$where = " m=1";
		}else{
			$where = " c=1";
		}
		$Info = $this->cms_area->select($where);
		include $this->admin_tpl('area_edit');
	}
	public function editareado(){
		$id = trim($_POST['id']);
		$name = trim($_POST['name']);
		$mf = trim($_POST['mf']);
		$fname = trim($_POST['fname']);
		//$this->cms_area = pc_base::load_model('cms_area_model');
		$this->cms_area = pc_base::load_model('go3capi_area_model');
		if($mf!='m'){
			$aKey = "name LIKE '%".$fname."%'";
			$limitInfo = $this->cms_area->get_one($aKey);
			if(!empty($limitInfo)){
				if($mf=='c'){   //市级
					$data = array(
						'id' => $id,
						'parentid' => $limitInfo['id'],
						'c' => '1',
						'name' => $name,
						'fname' => $fname
					);
					$gda = array(
						'area_id' => $id,
						'pid' => $limitInfo['id'],
						'name' => $name,
						'fname' => $fname,
						'sort' => '0'
					);
				}else{   //县区级
					$data = array(
						'id' => $id,
						'parentid' => $limitInfo['id'],
						'a' => '1',
						'name' => $name,
						'fname' => $fname
					);
					$gda = array(
						'area_id' => $id,
						'pid' => $limitInfo['id'],
						'name' => $name,
						'fname' => $fname,
						'sort' => '0'
					);
				}
			}else{
				showmessage('操作失败,上级区域错误','index.php?m=admin&c=area&a=index');
			}
		}else{
			$parentid = 'null';
			$m = '1';
			$data = array(
				'id' => $id,
				'parentid' => 'null',
				'm' => '1',
				'name' => $name,
				'fname' => 'null'
			);
			$gda = array(
				'area_id' => $id,
				'pid' => 'null',
				'name' => $name,
				'fname' => $fname,
				'sort' => '0'
			);
		}
		$this->cms_area->update($data,array('id'=>$id));
		showmessage('操作成功','index.php?m=admin&c=area&a=index');	
	}
	/**
	 * 删除区域
	 */
	public function delete_area(){
		$id = trim($_GET['id']);
		//$this->cms_area = pc_base::load_model('cms_area_model');
		$this->cms_area = pc_base::load_model('go3capi_area_model');
		$this->cms_area->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	/**
	 * 区域频道视频用户统计
	 */
	public function areacount(){
		$name  = $_GET['name'];
		$mf  = $_GET['mf'];
		$field    = isset($_GET['field']) ? $_GET['field'] : 'id';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'ASC';
		$where = " 1 ";
		$name  != '' ? $where.= " AND `name` LIKE '%$name%'" : '';
		if($mf == 'm'){
			$where.= " AND `m` = '1'";
		}elseif($mf == 'c'){
			$where.= " AND `c` = '1'";
		}elseif($mf == 'a'){
			$where.= " AND `a` = '1'";
		}
		$this->db = pc_base::load_model('cms_area_model');
		$this->tv_channel_area_mapping_model = pc_base::load_model('tv_channel_area_mapping_model');
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';
		$areaco  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);

		foreach ($areaco as $key=>$v){
			$id = $v['id'];
			$aKey = "area_id LIKE '%$id%'";
			$limitInfo = $this->tv_channel_area_mapping_model->listinfo($aKey);	
			$areaco[$key]['numch'] = count($limitInfo);   //区域频道数
		}
		$pages = $this->db->pages;
		include $this->admin_tpl('areacount');
	}
	/**
	 * 区域排序
	 */
	public function areaload(){
		
		include $this->admin_tpl('areaload');
	}
	//查询下级上级区域
	public function area_sellist(){
		$this->cms_area = pc_base::load_model('cms_area_model');
		$mf  = $_GET['mf'];
		if($mf=='c'){ //省级省份
			$limitInfo = $this->cms_area->select($where = ' m=1', $data = 'id,name', $limit = '', $order = 'id ASC', $group = '', $key='');
		}elseif($mf=='a'){  //城市
			$limitInfo = $this->cms_area->select($where = ' c=1', $data = 'id,name', $limit = '', $order = 'id ASC', $group = '', $key='');
		}
		$data = json_encode($limitInfo);
		echo $data;
	}
	/*
	 * 添加资讯
	*/
	public function addinfor(){
		$this->cms_area = pc_base::load_model('cms_area_model');
		$menu = pc_base::load_sys_class('tree');
		$menu->icon = array('│ ','├─ ','└─ ');
		$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
		$result = $this->cms_area->select();
		foreach ($result as $n=>$t) {
				$result[$n]['cname'] = $t['name'];
				$result[$n]['level'] = $this->op->get_level($t['id'],$result);
				$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
			}
			$str  = "<tr id='node-\$id' \$parentid_node>
							<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
						</tr>";
		$menu->init($result);
		$categorys = $menu->get_tree(0, $str);
		$show_header = true;
		$show_scroll = true;
		$this->information_type = pc_base::load_model('cms_information_type_model');
		$type_name_list = $this->information_type->select('', 'id, type_name', '', 'id ASC');
		include $this->admin_tpl('infor_add');
	}
	/*
	 * 修改资讯
	*/
	public function editinfor(){
		$id = $_REQUEST['id'];
		$this->information = pc_base::load_model('cms_information_model');
		$this->information_area = pc_base::load_model('cms_information_area_model');
		$aKey = "id = '".$id."'";
		$limitInfo = $this->information->get_one($aKey);
		$this->information_type = pc_base::load_model('cms_information_type_model');
		$information_type = $this->information_type->select();
		$this->cms_area = pc_base::load_model('cms_area_model');
		$inforda = $this->information_area->select(array('nid'=>$id));
		$menu = pc_base::load_sys_class('tree');
		$menu->icon = array('│ ','├─ ','└─ ');
		$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
		$result = $this->cms_area->select();
		foreach ($result as $n=>$t) {
			$result[$n]['cname'] = $t['name'];
			$result[$n]['checked'] = '';
			foreach ($inforda as $v){
				if($v['aid']=='0'){
					$v['aid'] = '01';
				}
				if ($t['id']==$v['aid']){
					$result[$n]['checked'] = 'checked';
				}
			}
			$result[$n]['level'] = $this->op->get_level($t['id'],$result);
			$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
		}
		$str  = "<tr id='node-\$id' \$parentid_node>
				<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
				</tr>";
		$menu->init($result);
		$categorys = $menu->get_tree(0, $str);
		include $this->admin_tpl('infor_edit');
	}
	
}
?>