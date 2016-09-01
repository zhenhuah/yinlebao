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

<form name="myform" id="myform" action="?m=go3c&c=auth&a=shop_editipdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">起始ip:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['ipstart']?>" name="ipstart" id="ipstart" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="80">结束ip:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['ipend']?>" name="ipend" id="ipend" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="80">是否启用:</th>
		  <td>		
		      <select id="status" name="status">
			  <option value='on' <?php if($data['status']=='on') echo 'selected';?>>启用</option>
			  <option value='off' <?php if($data['status']=='off') echo 'selected'?>>不启用</option>
			  </select>
		  </td>
		</tr>
		<tr>
		  <th width="80">所在类型:</th>
		  <td>		
		      <select id="iptype" name="iptype">
			  <option value='white' <?php if($data['iptype']=='white') echo 'selected';?>>白名单</option>
			  <option value='black' <?php if($data['iptype']=='black') echo 'selected'?>>黑名单</option>
			  </select>
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">
	<input type="hidden" name="mode" id="mode" value="shop_addipdo" />
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
