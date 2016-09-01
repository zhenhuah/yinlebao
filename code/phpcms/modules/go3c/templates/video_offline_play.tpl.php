<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" id="myform" action="?m=go3c&c=video&a=listorder" method="post" >
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="60" align="center">VID</th>
			<th width='50' align="center">清晰度</th>
			<th width='50' align="center">视频来源</th>			
			<th width='50' align="center">链接</th>
			<th width='50' align="center">尺寸比例</th>
			<th width='50' align="center">格式</th>
			<th width="80" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($play_info)){$i =1+($page-1)*$pagesize; foreach($play_info as $channel){?>   
	<tr>
	<td align="center"><?php echo $channel['vid']?></td>
	<td align="center"><?php echo $channel['quality']?></td>
	<td align="center"><?php echo $channel['source']?></td>
	<td align="left" style="width:300px; word-break: break-all; word-wrap:break-word;">
	  <a href="?m=go3c&c=mediaplay&a=mediaplay_view&vid=<?=$channel[vid]?>&play_url=<?=$channel['play_url']?>" target="_blank"><?php echo $channel['play_url']?></a>
	</td>
	<td align="center"><?php echo $channel['ratio']?></td>
	<td align="center"><?php echo $channel['format']?></td>
	<td align="left">
		<a href="javascript:deleteit('<?php echo $channel['vid']?>','<?php echo $channel['play_url']?>');void(0);">删除</a>
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
function deleteit(vid,play_url){
	if(confirm("确定删除此播放链接?")){
		location.href ='?m=go3c&c=video&a=video_play_delete&vid='+encodeURIComponent(vid)+'&play_url='+encodeURIComponent(play_url)+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
}
</script>