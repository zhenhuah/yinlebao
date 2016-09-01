<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="feedbacktheme" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
			<div class="content-menu ib-a blue line-x" style="float:right">
				<a class="add fb" href="javascript:addnew()" ><em><?php echo L('add')?></em></a>
			</div>
		</td>
		</tr>
    </tbody>
</table>
<?php }?>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
				<th width='30' align="center">id</th>
				<th width='30' align="center">反馈主题</th>
				<th width='20' align="center">状态</th>
				<th width='20' align="center">排序</th>
				<th width='20' align="center">时间</th>
				<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['id']?></td>
	<td align="center"><?php echo $v['content']?></td>
	<td align="center"><?php echo $v['status']=='1' ? '启用' : '不启用'?></td>
	<td align="center"><?php echo $v['listorder']?></td>
	<td align="center"><?php echo  date("Y-m-d H:i:s",$v['createtime'])?></td>
	<td align="center">
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
			url: 'index.php?m=go3c&c=auth&a=shop_addfeedbacktheme&pc_hash='+pc_hash,
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
    location.href ='?m=go3c&c=auth&a=shop_delfeedbacktheme&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(id){
	$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=auth&a=shop_editfeedbacktheme&id='+id+'&pc_hash='+pc_hash,
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
</script>
