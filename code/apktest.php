<?php
header(‘Content-type:text/xml’);
echo '123';
require('apk_parser.php');
$p=newApkParser();
$res=$p->open('7po_yule.apk');
echo $p->getXML();
?>