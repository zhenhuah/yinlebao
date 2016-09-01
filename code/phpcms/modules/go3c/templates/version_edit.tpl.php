<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>

<form name="myform" id="myform" action="?m=go3c&c=auth&a=edit_versiondo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font>版本</th>
		  <td>		
		  <select name="version">
		  	<option value="debug" <?php if ($data['version'] == 'debug') echo 'selected'?>>调试</option>
		  	<option value="demo" <?php if ($data['version'] == 'demo') echo 'selected'?>>演示</option>
		  	<option value="commercial" <?php if ($data['version'] == 'commercial') echo 'selected'?>>正式</option>
		  	<option value="manu" <?php if ($data['version'] == 'manu') echo 'selected'?>>工厂</option>
		  </select>
		</td>
		</tr>	
		<tr>
		  <th width="80">更换项目号</th>
		  <td>		
		  <select name="cid_new">
		  <?php foreach ($ainfo as $v) {?>
		  	<option value="<?php echo $v['ID']?>" <?php if ($v['ID'] == $cidtmp) echo 'selected'?>><?php echo $v['ID']?></option>
		  <?php }?>
		  </select>
		  <input type="hidden" name="cid_old" value="<?php echo $data['ID']?>" >
		</td>
		</tr>
		<tr>
			<th>遥控器型号</th>
			<td>
				<select name="remote" id="remote">
					<?php 
					if (!$data['remote_type']) 
						echo '<option value="" selected>默认</option>';
					foreach ($remoteArr as $v) {
						if ($v['type'] == $data['remote_type'])
							$selected = 'selected';
						else 
							$selected = '';
						echo '<option value="'.$v[type].'" '.$selected.'>'.$v[name].'</option>';
					}
					?>
				</select><br>
				<img id="img-remote" src="" style="display:none">
			</td>
		</tr>
		<tr>
		<th width="80">调试模式</th>
		<td>
		<input type="radio" name="debug_mode" value="0" <?php if ($data['debug_mode'] == '0') echo 'checked'?> >否&nbsp;&nbsp;
		<input type="radio" name="debug_mode" value="1" <?php if ($data['debug_mode'] == '1') echo 'checked'?> >是
		</td>
		</tr>
		<tr>
		<th width="80">是否允许重复鉴权</th>
		<td>
		<input type="radio" name="reg_repeat" value="0" <?php if ($data['reg_repeat'] == 0) echo 'checked'?> >否&nbsp;&nbsp;
		<input type="radio" name="reg_repeat" value="1" <?php if ($data['reg_repeat'] == 1) echo 'checked'?> >是
		</td>
		</tr>
		<tr>
		  <th width="80">版本描述</th>
		  <td>		
		  <textarea name="version_notice" rows="4" cols="40"><?php echo $data['version_notice']?></textarea>
		</td>
		</tr>		
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="vid" value="<?php echo $data['vid']?>" />
	<input type="hidden" name="mode" id="mode" value="edit_versiondo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('cl_submit');?>" />&nbsp;
</div> 
</form>
<script type="text/javascript">
$('#remote').change(function(){
	var type = $(this).val();
	$.ajax({
		url: 'index.php?m=go3c&c=auth&a=getRemoteImg&type='+type+'&pc_hash='+pc_hash,
		type: 'GET',
		//dataType: 'txt',
		success: function(data){
			console.log(data);
			$('#img-remote').attr('src', data).show();
		}
	})
})
</script>
</body>
</html>
