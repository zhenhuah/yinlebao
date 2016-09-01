<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="boot" name="c">
<input type="hidden" value="remote" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=boot&a=remote">全部数据</a>&nbsp;
			遥控器：<input name="name" type="text" value="<?php if(isset($name)) echo $name;?>" class="input-text" />&nbsp;
			型号：<input name="type" type="text" value="<?php if(isset($type)) echo $type;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<a class="button" style="float:right; text-decoration: none;" href="javascript:addremote()">添加</a>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="20" align="center">序号</th>
			<th width='20' align="center">名称</th>
			<th width='20' align="center">型号</th>
			<th width='20' align="center">丝印图</th>
			<th width="20" align="center">键值数</th>
			<th width="20" align="center">所属项目</th>
			<th width="20" align="center">语言</th>
			<th width="20" align="center">操作时间</th>
			<th width="50" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $remote){?>   
	<tr>
	<td align="center"><?php echo $remote['id']?></td>
	<td align="center"><?php echo $remote['name']?></td>
	<td align="center"><?php echo $remote['type'];?></td>
	<td align="center"><a href="<?php echo $remote['siurl']?>" target="_blank"><img style="width:30px; border:solid 1px gray; padding:2px;" src="<?php echo $remote['siurl']?>" /></a></td>
	<td align="center"><?php echo $remote['remote'];?></td>
	<td align="center"><?php echo $remote['spid'];?></td>
	<td align="center"><?php echo $remote['lang'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$remote['createtime']);?></td>
	<td align="center">
		<a style="color:green" href="javascript:editremote('<?php echo $remote['id'];?>')">修改</a> | 
		<a href="javascript:confirmurl('?m=go3c&c=boot&a=delremote&Id=<?php echo  $remote['id'];?>','你确定要执行该操作吗?')">删除</a> |
		<a style="color:blue" href="javascript:confirmurl('?m=go3c&c=boot&a=createremote&Id=<?php echo  $remote['id'];?>','你确定要执行该操作吗?')">生成文件</a> |
		<?php if($remote['key_url'] != '') {?>
		<a  href="../go3ccms/xml/conf/remote_<?php echo $remote['type']?>_<?php echo $remote['spid']?>.conf" target="_blank">查看文件</a>
		<?php }?>
		<input type="hidden" value="<?php echo $remote['id']?>" name="ids[]" />
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
function addremote()
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=boot&a=addremote&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'添加遥控器',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
}
function editremote(id)
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=boot&a=editremote&id='+id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'修改遥控器',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
}
</script>
