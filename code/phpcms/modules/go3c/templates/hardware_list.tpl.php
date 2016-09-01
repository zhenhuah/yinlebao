<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="hardware" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=client&a=hardware">全部数据</a>&nbsp;
			芯片：<input name="chip" type="text" value="<?php if(isset($chip)) echo $chip;?>" class="input-text" />&nbsp;
			ANDROID版本：<input name="ANDROID" type="text" value="<?php if(isset($ANDROID)) echo $ANDROID;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
			<th width="50" align="center">序号</th>
			<th width='50' align="center">主芯片</th>
			<th width='50' align="center">ANDROID版本</th>
			<th width='50' align="center">内存</th>
			<th width="50" align="center">存储(内置)</th>
			<th width="50" align="center">存储(外置)</th>
			<th width="50" align="center">支持接口</th>
			<th width="50" align="center">遥控器</th>
			<th width="50" align="center">BT支持</th>
			<th width="50" align="center">时间</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $client_version){?>   
	<tr>
	<td align="center"><?php echo $client_version['id']?></td>
	<td align="center"><?php echo $client_version['chip'];?></td>
	<td align="center"><?php echo $client_version['ANDROID'];?></td>
	<td align="center"><?php echo $client_version['memory'];?></td>
	<td align="center"><?php echo $client_version['storagen'];?></td>
	<td align="center"><?php echo $client_version['storagew'];?></td>
	<td align="center"><?php echo $client_version['Interface'];?></td>
	<td align="center"><?php echo $client_version['remotecontrol'];?></td>
	<td align="center"><?php echo $client_version['BT'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$client_version['createtime']);?></td>
	<td align="center">
		
	</td> 
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
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function versionlist(version) {
    location.href ='?m=go3c&c=client&a=versionlist&version='+version+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function preview(version)
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=client&a=preview&version='+version+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'图片预览',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
}
</script>
