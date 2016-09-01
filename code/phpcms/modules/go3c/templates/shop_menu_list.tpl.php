<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="menulist" name="a">
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
				<th width='30' align="center">序号id</th>
				<th width='30' align="center">菜单名</th>
				<th width='20' align="center">对应英文</th>
				<th width='20' align="center">菜单编号</th>
				<th width='20' align="center">菜单icon</th>
				<th width='20' align="center">状态</th>
				<th width='20' align="center">排序</th>
				<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['m_id']?></td>
	<td align="center"><?php echo $v['m_name_zh']?></td>
	<td align="center"><?php echo $v['m_name_en']?></td>
	<td align="center"><?php echo $v['m_key']?></td>
	<td align="center"><a href="<?php echo $v['m_icon']?>" target="_blank"><?php echo $v['m_icon']?></a></td>
	<td align="center"><?php echo $v['m_status']=='on' ? '显示' : '不显示'?></td>
	<td align="center"><?php echo $v['m_seq']?></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $v['m_id']?>')"><?php echo L('edit')?></a> | 
		<a href="javascript:sitmenu()">设置</a> 
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
	location.href ='?m=go3c&c=shop&a=shop_addmenu&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function sitmenu() {
    location.href ='?m=go3c&c=auth&a=menu_edit&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(m_id){
	location.href ='?m=go3c&c=shop&a=shop_editmenu&m_id='+m_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
