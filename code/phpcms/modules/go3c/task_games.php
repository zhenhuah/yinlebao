<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('admin', 'admin', 0);

//服务器的图片路径地址

define('TASK_IMG_PATH','http://192.168.150.91/images/go3ccms/');
//define('TASK_IMG_PATH','http://go3c.jdjkcn.net/');

//本地的客户端的图片域名(项目域名)(同步的时候用)
define('TASK_IMG_PATH_Local','http://192.168.150.91/go3ccms/');
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

class task_games extends admin {
	function __construct() {
		parent::__construct();
		pc_base::load_app_func('global_task');	//方法文件
		
		$this->task_db = pc_base::load_model('game_task_model');			//任务信息表连接
		$this->task_game = pc_base::load_model('task_game_model');//应用任务数据表连接
		$this->spid_db = pc_base::load_model('admin_model');			//后台登录表连接
		$this->term_type = pc_base::load_model('term_type_model');		//NJPHP 加载新增的终端类型表
		$this->game = pc_base::load_model('games_model');
		$this->app_type = pc_base::load_model('shop_type_model');
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));	
		$this->game_image_db = pc_base::load_model('games_image_model');
	}
	
	/*
	* 默认当前用户的数据列表
	*/
	public function init(){
	}
	
	/*
	 * 添加应用
	*/
	public function add_game(){
		$type_name_list = $this->app_type->select('pid = 10', 'cat_id, cat_name');
		include $this->admin_tpl('games_add');
	}
	
	/*
	 * 添加游戏
	 */
	public function add_game_do(){
		//插入应用信息
		$app_name = $_POST['app_name'];
		$app_desc = $_POST['app_desc'];
		$term = $_POST['term'];
		$owner = $_POST['owner'];
		$language = $_POST['language'];
		$file_hash = $_POST['file_hash'];
		$packagename = $_POST['packagename'];
		$os_ver = $_POST['os_ver'];
		$screen = $_POST['screen'];
		$score = $_POST['score'];
		$tag = $_POST['tag'];
		$seq = $_POST['seq'];
		$view_count = $_POST['view_count'];
		$download_count = $_POST['download_count'];
		$create_time = date('Y-m-d H:i:s');
		$update_time = date('Y-m-d H:i:s');
		$status = $_POST['status'] != '' ? $_POST['status'] : 1;
		$widgetProvider = $_POST['widgetProvider'];
		$yufabu_date = $_POST['yufabu_date'];
		$channel = $_POST['channel'];
		
		$where = "cat_name = '$channel'";
		$apptype = $this->app_type->select($where, 'pid');
		$type = 'app';
		if ($apptype[0]['pid'] == 10) 
			$type = 'game';
		$price = $_POST['price'];
		$source = $_POST['source'];
		
		if (isset($_POST['ishandle']))
			$ishandle = 1;
		else
			$ishandle = 0;
		if (isset($_POST['iskeyboard']))
			$iskeyboard = 1;
		else
			$iskeyboard = 0;
		if (isset($_POST['ismouse']))
			$ismouse = 1;
		else
			$ismouse = 0;
		if (isset($_POST['iscontroller']))
			$iscontroller = 1;
		else
			$iscontroller = 0;
		if (isset($_POST['isweb']))
			$isweb = 1;
		else
			$isweb = 0;
		if (isset($_POST['neednet']))
			$neednet = 1;
		else
			$neednet = 0;
			
		$insertdata = array(
			'title'			=>	$app_name,
			'desc'			=> 	$app_desc,
			'term'				=>	$term,
			'owner'				=>	$owner,
			'language'			=>	$language,
			'file_hash'			=>	$file_hash,
			'packagename'		=>	$packagename,
			'os_ver'			=>	$os_ver,
			'screen'			=>	$screen,
			'score'				=>	$score,
			'tag'				=>	$tag,
			'seq'				=>	$seq,
			'view_count'		=>	$view_count,
			'download_count'	=>	$download_count,
			'create_time'		=>	$create_time,
			'update_time'		=>	$update_time,
			'status'			=>	$status,
			'widgetProvider'	=>	$widgetProvider,
			'yufabu_date'		=>	$yufabu_date,
			'channel'			=>	$channel,
			'type'				=>	$type,
			'price'				=>	$price,
			'source'			=>	$source,
			'is_handle'			=>	$ishandle,
			'is_keyboard'		=>	$iskeyboard,
			'is_mouse'			=>	$ismouse,
			'is_controller'		=>	$iscontroller,
			'is_web'			=>	$isweb,
			'need_net'			=>	$neednet
		);
		$this->game->insert($insertdata);
		
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=games&a=shop_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 修改游戏
	 */
	public function edit_app(){
		$id = $_REQUEST['id'];
		//当前game
		$aKey = "id = '".$id."'";
		$game = $this->game->get_one($aKey);
		$termArr = array('请选择','STB','IOS','ANDROID','PC','FLASH');
		$selectHtml = '<select name="term" id="term">';
		foreach ($termArr as $k => $term) {
			if ($k == $game['term'])
				$selectHtml .= '<option value="'.$k.'" selected>'.$term.'</option>';
			else 
				$selectHtml .= '<option value="'.$k.'">'.$term.'</option>';
		}
		//所有game类型
		$type_name_list = $this->app_type->select('pid = 10', 'cat_id, cat_name');
		//当前app的icon image
		/*
		$this->app_image = pc_base::load_model('app_image_model');
		$iconwhere = "app_id = $id AND image_type = 3";
		$icon = $this->app_image->get_one($iconwhere);
		$icon = str_replace(TASK_IMG_PATH, '', $icon['image_file']);
		$imagewhere = "app_id = $id AND image_type = 2";
		$image = $this->app_image->get_one($imagewhere);
		$image = str_replace(TASK_IMG_PATH, '', $image['image_file']);
		*/
		include $this->admin_tpl('games_edit');
	}
	
	function edit_game_do() {
		//更新游戏信息
		$id = $_REQUEST['game_id'];
		$app_name = $_POST['app_name'];
		$app_desc = $_POST['app_desc'];
		$term = $_POST['term'];
		$owner = $_POST['owner'];
		$language = $_POST['language'];
		$file_hash = $_POST['file_hash'];
		$packagename = $_POST['packagename'];
		$os_ver = $_POST['os_ver'];
		$screen = $_POST['screen'];
		$score = $_POST['score'];
		$tag = $_POST['tag'];
		$seq = $_POST['seq'];
		$view_count = $_POST['view_count'];
		$download_count = $_POST['download_count'];
		$create_time = date('Y-m-d H:i:s');
		$update_time = date('Y-m-d H:i:s');
		$status = $_POST['status'] != '' ? $_POST['status'] : 1;
		$widgetProvider = $_POST['widgetProvider'];
		$yufabu_date = $_POST['yufabu_date'];
		$apptest = $_POST['apptest'];
		$channel = $_POST['channel'];
		
		$where = "cat_name = '$channel'";
		$apptype = $this->app_type->select($where, 'pid');
		$type = 'app';
		if ($apptype[0]['pid'] == 10) 
			$type = 'game';
		
		$active = $_POST['active'];
		$price = $_POST['price'];
		$source = $_POST['source'];
		
		if (isset($_POST['ishandle']))
			$ishandle = 1;
		else
			$ishandle = 0;
		if (isset($_POST['iskeyboard']))
			$iskeyboard = 1;
		else
			$iskeyboard = 0;
		if (isset($_POST['ismouse']))
			$ismouse = 1;
		else
			$ismouse = 0;
		if (isset($_POST['iscontroller']))
			$iscontroller = 1;
		else
			$iscontroller = 0;
		if (isset($_POST['isweb']))
			$isweb = 1;
		else
			$isweb = 0;
		if (isset($_POST['neednet']))
			$neednet = 1;
		else
			$neednet = 0;
			
		$insertdata = array(
			'title'			=>	$app_name,
			'desc'			=> 	$app_desc,
			'term'				=>	$term,
			'owner'				=>	$owner,
			'language'			=>	$language,
			'file_hash'			=>	$file_hash,
			'packagename'		=>	$packagename,
			'os_ver'			=>	$os_ver,
			'screen'			=>	$screen,
			'score'				=>	$score,
			'tag'				=>	$tag,
			'seq'				=>	$seq,
			'view_count'		=>	$view_count,
			'download_count'	=>	$download_count,
			'create_time'		=>	$create_time,
			'update_time'		=>	$update_time,
			'status'			=>	$status,
			'widgetProvider'	=>	$widgetProvider,
			'yufabu_date'		=>	$yufabu_date,
			'apptest'			=>	$apptest,
			'channel'			=>	$channel,
			'type'				=>	$type,
			'active'			=>	$active,
			'price'				=>	$price,
			'source'			=>	$source,
			'is_handle'			=>	$ishandle,
			'is_keyboard'		=>	$iskeyboard,
			'is_mouse'			=>	$ismouse,
			'is_controller'		=>	$iscontroller,
			'is_web'			=>	$isweb,
			'need_net'			=>	$neednet
		);
		$this->game->update($insertdata,array('id'=>$id));
		
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=games&a=shop_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 添加游戏图片
	 */
	public function add_game_image(){
		$gameid = $_GET['id'];
		include $this->admin_tpl('games_image_add');
	}
	
	public function add_game_image_do() {
		$gameid = $_POST['gameid'];
		$sort = $_POST['sort'];
		$type = $_POST['type'];
		$url = $_POST['url'];
		$create_date = date('Y-m-d', time());
		$data = array(
			'game_id'		=>	$gameid,
			'sort'			=>	$sort,
			'type'			=>	$type,
			'url'			=>	$url,
			'create_date'	=>	$create_date
		);
		if ($type == 1) {
			//只保存一张icon
			$icon = $this->game_image_db->get_one(array('game_id'=>$gameid,'type'=>$type));
			if ($icon !== false)
				$msg = '提交成功!';
				showmessage($msg,'?m=go3c&c=games&a=games_image_list&id='.$gameid.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		} else {
			$this->game_image_db->insert($data);
			$msg = '提交成功!';
			showmessage($msg,'?m=go3c&c=games&a=games_image_list&id='.$gameid.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
	}
	//修改游戏图片
	public function game_image_editdo(){
		$id = $_POST['id'];
		$gameid = $_POST['gameid'];
		$sort = $_POST['sort'];
		$type = $_POST['type'];
		$url = $_POST['url'];
		$create_date = date('Y-m-d', time());
		$data = array(
				'sort'			=>	$sort,
				'type'			=>	$type,
				'url'			=>	$url,
				'create_date'	=>	$create_date
		);
		if(empty($id)){
			$msg = '提交失败!,id不能为空!';
		}else{
			$msg = '提交成功!';
			$this->game_image_db->update($data,array('id'=>$id));
		}		
		showmessage($msg,'?m=go3c&c=games&a=games_image_list&id='.$gameid.'&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	/*
	 * 任务列表
	*/
	public function task() {
		$posid_list = self::posidInfoAllTerms();	//推荐位列表
		$spid = $this->current_spid['spid'];
		$term_type_list = array();
		$term_type_list = $this->term_type->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
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
		$where = "game_task AS t LEFT JOIN app_channel_category AS c ON t.app_type_id = c.cat_id WHERE";
		$where .= " spid = '".$this->current_spid['spid']."' ";
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
	
		include $this->admin_tpl('task_list_game');
	}
	/*
	 * 当前用户所属运营商下面的推荐位数据列表
	*/
	private function posidInfoAllTerms() {
		//加入判断 先选择终端类型ID start
		$where = " spid = '".$this->current_spid['spid']."' ";
		//终端下的推荐位列表 game_position
		$this->posid_db = pc_base::load_model('position_game_model');	//推荐位信息表连接
		$posid_where = " spid = '".$this->current_spid['spid']."'";
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
	 * 得到一条任务数据
	*/
	private function getOneTask($taskId) {
		$taskId = trim($taskId);	//任务ID
		if(!empty($taskId) && is_numeric($taskId))
		{
			$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
			return $taskInfo = $this->task_db->get_one($aKey);	//是否存在
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
		$position_type_list = $this->app_type->select($where = 'pid = 10', $data = 'cat_id,cat_name');
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
							'spid' => $spid,
							'posidInfo' => $posidInfo,
							'start_end_nums' => $posid_list[$posid]['minnum'].'-'.$posid_list[$posid]['maxnum'],
							'app_type_id' => $typeid,
							'imgType' => 'text',	//默认图片类型
							'taskDate' => strtotime($taskDate),
							'taskStatus' => '1',	//默认编辑状态
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
		
		include $this->admin_tpl('task_game_add');
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
	
		include $this->admin_tpl('task_game_add');
	}
	/*
	 * 选择可以推荐的游戏
	 */
	public function addgamestask(){
		$this->game = pc_base::load_model('games_model');
		$taskId   	= trim($_GET['taskId']);
		$id   	= trim($_GET['id']);
		$pkey="id ='".$id."'";
		$videoInfo = $this->game->get_one($pkey);
		include $this->admin_tpl('game_task_add');
	}
	/*
	 * 添加推荐游戏
	*/
	public function addgametaskdo(){		
		$id = trim($_POST['id']);
		$taskId = trim($_POST['taskId']);
		$videoTitle = trim($_POST['videoTitle']);
		$long_desc = trim($_POST['long_desc']);
		$videoImgUrl = trim($_POST['videoImgUrl']);
		$infor_url = trim($_POST['infor_url']);

		$this->game = pc_base::load_model('games_model');
		$this->game_task_game = pc_base::load_model('game_task_game_model');
		$gkey="id = $id";
		//查询该资讯是否已经加入该推荐位
		$pkey="taskId ='".$taskId."' and videoId='".$id."'";
		$gtaskInfo = $this->game_task_game->get_one($pkey);

		if(!empty($gtaskInfo)) showmessage('该游戏已经添加到推荐位!',HTTP_REFERER);
		if(empty($videoImgUrl)) showmessage('请设置海报!',HTTP_REFERER);
		//查询该推荐位能添加的个数(shop->game_task表)
		$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
		$updateVideNums = $taskInfo['videoNums'] + 1;
		if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1])){  //该推荐为的数量在范围内
			$insert_data = array(
					'videoId' => $id,
					'taskId' => $taskId,			            //任务ID
					'videoTitle' => $videoTitle,						//名称
					'videoPlayUrl' => $infor_url ? $infor_url : '',	//链接地址
					'videoDesc' => $long_desc,						//游戏简介
					'videoImg' => $videoImgUrl,
					'status' => 'Y',
					'spid' => $this->current_spid['spid'],
					'crontab_date'	=>	time(),
					'posidInfo'	=>	$taskInfo['posidInfo']
			);
			$status = $this->game_task_game->insert($insert_data);
			$taskUpdate = array(
					'videoNums' => $updateVideNums,	//任务游戏数量
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
			showmessage('推荐位'.$taskInfo['posidInfo'].'的游戏数量应该在'.$taskInfo['start_end_nums'],HTTP_REFERER);
		}
	}
	/*
	 * 删除某推荐位
	*/
	public function deleteTask() {
		$this->game_task_game = pc_base::load_model('game_task_game_model');
		if(!empty($_GET['taskId']) && is_numeric($_GET['taskId']))
		{
			$where_data = array(
					'taskId'=> $_GET['taskId']
			);
			$this->task_db->delete($where_data);
			$this->game_task_game->delete($where_data);	
			showmessage(L('operation_success'),HTTP_REFERER);	
		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}
	}
	/*
	 * 审核该游戏推荐位
	*/
	public function task_on(){
		$taskId = trim($_GET['taskId']);
		$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$update_where = array(
					'spid' => $this->current_spid['spid'],
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
	 * 下线该游戏推荐位
	*/
	public function task_off(){
		$taskId = trim($_GET['taskId']);
		$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
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
}