<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="publishlog" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=publishlog&a=init">全部数据</a>&nbsp;
			<?php if($this->current_spid['roleid'] == '1'){?>
		    用户名：<input name="username" type="text" value="<?php if(isset($username)) echo $username;?>" class="input-text" />&nbsp;
			<?php }?>
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
			<th width="50" align="center">ID</th>
			<th width='108' align="center">操作日期</th>
			<th width='108' align="center">操作类型</th>
			<th width='50' align="center">操作数量</th>
			<th width="68" align="center">操作人员</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['created']);?></td>
	<td align="center"><?php echo $action_content[$value['content']]?></td>
	<td align="center"><?php echo $value['count']?></td>
	<td align="center"><?php echo $value['username']?></td>
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