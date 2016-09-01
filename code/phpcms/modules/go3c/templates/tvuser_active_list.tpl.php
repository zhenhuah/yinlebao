<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="active_list" name="a">
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
			<th width='200' align="left">名称</th>
			<th width='200' align="center">上线次数</th>
			<th width="100" align="center">最近上线时间</th>
            </tr>
        </thead>
    <tbody>
	<?php 
	if(is_array($data)){
		foreach($data as $key=>$value){
			$count = $key + 1;
	?>   
	<tr>
	<td align="center"><?php echo $count?></td>
	<td align="left"><?php echo $value['user_id'];?></td>
	<td align="center"><?php echo $value['count'];?></td>
	<td align="center"><?php echo $value['update_time'];?></td>
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
