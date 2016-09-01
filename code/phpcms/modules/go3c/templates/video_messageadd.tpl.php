<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="table-list" id="load_priv">
<form name="myform" id="myform" action="?m=go3c&c=tvuser&a=addmessagedo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<tr>
<th width="90">标题:</th>
<td>
<input type="text" value="" name="title" id="title" size="25" >
</td>
</tr>
<tr>
<th width="90">描述:</th>
<td>
<textarea rows="5" cols="50" value="" name="content" id="content"></textarea>
</td>
</tr>
<tr>
<th width="90">账户:</th>
<td>
<input type="text" value="" name="userid" id="userid" size="25" >
</td>
</tr>
<tr>
<th width="90">类型:</th>
<td>
<select name="type" id="type">
	<option value="0">请选择</option>	
	<option value="1">系统消息</option>
	<option value="2">用户消息</option>	  	
</select>(注意:系统消息发送给全部用户) 
</td>
</tr>
</tbody>
</table>
<div class="btn">
<input type="submit"  class="button" name="dosubmit" id="dosubmit" value="<?php echo L('submit');?>" /></div>
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
</script>
</body>
</html>
