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

<form name="myform" id="myform" action="?m=go3c&c=shop&a=shop_addtaskdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">名称:</th>
		  <td>		
		  <input type="text" value="" name="title" id="title" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="80">路径:</th>
		  <td>		
		  <input type="text" value="" name="url" id="url" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="80">是否启用:</th>
		  <td>		
		  <select name="status">
				<option value="on">是</option>
				<option value="off">否</option>
			</select>
		</td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font>开始时间</th>
		  <td>
			<input type="text" value="" name="starttime" id="starttime" size="15" class="date" readonly><span id="starttime_error" style="display:none"><font color="red">请设置开始时间</font>
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "starttime",
				trigger    : "starttime",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>  
		</td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font>结束时间</th>
		  <td>
			<input type="text" value="" name="endtime" id="endtime" size="15" class="date" readonly><span id="endtime_error" style="display:none"><font color="red">请设置结束时间</font>
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "endtime",
				trigger    : "endtime",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>  
		</td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">
	<input type="hidden" name="mode" id="mode" value="shop_addtaskdo" />
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
