<?php include ytemplate('header.tpl');?>


<div class="table-list">
<table width="100%" cellspacing="0">
   <thead><tr>
<th style="width:150px;" align="center">VID</th>
<th align="center">分集名</th>
<th align="center" style="width:100px;">第几集</th>
<th style="width:100px;" align="center">海报</th>
<th style="width:150px;" align="center">操作</th>
    </tr></thead>
    <tbody>
<?php if(isset($video['eps']) && is_array($video['eps'])) { foreach($video['eps'] as $k => $v) { ?>
<tr>
<td align="center"><?php if(isset($v['id'])) echo $v['id'];?></td>
<td align="left"><?php if(isset($v['title'])) echo $v['title'];?></td>
<td align="center"><?php if(isset($v['seq'])) echo $v['seq'];?></td>
<td align="center"><img src="<?php if(isset($v['image'])) echo $v['image'];?>" style="width:90px;" /></td>
<td align="center">
<?php if(isset($indb_list[$v['id']]) && $indb_list[$v['id']]) { ?>
<a onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importpptv&a=pptvimportdetaill&id=<?php echo urlencode($video['id']);?>&ftype=<?php if(isset($video['type'])) echo $video['type'];?>&vid[]=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">已导入</a>
<?php } else { ?>
<a onclick="doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importpptv&a=pptvimportdetaill&id=<?php echo urlencode($video['id']);?>&ftype=<?php if(isset($video['type'])) echo $video['type'];?>&vid[]=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">导入</a>
<?php } ?>
</td>
</tr>
<?php } } ?>
</tbody>
</table>

</div>

<script>
function doImportPPTVDetaill(obj){
obj.innerHTML = '正在导入' ;
$.post(obj.getAttribute('href'),'',function(t){
if(t == 'ok'){
obj.innerHTML = '成功' ;
}
});
return false ;
}
</script>