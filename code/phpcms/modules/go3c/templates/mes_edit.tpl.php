<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=message&a=mes_editdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return checkAdmes();">
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
		  <option <?php if ($limitInfo['MODEL_FOR'] == 'BIGTV') echo 'selected'; ?> value="BIGTV">BIGTV</option>
		  <option <?php if ($limitInfo['MODEL_FOR'] == 'KTV') echo 'selected'; ?> value="KTV">KTV</option>	  	
		  </select> 
		</td>
		</tr>
		<tr>
		  <th width="90">消息标题</th>
		  <td>
		  <input type="text" value="<?php echo $limitInfo['MSG_TITLE'];?>" name="MSG_TITLE" id="MSG_TITLE" size="25" >
		  <span id="ad_MSG_TITLE" style="display:none"><font color="red">请填写标题</font> 
		  </td>
		</tr>
		<tr>
		  <th width="90">消息内容</th>
		  <td>
		  <textarea rows="5" cols="50" value="<?php echo $limitInfo['MSG_CONTENT'];?>" name="MSG_CONTENT" id="MSG_CONTENT"><? echo $limitInfo['MSG_CONTENT'];?></textarea>
		  </td>
		</tr>
		<tr>
		  <th width="90">URL</th>
		  <td>
		  <input type="text" value="<?php echo $limitInfo['MSG_URI'];?>" name="MSG_URI" id="MSG_URI" size="52" >
		  </td>
		</tr>
		<tr>
		  <th width="80">消息对象:</th>
		  <td>		
		  <select name="ISTRIGGER" id="ISTRIGGER" onchange="checkuse()">
		  <option value="0">请选择</option>	
		  <option <?php if ($limitInfo['ISTRIGGER'] == '1') echo 'selected'; ?> value="1">用户定制</option>
		  <option <?php if ($limitInfo['ISTRIGGER'] == '2') echo 'selected'; ?> value="2">非用户定制</option> 	
		  </select> 
		</td>
		</tr>
		<tr id="ISTRIGGERch">
		  <th width="80">触发条件:</th>
		  <td>		
		  <select name="ISTRIGGERch" id="ISTRIGGERch">
		   <option value="">请选择</option>
		  <option <?php if ($limitInfo['condition'] == '观看时间') echo 'selected'; ?> value="观看时间">观看时间</option>	
		  <option <?php if ($limitInfo['condition'] == '闲置时间') echo 'selected'; ?> value="闲置时间">闲置时间</option>
		  <option <?php if ($limitInfo['condition'] == '多客户端上线') echo 'selected'; ?> value="多客户端上线">多客户端上线</option>
		  <option <?php if ($limitInfo['condition'] == '多客户端下线') echo 'selected'; ?> value="多客户端下线">多客户端下线</option>
		  <option <?php if ($limitInfo['condition'] == '同一节目观看人数') echo 'selected'; ?> value="同一节目观看人数">同一节目观看人数</option>
		  </select>
		</td>
		</tr>
		<tr id="TRIGGER_CONDITION1">
		  <th width="90">触发参数</th>
		  <td>
		  <input type="text" value="<?php echo $limitInfo['parameter'];?>" name="parameter" id="parameter" size="20" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="mes_editdo" />
	<input type="hidden" name="MODEL_ID" id="MODEL_ID" value="<?php echo $limitInfo['MODEL_ID'];?>" />	
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

	if(address =='2'){
		$('#ISTRIGGERch').hide();
		$('#TRIGGER_CONDITION1').hide();
	}else{
		$('#ISTRIGGERch').show();
		$('#TRIGGER_CONDITION1').show();
	}
}
</script>
</body>
</html>
