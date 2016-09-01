<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_app_func('admin');
define('TASK_IMG_PATH','http://127.0.0.1/yinlebao');
class admin_manage extends admin {
	private $db,$role_db;
	function __construct() {
		parent::__construct();
		$this->db = pc_base::load_model('admin_model');
		$this->spid_db = pc_base::load_model('cms_spid_model');
		$this->role_db = pc_base::load_model('admin_role_model');
		$this->op = pc_base::load_app_class('admin_op');
	}
	
	/**
	 * 管理员管理列表
	 */
	public function init() {
		$userid = $_SESSION['userid'];
		$admin_username = param::get_cookie('admin_username');
		$page = $_GET['page'] ? intval($_GET['page']) : '1';
		$infos = $this->db->listinfo('', 'userid asc', $page, 20);
		$pages = $this->db->pages;
		$roles = getcache('role','commons');
		include $this->admin_tpl('admin_list');
	}
	
	/**
	 * 添加管理员
	 */
	public function add() {
		if(isset($_POST['dosubmit'])) {
			$info = array();
			if(!$this->op->checkname($_POST['info']['username'])){
				showmessage(L('admin_already_exists'));
			}
			$info = checkuserinfo($_POST['info']);		
			if(!checkpasswd($info['password'])){
				showmessage(L('pwd_incorrect'));
			}
			$passwordinfo = password($info['password']);
			$info['password'] = $passwordinfo['password'];
			$info['encrypt'] = $passwordinfo['encrypt'];
			$info['lastlogintime'] = time();
			$info['lastloginip'] = get_client_ip();
			$sspid=$_POST['spid'];
			$info['spid'] = implode(",",$sspid);
			$menuid=$_POST['menuid'];//一个数组;
			foreach ($menuid as $v){
				$info['area_id'] = $v;
			}
			$admin_fields = array('username', 'email', 'password', 'encrypt','roleid','realname','spid','status','area_id','lastlogintime','lastloginip');
			foreach ($info as $k=>$value) {
				if (!in_array($k, $admin_fields)){
					unset($info[$k]);
				}
			}
			$this->db->insert($info);
			if($this->db->insert_id()){
				//添加操作日志
				$status = 'add';
				$description = $info;
				$this->system_log($status,json_encode($description));
				showmessage(L('operation_success'),'?m=admin&c=admin_manage');
			}
		} else {
			$roles = $this->role_db->select(array('disabled'=>'0'));
			$swhere =" 1 group by spid";
			$spid_array = $this->spid_db->select($swhere);
			$menu = pc_base::load_sys_class('tree');
			$this->cms_area = pc_base::load_model('cms_area_model');
			$this->op = pc_base::load_app_class('role_op');
			$menu->icon = array('│ ','├─ ','└─ ');
			$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
			$result = $this->cms_area->select();
			foreach ($result as $n=>$t) {
					$result[$n]['cname'] = $t['name'];
					$result[$n]['checked'] = '';
					$result[$n]['level'] = $this->op->get_level($t['id'],$result);
					$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
				}
			$str  = "<tr id='node-\$id' \$parentid_node>
						<td style='padding-left:30px;'>\$spacer<input type='radio' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
					</tr>";
			$menu->init($result);
			$categorys = $menu->get_tree(0, $str);
			
			include $this->admin_tpl('admin_add');
		}
		
	}
	
	/**
	 * 修改管理员
	 */
	public function edit() {
		if(isset($_POST['dosubmit'])) {
			$memberinfo = $info = array();			
			$info = checkuserinfo($_POST['info']);
			$insp=$_POST['infospid'];//一个数组;
			$insp=implode(",", $insp);//一个字符串;
			$info['spid'] = $insp;
			$info['lastlogintime'] = time();
			$info['lastloginip'] = get_client_ip();
			$menuid=$_POST['menuid'];//一个数组;
			foreach ($menuid as $v){
				$info['area_id'] = $v;
			}
			if(isset($info['password']) && !empty($info['password']))
			{
				$this->op->edit_password($info['userid'], $info['password']);
			}
			$userid = $info['userid'];
			$admin_fields = array('username', 'email', 'roleid','realname','spid','status','lastlogintime','lastloginip','area_id');
			foreach ($info as $k=>$value) {
				if (!in_array($k, $admin_fields)){
					unset($info[$k]);
				}
			}
			$this->db->update($info,array('userid'=>$userid));
			//添加操作日志
			$status = 'edit';
			$description = $info;
			$this->system_log($status,json_encode($description));
			showmessage(L('operation_success'),'','','edit');
		} else {					
			$info = $this->db->get_one(array('userid'=>$_GET['userid']));
			$arr = explode(",",$info['spid']);
			extract($info);	
			$roles = $this->role_db->select(array('disabled'=>'0'));
			$spid_array = $this->spid_db->select();	
			$show_header = true;
			$menu = pc_base::load_sys_class('tree');
			$this->cms_area = pc_base::load_model('cms_area_model');
			$this->op = pc_base::load_app_class('role_op');
			$menu->icon = array('│ ','├─ ','└─ ');
			$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
			$result = $this->cms_area->select();
			foreach ($result as $n=>$t) {
					$result[$n]['cname'] = $t['name'];
					$result[$n]['checked'] = '';
					if ($t['id']==$info['area_id']){
						$result[$n]['checked'] = 'checked';
					}
					$result[$n]['level'] = $this->op->get_level($t['id'],$result);
					$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
				}
			$str  = "<tr id='node-\$id' \$parentid_node>
						<td style='padding-left:30px;'>\$spacer<input type='radio' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
					</tr>";
			$menu->init($result);
			$categorys = $menu->get_tree(0, $str);
			include $this->admin_tpl('admin_edit');		
		}
	}
	
	/**
	 * 删除管理员
	 */
	public function delete() {
		$userid = intval($_GET['userid']);
		if($userid == '1') showmessage(L('this_object_not_del'), HTTP_REFERER);
		$this->db->delete(array('userid'=>$userid));
		//添加操作日志
		$status = 'del';
		$description = $userid;
		$this->system_log($status,json_encode($description));
		showmessage(L('admin_cancel_succ'));
	}
	
	/**
	 * 更新管理员状态
	 */
	public function lock(){
		$userid = intval($_GET['userid']);
		$disabled = intval($_GET['disabled']);
		$this->db->update(array('disabled'=>$disabled),array('userid'=>$userid));
		showmessage(L('operation_success'),'?m=admin&c=admin_manage');
	}
	
	/**
	 * 管理员自助修改密码
	 */
	public function public_edit_pwd() {
		$userid = $_SESSION['userid'];
		if(isset($_POST['dosubmit'])) {
			$r = $this->db->get_one(array('userid'=>$userid),'password,encrypt');
			if ( password($_POST['old_password'],$r['encrypt']) !== $r['password'] ) showmessage(L('old_password_wrong'),HTTP_REFERER);
			if(isset($_POST['new_password']) && !empty($_POST['new_password'])) {
				$this->op->edit_password($userid, $_POST['new_password']);
			}
			//添加操作日志
			$status = 'edit';
			$description = $_POST;
			$this->system_log($status,json_encode($description));
			showmessage(L('password_edit_succ_logout'),'?m=admin&c=index&a=public_logout');			
		} else {
			$info = $this->db->get_one(array('userid'=>$userid));
			extract($info);
			include $this->admin_tpl('admin_edit_pwd');			
		}

	}
	/*
	 * 编辑用户信息
	 */
	public function public_edit_info() {
		$userid = $_SESSION['userid'];
		if(isset($_POST['dosubmit'])) {
			$admin_fields = array('email','realname','imageurl','lang');
			$info = array();
			$info = $_POST['info'];
			
			if(trim($info['lang'])=='') $info['lang'] = 'zh-cn';
			if(strpos($info['imageurl'],'http://')!== false){
				$info['imageurl'] = $info['imageurl'];
			}else{
				$info['imageurl'] =TASK_IMG_PATH.$info['imageurl'];
			}
			foreach ($info as $k=>$value) {
				if (!in_array($k, $admin_fields)){
					unset($info[$k]);
				}
			}
			$this->db->update($info,array('userid'=>$userid));
			param::set_cookie('sys_lang', $info['lang'],SYS_TIME+86400*30);
			//添加操作日志
			$status = 'edit';
			$description = $info;
			$this->system_log($status,json_encode($description));
			showmessage(L('operation_success'),HTTP_REFERER);			
		} else {
			$info = $this->db->get_one(array('userid'=>$userid));
			extract($info);
			$lang_dirs = glob(PC_PATH.'languages/*');
			$dir_array = array();
			foreach($lang_dirs as $dirs) {
				$dir_array[] = str_replace(PC_PATH.'languages/','',$dirs);
			}
			include $this->admin_tpl('admin_edit_info');			
		}	
	
	}
	/**
	 * 异步检测用户名
	 */
	function public_checkname_ajx() {
		$username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit(0);
		if ($this->db->get_one(array('username'=>$username),'userid')){
			exit('0');
		}
		exit('1');
	}
	/**
	 * 异步检测密码
	 */
	function public_password_ajx() {
		$userid = $_SESSION['userid'];
		$r = array();
		$r = $this->db->get_one(array('userid'=>$userid),'password,encrypt');
		if ( password($_GET['old_password'],$r['encrypt']) == $r['password'] ) {
			exit('1');
		}
		exit('0');
	}
	/**
	 * 异步检测emial合法性
	 */
	function public_email_ajx() {
		$email = $_GET['email'];
		if ($this->db->get_one(array('email'=>$email),'userid')){
			exit('0');
		}
		exit('1');
	}

	//电子口令卡
	function card() {
		if (pc_base::load_config('system', 'safe_card') != 1) {
			showmessage(L('your_website_opened_the_card_no_password'));
		}
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : showmessage(L('user_id_cannot_be_empty'));
		$data = array();
		if ($data = $this->db->get_one(array('userid'=>$userid), '`card`,`username`')) {
			$pic_url = '';
			if ($data['card']) {
				pc_base::load_app_class('card', 'admin', 0);
				$pic_url = card::get_pic($data['card']);
			}
			$show_header = true;
			include $this->admin_tpl('admin_card');
		} else {
			showmessage(L('users_were_not_found'));
		}
	}
	
	//绑定电子口令卡
	function creat_card() {
		if (pc_base::load_config('system', 'safe_card') != 1) {
			showmessage(L('your_website_opened_the_card_no_password'));
		}
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : showmessage(L('user_id_cannot_be_empty'));
		$data = $card = '';
		if ($data = $this->db->get_one(array('userid'=>$userid), '`card`,`username`')) {
			if (empty($data['card'])) {
				pc_base::load_app_class('card', 'admin', 0);
				$card = card::creat_card();
				if ($this->db->update(array('card'=>$card), array('userid'=>$userid))) {
					showmessage(L('password_card_application_success'), '?m=admin&c=admin_manage&a=card&userid='.$userid);
				} else {
					showmessage(L('a_card_with_a_local_database_please_contact_the_system_administrators'));
				}
			} else {
				showmessage(L('please_lift_the_password_card_binding'),HTTP_REFERER);
			}
		} else {
			showmessage(L('users_were_not_found'));
		}
	}
	
	//解除口令卡绑定
	function remove_card() {
		if (pc_base::load_config('system', 'safe_card') != 1) {
			showmessage(L('your_website_opened_the_card_no_password'));
		}
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : showmessage(L('user_id_cannot_be_empty'));
		$data = $result = '';
		if ($data = $this->db->get_one(array('userid'=>$userid), '`card`,`username`,`userid`')) {
			pc_base::load_app_class('card', 'admin', 0);
			if ($result = card::remove_card($data['card'])) {
					$this->db->update(array('card'=>''), array('userid'=>$userid));
					showmessage(L('the_binding_success'), '?m=admin&c=admin_manage&a=card&userid='.$userid);
			}
		} else {
			showmessage(L('users_were_not_found'));
		}
	}	
}
?>