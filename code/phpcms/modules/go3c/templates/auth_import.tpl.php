<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>

<form enctype="multipart/form-data" action="?m=go3c&c=auth&a=importdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
项目名称: <input type="text" name="cid" ><br><br>
鉴权文件: <input type="file" name="authcode" ><br><br>
</div>
</div>
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="submit" class="button" name="dosubmit" value="导入" />&nbsp;
</div> 
</form>