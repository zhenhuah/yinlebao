<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class adverts extends admin {

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
	 * 广告位列表
	 *
	 */
	public function adverts_list() {
		
		$this->term_db = pc_base::load_model('cms_term_type_model');
		$term_list = $this->term_db->select('', 'id,title', '', 'id ASC');
		$term_array = array();
		foreach($term_list as $pvalue){
			$term_array[$pvalue['id']]=$pvalue['title'];
		}
		
		$this->db = pc_base::load_model('cms_adverts_model');
		//构造SQL
		$where = "1 ";
		$title = strip_tags(trim($_GET['title']));
		if($_SESSION['roleid'] != '1') {
			//get user cids
			$cidarr = explode(',', $_SESSION['spid']);
			$len = count($cidarr);
			for ($i = 0; $i < $len; $i++) {
				$sqlin .= "'$cidarr[$i]'";
				if ($i != $len - 1)
					$sqlin .= ",";
			}
		}
		$spid = $_GET['spid'];
		$title ? $where.= " AND `title` LIKE '%$title%'" : '';
		if(!empty($spid))
		{
			$where .= " AND SPID = '".$spid."'";
		} else if ($_SESSION['roleid'] != '1') {
			$where .= " AND SPID in (".$sqlin.")";
		}
		//echo $where;
		$page  = $_GET['page'] ? $_GET['page'] : '1';

		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';

		$data  = $this->db->listinfo($where, $order = '`id` DESC', $page, $perpage);
		//echo '<pre>'; print_r($data);
		$pages = $this->db->pages;
		if($_SESSION['roleid']=='1'){
			$sp_list = $this->db->select($where = '', 'spid', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
		}else{
			$sp_list = $this->db->select($where = 'spid in ('.$sqlin.')', 'spid', $limit = '', $order = 'id ASC', $group = ' spid', $key='');
		}
		include $this->admin_tpl('adverts_list');
	}
	
	/**
	 * 删除
	 *
	 */
	public function delete() {
		$id 		= intval($_GET['id']);
		$this->db   = pc_base::load_model('cms_adverts_model');	
		//echo '<pre>';print_r($ha_id);exit;
		$this->db->delete(array('id'=>$id));
		showmessage('操作成功',$_SERVER['HTTP_REFERER'], $ms = 500);
	}
	
	
}
?>
