<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="table-list" id="load_priv">
<form name="myform" id="myform" action="?m=admin&c=clear_memcache&a=addmatchepgdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return addmatchepg();">
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<tr>
<th width="90">名称:</th>
<td>
<input type="text" value="" name="name" id="name" size="25" >
<span id="ad_name" style="display:none"><font color="red">请填写名称</font>
</td>
</tr>
<tr>
<th width="90">所属栏目:</th>
<td>
<select name="column_id" id="column_id">
		  <option value="0">请选择</option>
		   <?php {foreach($column_list as $key=>$column){?>
           		 <option value='<?php echo $column['id']?>';><?php echo $column['title']?></option>
			<?php }} ?>
		  </select>
<span id="ad_column_id" style="display:none"><font color="red">请选择栏目</font>
</td>
</tr>
<tr>
<th width="90">排序:</th>
<td>
<input type="text" value="" name="seq_number" id="seq_number" size="25" >
</td>
</tr>
</tbody>
</table>
<div class="btn"><input type="submit"  class="button" name="dosubmit" id="dosubmit" value="<?php echo L('submit');?>" /></div>
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
//添加信息表单验证
function addmatchepg()
{
	var name = $.trim($('#name').val());
	if (name != '')
	{
		$('#ad_name').hide();
	}else{
		$('#ad_name').show();
		return false;
	}
	return true;
}
</script>
</body>
</html>
