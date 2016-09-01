<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
if ($_SESSION['upgrade_role'] == 'NONE') {
	showmessage('您没有任何版本升级权限, 请联系系统管理员获得权限','index.php?m=go3c&c=client&a=upgrade_list');
}
?>
<style>
<!--
.must{color:red;width:5px;float:right}
-->
</style>
<input type="hidden" id="session-upgrade-role" value="<?php echo $_SESSION['upgrade_role']?>">
<form enctype="multipart/form-data" action="?m=go3c&c=client&a=add_upgrade_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return verifyForm()">
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
				<th><p class="must">*</p>升级方法</th>
				<td>
					<input type="radio" name="gray" id="upgrade-all" value="0" checked>全部升级
					<input type="radio" name="gray" id="upgrade-gray" value="1" >灰度升级
				</td>
			</tr>
			<tr class="tr-all-upgrade">
				<th><p class="must">*</p>平台类型</th>
				<td>
					<input type="checkbox" name="allterm" id="allterm">全部&nbsp;&nbsp;
					<input type="checkbox" name="term_type[]" id="ALL-A20" value="A20">A20&nbsp;&nbsp;
					<input type="checkbox" name="term_type[]" id="ALL-MX8726" value="MX8726">MX8726&nbsp;&nbsp;
					<input type="checkbox" name="term_type[]" id="ALL-A31S" value="A31S">A31S&nbsp;&nbsp;
					<input type="checkbox" name="term_type[]" id="ALL-S805" value="S805">S805&nbsp;&nbsp;
					<?php if ($_SESSION['upgrade_role'] == 'ALL' || $_SESSION['upgrade_role'] == 'APK') {?>
					<input type="checkbox" name="ipad" id="ipad" value="ipad">ipad&nbsp;&nbsp;
					<input type="checkbox" name="apad" id="apad" value="apad">apad&nbsp;&nbsp;
					<input type="checkbox" name="iphone" id="iphone" value="iphone">iphone&nbsp;&nbsp;
					<input type="checkbox" name="aphone" id="aphone" value="aphone">aphone&nbsp;&nbsp;
					<?php }?>
				</td>
			</tr>
			<tr class="tr-all-upgrade">
				<th><p class="must">*</p>项目ID</th>
				<td id="sp-all">
					<input type="checkbox" name="allid" id="allid">全部&nbsp;&nbsp;
					<?php foreach ($ainfo as $v) {?>
					<input type="checkbox" name="ID[]" value="<?php echo $v['ID']?>" ><?php echo $v['ID']?>&nbsp;&nbsp;
					<?php }?>
				</td>
			</tr>
			<tr class="tr-gray-upgrade" style="display:none">
				<th><p class="must">*</p>平台类型</th>
				<td>
					<select name="gray-term-type" id="gray-term">
						<option value="A20">A20</option>
						<option value="MX8726">MX8726</option>
						<option value="A31S">A31S</option>
						<option value="S805">S805</option>
						<?php if ($_SESSION['upgrade_role'] == 'ALL' || $_SESSION['upgrade_role'] == 'APK') {?>
						<option value="ipad">ipad</option>
						<option value="apad">apad</option>
						<option value="iphone">iphone</option>
						<option value="aphone">aphone</option>
						<?php }?>
					</select>
				</td>
			</tr>
			<tr class="tr-gray-upgrade" style="display:none">
				<th><p class="must">*</p>项目ID</th>
				<td>
					<select name="gray-ID" id="gray-id">
		            <?php {foreach($ainfo as $key=>$v){?>
		           		 <option value='<?php echo $v['ID']?>' <?php if($_GET['ID']==$v['ID']) echo 'selected';?>><?php echo $v['ID']?></option>
					<?php }} ?>
					</select>
				</td>
			</tr>
			<tr class="tr-gray-upgrade" style="display:none">
				<th><p class="must">*</p>设备列表</th>
				<td id="gray-upgrade-group">
					<?php
					foreach ($agroup as $v) {?>
					<input class="checkbox-group" type="checkbox" name="group[]" value="<?php echo $v['g_id']?>" ><?php echo $v['g_name']?><br>
					<?php }?>
				</td>
			</tr>
			<tr>
				<th width="80"><p class="must">*</p>升级类型</th>
				<td>		
					<?php 
					if ($_SESSION['upgrade_role'] == 'ALL') {
					?>
					<select id="upgrade_type" name="upgrade_type">
						<option value="APK">APK</option>
						<option value="FIRMWARE">FIRMWARE</option>
						<option value="SONG_DB">曲库数据</option>
						<option value="APP_BOOT">开机数据</option>
						<option value="APP_HOTKEY">业务数据</option>
						<option value="SONG_HOT">歌曲数据</option>
						<option value="WEB_PHONE">手机客户端</option>
					</select>
					<div id="upgrade_padphone" style="display:none">客户端</div>
					<?php 	
					} else {
						switch ($_SESSION['upgrade_role']) {
							case 'APK':
								$type = 'APK';
								break;
							case 'FIRMWARE':
								$type = 'FIRMWARE';
								break;
							case 'SONG_DB':
								$type = '曲库数据';
								break;
							case 'APP_BOOT':
								$type = '开机数据';
								break;
							case 'APP_HOTKEY':
								$type = '业务数据';
								break;
							case 'SONG_HOT':
								$type = '歌曲数据';
								break;
							case 'WEB_PHONE':
								$type = '手机客户端';
								break;
						}
						echo $type . '<input type="hidden" name="upgrade_type" value="' . $_SESSION['upgrade_role'] . '" />';
					}
					?>
				</td>
			</tr>
			<tr class="tr-firmware" style="display:none">
				<th><p class="must">*</p>板子型号</th>
				<td>
					<select name="firmware_config" id="firmware_config" >
						<option class="fw-config-a20" style="display:none" value="KT601B-HK-003" selected>KT601B-HK-003</option>
						<option class="fw-config-a20" style="display:none" value="KT601B-CH-002">KT601B-CH-002</option>
						<option class="fw-config-a20" style="display:none" value="KT601A-BX-003">KT601A-BX-003</option>
						<option class="fw-config-mx8726" style="display:none" value="KT801B-HK-003" selected>KT801B-HK-003</option>
						<option class="fw-config-S805" style="display:none" value="KT801B-HK-004" selected>KT801B-HK-004</option>
					</select>
				</td>
			</tr>
			<tr class="tr-firmware" style="display:none">
				<th>FW名称</th>
				<td><input type="text" name="firmware_name" ></td>
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
			<tr class="tr-depend-versioncode">
				<th><p class="must">*</p>依赖的FIRMWARE Versioncode</th>
				<td><input type="text" name="depend_firmware_versioncode" id="depend_firmware_versioncode" onkeypress="return isNum(event)" ></td>
			</tr>
			<tr class="tr-depend-versioncode">
				<th><p class="must">*</p>依赖的曲库 Versioncode</th>
				<td><input type="text" name="depend_song_db_versioncode" id="depend_song_db_versioncode" onkeypress="return isNum(event)" ></td>
			</tr>
			<tr class="tr-depend-versioncode">
				<th><p class="must">*</p>依赖的WEBPHONE Versioncode</th>
				<td><input type="text" name="depend_web_phone_versioncode" id="depend_web_phone_versioncode" onkeypress="return isNum(event)" ></td>
			</tr>
			<tr class="tr-depend-apk-versioncode" style="display:none">
				<th><p class="must">*</p>依赖的APK Versioncode</th>
				<td><input type="text" name="depend_apk_versioncode" id="depend_apk_versioncode" onkeypress="return isNum(event)" ></td>
			</tr>
			<!-- <tr>
				<th>文件MD5</th>
				<td><input type="text" name="filemd5" size="40" ></td>
			</tr> -->
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
					var type = $('#upgrade_type').val();
					var upgradeAll = $('#upgrade-all').attr('checked');
					var arr =[];    
				    var str='';
				    var arrid =[];    
				    var strid='';
					if (upgradeAll) {
						$("input[name='term_type[]']:checked").each(function(){    
					         arr.push({term_type:$(this).val()});
					         str += $(this).val()+",";
					     });
						$("input[name='ID[]']:checked").each(function(){    
							arrid.push({ID:$(this).val()});
							strid += $(this).val()+",";
					     });
					     if ($('#allid').attr('checked')) {
					    	 $("#allid").siblings().each(function(){    
									arrid.push({ID:$(this).val()});
									strid += $(this).val()+",";
							     });
						    }
					} else {
						str = $('#gray-term').val();
						strid = $('#gray-id').val();
					}
					$.ajaxFileUpload
					(
						{
							url:"?m=go3c&c=client&a=doajaxfileupload&type="+type+"&term="+str+"&spid="+strid+"&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
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
											$('#zip_size').val(data.size);
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

function termChangeBoard() {
	var upgradeAll = $('#upgrade-all').attr('checked');
	if (upgradeAll) {
		var allA20 = $('#ALL-A20').attr('checked');
		var allMX8726 = $('#ALL-MX8726').attr('checked');
		var allS805 = $('#ALL-S805').attr('checked');
		if (allA20)
			$('.fw-config-a20').show();
		else
			$('.fw-config-a20').hide();
		if (allMX8726)
			$('.fw-config-mx8726').show();
		else
			$('.fw-config-mx8726').hide();
		if (allS805)
			$('.fw-config-S805').show();
		else
			$('.fw-config-S805').hide();
	} else {
		var grayTerm = $('#gray-term').val();
		if (grayTerm == 'A20') {
			$('.fw-config-a20').show();
			$('.fw-config-mx8726').hide();
			$('.fw-config-S805').hide();
			$('#upgrade_padphone').hide();
			$('#upgrade_type').show();
		} else if (grayTerm == 'MX8726') {
			$('.fw-config-a20').hide();
			$('.fw-config-mx8726').show();
			$('.fw-config-S805').hide();
			$('#upgrade_padphone').hide();
			$('#upgrade_type').show();
		}else if (grayTerm == 'S805') {
			$('.fw-config-a20').hide();
			$('.fw-config-mx8726').hide();
			$('.fw-config-S805').show();
			$('#upgrade_padphone').hide();
			$('#upgrade_type').show();
		} else if (grayTerm == 'ipad' || grayTerm == 'apad' || grayTerm == 'iphone' || grayTerm == 'aphone') {
			$('#upgrade_padphone').show();
			$('#upgrade_type').hide();
		}
	}
}
				
$('#upgrade_type').change(function(){
	var v = $(this).val();
	if (v == 'FIRMWARE') {
		$('.tr-firmware').show();
		termChangeBoard();
	}
	else
		$('.tr-firmware').hide();
	if (v == 'APK') {
		$('.tr-depend-versioncode').show();
		$('.tr-depend-apk-versioncode').hide();
	} else if (v == 'FIRMWARE' || v == 'SONG_DB' || v == 'WEB_PHONE') {
		$('.tr-depend-apk-versioncode').show();
		$('.tr-depend-versioncode').hide();
	} else {
		$('.tr-depend-versioncode').hide();
		$('.tr-depend-apk-versioncode').hide();
	}
	var upgradeRole = $('#session-upgrade-role').val();
	if (upgradeRole == 'ALL'){
		if (v == 'APK' || v == 'FIRMWARE' || v == 'WEB_PHONE')
			$('#tr-versioncode').show();
		else
			$('#tr-versioncode').hide();
	}
})

$('#ALL-A20, #ALL-MX8726, #ALL-A31S, #ALL-S805').change(function(){
	if ($('#upgrade_type').val() == 'APK' || $('#upgrade_type').val() == 'FIRMWARE') {
		if ($(this).attr('checked')) {
			$(this).siblings().attr('checked', false);
		}
		termChangeBoard();
		$('#upgrade_padphone').hide();
		$('#upgrade_type').show();
		if ($('#upgrade_type').val() == 'FIRMWARE')
			$('.tr-firmware').show();
		else
			$('.tr-firmware').hide();
	}
})

$('#ipad,#apad,#iphone,#aphone').change(function(){
	if ($(this).attr('checked')){
		$(this).siblings().attr('checked', false);
		$('#upgrade_padphone').show();
		$('#upgrade_type').hide();
		$('.tr-firmware').hide();
		$('#tr-versioncode').show();
	} else {
		$('#upgrade_padphone').hide();
		$('#upgrade_type').show();
		var upgradeRole = $('#session-upgrade-role').val();
		var upgradeType = $('#upgrade_type').val();
		if (upgradeRole == 'ALL' && (upgradeType == 'APK' || upgradeType == 'FIRMWARE' || upgradeType == 'WEB_PHONE')) {
			$('#tr-versioncode').show();
		} else 
			$('#tr-versioncode').hide();
	}
})

$('#allterm, #allid').click(function(){
	var id = $(this).attr('id');
	if (id == 'allterm' && ($('#upgrade_type').val() == 'APK' || $('#upgrade_type').val() == 'FIRMWARE')) {
		$(this).attr('checked', false);
		//return false;
	}
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
	if (t == 'ipad' || t == 'apad' || t == 'iphone' || t == 'aphone') {
		$('#upgrade_padphone').show();
		$('#upgrade_type').hide();
	} else {
		$('#upgrade_padphone').hide();
		$('#upgrade_type').show();
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
	}
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
	if ($('#upgrade-all').attr('checked')) {
		var checkTerm = $('#allterm').parent().find('input:checked');
		if (!checkTerm.length) {
			alert('请勾选要升级的平台');
			return false;
		}
		var checkCid = $('#allid').parent().find('input:checked');
		if (!checkCid.length) {
			alert('请勾选要升级的项目');
			return false;
		}
	}
	if ($('#upgrade-gray').attr('checked')) {
		var checkDevice = $('.checkbox-group > div input:checked');
		var grayTerm = $('#gray-term').val();
		if (!checkDevice.length && grayTerm != 'ipad' && grayTerm != 'iphone' && grayTerm != 'apad' && grayTerm != 'aphone') {
			alert('请勾选设备');
			return false;
		}
	}
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
	var t = $('#upgrade_type').val();
	if (t == 'APK') {
		var dependFw = $('#depend_firmware_versioncode').val();
		var dependSongdb = $('#depend_song_db_versioncode').val();
		var dependWebphone = $('#depend_web_phone_versioncode').val();
		if (!dependFw) {
			alert('请填写APK依赖的FIRMWARE版本');
			$('#depend_firmware_versioncode').focus();
			return false;
		}
		if (!dependSongdb) {
			alert('请填写APK依赖的曲库版本');
			$('#depend_song_db_versioncode').focus();
			return false;
		}
		if (!dependWebphone) {
			alert('请填写APK依赖的WEB PHONE版本');
			$('#depend_web_phone_versioncode').focus();
			return false;
		}
		var term = '';
		if ($('#upgrade-all').attr('checked'))
			term = $('#allterm').siblings('input:checked').val();
		else
			term = $('#gray-term').val();
		if (dependFw) {
			//如果填写了依赖的fw版本 则检查数据库里是否有填写的这个版本的fw 提示先去添加fw升级信息并审核
			$.ajax({
				url: 'index.php?m=go3c&c=client&a=checkDependFw&v='+dependFw+'&t='+term+'&pc_hash='+pc_hash,
				type: 'GET',
				dataType: 'json',
				success: function(data){
					//data = $.parseJSON(data);
					console.log(data);
					if (!data.length) {
						alert('APK依赖的该FIRMWARE版本在所选平台下还不存在, 请先去添加填写的FIRMWARE版本或者填写其他版本的FIRMWARE');
						$('#depend_firmware_versioncode').val('').focus();
						return false;
					}
					var max = data.length;
					var noUpgradeStr = '';
					if ($('#upgrade-all').attr('checked')) {
						var allid = $('#allid').siblings('input:checked');
						var len = allid.length;
						for (var i = 0; i < len; i++) {
							var id = allid[i].defaultValue;
							//console.log(id);
							for (var j = 0; j < max; j++) {
								if (id == data[j]){
									break;
								} else if (j == max - 1) {
									noUpgradeStr = noUpgradeStr + id + ' ';
								}
							}
						}
					} else {
						var grayCid = $('#gray-id').val();
						for (var j = 0; j < max; j++) {
							if (grayCid == data[j]){
								break;
							} else if (j == max - 1) {
								noUpgradeStr = noUpgradeStr + grayCid + ' ';
							}
						}
					}
					if (noUpgradeStr) {
						alert('在所选平台下的 '+noUpgradeStr+'项目下, APK依赖的该FIRMWARE版本还不存在, 请先去添加填写的FIRMWARE版本或者填写其他版本的FIRMWARE');
						$('#depend_firmware_versioncode').val('').focus();
						return false;
					}
				}
			});
		}
		if (dependSongdb) {
			//如果填写了依赖的fw版本 则检查数据库里是否有填写的这个版本的fw 提示先去添加fw升级信息并审核
			$.ajax({
				url: 'index.php?m=go3c&c=client&a=checkDependSongdb&v='+dependSongdb+'&t='+term+'&pc_hash='+pc_hash,
				type: 'GET',
				dataType: 'json',
				success: function(data){
					//data = $.parseJSON(data);
					console.log(data);
					if (!data.length) {
						alert('APK依赖的该SONG_DB版本在所选平台下还不存在, 请先去添加填写的SONG_DB版本或者填写其他版本的SONG_DB');
						$('#depend_song_db_versioncode').val('').focus();
						return false;
					}
					var max = data.length;
					var noUpgradeStr = '';
					if ($('#upgrade-all').attr('checked')) {
						var allid = $('#allid').siblings('input:checked');
						var len = allid.length;
						for (var i = 0; i < len; i++) {
							var id = allid[i].defaultValue;
							//console.log(id);
							for (var j = 0; j < max; j++) {
								if (id == data[j]){
									break;
								} else if (j == max - 1) {
									noUpgradeStr = noUpgradeStr + id + ' ';
								}
							}
						}
					} else {
						var grayCid = $('#gray-id').val();
						for (var j = 0; j < max; j++) {
							if (grayCid == data[j]){
								break;
							} else if (j == max - 1) {
								noUpgradeStr = noUpgradeStr + grayCid + ' ';
							}
						}
					}
					if (noUpgradeStr) {
						alert('在所选平台下的 '+noUpgradeStr+'项目下, APK依赖的该SONG_DB版本还不存在, 请先去添加填写的SONG_DB版本或者填写其他版本的SONG_DB');
						$('#depend_song_db_versioncode').val('').focus();
						return false;
					}
				}
			});
		}
	} else if (t == 'SONG_DB' || t == 'FIRMWARE' || t == 'WEB_PHONE') {
		var dependApk = $('#depend_apk_versioncode').val();
		if (!dependApk) {
			alert('请填写依赖的APK版本');
			$('#depend_apk_versioncode').focus();
			return false;
		}
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

$(function(){
	var upgradeRole = $('#session-upgrade-role').val();
	if (upgradeRole == 'ALL' || upgradeRole == 'APK' || upgradeRole == 'FIRMWARE' || upgradeRole == 'WEB_PHONE')
		$('#tr-versioncode').show();
	else
		$('#tr-versioncode').hide();
})

</script>