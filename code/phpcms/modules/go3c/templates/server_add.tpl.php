<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform"
	action="?m=go3c&c=message&a=addservicedo&pc_hash=<?php echo $_SESSION['pc_hash'];?>"
	method="post" enctype="multipart/form-data" onSubmit="return checkAdserv();">
	<div class="addContent" style="background: #FFF; width: 100%;">
		<div class="col-1">
			<div class="content pad-6">
				<table width="100%" cellspacing="0" class="table_form">
					<tbody>
						<tr>
							<th width="80">服务器名</th>
							<td>
							<input type="text" value="" name="SERVICE_TITLE" id="SERVICE_TITLE" size="25" >
								<span id="ad_SERVICE_TITLE" style="display:none"><font color="red">请填写服务器名</font> </span>
							</td>
						</tr>
						<tr>
							<th width="90">服务器类型</th>
							<td>
								<input type="text" name="SERVICE_TYPE" />
							</td>
						</tr>
						<tr>
							<th width="90">服务器IP</th>
							<td>
							<input type="text" value="" name="SERVICE_IP" id="SERVICE_IP" size="25" >
							<span id="ad_SERVICE_IP" style="display:none"><font color="red">请填写服务器ip</font> </span>
							</td>
						</tr>
						<tr>
							<th width="90">服务器端口</th>
							<td>
								<input type="text" name="SERVICE_PORT" />
							</td>
						</tr>
						<tr>
							<th width="90">启动服务器</th>
							<td>
								<input type="checkbox" name="SERVICE_STATU" value="launched" checked="checked" />
							</td>
						</tr>
						<tr>
							<th width="90">范围</th>
							<td>
								<input type="text" name="SERVICE_SCOPE1" size="10" />&nbsp;到&nbsp;
								<input type="text" name="SERVICE_SCOPE2" size="10" />
							</td>
						</tr>
						<tr>
							<th></th>
							<td>(必填，如：0 到 100000)</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="bk10"></div>
	<div style="float: right">
		<input type="hidden" name="mode" id="mode" value="addmesdo" /> <input
			type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
	</div>
</form>
<script type="text/javascript">
function checkAdserv()
{
	var SERVICE_TITLE = $.trim($('#SERVICE_TITLE').val());
	var SERVICE_IP = $.trim($('#SERVICE_IP').val());
	var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
	var reg = SERVICE_IP.match(exp);
	if(reg == null){
		var ErrMsg="你输入的是一个非法的IP地址段！\nIP段为：:xxx.xxx.xxx.xxx（xxx为0-255)！" 
		alert(ErrMsg);
		return false;
	}
	if (SERVICE_TITLE != '')
	{
		$('#ad_SERVICE_TITLE').hide();
	}else{
		$('#ad_SERVICE_TITLE').show();
		return false;
	}
	if (SERVICE_IP != '')
	{
		$('#ad_SERVICE_IP').hide();
	}else{
		$('#ad_SERVICE_IP').show();
		return false;
	}
}
</script>
</body>
</html>
