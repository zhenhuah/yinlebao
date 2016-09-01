<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="recordcms" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=tvuser&a=init">全部数据</a>&nbsp;
		    账号：<input name="username" type="text" value="<?php if(isset($username)) echo $username;?>" class="input-text" />
		    开始日期：<input type="text" value="<?php echo $starttime;?>" name="starttime" id="starttime" size="10" class="date" readonly>	
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
			</script> &nbsp;
			结束日期：<input type="text" value="<?php echo $endtime;?>" name="endtime" id="endtime" size="10" class="date" readonly>	
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
			</script> &nbsp;
			每页：<input name="perpage" type="text" value="<?php echo $perpage;?>" class="input-text" size="3" />个
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="10" align="left">ID</th>
			<th style="width:40px; word-break: break-all; word-wrap:break-word;" align="left">vid</th>
			<th width='10' align="left">账号id</th>
			<th width='10' align="left">账号</th>
			<th style="width:50px; word-break: break-all; word-wrap:break-word;" align="left">操作说明</th>
			<th style="width:300px; word-break: break-all; word-wrap:break-word;" align="left">详细内容</th>
			<th width='10' align="left">操作时间</th>
			<th style="width:40px; word-break: break-all; word-wrap:break-word;" align="left">ip</th>
            </tr>
        </thead>
    <tbody>
	<?php 
	if(is_array($data)){
		foreach($data as $key=>$value){
	?>   
	<tr>
	<td align="left"><?php echo $value['logid'];?></td>
	<td style="width:40px; word-break: break-all; word-wrap:break-word;" align="left"><?php echo $value['vid'];?></td>
	<td align="left"><?php echo $value['userid'];?></td>
	<td align="left"><?php echo $value['username'];?></td>
	<td style="width:50px; word-break: break-all; word-wrap:break-word;" align="left"><?php echo cmslog_type($value['type'])?></td>
	<td style="width:300px; word-break: break-all; word-wrap:break-word;" align="left"><?php echo $value['contetdec'];?></td>
	<td align="left"><?php echo date('Y-m-d H:i:s', $value['createtime']);?></td>
	<td style="width:40px; word-break: break-all; word-wrap:break-word;" align="left"><?php echo $value['ip'];?></td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>