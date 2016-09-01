<?php
define('PHPCMS_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
include PHPCMS_PATH.'/phpcms/base.php';
@unlink('./index.html');
@unlink('./js.html');
pc_base::creat_app();
?>