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
<form enctype="multipart/form-data" action="?m=go3c&c=client&a=add_upgrade_tencent_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return verifyForm()">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">

	<table width="60%" cellspacing="0" class="table_form" style="margin-left:20%">
		<tbody>	
			<tr>
				<th><p class="must">*</p>强制升级</th>
				<td>
					<input type="radio" name="force" value="0" checked>非强制升级
					<input type="radio" name="force" value="1" >强制升级
				</td>
			</tr>
			<tr>
				<th width="80"><p class="must">*</p>升级类型</th>
				<td>		
					<select id="upgrade_type" name="upgrade_type">
						<option value="APK">APK</option>
						<option value="FIRMWARE">FIRMWARE</option>
						<option value="MIDWARE">MIDWARE</option>
					</select>
				</td>
			</tr>
			<tr id="tr-versioncode">
				<th><p class="must">*</p>Versioncode</th>
				<td><input type="text" name="versioncode" id="versioncode" maxlength="10" onkeypress="return isNum(event)" >(格式: 2014091601)</td>
			</tr>
			<tr>
				<th>新版本名</th>
				<td><input type="text" name="version" ></td>
			</tr>
			<tr>
				<th style="border:none"><p class="must">*</p>版本URL</th>
				<td style="border:none"><input type="text" id="url" name="url" size="80">
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
					<input type="text" name="size" id="size" onkeypress="return isNum(event)">B (解压后的文件)
					<input type="hidden" id="zip_size" name="zip_size" value="" >
				</td>
			</tr>
			<tr>
				<th><p class="must">*</p>文件md5</th>
				<td>
					<input type="text" name="filemd5" id="filemd5" size="40">
				</td>
			</tr>
			<tr>
				<th><p class="must">*</p>版本描述</th>
				<td><textarea name="description" id="description" style="width:100%;height:200px"></textarea></td>
			</tr>
		</tbody>
	</table>

</div>
</div>
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="submit" class="button" name="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>
<script type="text/javascript">
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
			url:"?m=go3c&c=client&a=doajaxfileuploadtencent&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
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

/*function verifyForm() {
	//当必须填写versioncode时检查
	if ($('#tr-versioncode').css('display') != 'none') {
		var v = $('#versioncode').val();
		if (!v) {
			alert('请填写版本号');
			return false;
		} else if (v.length != 10) {
			alert('请填写10位完整版本号');
			$('#versioncode').focus();
			return false;	
		}
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
}*/

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
</script>