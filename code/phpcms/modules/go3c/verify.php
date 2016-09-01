<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class verify extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	public function init() {
		
	}
	
	/**
	 * 视频上线
	 *
	 */
	public function online() {
		$this->db = pc_base::load_model('cms_video_model');
		//过滤条件
		$status   = isset($_GET['status']) ? intval($_GET['status']) : '';
		$column_id = isset($_GET['column_id']) ? intval($_GET['column_id']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		$asset_id = strip_tags(trim($_GET['asset_id']));
		//根据cms用户权限筛选区域
		$this->admin_model = pc_base::load_model('admin_model');
		$this->cms_area = pc_base::load_model('cms_area_model');
		$this->cms_video_area_model = pc_base::load_model('cms_video_area_model');
		$userid = $_SESSION['userid'];
		$ad_area = $this->admin_model->get_one(array('userid'=>$userid));
		$area_id = $ad_area['area_id']?$ad_area['area_id']:'01';
		$ad_area_le = $this->cms_area->get_one(array('id'=>$area_id)); //查询管理员区域级别(省市县)

		if($ad_area['roleid']!='1' || $area_id!='01'){  //区域筛选排除超级管理员和全网
			$area_id = $ad_area_le['id'];
			$video_id = $this->cms_video_area_model->select("area_id like '%$area_id%'", 'distinct(asset_id)', '', 'asset_id ASC');
			//$channel = self::unique_arr($channel,true);
			foreach($video_id as $key=>$value)
			{
  				$b[$key]=$value['asset_id'];
			}
			$video_id = join(',',$b);
			$where = "1 AND `published` = 0 AND `online_status` = 10 AND spid = '".$this->current_spid[spid]."' and asset_id in ('$video_id') ";
		}else{
			$where = " 1 AND `published` = 0 AND `online_status` = 10 AND spid = '".$this->current_spid[spid]."' ";
		}
		//构造SQL
		$where = " 1 AND `published` = 0 AND `online_status` = 10 AND spid = '".$this->current_spid[spid]."'";
		$status   != '' ? $where.= " AND `status` = '$status'" : '';
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		$asset_id != '' ? $where.= " AND `asset_id` LIKE '%$asset_id%'" : '';
		$column_id   != '' ? $where.= " AND `column_id` = '$column_id'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->db->pages;
		//获取栏目类型
		$this->cms_column = pc_base::load_model('cms_column_model');
		$cms_column = $this->cms_column->select(array('active'=>1));
		include $this->admin_tpl('verify_online_list');
	}

	/**
	 * 视频下线
	 *
	 */
	public function offline() {
		$this->db = pc_base::load_model('cms_video_model');
		//过滤条件
		$status   = isset($_GET['status']) ? intval($_GET['status']) : '';
		$column_id = isset($_GET['column_id']) ? intval($_GET['column_id']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		$asset_id = strip_tags(trim($_GET['asset_id']));
		//构造SQL
		$where = " 1  AND `published` = 1 AND `offline_status` = 1 AND spid = '".$this->current_spid[spid]."'";
		$status   != '' ? $where.= " AND `status` = '$status'" : '';
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		$asset_id != '' ? $where.= " AND `asset_id` = '$asset_id'" : '';
		$column_id   != '' ? $where.= " AND `column_id` = '$column_id'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->db->pages;
		//获取栏目类型
		$this->cms_column = pc_base::load_model('cms_column_model');
		$cms_column = $this->cms_column->select(array('active'=>1));
		include $this->admin_tpl('verify_offline_list');
	}
	
	/**
	 * 视频删除
	 *
	 */
	public function delete() {
		$this->db = pc_base::load_model('cms_video_model');
		//过滤条件
		$status   = isset($_GET['status']) ? intval($_GET['status']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		$asset_id = strip_tags(trim($_GET['asset_id']));
		$column_id = isset($_GET['column_id']) ? intval($_GET['column_id']) : '';
		//构造SQL
		$where = " 1 AND `published` = 0 AND `online_status` = 20 AND spid = '".$this->current_spid[spid]."'";
		$status   != '' ? $where.= " AND `status` = '$status'" : '';
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		$asset_id != '' ? $where.= " AND `asset_id` = '$asset_id'" : '';
		$column_id   != '' ? $where.= " AND `column_id` = '$column_id'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		$pages = $this->db->pages;
		//获取栏目类型
		$this->cms_column = pc_base::load_model('cms_column_model');
		$cms_column = $this->cms_column->select(array('active'=>1));
		include $this->admin_tpl('verify_delete_list');
	}

	/**
	 * 推荐
	 *
	 */
	public function position() {
		$term_id 	= intval($_GET['term_id']);
		$term_filt  = $term_id ? " AND p.term_id = '$term_id'" : '';
		$this->db   = pc_base::load_model('position_model');		
		$field    	= 'p.posid, p.name, p.term_id, p.type_id, tt.title AS termtype, pt.title AS postype ';
		$sql     	= "
			v9_position AS p 
			INNER JOIN v9_postion_type pt ON pt.id = p.type_id
			INNER JOIN v9_term_type tt ON p.term_id = tt.id
			WHERE 1
			AND online_status = '10' 
			AND spid = '".$this->current_spid[spid]."' "; 
		$order  	= 'ORDER BY p.posid ASC';
		$perpage 	= 10;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		$multipage  = $this->db->pages;
		//echo '<pre>';print_r($infos);
		include $this->admin_tpl('verify_position_list');	
	}
	
	/**
	 * 栏目设置
	 *
	 */
	public function category() {
		$this->belong_type = pc_base::load_model('cms_column_model');
		$belong_type_list = $this->belong_type->select('', 'id,title', '', 'id ASC');
		$belong_type_array = array();
		foreach($belong_type_list as $pvalue){
			if(intval($pvalue['id']) > 2 )
			$belong_type_array[$pvalue['id']]=$pvalue['title'];
		}
	
		$this->db = pc_base::load_model('cms_tags_model');
		//过滤条件
		$type   = isset($_GET['type']) ? intval($_GET['type']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		//构造SQL
		$where = " 1 AND ( `online_status` = 10 OR `online_status` = 20 )";
		$type   != '' ? $where.= " AND `type` = '$type'" : '';
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` ASC', $page, $pagesize = 15);
		$pages = $this->db->pages;
		include $this->admin_tpl('verify_category_list');
	}

	/**
	 *视频通过上线申请
	 *
	 */
	public function online_pass() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>11,'offline_status'=>0), array('id'=>$id));
		//添加操作日志
		$type = 'online_pass';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 *视频驳回上线申请
	 *
	 */
	public function online_refuse() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>12), array('id'=>$id));
		//添加操作日志
		$type = 'online_refuse';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 *视频通过下线申请
	 *
	 */
	public function offline_pass() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('offline_status'=>2), array('id'=>$id));
		//添加操作日志
		$type = 'offline_pass';
		$this->go3ccms_changlog($id,$type,$id);
		showmessage('操作成功',base64_decode($_GET['goback']));

	}

	/**
	 *视频驳回下线申请
	 *
	 */
	public function offline_refuse() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('offline_status'=>0), array('id'=>$id));
		//添加操作日志
		$type = 'offline_refuse';
		$this->go3ccms_changlog($id,$type,$id);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 *视频通过删除申请
	 *

	 */
	public function delete_pass() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>21), array('id'=>$id));
		//添加操作日志
		$type = 'delete_pass';
		$this->go3ccms_changlog($id,$type,$id);
		showmessage('操作成功',base64_decode($_GET['goback']));

	}

	/**
	 *视频驳回删除申请
	 *

	 */
	public function delete_refuse() {
		$this->db = pc_base::load_model('cms_video_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>2), array('id'=>$id));
		//添加操作日志
		$type = 'delete_refuse';
		$this->go3ccms_changlog($id,$type,$id);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 *推荐位通过推荐审核
	 *

	 */
	public function recomm_pass() {
		$this->db   = pc_base::load_model('cms_position_model');
		$posid = intval($_GET['posid']);
		$this->db->update(array('published'=>'0', 'online_status'=>'11'),array('posid'=>$posid));
		showmessage('操作成功',base64_decode($_GET['goback']));

	}

	/**
	 *推荐位驳回推荐审核
	 *

	 */
	public function recomm_refuse() {
		$this->db   = pc_base::load_model('cms_position_model');
		$posid = intval($_GET['posid']);
		$this->db->update(array('published'=>'0', 'online_status'=>'1'),array('posid'=>$posid));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	/**
	 *视频属性 通过上线申请
	 *
	 */
	public function category_pass() {
		$this->db = pc_base::load_model('cms_tags_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>11,'offline_status'=>0), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	/**
	 * 视频属性 批量通过上线申请 
	 *

	 */
	public function category_pass_all() {
		if(empty($_POST['ids'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_tags_model');
		$ids = $_POST['ids'] ;
		if(!is_array($ids))  showmessage('没有任何需要改变的') ;
		$this->db->update(array('online_status'=>11,'offline_status'=>0),'id in ('. join(',',$ids).')');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 *视频属性 驳回上线申请
	 *
	 */
	public function category_refuse() {
		$this->db = pc_base::load_model('cms_tags_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>12), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 视频属性 批量驳回上线申请
	 *
	 */
	public function category_refuse_all() {
		if(empty($_POST['ids'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_tags_model');
		$ids = $_POST['ids'] ;
		if(!is_array($ids))  showmessage('没有任何需要改变的') ;
		
		$this->db->update(array('online_status'=>2),'id in ('. join(',',$ids).')');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	
	/**
	 * 视频 批量通过删除申请
	 *
	 */
	public function delete_pass_all() {
		if(empty($_GET['id'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_video_model');
		$ids = $_GET['id'] ;		
		$this->db->update(array('online_status'=>21),'id in ('. $ids.')');
		//添加操作日志
		$type = 'delete_pass';
		$this->go3ccms_changlog($ids,$type,$ids);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 视频 批量驳回删除申请
	 *
	 */
	public function delete_refuse_all() {
		if(empty($_GET['id'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_video_model');
		$ids = $_GET['id'] ;
		
		$this->db->update(array('online_status'=>2),'id in ('.$ids.')');
		//添加操作日志
		$type = 'delete_refuse';
		$this->go3ccms_changlog($ids,$type,$ids);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 视频 批量通过下线申请
	 *
	 */
	public function offline_pass_all() {
		if(empty($_POST['ids'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_video_model');
		$ids = $_POST['ids'] ;
		if(!is_array($ids))  showmessage('没有任何需要改变的') ;
		
		$this->db->update(array('offline_status'=>2),'id in ('. join(',',$ids).')');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 视频 批量驳回下线申请
	 *
	 */
	public function offline_refuse_all() {
		if(empty($_POST['ids'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_video_model');
		$ids = $_POST['ids'] ;
		if(!is_array($ids))  showmessage('没有任何需要改变的') ;
		
		$this->db->update(array('offline_status'=>0),'id in ('. join(',',$ids).')');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 视频 批量通过上线申请
	 *

	 */
	public function online_pass_all() {
		if(empty($_POST['ids'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_video_model');
		$ids = $_POST['ids'] ;
		if(!is_array($ids))  showmessage('没有任何需要改变的') ;
		
		$this->db->update(array('online_status'=>11,'offline_status'=>0),'id in ('. join(',',$ids).')');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 视频 批量驳回上线申请
	 *
	 */
	public function online_refuse_all() {
		if(empty($_POST['ids'])) showmessage('没有任何需要改变的') ;
		$this->db = pc_base::load_model('cms_video_model');
		$ids = $_POST['ids'] ;
		if(!is_array($ids))  showmessage('没有任何需要改变的') ;
		
		$this->db->update(array('online_status'=>2),'id in ('. join(',',$ids).')');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	

	
	
	/**
	 * 客户端上线
	 *
	 */
	public function client_online() {
		//终端类型
		$this->term_type = pc_base::load_model('term_type_model');
		$term_type_list = $this->term_type->select('', 'id,title', '', 'id ASC');
		foreach($term_type_list as $pvalue){
			$term_type_array[$pvalue['id']]=$pvalue['title'];
		}
		$this->db = pc_base::load_model('cms_client_version_model');
		//构造SQL
		$where = " 1 AND `online_status` = '10' AND `published` = '0' ";
		$online_status   = isset($_GET['online_status']) ? intval($_GET['online_status']) : '';
		$title    		 = strip_tags(trim($_GET['title']));
		$term_id         = intval($_GET['term_id']);
		$os_type         = intval($_GET['os_type']);
		$online_status	 ? $where.= " AND `online_status` = '$online_status'" : '';
		$term_id	 	 ? $where.= " AND `term_id` = '$term_id'" : '';
		$os_type   != '' ? $where.= " AND `os_type` = '$os_type'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $pagesize = 15);
		//echo '<pre>'; print_r($data);
		$pages = $this->db->pages;
		include $this->admin_tpl('verify_client_online');
	}
	
	/**
	 * 客户端删除
	 *
	 */
	public function client_delete() {
		//终端类型
		$this->term_type = pc_base::load_model('term_type_model');
		$term_type_list = $this->term_type->select('', 'id,title', '', 'id ASC');
		foreach($term_type_list as $pvalue){
			$term_type_array[$pvalue['id']]=$pvalue['title'];
		}
		$this->db = pc_base::load_model('cms_client_version_model');
		//构造SQL
		$where = " 1 AND  `online_status` = '20' ";
		$online_status   = isset($_GET['online_status']) ? intval($_GET['online_status']) : '';
		$title    		 = strip_tags(trim($_GET['title']));
		$term_id         = intval($_GET['term_id']);
		$os_type         = intval($_GET['os_type']);
		$online_status	 ? $where.= " AND `online_status` = '$online_status'" : '';
		$term_id	 	 ? $where.= " AND `term_id` = '$term_id'" : '';
		$os_type   != '' ? $where.= " AND `os_type` = '$os_type'" : '';
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $pagesize = 15);
		//echo '<pre>'; print_r($data);
		$pages = $this->db->pages;
		include $this->admin_tpl('verify_client_delete');
	}
	
	
	/**
	 * 申请上线 通过
	 *
	 */
	public function client_online_pass() {
		$this->db = pc_base::load_model('cms_client_version_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>11), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 申请删除 通过
	 *
	 */
	public function client_delete_pass() {
		$this->db = pc_base::load_model('cms_client_version_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>21), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**
	 * 申请上线 拒绝

	 *
	 */
	public function client_online_refuse() {
		$this->db = pc_base::load_model('cms_client_version_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>12), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}

	/**

	 * 申请删除 拒绝

	 *
	 */
	public function client_delete_refuse() {
		$this->db = pc_base::load_model('cms_client_version_model');
		$id = intval($_GET['id']);
		$this->db->update(array('online_status'=>2), array('id'=>$id));
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	
	
	/**
	 * 数据同步前清空老数据
	 *
	 */
	public function pre_sync_area() {
		$this->db = pc_base::load_model('tv_column_content_area_model');
		$this->db->query("truncate table column_content_area");
		showmessage('正在清空老数据，下面开始同步数据到前端','?m=go3c&c=verify&a=sync_area',$ms = 500);
	}
	
	/**
	 * 数据同步
	 *
	 */
	public function sync_area() {

		$this->db  = pc_base::load_model('cms_tags_model');		
		$this->db2 = pc_base::load_model('tv_column_content_area_model');		
		$field    	= '*';
		$sql     	= "v9_tags WHERE type = '1'"; 
		$order  	= 'ORDER BY id ASC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 1;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		
		if($page < $totalpage+1){
			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $multipage.' ';
			
			$actor_array = array();
			$col_id_array = explode(',', $data_array[0]['belong']);
			foreach ($col_id_array AS $col_value){
				$actor_array[] = array('col_id'=>$col_value, 'area_id'=>$data_array[0]['id'], 'area_name'=>$data_array[0]['title']);
			}
			
			/**
			 * 插入前端代码开始
			 */
			foreach ($actor_array as $value) {
				if($value['col_id']){
					$this->db2->insert($value, true);
				}
			}
			/**
			 * 插入前端代码结束
			 */
			//echo '<pre>';print_r($actor_array);
			echo '正在同步...';
			//exit;
			
			$next_page = $page + 1;
			page_jump('go3c', 'verify', 'sync_area', $next_page);
		}else{
			showmessage(L('operation_success'),'?m=go3c&c=verify&a=category',$ms = 500);
		}
		
	}
	
	
	
	
	/**
	 * 数据同步前清空老数据
	 *
	 */
	public function pre_sync_year() {
		$this->db = pc_base::load_model('tv_column_content_year_model');
		$this->db->query("truncate table column_content_year");
		showmessage('正在清空老数据，下面开始同步数据到前端','?m=go3c&c=verify&a=sync_year',$ms = 500);
	}
	
	/**
	 * 数据同步
	 *
	 */
	public function sync_year() {

		$this->db  = pc_base::load_model('cms_tags_model');		
		$this->db2 = pc_base::load_model('tv_column_content_year_model');		
		$field    	= '*';
		$sql     	= "v9_tags WHERE type = '3'"; 
		$order  	= 'ORDER BY id ASC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 1;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		
		if($page < $totalpage+1){
			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $multipage.' ';
			
			$actor_array = array();
			$col_id_array = explode(',', $data_array[0]['belong']);
			foreach ($col_id_array AS $col_value){
				$actor_array[] = array('col_id'=>$col_value, 'seq_number'=>$data_array[0]['id'], 'year_released'=>$data_array[0]['title']);
			}
			
			/**
			 * 插入前端代码开始
			 */
			foreach ($actor_array as $value) {
				if($value['col_id']){
					$this->db2->insert($value, true);
				}
			}
			/**
			 * 插入前端代码结束
			 */
			//echo '<pre>';print_r($actor_array);
			echo '正在同步...';
			//exit;
			
			$next_page = $page + 1;
			page_jump('go3c', 'verify', 'sync_year', $next_page);
		}else{
			showmessage(L('operation_success'),'?m=go3c&c=verify&a=category',$ms = 500);
		}
		
	}
	
	
	
	
	
	/**
	 * 数据同步前清空老数据
	 *
	 */
	public function pre_sync_category() {
		$this->db = pc_base::load_model('tv_column_content_category_model');
		$this->db->query("truncate table column_content_category");
		showmessage('正在清空老数据，下面开始同步数据到前端','?m=go3c&c=verify&a=sync_category',$ms = 500);
	}
	
	/**
	 * 数据同步
	 *
	 */
	public function sync_category() {

		$this->db  = pc_base::load_model('cms_tags_model');		
		$this->db2 = pc_base::load_model('tv_column_content_category_model');		
		$field    	= '*';
		$sql     	= "v9_tags WHERE type = '2'"; 
		$order  	= 'ORDER BY id ASC';
		$perpage    = isset($_GET['perpage']) && intval($_GET['perpage']) ? intval($_GET['perpage']) : 1;
		$page    	= isset($_GET['page'])    && intval($_GET['page'])    ? intval($_GET['page'])    : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		
		if($page < $totalpage+1){
			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $multipage.' ';
			
			$actor_array = array();
			$col_id_array = explode(',', $data_array[0]['belong']);
			foreach ($col_id_array AS $col_value){
				$actor_array[] = array('col_id'=>$col_value, 'cat_id'=>$data_array[0]['id'], 'cat_name'=>$data_array[0]['title']);
			}
			
			/**
			 * 插入前端代码开始
			 */
			foreach ($actor_array as $value) {
				if($value['col_id']){
					$this->db2->insert($value, true);
				}
			}
			/**
			 * 插入前端代码结束
			 */
			//echo '<pre>';print_r($actor_array);
			echo '正在同步...';
			//exit;
			
			$next_page = $page + 1;
			page_jump('go3c', 'verify', 'sync_category', $next_page);
		}else{
			showmessage(L('operation_success'),'?m=go3c&c=verify&a=category',$ms = 500);
		}
		
	}
	
	//批量拒绝上线
	public function delete_allto(){
		$this->db = pc_base::load_model('cms_video_model');
		$ids=$_GET['id'];
		$this->db->update(array('online_status'=>2),'id in ('.$ids.')');
		//添加操作日志
		$type = 'online_refuse';
		$this->go3ccms_changlog($ids,$type,$ids);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//批量同意上线
	public function online_passto_all(){
		$this->db = pc_base::load_model('cms_video_model');
		$ids=$_GET['id'];
		$this->db->update(array('online_status'=>11,'offline_status'=>0),'id in ('.$ids.')');
		//添加操作日志
		$type = 'online_pass';
		$this->go3ccms_changlog($ids,$type,$ids);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	
	//批量拒绝下线申请
	public function delete_offline_allto(){
		$this->db = pc_base::load_model('cms_video_model');
		$ids=$_GET['id'];
		$this->db->update(array('offline_status'=>0),'id in ('.$ids.')');
		//添加操作日志
		$type = 'offline_refuse';
		$this->go3ccms_changlog($ids,$type,$ids);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//批量通过下线申请
	public function offline_to_all(){
		$this->db = pc_base::load_model('cms_video_model');
		$ids=$_GET['id'];
		$this->db->update(array('offline_status'=>2),'id in ('.$ids.')');
		//添加操作日志
		$type = 'offline_pass';
		$this->go3ccms_changlog($ids,$type,$ids);
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//游戏类型
	public function game_type(){
		$this->db_game = pc_base::load_model('cms_game_type_model');
		$title   	= trim($_GET['title']);
		
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';

		$where = " online_status='2'";
		$title  != '' ? $where.= " AND `title` LIKE '%$title%'" : '';
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db_game->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db_game->pages;
		include $this->admin_tpl('verify_type_list');
	}
	//审核通过游戏类型
	public function game_type_pass(){
		$this->db_game = pc_base::load_model('cms_game_type_model');
		$id = intval($_GET['id']);
		$this->db_game->update(array('online_status'=>11), array('id'=>$id));
		//添加操作日志
		$type = 'pass_game_type';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//拒绝审核通过游戏类型
	public function game_type_refuse(){
		$this->db_game = pc_base::load_model('cms_game_type_model');
		$id = intval($_GET['id']);
		$this->db_game->update(array('online_status'=>1), array('id'=>$id));
		//添加操作日志
		$type = 'refuse_game_type';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//游戏审核列表
	public function game_online(){
		$this->game_type = pc_base::load_model('cms_game_type_model');
		$game_type_list = $this->game_type->select('', 'id, title', '', 'id ASC');
		$game_type_array[0] = '请选择';
		foreach($game_type_list as $gvalue){
			$game_type_array[$gvalue['id']]=$gvalue['title'];
		}
		$this->db   = pc_base::load_model('cms_game_model');
		$title   	= trim($_GET['title']);
		$game_type   	= trim($_GET['game_type']);
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$game_type  = intval($_GET['game_type']);
				
		$where = " online_status='2'";
		$title  != '' ? $where.= " AND `title` LIKE '%$title%'" : '';
		$game_type  != '' ? $where.=" AND game_type = '$game_type'" : '';
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('verify_game_list');
	}
	//通过审核游戏
	public function game_list_pass(){
		$this->db_game = pc_base::load_model('cms_game_model');
		$id = intval($_GET['id']);
		$this->db_game->update(array('online_status'=>11), array('id'=>$id));
		//添加操作日志
		$type = 'pass_game_list';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//拒绝通过审核游戏
	public function game_list_refuse(){
		$this->db_game = pc_base::load_model('cms_game_model');
		$id = intval($_GET['id']);
		$this->db_game->update(array('online_status'=>1), array('id'=>$id));
		//添加操作日志
		$type = 'refuse_game_list';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//游戏下线审核列表
	public function game_offline(){
		$this->game_type = pc_base::load_model('cms_game_type_model');
		$game_type_list = $this->game_type->select('', 'id, title', '', 'id ASC');
		$game_type_array[0] = '请选择';
		foreach($game_type_list as $gvalue){
			$game_type_array[$gvalue['id']]=$gvalue['title'];
		}
		$this->db   = pc_base::load_model('cms_game_model');
		$title   	= trim($_GET['title']);
		$game_type   	= trim($_GET['game_type']);
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$game_type  = intval($_GET['game_type']);
				
		$where = " online_status='5'";
		$title  != '' ? $where.= " AND `title` LIKE '%$title%'" : '';
		$game_type  != '' ? $where.=" AND game_type = '$game_type'" : '';
		
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('verify_game_offline');
	}
	//通过游戏下线申请
	public function game_off_pass(){
		$this->db_game = pc_base::load_model('cms_game_model');
		$id = intval($_GET['id']);
		$this->db_game->update(array('online_status'=>6), array('id'=>$id));
		//添加操作日志
		$type = 'game_off_pass';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//拒绝游戏下线申请
	public function game_off_refuse(){
		$this->db_game = pc_base::load_model('cms_game_model');
		$id = intval($_GET['id']);
		$this->db_game->update(array('online_status'=>1), array('id'=>$id));
		//添加操作日志
		$type = 'game_off_refuse';
		$this->go3ccms_changlog($id,$type,'');
		showmessage('操作成功',base64_decode($_GET['goback']));
	}
	//资讯上线审核列表
	public function infor_online(){
		$this->information_type = pc_base::load_model('cms_information_type_model');
		$type_name_list = $this->information_type->select('', 'id, type_name', '', 'id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['id']]=$gvalue['type_name'];
		}
		$this->information = pc_base::load_model('cms_information_model');
		$title   	= trim($_GET['title']);
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$game_type  = intval($_GET['type']);
	
		$where = " online_status='4'";
		$title  != '' ? $where.= " AND `title` LIKE '%$title%'" : '';
		$game_type  != '' ? $where.=" AND type = '$game_type'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->information->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->information->pages;
		include $this->admin_tpl('verify_infor_list');
	}
	//资讯下线审核列表
	public function infor_off(){
		$this->information_type = pc_base::load_model('cms_information_type_model');
		$type_name_list = $this->information_type->select('', 'id, type_name', '', 'id ASC');
		$type_name_array[0] = '请选择';
		foreach($type_name_list as $gvalue){
			$type_name_array[$gvalue['id']]=$gvalue['type_name'];
		}
		$this->information = pc_base::load_model('cms_information_model');
		$title   	= trim($_GET['title']);
		$field    = isset($_GET['field']) ? $_GET['field'] : 'inputtime';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'DESC';
		$game_type  = intval($_GET['type']);
	
		$where = " online_status='5'";
		$title  != '' ? $where.= " AND `title` LIKE '%$title%'" : '';
		$game_type  != '' ? $where.=" AND type = '$game_type'" : '';
	
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->information->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->information->pages;
		include $this->admin_tpl('verify_infor_off');
	}
}
?>
