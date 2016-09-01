<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="opengame" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<a href="?m=go3c&c=tvuser&a=opengame"><?php echo L('all')?></a>&nbsp;
			用户id：<input name="userid" type="text" value="<?php if(isset($userid)) echo $userid;?>" class="input-text" size="20" />&nbsp;
			云游卡号：<input name="cardid" type="text" value="<?php if(isset($cardid)) echo $cardid;?>" class="input-text" size="20" />&nbsp;
			mac地址：<input name="mac_wire" type="text" value="<?php if(isset($mac_wire)) echo $mac_wire;?>" class="input-text" size="20" />&nbsp;
			<?php echo L('page_list')?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
			<th width="50" align="center">用户id</th>
			<th width='50' align="center">云游卡号</th>
			<th width="50" align="center">有线mac地址</th>
			<th width="50" align="center">时间</th>
			<th width="50" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['userid']?></td>
	<td align="center"><?php echo $value['cardid'];?></td>
	<td align="center"><?php echo $value['mac_wire'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['createtime']);?></td>
	<td align="center"><a href="javascript:dodelete('<?php echo $value['id']?>')">删除</a></td>
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
function dodelete(id) {
	if (confirm('确定要删除该版本吗?')) {
		location.href ='?m=go3c&c=tvuser&a=delete_open&id='+encodeURIComponent(id)+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}	
}
</script>
