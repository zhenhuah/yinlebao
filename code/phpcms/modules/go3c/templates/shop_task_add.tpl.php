<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task_shop&a=addshoptaskdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="taskId" value="<?php echo $taskId;?>" />
<input type="hidden" name="id" value="<?php echo $videoInfo['app_id'];?>" />

<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="table_form">
	<tbody>	
		<tr>
		  <th width="80"> 应用ID:</th>
		  <td><?php echo $videoInfo['app_id']?> </td>
		 </tr>

		<tr>
		  <th width="80"> 标题:</th>
		  <td><?php echo $videoInfo['app_name']?></td>
		</tr>
		<tr>
		  <th width="90"> <font color="red">*</font> 推荐后的标题:</th>
		  <td><input type="text" class="measure-input  input-text" value="<?php echo $videoInfo['app_name']?>"  name="videoTitle" id="videoTitle" style="width:280px;"><span id="videoTitle_error" style="display:none;"><font color="red">此标题不能为空</font></span></td>
		</tr>
		<tr>
		  <th width="80"> 详细介绍:</th>
		  <td><textarea rows="5" cols="41" name="long_desc" id="long_desc"><?php echo $videoInfo['app_desc'];?></textarea></td>
		</tr>
    </tbody>
</table>
</div>
	<div style="float:right" class="btn">
		<input type="submit" value="提交" id="dosubmit" name="dosubmit" class="button">&nbsp;&nbsp;
	</div>
</div>
</div>
</form>

</body>
</html>

<script type="text/javascript">
//添加视频具体任务
function runVideoTask()
{
	//名称
	var videoTitle = $.trim($('#videoTitle').val());
	if (videoTitle != '')
	{
		$('#videoTitle_error').hide();
	}else{
		$('#videoTitle_error').show();
		return false;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
	return true;
}
</script>
