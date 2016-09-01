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
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="serverlist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="client" name="c">
	<input type="hidden" value="serverlist" name="a">
	<input type="hidden" value="1" name="search">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	名称：<input name="SERVER_NAME" type="text" value="<?php if(isset($SERVER_NAME)) echo $SERVER_NAME;?>" class="input-text" />&nbsp;
			项目代号：
	<select id="sel" name="spid">
		  <option value=''>全部</option>
	      <?php  foreach ($spid_list as $key => $spid) {?>
		  <option value='<?php echo $spid['spid']?>' <?php if($_GET['spid']==$spid['spid']) echo 'selected';?>><?php echo $spid['spid']?></option>
		  <?php }?>
	</select>
	板子型号：
	<select id="board" name="board_type">
		  <option value=''>全部</option>
	      <?php  foreach ($board_list as $key => $spid) {?>
		  <option value='<?php echo $spid['board_type']?>' <?php if($_GET['board_type']==$spid['board_type']) echo 'selected';?>><?php echo $spid['board_type']?></option>
		  <?php }?>
	</select>
	<?php echo L('each page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
	<input type="submit" value="搜索" class="button" name="search"> &nbsp;&nbsp;	
	<a class="button" text-decoration: none;" href="javascript:doconfirmurl('生成js文件')">生成js文件</a>&nbsp;&nbsp;	
<a class="button" style="float:right; text-decoration: none;" href="javascript:addserver()">添加</a>
</form> 
</div>
<div class="table-list">
<form action="?m=go3c&c=client&a=serverlist" method="post" name="myform2">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center">名称</th>
			<th width='50' align="center">服务地址1</th>
			<th width='50' align="center">服务地址2</th>
			<th width='50' align="center">项目代号</th>
			<th width="50" align="center">板子型号</th>
			<th width="50" align="center">时间</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['SERVER_NAME']?></td>
	<td align="center"><?php echo $value['server1'];?></td>
	<td align="center"><?php echo $value['server2'];?></td>
	<td align="center"><?php echo $value['spid'];?></td>
	<td align="center"><?php echo $value['board_type'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['createtime']);?></td>
	<td align="center">
		<a style="color:green" href="javascript:editserverto('<?php echo $value['SERVER_ID'];?>')">修改</a> | 
		<a href="javascript:confirmurl('?m=go3c&c=client&a=deleteserver&SERVER_ID=<?php echo  $value['SERVER_ID'];?>','你确定要执行该操作吗?')">删除</a>
	</td> 
	</tr>
	<?php }} ?>
</tbody>
    </table>
    </form>
	</div>
    <div id="pages"><?php echo $this->server_list->pages;?></div>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function doconfirmurl(title) {
	//获取选中的select的值
	var spid = document.getElementById("sel").value; 
	var board = document.getElementById("board").value;
	if(spid==''||board==''){
		alert('请选择项目代号和板子!');
	}else{
		confirmurl('?m=go3c&c=client&a=createserverjs&spid='+spid+'&board='+board+'&goback='+BASE64.encode(location.search),title);
	}
}
function addserver()
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=client&a=addserver&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'添加服务',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
}
function editserverto(SERVER_ID)
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=client&a=editserver&SERVER_ID='+SERVER_ID+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'修改服务',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
}
</script>
