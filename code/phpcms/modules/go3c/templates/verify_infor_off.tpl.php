<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="verify" name="c">
<input type="hidden" value="game_online" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=verify&a=client_online">全部数据</a>&nbsp;
			资讯名称：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />
			资讯类型：<select id="type" name="type">
	            <?php foreach ($type_name_array as $key => $ptvalue) {?>
				<option value='<?php echo $key?>' <?php if($_GET['type_name']==$key) echo 'selected';?>><?php echo $ptvalue?></option>
				<?php }?>
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
			<th width="10" align="center">ID</th>
			<th width='38' align="center">名称</th>
			<th width='20' align="center">类型</th>
			<th width='88' align="center">缩列图</th>
			<th width='38' align="center">简介</th>
			<th width='38' align="center">状态</th>
			<th width='58' align="center">更新时间</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $client_version){?> 
	<tr>
	<td align="center"><?php echo $client_version['id']?></td>
	<td align="center"><?php echo $client_version['title'];?></td>
	<td align="center"><?php echo $client_version['type'];?></td>
	<td align="center"><img src="<?php echo $client_version['thumb'];?>" style="width:50px;" /></td>
	<td align="center"><?php echo $client_version['content'];?></td>
	<td align="center"><?php echo $client_version['online_status'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $client_version['updatetime'])?></td>
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
function dopass(id) {
    location.href ='?m=go3c&c=infor&a=infor_offdo&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dorefuse(id) {
    location.href ='?m=go3c&c=infor&a=infor_off_refuse&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
