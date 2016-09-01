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
<div class="content-menu ib-a blue line-x">
	<input  type="button"   id="btnUpload"  value="<?php echo L('ktv_file_up');?>" />
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="ktv" name="c">
<input type="hidden" value="songsup" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="28" align="center">ID</th>
			<th width='28' align="center"><?php echo L('ktv_Operator');?></th>
			<th width='28' align="center"><?php echo L('ktv_file_name');?></th>
			<th width='28' align="center">ip</th>
			<th width='28' align="center"><?php echo L('ktv_time');?></th>
			<th width='28' align="center"><?php echo L('ktv_spid');?></th>
			<th width='28' align="center"><?php echo L('ktv_oper');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['logid']?></td>
	<td align="center"><?php echo $value['username']?></td>
	<td align="center"><a href="<?php echo $value['contetdec']?>" target="_blank"><?php echo $value['vid']?></a></td>
	<td align="center"><?php echo $value['ip']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $value['createtime'])?></td>
	<td align="center"><?php echo $value['spid']?></td>
	<td align="center"><a href="javascript:dodelete('<?php echo $value['logid']?>')"><?php echo L('ktv_del');?></a></td>
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
jQuery("input[id='btnUpload']").click(function(){
	//alert(jQuery("input[name='info[column_id]'][@checked]").val());
	window.top.art.dialog(
	{
		id:'testIframe',
		iframe:"?m=go3c&c=ktv&a=song_upload&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
		title:'多文件上传',
		width:'500',
		height:'500'
	},
	function(){
		var ifram_page_data = window.top.art.dialog({id:'testIframe'}).data.iframe;
		window.top.art.dialog({id:'testIframe'}).close();
	}
	);
});
function dodelete(logid) {
    location.href ='?m=go3c&c=ktv&a=deleteSongup&logid='+logid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
