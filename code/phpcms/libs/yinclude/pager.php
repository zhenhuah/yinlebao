<?php
/**
* Copyright (c) 2009, 新浪网研发综合开发
* All rights reserved.
*
* 文件名称：pager.php
* 摘    要：分页相关函数
*/


/**
* 函数名称：get_pager
* @返回: 字符串
* @参数: $link, $count, $pagesize, $index, $param
*   或者是 array() key:
*   必须的键值 link , count , pagesize , index
*   可选的键值 always_show=FALSE , max_showpage=10 , get_name='p'
* @示例:
print get_pager('',2000,10,isset($_GET['p']) ? $_GET['p'] : 1,'some=ss');
print get_pager('?some=ss',2000,10,isset($_GET['p']) ? $_GET['p'] : 1);

$pager_args = array(
      'link'=>'?some=ss',
      'count'=>2000,
	  'pagesize'=>10,
	  'index'=>isset($_GET['p']) ? $_GET['p'] : 1,
	  'get_name'=>'p');
print get_pager($pager_args);
*/

function get_pager(){
	$args = func_get_args();
	if(count($args) == 1 && is_array($args[0])){
		extract($args[0]);
	}else{
		if(count($args) < 4) return 'args error';
		list($link,$count,$pagesize,$index) = $args;
		if(isset($args[4])) 
			$link = $link.(strpos($link,'?')!==FALSE ? '&':'?').$args[4] ;
	}
	
	if(!isset($next_name)) $next_name = '下一页';
	if(!isset($prev_name)) $prev_name = '首页';
	
	$count = intval($count);
	$pagesize = intval($pagesize);
	$index = (empty($index) || intval($index)==FALSE) ? 1 : intval($index);
	
	//检查参数
	if(!isset($link) || !isset($count) || !isset($pagesize) || !isset($index)){
		return 'args error';
	}
	
	if($count===FALSE || $pagesize===FALSE || $index===FALSE){
		return 'args error';
	}

	$pagecount = (int)($count /$pagesize ) ;
	if($count % $pagesize) $pagecount++ ;

	if((!isset($alwaysshow) || $alwaysshow==false) && $pagecount < 2){
		return '';
	}

	if(!isset($max_showpage)) $max_showpage = 5 ;
	
	$start = max(1, $index - intval($max_showpage/2));
	$end = min($start + $max_showpage - 1, $pagecount);
	$start = max(1, $end - $max_showpage + 1);
	
	if($index < $start) $index = $start; 
	if($index > $end) $index = $end ;

	$html = '<div class="pages" id="pages">'.$count.'条';

	if($index == 1){
		$html .= ' <span class="nextprev">'.$prev_name.'</span>';
	}else{
		$html .= ' <a class="nextprev" href="{link}'.($index-1).'">'.$prev_name.'</a>';
	}

	if($start > 1){
		$icount = 0 ;
		for($i=1;$i<$start;$i++){
			if($icount > 1)
				break ;
			$html .= ' <a class="a" href="{link}'.$i.'">'.$i.'</a>'."";
			$icount ++ ;
		}
		if($start > 3){
			$html .= ' <a class="a" href="{link}'.max($index-$max_showpage,1).'">...</a>' ;
		}
	}

	for($i=$start;$i<$end+1;$i++){
		if($i==$index){
			$html .= ' <span class="this">'.$i.'</span>'."";
			continue ;
		}
		$html .= ' <a class="a" href="{link}'.$i.'">'.$i.'</a>'."";
	}

	if($end < $pagecount){
		if($pagecount - $end > 2){
			$html .= ' <a class="a" href="{link}'.min($index+$max_showpage,$pagecount).'">...</a>' ;
		}
		$icount = 0 ;
		$i= $pagecount - $end > 2 ? $pagecount-1 : $end+1 ;
		for(;$i<=$pagecount;$i++){
			if($icount > 1)
				break ;
			$html .= ' <a class="a" href="{link}'.$i.'">'.$i.'</a>'."";
			$icount ++ ;
		}
	}

	if($index == $pagecount){
		$html .= ' <span class="nextprev">'.$next_name.'</span>';
	}else{
		$html .= ' <a class="nextprev" href="{link}'.($index+1).'">'.$next_name.'</a>';
	}

	$html .= '</div><div style="clear:both;"></div>';

	$link = $link.(strpos($link,'?')!==FALSE ? '&':'?').(!empty($get_name) ? $get_name : 'p').'=';

	return str_replace('{link}',$link,$html); 
}

/**
* 函数名称：show_nav_bar.
* 摘要: 获得 limit sql 字符串如 limit 10,20
* @返回: 字符串
* @参数: $count,$pagesize,$index
*/
function get_pager_limit_sql($count,$pagesize,$index){
	$index = intval($index);
	if(empty($index)) $index = 1;
	$limit_start = $pagesize*($index-1) ;
	if($limit_start >= $count){
		$limit_start = 0 ;
	}
	return "$limit_start,$pagesize";
}





