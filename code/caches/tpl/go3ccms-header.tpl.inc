<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>英乐宝后台管理中心</title>
<link href="http://go3c.jdjkcn.net/go3ccms/statics/css/reset.css" rel="stylesheet" type="text/css" />
<link href="http://go3c.jdjkcn.net/go3ccms/statics/css/zh-cn-system.css" rel="stylesheet" type="text/css" />
<link href="http://go3c.jdjkcn.net/go3ccms/statics/css/table_form.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="http://go3c.jdjkcn.net/go3ccms/statics/css/style/zh-cn-styles1.css" title="styles1" media="screen" />
<link rel="alternate stylesheet" type="text/css" href="http://go3c.jdjkcn.net/go3ccms/statics/css/style/zh-cn-styles2.css" title="styles2" media="screen" />
<link rel="alternate stylesheet" type="text/css" href="http://go3c.jdjkcn.net/go3ccms/statics/css/style/zh-cn-styles3.css" title="styles3" media="screen" />
    <link rel="alternate stylesheet" type="text/css" href="http://go3c.jdjkcn.net/go3ccms/statics/css/style/zh-cn-styles4.css" title="styles4" media="screen" />
<script language="javascript" type="text/javascript" src="http://go3c.jdjkcn.net/go3ccms/statics/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="http://go3c.jdjkcn.net/go3ccms/statics/js/admin_common.js"></script>
<script language="javascript" type="text/javascript" src="http://go3c.jdjkcn.net/go3ccms/statics/js/styleswitch.js"></script>
<script type="text/javascript">
window.focus();
var pc_hash = '<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>';
window.onload = function(){
var html_a = document.getElementsByTagName('a');
var num = html_a.length;
for(var i=0;i<num;i++) {
var href = html_a[i].href;
if(href && href.indexOf('javascript:') == -1) {
if(href.indexOf('?') != -1) {
html_a[i].href = href+'&pc_hash='+pc_hash;
} else {
html_a[i].href = href+'?pc_hash='+pc_hash;
}
}
}

var html_form = document.forms;
var num = html_form.length;
for(var i=0;i<num;i++) {
var newNode = document.createElement("input");
newNode.name = 'pc_hash';
newNode.type = 'hidden';
newNode.value = pc_hash;
html_form[i].appendChild(newNode);
}
}
</script>
</head>
<body>
<style type="text/css">
html{_overflow-y:scroll}
</style>