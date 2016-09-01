<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>

<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew(<?php echo $app_id;?>)" ><em><?php echo L('shop_add');?></em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="task_shop" name="c">
<input type="hidden" value="sd_info" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center"><?php echo L('shop_id');?></th>
			<th width='108' align="center"><?php echo L('shop_apk_link');?></th>
			<th width="68" align="center"><?php echo L('ktv_oper');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($download_info)){foreach($download_info as $pc){?>   
	<tr>
	<td align="center"><?php echo $pc['app_id']?></td>
	<td align="center"><a href="<?php echo $pc['install_file'];?>" target=_blank><?php echo $pc['install_file'];?></a></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $pc['install_file']?>', '<?php echo L('ktv_edit');?>')"><?php echo L('ktv_edit');?></a>
		<a href="javascript:dodelete('<?php echo $pc['install_file']?>', '<?php echo L('ktv_del');?>')"><?php echo L('ktv_del');?></a>
		<input type="hidden" value="<?php echo $pc['install_file']?>" name="ids[]" />
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
function edit(install_file) {
    location.href ='?m=go3c&c=task_shop&a=edit_appapk&install_file='+encodeURIComponent(install_file)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(install_file) {
    location.href ='?m=go3c&c=task_shop&a=delete_appapk&install_file='+encodeURIComponent(install_file)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(app_id){
	location.href ='?m=go3c&c=task_shop&a=shop_addapk&app_id='+encodeURIComponent(app_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
