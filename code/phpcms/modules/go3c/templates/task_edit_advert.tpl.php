<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>jquery.bigcolorpicker.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.bigcolorpicker.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery-1.7.2.min.js"></script>
<form name="myform" id="myform" action="?m=go3c&c=task&a=editAdvert&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data" >
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
		  </td>
		</tr>
		<tr>
		  <th width="90">标题 </th>
		  <td>
		  <input name="ad_title" id="ad_title" value="<?php echo $adInfo['title'];?>">
		  </td>
		</tr>
		<tr>
		<input type="hidden" name="ad_type" id="ad_type" value="<?php echo $adInfo['adType'];?>">
		  <th width="90"> <font color="red" <?php if($adInfo['adType'] == '1'){}else{ echo 'style="display:none;"';}?>>*</font> 文字描述 </th>
		  <td>
		  <textarea name="ad_adDesc" id="ad_adDesc" style="widht:160px;"><?php echo $adInfo['adDesc'];?></textarea>
		  &nbsp;&nbsp;<span id="ad_adDesc_error" style="display:none;"><font color="red">请设置文字</font></span>
		  </td>
		</tr>
		<!--tr>
		  <th width="90"><font color="red" <?php if($adInfo['adType'] == '2'){}else{ echo 'style="display:none;"';}?>>*</font> 图片地址 </th>
		  <td>
			<input type="text" value="<?php echo $adInfo['imgUrl'];?>" name="ad_imgUrl" id="ad_imgUrl" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red">请设置图片地址</font></span>
		  </td>
		</tr-->
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
		<!--tr>
		  <th width="90"> 图片类型 </th>
		  <td>
			<input type="radio" name="imgType" id="ImgText" value="ImgText" onclick="setImgType(this.value);">输入地址&nbsp;<input type="radio" name="imgType" id="ImgFile" value="ImgFile" onclick="setImgType(this.value);">上传图片&nbsp;<span id="imgType_error" style="display:none;"><font color="red">请选择图片类型</font></span>
		  </td>
		</tr-->
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
						//alert(ext);
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
			</span>&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red">请设置图片地址</font></span>
		  </td>
		</tr>
		<!--
		<tr id="view_ImgFile" style="display:none;">
		  <th width="90"><font color="red" id="data_2_1"  class="data" style="display:none">*</font>上传图片 </th>
		  <td>
			<input type="file" value="" name="ad_imgUrlFile" id="ad_imgUrlFile" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrlFile_error" style="display:none;"><font color="red">请选择上传图片</font></span>
		  </td>
		</tr>
		-->
		<tr>
		  <th width="90">图片地址 </th>
		  <td><?php if(!empty($adInfo['imgUrl'])){?><a href="<?php echo $adInfo['imgUrl'];?>" target="_blank"  id="viewImgUrl"><font color="blue">预览图片</font></a>
		  <?php }else{ echo '暂无图片数据';}?>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font>有效
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认图片正确!</font></span>
		 </td>
		</tr>
		<?php if($advert['display_type'] == '1'||$advert['display_type'] == '2'||$advert['display_type'] == '6'||$advert['display_type'] == '7'||$advert['display_type'] == '8'){?>
		<tr>
			<th width="90">按钮文本</th>
				<td>
					<input type="text" name="btn_txt" value="<?php echo $adInfo['btn_txt'];?>"/>
				</td>
		</tr>
		<?php if ($adInfo['is_link']) {?>
		<tr>
			<th width="90">链接地址</th>
				<td>
					<input type="text" name="ad_linkUrl" value="<?php echo $adInfo['linkUrl'];?>"/>
				</td>
		</tr>
		<?php } else {?>
		<tr >
			<th>app信息</th>
			<td>
				应用名:<input type="text" name="app_name" value="<?php echo $adInfo['app_name']?>"><br>
				应用包:<input type="text" name="app_package" value="<?php echo $adInfo['app_package']?>"><br>
				应用URL:<input type="text" name="app_url" value="<?php echo $adInfo['app_url']?>"><br>
			</td>
		</tr>
		<?php }?>
		<tr id="TRIGGER_duration">
			<th width="90">持续时间</th>
				<td>
					<input type="text" name="duration" id="duration" size="15" value="<?php echo $adInfo['duration'];?>"/>(s)&nbsp;&nbsp;显示次数&nbsp;&nbsp;
					<input type="text" name="num" id="num" size="15" value="<?php echo $adInfo['num'];?>"/>
				</td>
		</tr>
		<tr id="TRIGGER_dire" >
			<th width="90">移动方向</th>
				<td>
					<input type="text" name="dire" id="dire" size="15" value="<?php echo $adInfo['dire'];?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;移动速度&nbsp;&nbsp;&nbsp;
					<select name="speed" id="speed">
					 <option value="">请选择</option>
					 <option value="快" <?php if($adInfo['speed']=='快') echo 'selected';?>>快</option>
					 <option value="慢" <?php if($adInfo['speed']=='慢') echo 'selected';?>>慢</option>
					 </select>
				</td>
		</tr>
		<tr id="TRIGGER_width" >
			<th width="90">宽度</th>
				<td>
					<input type="text" name="width" id="width" size="15" value="<?php echo $adInfo['width'];?>"/>(px)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;高度&nbsp;&nbsp;&nbsp;
					<input type="text" name="height" id="height" size="15" value="<?php echo $adInfo['height'];?>"/>(px)
				</td>
		</tr>
		<tr id="TRIGGER_color" >
			<th width="90">字体颜色</th>
				<td>
					<input type="text" name="color" id="color" size="15" value="<?php echo $adInfo['color'];?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;弹出方式&nbsp;&nbsp;&nbsp;
					<input type="text" name="pop" id="pop" size="15" value="<?php echo $adInfo['pop'];?>"/>
				</td>
		</tr>
		<?php }?>
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
<script type="text/javascript">
$(function(){
	//1、用法
	$("#color").bigColorpicker("color","L",10);	
	$("#f333").bigColorpicker("f3","L",6);
});
</script>
</body>
</html>
