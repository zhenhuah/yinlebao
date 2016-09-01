<?php
function f_set_data($key,$data,$ttl=0){
	$key = MEMCACHED_KEY_PREFIX . $key ;
	$m = _f_get_mem_obj() ;
	if($m){
		$m->set($key,$data,MEMCACHE_COMPRESSED,$ttl) ;
		if($ttl) return  ;
	}

	$f_path = _f_get_f_path($key,true);
	if(!is_dir($f_path) && !mkdir($f_path,0700,true)){
		die('cant create the f_data directory');
	}
	
	$f_path = _f_get_f_path($key) ;
	if(!$ttl) $data = $data ;
	else $data = array('__ex__'=>$ttl+TIME,'data'=>$data) ;
	
	file_put_contents($f_path,'<?php return '.var_export($data,true).';') ;
}

function f_get_data($key){
	$key = MEMCACHED_KEY_PREFIX . $key ;
	$m = _f_get_mem_obj() ;
	if($m){
		$data = $m->get($key) ;
		if($data) return $data ;
	}
	$f_path = _f_get_f_path($key) ;
	if(!is_file($f_path)) return false ;
	$data = include $f_path ;
	if(!isset($data['__ex__'])){
		if($m) $m->set($key,$data,MEMCACHE_COMPRESSED,0) ;
		return $data ;
	}
	if(time() > $data['__ex__']){
		f_del_data($key);
	}
	return $data['data'] ;
}

function f_del_data($key){
	$key = MEMCACHED_KEY_PREFIX . $key ;
	$m = _f_get_mem_obj() ;
	if($m) return $m->delete($key);
	$f_path = _f_get_f_path($key) ;
	if(!is_file($f_path)) return true ;
	return unlink($f_path);
}

function _f_get_f_path($key,$only_path=false){
	$path = CACHE_ROOT.'/f_data/' ;
	$key = md5($key) ;
	$path .= substr($key,-2).'/' ;
	return $path.($only_path ? '' : $key) ;
}

function _f_get_mem_obj(){
	if(!function_exists('memcache_connect') || !defined('MEMCACHED_SERVERS') || !defined('MEMCACHED_KEY_PREFIX')) return false ;
	static $m ;
	if(!isset($m)){
		$m = new Memcache;
		$ms = explode(',', MEMCACHED_SERVERS) ;
		foreach ($ms as $v){
			list($s, $p) = explode(':', $v);
			$m->addServer($s, $p, false);
		}
	}
	return $m ;
}