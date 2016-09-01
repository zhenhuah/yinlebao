<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=infor&a=addinforTask&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return checkTask('<?php echo $taskType;?>');">
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
		  <th width="90"> <font color="red">*</font>海报类型 </th>
		  <td>
		 <select name="task_imgType_live">
		 <option value="">请选择</option>
		 <option value="402">402(竖图)</option>
		 <option value="403">403(横图)</option>
		 </select>
		  &nbsp;&nbsp;<span id="task_imgType_vod_error" style="display:none;"><font color="red">请设置海报类型</font></span>
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
	<input type="hidden" name="mode" id="mode" value="addTask" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
</body>
</html>
