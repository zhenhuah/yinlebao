<?php
/**
 * 传入数组 array
 *     table 表名 必传
 *     where 条件 可选
 *     order 排序 可选
 *     field 字段 可选
 *     pager 如果传入字符串，则是分页  可选
 *     page_size 分页数 可选
 *     page_index 当前页码 可选
 *     count_expire 总数计数过期时间 可选 默认为600
 *     list_expire 列表缓存时间 可选 默认为0
 *     list_m_key 列表缓存m_key 可选
 *
 * 返回值 数组
 *     list 列表结果
 *     pager 分页html(如果有分页)
 *     sum   列表总数(如果有分页)
 */
function get_list($arr){
	global $db ;

	if(is_string($arr)) $arr = array('table'=>$arr);

	extract($arr);
	if(!isset($where)) $where = '';
	if(!isset($order)) $order = '';
	if(!isset($count_expire)) $count_expire = 0;
	if(!isset($list_expire)) $list_expire = 0;
	if(!isset($list_m_key)) $list_m_key = '';
	if(!isset($r_key)) $r_key = '';
	if(!isset($tail_sql)) $tail_sql = '';

	if(!isset($field)) $field = '*';
	
	$order = ' ' . $order ;
	
	if(isset($pager)){
		if(!isset($page_size)) $page_size = 20;
		if($pager === true){
			//parse_str($_SERVER['QUERY_STRING'],$pager);
			$pager = $_GET ;
			if(isset($pager['p'])) unset($pager['p']);
			$pager = str_replace('/index.php','/',$_SERVER['SCRIPT_NAME']).(http_build_query($pager) == '' ? '' : '?'.http_build_query($pager) );
		}
	}else $pager = false;

	if(empty($page_index)){
		$page_index = 1;
		if(isset($_GET['p']) && intval($_GET['p'])!=false){
			$page_index = intval($_GET['p']) ;
		}
	}

	$d = array();

	$list = array();
	if($pager){
		include_once dirname(__FILE__) . '/pager.php' ;
		if(!isset($sum)){
			if(!isset($count_field)) $count_field = 'count(*) as c' ;
			$row = $db->r1($table,$where,$count_field,array('expire'=>$count_expire,'sql'=>$tail_sql)) ;
			if(!isset($row['c'])) return false ;
			$d['sum'] = $row['c'] ;
		}else{
			$d['sum'] = $sum ;
		}
		
		if(isset($max_sum) && $d['sum'] > $max_sum) $d['sum'] = $max_sum ;

		$d['pager'] = '';
		$d['list'] = array();
		
		if($d['sum'] != 0){
			$d['pager'] = get_pager($pager,$d['sum'],$page_size,$page_index) ;
			$d['list'] = $db->r($table,$where,$field,
				array('sql'=>$tail_sql . $order,
					  'limit'=>get_pager_limit_sql($d['sum'],$page_size,$page_index),
					  'expire'=>$list_expire,
					  'm_key'=>$list_m_key,
					  'r_key'=>$r_key)) ;
		}
	}else{
		$d['list'] = $db->r($table,$where,$field,
			array('sql'=>$tail_sql . $order,
				  'limit'=> !empty($limit) ? $limit:null ,
			      'expire'=>$list_expire,
			      'm_key'=>$list_m_key));
	}

	return $d ;
}