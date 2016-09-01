<?php
//设置时区 
date_default_timezone_set('Asia/Shanghai');
if(!defined('TIME')) define('TIME',time()) ;


if(!defined('MEMCACHED_KEY_PREFIX')) define('MEMCACHED_KEY_PREFIX','go3c') ;
if(!defined('CACHE_ROOT')) define('CACHE_ROOT',CACHE_PATH) ;
if(!defined('APP_NAME')) define('APP_NAME','go3ccms') ;
if(!defined('PHPCMS_PATH')) return ;

function yzy_sooner_db(){
	global $db ;
	if(!empty($db)) return $db ;
	
	$db = yzy_db('go3c') ;
}

function yzy_phpcms_db(){
	static $c ;
	if(isset($c)) return $c ;
	$c = yzy_db('default') ;
	return $c ;
}


function yzy_go3c_db(){
	static $c ;
	if(isset($c)) return $c ;
	$c = yzy_db('tv') ;
	return $c ;
}

function yzy_shop_db(){
	static $c ;
	if(isset($c)) return $c ;
	$c = yzy_db('shop') ;
	return $c ;
}
function yzy_analysis_db(){
	static $c ;
	if(isset($c)) return $c ;
	$c = yzy_db('analysis') ;
	return $c ;
}
function yzy_vodstb_db(){
	static $c ;
	if(isset($c)) return $c ;
	$c = yzy_db('vodstb') ;
	return $c ;
}
function yzy_ktv_db(){
	static $c ;
	if(isset($c)) return $c ;
	$c = yzy_db('ktv') ;
	return $c ;
}
function yzy_db($t){
	require_once PHPCMS_PATH . 'phpcms/libs/yinclude/DBHelper.php' ;
	$tmp = require PHPCMS_PATH . 'caches/configs/database.php' ;
	
	$itemp = explode(':',$tmp[$t]['hostname']) ;
	if(empty($itemp[1])) $itemp[1] = '3306' ;
	$server_conf = array(
		'host' => $itemp[0],
		'port' => $itemp[1],
		'user' => $tmp[$t]['username'],
		'pass' => $tmp[$t]['password'],
		'name' => $tmp[$t]['database'],
	);
			
	$db = new DBHelper(false,false,$server_conf) ;
	
	return $db ;
}

function get_my_sid(){
	if(!defined('IN_CRONTAB') || !IN_CRONTAB) return $_SESSION['userid'] ;
	
	return '9999' ;
}

function get_uname_by_sid($sid){
	if($sid == '9999') return  'autorun' ;
	$tmp = get_user_by_sid($sid) ;
	return $tmp['username'] ;
}


function get_spid_by_sid($sid=0){
	if(!$sid) $sid = get_my_sid() ;
	if($sid == '9999') return 'autorun' ;
	$tmp = get_user_by_sid($sid) ;
	return $tmp['spid'] ;
}


function get_user_by_sid($sid){
	static $c ;
	if(!isset($c)) $c = array() ;
	if(isset($c[$sid])) return $c[$sid] ;
	$phpcmsdb = yzy_phpcms_db() ;
	$tmp = $phpcmsdb->r1('v9_admin',array('userid'=>$sid)) ;

	$c[$sid] = $tmp ;
	return $c[$sid] ;
}


//发信息
function y_send_message($subject,$content,$send_from_id,$send_to_id,$folder='index'){
	$d = array() ;
	$d['subject'] = $subject ;
	$d['content'] = $content ;
	$d['send_from_id'] = $send_from_id ;
	$d['folder'] = $folder ;
	$d['status'] = 1 ;
	$d['message_time'] = time() ;
	
	if(is_array($send_to_id)) $send_to_ids = $send_to_id ;
	else $send_to_ids = explode(',',$send_to_id) ;
	
	
	foreach($send_to_ids as $send_to_id){
		$d['send_to_id'] = $send_to_id ;
		
		$phpcmsdb = yzy_phpcms_db() ;
		$phpcmsdb->w('v9_message',$d) ;
	}
 	
	return count($send_to_id) ;
}

function y_do_r($result){
	if(!is_array($result)){
		print $result ;
		exit ;
	}
	//js脚本
	if($result['tp'] == 'script'){
		if(!empty($result['script'])) $result['data'] = $result['script'] ;
		print '<script type="text/javascript">'.$result['data'].'</script>';
		return ;
	}

	//json 回调
	if($result['tp'] == 'json'){
		//header('Content-Type: text/javascript; charset=utf-8'); 
		if(isset($result['data'])){
			if(!isset($result['fun'])){
				print json_encode($result['data']);
			}else{
				print $result['fun'] . '('.json_encode($result['data']).');';
			}
		}
		return ;
	}
	
	//js 调用
	if($result['tp'] == 'jsfile'){
		header('Content-Type: text/javascript charset=utf-8');
		if(isset($result['data'])){
			print $result['data'] ;
		}
		return ;
	}
	
	
	if($result['tp'] == 'template'){
		if(!empty($result['data'])) extract($result['data']);
		require_once dirname(__FILE__) . '/template.php' ;
		include ytemplate($result['tpl']) ;
		return ;
	}
	
	return  ;
}


//针对格式为 array(array('1'=>array('index'=>'x','showname'=>'y'))) 的配置文件，获得 相应index下的 key
function get_config_skey($index,$config_f){
	static $s ;
	if(!isset($s)) $s = array() ;
	if(!isset($s[$config_f])){
		$s[$config_f] = array() ;
		$tmp = get_config($config_f) ;
		foreach($tmp as $k=>$v){
			$s[$config_f][$v['index']] = $k ;
		}
	}
	
	return $s[$config_f][$index] ;
}

//fix
function fix_imlog($d){
	$des = array('imchannel','imepg','imasset','iimchannel','iimepg','iimasset') ;
	
	foreach($des as $k){
		$d[$k] = idecode_arr($d[$k]) ;
	}
	
	$d['hasdo'] = array() ;
	foreach($des as $k){
		if(!empty($d[$k]['result'])) $d['hasdo'][$k] = array('t'=>$d[$k]['t'],'result'=>$d[$k]['result']) ;
	}
	
	return $d ;
}

function fix_asset($d){
	if(isset($d['category'])) $d['category'] = explode(',',$d['category']) ;
	if(isset($d['atarget'])) $d['atarget'] = explode(',',$d['atarget']) ;
	return $d ;
}


//将表头附加到数据
//参数: 表头,数据
function to_a_tab_keyarray($tabh,$tabd,$r_key=''){
	$r = array() ;
	foreach($tabd as $v){
		$tmp = array() ;
		foreach($tabh as $ik=>$iv){
			$tmp[$iv] = $v[$ik] ;
		}
		if($r_key) $r[$tmp[$r_key]] = $tmp ;
		else $r[] = $tmp ;
	}
	return $r ;
}

//序列化一维数组
function iencode_arr($arr){
	return http_build_query($arr) ;
}

//解开序列化的一维数组
function idecode_arr($str){
	parse_str($str,$arr) ;
	return $arr ;
}


//获取远程文件..+重试
function ifile_get_contents($f,$rec=3,$sleep=1){
	$s = '' ;
	$i = 0 ;
$context = stream_context_create(array(
     'http' => array(
      'timeout' => 3000 //超时时间，单位为秒
     ) 
));  
	while($s == ''){
		$s = file_get_contents($f,0,$context) ;
		if($s != '') break ;
		$i++ ;
		sleep($sleep) ;
		if($i >= $rec) return false ;
	}
	return $s ;
}

function go3c_fix_d(&$d){
	foreach($d as $k=>$v){
		if(is_array($v) && count($v) < 1) $v = '' ;
		$v = trim($v) ;
		$d[$k] = $v ;
	}
}

function go3c_fix_dsf(&$dsf){
	$r = $dsf ;
	$dsf = array() ;
	foreach($r as $k=>$v){
		if(is_numeric($k)) $dsf[$v] = $v ;
		else $dsf[$k] = $v ;
	}
}




function request($key,$method='GET',$format='string',$filter=true){
	$request = $method == 'GET' ? $_GET : $_POST ;
	if(isset($request[$key])){
		if($format == 'string' && is_string($request[$key])){
			if($filter) return  myspecialchars(trim($request[$key]));
			return trim($request[$key]);
		}else if($format == 'int'){
			return intval($request[$key]) ;
		}else if($format == 'array' && is_array($request[$key])){
			if($filter) return  myspecialchars(mytrim($request[$key]));
			return mytrim($request[$key]);
		}
	}
	return FALSE;
}

function mytrim($arg){
	if(is_array($arg)){
		foreach($arg as $k=>$v){
			$arg[$k] = mytrim($v);
		}
		return $arg ;
	}else if(is_string($arg)) return trim($arg);
	return $arg ;
}

function myspecialchars($arg){
	if(is_array($arg)){
		foreach($arg as $k=>$v){
			$arg[$k] = myspecialchars($v);
		}
		return $arg ;
	}elseif(is_string($arg)){
		$arg = htmlspecialchars($arg,ENT_QUOTES);
		$arg = str_replace('&amp;','&',$arg);
		return $arg;
	}
	return $arg ;
}

function logError($object)
{
  error_log(date("[Y-m-d H:i:s]")." -[".$_SERVER['REQUEST_URI']."] :".print_r($object,true)."\n", 3, "/tmp/php_err.log");
  return true;
}
