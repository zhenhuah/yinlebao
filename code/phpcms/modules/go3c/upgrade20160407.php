<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class upgrade extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		$this->client_upgrade = pc_base::load_model('go3capi_client_upgrade_model');
		$this->go3capi_guid = pc_base::load_model('go3capi_guid_model');
		$this->go3capi_upgrade_gray_device = pc_base::load_model('go3capi_upgrade_gray_device_model');
	}   	
	
	public function init() {
		
	}
	//天威升级列表
	public function upgradelist(){
		$type = $_GET['upgrade_type'];
		$status = isset($_GET['status']) ? $_GET['status'] : 1;
		//$typewhere = "(upgrade_type = 'APK' || upgrade_type = 'FIRMWARE' || upgrade_type = 'MIDWARE')";
		$where = " WHERE u_status = '$status'";
		if (!empty($type))
			$where .= " and upgrade_type = '$type'";
		$field    	= '*';
		$sql     	= "auth_client_upgrade ".$where;
		$order  	= ' ORDER BY upgrade_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->client_upgrade->mynum($sql);
		$totalpage	= $this->client_upgrade->mytotalpage($sql, $perpage);
		$data 		= $this->client_upgrade->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->client_upgrade->pages;
		include $this->admin_tpl('upgrade_list');
	}
	public function upgrade_add_topway(){
		//获取所有的盒子的mac地址
		$fd  = '*';
		$wh = "auth_guid WHERE 1 group by mac_wire ";
		$agroup = $this->go3capi_guid->mylistinfo($fd, $wh);
		$where = "1 group by versioncode";
		$upgrade = $this->client_upgrade->select($where, 'versioncode', '', 'versioncode ASC');
		include $this->admin_tpl('upgrade_add_topway');
	}
	public function upgrade_add_topwaydo(){
		$status = 2;
		$force = $_POST['force'];
		$gray = $_POST['gray'];
		$gray_term_type = $_POST['gray_term_type'];
		$upgrade_type = $_POST['upgrade_type'];
		$group = $_POST['group'];
		$versioncode = $_POST['versioncode'];
		$url = $_POST['url'];
		$size = $_POST['size'];
		$rely_version = $_POST['rely_version'];
		$filemd5 = $_POST['filemd5'];
		$version = trim($_POST['version']);
		$description = $_POST['description'];
		$upgrade_time =  date('Y-m-d H:i:s');
		//$filemd5p = md5_file($url);
		$cid = $_POST['cid'];
		$chid = $_POST['chid'];
		$is_mode = $_POST['is_mode'];
		$versionfr = $_POST['versionfr'];
		$versionto = $_POST['versionto'];
		//if ($filemd5 != $filemd5p)
		//	showmessage('文件的md5码不对');
		$versionf = str_replace('.','',$versionfr);
		$versiont = str_replace('.','',$versionto);
		if($is_mode=='2'&&($versionf>$versiont)){
			$msg = '增量升级时目标版本必须大于开始版本!';
			showmessage($msg,'?m=go3c&c=upgrade&a=upgradelist&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
		}
		
		if($is_mode=='2'){
			$data = array(
				'upgrade_type' => $upgrade_type,
				'u_status' => $status,	//2:新添加 待审核状态 0:pad / phone
				'is_gray' => $gray,
				'is_force' => $force,
				'url' => $url,
				'term_type' => $gray_term_type,
				'size' => $size,
				'rely_version' => $rely_version,
				'zip_size' => $size,
				'filemd5' => $filemd5,
				'versioncode' => $versioncode,
				'version' => $version,
				'description' => $description,
				'upgrade_time' => $upgrade_time,
				'cid' => $cid,
				'chid' => $chid,
				'is_mode' => $is_mode,
				'versionfr' => $versionfr,
				'versionto' => $versionto
			);
		}else{
			$data = array(
				'upgrade_type' => $upgrade_type,
				'u_status' => $status,	//2:新添加 待审核状态 0:pad / phone
				'is_gray' => $gray,
				'is_force' => $force,
				'url' => $url,
				'term_type' => $gray_term_type,
				'size' => $size,
				'rely_version' => $rely_version,
				'zip_size' => $size,
				'filemd5' => $filemd5,
				'versioncode' => $versioncode,
				'version' => $version,
				'description' => $description,
				'upgrade_time' => $upgrade_time,
				'cid' => $cid,
				'chid' => $chid,
				'is_mode' => $is_mode
			);
		}
		//把之前的版本设置为历史版本
		//$this->client_upgrade->update(array('u_status'=>0), array('upgrade_type'=>$upgrade_type, 'cid'=>'topway', 'u_status'=>1));
		//全部升级
		$this->client_upgrade->insert($data);
		//添加操作日志
		$status = 'add';
		$description = $data;
		$this->system_log($status,json_encode($description));
		$upid = $this->client_upgrade->insert_id();
		if($gray == '1'){  //为1时是灰度升级
			foreach($group as $v){
				$da = array(
					'mac_wire' => $v,
					'u_upgrade_id' => $upid
				);
				$this->go3capi_upgrade_gray_device->insert($da);
			}
		}
		showmessage('操作成功','index.php?m=go3c&c=upgrade&a=upgradelist');
	}
	public function edit_upgrade_topway(){
		$id = $_GET['id'];
		//获取所有的盒子的mac地址
		$fd  = '*';
		$wh = "auth_guid WHERE 1 group by mac_wire ";
		$agroup = $this->go3capi_guid->mylistinfo($fd, $wh);
		$upgrade = $this->client_upgrade->get_one(array('id'=>$id));
		$where = "1 group by versioncode";
		$upgradeversion = $this->client_upgrade->select($where, 'versioncode', '', 'versioncode ASC');
		include $this->admin_tpl('upgrade_edit_topway');
	}
	public function edit_upgrade_topwaydo(){
		$id = $_POST['id'];
		$status = 2;
		$force = $_POST['force'];
		$gray = $_POST['gray'];
		$gray_term_type = $_POST['gray_term_type'];
		$upgrade_type = $_POST['upgrade_type'];
		$group = $_POST['group'];
		$versioncode = $_POST['versioncode'];
		$url = $_POST['url'];
		$size = $_POST['size'];
		$rely_version = $_POST['rely_version'];
		$filemd5 = $_POST['filemd5'];
		$version = trim($_POST['version']);
		$description = $_POST['description'];
		$upgrade_time =  date('Y-m-d H:i:s');
		//$filemd5p = md5_file($url);
		$cid = $_POST['cid'];
		$chid = $_POST['chid'];
		$is_mode = $_POST['is_mode'];
		$versionfr = $_POST['versionfr'];
		$versionto = $_POST['versionto'];
		//if ($filemd5 != $filemd5p)
		//	showmessage('文件的md5码不对');
		$versionf = str_replace('.','',$versionfr);
		$versiont = str_replace('.','',$versionto);
		if($is_mode=='2'&&($versionf>$versiont)){
			$msg = '增量升级时目标版本必须大于开始版本!';
			showmessage($msg,'?m=go3c&c=upgrade&a=upgradelist&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
		}
		if($is_mode=='2'){
			$data = array(
				'upgrade_type' => $upgrade_type,
				'u_status' => $status,	//2:新添加 待审核状态 0:pad / phone
				'is_gray' => $gray,
				'is_force' => $force,
				'url' => $url,
				'term_type' => $gray_term_type,
				'size' => $size,
				'rely_version' => $rely_version,
				'zip_size' => $size,
				'filemd5' => $filemd5,
				'versioncode' => $versioncode,
				'version' => $version,
				'description' => $description,
				'upgrade_time' => $upgrade_time,
				'cid' => $cid,
				'chid' => $chid,
				'is_mode' => $is_mode,
				'versionfr' => $versionfr,
				'versionto' => $versionto
			);
		}else{
			$data = array(
				'upgrade_type' => $upgrade_type,
				'u_status' => $status,	//2:新添加 待审核状态 0:pad / phone
				'is_gray' => $gray,
				'is_force' => $force,
				'url' => $url,
				'term_type' => $gray_term_type,
				'size' => $size,
				'rely_version' => $rely_version,
				'zip_size' => $size,
				'filemd5' => $filemd5,
				'versioncode' => $versioncode,
				'version' => $version,
				'description' => $description,
				'upgrade_time' => $upgrade_time,
				'cid' => $cid,
				'chid' => $chid,
				'is_mode' => $is_mode
			);
		}
		//把之前的版本设置为历史版本
		//$this->client_upgrade->update(array('u_status'=>0), array('upgrade_type'=>$upgrade_type, 'cid'=>'topway', 'u_status'=>1));
		//全部升级
		$this->client_upgrade->update($data, array('id'=>$id));
		//添加操作日志
		$status = 'add';
		$description = $data;
		$this->system_log($status,json_encode($description));
		//$upid = $this->client_upgrade->insert_id();
		$upid = $id;
		if($gray == '1'){  //为1时是灰度升级
			$this->client_upgrade->delete(array('u_upgrade_id'=>$id));
			foreach($group as $v){
				$da = array(
					'mac_wire' => $v,
					'u_upgrade_id' => $upid
				);
				$this->go3capi_upgrade_gray_device->insert($da);
			}
		}
		showmessage('操作成功','index.php?m=go3c&c=upgrade&a=upgradelist');
	}
	//天威升级审核
	public function upgradeverify(){
		//$type = $_GET['upgrade_type'];
		$where = " WHERE u_status = '2'";
		$where .= " and upgrade_type = 'FIRMWARE'";
		$field    	= '*';
		$sql     	= "auth_client_upgrade ".$where;
		$order  	= ' ORDER BY upgrade_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->client_upgrade->mynum($sql);
		$totalpage	= $this->client_upgrade->mytotalpage($sql, $perpage);
		$data 		= $this->client_upgrade->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->client_upgrade->pages;
		include $this->admin_tpl('upgrade_topwayverify');
	}
	//审核同意
	public function veragree_upgrade(){
		$id = $_GET['id'];
		$upgrade_type = 'FIRMWARE';
		$this->client_upgrade->update(array('u_status'=>0), array('upgrade_type'=>$upgrade_type, 'cid'=>'topway', 'u_status'=>1));
		$this->client_upgrade->update(array('u_status'=>1), array('id'=>$id));
		//添加操作日志
		$data = $this->client_upgrade->get_one(array('id'=>$id));
		$status = 'audit';
		$description = $data;
		$this->system_log($status,json_encode($description));
		showmessage('操作成功','index.php?m=go3c&c=upgrade&a=upgradeverify');
	}
	//审核拒绝
	public function refuse_upgrade(){
		$id = $_GET['id'];
		$this->client_upgrade->update(array('u_status'=>-1), array('id'=>$id));
		$st = $this->client_upgrade->get_one(array('id'=>$id));
		//添加操作日志
		$status = 'audit';
		$description = $st;
		$this->system_log($status,json_encode($description));
		if($st['is_gray']==1){ //灰度升级则删除设备记录
			$this->go3capi_upgrade_gray_device->delete(array('id'=>$id));
		}
		showmessage('操作成功','index.php?m=go3c&c=upgrade&a=upgradeverify');
	}
	//删除升级信息
	public function delete_upgrade_topway(){
		$id = $_GET['id'];
		$this->client_upgrade->delete(array('id'=>$id));
		$this->go3capi_upgrade_gray_device->delete(array('u_upgrade_id'=>$id));
		$st = $this->client_upgrade->get_one(array('id'=>$id));
		//添加操作日志
		$status = 'del';
		$description = $st;
		$this->system_log($status,json_encode($description));
		showmessage('操作成功','index.php?m=go3c&c=upgrade&a=upgradelist');
	}
}
?>
