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
<input type="hidden" value="auth" name="c">
<input type="hidden" value="systemlog" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
			用户：<input name="user" type="text" value="<?php if(isset($user)) echo $user;?>" class="input-text" size="20"/>&nbsp;
			时间：<input type="text" value="<?php echo $createtime;?>" name="createtime" id="createtime" size="10" class="date" >
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
				<th width='30' align="center">序号</th>
				<th width='20' align="center">类型</th>
				<th width='20' align="center">账号</th>
				<th width='20' align="center">ip</th>
				<th style="width:300px; word-break: break-all; word-wrap:break-word;" align="left">描述</th>
				<th width='20' align="center">时间</th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['id']?></td>
	<td align="center"><?php echo systype($v['status'])?></td>
	<td align="center"><?php echo $v['username']?></td>
	<td align="center"><?php echo $v['ip']?></td>
	<td style="width:300px; word-break: break-all; word-wrap:break-word;" align="left"><?php echo $v['description'];?></td>
	<td align="center"><?php echo $v['createtime']=='' ? '' : date("Y-m-d H:i:s",$v['createtime']) ?></td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
</script>
