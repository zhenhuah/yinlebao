<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_validator = true;include $this->admin_tpl('header');?>
<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#password").formValidator({empty:true,onshow:"<?php echo L('not_change_the_password_please_leave_a_blank')?>",onfocus:"<?php echo L('password').L('between_6_to_20')?>"}).inputValidator({min:6,max:20,onerror:"<?php echo L('password').L('between_6_to_20')?>"});
	$("#pwdconfirm").formValidator({empty:true,onshow:"<?php echo L('not_change_the_password_please_leave_a_blank')?>",onfocus:"<?php echo L('input').L('passwords_not_match')?>",oncorrect:"<?php echo L('passwords_match')?>"}).compareValidator({desid:"password",operateor:"=",onerror:"<?php echo L('input').L('passwords_not_match')?>"});
	$("#email").formValidator({onshow:"<?php echo L('input').L('email')?>",onfocus:"<?php echo L('email').L('format_incorrect')?>",oncorrect:"<?php echo L('email').L('format_right')?>"}).regexValidator({regexp:"email",datatype:"enum",onerror:"<?php echo L('email').L('format_incorrect')?>"});
  })
</script>
<div class="pad_10">
<div class="common-form">
<form name="myform" action="?m=admin&c=admin_manage&a=edit" method="post" id="myform">
<input type="hidden" name="info[userid]" value="<?php echo $userid?>"></input>
<input type="hidden" name="info[username]" value="<?php echo $username?>"></input>
<table width="100%" class="table_form contentWrap">
<tr>
<td width="80"><?php echo L('username')?></td> 
<td><?php echo $username?></td>
</tr>
<tr>
<td><?php echo L('password')?></td> 
<td><input type="password" name="info[password]" id="password" class="input-text"></input></td>
</tr>
<tr>
<td><?php echo L('cofirmpwd')?></td> 
<td><input type="password" name="info[pwdconfirm]" id="pwdconfirm" class="input-text"></input></td>
</tr>
<tr style="display:none;">
<td><?php echo L('email')?></td>
<td>
<input type="text" name="info[email]" value="<?php echo $email?>" class="input-text" id="email" size="30"></input>
</td>
</tr>
<tr>
<td>SPID</td> 
<td>
<?php foreach($spid_array as $sp){?>
<input type="checkbox" id="infospid[]" name="infospid[]" value="<?php echo $sp['spid']?>" 
<?php 
foreach($arr as $v){
if($v==$sp['spid']) echo("checked");
}
?>><?php echo $sp['spid']?>&nbsp;
<?php }	?>
</td>
</tr>
<?php if($username!=='system'){ ?>
<tr>
<td>锁定</td> 
<td>
<select name="info[status]">
<option value="0" <?php echo (($info[status] == 0) ? 'selected="selected"' : '')?>>锁定</option>
<option value="1" <?php echo (($info[status] == 1) ? 'selected="selected"' : '')?>>正常</option>
</select>
</td>
</tr>
<?php }?>
<tr>
<td><?php echo L('realname')?></td>
<td>
<input type="text" name="info[realname]" value="<?php echo $realname?>" class="input-text" id="realname"></input>
</td>
</tr>
<?php if ($_SESSION['roleid']==1) {?>
<tr>
<td><?php echo L('userinrole')?></td>
<td>
<select name="info[roleid]">
<?php 
foreach($roles as $role)
{
?>
<option value="<?php echo $role['roleid']?>" <?php echo (($role['roleid']==$roleid) ? 'selected' : '')?>><?php echo $role['rolename']?></option>
<?php 
}	
?>
</select>
</td>
</tr>
<tr>
<td>区域选择</td>
<td>
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<?php echo $categorys;?>
</tbody>
</table>
</td>
</tr>
<link href="<?php echo CSS_PATH?>jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.treetable.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dnd-example").treeTable({
    	indent: 20
    	});
  });
</script>
<?php }?>
</table>

    <div class="bk15"></div>
    <input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="dialog" id="dosubmit">
</form>
</div>
</div>
</body>
</html>
