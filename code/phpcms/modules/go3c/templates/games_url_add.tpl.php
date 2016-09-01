<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>

<form name="myform" id="myform" action="?m=go3c&c=games&a=add_game_url_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return verify_form();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90">栏目</th>
		  <td>
		   	游戏链接
		  </td>
		</tr>
		<tr>
		  <th width="90">url</th>
		  <td>
		  <input type="text" value="" name="url" id="url" size="25" > <font color="red">*</font>
		  &nbsp;&nbsp;<span id="ad_type_name" style="display:none;"><font color="red">请填写链接</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">版本</th>
		  <td>
		  <input type="text" value="" name="version" id="version" size="25" >
		  </td>
		</tr>
		<tr>
		  <th width="90">大小</th>
		  <td>
		  <input type="text" value="" name="size" id="size" size="25" >
		  </td>
		</tr>
		<tr>
		  <th width="90">发布日期</th>
		  <td>
		  <input type="text" value="" name="release_date" id="release_date" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "release_date",
				trigger    : "release_date",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>
		  </td>
		</tr>
		<tr>
		  <th width="90">排序</th>
		  <td>
		  <input type="text" value="" name="sort" id="sort" size="25" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="gameid" value="<?php echo $gameid ?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function verify_form()
{
	//类型名称
	var type_name = $.trim($('#url').val());
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
