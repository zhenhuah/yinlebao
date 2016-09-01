<?php include ytemplate('header.tpl');?>
<style>
.table_form tbody td, .table_form tbody th { border:0; }
</style>


<form name="myform" action="?m=go3c&c=importspptv&a=importdetail&id=<?php echo urlencode($video['id']);?>" method="post" id="myform">
<table width="100%"><tbody>
<tr>
<td style="width:50%;">
<table width="100%" class="table_form contentWrap"><tbody>
<tr>
<th style="width:50px;">vid</th>
<td><input type="text" value="<?php if(isset($video['VID'])) echo $video['VID'];?>" style="width:150px;" readonly="readonly" />

</td>
</tr>
<tr>
<th>片名</th>
<td><input type="text" value="<?php if(isset($video['title'])) echo $video['title'];?>" style="width:300px;" name="d[title]" /></td>
</tr>
<tr>
<th>导演</th>
<td><input type="text" value="<?php if(isset($video['director'])) echo $video['director'];?>" style="width:300px;" name="d[director]" /></td>
</tr>
<tr>
<th>演员</th>
<td><input type="text" value="<?php if(isset($video['actor'])) echo $video['actor'];?>" style="width:300px;" name="d[actor]" /></td>
</tr>

<tr>
<th>地区</th>
<td><input type="text" value="<?php if(isset($video['area'])) echo $video['area'];?>" style="width:300px;" name="d[area]" /></td>
</tr>
<tr>
<th>tags</th>
<td><input type="text" value="<?php if(isset($video['tag'])) echo $video['tag'];?>" style="width:300px;" name="d[tag]" /></td>
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
<td>
<?php if(isset($video['pics']) && is_array($video['pics'])) { foreach($video['pics'] as $v) { ?>
<p><?php if(isset($v['pictype']) && $v['pictype']) { ?><?php if(isset($v['pictype'])) echo $v['pictype'];?>:<?php } ?> <input type="text" style="width:220px;" value="<?php if(isset($v['path'])) echo $v['path'];?>" /></p>
<br />
<img src="<?php if(isset($v['path'])) echo $v['path'];?>" style="height:120px;" />
<?php } } ?>
</td>

</tr>
<tr>
<th>播放链接</th>
<td>
<?php if(isset($video['vurls']) && is_array($video['vurls'])) { foreach($video['vurls'] as $v) { ?>
<?php $v['spath']=$v['path']?>
<?php if($v['spath'] == 'segment') { ?>
<?php $v['spath']="?m=go3c&c=importspptv&a=showsegment&id=" .$v['id'] . '&pc_hash=' . $_SESSION['pc_hash']?>
<?php } ?>
<p><input type="checkbox" value="<?php if(isset($v['path'])) echo $v['path'];?>" name="indbvtype[<?php if(isset($v['videotype'])) echo $v['videotype'];?>][<?php if(isset($v['sourcename'])) echo $v['sourcename'];?>]" checked="checked" /><input type="hidden" value="1" name="uscktype" /><a href="<?php if(isset($v['spath'])) echo $v['spath'];?>" target="_blank"><?php if(isset($v['sourcename'])) echo $v['sourcename'];?></a>: <input type="text" style="width:180px;" readonly="readonly" value="<?php if(isset($v['path'])) echo $v['path'];?>" /></p>
<?php } } ?>
</td>
</tr>

<?php if(isset($video['total_episodes']) && $video['total_episodes']) { ?>
<tr>
<th>总集数</th>
<td><?php if(isset($video['total_episodes'])) echo $video['total_episodes'];?></td>
</tr>


<?php if(isset($video['latest_episode_num']) && $video['latest_episode_num']) { ?>
<tr>
<th>已更新集数</th>
<td><?php if(isset($video['latest_episode_num'])) echo $video['latest_episode_num'];?></td>
</tr>
<?php } ?>

<?php } ?>

<?php if(isset($videotypes) && $videotypes) { ?>
<tr>
<th>资源类型</th>
<td><?php if(isset($videotypes) && is_array($videotypes)) { foreach($videotypes as $k => $vs) { ?>
<?php if(isset($vs) && is_array($vs)) { foreach($vs as $ik => $iv) { ?>
<p><input type="checkbox" value="1" name="indbvtype[<?php if(isset($ik)) echo $ik;?>][$iv]" checked="checked" /><input type="hidden" value="1" name="uscktype" /><?php if(isset($k)) echo $k;?> : <?php if(isset($ik)) echo $ik;?></p>
<?php } } ?>
<?php } } ?>
</td>
</tr>
<?php } ?>


<tr>
<td colspan="10" style="text-align:center;">
<?php if(isset($video['total_episodes']) && $video['total_episodes']) { ?>
<input type="button" style="width:82px;height:25px;" href="?m=go3c&c=importspptv&a=showdetaillist&id=<?php echo urlencode($video['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" onclick="window.location.href=this.getAttribute('href')" value="查看分集" />
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