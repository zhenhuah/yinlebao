<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>

<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">序号id:</th>
		  <td>
			<?php echo $data['id'];?>		  
		</td>
		</tr>
		<tr>
		  <th width="80">版本:</th>
		  <td>	
		  <?php echo $data['version'];?>
		</td>
		</tr>
		<tr>
		  <th width="80">日期:</th>
		  <td>	
		  <?php echo  date("Y-m-d H:i:s",$v['createtime'])?>
		</td>
		</tr>
		<tr>
		  <th width="80">IP地址:</th>
		  <td>	
		  <?php echo $data['ip'];?>
		</td>
		</tr>
		<tr>
		  <th width="80">城市:</th>
		  <td>	
		  <?php echo $data['city'];?>
		</td>
		</tr>
		<tr>
		  <th width="80">身份证:</th>
		  <td>	
		  <?php echo $data['carid'];?>
		</td>
		</tr>
		<tr>
		  <th width="80">QQ:</th>
		  <td>	
		  <?php echo $data['qq'];?>
		</td>
		</tr>
		<tr>
		  <th width="80">电话:</th>
		  <td>	
		  <?php echo $data['phone'];?>
		</td>
		</tr>
		<tr>
		  <th width="80">反馈问题:</th>
		  <td>	
		   <textarea id="content" name="content" value="" cols="30" rows="3"><?php echo $data['content'];?></textarea>
		</td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<script type="text/javascript">

</script>
</body>
</html>
