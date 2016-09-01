<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>

<form action="" method="GET" onsubmit="return cksubmit();">
<input type="hidden" name="m" value="go3c" />
<input type="hidden" name="c" value="import" />
<input type="hidden" name="a" value="imdo" />

<table cellspacing="0" cellpadding="0" class="table_form" width="500" align="center">
<tr>
	<th style="width:110px;">电视频道</th>
	<td>
	<label for="i_imchannel_0"><input id="i_imchannel_0" value="0" type="radio" name="imchannel" />不导入</label>
	&nbsp;
	<label for="i_imchannel_9"><input id="i_imchannel_9" value="9" type="radio" name="imchannel" />导入全部</label>
</tr>
<tr>
	<th>EPG</th>
	<td><input type="text" name="imepg"  style="width:120px;" id="imepg"  />
	如要导入请填选日期
	</td>
</tr>
<tr>
	<th>视频资源</th>
	<td>
	<label for="i_imasset_0"><input id="i_imasset_0" value="0" type="radio" name="imasset" />不导入</label>
	&nbsp;
	<label for="i_imasset_1"><input id="i_imasset_1" value="1" type="radio" name="imasset" />导入更新</label>
	&nbsp;
	<label for="i_imasset_9"><input id="i_imasset_9" value="9" type="radio" name="imasset" />导入全部</label>
	</td>
</tr>
<tr>
	<td colspan="10" class="cn">
		<input type="submit" value="导入" style="width:70px;height:21px;" onclick="" />
	</td>
</tr>
</table>
</form>


<br />
<hr />
<br />

<h2>导入（新的办法）</h2>


<style>
.itbf caption { padding:10px;font-weight:bold;font-size:18px; }
.itbf td { text-align:center; padding:5px; }
</style>

<form action="?m=go3c&c=import&a=imdodo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="POST">
<table cellspacing="0" cellpadding="0" class="table_form itbf" width="500" align="center">
<caption>电视频道</caption>
<tr>
	<td>
	<input type="submit" value="导入" name="do_channel" onclick="return confirm('确认？')" style="width:70px;height:21px;" />
	</td>
</tr>
</table>
</form>
<br />
<br />


<form action="?m=go3c&c=fans&a=imdoepg&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="POST">
<table cellspacing="0" cellpadding="0" class="table_form itbf" width="500" align="center">
<caption>EPG</caption>
<tr>
	<td>
	<input type="text" name="imepgs"  style="width:120px;" id="imepgs" value="<?php print date('Y-m-d');?>"  />-
	<input type="text" name="endimepgs"  style="width:120px;" id="endimepgs" value="<?php print date('Y-m-d');?>"  />
	<input type="submit" value="导入" name="do_epg" onclick="return confirm('确认？')" style="width:70px;height:21px;" />
	如要导入请填选日期
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash" />
	</td>
</tr>
</table>
</form>


<br />
<br />

<form action="?m=go3c&c=import&a=imdodo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="POST">
<table cellspacing="0" cellpadding="0" class="table_form itbf" width="500" align="center">
<caption>视频资源</caption>
<tr>
	<td>
	
	<input type="hidden" name="p1" value="<?php echo $spid;?>" />
	<label for="i_imasset_1"><input checked="checked" value="1" type="radio" name="p" />导入更新</label>
	&nbsp;
	<label for="i_imasset_9"><input value="9" type="radio" name="p" />导入全部</label>
	
	</td>
</tr>
<tr>
	<td colspan="10" class="cn">
		<input type="submit" value="导入" name="do_asset" onclick="return confirm('确认？')" style="width:70px;height:21px;" />
	</td>
</tr>
</table>

</form>



<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<script type="text/javascript">
Calendar.setup({
	weekNumbers: true,
	inputField : "imepgs",
	trigger    : "imepgs",
	dateFormat: "%Y-%m-%d",
	showTime: false,
	minuteStep: 1,
	onSelect   : function() {this.hide();}
	});
</script> 
<script type="text/javascript">
Calendar.setup({
	weekNumbers: true,
	inputField : "endimepgs",
	trigger    : "endimepgs",
	dateFormat: "%Y-%m-%d",
	showTime: false,
	minuteStep: 1,
	onSelect   : function() {this.hide();}
	});
</script> 
<script type="text/javascript">
Calendar.setup({
	weekNumbers: true,
	inputField : "imepg",
	trigger    : "imepg",
	dateFormat: "%Y-%m-%d",
	showTime: false,
	minuteStep: 1,
	onSelect   : function() {this.hide();}
	});
</script> 


<script type="text/javascript">
i$('i_imchannel_0').checked = true ;
i$('i_imasset_0').checked = true ;
i$('imepg').value = '' ;



function cksubmit(){
	if(i$('i_imchannel_0').checked && i$('i_imasset_0').checked && i$('imepg').value == ''){
		alert('请选择要导入的项目') ;
		return  false ;
	}
	if(!confirm('确实要导入？')) return false;
	return true ;
}
</script>
