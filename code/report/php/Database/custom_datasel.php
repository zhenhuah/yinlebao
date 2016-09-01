<?php
require_once("content.php");

$id = $_POST['id'];
//$id = 3;
//根据报表id查询对应的表
$sql1="select dataSource from auth_report_format where report_id ='$id' limit 1";
$res = mysql_query($sql1);
while ($row = mysql_fetch_assoc($res)) {
	$dataSource = $row['dataSource'];
}
//根据取出的表名去表里面取出数据
$sql2="select * from $dataSource where 1 order by id asc";
$res = mysql_query($sql2);
while ($row = mysql_fetch_assoc($res)) {
	$data2[] = $row;
}
//$st = json_encode(array('message' =>$data2,'status'=>'success'));
//var_dump($st);
echo json_encode(array('message' =>$data2,'status'=>'success'));
?>	
