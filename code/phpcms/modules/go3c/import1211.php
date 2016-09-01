<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

//定义SP id
//define('SPID','ddssp') ;

//定义使用线上接口还是本地接口
define('USE_ONLINE_API',true) ;


require_once PHPCMS_PATH . 'phpcms/libs/yinclude/import_common.php' ;



class import extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
		global $spid;
		$spid = $this->current_spid['spid'];
	}   	
	
	public function init() {
		
	}
	
	/**
	 * 资源导入
	 */
	public function run() {
		global $spid;
		include $this->admin_tpl('import');
	}
	
	public function imdo() {
		$d['doim'] = array() ;
	
		$d['doim']['imchannel'] = request('imchannel','GET','int') ;
		$d['doim']['imepg'] = request('imepg','GET') ;
		$d['doim']['imasset'] = request('imasset','GET','int') ;	

		//生成导入报告ID
		global $db ;
		$d['imlog_id'] = $db->w('go3c_imlog',array('sid'=>get_my_sid(),'created'=>time())) ;

		extract($d) ;
		
		include $this->admin_tpl('import_do');
	}
	
	public function imdodo() {
		$do_epg = request('do_epg','POST') ;
		$do_asset = request('do_asset','POST') ;
		$do_channel = request('do_channel','POST') ;
		
		$p = request('p','POST') ;
		if($do_epg) $d['ikey'] = 'epg' ;
		if($do_asset) $d['ikey'] = 'asset' ;
		if($do_channel) $d['ikey'] = 'channel' ;
		
		if($p) $d['p'] = $p ;

		$p1 = request('p1','POST') ;
                if($p1) $d['p1'] = $p1 ;
		
		$d['created'] = time() ;
		
		
		$phpcmsdb = yzy_phpcms_db() ; 
		$id = $phpcmsdb->w('v9_doimport',$d) ;
		
		print '<script>window.location.href="?m=go3c&c=import&a=imdodos&id='.$id.'&pc_hash='. $_SESSION['pc_hash'] . '";</script>' ;
	}
	
	public function imdodos() {
		$id = request('id','GET','int') ;
		
		include $this->admin_tpl('import_dodos');
	}
	
	
	public function doimportstauts() {
		$id = request('id','GET','int') ;
		if(!$id) $id = request('id','POST','int') ; 
		$phpcmsdb = yzy_phpcms_db() ; 
		$tmp = $phpcmsdb->r1('v9_doimport',array('id'=>$id),'id,ikey,status,imlog_id') ;
		
		print json_encode($tmp) ;
		exit ;
	}
	
	
	
	//将xml->sooner
	public function doscript(){
		$imchannel = request('imchannel','GET','int') ;
		$imepg = request('imepg','GET') ;
		$imasset = request('imasset','GET','int') ;	
		
		global $imlog_id ;
		$imlog_id = request('imlog_id','GET','int') ;
		if(!$imlog_id) return false ;
		
		
		set_time_limit(100) ;
		ini_set('memory_limit','1024M');
		
		//
		ignore_user_abort(true) ;
		
		$r = array() ;
		$r['tp'] = 'json' ;
		$r['data'] = &$d ;
		$r['fun'] = 'window.ImportDo_imchannel=' ;
		
		global $db ;
		
		//导入全量channel
		if($imchannel){
			$d = __bigtvm_import_doscript_imchannel($imchannel) ;
			$d['t'] = $imchannel ;
			
			//更新到日志
			$db->w('go3c_imlog',array('imchannel'=>iencode_arr($d)),array('id'=>$imlog_id)) ;
			
			$r['fun'] = 'window.ImportDo_imchannel=' ;
			y_do_r($r) ;
		}
		
		
		//导入epg
		if($imepg){
			
			$d = array('total'=>0,'upsum'=>0,'t'=>$imepg) ;
			$d['t'] = $imepg ;
			$d['result'] = 'ok' ;
			
			$chxml = __bigtvm_import_doscript_imchannel(false,true) ;
	
			foreach($chxml->channels->channel as $c){
				$cid = trim($c['id']) ;
				$tmp = __bigtvm_import_doscript_imepg($imepg,$cid) ;
				if($tmp['result'] == 'no') continue ;
				
				$d['total'] += $tmp['total'] ;
				$d['upsum'] += $tmp['upsum'] ;
			}
			
			//更新到日志
			$db->w('go3c_imlog',array('imepg'=>iencode_arr($d)),array('id'=>$imlog_id)) ;
			
			$r['fun'] = 'window.ImportDo_imepg=' ;
			y_do_r($r) ;
		}
		
		
		//导入asset
		if($imasset){
			$d = __bigtvm_import_doscript_imasset($imasset) ;
			$d['t'] = $imasset ;
			
			//更新到日志
			$db->w('go3c_imlog',array('imasset'=>iencode_arr($d)),array('id'=>$imlog_id)) ;
			
			$r['fun'] = 'window.ImportDo_imasset=' ;
			y_do_r($r) ;
		}
		
		return false ;
	}
	
	
	//将 sooner -> phpcms
	public function doiscript(){
		$mtype = request('mtype','GET') ;
		
		global $imlog_id ;
		$imlog_id = request('imlog_id','GET','int') ;
		if(!$imlog_id) return false ;

		$mts = array('imchannel','imepg','imasset') ;
		if(!in_array($mtype,$mts)) return false ;

		$r = array() ;
		$r['tp'] = 'json' ;
		$r['data'] = &$d ;
		$r['fun'] = "window.ImportDoi_{$mtype}=" ;
		
		global $db ;
		
		$func = '__bigtvm_import_doiscript_' . $mtype ;
		$d = $func($imlog_id) ;
		
		if(is_array($d)) $d['result'] = 'ok' ;
		else $d['result'] = 'fail' ;
		
		
		//更新到日志
		$db = yzy_sooner_db() ;
		$db->w('go3c_imlog',array('i'.$mtype=>iencode_arr($d)),array('id'=>$imlog_id)) ;
		
		y_do_r($r) ;
		
		return false ;
	
	}
	

}



/**
*   相对路径转图片链接

function get_img_url($path){
	
	global $imgClient;

	if(is_object($imgClient) && !empty($path))
	{
		$imgUrl = "http://111.208.56.207/poster".$path;

		$imgClient->runSynUpdateImg($imgUrl);//更新方法调用

		return "http://111.208.56.60/images/poster".$path;
	}else{
		return false;	//路径不合法
	}

}
*/
