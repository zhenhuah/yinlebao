<?php include ytemplate('header.tpl');?>
<style>
.table_form tbody td, .table_form tbody th { border:0; }
</style>

<form name="myform" action="?m=go3c&c=importhdpgk&a=hdpimportdetail&url=<?php echo urlencode($video['url']);?>&t=<?php if(isset($video['type'])) echo $video['type'];?>" method="post" id="myform">
<table width="100%"><tbody>
<tr>
<td style="width:50%;">
<table width="100%" class="table_form contentWrap"><tbody>
<tr>
<th style="width:50px;">vid</th>
<td><input type="text" value="<?php if(isset($video['id'])) echo $video['id'];?>" style="width:150px;" readonly="readonly" />

</td>
</tr>
<tr>
<th>片名</th>
<td><input type="text" value="<?php if(isset($video['title'])) echo $video['title'];?>" style="width:300px;" name="d[title]" /></td>
</tr>
<tr>
<th>导演</th>
<td><input type="text" value="<?php if(isset($video['directors'])) echo $video['directors'];?>" style="width:300px;" name="d[directors]" /></td>
</tr>
<tr>
<th>演员</th>
<td><input type="text" value="<?php if(isset($video['actors'])) echo $video['actors'];?>" style="width:300px;" name="d[actors]" /></td>
</tr>

<tr>
<th>地区</th>
<td><input type="text" value="<?php if(isset($video['area'])) echo $video['area'];?>" style="width:300px;" name="d[area]" /></td>
</tr>
<tr>
<th>tags</th>
<td><input type="text" value="<?php if(isset($video['tags'])) echo $video['tags'];?>" style="width:300px;" name="d[tags]" /></td>
</tr>
<tr>
<th>剧情</th>
<td><textarea style="width:300px;height:110px;" name="d[description]"><?php if(isset($video['description'])) echo $video['description'];?></textarea></td>
</tr>
</tbody></table>
</td>

<td>
<table width="100%" class="table_form"><tbody>
<tr>
<th style="width:100px;">图片</th>
<td><input type="text" style="width:220px;" value="<?php if(isset($video['image'])) echo $video['image'];?>" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td><img src="<?php if(isset($video['image'])) echo $video['image'];?>" style="height:120px;" /></td>
</tr>
<tr>
<th>播放链接</th>
<td>
<?php if(isset($video['urls']) && is_array($video['urls'])) { foreach($video['urls'] as $v) { ?>
<p><?php if(isset($v['stagname'])) echo $v['stagname'];?>: <input type="text" style="width:220px;" value="<?php if(isset($v['url'])) echo $v['url'];?>" /></p>
<?php } } ?>
</td>
</tr>

<tr>
<th>集数</th>
<td><?php echo count($video['eps']);?></td>
</tr>

<?php if(isset($video['count']) && $video['count']) { ?>
<tr>
<th>总集数</th>
<td><?php if(isset($video['count'])) echo $video['count'];?></td>
</tr>


<?php } ?>
<tr>
<td colspan="10" style="text-align:center;">
<?php if(isset($video['count']) && $video['count']) { ?>
<input type="button" style="width:82px;height:25px;" href="?m=go3c&c=importhdpgk&a=hdpdetaillist&url=<?php echo urlencode($video['link']);?>&t=<?php if(isset($video['type'])) echo $video['type'];?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" onclick="window.location.href=this.getAttribute('href')" value="查看分集" />
&nbsp;&nbsp;
<?php } ?>

<input type="hidden" name="showok" value="1" />
<input type="submit" style="width:62px;height:25px;" value="导入" />
&nbsp;&nbsp;
<input type="button" style="width:62px;height:25px;" onclick="window.top.art.dialog({id:'edit'}).close();" value="返回" />
</td>
</tr>


</tbody></table>
</td>
</tr>
</tbody></table>

    <div class="bk15"></div>
 	
<input name="pc_hash" type="hidden" value="<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">


</form>