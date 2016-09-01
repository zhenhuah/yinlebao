<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="bindlist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
			用户id：<input name="userid" type="text" value="<?php if(isset($userid)) echo $userid;?>" class="input-text" size="15"/>&nbsp;
			身份证：<input name="cardid" type="text" value="<?php if(isset($cardid)) echo $cardid;?>" class="input-text" size="20"/>&nbsp;
			MAC地址：<input name="mac_wire" type="text" value="<?php if(isset($mac_wire)) echo $mac_wire;?>" class="input-text" size="20"/>&nbsp;
			设备sn号：<input name="boxsn" type="text" value="<?php if(isset($boxsn)) echo $boxsn;?>" class="input-text" size="18"/>&nbsp;
			版本：<input name="version" type="text" value="<?php if(isset($version)) echo $version;?>" class="input-text" size="10"/>&nbsp;
			状态：<select id="bild" name="bild">
				  <option value='on' <?php if($_GET['bild']=='on') echo 'selected';?>>绑定</option>
				  <option value='off' <?php if($_GET['bild']=='off') echo 'selected';?>>解绑</option>
				   <option value='all'>全部</option>
			</select>
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
				<th width='20' align="center">身份证</th>
				<th width='20' align="center">手机号</th>
				<th width='20' align="center">地址</th>
				<th width='20' align="center">MAC地址</th>
				<th width='20' align="center">设备sn号</th>
				<th width='10' align="center">版本</th>
				<th width='20' align="center">绑定时间</th>
				<th width='20' align="center">状态</th>
				<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['userid']?></td>
	<td align="center"><?php echo $v['cardid']?></td>
	<td align="center"><?php echo $v['phonenumber']?></td>
	<td align="center"><?php echo $v['installaddress']?></td>
	<td align="center"><?php echo $v['mac_wire']?></td>
	<td align="center"><?php echo $v['boxsn']?></td>
	<td align="center"><?php echo $v['version']?></td>
	<td align="center"><?php echo date("Y-m-d H:i:s",$v['createtime'])?></td>
	<td align="center"><?php echo $v['bild']=='on' ? '已绑定' : '已解绑'?></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $v['id']?>')"><?php echo L('edit')?></a>
		<?php if($v['bild']=='on'){?>  
		<a href="javascript:relbild('<?php echo $v['id']?>')"> | 解绑
		<?php } ?>
		<!--<a href="javascript:delip('<?php echo $v['id']?>')">删除 -->
	</td> 
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
function addnew(){
	$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=auth&a=shop_addbind&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'添加',
					id:'viewOnlyDiv',
					lock:true,
					width:'450'
				});
			}
		});
}
function delip(id) {
    location.href ='?m=go3c&c=auth&a=shop_delbind&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function relbild(id) {
    location.href ='?m=go3c&c=auth&a=shop_relbild&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(id){
	$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=auth&a=shop_editbind&id='+id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'编辑',
					id:'viewOnlyDiv',
					lock:true,
					width:'450'
				});
			}
		});
}
</script>
