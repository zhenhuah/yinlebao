<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>jquery.bigcolorpicker.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.bigcolorpicker.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery-1.7.2.min.js"></script>
<form name="myform" id="myform" action="?m=go3c&c=task&a=addAdvert&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return checkAdvertTask();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font> 广告位:</th>
		  <td>
		   <?php if(!empty($term_adverts_list)){ foreach($term_adverts_list as $key => $row){ ?>		  
			<input type="hidden" id="parentId_<?php echo $row['id']; ?>" value="<?php echo $row['ad_type'];?>" >
		  <?php }}?>			
		  <select name="ad_position" id="ad_position" onChange="checkParentId();">
		  <option value="">请选择</option>
		  <?php if(!empty($term_adverts_list)){ foreach($term_adverts_list as $key => $row){ ?>		  
		  <option value="<?php echo $row['id'].'|'.$row['display_type']; ?>">
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
					case '0':	//图片
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
					case '5':	//全屏视频
						$display_type = '全屏视频';
					break;
					case '6':	//顶部跑马灯
						$display_type = '顶部跑马灯';
					break;
					case '7':	//底部跑马灯
						$display_type = '底部跑马灯';
					break;
					case '8':	//右下角弹窗
						$display_type = '右下角弹窗';
					break;
				}
				echo $row['title'].'-('.$ad_type.'-'.$display_type.')';
			?>
		  </option>
		  <?php }}?>			
		  </select>&nbsp;<span id="ad_position_error" style="display:none"><font color="red">请选择广告推荐位</font> 
		</td>
		</tr>
		<tr>
		  <th width="90">标题 </th>
		  <td>
			<input type="text" value="" name="title" id="title" size="25" >
		  </td>
		</tr>
		<tr>
		  <th width="90">文字描述 </th>
		  <td>
		   <textarea name="ad_adDesc" id="ad_adDesc" style="widht:160px;"></textarea>
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
		<!--
		<tr>
		  <th width="90"><font color="red" id="data_2"  class="data" style="display:none">*</font> 图片类型 </th>
		  <td>
			<input type="radio" name="imgType" id="ImgText" value="ImgText" onclick="setImgType(this.value);">输入地址&nbsp;<input type="radio" name="imgType" id="ImgFile" value="ImgFile" onclick="setImgType(this.value);">上传图片&nbsp;<span id="imgType_error" style="display:none;"><font color="red">请选择图片类型</font></span>
		  </td>
		</tr>
		-->
		<tr>
		<tr id="view_ImgText" >
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
			</span></br>
			请输入一个图片链接或上传一个本地图片!
			<span id="ad_imgUrl_error" style="display:none;"><font color="red">请选择上传图片</font></span>
		  &nbsp;&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red">请设置图片地址</font></span>
		  </td>
		</tr>
		<tr id="view_ImgFile" style="display:none;">
		  <th width="90"><font color="red" id="data_2_1"  class="data" style="display:none">*</font>图片 链接</th>
		  <td>
			<input type="file" value="" name="ad_imgUrlFile" id="ad_imgUrlFile" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrlFile_error" style="display:none;"><font color="red">请选择上传图片或输入地址</font></span>
		  </td>
		</tr>
		<tr>
		<th width="90">图片检查</th>
		  <td>
		  <a href="###" id="viewImgUrl"><font color="blue">浏览</font></a>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype"/><font color="red">*</font>有效
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认图片正确!</font></span>
		  </td>
		</tr>
		<tr>
			<th>超链接方式</th>
			<td><input type="radio" class="link-way" name="link" value="1" checked="checked">链接 <input type="radio" class="link-way" name="link" value="0">启动app</td>
		</tr>
		<tr class="tr-link">
		  <th width="90"><font color="red" id="data_3"  class="data" style="display:none">*</font> 链接地址 </th>
		  <td>
		  <input type="text" value="" name="ad_linkUrl" id="ad_linkUrl" size="25" >
		  &nbsp;&nbsp;<span id="ad_linkUrl_error" style="display:none;"><font color="red">请设置链接地址</font></span>
		  </td>
		</tr>
		<tr class="tr-app" style="display:none">
			<th>app信息</th>
			<td>
				应用名:<input type="text" name="app_name"><br>
				应用包:<input type="text" name="app_package"><br>
				应用URL:<input type="text" name="app_url"><br>
			</td>
		</tr>
		<tr>
		  <th width="90"><font color="red" id="data_3"  class="data" style="display:none">*</font>项目平台 </th>
		  <td>
		   <select name="board_type" id="board_type">
		  <option value="">请选择</option>
		   <?php if(!empty($sp_list)){ foreach($sp_list as $key => $srow){ ?>		  
		  <option value="<?php echo $srow['board_type']; ?>"><?php echo $srow['board_type'];?>
		   </option>
		  <?php }}?>			
		  </select>
		  &nbsp;&nbsp;<span id="board_type_error" style="display:none;"><font color="red">请设置项目平台</font></span>
		  </td>
		</tr>
		<tr id="btn_txt" style="display:none">
			<th>按钮文本</th>
			<td><input type="text" name="btn_txt"></td>
		</tr>
		<tr id="TRIGGER_starttime" style="display:none">
			<th width="90">开始时间</th>
				<td>
					<input type="text" name="starttime" id="starttime" size="15" class="date" readonly/>
					<script type="text/javascript">
						Calendar.setup({
						weekNumbers: true,
						inputField : "starttime",
						trigger    : "starttime",
						dateFormat: "%Y-%m-%d",
						showTime: false,
						minuteStep: 1,
						onSelect   : function() {this.hide();}
					});
			</script>
					&nbsp;结束时间&nbsp;
					<input type="text" name="endtime" id="endtime" size="15" class="date" readonly/>
					<script type="text/javascript">
						Calendar.setup({
						weekNumbers: true,
						inputField : "endtime",
						trigger    : "endtime",
						dateFormat: "%Y-%m-%d",
						showTime: false,
						minuteStep: 1,
						onSelect   : function() {this.hide();}
					});
			</script>
				</td>
		</tr>
		<tr id="TRIGGER_duration" style="display:none">
			<th width="90">持续时间</th>
				<td>
					<input type="text" name="duration" id="duration" size="15" />(s)&nbsp;&nbsp;显示次数&nbsp;&nbsp;
					<input type="text" name="num" id="num" size="15" />
				</td>
		</tr>
		<tr id="TRIGGER_dire" style="display:none">
			<th width="90">移动方向</th>
				<td>
					<input type="text" name="dire" id="dire" size="15" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;移动速度&nbsp;&nbsp;&nbsp;
					 <select name="speed" id="speed">
					 <option value="">请选择</option>
					 <option value="快">快</option>
					 <option value="慢">慢</option>
					 </select>
				</td>
		</tr>
		<tr id="TRIGGER_width" style="display:none">
			<th width="90">宽度</th>
				<td>
					<input type="text" name="width" id="width" size="15" />(px)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;高度&nbsp;&nbsp;&nbsp;
					<input type="text" name="height" id="height" size="15" />(px)
				</td>
		</tr>
		<tr id="TRIGGER_color" style="display:none">
			<th width="90">字体颜色</th>
				<td>
					<input type="text" name="color" id="color" size="15" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;弹出方式&nbsp;&nbsp;&nbsp;
					<input type="text" name="pop" id="pop" size="15" />
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
<script type="text/javascript">

$('.link-way').click(function(){
	var v = $(this).val();
	if (v == 1) {
		$('.tr-link').show();
		$('.tr-app').hide();
	} else {
		$('.tr-link').hide();
		$('.tr-app').show();
	}
})

$('#viewImgUrl').click(function(){
	window.open($('#ad_imgUrl').val());
})

$(function(){
	//1、用法
	$("#color").bigColorpicker("color","L",10);	
	$("#f333").bigColorpicker("f3","L",6);
});
function checkParentId()
{
	var i=document.myform.ad_position.selectedIndex;
	var address=document.myform.ad_position.options[i].value;
	strs=address.split("|"); //字符分割
	if(strs[1] =='1'||strs[1] =='2'||strs[1] =='6'||strs[1] =='7'||strs[1] =='8'){
		$('#TRIGGER_duration').show();
		$('#TRIGGER_dire').show();
		$('#TRIGGER_width').show();
		$('#TRIGGER_color').show();
		$('#TRIGGER_starttime').show();
		$('#btn_txt').show();
	}else{
		$('#TRIGGER_duration').hide();
		$('#TRIGGER_dire').hide();
		$('#TRIGGER_width').hide();
		$('#TRIGGER_color').hide();
		$('#TRIGGER_starttime').hide();
		$('#btn_txt').hide();
	}
}
//添加广告任务表单验证
function checkAdvertTask()
{
	var ad_imgUrl = $('#ad_imgUrl').val();
	if (ad_imgUrl==''){
		$('#ad_imgUrl_error').show();
		return false ;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
	//名称 ad_position
	var ad_position = $.trim($('#ad_position').val());

	if (ad_position != '')
	{
		$('#ad_position_error').hide();
		
		//判断数据类型
		var ad_type = $('#parentId_'+ad_position).val();

		if (ad_type == '1')	//文字
		{
			var ad_adDesc = $.trim($('#ad_adDesc').val());
			if (ad_adDesc != '')
			{
				$('#ad_adDesc_error').hide();
			}else{
				$('#ad_adDesc_error').show();
				return false;
			}
		}else if (ad_type == '2'){	//图片

			var ad_imgUrl = $.trim($('#ad_imgUrl').val());
				if (ad_imgUrl != '')
				{
					$('#ad_imgUrl_error').hide();
				}else{
					$('#ad_imgUrl_error').show();
					return false;
				}
		}else if (ad_type == '3'){	//视频
			var ad_linkUrl = $.trim($('#ad_linkUrl').val());
			if (ad_linkUrl != '')
			{
				$('#ad_linkUrl_error').hide();
			}else{
				$('#ad_linkUrl_error').show();
				return false;
			}
		}
	}else{
		$('#ad_position_error').show();
		return false;
	}
	
	//项目平台
	var board_type = $.trim($('#board_type').val());
	if (board_type != '')
	{
		$('#board_type_error').hide();
	}else{
		$('#board_type_error').show();
		return false;
	}
	
	return true;
}
</script>
</body>
</html>
