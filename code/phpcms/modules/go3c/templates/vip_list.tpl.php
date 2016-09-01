<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="vip_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a style="color:blue" href="?m=go3c&c=tvuser&a=vip_list">全部数据</a>&nbsp;|&nbsp;
			账号：<input name="user_id" type="text" value="<?php if(isset($user_id)) echo $user_id;?>" class="input-text" size="15" />&nbsp;
		    排序字段：<select name="field">
				<option value='registration_date' <?php if($_GET['field']=='registration_date') echo 'selected';?>>生成时间</option>
				<option value='update_time' <?php if($_GET['field']=='update_time') echo 'selected';?>>登陆时间</option>
				<option value='user_id' <?php if($_GET['field']=='user_id') echo 'selected';?>>账号</option>
			</select>&nbsp;
            排序顺序: <select name="order">
				<option value='DESC' <?php if($_GET['order']=='DESC') echo 'selected';?>>降序</option>
				<option value='ASC' <?php if($_GET['order']=='ASC') echo 'selected';?>>升序</option>
			</select>&nbsp;
			每页：<input name="perpage" type="text" value="<?php echo $perpage;?>" class="input-text" size="3" />个
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<br><br>
			批量操作: <select name="multiOperation" id="multiOperation">
				<option value='generate' <?php if($_GET['multiOperation']=='generate') echo 'selected';?>>批量生成VIP</option>
				<option value='export' <?php if($_GET['multiOperation']=='export') echo 'selected';?>>导出全部VIP</option>
			</select>&nbsp;
			<span id="pSum">生成个数(要求: 小于500): </span><input type="text" name="sumGenerate" id="sumGenerate" size="10">&nbsp;
			<input type="button" name="doConfirm" id="doConfirm" class="button" value="确定" />&nbsp;&nbsp;&nbsp;
			<img style="display: none;" src="<?php echo APP_PATH;?>statics/images/loading.gif" id="loading">
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
          <tr>
            <th style="width:30px;" align="right">全选<input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width="200" align="left">用户名</th>
			<th width='200' align="left">密码</th>
			<th width='200' align="center">生成时间</th>
			<th width='200' align="center">最后上线时间</th>
			<th width="100"  align="center">操作</th>
          </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="username" value="<?php echo $value['user_id']?>"  /></td>
	<td align="left"><?php echo $value['user_id']?></td>
	<td align="left"><?php echo $value['vip_pwd'];?></td>
	<td align="center"><?php echo $value['registration_date'];?></td>
	<td align="center"><?php if ($value['update_time']) echo $value['update_time']; else echo '未上线'?></td>
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
	<tr>
	<td align="right">全选<input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	选中项
	<select id="i_select_ckall">
		<option value="1">锁定</option>
		<option value="2">解锁</option>
		</select>
	<input type="button" onclick="doCKALL();" value="确定" />
	</td>
	</tr>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">

$('#multiOperation').change(function(){
	if ($(this).val() == 'generate')
		$('#sumGenerate, #pSum').show();
	else
		$('#sumGenerate, #pSum').hide();
})

$('#doConfirm').click(function(){
	var opt = $('#multiOperation').val();
	var num = $('#sumGenerate').val();
	if (opt == 'generate') {
		if (num > 500) {
			alert('请输入小于500的数字');
			$('#pSum').val('').focus();
			return false;
		} else if (num > 0) {
			$('#loading').show();
			$.ajax({
				url: 'index.php?m=go3c&c=tvuser&num=' + num + '&a=generateAccount&pc_hash='+pc_hash,
				type: 'GET',
				//dataType: 'json',
				success: function(data){
					console.log(data);
					if (data == 'success') {
						alert('成功批量生成 ' + num + ' 个VIP账号');
						location.href ='?m=go3c&c=tvuser&a=vip_list&pc_hash='+pc_hash
				    	+'&goback='+BASE64.encode(location.search);
					}
				}
			});
		} else {
			alert('请输入要生成的个数');
			$('#pSum').focus();
			return false;
		}
	} else {
		location.href ='?m=go3c&c=tvuser&a=exportVip&pc_hash='+pc_hash
    	+'&goback='+BASE64.encode(location.search);
	}
})

function clickCKB(a){
	$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}
function doCKALL(){
	var doitem = $('#i_select_ckall').val() ;
     var str='';
     $("input[name=username]:checked").each(function(){    
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	}
    if(str!=''){
    	if(doitem == '1'){
			location.href ='?m=go3c&c=tvuser&a=multiLock&users='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '2'){
			location.href ='?m=go3c&c=tvuser&a=multiUnlock&users='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
    }
}

function lock(user_id) {
    location.href ='?m=go3c&c=tvuser&a=ipuser_lock&user_id='+user_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function unlock(user_id){
	location.href ='?m=go3c&c=tvuser&a=ipuser_unlock&user_id='+user_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function editform(user_id,username){
	location.href ='?m=go3c&c=tvuser&a=editform&user_id='+user_id+'&username='+username+'&vip=1&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
</body>
</html>