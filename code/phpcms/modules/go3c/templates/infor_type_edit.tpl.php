<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=infor&a=editinfortypedo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data" onSubmit="return infortype();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90">栏目</th>
		  <td>
		   	资讯类型
		  </td>
		</tr>
		<tr>
		  <th width="90">资讯类型</th>
		  <td>
		  <input type="text" value="<?php echo $limitInfo['type_name'];?>" name="type_name" id="type_name" size="25" >
		  &nbsp;&nbsp;<span id="ad_type_name" style="display:none;"><font color="red">请填写类型</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">权重排序</th>
		  <td>
		  <input type="text" value="<?php echo $limitInfo['listorder'];?>" name="listorder" id="listorder" size="25" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">
<input type="hidden" name="id" id="id" value="<?php echo $limitInfo['id'];?>" />
	<input type="submit" class="button" value="保存" name="dosubmit">
</div> 
</form>
<script type="text/javascript">
function infortype()
{
	//文件路径
	var type_name = $.trim($('#type_name').val());
	if (type_name != '')
	{
		$('#ad_type_name').hide();
	}else{
		$('#ad_type_name').show();
		return false;
	}
}
</script>
</body>
</html>
