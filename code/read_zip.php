<?php
require_once 'zipphp.php';
$archive  = new PHPZip();
$zipdir = '/home/wwwroot/default/go3ccms/xml/ktvdatabase';
$savename = '/home/wwwroot/default/go3ccms/newsongdb.zip';
//$archive->Zip($zipdir, $savename);

$zipfile = '/home/wwwroot/default/go3ccms/newsongdb_2014102923.zip';
$to ='/home/wwwroot/default/go3ccms';

$archive->unZip($zipfile, $to);

?>
