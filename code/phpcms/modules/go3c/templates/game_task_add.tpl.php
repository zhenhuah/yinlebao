<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task_games&a=addgametaskdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data"  onSubmit="return runVideoTask();">
<input type="hidden" name="taskId" value="<?php echo $taskId;?>" />
<input type="hidden" name="id" value="<?php echo $videoInfo['id'];?>" />

<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="table_form">
	<tbody>	
		<tr>
		  <th width="80"> 游戏ID:</th>
		  <td><?php echo $videoInfo['id']?> </td>
		 </tr>

		<tr>
		  <th width="80"> 标题:</th>
		  <td><?php echo $videoInfo['title']?></td>
		</tr>
		<tr>
		  <th width="90"> <font color="red">*</font> 推荐后的标题:</th>
		  <td><input type="text" class="measure-input  input-text" value="<?php echo $videoInfo['videoTitle']?>"  name="videoTitle" id="videoTitle" style="width:280px;"><span id="videoTitle_error" style="display:none;"><font color="red">此标题不能为空</font></span></td>
		</tr>
		<tr>
		  <th width="80"> 详细介绍:</th>
		  <td><textarea rows="5" cols="41" name="long_desc" id="long_desc"><?php echo $videoInfo['desc'];?></textarea></td>
		</tr>
		<tr>
		  <th width="90">游戏链接:</th>
		  <td><input type="text" class="measure-input  input-text" name="infor_url" id="infor_url" style="width:280px;"></td>
		</tr>
		<tr id="isUserUpload_Div">
		  <th width="80"><font color="red">*</font>上传海报:</th>
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
							url:"?m=go3c&c=task&a=inforfileupload&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
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
											var set_img_url = web_url+data.msg;
											$('#videoImgUrl').val(set_img_url);
											//浏览
											
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
		<th width="90"><font color="red">*</font>图片正确吗? </th>
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
//添加游戏具体任务
function runVideoTask()
{
	//名称
	var videoTitle = $.trim($('#videoTitle').val());
	if (videoTitle != '')
	{
		$('#videoTitle_error').hide();
	}else{
		$('#videoTitle_error').show();
		return false;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
	return true;
}
</script>
