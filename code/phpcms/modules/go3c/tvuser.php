<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
define('TASK_IMG_PATH','http://127.0.0.1/yinlebao');
class tvuser extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->go3capi_open_game = pc_base::load_model('go3capi_open_game_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	/**
	 * 用户列表
	 *
	 */
	public function init() {
		$this->db   = pc_base::load_model('tv_member_model');
		$user_id 	= trim($_GET['user_id']);
		$uid_filt   = $user_id ? " AND m.user_id LIKE '%$user_id%'" : '';
		$username 	= trim($_GET['username']);
		$name_filt  = $username ? " AND m.username LIKE '%$username%'" : '';
		$field    	= 'm.*, ms.update_time';
		$sql     	= "member m LEFT JOIN member_status ms ON ms.user_id = m.user_id WHERE 1 ".$uid_filt.$name_filt;
		$order  	= 'GROUP BY m.user_id ORDER BY user_id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('tvuser_list');			
	}
	
	/**
	 * Show VIP account list. 
	 */
	public function vip_list() {
		$this->db   = pc_base::load_model('tv_member_model');
		$user_id 	= trim($_GET['user_id']);
		$uid_filt 	= ' AND m.is_vip = 1 ';
		$uid_filt   .= $user_id ? " AND m.user_id LIKE '%$user_id%'" : '';
		$field    	= 'm.*, ms.update_time';
		$sql     	= "member m LEFT JOIN member_status ms ON ms.user_id = m.user_id WHERE 1 ".$uid_filt.$name_filt;
		$order  	= 'GROUP BY m.user_id ORDER BY ';
		if (isset($_GET['field']))
			$order .= $_GET['field'];
		else 
			$order .= 'm.registration_date';
		if (isset($_GET['order']))
			$order .= ' ' . $_GET['order'];
		else 
			$order .= ' DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//die($sql);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('vip_list');
	}

	/**
	 * Multiply lock users.
	 */
	public function multiLock(){
		$this->db   = pc_base::load_model('tv_member_model');
		$users 	= trim($_GET['users']);
		$userArr = explode(',', $users);
		foreach ($userArr as $v) {
			$this->db->update(array('blocked'=>'1'), array('user_id'=>$v));
		}
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}

	/**
	 * Multiply unlock users.
	 */
	public function multiUnlock(){
		$this->db   = pc_base::load_model('tv_member_model');
		$users 	= trim($_GET['users']);
		$userArr = explode(',', $users);
		foreach ($userArr as $v) {
			$this->db->update(array('blocked'=>'0'), array('user_id'=>$v));
		}
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/**
	 * Generate accounts.
	 */
	public function generateAccount() {
		$this->db   = pc_base::load_model('tv_member_model');
		$num = trim($_GET['num']);
		$arr = array();
		for ($i = 0; $i < $num; $i++) {
			$account = $this->getRandom(6);
			$password = $this->getRandom(6);
			$res = $this->db->select(array('user_id' => $account));
			if (count($res)) {
				//the account already exists
				$num += 1;
			} else {
				$data = array(
				'user_id' => $account,
				'password' => strtoupper(md5($password)),
				'username' => $account,
				'sex' => '3',
				'registration_date' => date('Y-m-d H:i:s'),
				'registration_ip' => '192.168.19.1',
				'is_vip' => 1,
				'vip_pwd' => $password
				);
				$this->db->insert($data);
			}
		}
		echo 'success';
	}
	
	/**
	 * Get random.
	 */
	public function getRandom($len) {
		return rand(pow(10,($len-1)), pow(10,$len)-1);
	} 
	
	/**
	 * Export vip accounts to excel file.
	 */
	public function exportVip() {
		$this->db   = pc_base::load_model('tv_member_model');
		$vips = $this->db->select(array('is_vip' => 1));
		include $this->admin_tpl('export_vip');	
	}
	
	/**
	 * 用户活跃度排行
	 *
	 */
	public function active_list() {
		$this->db   = pc_base::load_model('tv_member_status_list_model');
		$field    	= 'count(*) as count, user_id, update_time';
		$sql     	= "member_status_list ";
		$order  	= 'GROUP BY user_id ORDER BY count DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 100;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('tvuser_active_list');			
	}
	
	/**
	 * 视频直播排行
	 *
	 */
	public function channel_list() {
		$this->db   = pc_base::load_model('tv_member_status_list_model');
		$field    	= 'vp.vid, c.channel_name as name, sum(vp.play_count) as count';
		$sql     	= "go3c.video_play_history vp, go3c.channel c where vp.vid = c.channel_id ";
		$order  	= 'GROUP BY vid ORDER BY count DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		//$multipage  = $this->db->pages;
		include $this->admin_tpl('tvuser_video_list');			
	}
	
	/**
	 * 视频点播排行
	 *
	 */
	public function video_list() {
		$this->db   = pc_base::load_model('tv_member_status_list_model');
		$field    	= 'vp.vid, v.name, sum(vp.play_count) as count';
		$sql     	= "go3c.video_play_history vp, go3c.video v where vp.vid = v.vid ";
		$order  	= 'GROUP BY vid ORDER BY count DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		//$multipage  = $this->db->pages;
		include $this->admin_tpl('tvuser_video_list');			
	}
	
	/**
	 * IP锁定
	 *
	 */
	public function lock_list() {
		$this->db   = pc_base::load_model('tv_blocked_ip_model');
		$field    	= '*';
		$sql     	= "blocked_ip ";
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('tvuser_lock_ipuser_list');			
	}
	
	/**
	 * 添加IP
	 *
	 */
	public function ip_lock(){
		$data_array = array(
			'IP' 	=> trim($_GET['ip']),
			'block_date' => date('Y-m-d H:i:s',time()),
		);
		$this->db = pc_base::load_model('tv_blocked_ip_model');	
		$this->db->insert($data_array, true);
		//添加操作日志
		$userid = $_SESSION['userid'];
		$type = 'add_ip';
		$this->go3ccms_changlog($userid,$type,json_encode($data_array));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/**
	 * 删除IP
	 *
	 */
	public function ip_unlock() {
		$id	  = intval($_GET['id']);
		$this->db = pc_base::load_model('tv_blocked_ip_model');	
		$this->db->delete(array('ID'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	
	
	/**
	 * 编辑
	 *
	 */
	public function edit(){
		$user_id 	= trim($_GET['user_id']);
		$password 	= trim($_GET['password']);
		$isvip = isset($_GET['vip']) ? $_GET['vip'] : 0;
		if(!isset($password)){
			showmessage('清输入密码',$_SERVER['HTTP_REFERER'], $ms = 500);
		}
		if ($isvip == 1)
			$data_array = array('password' => strtoupper(md5($password)), 'vip_pwd' => $password);
		else
			$data_array = array('password' => strtoupper(md5($password)));
		$this->db = pc_base::load_model('tv_member_model');	
		$this->db->update($data_array, array('user_id'=>$user_id));
		//添加操作日志
		$type = 'edit_user';
		$this->go3ccms_changlog($user_id,$type,json_encode($data_array));
		if ($isvip == 1)
			showmessage('操作成功','?m=go3c&c=tvuser&a=vip_list', $ms = 500);
		else
			showmessage('操作成功','?m=go3c&c=tvuser&a=init', $ms = 500);
	}
	
	/**
	 * 编辑
	 *
	 */
	public function editform(){
		$user_id 	= trim($_GET['user_id']);
		$username 	= trim($_GET['username']);
		$isvip = isset($_GET['vip']) ? $_GET['vip'] : 0;
		include $this->admin_tpl('tvuser_password');		
	}
	
	/**
	 * 锁定
	 *
	 */
	public function ipuser_lock(){
		$user_id 	= trim($_GET['user_id']);
		$this->db   = pc_base::load_model('tv_member_model');
		$this->db->update(array('blocked'=>'1'), array('user_id'=>$user_id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/**
	 * 解锁
	 *
	 */
	public function ipuser_unlock(){
		$user_id 	= trim($_GET['user_id']);
		$this->db   = pc_base::load_model('tv_member_model');
		$this->db->update(array('blocked'=>'0'), array('user_id'=>$user_id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/**
	 * 用户反馈列表
	 *
	 */
	public function user_feedback(){
		$this->db = pc_base::load_model('tv_issue_report_model');
		$issue_type = trim($_GET['issue_type']);
		if($issue_type=='链接异常'){
			$issue_type ? $where.= " `issue_type` LIKE '%播放故障%'" : '';
			$where.= " AND `description` LIKE '%获取防盗失败%'";
		}else {
			$issue_type ? $where.= " `issue_type` LIKE '%$issue_type%'" : '';
		}		
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';

		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';

		$data  = $this->db->listinfo($where, $order = '`time_added` DESC', $page, $perpage);
		//echo '<pre>'; print_r($data);
		$pages = $this->db->pages;
		include $this->admin_tpl('tvuser_user_feedback');
	}
	
	
		/**
	 * 用户反馈列表 add crash
	 *
	 */
	public function user_feedback_test(){
		$this->db = pc_base::load_model('test_report_model');
		$issue_type = trim($_GET['issue_type']);
		if($issue_type=='链接异常'){
			$issue_type ? $where.= " `issue_type` LIKE '%播放故障%'" : '';
			$where.= " AND `description` LIKE '%获取防盗失败%'";
		}else {
			$issue_type ? $where.= " `issue_type` LIKE '%$issue_type%'" : '';
		}		
		$rmac = trim($_GET['rmac']);
		$where .=  $where && $rmac ? ' AND ' : ''; 
		$where .= $rmac ? " rmac LIKE '%$rmac%'" : "";
		$cid = trim($_GET['cid']);
		$where .=  $where && $cid ? ' AND ' : ''; 
		$where .= $cid ? " customerid = '$cid'" : "";
		$clientid = trim($_GET['clientid']);
		$where .=  $where && $clientid ? ' AND ' : ''; 
		$where .= $clientid ? " clientid = '$clientid'" : "";
		//echo $where;die();
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';
		$data  = $this->db->listinfo($where, $order = '`time_added` DESC', $page, $perpage);
		//echo '<pre>'; print_r($data);echo '</pre>';die();
		$pages = $this->db->pages;
		
		$fd  = '*';
		$wh = "test_report WHERE customerid != '' group by customerid ";
		$cids = $this->db->mylistinfo($fd, $wh, $order = 'DESC', $page, $perpage);
		//var_dump($cids);die();
		$wh = "test_report WHERE clientid != '' group by clientid ";
		$clientids = $this->db->mylistinfo($fd, $wh, $order = 'DESC', $page, $perpage);
		
		include $this->admin_tpl('tvuser_user_feedback_test');
	}
	
	public function showreportlog() {
		$this->db = pc_base::load_model('test_report_model');
		$id = trim($_GET['id']);
		$where = "id = '$id'";
		$data = $this->db->select(array('id'=>$id));
		include $this->admin_tpl('tvuser_feedback_log');
	}
	
/**
	 * bigtv客户端下载统计
	 */
	public function bigtvdown() {
		$this->db   = pc_base::load_model('cms_down_model');
		$field    	= 'count(*) as count, type, updatetime,id';
		$sql     	= "v9_down ";
		$order  	= 'GROUP BY type ORDER BY updatetime DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 10;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('tvuser_bigtvdown');			
	}
	/**
	 * bigtv客户端下载详细
	 */
	public function down_list(){
		$type = $_GET['type'];
		$this->db   = pc_base::load_model('cms_down_model');
		$where = "type='$type'";
		$page  = $_GET['page'] ? $_GET['page'] : '1';

		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';

		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);

		$pages = $this->db->pages;
		include $this->admin_tpl('tvuser_down_list');
	}
	
/**
	 * ip纠错列表
	 */
	public function recordip(){
		$starttime = $_GET['starttime'];
		$endtime = $_GET['endtime'];
		$this->db   = pc_base::load_model('cms_recordip_model');
		$where = "1 ";
		$starttime   != '' ? $where.= " AND `updatetime` >= UNIX_TIMESTAMP('$starttime')" : '';
		$endtime   != '' ? $where.= " AND `updatetime` <= UNIX_TIMESTAMP('$endtime')" : '';

		$page  = $_GET['page'] ? $_GET['page'] : '1';

		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';

		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);

		$pages = $this->db->pages;
		include $this->admin_tpl('tvuser_recordip');
	}

	/**
	 * cms操作日志
	 */
	public function recordcms(){
		$starttime = $_GET['starttime'];
		$endtime = $_GET['endtime'];
		$username = $_GET['username'];
		$this->db   = pc_base::load_model('cms_cmslog_model');
		$where = "1 ";
		$starttime   != '' ? $where.= " AND `createtime` >= UNIX_TIMESTAMP('$starttime')" : '';
		$endtime   != '' ? $where.= " AND `createtime` <= UNIX_TIMESTAMP('$endtime')" : '';
		$username   != '' ? $where.= " AND `username` = '$username'" : '';

		$page  = $_GET['page'] ? $_GET['page'] : '1';

		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';

		$data  = $this->db->listinfo($where, $order = '`logid` DESC', $page, $perpage);

		$pages = $this->db->pages;
		include $this->admin_tpl('tvuser_recordcms');
	}
/**
	 * 链接到nagios监控软件
	 */
	public function mage(){
		//header("location:$url");	
		//window.open("$url",'_blank','');
		//echo "<script language=\"javascript\">window.open('$url','_blank','')</script>";
	
		include $this->admin_tpl('nagios');
	}

	public function mak_nagios(){
		$type = $_GET['type'];
		//$url = GO3C_nagios;
		if(!empty($type)){
			if($type == 'nagios1'){
			$url = GO3C_nagios.'cgi-bin/status.cgi?hostgroup=all&style=grid';
			header("location:$url");	
		}elseif($type == 'nagios2'){
			$url = GO3C_nagios.'cgi-bin/status.cgi?host=Upgrade-server';
			header("location:$url");
		}elseif($type == 'nagios3'){
			$url = GO3C_nagios.'cgi-bin/status.cgi?host=API-server1';
			header("location:$url");
		}elseif($type == 'nagios4'){
			$url = GO3C_nagios.'cgi-bin/status.cgi?host=Web-server';
			header("location:$url");
		}elseif($type == 'nagios5'){
			$url = GO3C_nagios.'cgi-bin/status.cgi?host=DB-server';
			header("location:$url");
		}elseif($type == 'nagios6'){
			$url = GO3C_nagios.'cgi-bin/status.cgi?host=Image-server';
			header("location:$url");
		}
		}else{
			showmessage('数据异常!',base64_decode($_GET['goback']));
		}
	}
	/**
	 * 安装卸载统计
	 */
	public function install(){
		$device_type  = $_GET['device_type'];
		$start  = $_GET['starttime'];
		$end  = $_GET['endtime'];
		$end .= ' 24:00:00';
		$end = '2014-04-02 00:00:00';
		$this->analysis_install = pc_base::load_model('cms_analysis_install_model');
		
		$where = " `device_type` = 401 ";
		$device_type   != '' ? $where.= " AND `device_type` = '$device_type'" : '';
		if ($start   != '') {
			//$starttime = $start;
			$starttime = '2014-03-27 00:00:00';
			$where.= " AND `operation_time` >= '$start'";
		} else {
			//$weekago = date('Y-m-d H:i:s', strtotime('-3 week'));
			$weekago = '2014-03-27 00:00:00';
			$where.= " AND `operation_time` >= '" . $weekago . "'";
		}
		if ($end != ' 24:00:00') {
			$endtime = $end;
			$where.= " AND `operation_time` <= '$end'";
		}
		//$start   != '' ? $where.= " AND `operation_time` >= '$start'" : $where.= " AND `operation_time` like '%2013-12-04%'";
		//$end   != '' ? $where.= " AND `operation_time` <= '$end'" : '';
		$field    = isset($_GET['field']) ? $_GET['field'] : 'operation_time';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'ASC';
		//$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '50';
		if (isset($_GET['perpage']) && intval($_GET['perpage']) > 0) {
			$page  = $_GET['page'] ? $_GET['page'] : '1';
			$perpage = intval($_GET['perpage']);
			$data  = $this->analysis_install->listinfo($where, $order = "$field $order", $page, $perpage);
		} else {
			$data  = $this->analysis_install->listinfo_analysis($where, $order = "$field $order");
		}
		$dataforchart = $this->regroupData($data);
		$dataforchart = json_encode($dataforchart);
		
		include $this->admin_tpl('install_list');
	}
	
/**
	 * 用户统计
	 */
public function cuser(){
		$device_type  = $_GET['device_type'];
		$user_id  = $_GET['user_id'];
		$start  = $_GET['starttime'];
		$end  = $_GET['endtime'];
		$end .= ' 24:00:00';
		$end = '2100-04-02 00:00:00';
		$this->analysis_user = pc_base::load_model('cms_analysis_user_model');
		$where = " 1 ";
		$user_id   != '' ? $where.= " AND `user_id` = '$user_id'" : '';
		$device_type   != '' ? $where.= " AND `device_type` = '$device_type'" : '';
		if ($start   != '') {
			//$starttime = $start;
			$starttime = '2014-03-27 00:00:00';
			$where.= " AND `operation_time` >= '$start'";
		} else {
			//$weekago = date('Y-m-d', strtotime('-1 week'));
			$starttime = '2000-03-27 00:00:00';
			$where.= " AND `operation_time` >= '" . $weekago . "'";
		}
		if ($end != ' 24:00:00') {
			$endtime = $end;
			$where.= " AND `operation_time` <= '$end'";
		}
		//$start   != '' ? $where.= " AND `start` >= '$operation_time'" : '';
		//$end   != ' 24:00:00' ? $where.= " AND `operation_time` <= '$end'" : '';
		
		$field    = isset($_GET['field']) ? $_GET['field'] : 'operation_time';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'ASC';
		
		//$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '50';
		if (isset($_GET['perpage']) && intval($_GET['perpage']) > 0) {
			$page  = $_GET['page'] ? $_GET['page'] : '1';
			$perpage = intval($_GET['perpage']);
			$data  = $this->analysis_user->listinfo($where, $order = "$field $order", $page, $perpage);
			//var_dump($data);die;
		} else {
			$data  = $this->analysis_user->listinfo_analysis($where, $order = "$field $order");

		}
		
		$dataforchart = $this->regroupData($data);

		$dataforchart = json_encode($dataforchart);
		//echo $dataforchart;
		
		include $this->admin_tpl('cuser_list');
	}
/**
	 * 点击量统计
	 */
	public function click(){
		$device_type  = $_GET['device_type'];
		$user_id  = $_GET['user_id'];
		$start  = $_GET['starttime'];
		$end  = $_GET['endtime'];
		$end .= ' 24:00:00';
		$end = '2014-04-05 00:00:00';
		$this->analysis_click = pc_base::load_model('cms_analysis_click_model');
		$where = " 1 ";
		$user_id   != '' ? $where.= " AND `user_id` = '$user_id'" : '';
		$device_type   != '' ? $where.= " AND `device_type` = '$device_type'" : '';
		if ($start   != '') {
			//$starttime = $start;
			$starttime = '2014-03-30 00:00:00';
			$where.= " AND `operation_time` >= '$start'";
		} else {
			//$weekago = date('Y-m-d', strtotime('-1 week'));
			$starttime = '2014-03-30 00:00:00';
			$where.= " AND `operation_time` >= '" . $weekago . "'";
		}
		if ($end != ' 24:00:00') {
			$endtime = $end;
			$where.= " AND `operation_time` <= '$end'";
		}
		//$start   != '' ? $where.= " AND `start` >= '$operation_time'" : '';
		//$end   != '' ? $where.= " AND `operation_time` <= '$end'" : '';
		$field    = isset($_GET['field']) ? $_GET['field'] : 'operation_time';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'ASC';

		//$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '50';
		if (isset($_GET['perpage']) && intval($_GET['perpage']) > 0) {
			$page  = $_GET['page'] ? $_GET['page'] : '1';
			$perpage = intval($_GET['perpage']);
			$data  = $this->analysis_click->listinfo($where, $order = "$field $order", $page, $perpage);
		} else {
			$data  = $this->analysis_click->listinfo_analysis($where, $order = "$field $order");
		}
		
		$dataforchart = $this->regroupData($data);
		$dataforchart = json_encode($dataforchart);
		
		include $this->admin_tpl('click_list');
	}
	
	/**
	 * 重组数据为图形化插件可用的多维数组
	 * 
	 */
	private function regroupData($arr) {
		$result = array();
		foreach ($arr as $k => $v) {
			$datestr = substr($v['operation_time'], 0, 10);
			switch ($v['device_type']) {
				case '101' : 
					$result[$datestr]['appletv']++;
					break;
				case '102' : 
					$result[$datestr]['atv']++;
					break;
				case '103' : 
					$result[$datestr]['ltv']++;
					break;
				case '201' : 
					$result[$datestr]['ipad']++;
					break;
				case '202' : 
					$result[$datestr]['apad']++;
					break;
				case '203' : 
					$result[$datestr]['winpad']++;
					break;
				case '301' : 
					$result[$datestr]['iphone']++;
					break;
				case '302' : 
					$result[$datestr]['aphone']++;
					break;
				case '303' : 
					$result[$datestr]['win8phone']++;
					break;
				case '401' :
					$result[$datestr]['pcweb']++;
					break;
				case '402' : 
					$result[$datestr]['pcclient']++;
					break;
				case '403' : 
					$result[$datestr]['win8pc']++;
					break;
			}
		}
		return $result;
	}
	/**
	 * 用户反馈异常播放链接
	 *
	 */
	public function play_error(){
		$this->db = pc_base::load_model('tv_issue_report_model');
		$this->video_db = pc_base::load_model('cms_video_model');
		$issue_type = trim($_GET['issue_type']);
		$term_name = trim($_GET['term_name']);
		$where = " WHERE isoff='1' and `issue_type` LIKE '%播放故障%'";
		if($term_name){
			$where.= " AND `term_name` = '$term_name'";
		}
		$field    	= '*';
		$sql     	= "issue_report ".$where;
		$order  	= ' ORDER BY time_added DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage);
		foreach ($data as $key=>$v){
			$arr = explode("|",$v['description']);
			$vid= $arr['1'];
			$video = $this->video_db->get_one(array('asset_id'=>$vid));
			$data[$key]['video_name'] = $video['title'];
		}
		$multipage  = $this->db->pages;
		//获取终端分类
		$aterm = $this->db->select('1 group by term_name', '*', '', 'time_added DESC');
		include $this->admin_tpl('play_error');
	}
	//下线该链接
	public function play_off(){
		$id = trim($_GET['id']);
		$this->db = pc_base::load_model('tv_issue_report_model');
		$this->video_play_info = pc_base::load_model('tv_video_play_info_model');
		$this->video_model = pc_base::load_model('tv_video_model');
		$this->video_image = pc_base::load_model('tv_video_image_model');
		$this->video_actors = pc_base::load_model('tv_video_actors_model');
		$this->video_play_control = pc_base::load_model('tv_video_play_control_model');
		$this->video_rating_list = pc_base::load_model('tv_video_rating_list_model');
		$this->video_tags = pc_base::load_model('tv_video_tags_model');
		$this->video_db = pc_base::load_model('cms_video_model');
		$this->video_short_list = pc_base::load_model('tv_video_short_list_model');
		$this->all_episode_list = pc_base::load_model('tv_all_episode_list_model');
		$video = $this->db->get_one(array('id'=>$id));
		if(empty($video)){
			showmessage('数据异常!',base64_decode($_GET['goback']));
		}
		$arr = explode("|",$video['description']);
		$vid= $arr['1'];
		$url= $arr['3'];
		$phpvideo = $this->video_db->get_one(array('asset_id'=>$vid));
		if(empty($phpvideo)){
			showmessage('不存在此视频!',base64_decode($_GET['goback']));
		}
		//查询在线的该电视剧链接数
		$play = $this->video_play_info->select(array('vid'=>$vid));
		$num = count($play);
		if($num>1){   //多连接删除链接
			$this->video_play_info->delete(array('vid'=>$vid,'play_url'=>$url));
			$this->db->update(array('isoff'=>2), array('id'=>$id));
			showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
		}else{//单个链接下线视频
			$this->video_model->delete(array('vid'=>$vid));
			$this->video_image->delete(array('vid'=>$vid));
			$this->video_actors->delete(array('vid'=>$vid));
			$this->video_play_control->delete(array('vid'=>$vid));
			$this->video_rating_list->delete(array('vid'=>$vid));
			$this->video_tags->delete(array('vid'=>$vid));
			$this->video_short_list->delete(array('vid'=>$vid));
			$this->all_episode_list->delete(array('vid'=>$vid));
			$this->db->update(array('isoff'=>2), array('id'=>$id));
			showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
		}		
	}
	//清除数据
	public function droup_playinfo(){
		$term_name = trim($_GET['term_name']);
		$this->issue_report = pc_base::load_model('tv_issue_report_model');
		if(empty($term_name)){
			$where = "isoff!='3'";
		}else{
			$where = "term_name = '$term_name' and isoff!='3'";
		}
		$issue = $this->issue_report->select($where, '*', '', 'id ASC');
		foreach ($issue as $v){
			$this->issue_report->update(array('isoff'=>3),array('id'=>$v['id']));
		}
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	//展示个人收藏EPG
	public function MemEpg(){
		$user_id = $_GET['user_id'];
		$this->epg_member = pc_base::load_model('tv_epg_member_model');
		$where = "1 ";
		$user_id   != '' ? $where.= " AND `user_id` = '$user_id'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->epg_member->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->epg_member->pages;
		include $this->admin_tpl('tvuser_MemEpg');
	}
	//删除个人EPG
	public function deleteepg(){
		$this->epg_member = pc_base::load_model('tv_epg_member_model');
		$id = $_GET['id'];
		$this->epg_member->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	
	//乐看上传视频管理
	public function upvideolist(){
		$this->video_up = pc_base::load_model('cms_video_up_model');
		$userid = $_GET['userid'];
		$where = "1 ";
		$userid   != '' ? $where.= " AND `userid` = '$userid'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->video_up->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->video_up->pages;
		include $this->admin_tpl('video_up_list');
	}
	//编辑乐看上传视频
	public function edit_upvideo(){
		$id = $_GET['id'];
		$this->video_up = pc_base::load_model('cms_video_up_model');
		$limitInfo = $this->video_up->get_one(array('id'=>$id));
		include $this->admin_tpl('video_up_edit');
	}
	public function edit_upvideodo(){
		$id = trim($_POST['id']);
		$this->video_up = pc_base::load_model('cms_video_up_model');
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$imageurl = trim($_POST['ad_imgUrl']);
		$catid = trim($_POST['catid']);
		$tagid = trim($_POST['tagid']);
		
		$data = array(
				'title' 	=> $title,
				'content' 	=> $content,
				'imageurl' 	=> $imageurl
		);
		$this->video_up->update($data,array('id'=>$id));
		$this->video_up->update(array('status'=>1),array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	//删除乐看上传的视频
	public function deleteupvideo(){
		$id = $_GET['id'];
		$this->video_up = pc_base::load_model('cms_video_up_model');
		$this->video_up->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	//审核上线乐看视频
	public function online_upvideo(){
		$id = $_GET['id'];
		$this->video_up = pc_base::load_model('cms_video_up_model');
		$this->video_message = pc_base::load_model('cms_video_message_model');
		$this->video_up->update(array('status'=>2),array('id'=>$id));
		//审核通过发送站内信
		$upvideo = $this->video_up->get_one(array('id'=>$id));
		$content='好消息!视频'.$upvideo['title'].'审核通过了';
		$message = array(
				'userid' 	=> $upvideo['userid'],
				'vid' 	=> $upvideo['id'],
				'title' 	=> $upvideo['title'],
				'content' 	=> $content,
				'type' 	=> '2',
				'status' 	=> '1',
				'm_time' 	=> time(),
		);
		$this->video_message->insert($message);
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	//拒绝通过上线乐看视频
	public function Refuse_upvideo(){
		$id = $_GET['id'];
		$this->video_up = pc_base::load_model('cms_video_up_model');
		$this->video_up->update(array('status'=>3),array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	//下线乐看视频
	public function off_upvideo(){
		$id = $_GET['id'];
		$this->video_up = pc_base::load_model('cms_video_up_model');
		$this->video_up->update(array('status'=>1),array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	
	//站内信列表
	public function message(){
		$this->video_message = pc_base::load_model('cms_video_message_model');
		$type = $_GET['type'];
		$title = $_GET['title'];
		$userid = $_GET['userid'];
		$where = "1 ";
		$type   != '' ? $where.= " AND `type` = '$type'" : '';
		$title   != '' ? $where.= " AND `title` = '$title'" : '';
		$userid   != '' ? $where.= " AND `userid` = '$userid'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->video_message->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->video_message->pages;
		include $this->admin_tpl('video_message_list');
	}
	//发送站内信
	public function addmessage(){
		include $this->admin_tpl('video_messageadd');
	}
	public function addmessagedo(){
		$this->video_message = pc_base::load_model('cms_video_message_model');
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$userid = trim($_POST['userid']);
		$type = trim($_POST['type']);
		if($type==1){
			//查询活跃的用户(最近3个月登陆用户)
			$this->tv_member   = pc_base::load_model('tv_member_model');
			$awhere = " unix_timestamp(last_login)>unix_timestamp(now())-7776000 ";
			$list = $this->tv_member->select($awhere);
			foreach ($list as $v){
				$userid = $v['user_id'];
				$message = array(
						'userid' 	=> $userid,
						'title' 	=> $title,
						'content' 	=> $content,
						'type' 		=> $type,
						'status' 	=> '1',
						'm_time' 	=> time(),
				);
				$this->video_message->insert($message);
			}
		}else{
			$message = array(
					'userid' 	=> $userid,
					'title' 	=> $title,
					'content' 	=> $content,
					'type' 		=> $type,
					'status' 	=> '1',
					'm_time' 	=> time(),
			);
			$this->video_message->insert($message);
		}
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	//删除站内信
	public function deletemes(){
		$id = $_GET['id'];
		$this->video_message = pc_base::load_model('cms_video_message_model');
		$this->video_message->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 300);
	}
	public function importlog(){
		$this->app_onlinelog = pc_base::load_model('app_onlinelog_model');
		$app_id = $_GET['app_id'];
		$where = "packagename is not NULL ";
		$app_id   != '' ? $where.= " AND `app_id` = '$app_id'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->app_onlinelog->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->app_onlinelog->pages;
		include $this->admin_tpl('importlog_list');
	}
	//云游戏开通记录
	public function opengame(){
		$userid = $_GET['userid'];
		$cardid = $_GET['cardid'];
		$mac_wire = $_GET['mac_wire'];
		$where = "1 ";
		$userid   != '' ? $where.= " AND `userid` = '$userid'" : '';
		$cardid   != '' ? $where.= " AND `cardid` = '$cardid'" : '';
		$mac_wire   != '' ? $where.= " AND `mac_wire` = '$mac_wire'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->go3capi_open_game->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->go3capi_open_game->pages;
		include $this->admin_tpl('opengame_list');
	}
	//删除开通记录
	public function delete_open(){
		$id = $_GET['id'];
		$this->go3capi_open_game->delete(array('id'=>$id));
		showmessage('操作成功','index.php?m=go3c&c=tvuser&a=opengame');
	}
	//用户统计明细清单
	public function statdetail() {
		$this->go3capi_guid_user = pc_base::load_model('go3capi_guid_user_model');
		$createtime 	= $_GET['createtime'];
		$uid_filt   = $createtime ? " AND m.createtime >= unix_timestamp('$createtime')" : '';
		$endtime 	= $_GET['endtime'];
		$name_filt  = $endtime ? " AND m.createtime <= unix_timestamp('$endtime')" : '';
		$field    	= 'm.*';
		$sql     	= "auth_guid_user m WHERE 1 ".$uid_filt.$name_filt;
		$order  	= 'ORDER BY m.id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->go3capi_guid_user->mynum($sql);
		$totalpage	= $this->go3capi_guid_user->mytotalpage($sql, $perpage);
		$data 		= $this->go3capi_guid_user->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->go3capi_guid_user->pages;
		include $this->admin_tpl('statdetail_list');			
	}
	//用户浏览明细清单
	public function bedetail() {
		$this->go3capi_log_module = pc_base::load_model('go3capi_log_module_model');
		$createtime 	= $_GET['createtime'];
		$uid_filt   = $createtime ? " AND m.starttime >= unix_timestamp('$createtime')" : '';
		$endtime 	= $_GET['endtime'];
		$name_filt  = $endtime ? " AND m.starttime <= unix_timestamp('$endtime')" : '';
		$field    	= 'm.*';
		$sql     	= "auth_log_module m WHERE 1 ".$uid_filt.$name_filt;
		$order  	= 'ORDER BY m.id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->go3capi_log_module->mynum($sql);
		$totalpage	= $this->go3capi_log_module->mytotalpage($sql, $perpage);
		$data 		= $this->go3capi_log_module->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->go3capi_log_module->pages;
		include $this->admin_tpl('bedetail_list');		
	}
	//获取自定义报表获取的数据
	public function custom_data(){
		$report_id = $_POST['report_id'];
		$this->go3capi_report_format = pc_base::load_model('go3capi_report_format_model');
		$where = "report_id = '$report_id'";
		$thc = $this->go3capi_report_format->select($where, '*', '', 'col ASC');
		echo json_encode(array('message' =>$thc,'status'=>'success'));
	}
	//查询字典表获取获取可筛选的表名
	public function custom_datafield(){
		$this->go3capi_log_field = pc_base::load_model('go3capi_log_field_model');
		$where = "1 group by tablen";
		$thc = $this->go3capi_log_field->select($where, '*', '', 'id ASC');
		echo json_encode(array('message' =>$thc,'status'=>'success'));
	}
	//根据数据表查询字典表获取获取可筛选的字段
	public function custom_datafieldlist(){
		$tablen = $_POST['tablen'];
		$this->go3capi_log_field = pc_base::load_model('go3capi_log_field_model');
		$where = "tablen = '$tablen'";
		$thc = $this->go3capi_log_field->select($where, '*', '', 'id ASC');
		echo json_encode(array('message' =>$thc,'status'=>'success'));
	}
	//保存所有设定的自定义表头数据
	public function custom_datasavethead(){
		$data = $_POST['data'];
		$report_id = $_POST['report_id'];
		$this->go3capi_report_format = pc_base::load_model('go3capi_report_format_model');
		$this->go3capi_report_format->delete(array('report_id'=>$report_id,'position'=>'thead'));
		foreach($data as $v){
			$da = array(
				'width' 	=> $v['width'],
				'position' 	=> $v['position'],
				'report_id' => $v['report_id'],
				'col' 		=> $v['col'],
				'font' 		=> $v['font'],
				'align' 	=> $v['align'],
				'font_color'=> $v['font_color'],
				'font_size' => $v['font_size'],
				'font_weight' => $v['font_weight'],
				'title' 	=> $v['title']
			);
			$insertId =$this->go3capi_report_format->insert($da);
		}
		if($insertId){
			echo json_encode(array('message' =>"添加成功表头!",'status'=>'success'));
		}else{
			echo json_encode(array('message' =>"添加失败!",'status'=>'error'));
		}
	}
	//保存所有设定的自定义表体数据
	public function custom_datasavetbody(){
		$data = $_POST['data'];
		$report_id = $_POST['report_id'];
		$this->go3capi_report_format = pc_base::load_model('go3capi_report_format_model');
		$this->go3capi_report_format->delete(array('report_id'=>$report_id,'position'=>'tbody'));
		foreach($data as $v){
			$da = array(
				'col' 		=> $v['col'],
				'font' 		=> $v['font'],
				'align' 	=> $v['align'],
				'font_color'=> $v['font_color'],
				'font_size' => $v['font_size'],
				'position' 	=> $v['position'],
				'report_id' => $v['report_id'],
				'orderable' => $v['orderable'],
				'font_weight' => $v['font_weight'],
				'title' 	=> $v['title'],
				'dataSource'=> $v['dataSource'],
				'dataField' => $v['dataField'],
				'width' 	=> $v['width'],
				'filter' 	=> $v['filter'],
				'status' 	=> $v['status'],
				'data_type' => $v['data_type']
			);
			$insertId =$this->go3capi_report_format->insert($da);
		}
		if($insertId){
			echo json_encode(array('message' =>"添加成功表体!",'status'=>'success'));
		}else{
			echo json_encode(array('message' =>"添加失败!",'status'=>'error'));
		}
	}
	//删除指定的设置的自定义数据
	public function custom_datadel(){
		$id = $_POST['id'];
		$this->go3capi_report_format = pc_base::load_model('go3capi_report_format_model');
		$this->go3capi_report_format->delete(array('id'=>$id));
		echo json_encode(array('message' =>"删除成功!",'status'=>'success'));
	}
	//查询根据设置的自定义表头的表头
	public function custom_dataselthead(){
		$this->go3capi_report_format = pc_base::load_model('go3capi_report_format_model');
		$report_id = $_POST['report_id'];
		$where = "report_id = '$report_id' and position= 'thead'";
		$thc = $this->go3capi_report_format->select($where, '*', '', 'col ASC');
		echo json_encode(array('message' =>$thc,'status'=>'success'));
	}
	//查询根据设置的自定义表单的表头
	public function custom_dataseltbody(){
		$this->go3capi_report_format = pc_base::load_model('go3capi_report_format_model');
		$report_id = $_POST['report_id'];
		$where = "report_id = '$report_id' and position= 'tbody'";
		$thc = $this->go3capi_report_format->select($where, '*', '', 'col ASC');
		//$tt = json_encode(array('message' =>$thc,'status'=>'success'));
		//var_dump($tt);
		echo json_encode(array('message' =>$thc,'status'=>'success'));
	}
	//根据自定义设置查询具体表的数据
	public function custom_datasel(){
		$datatablename = $_POST['id'];
		$datatablename = 'auth_guid_user';
		$this->go3capi_operating_receipt = pc_base::load_model('go3capi_operating_receipt_model');
		$this->go3capi_log_install  = pc_base::load_model('go3capi_log_install_model');
		$this->go3capi_log_module   = pc_base::load_model('go3capi_log_module_model');
		$this->go3capi_lessonx  = pc_base::load_model('go3capi_lessonx_model');
		$this->go3capi_log_livebox  = pc_base::load_model('go3capi_log_livebox_model');
		$this->go3capi_log_demandbox= pc_base::load_model('go3capi_log_demandbox_model');
		$this->go3capi_log_usercount = pc_base::load_model('go3capi_log_usercount_model');
		$this->go3capi_log_livechcount = pc_base::load_model('go3capi_log_livechcount_model');
		$this->go3capi_log_dbchcount = pc_base::load_model('go3capi_log_dbchcount_model');
		$this->go3capi_log_play 	= pc_base::load_model('go3capi_log_play_model');
		$$this->go3capi_guid_user   = pc_base::load_model('go3capi_guid_user_model');
		if($datatablename=='auth_log_install'){
			$where = "1";
			$thc = $this->go3capi_log_install->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_log_module'){
			$where = "1";
			$thc = $this->go3capi_log_module->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_lessonx'){
			$where = "1";
			$thc = $this->go3capi_lessonx->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_log_livebox'){
			$where = "1";
			$thc = $this->go3capi_log_livebox->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_log_demandbox'){
			$where='1';
			$thc = $this->go3capi_log_demandbox->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_log_usercount'){
			$where='1';
			$thc = $this->go3capi_log_usercount->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_log_livechcount'){
			$where='1';
			$thc = $this->go3capi_log_livechcount->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_log_dbchcount'){
			$where='1';
			$thc = $this->go3capi_log_dbchcount->select($where, '*', '', 'id ASC');
		}elseif($datatablename=='auth_log_play'){
			$where = "1";
			$thc = $this->go3capi_log_play->select($where, '*,from_unixtime(createtime) as createtime', '', 'id ASC');
		}elseif($datatablename=='auth_guid_user'){
			$where = "1";
			$thc = $this->go3capi_guid_user->select($where, '*', '', 'id ASC');
		}else{
			$where = "1";
			$thc = $this->go3capi_operating_receipt->select($where, '*', '', 'id ASC');
		}
		echo json_encode(array('message' =>$thc,'status'=>'success'));
	}
	//根据自定义设置查询具体表的数据
	public function custom_dataselone(){
		$this->go3capi_operating_receipt = pc_base::load_model('go3capi_operating_receipt_model');
		$thc = $this->go3capi_operating_receipt->get_one(array('id'=>1));
		echo json_encode($thc);
	}
	//用户绑定统计
	public function useridbild(){
		$this->guid_user_log = pc_base::load_model('go3capi_guid_user_log_model');
		$createtime = $_GET['createtime'];
		$createtime1 = $_GET['createtime1'];
		$endtime = $_GET['endtime'];
		$endtime2 = $_GET['endtime2'];
		$cardid = $_GET['cardid'];
		$mac_wire = $_GET['mac_wire'];
		$bild = $_GET['bild'];
		$boxsn = $_GET['boxsn'];
		$version = $_GET['version'];

		$where = "1 ";
		if(!empty($createtime)&&!empty($createtime1)){
			$where.="and createtime >=UNIX_TIMESTAMP('$createtime') and createtime <=UNIX_TIMESTAMP('$createtime1')";
		}
		if(!empty($endtime)&&!empty($endtime2)){
			$where.=" and endtime >=UNIX_TIMESTAMP('$endtime') and endtime <=UNIX_TIMESTAMP('$endtime2')";
		}
		if(!empty($cardid)){
			$where.=" and cardid ='$cardid' ";
		}
		if(!empty($mac_wire)){
			$where.=" and mac_wire ='$mac_wire' ";
		}
		if(!empty($bild)){
			$where.=" and bild ='$bild' ";
		}
		if(!empty($boxsn)){
			$where.=" and boxsn ='$boxsn' ";
		}
		if(!empty($version)){
			$where.=" and version ='$version' ";
		}
		$field    	= '*';
		$sql     	= "auth_guid_user_log WHERE ".$where;
		$order  	= 'ORDER BY id desc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->guid_user_log->mynum($sql);
		$totalpage	= $this->guid_user_log->mytotalpage($sql, $perpage);
		$data 		= $this->guid_user_log->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->guid_user_log->pages;
		include $this->admin_tpl('shop_useridbild');
	}
	
	//用户收视明细统计
	public function viewbild() {
		$this->go3capi_log_play = pc_base::load_model('go3capi_log_play_model');
		$starttime = $_GET['starttime'];
		$starttime1 = $_GET['starttime1'];
		$category = $_GET['category'];
		$where = "1 ";
		if($category!=''){
			$where.="and category = '$category' ";
		}
		if(!empty($starttime)){
			$where.="and starttime >=UNIX_TIMESTAMP('$starttime') ";
		}
		if(!empty($starttime1)){
			$where.="and starttime <=UNIX_TIMESTAMP('$starttime1')";
		}
		$field    	= '*';
		$sql     	= "auth_log_play WHERE ".$where;
		$order  	= 'ORDER BY id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->go3capi_log_play->mynum($sql);
		$totalpage	= $this->go3capi_log_play->mytotalpage($sql, $perpage);
		$data 		= $this->go3capi_log_play->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$numv = 0;$numl = 0;$nume = 0;$numts = 0;
		$thc = $this->go3capi_log_play->select($where, '*', '', 'id ASC');
		foreach($thc as $v){
			if($v['category']=='vod'){
				$numv=$numv+1;
			}elseif($v['category']=='live'){
				$numl=$numl+1;
			}elseif($v['category']=='epg'){
				$nume=$nume+1;
			}else{
				$numts=$numts+1;
			}
		}
		$multipage  = $this->go3capi_log_play->pages;
		include $this->admin_tpl('shop_viewbild');
	}
	//课程配置
	public function show_lesson(){
		$this->lesson = pc_base::load_model('db_lesson_model');
		$this->plan = pc_base::load_model('db_plan_model');
		$plan = $_GET['plan'];
		

		$name=$_GET['name'];

		$title = $_GET['title'];
		$where = "1 ";
		if(!empty($plan)){
			$where.=" and p.name ='$plan' ";
		}
		if(!empty($title)){
			$where.=" and title ='$title' ";
		}
		
		$plan_data = $this->plan->select('1', '*', '', 'id ASC');


		$field    	= 'a.*,p.name';
		$sql     	= "lesson as a left join plan as p on p.id=a.plan WHERE ".$where;
		$order  	= 'ORDER BY a.id desc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->lesson->mynum($sql);
		$totalpage	= $this->lesson->mytotalpage($sql, $perpage);
		$data 		= $this->lesson->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->lesson->pages;
		include $this->admin_tpl('show_lesson');
	}
//到达编辑课程页面

	public function lesson_edit(){
		$id = $_GET['id'];
		$this->lesson = pc_base::load_model('db_lesson_model');
		$this->plan = pc_base::load_model('db_plan_model');
		$plan_data = $this->plan->select('1', '*', '', 'id ASC');

		//$plandata = $this->plan->get_one(array('name'=>$name));
		$lessondata = $this->lesson->get_one(array('id'=>$id));
		include $this->admin_tpl('lesson_edit');
	}
	//编辑课程
	public function lesson_editdo(){
		$this->lesson = pc_base::load_model('db_lesson_model');
		$this->plan = pc_base::load_model('db_plan_model');
		
		$id = $_POST['id'];
		$plan = $_POST['plan'];
		$episode=$_POST['episode'];
		$title = $_POST['title'];
		$poster_list=$_POST['poster_list'];
		$standard_d=$_POST['standard_d'];
		$high_d=$_POST['high_d'];
		$bluelight_d=$_POST['bluelight_d'];
		$created_time=date('Y-m-d H:i:s');

		//$plan_data = $this->plan->select("plan='A套餐'", '*', '', 'id ASC');

		$dat = array(
			'id' => $id,
			'plan' => $plan,
			'episode'	=>	$episode,
			'title'	=>	$title,
			'poster_list'=>$poster_list,
			'standard_d'=>$standard_d,
			'high_d'  =>$high_d,
			'bluelight_d' =>$bluelight_d,
			'created_time'=>$created_time
		);
		$result=$this->lesson->update($dat, array('id'=>$id));
		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=tvuser&a=show_lesson&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	
	}
	//删除课程
	public function delete_lesson(){
		$this->lesson = pc_base::load_model('db_lesson_model');
		$id = $_GET['id'];
		if(empty($id)){
			$msg = '操作失败!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_lesson&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			
			$this->lesson->delete(array('id'=>$id));

			$msg = '操作成功!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_lesson&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	//批量删除课程
	public function delete_lessonall(){
		$this->lesson = pc_base::load_model('db_lesson_model');
		$ids=explode(',', $_GET['id']);
		foreach ($ids as $v){
			$this->lesson->delete(array('id'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//添加课程
	public function addLesson() {
		$this->lesson = pc_base::load_model('db_lesson_model');
		if(isset($_POST['dosubmit'])){
			$id= $_POST['id'];
			$plan = $_POST['plan'];
			$episode=$_POST['episode'];
			$title = $_POST['title'];
			$bluelight_d = $_POST['bluelight_d'];
			$super_d=$_POST["super_d"];
			$high_d=$_POST['high_d'];
			$standard_d = $_POST['standard_d'];
			$createdtime= date('Y-m-d H:i:s');
			if(strpos($_POST['imageurl'],'http://') !== false){
				$poster_list = $_POST['imageurl'];
			}else{
				$poster_list= TASK_IMG_PATH.$_POST['imageurl'];
			}

			$data = array(
				'plan'	=>	$plan,
				'episode'	=>	$episode,
				'title'	=>	$title,
				'bluelight_d'	=>	$bluelight_d,
				'super_d'	=>	$super_d,
				'high_d'	=>	$high_d,
				'standard_d'=>$standard_d,
				'poster_list'=>$poster_list,
				'created_time'=>$createdtime
			);
			$this->lesson->insert($data);
			//添加操作日志
			$status = 'add';
			$description = $data;
			$this->system_log($status,json_encode($description));
			showmessage(L('operation_success'),'?m=go3c&c=tvuser&a=show_lesson');
		}else{
			include $this->admin_tpl('addLesson');	
		}						
	}
//商品配置
public function show_goods(){
		$this->goods = pc_base::load_model('db_goods_model');
		$this->goods_type = pc_base::load_model('db_goods_type_model');
		$type = $_GET['type'];
		$name=	$_GET['name'];
		$where = "1 ";
		
		if(!empty($name)){
			$where.=" and name ='$name' ";
		}
		if(!empty($type)){
			$where.=" and gt.name ='$type' ";
		}
		
		$goods_data = $this->goods_type->select('1', '*', '', 'id ASC');
		$field    	= 'g.*,gt.name';
		$sql     	= "goods as g left join goods_type as gt on gt.id=g.type WHERE ".$where;
		$order  	= 'ORDER BY g.id desc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->goods->mynum($sql);
		$totalpage	= $this->goods->mytotalpage($sql, $perpage);
		$data 		= $this->goods->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->goods->pages;
		
		include $this->admin_tpl('show_goods');
	}
//添加课程
	public function addGoods() {
		$this->goods = pc_base::load_model('db_goods_model');
		if(isset($_POST['dosubmit'])){
			$id= $_POST['id'];
			$type = $_POST['type'];
			$sign=$_POST['sign'];
			$recommand = $_POST['recommand'];
			$poster_list = $_POST['poster_list'];
			$bigpic_list =$_POST['bigpic_list'];
			$video=$_POST["video"];
			$vip_price=$_POST['vip_price'];
			$member_price = $_POST['member_price'];
			$default_price = $_POST['default_price'];
			$createdtime= $_POST['createdtime'];
			if(strpos($_POST['imageurl'],'http://') !== false){
				$poster_list = $_POST['imageurl'];
			}else{
				$poster_list= TASK_IMG_PATH.$_POST['imageurl'];
			}

			$data = array(
				'type'	=>	$type,
				'sign'	=>	$sign,
				'recommand'	=>	$recommand,
				'poster_list'	=>	$poster_list,
				'bigpic_list'	=>	$bigpic_list,
				'video'	=>	$video,
				'vip_price'=>$vip_price,
				'member_price'=>$member_price,
				'default_price'=>$default_price,
				'created_time'=>$createdtime
			);
			$this->goods->insert($data);
			//添加操作日志
			$status = 'add';
			$description = $data;
			$this->system_log($status,json_encode($description));
			showmessage(L('operation_success'),'?m=go3c&c=tvuser&a=show_goods');
		}else{
			include $this->admin_tpl('addGoods');	
		}	
	}
	//删除商品
	public function delete_goods(){
		$this->goods = pc_base::load_model('db_goods_model');
		$id = $_GET['id'];
		if(empty($id)){
			$msg = '操作失败!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_goods&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			
			$this->goods->delete(array('id'=>$id));

			$msg = '操作成功!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_goods&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	public function delete_goodsall(){
		$this->goods = pc_base::load_model('db_lesson_model');
		$ids=explode(',', $_GET['id']);
		foreach ($ids as $v){
			$this->goods->delete(array('id'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//到达编辑商品页面
	public function goods_edit(){
		$id = $_GET['id'];

		$this->goods = pc_base::load_model('db_goods_model');
		$this->goods_type = pc_base::load_model('db_goods_type_model');
		$plan_data = $this->goods_type->select('1', '*', '', 'id ASC');
		//$pl_data = $this->plan->get_one(array('name'=>$name));
		$goodsdata = $this->goods->get_one(array('id'=>$id));
		include $this->admin_tpl('goods_edit');
	}
	//编辑商品
	public function goods_editdo(){
		$this->goods = pc_base::load_model('db_goods_model');
		$this->goods_type = pc_base::load_model('db_goods_type_model');

		$id = $_POST['id'];
		$sign = $_POST['sign'];
		$name=$_POST['name'];
		$type = $_POST['type'];
		$created_time=date('Y-m-d H:i:s');
		//$plan_data = $this->plan->select("plan='A套餐'", '*', '', 'id ASC');

		$dat = array(
			'sign'	=>	$sign,
			'name' => $name,
			'created_time'=>$created_time,
			'type'	=>	$type
		);
		$this->goods->update($dat,array('id'=>$id));

		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=tvuser&a=show_goods&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}

	//动画配置
	public function show_cartoon(){
		$this->cartoon = pc_base::load_model('db_cartoon_model');
		$this->cartoon_links = pc_base::load_model('db_cartoon_links_model');
		
		$title=	$_GET['title'];
		$where = "1 ";
		
		if(!empty($title)){
			$where.=" and title ='$title' ";
		}
		
		$field    	= 'c.*';
		$sql     	= "cartoon as c  WHERE ".$where;
		$order  	= 'ORDER BY c.id desc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->cartoon->mynum($sql);
		$totalpage	= $this->cartoon->mytotalpage($sql, $perpage);
		$data 		= $this->cartoon->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->cartoon->pages;
		
		include $this->admin_tpl('show_cartoon');
	}
	//添加动画
	public function addCartoon() {
		$this->cartoon = pc_base::load_model('db_cartoon_model');
		$this->cartoon_links = pc_base::load_model('db_cartoon_links_model');
		if(isset($_POST['dosubmit'])){
			$id= $_POST['id'];
			$title = $_POST['title'];
			//$sign=$_POST['sign'];
			$poster_list = $_POST['poster_list'];
			$bigpic_list =$_POST['bigpic_list'];
			$descs=$_POST["descs"];
			$createdtime= date('Y-m-d H:i:s');

			$episode=$_POST['episode'];
			$bluelight_d=$_POST['bluelight_d'];
			$super_d=$_POST['super_d'];
			$high_d=$_POST['high_d'];
			$standard_d=$_POST['standard_d'];
			$publish_time=$_POST['publish_time'];
			$data = array(
				'title'	=>	$title,
				'poster_list'	=>	$poster_list,
				'bigpic_list'	=>	$bigpic_list,
				'descs'	=>	$descs,
				'created_time' =>$createdtime,
				'publish_time' =>$publish_time
			);
			$this->cartoon->insert($data);

			$dat = array(
				'episode' => $episode,
				'bluelight_d' =>$bluelight_d,
				'super_d' => $super_d,
				'high_d'  => $high_d,
				'standard_d'=>$standard_d	
			);
			$this->cartoon_links->insert($dat);

			//添加操作日志
			$status = 'add';
			$description = $data;
			$this->system_log($status,json_encode($description));
			showmessage(L('operation_success'),'?m=go3c&c=tvuser&a=show_cartoon');
		}else{
			include $this->admin_tpl('addCartoon');	
		}	
	}
	//删除动画
	public function delete_cartoon(){
		$this->cartoon = pc_base::load_model('db_cartoon_model');
		$id = $_GET['id'];
		if(empty($id)){
			$msg = '操作失败!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_cartoon&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			
			$this->cartoon->delete(array('id'=>$id));

			$msg = '操作成功!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_cartoon&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	public function delete_cartoonall(){
		$this->cartoon = pc_base::load_model('db_cartoon_model');
		$ids=explode(',', $_GET['id']);
		foreach ($ids as $v){
			$this->cartoon->delete(array('id'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	//到达编辑动画页面
	public function cartoon_edit(){
		$id = $_GET['id'];
		$this->cartoon = pc_base::load_model('db_cartoon_model');
		$this->cartoon_links = pc_base::load_model('db_cartoon_links_model');
		$ctdata = $this->cartoon->get_one(array('id'=>$id));
		$ct_ldata=$this->cartoon_links->get_one(array('id'=>$id));
		include $this->admin_tpl('cartoon_edit');
	}
	//编辑动画
	public function cartoon_editdo(){
		$this->cartoon = pc_base::load_model('db_cartoon_model');
		$this->cartoon_links = pc_base::load_model('db_cartoon_links_model');

		$id= $_POST['id'];
			$title = $_POST['title'];
			//$sign=$_POST['sign'];
			$poster_list = $_POST['poster_list'];
			$bigpic_list =$_POST['bigpic_list'];
			$descs=$_POST["descs"];
			$createdtime= date('Y-m-d H:i:s');

			$episode=$_POST['episode'];
			$bluelight_d=$_POST['bluelight_d'];
			$super_d=$_POST['super_d'];
			$high_d=$_POST['high_d'];
			$standard_d=$_POST['standard_d'];
			$publish_time=$_POST['publish_time'];
			$data = array(
				'title'	=>	$title,
				'poster_list'	=>	$poster_list,
				'bigpic_list'	=>	$bigpic_list,
				'descs'	=>	$descs,
				'created_time' =>$createdtime,
				'publish_time'=>$publish_time
			);
		$this->cartoon->update($data,array('id'=>$id));
		$dat = array(
				'episode' => $episode,
				'bluelight_d' =>$bluelight_d,
				'super_d' => $super_d,
				'high_d'  => $high_d,
				'standard_d'=>$standard_d,
			);
		$this->cartoon_links->update($dat,array('id'=>$id));

		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=tvuser&a=show_cartoon&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}

	//通知配置
	public function show_notice(){
		$this->notice = pc_base::load_model('db_notice_model');
		$title=	$_GET['title'];
		$where = "1 ";
		
		if(!empty($title)){
			$where.=" and title ='$title' ";
		}
		
		$field    	= 'n.*';
		$sql     	= "notice as n WHERE ".$where;
		$order  	= 'ORDER BY n.id desc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->notice->mynum($sql);
		$totalpage	= $this->notice->mytotalpage($sql, $perpage);
		$data 		= $this->notice->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->notice->pages;
		
		include $this->admin_tpl('show_notice');
	}

	//添加通知
	public function addNotice() {
		$this->notice = pc_base::load_model('db_notice_model');
		if(isset($_POST['dosubmit'])){
			$id= $_POST['id'];
			$title = $_POST['title'];
			$descs = $_POST['descs'];
			$sign=$_POST["sign"];
			$video=$_POST['video'];
			$publish_time=$_POST['publish_time'];
			$createdtime= date('Y-m-d H:i:s');
			if(strpos($_POST['imageurl'],'http://') !== false){
				$poster_list = $_POST['imageurl'];
			}else{
				$poster_list= TASK_IMG_PATH.$_POST['imageurl'];
			}

			$data = array(
				'title'	=>	$title,
				'descs'	=>	$descs,
				'sign'	=>	$sign,
				'poster_list'=>$poster_list,
				'video' =>$video,
				'publish_time'=>$publish_time,
				'created_time'=>$createdtime
			);
			$this->notice->insert($data);
			//添加操作日志
			$status = 'add';
			$description = $data;
			$this->system_log($status,json_encode($description));
			showmessage(L('operation_success'),'?m=go3c&c=tvuser&a=show_notice');
		}else{
			include $this->admin_tpl('addnotice');	
		}						
	}
	//删除通知
	public function delete_notice(){
		$this->notice = pc_base::load_model('db_notice_model');
		$id = $_GET['id'];
		if(empty($id)){
			$msg = '操作失败!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_notice&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			
			$this->notice->delete(array('id'=>$id));

			$msg = '操作成功!';
			showmessage($msg,'?m=go3c&c=tvuser&a=show_notice&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	public function delete_noticeall(){
		$this->notice = pc_base::load_model('db_notice_model');
		$ids=explode(',', $_GET['id']);
		foreach ($ids as $v){
			$this->notice->delete(array('id'=>$v));
		}
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

//到达编辑通知页面
	public function notice_edit(){
		$id = $_GET['id'];
		$this->notice = pc_base::load_model('db_notice_model');
		$ntdata = $this->notice->get_one(array('id'=>$id));
		include $this->admin_tpl('notice_edit');
	}
//编辑通知
	public function notice_editdo(){
		$this->notice = pc_base::load_model('db_notice_model');

			$id= $_POST['id'];
			$title = $_POST['title'];
			$descs = $_POST['descs'];
			$sign=$_POST["sign"];
			$video= $_POST['video'];
			$publish_time=$_POST['publish_time'];
			$createdtime= date('Y-m-d H:i:s');
			if(strpos($_POST['imageurl'],'http://') !== false){
				$poster_list = $_POST['poster_list'];
			}else{
				$poster_list= TASK_IMG_PATH.$_POST['poster_list'];
			}

			$dat = array(
				'title'	=>	$title,
				'descs'	=>	$descs,
				'sign'	=>	$sign,
				'video' =>  $video,
				'poster_list'=>$poster_list,
				'publish_time'=>$publish_time,
				'created_time'=>$createdtime
			);
			$this->notice->update($dat,array('id'=>$id));
	
		//添加操作日志
		$status = 'edit';
		$description = $dat;
		$this->system_log($status,json_encode($description));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=tvuser&a=show_notice&pc_hash='.$_SESSION['pc_hash'], $ms = 2000);
	}



	public function addAdvert(){
		$this->advert = pc_base::load_model('db_advert_model');
		if(isset($_POST['dosubmit'])){
			$id= $_POST['id'];
			$open_video=$_POST['open_video'];
			$publish_time=$_POST['publish_time'];
			$createdtime= date('Y-m-d H:i:s');
			if(strpos($_POST['imageurl'],'http://') !== false){
				$poster_list = $_POST['imageurl'];
			}else{
				$poster_list= TASK_IMG_PATH.$_POST['imageurl'];
			}

			$data = array(
				
				'poster_list'=>$poster_list,
				'open_video' =>$open_video,
				'publish_time'=>$publish_time,
				'created_time'=>$createdtime
			);
			$this->advert->insert($data);
			//添加操作日志
			$status = 'add';
			$description = $data;
			$this->system_log($status,json_encode($description));
			showmessage(L('operation_success'),'?m=go3c&c=tvuser&a=addAdvert');
		}else{
			include $this->admin_tpl('addAdvert');	
		}						
	}

	
	

}
?>