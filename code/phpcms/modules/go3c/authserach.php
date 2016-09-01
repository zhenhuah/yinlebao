<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('admin', 'admin', 0);

class authserach extends admin {
	function __construct() {
		parent::__construct();
		//pc_base::load_app_func('global_task');	//方法文件
		
		$this->video_db = pc_base::load_model('cms_video_model');			//视频数据
		$this->tags_db = pc_base::load_model('cms_tags_model');			//tags 标签
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
梁工，第一个还是建议调这个“ 分集所在的总集vid”，所在页面见截图。
弹出窗口能够有一个输入框和搜索按钮，搜索的sql如下：
SELECT asset_id,title FROM `phpcms`.`v9_video` where (column_id=3 or column_id=4) and ispackage=1 and title like '%故事%'
然后编辑通过展示的title列表，选择一个视频总集后，将asset_id回填入输入框
	*/
	/*
	* 视频列表 
	*/
	public function video() {	
		//任务查询处理
		$mode = trim($_GET['mode']);
		$where = ' (column_id=3 or column_id=4) and ispackage=1 ';
		if($mode == 'query'){
			//视频名称
			$videoTitle = trim($_GET['title']);
			if(!empty($videoTitle)){
				$where .= " and title LIKE '%".$videoTitle."%'";
				$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
				$video_list = $this->video_db->listinfo($where, $order = ' `id` DESC', $page, $pagesize = 10);	
				$pages = $this->video_db->pages;
			}
		}
		
		/*
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$video_list = $this->video_db->listinfo($where, $order = ' `id` DESC', $page, $pagesize = 10);			
		$pages = $this->video_db->pages;
		*/

		include $this->admin_tpl('auth_video_list');	
	}

	/*
	* 视频查询 
	*/
	public function serach() {
		$q = '';
		if (isset($_GET['q'])) {
			$q = trim($_GET['q']);
		}
		if (empty($q)) {
			return;
		}
		
		if(!empty($q))
		{
			//视频名称
			$videoTitle = $q;
			$where .= " (column_id=3 or column_id=4) and ispackage=1 and title LIKE '%".$videoTitle."%'";
			$items = $this->video_db->listinfo($where, $order = ' `id` DESC');
			//$pages = $this->video_db->pages;
		}

		/*
		$items = array(
			//中文 开始
			'汉语(普通话)' => '1',
			'粤语' => '2',
			'闽南语' => '3',
			'沪语' => '4',
			'英语' => '5',
			'法语' => '6',
			'韩语' => '7',
			'日语' => '8',
			'德语' => '9',
			'俄语' => '10',
			'西班牙语' => '11',
			'葡萄牙语' => '12',
			'意大利语' => '13',
			'阿拉伯语' => '14',
			'印地语' => '15'
		);
		*/

		//asset_id,title
		if(!empty($items))
		{
			foreach ($items as $key => $value) {
				//if (strpos(strtolower($key), $q) !== false) {
				//	echo "$key|$value\n";
				//}
				echo $value['title']."|".$value['asset_id']."\n";
			}	
		}
	}

	//Tags 标签处理
	/*
	要求有
	a 查询语句
	SELECT title FROM `v9_tags` where type=2 and belong like '%3%'
	其中 %%中间的值可以为3，4，5，6，具体的值要看

	这里当前选择的是哪项， 电视栏目对应是3，电视剧对应4，电影对应5，乐酷对应6


	b 查询得到众多标签列表后，一定要可以多选
	c 将多选得到的标签，用逗号(,)连接之后，添回到 TAG标签 输入框内，如果原输入框内有内容，则用逗号连接，接在原内容之后
	例如  悬疑,爱情,冒险
	*/

	function tags()
	{		
		$mode = trim($_GET['mode']);
		$where = ' type=2  ';
		if($mode == 'query'){
			//tags类型处理
			$tagsType = trim($_GET['tagsType']);
			if(!empty($tagsType)){
				$where .= " and belong LIKE '%".$tagsType."%'";	
				
				$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
				$tags_list = $this->tags_db->listinfo($where, $order = ' `id` DESC', $page, $pagesize = 80);	
				$pages = $this->tags_db->pages;
			}
			/*
			//tags关键词查询
			$tagsKeywords = trim($_GET['tagsKeywords']);
			if(!empty($tagsKeywords)){
				$where .= " and title LIKE '%".$tagsKeywords."%'";				
			}
			*/			
		}
		/*
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$tags_list = $this->tags_db->listinfo($where, $order = ' `id` DESC', $page, $pagesize = 10);
		$pages = $this->tags_db->pages;
		*/
		include $this->admin_tpl('auth_tags_list');
	}

}//end class