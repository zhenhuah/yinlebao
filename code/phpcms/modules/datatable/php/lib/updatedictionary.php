<?php
define("DATATABLES", true, true);
require_once("content.php");

$modelid   = $_REQUEST['modelid'];	
$table   = $_REQUEST['table'];

/*
$sql5="select * from $table where id='03'";
echo $sql5."\n";
$res = mysql_fetch_assoc(mysql_query($sql5));
$tt = $res['parentid'];
var_dump($tt);
die();
*/
if(empty($table)||empty($modelid)){
	echo 'error,table or modelid is null';die();
}

$rescolumns = mysql_query("SHOW FULL COLUMNS FROM ".$table."") ;
while($row = mysql_fetch_array($rescolumns)){
  //echo '字段名称：'.$row['Field'].'-数据类型：'.$row['Type'].'-注释：'.$row['Comment'];
  //echo '<br/><br/>';
  
  //字段循环插入字典表
  $field = $row['Field'];
  $sql1="select * from v9_model_field where modelid='$modelid' and field='$field'";
  //echo $sql1;echo '<br/><br/>';
  
  $res = mysql_query($sql1);
  $num = mysql_num_rows($res);

  if($num){
	  echo 'error,Field already entered';
  }else{
	  $name = $row['Comment'];
	 $sql2="INSERT INTO `v9_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `minlength`, `maxlength`, `setting`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `isfulltext`, `isposition`, `listorder`, `disabled`, `isomnipotent`) VALUES('$modelid', 1, '$field', '$name', '', 0, 0, '', 0, 1, 0, 1, 0, 1, 1, 0, 0, 0, 0);";
	 
	 mysql_query($sql2);
	 $id = mysql_insert_id();
	 $sql3="update `v9_model_field` set listorder='$id' where fieldid='$id'";
	 //echo $sql3;
	 //echo '<br/><br/>';
	 mysql_query($sql3);
  }
  
}
echo 'success';
?>	
