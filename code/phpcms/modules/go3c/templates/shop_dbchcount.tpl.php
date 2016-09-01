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
<input type="hidden" value="dbchcount" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
			统计时间：<input type="text" value="<?php echo $createtime;?>" name="createtime" id="createtime" size="10" class="date" >
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
			节目类型： <select id= "showtype" name='showtype'>
						<option value="" <?php if($showtype == ''){ echo 'selected';}?>>全部</option>
						<option value="电影" <?php if($showtype== '电影'){ echo 'selected';}?>>电影</option>
		                <option value="电视剧" <?php if($showtype == '电视剧'){ echo 'selected';}?>>电视剧</option>
		                <option value="动漫"<?php if($showtype == '动漫'){ echo 'selected';}?>>动漫</option>    
          			</select>
						<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>"  size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<input type="button" value="导出数据" onclick="dianji()"/>
			<script>
				function dianji(){
				//alert("确定导出");
				var strcreatetime = document.getElementById('createtime').value;
				var strendtime = document.getElementById('endtime').value;
				var strshowtype = document.getElementById('showtype').value;
				//alert("phpcms/modules/go3c/templates/excel_table/shop_dbchcountexcel.php?createtime="+strcreatetime+"&endtime="+strendtime+"&showtype="+strshowtype);
				window.location.href="phpcms/modules/go3c/templates/excel_table/shop_dbchcountxcel.php?createtime="+strcreatetime+"&endtime="+strendtime+"&showtype="+strshowtype;
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
				<th width='200' align="center">节目类型</th>
				<th width='200' align="center">点播次数</th>
				<th width='200' align="center">点播人次</th>
				<th width='200' align="center">点播时长（分钟）</th>	
            </tr>
        </thead>
    <tbody>
<?php if(count($data)){foreach($data as $value){?>  
	<tr>
	<td align="left"><?php echo date('Y年-m月-d日 ',$value['createtime']);?></td>
	<td align="center"><?php echo $value['showtype'];?></td>
	<td align="center"><?php echo $value['dbcount'];?></td>	
	<td align="center"><?php echo $value['dbpeoplecnt'];?></td>	
	<td align="center"><?php echo $value['dbtime'];?></td>	
	
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
