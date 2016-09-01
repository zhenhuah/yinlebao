<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="user_feedback" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=tvuser&a=user_feedback">全部数据</a>&nbsp;
			 类型：<select name="issue_type">
				<option value='' <?php if($_GET['issue_type']=='') echo 'selected';?>>全部</option>
				<option value='网速太慢' <?php if($_GET['issue_type']=='网速太慢') echo 'selected';?>>网速太慢</option>
				<option value='链接异常' <?php if($_GET['issue_type']=='链接异常') echo 'selected';?>>链接异常</option>
				<option value='内容太少' <?php if($_GET['issue_type']=='内容太少') echo 'selected';?>>内容太少</option>
				<option value='值得推荐' <?php if($_GET['issue_type']=='值得推荐') echo 'selected';?>>值得推荐</option>
				<option value='播放故障' <?php if($_GET['issue_type']=='播放故障') echo 'selected';?>>播放故障</option>
				<option value='其他' <?php if($_GET['issue_type']=='其他') echo 'selected';?>>其他</option>
			</select>
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
			<th width="10%" align="left">时间</th>
			<th width='10%' align="left">终端名称</th>
			<th width='10%' align="left">操作系统</th>
			<th width='10%' align="left">终端硬件</th>
			<th width='10%' align="left">终端硬件配置</th>
			<th width='10%' align="left">客户端版本</th>
			<th width='10%' align="left">问题类型</th>
			<th style="width:300px; word-break: break-all; word-wrap:break-word;" align="left">问题描述</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td width="10%" align="left"><?php echo $value['time_added']?></td>
	<td width='10%' align="left"><?php echo $value['term_name'];?></td>
	<td width='10%' align="left"><?php echo $value['os']?></td>
	<td width='10%' align="left"><?php echo $value['hw_model']?></td>
	<td width='10%' align="left"><?php echo $value['hw_config']?></td>
	<td width='10%' align="left"><?php echo $value['client_version']?></td>
	<td width='10%' align="left"><?php echo $value['issue_type']?></td>
	<td style="width:300px; word-break: break-all; word-wrap:break-word;" align="left"><?php echo $value['description']?></td>
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
