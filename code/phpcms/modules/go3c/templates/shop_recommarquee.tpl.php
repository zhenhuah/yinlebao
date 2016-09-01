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
<link href="<?php echo CSS_PATH?>jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.datetimepicker.js"></script>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="task_shop" name="c">
<input type="hidden" value="recommarquee" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width='8%' align="center">序号</th>
			<th width='8%' align="center">内容</th>
			<th width='8%' align="center">状态</th>
			<th width='8%' align="center">开始时间</th>
			<th width='8%' align="center">结束时间</th>
			<th width='10%' align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['description']?></td>
	<td align="center"><?php echo $value['status']=='0'?'启用':'关闭'?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['starttime'])?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['endtime'])?></td>
	
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
function addnew() {
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=task_shop&a=shop_marqueeadd&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'添加',
					id:'viewOnlyDiv',
					lock:true,
					width:'550'
					});
		}
	});
}
function edit(id) {
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=task_shop&a=shop_marqueedit&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
					content: msg,
					title:'修改',
					id:'viewOnlyDiv',
					lock:true,
					width:'550'
					});
		}
	});
}
function dodelete(id) {
    location.href ='?m=go3c&c=task_shop&a=shop_marquedel&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
