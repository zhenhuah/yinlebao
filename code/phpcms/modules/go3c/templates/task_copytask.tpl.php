<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=copytaskdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" >

<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="table_form">
	<tbody>	
		<tr>
		  <th width="80">从</th>
		  <td><select name="taskIdc" id="taskIdc">
		  <?php foreach($TaskInfo as $row){ ?>
			<option value="<?php echo $row['taskId'];?>"><?php echo $row['posidInfo'];?></option>
			<?php } ?>
			</select>
		  </td>
		   <th width="80">复制到</th>
		   <td><select name="taskIdd" id="taskIdd">
		   <?php foreach($TaskInfo as $row){ ?>
			<option value="<?php echo $row['taskId'];?>"><?php echo $row['posidInfo'];?></option>
			<?php } ?>
			</select>
		   </td>
		 </tr>
		 <tr>
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

</script>
