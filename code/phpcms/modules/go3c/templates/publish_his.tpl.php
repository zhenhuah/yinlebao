<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>
<style type="text/css">
	body{margin:0 10px;}
	.table-list{margin-top:10px;}
</style>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function publishlogdetail(id) {
    location.href ='?m=go3c&c=publish&a=publishlogdetail&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
<div class="explain-col">
	<strong>发布历史</strong>
</div>

<div class="table-list">
<table cellspacing="0" cellpadding="0" border="0" class="table-list" style="width:100%;">
<thead>
	<tr>
		<th style="width:210px;">ID</th>
		<th class="cn" style="width:150px;">时间</th>
		<th class="ln">操作</th>
		<th class="ln">数量</th>
		<th style="width:160px;">操作人</th>
		<th style="width:60px;">详细</th>
	</tr>
</thead><tbody>
	<?php foreach($his['list'] as $v){ ?>
	<tr>
		<td class="cn"><?php print $v['id']; ?></td>
		<td class="ln"><?php print date('Y-m-d H:i:s',$v['created']); ?></td>
		<td class="ln"><?php print $v['do']; ?></td>
		<td class="ln"><?php if($v['docount']){echo intval($v['docount']);}else{echo intval($v['count']);} ?></td>
		<td class="cn"><?php echo $v['username']; //echo get_uname_by_sid($v['sid']); ?></td>
		<td class="cn"><a href="javascript:publishlogdetail('<?php echo $v['id']?>');void(0);">查看</a></td>
	</tr>

	<?php }?>
	
	<?php if(!empty($online['pager'])){ ?>
	<tr>
		<td colspan="10">
		<?php print $online['pager']; ?>
		</td>
	</tr>
	<?php }?>
</tbody>
</table>
</div>