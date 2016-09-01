<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad_10">
<div class="common-form">
<form id="myform" method="get" action="" name="myform">
  <input type="hidden" value="go3c" name="m">
  <input type="hidden" value="position" name="c">
  <?php if(!$pvid){ ?>
  <input type="hidden" value="position_video_add" name="a">
  <?php }else{ ?>
  <input type="hidden" value="position_video_edit" name="a">
  <?php }?>
  <input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
  <input type="hidden" name="type_id" id="type_id" value="<?php echo $type_id;?>" />
  <input type="hidden" name="posid" id="posid" value="<?php echo $posid;?>" />
  <input type="hidden" name="vid" id="vid" value="<?php echo $video['id'];?>" />
  <input type="hidden" name="spid" id="spid" value="<?php echo $video['spid'];?>" />
  <input type="hidden" name="asset_id" id="asset_id" value="<?php echo $video['asset_id'];?>" />
  <input type="hidden" name="pvid" id="vid" value="<?php echo $pvid;?>" />
<table width="100%" class="table_form">

<tbody><tr>
<td>终端类型</td> 
<td>
  <?php echo $go3c_term_type['title'];?>
</td>
</tr>
<!--
<tr>
<td>推荐位类型</td> 
<td>
  <?php echo $go3c_position_type['title'];?>
</td>
</tr>
-->
<tr>
<td>推荐位名称</td> 
<td>
  <?php echo $posname;?>
</td>
</tr>
<tr>
<td>推荐位视频</td> 
<?php if($video==null){?>
<td><input type="button" class="button" onclick="videolist(<?php echo $term_id;?>,<?php echo $posid;?>)" value="进入视频列表选择"><div id="titleTip" class="onShow">请从视频列表中选择推荐视频</div></td>
<?php }else{ ?>
<td><input type="button" class="button"  onclick="videolist(<?php echo $term_id;?>,<?php echo $posid;?>)" value="进入视频列表更改"></td>
<?php } ?>
</tr>

<tr>
<td>视频名称</td> 
<td>
  <?php  if($video) echo $video[title];?>
</td>
</tr>
<tr>
<td>视频ID</td> 
<td>
  <?php  if($video) echo $video[asset_id];?>
</td>
</tr>
<tr>
<td>SPID</td> 
<td>
  <?php  if($video) echo $video[spid];?>
</td>
</tr>
<tr>
<tr>
<td width="100">推荐位标题</td> 
<td><input type="text" size="40" id="title" value="<?php echo $position_video[title] ? $position_video[title] : $video[title]?>" class="input-text" name="title"><div id="titleTip" class="onShow">请输入推荐位标题</div></td>
</tr>

<tr>
<td>描述</td> 
<td>
<textarea style="height:50px;width:300px;" class="inputtext" id="description" cols="20" rows="2" name="description"><?php echo $position_video[description] ? $position_video[description] : ''?></textarea>
</td>
</tr>

<td>推荐位图片</td> 
<?php if(!is_array($poster)){?>
<td><div id="titleTip" class="onShow">先选择推荐视频，再选择图片</div></td>
<?php }else{ ?>
<td>
<div class="table-list">
   <table width="100%" cellspacing="0" style="margin-top:20px;" class="table_form">
      <tbody>
      <tr style="background:#DDDDDD;">
        <td width="50">type</td>
        <td width="150">用途</td>
        <td width="50">格式</td>
        <td width="80">尺寸</td>
        <td width="80">选中</td>
        <td>预览</td>
      </tr>
	  <?php
      if(is_array($poster)) {
        foreach($poster as $key => $value) {
      ?>
          <tr>
            <td><?=$value['type']?></td>
            <td><?=$poster_type_array[$value['type']]['description']?></td>
            <td><?=$poster_type_array[$value['type']]['file_format']?></td>
            <td><?=$poster_type_array[$value['type']]['resolution_ratio']?></td>
            <td><input type="radio" name="img_info" value="<?=$value['type'];?>||<?=$value['path'];?>" <?php if($position_video['img_type'] == $value['type']) echo 'checked="checked"'; ?> /></td>
            <td><a href="<?=$value['path']?>" target="_blank"><?=$value['path']?></a></td>
          </tr>
      <?php }}?>
    </tbody>
  </table>
</div>
</td>
<?php } ?>
</tr>

</tbody></table>
    <div class="bk15"></div>
    <input type="button" id="dosubmit" class="button" value="保存" name="dosubmit" onclick="checkandsave()">
    <input type="button" class="button" value="取消" onclick="history.go(-1)">
    <input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
</form>
</div>
</div>
</body>
<script type="text/javascript">
function videolist(termid,posid){
	location.href = '?m=go3c&c=position&a=position_item_list&pc_hash='+pc_hash+'&term_id='+termid+'&posid='+posid;
}
function checkandsave(){
	if($("input[id='title']").val().length == 0){
		alert("推荐位标题不能为空");
		return;	
	}
	if($('input:radio[name="img_info"]:checked').val()==null){
		alert("推荐图片必须选择");
		return;	
	}
	document.myform.submit();
}
</script>
</html>
