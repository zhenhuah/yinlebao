<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/publish_common.php' ;
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/db_list.php' ;

class publish extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		
	}   	
	
	/**
	 * 待发布项目
	 *
	 */
	public function job() {
		
		$d = array() ;
		$phpcmsdb = yzy_phpcms_db() ;
			
		//操作名称 每个操作对应的id 具体操作方法
		$d['atlist'] 	= ypublish_get_atlist() ;
		$d['atlist_id'] = ypublish_get_atlist_id() ;
		$d['mtype'] 	= request('mtype','GET') ;
		if(!$d['mtype'] || !isset($d['atlist'][$d['mtype']])) {
			$d['mtype'] = 'video' ;
		}
		
		//查询待处理数据总数
		$d['atsum'] = array() ;
		foreach($d['atlist'] as $k=>$v){
			$iwhere = ypublish_get_off_or_online_where($k) ;
			$itable = ypublish_get_off_or_online_table($k) ;	
			//新添加 _tags 的表没有 spid 字段
			if(strpos($itable,'_tags') && isset($iwhere['spid'])) unset($iwhere['spid']) ;
			$tmp = $phpcmsdb->r1($itable,$iwhere,'count(*) as c') ;
			$d['atsum'][$k] = $tmp['c'] ;
		}
		
		//数据表、排序、过滤条件
		$table = ypublish_get_off_or_online_table($d['mtype']) ;
		$order = ypublish_get_off_or_online_order($d['mtype']) ;
		$where = ypublish_get_off_or_online_where($d['mtype']) ;
		
		//翻页每页数量
		$page_size = 1000 ;
		
		//新添加 _tags 的表没有 spid 字段
		if(strpos($table,'_tags') && isset($where['spid'])) unset($where['spid']) ;
		
		//获取数据
		$d['online'] = get_list(
			array(
				'table'	 	=> $table ,
				'where' 	=> $where ,
				'order' 	=> $order ,
				'page_size' => $page_size ,
				'db' 		=> $phpcmsdb ,
				'pager' 	=> true ,
			)
		) ;
		$d['off_online_txt'] = ypublish_get_off_or_online_txt($d['mtype']) ;
		extract($d) ;
		include $this->admin_tpl('publish_job');
		
/*		//调试代码
		echo '<div style="border:solid 1px red; text-align:left; margin:10px 0; padding:10px;">';
		echo '<strong style="color:blue;">操  作</strong>：'.$d['mtype'].'<br />';
		echo '<strong style="color:blue;">源  表</strong>：'.$table.'<br />';
		echo '<strong style="color:blue;">排  序</strong>：'.$order.'<br />';
		echo '<strong style="color:blue;">条  件</strong>：';
		echo '<pre>';
		print_r($where);
		echo '<strong style="color:blue;">数  据</strong>：<br />';
		print_r($d['online']['list']);
		echo '</pre>';
		echo '</div>';*/
		
	}
	
	/**
	 * 发布项目
	 */
	public function jobrun() {
		
		$ids   = request('ids','POST','array') ;
		$mtype = request('mtype','POST') ;
		if(!$mtype) return false ;
		if(empty($ids) || count($ids) < 1) showmessage('没有任何可操作项目!');
		//ID适配，如果主键键名不是id，需要在这里做适配修改
		switch ($mtype) {
			case 'pre_adverts':
				$idname	= 'adId';
				break;
			case 'pre_task':
				$idname	= 'taskId';
				break;
			default:
				$idname	= 'id';
				break;
		}
	
		$allonline = request('allonline','POST') ;
		$allcanc   = request('allcanc','POST') ;
			
		$phpcmsdb  = yzy_phpcms_db()  ;
		$dbtable   = ypublish_get_off_or_online_table($mtype) ;
		
		//echo $dbtable; exit;
		
		//全部取消
		if($allcanc){
			$phpcmsdb->w($dbtable,ypublish_get_canc_off_or_online_where($mtype),'where '.$idname.' in ('.join(',',$ids).')') ;
			showmessage('操作成功',$_SERVER['HTTP_REFERER'],500);
		}
		
		//组合出调用的操作方法名
		$func = '__do_publish_to_'.$mtype ;
		
		//调试代码：主键、需要操作的数据主键编号、对应的操作方法
/*		echo $idname.'<br />';
		echo $dbtable.'<br />';
		echo $func.'<br /></pre>';
		print_r($ids);
		exit;*/
		
		//发布
		$phpcmsdb->r($dbtable, array($idname=>$ids), '*', array('func_row'=>$func)) ;
		
		
		//添加日志
		$sumcount = $func('getsumcount') ;
		
		$dd = array() ;
		$dd['sid']     	= get_my_sid() ;
		$dd['created'] 	= time() ;
		$dd['content'] 	= $mtype;
		$dd['count']   	= $sumcount;
		$dd['username'] = $this->current_spid['username'];
		
		//if($sumcount){
			$phpcmsdb->w('v9_publishlog',$dd) ;
		$this->cms_publishlog = pc_base::load_model('cms_publishlog_model');
		$aKey = " 1 ORDER BY id DESC ";
		$info = $this->cms_publishlog->get_one($aKey);
		$this->db = pc_base::load_model('cms_video_model');
		foreach ($ids as $v){
			$aKey = "id = '".$v."'";
			$limitInfo = $this->db->get_one($aKey);
			$logd = array() ;
			$logd['pid'] = $limitInfo['asset_id'];
			$logd['title'] = $limitInfo['title'];
			$logd['created'] = time();
			$logd['plid'] = $info['id'];
			$logd['content'] = $mtype;
			if(!empty($logd['pid'])&&!empty($logd['title'])&&$logd['plid']!='1'){
				$phpcmsdb->w('v9_publishlogdetail',$logd) ;
			}
		}
		//}
		//执行清除缓存行为(TOMCAT)
		//$ip = $HTTP_SERVER_VARS["SERVER_ADDR"];
		$url1 = GO3C_PATH1.'go3cci/cache.api?m=clear';
		$tmp1 = file_get_contents($url1);
		$url2 = GO3C_PATH2.'go3cci/cache.api?m=clear';
		$tmp2 = file_get_contents($url2);
		showmessage(ypublish_get_off_or_online_txt($mtype).'成功',$_SERVER['HTTP_REFERER'],500);
	}

	/**
	 * 发布历史
	 *
	 */
	public function his() {
		$phpcmsdb = yzy_phpcms_db()  ;
		
		$d = array() ;
		$d['atlist'] = array(
			'video'			=>'上线视频',
			'off_video'		=>'下线视频',
			'del_video'		=>'删除视频',
			'channel'		=>'上线频道',
			'channelepg'	=>'上线EPG',
			'tags_area'		=>'上线地区',
			'tags_cate'		=>'上线栏目分类',
			'tags_year'		=>'上线年代',
			'client_online' =>'客户端上线',
			'client_delete' =>'客户端删除',
			'pre_adverts' 	=>'广告位',
			'pre_task'      =>'推荐位',
		) ;
		
		$d['his'] = get_list(array(
			'table' 	=> 'v9_publishlog' ,
			'where' 	=> '' ,
			'order' 	=> 'order by id desc' ,
			'page_size' => 10 ,
			'db' 		=> $phpcmsdb ,
			'pager' 	=> true ,
		)) ;
		
		foreach($d['his']['list'] as $k=>$v){
			$tmp = explode('|',$v['content']) ;
			$v['do'] = $d['atlist'][$tmp[0]] ;
			$v['docount'] = $tmp[1] ;

			$d['his']['list'][$k] = $v ;
		}
		
		extract($d) ;
		include $this->admin_tpl('publish_his');
	}
	
	
	/**
	 * 取消发布
	 */
	public function canc() {
		$id = request('id','GET','int') ;
		if(!$id) return false ;
		
		$mtype = request('mtype','GET') ;
		if(!$mtype) return false ;
		
		
		$phpcmsdb = yzy_phpcms_db() ;
		
		if(strpos($mtype,'position') === 0){
			$phpcmsdb->w(ypublish_get_off_or_online_table($mtype),ypublish_get_canc_off_or_online_where($mtype),array('posid'=>$id));
		}else if(strpos($mtype,'pre_task') === 0){
			$phpcmsdb->w(ypublish_get_off_or_online_table($mtype),ypublish_get_canc_off_or_online_where($mtype),array('taskId'=>$id));
		}else{
			$phpcmsdb->w(ypublish_get_off_or_online_table($mtype),ypublish_get_canc_off_or_online_where($mtype),array('id'=>$id)) ;
		}
		
		$r = array() ;
		$r['tp'] = 'json' ;
		$r['data'] = $id ;
		$r['fun'] = "window.icanc_{$id}=" ;
		
		y_do_r($r) ;
	}
	
	/**
	 * 发布历史详细信息
	 */
	public function publishlogdetail(){
		$this->db = pc_base::load_model('cms_publishlogdetail_model');
		$id   = isset($_GET['id']) ? intval($_GET['id']) : '';

		$field    = isset($_GET['field']) ? $_GET['field'] : 'id';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'asc';
		
		$where = " 1 AND plid = '$id'";
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('publishlogdetail');
	}
	/**
	 * 服务器日志列表
	 */
	public function publishlogpc(){
		$this->db = pc_base::load_model('cms_publishlogpc_model');
		$where = " 1 ";
		$field    = isset($_GET['field']) ? $_GET['field'] : 'created';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'desc';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '15';
		$data  = $this->db->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->db->pages;
		include $this->admin_tpl('publishlogpc');
	}
}