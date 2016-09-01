<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=addVideo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data"  onSubmit="return runVideoTask();">
<input type="hidden" name="taskId" value="<?php echo $taskInfo['taskId'];?>" />
<input type="hidden" name="dataType" value="<?php echo $taskInfo['videoSource'];?>" />
<input type="hidden" name="videoId" value="<?php echo $videoId;?>" />

<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="table_form">
	<tbody>	
		<tr>
		  <th width="80"> 视频ID:</th>
		  <td><?php echo $videoInfo['asset_id']?> </td>
		 </tr>

		<tr>
		  <th width="80"> <font color="red">*</font> 标题:</th>
		  <td><?php echo $videoInfo['title']?></td>
		</tr>

		<tr>
		  <th width="80"> 上线日期:</th>
		  <td><?php echo $videoInfo['pub_date']?>&nbsp;</td>
		</tr>

		<tr>
		  <th width="80"> 下线日期:</th>
		  <td><?php echo $videoInfo['offline_date']?>&nbsp;</td>
		</tr>

		<!--tr>
		  <th width="80"> 视频分类:</th>
		  <td><label style="width:80px" class="ib"><?php echo $column_data[$videoInfo['column_id']];?></label>  </td>
		</tr-->
		<tr>
		  <th width="80"> 是否可用:</th>
		  <td><label style="width:80px" class="ib"><?php if($videoInfo['active'] == '1'){echo '可用';}elseif($videoInfo['active'] == '0'){echo '不可用';}?></label>  </td>
		</tr>

		<tr>
		  <th width="90"> <font color="red">*</font> 推荐后的标题:</th>
		  <td><input type="text" class="measure-input  input-text" value="<?php echo $videoInfo['title']?>"  name="videoTitle" id="videoTitle" style="width:280px;"><span id="videoTitle_error" style="display:none;"><font color="red">此标题不能为空</font></span></td>
		</tr>

		<tr>
		  <th width="80"> 视频简介:</th>
		  <td>
		  <input type="text" class="measure-input  input-text" value="<?php echo $videodataInfo['short_desc']?>"  name="videoDesc" id="videoDesc" style="width:280px;"></td>
		</tr>
		<tr>
		  <th width="80"> 详细介绍:</th>
		  <td><textarea rows="5" cols="41" name="long_desc" id="long_desc"><?php echo $videodataInfo['long_desc'];?></textarea></td>
		</tr>
	
		<tr>
		  <th width="120">视频清晰度(或类型):</th>
		  <td>
			  <select name="videoClarity" id="videoClarity">
				<option value="">请选择</option>
				<?php if(!empty($cms_video_content)){
					foreach($cms_video_content as $key => $value)
					{?>
					<option value="<?php echo $value['clarity']?>">清晰度<?php echo $value['clarity']?></option>
				<?php 	}}?>
			  </select>&nbsp;<span id="videoClarity_error" style="display:none;"><font color="red">请选择清晰度类型</font></span>
		  </td>
		</tr>
    
	<tr>
		  <th width="80"><font color="red">*</font>设置海报:</th>
		  <td>
			 <input type="radio" name="ImgDataType"  value="db" id="isDataBase" onclick="viewImageData(this.value);" checked>视频相关的海报尺寸&nbsp;
			 <input type="radio" name="ImgDataType"  value="isUpload" id="isUserUpload" onclick="viewImageData(this.value);">自己上传&nbsp;<span id="ImgDataType_error" style="display:none;"><font color="red">请设置海报</font></span>
		 </td>
	</tr>
		<tr id="isDataBase_Div" <?php if(empty($taskInfo['imgType'])){echo 'style="display:none;"';}?>>
		  <th width="100">视频海报类型:</th>
		  <td>
		  	<?php if(!empty($imgTypeList)){
					foreach($imgTypeList as $key => $value)
					{?>
					<input type="hidden" id="<?php echo $value['type']?>_imgPathUrl" value="<?php echo $value['path']?>">
			<?php }}?>
			 <select name="videoImg" id="videoImg" onChange="imgType(this.value);">
				<option value="">请选择</option>
				<?php if(!empty($imgTypeList)){
					foreach($imgTypeList as $key => $value)
					{?>
					<option value="<?php echo $value['type']?>" <?php if($taskInfo['imgType'] == $value['type']){echo 'selected';}?>>
					<?php 
						$imgInfo = $dbImgTypeList[$value['type']];
						if(!empty($imgInfo))
						{
							echo $value['type'].'-'.$imgInfo['description'].'('.$imgInfo['resolution_ratio'].')';
						}else{
							echo '尺寸类型'.$value['type'];
						}
					?>
					</option>
				<?php }}?>
			  </select>&nbsp;<span id="videoImg_error" style="display:none;"><font color="red">请选择视频海报类型</font></span><a href="###" target="_blank" id="lookImg" style="display:none;">浏览海报</a>
			</td>
		</tr>
		<tr id="isUserUpload_Div2" style="display:none;">
		  <th width="80"><font color="red">*</font>图片类型:</th>
		  <td>
		  <select name="imgType" id="imgType" onChange="imgType(this.value);">
		   <option value="">请选择</option>
		  <option value="121">正方形(360*360)</option>
		  <option value="102">树图(240*360)</option>
		  <option value="103">横图(360*240)</option>
		  <option value="111">背景大图(1280*720)</option>
		  <option value="122">icon(72*72)</option>
		  </select>
		  </td>
		</tr>
		<tr id="isUserUpload_Div" style="display:none;">
		  <th width="80"><font color="red">*</font>海报大图:</th>
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
//海报选择方式
function viewImageData(typeID)
{
	if (typeID == 'db')	//选择库里的数据
	{
		$('#isUserUpload_Div').hide();
		$('#isUserUpload_Div2').hide();
		$('#isDataBase_Div').show();		
		$('#ImgDataType_error').hide();
	}else if (typeID == 'isUpload')	//自己上传
	{
		$('#isDataBase_Div').hide();
		$('#isUserUpload_Div').show();
		$('#isUserUpload_Div2').show();
		$('#ImgDataType_error').hide();
	}
}

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

//添加视频具体任务
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
/*
	//清晰度或类型
	var videoClarity = $.trim($('#videoClarity').val());
	if (videoClarity > 0)
	{
		$('#videoClarity_error').hide();
	}else{
		$('#videoClarity_error').show();
		return false;
	}
	*/

	//海报方式
	var selectImgTypeOne = $('#isDataBase').attr('checked');	//方式一
	var selectImgTypeTwo = $('#isUserUpload').attr('checked');	//方式二

	if (selectImgTypeOne)	//从数据库选择尺寸列表
	{
		$('#ImgDataType_error').hide();
		//imgType
		var videoImg = $.trim($('#videoImg').val());

		if (videoImg != '')
		{
			$('#videoImg_error').hide();
		}else{
			$('#videoImg_error').show();
			return false;
		}
	}else if (selectImgTypeTwo){//自己上传
		$('#ImgDataType_error').hide();
		var videoImgUrl = $.trim($('#videoImgUrl').val());
		if (videoImgUrl != '')
		{
			$('#videoImgUrl_error').hide();
		}else{
			$('#videoImgUrl_error').show();
			return false;
		}
	}else{	
		$('#ImgDataType_error').show();
		return false;
	}

	return true;
}
</script>
