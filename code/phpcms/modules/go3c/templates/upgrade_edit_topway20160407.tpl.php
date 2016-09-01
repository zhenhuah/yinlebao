<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<style>
<!--
.must{color:red;width:5px;float:right}
-->
</style>
<input type="hidden" id="session-upgrade-role" value="<?php echo $_SESSION['upgrade_role']?>">
<form enctype="multipart/form-data" action="?m=go3c&c=upgrade&a=edit_upgrade_topwaydo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return verifyForm()">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">

	<table width="60%" cellspacing="0" class="table_form" style="margin-left:20%">
		<tbody>	
			<tr>
				<th><p class="must">*</p>强制升级</th>
				<td>
					<input type="radio" name="force" value="0" <?php if ($upgrade['is_force'] == 0) echo 'checked'?>>非强制升级
					<input type="radio" name="force" value="1" <?php if ($upgrade['is_force'] == 1) echo 'checked'?>>强制升级
				</td>
			</tr>
			<tr>
				<th><p class="must">*</p>升级方法</th>
				<td>
					<input type="radio" name="gray" id="upgrade-all" value="0" <?php if ($upgrade['is_gray'] == 0) echo 'checked'?>>全部升级
					<input type="radio" name="gray" id="upgrade-gray" value="1" <?php if ($upgrade['is_gray'] == 1) echo 'checked'?>>灰度升级
				</td>
			</tr>
			<tr class="tr-cid-upgrade">
				<th><p class="must">*</p>渠道项目</th>
				<td>
					<select name="cid" id="cid">
						<option value="OTT" <?php if ($upgrade['cid'] == "OTT") echo 'select'?>>OTT</option>
						<option value="PVS" <?php if ($upgrade['cid'] == "PVS") echo 'select'?>>PVS</option>
					</select>
				</td>
			</tr>
			<tr class="tr-chid-upgrade">
				<th><p class="must">*</p>产品编号</th>
				<td>
					<select name="chid" id="chid">
						<option value="14000">14000</option>
					</select>
				</td>
			</tr>
			<tr class="tr-term-upgrade">
				<th><p class="must">*</p>硬件平台</th>
				<td>
					<select name="gray_term_type" id="gray_term">
						<option value="66039" <?php if ($upgrade['term_type'] == "66039") echo 'select'?>>66039(S812 2G 8G)</option>
						<option value="66050" <?php if ($upgrade['term_type'] == "66050") echo 'select'?>>66050(S812 1G 8G)</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="80"><p class="must">*</p>升级类型</th>
				<td>		
					<select id="upgrade_type" name="upgrade_type">
						<option value="FIRMWARE">FIRMWARE</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>升级模式</th>
				<td>
					<input type="radio" name="is_mode" id="upgrade-modeall" value="1" <?php if ($upgrade['is_mode'] == 1) echo 'checked'?>>全量
					<input type="radio" name="is_mode" id="upgrade-modegray" value="2" <?php if ($upgrade['is_mode'] == 2) echo 'checked'?>>增量
				</td>
			</tr>
			<tr class="tr-gray-mode" style="display:none">
				<th width="80">本地版本</th>
				<td>		
					<select id="versionfr" name="versionfr">
						<?php
						foreach ($upgradeversion as $v) {?>
						<option value="<?php echo $v['versioncode']?>" <?php if ($upgrade['versionfr'] == $v['versioncode']) echo 'select'?>><?php echo $v['versioncode']?></option>
						<?php }?>
					</select> to 
					<select id="versionto" name="versionto">
						<?php
						foreach ($upgradeversion as $v) {?>
						<option value="<?php echo $v['versioncode']?>" <?php if ($upgrade['versionto'] == $v['versioncode']) echo 'select'?>><?php echo $v['versioncode']?></option>
						<?php }?>
					</select>
				</td>
			</tr>
			<tr class="tr-gray-upgrade" style="display:none">
				<th><p class="must">*</p>设备列表</th>
				<td id="gray-upgrade-group">
					<?php
					foreach ($agroup as $v) {?>
					<input class="checkbox-group" type="checkbox" name="group[]" value="<?php echo $v['mac_wire']?>" style="cursor:pointer;margin-right:15px;">&nbsp;<?php echo $v['mac_wire']?>&nbsp;&nbsp;
					<?php }?>
				</td>
			</tr>
			<tr id="tr-versioncode">
				<th><p class="must">*</p>Versioncode</th>
				<td><input type="text" name="versioncode" value="<?php echo $upgrade['versioncode']?>" id="versioncode" maxlength="10">(格式: 1.2.4)</td>
			</tr>
			<tr>
				<th>新版本名</th>
				<td><input type="text" name="version" size="20" value="<?php echo $upgrade['version']?>"></td>
			</tr>
			<tr>
				<th><p class="must">*</p>依赖的FIRMWARE</th>
				<td>
					<input type="text" name="rely_version" id="rely_version" size="20" value="<?php echo $upgrade['rely_version']?>">
				</td>
			</tr>
			<tr>
				<th style="border:none"><p class="must">*</p>版本URL</th>
				<td style="border:none"><input type="text" id="url" name="url" size="80" value="<?php echo $upgrade['url']?>">
				<input type="button" value="验证文件" id="verify-file"></td>
			</tr>
			<tr><td align="right" style="border:none">或者</td></tr>
			<tr>
				<th>上传版本</th>
				<td>
					<!--  <input type="file" id="file" name="apk" > -->
					<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
					<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
				</td>
			</tr>
			<tr>
				<th><p class="must">*</p>文件大小</th>
				<td>
					<input type="text" name="size" id="size" value="<?php echo $upgrade['size']?>" onkeypress="return isNum(event)">B (解压后的文件)
					<input type="hidden" id="zip_size" name="zip_size" value="<?php echo $upgrade['size']?>" >
				</td>
			</tr>
			<tr>
				<th><p class="must">*</p>文件md5</th>
				<td>
					<input type="text" name="filemd5" id="filemd5" size="40" value="<?php echo $upgrade['filemd5']?>">
				</td>
			</tr>
			<tr>
				<th><p class="must">*</p>版本描述</th>
				<td><textarea name="description" id="description" style="width:100%;height:200px"><?php echo $upgrade['description']?></textarea></td>
			</tr>
		</tbody>
	</table>

</div>
</div>
</div>
<div class="bk10"></div>
<input type="hidden" name="id" value="<?php echo $upgrade['id']?>" />
<div  style="float:right">	
	<input type="submit" class="button" name="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>
<script type="text/javascript">
$('#upgrade-modegray').click(function(){
	$('.tr-gray-mode').show();
})
$('#upgrade-modeall').click(function(){
	$('.tr-gray-mode').hide();
})
$('#upgrade-all').click(function(){
	$('.tr-all-upgrade').show();
	$('.tr-gray-upgrade').hide();
})

$('#upgrade-gray').click(function(){
	$('.tr-all-upgrade').hide();
	$('.tr-gray-upgrade').show();
})
$('#verify-file').live('click', function(){
	var url = $('#url').val();
	if (url)
		window.open($('#url').val());
	else 
		alert('请先输入文件链接或上传文件');
})
function ajaxFileUpload()
{
	//优化 start 
	var fileValue = jQuery('#fileToUpload').val();
	if (fileValue != '')
	{
		//验证文件类型					
		var filepath=fileValue; 								
		var extStart=filepath.lastIndexOf('.'); 
		var ext=filepath.substring(extStart,filepath.length).toUpperCase(); 
		if((ext != '.APK') && (ext != '.ZIP') && (ext != '.RAR'))
		{ 
			alert("图片文件限于apk/zip/rar格式"); 
			return false; 
		}
	}else{
		alert('请选择图片!');
		return false;
	}
	//优化 end
	$("#loading")
	.ajaxStart(function(){
		$(this).show();
	})
	.ajaxComplete(function(){
		$(this).hide();
	});
	
	$.ajaxFileUpload
	(
		{
			url:"?m=go3c&c=task&a=doajaxfileuploadtopway&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
			secureuri:false,
			fileElementId:'fileToUpload',
			dataType: 'json',
			success: function (data, status)
			{
				console.log(data);
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}else
					{
						if (data.msg != '0')
						{
							$('#url').val(data.msg);
							alert('上传成功!');
						}else{
							alert('上传失败!');											
						}
					}
				}
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)
	return false;
}
//文件上传验证
function checkUpload(fileObj)
{
	ajaxFileUpload();
	return false;
}
function isNum(e) {
    var k = window.event ? e.keyCode : e.which;
    if (((k >= 48) && (k <= 57)) || k == 8 || k == 0) {
    } else {
        if (window.event) {
            window.event.returnValue = false;
        }
        else {
            e.preventDefault();
        }
    }
}
function verifyForm() {
		var v = $('#versioncode').val();
		if (!v) {
			alert('请填写版本号');
			return false;
		}
		
	if (!$('#url').val()) {
		alert('请填写版本文件路径或上传版本文件');
		$('#url').focus();
		return false;
	}
	var trueSize = $('#size').val();
	if (!trueSize) {
		alert('请填写版本文件解压后真实大小');
		$('#size').focus();
		return false;
	}
	var des = $('#description').val();
	if (des.length < 10) {
		alert('请输入10个以上字符的描述');
		$('#description').focus();
		return false;
	}
}
</script>