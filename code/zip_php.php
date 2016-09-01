<?php
require_once 'zipphp.php';
$spid = $_GET['spid'];
$board_type = $_GET['board_type'];
//$spid = 'GO3CKTVTEST';
//$board_type = 'A20';
$url="/home/wwwroot/default/upgrade/android/stb/".$spid."/".$board_type."/Recommon_Data/appresources.zip";
unlink($url);
$zipdir="/home/wwwroot/default/upgrade/android/stb/".$spid."/".$board_type."/Recommon_Data/cache";
$savename = $url;
$archive  = new PHPZip();
$archive->Zip($zipdir, $savename);
//升级路径下压缩包
$url2="/home/wwwroot/default/download/KTV_Data/appresources.zip";
unlink($url2);
$zipdir2 ="/home/wwwroot/default/upgrade/android/stb/".$spid."/".$board_type."/Recommon_Data/cache";
$sjxml = "/home/wwwroot/default/upgrade/android/stb/".$spid."/".$board_type."/Recommon_Data/cache/datanew/version.xml";
$version = date("YmdH",time());
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<upgrade>\n";
$xml .= "<code>1</code>\n";
$xml .="<description>\n";
$xml .="初始化数据\n";
$xml .="</description>\n";
$xml .="<versioncode>".$version."</versioncode>\n";
$xml .="<must>0</must>\n";
$xml .="<size>1234</size>\n";
$xml .="</upgrade>\n";
$fp=fopen("$sjxml","w");
fwrite($fp,$xml);
@fclose($fp);
$savename2 = $url2;
$archive->Zip($zipdir2, $savename2);
echo 'ok!';
?>
