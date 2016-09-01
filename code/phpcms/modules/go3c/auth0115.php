<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class auth extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		$this->auth_info = pc_base::load_model('usercenter_auth_info_model');
		$this->valid_client = pc_base::load_model('usercenter_valid_client_model');
		$this->auth_code = pc_base::load_model('usercenter_code_model');
		$this->remote = pc_base::load_model('cms_remote_model');
		$this->cid_tmp = pc_base::load_model('usercenter_cid_tmp_model');
		$this->api_authguid = pc_base::load_model('api_authguid_model');
	}   	
	
	public function init() {
		
	}
	
	public function getRemoteImg() {
		$type = $_GET['type'];
		$res = $this->remote->select("type = '$type'","siurl");
		$img = $res[0]['siurl'];
		echo $img;
	}
	
	//客户列表
	public function client_list(){
		$admin_username = param::get_cookie('admin_username');
		$ID   	= trim($_GET['ID']);
		$term_type   	= trim($_GET['term_type']);
		$where = " WHERE 1";
		if($_SESSION['roleid']=='1'){	//超级管理员能看到所有数据
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}else{
			$where .= " and ID in ('".$_SESSION['spid']."')";
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}
		if(!empty($term_type)){
			$where .= " and term_type ='$term_type'";
		}
		$field    	= '*';
		$sql     	= "test_customer_new ".$where;
		$order  	= ' ORDER BY cid DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->auth_info->mynum($sql);
		$totalpage	= $this->auth_info->mytotalpage($sql, $perpage);
		$data 		= $this->auth_info->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->auth_info->pages;
		//获取客户id
		$fd  = '*';
		$wh = "test_customer_new WHERE 1 group by ID ";
		$ainfo = $this->auth_info->mylistinfo($fd, $wh, $order, $page, $perpage);
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('auth_type');
	}
	
//设备版本信息列表
	public function version(){
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->valid_client->select('', 'ID', $limit = '', '', ' ID');
		}else{
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$spid_list = $this->valid_client->select("ID in (".$sqlin.")", 'ID', $limit = '', '', ' ID');
		}	
		$admin_username = param::get_cookie('admin_username');
		$ID   	= trim($_GET['ID']);
		$term_type   	= trim($_GET['term_type']);
		$where = " WHERE 1";
		if(!empty($ID)){
			$where .= " AND c.ID = '".$ID."'";
		} else if ($_SESSION['roleid']!='1') {
			$where .= " AND c.ID in (".$sqlin.")";
		}
		if(!empty($term_type)){
			$where .= " and c.term_type ='$term_type'";
		}
		$rmac = trim($_GET['rmac']);
		$where .=  $where && $rmac ? ' AND ' : ''; 
		$where .= $rmac ? " c.rmac LIKE '%$rmac%'" : "";
		$field    	= 'r.*,c.*,r.name as remote_name';
		$sql     	= "test_client_new as c left join v9_remote as r on c.remote_type = r.type ".$where;
		//die($sql);
		$fieldorder    = isset($_GET['field']) ? $_GET['field'] : 'auth_time';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$order = ' ORDER BY ' . $fieldorder . ' ' . $order;
		//$order  	= ' ORDER BY auth_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 8;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->valid_client->mynum($sql);
		$totalpage	= $this->valid_client->mytotalpage($sql, $perpage);
		$data 		= $this->valid_client->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->valid_client->pages;
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht, ' ORDER BY cid DESC', $page, $perpage);
		include $this->admin_tpl('auth_version');
	}
	
	/*
	 * 添加客户模型
	 */
	public function add_auth(){
		include $this->admin_tpl('auth_add');
	}
	/*
	 * 添加客户操作
	 */
	public function add_authdo(){
		$ID = $_POST['ID'];
		$term_type = $_POST['term_type'];
		$limit_max = $_POST['limit_max'];
		$expiry_date = $_POST['expiry_date'];
		$auth_key = $_POST['auth_key'];
		$area = $_POST['area'];
		$cpu = $_POST['cpu'];
		$auth_key = md5($auth_key);
		$GO3C = $_POST['GO3C'];
		$data = array(
				'ID' => $ID,
				'term_type' => $term_type,
				'limit_max' => $limit_max,
				'expiry_date' => $expiry_date,
				'auth_key' => $auth_key,
				'area' => $area,
				'cpu' => $cpu
				);
		if (strpos($ID, 'GO3C')!==false){
			$data['GO3C'] = $GO3C;
		}else{
			$data['PROJ'] = $GO3C;
		}
		$this->auth_info->insert($data);
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=client_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	/*
	 * 编辑客户模型
	 */
	public function edit_auth(){
		$cid = $_GET['cid'];
		if(empty($cid)){
			$msg = '异常数据!';
			showmessage($msg,'?m=go3c&c=auth&a=client_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$aKey = "cid = '".$cid."'";
		$data = $this->auth_info->get_one($aKey);
		include $this->admin_tpl('auth_edit');
	}
	
	/*
	 * 编辑终端版本信息
	 */
	public function edit_version(){
		$vid = $_GET['vid'];
		if(empty($vid)){
			$msg = '异常数据!';
			showmessage($msg,'?m=go3c&c=auth&a=version&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$aKey = "vid = '".$vid."'";
		$data = $this->valid_client->get_one($aKey);
		//获取所有遥控器型号
		$wh = " 1 group by type ";
		$remoteArr = $this->remote->select($wh,'name,type');
		//get cid tmp
		$cidres = $this->cid_tmp->select(" t_vid = '$data[vid]' and t_status = 1", "t_cid");
		if (count($cidres))
			$cidtmp = $cidres[0]['t_cid'];
		else 
			$cidtmp = $data['ID'];
		//获取客户id
		$fd  = 'ID';
		$wh = " 1 group by ID ";
		$ainfo = $this->auth_info->select($wh,$fd);
		include $this->admin_tpl('version_edit');
	}
	
	/*
	 * 编辑客户操作
	 */
	public function edit_authdo(){
		$cid = $_POST['cid'];
		if(empty($cid)){
			$msg = '异常数据!';
			showmessage($msg,'?m=go3c&c=auth&a=client_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$ID = $_POST['ID'];
		$term_type = $_POST['term_type'];
		$limit_max = $_POST['limit_max'];
		$expiry_date = $_POST['expiry_date'];
		$auth_start = $_POST['auth_start'];
		$auth_end = $_POST['auth_end'];
		$area = $_POST['area'];
		$cpu = $_POST['cpu'];
		$auth_key = md5($auth_key);
		$GO3C = $_POST['GO3C'];
		$data = array(
				'ID' => $ID,
				'term_type' => $term_type,
				'limit_max' => $limit_max,
				'expiry_date' => $expiry_date,
				'auth_start' => $auth_start,
				'auth_end' => $auth_end,
				'area' => $area,
				'cpu' => $cpu
		);
		if (strpos($ID, 'GO3C')!==false){
			$data['GO3C'] = $GO3C;
		}else{
			$data['PROJ'] = $GO3C;
		}
		$this->auth_info->update($data,array('cid'=>$cid));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=client_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 编辑设备版本操作
	 */
	public function edit_versiondo(){
		$cidold = $_POST['cid_old'];
		$cidnew = $_POST['cid_new'];
		$vid = $_POST['vid'];
		//set tmp cid status = 0
		$this->cid_tmp->update(array('t_status'=>0), array('t_vid'=>$vid));
		if ($cidold != $cidnew) {
			$d = array(
			't_vid' => $vid,
			't_cid' => $cidnew,
			't_status' => 1,
			't_time' => date('Y-m-d H:i:s')
			);
			$this->cid_tmp->insert($d);
		}
		if(empty($vid)){
			$msg = '异常数据!';
			showmessage($msg,'?m=go3c&c=auth&a=version&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$version = $_POST['version'];
		$version_notice = $_POST['version_notice'];
		$reg_repeat = $_POST['reg_repeat'];
		$debug_mode = $_POST['debug_mode'];
		$data = array(
			'version' => $version,
			'version_notice' => $version_notice,
			'reg_repeat' => $reg_repeat,
			'debug_mode' => $debug_mode
		);
		if ($_POST['remote'] != '')	
			$data['remote_type'] = $_POST['remote'];
		$this->valid_client->update($data,array('vid'=>$vid));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=version&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 删除客户
	 */
	public function delete_auth(){
		$cid = $_GET['cid'];
		$this->auth_info->delete(array('cid'=>$cid));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=client_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//项目列表
	public function proclient(){
		$admin_username = param::get_cookie('admin_username');
		$device_id   	= trim($_GET['device_id']);
		$mac_wire   	= trim($_GET['mac_wire']);
		$where = " WHERE 1";
		if(!empty($device_id)){
			$where .= " and device_id ='$device_id'";
		} 
		if(!empty($mac_wire)){
			$where .= " and mac_wire ='$mac_wire'";
		}
		
		$field    	= '*';
		$sql     	= "auth_guid ".$where;
		$order  	= ' ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 7;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->api_authguid->mynum($sql);
		$totalpage	= $this->api_authguid->mytotalpage($sql, $perpage);
		$data 		= $this->api_authguid->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->api_authguid->pages;
		include $this->admin_tpl('valid_client');
	}
	/*
	 * 添加项目模型
	 */
	public function add_cilent(){
		include $this->admin_tpl('valid_client_add');
	}
	//添加项目操作
	public function add_cilentdo(){
		$ID = $_POST['ID'];
		$term_type = $_POST['term_type'];
		$key = $_POST['key'];
		$apply_time = $_POST['apply_time'];
		$ip = $_POST['ip'];
		$area = $_POST['area'];
		$chip = $_POST['chip'];
		$ram = $_POST['ram'];
		$rom = $_POST['rom'];
		$wifi = $_POST['wifi'];
		$bt = $_POST['bt'];
		$audio = $_POST['audio'];
		$RJ45_MAC = $_POST['RJ45_MAC'];
		$spdif = $_POST['spdif'];
		$data = array(
				'ID' => $ID,
				'term_type' => $term_type,
				'key' => $key,
				'apply_time' => $apply_time,
				'ip' => $ip,
				'area' => $area,
				'chip' => $chip,
				'ram' => $ram,
				'rom' => $rom,
				'wifi' => $wifi,
				'bt' => $bt,
				'audio' => $audio,
				'RJ45_MAC' => $RJ45_MAC,
				'spdif' => $spdif
		);
		$this->valid_client->insert($data);
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=proclient&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//修改项目模型
	public function edit_cilent(){
		$vid = $_GET['vid'];
		if(empty($vid)){
			$msg = '异常数据!';
			showmessage($msg,'?m=go3c&c=auth&a=proclient&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$aKey = "vid = '".$vid."'";
		$data = $this->valid_client->get_one($aKey);
		var_dump($data);
		include $this->admin_tpl('valid_client_edit');
	}
	public function edit_cilentdo(){
		$vid = $_POST['vid'];
		$ID = $_POST['ID'];
		$term_type = $_POST['term_type'];
		$key = $_POST['key'];
		$apply_time = $_POST['apply_time'];
		$ip = $_POST['ip'];
		$area = $_POST['area'];
		$chip = $_POST['chip'];
		$ram = $_POST['ram'];
		$rom = $_POST['rom'];
		$wifi = $_POST['wifi'];
		$bt = $_POST['bt'];
		$audio = $_POST['audio'];
		$RJ45_MAC = $_POST['RJ45_MAC'];
		$spdif = $_POST['spdif'];
		if(empty($vid)){
			$msg = '异常数据!';
			showmessage($msg,'?m=go3c&c=auth&a=proclient&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$data = array(
				'ID' => $ID,
				'key' => $key,
				'term_type' => $term_type,
				'apply_time' => $apply_time,
				'ip' => $ip,
				'area' => $area,
				'chip' => $chip,
				'ram' => $ram,
				'rom' => $rom,
				'wifi' => $wifi,
				'bt' => $bt,
				'audio' => $audio,
				'RJ45_MAC' => $RJ45_MAC,
				'spdif' => $spdif
		);
		$this->valid_client->update($data,array('vid'=>$vid));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=proclient&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//删除项目
	public function delete_cilent(){
		$id = $_GET['id'];
		if(empty($id)){
			$msg = '操作失败!';
			showmessage($msg,'?m=go3c&c=auth&a=proclient&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			$this->api_authguid->delete(array('id'=>$id));
			$msg = '操作成功!';
			showmessage($msg,'?m=go3c&c=auth&a=proclient&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	/*
	 * 授权码管理页面
	 */
	public function code(){
		$admin_username = param::get_cookie('admin_username');
		$title   	= trim($_GET['title']);
		$title = $title ? " AND title LIKE '%$title%' " : '';
		$field    	= '*';
		$sql     	= "code WHERE 1 ".$title;
		$order  	= 'ORDER BY id';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->auth_code->mynum($sql);
		$totalpage	= $this->auth_code->mytotalpage($sql, $perpage);
		$data 		= $this->auth_code->mylistinfo($field, $sql, $order, $page, $perpage);
		foreach ($data as $key=>$v){
			$type_id = $v['type_id'];
			$type = $this->auth_info->get_one(array('cid'=>$type_id));
			$data[$key]['aid'] = $type['ID'];
		}
		$multipage  = $this->auth_code->pages;
		include $this->admin_tpl('code_list');
	}
	/*
	 * 添加授权码模型
	 */
	public function add_code(){
		$field    	= '*';
		$sql     	= "test_customer_new WHERE 1 ";
		$order  	= 'GROUP by ID ORDER BY cid';
		$auth 		= $this->auth_info->mylistinfo($field, $sql, $order);
		
		$fieldv    	= '*';
		$sqlv     	= "valid_client WHERE 1 ";
		$orderv  	= 'GROUP by ID ORDER BY vid';
		$valid 		= $this->valid_client->mylistinfo($fieldv, $sqlv, $orderv);
		include $this->admin_tpl('code_add');
	}
	public function add_codedo(){
		$type_id = $_POST['type_id'];
		$client_id = $_POST['client_id'];
		$title = $_POST['title'];
		$data1 = $this->auth_info->get_one(array('ID'=>$type_id));
		$data2 = $this->valid_client->get_one(array('ID'=>$client_id));
		$data = array(
				'type_id' => $data1['cid'],
				'client_id' => $data2['vid'],
				'title' => $title
		);
		$this->auth_code->insert($data);
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=code&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	/*
	 * 修改授权码模型
	 */
	public function edit_code(){
		$id = $_GET['id'];
		$data = $this->auth_code->get_one(array('id'=>$id));
		
		$field    	= '*';
		$sql     	= "test_customer_new WHERE 1 ";
		$order  	= 'GROUP by ID ORDER BY cid';
		$auth 		= $this->auth_info->mylistinfo($field, $sql, $order);
		
		$fieldv    	= '*';
		$sqlv     	= "valid_client WHERE 1 ";
		$orderv  	= 'GROUP by ID ORDER BY vid';
		$valid 		= $this->valid_client->mylistinfo($fieldv, $sqlv, $orderv);
		include $this->admin_tpl('code_edit');
	}
	//修改授权码
	public function edit_codedo(){
		$type_id = $_POST['type_id'];
		$client_id = $_POST['client_id'];
		$title = $_POST['title'];
		$id = $_POST['id'];
		$data = array(
				'type_id' => $type_id,
				'client_id' => $client_id,
				'title' => $title
		);
		$this->auth_code->update($data,array('id'=>$id));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=code&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//删除授权码
	public function delete_code(){
		$id = $_GET['id'];
		$this->auth_code->delete(array('id'=>$id));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=code&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	//导入鉴权码
	public function import() {
		include $this->admin_tpl('auth_import');
	}
	
	public function importdo() {
		$cid = $_POST['cid'];
		$authcode = $_FILES['authcode'];
		if ($authcode['error']) {
			$msg = 'upload file error';
		} else if ($authcode['size']) {
			$path = $_SERVER['DOCUMENT_ROOT'].'/go3ccms/';
			$file = $path . 'uploadfile/auth.json';
			if(file_exists($file))
				unlink($file); 
			move_uploaded_file($authcode['tmp_name'], $file);
			$msg = 'upload file success';
		} else {
			$msg = 'file is empty';
		}
		$c = file_get_contents("http://www.go3c.tv:8060/go3cauth/api.php?m=importauth&cid=" . $cid);
		showmessage($c,'?m=go3c&c=auth&a=import&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	//批量删除客户列表
	public function delete_custall(){
		$ids=explode(',', $_GET['cid']);
		foreach ($ids as $v){
			$this->auth_info->delete(array('cid'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//批量删除终端设备
	public function delete_clientall(){
		$ids=explode(',', $_GET['id']);
		foreach ($ids as $v){
			$this->api_authguid->delete(array('id'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//批量更新终端版本
	public function update_clientall(){
		$ids = explode(',', $_GET['vid']);
		$doitem = $_GET['doitem'];
		foreach ($ids as $v){
			$this->valid_client->update(array('version'=>$doitem),array('vid'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//终端设备详情页面
	public function detail_cilent(){
		$vid = $_GET['vid'];
		if(empty($vid)){
			showmessage('数据异常!',base64_decode($_GET['goback']));
		}
		$client = $this->valid_client->get_one(array('vid'=>$vid));
		include $this->admin_tpl('auth_detail_cilent');
	}
	//菜单项列表
	public function menulist(){
		$this->shop_menu = pc_base::load_model('go3capi_menu_model');
		$field    	= '*';
		$sql     	= "auth_menu WHERE 1 ";
		$order  	= 'ORDER BY m_id';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->shop_menu->mynum($sql);
		$totalpage	= $this->shop_menu->mytotalpage($sql, $perpage);
		$data 		= $this->shop_menu->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->shop_menu->pages;
		include $this->admin_tpl('shop_menu_list');
	}
	public function menu_edit() {
		$this->shop_menu = pc_base::load_model('go3capi_menu_model');
		$menuArr = $this->getMenuByCid();
		$menuAll = $this->shop_menu->select();
		//var_dump($menuArr);
		$menuDisabled = array();
		foreach ($menuAll as $a) {
			$flag = false;
			foreach ($menuArr as $k => $v) {
				if ($k == $a['m_key']) {
					$flag = true;
					break;
				}
			}
			if (!$flag)
				$menuDisabled[$a[m_key]] = $a['m_name_zh'];
		}
		include $this->admin_tpl('shop_menu_edit');
	}
	private function getMenuByCid() {
		$this->shop_menu_diy = pc_base::load_model('go3capi_menu_diy_model');
		$field    	= 'd.*,m.*';
		$order = ' ';
		//$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$perpage = 10000;
		$where = " where 1";
		$sql = "auth_menu_diy as d left join auth_menu as m on d.d_mid = m.m_id" . $where;
		$data 		= $this->shop_menu_diy->mylistinfo($field, $sql, $order, $page, $perpage);
		$m = array();
		foreach ($data as $d) {
			$m[$d[m_key]] = $d[m_name_zh];
		}
		return $m;
	}
	public function menu_edit_do() {
		$this->shop_menu_diy = pc_base::load_model('go3capi_menu_diy_model');
		$this->shop_menu = pc_base::load_model('go3capi_menu_model');
		$menu = explode(',', $_GET['menuStr']);
		$menuStrl = explode(',', $_GET['menuStrl']);
		$this->shop_menu_diy->delete(array('d_cid'=>'topway'));
		$st = $this->shop_menu->select();
		foreach($st as $v){
			$m_id = $v['m_id'];
			$this->shop_menu->update(array('m_status'=>'off'),array('m_id'=>$m_id));
		}
		foreach ($menu as $v) {
			$res = $this->shop_menu->select("m_key = '$v'","m_id");
			$data = array(
			'd_cid' => 'topway',
			'd_mid' => $res[0]['m_id'],
			'd_time' => date('Y-m-d H:i:s')
			);
			$this->shop_menu_diy->insert($data);
			$m_id = $res[0]['m_id'];
			$this->shop_menu->update(array('m_status'=>'on'),array('m_id'=>$m_id));
		}
		//添加操作日志
		$status = 'edit';
		$description = $st;
		$this->system_log($status,json_encode($description));
		showmessage('操作成功','index.php?m=go3c&c=auth&a=menulist');
	}
	//ip白名单
	public function ipwhite(){
		$this->go3capi_ip = pc_base::load_model('go3capi_ip_model');
		$field    	= '*';
		$sql     	= "auth_ip WHERE 1 ";
		$order  	= 'ORDER BY id';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->go3capi_ip->mynum($sql);
		$totalpage	= $this->go3capi_ip->mytotalpage($sql, $perpage);
		$data 		= $this->go3capi_ip->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->go3capi_ip->pages;
		include $this->admin_tpl('shop_ipwhite');
	}
	//添加ip白名单
	public function shop_addip(){
		include $this->admin_tpl('shop_ipwhiteadd');
	}
	public function shop_addipdo(){
		$this->go3capi_ip = pc_base::load_model('go3capi_ip_model');
		$ipstart = $_POST['ipstart'];
		$ipend = $_POST['ipend'];
		$status = $_POST['status'];
		$iptype = $_POST['iptype'];
		
		$dat = array(
			'ipstart' => $ipstart,
			'ipend' => $ipend,
			'status' => $status,
			'iptype' => $iptype
		);
		$this->go3capi_ip->insert($dat);
		//添加操作日志
		$status = 'add';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=ipwhite&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//编辑白名单
	public function shop_editip(){
		$id = $_GET['id'];
		$this->go3capi_ip = pc_base::load_model('go3capi_ip_model');
		$data = $this->go3capi_ip->get_one(array('id'=>$id));
		include $this->admin_tpl('shop_ipwhiteedit');
	}
	public function shop_editipdo(){
		$this->go3capi_ip = pc_base::load_model('go3capi_ip_model');
		$id = $_POST['id'];
		$ipstart = $_POST['ipstart'];
		$ipend = $_POST['ipend'];
		$status = $_POST['status'];
		$iptype = $_POST['iptype'];
		
		$dat = array(
			'ipstart' => $ipstart,
			'ipend' => $ipend,
			'status' => $status,
			'iptype' => $iptype
		);
		$this->go3capi_ip->update($dat,array('id'=>$id));
		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=ipwhite&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	public function shop_delip(){
		$this->go3capi_ip = pc_base::load_model('go3capi_ip_model');
		$id = $_GET['id'];
		$this->go3capi_ip->delete(array('id'=>$id));
		//添加操作日志
		$st = $this->go3capi_ip->get_one(array('id'=>$id));
		$status = 'del';
		$description = $st;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=ipwhite&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	//设备绑定关系列表
	public function bindlist(){
		$this->guid_user = pc_base::load_model('go3capi_guid_user_model');
		$cardid = $_GET['cardid'];
		$userid = $_GET['userid'];
		$mac_wire = $_GET['mac_wire'];
		$bild = $_GET['bild'];
		$where = "1 ";
		if(!empty($cardid)){
			$where.="and cardid='$cardid' ";
		}
		if(!empty($userid)){
			$where.="and userid='$userid' ";
		}
		if(!empty($mac_wire)){
			$where.="and mac_wire='$mac_wire' ";
		}
		if(!empty($bild)){
			$where.=" and bild ='$bild' ";
		}
		$field    	= '*';
		$sql     	= "auth_guid_user WHERE ".$where;
		$order  	= 'ORDER BY id desc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->guid_user->mynum($sql);
		$totalpage	= $this->guid_user->mytotalpage($sql, $perpage);
		$data 		= $this->guid_user->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->guid_user->pages;
		include $this->admin_tpl('shop_bindlist');
	}
	//添加绑定关系
	public function shop_addbind(){
		include $this->admin_tpl('shop_addbind');
	}
	public function shop_addbinddo(){
		$this->guid_user = pc_base::load_model('go3capi_guid_user_model');
		$userid = $_POST['userid'];
		$cardid = $_POST['cardid'];
		$phonenumber = $_POST['phonenumber'];
		$installaddress = $_POST['installaddress'];
		$guid = $_POST['guid'];
		
		$dat = array(
			'userid' => $userid,
			'cardid' => $cardid,
			'phonenumber' => $phonenumber,
			'installaddress' => $installaddress,
			'guid' => $guid,
			'createtime' => time()
		);
		$this->guid_user->insert($dat);
		//添加操作日志
		$status = 'add';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=bindlist&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	//编辑绑定关系
	public function shop_editbind(){
		$id = $_GET['id'];
		$this->guid_user = pc_base::load_model('go3capi_guid_user_model');
		$data = $this->guid_user->get_one(array('id'=>$id));
		include $this->admin_tpl('shop_editbind');
	}
	public function shop_editbinddo(){
		$this->guid_user = pc_base::load_model('go3capi_guid_user_model');
		$id = $_POST['id'];
		$userid = $_POST['userid'];
		$cardid = $_POST['cardid'];
		$phonenumber = $_POST['phonenumber'];
		$installaddress = $_POST['installaddress'];
		$guid = $_POST['guid'];
		
		$dat = array(
			'userid' => $userid,
			'cardid' => $cardid,
			'phonenumber' => $phonenumber,
			'installaddress' => $installaddress,
			'guid' => $guid,
			'createtime' => time()
		);
		$this->guid_user->update($dat,array('id'=>$id));
		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=bindlist&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	public function shop_delbind(){
		$this->guid_user = pc_base::load_model('go3capi_guid_user_model');
		$id = $_GET['id'];
		$this->guid_user->delete(array('id'=>$id));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=bindlist&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	//测速文件列表
	public function networkspeed(){
		$this->networkspeed = pc_base::load_model('go3capi_networkspeed_model');
		$field    	= '*';
		$sql     	= "auth_networkspeed WHERE 1 ";
		$order  	= 'ORDER BY id';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->networkspeed->mynum($sql);
		$totalpage	= $this->networkspeed->mytotalpage($sql, $perpage);
		$data 		= $this->networkspeed->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->networkspeed->pages;
		include $this->admin_tpl('shop_networkspeed');
	}
	public function shop_addspeed(){
		include $this->admin_tpl('shop_addspeed');
	}
	public function shop_addspeeddo(){
		$this->networkspeed = pc_base::load_model('go3capi_networkspeed_model');
		$name = $_POST['name'];
		$url = $_POST['url'];
		$dat = array(
			'name' => $name,
			'url' => $url,
			'createtime' => time()
		);
		$this->networkspeed->insert($dat);
		//添加操作日志
		$status = 'add';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=networkspeed&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	public function shop_editspeed(){
		$id = $_GET['id'];
		$this->networkspeed = pc_base::load_model('go3capi_networkspeed_model');
		$data = $this->networkspeed->get_one(array('id'=>$id));
		include $this->admin_tpl('shop_editspeed');
	}
	public function shop_editspeeddo(){
		$this->networkspeed = pc_base::load_model('go3capi_networkspeed_model');
		$id = $_POST['id'];
		$name = $_POST['name'];
		$url = $_POST['url'];
		$dat = array(
			'name' => $name,
			'url' => $url,
			'createtime' => time()
		);
		$this->networkspeed->update($dat,array('id'=>$id));
		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=networkspeed&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	public function shop_delspeed(){
		$this->networkspeed = pc_base::load_model('go3capi_networkspeed_model');
		$id = $_GET['id'];
		$this->networkspeed->delete(array('id'=>$id));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=networkspeed&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	
	//解除绑定关系
	public function shop_relbild(){
		$this->guid_user = pc_base::load_model('go3capi_guid_user_model');
		$this->guid_user_log = pc_base::load_model('go3capi_guid_user_log_model');
		$id = $_GET['id'];
		$dat = array(
			'bild' => 'off',
			'endtime' => time()
		);
		$this->guid_user->update($dat,array('id'=>$id));
		$dato = $this->guid_user->get_one(array('id'=>$id));
		$datlog = array(
			'guid' => $dato['guid'],
			'mac_wire' => $dato['mac_wire'],
			'userid' => $dato['userid'],
			'cardid' => $dato['cardid'],
			'phonenumber' => $dato['phonenumber'],
			'installaddress' => $dato['installaddress'],
			'createtime' => time(),
			'status' => $dato['status'],
			'bild' => $dato['bild']
		);
		$this->guid_user_log->insert($datlog);
		//添加操作日志
		$status = 'edit';
		$description = $dato;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=bindlist&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	//后台操作日志列表
	public function systemlog(){
		$this->app_onlinelog = pc_base::load_model('app_onlinelog_model');
		$user = $_GET['user'];
		$createtime = $_GET['createtime'];
		$createtime1 = $_GET['createtime1'];
		$where = "`description` IS NOT NULL ";
		$userid   != '' ? $where.= " AND `userid` = '$userid'" : '';
		$createtime   != '' ? $where.= " AND `createtime` >= UNIX_TIMESTAMP('$createtime')" : '';
		$createtime1   != '' ? $where.= " AND `createtime` <= UNIX_TIMESTAMP('$createtime1')" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->app_onlinelog->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->app_onlinelog->pages;
		include $this->admin_tpl('systemlog');
	}
	//接口访问日志列表
	public function interfacelog(){
		$this->go3capi_logg = pc_base::load_model('go3capi_logg_model');
		$url = $_GET['url'];
		$createtime = $_GET['createtime'];
		$createtime1 = $_GET['createtime1'];
		$where = "1 ";
		$url   != '' ? $where.= " AND `url` like '%$url%'" : '';
		$createtime   != '' ? $where.= " AND `createtime` >= UNIX_TIMESTAMP('$createtime')" : '';
		$createtime1   != '' ? $where.= " AND `createtime` <= UNIX_TIMESTAMP('$createtime1')" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->go3capi_logg->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->go3capi_logg->pages;
		include $this->admin_tpl('auth_interfacelog');
	}
	
	//设置账号绑定盒子数量
	public function bindnumber(){
		$cardid = $_GET['cardid'];
		$this->bindnumber = pc_base::load_model('go3capi_guid_user_restrict_model');
		$field    	= '*';
		if($cardid!= ''){
			$sql     	= "auth_guid_user_restrict WHERE cardid='$cardid' ";
		}else{
			$sql     	= "auth_guid_user_restrict WHERE 1 ";
		}
		//$sql     	= "auth_guid_user_restrict WHERE 1 ";
		$order  	= 'ORDER BY id';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->bindnumber->mynum($sql);
		$totalpage	= $this->bindnumber->mytotalpage($sql, $perpage);
		$data 		= $this->bindnumber->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->bindnumber->pages;
		include $this->admin_tpl('shop_bindnumber');
	}
	public function shop_addcardid(){
		include $this->admin_tpl('shop_addcardid');
	}
	public function shop_addcardiddo(){
		$this->bindnumber = pc_base::load_model('go3capi_guid_user_restrict_model');
		$cardid = $_POST['cardid'];
		$num_max = $_POST['num_max'];
		$dat = array(
			'cardid' => $cardid,
			'num_max' => $num_max,
			'createtime' => time()
		);
		$num= $this->bindnumber->get_one(array('cardid'=>$cardid));
		if(!empty($num)){
			$msg = '身份证号已经存在!';
			showmessage($msg,'?m=go3c&c=auth&a=bindnumber&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
		}
		$this->bindnumber->insert($dat);
		//添加操作日志
		$status = 'add';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=bindnumber&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	public function shop_editcardid(){
		$id = $_GET['id'];
		$this->bindnumber = pc_base::load_model('go3capi_guid_user_restrict_model');
		$data = $this->bindnumber->get_one(array('id'=>$id));
		include $this->admin_tpl('shop_editcardid');
	}
	public function shop_editcardiddo(){
		$this->bindnumber = pc_base::load_model('go3capi_guid_user_restrict_model');
		$id = $_POST['id'];
		$cardid = $_POST['cardid'];
		$num_max = $_POST['num_max'];
		$dat = array(
			'cardid' => $cardid,
			'num_max' => $num_max,
			'createtime' => time()
		);
		$this->bindnumber->update($dat,array('id'=>$id));
		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=bindnumber&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	public function shop_delcardid(){
		$this->bindnumber = pc_base::load_model('go3capi_guid_user_restrict_model');
		$id = $_GET['id'];
		$this->bindnumber->delete(array('id'=>$id));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=auth&a=bindnumber&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}
	
}