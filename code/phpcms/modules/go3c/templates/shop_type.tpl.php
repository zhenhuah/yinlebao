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
<input type="hidden" value="shop" name="c">
<input type="hidden" value="type" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
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
			<th width='8%' align="center">类型ID</th>
			<th width='8%' align="center">类型名称</th>
			<th width='8%' align="center">类型代号</th>
			<th width='8%' align="center">上级类型ID</th>
			<th width='8%' align="center">层级</th>
			<th width='8%' align="center">下属应用数量</th>
			<th width='8%' align="center">描述</th>
			<th width='10%' align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['cat_id']?></td>
	<td align="center"><?php echo $value['cat_name']?></td>
	<td align="center"><?php echo $value['ctype']?></td>
	<td align="center"><?php echo $value['pid']?></td>
	<td align="center"><?php echo $value['level']?></td>
	<td align="center"><?php echo $value['count']?></td>
	<td align="center"><?php echo $value['description']?></td>
	
	<td align="center">
		<a href="javascript:edit('<?php echo $value['cat_id']?>')">编辑</a> | 
		<a href="javascript:dodelete('<?php echo $value['cat_id']?>')">删除</a>
		<?php if($value['cat_id'] == '27'){?>
		<a href="javascript:confirmurl('?m=go3c&c=shop&a=createjson&cat_id=<?php echo $value['cat_id'];?>','你确定要执行该操作吗?')"><em> |生成json</em></a>
		<?php } ?>
		<input type="hidden" value="<?php echo $value['cat_id']?>" name="ids[]" />
	</td> 
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
    location.href ='?m=go3c&c=shop&a=edit_shop_type&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=shop&a=delete_type&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=go3c&c=shop&a=add_shop_type&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
