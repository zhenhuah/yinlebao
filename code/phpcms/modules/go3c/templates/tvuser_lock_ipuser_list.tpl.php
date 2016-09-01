<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="lock_ipuser" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=tvuser&a=init">全部数据</a>&nbsp;
			IP：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />
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
			<th width="100" align="left">ID</th>
			<th width='200' align="left">IP</th>
			<th width='200' align="left">操作日期</th>
			<th width="100" align="left">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="left"><?php echo $value['ID']?></td>
	<td align="left"><?php echo $value['IP'];?></td>
	<td align="left"><?php echo $value['block_date']?></td>
	<td align="left"><a style="color:red"  href="?m=go3c&c=tvuser&a=ip_unlock&id=<?php echo $value['ID']?>">解锁</a></td> 
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>

<div class="content-menu ib-a blue line-x">
  <form name="myform"  action="" method="GET">
    添加锁定IP：
    <input name="ip" class="input-text" />
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="tvuser" name="c">
	<input type="hidden" value="ip_lock" name="a">
	<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
    <input type="submit" name="search" class="button" value="提交" />
  </form>
</div>
</div>
</body>
</html>
