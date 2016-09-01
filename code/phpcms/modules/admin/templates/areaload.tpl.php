<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<div class="pad-lr-10">
<form name="myform" action="" method="GET">
<input type="hidden" value="admin" name="m">
<input type="hidden" value="area" name="c">
<input type="hidden" value="index" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			区域名称：<input name="name" type="text" value="<?php if(isset($name)) echo $name;?>" class="input-text" />&nbsp;
			频道分类：<select name="mf">
				<option value="0">--请选择--</option>
		  		<option value="m">央视频道</option>
		  		<option value="c">高清频道</option>
		  		<option value="a">地方频道</option>
		  		<option value="a">地方卫视</option>
			</select>&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />	
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
            <tbody>
            <tr>
			<th width="20" align="center">区域ID</th>
			<th width="30" align="left">区域名称</th>
			<th width="60" align="left">上线频道数量</th>
			<th width='130' align="left">上线视频数量</th>
			<th width='50' align="left">用户数</th>
            </tr>
	<?php
	if(is_array($data)) {
		foreach($data as $key => $value1) {
	?>  
	<tr>
	<td align="center"><?=$value1['MSG_TITLE']?></td>
	<td align="left"><?=$value1['MSG_TITLE']?></td>
	<td align="left"><?=$value1['MSG_TITLE']?></td>
	<td align="left"><?=$value1['MSG_TITLE']?></td>
	<td align="left"><?=$value1['MSG_TITLE']?></td>
	</tr>
	<?php }} ?>
	</div>
	</tbody>
    </table>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
