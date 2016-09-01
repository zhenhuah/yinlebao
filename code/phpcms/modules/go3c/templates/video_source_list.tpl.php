<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加来源</em></a>
	<a href="?m=go3c&c=video&a=video_source_pre_sync" style="float:right; color:red; font-weight:bold;">数据同步</a>
</div>
<form name="myform" action="?m=go3c&c=channel&a=channel" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="35" align="center">序号</th>
			<th width="200" align="center">名称</th>
			<th width='100' align="center">类型</th>
			<th width="200" align="center">ICON</th>
			<th width="68" align="center">编辑</th>
			<!--th width="68" align="center">删除</th-->
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $channel){?>   
	<tr>
	<td align="center"><?php echo $channel['id']?></td>
	<td align="center"><?php echo $channel['title']?></td>
	<td align="center"><?php echo $channel['type']?></td>
	<td align="center"><?php echo $channel['icon_url']?></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $channel['id']?>', '<?php echo safe_replace($channel['title'])?>');void(0);"><?php echo L('edit')?></a>
	<!--td align="center">
		<a href="javascript:delete('<?php echo $channel['id']?>', '<?php echo safe_replace($channel['title'])?>');void(0);">删除</a>
	</td-->
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
function addnew(){
	location.href ='?m=content&c=content&a=add&catid=72&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(id, title) {
/*	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'编辑--'+title, id:'edit', iframe:'?m=content&c=content&a=edit&catid=1&id='+id ,width:'800px',height:'300px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
*/
	location.href ='?m=content&c=content&a=edit&catid=72&id='+id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
}
</script>
