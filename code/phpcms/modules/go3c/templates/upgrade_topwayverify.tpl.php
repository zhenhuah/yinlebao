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
    </tbody>
</table>

<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
				<th width='10' align="center">类型</th>
				<th width='30' align="center">是否强制</th>
				<th width='20' align="center"><?php echo L('auth_id')?></th>
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
	<td align="center"><a style="color:blue" href="<?php echo $value['url']?>">下载文件</a></td>
	<td align="center"><?php if ($value['zip_size']) echo $value['zip_size']; else echo $value['size']?></td>
	<td align="center"><?php echo $value['versioncode']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php echo $value['upgrade_time']?></td>
	<td align="center"><p style="overflow: hidden; height: 30px; line-height: 30px; max-width: 100px;" title="<?php echo $value['description']?>"><?php echo $value['description']?></p></td>
	<td align="center"><?php echo formatstatus($value['u_status'])?></td>
	<td align="center">
		<a href="javascript:veragree('<?php echo $value['id']?>')">同意</a> | 
		<a href="javascript:refuse('<?php echo $value['id']?>')">拒绝</a>
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
function veragree(id) {
    location.href ='?m=go3c&c=upgrade&a=veragree_upgrade&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function refuse(id) {
		location.href ='?m=go3c&c=upgrade&a=refuse_upgrade&id='+id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
}
</script>
