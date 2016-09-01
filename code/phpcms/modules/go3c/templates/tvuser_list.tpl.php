<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=tvuser&a=init">全部数据</a>&nbsp;
			账号：<input name="user_id" type="text" value="<?php if(isset($user_id)) echo $user_id;?>" class="input-text" />
			昵称：<input name="username" type="text" value="<?php if(isset($username)) echo $username;?>" class="input-text" />
			每页：<input name="perpage" type="text" value="<?php echo $perpage;?>" class="input-text" size="3" />个
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
          <tr>
			<th width="200" align="left">用户名</th>
			<th width='200' align="left">昵称</th>
			<th width='200' align="center">注册时间</th>
			<th width='200' align="center">最后上线时间</th>
			<th width="100"  align="center">操作</th>
          </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="left"><?php echo $value['user_id']?></td>
	<td align="left"><?php echo $value['username'];?></td>
	<td align="center"><?php echo $value['registration_date'];?></td>
	<td align="center"><?php echo $value['update_time'];?></td>
	<td align="center">
	  <a href="javascript:editform('<?php echo $value['user_id']?>','<?php echo $value['username']?>')">修改密码</a> | 
	<?php if($value['blocked'] == '0'){?>
		<a style="color:green" href="javascript:lock('<?php echo $value['user_id']?>')">锁定</a>
    <?php }else{?>
		<a style="color:red"  href="javascript:unlock('<?php echo $value['user_id']?>')">解锁</a>
	<?php }?>
	</td> 
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function lock(user_id) {
    location.href ='?m=go3c&c=tvuser&a=ipuser_lock&user_id='+user_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function unlock(user_id){
	location.href ='?m=go3c&c=tvuser&a=ipuser_unlock&user_id='+user_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function editform(user_id,username){
	location.href ='?m=go3c&c=tvuser&a=editform&user_id='+user_id+'&username='+username+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
