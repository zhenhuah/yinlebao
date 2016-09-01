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
<form name="myform" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="task_info" name="c">
	<input type="hidden" value="task" name="a">
	<input type="hidden" value="<?php echo $term_id;?>" name="term_id" id="term_id">
	<input type="hidden" value="<?php echo $spid;?>" name="spid" id="spid">
	<input type="hidden" value="1" name="dosearch">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	终端类型：<select id="term" name="term" onchange="term_filter_pos(this)">
		    	<option value='0'>全部</option>
	            <?php  foreach ($term_type_list as $key => $term) {?>
				<option value='<?php echo $term['id']?>' <?php if($term_id==$term['id']) echo 'selected';?>><?php echo $term['title']?></option>
				<?php }?>
			</select>
   推荐位：<select name="posid" id="posid">
				<option value="">全部</option>
				<?php if(!empty($posid_list)){foreach($posid_list as $row){?>
					
					<option value="<?php echo $row['posid'];?>" 
					<?php if($posid == $row['posid']){
						echo 'selected';
					}?>><?php echo $row['name']?></option>
					<?php }} ?>
			</select>&nbsp;
  任务状态：<select name="taskStatus" id="taskStatus">
				<option value=" " >请选择</option>
				<?php foreach($taskStatus_data as $key => $row){ ?>
				<option value="<?php echo $key;?>" <?php if(is_numeric($taskStatus) && ($key == $taskStatus)){ echo 'selected';}?>><?php echo $row;?></option>
				<?php } ?>
			</select>  &nbsp;
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
	<a class="add fb" href="###" onclick="return viewTask();" id="viewTermHome"><em>预览终端首页</em></a>
</form> 
</div>

<div class="table-list" style="margin-top:10px;">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="10" align="center">ID</th>
			<th width="20" align="center">终端</th>
			<th width="50" align="center">推荐位</th>	
			<th width='50' align="center">预发布时间</th>
			<th width='50' align="center">资讯类型</th>
			<th width='50' align="center">任务状态</th>			
			<th width="110" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($task_list)){foreach($task_list as $task){?>
		<tr>
		<td align="center"><?php echo $task['taskId']?></td>
		<td align="center"><?php echo term2name($task['term_id'])?></td>
		<td align="center"><?php echo $task['posidInfo']?></td>
		<td align="center"><?php echo date('Y-m-d',$task['taskDate']);?></td>		
		<td align="center">
		  <?php 
				if ($task['videoSource'] == '1')
				{
					echo '直播';
				}elseif ($task['videoSource'] == '2')
				{
					echo '点播';
				}elseif ($task['videoSource'] == '3')
				{
					echo 'EPG';
				}elseif ($task['videoSource'] == '100')
				{
					echo '资讯';
				}
		  ?>
		</td>
		<td align="center"><?php echo $taskStatus_data[$task['taskStatus']];?></td>	
		<td align="center" width="60">
		<a style="color:blue" href="javascript:runTask('edit','<?php echo $task['taskId'];?>')">修改</a> | 
		<a style="color:green" href="?m=go3c&c=infor&a=showinfor&taskId=<?php echo $task['taskId'];?>">添加资讯 </a> |
		<a style="color:green" href="?m=go3c&c=infor&a=viewinfor&mode=query&taskId=<?php echo $task['taskId'];?>">详细 </a> |
		<a href="javascript:confirmurl('?m=go3c&c=task_info&a=deleteTask&taskId=<?php echo $task['taskId'];?>','你确定要执行该操作吗？')">删除</a> |
		<?php if($task['taskStatus'] == '1'||$task['taskStatus'] == '3'){?>
		<a style="color:red" href="javascript:confirmurl('?m=go3c&c=infor&a=infor_task&taskId=<?php echo $task['taskId'];?>','你确定要执行该操作吗？')">发布上线</a>
		<?php  } elseif ($task['taskStatus'] == '4') { ?>
		<a style="color:red" href="javascript:confirmurl('?m=go3c&c=infor&a=infor_task_off&taskId=<?php echo $task['taskId'];?>','你确定要执行该操作吗？')">下线</a>
		<?php } ?>
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
<!--
//执行预浏览终端首页
function viewTask()
{
	$('#viewTermHome').attr('target','_self');	//先清空
	$('#viewTermHome').attr('href','###');	//先清空
	//终端
	var term_id = $.trim($('#term_id').val());
	if (term_id == '')
	{
		alert('请选择终端类型进来再操作');
		return false;
	}

	//日期
	var taskDate = $.trim($('#taskDate').val());
	if (taskDate == '')
	{
		alert('请选择预发布日期');
		return false;
	}

	//运营商ID
	var spid = $('#spid').val();
	if (spid == '')
	{
		alert('当前账号没有配置运营商');
		return false;
	}

	if ((taskDate != '') && (term_id != ''))
	{
		var setUrl = '?m=task&c=view&term_id='+term_id+'&taskDate='+taskDate+'&spid='+spid;
		$('#viewTermHome').attr('target','_blank');	//设置另一个页面打开
		$('#viewTermHome').attr('href',setUrl);	//设置
		return true;
		 //window.location.href = '?m=task&c=view&term_id='+term_id+'&taskDate='+taskDate;
	}else{
		return false;
	}
}
//添加推荐视频外链
function runOutTask(taskId)
{
	//终端
	var term_id = $.trim($('#term_id').val());
	if (term_id > 0)
	{
				$.ajax({
					type: "GET",
					url: 'index.php?m=go3c&c=task&a=addOutTask&term_id='+term_id+'&taskId='+taskId+'&pc_hash='+pc_hash,
					success: function(msg){
						art.dialog({
								content: msg,
								title:'添加推荐外链',
								id:'viewOnlyDiv',
								lock:true,
								width:'550'
								});
					}
				});
	}else{
		alert('请选择终端类型进来再操作');
	}
}
//添加、修改任务
function runTask(taskType,taskId)
{
	//终端
	//var term_id = $.trim($('#term_id').val());
	//if (term_id > 0)
	//{
		switch(taskType)
		{
			case 'add':	//添加任务
				$.ajax({
					type: "GET",
					url: 'index.php?m=go3c&c=task_info&a=addTask&pc_hash='+pc_hash,
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
				if (taskId > 0)
				{
					$.ajax({
							type: "GET",
							url: 'index.php?m=go3c&c=task_info&a=editTask&taskId='+taskId+'&pc_hash='+pc_hash,
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
				
				}
			break;
		}
	//}else{
	//	alert('请选择终端类型进来再操作');
	//}
}

//推荐位名称
function posidType(posid)
{
	if (posid > 0)
	{
		var indexNums = $("#task_posid").get(0).selectedIndex;	//当前索引值
		var posidInfo = $("select[name=task_posid] option[index="+indexNums+"]").text();
		$('#task_posidInfo').val(posidInfo);
		$('#task_posid_error').hide();
	}else{
		$('#task_posid_error').show();
	}
}

//添加、修改任务表单验证
function checkTask(runType)
{
	if (runType == 'addTask')	//添加
	{
		//终端类型 task_termid
		var task_termid = $.trim($('#task_termid').val());
		if (task_termid > 0)
		{
			$('#task_termid_error').hide();
		}else{
			$('#task_termid_error').show();
			return false;
		}
		//推荐位 task_posid
		var task_posid = $.trim($('#task_posid').val());
		if (task_posid > 0)
		{
			$('#task_posid_error').hide();
		}else{
			$('#task_posid_error').show();
			return false;
		}
	}else if(runType == 'editTask'){	//修改
		
	}

	//预发布日期 task_taskDate
	var task_taskDate = $.trim($('#task_taskDate').val());
	if (task_taskDate != '')
	{
		$('#task_taskDate_error').hide();
	}else{
		$('#task_taskDate_error').show();
		return false;
	}
	
	return true;
}

function term_filter_pos(obj) {
	document.myform.submit();
}

//-->
</script>