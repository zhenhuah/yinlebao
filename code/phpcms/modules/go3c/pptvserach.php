<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);

class pptvserach extends admin {
	
	/**
	 * 构造函数
	 *
	 */
	function __construct() {
		parent::__construct();
		//加载公共函数
		pc_base::load_app_func('global');
		$this->video_db = pc_base::load_model('cms_video_model');		//视频数据
		$this->tags_db = pc_base::load_model('cms_tags_model');			//tags 标签
		$this->spid_db = pc_base::load_model('admin_model');			//后台登录表连接

		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}

	/*
	* PPTV视频搜索
	*/
	public function video(){
		$perpage  = !empty($_GET['perpage']) ? $_GET['perpage'] 	: 10 ;
		$page     = !empty($_GET['page'])    ? $_GET['page'] 	: 1 ;
		$keyword  = !empty($_GET['keyword']) ? $_GET['keyword']		: '' ;
		if($keyword){
			$api_url    = "http://shipin.pptv.com/api/kw=$keyword/cur_page=$page/page_size={$perpage}/search.xml";
			$xmlString  = file_get_contents($api_url);
			$xmlString  = str_replace('<![CDATA[','',$xmlString);
			$xmlString  = str_replace(']]>','',$xmlString);
			$data_list  = xml_to_array($xmlString);
			foreach ($data_list['document']['items']['item'] as $value) {
				if($value['id']){
					$video_list[] = $value;
				}
			}
			array_sort($video_list,'update_time');
		}
		
		//echo '<pre>';print_r($data_list);
		//echo '<pre>';print_r($video_list);
		
		//翻页
		$total_num = $data_list['document']['total'];
		
		if($total_num%$perpage){
		   $page_count=(int)($total_num/$perpage)+1;  //计算页数最大值，如果数据量不能整除每页显示量，则页数应加1
		}else{ 
		   $page_count=$total_num/$perpage;           //如果数据量能整除每页显示量，页数直接为计算结果
		}
		if($page > 0) {
			$prepage =$page-1;              //页码值减1
		}
		if( $page < $page_count){
			$nextpage=$page+1;             //页码值加1
		}
		
		include $this->admin_tpl('pptv_video_list');
	}


	/*
	* XML接口搜索
	*/
	function api_search($api_url){
		if(empty($api_url)){
			return false;
		}else{
			//处理接口获取xml文件字符串
			$xmlString = file_get_contents($api_url);
			//移除xml保护符
			$xmlString = str_replace('<![CDATA[','',$xmlString);
			$xmlString = str_replace(']]>','',$xmlString);
			$searchArray = xml_to_array($xmlString);
			return $searchArray;
		}
	}
	
	
	/*
	* 视频查询
	*/
	public function serach() {
		exit;
		$q = '';
		if (isset($_GET['q'])) {
			$q = trim($_GET['q']);
		}
		if (empty($q)) {
			return;
		}

		if(!empty($q)){
			//视频名称
			$videoTitle = $q;
			$where .= " (column_id=3 or column_id=4) and ispackage=1 and title LIKE '%".$videoTitle."%'";
			$items = $this->video_db->listinfo($where, $order = ' `id` DESC');
		}
		if(!empty($items))
		{
			foreach ($items as $key => $value) {
				echo $value['title']."|".$value['asset_id']."\n";
			}
		}
	}

}//end class