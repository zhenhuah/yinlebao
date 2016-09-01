<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=task&a=subeditdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>
		<tr>
		<div class="content-menu ib-a blue line-x">
		<a class="add fb" href="javascript:video_trailer_add('<?php echo $asset_id ?>')"><em>添加预告片</em></a>
		</div>
		</tr>
		<tr>
      <td>
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr>
          	<td width="50">id</td>
          	<td width="50">预告片id</td>
            <td width="50">正片视频id</td>
            <td width="50">清晰度</td>
            <td width="50">画质</td>
            <td width="50">来源</td>
            <td width="150">url</td>
            <td width="50">尺寸</td>
            <td width="50">视频格式</td>
            <td width="50">视频协议</td>
            <td width="50">语言</td>
            <td width="80">操作</td>
          </tr>
		  <?php
		  if(is_array($video_trailer)) {
		  	//print_r($cms_video_poster);
		  	foreach($video_trailer as $key => $value1) {
		  ?>
          <tr>
          	<td><?=$value1['id']?></td>
          	<td><?=$value1['tid']?></td>
            <td><?=$value1['asset_id']?></td>
            <td><?=$value1['quality']?></td>
            <td><?=$value1['aspect']?></td>
            <td><?=$value1['source']?></td>
            <td><?=$value1['play_url']?></td>
            <td><?=$value1['ratio']?></td>
            <td><?=$value1['format']?></td>
            <td><?=$value1['protocol']?></td>
            <td><?php echo language($value1['language'])?></td>
            <td><a href="javascript:video_trailer_edit('<?php echo $value1['id']?>');void(0);">编辑</a>|
			<a href="javascript:video_trailer_delete('<?php echo $value1['id']?>','<?php echo $asset_id ?>');void(0);">删除</a></td>
          </tr>
          <?php }}?>
        </table>
      </td>
    </tr>
		</tbody>
	</table>
<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
	function video_trailer_edit(id){
		location.href ='?m=go3c&c=video&a=traileredit&id='+ id +'&pc_hash='+pc_hash
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
	function video_trailer_add(asset_id){
		location.href ='?m=go3c&c=video&a=traileradd&catid=64&asset_id='+ asset_id +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
	function video_trailer_delete(id,asset_id){
		location.href ='?m=go3c&c=video&a=trailer_delete&catid=64&id='+ id +'&asset_id='+asset_id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
</script>
	</div>
</div>      
</div>
<div class="bk10"></div>
</form>
</body>
</html>
