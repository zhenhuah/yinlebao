<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加新版本</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="client_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=client&a=client_list">全部数据</a>&nbsp;
			版本：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />
            终端类型：<select name="term_id">
				<option value='0' <?php if($_GET['term_id']==0) echo 'selected="selected"';?>>全部</option>
	            <?php foreach ($term_type_array as $key => $ttvalue) {?>
					<option value='<?php echo $key?>' <?php if($_GET['term_id']==$key) echo 'selected';?>><?php echo $ttvalue?></option>
				<?php }?>
			</select>&nbsp;
            OS类型：<select name="os_type">
				<option value='' <?php if($_GET['os_type']==0) echo 'selected="selected"';?>>全部</option>
				<option value='1' <?php if($_GET['os_type']==1) echo 'selected="selected"';?>>Android</option>
				<option value='2' <?php if($_GET['os_type']==2) echo 'selected="selected"';?>>iOS</option>
			</select>&nbsp;
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
			</select>&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<!--div style="text-align:right;padding:0 10px 10px 0;">
	<input class="button" type="button" onclick="addnew()" value="添加新版本" />
</div-->
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
			<th width="68" align="center">状态</th>
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
	<td align="center"><?php echo online_status($client_version['online_status'],$client_version['published']);?></td>
	<td align="center">
		<?php if($client_version['online_status'] != 11) {?>
			<?php if($client_version['online_status'] != 10){?>
				<a style="color:blue" href="javascript:edit(<?php echo $client_version['id']?>, '编辑')">编辑</a>
				<a style="color:green" href="javascript:dopass(<?php echo $client_version['id']?>, '申请上线')">申请上线</a>
			<?php }?>
		<?php }?>
		<a style="color:red" href="javascript:dodelete(<?php echo $client_version['id']?>, '申请删除')">申请删除</a>
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
function edit(id, title) {
    location.href ='?m=content&c=content&a=edit&catid=66&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dopass(id, title) {
    location.href ='?m=go3c&c=client&a=online_apply&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id, title) {
    location.href ='?m=go3c&c=client&a=delete_apply&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=content&c=content&a=add&catid=66&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

</script>
