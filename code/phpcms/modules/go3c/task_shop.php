<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('admin', 'admin', 0);

//服务器的图片路径地址

define('TASK_IMG_PATH','http://192.168.150.91/go3cadmin/');
//define('TASK_IMG_PATH','http://go3c.jdjkcn.net/');

//本地的客户端的图片域名(项目域名)(同步的时候用)
define('TASK_IMG_PATH_Local','http://192.168.150.91/go3cadmin/');
//define('TASK_IMG_PATH_Local','http://go3c.jdjkcn.net/');

//imglib 
define('IMG_LIB_PATH',PHPCMS_PATH.'phpcms/libs/imglib/') ;

define('TASK_SUBTITLE_PATH', 'video/subtitle/');

require_once IMG_LIB_PATH . 'HproseHttpClient.php' ;
require_once IMG_LIB_PATH . 'RpcCore.php' ;
require_once IMG_LIB_PATH . 'SynUpdateImageClient.php';

$getServerInstance = new HproseHttpClient(SYN_ROUTE_SERVER_TWO);//启用API路由
$imgClient = null;

if(is_object($getServerInstance))
{	
	global $imgClient;
	$imgClient =  new SynUpdateImage($getServerInstance);//更新
}

function get_img_url($path){
	
	global $getServerInstance,$imgClient,$err_info;

	if(is_object($imgClient) && !empty($path))
	{
		// $imgUrl = $path;	//测试用
		//$newImgUrl = parse_url($path);
		//$web_url = $newImgUrl['host'];
		$imgUrl = TASK_IMG_PATH_Local.$path;//正式使用
		 $imgClient->runSynUpdateImg($imgUrl);//更新方法调用
	}else{
		return "error path";	//路径不合法
	}

}

//调试方法必须存在
function logerror()
{

}
/*
//例子
 $imgUrl2 = 'http://111.208.56.207/poster/ingestposter/20130516/tc0516793331/poster58784.jpg';
 $imgUrl= 'http://www.baidu.com/img/shouye_b5486898c692066bd2cbaeda86d74448.gif';
echo get_img_url($imgUrl2);
*/

class task_shop extends admin {
	function __construct() {
		parent::__construct();
		pc_base::load_app_func('global_task');	//方法文件
		
		$this->task_db = pc_base::load_model('shop_task_model');			//任务信息表连接
		$this->task_app = pc_base::load_model('task_app_model');//应用任务数据表连接
		$this->spid_db = pc_base::load_model('admin_model');			//后台登录表连接
		$this->term_type = pc_base::load_model('term_type_model');		//NJPHP 加载新增的终端类型表
		$this->postion_type = pc_base::load_model('shop_type_model');	//NJPHP 加载新增的推荐位类型表
		$this->app_recommend = pc_base::load_model('app_recommend_model');	//推荐位apk表
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));	
	}
	
	/*
	* 默认当前用户的数据列表
	*/
	public function init(){
	}
	
	/*
	 * 添加应用
	*/
	public function add_app(){
		$this->app_type = pc_base::load_model('shop_type_model');
		$this->app_contype = pc_base::load_model('shop_app_contype_model');
		$type_name_list = $this->app_type->select('level=2 and ctype!="edu"', 'cat_id, cat_name');
		$app_contype = $this->app_contype->select();
		include $this->admin_tpl('app_add');
	}
	public function add_appedu(){
		$this->app_type = pc_base::load_model('shop_type_model');
		$this->app_contype = pc_base::load_model('shop_app_contype_model');
		$type_name_list = $this->app_type->select('level=2 and ctype="edu"', 'cat_id, cat_name');
		$app_contype = $this->app_contype->select();
		include $this->admin_tpl('app_add');
	}
	
	/*
	 * 添加资讯类型
	 */
	public function add_app_do(){
		//插入应用信息
		$app_name = $_POST['app_name'];
		$app_desc = $_POST['app_desc'];
		$channel_cat_id = $_POST['channel_cat_id'];
		$owner = $_POST['owner'];
		$language = $_POST['language'];
		$packagename = $_POST['packagename'];
		$os_ver = $_POST['os_ver'];
		$screen = $_POST['screen'];
		$score = $_POST['score'];
		$tag = $_POST['tag'];
		$view_count = $_POST['view_count'];
		$download_count = $_POST['download_count'];
		$create_time = date('Y-m-d H:i:s');
		$update_time = date('Y-m-d H:i:s');
		$status = $_POST['status'];
		$mobitype = $_POST['mobitype'];
		$widgetProvider = $_POST['widgetProvider'];
		$yufabu_date = $_POST['yufabu_date'];
		$channel = $_POST['channel'];
		$controller_type = $_POST['controller_type'];
		$versioncode = $_POST['versioncode'];
		$file_hash = $_POST['file_hash'];
		
		$this->app_type = pc_base::load_model('shop_type_model');
		$where = "cat_id = $channel_cat_id";
		$apptype = $this->app_type->get_one($where);
		$type = $apptype['ctype'];
		$version = $_POST['version'];
		$file_size = $_POST['file_size'];
		$price = $_POST['price'];
		$source = $_POST['source'];
		$release_date = $_POST['release_date'];
		
		if(empty($price)) $price='0.00';
		if(empty($channel_cat_id)) $channel_cat_id='0';
		if(empty($score)) $score='0';
		if(empty($view_count)) $view_count='0';
		if(empty($download_count)) $download_count='0';
		if(empty($status)) $status='1';
		if(empty($versioncode)) $versioncode='1';
		//$yufabu_date = empty($yufabu_date)?date('Y-m-d',time()):$yufabu_date;
		//$release_date = empty($release_date)?date('Y-m-d',time()):$release_date;
		
		if (isset($_POST['PC']))
			$PC = 1;
		else
			$PC = 0;
		if (isset($_POST['ANDROID']))
			$ANDROID = 1;
		else
			$ANDROID = 0;
		if (isset($_POST['IOS']))
			$IOS = 1;
		else
			$IOS = 0;
		if (isset($_POST['STB']))
			$STB = 1;
		else
			$STB = 0;
		if (isset($_POST['SSB']))
			$SSB = 1;
		else
			$SSB = 0;
		$insertdata = array(
			'app_name'			=>	$app_name,
			'app_desc'			=> 	$app_desc,
			'channel_cat_id'	=>	$channel_cat_id,
			'owner'				=>	$owner,
			'language'			=>	$language,
			'file_hash'			=>	$file_hash,
			'packagename'		=>	$packagename,
			'os_ver'			=>	$os_ver,
			'screen'			=>	$screen,
			'score'				=>	$score,
			'tag'				=>	$tag,
			'seq'				=>	'',
			'view_count'		=>	$view_count,
			'download_count'	=>	$download_count,
			'create_time'		=>	$create_time,
			'update_time'		=>	$update_time,
			'status'			=>	$status,
			'mobitype'			=>	$mobitype,
			'widgetProvider'	=>	$widgetProvider,
			'yufabu_date'		=>	$yufabu_date,
			'apptest'			=>	'',
			'channel'			=>	$channel,
			'type'				=>	$type,
			'active'			=>	'',
			'version'			=>	$version,
			'file_size'			=>	$file_size,
			'price'				=>	$price,
			'source'			=>	$source,
			'release_date'		=>	$release_date,
			'seq'				=>	'0',
			'PC'				=>	$PC,
			'ANDROID'			=>	$ANDROID,
			'IOS'				=>	$IOS,
			'STB'				=>	$STB,
			'SSB'				=>	$SSB,
			'versioncode'				=>	$versioncode,
			'controller_type'	=>	$controller_type
		);
		var_dump($insertdata);
		$this->app = pc_base::load_model('app_model');
		$this->app->insert($insertdata);
		
		//插入应用图片
		$appid = $this->app->insert_id();
		$ad_imgUrl = $_POST['ad_imgUrl'];
		$icondata = array(
			'app_id'	=>	$appid,
			'term_id'	=>	0,
			'os_type'	=>	0,
			'seq_number'=>	0,
			'image_file'=>	$ad_imgUrl,
			'image_type'=>	122
		);
		$this->app_image = pc_base::load_model('app_image_model');
		$this->app_image->insert($icondata);
		//if (isset($_POST['ad_imgUrl1']) && $_POST['ad_imgUrl1'] != '') {
		//	$image = TASK_IMG_PATH . $_POST['ad_imgUrl1'];
		//	$icondata['image_file'] = $image;
		//	$icondata['image_type'] = 103;
		//	$this->app_image->insert($icondata);
		//}
		
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=shop&a=shop_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 修改应用
	 */
	public function edit_app(){
		$id = $_REQUEST['id'];
		//当前app
		$this->app = pc_base::load_model('app_model');
		$aKey = "app_id = '".$id."'";
		$app = $this->app->get_one($aKey);
		//所有app类型
		$this->app_type = pc_base::load_model('shop_type_model');
		$type_name_list = $this->app_type->select('level=2 and ctype!="edu"', 'cat_id, cat_name');
		$this->app_contype = pc_base::load_model('shop_app_contype_model');
		$app_contype = $this->app_contype->select();
		//当前app的icon image
		$this->app_image = pc_base::load_model('app_image_model');
		$iconwhere = "app_id = $id ";
		$icon = $this->app_image->get_one($iconwhere);
		$icon = str_replace(TASK_IMG_PATH, '', $icon['image_file']);
		//$imagewhere = "app_id = $id AND image_type = 'big'";
		//$image = $this->app_image->get_one($imagewhere);
		//$image = str_replace(TASK_IMG_PATH, '', $image['image_file']);
		include $this->admin_tpl('app_edit');
	}
	
	public function edit_app_do() {
		$appid = $_POST['app_id'];
		//插入应用信息
		$app_name = $_POST['app_name'];
		$app_desc = $_POST['app_desc'];
		$channel_cat_id = $_POST['channel_cat_id'];
		$owner = $_POST['owner'];
		$language = $_POST['language'];
		$packagename = $_POST['packagename'];
		$os_ver = $_POST['os_ver'];
		$screen = $_POST['screen'];
		$score = $_POST['score'];
		$tag = $_POST['tag'];
		$view_count = $_POST['view_count'];
		$download_count = $_POST['download_count'];
		//$create_time = date('Y-m-d H:i:s');
		$update_time = date('Y-m-d H:i:s');
		$status = $_POST['status'];
		$mobitype = $_POST['mobitype'];
		$widgetProvider = $_POST['widgetProvider'];
		$yufabu_date = $_POST['yufabu_date'];
		$channel = $_POST['channel'];
		$controller_type = $_POST['controller_type'];
		$versioncode = $_POST['versioncode'];
		$file_hash = $_POST['file_hash'];
		
		$this->app_type = pc_base::load_model('shop_type_model');
		$where = "cat_id = $channel_cat_id";
		$apptype = $this->app_type->get_one($where);
		$type = $apptype['ctype'];
		
		$version = $_POST['version'];
		$file_size = $_POST['file_size'];
		$price = $_POST['price'];
		$source = $_POST['source'];
		$release_date = $_POST['release_date'];
		$yufabu_date = date('Y-m-d',time());
		$release_date = date('Y-m-d',time());
		if(empty($price)) $price='0.00';
		if(empty($channel_cat_id)) $channel_cat_id='0';
		if(empty($score)) $score='0';
		if(empty($view_count)) $view_count='0';
		if(empty($download_count)) $download_count='0';
		if(empty($status)) $status='1';
		if(empty($versioncode)) $versioncode='1';
		if (isset($_POST['PC']))
			$PC = 1;
		else
			$PC = 0;
		if (isset($_POST['ANDROID']))
			$ANDROID = 1;
		else
			$ANDROID = 0;
		if (isset($_POST['IOS']))
			$IOS = 1;
		else
			$IOS = 0;
		if (isset($_POST['STB']))
			$STB = 1;
		else
			$STB = 0;
		if (isset($_POST['SSB']))
			$SSB = 1;
		else
			$SSB = 0;
			
		$insertdata = array(
			'app_name'			=>	$app_name,
			'app_desc'			=> 	$app_desc,
			'channel_cat_id'	=>	$channel_cat_id,
			'owner'				=>	$owner,
			'language'			=>	$language,
			'file_hash'			=>	$file_hash,
			'packagename'		=>	$packagename,
			'os_ver'			=>	$os_ver,
			'screen'			=>	$screen,
			'score'				=>	$score,
			'tag'				=>	$tag,
			'seq'				=>	'',
			'view_count'		=>	$view_count,
			'download_count'	=>	$download_count,
			'update_time'		=>	$update_time,
			'status'			=>	$status,
			'mobitype'			=>	$mobitype,
			'widgetProvider'	=>	$widgetProvider,
			'yufabu_date'		=>	$yufabu_date,
			'apptest'			=>	'',
			'channel'			=>	$type,
			'type'				=>	$type,
			'active'			=>	'',
			'version'			=>	$version,
			'file_size'			=>	$file_size,
			'price'				=>	$price,
			'source'			=>	$source,
			'release_date'		=>	$release_date,
			'PC'				=>	$PC,
			'ANDROID'			=>	$ANDROID,
			'IOS'				=>	$IOS,
			'STB'				=>	$STB,
			'SSB'				=>	$SSB,
			'versioncode'				=>	$versioncode,
			'controller_type'	=>	$controller_type
		);
		$this->app = pc_base::load_model('app_model');
		$this->app->update($insertdata, array('app_id'=>$appid));
		
		//插入应用图片
		//$appid = $this->app->insert_id();
		$icon = $_POST['ad_imgUrl'];
		$icondata = array(
			'app_id'	=>	$appid,
			'term_id'	=>	0,
			'os_type'	=>	0,
			'seq_number'=>	0,
			'image_file'=>	$icon,
			'image_type'=>	122
		);
		$this->app_image = pc_base::load_model('app_image_model');
		//$this->app_image->update($icondata, array('app_id'=>$appid));
		//if (isset($_POST['ad_imgUrl1']) && $_POST['ad_imgUrl1'] != '') {
		//	$image = TASK_IMG_PATH . $_POST['ad_imgUrl1'];
		//	$icondata['image_file'] = $image;
		//	$icondata['image_type'] = 2;
		//	$this->app_image->update($icondata, array('app_id'=>$appid));
		//}
		$msg = '修改成功!';
		showmessage($msg,'?m=go3c&c=shop&a=shop_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	* 任务列表
	*/
	public function task() {
		$posid_list = self::posidInfoAllTerms();	//推荐位列表
		$spid = $this->current_spid['spid'];
		$term_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->task_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'taskId ASC', $group = ' spid', $key='');
			$board_list = $this->task_db->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'taskId ASC', $group = ' board_type', $key='');
		}else{
			$spid_list = $this->task_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'taskId ASC', $group = ' spid', $key='');
			$board_list = $this->task_db->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'taskId ASC', $group = ' board_type', $key='');
		}
		//申请审核处理 start
		if($_GET['tstatus'] == 'apply')
		{		
			$status_data = array(
				'taskId' => trim($_GET['taskId']),		//任务ID
				'taskStatus' => '100',	//要修改的状态值
			);
			self::taskStatusProcess($status_data);
		}
		//申请审核处理 end
	
		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置
		$where = "app_pre_task AS t LEFT JOIN app_channel_category AS c ON t.app_type_id = c.cat_id WHERE";
		if($_SESSION['roleid']=='1'){
			$where .= " 1 ";
		}else{
			$where .= " t.spid in ('".$_SESSION['spid']."') ";
		}
		//查询处理 start 
		if(!empty($_GET['dosearch']))
		{
			//终端类型
			$term_id = trim($_GET['term']);
			if (!empty($term_id) && $term_id) {
				$where .= " AND term_id = '".$term_id."'";
				//$posid_list = self::posidInfo($term_id);
			}
			//任务(推荐位)			
			$posid = trim($_GET['posid']);
			if(!empty($posid))
			{
				$where .= " AND posid = '".$posid."'";
			}
			// 状态 
			$taskStatus = trim($_GET['taskStatus']);
			if(!empty($taskStatus))
			{
				$where .= " AND taskStatus = '".$taskStatus."'";
			}
			//	预发布时间
			$taskDate = trim($_GET['taskDate']);
			if(!empty($taskDate))
			{
				$where .= " AND ('$taskDate' = DATE_FORMAT( FROM_UNIXTIME( taskDate ) , '%Y-%m-%d' ))";
			}
			//	任务下线时间
			$offline_date = trim($_GET['offline_date']);
			if(!empty($offline_date))
			{
				$where .= " AND ('$offline_date' = DATE_FORMAT( FROM_UNIXTIME( offline_date ) , '%Y-%m-%d' ))";
			}
		}	
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		//$task_list  = $this->task_db->listinfo($where, $order = '`taskDate` DESC', $page, $pagesize = 15);
		$task_list  = $this->task_db->mylistinfo('t.*, c.cat_name AS app_type', $where, $order = ' ORDER BY `taskDate` DESC', $page, $pagesize = 15);
		$pages = $this->task_db->pages;
		
		include $this->admin_tpl('task_list_shop');	
	}
	
	/*
	* 当前用户所属运营商下面的推荐位数据列表
	*/
	private function posidInfoAllTerms() {
		//加入判断 先选择终端类型ID start	
		//终端下的推荐位列表 v9_position
		$this->posid_db = pc_base::load_model('position_app_model');	//推荐位信息表连接
		if($_SESSION['roleid']=='1'){
			$posid_where = " 1 ";
		}else{
			$posid_where = " spid in ('".$_SESSION['spid']."')";
		}
		$posid_list = $this->posid_db->select($posid_where);
		$posid_list_data = '';
		if(!empty($posid_list))
		{
			foreach($posid_list as $key => $value)
			{
				$posid_list_data[$value['posid']] = $value; 
			}
		}
		return $posid_list_data;
	}
	
	/*
	* 当前用户所属运营商下面的推荐位数据列表
	* param $term_id "终端类型ID"
	*/
	private function posidInfo($term_id) {
		//加入判断 先选择终端类型ID start	
		$term_type_data = array_keys(self::term_list());	//所有终端的ID
		if(!empty($term_id) && is_numeric($term_id) && in_array($term_id,$term_type_data))
		{
			$where = " spid = '".$this->current_spid['spid']."' AND term_id = '".$term_id."' ";

			//终端下的推荐位列表 v9_position
			$this->posid_db = pc_base::load_model('position_app_model');	//推荐位信息表连接
			$posid_where = " term_id = '".$term_id."' AND spid = '".$this->current_spid['spid']."'";
			$posid_list = $this->posid_db->select($posid_where);
			if(!empty($posid_list))
			{
				foreach($posid_list as $key => $value)
				{
					$posid_list_data[$value['posid']] = $value; 
				}
			}
		}else{
			$posid_list_data = '';
		}

		if(!empty($posid_list_data))
		{
			return $posid_list_data;
		}else{
			showmessage('请选择终端类型',HTTP_REFERER);
		}
		//加入判断 先选择终端类型ID end	
	}
	
	/*
	* 审核状态
	*/
	private function taskStatusProcess($status_data) {
		if(!empty($status_data) && is_array($status_data))
		{
			$taskId = trim($status_data['taskId']);	//任务ID

			if(!empty($taskId) && is_numeric($taskId) && is_numeric($status_data['taskStatus']))
			{		
				$taskInfo = self::getOneTask($taskId);

				if(empty($taskInfo))	//任务是否存在
				{
					showmessage('操作错误或数据不存在!',HTTP_REFERER);
				}else{
					$start_end_nums = explode('-',$taskInfo['start_end_nums']);
				}

				if(($taskInfo['videoNums'] >= $start_end_nums['0']) && ($taskInfo['videoNums'] <= $start_end_nums['1']))
				{
					$update_where = array(
						'spid' => $this->current_spid['spid'],
						'taskId' => $taskInfo['taskId']
					);

					$update_data = array(
						'taskStatus' => $status_data['taskStatus'],	//审核状态
						'posttime' => time()
					);
					$this->task_db->update($update_data,$update_where);
					showmessage('提交成功!',HTTP_REFERER);
				}else{
					showmessage('推荐位'.$taskInfo['posidInfo'].'的视频数量应该在'.$taskInfo['start_end_nums'],HTTP_REFERER);
				}
			}
		}
	}
	
	/*
	* 得到一条任务数据
	*/
	private function getOneTask($taskId) {
		$taskId = trim($taskId);	//任务ID
		if(!empty($taskId) && is_numeric($taskId))
		{	if($_SESSION['roleid']=='1'){
				$aKey = " taskId = '".$taskId."' ";
			}else{
				$aKey = " taskId = '".$taskId."' AND spid in ('".$_SESSION['spid']."')";
			}
			return $taskInfo = $this->task_db->get_one($aKey);	//是否存在
		}else{
			return '';
		}
	}
	
	/*
	* 推荐位任务操作状态
	*/
	public function taskStatus(){
		return $posidStatus_data = array(
			'0' => '下线状态',
			'1' => '编辑状态',
			'2' => '审核状态',
			'3' => '审核未通过',
			'4' => '已审核通过',
			'100' => '在线状态',
		);
	}
	
	/*
	* 所有终端类型 v9_term_type
	*/
	public function term_list(){
		$this->term_db = pc_base::load_model('cms_term_type_model');	//终端类型信息表连接		
		$get_term_data = $this->term_db->select();
		if(!empty($get_term_data))	//过滤归整一下方便后面直接使用
		{
			foreach($get_term_data as $key => $row)
			{
				$term_list[$row['id']] = $row;
			}
			//$this->term_list = $temp_term_list;
			return $term_list;
		}else{
			return '';
		}
	}
	
	/**
	 * 添加任务
	 *
	 */
	public function addTask() {
		$term_type_list = $position_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$position_type_list = $this->postion_type->select($where = 'level=2 and ctype!="edu"', $data = 'cat_id,cat_name');
		$posid_list = self::posidInfoAllTerms();	//推荐位列表	
		$taskType = 'addTask';	//
		$mode = trim($_POST['mode']);
		if(!empty($mode))	//提交添加处理
		{
			if($mode == 'addTask')
			{
				$term_id = trim($_POST['task_termid']);
				$posid = trim($_POST['task_posid']);
				$spid = $this->current_spid['spid'];
				$posidInfo = trim($_POST['task_posidInfo']);
				$typeid = trim($_POST['task_typeid']);				
				$taskDate = trim($_POST['task_taskDate']);
				$task_name = trim($_POST['task_name']);

				if(!empty($posid) && !empty($spid) && !empty($taskDate))
				{
					//审核审核状态、等待上线 在线状态 这三种状态同一日期必须只能有一个存在
					$aKey = " taskStatus > '0' AND posid = '".$posid."' AND taskDate = '".strtotime($taskDate)."'";
					$limitInfo = $this->task_db->get_one($aKey);
					if(empty($limitInfo))
					{
						$insert_data = array(
							'term_id' => $term_id,
							'posid' => $posid,
							'spid' => $posid_list[$posid]['spid'],
							'board_type' => $posid_list[$posid]['board_type'],
							'posidInfo' => $posidInfo,
							'start_end_nums' => $posid_list[$posid]['minnum'].'-'.$posid_list[$posid]['maxnum'],
							'app_type_id' => $typeid,
							'imgType' => 'text',	//默认图片类型
							'taskDate' => strtotime($taskDate),
							'taskStatus' => '1',	//默认编辑状态
							'task_name' => $task_name,
							'posttime' => time()
						);						
						$this->task_db->insert($insert_data);
						showmessage('提交成功',HTTP_REFERER);
					}else{
						showmessage('该条件下有一条任务己经存在,请不要重复添加！',HTTP_REFERER);
					}
				}else{
					showmessage('相关必填项没有设置!',HTTP_REFERER);
				}
			}else{
				showmessage('操作错误或数据不存在!',HTTP_REFERER);
			}
		}

		include $this->admin_tpl('task_shop_add');	
	}
	
	/**
	 * 修改编辑任务
	 *
	 */
	public function editTask() {
		$taskId = trim(getgpc('taskId'));	//任务ID		
		$taskInfo = self::getOneTask($taskId);
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$taskBase = explode('-',$taskInfo['posidInfo']);
		}
		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置

		$mode = trim($_POST['mode']);
		$taskType = 'editTask';
		if(!empty($mode))	//提交处理
		{
			if($mode == 'editTask')	//修改
			{
				$spid = $this->current_spid['spid'];
				$taskDate = trim($_POST['task_taskDate']);
				$task_name = trim($_POST['task_name']);
				if(!empty($spid) && !empty($taskDate))
				{
					//审核审核状态、等待上线 在线状态 这三种状态同一日期必须只能有一个存在
					$aKey = " taskStatus > '0' AND term_id = '".$taskInfo['term_id']."' AND posid = '".$taskInfo['posid']."' AND spid = '".$spid."' AND taskDate = '".strtotime($taskDate)."'";
					$limitInfo = $this->task_db->listinfo($aKey);
					
					$totalNums = count($limitInfo);
					if($totalNums > '1')	//有大于一条的数据存在，不能再修改
					{
						showmessage('该条件下己经有一条有效任务存在！',HTTP_REFERER);
					}else{
						if($totalNums == '1')	//有一条可用的数据存在
						{
							if($taskId != $limitInfo[0]['taskId'])//不是当前要修改的！
							{
								showmessage('该条件下己经有一条有效任务存在！',HTTP_REFERER);
							}
						}

						$update_data = array(
						'taskDate' => strtotime($taskDate),
						'taskStatus' => 1,
						'task_name' => $task_name,
						'posttime' => time()	//最后操作时间
						);
						
						$update_where = array('taskId' => $taskId);
						$this->task_db->update($update_data,$update_where);
						showmessage('修改成功',HTTP_REFERER);
					}
				}
			}else{
				showmessage('操作错误或数据不存在!',HTTP_REFERER);
			}
		}

		include $this->admin_tpl('task_shop_add');	
	}
	
	/*
	* 任务删除
	*/
	public function deleteTask() {
		if(!empty($_GET['taskId']) && is_numeric($_GET['taskId']))
		{
			$where_data = array(
				'taskId'=> $_GET['taskId']
				);
			$this->task_db->delete($where_data);
			//$this->task_video_db->delete($where_data);
			$this->task_apprecommend = pc_base::load_model('task_apprecommend_model');
			$where_recom = array(
				'type'=> $_GET['taskId']
				);
			$this->task_apprecommend->delete($where_recom);
			showmessage(L('operation_success'),HTTP_REFERER);

		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}
	}
	
	/*
	 * 申请审核该推荐位
	 */
	public function task_on(){
		$taskId = trim($_GET['taskId']);
		//$this->task_db = pc_base::load_model('cms_pre_task_model');
		$aKey = " taskId = '".$taskId."'";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			//上线之前删除旧的推荐apk
			$this->app_recommend->delete(array('type'=>$taskId));
			//查询该推荐位下所有的应用
			$tKey = "task_id = '".$taskId."'";
			$taskapk = $this->task_app->select($tKey);
			foreach ($taskapk as $v){
				$recom = array(
						'app_id' => $v['id'],
						'type' => $v['task_id'],
						'image_type' => '122'
				);
				$this->app_recommend->insert($recom);
			}
			$update_where = array(
					'spid' => $taskInfo['spid'],
					'taskId' => $taskInfo['taskId']
			);
			$update_data = array(
					'taskStatus' => '100',	//审核状态
					'posttime' => time()
			);
			$this->task_db->update($update_data,$update_where);
			showmessage('提交成功!',HTTP_REFERER);
		}
	}
	
	/*
	 * 下线推荐位
	 */
	public function task_off(){
		$taskId = trim($_GET['taskId']);
		$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			//删除推荐位的数据
			$this->app_recommend->delete(array('type'=>$taskId));
			$update_where = array(
					'spid' => $this->current_spid['spid'],
					'taskId' => $taskInfo['taskId']
			);
			$update_data = array(
					'taskStatus' => '1',	//审核状态
					'posttime' => time()
			);
			$this->task_db->update($update_data,$update_where);
			showmessage('提交成功!',HTTP_REFERER);
		}
	}
	
	/*
	 *添加推荐应用展示页面 
	 */
	public function addshoptask(){
		$this->app = pc_base::load_model('app_model');
		$taskId   	= trim($_GET['taskId']);
		$id   	= trim($_GET['id']);
		$pkey="app_id ='".$id."'";
		$videoInfo = $this->app->get_one($pkey);
		include $this->admin_tpl('shop_task_add');
	}
	
	/*
	 * 添加推荐资讯
	 */
	public function addshoptaskdo(){
		$id = trim($_POST['id']);
		$taskId = trim($_POST['taskId']);
		$videoTitle = trim($_POST['videoTitle']);
		$long_desc = trim($_POST['long_desc']);
		$videoImgUrl = trim($_POST['videoImgUrl']);
		$infor_url = trim($_POST['infor_url']);
		$this->task_apprecommend = pc_base::load_model('task_apprecommend_model');
		//查询是否已经加入该推荐位
		$pkey="type ='".$taskId."' and app_id='".$id."'";
		$gtaskInfo = $this->task_apprecommend->get_one($pkey);
		if(!empty($gtaskInfo)) showmessage('该应用已经添加到推荐位!',HTTP_REFERER);
		//查询该推荐位能添加的个数
		$aKey = " taskId = '".$taskId."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
		$updateVideNums = $taskInfo['videoNums'] + 1;
		if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1])){  //该推荐为的数量在范围内
			/*$insert_data = array(
					'id' => $id,
					'task_id' => $taskId,			            //任务ID
					'name' => $videoTitle,				//名称
					'playurl' => $infor_url ? $infor_url : '',//链接地址
					'desc' => $long_desc,					//视频简介
					'imageurl' => $videoImgUrl,
					'status' => '4',
					'sort'	=>	0,
					'position'	=>	$taskInfo['posidInfo']
			);*/
			$status = $this->task_apprecommend->insert($insert_data);
			$recom = array(
					'app_id'=>$id,
					'type'=>$taskId,
					'image_type'=>'122'   //icon图标的代表类型编号
			);
			$this->task_apprecommend->insert($recom);
			
			$taskUpdate = array(
					'videoNums' => $updateVideNums,	//任务资讯数量
					'taskStatus' => '1',	//回滚到默认编辑状态
					'taskDate' => $taskInfo['taskDate']
			);
			$taskWhere = array(
					'taskId' => $taskInfo['taskId']
			);
			$this->task_db->update($taskUpdate,$taskWhere);
			if($taskInfo['taskStatus'] != '1')
			{
				$msg = "提交成功,请注意：此推荐任务列表内容已变化，需要重新提交审核，并发布才能生效！";
				showmessage($msg,HTTP_REFERER,30000);
			}else{
				$msg = '提交成功!';
				showmessage($msg,HTTP_REFERER);
			}
		}else{
			showmessage('推荐位'.$taskInfo['posidInfo'].'的资讯数量应该在'.$taskInfo['start_end_nums'],HTTP_REFERER);
		}
	}
	
	/**
	 * 应用排序
	 */
	public function listorder() {
		$lastpreId = 0;
		if(isset($_POST['dosubmit'])) {
			foreach($_POST['listorders'] as $preId => $videoSort) {
				$this->task_app->update(array('sort'=>$videoSort),array('id'=>$preId));
				$lastpreId = $preId;
			}
			
			if(!empty($lastpreId) && is_numeric($lastpreId))
			{
				$where = " id = '".$lastpreId."'";
			

				$preInfo = $this->task_app->get_one($where);
				
				//回滚到默认编辑状态优化 start 
				$taskInfo = self::getOneTask($preInfo['task_id']);
				
				//比对预发布时间，如果时间在当前之前则取当前时间 start
				$taskInfo['taskDate'] = max($taskInfo['taskDate'],time());
				//比对预发布时间，如果时间在当前之前则取当前时间 end
				
				$taskUpdate = array(
					'taskStatus' => '1',	//回滚到默认编辑状态
					'taskDate' => $taskInfo['taskDate']
				);
				$taskWhere = array(
					'taskId' => $taskInfo['taskId']	
				);
				$this->task_db->update($taskUpdate,$taskWhere);
				if($taskInfo['taskStatus'] != '1')
				{
					$msg = "操作成功,请注意：此推荐任务列表内容已变化，需要重新提交审核，并发布才能生效！";
					showmessage($msg,HTTP_REFERER,30000);
				}else{
					$msg = '操作成功!';
					showmessage($msg,HTTP_REFERER);
				}
				//回滚到默认编辑状态优化end 
			
			}else{
				showmessage('操作错误或数据不存在!',HTTP_REFERER);
			}
		} else {
			showmessage(L('operation_failure'),HTTP_REFERER);
		}
	}
	/*
	 * 应用图片列表
	 */
	public function spic_list(){
		$this->app_image = pc_base::load_model('app_image_model');
		$app_id = trim($_GET['app_id']);
		$piclist = $this->app_image->select(array('app_id'=>$app_id));
		include $this->admin_tpl('app_pic');
	}
	/*
	 * 添加应用图片
	*/
	public function shop_addpic(){
		$this->app_image = pc_base::load_model('app_image_model');
		$app_id = trim($_GET['app_id']);
		include $this->admin_tpl('app_addpic');
	}
	public function shop_addpicdo(){
		$app_id = trim($_POST['app_id']);
		$image_type = trim($_POST['image_type']);
		$ad_imgUrl = $_POST['ad_imgUrl'];
		if(strpos($ad_imgUrl,'http://')!== false){
		}else{
			$ad_imgUrl = TASK_IMG_PATH.$ad_imgUrl;
		}
		$this->app_image = pc_base::load_model('app_image_model');
		if(empty($app_id)||empty($ad_imgUrl)){
			$msg = '提交失败!';
			showmessage($msg,'?m=go3c&c=task_shop&a=spic_list&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			$data = array(
					'app_id'=>$app_id,
					'image_type'=>$image_type,
					'image_file'=>$ad_imgUrl,
					'term_id'=>'0',
					'os_type'=>'0',
					'seq_number'=>'0'
			);
			$this->app_image->insert($data);
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=task_shop&a=spic_list&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	/*
	 * 编辑应用图片
	 */
	public function edit_appic(){
		$this->app_image = pc_base::load_model('app_image_model');
		$id = trim($_GET['id']);
		$data = $this->app_image->get_one(array('id'=>$id));
		include $this->admin_tpl('app_editpic');
	}
	public function edit_appicdo(){
		$this->app_image = pc_base::load_model('app_image_model');
		$app_id = trim($_POST['app_id']);
		$ad_imgUrl = trim($_POST['ad_imgUrl']);
		$image_type = trim($_POST['image_type']);
		$id = trim($_POST['id']);
		if(strpos($ad_imgUrl,'http://')!== false){
		}else{
			$ad_imgUrl = TASK_IMG_PATH.$ad_imgUrl;
		}
		if(empty($ad_imgUrl)){
			$msg = '提交失败!';
			showmessage($msg,'?m=go3c&c=task_shop&a=spic_list&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			$data = array(
					'app_id'=>$app_id,
					'image_type'=>$image_type,
					'image_file'=>$ad_imgUrl,
					'term_id'=>'0',
					'os_type'=>'0',
					'seq_number'=>'0'
			);
			$this->app_image->update($data,array('id'=>$id));
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=task_shop&a=spic_list&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	//删除应用图片
	public function delete_appic(){
		$this->app_image = pc_base::load_model('app_image_model');
		$image_file = trim($_GET['image_file']);
		$data = $this->app_image->get_one(array('image_file'=>$image_file));
		$this->app_image->delete(array('image_file'=>$image_file));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=task_shop&a=spic_list&app_id='.$data['app_id'].'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	/*
	 * 应用apk链接列表
	*/
	public function sd_info(){
		$this->app_download_info = pc_base::load_model('app_download_info_model');
		$app_id = trim($_GET['app_id']);
		$download_info = $this->app_download_info->select(array('app_id'=>$app_id));
		include $this->admin_tpl('app_download');
	}
	/*
	 * 添加应用apk模型
	 */
	public function shop_addapk(){
		$this->app_download_info = pc_base::load_model('app_download_info_model');
		$app_id = trim($_GET['app_id']);
		include $this->admin_tpl('app_addapk');
	}
	public function shop_addapkdo(){
		$this->app_download_info = pc_base::load_model('app_download_info_model');
		$app_id = trim($_POST['app_id']);
		$ad_imgUrl = TASK_IMG_PATH.trim($_POST['ad_imgUrl']);
		if(empty($app_id)||empty($ad_imgUrl)){
			$msg = '提交失败!';
			showmessage($msg,'?m=go3c&c=task_shop&a=sd_info&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			$data = array(
					'app_id'=>$app_id,
					'install_file'=>$ad_imgUrl,
					'term_id'=>'0',
					'os_type'=>'0'
			);
			$this->app_download_info->insert($data);
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=task_shop&a=sd_info&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	/*
	 * 编辑应用apk模型
	 */
	public function edit_appapk(){
		$this->app_download_info = pc_base::load_model('app_download_info_model');
		$install_file = trim($_GET['install_file']);
		$data = $this->app_download_info->get_one(array('install_file'=>$install_file));
		include $this->admin_tpl('app_editapk');
	}
	public function edit_appapkdo(){
		$app_id = trim($_POST['app_id']);
		$ad_imgUrl = trim($_POST['ad_imgUrl']);
		$image_id = trim($_POST['image_id']);
		$this->app_download_info = pc_base::load_model('app_download_info_model');
		if(strpos($ad_imgUrl,'http://')!== false){
		}else{
			$ad_imgUrl = TASK_IMG_PATH.$ad_imgUrl;
		}
		if(empty($ad_imgUrl)){
			$msg = '提交失败!';
			showmessage($msg,'?m=go3c&c=task_shop&a=sd_info&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			$data = array(
					'app_id'=>$app_id,
					'install_file'=>$ad_imgUrl,
					'term_id'=>'0',
					'os_type'=>'0'
			);
			$this->app_download_info->update($data,array('install_file'=>$image_id));
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=task_shop&a=sd_info&app_id='.$app_id.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	//删除应用链接
	public function delete_appapk(){
		$this->app_download_info = pc_base::load_model('app_download_info_model');
		$install_file = trim($_GET['install_file']);
		$data = $this->app_download_info->get_one(array('install_file'=>$install_file));
		$this->app_download_info->delete(array('install_file'=>$install_file));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=task_shop&a=sd_info&app_id='.$data['app_id'].'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	//添加客户端apk模型
	public function add_rdapp(){
		$this->rdapp_model = pc_base::load_model('tv_3rdapp_model');
		//获取类型分类
		$fdt  = '*';
		$wht = "3rdapp WHERE 1 group by type ";
		$order  	= '';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$type_list = $this->rdapp_model->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('rdapp_add');
	}
	//添加客户端apk动作
	public function add_rdappdo(){
		$this->rdapp_model = pc_base::load_model('tv_3rdapp_model');
		$name = trim($_POST['name']);
		$type = trim($_POST['type']);
		$classname = trim($_POST['classname']);
		if(strpos($_POST['ad_imgUrl'],'http://') !== false){
			$ad_imgUrl = trim($_POST['ad_imgUrl']);
		}else{
			$ad_imgUrl = TASK_IMG_PATH.trim($_POST['ad_imgUrl']);
		}
		if(strpos($_POST['ad_imgUrl1'],'http://') !== false){
			$ad_imgUrl1 = trim($_POST['ad_imgUrl1']);
		}else{
			$ad_imgUrl1 = TASK_IMG_PATH.trim($_POST['ad_imgUrl1']);
		}
		//$ad_imgUrl = TASK_IMG_PATH.trim($_POST['ad_imgUrl']);
		//$ad_imgUrl1 = TASK_IMG_PATH.trim($_POST['ad_imgUrl1']);
		if(empty($name)||empty($classname)||empty($ad_imgUrl)||empty($ad_imgUrl1)){
			$msg = '提交失败!';
			showmessage($msg,'?m=go3c&c=shop&a=rdapp_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			$data = array(
					'name'=>$name,
					'type'=>$type,
					'classname'=>$classname,
					'os'=>'android',
					'icon_url'=>$ad_imgUrl1,
					'install_url'=>$ad_imgUrl
			);
			$this->rdapp_model->insert($data);
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=shop&a=rdapp_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	//修改客户端apk模型
	public function edit_rdapp(){
		$id = trim($_GET['id']);
		$this->rdapp_model = pc_base::load_model('tv_3rdapp_model');
		$data = $this->rdapp_model->get_one(array('id'=>$id));
		//获取类型分类
		$fdt  = '*';
		$wht = "3rdapp WHERE 1 group by type ";
		$order  	= '';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$type_list = $this->rdapp_model->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('rdapp_edit');
	}
	//修改客户端apk操作
	public function edit_rdappdo(){
		$this->rdapp_model = pc_base::load_model('tv_3rdapp_model');
		$id = trim($_POST['id']);
		$name = trim($_POST['name']);
		$type = trim($_POST['type']);
		$classname = trim($_POST['classname']);
		if(strpos($_POST['ad_imgUrl'],'http://') !== false){
			$ad_imgUrl = trim($_POST['ad_imgUrl']);
		}else{
			$ad_imgUrl = TASK_IMG_PATH.trim($_POST['ad_imgUrl']);
		}
		if(strpos($_POST['ad_imgUrl1'],'http://') !== false){
			$ad_imgUrl1 = trim($_POST['ad_imgUrl1']);
		}else{
			$ad_imgUrl1 = TASK_IMG_PATH.trim($_POST['ad_imgUrl1']);
		}
		//$ad_imgUrl = trim($_POST['ad_imgUrl']);
		//$ad_imgUrl1 = TASK_IMG_PATH.trim($_POST['ad_imgUrl1']);
		if(empty($name)||empty($classname)||empty($ad_imgUrl)||empty($ad_imgUrl1)){
			$msg = '提交失败!';
			showmessage($msg,'?m=go3c&c=shop&a=rdapp_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}else{
			$data = array(
					'name'=>$name,
					'type'=>$type,
					'classname'=>$classname,
					'os'=>'android',
					'icon_url'=>$ad_imgUrl,
					'install_url'=>$ad_imgUrl1
			);
			$this->rdapp_model->update($data,array('id'=>$id));
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=shop&a=rdapp_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	//删除客户端apk
	public function delete_rdapp(){
		$this->rdapp_model = pc_base::load_model('tv_3rdapp_model');
		$id = trim($_GET['id']);
		$this->rdapp_model->delete(array('id'=>$id));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=shop&a=rdapp_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//生成推荐位的json文件
	public function createjson(){
		$url = 'http://www.go3c.tv:8060/go3cauth/app.php?m=getapprecom&cover=1';
		$RawurlStr = file_get_contents($url);
		if(empty($RawurlStr)){
			$msg = '抱歉,生成异常!';
			showmessage($msg,'?m=go3c&c=task_shop&a=task&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$xmlurl = '/home/wwwroot/default/upgrade/android/stb/GO3CKTVTEST/A20/Recommon_Data/cache/datanew/apprecom.json';
		//$newfile = '../go3c/ktv/cache/apprecom.json';
		$fp=fopen("$xmlurl","w");
		fwrite($fp,$RawurlStr);
		@fclose($fp);
		chmod("$xmlurl",0777);
		$datalist = json_decode($RawurlStr,true);
		foreach($datalist as $v){
			$url = $v['icon'];
			$path = '/home/wwwroot/default/upgrade/android/stb/GO3CKTVTEST/A20/Recommon_Data/cache/datanew/';
			self::down_image($url,$path);
		}
		//生成zip压缩包
		$zipurl = 'http://www.go3c.tv:8060/go3ccms/zip_php.php?spid=GO3CKTVTEST&board_type=A20';
		$tmp2 = file_get_contents($zipurl);
		$msg = '生成成功!';
		showmessage($msg,'?m=go3c&c=task_shop&a=task&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//生成指定推荐位应用的json文件
	public function createtaskjson(){
		$spid = 'GO3CKTVTEST';
		$sbd = 'A20';
		$tream = 'stb';
		$this->app_image = pc_base::load_model('app_image_model');
		$this->app_download_info = pc_base::load_model('app_download_info_model');
		$taskId = trim($_GET['taskId']);
		if(empty($taskId)){
			$msg = '抱歉,数据异常!';
			showmessage($msg,'?m=go3c&c=task_shop&a=task&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$task_name = $this->task_db->get_one(array('taskId'=>$taskId));
		if($task_name['task_name']=='apk_daemon'){
			$where = "app_recommend AS t LEFT JOIN app AS c ON t.app_id = c.app_id WHERE t.type='$taskId'";
		}else{
			$where = "app_recommend AS t LEFT JOIN t_app_all_list AS c ON t.app_id = c.app_id WHERE t.type='$taskId'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$recomapp  = $this->app_recommend->mylistinfo('t.type,c.*', $where, $order = ' ORDER BY t.seq_number ASC', $page, $pagesize = 50);
		$app = array();
		foreach ($recomapp as $key=>$v){		
			if($task_name['task_name']=='bootlive'){
				$app[$key]['name'] = $v['app_name'];
				$app[$key]['os'] = 'android';
				$app[$key]['type'] = 'live';
				$app[$key]['className'] = $v['packagename'];
				$app[$key]['iconUrl'] = $v['image_file'];
				$app[$key]['installUrl'] = $v['install_file'];
				$url = $v['image_file'];
				$path = '/home/wwwroot/default/upgrade/android/stb/GO3CKTVTEST/A20/Recommon_Data/cache/datanew/';
				self::down_image($url,$path);
			}elseif($task_name['task_name']=='apk_preinstall'){
				$url = 'http://www.go3c.tv:8060/go3cauth/app.php?m=getpreinstallapp&cover=1';
				$php_json = file_get_contents($url);
				if(empty($php_json)){
					$msg = '抱歉,生成异常!';
					showmessage($msg,'?m=go3c&c=task_shop&a=task&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
				}
			}elseif($task_name['task_name']=='apk_daemon'){
				$app[$key]['title'] = $v['app_name'];
				$app[$key]['packagename'] = $v['packagename'];
			}else{
				$app[$key]['id'] = $v['app_id'];
				$app[$key]['title'] = $v['app_name'];
				$app[$key]['packagename'] = $v['packagename'];
				$app[$key]['file_path'] = $v['install_file'];
				$app[$key]['total'] = $v['total'];
				$app[$key]['score'] = $v['score'];
				$app[$key]['version'] = $v['version'];
				$app[$key]['channel'] = $v['channel'];
				$app[$key]['recomm_type'] = $v['type'];
				$app[$key]['size'] = $v['file_size'];
				$app[$key]['description'] = $v['app_desc'];
				if($task_name['task_name']=='recomm_browser'){  //浏览器客户端增加一个type
					$app[$key]['type'] = 'apk';
				}
				$app_id = $v['app_id'];
				$term = $this->app_download_info->get_one(array('app_id'=>$app_id));
				$app[$key]['term_id'] = $term['term_id'];
				$where = "app_id = $app_id";
				$image = $this->app_image->select($where, '*');
				foreach ($image as $k=>$vi){
					$listimg[$k]['imageSeq'] = $vi['seq_number'];
					$listimg[$k]['imageURL'] = $vi['image_file'];
					$listimg[$k]['imageType'] = $vi['image_type'];
					$url = $vi['image_file'];
					$path = '/home/wwwroot/default/upgrade/android/stb/GO3CKTVTEST/A20/Recommon_Data/cache/datanew/';
					self::down_image($url,$path);
				}
				$app[$key]['images'] = $listimg;
			}			
		}
		if($task_name['task_name']!='apk_preinstall'){
			$php_json = json_encode($app);
		}
		if(empty($php_json)){
			$msg = '抱歉,生成异常!';
			showmessage($msg,'?m=go3c&c=task_shop&a=task&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$xmlurl = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/'.$task_name['task_name'].'.json';
		$fp=fopen("$xmlurl","w");
		fwrite($fp,$php_json);
		@fclose($fp);
		chmod("$xmlurl",0777);
		//更新升级xml文件
		$sjxml = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/appresources.xml';
		$jszip = 'http://www.go3c.tv:8060/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/';
		self::appresources($spid,$sjxml,$jszip);
		$msg = '生成成功!';
		showmessage($msg,'?m=go3c&c=task_shop&a=task&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//更新升级xml文件
	public function appresources($spid,$sjxml,$jszip){
		$tnum = time();
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml .= "<Resources>\n";
		$xml .="<projid>".$spid."</projid>\n";
		$xml .="<versioncode>".$tnum."</versioncode>\n";
		$xml .="<url>".$jszip."appresources.zip</url>\n";
		$xml .="</Resources>\n";
		$fp=fopen("$sjxml","w");
		fwrite($fp,$xml);
		@fclose($fp);
		//生成zip压缩包
		//$zipurl = 'http://www.go3c.tv:8060/go3ccms/zip_php.php';
		$zipurl = 'http://www.go3c.tv:8060/go3ccms/zip_php.php?spid='.$spid.'&board_type=A20';
		$tmp2 = file_get_contents($zipurl);
	}
	//添加应用外链
	public function add_applink(){
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		if($_SESSION['roleid']=='1'){
			$awhere = "";
		}else{
			$awhere = " 1 AND spid in ('".$_SESSION['spid']."')";
		}
		$sp_list = $this->cms_spid->select($awhere);
		include $this->admin_tpl('app_addlink');
	}
	public function add_applinkdo(){
		$this->app_link = pc_base::load_model('app_link_model');
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		$seq_number = $_REQUEST['seq_number'];
		$title = $_REQUEST['title'];
		$file_path = $_REQUEST['videoImgUrl'];
		$url = $_REQUEST['url'];
		$spid = $_REQUEST['spid'];
		if(!empty($spid)){
			$sp = $this->cms_spid->get_one(array('spid'=>$spid));
		}
		if(empty($title)||empty($file_path)||empty($url)){
			exit('操作错误或数据异常!');
		}
		$insert_data = array(
				'seq_number' => $seq_number?$seq_number:'',
				'title' 	 => $title,
				'file_path'  => $file_path,
				'type' 		 => 'url',
				'url' 		 => $url,
				'spid' 		 => $spid,
				'statue' 	 => '1',
				'board_type' => $sp['board_type'],
				'createtime' =>time()
				);
		$this->app_link->insert($insert_data);
		$msg = '添加成功!';
		showmessage($msg,'?m=go3c&c=shop&a=link_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//修改应用外链
	public function edit_applink(){
		$id= $_GET['id'];
		$this->app_link = pc_base::load_model('app_link_model');
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		if(empty($id)){
			exit('操作错误或数据异常!');
		}
		if($_SESSION['roleid']=='1'){
			$awhere = " 1 group by spid";
		}else{
			$awhere = " 1 AND spid in ('".$_SESSION['spid']."') group by spid";
		}
		$sp_list = $this->cms_spid->select($awhere);
		$applist = $this->app_link->get_one(array('id'=>$id));
		include $this->admin_tpl('app_editlink');
	}
	public function edit_applinkdo(){
		$this->app_link = pc_base::load_model('app_link_model');
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		$seq_number = trim($_POST['seq_number']);
		$title = trim($_POST['title']);
		$url = trim($_POST['url']);
		$file_path = trim($_POST['ad_imgUrl']);
		$id = trim($_POST['id']);
		$spid = $_REQUEST['spid'];
		if(!empty($spid)){
			$sp = $this->cms_spid->get_one(array('spid'=>$spid));
		}
		if(empty($title)||empty($file_path)||empty($url)||empty($id)){
			exit('操作错误或数据异常!');
		}
		$data = array(
				'seq_number' => $seq_number?$seq_number:'',
				'title' 	 => $title,
				'file_path'  => $file_path,
				'type' 		 => 'url',
				'url' 		 => $url,
				'spid' 		 => $spid,
				'statue' 	 => '1',
				'board_type' => $sp['board_type'],
				'createtime' =>time()
		);
		$this->app_link->update($data,array('id' => $id));		
		showmessage('提交成功',HTTP_REFERER);
	}
	//删除外链应用
	public function delete_applink(){
		$this->app_link = pc_base::load_model('app_link_model');
		$id = trim($_GET['id']);
		$this->app_link->delete(array('id'=>$id));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=shop&a=link_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//生成外链应用的json文件
	public function createlinkjson(){		
		$this->app_link = pc_base::load_model('app_link_model');
		if($_SESSION['roleid']=='1'){
			$spid = 'GO3CKTVTEST';
			$sbd = 'A20';
			$tream = 'stb';
			$recomapplink = $this->app_link->select();
			$app = array();
			foreach ($recomapplink as $key=>$v){
				$app[$key]['title'] = $v['title'];
				$app[$key]['type'] = $v['type'];
				$app[$key]['file_path'] = $v['file_path'];
				$app[$key]['url'] = $v['url'];
				$url = $v['file_path'];
				$path = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/';
				self::down_image($url,$path);
			}
			$php_json = json_encode($app);
			if(empty($php_json)){
				$msg = '抱歉,生成异常!';
				showmessage($msg,'?m=go3c&c=shop&a=link_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
			}
			$xmlurl = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/recomm_url.json';
			$fp=fopen("$xmlurl","w");
			fwrite($fp,$php_json);
			@fclose($fp);
			chmod("$xmlurl",0777);
			
			//更新升级xml文件
			$sjxml = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/appresources.xml';
			$jszip = 'http://www.go3c.tv:8060/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/';
			self::appresources($spid,$sjxml,$jszip);
		}else{
			$spp = $_SESSION['spid'];
			$arr = explode(',',$spp);
			$sbd = 'A20';
			$tream = 'stb';
			foreach ($arr as $spid){
				$awh = "spid = '.$spid.'";
				$recomapplink = $this->app_link->select($awh);
				$app = array();
				foreach ($recomapplink as $key=>$v){
					$app[$key]['title'] = $v['title'];
					$app[$key]['type'] = $v['type'];
					$app[$key]['file_path'] = $v['file_path'];
					$app[$key]['url'] = $v['url'];
					$url = $v['file_path'];
					$path = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/';
					self::down_image($url,$path);
				}
				$php_json = json_encode($app);
				if(empty($php_json)){
					$msg = '抱歉,生成异常!';
					showmessage($msg,'?m=go3c&c=shop&a=link_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
				}
				$xmlurl = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/recomm_url.json';
				$fp=fopen("$xmlurl","w");
				fwrite($fp,$php_json);
				@fclose($fp);
				chmod("$xmlurl",0777);
				
				//更新升级xml文件
				$sjxml = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/appresources.xml';
				$jszip = 'http://www.go3c.tv:8060/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/';
				self::appresources($spid,$sjxml,$jszip);
			}
		}		
		$msg = '生成成功!';
		showmessage($msg,'?m=go3c&c=shop&a=link_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//下载远程的图片到本地服务器
	public function down_image($url,$path){
		$newfname = $path . basename($url);
		$file = fopen ($url, "rb");
		if ($file) {
			$newf = fopen ($newfname, "wb");
			if ($newf)
				while(!feof($file))
			{
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
		}
		if ($file) 
		{
			fclose($file);
		}
		if ($newf) {
			fclose($newf);
		}
	}
	//推荐跑马灯列表
	public function recommarquee(){
		$this->go3capi_recom = pc_base::load_model('go3capi_recom_model');
		$field    	= '*';
		$sql     	= "auth_recom WHERE 1 ";
		$order  	= 'ORDER BY id desc';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->go3capi_recom->mynum($sql);
		$totalpage	= $this->go3capi_recom->mytotalpage($sql, $perpage);
		$data 		= $this->go3capi_recom->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->go3capi_recom->pages;
		include $this->admin_tpl('shop_recommarquee');	
	}
	//添加跑马灯
	public function shop_marqueeadd(){
		include $this->admin_tpl('shop_marqueeadd');
	}
	public function shop_marqueeadddo(){
		$this->go3capi_recom = pc_base::load_model('go3capi_recom_model');
		$description = trim($_POST['description']);
		$status = trim($_POST['status']);
		$starttime = trim($_POST['starttime']);
		$endtime = trim($_POST['endtime']);
		
		$da = array(
			'description' 	 => $description,
			'status' 	 => $status,
			'starttime' 	 => strtotime($starttime),
			'endtime' 	 => strtotime($endtime)
		);
		$this->go3capi_recom->insert($da);
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=task_shop&a=recommarquee&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//编辑跑马灯
	public function shop_marqueedit(){
		$id = $_GET['id'];
		$this->go3capi_recom = pc_base::load_model('go3capi_recom_model');
		$data = $this->go3capi_recom->get_one(array('id'=>$id));
		include $this->admin_tpl('shop_marqueeedit');
	}
	public function shop_marqueeditdo(){
		$this->go3capi_recom = pc_base::load_model('go3capi_recom_model');
		$id = trim($_POST['id']);
		$description = trim($_POST['description']);
		$status = trim($_POST['status']);
		$starttime = trim($_POST['starttime']);
		$endtime = trim($_POST['endtime']);
		
		$da = array(
			'description' 	 => $description,
			'status' 	 => $status,
			'starttime' 	 => strtotime($starttime),
			'endtime' 	 => strtotime($endtime)
		);
		$this->go3capi_recom->update($da,array('id'=>$id));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=task_shop&a=recommarquee&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//删除推荐跑马灯
	public function shop_marquedel(){
		$id = $_GET['id'];
		$this->go3capi_recom = pc_base::load_model('go3capi_recom_model');
		$this->go3capi_recom->delete(array('id'=>$id));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=task_shop&a=recommarquee&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
}