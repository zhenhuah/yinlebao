<?php
function ytemplate($tpl) {
	global $CONFIG ;
	if(!is_dir(CACHE_ROOT.'/tpl/') && !mkdir(CACHE_ROOT.'/tpl/')){
		die('cant create the tpl directory');
	}
	$to = CACHE_ROOT.'/tpl/'.str_replace('/','-',(defined('APP_NAME') ? APP_NAME.'/':'').$tpl).'.inc';

	$from = ytemplate_get_path($tpl);
	if(!is_file($to) || filemtime($from) > filemtime($to)) {
		ytemplate_compile($from, $to);
	}

	return $to;
}

function ytemplate_module($module){
	if(is_array($module) && isset($module['tpl'])){
		$tpl = $module['tpl'] ;
		$module = $module['module'] ;
	}
	$do_fun = '_'.APP_NAME.'_module_'.$module;
	$args = func_get_args();
	unset($args[0]) ;
	if(function_exists($do_fun)){
		$args = array_merge($args,array()) ;
		$data = call_user_func_array($do_fun,$args) ;
		if(!$data) return false ;
		if($data && is_array($data)) extract($data);
	}
	if(count($args) > 0){
		if($args == 1 && is_array($args[0])) extract($arg[0]);
		else extract($args);
	}
	if(!isset($tpl)) $tpl = $module.'.tpl' ;
	include ytemplate('module/'.$tpl);
}

function ytemplate_get_path($tpl){
	global $CONFIG ;
	$from = YTEMPLATE_ROOT.'/'.$tpl;
	return $from ;
}

function ytemplate_compile($from, $to) {
	$content = ytemplate_parse(file_get_contents($from));
	file_put_contents($to, $content);
}

function ytemplate_parse($str) {
	$str = preg_replace("/\{\*.+?\*\}/s",'',$str);
	$str = preg_replace("/([\n\r]+)\t+/s","\\1",$str);
	$str = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\.{}\}/",'',$str);
	$str = preg_replace("/\{template\s+(.+)\}/", "<?php include ytemplate(\\1);?>", $str);
	$str = preg_replace("/\{template_module\s+(.+)\}/", "<?php ytemplate_module(\\1);?>", $str);
	$str = preg_replace("/\{php\s+(.+?)\}/", "<?php \\1?>", $str);
	$str = preg_replace("/\{if\s+([\!]?)(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/", "<?php if(isset(\\2) && \\1\\2) { ?>", $str);
	$str = preg_replace("/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str);
	$str = preg_replace("/\{else\}/", "<?php } else { ?>", $str);
	$str = preg_replace("/\{elseif\s+(.+?)\}/", "<?php } else if(\\1) { ?>", $str);
	$str = preg_replace("/\{\/if\}/", "<?php } ?>", $str);
	$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/", "<?php if(isset(\\1) && is_array(\\1)) { foreach(\\1 as \\2) { ?>", $str);
	$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php if(isset(\\1) && is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>", $str);
	$str = preg_replace("/\{\/loop\}/", "<?php } } ?>", $str);
	$str = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str);
	$str = preg_replace("/<\?php([^\?]+)\?>/es", "ytemplate_addquote('<?php\\1?>')", $str);
	$str = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php if(isset(\\1)) echo \\1;?>", $str);
	$str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "ytemplate_addquote('<?php if(isset(\\1)) echo \\1;?>')", $str);
	$str = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php if(defined('\\1')) echo \\1;?>", $str);
	$str = preg_replace("/\'([A-Za-z]+)\[\'([A-Za-z\.]+)\'\](.?)\'/s", "'\\1[\\2]\\3'", $str);
	
	return $str;
}

function ytemplate_addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}