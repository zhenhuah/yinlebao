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
<style type="text/css">
	body{margin:0 10px;}
</style>
<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="game" name="c">
	<input type="hidden" value="pcgame" name="a">
	<input type="hidden" value="1" name="dosearch">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
   推荐位：<select name="posid" id="posid">
				<option value="">全部</option>
				<?php if(!empty($posid_list)){foreach($posid_list as $row){?>
					
					<option value="<?php echo $row['posid'];?>" 
					<?php if($posid == $row['posid']){
						echo 'selected';
					}?>><?php echo $row['name']?></option>
					<?php }} ?>
			</select>&nbsp;
	预发布时间：<input type="text" value="<?php echo $taskDate;?>" name="taskDate" id="taskDate" size="10" class="date" >
	<script type="text/javascript">
		Calendar.setup({
		weekNumbers: true,
		inputField : "taskDate",
		trigger    : "taskDate",
		dateFormat: "%Y-%m-%d",
		showTime: false,
		minuteStep: 1,
		onSelect   : function() {this.hide();}
		});
	</script> &nbsp;
	<input type="submit" value="搜索" class="button" name="search"> &nbsp;&nbsp;	
	<a class="add fb" href="javascript:runTask('add')"><em>添加任务</em></a>&nbsp;
</form> 
</div>

<div class="table-list" style="margin-top:10px;">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="30" align="center">ID</th>
			<th width="50" align="center">推荐位</th>	
			<th width='50' align="center">预发布时间</th>
			<th width='50' align="center">任务状态</th>					
			<th width="110" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($task_list)){foreach($task_list as $task){?>
		<tr>
		<td align="center"><?php echo $task['taskId']?></td>
		<td align="center"><?php echo $task['posidInfo']?></td>
		<td align="center"><?php echo date('Y-m-d',$task['taskDate']);?></td>		
		<td align="center"><?php echo istask($task['taskStatus'])?></td>		
		<td align="left" width="60">
		<a style="color:blue" href="javascript:runTask('edit','<?php echo $task['taskId'];?>')">修改</a> | 
		<a style="color:green" href="?m=go3c&c=infor&a=showinfor&taskId=<?php echo $task['taskId'];?>">添加资讯 </a> |
		<a style="color:green" href="?m=go3c&c=infor&a=viewinfor&mode=query&taskId=<?php echo $task['taskId'];?>">详细 </a> |
		<a href="javascript:confirmurl('?m=go3c&c=task&a=deleteTask&taskId=<?php echo $task['taskId'];?>','你确定要执行该操作吗？')">删除</a> |
		<?php if($task['taskStatus'] == '1'||$task['taskStatus'] == '3'){?>
		<a style="color:red" href="javascript:confirmurl('?m=go3c&c=infor&a=infor_task&taskId=<?php echo $task['taskId'];?>','你确定要执行该操作吗？')">申请审核</a>
		<?php  } ?>
		</td>
		</tr>
	<?php }}else{echo "<tr><td align='center' colspan='7'>暂无数据</td></tr>";}?>
	</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
//添加、修改任务
function runTask(taskType,taskId)
{
		switch(taskType)
		{
			case 'add':	//添加任务
				$.ajax({
					type: "GET",
					url: 'index.php?m=go3c&c=infor&a=addTask&pc_hash='+pc_hash,
					success: function(msg){
						art.dialog({
								content: msg,
								title:'添加任务',
								id:'viewOnlyDiv',
								lock:true,
								width:'550'
								});
					}
				});
			break;

			case 'edit':	//修改任务
					$.ajax({
							type: "GET",
							url: 'index.php?m=go3c&c=infor&a=editTask&taskId='+taskId+'&pc_hash='+pc_hash,
							success: function(msg){
								art.dialog({
										content: msg,
										title:'修改任务',
										id:'viewOnlyDiv',
										lock:true,
										width:'550'
										});
							}
						});
			break;
		}
}
</script>