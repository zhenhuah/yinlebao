<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加项目</em></a>
</div>
<form name="myform" action="" method="GET">
<input type="hidden" value="admin" name="m">
<input type="hidden" value="role" name="c">
<input type="hidden" value="spidlist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			项目代号：<input name="spid" type="text" value="<?php if(isset($spid)) echo $spid;?>" class="input-text" />&nbsp;
			名称：<input name="custname" type="text" value="<?php if(isset($custname)) echo $custname;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />	
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
            <tbody>
            <tr>
			<th width="20" align="left">项目代号</th>
			<th width="30" align="left">项目客户名称</th>
			<th width="50" align="left">邮箱</th>
			<th width='20' align="left">电话</th>
			<th width='20' align="left">QQ号码</th>
			<th width='20' align="left">手机</th>
			<th width='50' align="left">客户网址</th>
			<th width='50' align="left">操作</th>
            </tr>
	<?php
	if(is_array($spiddata)) {
		foreach($spiddata as $key => $value1) {
	?>  
	<tr>
	<td align="left"><?=$value1['spid']?></td>
	<td align="left"><?=$value1['custname']?></td>
	<td align="left"><?=$value1['email']?></td>
	<td align="left"><?=$value1['tel']?></td>
	<td align="left"><?=$value1['qq']?></td>
	<td align="left"><?=$value1['phone']?></td>
	<td align="left"><?=$value1['web']?></td>
	<td align="left">
	<a style="color:blue" href="javascript:edit('<?php echo $value1['id']?>'),void(0);"><?php echo L('edit')?></a> |		
	<a style="color:green" title="删除" href="javascript:deletespid('<?php echo $value1['id']?>', '删除')">删除</a>
	</td>
	</tr>
	<?php }} ?>
	</div>
	</tbody>
    </table>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function addnew()
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=admin&c=role&a=add_spid&pc_hash='+pc_hash,
		success: function(msg){
		art.dialog({
				content: msg,
				title:'添加项目',
				id:'viewOnlyDiv',
				lock:true,
				width:'350'
			});
		}
	});
}
function edit(id)
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=admin&c=role&a=edit_spid&id='+encodeURIComponent(id)+'&pc_hash='+pc_hash,
		success: function(msg){
		art.dialog({
				content: msg,
				title:'修改项目',
				id:'viewOnlyDiv',
				lock:true,
				width:'550'
			});
		}
	});
}
function deletespid(id) {
    location.href ='?m=admin&c=role&a=deletespid&id='+encodeURIComponent(id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
