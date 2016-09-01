<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=boot&a=addAdvert&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return checkAdTaskadboot();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font> 名称:</th>
		  <td>
		   <?php if(!empty($term_adverts_list)){ foreach($term_adverts_list as $key => $row){ ?>		  
			<input type="hidden" id="parentId_<?php echo $row['id']; ?>" value="<?php echo $row['ad_type'];?>" >
		  <?php }}?>			
		  <select name="ad_position" id="ad_position">
		  <option value="">请选择</option>
		  <?php if(!empty($term_adverts_list)){ foreach($term_adverts_list as $key => $row){ ?>		  
		  <option value="<?php echo $row['id']; ?>">
			<?php 
				switch($row['ad_type'])
				{
					case '1':	//文字
						$ad_type = '文字';
					break;
					case '2':	//图片
						$ad_type = '图片';
					break;
					case '3':	//视频
						$ad_type = '视频';
					break;
					case '0':	//引导图
						$ad_type = '引导图';
					break;
				}
				switch($row['display_type'])
				{
					case '1':	//水平跑马灯
						$display_type = '水平跑马灯';
					break;

					case '2':	//垂直跑马灯
						$display_type = '垂直跑马灯';
					break;

					case '3':	//图片翻转
						$display_type = '图片翻转';
					break;
					case '4':	//嵌入小视频
						$display_type = '嵌入小视频';
					break;
					case '5':
						$display_type = '全屏视频';
						break;
					case '6':
						$display_type = '顶部跑马灯';
						break;
					case '7':
						$display_type = '底部跑马灯';
						break;
					case '8':
						$display_type = '右下角弹窗';
						break;
					case '9':
						$display_type = '背景图片';
					break;
				}
				echo $row['title'].'-('.$ad_type.'-'.$display_type.')';
			?>
		  </option>
		  <?php }}?>			
		  </select>&nbsp;<span id="ad_position_error" style="display:none"><font color="red">请选择广告推荐位</font> 
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
		<tr>
		<tr id="view_ImgText" class="default-upload" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 图片链接 </th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="" name="ad_imgUrl" id="ad_imgUrl" size="25" >	
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
			<tr class="default-upload">
			<th width="90">图片上传</th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			请输入一个图片链接或上传一个本地图片!
			<span id="ad_imgUrl_error" style="display:none;"><font color="red">请选择上传图片</font></span>
		  &nbsp;&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red">请设置图片地址</font></span>
		  </td>
		</tr>
		<tr class="default-upload">
		<th width="90">图片检查</th>
		  <td>
		  <a href="###" style="display:none;" id="viewImgUrl"><font color="blue">浏览</font></a>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font>有效
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认图片正确!</font></span>
		  </td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font>应用背景图</th>
			<td><input type="text" name="url_app_animation" id="url_app_animation"> 或 上传 <input type="file" name="file_app_animation" id="file_app_animation"></td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font>应用重启背景图</th>
			<td><input type="text" name="url_app_restart_animation" id="url_app_restart_animation"> 或 上传 <input type="file" name="file_app_restart_animation" id="file_app_restart_animation"></td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font>应用切回背景图</th>
			<td><input type="text" name="url_app_return_animation" id="url_app_return_animation"> 或 上传 <input type="file" name="file_app_return_animation" id="file_app_return_animation"></td>
		</tr>
		<tr class="app-background-upload" style="display:none">
			<th width="100"><font color="red">*</font>视频暂停背景图</th>
			<td><input type="text" name="url_app_pause_animation" id="url_app_pause_animation"> 或 上传 <input type="file" name="file_app_pause_animation" id="file_app_pause_animation"></td>
		</tr>
		<tr>
		  <th width="90"><font color="red" id="data_3"  class="data" style="display:none">*</font> 链接地址 </th>
		  <td>
		  <input type="text" value="" name="ad_linkUrl" id="ad_linkUrl" size="25" >
		  &nbsp;&nbsp;<span id="ad_linkUrl_error" style="display:none;"><font color="red">请设置链接地址</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">持续时间 </th>
		  <td>
		  <input type="text" value="" name="duration" id="duration" size="25" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="addAdTask" />
	<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 

</form>

</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
//添加广告任务表单验证
function checkAdTaskadboot()
{
	/*var ad_imgUrl = $('#ad_imgUrl').val();
	if (ad_imgUrl==''){
		$('#ad_imgUrl_error').show();
		return false ;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}*/
	//名称 ad_position
	var ad_position = $.trim($('#ad_position').val());

	var name = $('#ad_position').find("option[value="+ad_position+"]").text();
	if (name.indexOf('APP_BACKGROUND_IMGS') != -1) {
		return true;
	}
	
	if (ad_position != '')
	{
		$('#ad_position_error').hide();
		
		//判断数据类型
		var ad_imgUrl = $.trim($('#ad_imgUrl').val());
				if (ad_imgUrl != '')
				{
					$('#ad_imgUrl_error').hide();
				}else{
					$('#ad_imgUrl_error').show();
					return false;
				}
	}else{
		$('#ad_position_error').show();
		return false;
	}
	return true;
}

$('#ad_position').change(function(){
	var v = $(this).val();
	var name = $(this).find("option[value="+v+"]").text();
	$('.app-background-upload,.default-upload').hide();
	if (name.indexOf('APP_BACKGROUND_IMGS') != -1) {
		$('.app-background-upload').show();
	} else {
		$('.default-upload').show();
	}
})
</script>
