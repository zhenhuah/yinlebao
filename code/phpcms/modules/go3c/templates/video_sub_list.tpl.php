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
		<a class="add fb" href="javascript:video_sub_add('<?php echo $asset_id ?>')"><em><?php echo L('add_sub');?></em></a>
		</div>
		</tr>
		<tr>
      <th width="80"><?php echo L('v_sub');?></th>
      <td>
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr>
          	<td width="50">id</td>
            <td width="50"><?php echo L('v_id');?></td>
            <td width="150"><?php echo L('language');?></td>
            <td width="50"><?php echo L('pr_sources');?></td>
            <td width="80"><?php echo L('pr_time');?></td>
            <td><?php echo L('sub_file');?></td>
            <td width="80"><?php echo L('operation');?></td>
          </tr>
		  <?php
		  if(is_array($video_subtitle)) {
		  	//print_r($cms_video_poster);
		  	foreach($video_subtitle as $key => $value1) {
		  ?>
          <tr>
          	<td><?=$value1['id']?></td>
            <td><?=$value1['asset_id']?></td>
            <td><?=$value1['language']?></td>
            <td><?=$value1['source']?></td>
            <td><?=$value1['run_time']?></td>
            <td style="width:300px; word-break: break-all; word-wrap:break-word;"><a href="<?='video/subtitle/'.$value1['url']?>" target="_blank"><?=$value1['url']?></a></td>
            <td><a href="javascript:video_sub_edit('<?php echo $value1['id']?>');void(0);"><?php echo L('edit');?></a>|
			<a href="javascript:video_sub_delete('<?php echo $value1['id']?>','<?php echo $asset_id ?>');void(0);"><?php echo L('del');?></a></td>
          </tr>
          <?php }}?>
        </table>
      </td>
    </tr>
		</tbody>
	</table>
<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
	function video_sub_edit(id){
		location.href ='?m=go3c&c=task&a=subedit&id='+ id +'&pc_hash='+pc_hash
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
	function video_sub_add(asset_id){
		location.href ='?m=go3c&c=task&a=subadd&catid=64&asset_id='+ asset_id +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
	function video_sub_delete(id,asset_id){
		location.href ='?m=go3c&c=task&a=sub_delete&catid=64&id='+ id +'&asset_id='+asset_id+'&pc_hash='+pc_hash
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
