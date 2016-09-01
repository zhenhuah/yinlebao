<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('admin', 'admin', 0);

//服务器的图片路径地址

define('TASK_IMG_PATH','http://127.0.0.1/yinlebao/');
//define('TASK_IMG_PATH','http://go3c.jdjkcn.net/');

//本地的客户端的图片域名(项目域名)(同步的时候用)
define('TASK_IMG_PATH_Local','http://127.0.0.1/yinlebao/');
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
pc_base::load_sys_class('form', '', 0);
class task extends admin {
	function __construct() {
		parent::__construct();
		pc_base::load_app_func('global_task');	//方法文件
		pc_base::load_app_func('global');
		$this->op = pc_base::load_app_class('role_op');
		
		$this->task_db = pc_base::load_model('cms_pre_task_model');			//任务信息表连接
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');//视频任务数据表连接
		$this->spid_db = pc_base::load_model('admin_model');			//后台登录表连接
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

	/*
	* 任务列表
	*/
	public function task() {
		$term_id = trim($_GET['term_id']);			// 终端类型
		$posid_list = self::posidInfo($term_id);	//推荐位列表
		$spid = $this->current_spid['spid'];

		//申请审核处理 start
		if($_GET['tstatus'] == 'apply')
		{		
			$status_data = array(
				'taskId' => trim($_GET['taskId']),		//任务ID
				'taskStatus' => '2',	//要修改的状态值
			);
			self::taskStatusProcess($status_data);
		}
		//申请审核处理 end
	
		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置
		$where = " spid = '".$this->current_spid['spid']."' AND term_id = '".$term_id."' ";	
		//查询处理 start 
		if(!empty($_GET['dosearch']))
		{
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
			//	推荐位名称
			$name = trim($_GET['name']);
			if(!empty($name))
			{
				$where .= " AND posidInfo like '%$name%'";
			}
			//	推荐位ID
			$type_id = trim($_GET['type_id']);
			if(!empty($type_id))
			{
				$where .= " AND type_id = '$type_id'";
			}
		}	
		
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$task_list  = $this->task_db->listinfo($where, $order = '`taskDate` DESC', $page, $pagesize = 15);
		$pages = $this->task_db->pages;

		include $this->admin_tpl('task_task_list');	
	}

	/**
	 * 添加任务
	 *
	 */
	public function addTask() {
		$term_id = trim(getgpc('term_id'));			// 终端类型
		$posid_list = self::posidInfo($term_id);	//推荐位列表	

		switch($term_id)	//图片大小默认值
		{
			case '1':	//STB 机顶盒 
				$defautlImg = '102';
			break;
			case '2':	//IPAD 
				$defautlImg = '202';
			break;
			case '3':	//PHONE
				$defautlImg = '302';
			break;
			case '4':	//PC
				$defautlImg = '402';
			break;
		}

		$taskType = 'addTask';	//
		$mode = trim($_POST['mode']);
		if(!empty($mode))	//提交添加处理
		{
			if($mode == 'addTask')
			{
				$posid = trim($_POST['task_posid']);
				$spid = $this->current_spid['spid'];
				$posidInfo = trim($_POST['task_posidInfo']);
				$videoSource = trim($_POST['task_videoSource']);				
				$taskDate = trim($_POST['task_taskDate']);

				if(!empty($term_id) && !empty($posid) && !empty($spid) && !empty($videoSource) && !empty($taskDate))
				{
					switch($videoSource)
					{
						case '1':	//直播
							$imgType = trim($_POST['task_imgType_live']);
						break;

						case '2':	//点播
							$imgType = trim($_POST['task_imgType_vod']);
						break;

						case '3':	//EPG
							$imgType = trim($_POST['task_imgType_live']);
						break;
					}
					
					if(empty($imgType))
					{
						showmessage('请设置海报类型',HTTP_REFERER);
					}

					//审核审核状态、等待上线 在线状态 这三种状态同一日期必须只能有一个存在
					$aKey = " taskStatus > '0' AND term_id = '".$term_id."' AND posid = '".$posid."' AND taskDate = '".strtotime($taskDate)."'";
					$limitInfo = $this->task_db->get_one($aKey);
					if(empty($limitInfo))
					{
						//根据推荐位id获取区域id
						$this->position_db = pc_base::load_model('position_model');
						$pKey = " posid = '".$posid."'";
						$ptask = $this->position_db->get_one($pKey);
						$insert_data = array(
							'term_id' => $term_id,
							'posid' => $posid,
							'spid' => $posid_list[$posid]['spid'],
							'posidInfo' => $posidInfo,
							'start_end_nums' => $posid_list[$posid]['minnum'].'-'.$posid_list[$posid]['maxnum'],
							'videoSource' => $videoSource,
							'imgType' => $imgType,	//默认图片类型
							'taskDate' => strtotime($taskDate),
							'taskStatus' => '1',	//默认编辑状态
							'area_id'  => $ptask['area_id'],
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

		include $this->admin_tpl('task_task_info');	
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

		include $this->admin_tpl('task_task_info');	
	}	

	/*
	* 1=直播或频道,使用的数据表 v9_channel 过滤的条件是:
	* 2=点播,使用的数据表 v9_video 过滤的条件是：
	* 3=EGP,使用的数据表  v9_channelepg 过滤的条件是
	*
	* 数据库里所有视频类型(展示)后进行具体的添加操作
	* 优化：类型对应的条件及模板要显示的数据
	*/
	public function showVideo(){
		$taskId = trim(getgpc('taskId'));	//任务ID		
		$taskInfo = self::getOneTask($taskId);
		if(empty($taskInfo))	//任务是否存在
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$taskInfo['taskBase'] = explode('-',$taskInfo['posidInfo']);
		}

		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置
		$videoSource = $taskInfo['videoSource'];	//数据类型
		$column_id = $_GET['column_id'];
		$videoSource = $column_id==2?1:$videoSource;

		switch($videoSource)
		{ 
			case '1':	//直播或频道v9_channel
				$current_db = pc_base::load_model('cms_channel_model');
				//$aKey = "published = 1 AND ";	//记得到时打开，现在调试关闭中
				$aKey = " published = 1 AND (img != '' || imgpath != '')";	//图片必须有一种类型有数据存在
				//查询 start
				if($_GET['mode'] == 'query')
				{
					//channel_id
					$channel_id = trim($_GET['channel_id']);
					if(!empty($channel_id))
					{
						$aKey .= " AND channel_id = '".$channel_id."'";
					}
					//名称
					$keyword = trim($_GET['keyword']);
					if(!empty($keyword))
					{
						$aKey .= " AND title LIKE '%".$keyword."%'";
					}

					//状态 published_online_status
					$published_online_status = trim($_GET['published_online_status']);

					if($published_online_status == '1')	//己上线
					{
						$aKey .= " AND published = '1' ";
					}elseif($published_online_status == '11'){	//已审核通过
						$aKey .= " AND online_status = '11' AND published = '0'";
					}else{
						$aKey .= " AND (published = '1' || (online_status = '11' AND published = '0'))";
					}
				}else{
					$aKey .= " AND (published = '1' || (online_status = '11' AND published = '0'))";
				}
			
				//查询 end
				$orderBy = '`id` DESC';	
			break;

			case '2':	//点播	v9_video
			$current_db = pc_base::load_model('cms_video_model');
			//$aKey = " spid = '".$this->current_spid['spid']."' AND active = '1' ";
			//$aKey = " spid = '".$this->current_spid['spid']."' ";
			$aKey = " 1 ";
			switch($taskInfo['term_id']){
				case "1": $aKey .= " AND STB=1 ";break;
				case "2": $aKey .= " AND PAD=1 ";break;
				case "3": $aKey .= " AND PHONE=1 ";break;
				case "4": $aKey .= " AND PC=1 ";break;
			}
			//获取栏目类型
			$this->cms_column = pc_base::load_model('cms_column_model');
			$cms_column = $this->cms_column->select(array('active'=>1));
			//查询 start
			if($_GET['mode'] == 'query')
			{
				//VID
				$asset_id = trim($_GET['asset_id']);
				if(!empty($asset_id))
				{
					$aKey .= " AND asset_id = '".$asset_id."'";
				}
				//名称
				$keyword = trim($_GET['keyword']);
				if(!empty($keyword))
				{
					$aKey .= " AND title LIKE '%".$keyword."%'";
				}

					//状态 published_online_status
					$published_online_status = trim($_GET['published_online_status']);

					if($published_online_status == '1')	//己上线
					{
						$aKey .= " AND published = '1' ";
					}elseif($published_online_status == '11'){	//已审核通过
						$aKey .= " AND online_status = '11' AND published = '0'";
					}else{
						$aKey .= " AND (published = '1' || (online_status = '11' AND published = '0'))";
					}

					//类型
					$column_id = trim($_GET['column_id']);
					if(!empty($column_id))
					{
						$aKey .= " AND column_id = '".$column_id."'";
					}
					//是否总集
					$ispackage = isset($_GET['ispackage']) ? intval($_GET['ispackage']) : 0;
					if($ispackage!='0'){
						$aKey .= " AND ispackage = '".$ispackage."'";
					}
				}else{
					$aKey .= " AND (published = '1' || (online_status = '11' AND published = '0'))";
				}

				//查询 end

				$orderBy = '`id` DESC';	
			break;

			case '3':	//EGP v9_channelepg 
				$current_db = pc_base::load_model('cms_channelepg_model');
				$aKey = " published = 1 ";
				
				//查询 start
				if($_GET['mode'] == 'query')
				{
					//频道名称
					$channelName = trim($_GET['channelName']);
					if(!empty($channelName))
					{
						$aKey .= " AND title LIKE '%".$channelName."%'";
					}

					//名称
					$keyword = trim($_GET['keyword']);
					if(!empty($keyword))
					{
						$aKey .= " AND text LIKE '%".$keyword."%'";
					}					

					//类型
					$channel_id = trim($_GET['channel_id']);
					if(!empty($channel_id))
					{
						$aKey .= " AND channel_id = '".$channel_id."'";
					}

					//日期
					$starttime = trim($_GET['starttime']);
					if(!empty($starttime))
					{
						$aKey .= " AND starttime LIKE '".$starttime."%'";
					}
				}
				//查询 end
				$orderBy = " epgid DESC ";
			break;
			
			default:
				showmessage('请选择视频类型',HTTP_REFERER);
			break;
		}

		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$video_list = $current_db->listinfo($aKey, $orderby, $page, $pagesize = 20);
		$pages = $current_db->pages;
		//echo '<pre>';print_r($video_list);exit;
		include $this->admin_tpl('task_show_video');	
	}

	/*
	* 添加视频到任务下
	*/
	public function addVideo(){	
		$taskId = trim(getgpc('taskId'));	//任务ID		
		$taskInfo = self::getOneTask($taskId);
		if(empty($taskInfo))//任务是否存在
		{
			exit('操作错误或数据不存在!');
		}else{
			$taskInfo['taskBase'] = explode('-',$taskInfo['posidInfo']);
		}
		$videoId = trim($_REQUEST['videoId']);	//视频ID
		$videoSource = trim($_REQUEST['dataType']);
		$taskInfo['videoSource'] =$videoSource==1?1:$taskInfo['videoSource'];
		$dataType = $taskInfo['videoSource'];	//视频数据
		switch($dataType)
		{
			case '1':	//直播或频道 ok2
				self::getLive($videoId,$taskInfo);
			break;

			case '2':	//点播 point ok2
				self::getVod($videoId,$taskInfo);
			break;

			case '3':	//EGP
				self::getEpg($videoId,$taskInfo);
			break;

			default:
				exit('操作错误或数据不存在！');
			break;
		}
	}

	///////////////////数据源类型拆分 start ////////////////////////////
	/*
	* 直播或频道  -- OK3
	*/
	public function getLive($videoId,$taskInfo){		
		if(empty($videoId) || empty($taskInfo))
		{
			exit('操作错误或数据不存在!');
		}
		
		//加入判断，视频任务表里是否己经添加 start
		//$getStatusInfo = self::getOneVideo($taskInfo['taskId'],$taskInfo['videoSource'],$videoId);
		//if(!empty($getStatusInfo))
		//{
		//	exit('1');
		//}
		//加入判断，视频任务表里是否己经添加 end
		$current_db = pc_base::load_model('cms_channel_model');
		$aKey = " channel_id = '".$videoId."'";
		$videoInfo = $current_db->get_one($aKey);
		if(!empty($videoInfo))
		{
			if(!empty($_POST['dosubmit']))//提交处理
			{				
				$videoTitle = trim($_POST['videoTitle']);	
				$videoDesc = trim($_POST['videoDesc']);
								
				$videoClarity = trim($_POST['videoClarity']);
				$videoPlayUrl =  $videoInfo[$videoClarity];	//播放地址

				$ImgDataType = trim($_POST['ImgDataType']);//选择海报方式
				
				if(empty($videoDesc)){
					$videoDesc = $videoTitle;
				}		
				if(!empty($videoTitle) && in_array($ImgDataType,array('db','isUpload')))
				{		
					$insert_data = array(
						'taskId' => $taskInfo['taskId'],			//任务ID	
						'term_id' => $taskInfo['term_id'],			//终端类型
						'posid' => $taskInfo['posid'],				//推荐位类型
						'spid' => $taskInfo['spid'],				//运营商
						'posidInfo' => $taskInfo['posidInfo'],		//任务名称
						'videoId' => $videoId,						//视频ID
						'videoSource' => $taskInfo['videoSource'],	//来自那个表类型
						'videoTitle' => $videoTitle,				//名称	
						'videoClarity' => $videoClarity,			//对应清晰度类型
						'videoPlayUrl' => $videoClarity?$videoPlayUrl:'',//播放地址				
						'videoDesc' => $videoDesc,					//视频简介
						'videoSort' => '0',							//排序
						'status' => 'Y',							//可用状态
						'crontab_date' => $taskInfo['taskDate'],	//任务时间
						'online_date' => $videoInfo['inputtime'] ? $videoInfo['inputtime']:'0',
						'online_status' => $videoInfo['online_status'],
						'offline_date' => strtotime($videoInfo['offline_date'])?strtotime($videoInfo['offline_date']):'0',
						'offline_status' => $videoInfo['offline_status'],
						'posttime' => time()
					);
		
					//图片处理 start 
					if($ImgDataType == 'isUpload')
					{
						/*
						//上传图片//附件上传处理
						if($_FILES['videoImgUrl']['error'] == '0')
						{
							$upload_path = uploaded_file('videoImgUrl','taskImg');	//上传图片
							if($upload_path['msg'] == '1')	//上传成功
							{
								$insert_data['imgType'] = '0'; //0代表自己上传
								//$insert_data['videoImg'] = APP_PATH.'uploadfile/'.$upload_path['path'];
								$insert_data['videoImg'] = get_img_url('uploadfile/'.$upload_path['path']);
							}
						}else{
							showmessage('请设置海报!',HTTP_REFERER);
						}
						*/
						/*
						$videoImgUrl = trim($_POST['videoImgUrl']);
						$imgType = trim($_POST['imgType']);
						if(!empty($videoImgUrl))
						{
							$insert_data['imgType'] = '0'; //0代表自己上传
							$insert_data['videoImg'] = $videoImgUrl;
						}else{
							showmessage('请设置海报!',HTTP_REFERER);
						}*/
						$videoImgUrl = trim($_POST['videoImgUrl']);
						$imgType = trim($_POST['imgType']);
						if(empty($videoImgUrl)||empty($imgType)){
							showmessage('请设置海报或其类型!',HTTP_REFERER);
						}
						$insert_data['imgType'] = $imgType;
						$insert_data['imageid'] = $imgType;
						$insert_data['videoImg'] = $videoImgUrl;
					}elseif($ImgDataType == 'db'){
						$videoImg = trim($_POST['videoImg']);
						$insert_data['imgType'] = $videoImg; 						
						$insert_data['videoImg'] = $videoInfo[$videoImg];	//图片字段 img 	imgpath
					}else{
						showmessage('请设置海报!',HTTP_REFERER);
					}

					if(empty($insert_data['videoImg']))
					{
						showmessage('请设置海报!',HTTP_REFERER);	//无语....
					}
					//图片处理 end			
					
					$taskInfo['videoNums'] = self::getVideoCount($taskInfo['taskId']);
					//执行 start
					$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
					$updateVideNums = $taskInfo['videoNums'] + 1;
					if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1]))
					{
						$status = $this->task_video_db->insert($insert_data);						
						if(!empty($status))
						{
							//比对任务表中的下线时间，判断是否要更新 start
							self::updateTaskOffline($taskInfo,$videoInfo['offline_date']);
							//比对任务表中的下线时间，判断是否要更新 end
							
							//比对预发布时间，如果时间在当前之前则取当前时间 start
							$taskInfo['taskDate'] = max($taskInfo['taskDate'],time());
							//比对预发布时间，如果时间在当前之前则取当前时间 end
							
							$taskUpdate = array(
								'videoNums' => $updateVideNums,	//任务视频数量
								'taskStatus' => '1',	//回滚到默认编辑状态
								'taskDate' => $taskInfo['taskDate'],
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
							showmessage('提交失败!',HTTP_REFERER);
						}
					}else{
						showmessage('推荐位'.$taskInfo['posidInfo'].'的视频数量应该在'.$taskInfo['start_end_nums'],HTTP_REFERER);
					}
					//执行 end
				}else{
					showmessage('提交失败,请设置相关必填项!',HTTP_REFERER);
				}
			}
		}else{
			exit('操作错误或数据不存在!');
		}
		
		include $this->admin_tpl('task_add_video_channel');
	}

	/*
	* 点播  -- OK3
	*/
	public function getVod($videoId,$taskInfo){		
		if(empty($videoId) || empty($taskInfo))
		{
			exit('操作错误或数据不存在!');
		}
		//加入判断，视频任务表里是否己经添加 start
		//$getStatusInfo = self::getOneVideo($taskInfo['taskId'],$taskInfo['videoSource'],$videoId);
		//if(!empty($getStatusInfo))
		//{s
		//	exit('1');
		//}
		//加入判断，视频任务表里是否己经添加 end
		//视频分类
		$column_data = array(
			'1' => '首页推荐',
			'2' => '电视直播',
			'3' => '电视栏目',
			'4' => '电视剧',
			'5' => '电影',
			'6' => '乐酷',
			'7' => '动漫',
			'8' => '纪录片',
			'9' => '音乐'
		);	

		$current_db = pc_base::load_model('cms_video_model');
		
		//$aKey = " asset_id = '".$videoId."' AND spid = '".$this->current_spid['spid']."' AND active = '1' ";
		$aKey = " asset_id = '".$videoId."' AND active = '1' ";
		$videoInfo = $current_db->get_one($aKey);
		//取视频详细信息
		$this->cms_video_data = pc_base::load_model('cms_video_data_model');
		$dwhere = "id = '".$videoInfo['id']."'";
		$videodataInfo = $this->cms_video_data->get_one($dwhere);
		
		if(!empty($videoInfo))
		{
			//视频清晰度类型数据 video_content
			$this->cms_video_content   = pc_base::load_model('cms_video_content_model');
			$cms_video_content_data = $this->cms_video_content->select(array('asset_id'=>$videoId));
			if(!empty($cms_video_content_data))	//归整数据
			{
				foreach($cms_video_content_data as $kk => $vv)
				{
					$cms_video_content[$vv['clarity']] = $vv;
				}
			}

			//海报类型数据 video_poster
			$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');
			$ImgTypeData = $this->cms_video_poster->select(array('asset_id'=>$videoId));
			if(!empty($ImgTypeData))	//归整数据
			{
				foreach($ImgTypeData as $k => $v)
				{
					$imgTypeList[$v['type']] = $v;
				}
			}else {
				//如果是分集没有图片,就取总集的图片
				if(!empty($videoInfo['parent_id'])){
					$ImgTypeData = $this->cms_video_poster->select(array('asset_id'=>$videoInfo['parent_id']));
					foreach($ImgTypeData as $k => $v)
					{
						$imgTypeList[$v['type']] = $v;
					}
				}
			}
			
			//海报尺寸名称翻译 v9_dbmover_poster_type
			$this->cms_poster_type = pc_base::load_model('dbmover_poster_type_model');
			$dbImgTypeData = $this->cms_poster_type->select();
			if(!empty($dbImgTypeData))	//归整数据
			{
				foreach($dbImgTypeData as $k => $v)
				{
					$dbImgTypeList[$v['id']] = $v;
				}
			}
	
			if(!empty($_POST['dosubmit']))//提交处理
			{				
				$videoTitle = trim($_POST['videoTitle']);	
				$videoDesc = trim($_POST['videoDesc']);
				$videoImg = trim($_POST['videoImg']);
				$videoClarity = trim($_POST['videoClarity']);
				$long_desc = trim($_POST['long_desc']);
				$videoPlayUrl = $cms_video_content[$videoClarity]['path'];
				if(empty($videoDesc)){
					$video_data_db = pc_base::load_model('cms_video_data_model');
					$dataKey = " id = '".$videoInfo['id']."'";			
					$dataInfo = $video_data_db->get_one($dataKey);
					$videoDesc = $dataInfo['long_desc'];
				}

				$ImgDataType = trim($_POST['ImgDataType']);//选择海报方式
				if(!empty($videoTitle) && in_array($ImgDataType,array('db','isUpload')))
				{
					$insert_data = array(
						'taskId' => $taskInfo['taskId'],			//任务ID	
						'term_id' => $taskInfo['term_id'],			//终端类型
						'posid' => $taskInfo['posid'],				//推荐位类型
						'spid' => $taskInfo['spid'],				//运营商
						'posidInfo' => $taskInfo['posidInfo'],		//任务名称
						'videoId' => $videoId,						//视频ID
						'videoSource' => $taskInfo['videoSource'],	//来自那个表类型
						'videoTitle' => $videoTitle,				//名称	
						'videoClarity' => $videoClarity,			//对应清晰度类型
						'videoPlayUrl' => $videoClarity ? $videoPlayUrl : '',//播放地址						
						'videoDesc' => $videoDesc,					//视频简介
						'videoSort' => '0',							//排序
						'status' => 'Y',							//可用状态
						'crontab_date' => $taskInfo['taskDate'],	//任务时间
						'online_date' => strtotime($videoInfo['pub_date'])?strtotime($videoInfo['pub_date']):'0',
						'online_status' => $videoInfo['online_status'],
						'offline_date' => strtotime($videoInfo['offline_date'])?strtotime($videoInfo['offline_date']):'0',
						'offline_status' => $videoInfo['offline_status'],
						'posttime' => time()
					);
					if(!empty($long_desc)){
						$this->cms_video_data->update(array('long_desc'=>$long_desc), array('id'=>$videoInfo['id']));
					}
					if($ImgDataType == 'isUpload')
					{
						/*
						//上传图片//附件上传处理
						if($_FILES['videoImgUrl']['error'] == '0')
						{
							$upload_path = uploaded_file('videoImgUrl','taskImg');	//上传图片
							if($upload_path['msg'] == '1')	//上传成功
							{
								$insert_data['imgType'] = '0'; //0代表自己上传
								//$insert_data['videoImg'] = APP_PATH.'uploadfile/'.$upload_path['path'];
								$insert_data['videoImg'] = get_img_url('uploadfile/'.$upload_path['path']);
							}
						}else{
							showmessage('请设置海报!',HTTP_REFERER);
						}
						
						$videoImgUrl = trim($_POST['videoImgUrl']);
						if(!empty($videoImgUrl))
						{
							$insert_data['imgType'] = '0'; //0代表自己上传
							$insert_data['videoImg'] = $videoImgUrl;
						}else{
							showmessage('请设置海报!',HTTP_REFERER);
						}*/
						$videoImgUrl = trim($_POST['videoImgUrl']);
						$imgType = trim($_POST['imgType']);
						if(empty($videoImgUrl)||empty($imgType)){
							showmessage('请设置海报或其类型!',HTTP_REFERER);
						}
						$insert_data['imgType'] = $imgType;
						$insert_data['imageid'] = $imgType;
						$insert_data['videoImg'] = $videoImgUrl;
					}elseif($ImgDataType == 'db'){
						$insert_data['imgType'] = $videoImg; 
						//通过上面的$imgTypeList得到图片路径数据
						$insert_data['videoImg'] = $imgTypeList[$videoImg]['path'];	
					}else{
						showmessage('请设置海报!',HTTP_REFERER);
					}
					/*
					if(empty($insert_data['videoImg']))
					{
						showmessage('请设置海报!',HTTP_REFERER);	//无语....
					}*/
					//执行 start
					$taskInfo['videoNums'] = self::getVideoCount($taskInfo['taskId']);
					
					$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
					$updateVideNums = $taskInfo['videoNums'] + 1;
					if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1]))
					{	
							$status = $this->task_video_db->insert($insert_data);
						if(!empty($status))
						{
							//比对任务表中的下线时间，判断是否要更新 start
							self::updateTaskOffline($taskInfo,$videoInfo['offline_date']);
							//比对任务表中的下线时间，判断是否要更新 end
							
							//比对预发布时间，如果时间在当前之前则取当前时间 start
							$taskInfo['taskDate'] = max($taskInfo['taskDate'],time());
							//比对预发布时间，如果时间在当前之前则取当前时间 end
							
							$taskUpdate = array(
								'videoNums' => $updateVideNums,	//任务视频数量
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
							showmessage('提交失败!',HTTP_REFERER);
						}
					}else{
						showmessage('推荐位'.$taskInfo['posidInfo'].'的视频数量应该在'.$taskInfo['start_end_nums'],HTTP_REFERER);
					}
					//执行 end
				}else{
					showmessage('请设置相关必填项!',HTTP_REFERER);
				}
			}
		}else{
			exit('操作错误或数据不存在!');
		}
		
		include $this->admin_tpl('task_add_video');

	}

	/*
	* EPG -- OK3
	* epg 的图片要查一下v9_channel表的channel_id对应的img imgpath
	*/
	public function getEpg($videoId,$taskInfo){
		if(empty($videoId) || empty($taskInfo))
		{
			exit('操作错误或数据不存在!');
		}
		//加入判断，视频任务表里是否己经添加 start
		$getStatusInfo = self::getOneVideo($taskInfo['taskId'],$taskInfo['videoSource'],$videoId);
		if(!empty($getStatusInfo))
		{
			exit('1');
		}
		//加入判断，视频任务表里是否己经添加 end
		$current_db = pc_base::load_model('cms_channelepg_model');
		//$aKey = "  epgid = '".$videoId."' ";
		$aKey = "  id = '".$videoId."' ";

		$videoInfo = $current_db->get_one($aKey);
		
		if(!empty($videoInfo))
		{
			$channelInfo = self::getChannelImg($videoInfo['channel_id']);
			
			if(!empty($_POST['dosubmit']))//提交处理
			{				
				$videoTitle = trim($_POST['videoTitle']);	
				$videoDesc = trim($_POST['videoDesc']);

				$videoImg = trim($_POST['videoImg']);
				$videoClarity = trim($_POST['videoClarity']);
				$videoPlayUrl = $videoInfo[$videoClarity];	//播放地址
				$ImgDataType = trim($_POST['ImgDataType']);//选择海报方式
				if(!empty($videoTitle) && in_array($ImgDataType,array('db','isUpload')))
				{
					$insert_data = array(
						'taskId' => $taskInfo['taskId'],			//任务ID	
						'term_id' => $taskInfo['term_id'],			//终端类型
						'posid' => $taskInfo['posid'],				//推荐位类型
						'spid' => $taskInfo['spid'],				//运营商
						'posidInfo' => $taskInfo['posidInfo'],		//任务名称
						'videoId' => $videoId,						//视频ID
						'videoSource' => $taskInfo['videoSource'],	//来自那个表类型
						'videoTitle' => $videoTitle,				//名称	
						'videoClarity' => $videoClarity,			//对应清晰度类型
						'videoPlayUrl' => $videoClarity ? $videoPlayUrl:'',//播放地址						
						'videoDesc' => $videoDesc,					//视频简介
						'videoSort' => '0',							//排序
						'status' => 'Y',							//可用状态
						'crontab_date' => $taskInfo['taskDate'],	//任务时间 
						'online_date' => $videoInfo['inputtime']?$videoInfo['inputtime']:'0',
						'online_status' => $videoInfo['online_status'],
						'offline_date' =>$videoInfo['updatetime']?$videoInfo['updatetime']:'0',
						'offline_status' => $videoInfo['offline_status'],
						'posttime' => time()
					);
					
					//图片处理 start 
					if($ImgDataType == 'isUpload')
					{
						/*
						//上传图片//附件上传处理
						if($_FILES['videoImgUrl']['error'] == '0')
						{
							$upload_path = uploaded_file('videoImgUrl','taskImg');	//上传图片
							if($upload_path['msg'] == '1')	//上传成功
							{
								$insert_data['imgType'] = '0'; //0代表自己上传
								//$insert_data['videoImg'] = APP_PATH.'uploadfile/'.$upload_path['path'];
								$insert_data['videoImg'] = get_img_url('uploadfile/'.$upload_path['path']);
							}
						}else{
							showmessage('请设置海报!',HTTP_REFERER);
						}
						*/
						$videoImgUrl = trim($_POST['videoImgUrl']);
						if(!empty($videoImgUrl))
						{
							$insert_data['imgType'] = '0'; //0代表自己上传
							$insert_data['videoImg'] = $videoImgUrl;
						}else{
							showmessage('请设置海报!',HTTP_REFERER);
						}
					}elseif($ImgDataType == 'db'){
						$videoImg = trim($_POST['videoImg']);
						$insert_data['imgType'] = $videoImg; 						
						$insert_data['videoImg'] = $channelInfo[$videoImg];	//图片字段 img 	imgpath
					}else{
						showmessage('请设置海报!',HTTP_REFERER);
					}

					if(empty($insert_data['videoImg']))
					{
						showmessage('请设置海报!',HTTP_REFERER);	//无语....
					}
					//图片处理 end	
					
					//执行 start
					$taskInfo['videoNums'] = self::getVideoCount($taskInfo['taskId']);
					
					$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
					$updateVideNums = $taskInfo['videoNums'] + 1;
					if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1]))
					{
						$status = $this->task_video_db->insert($insert_data);
						
						if(!empty($status))
						{
							//比对任务表中的下线时间，判断是否要更新 start
							self::updateTaskOffline($taskInfo,$videoInfo['offline_date']);
							//比对任务表中的下线时间，判断是否要更新 end

							//比对预发布时间，如果时间在当前之前则取当前时间 start
							$taskInfo['taskDate'] = max($taskInfo['taskDate'],time());
							//比对预发布时间，如果时间在当前之前则取当前时间 end
							
							//'taskStatus' => '0',	//默认编辑状态
							$taskUpdate = array(
								'videoNums' => $updateVideNums,	//任务视频数量
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
							showmessage('提交失败!',HTTP_REFERER);
						}
					}else{
						showmessage('推荐位'.$taskInfo['posidInfo'].'的视频数量应该在'.$taskInfo['start_end_nums'],HTTP_REFERER);
					}
					//执行 end
				}else{
					showmessage('提交失败,请设置相关必填项!',HTTP_REFERER);
				}
			}
		}else{
			exit('操作错误或数据不存在!');
		}
			
		include $this->admin_tpl('task_add_video_epg');
	}

	///////////////////数据源类型拆分 end ////////////////////////////

	///////////////////////// 私有方法 start //////////////////////

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
			$this->posid_db = pc_base::load_model('cms_position_model');	//推荐位信息表连接
			if($_SESSION['roleid']=='1'){
				$posid_where = " term_id = '".$term_id."'";
			}else{
				$posid_where = " term_id = '".$term_id."' AND spid = '".$this->current_spid['spid']."'";
			}
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
		}
		//else{
		//	showmessage('请选择终端类型',HTTP_REFERER);
		//}
		//加入判断 先选择终端类型ID end	
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

	/*
	* 得到任务的视频数量
	*/
	private function getVideoCount($taskId){
		$taskId = trim($taskId);
		if(!empty($taskId) && is_numeric($taskId))
		{
			$findWhere = " taskId = '".$taskId."' ";
			$d = $this->task_video_db->select($findWhere);
			if(empty($d)) 
			return 0;
			else 
			return count($d);
		}else{
			return 0;
		}
	}
	/*
	* 任务的下线时间是否更新 
	*/
	private function updateTaskOffline($taskInfo,$offline_date) {
		$get_offline_date = strtotime($offline_date);
		if(!empty($taskInfo) && !empty($get_offline_date))
		{
			//$offline_date 如果小于当前任务的下线时间，则修改
			if(!empty($taskInfo['offline_date']) && ($taskInfo['offline_date'] > $get_offline_date))
			{
				$update_data = array(
					'offline_date' => $get_offline_date
				);
				$update_where = array(
					'taskId' => $taskInfo['taskId'],
					'spid' => $this->current_spid['spid']
				);
				$this->task_db->update($update_data,$update_where);
			
			}elseif(empty($taskInfo['offline_date'])){
				$update_data = array(
					'offline_date' => $get_offline_date
				);
				$update_where = array(
					'taskId' => $taskInfo['taskId'],
					'spid' => $this->current_spid['spid']
				);
				$this->task_db->update($update_data,$update_where);
			}
		}
	}

	/*
	* 得到一条具体任务下的视频数据信息
	*/
	private function getOneVideo($taskId,$dataType,$videoId) {
		$taskId = trim($taskId);		//任务ID
		$dataType = trim($dataType);	//数据类型
		$videoId = trim($videoId);		//任务ID
		if(!empty($taskId) && is_numeric($taskId) && !empty($dataType) && is_numeric($dataType) && !empty($videoId))
		{
			$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' AND videoId = '".$videoId."'	AND videoSource = '".$dataType."' ";
			return $preInfo = $this->task_video_db->get_one($aKey);	//是否存在
		}else{
			return '';
		}
	}
	///////////////////////// 私有方法 end //////////////////////

	/*
	* AJAX 终端类型下的推荐位列表数据 --OK
	*/
	public function term_posid() {

		$term_id = trim($_GET['term_id']);

		if(!empty($term_id) && is_numeric($term_id))
		{
			//推荐位 v9_position
			$this->posid_db = pc_base::load_model('cms_position_model');	//推荐位信息表连接
			$posid_where = " term_id = '".$term_id."' AND spid = '".$this->current_spid['spid']."'";
			$posid_list = $this->posid_db->select($posid_where);

			if(!empty($posid_list))
			{
				$options = "<option value=''>请选择</option>";
				foreach($posid_list as $key => $value)
				{
					$options .= "<option value=".$value['posid'].">".$value['name']."</option>"; 
				}
			}
			exit($options);
		}
	}


	/////////////////// 各种配置 start /////////////////
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
	/////////////////// 各种配置 end /////////////////

	
	/*
	* 审核详细查看任务下的所属视频列表
	*/
	public function verifyVideo() {		
		$taskId = trim($_GET['taskId']);//任务ID
		if(!empty($taskId))
		{
			$where = " spid = '".$this->current_spid['spid']."' AND taskId = '".$taskId."'";
			$taskInfo = self::getOneTask($taskId);
			if(empty($taskInfo))
			{
				showmessage('该任务己经不存在!',HTTP_REFERER);
			}
		}else{
			showmessage('请选择详细的任务再操作!',HTTP_REFERER);
		}	

		//任务查询处理
		$mode = trim($_GET['mode']);
		if($mode == 'query')
		{
			//视频名称
			$videoTitle = trim($_GET['title']);
			if(!empty($videoTitle))
			{
				$where .= " AND videoTitle LIKE '%".$videoTitle."%'";
			}
		}

		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';

		$video_list = $this->task_video_db->listinfo($where, $order = ' videoSort ASC,`preId` DESC', $page, $pagesize = 15);
		$pages = $this->task_video_db->pages;

		include $this->admin_tpl('task_verify_video_list');	
	}

	/*
	* 任务下的所属视频列表 
	*/
	public function video() {		
		$taskId = trim($_GET['taskId']);//任务ID
		if(!empty($taskId))
		{
			$where = " spid = '".$this->current_spid['spid']."' AND taskId = '".$taskId."'";
			$taskInfo = self::getOneTask($taskId);
			if(empty($taskInfo))
			{
				showmessage('该任务己经不存在!',HTTP_REFERER);
			}
		}else{
			showmessage('请选择详细的任务再操作!',HTTP_REFERER);
		}	

		//任务查询处理
		$mode = trim($_GET['mode']);
		if($mode == 'query')
		{
			//视频名称
			$videoTitle = trim($_GET['title']);
			if(!empty($videoTitle))
			{
				$where .= " AND videoTitle LIKE '%".$videoTitle."%'";
			}
		}

		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';

		$video_list = $this->task_video_db->listinfo($where, $order = ' videoSort ASC,`preId` DESC', $page, $pagesize = 15);
		$pages = $this->task_video_db->pages;

		include $this->admin_tpl('task_video_list');	
	}

	/**
	 * 编辑任务下的视频
	 *
	 */
	public function editVideo() {				
		$preId = $_REQUEST['preId'];	//预发布ID
		$isout = $_REQUEST['isout'];	//是否外链
		if(!empty($preId) && is_numeric($preId))
		{
			$where = " spid = '".$this->current_spid['spid']."' AND preId = '".$preId."'";
		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}

		$preInfo = $this->task_video_db->get_one($where);	//查询

		$wherec = " asset_id = '".$preInfo['videoId']."'";
		$this->video_content_db = pc_base::load_model('cms_video_content_model');
		$preconInfo = $this->video_content_db->select($wherec);	//查询
		$current_db = pc_base::load_model('cms_video_model');
		$aKey = " asset_id = '".$preInfo['videoId']."' ";			
		$videoInfo = $current_db->get_one($aKey);
		$video_data_db = pc_base::load_model('cms_video_data_model');
		$dataKey = " id = '".$videoInfo['id']."'";			
		$dataInfo = $video_data_db->get_one($dataKey);
		$preInfo['long_desc'] = $dataInfo['long_desc'];
		if(empty($preInfo['videoDesc'])){
			$preInfo['videoDesc'] = $dataInfo['short_desc'];
		}
		if(!empty($preInfo))
		{ 
			//得到视频类型选择和海报类型选择数据 start
			$imgList = self::getVideoImg($preInfo['videoSource'],$preInfo['videoId']);
			//得到视频类型选择和海报类型选择数据 end
			
			if($_POST['doSubmit'])//提交处理
			{
				$videoTitle = trim($_POST['videoTitle']);
				$videoDesc = trim($_POST['videoDesc']);
				$taskId = trim($_POST['taskId']);
				$term_id = trim($_POST['term_id']);
				$videoId = trim($_POST['videoId']);
				$long_desc = trim($_POST['long_desc']);
				$videoSort = is_numeric($_POST['videoSort']) ? $_POST['videoSort'] : '0';
				$videoPlayUrl = trim($_POST['videoPlayUrl']);
				$status = (trim($_POST['status']) == 'N') ? 'N':'Y';
				$ImgDataType = trim($_POST['ImgDataType']);//选择海报方式
				
				if(empty($videoDesc)){
					$videoDesc = $dataInfo['short_desc'];
				}
				if(!empty($videoTitle))
				{
					//修改数组	 	 	 	 	 	 	 	 	 	 	 	  	 	 	 	
					$update_data = array(
						'videoTitle' => strip_tags($videoTitle),
						//'videoPlayUrl' => $videoPlayUrl,
						'videoPlayUrl' => $videoPlayUrl,			//外链链接
						'videoDesc' => strip_tags($videoDesc),
						'videoSort' => $videoSort,					//排序
						'status' => $status,
						'posttime' => time()
					);
					if(!empty($long_desc)){
						$this->cms_video_data = pc_base::load_model('cms_video_data_model');
						$this->cms_video_data->update(array('long_desc'=>$long_desc), array('id'=>$videoInfo['id']));
					}
					if($ImgDataType == 'isUpload')//上传图片
					{
						//附件上传处理
						if($_FILES['videoImgUrl']['error'] == '0')
						{
							/*
							$upload_path = uploaded_file('videoImgUrl','taskImg');	//上传图片
							if($upload_path['msg'] == '1')	//上传成功
							{
								$update_data['imgType'] = '0';
								//$update_data['videoImg'] = APP_PATH.'uploadfile/'.$upload_path['path'];
								$update_data['videoImg'] = get_img_url('uploadfile/'.$upload_path['path']);
							}
							*/							
						}
						$videoImgUrl = trim($_POST['videoImgUrl']);
						$imgType = trim($_POST['imgType']);
						if(!empty($videoImgUrl)&&!empty($imgType))
						{
							$update_data['imgType'] = $imgType;
							$update_data['videoImg'] = $videoImgUrl;
						}
					}else{	//查数据库设置路径
						if(!empty($_POST['videoImg']))
						{
							$videoImg = trim($_POST['videoImg']);
							if($preInfo['videoSource'] == '2')	//点播
							{							
								//$update_data['imgType'] = $videoImg;
								//$update_data['videoImg'] =$imgList[$videoImg]['path'];
							}else{	//直播 EPG
								$update_data['imgType'] = $videoImg;
								$update_data['videoImg'] =$imgList[$videoImg];
							}
						}
					}
					$taskvideo = $this->task_video_db->get_one(array('preId'=>$preId));
					$insert_data = array(
							'taskId' => $taskvideo['taskId'],			//任务ID
							'term_id' => $taskvideo['term_id'],			//终端类型
							'posid' => $taskvideo['posid'],				//推荐位类型
							'spid' => $taskvideo['spid'],				//运营商
							'posidInfo' => $taskvideo['posidInfo'],		//任务名称
							'videoId' => $videoId,						//视频ID
							'videoSource' => $taskvideo['videoSource'],	//来自那个表类型
							'videoTitle' => strip_tags($videoTitle),				//名称
							'videoClarity' => '',			//对应清晰度类型
							'videoPlayUrl' => $videoPlayUrl,//播放地址
							'videoDesc' => $videoDesc,					//视频简介
							'videoSort' => '0',							//排序
							'status' => 'Y',							//可用状态
							'crontab_date' => $taskvideo['taskDate'],	//任务时间
							'online_date' => strtotime($videoInfo['pub_date'])?strtotime($videoInfo['pub_date']):'0',
							'online_status' => $videoInfo['online_status'],
							'offline_date' => strtotime($videoInfo['offline_date'])?strtotime($videoInfo['offline_date']):'0',
							'offline_status' => $videoInfo['offline_status'],
							'posttime' => time()
					);
					
					//修改条件
					$update_where = array(					
						'preId' => $preId,	
						'spid' => $this->current_spid['spid']
					);

					$this->task_video_db->update($update_data,$update_where);
					
					//回滚到默认编辑状态优化 start 
					$taskInfo = self::getOneTask($preInfo['taskId']);
					
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

					//showmessage('修改成功',HTTP_REFERER);

				}else{
					showmessage('修改失败',HTTP_REFERER);
				}
			}
		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}

		include $this->admin_tpl('task_edit_video');	
	}

	/*
	* 视频数据类型img
	*/
	public function getVideoImg($sourceType,$videoId)
	{
		switch($sourceType)
		{
			case '1':	//直播或频道
				$imgList = self::getChannelImg($videoId);
			break;

			case '2':	//点播
				$imgList = self::getVodImg($videoId);
			break;

			case '3':	//EPG
				$imgList = self::getEpgImg($videoId);
			break;
		}

		return $imgList;
	}
	
	/*
	* 直播视频数据类型img
	*
	* 得到频道ID所属信息（图片）
	* epg 的图片要查一下v9_channel表的channel_id对应的img imgpath
	*/
	public function getChannelImg($channel_id){		
		if(!empty($channel_id) && is_numeric($channel_id))
		{
			$current_db = pc_base::load_model('cms_channel_model');

			$aKey = " channel_id = '".$channel_id."'";
			return $current_db->get_one($aKey);
		}else{
			return '';
		}
	}

	/*
	* 点播视频数据类型img
	*/	
	public function getVodImg($videoId){
		if(!empty($videoId))
		{
			//海报类型数据 video_poster
			$this->cms_video_poster = pc_base::load_model('cms_video_poster_model');

			$ImgTypeData = $this->cms_video_poster->select(array('asset_id'=>$videoId));

			if(!empty($ImgTypeData))	//归整数据
			{
				foreach($ImgTypeData as $k => $v)
				{
					$imgTypeList[$v['type']] = $v;
				}
			}

			//海报尺寸名称翻译 v9_dbmover_poster_type
			$this->cms_poster_type = pc_base::load_model('dbmover_poster_type_model');
			$dbImgTypeData = $this->cms_poster_type->select();
			if(!empty($dbImgTypeData))	//归整数据
			{
				foreach($dbImgTypeData as $k => $v)
				{
					$imgTypeList[$v['id']]['nameInfo'] = $v;
				}
			}

			return $imgTypeList;
		}else{
			return '';
		}
	}

	/*
	* EPG视频数据类型img
	*/	
	public function getEpgImg($videoId){
		if(!empty($videoId))
		{
			//先查出数据的频道ID
			$current_db = pc_base::load_model('cms_channelepg_model');
			//$aKey = " epgid = '".$videoId."'";
			$aKey = " id = '".$videoId."'";
			$getEpgInfo = $current_db->get_one($aKey);
			if(!empty($getEpgInfo))
			{
				return $channelInfo = self::getChannelImg($getEpgInfo['channel_id']);
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	/*
	* 任务及频删除除
	*/
	public function deleteTask() {
		if(!empty($_GET['taskId']) && is_numeric($_GET['taskId']))
		{
			$where_data = array(
				'taskId'=> $_GET['taskId'],
				'spid' => $this->current_spid['spid']
				);
			$this->task_db->delete($where_data);
			$this->task_video_db->delete($where_data);

			showmessage(L('operation_success'),HTTP_REFERER);

		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}
	}

	/*
	* 具体视频删除
	*/
	public function deleteVideo() {
		if(!empty($_GET['preId']) && is_numeric($_GET['preId']))
		{
			$where_data = array(
				'preId'=> $_GET['preId'],
				'spid' => $this->current_spid['spid']
				);

			
			$taskId = trim($_GET['taskId']);
			$taskInfo = self::getOneTask($taskId);
			$taskInfo['videoNums'] = self::getVideoCount($taskInfo['taskId']);
			$this->task_video_db->delete($where_data);
			
			$taskUpdate = array(
				'videoNums' => $taskInfo['videoNums'] - 1,	//任务视频数量
			);
			
			$taskWhere = array(
				'taskId' => $taskInfo['taskId']	
			);
			
			
			//得到任务下最小的下线时间 start
			$orderBy = " offline_date ASC ";
			$findWhere = " taskId = '".$taskInfo['taskId']."' ";
			$getOffineDate = $this->task_video_db->select($findWhere,'*',$limit = '1',$findWhere);
	
			if($taskInfo['videoNums']>0)
			{
				$taskUpdate['taskStatus'] = '1';//回滚到编辑状态
				//比对预发布时间，如果时间在当前之前则取当前时间 start
				$taskUpdate['taskDate'] = max($taskInfo['taskDate'],time());
				//比对预发布时间，如果时间在当前之前则取当前时间 end

				if($taskInfo['videoSource'] == '2')	//点播要修改下线时间
				{
					if(!empty($getOffineDate[0]['offline_date']))
					{
						$taskUpdate['offline_date'] = $getOffineDate[0]['offline_date'];
					}
				}

				$this->task_db->update($taskUpdate,$taskWhere);
			}

			//showmessage(L('operation_success'),HTTP_REFERER);
			if($taskInfo['taskStatus'] != '1')
			{
				$msg = "操作成功,请注意：此推荐任务列表内容已变化，需要重新提交审核，并发布才能生效！";
				showmessage($msg,HTTP_REFERER,30000);
			}else{
				$msg = '操作成功!';
				showmessage($msg,HTTP_REFERER);
			}

		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}
	}

	/**
	 * 视频排序
	 */
	public function listorder() {
		$lastpreId = 0;
		if(isset($_POST['dosubmit'])) {
			foreach($_POST['listorders'] as $preId => $videoSort) {
				$this->task_video_db->update(array('videoSort'=>$videoSort),array('preId'=>$preId,'spid' => $this->current_spid['spid']));
				$lastpreId = $preId;
			}
			
			if(!empty($lastpreId) && is_numeric($lastpreId))
			{
				$where = " spid = '".$this->current_spid['spid']."' AND preId = '".$lastpreId."'";
			

				$preInfo = $this->task_video_db->get_one($where);
				
				//回滚到默认编辑状态优化 start 
				$taskInfo = self::getOneTask($preInfo['taskId']);
				
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

	///////////////////////广告、任务审核 start /////////////////////////////
	/**
	 * 任务审核
	 */
	public function verifyTask() {
		//$term_list = $this->term_list;
		$term_list = self::term_list();
		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置
		
		//申请审核处理 start
		if(in_array($_GET['status'],array('Y','N')))
		{		
			$status_data = array(
				'taskId' => trim($_GET['taskId']),		//任务ID
				'taskStatus' => (trim($_GET['status']) == 'Y') ? '4':'3',	//要修改的状态值
			);
			self::taskStatusProcess($status_data);
		}
		//申请审核处理 end
		if($_SESSION['roleid']=='1'){
			$where = " taskStatus > 1 AND taskStatus!=3";
		}else{
			$where = " spid = '".$this->current_spid['spid']."' AND taskStatus > 1 AND taskStatus!=3";
		}		
		//查询处理 start 
		if(!empty($_GET['dosearch']))
		{
			$term_id = trim($_GET['term_id']);			// 终端类型
			if(!empty($term_id))
			{
				$where .= " AND term_id = '".$term_id."'";
				$posid_list = self::posidInfo($term_id);	//推荐位列表
			}
			
			//任务(推荐位)			
			$posid = trim($_GET['posid']);
			if(!empty($posid))
			{
				$where .= " AND posid = '".$posid."'";
			}
			
			//	预发布时间
			$taskDate = trim($_GET['taskDate']);
			if(!empty($taskDate))
			{
				$where .= " AND ('$taskDate' = DATE_FORMAT( FROM_UNIXTIME( taskDate ) , '%Y-%m-%d' ))";
			}
		}	
		
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$task_list  = $this->task_db->listinfo($where, $order = '`taskDate` DESC', $page, $pagesize = 15);
		$pages = $this->task_db->pages;

		include $this->admin_tpl('task_verify_list');	
	}

	/*
	* 广告状态修改
	*/
	private function advertStatus($status_data) {
		
		if(!empty($status_data) && is_array($status_data))
		{
			$adId = trim($status_data['adId']);	//广告任务ID

			if(!empty($adId) && is_numeric($adId) && is_numeric($status_data['adStatus']))
			{		
				$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告推荐位信息表

				if($_SESSION['roleid']=='1'){
					$getOneWhere = " adId = '".$adId."' ";
				}else{
					$getOneWhere = " adId = '".$adId."' AND spid = '".$this->current_spid['spid']."' ";
				}

				$adInfo = $this->adverts_db->get_one($getOneWhere);;
				if(empty($adInfo))	//任务是否存在
				{
					showmessage('操作错误或数据不存在!',HTTP_REFERER);
				}
			
				$update_where = array(
					'spid' => $adInfo['spid'],
					'adId' => $adInfo['adId']
				);

				$update_data = array(
					'adStatus' => $status_data['adStatus'],	//审核状态
					'posttime' => time()
				);
				$this->adverts_db->update($update_data,$update_where);
				showmessage('操作成功!',HTTP_REFERER);
			}
		}
	}
	
	/*
	* 广告
	*/	
	public function advert() {
		$this->term_db = pc_base::load_model('cms_term_type_model');	//终端类型信息表连接
		$term_type_list = array();
		$term_type_list = $this->term_db->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		$term_type_data = array_keys(self::term_list());	//所有终端的ID
		$term_id = trim($_GET['term_id'])?trim($_GET['term_id']):'';
		if(!in_array($term_id,$term_type_data))
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}

		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告推荐位信息表
		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置

		//申请审核 start
		$tstatus = trim($_GET['tstatus']);
		if(($tstatus == 'apply') && !empty($_GET['adId']) && is_numeric($_GET['adId']))
		{	
			$status_data = array(
				'adId' => trim($_GET['adId']),
				'adStatus' => '2'
			);
			self::advertStatus($status_data);
		}
		//申请审核 end

		$spid = $this->current_spid['spid'];
		if($_SESSION['roleid']=='1'){
			$aKey = " ad_belong ='1' ";
		}else{
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$aKey = " ad_belong ='1' AND spid in (".$sqlin.") ";
		}		
		//查询
		if($_GET['mode'] == 'query')
		{
			//终端term
			$term_id = trim($_GET['term_id']);
			if(!empty($term_id)){
				$aKey .= " AND term_id = '".$term_id."'";
			}
			//广告类型 adType
			$adType = trim($_GET['adType']);
			if(!empty($adType)){
				$aKey .= " AND adType = '".$adType."'";
			}
			$spid = trim($_GET['spid']);
			if(!empty($spid)){
				$aKey .= " AND spid = '".$spid."'";
			} else if($_SESSION['roleid']=='1') {
				$aKey .= " AND spid in (".$sqlin.")";
			}
			//显示类型 viewType
			$viewType = trim($_GET['viewType']);
			if(!empty($viewType)){
				$aKey .= " AND viewType = '".$viewType."'";
			}

			//发布时间 taskDate
			$taskDate = trim($_GET['taskDate']);
			if(!empty($taskDate)){
				$aKey .= " AND taskDate = '".strtotime($taskDate)."'";
			}
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$advert_list  = $this->adverts_db->listinfo($aKey, $order = '`taskDate` DESC', $page, $pagesize = 10);
		$pages = $this->adverts_db->pages;
		$this->db = pc_base::load_model('cms_adverts_model');
		if($_SESSION['roleid']=='1'){
			$sp_list = $this->db->select($where = '', 'spid', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
		}else{
			$sp_list = $this->db->select($where = 'spid in ('.$sqlin.')', 'spid', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
		}
		include $this->admin_tpl('task_advert_list');
	}

	/*
	* 添加广告
	*/
	public function addAdvert() {
		$term_type_data = array_keys(self::term_list());	//所有终端的ID
		$term_id = trim(getgpc('term_id'));	//终端
		$spid = $this->current_spid['spid'];

		if(!in_array($term_id,$term_type_data))
		{
			exit('操作错误或数据不存在!');
		}

		$this->cms_adverts_db = pc_base::load_model('cms_adverts_model');//广告任务信息表
		$this->cms_spid_db = pc_base::load_model('cms_spid_model');
		if($_SESSION['roleid']=='1'){
			$adWhere = " term_id = '".$term_id."' AND ad_belong='1'";
			$spwh = " 1 group by board_type";
		}else{
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$adWhere = " term_id = '".$term_id."' AND SPID in (".$sqlin.") AND ad_belong='1'";
			$spwh = " spid in (".$sqlin.") group by board_type";
		}
		$term_adverts_data = $this->cms_adverts_db->select($adWhere);
		$sp_list = $this->cms_spid_db->select($spwh);
		if(!empty($term_adverts_data))	//归整数据方便插入取数据
		{
			foreach($term_adverts_data as $key => $value)
			{
				$term_adverts_list[$value['id']] = $value;
			}
		}else{
			exit('该终端类型下面没有广告推荐位!');
		}

		//提交处理
		if($_POST['mode'] == 'addAdTask')
		{
			$position= trim($_POST['ad_position']);	//推荐位名称 *
			$arr = explode("|",$position);
			$posit = $arr['0'];
			$insertPosInfo = $term_adverts_list[$posit];
			$adDesc = trim($_POST['ad_adDesc']);		//文字 *
			$imgType = trim($_POST['imgType']);			//图片类型
			//$taskDate = trim($_POST['ad_taskDate']);	//预发布时间 *
			$imgUrl = trim($_POST['ad_imgUrl']);		//图片地址
			$duration = trim($_POST['duration']);		//持续时间
			$title = trim($_POST['title']);
			$color = trim($_POST['color']);
			$dire = trim($_POST['dire']);
			$speed = trim($_POST['speed']);
			$islink = $_POST['link'];					//点击广告的跳转方式
			$linkUrl = trim($_POST['ad_linkUrl']);		//链接地址
			$app_name = trim($_POST['app_name']);
			$app_package = trim($_POST['app_package']);
			$app_url = trim($_POST['app_url']);
			$btntxt = trim($_POST['btn_txt']);
			if (!$btntxt)
				$btntxt = '点击查看';
			$starttime = trim($_POST['starttime']);
			$endtime = trim($_POST['endtime']);
			$num = trim($_POST['num']);
			$width = trim($_POST['width']);
			$height = trim($_POST['height']);
			$pop = trim($_POST['pop']);

			$regtype = trim($_POST['regtype']);		//检查图片是否正确
			$tupdated = date('Y-m-d H:i:s');
			if(empty($imgUrl)) showmessage('图片地址不能为空!',HTTP_REFERER,3000);
			if($regtype=='') showmessage('没有确认图片是否正确!',HTTP_REFERER,3000);
					
			if(!empty($position) && !empty($insertPosInfo))
			{
				//得到当前的PID的广告类型判断那个字段不能为空处理 start
				switch($insertPosInfo['ad_type'])
				{
					case '1':	//文字
						if(empty($adDesc)) showmessage('文字不能为空');
					break;

					case '2':	//图片
						if(!empty($imgType))
						{
							if($imgType == 'ImgText')	//文本地址
							{
								//$imgUrl = trim($_POST['ad_imgUrl']);		//图片地址
								if(empty($imgUrl)) showmessage('图片地址不能为空');
							}elseif($imgType == 'ImgFile'){	//上传方式 采用AJAX上传了
								/*
								if($_FILES['ad_imgUrlFile']['error'] == '0')
								{
									$upload_path = uploaded_file('ad_imgUrlFile','taskImg');	//上传图片
									if($upload_path['msg'] == '1')	//上传成功
									{
										$imgUrl = APP_PATH.'uploadfile/'.$upload_path['path'];
									}
								}
								if(empty($imgUrl)) showmessage('图片上传失败!');
								*/
							}else{
								showmessage('请设置图片');
							}
						}else{
							showmessage('请设置图片');
						}
						
					break;

					case '3':	//视频
						if(empty($linkUrl)) showmessage('链接地址不能为空');
					break;
				}
				$insert_data = array(
					'term_id' => $term_id,	
					'spid' => $insertPosInfo['SPID'],
					'board_type' => $insertPosInfo['board_type'],
					'parentId' => $posit,
					'position' => $insertPosInfo['title'],
					'type_id' => $insertPosInfo['type_id'],
					'adType' => $insertPosInfo['ad_type'],		//广告类型 *
					'viewType' => $insertPosInfo['display_type'],	//显示方式 *
					'imgUrl' => $imgUrl,
					'linkUrl' => $linkUrl,
					'adDesc' => $adDesc,
					//'taskDate' => strtotime($taskDate),
					'posttime' => time(),
					'adStatus' => '1',
					'duration' => $duration,
					'title' => $title,
					'color' => $color,
					'dire' => $dire,
					'speed' => $speed,
					'starttime' => strtotime($starttime),
					'endtime' => strtotime($endtime),
					'num' => $num,
					'width' => $width,
					'height' => $height,
					'pop' => $pop,
					'time_updated' => $tupdated,
					'is_link' => $islink,
					'app_name' => $app_name,
					'app_package' => $app_package,
					'app_url' => $app_url,
					'btn_txt' => $btntxt
				);
				$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告任务信息表
				$insertId = $this->adverts_db->insert($insert_data);
				if(!empty($insertId))
				{
					$msg = '提交成功!';
				}else{
					$msg = '提交失败!';
				}
				showmessage($msg,HTTP_REFERER,'2600');
			}else{
				showmessage('操作错误或数据不存在',HTTP_REFERER);
			}
		}
		
		include $this->admin_tpl('task_add_advert');
		
	}

	/*
	* 修改广告
	*/
	public function editAdvert()
	{
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告任务信息表
		$this->cms_adverts_db = pc_base::load_model('cms_adverts_model');
		$term_id = trim(getgpc('term_id'));	//终端
		$adId = trim(getgpc('adId'));	

		if(!empty($adId) && is_numeric($adId))
		{
			$spid = $this->current_spid['spid'];
			if($_SESSION['roleid']=='1'){
				$aKey = " adId = '".$adId."'";
			}else{
				$cidarr = explode(',', $_SESSION['spid']);
				$len = count($cidarr);
				for ($i = 0; $i < $len; $i++) {
					$sqlin .= "'$cidarr[$i]'";
					if ($i != $len - 1)
						$sqlin .= ",";
				}
				$aKey = " adId = '".$adId."' AND spid in (".$sqlin.")";
			}
			$adInfo = $this->adverts_db->get_one($aKey);
			$advert = $this->cms_adverts_db->get_one(array('type_id'=>$adInfo['type_id']));
			if(!empty($adInfo))
			{
				//提交处理
				if($_POST['mode'] == 'editAdTask')
				{
					//$position= trim($_POST['ad_position']);	//推荐位名称 *
					//$adType = trim($_POST['ad_adType']);		//广告类型 *
					//$viewType = trim($_POST['ad_viewType']);	//显示方式 *
					$title = trim($_POST['ad_title']);
					$adDesc = trim($_POST['ad_adDesc']);			//文字 *
					$imgType = trim($_POST['imgType']);			//图片类型
					$linkUrl = trim($_POST['ad_linkUrl']);		//链接地址
					$app_name = trim($_POST['app_name']);
					$app_package = trim($_POST['app_package']);
					$app_url = trim($_POST['app_url']);
					$btntxt = trim($_POST['btn_txt']);
					if (!$btntxt)
						$btntxt = '点击查看';
					//$taskDate = trim($_POST['ad_taskDate']);	//预发布时间 *
					$imgUrl = trim($_POST['ad_imgUrl']);		//图片地址
					$duration = trim($_POST['duration']);		//持续时间
					$regtype = trim($_POST['regtype']);		//检查图片是否正确
					$tupdated = date('Y-m-d H:i:s');
					$color = trim($_POST['color']);
					$dire = trim($_POST['dire']);
					$speed = trim($_POST['speed']);
					$starttime = trim($_POST['starttime']);
					$endtime = trim($_POST['endtime']);
					$num = trim($_POST['num']);
					$width = trim($_POST['width']);
					$height = trim($_POST['height']);
					$pop = trim($_POST['pop']);
					if(empty($imgUrl)) showmessage('图片地址不能为空!',HTTP_REFERER,3000);
					if($regtype=='') showmessage('没有确认图片是否正确!',HTTP_REFERER,3000);
					switch($adInfo['adType'])
					{
						case '1':	//文字
							if(empty($adDesc)) showmessage('文字不能为空');
						break;

						case '2':	//图片
							//if(empty($imgUrl)) showmessage('图片地址不能为空');
							if(!empty($imgType))
							{
								if($imgType == 'ImgText')	//文本地址
								{
									//$imgUrl = trim($_POST['ad_imgUrl']);		//图片地址
									if(empty($imgUrl)) showmessage('图片地址不能为空');
								}elseif($imgType == 'ImgFile'){	//上传方式 采用AJAX上传了
									/*
									if($_FILES['ad_imgUrlFile']['error'] == '0')
									{
										$upload_path = uploaded_file('ad_imgUrlFile','taskImg');	//上传图片
										if($upload_path['msg'] == '1')	//上传成功
										{
											$imgUrl = APP_PATH.'uploadfile/'.$upload_path['path'];
										}
									}
									if(empty($imgUrl)) showmessage('图片上传失败!');
									*/
								}else{
									showmessage('请设置图片');
								}
							}
						break;

						case '3':	//视频
							if(empty($linkUrl)) showmessage('链接地址不能为空');
						break;
					}

					$update_data = array(
							//'position' => $position,
							//'adType' => $adType,
							//'viewType' => $viewType,
							//'imgUrl' => $imgUrl,
							'linkUrl' => $linkUrl,
							'adDesc' => $adDesc,
							'taskDate' => strtotime($taskDate),
							'posttime' => time(),
							//'duration' => $duration,
							'time_updated' => $tupdated,
							'title' => $title,
							'color' => $color,
							'dire' => $dire,
							'speed' => $speed,
							'starttime' => strtotime($starttime),
							'endtime' => strtotime($endtime),
							'num' => $num,
							'width' => $width,
							'height' => $height,
							'pop' => $pop,
							'adStatus' => '1',
							'app_name' => $app_name,
							'app_package' => $app_package,
							'app_url' => $app_url,
							'btn_txt' => $btntxt
						);
						if(!empty($imgUrl)) $update_data['imgUrl'] = $imgUrl;
						$this->adverts_db->update($update_data,array('adId' => $adId));
		
						showmessage('提交成功',HTTP_REFERER);
				}
			}else{
				exit('操作错误或数据不存在2');
			}
		}else{
			exit('操作错误或数据不存在3');
		}
		include $this->admin_tpl('task_edit_advert');
	}

	/*
	* 删除广告
	*/
	public function deleteAdvert()
	{
		$adId = trim($_GET['adId']);
		$spid = $this->current_spid['spid'];
		if(!empty($spid) && !empty($adId) && is_numeric($adId))
		{
			$deleKey = array(
				'adId' => $adId
			);
			
			//同步删除go3c中的数据
			//$this->adverts_go3c = pc_base::load_model('tv_adverts_model');//广告任务信息表
			//$this->adverts_go3c->delete(array('ad_id' => $adId));
			
			//删除phpcms中的广告数据
			$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告任务信息表
			$res = $this->adverts_db->select("adId = '$adId'", "genus");
			$genus = $res[0]['genus'];
			if ($genus == 'APP_BACKGROUND_IMGS') {
				$ids = $this->adverts_db->select("genus = '$genus'", "adId");
				foreach ($ids as $id) {
					$this->adverts_db->delete(array('adId'=>$id['adId']));
				}
			} else {
				$this->adverts_db->delete($deleKey);
			}
			showmessage('提交成功',HTTP_REFERER);
			
		}else{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}
	}

	/*
	* 广告审核列表
	*/
	public function verifyAdvert() {
		$term_list = self::term_list();
		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告任务信息表
		$term_id = trim($_GET['term_id']);
		//申请审核处理 start
		if(in_array($_GET['status'],array('Y','N')))
		{		
			$status_data = array(
				'adId' => trim($_GET['adId']),
				'adStatus' => (trim($_GET['status']) == 'Y') ? '4':'3',	//要修改的状态值
			);
			self::advertStatus($status_data);
		}
		//申请审核处理 end
		if($_SESSION['roleid']=='1'){
			$where = " ad_belong='1' AND adStatus > 1";
		}else{
			$where = " ad_belong='1' AND spid in ('".$this->current_spid['spid']."') AND adStatus > 1";
		}			
		//查询处理 start 
		if(!empty($_GET['search']))
		{
			$term_id = trim($_GET['term_id']);			// 终端类型
			if(!empty($term_id))
			{
				$where .= " AND term_id = '".$term_id."'";
			}
			$spid = trim($_GET['spid']);			// 项目
			if(!empty($spid))
			{
				$where .= " AND spid = '".$spid."'";
			}
			$adType = trim($_GET['adType']);			// 广告类型
			if(!empty($adType))
			{
				$where .= " AND adType = '".$adType."'";
			}
			$viewType = trim($_GET['viewType']);			// 显示方式
			if(!empty($viewType))
			{
				$where .= " AND viewType = '".$viewType."'";
			}
			//	预发布时间
			$taskDate = trim($_GET['taskDate']);
			if(!empty($taskDate))
			{
				$where .= " AND ('$taskDate' = DATE_FORMAT( FROM_UNIXTIME( taskDate ) , '%Y-%m-%d' ))";
			}
		}	
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$advert_list  = $this->adverts_db->listinfo($where, $order = '`taskDate` DESC', $page, $pagesize = 15);
		$pages = $this->adverts_db->pages;
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		if($_SESSION['roleid']=='1'){
			$awhere = "";
		}else{
			$awhere = " 1 AND spid in ('".$_SESSION['spid']."')";
		}
		$sp_list = $this->cms_spid->select($awhere);
		include $this->admin_tpl('task_verify_advert');
	}
	///////////////////////广告、任务审核 end /////////////////////////////
	//添加字幕
	public function subadd(){
		$asset_id = $_REQUEST['asset_id'];
		$this->video_source = pc_base::load_model('cms_video_source_model');
		$subtitlePathDB = $this->video_source->select();
		include $this->admin_tpl('video_subadd');
	}
	public function subadddo(){
		$asset_id = trim($_POST['asset_id']);
		$url = trim($_POST['ad_imgUrl']);
		$source = trim($_POST['source']);
		$run_time = trim($_POST['run_time']);
		$language = trim($_POST['language']);
		$format = trim($_POST['format']);
		$subtitlePath = TASK_IMG_PATH . TASK_SUBTITLE_PATH;
		$this->tv_config = pc_base::load_model('tv_config_model');	
		$subtitlePathDB = $this->tv_config->select(array('field'=>'subtitle_path'));
		$subtitlePathDB = $subtitlePathDB[0]['value'];
		if ($subtitlePathDB != $subtitlePath) {
			$this->tv_config->update(array('value' => $subtitlePath), array('field' => 'subtitle_path'));
		}
		
		if(!empty($asset_id)||!empty($url)||$source!='0'){
			$this->cms_video_subtitle = pc_base::load_model('cms_video_subtitle_model');
			$data_sub = array(
				'asset_id' => $asset_id,
				'url' => $url,
				'source' => $source,
				'run_time' => $run_time,
				'language' => $language,
				'format' => $format
			);
			$this->cms_video_subtitle->insert($data_sub);
			showmessage('操作成功','index.php?m=go3c&c=video&a=online&menuid=2266');
		}else{
			showmessage('操作失败,视频id,文件或字幕来源不能为空!',HTTP_REFERER);
		}
	}
	//修改字幕
	public function subedit(){
		$id = $_REQUEST['id'];
		$this->cms_video_subtitle = pc_base::load_model('cms_video_subtitle_model');
		$aKey = "id = '".$id."'";
		$limitInfo = $this->cms_video_subtitle->get_one($aKey);
		$this->video_source = pc_base::load_model('cms_video_source_model');
		$subtitlePathDB = $this->video_source->select();
		include $this->admin_tpl('video_subedit');
	}
	public function subeditdo(){
		$id = trim($_POST['id']);
		$asset_id = trim($_POST['asset_id']);
		$url = trim($_POST['ad_imgUrl']);
		$source = trim($_POST['source']);
		$run_time = trim($_POST['run_time']);
		$language = trim($_POST['language']);
		$format = trim($_POST['format']);
		$this->cms_video_subtitle = pc_base::load_model('cms_video_subtitle_model');
		if(!empty($id)||!empty($url)||$source!='0'){
			$data_edit = array(
				'url' => $asset_id.$url,
				'source' => $source,
				'run_time' => $run_time,
				'language' => $language,
				'format' => $format
			);
			$this->cms_video_subtitle->update($data_edit,array('id'=>$id));
			showmessage('操作成功','index.php?m=go3c&c=video&a=online&menuid=2266');
		}else {
			showmessage('操作失败,视频id,文件或字幕来源不能为空!',HTTP_REFERER);
		}
	}
	//删除字幕
	public function sub_delete(){
		$id = $_REQUEST['id'];
		$asset_id = $_REQUEST['asset_id'];
		$this->cms_video_subtitle = pc_base::load_model('cms_video_subtitle_model');
		$this->cms_video_subtitle->delete(array('id'=>$id));
		$this->tv_video_subtitle = pc_base::load_model('tv_video_subtitle_model');
		$this->tv_video_subtitle->delete(array('vid'=>$asset_id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	//新上传机制
	public function doajaxfileupload()
	{
		$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/go3ccms/';
		//$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/';
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if (isset($_GET['fileid']))
			$fileElementName = $_GET['fileid'];
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{

				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$error = 'No file was uploaded..';
		}else {
			$fileName = md5(date("Y").date("m").date("d").date("H").date("i").date("s")).strrchr($_FILES[$fileElementName]['name'], ".");	
			$img_dir = 'uploadfile/taskImg';
			$uploaddir = $absolute_path.$img_dir;
		
			if(!file_exists($uploaddir))    //检查文件目录是否存在；
			{
				mkdir ($uploaddir,0777);   //创建文件目录；
			}
					
			$uploadfile = $uploaddir."/". $fileName;
			$img_path = $img_dir.'/'. $fileName;	//路径

			if(!move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile ))//上传文件
			{
				$msg = '0';
			}else{
				//$msg = '1';	//表示成功，其他都是上传失败
				//$msg = substr($img_path,1);	//返回路径
				$msg = $img_path;	//返回路径
				get_img_url($msg);	//同步图片
			}
			@unlink($_FILES['fileToUpload']);		
		}		
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";
		echo "}";
	}
	
	//文件上传机制
	public function dofileajaxfileupload()
	{
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{

				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$error = 'No file was uploaded..';
		}else {
			$asset_id = trim($_GET['asset_id']);
			$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/go3ccms/' . TASK_SUBTITLE_PATH . $asset_id;
			if(!file_exists($uploaddir))    //检查文件目录是否存在；
			{
				@mkdir ($uploaddir,0777);   //创建文件目录；
			}
			$fileName = md5(date("Y").date("m").date("d").date("H").date("i").date("s")).strrchr($_FILES[$fileElementName]['name'], ".");		
			$uploadfile = $uploaddir."/". $fileName;
			//上传到本台服务器上
			if(!move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile ))//上传文件
			{
				$msg = '0';
			}else{
				//$msg = '1';	//表示成功，其他都是上传失败
				//$msg = substr($img_path,1);	//返回路径
				$msg = TASK_SUBTITLE_PATH . $asset_id . '/' . $fileName;	//返回路径
				get_img_url($msg);	//同步图片
				$msg = $asset_id . '/' . $fileName;
			}
			@unlink($_FILES['fileToUpload']);
		}	
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";
		echo "}";
	}
	
	public function doajaxfileuploadtencent() {
		//'http://www.go3c.tv:8060/download/android/stb/'.$t.'/ktv/' . $apk['name'];
		//var_dump($_GET['type']);die();
		$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/apk/topway/';
		//$absolute_path	= 'http://192.168.150.91'.'/apk/topway/';
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if (isset($_GET['fileid']))
			$fileElementName = $_GET['fileid'];
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$error = 'No file was uploaded..';
		}else {
			$fileName = md5(date("Y").date("m").date("d").date("H").date("i").date("s")).strrchr($_FILES[$fileElementName]['name'], ".");	
			$upgradepath = 'http://192.168.150.91/apk/topway/' . $fileName;
			$uploadfile = $absolute_path . $fileName;
			if(!move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile ))//上传文件
			{	
				$msg = '0';
			}else{
				//$msg = '1';	//表示成功，其他都是上传失败
				$msg = $uploadfile;	//返回路径
				//get_img_url($msg);	//同步图片
			}
			@unlink($_FILES['fileToUpload']);		
		}
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $upgradepath . "'\n";
		echo "}";
	}
	
	public function doajaxfileuploadtopway() {
		$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/apk/upgrade/';
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if (isset($_GET['fileid']))
			$fileElementName = $_GET['fileid'];
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$error = 'No file was uploaded..';
		}else {
			$upgradepath = 'http://192.168.150.87/apk/upgrade/' . $_FILES[$fileElementName]['name'];
			$uploadfile = $absolute_path . $_FILES[$fileElementName]['name'];
			if(!move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile ))//上传文件
			{	
				$msg = '0';
			}else{
				//$msg = '1';	//表示成功，其他都是上传失败
				$msg = $uploadfile;	//返回路径
				//get_img_url($msg);	//同步图片
			}
			@unlink($_FILES['fileToUpload']);		
		}
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $upgradepath . "'\n";
		echo "}";
	}
	
	//添加推荐外链视频
	public function addOutTask(){
		$term_id = $_REQUEST['term_id'];
		$taskId = $_REQUEST['taskId'];
		include $this->admin_tpl('task_add_outvideo');
	}
	//添加推荐外链视频
	public function addOutTaskdo(){
		$this->pre_task_video = pc_base::load_model('cms_pre_task_video_model');
		$this->task_db = pc_base::load_model('cms_pre_task_model');	
		$asset_id = $_REQUEST['asset_id'];
		$title = $_REQUEST['title'];
		$videoDesc = $_REQUEST['videoDesc'];
		$long_desc = $_REQUEST['long_desc'];
		$videoImgUrl = $_REQUEST['videoImgUrl'];
		$term_id = $_REQUEST['term_id'];
		$playurl = $_REQUEST['playurl'];
		$taskId = $_REQUEST['taskId'];
		if(empty($asset_id) || empty($term_id)|| empty($taskId)|| empty($title))
		{
			exit('操作错误或数据异常!');
		}
		$aKey = " taskId = '".$taskId."' AND term_id = '".$term_id."' AND videoId = '".$asset_id."'";
		$limitInfo = $this->pre_task_video->get_one($aKey);
		if(!empty($limitInfo)){
			showmessage('提交失败,请注意同一推荐位不能有重复视频!',HTTP_REFERER);
		}
		$taskInfo = self::getOneTask($taskId);
		$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
		$updateVideNums = $taskInfo['videoNums'] + 1;
		if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1]))
		{
			$insert_data = array(
			'taskId' => $taskId,						//任务ID	
			'term_id' => $term_id,						//终端类型
			'posid' => $taskInfo['posid'],				//推荐位类型
			'spid' => $taskInfo['spid'],				//运营商
			'posidInfo' => $taskInfo['posidInfo'],		//任务名称
			'videoId' => $asset_id,						//视频ID
			'videoSource' => $taskInfo['videoSource'],	//来自那个表类型
			'videoTitle' => $title,						//名称	
			'videoPlayUrl' => $playurl,					//播放地址						
			'videoDesc' => $videoDesc,					//视频简介
			'videoImg' => $videoImgUrl,
			'videoSort' => '0',							//排序
			'status' => 'Y',							//可用状态
			'crontab_date' => time(),					//任务时间
			'online_date' => '0',
			'online_status' => '11',
			'offline_date' => '0',
			'offline_status' => '0',
			'posttime' => time(),
			'isout' => '1',
			'area_id' => $taskInfo['area_id']
		);
		$status = $this->pre_task_video->insert($insert_data);
		if(!empty($status))
		{
			$taskUpdate = array(
					'videoNums' => $updateVideNums,	//任务视频数量
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
			showmessage('提交失败!',HTTP_REFERER);
		}
		}else{
			showmessage('推荐位'.$tasknum['posidInfo'].'的视频数量应该在'.$tasknum['start_end_nums'],HTTP_REFERER);
		}
	}
	//资讯图片上传
	public function inforfileupload()
	{
		$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/yinlebao/';
		
		//$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/';
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if (isset($_GET['fileid']))
			$fileElementName = 'fileToUpload1';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{
	
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
	
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$error = 'No file was uploaded..';
		}else {
			$fileName = md5(date("Y").date("m").date("d").date("H").date("i").date("s")).strrchr($_FILES[$fileElementName]['name'], ".");
			$img_dir = '/uploadfile/infor';
			$uploaddir = $absolute_path.$img_dir;
	
			if(!file_exists($uploaddir))    //检查文件目录是否存在；
			{
				mkdir ($uploaddir,0777);   //创建文件目录；
			}
				
			$uploadfile = $uploaddir."/". $fileName;
			$img_path = $img_dir.'/'. $fileName;	//路径
	
			if(!move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile ))//上传文件
			{
				$msg = '0';
			}else{
				//$msg = '1';	//表示成功，其他都是上传失败
				//$msg = substr($img_path,1);	//返回路径
				$msg =$img_path;	//返回路径
				get_img_url($msg);	//同步图片
			}
			@unlink($_FILES['fileToUpload']);
		}
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";
		echo "}";
	}
	
	/*
	 * 添加资讯
	*/
	public function addinfordo(){
		$userid = $_SESSION['userid'];
		$this->information = pc_base::load_model('cms_information_model');
		$this->information_area = pc_base::load_model('cms_information_area_model');
		$this->cms_area = pc_base::load_model('cms_area_model');
		$title = trim($_POST['title']);
		$type = trim($_POST['type']);
		$keywords = trim($_POST['keywords']);
		$content = trim($_POST['content']);
		$description = trim($_POST['description']);
		$author = trim($_POST['author']);
		$playcount = trim($_POST['playcount']);
		$thumb = TASK_IMG_PATH.trim($_POST['ad_imgUrl']);
		$linkurl = trim($_POST['linkurl']);
		$menuid=$_POST['menuid'];//一个数组;
		if(empty($menuid)){
			showmessage('点播区域不能为空!', HTTP_REFERER,$ms = 1000);
		}
		if(!empty($title)&&!empty($description)){
			$data_infor = array(
					'title' => $title,
					'type' => $type,
					'keywords' => $keywords,
					'content' => $content,
					'description' => $description,
					'author' => $author,
					'playcount' => $playcount,
					'linkurl' => $linkurl,
					'thumb' => $thumb,
					'inputtime' =>time(),
					'updatetime' =>time(),
					'sysadd' =>$userid
			);
			$this->information->insert($data_infor);
			$nid = $this->information->insert_id();
			if($nid){
				//添加新闻区域
				foreach ($menuid as $vc){
					$area_id = $vc;
					if($area_id=='01'){
						$area_id = '0';
						continue;
					}
					$ad_area_le = $this->cms_area->get_one(array('id'=>$area_id));
					$data_vi = array(
							'nid'		=>$nid,
							'aid'		=>$area_id,
							'aname'		=>$ad_area_le['name'],
							'inputtime' =>time()
					);
					//循环插入点播区域记录
					$this->information_area->insert($data_vi);
				}
			}
			showmessage('操作成功','index.php?m=go3c&c=infor&a=infor_list');
		}else{
			showmessage('操作失败,资讯标题或内容不能为空!',HTTP_REFERER);
		}
	}
	/*
	 * 修改资讯
	 */
	public function editinfordo(){
		$userid = $_SESSION['userid'];
		$this->information = pc_base::load_model('cms_information_model');
		$this->information_area = pc_base::load_model('cms_information_area_model');
		$this->cms_area = pc_base::load_model('cms_area_model');
		$id = trim($_POST['id']);
		$title = trim($_POST['title']);
		$type = trim($_POST['type']);
		$keywords = trim($_POST['keywords']);
		$content = trim($_POST['content']);
		$description = trim($_POST['description']);
		$author = trim($_POST['author']);
		$playcount = trim($_POST['playcount']);
		$thumb = TASK_IMG_PATH.trim($_POST['ad_imgUrl']);
		$linkurl = trim($_POST['linkurl']);
		$menuid=$_POST['menuid'];//一个数组;
		if(empty($menuid)){
			showmessage('点播区域不能为空!', HTTP_REFERER,$ms = 1000);
		}
		if(!empty($title)&&!empty($description)){
			$data_infor = array(
					'title' => $title,
					'type' => $type,
					'keywords' => $keywords,
					'content' => $content,
					'description' => $description,
					'author' => $author,
					'playcount' => $playcount,
					'linkurl' => $linkurl,
					'thumb' => $thumb,
					'updatetime' =>time(),
					'sysadd' =>$userid
			);
			if($id){
				$this->information_area->delete(array('nid'=>$id));
				//添加新闻区域
				foreach ($menuid as $vc){
					$area_id = $vc;
					if($area_id=='01'){
						$area_id = '0';
						continue;
					}
					$ad_area_le = $this->cms_area->get_one(array('id'=>$area_id));
					$data_vi = array(
							'nid'		=>$id,
							'aid'		=>$area_id,
							'aname'		=>$ad_area_le['name'],
							'inputtime' =>time()
					);
					//循环插入点播区域记录
					$this->information_area->insert($data_vi);
				}
			}
			$this->information->update($data_infor,array('id'=>$id));
			showmessage('操作成功','index.php?m=go3c&c=infor&a=infor_list');
		}else{
			showmessage('操作失败,资讯标题或内容不能为空!',HTTP_REFERER);
		}
	}
	/*
	 *添加推荐资讯展示页面 
	 */
	public function addinfortask(){
		$this->information = pc_base::load_model('cms_information_model');
		$taskId   	= trim($_GET['taskId']);
		$id   	= trim($_GET['id']);
		$pkey="id ='".$id."'";
		$videoInfo = $this->information->get_one($pkey);
		include $this->admin_tpl('infor_task_add');
	}
	/*
	 * 添加推荐资讯
	 */
	public function addinfortaskdo(){
		$id = trim($_POST['id']);
		$taskId = trim($_POST['taskId']);
		$videoTitle = trim($_POST['videoTitle']);
		$videoDesc = trim($_POST['videoDesc']);
		$long_desc = trim($_POST['long_desc']);
		$videoImgUrl = trim($_POST['videoImgUrl']);
		$infor_url = trim($_POST['infor_url']);
		$this->task_db = pc_base::load_model('cms_pre_task_model');
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');
		$this->information = pc_base::load_model('cms_information_model');
		$gkey="id = $id";
		$videoInfo = $this->information->get_one($gkey);
		//查询该资讯是否已经加入该推荐位
		$pkey="taskId ='".$taskId."' and videoId='".$id."'";
		$gtaskInfo = $this->task_video_db->get_one($pkey);
		if(!empty($gtaskInfo)) showmessage('该资讯已经添加到推荐位!',HTTP_REFERER);
		if(empty($videoImgUrl)) showmessage('请设置海报!',HTTP_REFERER);
		//查询该推荐位能添加的个数
		$aKey = " taskId = '".$taskId."' AND spid = '".$this->current_spid['spid']."' ";
		$taskInfo = $this->task_db->get_one($aKey);	//是否存在
		$start_end_nums  = explode('-',$taskInfo['start_end_nums']);
		$updateVideNums = $taskInfo['videoNums'] + 1;
		if(($updateVideNums >= $start_end_nums[0]) && ($updateVideNums <= $start_end_nums[1])){  //该推荐为的数量在范围内
			$insert_data = array(
					'taskId' => $taskId,			            //任务ID
					'term_id' => $taskInfo['term_id'],			//终端类型
					'posid' => $taskInfo['posid'],				//推荐位类型
					'spid' => $taskInfo['spid'],				//运营商
					'posidInfo' => $taskInfo['posidInfo'],		//任务名称
					'videoId' => $id,							//资讯ID
					'videoSource' => $taskInfo['videoSource'],	//来自那个表类型
					'videoTitle' => $videoTitle,				//名称
					'imgType' => $taskInfo['imgType'],			//图片类型
					'videoPlayUrl' => $infor_url ? $infor_url : '',//链接地址
					'videoDesc' => $videoDesc,					//视频简介
					'videoImg' => $videoImgUrl,
					'videoSort' => '0',							//排序
					'status' => 'Y',							//可用状态
					'crontab_date' => $taskInfo['taskDate'],	//任务时间
					'online_date' => strtotime($videoInfo['inputtime'])?strtotime($videoInfo['inputtime']):'0',
					'online_status' => $videoInfo['online_status'],
					'posttime' => time()
			);
			$status = $this->task_video_db->insert($insert_data);
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
	//复制推荐位
	public function copytask(){
		$term_id = trim($_GET['term_id']);
		//查询终端包含的推荐位
		$pkey="term_id ='".$term_id."'";
		$TaskInfo = $this->task_db->select($pkey);
		include $this->admin_tpl('task_copytask');
	}
	//操作复制推荐位动作
	public function copytaskdo(){
		$taskIdc = trim($_POST['taskIdc']);
		$taskIdd = trim($_POST['taskIdd']);
		//查询目标推荐位的信息
		$tkey="taskId ='".$taskIdd."'";
		$TaskInfo = $this->task_db->get_one($tkey);
		//查询需要复制的某个推荐位的视频
		$pkey="taskId ='".$taskIdc."'";
		$VideoInfo = $this->task_video_db->select($pkey);
		foreach ($VideoInfo as $v){
			$start_end_nums  = explode('-',$TaskInfo['start_end_nums']);
			$updateVideNums = $TaskInfo['videoNums']+1;
			if($updateVideNums<=$start_end_nums[1]){
				$taskvideo = array(
						'taskId' => $TaskInfo['taskId'],			//任务ID
						'term_id' => $TaskInfo['term_id'],			//终端类型
						'posid' => $TaskInfo['posid'],				//推荐位类型
						'spid' => $TaskInfo['spid'],				//运营商
						'posidInfo' => $TaskInfo['posidInfo'],		//任务名称
						'videoId' => $v['videoId'],						//视频ID
						'videoSource' => $v['videoSource'],			//来自那个表类型
						'videoTitle' => $v['videoTitle'],				//名称
						'imgType' => $v['imgType'],
						'videoImg' => $v['videoImg'],
						'videoClarity' => $v['videoClarity'],			//对应清晰度类型
						'videoPlayUrl' => $v['videoPlayUrl'],			//播放地址
						'videoDesc' => $v['videoDesc'],					//视频简介
						'videoSort' => '0',								//排序
						'status' => 'Y',								//可用状态
						'crontab_date' => $TaskInfo['taskDate'],		//任务时间
						'online_date' => $v['online_date'],
						'online_status' => $v['online_status'],
						'offline_date' =>$v['offline_date'],
						'offline_status' => $v['offline_status']
				
				);
				$this->task_video_db->insert($taskvideo);
				//更新推荐位的信息
				$taskUpdate = array(
						'videoNums' => $updateVideNums,	//任务视频数量
						'taskStatus' => '1',	//回滚到默认编辑状态
						'taskDate' => $TaskInfo['taskDate']
				);
				$taskWhere = array(
						'taskId' => $TaskInfo['taskId']
				);
				$this->task_db->update($taskUpdate,$taskWhere);
			}else{
				showmessage('复制完成,已达推荐位准许最大数量!',HTTP_REFERER);
			}
		}
		$msg = "提交成功,请注意：此推荐任务列表内容已变化，需要重新提交审核，并发布才能生效！";
		showmessage($msg,HTTP_REFERER,30000);
	}
	/*
	 * 开机过程审核列表
	*/
	public function belong() {
		$term_list = self::term_list();
		$taskStatus_data = self::taskStatus();	//得到状态中文名称配置
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告任务信息表
		$term_id = trim($_GET['term_id']);
		//申请审核处理 start
		if(in_array($_GET['status'],array('Y','N')))
		{
			$status_data = array(
					'adId' => trim($_GET['adId']),
					'adStatus' => (trim($_GET['status']) == 'Y') ? '4':'3',	//要修改的状态值
			);
			self::advertStatus($status_data);
		}
		//申请审核处理 end
		if($_SESSION['roleid']=='1'){
			$where = " ad_belong='2' AND adStatus > 1";
		}else{
			$where = " ad_belong='2' AND spid in ('".$this->current_spid['spid']."') AND adStatus > 1";
		}
		//查询处理 start
		if(!empty($_GET['search']))
		{
			$term_id = trim($_GET['term_id']);			// 终端类型
			if(!empty($term_id))
			{
				$where .= " AND term_id = '".$term_id."'";
			}
				
			//	预发布时间
			$taskDate = trim($_GET['taskDate']);
			if(!empty($taskDate))
			{
				$where .= " AND ('$taskDate' = DATE_FORMAT( FROM_UNIXTIME( taskDate ) , '%Y-%m-%d' ))";
			}
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$advert_list  = $this->adverts_db->listinfo($where, $order = '`taskDate` DESC', $page, $pagesize = 15);
		$pages = $this->adverts_db->pages;
		include $this->admin_tpl('task_verify_belong');
	}
	//除管理员以外的用户上线开机
	public function advertonline(){
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');
		$term_id = trim($_GET['term_id']);
		$adId = trim($_GET['adId']);
		$this->adverts_db->update(array('adStatus'=>100),array('adId' => $adId,'term_id' => $term_id));
		showmessage('提交成功',HTTP_REFERER);
	}
}
