<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=ktv&a=addrunRecommdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return runrecom();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90"> <font color="red">*</font>推荐位 </th>
		  <td>
		  <select name="rids" id="rids">
				<option value="">请选择</option>
				<?php if(!empty($list)){foreach($list as $row){?>
					
					<option value="<?php echo $row['rid'];?>"><?php echo $row['title']?></option>
					<?php }} ?>
			</select>
			&nbsp;&nbsp;<span id="title_add_error" style="display:none;"><font color="red">请选择推荐位</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90"> <font color="red">*</font>类型 </th>
		  <td>
		  <select name="type_ids" id="type_ids" >
				<option value="">请选择</option>
		 		<option value="song">歌曲</option>
		 		<option value="singer">歌星</option>
		 		<option value="album">专辑</option>
			</select>&nbsp;&nbsp;<span id="type_id_error" style="display:none;"><font color="red">请选择类型</font></span>
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function runrecom(){
	var rids = $.trim($('#rids').val());
	if (rids != '')
	{
		$('#title_add_error').hide();
	}else{
		$('#title_add_error').show();
		return false;
	}
	var type_ids = $.trim($('#type_ids').val());
	if (type_ids != '')
	{
		$('#type_id_error').hide();
	}else{
		$('#type_id_error').show();
		return false;
	}
}
</script>
</body>
</html>
