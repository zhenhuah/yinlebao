<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="games" name="c">
<input type="hidden" value="showgame" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=games&a=showgame">全部数据</a>&nbsp;
			<select id="game_type" name="game_type">
	            <?php foreach ($type_name_array as $key => $ptvalue) {?>
				<option value='<?php echo $ptvalue?>' <?php if($_GET['game_type']==$key) echo 'selected';?>><?php echo $ptvalue?></option>
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
			<th width="68" align="center">操作日期</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['title']?></td>
	<td align="center"><?php echo $value['channel']?></td>
	<td align="center"><?php echo $value['update_time'];?></td>
	<td align="center">
		<a style="color:red" href="javascript:xuan('<?php echo $value['id']?>','<?php echo $taskId?>')">选择</a>
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
function xuan(id,taskId) {
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=task_games&a=addgamestask&taskId='+taskId+'&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			if (msg == '1')
			{
				var msg = '当前选择的数据己经添加了，请不要重复操作(或到任务详细列表中修改)!';
			}
			art.dialog({
				content: msg,
				title:'添加游戏应用',
				id:'viewOnlyDiv',
				lock:true,
				width:'680'
			});
		}
	});
}
</script>
