<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=editVideo&preId=<?php echo $preInfo['preId']?>" method="post" enctype="multipart/form-data" onSubmit="return checkVideoTaskFrom();">
<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	<table width="100%" cellspacing="0" class="table_form">
		<tr>
			  <th width="80">终端类型:</th>
			  <td>
					<?php 
						if(!empty($preInfo['posidInfo']))
						{
							$posidData = explode('-',$preInfo['posidInfo']);
							echo $posidData[0];
						} 
					?> 
			  </td>
		</tr>
		
		<tr>
			  <th width="80">推荐位类型:</th>
			  <td>
					<!--<?php echo $preInfo['posidInfo']; ?>-->
					<?php 
						echo $posidData[0].'-'.$posidData[1];
					?> 
			  </td>
		</tr>
		
		<tr>
		  <th width="80"> spid:</th>
		  <td><?php echo $preInfo['spid'];?></td>
		</tr>
		
		<tr>
			<th width="80"> VID:</th>
		  <td><?php echo $preInfo['videoId'];?></td>
		</tr>
		
		<tr>
		  <th width="80"> <font color="red">*</font> 视频名称:</th>
		  <td>
		  <input type="text" style="width:250px;" name="videoTitle" id="videoTitle" value="<?php echo $preInfo['videoTitle'];?>" style="color:" class="measure-input " />
		   <span style="display:none;" id="videoTitle_error" >
		   <font color="red">视频名称不能为空</font></span>
		  </td>
		</tr>
		<?php if($isout=='0'){?>
		<tr>
		  <th width="80"> 播放地址:</th>
		  <td>
			  <?php {foreach($preconInfo as $conInfo){?> 
			 <a style="color:green" target="_blank"><?php echo $conInfo['path']?></a></br>
		  <?php }} ?>
		  </td>
		</tr>
		<?php }?>
		<?php if($isout=='1'){?>
		<tr>
		  <th width="80"> 视频链接:</th>
		  <td>
		  <input type="text" style="width:350px;" name="videoPlayUrl" id="videoPlayUrl" value="<?php echo $preInfo['videoPlayUrl'];?>" style="color:" class="measure-input " />
		  </td>
		</tr>
		<?php }?>	
		
		<tr>
		  <th width="80"> 展示图(海报):</th>
		  <td>
			  <?php if(!empty($preInfo['videoImg'])){?>
				<a href="<?php echo $preInfo['videoImg'];?>" style="color:green" target="_blank">浏览图片</a>
			  <?php }?>
		 </td>
		</tr>
		<tr id="viewImagePostion">
		  <th width="80"> 设置海报:</th>
		  <td>
			 <input type="radio" name="ImgDataType"  id="isDataBase" value="db" onclick="viewImage('<?php echo $preInfo['videoSource']?>','<?php echo $preInfo['videoId']?>');">视频海报尺寸&nbsp;
			 <input type="radio" name="ImgDataType"  id="isUserUpload" value="isUpload" onclick="viewImage('100','100');">自己上传&nbsp; <a href="javascript:resetImg();" id="ResetImg" style="display:none;">取消重置</a>
		 </td>
		</tr>

		<?php if($preInfo['videoSource'] == '2'){ ?>
		<tr id="SelectImageType" style="display:none;">
		  <th width="120"> 请选择海报类型:</th>
		  <td>
		  	<?php if(!empty($imgList)){
					foreach($imgList as $key => $value)
					{?>
					<input type="hidden" id="<?php echo $value['type']?>_imgPathUrl" value="<?php echo $value['path']?>">
			<?php }}?>
			<select name="videoImg" id="videoImg" onChange="imgType(this.value);">
				<option value="">请选择</option>
				<?php if(!empty($imgList)){
					foreach($imgList as $key => $value){ ?>
						<option value="<?php echo $value['type']?>" <?php if($preInfo['imgType'] == $value['type']){ echo 'selected';}?>>
						<?php if(!empty($value['nameInfo'])){?>
						<?php echo $value['type'];?>-<?php echo $value['nameInfo']['description'];?>(<?php echo $value['nameInfo']['resolution_ratio'];?>)</option>
						<?php }else{ echo '尺寸类型'.$value['type'];}?>
				<?php }} ?>
			</select>&nbsp;<span id="videoImg_error" style="display:none;"><font color="red">请选择视频海报类型</font></span><a href="###" target="_blank" id="lookImg" style="display:none;">浏览海报</a>
		  </td>
		</tr>
		<?php }else{?>
		<tr id="SelectImageType" style="display:none;">
		  <th width="120"> 请选择海报类型:</th>
		  <td>
				<?php if(!empty($imgList['img'])){?>					
					<input type="hidden" id="img_imgPathUrl" value="<?php echo $imgList['img'];?>">
				<?php }?>
				<?php if(!empty($imgList['imgpath'])){?>					
					<input type="hidden" id="imgpath_imgPathUrl" value="<?php echo $imgList['imgpath'];?>">
				<?php }?>
			<select name="videoImg" id="videoImg" onChange="imgType(this.value);">
				<option value="">请选择</option>
				<?php if(!empty($imgList['img'])){?>					
					<option value="img" <?php if($preInfo['imgType'] == 'img'){echo 'selected';}?>>长方形台标</option>
				<?php }?>
				<?php if(!empty($imgList['imgpath'])){?>					
					<option value="imgpath" <?php if($preInfo['imgType'] == 'imgpath'){echo 'selected';}?>>正方形台标</option>
				<?php }?>
			</select>&nbsp;<span id="videoImg_error" style="display:none;"><font color="red">请选择视频海报类型</font></span><a href="###" target="_blank" id="lookImg" style="display:none;">浏览海报</a>
		  </td>
		</tr>
		<!--tr id="isUserUpload_Div" style="display:none;">
		  <th width="80"><font color="red">*</font>上传海报:</th>
		  <td><input type="file" name="videoImgUrl" id="videoImgUrl" value=""/>
		  &nbsp;<span id="videoImgUrl_error" style="display:none;"><font color="red">请选择海报上传</font></span>
		  </td>
		</tr-->
		<?php }?>
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
		  <!--td><input type="file" name="videoImgUrl" id="videoImgUrl" value=""/>
		  &nbsp;<span id="videoImgUrl_error" style="display:none;"><font color="red">请选择海报上传</font></span>
		  </td-->
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
		<tr>
		  <th width="80"> 视频简介:</th>
		  <td>
		  <input type="text" class="measure-input  input-text" value="<?php echo $preInfo['videoDesc']?>"  name="videoDesc" id="videoDesc" style="width:280px;"></td>
		</tr>
		<?php if($isout=='0'){?>
		<tr>
		  <th width="80"> 详细介绍:</th>
		  <td><textarea name="long_desc" id="long_desc" cols="40" rows="5"><?php echo $preInfo['long_desc']?></textarea>
		  </td>
		</tr>
		<?php }?>	
		<tr>
		  <th width="80"> 位置排序:</th>
		  <td><input type="text" name="videoSort" id="videoSort" size="12" value="<?php echo $preInfo['videoSort']?>" class="input-text"  >  </td>
		</tr>

		<tr>
		  <th width="80"> 是否可用:</th>
		  <td>
			<input type="radio" name="status"  value="Y"  <?php if($preInfo['status'] == 'Y'){echo 'checked';}?>>可用&nbsp;
			 <input type="radio" name="status"  value="N"  <?php if($preInfo['status'] == 'N'){echo 'checked';}?>>不可用&nbsp;
			</td>
		</tr>			
	</table>
<div class="bk10"></div>
<div class="btn" style="float:right">
	<input type="hidden" name="preId" id="preId" value="<?php echo $preInfo['preId'];?>" />
	<input type="hidden" name="videoId" id="videoId" value="<?php echo $preInfo['videoId'];?>" />
	<input type="hidden" name="taskId" id="taskId" value="<?php echo $preInfo['taskId'];?>" />
	<input type="hidden" name="term_id" id="term_id" value="<?php echo $preInfo['term_id'];?>" />
	<input type="submit" class="button" name="doSubmit" id="doSubmit" value="保存" />&nbsp;&nbsp;
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
imgType('<?php echo $preInfo['imgType'];?>');	//默认尺寸浏览效果

//海报设置
function viewImage(sourceData,vid)
{
	if (sourceData > 0)
	{
		if (sourceData == '100')	//自己上传
		{
			$('#SelectImageType').hide();
			$('#isUserUpload_Div').show();
			$('#isUserUpload_Div2').show();
		}else{
			$('#videoImgUrl').val('');	//清空一下
			if (vid != '')
			{
				/*$.ajax({
					type: "GET",
					url: '?m=go3c&c=task&a=getVideoImg&sourceType='+sourceData+'&videoId='+vid+'&pc_hash='+pc_hash,
					success: function(msg){
						alert(msg);
					}
				});
				*/
				$('#isUserUpload_Div').hide();
				$('#isUserUpload_Div2').hide();
				$('#SelectImageType').show();
			}else{
				return false;
			}					
		}
		$('#ResetImg').show();
	}else{
		return false;
	}
}

//取消选中效果
function resetImg()
{
	$('#isUserUpload_Div').hide();
	$('#isUserUpload_Div2').hide();
	$('#SelectImageType').hide();
	$("input[name=ImgDataType]").removeAttr("checked");
	$('#ResetImg').hide();
}
</script>