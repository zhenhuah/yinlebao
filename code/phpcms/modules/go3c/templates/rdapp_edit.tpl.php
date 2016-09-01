<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>

<form name="myform" id="myform" action="?m=go3c&c=task_shop&a=edit_rdappdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subapk();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font><?php echo L('shop_name');?>:</th>
		  <td>		
		  <input type="text" value="<?php echo $data['name'] ?>" name="name" id="name" size="40" >
		  &nbsp;&nbsp;<span id="ad_name" style="display:none;"><font color="red"><?php echo L('shop_radname');?></font></span>
		</td>
		</tr>
		<tr>
		  <th width="90"><?php echo L('shop_type');?></th>
		  <td>
			<select name="type" id="type">
		 	<option value="0"><?php echo L('ktv_sel');?></option>
		 	<?php {foreach($type_list as $key=>$list){?>	
		  	<option value="<?php echo $list['type']?>" <?php if ($list['type'] == $data['type']) echo 'selected'?>><?php echo $list['type']?></option>
		  	<?php }} ?>
		  	</select>
		  	&nbsp;<span id="ad_type" style="display:none;"><font color="red"><?php echo L('shop_type_null');?></font></span>
		  </td>
		</tr>	
		
		<tr>
		  <th width="80"><?php echo L('shop_pagename');?></th>
		  <td>
		  <input type="text" value="<?php echo $data['classname'] ?>" name="classname" id="classname" size="30" >
		  &nbsp;<span id="ad_classname" style="display:none"><font color="red"><?php echo L('shop_1');?></font></span>
		  </td>
		</tr>	
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> <?php echo L('shop_icon_link');?></th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="<?php echo $data['icon_url'] ?>" name="ad_imgUrl" id="ad_imgUrl" size="100" >	
				 </td>
		</tr>
		<tr>
			<th width="90"><?php echo L('shop_icon_y');?></th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			<a href="###" style="display:none;" id="viewImgUrl"><font color="red"><?php echo L('ktv_pre');?></font></a>
		  </td>
		</tr>
		<tr id="view_ImgFile" style="display:none;">
		  <th width="90"><font color="red" id="data_2_1"  class="data" style="display:none">*</font><?php echo L('file_link');?></th>
		  <td>
			<input type="file" value="" name="ad_imgUrlFile" id="ad_imgUrlFile" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrlFile_error" style="display:none;"><font color="red"><?php echo L('up_file3');?></font></span>
		  </td>
		</tr>
		
		<tr id="view_ImgText1" >
		  <th width="90"><?php echo L('shop_apk_link');?> </th>
		  <td>
		  <input type="hidden" name="imgType1" id="ImgText1" value="ImgText">
			<input type="text" value="<?php echo $data['install_url'] ?>" name="ad_imgUrl1" id="ad_imgUrl1" size="100" >	
				 </td>
		</tr>
		<tr>
			<th width="90"><?php echo L('shop_apk_y');?></th>
		 	 <td>
			<span id="content1">
			<img id="loading1" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload1" id="fileToUpload1" class="input" onchange="checkUpload1(this);">
			<button class="button" id="buttonUpload1" onclick="return ajaxFileUpload1();" style="display:none;">Upload</button>
			</span></br>
			<a href="###" style="display:none;" id="viewImgUrl1"><font color="red"><?php echo L('ktv_pre');?></font></a>&nbsp;&nbsp;&nbsp;&nbsp;
		  </td>
		</tr>
		<tr id="view_ImgFile1" style="display:none;">
		  <th width="90"><font color="red" id="data_2_1"  class="data" style="display:none">*</font><?php echo L('file_link');?></th>
		  <td>
			<input type="file" value="" name="ad_imgUrlFile" id="ad_imgUrlFile" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrlFile_error" style="display:none;"><font color="red"><?php echo L('up_file3');?></font></span>
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
<input type="hidden" name="id" id="id" value="<?php echo $data['id'] ?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('ktv_sub');?>" />&nbsp;
</div> 
</form>
<script type="text/javascript">
//icon上传验证
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
		$('#loading1').hide();
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

//横图上传验证
function checkUpload1(fileObj)
{
	if(fileObj.value != '') 
	{
		//方式一
		//jQuery('#buttonUpload').show();

		//方法二
		ajaxFileUpload1();
	}
	return false;
}

function ajaxFileUpload1()
{
	//优化 start 
	var fileValue = jQuery('#fileToUpload1').val();
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


	$("#loading1")
	.ajaxStart(function(){
		$(this).show();
		$('#loading').hide();
	})
	.ajaxComplete(function(){
		$(this).hide();
	});

	$.ajaxFileUpload
	(
		{
			url:"?m=go3c&c=task&a=inforfileupload&fileid=fileToUpload1&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
			secureuri:false,
			fileElementId:'fileToUpload1',
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
							$('#viewImgUrl1').attr('target','_blank');	//设置另一个页面打开
							$('#viewImgUrl1').attr('href',set_img_url);	//设置
							$('#viewImgUrl1').show();
							
							$('#ad_imgUrl1').val(data.msg);
							alert('上传成功!');
						}else{
							$('#viewImgUrl1').hide();
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

function subapk()
{
	var name = $.trim($('#name').val());
	if (name != '')
	{
		$('#ad_name').hide();
	}else{
		$('#ad_name').show();
		return false;
	}
	var type = $.trim($('#type').val());
	if (type != '')
	{
		$('#ad_type').hide();
	}else{
		$('#ad_type').show();
		return false;
	}
	var classname = $.trim($('#classname').val());
	if (classname != '')
	{
		$('#ad_classname').hide();
	}else{
		$('#ad_classname').show();
		return false;
	}
}
</script>
</body>
</html>
