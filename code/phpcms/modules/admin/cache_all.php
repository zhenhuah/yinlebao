<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php';
class cache_all extends admin {
	private $cache_api;
	
	public function init() {
		if (isset($_POST['dosubmit']) || isset($_GET['dosubmit'])) {
			$page = $_GET['page'] ? intval($_GET['page']) : 0;
			$modules = array(
				array('name' => L('module'), 'function' => 'module'),
				array('name' => L('sites'), 'mod' => 'admin', 'file' => 'sites', 'function' => 'set_cache'),
				array('name' => L('category'), 'function' => 'category'),
				array('name' => L('downservers'), 'function' => 'downservers'),
				array('name' => L('badword_name'), 'function' => 'badword'),
				array('name' => L('ipbanned'), 'function' => 'ipbanned'),
				array('name' => L('keylink'), 'function' => 'keylink'),
				array('name' => L('linkage'), 'function' => 'linkage'),
				array('name' => L('position'), 'function' => 'position'),
				array('name' => L('admin_role'), 'function' => 'admin_role'),
				array('name' => L('urlrule'), 'function' => 'urlrule'),
				array('name' => L('sitemodel'), 'function' => 'sitemodel'),
				array('name' => L('type'), 'function' => 'type', 'param' => 'content'),
				array('name' => L('workflow'), 'function' => 'workflow'),
				array('name' => L('dbsource'), 'function' => 'dbsource'),
				array('name' => L('member_setting'), 'function' => 'member_setting'),
				array('name' => L('member_group'), 'function' => 'member_group'),
				array('name' => L('membermodel'), 'function' => 'membermodel'),
				array('name' => L('member_model_field'), 'function' => 'member_model_field'),
				array('name' => L('search_type'), 'function' => 'type', 'param' => 'search'),
				array('name' => L('search_setting'), 'function' => 'search_setting'),
				array('name' => L('update_vote_setting'), 'function' => 'vote_setting'),
				array('name' => L('update_link_setting'), 'function' => 'link_setting'),
				array('name' => L('special'), 'function' => 'special'),
				array('name' => L('setting'), 'function' => 'setting'),
				array('name' => L('database'), 'function' => 'database'),
				array('name' => L('update_formguide_model'), 'mod' => 'formguide', 'file' => 'formguide', 'function' => 'public_cache'),
				array('name' => L('cache_file'), 'function' => 'cache2database'),
				array('name' => L('cache_copyfrom'), 'function' => 'copyfrom'),
				array('name' => L('clear_files'), 'function' => 'del_file'),
			);
			$this->cache_api = pc_base::load_app_class('cache_api', 'admin');
			$m = $modules[$page];
			if ($m['mod'] && $m['function']) {
				if ($m['file'] == '') $m['file'] = $m['function'];
				$M = getcache('modules', 'commons');
				if (in_array($m['mod'], array_keys($M))) {
					$cache = pc_base::load_app_class($m['file'], $m['mod']);
					$cache->$m['function']();
				}
			} else if($m['target']=='iframe') {
				echo '<script type="text/javascript">window.parent.frames["hidden"].location="index.php?'.$m['link'].'";</script>';
			} else {
				$this->cache_api->cache($m['function'], $m['param']);
			}
			$page++;
			if (!empty($modules[$page])) {
				echo '<script type="text/javascript">window.parent.addtext("<li>'.L('update').$m['name'].L('cache_file_success').'..........</li>");</script>';
				showmessage(L('update').$m['name'].L('cache_file_success'), '?m=admin&c=cache_all&page='.$page.'&dosubmit=1&pc_hash='.$_SESSION['pc_hash'], 0);
			} else {
				echo '<script type="text/javascript">window.parent.addtext("<li>'.L('update').$m['name'].L('site_cache_success').'..........</li>")</script>';
				showmessage(L('update').$m['name'].L('site_cache_success'), 'blank');
			}
		} else {
			include $this->admin_tpl('cache_all');
		}
	}

	public function up_database(){
		//获取当前用户信息
		$this->spid_db = pc_base::load_model('admin_model');			//后台登录表连接
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告推荐位信息表
		$userid = $_SESSION['userid'];
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
			//手工执行接口数据
			$url3 = GO3C_PATH3.'go3c_table_update.php';
			$url4 = GO3C_PATH3.'checklock.php';
			$tmp4 = file_get_contents($url4);
			$phpcmsdb = yzy_phpcms_db() ;
			$tc = time();
			$rd = $phpcmsdb->r1('v9_times') ;
			$logintime = $rd['logintime'];
			if($tc>$logintime+60||$tmp4=='unlocked'){
				//$tmp3 = file_get_contents($url3);
				//同步数据时生成开机xml文件
				$termd = array('1');     //终端类型
				$spid = $this->current_spid['spid'];
				$roleid = $_SESSION['roleid'];
				//根据roleid查询账号属于哪个项目
				$this->admin = pc_base::load_model('admin_model');
				$admKey = "roleid = '$roleid'";
				$adinfo  = $this->admin->get_one($admKey);
				//$adinfo['spid'] = 'GO3CKTV,WMMY,ZYDQ';
				$this->spid_db = pc_base::load_model('cms_spid_model');
				if($_SESSION['roleid']=='1'){  //超级管理员生成所有项目的xml文件
					$spiddata  = $this->spid_db->select();
					foreach ($spiddata as $svp){  //循环多个项目
						$spid = $svp['spid'];   //项目名
						if(strpos($svp['board_type'],',')!== false){
							$board = explode(",",$svp['board_type']);
							foreach ($board as $sb){  //循环一个项目包含多个板子
								$sbd = $sb;   //板子名
								foreach ($termd as $vt){     //循环多个终端
									if($vt=='1'){
										$xmlurl = '../go3ccms/xml/stb/'.$spid.'/'.$sbd.'/resources.xml';
									}elseif($vt=='2'){
										$xmlurl = '../go3ccms/xml/phone/'.$spid.'/'.$sbd.'/resources.xml';
									}elseif($vt=='3'){
										$xmlurl = '../go3ccms/xml/pad/'.$spid.'/'.$sbd.'/resources.xml';
									}
									self::create_xml($spid,$sbd,$vt,$xmlurl,$roleid);
								}
							}
						}else{   //一个项目->一个板子
							$sbd = $svp['board_type'];
							foreach ($termd as $vt){     //循环多个终端
								if($vt=='1'){
									$xmlurl = '../go3ccms/xml/stb/'.$spid.'/'.$sbd.'/resources.xml';
								}elseif($vt=='2'){
									$xmlurl = '../go3ccms/xml/phone/'.$spid.'/'.$sbd.'/resources.xml';
								}elseif($vt=='3'){
									$xmlurl = '../go3ccms/xml/pad/'.$spid.'/'.$sbd.'/resources.xml';
								}
								self::create_xml($spid,$sbd,$vt,$xmlurl,$roleid);
							}
						}
					}
				}else{   //角色生成该角色有权限操作的项目
					$admKey = "spid in ('".$_SESSION['spid']."')";
					$spiddata  = $this->spid_db->select($admKey);
					foreach ($spiddata as $svp){  //循环多个项目
						$spid = $svp['spid'];   //项目名
						if(strpos($svp['board_type'],',')!== false){
							$board = explode(",",$svp['board_type']);
							foreach ($board as $sb){  //循环一个项目包含多个板子
								$sbd = $sb;   //板子名
								foreach ($termd as $vt){     //循环多个终端
									if($vt=='1'){
										$xmlurl = '../go3ccms/xml/stb/'.$spid.'/'.$sbd.'/resources.xml';
									}elseif($vt=='2'){
										$xmlurl = '../go3ccms/xml/phone/'.$spid.'/'.$sbd.'/resources.xml';
									}elseif($vt=='3'){
										$xmlurl = '../go3ccms/xml/pad/'.$spid.'/'.$sbd.'/resources.xml';
									}
									self::create_xml($spid,$sbd,$vt,$xmlurl,$roleid);
								}
							}
						}else{   //一个项目->一个板子
							$sbd = $svp['board_type'];
							foreach ($termd as $vt){     //循环多个终端
								if($vt=='1'){
									$xmlurl = '../go3ccms/xml/stb/'.$spid.'/'.$sbd.'/resources.xml';
								}elseif($vt=='2'){
									$xmlurl = '../go3ccms/xml/phone/'.$spid.'/'.$sbd.'/resources.xml';
								}elseif($vt=='3'){
									$xmlurl = '../go3ccms/xml/pad/'.$spid.'/'.$sbd.'/resources.xml';
								}
								self::create_xml($spid,$sbd,$vt,$xmlurl,$roleid);
							}
						}
					}
				}
				//执行清除缓存行为(TOMCAT)
				$url1 = GO3C_PATH1.'go3cci/cache.api?m=clear';
				$tmp1 = file_get_contents($url1);
				$url2 = GO3C_PATH2.'go3cci/cache.api?m=clear';
				$tmp2 = file_get_contents($url2);
				$phpcmsdb->d('v9_times',array('logintime'=>$logintime)) ;
				$dd = array(
					'username' 	 => $tc,
					'logintime'  => $tc
					);
				$phpcmsdb->w('v9_times',$dd) ; 
				showmessage('数据更新成功','?m=admin&c=index',500);
			}else{
				showmessage('抱歉!脚本更新中,请稍后尝试!', '?m=admin&c=index',500);
			}
			include $this->admin_tpl('cache_all');
	}
	
	public function select_app($term,$spid,$sbd,$app_on,$roleid){
		$this->spid_db = pc_base::load_model('admin_model');			//后台登录表连接
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');//广告推荐位信息表
		$wKey = "adStatus = '100' AND term_id = '".$term."' AND spid = '".$spid."' AND board_type = '".$sbd."' AND ad_belong='2' AND genus ='".$app_on."'";
		$advert_list  = $this->adverts_db->get_one($wKey);
		return $advert_list;
	}
	public function create_xml($spid,$sbd,$term_id,$xmlurl,$roleid){
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml .= "<Resources>\n";
		//$spid = 'ktv';
		//获取某个项目某个客户端的整个压缩包(APP_ZIP)
		//if($_SESSION['roleid']=='1'){
		//	$wKey = "adStatus = '100' AND term_id = '".$term_id."' AND ad_belong='2' AND genus ='APP_ZIP'";
		//}else{
			$wKey = "adStatus = '100' AND term_id = '".$term_id."' AND spid = '".$spid."' AND board_type = '".$sbd."' AND ad_belong='2' AND genus ='APP_ZIP'";
		//}
		$APP_ZIP_list  = $this->adverts_db->get_one($wKey);
		if(empty($APP_ZIP_list)){
			$xml .="<projid></projid>\n";
			$xml .="<versioncode></versioncode>\n";
			$xml .="<url></url>\n";
		}else{
			$xml .="<projid>".$APP_ZIP_list['spid']."</projid>\n";
			$xml .="<versioncode>".$APP_ZIP_list['version']."</versioncode>\n";
			$xml .="<url>".$APP_ZIP_list['imgUrl']."</url>\n";
		}
		$app1 = array('BOOT_LOGO','ANDROID_LOGO','ANDROID_BOOTANIMATION','ANDROID_SETUP_WIZARD','APP_UPGRADE','APP_SKIN','APP_ANIMATION','APP_VIDEOS','APP_ICONS','APP_LOADING','REMOTE','APP_RESTART_ANIMATION','APP_RETURN_ANIMATION');
		foreach ($app1 as $v){
			if($v =='BOOT_LOGO'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<BOOT_LOGO>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</BOOT_LOGO>\n";
				}else{
					$xml .="<BOOT_LOGO>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</BOOT_LOGO>\n";
				}
			}
			if($v =='ANDROID_LOGO'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<ANDROID_LOGO>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</ANDROID_LOGO>\n";
				}else{
					$xml .="<ANDROID_LOGO>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</ANDROID_LOGO>\n";
				}
			}
			if($v =='ANDROID_BOOTANIMATION'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<ANDROID_BOOTANIMATION>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</ANDROID_BOOTANIMATION>\n";
				}else{
					$arr1 = explode("/",$data['imgUrl']);
					$num = count($arr1)-1;
					$zm = '/home/wwwroot/default/test/test_md5sum.php';
					$test = "/usr/local/php/bin/php -f $zm";
					//exec($test,$array);
					$xml .="<ANDROID_BOOTANIMATION>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<md5>d4d13b29b96e85d6cecf64c636ec2564</md5>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</ANDROID_BOOTANIMATION>\n";
				}
			}
			if($v =='ANDROID_SETUP_WIZARD'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<ANDROID_SETUP_WIZARD>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</ANDROID_SETUP_WIZARD>\n";
				}else{
					$xml .="<ANDROID_SETUP_WIZARD>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</ANDROID_SETUP_WIZARD>\n";
				}
			}
			if($v =='APP_UPGRADE'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_UPGRADE>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_UPGRADE>\n";
				}else{
					$xml .="<APP_UPGRADE>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_UPGRADE>\n";
				}
			}
			if($v =='APP_SKIN'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_SKIN>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_SKIN>\n";
				}else{
					$xml .="<APP_SKIN>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_SKIN>\n";
				}
			}
			if($v =='APP_ANIMATION'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_ANIMATION>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_ANIMATION>\n";
				}else{
					$xml .="<APP_ANIMATION>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_ANIMATION>\n";
				}
			}
			if($v =='APP_VIDEOS'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_VIDEOS>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_VIDEOS>\n";
				}else{
					$xml .="<APP_VIDEOS>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_VIDEOS>\n";
				}
			}
			if($v =='APP_ICONS'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_ICONS>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_ICONS>\n";
				}else{
					$xml .="<APP_ICONS>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_ICONS>\n";
				}
			}
			if($v =='APP_LOADING'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_LOADING>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_LOADING>\n";
				}else{
					$xml .="<APP_LOADING>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_LOADING>\n";
				}
			}
			if($v == 'REMOTE'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<REMOTE>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</REMOTE>\n";
				}else{
					$xml .="<REMOTE>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</REMOTE>\n";
				}
			}
			if($v == 'APP_RESTART_ANIMATION'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_RESTART_ANIMATION>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_RESTART_ANIMATION>\n";
				}else{
					$xml .="<APP_RESTART_ANIMATION>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_RESTART_ANIMATION>\n";
				}
			}
			if($v == 'APP_RETURN_ANIMATION'){
				$data = self::select_app($term_id,$spid,$sbd,$v,$roleid);
				if(empty($data)){
					$xml .="<APP_RETURN_ANIMATION>\n";
					$xml .="<versioncode>\n";
					$xml .="</versioncode>\n";
					$xml .="<url>\n";
					$xml .="</url>\n";
					$xml .="</APP_RETURN_ANIMATION>\n";
				}else{
					$xml .="<APP_RETURN_ANIMATION>\n";
					$xml .="<versioncode>".$data['version']."</versioncode>\n";
					$xml .="<url>".$data['imgUrl']."</url>\n";
					$xml .="</APP_RETURN_ANIMATION>\n";
				}
			}
		}
		
		//if($_SESSION['roleid']=='1'){
		//	$wKey = "adStatus = '100' AND term_id = '".$term_id."' AND ad_belong='2' AND genus ='APP_WIZARDS'";
		//}else{
			$wKey = "adStatus = '100' AND term_id = '".$term_id."' AND spid = '".$spid."' AND board_type = '".$sbd."' AND ad_belong='2' AND genus ='APP_WIZARDS'";
		//}
		$WIZARDS_list  = $this->adverts_db->select($wKey);
		if($WIZARDS_list){
			if(count($WIZARDS_list)>1){
				$num = count($WIZARDS_list);
				$xml .="<APP_WIZARDS>\n";
				$xml .="<versioncode>".$WIZARDS_list[0]['version']."</versioncode>\n";
				$xml .="<num>".$num."</num>\n";
				foreach ($WIZARDS_list as $vs){
					$xml .="<url>".$vs['imgUrl']."</url>\n";
				}
				$xml .="</APP_WIZARDS>\n";
			}else{
				$xml .="<APP_WIZARDS>\n";
				$xml .="<versioncode>".$WIZARDS_list['version']."</versioncode>\n";
				$xml .="<url>".$WIZARDS_list['imgUrl']."</url>\n";
				$xml .="</APP_WIZARDS>\n";
			}
		}else{
			$xml .="<APP_WIZARDS>\n";
			$xml .="<versioncode>\n";
			$xml .="</versioncode>\n";
			$xml .="<url>\n";
			$xml .="</url>\n";
			$xml .="</APP_WIZARDS>\n";
		}
		$xml .= "</Resources>\n";
		$fp=fopen("$xmlurl","w");
		fwrite($fp,$xml);
		@fclose($fp);
		if(!empty($APP_ZIP_list['version'])){
			self::create_xml_log($APP_ZIP_list['version'],$term_id,$spid);
		}
	}
	//记录发布开机图的版本
	public function create_xml_log($version,$term,$spid){
		$ip = ip();
		$this->cmslog = pc_base::load_model('cms_cmslog_model');
		$dlog = array(
			'type' => 'online_advert',
			'contetdec' => '开机图升级',
			'userid' => $_SESSION['userid'],
			'username' => $_SESSION['username'],
			'ip' => $ip,
			'version' => $version,
			'ad_belong' => '2',
			'term' => $term,
			'fid' => '1',
			'spid' => $spid,
			'createtime' => time()
		);
		//查询此版本是否已经发布
		$ylog = $this->cmslog->get_one(array('version'=>$version,'term'=>$term,'spid'=>$spid));
		if(empty($ylog)){
			$this->cmslog->insert($dlog);
		}else{
			$createtime = time();
			$this->cmslog->update(array('createtime'=>$createtime), array('version'=>$version,'term'=>$term,'spid'=>$spid));
		}
	}
}
?>