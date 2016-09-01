<?php

pc_base::load_app_class('admin','admin',0);

require_once PHPCMS_PATH . 'phpcms/libs/yinclude/bigtvm_common.php' ;

global $cdays ;
$cdays=array('0'=>'从不','8'=>'每日','1'=>'星期一','2'=>'星期二','3'=>'星期三','4'=>'星期四','5'=>'星期五','6'=>'星期六','7'=>'星期日');

class crontab  extends admin  {
	public function init() {
		
		
		include $this->admin_tpl('crontab_import');
	}
	
	
	public function import() {
	
		$phpcmsdb = yzy_phpcms_db() ;
		
		if(!empty($_POST['submit'])){
		
			$d = request('d','POST','array') ;
			$us = request('us','POST','array') ;
			if($us) $us = join(',',$us) ;
			
			foreach($d as $k=>$v){
				$v['ikey'] = $k ;
				$v['us'] = $us ;
				
				$tmp = $phpcmsdb->w('v9_crontab',$v,array('ikey'=>$k)) ;
				if(!$tmp) $phpcmsdb->w('v9_crontab',$v) ;
			}
		}
		

		$d = $phpcmsdb->r('v9_crontab',array('ikey'=>array('asset','epg','channel')),'*',array('r_key'=>'ikey')) ;
		
		$usernames = $phpcmsdb->r('v9_admin','','username',array('r_key'=>'username')) ;
		
		
		global $cdays ;
		
		include $this->admin_tpl('crontab_import');
	}	
	
	
	
	public function publish() {
	
		$phpcmsdb = yzy_phpcms_db() ;
		
		if(!empty($_POST['submit'])){
		
			$d = request('d','POST','array') ;
			
			$atlist = request('atlist','POST','array') ;
			if($atlist) $atlist = join(',',$atlist) ;
			
			$us = request('us','POST','array') ;
			if($us) $us = join(',',$us) ;
			
			$d['us'] = $us ;
			$d['tinfo'] = $atlist ;
			$d['ikey'] = 'publish' ;

			$tmp = $phpcmsdb->w('v9_crontab',$d,array('ikey'=>'publish')) ;
			if(!$tmp) $phpcmsdb->w('v9_crontab',$d) ;
		}
		
		
		$d = array() ;
		$d['atlist'] = array('video'=>'视频上线','channel'=>'频道上线','channelepg'=>'EPG上线','position'=>'推荐位上线','off_video'=>'视频下线','del_video'=>'视频删除') ;
		$d['dd'] = $phpcmsdb->r1('v9_crontab',array('ikey'=>'publish')) ;
		
		extract($d) ;
		
	
		$usernames = $phpcmsdb->r('v9_admin','','username',array('r_key'=>'username')) ;
		
		
		global $cdays ;
		include $this->admin_tpl('crontab_publish');
	}
	
	
	
	public function offline() {
	
		$phpcmsdb = yzy_phpcms_db() ;
		
		if(!empty($_POST['submit'])){
			$d = request('d','POST','array') ;
			
			$pus = request('pus','POST','array') ;
			if($pus) $pus = join(',',$pus) ;
			
			$aus = request('aus','POST','array') ;
			if($aus) $aus = join(',',$aus) ;
			
			$d['us'] = $aus ;
			$d['ikey'] = 'off_video' ;


			$tmp = $phpcmsdb->w('v9_crontab',$d,array('ikey'=>'off_video')) ;
			if(!$tmp) $phpcmsdb->w('v9_crontab',$d) ;
			
			
			$d['us'] = $pus ;
			$d['ikey'] = 'off_position_video' ;

			$tmp = $phpcmsdb->w('v9_crontab',$d,array('ikey'=>'off_position_video')) ;
			if(!$tmp) $phpcmsdb->w('v9_crontab',$d) ;
		}
		
		
		$d = $phpcmsdb->r('v9_crontab',array('ikey'=>array('off_video','off_position_video')),'*',array('r_key'=>'ikey')) ;
		
		extract($d) ;	
		$usernames = $phpcmsdb->r('v9_admin','','username',array('r_key'=>'username')) ;
		
		
		global $cdays ;
		include $this->admin_tpl('crontab_offline');
	}	
}
?>