<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task_shop&a=edit_applinkdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data" onSubmit="return appTask();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90"><font color="red">*</font>名称</th>
		  <td>
		  <input type="text" value="<?php echo $applist['title'];?>" name="title" id="title" style="width:280px;"><span id="title_error" style="display:none;"><font color="red">名称不能为空</font></span>
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
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 图片链接 </th>
		  <td>			  
			<input type="hidden" name="imgType" id="ImgText" value="ImgText">
			<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
			<input type="text" value="<?php echo $applist['file_path'];?>" name="ad_imgUrl" id="ad_imgUrl" size="25" >
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
		<tr>
		  <th width="90">图片地址 </th>
		  <td><?php if(!empty($applist['file_path'])){?><a href="<?php echo $applist['file_path'];?>" target="_blank"  id="viewImgUrl"><font color="blue">预览图片</font></a>
		  <?php }else{ echo '暂无图片数据';}?>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font>有效
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认图片正确!</font></span>
		 </td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font>外链链接:</th>
		  <td><input type="text" class="measure-input  input-text" value="<?php echo $applist['url'];?>"  name="url" id="url" style="width:280px;"><span id="playurl_error" style="display:none;"><font color="red">链接不能为空</font></span></td>
		</tr>
		<tr>
		  <th width="80">项目代号:</th>
		  <td>
		  <select name="spid" id="spid">
		  <option value="">选择</option>
		   <?php {foreach($sp_list as $key=>$v){?>
           		 <option value='<?php echo $v['spid']?>' <?php if($applist['spid']==$v['spid']){ echo 'selected';}?>><?php echo $v['spid']?></option>
			<?php }} ?>
		  </select>
		</tr>
		<tr>
		  <th width="80">排序:</th>
		  <td><input type="text" class="measure-input  input-text" value="<?php echo $applist['seq_number'];?>"  name="seq_number" id="seq_number" style="width:50px;"></td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	

	<input type="hidden" name="mode" id="mode" value="edit_applinkdo" />
	<input type="hidden" name="id" id="id" value="<?php echo $applist['id'];?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 

</form>

</body>
</html>
<script type="text/javascript">
//添加外链
function appTask()
{
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
	var url = $.trim($('#url').val());
	if (url != '')
	{
		$('#playurl_error').hide();
	}else{
		$('#playurl_error').show();
		return false;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
	return true;
}
</script>
