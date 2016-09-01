<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="shop" name="c">
<input type="hidden" value="link_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=shop&a=link_list">全部数据</a>&nbsp;
			名称：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;		
			项目代号:<select id="spid" name="spid">
			<option value=''>全部</option>
			<?php foreach ($sp_list as $key => $value) {?>
			<option value='<?php echo $value['spid']?>'<?php if($_GET['spid']==$value['spid']) echo 'selected';?>><?php echo $value['spid']?></option>
			<?php }?>
			</select>
			状态:<select id="statue" name="statue">
			<option value='' >全部</option>
			<option value='1' <?php if($_GET['statue']==1) echo 'selected';?>>未上线</option>
			<option value='2' <?php if($_GET['statue']==2) echo 'selected';?>>申请审核</option>
			<option value='3' <?php if($_GET['statue']==3) echo 'selected';?>>审核通过</option>
			<option value='4' <?php if($_GET['statue']==4) echo 'selected';?>>上线</option>
			</select>
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" /> 
			 <?php if($_SESSION['roleid']=='1') {?>
			|
			<a class="add fb" href="javascript:confirmurl('?m=go3c&c=task_shop&a=createlinkjson','你确定要执行该操作吗?')"><em>生成推荐json文件</em></a>
			<?php }?>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width='20' align="center">名称</th>
			<th width='20' align="center">类型</th>
			<th width='20' align="center">图片</th>
			<th width='15' align="center">跳转地址</th>
			<th width='30' align="center">状态</th>
			<th width='30' align="center">项目</th>
			<th width='30' align="center">时间</th>
			<th width='30' align="center">排序</th>
			<th width="50" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($infor_list)){foreach($infor_list as $value){?>   
	<tr>
	<td align="center"><?php echo $value['title']?></td>
	<td align="center">
	<?php if($value['type']=='url') echo '外链';?></td>
	<td align="center"><a href="<?php echo $value['file_path']?>" target="_blank"><img style="width:30px; border:solid 1px gray; padding:2px;" src="<?php echo $value['file_path']?>" /></a></td>
	<td align="center"><a href="<?php echo $value['url']?>" target="_blank"><?php echo $value['url']?></a></td>
	<td align="center"><?php echo link_status($value['statue']);?></td>
	<td align="center"><?php echo $value['spid']?></td>
	<td align="center"><?php echo date("Y-m-d h:i:s",$value['createtime'])?></td>
	<td align="center"><?php echo $value['seq_number']?></td>
	<td align="center">
	<?php if($value['statue'] == 2){?>
		<a style="color:green" href="javascript:pass('<?php echo $value['id']?>')">通过审核</a> |
		<a href="javascript:refuse('<?php echo $value['id']?>')">拒绝审核</a>
	<?php } ?>
	<?php if($value['statue'] == 3){?>
		<a style="color:red" href="javascript:release('<?php echo $value['id']?>')">发布上线</a>
	<?php } ?>
	<?php if($value['statue'] == 4){?>
		<a style="color:blue" href="javascript:offline('<?php echo $value['id']?>')">下线</a>
	<?php } ?>
	<?php if($value['statue'] == 1){?>
		<a style="color:blue" href="javascript:online('<?php echo $value['id']?>')">申请上线</a> |
		<a href="javascript:edit('<?php echo $value['id']?>')">编辑</a> | 
		<a href="javascript:dodelete('<?php echo $value['id']?>')">删除</a>
	<?php } ?>
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
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=task_shop&a=edit_applink&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'修改外链应用',
					id:'viewOnlyDiv',
					lock:true,
					width:'550'
					});
		}
	});
}
function dodelete(id) {
    location.href ='?m=go3c&c=task_shop&a=delete_applink&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew()
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=task_shop&a=add_applink&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'添加外链应用',
					id:'viewOnlyDiv',
					lock:true,
					width:'550'
					});
		}
	});
}
function online(id) {
    location.href ='?m=go3c&c=shop&a=online_applink&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function pass(id) {
    location.href ='?m=go3c&c=shop&a=pass_applink&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function refuse(id) {
    location.href ='?m=go3c&c=shop&a=refuse_applink&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function release(id) {
    location.href ='?m=go3c&c=shop&a=release_applink&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function offline(id) {
    location.href ='?m=go3c&c=shop&a=offline_applink&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
