<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=go3c&c=channel&a=channel" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="35" align="center">序号</th>
			<th width="60" align="center">名称</th>
			<th width="160" align="center">终端</th>
			<th width="160" align="center">推荐位名称</th>
			<th width="36" align="center"></th>
			<th width="150" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){$i = 1+($page-1)*$pagesize;foreach($data as $channel){?>   
	<tr>
	<td align="center"><?php echo $i++?></td>
	<td align="center"><?php echo $channel['name']?></td>
	<td align="center"><?php echo $channel['termtype']?></td>
	<td align="center"><?php echo $channel['postype']?></td>
	<td align="center"><a  style="color:blue" href="?m=go3c&c=position&a=position_list&posid=<?php echo $channel['posid']?>&view=0">详细</a></td>
	<td align="center">
		<a style="color:green" href="javascript:dopass(<?php echo $channel['posid']?>, '通过申请')">通过</a>
		&nbsp;&nbsp;
		<a style="color:red" href="javascript:dorefuse(<?php echo $channel['posid']?>, '驳回申请')">拒绝</a>
		<input type="hidden" value="<?php echo $channel['id']?>" name="ids[]" />
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
function dopass(id, title) {
    location.href ='?m=go3c&c=verify&a=recomm_pass&posid='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dorefuse(id, title) {
    location.href ='?m=go3c&c=verify&a=recomm_refuse&posid='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
