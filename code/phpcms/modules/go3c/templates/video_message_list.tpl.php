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
<input type="hidden" value="message" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		           账号：<input name="userid" type="text" value="<?php if(isset($userid)) echo $userid;?>" class="input-text" />
		           标题：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />
			类型：<select name="type">
				<option value='' <?php if($_GET['type']==0) echo 'selected';?>>全部</option>
				<option value='1' <?php if($_GET['type']==1) echo 'selected';?>>系统消息</option>
				<option value='2' <?php if($_GET['type']==2) echo 'selected';?>>用户消息</option>
			</select>&nbsp;
			每页：<input name="perpage" type="text" value="<?php echo $perpage;?>" class="input-text" size="3" />个
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />&nbsp;&nbsp;
			<div class="content-menu ib-a blue line-x" style="float:right">
			<a class="add fb" href="javascript:addmessage()"><em>添加</em></a>&nbsp;
			</div>
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
			<th width='10' align="left">视频id</th>
			<th width='10' align="left">描述</th>
			<th width='10' align="left">类型</th>
			<th width='10' align="left">状态</th>
			<th width='10' align="left">时间</th>
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
	<td align="left"><?php echo $value['vid'];?></td>
	<td align="left"><?php echo $value['content'];?></td>
	<td align="left">
	<?php
		if ($value['type'] == '1')
		{
			echo '系统消息';
		}elseif ($value['type'] == '2')
		{
			echo '用户消息';
		}
	?>
	</td>
	<td align="left">
	<?php
		if ($value['status'] == '1')
		{
			echo '未阅';
		}elseif ($value['status'] == '2')
		{
			echo '已阅';
		}elseif ($value['status'] == '3')
		{
			echo '删除';
		}
	?>
	</td>
	<td align="left"><?php echo date('Y-m-d H:i:s', $value['m_time']);?></td>
	<td align="left">
	<a href="javascript:deletemes('<?php echo $value['id']?>')">删除</a>
	</td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->video_message->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function addmessage(){
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=tvuser&a=addmessage&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'添加',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
					});
		}
	});
}
function deletemes(id) {
    location.href ='?m=go3c&c=tvuser&a=deletemes&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>