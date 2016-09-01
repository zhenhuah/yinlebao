<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>

<form name="myform" id="myform" action="?m=go3c&c=client&a=audio_edit_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><?php echo L('auth_id');?>:</th>
		  <td><?php echo $cid?></td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font>设置音频:</th>
		  <td>
		  	<select name="audio">
		  		<option value="HDMI" <?php if ($audio == 'HDMI') echo 'selected'?>>HDMI</option>
		  		<option value="AV" <?php if ($audio == 'AV') echo 'selected'?>>AV</option>
		  		<option value="ALL" <?php if ($audio == 'ALL') echo 'selected'?>>HDMI/AV</option>
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
	<input type="hidden" name="cid" id="cid" value="<?php echo $cid?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('cl_submit');?>" />&nbsp;
</div> 
</form>
</body>
</html>
