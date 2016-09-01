<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=boot&a=editAdvert&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"> 推荐位名称</th>
		  <td><?php echo $adInfo['position'];?></td>
		</tr>
		<tr>
		  <th width="90"> 广告类型 </th>
		  <td> <?php if($adInfo['adType'] == '1'){ echo '文字';}?>
				<?php if($adInfo['adType'] == '2'){ echo '图片';}?>
				<?php if($adInfo['adType'] == '3'){ echo '视频';}?>	
				<?php if($adInfo['adType'] == '0'){ echo '引导图';}?>		
		  </td>
		</tr>
		<tr>
		  <th width="90">显示方式 </th>
		  <td>
		    <?php if($adInfo['viewType'] == '1'){ echo '水平跑马灯';}?>
			<?php if($adInfo['viewType'] == '2'){ echo '垂直跑马灯';}?>
			<?php if($adInfo['viewType'] == '3'){ echo '图片翻转';}?>
			<?php if($adInfo['viewType'] == '4'){ echo '嵌入小视频';}?>
			<?php if($adInfo['viewType'] == '9'){ echo '背景图片';}?>
		  </td>
		</tr>
		<tr>
		<input type="hidden" name="ad_type" id="ad_type" value="<?php echo $adInfo['adType'];?>">
		  <th width="90"> <font color="red" <?php if($adInfo['adType'] == '1'){}else{ echo 'style="display:none;"';}?>>*</font> 文字 </th>
		  <td>
			<input type="text" value="<?php echo $adInfo['adDesc'];?>" name="ad_adDesc" id="ad_adDesc" size="25" >
		  &nbsp;&nbsp;<span id="ad_adDesc_error" style="display:none;"><font color="red">请设置文字</font></span>
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
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 图片链接 </th>
		  <td>			  
			<input type="hidden" name="imgType" id="ImgText" value="ImgText">
			<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="<?php echo $adInfo['imgUrl'];?>" name="ad_imgUrl" id="ad_imgUrl" size="25" >
			<span id="ad_ad_imgUrl_error" style="display:none;"><font color="red">必须上传图片</font></span><br/>			
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
			</span>&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red">请设置图片地址</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">图片地址 </th>
		  <td><?php if(!empty($adInfo['imgUrl'])){?><a href="<?php echo $adInfo['imgUrl'];?>" target="_blank"  id="viewImgUrl"><font color="blue">预览图片</font></a>
		  <?php }else{ echo '暂无图片数据';}?>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font>有效
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认图片正确!</font></span>
		 </td>
		</tr>
		<tr>
		  <th width="90"> <font color="red" <?php if($adInfo['adType'] == '3'){}else{ echo 'style="display:none;"';}?>>*</font> 链接地址 </th>
		  <td>
		  <input type="text" value="<?php echo $adInfo['linkUrl'];?>" name="ad_linkUrl" id="ad_linkUrl" size="25" >
		  &nbsp;&nbsp;<span id="ad_linkUrl_error" style="display:none;"><font color="red">请设置链接地址</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90"> 持续时间</th>
		  <td>
		  <input type="text" value="<?php echo $adInfo['duration'];?>" name="duration" id="duration" size="25" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	

	<input type="hidden" name="mode" id="mode" value="editAdTask" />
	<input type="hidden" name="adId" id="adId" value="<?php echo $adInfo['adId'];?>" />
	<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 

</form>

</body>
</html>
