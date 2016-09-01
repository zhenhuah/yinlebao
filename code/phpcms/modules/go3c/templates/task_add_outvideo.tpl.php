<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=addOutTaskdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data"  onSubmit="return VideoTask();">
<input type="hidden" name="term_id" value="<?php echo $term_id;?>" />
<input type="hidden" name="taskId" value="<?php echo $taskId;?>" />
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="table_form">
	<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font>视频ID:</th>
		  <td><input type="text" class="measure-input  input-text" value=""  name="asset_id" id="asset_id" style="width:180px;"><span id="asset_id_error" style="display:none;"><font color="red">id不能为空</font></span></td>
		 </tr>
		<tr>
		  <th width="80"> <font color="red">*</font>推荐标题:</th>
		  <td><input type="text" class="measure-input  input-text" value=""  name="title" id="title" style="width:280px;"><span id="title_error" style="display:none;"><font color="red">标题不能为空</font></span></td>
		</tr>
		<tr>
		  <th width="80"> 视频简介:</th>
		  <td>
		  <input type="text" class="measure-input  input-text" value=""  name="videoDesc" id="videoDesc" style="width:280px;"></td>
		</tr>
		<tr>
		  <th width="80"> 详细介绍:</th>
		  <td><textarea rows="5" cols="41" name="long_desc" id="long_desc"></textarea></td>
		</tr>
		<tr>
		  <th width="80">连接:</th>
		  <td><input type="text" class="measure-input  input-text" value=""  name="playurl" id="playurl" style="width:280px;"><span id="playurl_error" style="display:none;"><font color="red">链接不能为空</font></span></td>
		</tr>
		<tr id="isUserUpload_Div">
		  <th width="80">上传海报:</th>
		  <td>
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
											$('#videoImgUrl').val(web_url+data.msg);
											//浏览
											var set_img_url = web_url+data.msg;
											$('#viewImgUrl').attr('target','_blank');	//设置另一个页面打开
											$('#viewImgUrl').attr('href',set_img_url);	//设置
											$('#viewImgUrl').show();
											jQuery('#fileToUpload').val('');
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
			<input type="text" name="videoImgUrl" id="videoImgUrl" value=""/>
			<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<a href="###" style="display:none;" id="viewImgUrl">浏览</a><span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span>
			&nbsp;<span id="videoImgUrl_error" style="display:none;"><font color="red">请选择海报上传</font></span>
		  </td>
		</tr>
		<tr>
		<th width="90">图片正确吗? </th>
		  <td>
		  <input type="checkbox" id="regtype" name="regtype" />
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认图片正确!</font></span>
		  </td>
		</tr>
    </tbody>
</table>
</div>
	<div style="float:right" class="btn">
		<input type="submit" value="提交" id="dosubmit" name="dosubmit" class="button">&nbsp;&nbsp;
	</div>
</div>
</div>
</form>

</body>
</html>

<script type="text/javascript">
function imgType(sType)
{
	$('#lookImg').attr('target','_self');	//先清空
	$('#lookImg').attr('href','###');	//先清空
	if (sType != '')	//清空上传
	{
		$('#videoImg_error').hide();
		var getImgUrl = $('#'+sType+'_imgPathUrl').val();
		$('#lookImg').attr('target','_blank');	//设置另一个页面打开
		$('#lookImg').attr('href',getImgUrl);	//设置
		$('#lookImg').show();
		$('#videoImgUrl').val('');
	}else{		
		$('#lookImg').hide();
		$('#videoImg_error').show();
	}
}

imgType('<?php echo $taskInfo['imgType'];?>');	//默认尺寸浏览效果

//添加外链
function VideoTask()
{
	//id
	var asset_id = $.trim($('#asset_id').val());
	if (asset_id != '')
	{
		$('#asset_id_error').hide();
	}else{
		$('#asset_id_error').show();
		return false;
	}
	//名称
	var title = $.trim($('#title').val());
	if (title != '')
	{
		$('#title_error').hide();
	}else{
		$('#title_error').show();
		return false;
	}
	//链接
	var playurl = $.trim($('#playurl').val());
	if (playurl != '')
	{
		$('#playurl_error').hide();
	}else{
		$('#playurl_error').show();
		return false;
	}
	/*
	//海报方式
	var videoImgUrl = $.trim($('#videoImgUrl').val());
	if (videoImgUrl != '')
	{
		$('#videoImgUrl_error').hide();
	}else{
		$('#videoImgUrl_error').show();
		return false;
	}
	return true;
	*/
}
</script>
