<?php include ytemplate('header.tpl');?>

<div style="padding:10px;">

<div class="explain-col">
<form name="myfrom" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="importpptv" name="c">
<input type="hidden" value="pptvlist" name="a">

  类型：<select name="ftype" id="ftype">
<option value="">请选择</option>
<?php if(isset($ftypes) && is_array($ftypes)) { foreach($ftypes as $k => $v) { ?>
<option value="<?php if(isset($k)) echo $k;?>"<?php if($ftype == $k) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp; 

<?php $spages=array(10,20,50,100)?>
 每页显示：<select name="page_size" id="page_size">
<?php if(isset($spages) && is_array($spages)) { foreach($spages as $v) { ?>
<option value="<?php if(isset($v)) echo $v;?>"<?php if($page_size == $v) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp;

<input type="submit" value="查询" class="button" name="search"> &nbsp;&nbsp;	

<input name="pc_hash" type="hidden" value="<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">

</form> 
</div>
<br />

<div class="table-list">
<table width="100%" cellspacing="0">
   <thead><tr>
<th style="width:150px;" align="center">VID</th>
<th align="center">片名</th>
<th align="center" style="width:100px;">类别</th>
<th style="width:100px;" align="center">状态</th>
<th style="width:230px;" align="center">操作</th>
<th style="width:60px;" align="center">导入</th>
    </tr></thead>
    <tbody>
<?php $Sstatus=array('online'=>'在线','offline'=>'下线')?>
<?php if(isset($list) && is_array($list)) { foreach($list as $k => $v) { ?>
<tr>
<td align="center"><?php if(isset($v['id'])) echo $v['id'];?></td>
<td align="left"><?php if(isset($v['name'])) echo $v['name'];?></td>
<td align="center"><?php if(isset($ftypes[$v['type']])) echo $ftypes[$v['type']];?></td>
<td align="center"><?php if(isset($Sstatus[$v['status']])) echo $Sstatus[$v['status']];?></td>
<td align="center">
<a href="?m=go3c&c=importpptv&a=pptvdetail&id=<?php echo urlencode($v['id']);?>&ftype=<?php if(isset($v['type'])) echo $v['type'];?>" onclick="popShowDetail(this.href);return false;">详情</a>
&nbsp;&nbsp;

<?php if(in_array($v['type'],array('tv','cartoon','show'))) { ?>
<a href="?m=go3c&c=importpptv&a=pptvdetaillist&id=<?php echo urlencode($v['id']);?>&ftype=<?php if(isset($v['type'])) echo $v['type'];?>" onclick="popShowDetail(this.href);return false;">查看分集</a>
&nbsp;&nbsp;
<a href="?m=go3c&c=importpptv&a=pptvimportdetaill&id=<?php echo urlencode($v['id']);?>&ftype=<?php if(isset($v['type'])) echo $v['type'];?>&vid=all" onclick="doImportPPTVDetaill(this);return false;">导入新增分集</a>
&nbsp;&nbsp;
<?php } ?>
</td>
<td align="center">

<?php if(isset($indb_list[$v['id']]) && $indb_list[$v['id']]) { ?>
<a href="?m=go3c&c=importpptv&a=pptvimportdetail&id=<?php echo urlencode($v['id']);?>&ftype=<?php if(isset($v['type'])) echo $v['type'];?>" onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;">已导入</a>
<?php } else { ?>
<a href="?m=go3c&c=importpptv&a=pptvimportdetail&id=<?php echo urlencode($v['id']);?>&ftype=<?php if(isset($v['type'])) echo $v['type'];?>" onclick="doImportPPTVDetaill(this);return false;">导入</a>
<?php } ?>

</td>
</tr>
<?php } } ?>
<?php if(isset($pager) && $pager) { ?>
<tr>
<td colspan="20" style="text-align:center;padding:10px;">
<?php if(isset($pager)) echo $pager;?>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>

<script>
function popShowDetail(url){
window.top.art.dialog({title:'视频资源详情', id:'edit', iframe:url,width:'800px',height:'450px'},'', function(){window.top.art.dialog({id:'edit'}).close()});
}


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

</div>