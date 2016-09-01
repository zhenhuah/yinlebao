<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=editTaskStatus&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return checkTask();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"> 终端类型</th>
		  <td><?php echo $taskInfo['taskBase'][0];?>
		  </td>
		</tr>
		<tr>
		  <th width="80"> 推荐位</th>
		  <td><?php echo $taskInfo['posidInfo']?>
		  </td>
		</tr>
		<tr>
		  <th width="80"> 视频类型</th>
		  <td>
		  <?php 
				if ($taskInfo['videoSource'] == '1')
				{
					echo '直播';
				}elseif ($taskInfo['videoSource'] == '2')
				{
					echo '点播';
				}elseif ($taskInfo['videoSource'] == '3')
				{
					echo 'EPG';
				}
		  ?>
		  </td>
		</tr>
		<tr>
		  <th width="80"> 海报类型</th>
		  <td>
		  <?php 
				if ($taskInfo['imgType'] == 'img')
				{
					echo '长方形台标';
				}elseif ($taskInfo['imgType'] == 'imgpath')
				{
					echo '正方形台标';
				}else{
					echo $taskInfo['imgType'];
				}
		  ?>
		  </td>
		</tr>
		<tr>
		  <th width="80"> 目前状态</th>
		  <td>
			<?php echo $taskStatus_data[$taskInfo['taskStatus']];?>
		  </td>
		</tr>
		<tr>
		  <th width="80">预发布日期</th>
		  <td>
			<input type="text" value="<?php if(!empty($taskInfo['taskDate'])){echo date('Y-m-d',$taskInfo['taskDate']);}?>" name="task_taskDate" id="task_taskDate" size="15"  disabled><span id="task_taskDate_error" style="display:none"><font color="red">请设置预发布时间</font>
		</td>
		</tr>

		<tr>
		  <th width="80"><font color="red">*</font> 任务状态</th>
		  <td>
			<select name="task_taskStatus" id="task_taskStatus">
				<option value="">请选择</option>
				<?php foreach($taskStatus_data as $key => $row){ ?>
				<option value="<?php echo $key;?>" <?php if($taskInfo['taskStatus'] == $key){ echo 'selected';}?>><?php echo $row?></option>
				<?php } ?>
			</select>
			<span id="task_taskStatus_error" style="display:none"><font color="red">请设置任务状态</font>
		</td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="editTask" />
	<input type="hidden" name="taskId" id="taskId" value="<?php echo $taskId;?>" />
	<input type="hidden" name="term_id" id="term_id" value="<?php echo $taskInfo['term_id'];?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 

</form>

</body>
</html>
