<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加视频</em></a>
</div>
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
		    <a href="?m=admin&c=area&a=index">全部数据</a>&nbsp;
			VID：<input name="asset_id" type="text" value="<?php if(isset($asset_id)) echo $asset_id;?>" class="input-text" />&nbsp;
			名称：<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />&nbsp;
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
			<th width="20" align="center">ID</th>
			<th width="30" align="left">导入时间</th>
			<th width="60" align="left">VID</th>
			<th width='130' align="left">名称</th>
			<th width='50' align="left">类型</th>
			<th width="60" align="left">状态</th>
			<th width="30" align="left">是否有图</th>
			<th width="120" align="center">操作</th>
            </tr>
	<?php if(is_array($data)){$i =1+($page-1)*$pagesize; foreach($data as $channel){?>   
	<tr>
	<td align="center"><?php echo $channel['id']?></td>
	<td align="left"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<td align="left"><?php echo $channel['asset_id']?></td>
	<td align="left"><?php echo $channel['title']?></td>
	<td align="left"><?php echo columnid2name($channel['column_id'],$channel['ispackage'])?></td>
	<td align="left"><?php echo online_status($channel['online_status']);?></td>
	<td align="left"><a style="color:red"><?php echo videopic($channel['pic'])?></a></td>
	<td align="left">
		<a href="javascript:video_content('<?php echo $channel['asset_id']?>');void(0);">链接</a> |
		<a href="javascript:video_poster('<?php echo $channel['asset_id']?>');void(0);">海报</a> |
		<a href="javascript:video_sub('<?php echo $channel['asset_id']?>');void(0);">字幕</a> |
		<!--
		<a href="javascript:video_content_add('<?php echo $channel['asset_id']?>');void(0);">加链</a> |
		<a href="javascript:video_poster_add('<?php echo $channel['asset_id']?>');void(0);">加图</a> |
		-->
		<?php if($channel['online_status'] != 11) {?>
		<?php    if($channel['online_status'] != 10){?>
		<a style="color:blue" href="javascript:edit('<?php echo $channel['id']?>', '<?php echo safe_replace($channel['title'])?>');void(0);"><?php echo L('edit')?></a> |
		<a style="color:green" href="javascript:dopass(<?php echo $channel['id']?>, '申请审核')">申请审核</a> | 
		<?php    if($channel['online_status'] != 3){?>
		<a style="color:red" title="置为编辑未通过状态" href="javascript:dorefuse(<?php echo $channel['id']?>, '数据有误')">数据有误</a>
		<?php }}}?>
	</td>
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
