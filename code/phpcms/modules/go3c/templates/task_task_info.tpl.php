<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=<?php echo $taskType;?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return checkTask('<?php echo $taskType;?>');">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<?php if($taskType == 'addTask'){?>
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
		  <th width="90"> <font color="red">*</font>视频类型 </th>
		  <td>
		  <select name="task_videoSource" id="task_videoSource" onChange="videoType(this.value);">
				<option value="">请选择</option>
				<option value="1">直播</option>
				<option value="2">点播</option>
				<option value="3">EPG</option>
			</select>&nbsp;&nbsp;<span id="task_videoSource_error" style="display:none;"><font color="red">请选择视频类型</font></span>
		  </td>
		</tr>
		<tr id="vodType" style="display:none;">
		  <th width="90"> <font color="red">*</font>海报类型 </th>
		  <td>
		  <input type="text" name="task_imgType_vod" id="task_imgType_vod" value="<?php echo $defautlImg;?>">
		  &nbsp;&nbsp;<span id="task_imgType_vod_error" style="display:none;"><font color="red">请设置海报类型</font></span>
		  </td>
		</tr>
		<tr id="LiveType" style="display:none;">
		  <th width="90"> <font color="red">*</font>海报类型 </th>
		  <td>
		  <select name="task_imgType_live" id="task_imgType_live" >
				<option value="">请选择</option>
				<option value="img">长方形台标</option>
				<option value="imgpath">正方形台标</option>
			</select>&nbsp;&nbsp;<span id="task_imgType_live_error" style="display:none;"><font color="red">请设置海报类型</font></span>
			</td>
		</tr>
		<?php }?>

		<?php if($taskType == 'editTask'){?>	
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
