        
<?php
header ( "Content-type:application/vnd.ms-excel" );
header ( "Content-Disposition:filename=shop_livechcount.xls" );
//接收信息
$createtime=$_GET['createtime'];
$endtime=$_GET['endtime'];
$livechannel=$_GET['livechannel'];

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
  if(!empty($livechannel)){
      $where.=" and livechannel ='$livechannel' ";
    }

$result = mysql_query("SELECT * FROM  auth_log_livechcount WHERE $WHERE $name_filt ");
//echo "SELECT * FROM  auth_log_livechcount WHERE $WHERE $name_filt";
//文件表格样式
echo "<table border='1'>
<tr>
<th>日期</th>
<th>频道</th>
<th>直播次数</th>
<th>直播人次</th>
<th>直播时长</th>
</tr>";
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . date('Y-m-d H:i:s ',$row['createtime'] ). "</td>";
  echo "<td>" . $row['livechannel'] . "</td>";
  echo "<td>" . $row['livecount'] . "</td>";
  echo "<td>" . $row['livepeoplecnt'] . "</td>";
  echo "<td>" . $row['livetime'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($con);

?>
