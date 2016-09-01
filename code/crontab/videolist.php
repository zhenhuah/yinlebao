<?php
header("Content-type: text/html; charset=utf-8");
require_once 'videophpcms.php';

$method = $_GET['m'];
$type = $_GET['type'];
$date = $_GET['date'];

if ($method == 'getvideolist'){
	$datamenu = getvideolist($type,$date);
	$datamenu = json_encode($datamenu);
	echo $datamenu;
}

//获取视频上下线数据列表
function getvideolist($type,$date) {
	$con = connectsqlphpcms();
	$where = "1 ";
	if($type =='online'){
		$where.= "and online='1' ";
	}elseif($type =='offline'){
		$where.= "and offline='1' ";
	}else{
		
	}
	if(!empty($date)){
		$where.= "and updatetime='$date' ";
	}
	$sql1 = "select * from v9_video_log where ".$where;
	$res = mysql_query($sql1);
	$category = array();
	$column_category = array();
	while ($var = mysql_fetch_array($res)) {
		$category['asset_id'] = $var['asset_id'];
		$category['title'] = $var['title'];
		$category['column_id'] = $var['column_id'];
		$category['ispackage'] = $var['ispackage'];
		$category['parent_id'] = $var['parent_id'];
		$category['episode_number'] = $var['episode_number'];
		$category['director'] = $var['director'];
		$category['actor'] = $var['actor'];
		$category['updatetime'] = $var['updatetime'];
		array_push($column_category, $category);
	}
	return $column_category;
}

?>
