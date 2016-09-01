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

<form name="myform" id="myform" action="?m=go3c&c=auth&a=add_authdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font><?php echo L('auth_id');?>:</th>
		  <td>		
		  <input type="text" value="" name="ID" id="ID" size="30" >
		  &nbsp;&nbsp;<span id="ad_ID" style="display:none;"><font color="red"><?php echo L('auth_not_null');?></font></span>
		</td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('term_type');?>:</th>
		  <td>		
		  <input type="text" value="" name="term_type" id="term_type" size="30" >
		</td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('s_max');?></th>
		  <td>
		  <input type="text" value="" name="limit_max" id="limit_max" size="10" >(<?php echo L('client_num');?>)
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('cl_date');?></th>
		  <td>
		  <input type="text" value="" name="expiry_date" id="expiry_date" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "expiry_date",
				trigger    : "expiry_date",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>
		  </td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font><?php echo L('des_key');?></th>
		  <td>
		  <input type="text" value="" name="auth_key" id="auth_key" size="30" >(<?php echo L('eg_auth');?>)
		   &nbsp;&nbsp;<span id="ad_auth_key" style="display:none;"><font color="red"><?php echo L('key_null');?></font></span>
		  </td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('jarea');?></th>
		  <td>
		  <input type="text" value="" name="area" id="area" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">CPU</th>
		  <td>
		  <input type="text" value="" name="cpu" id="cpu" size="10" >
		  </td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('auth_info');?>:</th>
		  <td>		
		  <textarea name="GO3C" id="GO3C"></textarea>
		</td>
		</tr>			
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="add_authdo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('cl_submit');?>" />&nbsp;
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
	var auth_key = $.trim($('#auth_key').val());
	if (auth_key != '')
	{
		$('#ad_auth_key').hide();
	}else{
		$('#ad_auth_key').show();
		return false;
	}
}
</script>
</body>
</html>
