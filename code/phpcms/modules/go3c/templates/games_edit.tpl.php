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

<form name="myform" id="myform" action="?m=go3c&c=task_games&a=edit_game_do&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><font color="red">*</font>标题:</th>
		  <td>		
		  <input type="text" value="<?php echo $game['title']?>" name="app_name" id="app_name" size="40" >
		  &nbsp;&nbsp;<span id="ad_app_name" style="display:none;"><font color="red">标题不能为空</font></span>
		</td>
		</tr>
		<tr>
		  <th width="80">描述:</th>
		  <td>		
		  <textarea name="app_desc" id="app_desc"><?php echo $game['desc']?></textarea>
		</td>
		</tr>	
		<tr>
		  <th width="80"><font color="red">*</font>平台类型</th>
		  <td><?php echo $selectHtml?>&nbsp;&nbsp;<span id="ad_term" style="display:none;"><font color="red">平台类型不能为空</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90"><font color="red">*</font>游戏类型 </th>
		  <td>
			<select name="channel_cat_id" id="channel_cat_id">
		 	<option value="0">请选择</option>
		 	<?php {foreach($type_name_list as $key=>$list){?>	
		  	<option value="<?php echo $list['cat_id']?>" <?php if ($list['cat_name'] == $game['channel']) echo 'selected'?>><?php echo $list['cat_name']?></option>
		  	<?php }} ?>
		  	</select>
		  	&nbsp;&nbsp;<span id="ad_channel_cat_id" style="display:none;"><font color="red">应用类型不能为空</font></span>
		  </td>
		  <input type="hidden" name="channel" id="channel" value="<?php echo $game['channel']?>" />
		</tr>	
		<tr>
		  <th width="80">预发布日期</th>
		  <td>
		  <input type="text" value="<?php echo $game['yufabu_date']?>" name="yufabu_date" id="yufabu_date" size="10" class="date" >
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
		  <th width="80">作者</th>
		  <td>
		  <input type="text" value="<?php echo $game['owner']?>" name="owner" id="owner" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">语言</th>
		  <td>
		  <input type="text" value="<?php echo $game['language']?>" name="language" id="language" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">文件hash</th>
		  <td>
		  <input type="text" value="<?php echo $game['file_hash']?>" name="file_hash" id="file_hash" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">包名</th>
		  <td>
		  <input type="text" value="<?php echo $game['packagename']?>" name="packagename" id="packagename" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">固件版本</th>
		  <td>
		  <input type="text" value="<?php echo $game['os_ver']?>" name="os_ver" id="os_ver" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">分辨率</th>
		  <td>
		  <input type="text" value="<?php echo $game['screen']?>" name="screen" id="screen" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">评分</th>
		  <td>
		  <input type="text" value="<?php echo $game['score']?>" name="score" id="score" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">标签</th>
		  <td>
		  <input type="text" value="<?php echo $game['tag']?>" name="tag" id="tag" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">序列</th>
		  <td>
		  <input type="text" value="<?php echo $game['seq']?>" name="seq" id="seq" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">浏览次数</th>
		  <td>
		  <input type="text" value="<?php echo $game['view_count']?>" name="view_count" id="view_count" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">下载次数</th>
		  <td>
		  <input type="text" value="<?php echo $game['download_count']?>" name="download_count" id="download_count" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">部件提供方</th>
		  <td>
		  <input type="text" value="<?php echo $game['widgetProvider']?>" name="widgetProvider" id="widgetProvider" size="30" >
		  </td>
		</tr>
		<tr>
		  <th width="80">apptest</th>
		  <td>
		  <input type="text" value="<?php echo $game['apptest']?>" name="apptest" id="apptest" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">active</th>
		  <td>
		  <input type="text" value="<?php echo $game['active']?>" name="active" id="active" size="30" >
		  </td>
		</tr>
		<tr>
		  <th width="80">价格</th>
		  <td>
		  <input type="text" value="<?php echo $game['price']?>" name="price" id="price" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">状态</th>
		  <td>
		  <input type="text" value="<?php echo $game['status']?>" name="status" id="status" size="30" >
		  </td>
		</tr>	
		<tr>
		  <th width="80">来源</th>
		  <td>
		  <input type="text" value="<?php echo $game['source']?>" name="source" id="source" size="30" >
		  </td>
		</tr>	
		<tr>
			<td colspan="3">
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   &nbsp;&nbsp;&nbsp;&nbsp;支持手柄&nbsp;&nbsp;<input type="checkbox" name="ishandle" <?php if($game['is_handle'] == 1) echo 'checked'?>>
			   &nbsp;&nbsp;&nbsp;&nbsp;支持键盘 <input type="checkbox" name="iskeyboard" <?php if($game['is_keyboard'] == 1) echo 'checked'?>>
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支持鼠标 <input type="checkbox" name="ismouse" <?php if($game['is_mouse'] == 1) echo 'checked'?>>
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支持遥控器 <input type="checkbox" name="iscontroller" <?php if($game['is_controller'] == 1) echo 'checked'?>>
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;是否网页版 <input type="checkbox" name="isweb" <?php if($game['is_web'] == 1) echo 'checked'?>>
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;是否需要网络 <input type="checkbox" name="neednet" checked="checked" <?php if($game['need_net'] == 1) echo 'checked'?>>
		  	</td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="game_id" id="game_id" value="<?php echo $game['id'] ?>" />
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
