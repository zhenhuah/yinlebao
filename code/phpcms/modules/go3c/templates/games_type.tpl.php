<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加类型</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="games" name="c">
<input type="hidden" value="type" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=games&a=type">全部数据</a>&nbsp;
			名称：<input name="type_name" type="text" value="<?php if(isset($type_name)) echo $type_name;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
			<th width='8%' align="center">ID</th>
			<th width='8%' align="center">商店类型</th>
			<th width='8%' align="center">层级</th>
			<th width='8%' align="center">上级类型ID</th>
			<th width='8%' align="center">权重</th>
			<th width='8%' align="center">下属应用数量</th>
			<th width='8%' align="center">状态</th>
			<th width='8%' align="center">描述</th>
			<th width='8%' align="center">URL</th>
			<th width='8%' align="center">备注</th>
			<th width='10%' align="center">操作</th>
			<th width='10%' align="center">管理员</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['cat_id']?></td>
	<td align="center"><?php echo $value['cat_name']?></td>
	<td align="center"><?php echo $value['level']?></td>
	<td align="center"><?php echo $value['pid']?></td>
	<td align="center"><?php echo $value['sort']?></td>
	<td align="center"><?php echo $value['count']?></td>
	<td align="center"><?php echo $value['status']?></td>
	<td align="center"><?php echo $value['description']?></td>
	<td align="center"><?php echo $value['url']?></td>
	<td align="center"><?php echo $value['remark']?></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $value['cat_id']?>')">编辑</a> | 
		<a href="javascript:dodelete('<?php echo $value['cat_id']?>')">删除</a>
		<input type="hidden" value="<?php echo $value['cat_id']?>" name="ids[]" />
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
function edit(id) {
    location.href ='?m=go3c&c=games&a=edit_shop_type&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=games&a=delete_type&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=go3c&c=games&a=add_shop_type&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
