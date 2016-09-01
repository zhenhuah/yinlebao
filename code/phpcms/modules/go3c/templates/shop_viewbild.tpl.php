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
<input type="hidden" value="viewbild" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
			类型：<select name="category" id="category">
				  <option value="" >请选择</option>
				  <option value="vod" <?php if($category == 'vod'){ echo 'selected';}?>>点播</option>
				  <option value="live" <?php if($category == 'live'){ echo 'selected';}?>>直播</option>
				  <option value="epg" <?php if($category == 'epg'){ echo 'selected';}?>>时移</option>
				  <option value="timeshift" <?php if($category == 'timeshift'){ echo 'selected';}?>>回看</option>
			      </select>  &nbsp;
			开始时间：<input type="text" value="<?php echo $starttime;?>" name="starttime" id="starttime" size="10" class="date" >
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
			点播:<?php echo $numv;?>&nbsp;直播:<?php echo $numl;?>&nbsp;时移:<?php echo $nume;?>&nbsp;回看:<?php echo $numts;?>&nbsp;&nbsp;
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
				<th width='30' align="center">用户id</th>
				<th width='30' align="center">节目名</th>
				<th width='20' align="center">类型</th>
				<th width='20' align="center">时间</th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['id']?></td>
	<td align="center"><?php echo $v['userid']?></td>
	<td align="center"><?php echo $v['name']?></td>
	<td align="center"><?php echo cattypeplay($v['category'])?></td>
	<td align="center"><?php if(!empty($v['starttime'])) echo date('Y-m-d h:i:s',$v['starttime']);?></td>
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
