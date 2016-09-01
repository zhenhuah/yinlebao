<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
function formatforce($flag) {
	if ($flag == -1) {
		$force = '禁止升级';
	} else if ($flag == 0) {
		$force = '非强制升级';
	} else if ($flag == 1) {
		$force = '强制升级';
	}
	return $force;
}
function formatstatus($status) {
	if ($status == -1) {
		$res = '审核未通过';
	} else if ($status == 0) {
		$res = '历史版本';
	} else if ($status == 1) {
		$res = '最新版本';
	} else if ($status == 2) {
		$res = '待审核';
	}
	return $res;
}
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="upgrade" name="c">
<input type="hidden" value="upgradelist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<a href="?m=go3c&c=upgrade&a=upgradelist"><?php echo L('all')?></a>&nbsp;
			<?php  if ($_SESSION['upgrade_role'] == 'ALL') {?>
			升级类型：<select id="upgrade_type" name="upgrade_type">
						<option value="">全部</option>
						<option value="APK" <?php if ($_GET['upgrade_type'] == 'APK') echo 'selected'?>>APK</option>
						<option value="FIRMWARE" <?php if ($_GET['upgrade_type'] == 'FIRMWARE') echo 'selected'?>>FIRMWARE</option>
						<option value="SONG_DB" <?php if ($_GET['upgrade_type'] == 'MIDWARE') echo 'selected'?>>MIDWARE</option>
					</select>
			<?php }?>
			版本状态：<select name="status">
				<option value="1" <?php if (isset($_GET['status']) && $_GET['status'] == 1) echo 'selected'?>>最新版本</option>
				<option value="0" <?php if (isset($_GET['status']) && $_GET['status'] == 0) echo 'selected'?>>历史版本</option>
				<option value="2" <?php if (isset($_GET['status']) && $_GET['status'] == 2) echo 'selected'?>>待审核</option>
				<option value="-1" <?php if (isset($_GET['status']) && $_GET['status'] == -1) echo 'selected'?>>审核未通过</option>
			</select>
			<?php echo L('page_list')?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<div class="content-menu ib-a blue line-x" style="float:right">
				<a class="add fb" href="javascript:addnew()" ><em><?php echo L('add')?></em></a>
			</div>
		</div>
		</td>
		</tr>
    </tbody>
</table>

<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
				<th width='10' align="center">类型</th>
				<th width='30' align="center">是否强制</th>
				<th width='20' align="center"><?php echo L('auth_id')?></th>
				<th width='20' align="center">硬件编号</th>
				<th width='35' align="center">文件链接</th>
				<th width='10' align="center">包大小(B)</th>
				<th width='10' align="center">Versioncode</th>
				<th width='10' align="center">版本名</th>
				<th width='30' align="center">升级时间</th>
				<th width='100' align="center">描述</th>
				<th width='30' align="center">版本状态</th>
				<th width='30' align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['upgrade_type']?></td>
	<td align="center"><?php echo formatforce($value['is_force'])?></td>
	<td align="center"><?php if ($value['cid'] == 'ALL_CIDS') echo '所有项目'; else echo $value['cid']?></td>
	<td align="center"><?php echo $value['term_type']?></td>
	<td align="center"><a style="color:blue" href="<?php echo $value['url']?>">下载文件</a></td>
	<td align="center"><?php if ($value['zip_size']) echo $value['zip_size']; else echo $value['size']?></td>
	<td align="center"><?php echo $value['versioncode']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php echo $value['upgrade_time']?></td>
	<td align="center"><p style="overflow: hidden; height: 30px; line-height: 30px; max-width: 100px;" title="<?php echo $value['description']?>"><?php echo $value['description']?></p></td>
	<td align="center"><?php echo formatstatus($value['u_status'])?></td>
	<td align="center">
	<a href="javascript:veredit('<?php echo $value['id']?>')">编辑</a> | 
	<a href="javascript:verdel('<?php echo $value['id']?>')">删除</a></td>
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
function addnew() {
    location.href ='?m=go3c&c=upgrade&a=upgrade_add_topway&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function veredit(id) {
    location.href ='?m=go3c&c=upgrade&a=edit_upgrade_topway&id='+id+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
}
function verdel(id) {
	if (confirm('确定要删除该版本吗?')) {
		location.href ='?m=go3c&c=upgrade&a=delete_upgrade_topway&id='+id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
}
</script>
