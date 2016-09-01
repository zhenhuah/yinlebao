<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=message&a=mes_takendo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return checkAdmes();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">推送源:</th>
		  <td>		
		  <select name="PUSH_SOURCE" id="PUSH_SOURCE">
		  <option value="0">请选择</option>	
		  <option value="BIGTV">BIGTV</option>
		  <option value="KTV">KTV</option>	  	
		  </select> 
		</td>
		</tr>
		<tr>
		  <th width="80">内容类型:</th>
		  <td>		
		  <select name="CONTENT_TYPE" id="CONTENT_TYPE">
		  <option value="0">系统公告</option>	
		  <option value="1">直播频道</option>
		  <option value="201">点播电影</option>
		  <option value="202">点播电视剧</option>
		  <option value="203">点播综艺</option>
		  <option value="3">热门好玩</option>
		  <option value="4">最新新闻</option>
		  <option value="5">最新广告</option> 	
		  </select> 
		</td>
		</tr>
		<tr>
		  <th width="90">消息标题</th>
		  <td>
		  <input type="text" value="" name="MSG_TITLE" id="MSG_TITLE" size="25" >
		  <span id="ad_MSG_TITLE" style="display:none"><font color="red">请填写标题</font> 
		  </td>
		</tr>
		<tr>
		  <th width="90">消息内容</th>
		  <td>
		  <textarea rows="5" cols="50" name="MSG_CONTENT" id="MSG_CONTENT"></textarea>
		  <span id="ad_MSG_CONTENT" style="display:none"><font color="red">请填写内容</font>
		  </td>
		</tr>
		<tr>
		  <th width="90">URL</th>
		  <td>
		  <input type="text" value="" name="MSG_URI" id="MSG_URI" size="55" >
		  <span id="ad_MSG_URI" style="display:none"><font color="red">请填写url</font>
		  </td>
		</tr>
		<tr>
		  <th width="80">推送类型:</th>
		  <td>		
		  <select name="PUSH_TYPE" id="PUSH_TYPE" onchange="checkos()">
		  <option value="0">全部推送</option>
		  <option value="100" id="ados" >操作系统推送</option>
		  <option value="200" id="ados" >客户端推送</option>
		  <option value="300" id="ados" >平台推送</option>
		  <option value="400" id="ados" >指定推送</option>
		  <option value="500" id="ados" >区域推送</option>
		  </select> 
		</td>
		</tr>
		<tr id="ad_os" style="display:none">
		  <th width="80">操作系统:</th>
		  <td><input type="checkbox" name="pushTypeFilter[]" value="ios"  grade="ios" />ios
		  <input type="checkbox" name="pushTypeFilter[]" value="android"  grade="android" />android
		  <input type="checkbox" name="pushTypeFilter[]" value="windows"  grade="windows" />windows
		  <input type="checkbox" name="pushTypeFilter[]" value="linux"  grade="linux" />linux
		  </td>
		</tr>
		<tr id="ad_ss" style="display:none">
		  <th width="80">客户端:</th>
		  <td><input type="checkbox" name="pushTypeFilter[]" value="iphone"  />iphone
		  <input type="checkbox" name="pushTypeFilter[]" value="aphone"  />aphone
		  <input type="checkbox" name="pushTypeFilter[]" value="winphone"  />winphone
		  <input type="checkbox" name="pushTypeFilter[]" value="ipad"  />ipad
		  <input type="checkbox" name="pushTypeFilter[]" value="apad"  />apad
		  <input type="checkbox" name="pushTypeFilter[]" value="winpad"  />winpad
		  <input type="checkbox" name="pushTypeFilter[]" value="appletv"  />appletv
		  <input type="checkbox" name="pushTypeFilter[]" value="atv"  />atv
		  <input type="checkbox" name="pushTypeFilter[]" value="ltv"  />ltv
		  <input type="checkbox" name="pushTypeFilter[]" value="pcweb"  />pcweb
		  <input type="checkbox" name="pushTypeFilter[]" value="pcclien"  />pcclien
		  <input type="checkbox" name="pushTypeFilter[]" value="win8pc"  />win8pc
		  </td>
		</tr>
		<tr id="ad_ps" style="display:none">
		  <th width="80">平台:</th>
		  <td><input type="checkbox" name="pushTypeFilter[]" value="phone"  grade="phone"/>phone
		  <input type="checkbox" name="pushTypeFilter[]" value="pad"  grade="pad"/>pad
		  <input type="checkbox" name="pushTypeFilter[]" value="tv"  grade="tv"/>tv
		  <input type="checkbox" name="pushTypeFilter[]" value="pc"  grade="pc"/>pc
		  </td>
		</tr>
		<tr id="ad_zs" style="display:none">
		  <th width="80">指定:</th>
		  <td><input type="text" name="pushFilter" id="pushFilter" value=""  />（注：多个用户中间用","隔开)
		  </td>
		</tr>
		<tr>
		  <th width="80">推送模式:</th>
		  <td>		
		  <select name="PUSH_CODE" id="PUSH_CODE" onchange="checkcode()">
		   <option value="0">立即推送</option>	
		  <option value="1">定时推送</option>	
		  <option value="2">循环推送</option>
		  </select> 
		</td>
		</tr>
		<tr id="ad_di" style="display:none">
		  <th width="80">推送时间:</th>
		  <td><input type="text" name="pushCodeFilter" id="pushCodeFilter" value=""  />（如：20130827000000，表示2013-08-27 00:00:00)
		  </td>
		</tr>
		<tr id="ad_dixun" style="display:none">
		  <th width="80">循环推送:</th>
		  <td><input type="text" name="starttime" id="starttime" value=""  />到
		  <input type="text" name="endtime" id="endtime" value=""  />（如：20130827000000，表示2013-08-27 00:00:00)
		  推送次数:<input type="text" name="sumc" id="sumc" value=""  />
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="mes_takendo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
function checkos()
{
	var i=document.myform.PUSH_TYPE.selectedIndex;
	var address=document.myform.PUSH_TYPE.options[i].value;

	if(address =='100'){
		$('#ad_os').show();
		$('#ad_ss').hide();
		$('#ad_ps').hide();
		$('#ad_zs').hide();
	}else if(address =='200'){
		$('#ad_ss').show();
		$('#ad_os').hide();
		$('#ad_ps').hide();
		$('#ad_zs').hide();
	}else if(address =='300'){
		$('#ad_ps').show();
		$('#ad_os').hide();
		$('#ad_ss').hide();
		$('#ad_zs').hide();
	}else if(address =='400'){
		$('#ad_zs').show();
		$('#ad_os').hide();
		$('#ad_ss').hide();
		$('#ad_ps').hide();
	}else{
		$('#ad_os').hide();
		$('#ad_ss').hide();
		$('#ad_ps').hide();
		$('#ad_zs').hide();
	}
}
function checkcode()
{
	var i=document.myform.PUSH_CODE.selectedIndex;
	var address=document.myform.PUSH_CODE.options[i].value;

	if(address =='1'){
		$('#ad_di').show();
		$('#ad_dixun').hide();
	}else if(address =='2'){
		$('#ad_dixun').show();
		$('#ad_di').hide();
	}else{
		$('#ad_di').hide();
		$('#ad_dixun').hide();
	}
}
//添加信息表单验证
function checkAdmes()
{
	var MSG_TITLE = $.trim($('#MSG_TITLE').val());
	if (MSG_TITLE != '')
	{
		$('#ad_MSG_TITLE').hide();
	}else{
		$('#ad_MSG_TITLE').show();
		return false;
	}
	var MSG_CONTENT = $.trim($('#MSG_CONTENT').val());
	if (MSG_CONTENT != '')
	{
		$('#ad_MSG_CONTENT').hide();
	}else{
		$('#ad_MSG_CONTENT').show();
		return false;
	}
	var MSG_URI = $.trim($('#MSG_URI').val());
	if (MSG_URI != '')
	{
		$('#ad_MSG_URI').hide();
	}else{
		$('#ad_MSG_URI').show();
		return false;
	}
	return true;
}
</script>
</body>
</html>
