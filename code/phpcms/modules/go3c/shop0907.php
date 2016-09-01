<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class shop extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		$this->db   = pc_base::load_model('shop_type_model');
		$this->cms_spid = pc_base::load_model('cms_spid_model');
	}   	
	
	public function init() {
		
	}
	
	/**
	 * 应用商店类型列表
	 *
	 */
	public function type() {
		$admin_username = param::get_cookie('admin_username');
		$type_name   	= trim($_GET['type_name']);
		$title_filt = $type_name ? " AND cat_name LIKE '%$type_name%' " : '';
		$field    	= '*';
		$sql     	= "app_channel_category WHERE 1 ".$title_filt;
		//$sql .= " AND pid = 1 ";
		$order  	= 'ORDER BY cat_id';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 20;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('shop_type');			
	}
	
	/*
	 * 添加商店类型
	 */
	public function add_shop_type(){
		$type_list = $this->db->select('level=1', '*', '', 'cat_id ASC');
		include $this->admin_tpl('shop_type_add');
	}
	
	/*
	 * 添加商店类型
	 */
	public function add_shop_type_do(){
		$type_name = $_POST['type_name'];
		$sort = $_POST['sort'] == '' ? '0' : $_POST['sort'];
		$count = $_POST['count'] == '' ? '0' : $_POST['count'];
		$status = $_POST['status'] == '' ? '1' : $_POST['status'];
		$url = $_POST['url'];
		$description = $_POST['description'];
		$remark = $_POST['remark'];
		$cat_id = $_POST['cat_id'];
		if($cat_id!='0'){  //添加二级类型
			$data_type = array(
					'cat_name' => $type_name,
					'description' => $description,
					'url' => $url,
					'status' => $status,
					'remark' => $remark,
					'sort' => $sort,
					'pid' => $cat_id,
					'level' => 2,
					'count' => $count
			);
		}else{ //添加一级类型
			$data_type = array(
					'cat_name' => $type_name,
					'description' => $description,
					'url' => $url,
					'status' => $status,
					'remark' => $remark,
					'sort' => $sort,
					'pid' => 0,
					'level' => 1,
					'count' => $count
			);
		}
		$this->db->insert($data_type);
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=shop&a=type&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/*
	 * 删除商店类型
	 */
	public function delete_type(){
		$id = $_REQUEST['id'];
		$this->db->delete(array('cat_id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/*
	 * 编辑商店类型
	*/
	public function edit_shop_type(){
		$id = $_REQUEST['id'];
		$aKey = "cat_id = '".$id."'";
		$data = $this->db->get_one($aKey);
		$type_list = $this->db->select('level=1', '*', '', 'cat_id ASC');
		include $this->admin_tpl('shop_type_edit');
	}
	
	/*
	 * 编辑商店类型
	*/
	public function edit_shop_type_do(){
		$id = $_REQUEST['id'];
		$type_name = $_POST['type_name'];
		$sort = $_POST['sort'] == '' ? '0' : $_POST['sort'];
		$count = $_POST['count'] == '' ? '0' : $_POST['count'];
		$status = $_POST['status'] == '' ? '1' : $_POST['status'];
		$url = $_POST['url'];
		$description = $_POST['description'];
		$remark = $_POST['remark'];
		$cat_id = $_POST['cat_id'];
		if($cat_id!='0'){  //添加二级类型
			$data_type = array(
					'cat_name' => $type_name,
					'description' => $description,
					'url' => $url,
					'status' => $status,
					'remark' => $remark,
					'sort' => $sort,
					'pid' => $cat_id,
					'level' => 2,
					'count' => $count
			);
		}else{
			$data_type = array(
					'cat_name' => $type_name,
					'description' => $description,
					'url' => $url,
					'status' => $status,
					'remark' => $remark,
					'sort' => $sort,
					'pid' => 0,
					'level' => 1,
					'count' => $count
			);
		}
		$this->db->update($data_type,array('cat_id'=>$id));
		$msg = '提交成功!';
		showmessage($msg,'?m=go3c&c=shop&a=type&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	
	/**
	 * 商店列表
	 *
	 */
	public function shop_list() {
		$admin_username = param::get_cookie('admin_username');
		$type_name_list = $this->db->select('level = 2', 'cat_id, cat_name', '', 'cat_id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$this->app = pc_base::load_model('app_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND app_name LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "app WHERE 1 ".$title_filt;
		//查询处理 start 
		$type = 0;
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$status = trim($_GET['status']);
			if(!empty($type) && $type)
			{
				$sql .= " AND channel_cat_id = '".$type."'";
			}
			if(!empty($status) && $type != '全部')
			{
				$sql .= " AND status = '".$status."'";
			}
		}
		$order  	= 'ORDER BY app_id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->app->mynum($sql);
		$totalpage	= $this->app->mytotalpage($sql, $perpage);
		$data 		= $this->app->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->app->pages;
		include $this->admin_tpl('shop_list');		
	}
	
	/*
	 * 删除应用
	 */
	public function delete_app(){
		$id = $_REQUEST['id'];
		$this->app_image = pc_base::load_model('app_image_model');
		$this->app_image->delete(array('app_id'=>$id));
		$this->app_download = pc_base::load_model('app_download_info_model');
		$this->app_download->delete(array('app_id'=>$id));
		$this->app = pc_base::load_model('app_model');
		$this->app->delete(array('app_id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	/*
	 * 应用上线发布,复制数据到接口表
	 */
	public function shop_pass(){
		$id = $_REQUEST['id'];
		$this->app = pc_base::load_model('app_model');
		/*$this->app_image = pc_base::load_model('app_image_model');
		$this->app_download = pc_base::load_model('app_download_info_model');
		$this->app_all_list = pc_base::load_model('app_all_list_model');
		$appd = $this->app->get_one(array('app_id'=>$id));
		$appimage = $this->app_image->get_one(array('app_id'=>$id,'image_type'=>'122'));
		$appload = $this->app_download->get_one(array('app_id'=>$id));
		if(empty($appimage)||empty($appload)){
			showmessage('抱歉,最少要包含一张图片和apk文件!',$_SERVER['HTTP_REFERER'], $ms = 500);
		}
		//组合接口数据
		$data = array(
				'app_id' => $appd['app_id'],
				'app_name' => $appd['app_name'],
				'spell' => $appd['spell'],
				'app_desc' => $appd['app_desc'],
				'owner' => $appd['owner'],
				'file_size' => $appd['file_size'],
				'score' => $$appd['score'],
				'channel_cat_id' => $appd['channel_cat_id'],
				'channel' => $appd['channel'],
				'packagename' => $appd['packagename'],
				'version' => $appd['version'],
				'type' => $appd['type'],
				'term_id' => $appload['term_id'],
				'download_count' => $appd['download_count'],
				'release_date' => date("Y-m-d",time()),
				'image_file' => $appimage['image_file'],
				'install_file' => $appload['install_file']
		);
		$this->app_all_list->insert($data);
		*/
		$this->app->update(array('status'=>2), array('app_id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	/*
	 * 应用下线,删除接口表的相对应数据
	 */
	public function shop_off(){
		$this->app = pc_base::load_model('app_model');
		$this->app_all_list = pc_base::load_model('app_all_list_model');
		$this->app_onlinelog = pc_base::load_model('app_onlinelog_model');
		$id = intval($_GET['id']);
		$this->app->update(array('status'=>1), array('app_id'=>$id));
		$this->app_all_list->delete(array('app_id'=>$id));
		$this->app = pc_base::load_model('app_model');
		$appd = $this->app->get_one(array('app_id'=>$id));
		//下线操作记录上线日志
		$dalog = array(
			'app_id'=>$appd['app_id'],
			'sid'=>$appd['sid'],
			'packagename'=>$appd['packagename'],
			'status'=>'off',
			'reason'=>'同意下线',
			'createtime'=>time()
		);
		$this->app_onlinelog->insert($dalog);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	/*
	 * 显示所有可以推荐的应用
	 */
	public function showShop(){
		$taskId   	= trim($_GET['taskId']);
		$admin_username = param::get_cookie('admin_username');
		$type_name_list = $this->db->select('level = 2', 'cat_id, cat_name', '', 'cat_id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$this->app = pc_base::load_model('app_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND app_name LIKE '%$title%'" : '';
		$field    	= '*';
		if($taskId=='8'){
			$sql     	= "app WHERE 1 ".$title_filt;
		}else{
			$sql     	= "app WHERE status='4' ".$title_filt;
		}
		//查询处理 start 
		$type = 0;
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$status = trim($_GET['status']);
			if(!empty($type) && $type)
			{
				$sql .= " AND channel_cat_id = '".$type."'";
			}
			if(!empty($status) && $type != '全部')
			{
				$sql .= " AND status = '".$status."'";
			}
		}
		$order  	= 'ORDER BY app_id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->app->mynum($sql);
		$totalpage	= $this->app->mytotalpage($sql, $perpage);
		$data 		= $this->app->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->app->pages;
		include $this->admin_tpl('task_add_shop_list');	
	}
	
	/*
	 * 列出某推荐的应用
	 */
	public function viewShop(){
		$this->task_app = pc_base::load_model('task_app_model');//应用任务数据表连接
		$taskId   	= trim($_GET['taskId']);
		$where = " task_id =$taskId";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$infor_list = $this->task_app->listinfo($where, $order = "", $page, $perpage);
		$pages = $this->task_app->pages;
		include $this->admin_tpl('shop_view_list');
	}
	
	/*
	 * 	//删除该推荐位的此应用
	 */
	public function deleteTaskinfor(){
		$preId   	= trim($_GET['preId']);
		$taskId   	= trim($_GET['taskId']);
		$this->task_db = pc_base::load_model('shop_task_model');
		$this->task_app = pc_base::load_model('task_app_model');
		if(empty($preId)||empty($taskId)){
			showmessage('操作错误或数据不存在!',HTTP_REFERER);
		}else{
			$where_data = array(
					'task_id'=> $taskId,
					'id' => $preId
			);
			$this->task_app->delete($where_data);
			$gkey="taskId = $taskId";
			$taskInfo = $this->task_db->get_one($gkey);
			$videoNums = $taskInfo['videoNums']-1;
			$taskUpdate = array(
					'videoNums'=>$videoNums
			);
			$taskWhere =array(
					'taskId'=> $taskId
			);
			$this->task_db->update($taskUpdate,$taskWhere);
			showmessage(L('operation_success'),HTTP_REFERER);
		}
	}
	/*
	 * 批量下线应用
	*/
	public function delete_allto(){
		$this->app = pc_base::load_model('app_model');
		$this->app_all_list = pc_base::load_model('app_all_list_model');
		$this->app_onlinelog = pc_base::load_model('app_onlinelog_model');
		$ids=explode(',', $_GET['app_id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->app_all_list->delete(array('app_id'=>$v));
				$this->app->update(array('status'=>1), array('app_id'=>$v));
				$this->app = pc_base::load_model('app_model');
				$appd = $this->app->get_one(array('app_id'=>$v));
				//下线操作记录上线日志
				$dalog = array(
					'app_id'=>$appd['app_id'],
					'sid'=>$appd['sid'],
					'packagename'=>$appd['packagename'],
					'status'=>'off',
					'reason'=>'同意下线',
					'createtime'=>time()
				);
				$this->app_onlinelog->insert($dalog);
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	/*
	 * 批量申请审核应用
	*/
	public function online_pass_all(){
		$this->app = pc_base::load_model('app_model');
		//$this->app_image = pc_base::load_model('app_image_model');
		//$this->app_download = pc_base::load_model('app_download_info_model');
		//$this->app_all_list = pc_base::load_model('app_all_list_model');
		$ids=explode(',', $_GET['app_id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
			/*	$appd = $this->app->get_one(array('app_id'=>$v));
				$appimage = $this->app_image->get_one(array('app_id'=>$v,'image_type'=>'122'));
				$appload = $this->app_download->get_one(array('app_id'=>$v));
				if(empty($appimage)||empty($appload)){
					showmessage('抱歉,最少要包含一张图片和apk文件!',$_SERVER['HTTP_REFERER'], $ms = 500);
				}
				//判断此应用是否已经上线
				$app_on = $this->app_all_list->get_one(array('app_id'=>$v));
				if(!empty($app_on)){
					continue;
				}
				//组合接口数据
				$data = array(
						'app_id' => $appd['app_id'],
						'app_name' => $appd['app_name'],
						'spell' => $appd['spell'],
						'app_desc' => $appd['app_desc'],
						'owner' => $appd['owner'],
						'file_size' => $appd['file_size'],
						'score' => $$appd['score'],
						'channel_cat_id' => $appd['channel_cat_id'],
						'channel' => $appd['channel'],
						'packagename' => $appd['packagename'],
						'version' => $appd['version'],
						'type' => $appd['type'],
						'term_id' => $appload['term_id'],
						'download_count' => $appd['download_count'],
						'release_date' => date("Y-m-d",time()),
						'image_file' => $appimage['image_file'],
						'install_file' => $appload['install_file']
				);
				$this->app_all_list->insert($data);
				*/
				$this->app->update(array('status'=>2), array('app_id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	/*
	 * 批量删除应用
	*/
	public function online_error(){
		$this->app = pc_base::load_model('app_model');
		$this->app_image = pc_base::load_model('app_image_model');
		$this->app_download = pc_base::load_model('app_download_info_model');
		$ids=explode(',', $_GET['app_id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->app->delete(array('app_id'=>$v));
				$this->app_image->delete(array('app_id'=>$v));
				$this->app_download->delete(array('app_id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	//客户端列表
	public function rdapp_list(){
		$this->rdapp_model = pc_base::load_model('tv_3rdapp_model');
		$name   	= trim($_GET['name']);
		$type   	= trim($_GET['type']);
		$name = $name ? " AND name = '".$name."'" : '';
		$field    	= '*';
		$sql     	= "3rdapp WHERE 1 ";
		if($name){
			$sql .= " AND name = '".$name."'";
		}
		if($type){
			$sql .= " AND type = '".$type."'";
		}
		$order  	= '';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->rdapp_model->mynum($sql);
		$totalpage	= $this->rdapp_model->mytotalpage($sql, $perpage);
		$infor_list = $this->rdapp_model->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->rdapp_model->pages;
		//获取类型分类
		//$fdt  = '*';
		//$wht = "3rdapp WHERE 1 group by type ";
		//$type_list = $this->rdapp_model->mylistinfo($fdt, $wht, $order, $page, $perpage);
		include $this->admin_tpl('rdapp_list');
	}
	//生成云空间的类型的json文件
	public function createjson(){
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		if($_SESSION['roleid']=='1'){
			$awhere = " 1 ";
		}else{
			$awhere = " 1 AND spid in ('".$_SESSION['spid']."') ";
		}
		$sp_list = $this->cms_spid->select($awhere);
		$tream = 'stb';
		$cat_id   	= trim($_GET['cat_id']);
		if(empty($cat_id)){
			$msg = '抱歉,数据异常!';
			showmessage($msg,'?m=go3c&c=shop&a=type&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
		}
		$this->app_all_list = pc_base::load_model('app_all_list_model');
		foreach ($sp_list as $vsp){
			$spid = $vsp['spid'];
			$sbd  = $vsp['board_type'];
			$where = "channel_cat_id = $cat_id";
			$app_list = $this->app_all_list->select($where, '*');
			$app = array();
			foreach ($app_list as $key=>$v){
				$app[$key]['id'] = $v['app_id'];
				$app[$key]['title'] = $v['app_name'];
				$app[$key]['total'] = $v['total'];
				$app[$key]['score'] = $v['score'];
				$app[$key]['version'] = $v['version'];
				$app[$key]['channel'] = $v['channel'];
				$app[$key]['packagename'] = $v['packagename'];
				$app[$key]['logo'] = $v['image_file'];
				$app[$key]['file_path'] = $v['install_file'];
				$url = $v['image_file'];
				$path = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/';
				self::down_image($url,$path);
			}
			$php_json = json_encode($app);
			$xmlurl = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/app_yun.json';
			$fp=fopen("$xmlurl","w");
			fwrite($fp,$php_json);
			@fclose($fp);
			chmod("$xmlurl",0777);
			//更新升级xml文件
			$sjxml = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/appresources.xml';
			$jszip = 'http://www.go3c.tv:8060/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/';
			self::appresources($spid,$sbd,$sjxml,$jszip);
		}
		$msg = '生成成功!';
		showmessage($msg,'?m=go3c&c=shop&a=type&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//生成直播点播的json文件
	public function createrdapjson(){
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		if($_SESSION['roleid']=='1'){
			$awhere = " 1 ";
		}else{
			$awhere = " 1 AND spid in ('".$_SESSION['spid']."') ";
		}
		$sp_list = $this->cms_spid->select($awhere);
		$tream = 'stb';
		foreach ($sp_list as $vsp){
			$spid = $vsp['spid'];
			$sbd  = $vsp['board_type'];
				//更新升级xml文件
				$sjxml1 = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/appresources.xml';
				$jszip = 'http://www.go3c.tv:8060/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/';
				$this->rdapp_model = pc_base::load_model('tv_3rdapp_model');
				$type   	= trim($_GET['type']);
				if($type=='vod'){
					$type_apk = array('1'=>'vod');
				}elseif($type=='music'){
					$type_apk = array('1'=>'music');
				}elseif($type=='live'){
					$type_apk = array('1'=>'live');
				}else{
					$type_apk = array('1'=>'vod','2'=>'music','3'=>'live');
				}
				foreach ($type_apk as $vt){
					$where = "type = '$vt'";
					$app_list = $this->rdapp_model->select($where, '*');
					$app = array();
					foreach ($app_list as $key=>$v){
						$app[$key]['name'] = $v['name'];
						$app[$key]['os'] = $v['os'];
						$app[$key]['type'] = $v['type'];
						$app[$key]['className'] = $v['classname'];
						$app[$key]['iconUrl'] = $v['icon_url'];
						$app[$key]['installUrl'] = $v['install_url'];
						$app[$key]['desc'] = $v['desc'];
						$url = $v['icon_url'];
						$path = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/';
						self::down_image($url,$path);
					}
					$php_json = json_encode($app);
					$xmlurl = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/'.$vt.'.json';
					$fp=fopen("$xmlurl","w");
					fwrite($fp,$php_json);
					@fclose($fp);
					chmod("$xmlurl",0777);
				}
				$version = date("YmdH",time());
				$tnum = time();
				$xml1 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
				$xml1 .= "<Resources>\n";
				$xml1 .="<projid>".$spid."</projid>\n";
				$xml1 .="<versioncode>".$tnum."</versioncode>\n";
				$xml1 .="<url>".$jszip."appresources.zip</url>\n";
				$xml1 .="</Resources>\n";
				$fp=fopen("$sjxml1","w");
				fwrite($fp,$xml1);
				@fclose($fp);
				//更新升级xml文件
				$sjxml = '/home/wwwroot/default/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/cache/datanew/version.xml';
				$jszip = 'http://www.go3c.tv:8060/upgrade/android/'.$tream.'/'.$spid.'/'.$sbd.'/Recommon_Data/';
				self::appresources($spid,$sbd,$sjxml,$jszip);
			}
		$msg = '生成成功!';
		showmessage($msg,'?m=go3c&c=shop&a=rdapp_list&pc_hash='.$_SESSION['pc_hash'], $ms = 500);
	}
	//应用外链
	public function link_list(){
		$this->app_link = pc_base::load_model('app_link_model');
		$this->cms_spid = pc_base::load_model('cms_spid_model');
		if($_SESSION['roleid']=='1'){
			$awhere = "";
		}else{
			$awhere = " spid in ('".$_SESSION['spid']."')";
		}
		$sp_list = $this->cms_spid->select($awhere);
		$title   	= trim($_GET['title']);
		$spid   	= trim($_GET['spid']);
		$statue   	= trim($_GET['statue']);
		$title = $title ? " AND title = '".$title."'" : '';
		$field    	= '*';
		$sql     	= "app_link WHERE 1 ";
		if($_SESSION['roleid']=='1'){	//超级管理员
			if($spid){
				$sql .= " AND spid = '".$spid."'";
			}else{
				$sql .= " ";
			}
		}else{
			if($spid){
				$sql .= " AND spid = '".$spid."'";
			}else{
				$sql .= " AND spid in ('".$_SESSION['spid']."') ";
			}
		}
		if($title){
			$sql .= " AND title = '".$title."'";
		}
		if($statue){
			$sql .= " AND statue = '".$statue."'";
		}
		$order  	= '';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->app_link->mynum($sql);
		$totalpage	= $this->app_link->mytotalpage($sql, $perpage);
		$infor_list = $this->app_link->mylistinfo($field, $sql, $order, $page, $perpage);
		$multipage  = $this->app_link->pages;
		include $this->admin_tpl('applink_list');
	}
	//更新升级xml文件
	public function appresources($spid,$sbd,$sjxml,$jszip){
		$version = date("YmdH",time());
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml .= "<upgrade>\n";
		$xml .= "<code>1</code>\n";
		$xml .="<description>\n";
		$xml .="初始化数据\n";
		$xml .="</description>\n";
		$xml .="<versioncode>".$version."</versioncode>\n";
		$xml .="<must>0</must>\n";
		$xml .="<size>1234</size>\n";
		$xml .="</upgrade>\n";
		$fp=fopen("$sjxml","w");
		fwrite($fp,$xml);
		@fclose($fp);
		//生成zip压缩包
		$zipurl = 'http://www.go3c.tv:8060/go3ccms/zip_php.php?spid='.$spid.'&board_type='.$sbd;
		$tmp2 = file_get_contents($zipurl);
	}
	//计算文件夹大小
	
	//应用外链申请审核
	public function online_applink(){
		$this->app_link = pc_base::load_model('app_link_model');
		$id   	= trim($_GET['id']);
		if(empty($id)){
			showmessage('数据异常！',base64_decode($_GET['goback']));
		}else{
			$this->app_link->update(array('statue'=>2),array('id'=>$id));
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	//应用外链通过审核
	public function pass_applink(){
		$this->app_link = pc_base::load_model('app_link_model');
		$id   	= trim($_GET['id']);
		if(empty($id)){
			showmessage('数据异常！',base64_decode($_GET['goback']));
		}else{
			$this->app_link->update(array('statue'=>3),array('id'=>$id));
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	//应用外链拒绝通过审核
	public function refuse_applink(){
		$this->app_link = pc_base::load_model('app_link_model');
		$id   	= trim($_GET['id']);
		if(empty($id)){
			showmessage('数据异常！',base64_decode($_GET['goback']));
		}else{
			$this->app_link->update(array('statue'=>1),array('id'=>$id));
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	//应用外链发布
	public function release_applink(){
		$this->app_link = pc_base::load_model('app_link_model');
		$id   	= trim($_GET['id']);
		if(empty($id)){
			showmessage('数据异常！',base64_decode($_GET['goback']));
		}else{
			$this->app_link->update(array('statue'=>4),array('id'=>$id));
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	//应用外链下线
	public function offline_applink(){
		$this->app_link = pc_base::load_model('app_link_model');
		$id   	= trim($_GET['id']);
		if(empty($id)){
			showmessage('数据异常！',base64_decode($_GET['goback']));
		}else{
			$this->app_link->update(array('statue'=>1),array('id'=>$id));
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
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
	//审核apk列表页
	public function shoponline(){
		$admin_username = param::get_cookie('admin_username');
		$type_name_list = $this->db->select('level = 2', 'cat_id, cat_name', '', 'cat_id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$this->app = pc_base::load_model('app_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND app_name LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "app WHERE status=2 ".$title_filt;
		//查询处理 start 
		$type = 0;
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$status = trim($_GET['status']);
			if(!empty($type) && $type)
			{
				$sql .= " AND channel_cat_id = '".$type."'";
			}
		}
		$order  	= 'ORDER BY app_id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->app->mynum($sql);
		$totalpage	= $this->app->mytotalpage($sql, $perpage);
		$data 		= $this->app->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->app->pages;
		include $this->admin_tpl('shoponline_list');
	}
	//apk单个上线
	public function shop_agree(){
		$id = $_GET['id'];
		$this->app = pc_base::load_model('app_model');
		$this->app_onlinelog = pc_base::load_model('app_onlinelog_model');
		$this->app_image = pc_base::load_model('app_image_model');
		$this->app_download = pc_base::load_model('app_download_info_model');
		$this->app_all_list = pc_base::load_model('app_all_list_model');
		$appd = $this->app->get_one(array('app_id'=>$id));
		$appimage = $this->app_image->get_one(array('app_id'=>$id,'image_type'=>'102'));
		$appload = $this->app_download->get_one(array('app_id'=>$id));
		if(empty($appimage)||empty($appload)){
			showmessage('抱歉,最少要包含一张图片和apk文件!',$_SERVER['HTTP_REFERER'], $ms = 500);
		}
		//组合接口数据
		$data = array(
				'app_id' => $appd['app_id'],
				'app_name' => $appd['app_name'],
				'spell' => $appd['spell'],
				'app_desc' => $appd['app_desc'],
				'owner' => $appd['owner'],
				'file_size' => $appd['file_size'],
				'score' => $$appd['score'],
				'channel_cat_id' => $appd['channel_cat_id'],
				'channel' => $appd['channel'],
				'packagename' => $appd['packagename'],
				'version' => $appd['version'],
				'type' => $appd['type'],
				'term_id' => $appload['term_id'],
				'download_count' => $appd['download_count'],
				'release_date' => date("Y-m-d",time()),
				'image_file' => $appimage['image_file'],
				'install_file' => $appload['install_file']
		);
		$this->app_all_list->insert($data);
		
		$this->app->update(array('status'=>4), array('app_id'=>$id));
		//上线操作记录上线日志
		$dalog = array(
			'app_id'=>$appd['app_id'],
			'sid'=>$appd['sid'],
			'packagename'=>$appd['packagename'],
			'status'=>'on',
			'reason'=>'同意上线',
			'createtime'=>time()
		);
		$this->app_onlinelog->insert($dalog);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//apk单个拒绝上线
	public function shop_refuse(){
		$id = $_REQUEST['id'];
		$this->app = pc_base::load_model('app_model');
		$this->app->update(array('status'=>1), array('app_id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//apk批量上线
	public function shop_agreeto(){
		$this->app = pc_base::load_model('app_model');
		$this->app_image = pc_base::load_model('app_image_model');
		$this->app_onlinelog = pc_base::load_model('app_onlinelog_model');
		$this->app_download = pc_base::load_model('app_download_info_model');
		$this->app_all_list = pc_base::load_model('app_all_list_model');
		$ids=explode(',', $_GET['app_id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$appd = $this->app->get_one(array('app_id'=>$v));
				$appimage = $this->app_image->get_one(array('app_id'=>$v,'image_type'=>'102'));
				$appload = $this->app_download->get_one(array('app_id'=>$v));
				if(empty($appimage)||empty($appload)){
					showmessage('抱歉,最少要包含一张图片和apk文件!',$_SERVER['HTTP_REFERER'], $ms = 500);
				}
				//判断此应用是否已经上线
				$app_on = $this->app_all_list->get_one(array('app_id'=>$v));
				if(!empty($app_on)){
					continue;
				}
				//组合接口数据
				$data = array(
						'app_id' => $appd['app_id'],
						'app_name' => $appd['app_name'],
						'spell' => $appd['spell'],
						'app_desc' => $appd['app_desc'],
						'owner' => $appd['owner'],
						'file_size' => $appd['file_size'],
						'score' => $$appd['score'],
						'channel_cat_id' => $appd['channel_cat_id'],
						'channel' => $appd['channel'],
						'packagename' => $appd['packagename'],
						'version' => $appd['version'],
						'type' => $appd['type'],
						'term_id' => $appload['term_id'],
						'download_count' => $appd['download_count'],
						'release_date' => date("Y-m-d",time()),
						'image_file' => $appimage['image_file'],
						'install_file' => $appload['install_file']
				);
				$this->app_all_list->insert($data);
				$this->app->update(array('status'=>4), array('app_id'=>$v));
				//上线操作记录上线日志
				$dalog = array(
					'app_id'=>$appd['app_id'],
					'sid'=>$appd['sid'],
					'packagename'=>$appd['packagename'],
					'status'=>'on',
					'reason'=>'同意上线',
					'createtime'=>time()
				);
				$this->app_onlinelog->insert($dalog);
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	//apk批量拒绝
	public function shop_refuseto(){
		$this->app = pc_base::load_model('app_model');
		$ids=explode(',', $_GET['app_id']);
		if(empty($ids)){
			showmessage('你还没有选择任何内容！',base64_decode($_GET['goback']));
		}else{
			foreach ($ids as $v){
				$this->app->update(array('status'=>1), array('app_id'=>$v));
			}
			showmessage('操作成功',base64_decode($_GET['goback']));
		}
	}
	
	//教育类apk应用列表
	public function shop_edulist(){
		$type_name_list = $this->db->select('level = 2', 'cat_id, cat_name', '', 'cat_id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$this->app = pc_base::load_model('app_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND app_name LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "app WHERE type='game' ".$title_filt;
		//查询处理 start 
		$type = 0;
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$status = trim($_GET['status']);
			if(!empty($type) && $type)
			{
				$sql .= " AND channel_cat_id = '".$type."'";
			}
			if(!empty($status) && $type != '全部')
			{
				$sql .= " AND status = '".$status."'";
			}
		}
		$order  	= 'ORDER BY app_id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->app->mynum($sql);
		$totalpage	= $this->app->mytotalpage($sql, $perpage);
		$data 		= $this->app->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->app->pages;
		include $this->admin_tpl('shop_edulist');
	}
	
	public function sho_eduverify(){
		$admin_username = param::get_cookie('admin_username');
		$type_name_list = $this->db->select('level = 2', 'cat_id, cat_name', '', 'cat_id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['cat_id']]=$gvalue['cat_name'];
		}
		$this->app = pc_base::load_model('app_model');
		$title   	= trim($_GET['title']);
		$title_filt = $title ? " AND app_name LIKE '%$title%'" : '';
		$field    	= '*';
		$sql     	= "app WHERE status=2 ".$title_filt;
		//查询处理 start 
		$type = 0;
		if(!empty($_GET['search']))
		{
			//任务(推荐位)			
			$type = trim($_GET['type']);
			$status = trim($_GET['status']);
			if(!empty($type) && $type)
			{
				$sql .= " AND channel_cat_id = '".$type."'";
			}
		}
		$order  	= 'ORDER BY app_id DESC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 15;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->app->mynum($sql);
		$totalpage	= $this->app->mytotalpage($sql, $perpage);
		$data 		= $this->app->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->app->pages;
		include $this->admin_tpl('eduverify_onlinelist');
	}
	
	
}
?>