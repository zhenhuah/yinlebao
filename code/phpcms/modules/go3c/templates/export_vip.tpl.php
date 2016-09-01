<?php
header ( "Content-type:application/vnd.ms-excel" );
header ( "Content-Disposition:filename=vips_" . time() . ".xls" );
$txt = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<title>无标题文档</title>
<style>
td{
    text-align:center;
    font-size:12px;
    font-family:Arial, Helvetica, sans-serif;
    border:#1C7A80 1px solid;
    color:#152122;
    width:100px;
}
table,tr{
    border-style:none;
}
.title{
    background:#7DDCF0;
    color:#FFFFFF;
    font-weight:bold;
}
</style>
</head>
 
<body>
<table width='800' border='1'>
  <tr>
    <td class='title'>VIP账号</td>
    <td class='title'>VIP密码</td>
  </tr>";

foreach ($vips as $v) {
	$txt .= "<tr><td>" . $v['user_id'] . "</td><td>" . $v['vip_pwd'] . "</td></tr>";
}

$txt .= "
</table>
</body>
</html>
";

echo $txt;
?>