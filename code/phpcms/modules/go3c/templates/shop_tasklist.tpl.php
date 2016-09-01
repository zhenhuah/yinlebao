<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="shop" name="c">
<input type="hidden" value="tasklist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
    </tbody>
</table>
<?php }?>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
				<th width='30' align="center">名称</th>
				<th width='30' align="center">类型</th>
				<th width='20' align="center">路径</th>
				<th width='20' align="center">开始时间</th>
				<th width='20' align="center">结束时间</th>
				<th width='20' align="center">添加时间</th>
				<th width='20' align="center">是否启用</th>
				<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['title']?></td>
	<td align="center"><?php echo $v['type']?></td>
	<td align="center"><?php echo $v['url']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $v['starttime'])?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $v['endtime'])?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $v['createtime'])?></td>
	<td align="center"><?php echo $v['status']=='on' ? '启用' : '不启用'?></td>
	<td align="center">
		<a href="javascript:taskimport('<?php echo $v['id']?>')">导入 | 
		<a href="javascript:edit('<?php echo $v['id']?>')"><?php echo L('edit')?></a> | 
		<a href="javascript:delip('<?php echo $v['id']?>')">删除
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
function addnew(){
	$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=shop&a=shop_addtask&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'添加',
					id:'viewOnlyDiv',
					lock:true,
					width:'400'
				});
			}
		});
}
function delip(id) {
    location.href ='?m=go3c&c=shop&a=shop_deltask&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(id){
	$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=shop&a=shop_edittask&id='+id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'编辑',
					id:'viewOnlyDiv',
					lock:true,
					width:'400'
				});
			}
		});
}
function taskimport(id) {
    location.href ='?m=go3c&c=shop&a=taskimport&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
