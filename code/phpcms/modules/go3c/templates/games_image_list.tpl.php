<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew('<?php echo $gameid?>')" ><em>添加图片</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="games" name="c">
<input type="hidden" value="games_image_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<input type="hidden" value="<?php echo $gameid;?>" name="gameid">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=games&a=games_image_list">全部数据</a> | 
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
            <th width='10' align="center">图片ID</th>
			<th width='10' align="center">游戏ID</th>
			<th width='10' align="center">排序</th>
			<th width='20' align="center">图片类型</th>
			<th width='150' align="center">链接</th>
			<th width='30' align="center">上传时间</th>
			<th width="100" align="center">操作</th>
			<th width='10' align="center">管理员</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['game_id']?></td>
	<td align="center"><?php echo $value['sort']?></td>
	<td align="center"><?php echo image_type($value['type']);?></td>
	<td align="center"><a href="<?php echo $value['url']?>" target="_Blank"><?php echo $value['url']?></a></td>
	<td align="center"><?php echo $value['create_date']?></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $value['id']?>')">编辑</a> | 
		<a href="javascript:dodelete('<?php echo $value['id']?>','<?php echo $value['game_id']?>')">删除</a>
	</td> 
	<td align="center"><?php echo $admin_username?></td>
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
function edit(id) {
    location.href ='?m=go3c&c=games&a=edit_game_image&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id,game_id) {
    location.href ='?m=go3c&c=games&a=delete_game_image&id='+id+'&game_id='+game_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(id){
	location.href ='?m=go3c&c=task_games&a=add_game_image&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
