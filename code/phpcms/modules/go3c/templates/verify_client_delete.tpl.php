<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="verify" name="c">
<input type="hidden" value="client_delete" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=verify&a=client_delete">全部数据</a>&nbsp;
			版本：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />
            终端类型：<select name="term_id">
				<option value='0' <?php if($_GET['term_id']==0) echo 'selected="selected"';?>>全部</option>
	            <?php foreach ($term_type_array as $key => $ttvalue) {?>
					<option value='<?php echo $key?>' <?php if($_GET['term_id']==$key) echo 'selected';?>><?php echo $ttvalue?></option>
				<?php }?>
			</select>
            OS类型：<select name="os_type">
				<option value='' <?php if($_GET['os_type']==0) echo 'selected="selected"';?>>全部</option>
				<option value='1' <?php if($_GET['os_type']==1) echo 'selected="selected"';?>>Android</option>
				<option value='2' <?php if($_GET['os_type']==2) echo 'selected="selected"';?>>iOS</option>
			</select>
            状态：<select name="online_status">
				<option value='' <?php if($_GET['online_status']==0) echo 'selected="selected"';?>>全部</option>
				<option value='1' <?php if($_GET['online_status']==1) echo 'selected="selected"';?>>导入</option>
				<option value='2' <?php if($_GET['online_status']==2) echo 'selected="selected"';?>>正在编辑</option>
				<option value='3' <?php if($_GET['online_status']==3) echo 'selected="selected"';?>>编辑未通过</option>
				<option value='10' <?php if($_GET['online_status']==10) echo 'selected="selected"';?>>已提交审核</option>
				<option value='11' <?php if($_GET['online_status']==11) echo 'selected="selected"';?>>已审核通过</option>
				<option value='12' <?php if($_GET['online_status']==12) echo 'selected="selected"';?>>审核未通过</option>
				<option value='20' <?php if($_GET['online_status']==20) echo 'selected="selected"';?>>已提交删除</option>
				<option value='99' <?php if($_GET['online_status']==99) echo 'selected="selected"';?>>错误</option>
			</select>

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
			<th width="50" align="center">终端类型</th>
			<th width='108' align="center">OS类型</th>
			<th width='50' align="center">版本号</th>
			<th width='50' align="center">发布时间</th>
			<th width="50" align="center">最后编辑</th>
			<th width="50" align="center">修改时间</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $client_version){?>   
	<tr>
	<td align="center"><?php echo $term_type_array[$client_version['term_id']]?></td>
	<td align="center"><?php echo $client_version['os_type'] == 1 ? 'Android' : 'iOS'  ?></td>
	<td align="center"><?php echo $client_version['title']?></td>
	<td align="center"><?php echo $client_version['release_date'];?></td>
	<td align="center"><?php echo $client_version['username'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$client_version['updatetime']);?></td>
	<td align="center">
		<a style="color:blue" href="javascript:dopass(<?php echo $client_version['id']?>, '通过')">通过</a>
		<a style="color:green" href="javascript:dorefuse(<?php echo $client_version['id']?>, '拒绝')">拒绝</a>
		<input type="hidden" value="<?php echo $client_version['id']?>" name="ids[]" />
	</td> 
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
function dopass(id, title) {
    location.href ='?m=go3c&c=verify&a=client_delete_pass&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dorefuse(id, title) {
    location.href ='?m=go3c&c=verify&a=client_delete_refuse&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=go3c&c=content&a=edit&catid=66&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

</script>
