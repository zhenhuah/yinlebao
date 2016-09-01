<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad_10">
<div class="common-form">
<form name="myform"  action="" method="GET">
  <input type="hidden" value="go3c" name="m">
  <input type="hidden" value="tvuser" name="c">
  <input type="hidden" value="edit" name="a">
  <input type="hidden" value="<?php echo $isvip?>" name="vip">
  <input type="hidden" value="<?php echo $user_id;?>" name="user_id">
  <input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
  <table width="100%" class="table_form">
	<tbody>
	<tr><td>账号</td><td><?php echo $user_id;?></td></tr>
	<tr><td>昵称</td><td><?php echo $username;?></td></tr>
	<tr>
	  <td width="100">密码</td> 
	  <td><input type="text" name="password" id="password" value="" size="40" class="input-text"></td>
	</tr>
  </table>
  <div class="bk15"></div>
  <input type="submit" class="button" value="保存" name="dosubmit">
  <input type="button" class="button" value="取消" onclick="history.go(-1)">
</form>
</div>
</div>
</body>
</html>
