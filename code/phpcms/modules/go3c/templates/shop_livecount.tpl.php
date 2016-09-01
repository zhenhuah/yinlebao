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
<input type="hidden" value="livechcount" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
			统计时间：<input type="text" value="<?php echo $starttime;?>" name="starttime" id="starttime" size="10" class="date" >
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
			</script> --
			<input type="text" value="<?php echo $starttime1;?>" name="starttime1" id="starttime1" size="10" class="date" >
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "starttime1",
				trigger    : "starttime1",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script> &nbsp;
			直播频道:
					<select  name='livechannel'  value="<?php if(isset($livechannel)) echo $livechannel;?>">
						<option  value='' selected='<?php if(isset($livechannel)) echo $livechannel;?>' ><?php if(isset($livechannel)) echo $livechannel;?></option>
						<option  value='cctv1' >cctv1</option>
		                <option  value='深圳卫视'  >深圳卫视</option>
		                <option  value='广东卫视' >广东卫视</option> 
          			</select>
			
						<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>"  size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<div style="float:right;"><a href="">导出数据</a></div>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<?php } ?>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
				<th width="200" align="left">日期</th>
				<th width='200' align="left">频道</th>
				<th width='200' align="center">直播次数</th>
				<th width='200' align="center">直播人次</th>
				<th width='200' align="center">直播时长</th>	
            </tr>
        </thead>
    <tbody>
<?php if(count($data)){foreach($data as $value){?>  
	<tr>
	<td align="left"><?php echo date('Y年-m月-d日 ',$value['starttime']);?></td>
	<td align="left"><?php echo $value['usercount'];?></td>
	<td align="center"><?php echo $value['opencount'];?></td>	
	<td align="center"><?php echo $value['newcount'];?></td>	
	<td align="center"><?php echo $value['activereach'];echo '%'?></td>	
	
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
