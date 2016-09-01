<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<style type="text/css">
.demo{width:450px}
.select_side{float:left; width:200px}
#selectL,#selectR{width:180px; height:150px}
.select_opt{float:left; width:40px; height:100%; margin-top:36px}
.select_opt p{width:26px; height:26px; margin-top:6px; background:url(<?php echo IMG_PATH;?>arr.gif) no-repeat; cursor:pointer; text-indent:-999em}
.select_opt p#toright{background-position:2px 0}
.select_opt p#toleft{background-position:2px -22px}
.sub_btn{clear:both; height:42px; line-height:42px; padding-top:10px; text-align:center}
</style>
<form name="myform" id="myform" action="?m=go3c&c=client&a=upgradeBootAdd&pc_hash=<?php echo $_SESSION['pc_hash'];?>" onSubmit="return verifyForm()" method="post" enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><?php echo L('advert_spid');?>:</th>
			<td>
				  <div class="demo">
				     <div class="select_side">
				     <p><?php echo L('advert_fav_spid');?></p>
				     <?php 
						if($_SESSION['roleid'] != '1')
							$cidarr = explode(',', $_SESSION['spid']);
						else
							$cidarr = array('GO3CKTV','GO3CKTVTEST','ZYDQ','WMMY','FSTLM','SSDQ');
				     ?>
				     <select id="selectL" multiple="multiple">
				     	<?php foreach ($cidarr as $v) {?>
				     		<option value="<?php echo $v?>"><?php echo $v?></option>
				     	<?php }?>
				     </select>
				     </div>
				     <div class="select_opt">
				        <p id="toright" title="添加">&gt;</p>
				        <p id="toleft" title="移除">&lt;</p>
				     </div>
				     <div class="select_side">
				     <p><?php echo L('advert_chec_spid');?></p>
				     <select id="selectR" multiple="multiple">
				     	
				     </select>
				     </div>
				     <input type="hidden" value="" id="cidStr" name="cidStr">
				  </div>
			</td>
		</tr>
		<tr>
			<th><?php echo L('advert_type');?></th>
			<td>
				<select name="bootType" id="bootType">
					<option value="BOOTANIMATION"><?php echo L('advert_boot_an');?></option>
					<option value="APP_BACKGROUND_IMGS"><?php echo L('advert_boot_pic');?></option>
					<option value="APP_WIZARDS"><?php echo L('advert_boot_gu');?></option>
				</select>
			</td>
		</tr>
		<tr id="view_ImgText" class="default-upload" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> <?php echo L('advert_file_link');?> </th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
			<input type="text" value="" name="ad_imgUrl" id="ad_imgUrl" size="25" >	
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
						
					}else{
						alert('请选择文件!');
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
							url:"?m=go3c&c=task&a=doajaxfileupload&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
							secureuri:false,
							fileElementId:'fileToUpload',
							dataType: 'json',
							success: function (data, status)
							{
								if(typeof(data.error) != 'undefined')
								{
									if(data.error != '')
									{
										alert(data.error);
									}else
									{
										if (data.msg != '0')
										{
											//alert(data.msg);												
											var web_url = 'http://www.go3c.tv:8060/go3ccms/' + data.msg;
											$('#ad_imgUrl').val(web_url);
											//浏览
											$('#viewImgUrl').attr('target','_blank');	//设置另一个页面打开
											$('#viewImgUrl').attr('href',web_url);	//设置
											$('#viewImgUrl').show();
											$('#regtype').show();
											jQuery('#fileToUpload').val('');
											jQuery('#buttonUpload').hide();
											alert('上传成功!');
										}else{
											$('#viewImgUrl').hide();
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
					if(fileObj.value != '') 
					{
						//var pathimg = document.getElementById("new_img");
						//var path = pathimg.src;	
						//验证文件类型					
						var filepath=fileObj.value; 								
						var extStart=filepath.lastIndexOf('.'); 
						var ext=filepath.substring(extStart,filepath.length).toUpperCase(); 
						if (ext != '.ZIP') {
							alert('请上传ZIP格式的压缩包');
							return false;
						}
						
						//方式一
						//jQuery('#buttonUpload').show();

						//方法二
						ajaxFileUpload();
					}
					return false;
				}
				</script>
				 </td>
		</tr>
		<tr class="default-upload">
			<th width="90"><?php echo L('ktv_up_file');?></th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			<?php echo L('ktv_file_1');?>
			<span id="ad_imgUrl_error" style="display:none;"><font color="red"><?php echo L('ktv_file_2');?></font></span>
		  </td>
		</tr>
		<tr class="default-upload">
		<th width="90"><?php echo L('ch_file');?></th>
		  <td>
		  <a href="###" style="display:none;" id="viewImgUrl"><font color="blue"><?php echo L('ktv_pre');?></font></a>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font><?php echo L('advert_valid');?>
		  <span id="ad_regtype_error" style="display:none;"><font color="red"><?php echo L('advert_che_valid');?></font></span>
		  </td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font><?php echo L('advert_boot_pic');?></th>
			<td><input type="file" name="file_app_animation" id="file_app_animation"></td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font><?php echo L('advert_boot_cqpic');?></th>
			<td><input type="file" name="file_app_restart_animation" id="file_app_restart_animation"></td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font><?php echo L('advert_boot_qhpic');?></th>
			<td><input type="file" name="file_app_return_animation" id="file_app_return_animation"></td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font><?php echo L('advert_vod_qhpic');?></th>
			<td><input type="file" name="file_app_pause_animation" id="file_app_pause_animation"></td>
		</tr>
		<tr class="app-wizard-upload" style="display:none">
			<th width="100"><font color="red">*</font><?php echo L('advert_boot_gu');?></th>
			<td id="td-app-wizards">
				<input type="hidden" value="" id="appWizardIndex" name="appWizardIndex">
				<input type="file" name="file_app_wizard_0" id="file_app_wizard_0">
				<input type="button" style="float:right" value="<?php echo L('advert_boot_gadd');?>" onclick="showMore()">
			</td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('ktv_sub');?>" />&nbsp;
</div> 

</form>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
$(function(){
	APP_WIZARDS_INDEX = 0;
	
	var leftSel = $("#selectL");
	var rightSel = $("#selectR");
	$("#toright").bind("click",function(){		
		leftSel.find("option:selected").each(function(){
			$(this).remove().appendTo(rightSel);
		});
	});
	$("#toleft").bind("click",function(){	
		rightSel.find("option:selected").each(function(){
			$(this).remove().appendTo(leftSel);
		});
	});
	leftSel.dblclick(function(){
		$(this).find("option:selected").each(function(){
			$(this).remove().appendTo(rightSel);
		});
	});
	rightSel.dblclick(function(){
		$(this).find("option:selected").each(function(){
			$(this).remove().appendTo(leftSel);
		});
	});
})

$('#bootType').change(function(){
	var v = $(this).val();
	$('.app-background-upload,.default-upload,.app-wizard-upload').hide();
	if (v == 'BOOTANIMATION')
		$('.default-upload').show();
	else if (v == 'APP_BACKGROUND_IMGS')
		$('.app-background-upload').show();
	else
		$('.app-wizard-upload').show();
})

function verifyForm() {
	var selVal = [];
	var rightSel = $("#selectR");
	rightSel.find("option").each(function(){
		selVal.push(this.value);
	});
	selVals = selVal.join(",");
	$('#cidStr').val(selVals);
	$('#appWizardIndex').val(APP_WIZARDS_INDEX);
}

function showMore() {
	APP_WIZARDS_INDEX++;
	var htm = '<input type="file" name="file_app_wizard_'+APP_WIZARDS_INDEX+'" id="file_app_wizard_'+APP_WIZARDS_INDEX+'" style="float:left;clear:both">';
	$('#td-app-wizards').append(htm);
}
</script>
</body>
</html>