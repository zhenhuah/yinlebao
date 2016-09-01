<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="table-list" id="load_priv">
<form name="myform" id="myform" action="?m=admin&c=role&a=editdo_spid&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return addarea();">
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<tr>
<th width="90">项目代号</th>
<td>
<input type="text" value="<?php echo $data['spid'];?>" name="spid" id="spid" size="25" >
<span id="ad_spid" style="display:none"><font color="red">请正确填写项目代号</font>
</td>
</tr>
<tr>
<th width="90">项目客户名称</th>
<td>
<input type="text" value="<?php echo $data['custname'];?>" name="custname" id="custname" size="25" >
</td>
</tr>
<tr>
<th width="90">项目描述</th>
<td>
<textarea id="custdesc" style="height:46px;" name="custdesc"><?php echo $data['custdesc'];?></textarea>
</td>
</tr>
<tr>
<th width="90">邮箱</th>
<td>
<input type="text" value="<?php echo $data['email'];?>" name="email" id="email" size="25" >
</td>
</tr>
<tr>
<th width="90">电话</th>
<td>
<input type="text" value="<?php echo $data['tel'];?>" name="tel" id="tel" size="25" >
</td>
</tr>
<tr>
<th width="90">QQ</th>
<td>
<input type="text" value="<?php echo $data['qq'];?>" name="qq" id="qq" size="25" >
</td>
</tr>
<tr>
<th width="90">手机</th>
<td>
<input type="text" value="<?php echo $data['phone'];?>" name="phone" id="phone" size="25" >
</td>
</tr>
<tr>
<th width="90">客户web</th>
<td>
<input type="text" value="<?php echo $data['web'];?>" name="web" id="web" size="25" >
</td>
</tr>
</tbody>
</table>
<div class="btn">
<input type="hidden" name="id" id="id" value="<?php echo $data['id'];?>" />	
<input type="submit"  class="button" name="dosubmit" id="dosubmit" value="<?php echo L('submit');?>" /></div>
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
//添加信息表单验证
function addspid()
{
	var spid = $.trim($('#spid').val());
	if (spid != '')
	{
		$('#ad_spid').hide();
	}else{
		$('#ad_spid').show();
		return false;
	}
	return true;
}
</script>
</body>
</html>