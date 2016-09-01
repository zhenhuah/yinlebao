<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task_info&a=<?php echo $taskType;?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return checkTask('<?php echo $taskType;?>');">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<?php if($taskType == 'addTask'){?>
		<tr>
			<th width="90"> <font color="red">*</font>终端类型 </th>
			<td>
				<select name="task_termid" id="task_termid">
					<option value="0">请选择</option>
					<?php if(!empty($term_type_list)){foreach($term_type_list as $row){?>
						
						<option value="<?php echo $row['id'];?>" 
						<?php if($term_id == $row['id']){
							echo 'selected';
						}?>><?php echo $row['title']?></option>
						<?php }} ?>
				</select>
				<input type="hidden" name="task_termidInfo" id="task_termidInfo" value="" />&nbsp;&nbsp;<span id="task_termid_error" style="display:none;"><font color="red">请选择终端类型</font></span>
			</td>
		</tr>
		<tr>
		  <th width="90"> <font color="red">*</font>推荐位类型 </th>
		  <td>
		  <select name="task_posid" id="task_posid" onChange="posidType(this.value);">
				<option value="">请选择</option>
				<?php if(!empty($posid_list)){foreach($posid_list as $row){?>
					
					<option value="<?php echo $row['posid'];?>" 
					<?php if($taskInfo['posid'] == $row['posid']){
						echo 'selected';
					}?>><?php echo $row['name']?></option>
					<?php }} ?>
			</select>
			<input type="hidden" name="task_posidInfo" id="task_posidInfo" value="<?php echo $taskInfo['posidInfo']?>" />&nbsp;&nbsp;<span id="task_posid_error" style="display:none;"><font color="red">请选择推荐位类型</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90"> <font color="red">*</font>资讯类型 </th>
		  <td>
		  <select name="task_videoSource" id="task_videoSource">
				<option value="">请选择</option>
				<option value="100">资讯</option>
			</select>&nbsp;&nbsp;<span id="task_videoSource_error" style="display:none;"><font color="red">请选择视频类型</font></span>
		  </td>
		</tr>
		<?php }?>

		<?php if($taskType == 'editTask'){?>	
		<tr>
		  <th width="80"> 终端</th>
		  <td><?php echo term2name($taskInfo['term_id'])?>
		  </td>
		</tr>
		<tr>
		  <th width="80"> 推荐位</th>
		  <td><?php echo $taskInfo['posidInfo']?>
		  </td>
		</tr>
		<tr>
		  <th width="80"> 资讯类型</th>
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
				}elseif ($taskInfo['videoSource'] == '100')
				{
					echo '资讯';
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
		<?php }?>
		<tr>
		  <th width="80"><font color="red">*</font> 预发布日期</th>
		  <td>
			<input type="text" value="<?php if(!empty($taskInfo['taskDate'])){echo date('Y-m-d',$taskInfo['taskDate']);}?>" name="task_taskDate" id="task_taskDate" size="15" class="date" readonly><span id="task_taskDate_error" style="display:none"><font color="red">请设置预发布时间</font>
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "task_taskDate",
				trigger    : "task_taskDate",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>  
		</td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	
	<?php if($taskType == 'addTask'){?>
	<input type="hidden" name="mode" id="mode" value="addTask" />
	<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
	<?php }?>

	<?php if($taskType == 'editTask'){?>
	<input type="hidden" name="mode" id="mode" value="editTask" />
	<input type="hidden" name="taskId" id="taskId" value="<?php echo $taskId;?>" />
	<input type="hidden" name="term_id" id="term_id" value="<?php echo $taskInfo['term_id'];?>" />
	<?php }?>
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 

</form>

</body>
</html>
