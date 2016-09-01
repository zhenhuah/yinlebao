<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="shop" name="c">
<input type="hidden" value="showShop" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $taskId;?>" name="taskId">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=shop&a=shop_list">全部数据</a>&nbsp;
			名称：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
			类型:<select id="type" name="type">
	            <?php foreach ($type_name_array as $key => $ptvalue) {?>
				<option value='<?php echo $key?>' <?php if($type==$key) echo 'selected';?>><?php echo $ptvalue?></option>
				<?php }?>
			</select>
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
			<th width='5' align="center">ID</th>
			<th width='30' align="center">名称</th>
			<th width='20' align="center">更新时间</th>
			<th width='20' align="center">类型</th>
			<th width='10' align="center">版本</th>
			<th width='15' align="center">支持支持终端</th>
			<th width="40" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['app_id']?></td>
	<td align="center"><?php echo $value['app_name']?></td>
	<td align="center"><?php echo $value['update_time']?></td>
	<td align="center"><?php echo $value['channel']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php if($value['STB']==1) echo "STB|"?><?php if($value['SSB']==1) echo "SSB|"?><?php if($value['IOS']==1) echo "IOS|"?><?php if($value['PC']==1) echo "PC|"?><?php if($value['ANDROID']==1) echo "ANDROID"?></td>
	<td align="center">
		<a style="color:red" href="javascript:selectapp(<?php echo $value['app_id']?>, <?php echo $taskId?>)">选择</a>
	</td> 
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $multipage;?></div>
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function selectapp(id,taskId) {
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=task_shop&a=addshoptask&taskId='+taskId+'&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			if (msg == '1')
			{
				var msg = '当前选择的数据己经添加了，请不要重复操作(或到任务详细列表中修改)!';
			}
			art.dialog({
				content: msg,
				title:'添加应用',
				id:'viewOnlyDiv',
				lock:true,
				width:'680'
			});
		}
	});
}
</script>
</body>
</html>