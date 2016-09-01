<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="upvideolist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    账号：<input name="userid" type="text" value="<?php if(isset($userid)) echo $userid;?>" class="input-text" />
			每页：<input name="perpage" type="text" value="<?php echo $perpage;?>" class="input-text" size="3" />个
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
			<th width="10" align="left">ID</th>
			<th width='10' align="left">账号</th>
			<th width='10' align="left">标题</th>
			<th width='10' align="left">视频</th>
			<th width='10' align="left">图片</th>
			<th width='10' align="left">描述</th>
			<th style="width:50px; word-break: break-all; word-wrap:break-word;" align="left">上传时间</th>
			<th width='10' align="left">状态</th>
			<th width='30' align="left">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php 
	if(is_array($data)){
		foreach($data as $key=>$value){
	?>   
	<tr>
	<td align="left"><?php echo $value['id'];?></td>
	<td align="left"><?php echo $value['userid'];?></td>
	<td align="left"><?php echo $value['title'];?></td>
	<td align="left"><a href="<?php echo $value['path']?>" target="_blank"><?php echo $value['path'];?></a></td>
	<td align="left">
	<a href="<?php echo $value['imageurl']?>" target="_blank"><img style="width:30px; border:solid 1px gray; padding:2px;" src="<?php echo $value['imageurl']?>" /></a>
	</td>
	<td align="left"><?php echo $value['content'];?></td>
	<td align="left"><?php echo date('Y-m-d H:i:s', $value['updatetime']);?></td>
	<td align="left"><?php echo up_status($value['status']);?></td>
	<td align="left">
	<?php if($value['status'] == 1) {?>
	<a style="color:red" href="javascript:online_upvideo('<?php echo $value['id']?>')">审核</a>|
	<a href="javascript:Refuse_upvideo('<?php echo $value['id']?>')">拒绝</a>|
	<?php }?>
	<?php if($value['status'] != 2) {?>
	<a style="color:blue" href="javascript:edit_upvideo('<?php echo $value['id']?>')">编辑</a>|
	<a href="javascript:deleteupvideo('<?php echo $value['id']?>')">删除</a>|
	<?php }?>
	<?php if($value['status'] == 2) {?>
	<a style="color:blue" href="javascript:off_upvideo('<?php echo $value['id']?>')">下线</a>
	<?php }?>
	</td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->video_up->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function edit_upvideo(id){
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=tvuser&a=edit_upvideo&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'编辑',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
					});
		}
	});
}
function deleteupvideo(id) {
    location.href ='?m=go3c&c=tvuser&a=deleteupvideo&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function online_upvideo(id) {
    location.href ='?m=go3c&c=tvuser&a=online_upvideo&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function Refuse_upvideo(id) {
    location.href ='?m=go3c&c=tvuser&a=Refuse_upvideo&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function off_upvideo(id) {
    location.href ='?m=go3c&c=tvuser&a=off_upvideo&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>