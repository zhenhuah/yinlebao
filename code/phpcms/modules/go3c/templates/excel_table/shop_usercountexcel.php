        
<?php
header ( "Content-type:application/vnd.ms-excel" );
header ( "Content-Disposition:filename=shop_usercount.xls" );
//接收信息
$createtime=$_GET['createtime'];
$endtime=$_GET['endtime'];
//查询数据库并取出数据显示
$con = mysql_connect("localhost","root","86985773");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("go3capi", $con);
mysql_query("SET NAMES 'UTF8'"); 
     
  $WHERE = "1 ";
  $name_filt  = $endtime ? " AND createtime <=('$endtime')" : '';
  if(!empty($createtime)){
      $WHERE.="AND  createtime >=('$createtime') ";
  }

$result = mysql_query("SELECT * FROM  auth_log_usercount WHERE $WHERE $name_filt ");
//var_dump($result);die;
//echo "SELECT * FROM  auth_log_demandbox WHERE $WHERE $name_filt";
//文件表格样式
echo "<table border='1'>
<tr>
<th>日期</th>
<th>总用户量</th>
<th>开机用户量</th>
<th>新增用户量</th>
<th>用户活跃率</th>
</tr>";
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
   echo "<td>" .$row['createtime'] . "</td>";
  echo "<td>" . $row['usercount'] . "</td>";
  echo "<td>" . $row['opencount'] . "</td>";
  echo "<td>" . $row['newcount'] . "</td>";
  echo "<td>" . $row['activereach'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($con);

?>
