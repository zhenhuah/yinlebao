<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="http://192.168.150.91/go3cadmin/statics/css/jquery.datetimepicker.css"/>
<form name="myform" id="myform" action="?m=go3c&c=task_shop&a=shop_marqueeadddo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">内容:</th>
		  <td><textarea rows="5" cols="41" name="description" id="description"></textarea></td>
		 </tr>
		 <tr>
		  <th width="80">状态:</th>
		  <td>
			<select id="status" name="status" >
		    	<option value='0'>启用</option>
				<option value='1'>关闭</option>
			</select>
		  </td>
		 </tr>
		<tr>
		  <th width="80"><font color="red">*</font> 开始日期</th>
		  <td>
			<input type="text" value="<?php if(!empty($starttime)){echo date('Y-m-d',$starttime);}?>" name="starttime" id="starttime" size="15" class="date" readonly>
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "starttime",
				trigger    : "starttime",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>  
		</td>
		</tr>
		<tr>
		  <th width="80"><font color="red">*</font> 结束日期</th>
		  <td>
			<input type="text" value="<?php if(!empty($endtime)){echo date('Y-m-d',$endtime);}?>" name="endtime" id="endtime" size="15" class="date" readonly>
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "endtime",
				trigger    : "endtime",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>  
		</td>
		</tr>
		<tr>
		  <th width="80">内容:</th>
		  <td><input type="text" value="2014/03/15 05:06" id="datetimepicker"/><br><br></td>
		 </tr>
		
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="shop_marqueeadddo" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
<script language="javascript" type="text/javascript" src="http://192.168.150.91/go3cadmin/statics/js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="http://192.168.150.91/go3cadmin/statics/js/jquery.datetimepicker.js"></script>
<script>
$('#datetimepicker').datetimepicker();
</script>
</body>
</html>
