<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="versionlist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center">序号</th>
			<th width='50' align="center">终端</th>
			<th width='50' align="center">版本</th>
			<th width='100' align="center">图片(文件)</th>
			<th width='50' align="center">操作类型</th>
			<th width='50' align="center">操作用户</th>
			<th width="50" align="center">时间</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $client_version){?>   
	<tr>
	<td align="center"><?php echo $client_version['logid']?></td>
	<td align="center">
	<?php if ($client_version['term'] == '1')
				{
					echo 'STB';
				}elseif ($client_version['term'] == '2')
				{
					echo 'PAD';
				}elseif ($client_version['term'] == '3')
				{
					echo 'PHONE';
				}else{
					echo 'PC';
				}
				?></td>
	<td align="center"><?php echo $client_version['version'];?></td>
	<td align="center"><a href="<?php echo $client_version['url']?>" target="_blank"><img style="width:30px; border:solid 1px gray; padding:2px;" src="<?php echo $client_version['url']?>" /></a></td>
	<td align="center"><?php echo $client_version['type'];?></td>
	<td align="center"><?php echo $client_version['username'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$client_version['createtime']);?></td>
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
</script>
