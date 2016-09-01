<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="client_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<div style="text-align:right;padding:0 10px 10px 0;">
	<input class="button" type="button" onclick="addnew()" value="添加服务器" />
</div>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="150" align="center">服务器名称</th>
			<th width='150' align="center">服务器地址</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $server){?>   
	<tr>
	<td align="center"><?php echo $server['title']?></td>
	<td align="center"><?php echo $server['ip'];?></td>
	<td align="center">
		<a style="color:blue" href="javascript:edit(<?php echo $server['id']?>, '编辑')">编辑</a>
		<input type="hidden" value="<?php echo $server['id']?>" name="ids[]" />
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
function edit(id, title) {
    location.href ='?m=content&c=content&a=edit&catid=67&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=content&c=content&a=add&catid=67&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

</script>
