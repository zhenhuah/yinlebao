<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加授权码</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="code" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=auth&a=code">全部数据</a>&nbsp;
			名称：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>

<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
			<th width='10' align="center">授权码ID</th>
			<th width='30' align="center">授权码名称</th>
			<th width='20' align="center">客户</th>
			<th width='10' align="center">项目ID</th>
			<th width="70" align="center">操作</th>
			<th width='10' align="center">管理员</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['title']?></td>
	<td align="center"><?php echo $value['aid']?></td>
	<td align="center"><?php echo $value['client_id']?></td>
	<td align="center">
	<a href="javascript:edit('<?php echo $value['id']?>')">编辑</a> | 
	<a href="javascript:dodelete('<?php echo $value['id']?>')">删除</a>
	<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
	</td> 
	<td align="center"><?php echo $admin_username?></td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $multipage;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function addnew() {
    location.href ='?m=go3c&c=auth&a=add_code&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(id) {
    location.href ='?m=go3c&c=auth&a=edit_code&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=auth&a=delete_code&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
