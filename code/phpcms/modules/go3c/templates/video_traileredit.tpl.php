<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=video&a=trailereditdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">预告片ID:</th>
		  <td><input type="text" value="<?php echo $limitInfo['tid'];?>" name="tid" id="tid" size="20" ></td>
		</tr>
		<tr>
		  <th width="80">正片视频ID:</th>
		  <td><?php echo $limitInfo['asset_id'];?></td>
		</tr>
		<tr>
		  <th width="80">清晰度:</th>
		  <td>		
		  <input type="text" value="<?php echo $limitInfo['quality'];?>" name="quality" id="quality" size="25" >(清晰度:1=low,2=medium,3=high,4=HD)
		</td>
		</tr>	
		<tr>
		  <th width="80">画质:</th>
		  <td>		
		  <input type="text" value="<?php echo $limitInfo['aspect'];?>" name="aspect" id="aspect" size="25" >
		</td>
		</tr>
		<tr>
		  <th width="90">视频来源类型 </th>
		  <td>
			<select name="source" id="source">
		 	<option value="0">请选择</option>
		 	<?php {foreach($subtitlePathDB as $key=>$subtitle){?>	
		  	<option <?php if ($limitInfo['source'] == $subtitle['id']) echo 'selected'; ?> value="<?php echo $subtitle['id']?>"><?php echo $subtitle['title']?></option>
		  	<?php }} ?>	
		  	</select>
		  </td>
		</tr>
		<tr>
		  <th width="80">预告片链接:</th>
		  <td>		
		  <input type="text" value="<?php echo $limitInfo['play_url'];?>" name="play_url" id="play_url" size="50" >
		  <span id="play_url_error" style="display:none;"><font color="red">请填写预告链接</font></span>
		</td>
		</tr>
		<tr>
		  <th width="80">尺寸:</th>
		  <td>		
		  <input type="text" value="<?php echo $limitInfo['ratio'];?>" name="ratio" id="ratio" size="25" >(4:3,16:9)
		</td>
		</tr>
		<tr>
		  <th width="80">视频格式:</th>
		  <td>		
		  <input type="text" value="<?php echo $limitInfo['format'];?>" name="format" id="format" size="25" >(m3u8,flv....)
		</td>
		</tr>
		<tr>
		  <th width="80">视频协议:</th>
		  <td>		
		  <input type="text" value="<?php echo $limitInfo['protocol'];?>" name="protocol" id="protocol" size="25" >(http,rstp....)
		</td>
		</tr>
		<tr>
		  <th width="90">语言类型 </th>
		  <td>
			<select name="language" id="language">
		  	<option <?php if ($limitInfo['language'] == 'cn') echo 'selected'; ?> value="cn"><?php echo "中文" ?></option>
		  	<option <?php if ($limitInfo['language'] == 'en') echo 'selected'; ?> value="en"><?php echo "英文" ?></option>
		  	</select>
		  </td>
		</tr>
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 图片链接 </th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="<?php echo $limitInfo['img_url'];?>" name="ad_imgUrl" id="ad_imgUrl" size="25" >	
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
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">
<input type="hidden" name="id" id="id" value="<?php echo $limitInfo['id'];?>" />
<input type="hidden" name="asset_id" id="asset_id" value="<?php echo $limitInfo['asset_id'];?>" />	
	<input type="submit" class="button" value="保存" name="dosubmit">
</div> 
</form>
<script type="text/javascript">
function subtitle()
{
	//文件路径
	var play_url = $.trim($('#play_url').val());
	if (play_url != '')
	{
		$('#play_url_error').hide();
	}else{
		$('#play_url_error').show();
		return false;
	}
}
</script>
</body>
</html>
