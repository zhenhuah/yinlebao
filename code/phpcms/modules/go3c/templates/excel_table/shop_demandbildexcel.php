        
<?php
header ( "Content-type:application/vnd.ms-excel" );
header ( "Content-Disposition:filename=shop_demandbild.xls" );
//接收信息
$mac_wire=$_GET['mac_wire'];
$createtime=$_GET['createtime'];
$endtime=$_GET['endtime'];
$showtype=$_GET['showtype'];

//查询数据库并取出数据显示
$con = mysql_connect("localhost","root","86985773");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("go3capi", $con);
mysql_query("SET NAMES 'UTF8'"); 
     
  $WHERE = "1 ";
  $name_filt  = $endtime ? " AND createtime <= unix_timestamp('$endtime')" : '';
  if(!empty($createtime)){
      $WHERE.="AND  createtime >=UNIX_TIMESTAMP('$createtime') ";
  }
  if(!empty($mac_wire)){
      $WHERE.=" AND mac_wire ='$mac_wire' ";
  }
  if(!empty($showtype)){
      $WHERE.=" and showtype ='$showtype' ";
    }

$result = mysql_query("SELECT * FROM  auth_log_demandbox WHERE $WHERE $name_filt ");
//echo "SELECT * FROM  auth_log_demandbox WHERE $WHERE $name_filt";
//文件表格样式
echo "<table border='1'>
<tr>
<th>终端编号</th>
<th>安装地址</th>
<th>节目类型</th>
<th>节目名</th>
<th>进入时间</th>
<th>退出时间</th>
<th>停留时间</th>

</tr>";
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['mac_wire'] . "</td>";
  echo "<td>" . $row['installaddress'] . "</td>";
  echo "<td>" . $row['showtype'] . "</td>";
  echo "<td>" . $row['showname'] . "</td>";
  echo "<td>" . date('Y-m-d H:i:s ',$row['createtime'] ). "</td>";
  echo "<td>" . date('Y-m-d H:i:s ',$row['endtime'] ). "</td>";
  echo "<td>" . $row['waittime'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($con);

?>
