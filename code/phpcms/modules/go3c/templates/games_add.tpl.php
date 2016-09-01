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

<form name="myform" id="myform" action="?m=go3c&c=task_games&a=add_game_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font>标题:</th>
		  <td>		
		  <input type="text" value="" name="app_name" id="app_name" size="40" >
		  &nbsp;&nbsp;<span id="ad_app_name" style="display:none;"><font color="red">标题不能为空</font></span>
		</td>
		</tr>
		<tr>
		  <th width="80">描述:</th>
		  <td>		
		  <textarea name="app_desc" id="app_desc" style="widht:160px;"></textarea>
		</td>
		</tr>	
		<tr>
		  <th width="80"><font color="red">*</font>平台类型</th>
		  <td>
		  <select name="term" id="term">
		      <option value="0">请选择</option>
		      <option value="1">STB</option>
		      <option value="2">IOS</option>
		      <option value="3">ANDROID</option>
		      <option value="4">PC</option>
		      <option value="5">FLASH</option>
		  </select>
		  &nbsp;&nbsp;<span id="ad_term" style="display:none;"><font color="red">平台类型不能为空</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90"><font color="red">*</font>游戏类型 </th>
		  <td>
			<select name="channel_cat_id" id="channel_cat_id">
		 	<option value="0">请选择</option>
		 	<?php {foreach($type_name_list as $key=>$list){?>	
		  	<option value="<?php echo $list['cat_id']?>"><?php echo $list['cat_name']?></option>
		  	<?php }} ?>
		  	</select>
		  	&nbsp;&nbsp;<span id="ad_channel_cat_id" style="display:none;"><font color="red">应用类型不能为空</font></span>
		  </td>
		  <input type="hidden" name="channel" id="channel" value="" />
		</tr>	
		<tr>
		  <th width="80">预发布日期</th>
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
		  <th width="80">评分</th>
		  <td>
		  <input type="text" value="" name="score" id="score" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">价格</th>
		  <td>
		  <input type="text" value="" name="price" id="price" size="10" >
		  </td>
		</tr>
		<tr>
		  <th width="80">浏览次数</th>
		  <td>
		  <input type="text" value="" name="view_count" id="view_count" size="10" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">下载次数</th>
		  <td>
		  <input type="text" value="" name="download_count" id="download_count" size="10" >
		  </td>
		</tr>
		<tr>
		  <th width="80">作者</th>
		  <td>
		  <input type="text" value="" name="owner" id="owner" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">语言</th>
		  <td>
		  <input type="text" value="" name="language" id="language" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">文件hash</th>
		  <td>
		  <input type="text" value="" name="file_hash" id="file_hash" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">包名</th>
		  <td>
		  <input type="text" value="" name="packagename" id="packagename" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">固件版本</th>
		  <td>
		  <input type="text" value="" name="os_ver" id="os_ver" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">分辨率</th>
		  <td>
		  <input type="text" value="" name="screen" id="screen" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">标签</th>
		  <td>
		  <input type="text" value="" name="tag" id="tag" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">序列</th>
		  <td>
		  <input type="text" value="" name="seq" id="seq" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">部件提供方</th>
		  <td>
		  <input type="text" value="" name="widgetProvider" id="widgetProvider" size="30" >
		  </td>
		</tr>
		<tr>
		  <th width="80">来源</th>
		  <td>
		  <input type="text" value="" name="source" id="source" size="30" >
		  </td>
		</tr>	
		<tr>
			<td colspan="3">
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支持手柄&nbsp;&nbsp;<input type="checkbox" name="ishandle">&nbsp;&nbsp;&nbsp;&nbsp;支持键盘 <input type="checkbox" name="iskeyboard">
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支持鼠标 <input type="checkbox" name="ismouse">
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支持遥控器 <input type="checkbox" name="iscontroller">
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;是否网页版 <input type="checkbox" name="isweb">
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;是否需要网络 <input type="checkbox" name="neednet" checked="checked">
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
<script type="text/javascript">
function subtitle()
{
	var title = $.trim($('#app_name').val());
	if (title != '')
	{
		$('#ad_app_name').hide();
	}else{
		$('#ad_app_name').show();
		return false;
	}
	var term = $.trim($('#term').val());
	if (term != 0)
	{
		$('#ad_term').hide();
	}else{
		$('#ad_term').show();
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
