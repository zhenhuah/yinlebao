<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
function formatType($type, $bg) {
	if ($type == 'BOOTANIMATION')
		return '开机动画';
	else if ($type == 'APP_WIZARDS')
		return '开机引导图';
	else {
		if ($bg == 0)
			return '应用启动背景图';
		else if ($bg == 1)
			return '应用重启背景图';
		else if ($bg == 2)
			return '应用切回背景图';
		else 
			return '视频暂停背景图';
	}
}
?>
<form name="myform" id="myform" action="?m=go3c&c=client&a=upgradeBootEdit&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data">
<input type="hidden" value="<?php echo $data['b_id']?>" name="id">
<input type="hidden" value="<?php echo $data['b_type']?>" id="type">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		    <th width="80"><?php echo L('advert_spid');?>:</th>
	        <td><?php echo $data['b_cid']?></td>
		</tr>
		<tr>
			<th><?php echo L('advert_type');?></th>
			<td><?php echo formatType($data['b_type'], $data['b_bg_type'])?></td>
		</tr>
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font><?php echo L('advert_file_link');?></th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
			<input type="text" value="<?php echo $data['b_url']?>" name="ad_imgUrl" id="ad_imgUrl" size="25" >	
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
						if ($('#type').val() == 'BOOTANIMATION' && ext != '.ZIP') {
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
		<tr>
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
		<tr>
		<th width="90"><?php echo L('ch_file');?></th>
		  <td>
		  <a href="###" style="display:none;" id="viewImgUrl"><font color="blue"><?php echo L('ktv_pre');?></font></a>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font><?php echo L('advert_valid');?>
		  <span id="ad_regtype_error" style="display:none;"><font color="red"><?php echo L('advert_che_valid');?></font></span>
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
</script>
</body>
</html>