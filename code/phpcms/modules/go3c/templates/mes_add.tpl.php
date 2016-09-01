<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=message&a=addmesdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return checkAdmes();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">消息源:</th>
		  <td>		
		  <select name="MODEL_FOR" id="MODEL_FOR">
		  <option value="0">请选择</option>	
		  <option value="BIGTV">BIGTV</option>
		  <option value="KTV">KTV</option>	  	
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
		  </td>
		</tr>
		<tr>
		  <th width="90">URL</th>
		  <td>
		  <input type="text" value="" name="MSG_URI" id="MSG_URI" size="55" >
		  </td>
		</tr>
		<tr>
		  <th width="80">消息对象:</th>
		  <td>		
		  <select name="ISTRIGGER" id="ISTRIGGER" onchange="checkuse()">
		  <option value="0">请选择</option>
		  <option value="1">用户定制</option>
		  <option value="2">非用户定制</option>	
		  </select> 
		</td>
		</tr>
		<tr id="ISTRIGGERch" style="display:none">
		  <th width="80">触发条件:</th>
		  <td>		
		  <select name="ISTRIGGERch" id="ISTRIGGERch" onchange="checkcode()">
		   <option value="">请选择</option>	
		  <option value="观看时间">观看时间</option>
		  <option value="闲置时间">闲置时间</option>
		  <option value="多客户端上线">多客户端上线</option>
		  <option value="多客户端下线">多客户端下线</option>
		  <option value="同一节目观看人数">同一节目观看人数</option>
		  </select> 
		</td>
		</tr>
		<tr id="TRIGGER_CONDITION1" style="display:none">
		  <th width="90">触发参数</th>
		  <td>
		  <input type="text" value="" name="TRIGGER_CONDITION1" id="TRIGGER_CONDITION1" size="10" >----
		  <input type="text" value="" name="TRIGGER_CONDITION2" id="TRIGGER_CONDITION2" size="10" >(时间为单位秒)
		  </td>
		</tr>
		<tr id="TRIGGER_CONDITION2" style="display:none">
		  <th width="90">触发参数</th>
		  <td>
		  <input type="text" value="" name="TRIGGER_CONDITION3" id="TRIGGER_CONDITION3" size="10" >----
		  <input type="text" value="" name="TRIGGER_CONDITION4" id="TRIGGER_CONDITION4" size="10" >(时间为单位秒)
		  </td>
		</tr>
		<tr id="TRIGGER_CONDITION3" style="display:none">
		  <th width="90">触发参数</th>
		  <td>
		  <input type="text" value="" name="TRIGGER_CONDITION5" id="TRIGGER_CONDITION5" size="10" >----
		  <input type="text" value="" name="TRIGGER_CONDITION6" id="TRIGGER_CONDITION6" size="10" >(人数单位个)
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="addmesdo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script type="text/javascript">
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
	
	return true;
}
function checkuse()
{
	var i=document.myform.ISTRIGGER.selectedIndex;
	var address=document.myform.ISTRIGGER.options[i].value;

	if(address =='1'){
		$('#ISTRIGGERch').show();
	}else{
		$('#ISTRIGGERch').hide();
		$('#TRIGGER_CONDITION1').hide();
		$('#TRIGGER_CONDITION2').hide();
		$('#TRIGGER_CONDITION3').hide();
	}
}
function checkcode()
{
	var i=document.myform.ISTRIGGERch.selectedIndex;
	var address=document.myform.ISTRIGGERch.options[i].value;

	if(address =='观看时间'){
		$('#TRIGGER_CONDITION1').show();
	}else if(address =='闲置时间'){
		$('#TRIGGER_CONDITION2').show();
	}else if(address =='同一节目观看人数'){
		$('#TRIGGER_CONDITION3').show();	
	}else{
		$('#TRIGGER_CONDITION1').hide();
		$('#TRIGGER_CONDITION2').hide();
		$('#TRIGGER_CONDITION3').hide();
	}
}
</script>
</body>
</html>
