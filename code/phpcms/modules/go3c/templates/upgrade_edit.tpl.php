<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<style>
<!--
.must{color:red;width:5px;float:right}
-->
</style>
<form enctype="multipart/form-data" action="?m=go3c&c=client&a=edit_upgrade_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return verifyForm()">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">

	<table width="60%" cellspacing="0" class="table_form" style="margin-left:20%">
		<tbody>	
			<tr>
				<th width="80">升级类型<input type="hidden" name="upgrade_type" value="<?php echo $data['upgrade_type']?>"></th>
				<td><?php echo $data['upgrade_type']?></td>
			</tr>
			<tr>
				<th><p class="must">*</p>是否升级</th>
				<td>
					<input type="radio" name="force" value="0" <?php if ($data['is_force'] == 0) echo 'checked'?>>非强制升级
					<input type="radio" name="force" value="1" <?php if ($data['is_force'] == 1) echo 'checked'?>>强制升级
				</td>
			</tr>
			<tr>
				<th>升级方法</th>
				<td>
					<?php if ($data['is_force'] == 0) echo '全部升级'?>
					<?php if ($data['is_force'] == 1) echo '灰度升级'?>
				</td>
			</tr>
			<tr class="tr-all-upgrade">
				<th>平台类型</th>
				<td><?php echo $data['term_type']?></td>
			</tr>
			<tr class="tr-all-upgrade">
				<th>项目ID</th>
				<td><?php echo $data['cid']?></td>
			</tr>
			<tr class="tr-firmware" style="display:none">
				<th>板子型号</th>
				<td><?php echo $data['firmware_config']?></td>
			</tr>
			<tr class="tr-firmware" style="display:none">
				<th>FW名称</th>
				<td><input type="text" name="firmware_name" value="<?php echo $data['firmware_name']?>" /></td>
			</tr>
			<?php 
			$arr = array('APK','FIRMWARE','WEB_PHONE','ipad','apad','iphone','aphone');
			if (in_array($data['upgrade_type'], $arr)) {
			?>
			<tr>
				<th><p class="must">*</p>Versioncode</th>
				<td><input type="text" name="versioncode" id="versioncode" value="<?php echo $data['versioncode']?>" /></td>
			</tr>
			<?php } ?>
			<tr>
				<th>新版本名</th>
				<td><input type="text" name="version" value="<?php echo $data['version']?>" ></td>
			</tr>
			<tr>
				<th style="border:none">版本URL</th>
				<td style="border:none"><?php echo $data['url']?></td>
			</tr>
			<?php if ($data['upgrade_type'] == 'APK') {?>
			<tr class="tr-depend-versioncode">
				<th><p class="must">*</p>依赖的FIRMWARE Versioncode</th>
				<td><input type="text" name="depend_firmware_versioncode" id="depend_firmware_versioncode" value="<?php echo $data['depend_firmware']?>" onkeypress="return isNum(event)" ></td>
			</tr>
			<tr class="tr-depend-versioncode">
				<th><p class="must">*</p>依赖的曲库 Versioncode</th>
				<td><input type="text" name="depend_song_db_versioncode" id="depend_song_db_versioncode" value="<?php echo $data['depend_song_db']?>" onkeypress="return isNum(event)" ></td>
			</tr>
			<tr class="tr-depend-versioncode">
				<th><p class="must">*</p>依赖的WEBPHONE Versioncode</th>
				<td><input type="text" name="depend_web_phone_versioncode" id="depend_web_phone_versioncode" value="<?php echo $data['depend_web_phone']?>" onkeypress="return isNum(event)" ></td>
			</tr>
			<?php } else if ($data['upgrade_type'] == 'FIRMWARE' || $data['upgrade_type'] == 'SONG_DB' || $data['upgrade_type'] == 'WEB_PHONE') {?>
			<tr class="tr-depend-apk-versioncode">
				<th><p class="must">*</p>依赖的APK Versioncode</th>
				<td><input type="text" name="depend_apk_versioncode" id="depend_apk_versioncode" value="<?php echo $data['depend_apk']?>" onkeypress="return isNum(event)" ></td>
			</tr>
			<?php }?>
			<tr>
				<th><p class="must">*</p>文件大小</th>
				<td><input type="text" name="size" id="size" value="<?php echo $data['size']?>" >B (解压后的文件)</td>
			</tr>
			<!-- <tr>
				<th>文件MD5</th>
				<td><input type="text" name="filemd5" size="40" ></td>
			</tr> -->
			<tr>
				<th><p class="must">*</p>版本描述</th>
				<td><textarea name="description" id="description" style="width:100%;height:200px"><?php echo $data['description']?></textarea></td>
			</tr>
		</tbody>
	</table>

</div>
</div>
</div>
<div class="bk10"></div>
<input type="hidden" name="uid" value="<?php echo $data['id']?>" />
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
					var type = $('#upgrade_type').val();
					$.ajaxFileUpload
					(
						{
							url:"?m=go3c&c=client&a=doajaxfileupload&type="+type+"&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
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

$('#upgrade_type').change(function(){
	if ($(this).val() == 'FIRMWARE')
		$('.tr-firmware').show();
	else
		$('.tr-firmware').hide();
	if ($(this).val() == 'APK')
		$('.tr-depend-versioncode').show();
	else 
		$('.tr-depend-versioncode').hide();
})

$('#allterm, #allid').click(function(){
	if ($(this).attr('checked')) {
		$(this).siblings().attr('disabled', true).attr('checked', false);
	} else {
		$(this).siblings().attr('disabled', false).attr('checked', false);
	}
})

$('#upgrade-all').click(function(){
	$('.tr-all-upgrade').show();
	$('.tr-gray-upgrade').hide();
})

$('#upgrade-gray').click(function(){
	$('.tr-all-upgrade').hide();
	$('.tr-gray-upgrade').show();
})

$('#gray-term, #gray-id').change(function(){
	var t = $('#gray-term').val();
	var c = $('#gray-id').val();
	$.ajax({
		url: 'index.php?m=go3c&c=client&term=' + t + '&cid=' + c + '&a=get_group&pc_hash='+pc_hash,
		type: 'GET',
		dataType: 'json',
		success: function(data){
			console.log(data);
			var htm = '';
			for (var i in data) {
				htm += '<div class="checkbox-group">';
				htm += 		'<input type="checkbox" name="group[]" value="'+data[i].g_id+'">'+data[i].g_name+'<br>';
				htm += 		'<div id="group-'+data[i].g_id+'" style="margin-left: 16px;">';
				var devices = data[i].devices;
				for (var j in devices) {
					htm +=		'<input class="checkbox-device" type="checkbox" name="device[]" value="'+devices[j].d_guid+'">'+devices[j].d_guid+'<br>';
				}
				htm +=		'</div>';
				htm += '</div>';
			}
			$('#gray-upgrade-group').html(htm);
		}
	});
})

$('.checkbox-group > input').live('click', function(){
	var check = $(this).attr('checked');
	$(this).siblings('div').find('input').attr('checked', check);
})

$('#verify-file').live('click', function(){
	var url = $('#url').val();
	if (url)
		window.open($('#url').val());
	else 
		alert('请先输入文件链接或上传文件');
})

function verifyForm() {
	if (!$('#size').val()) {
		alert('请填写版本文件解压后真实大小');
		return false;
	}
	var des = $('#description').val();
	if (des.length < 10) {
		alert('请输入10个以上字符的描述');
		$('#description').focus();
		return false;
	}
}

$('#depend-apk').click(function(){
	var id = $(this).attr('id');
	if ($(this).attr('checked')) {
		$('.tr-' + id).show();
	} else {
		$('.tr-' + id).hide();
	}
})

</script>