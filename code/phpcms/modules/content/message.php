<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/db_list.php' ;

class message extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		pc_base::load_sys_class('form','',0);
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}
	
	public function init() {
	}
	
	/**
	 * 服务器管理
	 *
	 */
	public function service(){
		$SERVER_NAME  = trim($_POST['SERVER_NAME']);
		$SERVER_TYPE = trim($_POST['SERVER_TYPE']);
		
		$where = " 1";
		$SERVER_NAME  != '' ? $where.= " AND `SERVER_NAME` LIKE '%$SERVER_NAME%'" : '';
		$SERVER_TYPE  != '' ? $where.= " AND `SERVER_TYPE` = '$SERVER_TYPE'" : '';
		
		$this->cms_server_config = pc_base::load_model('cms_server_config_model');		
		$data  = $this->cms_server_config->listinfo($where);
		include $this->admin_tpl('service_list');
	}
	/**
	 * 添加服务器
	 *
	 */
	public function addservice(){
		include $this->admin_tpl('service_add');
	}
	public function addservicedo(){
		
	}
	//消息列表
	public function mes(){
		$MSG_TITLE  = trim($_GET['MSG_TITLE']);
		$MSG_CONTENT  = trim($_GET['MSG_CONTENT']);
		$where = " 1";
		$MSG_TITLE  != '' ? $where.= " AND `MSG_TITLE` LIKE '%$MSG_TITLE%'" : '';
		$MSG_CONTENT  != '' ? $where.= " AND `MSG_CONTENT` LIKE '%$MSG_CONTENT%'" : '';
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';

		$this->cms_sermes_mod = pc_base::load_model('cms_sermes_mod_model');
		$data  = $this->cms_sermes_mod->listinfo($where, $order = '`MODEL_ID` ASC', $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('mes_list');
	}
	//添加信息
	public function addmes(){
		include $this->admin_tpl('mes_add');
	}
	//添加消息
	public function addmesdo(){
		$go3cdb = yzy_go3c_db() ;
		$COMCODE = trim($_POST['COMCODE']);
		$MSG_TITLE = trim($_POST['MSG_TITLE']);
		$MSG_CONTENT = trim($_POST['MSG_CONTENT']);
		$MSG_URI= trim($_POST['MSG_URI']);
		$MODEL_FOR = trim($_POST['MODEL_FOR']);
		$ISTRIGGER = trim($_POST['ISTRIGGER']);
		$TRIGGER_CONDITION = trim($_POST['TRIGGER_CONDITION']);
		$id = time();
		if(!empty($MSG_TITLE)){
			$mes_data = array(
				'MODEL_ID' =>$id,
				'COMCODE' => $COMCODE,
				'MSG_TITLE' => $MSG_TITLE,
				'MSG_CONTENT' => $MSG_CONTENT,
				'MSG_URI' => $MSG_URI,
				'MODEL_FOR' => $MODEL_FOR,
				'ISTRIGGER' => $ISTRIGGER,
				'TRIGGER_CONDITION' => $TRIGGER_CONDITION
			);
			$go_mes = array(
				'MODEL_ID' =>$id,
				'MSG_TITLE' => $MSG_TITLE,
				'MSG_CONTENT' => $MSG_CONTENT,
				'MSG_URI' => $MSG_URI,
				'MODEL_FOR' => $MODEL_FOR,
				'ISTRIGGER' => $ISTRIGGER,
				'TRIGGER_CONDITION' => $TRIGGER_CONDITION
			);
			$this->cms_sermes_mod = pc_base::load_model('cms_sermes_mod_model');
			$this->cms_sermes_mod->insert($mes_data);
			$go3cdb->w('t_msg_model',$go_mes) ;
			showmessage('操作成功!','?m=go3c&c=message&a=mes&menuid=2358&pc_hash=36vrR2');
		}else{
			showmessage('操作失败,标题不能为空!',HTTP_REFERER);
		}
	}
	//修改信息
	public function mes_edit(){
		$MODEL_ID = $_REQUEST['MODEL_ID'];
		$this->cms_sermes_mod = pc_base::load_model('cms_sermes_mod_model');
		$aKey = "MODEL_ID = '".$MODEL_ID."'";
		$limitInfo = $this->cms_sermes_mod->get_one($aKey);
		include $this->admin_tpl('mes_edit');
	}
	public function mes_editdo(){
		$go3cdb = yzy_go3c_db() ;
		$MODEL_ID = trim($_POST['MODEL_ID']);
		$COMCODE = trim($_POST['COMCODE']);
		$MSG_TITLE = trim($_POST['MSG_TITLE']);
		$MSG_CONTENT = trim($_POST['MSG_CONTENT']);
		$MSG_URI = trim($_POST['MSG_URI']);
		$MODEL_FOR = trim($_POST['MODEL_FOR']);
		$ISTRIGGER = trim($_POST['ISTRIGGER']);
		$TRIGGER_CONDITION = trim($_POST['TRIGGER_CONDITION']);
		if(!empty($MODEL_ID)){
			$mes_data = array(
				'COMCODE' => $COMCODE,
				'MSG_TITLE' => $MSG_TITLE,
				'MSG_CONTENT' => $MSG_CONTENT,
				'MSG_URI' => $MSG_URI,
				'MODEL_FOR' => $MODEL_FOR,
				'ISTRIGGER' => $ISTRIGGER,
				'TRIGGER_CONDITION' => $TRIGGER_CONDITION
			);
			$go_mes = array(
				'MSG_TITLE' => $MSG_TITLE,
				'MSG_CONTENT' => $MSG_CONTENT,
				'MSG_URI' => $MSG_URI,
				'MODEL_FOR' => $MODEL_FOR,
				'ISTRIGGER' => $ISTRIGGER,
				'TRIGGER_CONDITION' => $TRIGGER_CONDITION
			);
			$this->cms_sermes_mod = pc_base::load_model('cms_sermes_mod_model');
			$this->cms_sermes_mod->update($mes_data,array('MODEL_ID'=>$MODEL_ID));
			$go3cdb->w('t_msg_model',$go_mes, array('MODEL_ID'=>$MODEL_ID)) ;
			showmessage('操作成功!','?m=go3c&c=message&a=mes&menuid=2358&pc_hash=36vrR2');
		}else{
			showmessage('操作失败,标题不能为空!',HTTP_REFERER);
		}
	}
	//删除消息
	public function mes_del(){
		$go3cdb = yzy_go3c_db() ;
		$MODEL_ID = $_REQUEST['MODEL_ID'];
		$this->cms_sermes_mod = pc_base::load_model('cms_sermes_mod_model');
		$this->cms_sermes_mod->delete(array('MODEL_ID'=>$MODEL_ID));
		$go3cdb->d('t_msg_model',array('MODEL_ID'=>$MODEL_ID)) ;
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 60);
	}
	//消息管理
	public function mange(){
		$SERVER_NAME  = trim($_POST['SERVER_NAME']);
		$SERVER_TYPE = trim($_POST['SERVER_TYPE']);
		
		$where = " 1";
		$SERVER_NAME  != '' ? $where.= " AND `SERVER_NAME` LIKE '%$SERVER_NAME%'" : '';
		$SERVER_TYPE  != '' ? $where.= " AND `SERVER_TYPE` = '$SERVER_TYPE'" : '';
		
		$this->cms_server_config = pc_base::load_model('cms_server_config_model');		
		$data  = $this->cms_server_config->listinfo($where);
		include $this->admin_tpl('manage_list');
	}
	//消息推送
	public function mes_taken(){
		include $this->admin_tpl('mes_taken');
	}
	public function mes_takendo(){
		$PUSH_SOURCE = trim($_POST['PUSH_SOURCE']);
		$CONTENT_TYPE = trim($_POST['CONTENT_TYPE']);
		$MSG_TITLE = trim($_POST['MSG_TITLE']);
		$MSG_CONTENT = trim($_POST['MSG_CONTENT']);
		$MSG_URI = trim($_POST['MSG_URI']);
		$PUSH_TYPE = trim($_POST['PUSH_TYPE']);
		$PUSH_CODE = trim($_POST['PUSH_CODE']);
		
		$pushFilter=$_POST['pushFilter'];
		
		//header("content-Type: text/html; charset=Utf-8");  
        //$MSG_TITLE = mb_convert_encoding("$MSG_TITLE","UTF-8","GBK");
        //$MSG_CONTENT = mb_convert_encoding("$MSG_CONTENT","UTF-8","GBK");
		//$MSG_URI = mb_convert_encoding("$MSG_URI","UTF-8","GBK");

		$pushTypeFilter=$_POST['pushTypeFilter'];//一个数组；
		var_dump($pushTypeFilter);
		if($PUSH_TYPE!='0'&&!empty($pushTypeFilter)){
			$pushTypeFilter=implode(",", $pushTypeFilter);//一个字符串;
		}
		
		$pushCodeFilter=$_POST['pushCodeFilter'];
		$starttime=$_POST['starttime'];
		$endtime=$_POST['endtime'];
		$sumc=$_POST['sumc'];
		
		$msgContent = '{';
		$msgContent.= "\"title\"".':'."\"$MSG_TITLE\"".','."\"content\"".':'."\"$MSG_CONTENT\"".','."\"uri\"".':'."\"$MSG_URI\"";
		$msgContent.= '}';
		//拼接url
		//http://hostname:port/path/msg.api?m=msgReceive&msgContent=testtesttest&contentType=0&pushType=0&pushCode=1
		$path = 'http://www.go3c.tv:8060/MsgServer/msg.api?';
		$url.= $path.'m=msgReceive&msgContent='.$msgContent.'&contentType='.$CONTENT_TYPE.'&pushType='.$PUSH_TYPE.'&pushCode='.$PUSH_CODE.'&pushSource='.$PUSH_SOURCE ;
		if(!empty($pushTypeFilter)){
			$url.= '&pushTypeFilter='.$pushTypeFilter;
		}
		if(!empty($pushFilter)){
			$url.= '&pushTypeFilter='.$pushFilter;
		}
		if(!empty($pushCodeFilter)){
			$url.= '&pushCodeFilter='.$pushCodeFilter;
		}
		if (!empty($starttime)&&!empty($endtime)&&!empty($sumc)){
			$st = $starttime.','.$endtime.','.$sumc;
			$url.= '&pushCodeFilter='.$st;
		}
		
		var_dump($url);
		$s = file_get_contents($url);
		$ss=json_decode($s,true);
		if($ss['code']=='0'){
			showmessage('操作成功!',HTTP_REFERER);
		}else{
			showmessage('操作失败!',HTTP_REFERER);
		}
	}
}
?>
