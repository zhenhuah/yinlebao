<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php';
class clear_memcache extends admin {
	function __construct() {
		parent::__construct();
		$this->channelepg_match = pc_base::load_model('cms_channelepg_match_model');
		$this->column = pc_base::load_model('cms_column_model');
	}
	public function memcache(){

		include $this->admin_tpl('memcache');
	}
	//拷贝专题文件
	public function copybigtv(){
		$filename  = $_GET['filename'];
		//执行linux命令的php文件的路径
		$url = 'http://111.208.56.54/copyzt.php?filename='.$filename;
		$tmp = file_get_contents($url);
		showmessage('专题复制成功!',base64_decode($_GET['goback']),2000);
	}
	public function memcache_bigtv(){
		$type  = $_GET['type'];
		//php加锁的判断
		$url4 = GO3C_PATH3.'checklock.php';
		$tmp4 = file_get_contents($url4);
		if($type=='html'){
			//删除网站模型缓存
			$deurl = GO3C_tpl1.'delecache.php';
			//生成静态页
			$html = GO3C_html1.'buildhtml.php';
		}else{
			//手工执行memcache缓存
			$urlcache54 = GO3C_memcache54.'memcacheall.php?type='.$type;
		}
		$phpcmsdb = yzy_phpcms_db() ;
		$tc = time();
		$rd = $phpcmsdb->r1('v9_times') ;
		$logintime = $rd['logintime'];
		if($tc>$logintime+60||$tmp4=='unlocked'){
			if($type=='html'){
				$tmp54 = file_get_contents($deurl);
				$tmp55 = file_get_contents($html);
			}else{
				$tmp5 = file_get_contents($urlcache54);
			}			
			$phpcmsdb->d('v9_times',array('logintime'=>$logintime)) ;
			$dd = array(
				'username' 	 => $tc,
				'logintime'  => $tc
				);
			$phpcmsdb->w('v9_times',$dd) ; 
			//添加操作日志
			$type = 'up_memcache';
			$this->go3ccms_changlog('',$type,json_encode($url4));
			showmessage('memcache缓存更新成功',base64_decode($_GET['goback']),2000);
		}else{
			showmessage('抱歉!更新太频繁,请稍后尝试!',base64_decode($_GET['goback']),2000);
		}
	}
	public function memcache_www(){
		$type  = $_GET['type'];
		//php加锁的判断
		$url4 = GO3C_PATH3.'checklock.php';
		$tmp4 = file_get_contents($url4);
		if($type=='html'){
			//删除网站模型缓存
			$deurl = GO3C_tpl2.'delecache.php';
			//生成静态页
			$html = GO3C_html2.'buildhtml.php';
		}else{
			//手工执行memcache缓存
			$urlcache = GO3C_memcache.'memcacheall.php?type='.$type;
		}
		$phpcmsdb = yzy_phpcms_db() ;
		$tc = time();
		$rd = $phpcmsdb->r1('v9_times') ;
		$logintime = $rd['logintime'];
		if($tc>$logintime+60||$tmp4=='unlocked'){
			if($type=='html'){
				$tmp2 = file_get_contents($deurl);
				$tmp3 = file_get_contents($html);
			}else{
				$tmp = file_get_contents($urlcache);
			}
			$phpcmsdb->d('v9_times',array('logintime'=>$logintime)) ;
			$dd = array(
				'username' 	 => $tc,
				'logintime'  => $tc
				);
			$phpcmsdb->w('v9_times',$dd) ; 
			//添加操作日志
			$type = 'up_memcache';
			$this->go3ccms_changlog('',$type,json_encode($url4));
			showmessage('memcache缓存更新成功',base64_decode($_GET['goback']),2000);
		}else{
			showmessage('抱歉!更新太频繁,请稍后尝试!',base64_decode($_GET['goback']),2000);
		}
	}
	//EPG智能字典列表
	public function MatchEpg(){
		$name = $_GET['name'];
		$column_id = $_GET['column_id'];
		$field    = isset($_GET['field']) ? $_GET['field'] : 'id';
		$order    = isset($_GET['order']) ? $_GET['order'] : 'ASC';
		$where = " 1 ";
		$name != '' ? $where.= " AND `name` LIKE '%$name%'" : '';
		$column_id != '' ? $where.= " AND `column_id`='$column_id'" : '';
		$page  = $_GET['page'] ? $_GET['page'] : '1';
		$perpage = intval($_GET['perpage']) ? intval($_GET['perpage']) : '10';
		$data  = $this->channelepg_match->listinfo($where, $order = "$field $order", $page, $perpage);
		$pages = $this->channelepg_match->pages;
		$column_list = $this->column->select();
		include $this->admin_tpl('matchepg');
	}
	//添加智能字典
	public function addmatchepg(){
		$column_list = $this->column->select();
		include $this->admin_tpl('matchepg_add');
	}
	public function addmatchepgdo(){
		$name = trim($_POST['name']);
		$seq_number = trim($_POST['seq_number']);
		$column_id = trim($_POST['column_id']);
		if(empty($column_id)){
			showmessage('操作失败,所属栏目不能为空!','index.php?m=admin&c=clear_memcache&a=MatchEpg');			
		}else{
			$column = $this->column->get_one(array('id'=>$column_id));
		}
		$data = array(
			'name' 			=> $name,
			'column_id' 	=> $column_id,
			'column_name' 	=> $column['title'],
			'seq_number' 	=> $seq_number,
			'inputtime' 	=> time(),
			'isextend' 		=> '0'
		);
		$this->channelepg_match->insert($data);
		showmessage('操作成功','index.php?m=admin&c=clear_memcache&a=MatchEpg');
	}
	//修改智能字典
	public function editmatchepg(){
		$id = $_GET['id'];
		$column_list = $this->column->select();
		$epgminfo= $this->channelepg_match->get_one(array('id'=>$id));
		include $this->admin_tpl('matchepg_edit');
	}
	public function editmatchepgdo(){
		$id = trim($_POST['id']);
		$name = trim($_POST['name']);
		$seq_number = trim($_POST['seq_number']);
		$column_id = trim($_POST['column_id']);
		if(empty($column_id)){
			showmessage('操作失败,所属栏目不能为空!','index.php?m=admin&c=clear_memcache&a=MatchEpg');
		}else{
			$column = $this->column->get_one(array('id'=>$column_id));
		}
		$data = array(
				'name' 			=> $name,
				'column_id' 	=> $column_id,
				'column_name' 	=> $column['title'],
				'seq_number' 	=> $seq_number,
				'inputtime' 	=> time(),
				'isextend' 		=> '0'
		);
		$this->channelepg_match->update($data,array('id'=>$id));
		showmessage('操作成功','index.php?m=admin&c=clear_memcache&a=MatchEpg');
	}
	//删除智能字典
	public function delete_matchepg(){
		$id = $_GET['id'];
		$this->channelepg_match->delete(array('id'=>$id));
		showmessage('操作成功','index.php?m=admin&c=clear_memcache&a=MatchEpg');
	}
}
?>