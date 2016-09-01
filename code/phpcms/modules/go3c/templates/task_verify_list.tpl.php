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
	.table-list{margin-top:10px;}
</style>

<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="task" name="c">
	<input type="hidden" value="verifyTask" name="a">
	<input type="hidden" value="1" name="dosearch">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">


	终端类型：<select name="term_id" id="term_id" onChange="termType(this.value,'posid')">
					<option value="">请选择</option>
					<?php if(!empty($term_list)){foreach($term_list as $row){?>
					
					<option value="<?php echo $row['id'];?>" 
					<?php if($term_id == $row['id']){
						echo 'selected';
					}?>><?php echo $row['title']?></option>
					<?php }} ?>
				</select>&nbsp;	
			
   推荐位：<select name="posid" id="posid">
				<option value="">请选择</option>
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
	<input type="submit" value="搜索" class="button" name="search">
</form> 
</div>

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="35" align="center">ID</th>
			<th width="50" align="center">推荐位</th>	
			<th width='50' align="center">预发布时间</th>
			<th width='50' align="center">视频类型</th>
			<th width='50' align="center">任务状态</th>					
			<th width="80" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($task_list)){foreach($task_list as $task){?>
		<tr>
		<td align="center"><?php echo $task['taskId']?></td>
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
				}
		  ?>
		</td>
		<td align="center"><?php echo $taskStatus_data[$task['taskStatus']];?></td>	
		<td align="center">
		<a style="color:green" href="?m=go3c&c=task&a=verifyVideo&mode=query&taskId=<?php echo $task['taskId'];?>">详细 </a> 
		<?php if(($task['taskStatus'] > 0) && ($task['taskStatus'] < 4)){?>
		| <a style="color:green" href="javascript:confirmurl('?m=go3c&c=task&a=verifyTask&term_id=<?php echo $task['term_id'];?>&taskId=<?php echo $task['taskId'];?>&status=Y','你确定要执行该操作吗？')">通过 </a> | 
		<a style="color:red" href="javascript:confirmurl('?m=go3c&c=task&a=verifyTask&term_id=<?php echo $task['term_id'];?>&taskId=<?php echo $task['taskId'];?>&status=N','你确定要执行该操作吗？')">拒绝</a> 
		<?php }?>		
		<!--
		<a style="color:green" href="javascript:verifyTask('<?php echo $task['taskId'];?>')">审核任务 </a> 
		-->
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

	if ((taskDate != '') && (term_id != ''))
	{
		var setUrl = '?m=task&c=view&term_id='+term_id+'&taskDate='+taskDate;
		$('#viewTermHome').attr('target','_blank');	//设置另一个页面打开
		$('#viewTermHome').attr('href',setUrl);	//设置
		return true;
		 //window.location.href = '?m=task&c=view&term_id='+term_id+'&taskDate='+taskDate;
	}else{
		return false;
	}
}

//修改任务
function verifyTask(taskId)
{
	if (taskId > 0)
	{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=task&a=editTaskStatus&taskId='+taskId+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'审核任务',
					id:'viewOnlyDiv',
					lock:true,
					width:'450'
				});
			}
		});
	}

}


//AJAX 得到对应的推荐位列表
function termType(tid,nodeId,errorStatus='')
{
	if ((tid > 0) && (nodeId != ''))
	{
		$.ajax({
			type: "GET",
			url: '?m=go3c&c=task&a=term_posid&term_id=' + tid + '&pc_hash=' + pc_hash,
			success: function(msg){
				$('#'+nodeId).html(msg);
			}
		});

		if (errorStatus != '')
		{
			$('#'+errorStatus+'_error').hide();
		}
	}else{
		if (errorStatus != '')
		{
			$('#'+errorStatus+'_error').show();
		}
	}
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

//视频类型
function videoType(typeId)
{
	if (typeId > 0)
	{
		if ( typeId == '2') //点播
		{
			$('#LiveType').hide();
			$('#vodType').show();
		}else{
			$('#vodType').hide();
			$('#LiveType').show();
		}
		$('#task_videoSource_error').hide();
	}else{
		$('#LiveType').hide();
		$('#vodType').hide();
		$('#task_videoSource_error').show();
	}
}

//修改任务表单验证
function checkTask()
{
	//任务状态 taskStatus
	var taskStatus = $.trim($('#task_taskStatus').val());

	if (taskStatus != '')
	{
		$('#task_taskStatus_error').hide();
	}else{
		$('#task_taskStatus_error').show();
		return false;
	}
	
	return true;
}

//-->
</script>