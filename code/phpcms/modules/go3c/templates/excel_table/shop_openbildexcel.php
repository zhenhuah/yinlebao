        
<?php
header ( "Content-type:application/vnd.ms-excel" );
header('content-disposition:filename=shop_openbild.xls');

//接收信息
$mac_wire=$_GET['mac_wire'];
$starttime=$_GET['starttime'];
$starttime1=$_GET['starttime1'];

//查询数据库并取出数据显示
$con = mysql_connect("localhost","root","86985773");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("go3capi", $con);
mysql_query("SET NAMES 'UTF8'"); 
     
  $WHERE = "1 ";
 if(!empty($starttime)){
      $WHERE.=" AND starttime >=('$starttime') ";

    }

  if(!empty($starttime)){
      $WHERE.=" AND starttime <=('$starttime1') ";
      
    }
  if(!empty($mac_wire)){
      $WHERE.=" AND mac_wire ='$mac_wire' ";
  }

$result = mysql_query("SELECT * FROM  auth_log_openbox WHERE $WHERE ");
//echo "SELECT * FROM  auth_log_openbox WHERE $WHERE $name_filt";
//文件表格样式
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
