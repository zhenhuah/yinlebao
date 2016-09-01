<?php
require_once("content.php");

//查询出所有按钮类型的集合
$sql1="select title from t_buttonlist group by title";
$data1 = array();
$res = mysql_query($sql1);
while ($row = mysql_fetch_assoc($res)) {
	$data1[] = $row;
}
$data2 = array();
//遍历每个类型下的所有按钮
foreach($data1 as $v){
	$title = $v['title'];
	$sql2="select id,name,value,classname,depend,callback from t_buttonlist where title='$title' order by seq asc";
	$res2 = mysql_query($sql2);
	while ($row2 = mysql_fetch_assoc($res2)) {
		$stn = explode(",",$row2['name']);
		$row4['name'] = $stn;
		$row4['value'] = $row2['value'];
		$row4['classname'] = $row2['classname'];
		$row4['callback'] = $row2['callback'];
		$id = $row2['id'];
		if($row2['depend']=='on'){
			$sql3="select source,operator,conditions from t_buttonlist where id='$id'";
			$res3 = mysql_query($sql3);
			while ($row3 = mysql_fetch_assoc($res3)) {
				$data3 = array();
				$st = explode(",",$row3['conditions']);
				$row3['condition'] = $st;
				$data3[] = $row3;
			}
			$row4['depend'] = $data3;
		}
		$data2[$title][] = $row4;
		$row4 = array();
	}
}
$data2 =json_encode($data2);
echo $data2;
?>	
