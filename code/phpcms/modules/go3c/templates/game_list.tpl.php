<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加游戏</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="game" name="c">
<input type="hidden" value="game_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<select id="game_type" name="game_type">
	            <?php foreach ($game_type_array as $key => $ptvalue) {?>
				<option value='<?php echo $key?>' <?php if($_GET['game_type']==$key) echo 'selected';?>><?php echo $ptvalue?></option>
				<?php }?>
			</select>
			名称：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
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
			<th width="50" align="center">ID</th>
			<th width='108' align="center">游戏名称</th>
			<th width='108' align="center">游戏类型</th>
			<th width='50' align="center">游戏题材</th>
			<th width='50' align="center">游戏logo</th>
			<th width="68" align="center">操作日期</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['title']?></td>
	<td align="center"><?php echo $game_type_array[$value['game_type']]?></td>
	<td align="center"><?php echo $value['game_theme']?></td>
	<td align="center"><?php echo $value['gm_logo']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['inputtime']);?></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $value['id']?>')">编辑</a> | 
		<a href="javascript:dodelete('<?php echo $value['id']?>')">删除</a>
		<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
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
function edit(id) {
    location.href ='?m=content&c=content&a=edit&catid=71&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=content&c=content&a=delete&dosubmit=1&ajax_preview=1&catid=71&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=content&c=content&a=add&catid=71&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
