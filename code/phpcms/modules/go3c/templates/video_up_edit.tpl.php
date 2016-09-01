<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="table-list" id="load_priv">
<form name="myform" id="myform" action="?m=go3c&c=tvuser&a=edit_upvideodo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<tr>
<th width="90">视频ID:</th>
<td>
<?php echo $limitInfo['id']?>
</td>
</tr>
<tr>
<th width="90">账户:</th>
<td>
<?php echo $limitInfo['userid']?>
</td>
</tr>
<tr>
<th width="90">标题:</th>
<td>
<input type="text" value="<?php echo $limitInfo['title']?>" name="title" id="title" size="25" >
</td>
</tr>
<tr>
<th width="90">描述:</th>
<td>
<textarea rows="5" cols="50" value="<?php echo $limitInfo['content'];?>" name="content" id="content"><? echo $limitInfo['content'];?></textarea>
</td>
</tr>

<tr>
<th width="90">视频链接:</th>
<td>
<input type="text" value="<?php echo $limitInfo['path'];?>" name="path" id="path" size="50" >
</td>
</tr>
<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 图片链接 </th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="<?php echo $limitInfo['imageurl'];?>" name="ad_imgUrl" id="ad_imgUrl" size="25" >	
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
						if((ext != '.JPG') && (ext != '.JPEG') && (ext != '.PNG'))
						{ 
							alert("图片文件限于jpg,jpeg,png格式"); 
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
											var web_url = $('#webSiteUrl').val();
											$('#ad_imgUrl').val(web_url+data.msg);
											//浏览
											var set_img_url = web_url+data.msg;
											$('#viewImgUrl').attr('target','_blank');	//设置另一个页面打开
											$('#viewImgUrl').attr('href',set_img_url);	//设置
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
						if((ext != '.JPG') && (ext != '.JPEG') && (ext != '.PNG'))
						{ 
							alert("图片文件限于jpg,jpeg,png格式"); 
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
			<th width="90">图片上传</th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			请输入一个图片链接或上传一个本地图片!
			<span id="ad_imgUrl_error" style="display:none;"><font color="red">请选择上传图片</font></span>
		  </td>
		</tr>
</tbody>
</table>
<div class="btn">
<input type="hidden" name="id" id="id" value="<?php echo $limitInfo['id'];?>" />
<input type="submit"  class="button" name="dosubmit" id="dosubmit" value="<?php echo L('submit');?>" /></div>
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
</script>
</body>
</html>
