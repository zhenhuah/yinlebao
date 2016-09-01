<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<script type="text/javascript" src="<?php echo CKEDITOR_PATH?>ckeditor.js"></script>
<link rel="stylesheet" href="<?php echo CKEDITOR_PATH?>sample.css">
<link href="<?php echo CSS_PATH?>jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.treetable.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dnd-example").treeTable({
    	indent: 20
    	});
  });
  function checknode(obj)
  {
      var chk = $("input[type='checkbox']");
      var count = chk.length;
      var num = chk.index(obj);
      var level_top = level_bottom =  chk.eq(num).attr('level')
      for (var i=num; i>=0; i--)
      {
              var le = chk.eq(i).attr('level');
              if(eval(le) < eval(level_top)) 
              {
                  chk.eq(i).attr("checked",true);
                  var level_top = level_top-1;
              }
      }
      for (var j=num+1; j<count; j++)
      {
              var le = chk.eq(j).attr('level');
              if(chk.eq(num).attr("checked")==true) {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",true);
                  else if(eval(le) == eval(level_bottom)) break;
              }
              else {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",false);
                  else if(eval(le) == eval(level_bottom)) break;
              }
      }
  }
</script>
<form name="myform" id="myform" action="?m=go3c&c=task&a=addinfordo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">标题:</th>
		  <td>		
		  <input type="text" value="" name="title" id="title" size="40" >
		  &nbsp;&nbsp;<span id="ad_title" style="display:none;"><font color="red">标题不能为空</font></span>
		</td>
		</tr>
		<tr>
		  <th width="90">资讯类型 </th>
		  <td>
			<select name="type" id="type">
		 	<option value="0">请选择</option>
		 	<?php {foreach($type_name_list as $key=>$list){?>	
		  	<option value="<?php echo $list['id']?>"><?php echo $list['type_name']?></option>
		  	<?php }} ?>
		  	</select>
		  </td>
		</tr>
		<tr>
		  <th width="90">关键词</th>
		  <td>
		  <input type="text" value="" name="keywords" id="keywords" size="30" >
		  </td>
		</tr>
		<tr>
		  <th width="90">简介</th>
		  <td>
		  <input type="text" value="" name="content" id="content" style="width:280px;" >
		  </td>
		</tr>
		<tr>
		  <th width="90">描述</th>
		  <td>
		  <textarea class="ckeditor" rows="5" cols="41" name="description" id="description"></textarea>
		  &nbsp;&nbsp;<span id="ad_description" style="display:none;"><font color="red">内容不能为空</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">作者</th>
		  <td>
		  <input type="text" value="" name="author" id="author" size="30" >
		  </td>
		</tr>
		<tr>
		  <th width="90">浏览次数</th>
		  <td>
		  <input type="text" value="" name="playcount" id="playcount" size="30" >
		  </td>
		</tr>
		<tr>
		  <th width="90">外链链接</th>
		  <td>
		  <input type="text" value="" name="linkurl" id="linkurl" size="30" >
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
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 文件链接 </th>
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
				//文件上传验证
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
				</script>
				 </td>
		</tr>
			<tr>
			<th width="90">资讯图片</th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			<a href="###" style="display:none;" id="viewImgUrl"><font color="red">预览</font></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<span id="ad_imgUrl_error" style="display:none;"><font color="red">请选择上传文件</font></span>
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
		<th width="90">文件检查</th>
		  <td>
		  <a href="###" style="display:none;" id="viewImgUrl"><font color="blue">浏览</font></a>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" /><font color="red">*</font>文件有效
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认文件正确!</font></span>
		  </td>
		</tr>
		<tr>
<th width="80">区域选择:</th>
<td>
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<?php echo $categorys;?>
</tbody>
</table>
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
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function subtitle()
{
	//文件路径
	var ad_imgUrl = $.trim($('#ad_imgUrl').val());
	if (ad_imgUrl != '')
	{
		$('#ad_imgUrl_error').hide();
	}else{
		$('#ad_imgUrl_error').show();
		return false;
	}
	var title = $.trim($('#title').val());
	if (title != '')
	{
		$('#ad_title').hide();
	}else{
		$('#ad_title').show();
		return false;
	}
	var description = CKEDITOR.instances.editor1.getData();
	if (description != '')
	{
		$('#ad_description').hide();
	}else{
		$('#ad_description').show();
		return false;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
}
</script>
</body>
</html>
