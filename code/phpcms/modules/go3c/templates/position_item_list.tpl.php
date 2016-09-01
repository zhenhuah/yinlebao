<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="position" name="c">
<input type="hidden" value="position_item_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=position&a=position_item_list">全部数据</a>&nbsp;
			VID：<input name="asset_id" type="text" value="<?php if(isset($asset_id)) echo $asset_id;?>" class="input-text" />
			名称：<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />
 类型：<select name="filter">
				<option value='' <?php if($_GET['filter']==0) echo 'selected';?>>全部</option>
				<option value='3' <?php if($_GET['filter']==3) echo 'selected';?>>电视栏目</option>
				<option value='4' <?php if($_GET['filter']==4) echo 'selected';?>>电视剧</option>
				<option value='5' <?php if($_GET['filter']==5) echo 'selected';?>>电影</option>
				<option value='6' <?php if($_GET['filter']==6) echo 'selected';?>>乐酷</option>
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
			<th align="center" width="50">序号</th>
			<th align="center" width="88">导入时间</th>
			<th align="center" width="60">VID</th>
			<th align="center" width="128">名称</th>
			<th align="center" width="50">类型</th>
			<th align="center" width="128">操作</th>
            </tr>
        </thead>
    <tbody>

	<?php if(is_array($data)){foreach($data as $channel){?>   
	<tr>
	<td align="center"><?php echo $channel['id']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<td align="center"><?php echo $channel['asset_id']?></td>
	<td align="center"><?php echo $channel['title']?></td>
	<td align="center"><?php echo columnid2name($channel['column_id'],$channel['ispackage'])?></td>
	<td align="center"><a style="color:blue" href="javascript:selectvideo(<?php echo $term_id;?>,<?php echo $posid;?>,<?php echo $channel['id']?>);void(0);"><?php echo '选择'?></a></td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
	</div>
</body>
<script type="text/javascript">
function selectvideo(termid,posid,id){
	location.href='?m=go3c&c=position&a=position_item_manage&pc_hash='+pc_hash+'&term_id='+termid+'&posid='+posid+'&id='+id;
}
</script>
</html>
