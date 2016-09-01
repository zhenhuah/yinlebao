<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" id="myform" action="?m=go3c&c=video&a=offline_poster_list" method="post" >
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="60" align="center">VID</th>
			<th width='50' align="center">类型</th>		
			<th width='50' align="center">图片</th>
			<th width="80" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($image_info)){$i =1+($page-1)*$pagesize; foreach($image_info as $channel){?>   
	<tr>
	<td align="center"><?php echo $channel['vid']?></td>
	<td align="center"><?php echo $channel['img_type']?></td>
	<td align="center"><a href="<?php echo $channel['img_url']?>" target="_blank"><img style="width:40px; border:solid 1px gray; padding:2px;" src="<?php echo $channel['img_url']?>" /></a></td>
	<td align="center">
		<a href="javascript:deleteit('<?php echo $channel['vid']?>','<?php echo $channel['img_type']?>');void(0);">删除</a>
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
function deleteit(vid,img_type){
	if(confirm("确定删除此图片?")){
		location.href ='?m=go3c&c=video&a=video_image_delete&vid='+encodeURIComponent(vid)+'&img_type='+encodeURIComponent(img_type)+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
}
</script>