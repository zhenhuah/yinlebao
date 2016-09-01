<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>

<form name="myform" id="myform" action="?m=go3c&c=auth&a=shop_editbinddo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">用户id:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['userid']?>" name="userid" id="userid" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="80">身份证:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['cardid']?>" name="cardid" id="cardid" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="80">手机号:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['phonenumber']?>" name="phonenumber" id="phonenumber" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="80">地址:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['installaddress']?>" name="installaddress" id="installaddress" size="40" >
		</td>
		</tr>
		<tr>
		  <th width="80">guid:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['guid']?>" name="guid" id="guid" size="40" >
		</td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">
	<input type="hidden" name="mode" id="mode" value="shop_editbinddo" />
	<input type="hidden" name="id" id="id" value="<?php echo $id?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('ktv_sub');?>" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function menu_form()
{
	//类型名称
	var m_name_zh = $.trim($('#m_name_zh').val());
	if (m_name_zh != '')
	{
		$('#ad_m_name_zh').hide();
	}else{
		$('#ad_m_name_zh').show();
		return false;
	}
}
</script>
</body>
</html>
