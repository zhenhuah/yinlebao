<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="useridbild" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
		身份证：<input name="cardid" type="text" value="<?php if(isset($cardid)) echo $cardid;?>" class="input-text" size="18"/>&nbsp;
		MAC：<input name="mac_wire" type="text" value="<?php if(isset($mac_wire)) echo $mac_wire;?>" class="input-text" size="15"/>&nbsp;
		sn号：<input name="boxsn" type="text" value="<?php if(isset($boxsn)) echo $boxsn;?>" class="input-text" size="15"/>&nbsp;
		版本：<input name="version" type="text" value="<?php if(isset($version)) echo $version;?>" class="input-text" size="15"/>&nbsp;
		状态：<select id="bild" name="bild">
				  <option value=''>全部</option>
				  <option value='on' <?php if($_GET['bild']=='on') echo 'selected';?>>绑定</option>
				  <option value='off' <?php if($_GET['bild']=='off') echo 'selected';?>>解绑</option>
			</select>
			绑定时间：<input type="text" value="<?php echo $createtime;?>" name="createtime" id="createtime" size="10" class="date" >
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
			</script> --
			<input type="text" value="<?php echo $createtime1;?>" name="createtime1" id="createtime1" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "createtime1",
				trigger    : "createtime1",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script> &nbsp;
			解绑时间：<input type="text" value="<?php echo $endtime;?>" name="endtime" id="endtime" size="10" class="date" >
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
			</script> --
			<input type="text" value="<?php echo $endtime2;?>" name="endtime2" id="endtime2" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "endtime2",
				trigger    : "endtime2",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script> &nbsp;
			<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<?php }?>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
				<th width='15' align="center">用户id</th>
				<th width='15' align="center">身份证</th>
				<th width='30' align="center">地址</th>
				<th width='20' align="center">MAC地址</th>
				<th width='20' align="center">sn号</th>
				<th width='20' align="center">版本</th>
				<th width='20' align="center">时间</th>
				<th width='20' align="center">状态</th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['userid']?></td>
	<td align="center"><?php echo $v['cardid']?></td>
	<td align="center"><?php echo $v['installaddress']?></td>
	<td align="center"><?php echo $v['mac_wire']?></td>
	<td align="center"><?php echo $v['boxsn']?></td>
	<td align="center"><?php echo $v['version']?></td>
	<td align="center"><?php echo date("Y-m-d H:i:s",$v['createtime'])?></td>
	<td align="center"><?php echo $v['bild']=='on' ? '绑定' : '解绑'?></td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $multipage;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
</script>
