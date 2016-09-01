<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew('<?php echo $asset_id;?>')" ><em><?php echo L('vadd');?></em></a>
</div>
<form name="myform_search" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="video" name="c">
<input type="hidden" value="video_content_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=video&a=online"><?php echo L('all_data');?></a>&nbsp;
			VID：<input name="asset_id" type="text" value="<?php if(isset($asset_id)) echo $asset_id;?>" class="input-text" />&nbsp;
			<?php echo L('each page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
<form name="myform" id="myform" action="?m=go3c&c=video&a=listorder" method="post" >
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center"><?php echo L('number');?></th>
			<th width="88" align="center"><?php echo L('import_time');?></th>
			<th width="60" align="center">VID</th>
			<th width='50' align="center"><?php echo L('vsource');?></th>
			<th width='50' align="center"><?php echo L('vclarity');?></th>
			<th width='50' align="center"><?php echo L('vlink');?></th>
			<th width='50' align="center"><?php echo L('weights');?></th>
			<th width="80" align="center"><?php echo L('operation');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){$i =1+($page-1)*$pagesize; foreach($data as $channel){?>   
	<tr>
	<td align="center"><?php echo $channel['id']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<td align="center"><?php echo $channel['asset_id']?></td>
	<td align="center"><?php echo $channel['source']?></td>
	<td align="center"><?php echo $channel['clarity']?></td>
	<td align="left" style="width:300px; word-break: break-all; word-wrap:break-word;">
	  <a href="?m=go3c&c=mediaplay&a=mediaplay_view&assetid=<?=$channel[asset_id]?>&id=<?=$channel['id']?>" target="_blank"><?php echo $channel['path']?></a>
	</td>
	<td align="center"><input name='listorders[<?php echo $channel['id'];?>]' type='text' size='3' value='<?php echo $channel['listorder'];?>' class='input-text-c'></td>
	<td align="left">
		<a href="javascript:edit('<?php echo $channel['id']?>');void(0);"><?php echo L('vedit');?></a> |
		<a href="javascript:deleteit('<?php echo $channel['id']?>');void(0);"><?php echo L('del');?></a>
	</td>
	</tr>
	<?php }} ?>
	<tr>
	<td colspan="20"><input type="button" class="button" value="<?php echo L('listorder');?>" onclick="myform.action='?m=go3c&c=video&a=listorder&steps=<?php echo $steps;?>';myform.submit();"/>
	</td>
	</tr>
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
function addnew(asset_id){
	location.href ='?m=content&c=content&a=add&catid=64&asset_id='+ asset_id +'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function edit(id) {
    location.href ='?m=content&c=content&a=edit&catid=64&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function deleteit(id){
	if(confirm("确定删除此播放链接?")){
		location.href ='?m=go3c&c=video&a=video_content_delete&id='+id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
}
</script>