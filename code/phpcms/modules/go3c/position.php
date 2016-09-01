<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
class position extends admin {

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
	 * 终端信息列表
	 *
	 */
	public function position_list() {
		
		$this->postion_type = pc_base::load_model('position_model');
		$term_id 	= intval($_GET['term_id']);
		$where_term = $term_id ? " term_id = '$term_id' AND spid = '".$this->current_spid[spid]."' " : '';
		$postion_type_list = $this->postion_type->select($where_term, 'posid, name', '', 'posid ASC');
		$i = 0;
		foreach($postion_type_list as $pvalue){
			if($i == 0) $i = intval($pvalue['posid']);			
			$postion_type_array[$pvalue['posid']]=$pvalue['name'];
		}
		
		$editenable = isset($_GET['view'])?intval($_GET['view']):1;
		//推荐位数据
		$posid 		= intval($_GET['posid'])?intval($_GET['posid']):$i;
		$pos_filt   = $posid ?   " AND pv.posid = '$posid'" : '';
		$term_filt  = $term_id ? " AND pv.term_id = '$term_id'" : '';
		//$type_filt  = $type_id ? " AND pv.type_id = '$type_id'" : " AND pv.type_id = '1'";
		$this->db   = pc_base::load_model('position_model');		
		$field    	= 'pv.*, p.name';
		$sql     	= "v9_position_video AS pv LEFT JOIN v9_position AS p ON p.posid = pv.posid WHERE 1 AND pv.spid = '".$this->current_spid[spid]."'".$term_filt.$pos_filt; 
		//echo $sql;
		$order  	= 'ORDER BY pv.listorder DESC';
		$perpage 	= 20;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		$data 		= $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
		//echo '<pre>';print_r($data);
		$multipage  = $this->db->pages;
		include $this->admin_tpl('position_list');			
	}
	
	/**
	 * 推荐位信息 添加
	 *
	 */
	public function position_video_add(){
	
		$term_id 	= intval($_GET['term_id']);
		//$type_id 	= intval($_GET['type_id']);
		$posid 		= intval($_GET['posid']);
		$vid 		= intval($_GET['vid']);
		$img_info   = explode('||',$_GET['img_info']);
		
		$data_array = array(
			'term_id'  	  => $term_id,
			'posid'    	  => $posid,
			'vid'    	  => $vid,
			'img_type' 	  => $img_info[0],
			'path' 	  	  => $img_info[1],
			'spid' 	   	  => $_GET['spid'],
			'asset_id'    => $_GET['asset_id'],
			'title'    	  => $_GET['title'],
			'description' => $_GET['description'],
			'inputtime'   => time(),
		);
		//echo '<pre>';print_r($data_array);exit;
		$this->db = pc_base::load_model('cms_position_video_model');	
		$insert_id = $this->db->insert($data_array, true);
		showmessage('操作成功','?m=go3c&c=position&a=position_list&term_id='.$term_id.'&posid='.$posid, $ms = 500);
	}
	
	/**
	 * 推荐位信息 修改
	 *
	 */
	public function position_video_edit(){
	
		$pvid 		= intval($_GET['pvid']);
		$term_id 	= intval($_GET['term_id']);
		$posid 		= intval($_GET['posid']);
		$img_info   = explode('||',$_GET['img_info']);
		
		$data_array = array(
			'img_type' 	  => $img_info[0],
			'path' 	  	  => $img_info[1],
			'spid' 	   	  => $_GET['spid'],
			'asset_id'    => $_GET['asset_id'],
			'title'    	  => $_GET['title'],
			'description' => $_GET['description'],
			'inputtime'   => time(),
		);
		//echo '<pre>';print_r($data_array);exit;
		$this->db = pc_base::load_model('cms_position_video_model');	
		$this->db->update($data_array, array('id'=>$pvid));
		showmessage('操作成功','?m=go3c&c=position&a=position_list&term_id='.$term_id.'&posid='.$posid, $ms = 500);
	}
	
	/**
	 * 推荐位信息列表
	 *
	 */
	public function position_item_list(){
		
		//获取上一个页面的 term_id 和 type_id
		$term_id 	= intval($_GET['term_id']);
		$posid 		= intval($_GET['posid']);
		$this->db = pc_base::load_model('cms_video_model');
		$filter   = isset($_GET['filter']) ? intval($_GET['filter']) : '';
		$keyword  = strip_tags(trim($_GET['keyword']));
		$asset_id = strip_tags(trim($_GET['asset_id']));
		$where = " 1 AND spid = '".$this->current_spid[spid]."' ";
		if($filter == 3){
			$where.= " AND column_id = '3' AND published = 1 ";
		}elseif($filter == 4){
			$where.= " AND column_id = '4' AND published = 1 ";
		}elseif($filter == 5){
			$where.= " AND column_id = '5' AND published = 1 ";
		}elseif($filter == 6){
			$where.= " AND column_id = '6' AND published = 1 ";
		}else{
			$where.= " AND published = 1 ";
		}
		//echo $where;
		$keyword  != '' ? $where.= " AND `title` LIKE '%$keyword%'" : '';
		$asset_id != '' ? $where.= " AND `asset_id` LIKE '%$asset_id%'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $pagesize = 10);
		$pages = $this->db->pages;

		include $this->admin_tpl('position_item_list');
	}
	
	/**
	 * 推荐位信息管理
	 *
	 */
	public function position_item_manage(){
		
		//NJPHP 终端类型
		$term_id = intval($_GET['term_id']);
		$posid   = intval($_GET['posid']);
		$this->term_type = pc_base::load_model('term_type_model');
		$go3c_term_type  = $this->term_type->get_one(array('id'=>$term_id));	
		
		//NJPHP 推荐位类型
		/*
		$type_id = intval($_GET['type_id']);
		$this->postion_type = pc_base::load_model('postion_type_model');
		$go3c_position_type = $this->postion_type->get_one(array('id'=>$type_id));	
		*/
		
		//NJPHP  推荐位posid
		$this->position = pc_base::load_model('cms_position_model');
		$position = $this->position->get_one(array('posid'=>$posid), 'posid, name');
		$posname = $position['name'];
	
		//海报格式
		$this->dbmover_poster_type = pc_base::load_model('dbmover_poster_type_model');
		$poster_type = $this->dbmover_poster_type->select();
		foreach ($poster_type as $value) {
			$poster_type_array[$value['id']] = $value;
		}	
		
		//获取视频的id,如果是新增通过视频表中的id查询，如果是修改通过推荐视频表中的vid来查询
		$asset_id = intval($_GET['vid']) ? intval($_GET['vid']) : intval($_GET['id']);
		if($asset_id){
			//获取视频信息
			$this->video = pc_base::load_model('cms_video_model');
			$video = $this->video->get_one(array('id'=>$asset_id),'id, title, asset_id, spid');
			
			//获取海报列表
			$this->poster = pc_base::load_model('cms_video_poster_model');			
			$poster = $this->poster->select(array('asset_id'=>$video['asset_id']),'type,asset_id,title,path');
		}
		
		//如果有vid 根据pvid把当前的推荐信息取出来
		$vid  = intval($_GET['vid']);
		$pvid = intval($_GET['pvid']);
		
		if($pvid){	
			$this->position_video = pc_base::load_model('cms_position_video_model');
			$position_video = $this->position_video->get_one(array('id'=>$pvid));
			//echo '<pre>';print_r($position_video);
		}
		
		include $this->admin_tpl('position_item_manage');	
	}

	
	/**
	 * 推荐位数据上移
	 *
	 */
	public function position_move_up() {
		$id 		= intval($_GET['id']);
		$posid 		= intval($_GET['posid']);
		$term_id 	= intval($_GET['term_id']);
		$this->db   = pc_base::load_model('cms_position_video_model');	
		$this->db->query("update v9_position_video set listorder = listorder + 1 WHERE posid = '$posid' AND id = '$id'");
		showmessage('操作成功','?m=go3c&c=position&a=position_list&term_id='.$term_id.'&posid='.$posid, $ms = 500);
	}
	
	/**
	 * 推荐位数据下移
	 *
	 */
	public function position_move_down() {
		$id 		= intval($_GET['id']);
		$posid 		= intval($_GET['posid']);
		$term_id 	= intval($_GET['term_id']);
		$this->db   = pc_base::load_model('cms_position_video_model');	
		$this->db->query("update v9_position_video set listorder = listorder - 1 WHERE posid = '$posid' AND id = '$id'");
		showmessage('操作成功','?m=go3c&c=position&a=position_list&term_id='.$term_id.'&posid='.$posid, $ms = 500);
	}
	
	/**
	 * 推荐位数据删除
	 *
	 */
	public function position_delete() {
		$id 		= intval($_GET['id']);
		$posid 		= intval($_GET['posid']);
		$term_id 	= intval($_GET['term_id']);
		$this->db   = pc_base::load_model('cms_position_video_model');	
		$this->db->delete(array('id'=>$id, 'posid'=>$posid));
		showmessage('操作成功','?m=go3c&c=position&a=position_list&term_id='.$term_id.'&posid='.$posid, $ms = 500);
	}
	
	/**
	 * 推荐位上线审核
	 *
	 */
	public function position_online() {
		$term_id 	= intval($_GET['term_id']);
		$posid 	    = intval($_GET['posid']);
		$totalnum 	= intval($_GET['totalnum']);
		
		//查询推荐位的推荐视频数量范围
		$this->db   = pc_base::load_model('cms_position_model');	
		$position   = $this->db->get_one(array('posid'=>$posid), 'posid,minnum, maxnum');
		
		//申请上线
		//if($totalnum){
		if($totalnum >= $position['minnum'] && $totalnum <= $position['maxnum']){
			$this->db->update(array('published'=>'0', 'online_status'=>'10'),array('posid'=>$position['posid']));
			showmessage('操作成功','?m=go3c&c=position&a=position_list&term_id='.$term_id.'&posid='.$posid, $ms = 500);
		}else{
			showmessage('操作无效。此推荐位的视频数量应该在 '.$position['minnum'].'~'.$position['maxnum']." 之间", '?m=go3c&c=position&a=position_list&term_id='.$term_id.'&posid='.$posid, $ms = 1200);
		}
		
	}
	
}
?>
