<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=shop&a=edit_shop_type_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return verify_form();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90">类型名称</th>
		  <td>
		  <input type="text" value="<?php echo $data['cat_name'] ?>" name="type_name" id="type_name" size="25" > <font color="red">*</font>
		  &nbsp;&nbsp;<span id="ad_type_name" style="display:none;"><font color="red">请填写类型</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">类型代号</th>
		  <td>
		  <input type="text" value="<?php echo $data['ctype'] ?>" name="ctype" id="ctype" size="25" >
		  </td>
		</tr>
		<tr>
		  <th width="90">上级类型</th>
		  <td>
			<select name="cat_id">
			<option value='0'>一级菜单</option>
            <?php {foreach($type_list as $key=>$v){?>
           		 <option value='<?php echo $v['cat_id']?>' <?php if($data['pid']==$v['cat_id']){ echo 'selected';}?>><?php echo $v['cat_name']?></option>
			<?php }} ?>
			</select>
		  </td>
		</tr>
		<tr>
		  <th width="90">下属应用数量</th>
		  <td>
		  <input type="text" value="<?php echo $data['count'] ?>" name="count" id="count" size="25" >
		  </td>
		</tr>
		<tr>
		  <th width="90">描述</th>
		  <td>
		  <textarea name="description" id="description"><?php echo $data['description'] ?></textarea>
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="id" id="id" value="<?php echo $data['cat_id'] ?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function verify_form()
{
	//类型名称
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
