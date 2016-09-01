<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="addContent" style="background:#FFF; width:100%;">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">服务器监控管理
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="col-1">
<div class="content pad-6">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb" href="javascript:mak_nagios('nagios1')"><em>监控状态总览</em></a> |
		<a class="add fb" href="javascript:mak_nagios('nagios2')"><em>监控升级服务器</em></a> |
		<a class="add fb" href="javascript:mak_nagios('nagios3')"><em>监控接口服务器</em></a> |	
<a class="add fb" href="javascript:mak_nagios('nagios4')"><em>监控网页服务器</em></a> |
<a class="add fb" href="javascript:mak_nagios('nagios5')"><em>监控数据库服务器</em></a> |
<a class="add fb" href="javascript:mak_nagios('nagios6')"><em>监控图片服务器</em></a> |
</div>
<div class="table-list">
<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
	function mak_nagios(type){
		location.href ='?m=go3c&c=tvuser&a=mak_nagios&type='+type
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
</script>
	</div>
</div>      
</div>
</div>
</body>
</html>
