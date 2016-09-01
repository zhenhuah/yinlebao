<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class client extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		$this->server_list = pc_base::load_model('cms_server_list_model');
		$this->auth_info = pc_base::load_model('usercenter_auth_info_model');
		$this->client_upgrade = pc_base::load_model('cms_client_upgrade_model');
		$this->auth_group = pc_base::load_model('cms_auth_group_model');
		$this->auth_device = pc_base::load_model('cms_auth_device_model');
		$this->upgrade_gray = pc_base::load_model('upgrade_gray_model');
		$this->client_menu = pc_base::load_model('client_menu_model');
		$this->client_menu_diy = pc_base::load_model('client_menu_diy_model');
		$this->upgrade_boot = pc_base::load_model('upgrade_boot_model');
		$this->upgrade_advert = pc_base::load_model('upgrade_advert_model');
		$this->customer_audio = pc_base::load_model('customer_audio_model');
		$this->ktv_servers = pc_base::load_model('usercenter_ktv_servers_model');
	}   	
	
	public function init() {
		
	}
	
	//腾讯升级列表
	public function tencent_upgrade(){
		$admin_username = param::get_cookie('admin_username');
		$type = $_GET['upgrade_type'];
		$status = isset($_GET['status']) ? $_GET['status'] : 1;
		//$typewhere = "(upgrade_type = 'APK' || upgrade_type = 'FIRMWARE' || upgrade_type = 'MIDWARE')";
		$where = " WHERE cid = 'TENCENT' and u_status = '$status'";
		if (!empty($type))
			$where .= " and upgrade_type = '$type'";
		$field    	= '*';
		$sql     	= "v9_client_upgrade ".$where;
		$order  	= ' ORDER BY upgrade_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->client_upgrade->mynum($sql);
		$totalpage	= $this->client_upgrade->mytotalpage($sql, $perpage);
		$data 		= $this->client_upgrade->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->client_upgrade->pages;
		include $this->admin_tpl('tencent_upgrade');
	}
	
	//添加腾讯版本升级
	public function add_upgrade_tencent() {
		include $this->admin_tpl('upgrade_add_tencent');
	}
	
	public function add_upgrade_tencent_do() {
		//var_dump($_POST);die();
		$upgrade_type = $_POST['upgrade_type'];
		$force = $_POST['force'];
		$status = 1;
		$url = trim($_POST['url']);
		//if (!$this->check_remote_file_exists($url))
		//	showmessage('填写的文件不存在或服务器超时',HTTP_REFERER,2000);
		//$zip_size = strlen(file_get_contents($url));
		//die($zip_size);
		$size = $_POST['size'];
		$zip_size = $size;
		//if ($size < $zip_size)
		//	showmessage('解压后的文件大小必须不小于上传的包文件大小');
		//$filemd5 = md5_file($url);
		$filemd5 = trim($_POST['filemd5']);
		$versioncode = trim($_POST['versioncode']);
		$version = trim($_POST['version']);
		$description = $_POST['description'];
		$upgrade_time = date('Y-m-d H:i:s');
		$data = array(
			'upgrade_type' => $upgrade_type,
			'u_status' => $status,	//2:新添加 待审核状态 0:pad / phone
			'is_gray' => 0,
			'is_force' => $force,
			'url' => $url,
			'size' => $size,
			'zip_size' => $zip_size,
			'filemd5' => $filemd5,
			'versioncode' => $versioncode,
			'version' => $version,
			'description' => $description,
			'upgrade_time' => $upgrade_time,
			'cid' => 'TENCENT'
		);
		//把之前的版本设置为历史版本
		$this->client_upgrade->update(array('u_status'=>0), array('upgrade_type'=>$upgrade_type, 'cid'=>'TENCENT', 'u_status'=>1));
		//全部升级
		$this->client_upgrade->insert($data);
		showmessage('操作成功','index.php?m=go3c&c=client&a=tencent_upgrade');
	}
	
	public function doajaxfileuploadtencent() {
		//'http://www.go3c.tv:8060/download/android/stb/'.$t.'/ktv/' . $apk['name'];
		//var_dump($_GET['type']);die();
		$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/download/tencent/';
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
			$upgradepath = 'http://www.go3c.tv:8060/download/tencent/' . $_FILES[$fileElementName]['name'];
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
	
	//开机列表
	public function upgrade_boot(){
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->upgrade_boot->select($where = 'b_status = 1', $data = 'b_cid', $limit = '', $order = 'b_time DESC', $group = ' b_cid', $key='');
		}else{
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$spid_list = $this->upgrade_boot->select($where = 'b_status = 1 and b_cid in ('.$sqlin.')', $data = 'b_cid', $limit = '', $order = 'b_time DESC', $group = ' b_cid', $key='');
		}	
		$spid = trim($_GET['spid']);
		$aKey = ' b_status = 1 ';
		if(!empty($spid)){
			$aKey .= "AND b_cid = '".$spid."'";
		} else if ($_SESSION['roleid']!='1') {
			$aKey .= "AND b_cid in (".$sqlin.")";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$data  = $this->upgrade_boot->listinfo($aKey, $order = 'b_time DESC', $page, $pagesize = 15);
		$pages = $this->upgrade_boot->pages;
		include $this->admin_tpl('upgrade_boot');
	}
	
	public function upgradeBootAdd() {
		if (isset($_POST['dosubmit'])) {
			//var_dump($_FILES);die();
			$cids = explode(',', $_POST['cidStr']);
			$bootuploadpath = $_SERVER['DOCUMENT_ROOT'] . '/images/go3ccms/uploadfile/boot/';
			$bootuploadpathabs = 'http://www.go3c.tv:8060/images/go3ccms/uploadfile/boot/';
			foreach ($cids as $v) {
				if ($_POST['bootType'] == 'BOOTANIMATION') {
					//set former status = 0
					$this->upgrade_boot->update(array('b_status' => 0), array('b_cid' => $v, 'b_type' => $_POST['bootType']));
					$data = array(
					'b_time' => date('Y-m-d H:i:s'),
					'b_status' => 1,
					'b_type' => $_POST['bootType'],
					'b_cid' => $v,
					'b_url' => $_POST['ad_imgUrl']
					);
					$this->upgrade_boot->insert($data);
				} else if ($_POST['bootType'] == 'APP_BACKGROUND_IMGS') {
					//set former status = 0
					$this->upgrade_boot->update(array('b_status' => 0), array('b_cid' => $v, 'b_type' => $_POST['bootType']));
					if ($_FILES['file_app_animation']['size']) {
						$file1 = $_FILES['file_app_animation'];
						if (!$file1['error'] && $file1['size']) {
							move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_animation_'.time().'.jpg');
						}
						$url1 = $bootuploadpathabs . 'app_animation_'.time().'.jpg';
					}
					if ($_FILES['file_app_restart_animation']['size']) {
						$file1 = $_FILES['file_app_restart_animation'];
						if (!$file1['error'] && $file1['size']) {
							move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_restart_animation_'.time().'.jpg');
						}
						$url2 = $bootuploadpathabs . 'app_restart_animation_'.time().'.jpg';
					}
					if ($_FILES['file_app_return_animation']['size']) {
						$file1 = $_FILES['file_app_return_animation'];
						if (!$file1['error'] && $file1['size']) {
							move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_return_animation_'.time().'.jpg');
						}
						$url3 = $bootuploadpathabs . 'app_return_animation_'.time().'.jpg';
					}
					if ($_FILES['file_app_pause_animation']['size']) {
						$file1 = $_FILES['file_app_pause_animation'];
						if (!$file1['error'] && $file1['size']) {
							move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'app_pause_animation_'.time().'.jpg');
						}
						$url4 = $bootuploadpathabs . 'app_pause_animation_'.time().'.jpg';
					}
					if ($url1) {
						$arr = array($url1,$url2,$url3,$url4);
						foreach ($arr as $k => $img) {
							$data = array(
								'b_time' => date('Y-m-d H:i:s'),
								'b_status' => 1,
								'b_type' => $_POST['bootType'],
								'b_bg_type' => $k,
								'b_cid' => $v,
								'b_url' => $img
							);
							$this->upgrade_boot->insert($data);
						}
					}
				} else {
					$index = $_POST['appWizardIndex'];
					for ($i = 0; $i <= $index; $i++) {
						if ($_FILES['file_app_wizard_' . $i]['size']) {
							$file = $_FILES['file_app_wizard_' . $i];
							if (!$file['error'] && $file['size']) {
								move_uploaded_file($file['tmp_name'], $bootuploadpath . 'app_wizard_' . $i . '_' . time().'.jpg');
							}
							$url = $bootuploadpathabs . 'app_wizard_' . $i . '_' . time().'.jpg';
							$data = array(
								'b_time' => date('Y-m-d H:i:s'),
								'b_status' => 1,
								'b_type' => $_POST['bootType'],
								'b_cid' => $v,
								'b_url' => $url
							);
							$this->upgrade_boot->insert($data);
						}
					}
				}
			}
			showmessage('操作成功','?m=go3c&c=client&a=upgrade_boot');
		} else 
			include $this->admin_tpl('upgrade_boot_add');
	}
	
	public function upgradeBootEdit() {
		if (isset($_POST['dosubmit'])) {
			$id = $_POST['id'];
			$url = $_POST['ad_imgUrl'];
			$this->upgrade_boot->update(array('b_url' => $url), array('b_id' => $id));
			showmessage('操作成功','?m=go3c&c=client&a=upgrade_boot');
		} else {
			$id = $_GET['id'];
			$data = $this->upgrade_boot->select("b_id = '$id'");
			$data = $data[0];
			include $this->admin_tpl('upgrade_boot_edit');
		}
	}
	
	public function upgradeBootDelete() {
		$id = $_GET['id'];
		$this->upgrade_boot->delete(array('b_id' => $id));
		showmessage('操作成功','?m=go3c&c=client&a=upgrade_boot');
	}
	
	//打包升级包
	public function upgradeBootGetZip() {
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
			$ad = $this->upgrade_boot->select("b_id = '$id'","*");
			$genus = $ad[0]['b_type'];
			$img = $ad[0]['b_url'];
			if ($genus == 'APP_BACKGROUND_IMGS') {
				$bgtype = $ad[0]['b_bg_type']; 
				$pathbg = $path . '/app_animationnew';
				if(!file_exists($pathbg))
					mkdir($pathbg, 0777);
				if ($bgtype == 0)
					copy($img, $pathbg . '/app_animation.jpg');
				else if ($bgtype == 1)
					copy($img, $pathbg . '/app_restart_animation.jpg');
				else if ($bgtype == 2)
					copy($img, $pathbg . '/app_return_animation.jpg');
				else if ($bgtype == 3)
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
		showmessage($msg,'?m=go3c&c=client&a=upgrade_boot&zipurl=' . $desurl);
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
	
	//广告列表
	public function upgrade_advert(){
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->upgrade_advert->select($where = 'a_status = 1', $data = 'a_cid', $limit = '', $order = 'a_time DESC', $group = ' a_cid', $key='');
		}else{
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$spid_list = $this->upgrade_advert->select($where = 'a_status = 1 and a_cid in ('.$sqlin.')', $data = 'a_cid', $limit = '', $order = 'a_time DESC', $group = ' a_cid', $key='');
		}	
		$spid = trim($_GET['spid']);
		$aKey = ' a_status = 1 ';
		if(!empty($spid)){
			$aKey .= "AND a_cid = '".$spid."'";
		} else if ($_SESSION['roleid']!='1') {
			$aKey .= "AND a_cid in (".$sqlin.")";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$data  = $this->upgrade_advert->listinfo($aKey, $order = 'a_time DESC', $page, $pagesize = 15);
		$pages = $this->upgrade_advert->pages;
		include $this->admin_tpl('upgrade_advert');
	}
	
	public function upgradeAdvertAdd() {
		if (isset($_POST['dosubmit'])) {
			//var_dump($_POST);die();
			$cids = explode(',', $_POST['cidStr']);
			$bootuploadpath = $_SERVER['DOCUMENT_ROOT'] . '/images/go3ccms/uploadfile/boot/';
			$bootuploadpathabs = 'http://www.go3c.tv:8060/images/go3ccms/uploadfile/boot/';
			$url = '';
			if ($_FILES['advertImg']['size']) {
				$file1 = $_FILES['advertImg'];
				if (!$file1['error'] && $file1['size']) {
					$flag = move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'ad_'.time().'.jpg');
					if ($flag)
						$url = $bootuploadpathabs . 'ad_'.time().'.jpg';
					else
						showmessage('图片上传失败, 请重试','?m=go3c&c=client&a=upgrade_advert');
				}
			}
			foreach ($cids as $v) {
				$data = array(
				'a_time' => date('Y-m-d H:i:s'),
				'a_status' => 1,
				'a_cid' => $v,
				'a_type' => $_POST['advertType'],
				'a_list_mode' => 'order',
				'a_click_mode' => $_POST['advertClickMode'],
				'a_content' => $_POST['advertContent'],
				'a_img' => $url,
				'a_btn_txt' => $_POST['advertBtn'],
				'a_color' => $_POST['advertColor'],
				'a_show_time' => $_POST['advertShowTime'],
				'a_speed' => $_POST['advertSpeed']
				);
				if ($_POST['advertClickMode'] == '0')
					$data['a_link'] = $_POST['advertLink'];
				else {
					$data['a_app_name'] = $_POST['advertAppName'];
					$data['a_app_package'] = $_POST['advertAppPackage'];
					$data['a_app_url'] = $_POST['advertAppUrl'];
				}
				$this->upgrade_advert->insert($data);
			}
			showmessage('操作成功','?m=go3c&c=client&a=upgrade_advert');
		} else 
			include $this->admin_tpl('upgrade_advert_add');
	}
	
	public function upgradeAdvertEdit() {
		if (isset($_POST['dosubmit'])) {
			$id = $_POST['id'];
			$data = array(
				'a_time' => date('Y-m-d H:i:s'),
				'a_click_mode' => $_POST['advertClickMode'],
				'a_content' => $_POST['advertContent'],
				'a_btn_txt' => $_POST['advertBtn'],
				'a_color' => $_POST['advertColor'],
				'a_show_time' => $_POST['advertShowTime'],
				'a_speed' => $_POST['advertSpeed']
			);
			if ($_POST['advertClickMode'] == '0') {
				$data['a_link'] = $_POST['advertLink'];
				$data['a_app_name'] = '';
				$data['a_app_package'] = '';
				$data['a_app_url'] = '';
			} else {
				$data['a_link'] = '';
				$data['a_app_name'] = $_POST['advertAppName'];
				$data['a_app_package'] = $_POST['advertAppPackage'];
				$data['a_app_url'] = $_POST['advertAppUrl'];
			}
			$bootuploadpath = $_SERVER['DOCUMENT_ROOT'] . '/images/go3ccms/uploadfile/boot/';
			$bootuploadpathabs = 'http://www.go3c.tv:8060/images/go3ccms/uploadfile/boot/';
			$url = '';
			if ($_FILES['advertImg']['size']) {
				$file1 = $_FILES['advertImg'];
				if (!$file1['error'] && $file1['size']) {
					$flag = move_uploaded_file($file1['tmp_name'], $bootuploadpath . 'ad_'.time().'.jpg');
					if ($flag)
						$url = $bootuploadpathabs . 'ad_'.time().'.jpg';
					else
						showmessage('图片上传失败, 请重试','?m=go3c&c=client&a=upgrade_advert');
				}
			}
			if ($url)
				$data['a_img'] = $url;
			$this->upgrade_advert->update($data, array('a_id' => $id));
			showmessage('操作成功','?m=go3c&c=client&a=upgrade_advert');
		} else {
			$id = $_GET['id'];
			$data = $this->upgrade_advert->select("a_id = '$id'");
			$data = $data[0];
			include $this->admin_tpl('upgrade_advert_edit');
		}
	}

	public function upgradeAdvertDelete() {
		$id = $_GET['id'];
		$this->upgrade_advert->delete(array('a_id' => $id));
		showmessage('操作成功','?m=go3c&c=client&a=upgrade_advert');
	}
	
	//生成firmware config文件并下载
	public function generateConfig(){
		$ids=explode(',', $_GET['cid']);
		$list = array();
		foreach ($ids as $v){
			$upgrade = $this->client_upgrade->select("id = '$v'","*");
			$upgrade = $upgrade[0];
			$data = array(
			'cid' => $upgrade['term_type'],
			'ctype' => $upgrade['firmware_config'],
			'versioncode' => $upgrade['versioncode'],
			'name' => basename($upgrade['url'])
			);
			$list[] = $data;
		}
		$php_json = json_encode($list);
		$xmlurl = '/home/wwwroot/default/download/KTV_Data/firmwarelist.json';
		chmod("$xmlurl",0777);
		$fp=fopen("$xmlurl","w");
		fwrite($fp,$php_json);
		@fclose($fp);
		$url = str_replace('/home/wwwroot/default/', 'http://www.go3c.tv:8060/', $xmlurl);
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=client&a=upan_list&configurl=' . $url);
	}
	
	//U盘升级列表
	public function upan_list(){
		$admin_username = param::get_cookie('admin_username');
		$config   	= trim($_GET['config']);
		$term_type   	= trim($_GET['term_type']);
		$status = isset($_GET['status']) ? $_GET['status'] : 1;
		$where = " WHERE upgrade_type = 'FIRMWARE' AND u_status = '$status'";
		if(!empty($config)){
				$where .= " and firmware_config = '$config'";
			}
		if(!empty($term_type)){
			$where .= " and term_type = '$term_type'";
		}
		$where .= " group by url ";
		$field    	= '*';
		$sql     	= "v9_client_upgrade ".$where;
		$order  	= ' ORDER BY upgrade_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->client_upgrade->mynum($sql);
		$totalpage	= $this->client_upgrade->mytotalpage($sql, $perpage);
		$data 		= $this->client_upgrade->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->client_upgrade->pages;
		
		$order = ' ORDER BY cid DESC';
		//获取客户id
		$fd  = '*';
		$wh = "test_customer_new WHERE 1 group by ID ";
		$ainfo = $this->auth_info->mylistinfo($fd, $wh, $order, $page, $perpage);
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('upan_list');
	}
	
	public function  check_remote_file_exists($url) {
        $curl= curl_init($url);   
        curl_setopt($curl, CURLOPT_NOBODY, true);// 不取回数据  
        curl_setopt($curl, CURLOPT_TIMEOUT,60);
        $result= curl_exec($curl);   // 发送请求     
        $found= false;    
        // 如果请求没有发送失败     
        if($result!== false) {         
            // 再检查http响应码是否为200         
            $statusCode= curl_getinfo($curl, CURLINFO_HTTP_CODE);           
            if($statusCode== 200) { $found= true; }
        }
        curl_close($curl);
        return  $found;
    }
	
	public function menu_edit_do() {
		$menu = explode(',', $_GET['menuStr']);
		//delete previous menu by cid
		$this->client_menu_diy->delete(array('d_cid'=>$_GET['cid']));
		foreach ($menu as $v) {
			$res = $this->client_menu->select("m_key = '$v'","m_id");
			$data = array(
			'd_cid' => $_GET['cid'],
			'd_mid' => $res[0]['m_id'],
			'd_time' => date('Y-m-d H:i:s')
			);
			$this->client_menu_diy->insert($data);
		}
		showmessage('操作成功','index.php?m=go3c&c=client&a=menu_list');
	}
	
	public function menu_edit() {
		$cid = $_GET['cid'];
		$menuArr = $this->getMenuByCid($cid);
		$menuAll = $this->client_menu->select();
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
		include $this->admin_tpl('menu_edit');
	}

	//菜单项列表
	public function menu_list(){
		$admin_username = param::get_cookie('admin_username');
		$cid = trim($_GET['cid']);
		//获取项目代号
		$fd  = 'ID';
		if ($_SESSION['roleid']=='1') {
			$wh = ' 1 ';
		} else {
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$wh = " ID in (" . $sqlin . ")";
		}
		$wh .= " group by ID ";
		$ainfo = $this->auth_info->select($wh,$fd);
		if(!empty($cid)){
			$menuArr[$cid] = $this->getMenuByCid($cid);
		} else {
			foreach ($ainfo as $v) {
				$menuArr[$v[ID]] = $this->getMenuByCid($v['ID']);
			}
		}
		include $this->admin_tpl('menu_list');
	}
	
	//音频列表
	public function audio_list() {
		$admin_username = param::get_cookie('admin_username');
		$cid = trim($_GET['cid']);
		//获取项目代号
		$fd  = 'ID';
		if ($_SESSION['roleid']=='1') {
			$wh = ' 1 ';
		} else {
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
			$wh = " ID in (" . $sqlin . ")";
		}
		$wh .= " group by ID ";
		$ainfo = $this->auth_info->select($wh,$fd);
		if(!empty($cid)){
			$menuArr[$cid] = $this->getAudioByCid($cid);
		} else {
			foreach ($ainfo as $v) {
				$menuArr[$v[ID]] = $this->getAudioByCid($v['ID']);
			}
		}
		include $this->admin_tpl('audio_list');
	}
	
	public function audio_edit() {
		$cid = $_GET['cid'];
		$audio = $this->getAudioByCid($cid);
		include $this->admin_tpl('audio_edit');
	}
	
	public function audio_edit_do() {
		$cid = $_POST['cid'];
		$audio = $_POST['audio'];
		$data = $this->customer_audio->select("a_cid = '$cid'", "a_audio");
		if (count($data))
			$this->customer_audio->update(array("a_audio" => $audio), array("a_cid" => $cid));
		else {
			$d = array(
				'a_cid' => $cid,
				'a_audio' => $audio
			);
			$this->customer_audio->insert($d);
		}
		showmessage('操作成功','index.php?m=go3c&c=client&a=audio_list');
	}
	
	private function getMenuByCid($id) {
		$field    	= 'd.*,m.*';
		$order = ' ';
		//$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$perpage = 10000;
		$where = " where d.d_cid = '$id'";
		$sql = "client_menu_diy as d left join client_menu as m on d.d_mid = m.m_id" . $where;
		//$totalnum	= $this->client_menu_diy->mynum($sql);
		//$totalpage	= $this->client_menu_diy->mytotalpage($sql, $perpage);
		//echo $totalpage;
		$data 		= $this->client_menu_diy->mylistinfo($field, $sql, $order, $page, $perpage);
		//$multipage  = $this->client_menu_diy->pages;
		$m = array();
		foreach ($data as $d) {
			$m[$d[m_key]] = $d[m_name_zh];
		}
		return $m;
	}

	private function getAudioByCid($id) {
		$field    	= '*';
		$order = ' ';
		//$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$perpage = 10000;
		$where = " where a_cid = '$id'";
		$sql = "customer_audio" . $where;
		$data 		= $this->customer_audio->mylistinfo($field, $sql, $order, $page, $perpage);
		//$multipage  = $this->client_menu_diy->pages;
		if (count($data))
			return $data[0]['a_audio'];
		else 
			return '';
	}
	
	public function checkDependFw() {
		$versioncode = $_GET['v'];
		$term = $_GET['t'];
		$res = $this->client_upgrade->select("upgrade_type = 'FIRMWARE' AND versioncode = '$versioncode' AND term_type = '$term'","cid");
		$data = array();
		foreach ($res as $v) {
			array_push($data, $v['cid']);
		}
		echo json_encode($data);
	}
	
	public function checkDependSongdb() {
		$versioncode = $_GET['v'];
		$term = $_GET['t'];
		$res = $this->client_upgrade->select("upgrade_type = 'SONG_DB' AND versioncode = '$versioncode' AND term_type = '$term'","cid");
		$data = array();
		foreach ($res as $v) {
			array_push($data, $v['cid']);
		}
		echo json_encode($data);
	}
	
	public function upgradeApprove(){
		$id = $_GET['id'];
		$type = $_GET['type'];
		$term = $_GET['term'];
		$cid = $_GET['cid'];
		$fwconfig = $_GET['fwconfig'];
		$this->client_upgrade->update(array('u_status'=>0),array('upgrade_type'=>$type,'u_status'=>1,'term_type'=>$term,'cid'=>$cid,'firmware_config'=>$fwconfig));
		$this->client_upgrade->update(array('u_status'=>1),array('id'=>$id));
		showmessage('操作成功','index.php?m=go3c&c=client&a=upgrade_verify');
	}
	
	public function upgradeRefuse(){
		$id = $_GET['id'];
		$this->client_upgrade->update(array('u_status'=>-1),array('id'=>$id));
		showmessage('操作成功','index.php?m=go3c&c=client&a=upgrade_verify');
	}
	
	//批量通过审核版本
	public function upgradeApproveMulti(){
		$ids=explode(',', $_GET['cid']);
		foreach ($ids as $v){
			$upgrade = $this->client_upgrade->select("id = $v","upgrade_type,term_type,cid,firmware_config");
			$type = $upgrade[0]['upgrade_type'];
			$term = $upgrade[0]['term_type'];
			$cid = $upgrade[0]['cid'];
			$fwconfig = $upgrade[0]['firmware_config'];
			//echo $type.'<br>';
			$this->client_upgrade->update(array('u_status'=>0),array('upgrade_type'=>$type,'term_type'=>$term,'cid'=>$cid,'u_status'=>1,'firmware_config'=>$fwconfig));
			$this->client_upgrade->update(array('u_status'=>1),array('id'=>$v));
		}
		showmessage('操作成功','index.php?m=go3c&c=client&a=upgrade_verify');
	}
	
	//批量拒绝审核版本
	public function upgradeRefuseMulti(){
		$ids=explode(',', $_GET['cid']);
		foreach ($ids as $v){
			$this->client_upgrade->update(array('u_status'=>-1),array('id'=>$v));
		}
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=client&a=upgrade_verify');
	}
	
	//待审核列表
	public function upgrade_verify(){
		$admin_username = param::get_cookie('admin_username');
		$ID   	= trim($_GET['ID']);
		$term_type   	= trim($_GET['term_type']);
		$type = $_GET['upgrade_type'];
		$where = " WHERE u_status = 2";
		if ($_SESSION['roleid']=='19') {
			$where .= " and term_type = 'A20'";
		} else if ($_SESSION['roleid']=='21') {
			$where .= " and term_type = 'MX8726'";
		}
		/*if($_SESSION['roleid']=='1'){	//超级管理员能看到所有数据
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}else{
			$where .= " and ID in ('".$_SESSION['spid']."')";
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}*/
		if(!empty($ID)){
			$where .= " and cid LIKE '%$ID%'";
		}
		if(!empty($term_type)){
			$where .= " and term_type ='$term_type'";
		}
		if (!empty($type))
			$where .= " and upgrade_type = '$type'";
		$field    	= '*';
		$sql     	= "v9_client_upgrade ".$where;
		$order  	= ' ORDER BY upgrade_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->client_upgrade->mynum($sql);
		$totalpage	= $this->client_upgrade->mytotalpage($sql, $perpage);
		$data 		= $this->client_upgrade->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->client_upgrade->pages;
		
		$order = ' ORDER BY cid DESC';
		//获取客户id
		$fd  = '*';
		$wh = "test_customer_new WHERE 1 group by ID ";
		$ainfo = $this->auth_info->mylistinfo($fd, $wh, $order, $page, $perpage);
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('upgrade_verify');
	}
	
	//升级列表
	public function upgrade_list(){
		$admin_username = param::get_cookie('admin_username');
		$ID   	= trim($_GET['ID']);
		$term_type   	= trim($_GET['term_type']);
		$type = $_GET['upgrade_type'];
		$status = isset($_GET['status']) ? $_GET['status'] : 1;
		$typewhere = "(upgrade_type = 'APK' || upgrade_type = 'FIRMWARE' || upgrade_type = 'SONG_DB' || upgrade_type = 'WEB_PHONE')";
		if ($_SESSION['upgrade_role'] != 'ALL' && $_SESSION['upgrade_role'] != 'NONE')
			$typewhere = "upgrade_type = '$_SESSION[upgrade_role]'";
		$where = " WHERE ".$typewhere." and u_status = '$status'";
		/*if($_SESSION['roleid']=='1'){	//超级管理员能看到所有数据
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}else{
			$where .= " and ID in ('".$_SESSION['spid']."')";
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}*/
		if(!empty($ID)){
				$where .= " and cid LIKE '%$ID%'";
			}
		if(!empty($term_type)){
			$where .= " and term_type ='$term_type'";
		}
		if (!empty($type))
			$where .= " and upgrade_type = '$type'";
		$field    	= '*';
		$sql     	= "v9_client_upgrade ".$where;
		$order  	= ' ORDER BY upgrade_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->client_upgrade->mynum($sql);
		$totalpage	= $this->client_upgrade->mytotalpage($sql, $perpage);
		$data 		= $this->client_upgrade->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->client_upgrade->pages;
		
		$order = ' ORDER BY cid DESC';
		//获取客户id
		$fd  = '*';
		$wh = "test_customer_new WHERE 1 group by ID ";
		$ainfo = $this->auth_info->mylistinfo($fd, $wh, $order, $page, $perpage);
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('upgrade_list');
	}
	
	//运营数据列表
	public function operation_list(){
		$admin_username = param::get_cookie('admin_username');
		$ID   	= trim($_GET['ID']);
		$term_type   	= trim($_GET['term_type']);
		$type = $_GET['upgrade_type'];
		$status = isset($_GET['status']) ? $_GET['status'] : 1;
		$typewhere = "(upgrade_type = 'APP_BOOT' || upgrade_type = 'SONG_HOT' || upgrade_type = 'APP_HOTKEY')";
		if ($_SESSION['upgrade_role'] != 'ALL' && $_SESSION['upgrade_role'] != 'NONE' && $_SESSION['upgrade_role'] != 'SONG_DB')
			$typewhere = "upgrade_type = '$_SESSION[upgrade_role]'";
		$where = " WHERE ".$typewhere." and u_status = '$status'";
		/*if($_SESSION['roleid']=='1'){	//超级管理员能看到所有数据
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}else{
			$where .= " and ID in ('".$_SESSION['spid']."')";
			if(!empty($ID)){
				$where .= " and ID LIKE '%$ID%'";
			}
		}*/
		if(!empty($ID)){
				$where .= " and cid LIKE '%$ID%'";
			}
		if(!empty($term_type)){
			$where .= " and term_type ='$term_type'";
		}
		if (!empty($type))
			$where .= " and upgrade_type = '$type'";
		$field    	= '*';
		$sql     	= "v9_client_upgrade ".$where;
		$order  	= ' ORDER BY upgrade_time DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->client_upgrade->mynum($sql);
		$totalpage	= $this->client_upgrade->mytotalpage($sql, $perpage);
		$data 		= $this->client_upgrade->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->client_upgrade->pages;
		
		$order = ' ORDER BY cid DESC';
		//获取客户id
		$fd  = '*';
		$wh = "test_customer_new WHERE 1 group by ID ";
		$ainfo = $this->auth_info->mylistinfo($fd, $wh, $order, $page, $perpage);
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('operation_list');
	}
	
	//添加版本升级
	public function add_upgrade() {
		//获取客户id
		$fd  = '*';
		$wh = "test_customer_new WHERE 1 group by ID ";
		$ainfo = $this->auth_info->mylistinfo($fd, $wh);
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht);
		//$agroup = $this->get_group($aterm[0]['term_type'], $ainfo[0]['ID']);
		$fd = '*';
		$wh = "g_term = '".$aterm[0][term_type]."' and g_cid = '".$ainfo[0][ID]."'";
		$agroup = $this->auth_group->select($wh, $fd);
		include $this->admin_tpl('upgrade_add');
	}
	
	public function get_group() {
		$fd = '*';
		$wh = "g_term = '$_GET[term]' and g_cid = '$_GET[cid]'";
		$agroup = $this->auth_group->select($wh, $fd);
		$agd = array();
		foreach ($agroup as $v) {
			$v['devices'] = $this->getdevice($v['g_id']);
			array_push($agd, $v);
		}
		echo json_encode($agd);
	}

	public function getdevice($gid) {
		$fd = '*';
		$wh = "d_group = '$gid'";
		$adevice = $this->auth_device->select($wh, $fd);
		//$adevice = json_encode($agroup);
		return $adevice;
	}
	
	public function add_upgrade_do() {
		//var_dump($_POST);die();
		$upgrade_type = $_POST['upgrade_type'];
		$force = $_POST['force'];
		$firmware_name = trim($_POST['firmware_name']);
		$status = 2;
		if ($_POST['gray'] == '0') {
			if (isset($_POST['ipad'])) {
				$upgrade_type = 'ipad';
				$status = 0;
			} else if (isset($_POST['iphone'])) {
				$upgrade_type = 'iphone';
				$status = 0;
			} else if (isset($_POST['apad'])) {
				$upgrade_type = 'apad';
				$status = 0;
			} else if (isset($_POST['aphone'])) {
				$upgrade_type = 'aphone';
				$status = 0;
			}
		} else {
			$t = $_POST['gray-term-type'];
			if ($t == 'ipad' || $t == 'iphone' || $t == 'apad' || $t == 'aphone') {
				$upgrade_type = $t;
				$status = 0;
			}
		}
		if ($upgrade_type == 'FIRMWARE')
			$firmware_config = $_POST['firmware_config'];
		else 
			$firmware_config = '';
		$url = trim($_POST['url']);
		if (!$this->check_remote_file_exists($url))
			showmessage('填写的文件不存在或服务器超时',HTTP_REFERER,2000);
		if ($_POST['zip_size']){
			$zip_size = $_POST['zip_size'];
			$isupload = true;
		}else {
			$zip_size = strlen(file_get_contents($url));
			$isupload = false;
		}
		$size = $_POST['size'];
		if ($size < $zip_size)
			showmessage('解压后的文件大小必须大于上传的包文件大小');
		$filemd5 = md5_file($url);
		if ($upgrade_type == 'SONG_DB' || $upgrade_type == 'SONG_HOT' || $upgrade_type == 'APP_HOTKEY' || $upgrade_type == 'APP_BOOT') {
			//这些类型的升级版本 从服务器xml文件读取
			if ($upgrade_type == 'SONG_DB')
				$xml = 'http://www.go3c.tv:8060/go3ccms/xml/database/database/basicnew/version.xml';
			else if ($upgrade_type == 'SONG_HOT')
				$xml = 'http://www.go3c.tv:8060/go3ccms/xml/ktvdatabase/database/latestnew/version.xml';
			else if ($upgrade_type == 'APP_HOTKEY')
				$xml = 'http://www.go3c.tv:8060/upgrade/android/stb/GO3CKTVTEST/A20/Recommon_Data/cache/datanew/version.xml';
			else if ($upgrade_type == 'APP_BOOT')
				$xml = 'http://www.go3c.tv:8060/go3ccms/xml/appassets/app_animationnew/version.xml';
			$a = simplexml_load_file($xml);
			$versioncode = $a->versioncode;
			//仅供测试升级
			//$versioncode = '2014091815';
		} else {
			$versioncode = trim($_POST['versioncode']);
		}
		$version = trim($_POST['version']);
		$description = $_POST['description'];
		$upgrade_time = date('Y-m-d H:i:s');
		if ($upgrade_type == 'APK') {
			$depend_firmware = trim($_POST['depend_firmware_versioncode']);
			$depend_song_db = trim($_POST['depend_song_db_versioncode']);
			$depend_web_phone = trim($_POST['depend_web_phone_versioncode']);
		} else if ($upgrade_type == 'SONG_DB' || $upgrade_type == 'FIRMWARE' || $upgrade_type == 'WEB_PHONE') {
			$depend_apk = trim($_POST['depend_apk_versioncode']);
		} else {
			$depend_firmware = '';
			$depend_song_db = '';
			$depend_web_phone = '';
			$depend_apk = '';
		}
		
		$data = array(
			'upgrade_type' => $upgrade_type,
			'u_status' => $status,	//2:新添加 待审核状态 0:pad / phone
			'is_gray' => 0,
			'firmware_name' => $firmware_name,
			'firmware_config' => $firmware_config,
			'is_force' => $force,
			'url' => $url,
			'size' => $size,
			'zip_size' => $zip_size,
			'filemd5' => $filemd5,
			'versioncode' => $versioncode,
			'version' => $version,
			'description' => $description,
			'upgrade_time' => $upgrade_time,
			'depend_firmware' => $depend_firmware,
			'depend_song_db' => $depend_song_db,
			'depend_web_phone' => $depend_web_phone,
			'depend_apk' => $depend_apk
		);
		//把之前的版本设置为历史版本
		/*if ($upgrade_type == 'FIRMWARE')
			$this->client_upgrade->update(array('u_status'=>0), array('upgrade_type'=>$upgrade_type,'firmware_config'=>$firmware_config));
		else
			$this->client_upgrade->update(array('u_status'=>0), array('upgrade_type'=>$upgrade_type));*/
		if ($_POST['gray'] == '0') {
			//全部升级
			$term_type = isset($_POST['term_type']) ? $_POST['term_type'] : array();
			$cid = isset($_POST['ID']) ? $_POST['ID'] : array();
			//获取终端分类
			/*$fdt  = '*';
			$wht = "test_customer_new WHERE 1 group by term_type ";
			$aterm = $this->auth_info->mylistinfo($fdt, $wht);
			$alltermarr = array();
			foreach ($aterm as $v) {
				array_push($alltermarr, $v['term_type']);
			}*/
			$alltermarr = array('A20','MX8726','A31S','S805');
			//获取客户id
			$fd  = '*';
			$wh = "test_customer_new WHERE 1 group by ID ";
			$ainfo = $this->auth_info->mylistinfo($fd, $wh);
			$allcidarr = array();
			foreach ($ainfo as $v) {
				array_push($allcidarr, $v['ID']);
			}
			if (isset($_POST['allterm']) && isset($_POST['allid'])) {
				// upgrade to all terms and cids
				//$data['term_type'] = 'ALL_TERMS';
				//$data['cid'] = 'ALL_CIDS';
				//$this->client_upgrade->insert($data);
				$this->insert_several_upgrades($data, $alltermarr, $allcidarr,$isupload);
				//echo 'term: all       cid: all    url: ' . $data['url'] . '<br>';
			} else if (isset($_POST['allterm']) && !isset($_POST['allid'])) {
				// upgrade to all terms
				$this->insert_several_upgrades($data, $alltermarr, $cid,$isupload);
			} else if (!isset($_POST['allterm']) && isset($_POST['allid'])) {
				// upgrade to all cids
				if ($status == 0) {
					//pad phone upgrade
					foreach ($allcidarr as $c) {
						$data['term_type'] = $upgrade_type;
						$data['cid'] = $c;
						$this->client_upgrade->insert($data);
					}
				} else
					$this->insert_several_upgrades($data, $term_type, $allcidarr,$isupload);
			} else {
				// upgrade to some terms and some cids
				if ($status == 0) {
					//pad phone upgrade
					foreach ($cid as $c) {
						$data['term_type'] = $upgrade_type;
						$data['cid'] = $c;
						$this->client_upgrade->insert($data);
					}
				} else
					$this->insert_several_upgrades($data, $term_type, $cid,$isupload);
			}
		} else {
			//灰度升级
			$data['is_gray'] = 1;
			$data['term_type'] = $_POST['gray-term-type'];
			$data['cid'] = $_POST['gray-ID'];
			//var_dump($data);die();
			$this->client_upgrade->insert($data);
			$d = array();
			$d['u_upgrade_id'] = $this->client_upgrade->insert_id();
			$devices = isset($_POST['device']) ? $_POST['device'] : array();
			if (!count($devices)) {
				showmessage('请选择灰度升级的设备','index.php?m=go3c&c=client&a=add_upgrade',2000);
			} else {
				foreach ( $devices as $v) {
					$d['u_guid'] = $v;
					//echo $d['u_guid'] . '<br>';
					$this->upgrade_gray->insert($d);
				}
			}
		}
		if ($upgrade_type == 'APK' || $upgrade_type == 'SONG_DB' || $upgrade_type == 'FIRMWARE' || $upgrade_type == 'WEB_PHONE')
			showmessage('操作成功','index.php?m=go3c&c=client&a=upgrade_list');
		else 
			showmessage('操作成功','index.php?m=go3c&c=client&a=operation_list');
	}
	
	private function insert_several_upgrades($d, $tarr, $carr,$isupload) {
		$urlist=explode('/', $d['url']);
		$urlname=end($urlist);
		foreach ($tarr as $t) {
			foreach ($carr as $c) {
				$d['term_type'] = $t;
				$d['cid'] = $c;
				//根据不同的项目平台生成url
				if($d['upgrade_type']=='APK'||$d['upgrade_type']=='FIRMWARE'||$d['upgrade_type']=='APP_BOOT'){  //平台划分
					$uploaddir = 'http://www.go3c.tv:8060/download/upgrade/ktv/' .$d['upgrade_type'].'/' .$t.'/' ;
					if ($d['upgrade_type'] == 'APP_BOOT'){
						$xml = 'http://www.go3c.tv:8060/go3ccms/xml/appassets/app_animationnew/version.xml';
						$a = simplexml_load_file($xml);
						$versioncode = $a->versioncode;
						$ar = explode('.',$urlname);
						$ar1 = $ar['0'];
						$filename = $ar1.'_'.$versioncode.'.zip';
					}else{
						$filename = $urlname;
					}
					$url = $uploaddir.$filename;
				}else{										//项目划分
					if ($d['upgrade_type'] == 'SONG_DB')
						$xml = 'http://www.go3c.tv:8060/go3ccms/xml/database/database/basicnew/version.xml';
					else if ($d['upgrade_type'] == 'SONG_HOT')
						$xml = 'http://www.go3c.tv:8060/go3ccms/xml/ktvdatabase/database/latestnew/version.xml';
					else if ($d['upgrade_type'] == 'APP_HOTKEY')
						$xml = 'http://www.go3c.tv:8060/upgrade/android/stb/GO3CKTVTEST/A20/Recommon_Data/cache/datanew/version.xml';
					$a = simplexml_load_file($xml);
					$versioncode = $a->versioncode;
					$uploaddir = 'http://www.go3c.tv:8060/download/upgrade/ktv/' .$d['upgrade_type'].'/' .$c.'/' ;
					$ar = explode('.',$urlname);
					$ar1 = $ar['0'];
					$filename = $ar1.'_'.$versioncode.'.zip';
					$url = $uploaddir.$filename;
				}
				if($isupload==true){
					$d['url'] = $url;
				}
				$this->client_upgrade->insert($d);
				//echo 'term: ' . $t . '       cid: ' . $c . '    url: ' . $d['url'] . '<br>';
			}
		}
	}
	
	public function doajaxfileupload() {
		//'http://www.go3c.tv:8060/download/android/stb/'.$t.'/ktv/' . $apk['name'];
		//var_dump($_GET['type']);die();
		$absolute_path	= $_SERVER['DOCUMENT_ROOT'].'/upgrade/phpapi/' . $_GET['type'] . '/';
		$term = $_GET['term'];
		$spid = $_GET['spid'];
		$error = "";
		$msg = "";
		$size = "";
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
			$upgradepath = UPGRADE_PATH . $_GET['type'] . '/' . $_FILES[$fileElementName]['name'];
			$uploadfile = $absolute_path . $_FILES[$fileElementName]['name'];
			if(!move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile ))//上传文件
			{	
				$msg = '0';
				$size = '0';
			}else{
				//$msg = '1';	//表示成功，其他都是上传失败
				$msg = $uploadfile;	//返回路径
				$size = $_FILES[$fileElementName]['size'];
				//get_img_url($msg);	//同步图片
			}
			@unlink($_FILES['fileToUpload']);		
		}	
		//循环检查,获取项目平台信息
		if(!empty($term)){
			$termlist=array_filter(explode(',', $term));   //平台
		}else{
			$term = 'A20,MX8726,A31S';
			$termlist=explode(',', $term);
		}
		if(!empty($spid)){
			$spidlist=array_filter(explode(',', $spid));   //项目
		}else{
			$spidlist = $this->auth_info->select($where = '1', $data = 'ID', $limit = '', $order = 'cid ASC', $group = ' ID', $key='');
		}
		if($_GET['type']=='APK'||$_GET['type']=='FIRMWARE'||$_GET['type']=='APP_BOOT'){  //平台划分
			if ($_GET['type'] == 'APP_BOOT'){
				$xml = 'http://www.go3c.tv:8060/go3ccms/xml/appassets/app_animationnew/version.xml';
				$a = simplexml_load_file($xml);
				$versioncode = $a->versioncode;
			}
			if($_GET['type']=='APP_BOOT'){
				$ar = explode('.',$_FILES[$fileElementName]['name']);
				$ar1 = $ar['0'];
				$filename = $ar1.'_'.$versioncode.'.zip';
			}else{
				$filename = $_FILES[$fileElementName]['name'];
			}
			foreach ($termlist as $v){
				$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/download/upgrade/ktv/' .$_GET['type'].'/' .$v.'/' ;
				if(!file_exists($uploaddir))    //检查文件目录是否存在
				{
					mkdir ($uploaddir,0777);   //创建文件目录
				}
				
				$newfile = $uploaddir.$filename;
				copy($uploadfile, $newfile);
			}
			//文件 重新命名
			$newname = $absolute_path.$filename;
			//rename($uploadfile, $newname);
		}else{
			//这些类型的升级版本 从服务器xml文件读取
			if ($_GET['type'] == 'SONG_DB')
				$xml = 'http://www.go3c.tv:8060/go3ccms/xml/database/database/basicnew/version.xml';
			else if ($_GET['type'] == 'SONG_HOT')
				$xml = 'http://www.go3c.tv:8060/go3ccms/xml/ktvdatabase/database/latestnew/version.xml';
			else if ($_GET['type'] == 'APP_HOTKEY')
				$xml = 'http://www.go3c.tv:8060/upgrade/android/stb/GO3CKTVTEST/A20/Recommon_Data/cache/datanew/version.xml';
			$a = simplexml_load_file($xml);
			$versioncode = $a->versioncode;
			$ar = explode('.',$_FILES[$fileElementName]['name']);
			$ar1 = $ar['0'];
			$filename = $ar1.'_'.$versioncode.'.zip';
			foreach ($spidlist as $v){					//项目划分
				$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/download/upgrade/ktv/' .$_GET['type'].'/' .$v.'/' ;
				if(!file_exists($uploaddir))    //检查文件目录是否存在
				{
					mkdir ($uploaddir,0777);   //创建文件目录
				}
				$newfile = $uploaddir.$filename;
				copy($uploadfile, $newfile);
			}
			//文件 重新命名
			$newname = $absolute_path.$filename;
			//rename($uploadfile, $newname);
		}
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $upgradepath . "',\n";
		echo				"size: '" . $size . "'\n";
		echo "}";
	}
	/**
	 * 删除版本更新信息
	 */
	public function delete_upgrade(){
		$cid = $_GET['cid'];
		$this->client_upgrade->delete(array('id'=>$cid));
		$this->upgrade_gray->delete(array('u_upgrade_id'=>$cid));
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=client&a=upgrade_list');
	}
	
	//批量删除ktv版本更新
	public function delete_upgrade_multi(){
		$ids=explode(',', $_GET['cid']);
		foreach ($ids as $v){
			$this->client_upgrade->delete(array('id'=>$v));
			$this->upgrade_gray->delete(array('u_upgrade_id'=>$v));
		}
		$msg = '操作成功!';
		showmessage($msg,'?m=go3c&c=client&a=upgrade_list');
	}
	
	/**
	 * 编辑版本更新 
	 */
	public function edit_upgrade() {
		$id = $_GET['cid'];
		$where = "id = $id";
		$data = $this->client_upgrade->select($where, '*');
		$data = $data[0];
		//获取客户id
		$fd  = '*';
		$wh = "test_customer_new WHERE 1 group by ID ";
		$ainfo = $this->auth_info->mylistinfo($fd, $wh);
		//获取终端分类
		$fdt  = '*';
		$wht = "test_customer_new WHERE 1 group by term_type ";
		$aterm = $this->auth_info->mylistinfo($fdt, $wht);
		//获取设备组
		$fd = '*';
		$wh = "g_term = '".$aterm[0][term_type]."' and g_cid = '".$ainfo[0][ID]."'";
		$agroup = $this->auth_group->select($wh, $fd);
		include $this->admin_tpl('upgrade_edit');
	}
	
	public function edit_upgrade_do() {
		$uid = $_POST['uid'];
		$force = $_POST['force'];
		$upgrade_type = $_POST['upgrade_type'];
		$version = trim($_POST['version']);
		//$url = trim($_POST['url']);
		//$filemd5 = md5_file($url);
		$upgrade_time = date('Y-m-d H:i:s');
		//$depend_apk = trim($_POST['depend_apk_versioncode']);
		$depend_firmware = trim($_POST['depend_firmware_versioncode']);
		$depend_song_db = trim($_POST['depend_song_db_versioncode']);
		$size = trim($_POST['size']);
		$description = $_POST['description'];
		//$firmware_name = trim($_POST['firmware_name']);
		//if ($upgrade_type == 'FIRMWARE')
		//	$firmware_config = $_POST['firmware_config'];
		//else 
		//	$firmware_config = '';
		if ($upgrade_type == 'APK') {
			$depend_firmware = trim($_POST['depend_firmware_versioncode']);
			$depend_song_db = trim($_POST['depend_song_db_versioncode']);
			$depend_web_phone = trim($_POST['depend_web_phone_versioncode']);
		} else if ($upgrade_type == 'SONG_DB' || $upgrade_type == 'FIRMWARE' || $upgrade_type == 'WEB_PHONE') {
			$depend_apk = trim($_POST['depend_apk_versioncode']);
		} else {
			$depend_firmware = '';
			$depend_song_db = '';
			$depend_web_phone = '';
			$depend_apk = '';
		}
		$newdata = array(
			//'u_status' => 2,	//2:新添加 待审核状态
			//'firmware_name' => $firmware_name,
			//'firmware_config' => $firmware_config,
			'is_force' => $force,
			//'url' => $url,
			'size' => $size,
			//'zip_size' => $zip_size,
			//'filemd5' => $filemd5,
			'version' => $version,
			'description' => $description,
			'upgrade_time' => $upgrade_time,
			'depend_firmware' => $depend_firmware,
			'depend_song_db' => $depend_song_db,
			'depend_web_phone' => $depend_web_phone,
			'depend_apk' => $depend_apk
		);
		$arr = array('APK','FIRMWARE','WEB_PHONE','ipad','apad','iphone','aphone');
		if (in_array($upgrade_type, $arr))
			$newdata['versioncode'] = trim($_POST['versioncode']);
			
		$this->client_upgrade->update($newdata,array('id'=>$uid));
		if ($upgrade_type == 'APK' || $upgrade_type == 'SONG_DB' || $upgrade_type == 'FIRMWARE' || $upgrade_type == 'WEB_PHONE')
			showmessage('操作成功','index.php?m=go3c&c=client&a=upgrade_list');
		else 
			showmessage('操作成功','index.php?m=go3c&c=client&a=operation_list');
	}
	
	/**
	 * 版本列表
	 *
	 */
	public function client_list() {
		//终端类型
		$this->term_type = pc_base::load_model('term_type_model');
		$term_type_list = $this->term_type->select('', 'id,title', '', 'id ASC');
		foreach($term_type_list as $pvalue){
			$term_type_array[$pvalue['id']]=$pvalue['title'];
		}
		
		$this->db = pc_base::load_model('cms_client_version_model');
		//构造SQL
		$where = " 1 ";
		$online_status   = isset($_GET['online_status']) ? intval($_GET['online_status']) : '';
		$title    		 = strip_tags(trim($_GET['title']));
		$term_id         = intval($_GET['term_id']);
		$os_type         = intval($_GET['os_type']);
		$online_status	 ? $where.= " AND `online_status` = '$online_status'" : '';
		$term_id	 	 ? $where.= " AND `term_id` = '$term_id'" : '';
		$os_type   != '' ? $where.= " AND `os_type` = '$os_type'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		//echo '<pre>'; print_r($data);
		$pages = $this->db->pages;
		include $this->admin_tpl('client_version_list');
	}
	
	/**
	 * 申请上线
	 *
	 */
	public function online_apply() {
		$this->db = pc_base::load_model('cms_client_version_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>10), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 申请删除
	 *
	 */
	public function delete_apply() {
		$this->db = pc_base::load_model('cms_client_version_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>20), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));

	}
	
	

	/**
	 * 数据同步前清空老数据
	 *
	 */
	public function pre_sync() {
		$this->db = pc_base::load_model('tv_client_update_model');
		$this->db->query("truncate table client_update");
		showmessage('正在清空老数据，下面开始同步数据到前端','?m=go3c&c=client&a=sync',$ms = 500);
	}

	
	/**
	 * 数据同步
	 *
	 */
	public function sync() {

		$this->db  = pc_base::load_model('cms_client_version_model');		
		$this->db2 = pc_base::load_model('tv_client_update_model');		
		$field    	= 'v.*, vd.features';
		$sql     	= 'v9_client_version v left join v9_client_version_data vd on vd.id = v.id '; 
		$order  	= 'ORDER BY id ASC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 1;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		
		if($page < $totalpage+1){
			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $multipage.' ';
			
			$ver_array['term_id'] 		  = $data_array[0]['term_id'];
			$ver_array['os_type'] 		  = $data_array[0]['os_type'];
			$ver_array['version_number']  = $data_array[0]['title'];
			$ver_array['features'] 		  = $data_array[0]['features'];
			$ver_array['release_date'] 	  = $data_array[0]['release_date'];
			$ver_array['force_update']	  = $data_array[0]['force_update'];
			$ver_array['date_tested'] 	  = $data_array[0]['date_tested'];
			$ver_array['last_edit_time']  = date('Y-m-d H:i:s', $data_array[0]['updatetime']);
			$ver_array['last_edit_by'] 	  = $data_array[0]['username'];
			$ver_array['update_location'] = $data_array[0]['update_location'];
			
			//状态：1=编辑，2=待审核，3=已审核通过，4=审核未通过，5=已发布
			if($data_array[0]['published'] == '2'){
				$ver_array['status'] = '1';
			}elseif ($data_array[0]['published'] == '10'){
				$ver_array['status'] = '2';
			}elseif ($data_array[0]['published'] == '11'){
				$ver_array['status'] = '5';
			}elseif ($data_array[0]['published'] == '12'){
				$ver_array['status'] = '4';
			}elseif ($data_array[0]['published'] == '11'){
				$ver_array['status'] = '5';
			}else{
				$ver_array['status'] = '1';
			}
			
			$this->db2->insert($ver_array, true);
			echo '正在同步...';
			//exit;
			
			$next_page = $page + 1;
			page_jump('go3c', 'client', 'sync', $next_page);
		}else{
			showmessage(L('operation_success'),'?m=go3c&c=client&a=client_list',$ms = 500);
		}
		
	}
	/**
	 * 升级列表
	 *
	 */
	public function version_list() {
		$this->cmslog = pc_base::load_model('cms_cmslog_model');
		//构造SQL
		$spid = $this->current_spid['spid'];
		if($_SESSION['roleid']=='1'){
			$where = " ad_belong='2' AND fid='1' ";
		}else{
			$where = " ad_belong='2' AND fid='1' AND spid in (".$spid.") ";
		}
		$version   = $_GET['version'] ? $_GET['version'] : '';
		$username   		 = strip_tags(trim($_GET['username']));
		$version	 ? $where.= " AND `version` = '$version'" : '';
		$username	 	 ? $where.= " AND `username` = '$username'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->cmslog->listinfo($where, $order = '`logid` DESC', $page, $perpage);
		$pages = $this->cmslog->pages;
		include $this->admin_tpl('version_list');
	}
	//升级详情
	public function versionlist(){
		$this->cmslog = pc_base::load_model('cms_cmslog_model');
		$version   = $_GET['version'];
		$term   = $_GET['term'];
		$spid   = $_GET['spid'];
		//构造SQL
		//$spid = $this->current_spid['spid'];
		if($_SESSION['roleid']=='1'){
			$where = " ad_belong='2' AND fid='2' AND spid='".$spid."' AND term='".$term."' AND version='".$version."' ";
		}else{
			$where = " ad_belong='2' AND fid='2' AND spid='".$spid."' AND term='".$term."' AND version='".$version."' ";
		}				
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->cmslog->listinfo($where, $order = '`logid` DESC', $page, $perpage);
		$pages = $this->cmslog->pages;
		include $this->admin_tpl('version_detail');
	}
	//预览升级版本的图片/视频
	public function preview(){
		$this->cmslog = pc_base::load_model('cms_cmslog_model');
		$version   = $_GET['version'];
		$term   = $_GET['term'];
		$spid   = $_GET['spid'];
		//构造SQL
		if($_SESSION['roleid']=='1'){
			$where = " ad_belong='2' AND fid='2' AND spid='".$spid."' AND term='".$term."' AND version='".$version."' ";
		}else{
			$where = " ad_belong='2' AND fid='2' AND spid='".$spid."' AND term='".$term."' AND version='".$version."' ";
		}
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data1  = $this->cmslog->listinfo($where, $order = '`logid` DESC', $page, $perpage);
		foreach ($data1 as $key=>$v){
			if((strpos($v['url'],'zip')!== false)||(strpos($v['url'],'rar')!== false)||(strpos($v['url'],'.gz')!== false)) continue;
			$data[$key] = $v;
		}
		$pages = $this->cmslog->pages;
		include $this->admin_tpl('version_preview');
	}
	//硬件列表
	public function hardware(){
		$this->hardware = pc_base::load_model('cms_hardware_model');
		//构造SQL
		$where = " 1 ";
		$chip   = $_GET['chip'] ? $_GET['chip'] : '';
		$ANDROID   		 = strip_tags(trim($_GET['ANDROID']));
		$chip	 ? $where.= " AND `chip` = '$chip'" : '';
		$ANDROID	 	 ? $where.= " AND `ANDROID` = '$ANDROID'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->hardware->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->hardware->pages;
		include $this->admin_tpl('hardware_list');
	}
	//项目服务服务器
	public function serverlist(){
		$this->server_list = pc_base::load_model('cms_server_list_model');
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->server_list->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' spid', $key='');
			$board_list = $this->server_list->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' board_type', $key='');
		}else{
			$spid_list = $this->server_list->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' spid', $key='');
			$board_list = $this->server_list->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' board_type', $key='');
		}
		//构造SQL
		$where = " 1 ";		
		$SERVER_NAME   = $_GET['SERVER_NAME'];
		$spid   = $_GET['spid'];
		$board_type  = $_GET['board_type'];
		$SERVER_NAME	 ? $where.= " AND `SERVER_NAME` = '$SERVER_NAME'" : '';
		$spid	 ? $where.= " AND `spid` = '$spid'" : '';
		$board_type	? $where.= " AND `board_type` = '$board_type'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';
		$data  = $this->server_list->listinfo($where, $order = '`SERVER_ID` DESC', $page, $perpage);
		$pages = $this->server_list->pages;
		include $this->admin_tpl('serverlist_list');
	}
	//添加项目服务
	public function addserver(){
		$this->server_list = pc_base::load_model('cms_server_list_model');
		//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->server_list->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' spid', $key='');
			$board_list = $this->server_list->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' board_type', $key='');
		}else{
			$spid_list = $this->server_list->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' spid', $key='');
			$board_list = $this->server_list->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' board_type', $key='');
		}
		include $this->admin_tpl('serverlist_add');
	}
	public function addserverdo(){
		$this->server_list = pc_base::load_model('cms_server_list_model');
		$SERVER_NAME = trim($_POST['SERVER_NAME']);
		$server1 = trim($_POST['server1']);
		$server2 = trim($_POST['server2']);
		$spid = trim($_POST['spid']);
		$board_type = trim($_POST['board_type']);
		$data = array(
				'SERVER_NAME' => $SERVER_NAME,
				'server1'     => $server1,
				'server2' 	  => $server2,
				'spid' 		  => $spid,
				'board_type'  => $board_type,
				'createtime'  => time()
		);
		$this->server_list->insert($data);
		showmessage('操作成功','index.php?m=go3c&c=client&a=serverlist');
	}
	//修改项目服务
	public function editserver(){
		$SERVER_ID = $_GET['SERVER_ID'];
	//获取项目代号
		if($_SESSION['roleid']=='1'){
			$spid_list = $this->server_list->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' spid', $key='');
			$board_list = $this->server_list->select($where = '1', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' board_type', $key='');
		}else{
			$spid_list = $this->server_list->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' spid', $key='');
			$board_list = $this->server_list->select($where = 'spid in ("'.$_SESSION['spid'].'")', $data = 'spid,board_type', $limit = '', $order = 'SERVER_ID ASC', $group = ' board_type', $key='');
		}
		$aKey = "SERVER_ID = '".$SERVER_ID."'";
		$data = $this->server_list->get_one($aKey);
		include $this->admin_tpl('serverlist_edit');
	}
	public function editserverdo(){
		$SERVER_ID = trim($_POST['SERVER_ID']);
		$SERVER_NAME = trim($_POST['SERVER_NAME']);
		$server1 = trim($_POST['server1']);
		$server2 = trim($_POST['server2']);
		$spid = trim($_POST['spid']);
		$board_type = trim($_POST['board_type']);
		$data = array(
				'SERVER_NAME' => $SERVER_NAME,
				'server1'     => $server1,
				'server2' 	  => $server2,
				'spid' 		  => $spid,
				'board_type'  => $board_type,
				'createtime'  => time()
		);
		$this->server_list->update($data, array('SERVER_ID'=>$SERVER_ID));
		showmessage('操作成功','index.php?m=go3c&c=client&a=serverlist');
	}
	//删除项目服务
	public function deleteserver(){
		$SERVER_ID = $_GET['SERVER_ID'];
		$this->server_list->delete(array('SERVER_ID'=>$SERVER_ID));
		showmessage('操作成功','index.php?m=go3c&c=client&a=serverlist');
	}
	//生成项目服务的js文件
	public function createserverjs(){
		$spid = $_GET['spid'];
		$board = $_GET['board'];
		$where = "spid = '$spid' and board_type = '$board'";
		$list = $this->server_list->select($where, '*');
		foreach ($list as $key=>$v){			
			$app1 = array('server' => $v['server1'],'name' => 'server1');
			$app2 = array('server' => $v['server2'],'name' => 'server2');
			$app = array($app1, $app2);			
			$applist[$v['SERVER_NAME']] = $app;
			$php_json = json_encode($applist);
		}
		$xmlurl = '/home/wwwroot/default/server2/'.$spid.'/'.$board.'/'.'serverlist.js';
		$fp=fopen("$xmlurl","w");
		fwrite($fp,$php_json);
		@fclose($fp);
		chmod("$xmlurl",0777);
		$msg = '生成成功!';
		showmessage($msg,'?m=go3c&c=client&a=serverlist&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//KTV歌曲服务器下载列表
	public function ktvserver(){
		$this->ktv_servers = pc_base::load_model('usercenter_ktv_servers_model');
		$auth_info = $this->auth_info->select('', 'distinct(ID)', '', 'cid  ASC');
		//构造SQL
		$where = " 1 ";
		$s_clientid   = $_GET['s_clientid'] ? $_GET['s_clientid'] : '';
		$s_dns  = $_GET['s_dns'] ? $_GET['s_dns'] : '';
		$s_clientid	 ? $where.= " AND `s_clientid` = '$s_clientid'" : '';
		$s_dns  ? $where.= " AND `s_dns` = '$s_dns'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->ktv_servers->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->ktv_servers->pages;
		include $this->admin_tpl('ktv_servers_list');
	}
	//添加曲库服务器
	public function addservs(){
		$auth_info = $this->auth_info->select('', 'distinct(ID)', '', 'cid  ASC');
		include $this->admin_tpl('ktv_serversadd');
	}
	public function addservsdo(){
		$s_dns = trim($_POST['s_dns']);
		$s_apipath = trim($_POST['s_apipath']);
		$s_netid = trim($_POST['s_netid']);
		$s_clientid = trim($_POST['s_clientid']);
		$s_speed = trim($_POST['s_speed']);
		$s_allow3rd = trim($_POST['s_allow3rd']);
		$s_slice = trim($_POST['s_slice']);
		$s_status = trim($_POST['s_status']);
		
		$data = array(
				's_dns' => $s_dns,
				's_apipath'  => $s_apipath,
				's_netid' 	 => $s_netid,
				's_speed' 	=> $s_speed,
				's_clientid'  => $s_clientid,
				's_allow3rd'  => $s_allow3rd,
				's_slice'  => $s_slice,
				's_status'  => $s_status,
				's_addtime'  => time(),
				's_updatetime'  => time()
		);
		$this->ktv_servers->insert($data);
		showmessage('操作成功','index.php?m=go3c&c=client&a=ktvserver');
	}
	//编辑曲库服务器信息
	public function editservs(){
		$auth_info = $this->auth_info->select('', 'distinct(ID)', '', 'cid  ASC');
		$id  = $_GET['id'];
		$data = $this->ktv_servers->get_one(array('id'=>$id));
		include $this->admin_tpl('ktv_serversedit');
	}
	public function editservsdo(){
		$id = trim($_POST['id']);
		$s_dns = trim($_POST['s_dns']);
		$s_apipath = trim($_POST['s_apipath']);
		$s_netid = trim($_POST['s_netid']);
		$s_clientid = trim($_POST['s_clientid']);
		$s_speed = trim($_POST['s_speed']);
		$s_allow3rd = trim($_POST['s_allow3rd']);
		$s_slice = trim($_POST['s_slice']);
		$s_status = trim($_POST['s_status']);
	
		$data = array(
				's_dns' => $s_dns,
				's_apipath'  => $s_apipath,
				's_netid' 	 => $s_netid,
				's_speed' 	=> $s_speed,
				's_clientid'  => $s_clientid,
				's_allow3rd'  => $s_allow3rd,
				's_slice'  => $s_slice,
				's_status'  => $s_status,
				's_addtime'  => time(),
				's_updatetime'  => time()
		);
		$this->ktv_servers->update($data,array('id'=>$id));
		showmessage('操作成功','index.php?m=go3c&c=client&a=ktvserver');
	}
	//删除
	public function delserv(){
		$id  = $_GET['id'];
		$this->ktv_servers->delete(array('id'=>$id));
		showmessage('操作成功','index.php?m=go3c&c=client&a=ktvserver');
	}
}
?>
