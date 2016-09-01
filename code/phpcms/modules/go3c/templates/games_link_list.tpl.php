<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew('<?php echo $gameid?>')" ><em>添加链接</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="games" name="c">
<input type="hidden" value="games_link_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<input type="hidden" value="<?php echo $gameid;?>" name="gameid">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=games&a=games_link_list">全部数据</a>&nbsp;
			链接：<input name="url" type="text" value="<?php if(isset($url)) echo $url;?>" class="input-text" />&nbsp;
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
            <th width='10' align="center">链接ID</th>
			<th width='10' align="center">游戏ID</th>
			<th width='10' align="center">链接</th>
			<th width='10' align="center">版本</th>
			<th width='10' align="center">大小</th>
			<th width='30' align="center">发布时间</th>
			<th width='10' align="center">排序</th>
			<th width="100" align="center">操作</th>
			<th width='10' align="center">管理员</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['game_id']?></td>
	<td align="center"><?php echo $value['url']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php echo $value['size']?></td>
	<td align="center"><?php echo $value['release_date']?></td>
	<td align="center"><?php echo $value['sort']?></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $value['id']?>')">编辑</a> | 
		<a href="javascript:dodelete('<?php echo $value['id']?>')">删除</a>
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
    location.href ='?m=go3c&c=games&a=edit_game_url&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=games&a=delete_game_url&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(id){
	location.href ='?m=go3c&c=games&a=add_game_url&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
