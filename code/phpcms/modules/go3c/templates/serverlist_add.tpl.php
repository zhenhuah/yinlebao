<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=client&a=addserverdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return checkAdserver();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90">服务名称 </th>
		  <td>
		  <input type="text" value="" name="SERVER_NAME" id="SERVER_NAME" size="25" >
		  <span id="ad_SERVER_NAME" style="display:none;"><font color="red">请填写名称 !</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">服务器地址 </th>
		  <td>
		  <input type="text" value="" name="server1" id="server1" size="50" >
		  <span id="ad_server1" style="display:none;"><font color="red">请填写地址!</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">备用服务器地址 </th>
		  <td>
		  <input type="text" value="" name="server2" id="server2" size="50" >
		  </td>
		</tr>
		<tr>
		  <th width="90">项目代号 </th>
		  <td>
		  <select id="spid" name="spid">
		  <option value=''>全部</option>
	      <?php  foreach ($spid_list as $key => $spid) {?>
		  <option value='<?php echo $spid['spid']?>' <?php if($_GET['spid']==$spid['spid']) echo 'selected';?>><?php echo $spid['spid']?></option>
		  <?php }?>
		</select>
		  <span id="ad_spid" style="display:none;"><font color="red">请填写项目代号!</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">板子型号 </th>
		  <td>
		  <select id="board" name="board_type">
		  <option value=''>全部</option>
	      <?php  foreach ($board_list as $key => $board) {?>
		  <option value='<?php echo $board['board_type']?>' <?php if($_GET['board_type']==$board['board_type']) echo 'selected';?>><?php echo $board['board_type']?></option>
		  <?php }?>
		</select>
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="addserverdo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 

</form>

</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
//添加广告任务表单验证
function checkAdserver()
{
	var SERVER_NAME = $.trim($('#SERVER_NAME').val());
	if (SERVER_NAME != '')
	{
		$('#ad_SERVER_NAME').hide();
	}else{
		$('#ad_SERVER_NAME').show();
		return false;
	}
	var server1 = $.trim($('#server1').val());
	if (server1 != '')
	{
		$('#ad_server1').hide();
	}else{
		$('#ad_server1').show();
		return false;
	}
	var spid = $.trim($('#spid').val());
	if (spid != '')
	{
		$('#ad_spid').hide();
	}else{
		$('#ad_spid').show();
		return false;
	}
}
</script>
