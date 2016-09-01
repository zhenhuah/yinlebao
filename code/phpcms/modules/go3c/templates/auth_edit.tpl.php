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

<form name="myform" id="myform" action="?m=go3c&c=auth&a=edit_authdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font><?php echo L('auth_id');?>:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['ID']?>" name="ID" id="ID" size="30" >
		  &nbsp;&nbsp;<span id="ad_ID" style="display:none;"><font color="red"><?php echo L('auth_not_null');?></font></span>
		</td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('term_type');?>:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['term_type']?>" name="term_type" id="term_type" size="30" >
		</td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('s_max');?></th>
		  <td>
		  <input type="text" value="<?php echo $data['limit_max']?>" name="limit_max" id="limit_max" size="10" >(<?php echo L('client_num');?>)
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('cl_date');?></th>
		  <td>
		  <input type="text" value="<?php echo $data['expiry_date']?>" name="expiry_date" id="expiry_date" size="10" class="date" >
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
		  <th width="80"><?php echo L('auth_start');?></th>
		  <td>
		  <input type="text" value="<?php echo $data['auth_start']?>" name="auth_start" id="auth_start" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "auth_start",
				trigger    : "auth_start",
				dateFormat: "%Y-%m-%d",
				showTime: true,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('auth_end');?></th>
		  <td>
		  <input type="text" value="<?php echo $data['auth_end']?>" name="auth_end" id="auth_end" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "auth_end",
				trigger    : "auth_end",
				dateFormat: "%Y-%m-%d",
				showTime: true,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>
		  </td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('jarea');?></th>
		  <td>
		  <input type="text" value="<?php echo $data['area']?>" name="area" id="area" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">CPU</th>
		  <td>
		  <input type="text" value="<?php echo $data['cpu']?>" name="cpu" id="cpu" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('auth_info');?>:</th>
		  <td>		
		  <textarea name="GO3C" id="GO3C">
		  <?php 
		  if (strpos($data['ID'], 'GO3C')!==false){
		  	echo $data['GO3C'];
		  }else{
		  	echo $data['PROJ'];
		  }
		  ?>
		  </textarea>
		</td>
		</tr>		
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="cid" id="cid" value="<?php echo $data['cid']?>" />
	<input type="hidden" name="mode" id="mode" value="edit_authdo" />
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
	/*var auth_key = $.trim($('#auth_key').val());
	if (auth_key != '')
	{
		$('#ad_auth_key').hide();
	}else{
		$('#ad_auth_key').show();
		return false;
	}*/
}
</script>
</body>
</html>
