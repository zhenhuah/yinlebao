<?php
/**
 *  extention.func.php 用户自定义函数库
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-10-27
 */
 
/**
 *样式表
 */
function common_css(){
	echo '
	<style type="text/css">
	*{font-size:12px;}
	ul{
		padding:0;
		margin:0 auto;
	}
	ul li{
		width:300px;
		height:30px;
		background:#DDDDDD;
		border-right: solid 1px #666666;
		border-bottom: solid 1px #666666;
		margin:2px 5px 10px 0;
	}
	ul li a{
	    text-decoration:none;
	  	padding:5px;
	  	color:#000;
	  	font-size:16px;
	  	font-family:"微软雅黑";
	}
	</style>';
}


?>