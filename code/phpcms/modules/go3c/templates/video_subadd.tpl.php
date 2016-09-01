<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=subadddo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><?php echo L('language');?>:</th>
		  <td>		
		  <select name="language" id="language">
		  <option value="0"><?php echo L('pl_selec');?></option>	
		  <option value="中文">中文</option>
		  <option value="英文">英文</option>	  	
		  </select> 
		</td>
		</tr>
		<tr>
		  <th width="90"><?php echo L('sub_type');?></th>
		  <td>
			<select name="format" id="format">
		 	<option value="0"><?php echo L('pl_selec');?></option>	
		  	<option value="srt">srt</option>
		  	<option value="txt">txt</option>
		  	</select>
		  </td>
		</tr>
		<tr>
		  <th width="90"><?php echo L('source_type');?></th>
		  <td>
			<select name="source" id="source">
		 	<option value="0"><?php echo L('pl_selec');?></option>
		 	<?php {foreach($subtitlePathDB as $key=>$subtitle){?>	
		  	<option value="<?php echo $subtitle['id']?>"><?php echo $subtitle['title']?></option>
		  	<?php }} ?>
		  	</select>
		  </td>
		</tr>
		<script type="text/javascript">
			function setImgType(id)
			{
				if (id == 'ImgText')
				{
					$('#imgType_error').hide();
					$('#view_ImgFile').hide();
					$('#view_ImgText').show();
				}else if (id == 'ImgFile') {
					$('#imgType_error').hide();
					$('#view_ImgText').hide();
					$('#view_ImgFile').show();
				}else{					
					$('#view_ImgText').hide();
					$('#view_ImgFile').hide();
					$('#imgType_error').show();
				}
			}
		</script>
		<tr>
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font><?php echo L('file_link');?> </th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
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
						if (ext != '.TXT' && ext != '.SRT'){
							alert('请选择正确的字幕文件格式(.txt .srt)');
							return false;
						}

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
							url:"?m=go3c&c=task&a=dofileajaxfileupload&asset_id=<?php echo $asset_id;?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
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
											var web_url = $('#webSiteUrl').val();
											var sub = 'video/subtitle/'; 
											//浏览
											var set_img_url = web_url+sub+data.msg;
											$('#viewImgUrl').attr('target','_blank');	//设置另一个页面打开
											$('#viewImgUrl').attr('href',set_img_url);	//设置
											$('#viewImgUrl').show();
											
											$('#ad_imgUrl').val(data.msg);
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
			<tr>
			<th width="90"><?php echo L('up_file');?></th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			<a href="###" style="display:none;" id="viewImgUrl"><font color="red"><?php echo L('preview');?></font></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<span id="ad_imgUrl_error" style="display:none;"><font color="red"><?php echo L('up_file1');?></font></span>
		  &nbsp;&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red"><?php echo L('up_file2');?></font></span>
		  </td>
		</tr>
		<tr id="view_ImgFile" style="display:none;">
		  <th width="90"><font color="red" id="data_2_1"  class="data" style="display:none">*</font><?php echo L('file_link');?></th>
		  <td>
			<input type="file" value="" name="ad_imgUrlFile" id="ad_imgUrlFile" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrlFile_error" style="display:none;"><font color="red"><?php echo L('up_file3');?></font></span>
		  </td>
		</tr>
		<tr>
		<th width="90"><?php echo L('ch_file');?></th>
		  <td>
		  <a href="###" style="display:none;" id="viewImgUrl"><font color="blue"><?php echo L('preview');?></font></a>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font><?php echo L('file_valid');?>
		  <span id="ad_regtype_error" style="display:none;"><font color="red"><?php echo L('check_file');?></font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90"><?php echo L('sub_time');?></th>
		  <td>
		  <input type="text" value="" name="run_time" id="run_time" size="25" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="subadddo" />
	<input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id;?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function subtitle()
{
	//文件路径
	var ad_imgUrl = $.trim($('#ad_imgUrl').val());
	if (ad_imgUrl != '')
	{
		$('#ad_imgUrl_error').hide();
	}else{
		$('#ad_imgUrl_error').show();
		return false;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
}
</script>
</body>
</html>
