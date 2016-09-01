<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="down_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=tvuser&a=init">全部数据</a>&nbsp;
			每页：<input name="perpage" type="text" value="<?php echo $perpage;?>" class="input-text" size="3" />个
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
			<th width="100" align="center">ID</th>
			<th width='200' align="left">客户端名称</th>
			<th width="100" align="center">下载时间</th>
            </tr>
        </thead>
    <tbody>
	<?php 
	if(is_array($data)){
		foreach($data as $key=>$value){
	?>   
	<tr>
	<td align="center"><?php echo $value['id'];?></td>
	<td align="left"><a href="javascript:down_list('<?php echo $value['type']?>');void(0);"><?php echo $value['type'];?></a></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $value['updatetime']);?></td>
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