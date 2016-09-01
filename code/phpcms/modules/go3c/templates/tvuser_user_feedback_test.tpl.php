<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="user_feedback_test" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=tvuser&a=user_feedback_test">全部数据</a>&nbsp;
		    RMAC: <input type="text" name="rmac"  value="<?php if ($_GET['rmac']) echo $_GET['rmac']?>">&nbsp;
		    项目: <select name="cid">
		            <option value='' <?php if(!isset($_GET['cid'])) echo 'selected';?>>全部</option>
		            <?php {foreach($cids as $key=>$v){?>
		           		 <option value='<?php echo $v['customerid']?>' <?php if($_GET['cid']==$v['customerid']) echo 'selected';?>><?php echo $v['customerid']?></option>
					<?php }} ?>
				  </select>&nbsp;
		    平台: <select name="clientid">
		            <option value='' <?php if(!isset($_GET['clientid'])) echo 'selected';?>>全部</option>
		            <?php {foreach($clientids as $key=>$v){?>
		           		 <option value='<?php echo $v['clientid']?>' <?php if($_GET['clientid']==$v['clientid']) echo 'selected';?>><?php echo $v['clientid']?></option>
					<?php }} ?>
				  </select>&nbsp;
			 类型：<select name="issue_type">
				<option value='' <?php if($_GET['issue_type']=='') echo 'selected';?>>全部</option>
				<option value='网速太慢' <?php if($_GET['issue_type']=='网速太慢') echo 'selected';?>>网速太慢</option>
				<option value='链接异常' <?php if($_GET['issue_type']=='链接异常') echo 'selected';?>>链接异常</option>
				<option value='内容太少' <?php if($_GET['issue_type']=='内容太少') echo 'selected';?>>内容太少</option>
				<option value='值得推荐' <?php if($_GET['issue_type']=='值得推荐') echo 'selected';?>>值得推荐</option>
				<option value='播放故障' <?php if($_GET['issue_type']=='播放故障') echo 'selected';?>>播放故障</option>
				<option value='crash' <?php if($_GET['issue_type']=='crash') echo 'selected';?>>程序crash</option>
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
			<th width="7%" align="left">RMAC</th>
			<th width="7%" align="left">项目</th>
			<th width="7%" align="left">平台</th>
			<th width='7%' align="left">终端名称</th>
			<th width='7%' align="left">操作系统</th>
			<th width='7%' align="left">终端硬件</th>
			<th width='10%' align="left">终端硬件配置</th>
			<th width='7%' align="left">客户端版本</th>
			<th width='7%' align="left">问题类型</th>
			<th width='10%' align="left">crash时间</th>
			<th width='7%' style="width:300px; word-break: break-all; word-wrap:break-word;" align="left">问题描述</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="left"><?php echo $value['time_added']?></td>
	<td align="left"><?php echo $value['rmac'];?></td>
	<td align="left"><?php echo $value['customerid']?></td>
	<td align="left"><?php echo $value['clientid']?></td>
	<td align="left"><?php echo $value['term_name'];?></td>
	<td align="left"><?php echo $value['os']?></td>
	<td align="left"><?php echo $value['hw_model']?></td>
	<td align="left"><?php echo $value['hw_config']?></td>
	<td align="left"><?php echo $value['client_version']?></td>
	<td align="left"><?php echo $value['issue_type']?></td>
	<td align="left"><?php echo $value['crash_time']?></td>
	<td style="width:300px" align="left"><a style="color:blue" href="?m=go3c&c=tvuser&a=showreportlog&id=<?php echo $value['id'];?>">查看详情</a></td>
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
