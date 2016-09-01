<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform" action="" method="GET">
<input type="hidden" value="admin" name="m">
<input type="hidden" value="clear_memcache" name="c">
<input type="hidden" value="MatchEpg" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			名称：<input name="name" type="text" value="<?php if(isset($name)) echo $name;?>" class="input-text" />&nbsp;			
			栏目分类：<select id="column_id" name="column_id">
					<option value="">请选择</option>
					<?php {foreach($column_list as $key=>$column){?>
					<option value="<?php echo $column['id'];?>" <?php if($column_id==$column['id']) echo 'selected';?>><?php echo $column['title'];?></option>
					<?php }} ?>
				</select>
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<div class="content-menu ib-a blue line-x" style="float:right">
	<a class="add fb" href="javascript:add()" ><em>添加</em></a>
</div>	
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
            <tbody>
            <tr>
			<th width="20" align="center">ID</th>
			<th width="60" align="left">名称</th>
			<th width="60" align="left">所属栏目</th>
			<th width="30" align="left">顺序</th>
			<th width='60' align="left">时间</th>
	<!--  		<th width='30' align="left">是否启用</th> -->
			<th width='30' align="left">编辑</th>
			<th width='30' align="left">删除</th>
            </tr>
	<?php
	if(is_array($data)) {
		foreach($data as $key => $value1) {
	?>  
	<tr>
	<td align="center"><?=$value1['id']?></td>
	<td align="left"><?=$value1['name']?></td>
	<td align="left"><?php {foreach($column_list as $key=>$column){?>
	<?php if($value1['column_id']==$column['id']) echo $column['title'];?>
	<?php }} ?></td>
	<td align="left"><?=$value1['seq_number']?></td>
	<td align="left"><?php echo date("Y-m-d h:i:s",$value1['inputtime'])?></td>
<!--	<?php    if($value1['isextend'] == 0){?>
		<td align="left"><?php echo '启用'?></td>
	<?php }else{?>
		<td align="left"><?php echo '没启用'?></td>
	<?php }?> -->
	<td align="left"><a href="javascript:edit('<?=$value1['id']?>')">编辑</a></td>
	<td align="left"><a href="javascript:deleteme('<?=$value1['id']?>')">删除</a></td>
	</tr>
	<?php }} ?>
	</div>
	</tbody>
    </table>
    <div id="pages"><?php echo $this->channelepg_match->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function add(){
	$.ajax({
		type: "GET",
		url: 'index.php?m=admin&c=clear_memcache&a=addmatchepg&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'添加字典',
					id:'viewOnlyDiv',
					lock:true,
					width:'400'
					});
		}
	});
}
function edit(id){
	$.ajax({
		type: "GET",
		url: 'index.php?m=admin&c=clear_memcache&a=editmatchepg&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'修改字典',
					id:'viewOnlyDiv',
					lock:true,
					width:'400'
					});
		}
	});
}
function deleteme(id) {
    location.href ='?m=admin&c=clear_memcache&a=delete_matchepg&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
