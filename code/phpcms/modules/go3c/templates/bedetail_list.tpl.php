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
<input type="hidden" value="bedetail" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			进入时间:
			<input type="text" value="<?php echo $createtime;?>" name="createtime" id="createtime" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "createtime",
				trigger    : "createtime",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script>--
			<input type="text" value="<?php echo $endtime;?>" name="endtime" id="endtime" size="10" class="date" >
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
			用户总数：<?php echo $totalnum;?>
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
			<th width="200" align="left">序号</th>
			<th width='200' align="left">终端mac</th>
			<th width='200' align="center">安装地址</th>
			<th width='200' align="center">电话</th>
			<th width='200' align="center">模块</th>
			<th width='200' align="center">进入时间</th>
			<th width='200' align="center">退出时间</th>
			<th width='200' align="center">停留时间</th>
          </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>  
	<tr>
	<td align="left"><?php echo $value['id']?></td>
	<td align="left"><?php echo $value['mac_wire'];?></td>
	<td align="center"><?php echo $value['installaddress'];?></td>
	<td align="center"><?php echo $value['phonenumber'];?></td>	
	<td align="center"><?php echo $value['modulename'];?></td>
	<td align="center"><?php if(!empty($value['endtime'])) echo date('Y-m-d H:i:s',$value['endtime']);?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['starttime']);?></td>
	<td align="center"><?php echo $value['waittime'];?>(s)</td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->go3capi_log_module->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
</script>
