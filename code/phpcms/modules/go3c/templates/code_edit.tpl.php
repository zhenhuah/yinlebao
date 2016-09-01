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

<form name="myform" id="myform" action="?m=go3c&c=auth&a=edit_codedo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font>客户名称:</th>
		  <td>		
		 	<select id="type_id" name="type_id">
	            <?php foreach ($auth as $key => $ptvalue) {?>
				<option value='<?php echo $ptvalue['cid']?>' <?php if($ptvalue['cid']==$data['type_id']) echo 'selected';?>><?php echo $ptvalue['ID']?></option>
				<?php }?>
			</select>
		  &nbsp;&nbsp;<span id="ad_ID" style="display:none;"><font color="red">客户名称不能为空</font></span>
		</td>
		</tr>
		<tr>
		  <th width="80">项目名称:</th>
		  <td>		
		  <select id="client_id" name="client_id">
	            <?php foreach ($valid as $key => $ptvalue) {?>
				<option value='<?php echo $ptvalue['vid']?>' <?php if($ptvalue['vid']==$data['client_id']) echo 'selected';?>><?php echo $ptvalue['ID']?></option>
				<?php }?>
			</select>
		</td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font>授权码:</th>
		  <td>
		  <input type="text" value="<?php echo $data['title']?>" name="title" id="title" size="10" >
		  &nbsp;&nbsp;<span id="ad_title" style="display:none;"><font color="red">授权码不能为空</font></span>
		  </td>
		</tr>				
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="edit_codedo" />
	<input type="hidden" name="id" id="id" value="<?php echo $data['id']?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function subtitle()
{
	var type_id = $.trim($('#type_id').val());
	if (type_id != '')
	{
		$('#ad_ID').hide();
	}else{
		$('#ad_ID').show();
		return false;
	}
	var "title" = $.trim($('#"title"').val());
	if ("title" != '')
	{
		$('#ad_title').hide();
	}else{
		$('#ad_title').show();
		return false;
	}
}
</script>
</body>
</html>
