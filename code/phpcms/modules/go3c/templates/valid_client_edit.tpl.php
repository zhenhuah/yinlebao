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

<form name="myform" id="myform" action="?m=go3c&c=auth&a=edit_cilentdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font>客户名称:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['ID']?>" name="ID" id="ID" size="30" >
		  &nbsp;&nbsp;<span id="ad_ID" style="display:none;"><font color="red">客户名称不能为空</font></span>
		</td>
		</tr>
		<tr>
		  <th width="80">终端类型:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['term_type']?>" name="term_type" id="term_type" size="30" >
		</td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font>加密key</th>
		  <td>
		  <input type="text" value="<?php echo $data['key']?>" name="key" id="key" size="30" >
		  &nbsp;&nbsp;<span id="ad_key" style="display:none;"><font color="red">加密key不能为空</font></span>
		  </td>
		</tr>	
		<tr>
		  <th width="80">申请日期</th>
		  <td>
		  <input type="text" value="<?php echo $data['apply_time']?>" name="apply_time" id="apply_time" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "apply_time",
				trigger    : "apply_time",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>
		  </td>
		</tr>
		<tr>
		  <th width="80">IP</th>
		  <td>
		  <input type="text" value="<?php echo $data['ip']?>" name="ip" id="ip" size="30" >
		  </td>
		</tr>
		<tr>
		  <th width="80">区域</th>
		  <td>
		  <input type="text" value="<?php echo $data['area']?>" name="area" id="area" size="10" >
		  </td>
		</tr>		
		<tr>
		  <th width="80">芯片</th>
		  <td>
		  <input type="text" value="<?php echo $data['chip']?>" name="chip" id="chip" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">RAM</th>
		  <td>
		  <input type="text" value="<?php echo $data['ram']?>" name="ram" id="ram" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">ROM</th>
		  <td>
		  <input type="text" value="<?php echo $data['rom']?>" name="rom" id="rom" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">WIFI</th>
		  <td>
		  <input type="text" value="<?php echo $data['wifi']?>" name="wifi" id="wifi" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">BT</th>
		  <td>
		  <input type="text" value="<?php echo $data['bt']?>" name="bt" id="bt" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">音频</th>
		  <td>
		  <input type="text" value="<?php echo $data['audio']?>" name="audio" id="audio" size="10" >
		  </td>
		</tr>
		<tr>
		  <th width="80">RJ45_MAC</th>
		  <td>
		  <input type="text" value="<?php echo $data['RJ45_MAC']?>" name="RJ45_MAC" id="RJ45_MAC" size="10" >
		  </td>
		</tr>
		<tr>
		  <th width="80">spdif</th>
		  <td>
		  <input type="checkbox" name="spdif" id="spdif" <?php if ($data['spdif']) echo 'checked'?>>
		  </td>
		</tr>			
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="vid" id="vid" value="<?php echo $data['vid']?>" />
	<input type="hidden" name="mode" id="mode" value="edit_cilentdo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function subtitle()
{
	var ID = $.trim($('#ID').val());
	if (ID != '')
	{
		$('#ad_ID').hide();
	}else{
		$('#ad_ID').show();
		return false;
	}
	var key = $.trim($('#key').val());
	if (key != '')
	{
		$('#ad_key').hide();
	}else{
		$('#ad_key').show();
		return false;
	}
}
</script>
</body>
</html>
