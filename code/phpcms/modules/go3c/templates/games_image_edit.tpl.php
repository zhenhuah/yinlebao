<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>
<form name="myform" id="myform" action="?m=go3c&c=task_games&a=game_image_editdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return verify_form();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90">栏目</th>
		  <td>
		   	游戏图片
		  </td>
		</tr>
		<tr>
		  <th width="90"><font color="red"> * </font>图片类型</th>
		  <td>
		  <select name="type">
		  	<option value="0">-请选择-</option>
		  	<?php {if ($data['type'] == 1){?>	
		  	<option <?php echo 'selected'; ?> value="<?php echo $data['type']?>"><?php echo image_type($data['type'])?></option>
		  	<option value="2">横图</option>
		  	<option value="3">竖图</option>
		  	<?php }} ?>
		  	<?php {if ($data['type'] == 2){?>	
		  	<option <?php echo 'selected'; ?> value="<?php echo $data['type']?>"><?php echo image_type($data['type'])?></option>
		  	<option value="1">icon</option>
		  	<option value="3">竖图</option>
		  	<?php }} ?>
		  	<?php {if ($data['type'] == 3){?>	
		  	<option <?php echo 'selected'; ?> value="<?php echo $data['type']?>"><?php echo image_type($data['type'])?></option>
		  	<option value="1">icon</option>
		  	<option value="2">横图</option>
		  	<?php }} ?>
		  </select>
		  &nbsp;&nbsp;<span id="ad_type_name" style="display:none;"><font color="red">请选择类型</font></span>
		  </td>
		</tr>
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 图片链接 </th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="<?php echo $data['url']?>" name="url" id="url" size="100" >	
				 </td>
		</tr>
		<tr>
			<th width="90">图片</th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			<a href="###" style="display:none;" id="viewImgUrl"><font color="red">预览</font></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<span id="ad_imgUrl_error" style="display:none;"><font color="red">请选择上传图片图片</font></span>
		  &nbsp;&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red">请设置文件地址</font></span>
		  </td>
		</tr>
		<tr id="view_ImgFile" style="display:none;">
		  <th width="90"><font color="red" id="data_2_1"  class="data" style="display:none">*</font>文件 链接</th>
		  <td>
			<input type="file" value="" name="ad_imgUrlFile" id="ad_imgUrlFile" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrlFile_error" style="display:none;"><font color="red">请选择上传文件或输入地址</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">排序</th>
		  <td>
		  <input type="text" value="<?php echo $data['sort']?>" name="sort" id="sort" size="25" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
	<input type="hidden" name="gameid" value="<?php echo $data['game_id'] ?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">

//图片上传验证
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
		if((ext != '.JPG') && (ext != '.JPEG') && (ext != '.PNG') && (ext != '.ICO'))
		{ 
			alert("图片文件限于jpg,jpeg,png,ico格式"); 
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
							var web_url = $('#webSiteUrl').val();
							//var sub = 'uploadfile/infor/'; 
							//浏览
							var set_img_url = web_url+data.msg;
							$('#viewImgUrl').attr('target','_blank');	//设置另一个页面打开
							$('#viewImgUrl').attr('href',set_img_url);	//设置
							$('#viewImgUrl').show();
							
							$('#url').val(set_img_url);
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

function verify_form()
{
	//类型名称
	var url = $.trim($('#url').val());
	if (url != '')
	{
		$('#ad_imgUrlFile_error').hide();
	}else{
		$('#ad_imgUrlFile_error').show();
		return false;
	}
}
</script>
</body>
</html>
