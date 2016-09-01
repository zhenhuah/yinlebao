	   	 	
<?php
/*header ( "Content-type:application/vnd.ms-excel" );
header('content-disposition:filename=php100.xls');
*/
//接收信息
$mac_wire=$_GET['strstarttime'];
$starttime=$_GET['strstarttime'];
$endtime=$_GET['endtime'];

//查询数据库并取出数据显示
$con = mysql_connect("localhost","root","86985773");
mysql_query("SET NAMES 'UTF8'"); 
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("go3capi", $con);

$result = mysql_query("SELECT * FROM  auth_log_openbox");

echo "<table border='1'>
<tr>
<th>序号</th>
<th>终端编号</th>
<th>安装地址</th>
<th>进入时间</th>

</tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['id'] . "</td>";
  echo "<td>" . $row['mac_wire'] . "</td>";
  echo "<td>" . $row['installaddress'] . "</td>";
  echo "<td>" . $row['starttime'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($con);

?>
