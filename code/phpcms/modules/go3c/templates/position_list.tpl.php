<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="position" name="c">
<input type="hidden" value="position_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $term_id;?>" name="term_id">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($editenable){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">选择推荐位
            <select id="posid_select" name="posid" onchange="change_position(this)">
            <?php foreach ($postion_type_array as $key => $ptvalue) {?>
			<option value='<?php echo $key?>' <?php if($_GET['posid']==$key) echo 'selected';?>><?php echo $ptvalue?></option>
			<?php }?>
			</select>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div style="text-align:right;padding:0 10px 10px 0;">
	<input class="button" type="button" onclick="addvideo(<?php echo $term_id;?>,<?php echo $posid;?>)" value="添加视频" />    &nbsp;&nbsp;
	<input class="button" type="button" onclick="online(<?php echo $term_id;?>,<?php echo $totalnum;?>)" value="申请审核" />
</div>
<?php }else{ ?>
<div style="text-align:right;padding:0 10px 10px 0;">
	<input class="button" type="button" onclick="history.back()" value="返回" />
</div>
<?php }?>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="35" align="center">序号</th>
			<th width="60" align="center">VID</th>
			<th width="160" align="center">栏目</th>
			<th width="160" align="center">名称</th>
			<th width="160" align="center">时间</th>
			<!--th width="60" align="center">图片类型</th-->
			<th width="60" align="center">图片预览</th>
			<th width="35" align="center">排序</th>
			<th width="150" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){
	$i = 1;$len=count($data);
	foreach($data as $channel){?>   
	<tr>
	<td align="center"><?php echo $i?></td>
	<td align="center"><?php echo $channel['asset_id']?></td>
	<td align="center"><?php echo $channel['name']?></td>
	<td align="center"><?php echo $channel['title']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<!--td align="center"><?php echo $channel['img_type']?></td-->
	<td align="center"><a style="color:blue;" href="<?php echo $channel['path']?>" target="_blank">预览</a></td>
	<td align="center"><?php echo $channel['listorder']?></td>
	<td align="center">
	<?php if($editenable){?>
		<a href="?m=go3c&c=position&a=position_item_manage&term_id=<?php echo $channel['term_id']?>&type_id=<?php echo $channel['type_id']?>&posid=<?php echo $channel['posid']?>&pvid=<?php echo $channel['id']?>&vid=<?php echo $channel['vid']?>">编辑</a> |
		<a href="?m=go3c&c=position&a=position_delete&term_id=<?php echo $channel['term_id'];?>&type_id=<?php echo $channel['type_id']?>&posid=<?php echo $channel['posid']?>&id=<?php echo $channel['id']?>">删除</a>
		<?php if($i>1){?>
		 |<a href="?m=go3c&c=position&a=position_move_up&term_id=<?php echo $channel['term_id'];?>&type_id=<?php echo $channel['type_id']?>&posid=<?php echo $channel['posid']?>&id=<?php echo $channel['id']?>">上升</a>
		<?php } if($len>$i){?>
		 |<a href="?m=go3c&c=position&a=position_move_down&term_id=<?php echo $channel['term_id'];?>&type_id=<?php echo $channel['type_id']?>&posid=<?php echo $channel['posid']?>&id=<?php echo $channel['id']?>">下降</a>
		<?php } ?>
	<?php }?>
	</td>
	</tr>
	<?php $i++;}} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
<script type="text/javascript">
var selected_posid = "1";
function change_position(obj){
	//selected_posid  = obj.options[obj.options.selectedIndex].value;
	document.myform.submit();
}
function addvideo(term_id,posid){
	var obj = document.getElementById('posid_select');
	selected_posid  = obj.options[obj.options.selectedIndex].value;
	location.href='?m=go3c&c=position&a=position_item_manage&pc_hash='+pc_hash+'&term_id='+term_id+'&posid='+selected_posid;
}
function online(term_id,totalnum){
	var obj = document.getElementById('posid_select');
	selected_posid  = obj.options[obj.options.selectedIndex].value;
	location.href='?m=go3c&c=position&a=position_online&pc_hash='+pc_hash+'&totalnum='+totalnum+'&term_id='+term_id+'&posid='+selected_posid;
}
</script>
</html>
