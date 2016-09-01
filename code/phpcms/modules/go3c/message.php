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
		$SERVER_TYPE  != '' ? $where.= " AND `SERVER_TYPE` LIKE '%$SERVER_TYPE%'" : '';
		$where .= ' order by SERVER_ID desc';
		
		$this->msg_server_config = pc_base::load_model('msg_t_server_config_model');		
		$data  = $this->msg_server_config->listinfo($where);
		include $this->admin_tpl('service_list');
	}
	/**
	 * 添加服务器
	 *
	 */
	public function addservice(){
		include $this->admin_tpl('server_add');
	}
	public function addservicedo(){
		$SERVICE_TITLE = trim($_POST['SERVICE_TITLE']);
		$SERVICE_TYPE = trim($_POST['SERVICE_TYPE']);
		$SERVICE_IP = trim($_POST['SERVICE_IP']);
		$SERVICE_PORT = trim($_POST['SERVICE_PORT']);
		$SERVICE_STATU = trim($_POST['SERVICE_STATU']) ? '0' : '1';
		$SERVICE_SCOPE1 = trim($_POST['SERVICE_SCOPE1']);
		$SERVICE_SCOPE2 = trim($_POST['SERVICE_SCOPE2']);
		$SERVICE_SCOPE = $SERVICE_SCOPE1 . '到' . $SERVICE_SCOPE2;
		if(!empty($SERVICE_TITLE)){
			$service_data = array(
				'SERVER_ID' => time(),
				'SERVER_NAME' => $SERVICE_TITLE,
				'SERVER_TYPE' => $SERVICE_TYPE,
				'SERVER_IP' => $SERVICE_IP,
				'SERVER_PORT' => $SERVICE_PORT,
				'SERVER_SCOPE' => $SERVICE_SCOPE,
				'SERVER_STATU' => $SERVICE_STATU
			);
			$this->msg_t_server_config = pc_base::load_model('msg_t_server_config_model');
			$this->msg_t_server_config->insert($service_data);
			showmessage('操作成功!','?m=go3c&c=message&a=service');
		}else{
			showmessage('操作失败,服务器名不能为空!',HTTP_REFERER);
		}
	}
	
	public function editservice() {
		$serverid = $_GET['SERVER_ID'];
		$this->msg_t_server_config = pc_base::load_model('msg_t_server_config_model');
		$data = $this->msg_t_server_config->select(array('SERVER_ID' => $serverid));
		$data = $data[0];
		include $this->admin_tpl('server_edit');
	}
	
	public function editservicedo() {
		$serverid = $_POST['serverid'];
		$SERVER_NAME = trim($_POST['SERVICE_TITLE']);
		$SERVER_TYPE = trim($_POST['SERVER_TYPE']);
		$SERVER_IP = trim($_POST['SERVER_IP']);
		$SERVER_PORT = trim($_POST['SERVER_PORT']);
		$SERVER_STATU = trim($_POST['SERVER_STATU']) ? '0' : '1';
		$SERVER_SCOPE = trim($_POST['SERVER_SCOPE']);
		if(!empty($SERVER_NAME)){
			$server_data = array(
				'SERVER_NAME' => $SERVER_NAME,
				'SERVER_TYPE' => $SERVER_TYPE,
				'SERVER_IP' => $SERVER_IP,
				'SERVER_PORT' => $SERVER_PORT,
				'SERVER_SCOPE' => $SERVER_SCOPE,
				'SERVER_STATU' => $SERVER_STATU
			);
			$this->msg_t_server_config = pc_base::load_model('msg_t_server_config_model');
			$this->msg_t_server_config->update($server_data, array('SERVER_ID' => $serverid));
			showmessage('操作成功!','?m=go3c&c=message&a=service');
		}else{
			showmessage('操作失败,标题不能为空!','?m=go3c&c=message&a=service');
		}
	}
	
	public function deleteservice() {
		$serverid = $_GET['SERVER_ID'];
		$this->msg_t_server_config = pc_base::load_model('msg_t_server_config_model');
		$this->msg_t_server_config->delete(array('SERVER_ID' => $serverid));
		showmessage('操作成功!','?m=go3c&c=message&a=service');
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

		$this->msg_t_msg_model = pc_base::load_model('msg_t_msg_model_model');
		$data  = $this->msg_t_msg_model->listinfo($where, $order = '`MODEL_ID` ASC', $page, $perpage);
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
		$MSG_TITLE = trim($_POST['MSG_TITLE']);
		$MSG_CONTENT = trim($_POST['MSG_CONTENT']);
		$MSG_URI= trim($_POST['MSG_URI']);
		$MODEL_FOR = trim($_POST['MODEL_FOR']);
		$ISTRIGGER = trim($_POST['ISTRIGGER']);
		$ISTRIGGERch= trim($_POST['ISTRIGGERch']);
		$TRIGGER_CONDITION1= trim($_POST['TRIGGER_CONDITION1']);
		$TRIGGER_CONDITION2= trim($_POST['TRIGGER_CONDITION2']);
		$TRIGGER_CONDITION3= trim($_POST['TRIGGER_CONDITION3']);
		$TRIGGER_CONDITION4= trim($_POST['TRIGGER_CONDITION4']);
		$TRIGGER_CONDITION5= trim($_POST['TRIGGER_CONDITION5']);
		$TRIGGER_CONDITION6= trim($_POST['TRIGGER_CONDITION6']);
		if(!empty($ISTRIGGERch)){
			if(!empty($TRIGGER_CONDITION1)||!empty($TRIGGER_CONDITION2)){
				$TRIGGER_CONDITION = '{';
				$parameter = $TRIGGER_CONDITION1.'-'.$TRIGGER_CONDITION2;
				$TRIGGER_CONDITION.= "\"condition\"".':'."\"$ISTRIGGERch\"".','."\"parameter\"".':'."\"$parameter\"";
				$TRIGGER_CONDITION.= '}';
			}elseif (!empty($TRIGGER_CONDITION3)||!empty($TRIGGER_CONDITION4)){
				$TRIGGER_CONDITION = '{';
				$parameter = $TRIGGER_CONDITION3.'-'.$TRIGGER_CONDITION4;
				$TRIGGER_CONDITION.= "\"condition\"".':'."\"$ISTRIGGERch\"".','."\"parameter\"".':'."\"$parameter\"";
				$TRIGGER_CONDITION.= '}';
			}elseif (!empty($TRIGGER_CONDITION5)||!empty($TRIGGER_CONDITION6)){
				$TRIGGER_CONDITION = '{';
				$parameter = $TRIGGER_CONDITION5.'-'.$TRIGGER_CONDITION6;
				$TRIGGER_CONDITION.= "\"condition\"".':'."\"$ISTRIGGERch\"".','."\"parameter\"".':'."\"$parameter\"";
				$TRIGGER_CONDITION.= '}';
			}else {
				$TRIGGER_CONDITION = '{';
				$TRIGGER_CONDITION.= "\"condition\"".':'."\"$ISTRIGGERch\"";
				$TRIGGER_CONDITION.= '}';
			}
		}
		$id = time();
		if(!empty($MSG_TITLE)){
			$mes_data = array(
				'MODEL_ID' =>$id,
				'MSG_TITLE' => $MSG_TITLE,
				'MSG_CONTENT' => $MSG_CONTENT,
				'MSG_URI' => $MSG_URI,
				'MODEL_FOR' => $MODEL_FOR,
				'ISTRIGGER' => $ISTRIGGER,
				'TRIGGER_CONDITION' => $TRIGGER_CONDITION
			);
			$this->msg_t_msg_model = pc_base::load_model('msg_t_msg_model_model');
			$this->msg_t_msg_model->insert($mes_data);
			$go3cdb->w('t_msg_model',$mes_data) ;
			showmessage('操作成功!','?m=go3c&c=message&a=mes&menuid=2358');
		}else{
			showmessage('操作失败,标题不能为空!','?m=go3c&c=message&a=mes&menuid=2358');
		}
	}
	//修改信息
	public function mes_edit(){
		$MODEL_ID = $_REQUEST['MODEL_ID'];
		$this->msg_t_msg_model = pc_base::load_model('msg_t_msg_model_model');
		$aKey = "MODEL_ID = '".$MODEL_ID."'";
		$limitInfo = $this->msg_t_msg_model->get_one($aKey);
		if(!empty($limitInfo['TRIGGER_CONDITION'])){
			$st = json_decode($limitInfo['TRIGGER_CONDITION']);
			foreach ($st as $k=>$v){
			if(is_object($v)) $limitInfo[$k]=json_to_array($v);else $limitInfo[$k]=$v;
			}
		}
		include $this->admin_tpl('mes_edit');
	}
	public function mes_editdo(){
		$go3cdb = yzy_go3c_db() ;
		$MODEL_ID = trim($_POST['MODEL_ID']);
		$MSG_TITLE = trim($_POST['MSG_TITLE']);
		$MSG_CONTENT = trim($_POST['MSG_CONTENT']);
		$MSG_URI = trim($_POST['MSG_URI']);
		$MODEL_FOR = trim($_POST['MODEL_FOR']);
		$ISTRIGGER = trim($_POST['ISTRIGGER']);
		$ISTRIGGERch = trim($_POST['ISTRIGGERch']);
		$parameter = trim($_POST['parameter']);
		if(!empty($ISTRIGGERch)){
			if (!empty($parameter)){
				$TRIGGER_CONDITION = '{';
				$TRIGGER_CONDITION.= "\"condition\"".':'."\"$ISTRIGGERch\"".','."\"parameter\"".':'."\"$parameter\"";
				$TRIGGER_CONDITION.= '}';
			}else{
				$TRIGGER_CONDITION = '{';
				$TRIGGER_CONDITION.= "\"condition\"".':'."\"$ISTRIGGERch\"";
				$TRIGGER_CONDITION.= '}';
			}
		}
		if(!empty($MODEL_ID)){
			$mes_data = array(
				'MSG_TITLE' => $MSG_TITLE,
				'MSG_CONTENT' => $MSG_CONTENT,
				'MSG_URI' => $MSG_URI,
				'MODEL_FOR' => $MODEL_FOR,
				'ISTRIGGER' => $ISTRIGGER,
				'TRIGGER_CONDITION' => $TRIGGER_CONDITION
			);
			$this->msg_t_msg_model = pc_base::load_model('msg_t_msg_model_model');
			$this->msg_t_msg_model->update($mes_data,array('MODEL_ID'=>$MODEL_ID));
			$go3cdb->w('t_msg_model',$mes_data, array('MODEL_ID'=>$MODEL_ID)) ;
			showmessage('操作成功!','?m=go3c&c=message&a=mes&menuid=2358');
		}else{
			showmessage('操作失败,标题不能为空!','?m=go3c&c=message&a=mes&menuid=2358');
		}
	}
	//删除消息
	public function mes_del(){
		$go3cdb = yzy_go3c_db() ;
		$MODEL_ID = $_REQUEST['MODEL_ID'];
		$this->msg_t_msg_model = pc_base::load_model('msg_t_msg_model_model');
		$this->msg_t_msg_model->delete(array('MODEL_ID'=>$MODEL_ID));
		$go3cdb->d('t_msg_model',array('MODEL_ID'=>$MODEL_ID)) ;
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 60);
	}
	//消息管理
	public function mange(){
		$PUSH_TITLE = trim($_POST['PUSH_TITLE']);
		$PUSH_SOURCE  = trim($_POST['PUSH_SOURCE']);
		$PUSH_SIGN = trim($_POST['PUSH_SIGN']);
		$field    = isset($_POST['field']) ? $_POST['field'] : 'CREATE_TIME';
		$order    = isset($_POST['order']) ? $_POST['order'] : 'DESC';
		$where = " 1";
		$PUSH_TITLE != '' ? $where .= " AND `MSG_CONTENT` like '%$PUSH_TITLE%'" : '';
		$PUSH_SOURCE  != '' ? $where.= " AND `PUSH_SOURCE` = '$PUSH_SOURCE'" : '';
		$PUSH_SIGN  != '' ? $where.= " AND `PUSH_SIGN` = '$PUSH_SIGN'" : '';
		$this->msg_t_msg = pc_base::load_model('msg_t_msg_model');
		$page  = $_POST['page'] ? $_POST['page'] : '1';
		$perpage = intval($_POST['perpage']) ? intval($_POST['perpage']) : '15';
		$data  = $this->msg_t_msg->listinfo($where, $order = "$field $order", $page, $perpage);	
		include $this->admin_tpl('manage_list');
	}
	public function mes_device(){
		$USER_ID = trim($_POST['USER_ID']);
		$BELONGTO  = trim($_POST['BELONGTO']);
		$DEVICE_TYPE  = trim($_POST['DEVICE_TYPE']);
		
		$where = " 1";
		$USER_ID  != '' ? $where.= " AND `USER_ID` LIKE '%$USER_ID%'" : '';
		$BELONGTO  != '' ? $where.= " AND `BELONGTO` = '$BELONGTO'" : '';
		$DEVICE_TYPE  != '' ? $where.= " AND `DEVICE_TYPE` = '$DEVICE_TYPE'" : '';
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';

		$this->msg_t_device_model = pc_base::load_model('msg_t_device_model');
		$data  = $this->msg_t_device_model->listinfo($where, $order = '`DEVICE_ID` ASC', $page, $perpage);

		$pages = $this->db->pages;
		include $this->admin_tpl('mes_device');
	}
	//推送结果展示
	public function mes_show(){
		$MSG_ID  = trim($_GET['MSG_ID']);
		$this->msg_t_msg = pc_base::load_model('msg_t_msg_model');
		$akey = "MSG_ID = '$MSG_ID'";
		$data  = $this->msg_t_msg->listinfo($akey);
		include $this->admin_tpl('mes_show');
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

		$MSG_TITLE = str_replace(' ','',$MSG_TITLE);
		$MSG_CONTENT = str_replace(' ','',$MSG_CONTENT);

		$pushTypeFilter=$_POST['pushTypeFilter'];//一个数组；
		if($PUSH_TYPE!='0'&&!empty($pushTypeFilter)){
			$pushTypeFilter=implode(",", $pushTypeFilter);//一个字符串;
		}
		
		$pushCodeFilter=$_POST['pushCodeFilter'];
		$starttime=$_POST['starttime'];
		$endtime=$_POST['endtime'];
		if (!empty($starttime)||!empty($endtime)||!empty($pushCodeFilter)){
			 $sy = date("YmdHis");
			 if($sy>$starttime||$sy>$endtime||$endtime>$pushCodeFilter){
			 	showmessage('操作失败,推送时间不对!',$_SERVER['HTTP_REFERER'], $ms = 600);
			 }
		}
		$sumc=$_POST['sumc'];
		
		$msgContent = '{';
		$msgContent.= "\"title\"".':'."\"$MSG_TITLE\"".','."\"content\"".':'."\"$MSG_CONTENT\"".','."\"uri\"".':'."\"$MSG_URI\"";
		$msgContent.= '}';
		//拼接url
		//http://hostname:port/path/msg.api?m=msgReceive&msgContent=testtesttest&contentType=0&pushType=0&pushCode=1
		//$GO3C_PATH4 = 'http://www.go3c.tv:8041/go3cci/system.api?m=getinfo';
		$info = file_get_contents(GO3C_PATH4);
		$info=json_decode($info,true);
		$url.= $info['msgServer'].'/msg.api?m=msgReceive&msgContent='.$msgContent.'&contentType='.$CONTENT_TYPE.'&pushType='.$PUSH_TYPE.'&pushCode='.$PUSH_CODE.'&pushSource='.$PUSH_SOURCE ;
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
