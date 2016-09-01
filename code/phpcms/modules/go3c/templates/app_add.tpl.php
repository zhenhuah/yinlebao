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

<form name="myform" id="myform" action="?m=go3c&c=task_shop&a=add_app_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font><?php echo L('shop_name');?>:</th>
		  <td>		
		  <input type="text" value="" name="app_name" id="app_name" size="40" >
		  &nbsp;&nbsp;<span id="ad_app_name" style="display:none;"><font color="red"><?php echo L('shop_2');?></font></span>
		</td>
		</tr>
		<tr>
		  <th width="80"><?php echo L('shop_description');?>:</th>
		  <td>		
		  <textarea name="app_desc" id="app_desc"></textarea>
		</td>
		</tr>
		<tr>
		  <th width="90"><font color="red">*</font><?php echo L('shop_type');?></th>
		  <td>
			<select name="channel_cat_id" id="channel_cat_id">
		 	<option value="0"><?php echo L('ktv_sel');?></option>
		 	<?php {foreach($type_name_list as $key=>$list){?>	
		  	<option value="<?php echo $list['cat_id']?>"><?php echo $list['cat_name']?></option>
		  	<?php }} ?>
		  	</select>
		  	&nbsp;&nbsp;<span id="ad_channel_cat_id" style="display:none;"><font color="red"><?php echo L('shop_3');?></font></span>
		  </td>
		  <input type="hidden" name="channel" id="channel" value="" />
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_owner');?></th>
		  <td>
		  <input type="text" value="" name="owner" id="owner" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_language');?></th>
		  <td>
		  <input type="text" value="" name="language" id="language" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_packagename');?></th>
		  <td>
		  <input type="text" value="" name="packagename" id="packagename" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_os_ver');?></th>
		  <td>
		  <input type="text" value="" name="os_ver" id="os_ver" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_score');?></th>
		  <td>
		  <input type="text" value="" name="score" id="score" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_tag');?></th>
		  <td>
		  <input type="text" value="" name="tag" id="tag" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_view_count');?></th>
		  <td>
		  <input type="text" value="" name="view_count" id="view_count" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_download_count');?></th>
		  <td>
		  <input type="text" value="" name="download_count" id="download_count" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_mobitype');?></th>
		  <td>
		  <input type="text" value="" name="mobitype" id="mobitype" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_widgetProvider');?></th>
		  <td>
		  <input type="text" value="" name="widgetProvider" id="widgetProvider" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><font color="red">*</font><?php echo L('shop_yufabu_date');?></th>
		  <td>
		  <input type="text" value="" name="yufabu_date" id="yufabu_date" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "yufabu_date",
				trigger    : "yufabu_date",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>
		  </td>
		</tr>		
		<tr>
		  <th width="80"><?php echo L('shop_version');?></th>
		  <td>
		  <input type="text" value="" name="version" id="version" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('ktv_size');?></th>
		  <td>
		  <input type="text" value="" name="file_size" id="file_size" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_pice');?></th>
		  <td>
		  <input type="text" value="" name="price" id="price" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_source');?></th>
		  <td>
		  <input type="text" value="" name="source" id="source" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80"><font color="red">*</font><?php echo L('shop_date');?></th>
		  <td>
		  <input type="text" value="" name="release_date" id="release_date" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "release_date",
				trigger    : "release_date",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>
		  </td>
		</tr>	
		<tr>
		  <th width="80"><?php echo L('shop_z_term');?></th>
		  <td>
		  PC:<input type="checkbox" name="PC" id="PC" >&nbsp;&nbsp;
		  ANDROID:<input type="checkbox" name="ANDROID" id="ANDROID" >&nbsp;&nbsp;
		  IOS:<input type="checkbox" name="IOS" id="IOS" >&nbsp;&nbsp;
		  STB:<input type="checkbox" name="STB" id="STB" >&nbsp;&nbsp;
		  SSB:<input type="checkbox" name="SSB" id="SSB" >
		  </td>
		</tr>
		<tr >
		  <th width="90"><?php echo L('controller_type');?></th>
		  <td>
		   <select name="controller_type" id="controller_type">
		 	<?php {foreach($app_contype as $key=>$vv){?>	
		  	<option value="<?php echo $vv['id']?>"><?php echo $vv['contype']?></option>
		  	<?php }} ?>
		  	</select>
		  </td>
		</tr>
		<tr >
		  <th width="90"><font color="red">*</font>升级版本</th>
		  <td>
		  <input type="text" value="" name="versioncode" id="versioncode" size="30" >
		  </td>
		</tr>
		<tr >
		  <th width="90"><font color="red">*</font>apk文件MD5</th>
		  <td>
		  <input type="text" value="" name="file_hash" id="file_hash" size="30" >
		  </td>
		</tr>
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font>ICON图片</th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="" name="ad_imgUrl" id="ad_imgUrl" size="100" >	
				 </td>
		</tr>
		<tr>
			<th width="90">ICON图片</th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			<a href="###" style="display:none;" id="viewImgUrl"><font color="red"><?php echo L('ktv_pre');?></font></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<span id="ad_imgUrl_error" style="display:none;"><font color="red"><?php echo L('shop_icon_pic');?></font></span>
		  </td>
		</tr>
		<tr id="view_ImgFile" style="display:none;">
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
	<input type="hidden" name="mode" id="mode" value="addinfordo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('ktv_sub');?>" />&nbsp;
</div> 
</form>
<script type="text/javascript">
//icon上传验证
function checkUpload(fileObj)
{
	ajaxFileUpload();
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
			url:"?m=go3c&c=task&a=doajaxfileuploadtencent&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
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
							//var set_img_url = web_url+data.msg;
							var set_img_url = data.msg;
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

function subtitle()
{
	//文件路径
	/*var ad_imgUrl = $.trim($('#ad_imgUrl').val());
	if (ad_imgUrl != '')
	{
		$('#ad_imgUrl_error').hide();
	}else{
		$('#ad_imgUrl_error').show();
		return false;
	}*/
	var title = $.trim($('#app_name').val());
	if (title != '')
	{
		$('#ad_app_name').hide();
	}else{
		$('#ad_app_name').show();
		return false;
	}
	var type = $.trim($('#channel_cat_id').val());
	if (type != 0)
	{
		$('#ad_channel_cat_id').hide();
		var channel = $("#channel_cat_id option[value=" + type + "]").text();
		$('#channel').attr('value', channel);
	}else{
		$('#ad_channel_cat_id').show();
		$('#channel').attr('value', '');
		return false;
	}
}
</script>
</body>
</html>
