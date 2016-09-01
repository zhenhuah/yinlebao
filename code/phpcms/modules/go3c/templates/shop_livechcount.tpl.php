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
			进入时间：<input type="text" value="<?php echo $createtime;?>" name="createtime" id="createtime" size="10" class="date" >
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
			</script> --
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
			直播频道:
					<select id= "livechannel" name='livechannel'>
						<option value="" <?php if($livechannel == ''){ echo 'selected';}?>>全部</option>
						<option value="cctv1" <?php if($livechannel == 'cctv1'){ echo 'selected';}?>>cctv1</option>
		                <option value="深圳卫视" <?php if($livechannel == '深圳卫视'){ echo 'selected';}?>>深圳卫视</option>
		                <option value="广东卫视"<?php if($livechannel == '广东卫视'){ echo 'selected';}?>>广东卫视</option>    
          			</select>
						<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>"  size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<input type="button" value="导出数据" onclick="dianji()"/>
			<script>
				function dianji(){
				//alert("确定导出");
				var strcreatetime = document.getElementById('createtime').value;
				var strendtime = document.getElementById('endtime').value;
				var strlivechannel = document.getElementById('livechannel').value;
				//alert("phpcms/modules/go3c/templates/excel_table/shop_livechcountexcel.php?createtime="+strcreatetime+"&endtime="+strendtime+"&livechannel="+strlivechannel);
				window.location.href="phpcms/modules/go3c/templates/excel_table/shop_livechcountxcel.php?createtime="+strcreatetime+"&endtime="+strendtime+"&livechannel="+strlivechannel;
				}
			</script>
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
				<th width='200' align="center">频道</th>
				<th width='200' align="center">直播次数</th>
				<th width='200' align="center">直播人次</th>
				<th width='200' align="center">直播时长（分钟）</th>	
            </tr>
        </thead>
    <tbody>
<?php if(count($data)){foreach($data as $value){?>  
	<tr>
	<td align="left"><?php echo date('Y年-m月-d日 ',$value['createtime']);?></td>
	<td align="center"><?php echo $value['livechannel'];?></td>	
	<td align="center"><?php echo $value['livecount'];?></td>	
	<td align="center"><?php echo $value['livepeoplecnt'];?></td>	
	<td align="center"><?php echo $value['livetime'];?></td>	
	
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
