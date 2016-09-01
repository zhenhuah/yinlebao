<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('admin', 'admin', 0);

//服务器的图片路径地址

define('TASK_IMG_PATH','http://www.go3c.tv/images/go3ccms/');
//define('TASK_IMG_PATH','http://go3c.jdjkcn.net/');

//本地的客户端的图片域名(项目域名)(同步的时候用)
define('TASK_IMG_PATH_Local','http://www.go3c.tv:8060/go3ccms/');
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

class boot extends admin {
	function __construct() {
		parent::__construct();
		pc_base::load_app_func('global_task');	//方法文件
		pc_base::load_app_func('global');
		
		$this->task_db = pc_base::load_model('cms_pre_task_model');			//任务信息表连接
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');//视频任务数据表连接
		$this->spid_db = pc_base::load_model('admin_model');			//后台登录表连接
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));	
		$this->term_db = pc_base::load_model('cms_term_type_model');	//终端类型信息表连接
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告推荐位信息表
	}
	
	/*
	* 默认当前用户的数据列表
	*/
	public function init(){
	}

	//打包升级包
	public function generateZip() {
		$cid = $_GET['cid'];
		$ids = explode(',', $_GET['ids']);
		//remove upgrade data
		$path = $_SERVER['DOCUMENT_ROOT'] . '/download/KTV_Data/appboot/' . $cid;
		if(file_exists($path)) {
			$this->del_DirAndFile($path);
		}
		mkdir ($path,0777);   //创建文件目录
		$appwizardsIndex = 1;
		foreach ($ids as $id) {
			$ad = $this->adverts_db->select("adId = '$id'","*");
			$genus = $ad[0]['genus'];
			$img = $ad[0]['imgUrl'];
			if ($genus == 'APP_BACKGROUND_IMGS') {
				$bgtype = $ad[0]['app_bg_img_type']; 
				$pathbg = $path . '/app_animationnew';
				if(!file_exists($pathbg))
					mkdir($pathbg, 0777);
				if ($bgtype == 1)
					copy($img, $pathbg . '/app_animation.jpg');
				else if ($bgtype == 2)
					copy($img, $pathbg . '/app_restart_animation.jpg');
				else if ($bgtype == 3)
					copy($img, $pathbg . '/app_return_animation.jpg');
				else if ($bgtype == 4)
					copy($img, $pathbg . '/app_pause_animation.jpg');
			} else if ($genus == 'APP_WIZARDS') {
				$pathbg = $path . '/app_wizardsnew';
				if(!file_exists($pathbg))
					mkdir($pathbg, 0777);
				copy($img, $pathbg . '/guider_' . $appwizardsIndex . '.jpg');
				$appwizardsIndex++;
			} else if ($genus == 'BOOTANIMATION') {
				copy($img, $path . '/bootanimation.zip');
			}
		}
		//generate xml
		$xmlurl = $path . '/version.xml';
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml .= "<upgrade>\n";
		$xml .= "<code>1</code>\n";
		$xml .= "<description>upgrade app boot resources</description>\n";
		$xml .= "<url>1</url>\n";
		$xml .= "<must>1</must>\n";
		$xml .= "<size>1234</size>\n";
		$xml .="<versioncode>".date("YmdH")."</versioncode>\n";
		$xml .="</upgrade>\n";
		$fp=fopen("$xmlurl","w+");
		fwrite($fp,$xml);
		@fclose($fp);
		//update upgrade xml versioncode
		$upgradexml = "/home/wwwroot/default/go3ccms/xml/appassets/app_animationnew/version.xml";
		//$upgradexml = str_replace('http://www.go3c.tv:8060/', '/home/wwwroot/default/', $upgradexml);
		$fp=fopen("$upgradexml","w+");
		fwrite($fp,$xml);
		@fclose($fp);
		//generate app boot upgrade zip
		require_once $_SERVER['DOCUMENT_ROOT'] . '/go3ccms/zipphp.php';
		$archive  = new PHPZip();
		$desurl = $path . '/appassets_' . date("YmdH") . '.zip';
		$archive->Zip($path, $desurl);
		$desurl = str_replace('/home/wwwroot/default/', 'http://www.go3c.tv:8060/', $desurl);
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=boot&a=bootlist&term_id=1&zipurl=' . $desurl);
	}
	
	private function del_DirAndFile($dirName){
		if(is_dir($dirName)){
			//echo "<br /> ";
			if ( $handle = opendir( "$dirName" ) ) {
				while ( false !== ( $item = readdir( $handle ) ) ) {
					if ( $item != "." && $item != ".." ) {
						if ( is_dir( "$dirName/$item" ) ) {
							$this->del_DirAndFile( "$dirName/$item" );
						} else {
							if( unlink( "$dirName/$item" ) ) {
								//echo "已删除文件: $dirName/$item<br /> ";
							}
						}
					}
				}
				closedir( $handle );
				if( rmdir( $dirName ) ) {
				//	echo "已删除目录: $dirName<br /> ";
				} 
			}
		}
	}
	
	//开机图列表
	public function bootlist(){
		$spid = $this->current_spid['spid'];
		$term_type_list = array();
		$term_type_list = $this->term_db->select($where = '', $data = 'id,title', $limit = '', $order = 'id ASC', $group = '', $key='');
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->adverts_db->select($where = 'ad_belong=2', $data = 'adId,spid,version', $limit = '', $order = 'adId ASC', $group = ' spid', $key='');
		}else{
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$spid_list = $this->adverts_db->select($where = 'ad_belong=2 and spid in ('.$sqlin.')', $data = 'adId,spid,version', $limit = '', $order = 'adId ASC', $group = ' spid', $key='');
		}		
		$term_type_data = array_keys(self::term_list());	//所有终端的ID
		$term_id = trim($_GET['term_id']);
		if(!in_array($term_id,$term_type_data))
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}	
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
		if($_SESSION['roleid']=='1'){
			$aKey = " ad_belong='2'";
		}else{
			$aKey = " spid in (".$sqlin.") AND ad_belong='2'";
		}
		//查询
		if($_GET['mode'] == 'query')
		{
			//终端term
			$term = trim($_GET['term']);
			if(!empty($term)){
				$aKey .= " AND term_id = '".$term."'";
			}
			//版本version
			$version = trim($_GET['version']);
			if(!empty($version)){
				$aKey .= " AND version = '".$version."'";
			}

			//显示类型 viewType
			$spid = trim($_GET['spid']);
			if(!empty($spid)){
				$aKey .= " AND spid = '".$spid."'";
			} else if ($_SESSION['roleid']!='1') {
				$aKey .= " AND spid in (".$sqlin.")";
			}

			//发布时间 taskDate
			$taskDate = trim($_GET['taskDate']);
			if(!empty($taskDate)){
				$aKey .= " AND taskDate = '".strtotime($taskDate)."'";
			}
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$advert_list  = $this->adverts_db->listinfo($aKey, $order = '`taskDate` DESC', $page, $pagesize = 15);
		$pages = $this->adverts_db->pages;
		include $this->admin_tpl('task_boot_list');
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
	 * 广告状态修改
	*/
	private function advertStatus($status_data) {
	
		if(!empty($status_data) && is_array($status_data))
		{
			$adId = trim($status_data['adId']);	//广告任务ID
	
			if(!empty($adId) && is_numeric($adId) && is_numeric($status_data['adStatus']))
			{
				if($_SESSION['roleid']=='1'){
					$getOneWhere = " adId = '".$adId."' ";
				}else{
					$getOneWhere = " adId = '".$adId."' AND spid in ('".$this->current_spid['spid']."') ";
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
	 * 所有终端类型 v9_term_type
	*/
	public function term_list(){
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
		if($_SESSION['roleid']=='1'){
			$adWhere = " term_id = '".$term_id."'";
		}else{
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$adWhere = ' ad_belong=2 and spid in ('.$sqlin.')';
		}
		$term_adverts_data = $this->cms_adverts_db->select($adWhere);
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
			$insertPosInfo = $term_adverts_list[$position];
			$adDesc = trim($_POST['ad_adDesc']);		//文字 *
			$imgType = trim($_POST['imgType']);			//图片类型
			$linkUrl = trim($_POST['ad_linkUrl']);		//链接地址
			//$taskDate = trim($_POST['ad_taskDate']);	//预发布时间 *
			$imgUrl = trim($_POST['ad_imgUrl']);		//图片地址
			$duration = trim($_POST['duration']);		//持续时间
			//$version = trim($_POST['version']);		    //版本号
			$tupdated = date('Y-m-d H:i:s');
	
			$regtype = trim($_POST['regtype']);		//检查图片是否正确
			$tupdated = date('Y-m-d H:i:s');
			
			//app background imgs
			$url1 = $_POST['url_app_animation'];
			$url2 = $_POST['url_app_restart_animation'];
			$url3 = $_POST['url_app_return_animation'];
			$url4 = $_POST['url_app_pause_animation'];
			$bootuploadpath = $_SERVER['DOCUMENT_ROOT'] . '/images/go3ccms/uploadfile/boot/';
			$bootuploadpathabs = 'http://www.go3c.tv:8060/images/go3ccms/uploadfile/boot/';
			if (!$url1 && $_FILES['file_app_animation']['size']) {
				$file1 = $_FILES['file_app_animation'];
				if (!$file1['error'] && $file1['size']) {
					move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_animation.jpg');
				}
				$url1 = $bootuploadpathabs . 'app_animation.jpg';
			}
			if (!$url2 && $_FILES['file_app_restart_animation']['size']) {
				$file1 = $_FILES['file_app_restart_animation'];
				if (!$file1['error'] && $file1['size']) {
					move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_restart_animation.jpg');
				}
				$url2 = $bootuploadpathabs . 'app_restart_animation.jpg';
			}
			if (!$url3 && $_FILES['file_app_return_animation']['size']) {
				$file1 = $_FILES['file_app_return_animation'];
				if (!$file1['error'] && $file1['size']) {
					move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_return_animation.jpg');
				}
				$url3 = $bootuploadpathabs . 'app_return_animation.jpg';
			}
			if (!$url4 && $_FILES['file_app_pause_animation']['size']) {
				$file1 = $_FILES['file_app_pause_animation'];
				if (!$file1['error'] && $file1['size']) {
					move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_pause_animation.jpg');
				}
				$url4 = $bootuploadpathabs . 'app_pause_animation.jpg';
			}
			if ($url1) {
				//app background imgs
				$arr = array($url1,$url2,$url3,$url4);
				foreach ($arr as $k => $v) {
					$data = array(
							'term_id' => $term_id,
							'spid' => $insertPosInfo['SPID'],
							'parentId' => $position,
							'position' => $insertPosInfo['title'],
							'type_id' => $insertPosInfo['type_id'],
							'adType' => $insertPosInfo['ad_type'],		//广告类型 *
							'viewType' => $insertPosInfo['display_type'],	//显示方式 *
							'imgUrl' => $v,
							'linkUrl' => $linkUrl,
							'adDesc' => $adDesc,
							//'taskDate' => strtotime($taskDate),
							'posttime' => time(),
							'adStatus' => '1',
							'duration' => $duration,
							'time_updated' => $tupdated,
							//'version' => $version,
							'ad_belong' => '2',
							'genus' => $insertPosInfo['genus'],
							'ispic' => 1,
							'app_bg_img_type' => ($k + 1)
					);
					$this->adverts_db->insert($data);
				}
				showmessage('操作成功',HTTP_REFERER,'2000');
			}
			
			if(empty($imgUrl)) showmessage('图片地址不能为空!',HTTP_REFERER,3000);
			if($regtype=='') showmessage('没有确认图片是否正确!',HTTP_REFERER,3000);
			//if($version==''|| !preg_match('/^[0-9-]+$/',$version)) showmessage('请填写版本号且为纯数字!',HTTP_REFERER,3000);
			//判断该开机选项是否已经添加
			$advert = $this->adverts_db->get_one(array('ad_belong'=>2,'spid'=>$insertPosInfo['SPID'],'genus'=>$insertPosInfo['genus']));
			if(!empty($advert) && $advert['genus'] != 'APP_WIZARDS'){
				showmessage('该开机项已经添加!',HTTP_REFERER,3000);
			}
			if(!empty($position) && !empty($insertPosInfo))
			{
				//得到当前的PID的广告类型判断那个字段不能为空处理 start
				switch($insertPosInfo['ad_type'])
				{
					case '1':	//文字
						if(empty($imgUrl)) showmessage('文字不能为空');
						break;
	
					case '2':	//图片
						if(!empty($imgType))
						{
							if($imgType == 'ImgText')	//文本地址
							{
								//$imgUrl = trim($_POST['ad_imgUrl']);		//图片地址
								if(empty($imgUrl)) showmessage('图片地址不能为空');
							}elseif($imgType == 'ImgFile'){	//上传方式 采用AJAX上传了
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
				if((strpos($imgUrl,'png')!== false)||(strpos($imgUrl,'jpg')!== false)||(strpos($imgUrl,'jpeg')!== false)||(strpos($imgUrl,'gif')!== false)){
					$ispic='1';
				}else{
					$ispic='2';
				}
				$insert_data = array(
						'term_id' => $term_id,
						'spid' => $insertPosInfo['SPID'],
						'parentId' => $position,
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
						'time_updated' => $tupdated,
						//'version' => $version,
						'ad_belong' => '2',
						'genus' => $insertPosInfo['genus'],
						'ispic' => $ispic
				);
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
		include $this->admin_tpl('task_add_boot');	
	}
	/*
	 * 修改广告
	*/
	public function editAdvert()
	{
		$term_id = trim(getgpc('term_id'));	//终端
		$adId = trim(getgpc('adId'));
		if(!empty($adId) && is_numeric($adId))
		{
			$spid = $this->current_spid['spid'];
			if($_SESSION['roleid']=='1'){
				$aKey = " adId = '".$adId."' AND ad_belong='2'";
			}else{
				$cidarr = explode(',', $_SESSION['spid']);
				$len = count($cidarr);
				for ($i = 0; $i < $len; $i++) {
					$sqlin .= "'$cidarr[$i]'";
					if ($i != $len - 1)
						$sqlin .= ",";
				}
				$aKey = " adId = '".$adId."' AND spid in (".$sqlin.") AND ad_belong='2'";
			}
			$adInfo = $this->adverts_db->get_one($aKey);
			if(!empty($adInfo))
			{
				//提交处理
				if($_POST['mode'] == 'editAdTask')
				{
					$adDesc = trim($_POST['ad_adDesc']);			//文字 *
					$imgType = trim($_POST['imgType']);			//图片类型
						
					$linkUrl = trim($_POST['ad_linkUrl']);	//链接地址
					//$taskDate = trim($_POST['ad_taskDate']);	//预发布时间 *
					$imgUrl = trim($_POST['ad_imgUrl']);		//图片地址
					$duration = trim($_POST['duration']);		//持续时间
					$regtype = trim($_POST['regtype']);		//检查图片是否正确
					$tupdated = date('Y-m-d H:i:s');
					$version = trim($_POST['version']);      //版本
					if(empty($imgUrl)) showmessage('图片地址不能为空!',HTTP_REFERER,3000);
					if($regtype=='') showmessage('没有确认图片是否正确!',HTTP_REFERER,3000);
					//if($version==''|| !preg_match('/^[0-9-]+$/',$version)) showmessage('请填写版本号且为纯数字!',HTTP_REFERER,3000);
					//if($version<$adInfo['version']){
					//	showmessage('新版本号要比旧版本号大!',HTTP_REFERER,3000);
					//}
					switch($adInfo['adType'])
					{
						case '1':	//文字
							if(empty($adDesc)) showmessage('文字不能为空');
							break;
	
						case '2':	//图片
							if(!empty($imgType))
							{
								if($imgType == 'ImgText')	//文本地址
								{
									if(empty($imgUrl)) showmessage('图片地址不能为空');
								}elseif($imgType == 'ImgFile'){	//上传方式 采用AJAX上传了
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
								'linkUrl' => $linkUrl,
								'adDesc' => $adDesc,
								'taskDate' => strtotime($taskDate),
								'posttime' => time(),
								'time_updated' => $tupdated,
								'version' => $version,
								'adStatus' => '1'
						);
	
						if(!empty($imgUrl)) $update_data['imgUrl'] = $imgUrl;
						if(!empty($imgUrl)){
							if((strpos($imgUrl,'png')!== false)||(strpos($imgUrl,'jpg')!== false)||(strpos($imgUrl,'jpeg')!== false)||(strpos($imgUrl,'gif')!== false)){
								$ispic='1';
							}else{
								$ispic='2';
							}
							$update_data['ispic'] = $ispic;
						}
						$this->adverts_db->update($update_data,array('adId' => $adId,'spid' => $adInfo['spid']));
	
						showmessage('提交成功',HTTP_REFERER);
				}
			}else{
				exit('操作错误或数据不存在');
			}
		}else{
			exit('操作错误或数据不存在');
		}
		include $this->admin_tpl('task_edit_boot');
	}
	/*
	 * 广告
	*/
	public function advert() {
		$term_type_data = array_keys(self::term_list());	//所有终端的ID
		$term_id = trim($_GET['term_id']);
		if(!in_array($term_id,$term_type_data))
		{
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}
	
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
			$aKey = " term_id = '".$term_id."' ";
		}else{
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$aKey = " term_id = '".$term_id."' AND spid in (".$sqlin.") ";
		}
		//查询
		if($_GET['mode'] == 'query')
		{
			//广告类型 adType
			$adType = trim($_GET['adType']);
			if(!empty($adType)){
				$aKey .= " AND adType = '".$adType."'";
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
		$advert_list  = $this->adverts_db->listinfo($aKey, $order = '`taskDate` DESC', $page, $pagesize = 15);
		$pages = $this->adverts_db->pages;
	
		include $this->admin_tpl('task_boot_list');
	}
	//预览开机图
	public function showview(){
		$term_id = $_GET['term_id'];
		$spid = $_GET['spid'];
		$field    = isset($_GET['field']) ? $_GET['field'] : 'adId';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$where = "term_id = '".$term_id."' AND spid = '".$spid."' ";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '1';
		$data  = $this->adverts_db->listinfo($where, $order = "$field $order", $page, $perpage);
		
		include $this->admin_tpl('task_view_boot');
	}
	//遥控器列表
	public function remote(){
		$spid = $this->current_spid['spid'];
		$name = $_GET['name'];
		$type = $_GET['type'];
		$this->remote_db = pc_base::load_model('cms_remote_model');//遥控器信息表
		$field    = isset($_GET['field']) ? $_GET['field'] : 'id';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'ASC';
		if($_SESSION['roleid']=='1'){
			$where = "1 ";
		}else{
			$where = " spid in ('".$spid."') ";
		}
		if(!empty($name)){
			$where.= " name='".$name."' ";
		}
		if(!empty($type)){
			$where.= " type='".$type."' ";
		}
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->remote_db->listinfo($where, $order = "$field $order", $page, $perpage);
		include $this->admin_tpl('boot_remote_list');
	}
	//添加遥控器模型
	public function addremote(){
		include $this->admin_tpl('boot_add_remote');
	}
	//添加遥控器操作
	public function addremotedo(){
		$this->remote_db = pc_base::load_model('cms_remote_model');//遥控器信息表
		$name = trim($_POST['name']);
		$type = trim($_POST['type']);
		$ad_imgUrl = trim($_POST['ad_imgUrl']);
		$remote = trim($_POST['remote']);
		$spid = trim($_POST['spid']);
		$contetdec = trim($_POST['contetdec']);
		$lang = trim($_POST['lang']);
		if(empty($name)||empty($ad_imgUrl)){
			showmessage('名称和丝印不能为空');
		}else{
			$inset_remote = array(
					'name' 	  => $name,
					'type'    => $type,
					'contetdec'=>$contetdec,
					'siurl'   => $ad_imgUrl,
					'lang'	  =>$lang,
					'remote'  => $remote,
					'spid'    => $spid,
					'createtime'=> time()
			);
			$this->remote_db->insert($inset_remote);
			showmessage("提交成功!",HTTP_REFERER,'2600');
		}
	}
	//修改遥控器模型
	public function editremote(){
		$this->remote_db = pc_base::load_model('cms_remote_model');//遥控器信息表
		$id = trim($_GET['id']);
		$remote = $this->remote_db->get_one(array('id'=>$id));
		include $this->admin_tpl('boot_edit_remote');
	}
	//修改遥控器操作
	public function editremotedo(){
		$this->remote_db = pc_base::load_model('cms_remote_model');//遥控器信息表
		$id = trim($_POST['id']);
		$ad_imgUrl = trim($_POST['ad_imgUrl']);
		$remote = trim($_POST['remote']);
		$contetdec = trim($_POST['contetdec']);
		if(empty($id)){
			showmessage('数据异常!');
		}
		if(empty($contetdec)||empty($ad_imgUrl)){
			showmessage('丝印不能为空');
		}else{
			$iremote = array(
					'contetdec'=>$contetdec,
					'siurl'   => $ad_imgUrl,
					'remote'  => $remote,
					'createtime'=> time()
			);
			$this->remote_db->update($iremote,array('id'=>$id));
			showmessage("提交成功!",HTTP_REFERER,'2600');
		}
	}
	//删除遥控器
	public function delremote(){
		$this->remote_db = pc_base::load_model('cms_remote_model');//遥控器信息表
		$id = trim($_GET['Id']);
		if(empty($id)){
			showmessage('数据异常!');
		}
		$this->remote_db->delete(array('id' => $id));
		showmessage('提交成功',HTTP_REFERER);
	}
	//生成conf键值文件
	public function createremote(){
		$id = trim($_GET['Id']);
		$this->remote_db = pc_base::load_model('cms_remote_model');//遥控器信息表
		$remote = $this->remote_db->get_one(array('id'=>$id));
		$contetdec = $remote['contetdec'];
		$type = $remote['type'];
		$spid = $remote['spid'];
		$xmlurl = '../go3ccms/xml/conf/remote_'.$type.'_'.$spid.'.conf';
		$fp=fopen("$xmlurl","w");
		fwrite($fp,$contetdec);
		@fclose($fp);
		chmod("$xmlurl",0777);
		//get remote conf file md5
		$remote_md5 = md5_file($xmlurl);
		//更新生成的键值文件路径
		$this->remote_db->update(array('key_url' => $xmlurl,'remote_md5'=>$remote_md5),array('id'=>$id));
		//记录生成conf文件日志
		$this->cmslog_db = pc_base::load_model('cms_cmslog_model');
		$ip = ip();
		$log = array(
				'vid'      => $type,
				'type'      => 'remote_key',
				'userid'      => $_SESSION['userid'],
				'username'   => $_SESSION['userid'],
				'ip'   => $ip,
				'version'   => '',
				'url'   => $remote['siurl'],
				'ad_belong'   => '1',
				'term'   => '1',
				'fid'   => '2',
				'createtime'   => time()
		);
		$this->cmslog_db->insert($log);
		showmessage('提交成功',HTTP_REFERER);
	}
	//除管理员以外的用户上线开机
	public function advertonline(){
		$term_id = trim($_GET['term_id']);
		$adId = trim($_GET['adId']);
		$this->adverts_db->update(array('adStatus'=>100),array('adId' => $adId,'term_id' => $term_id));
		showmessage('提交成功',HTTP_REFERER);
	}
}
