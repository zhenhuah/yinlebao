<?php include ytemplate('header.tpl');?>

<?php if(request('doimport','GET')) { ?>
<div class="explain-col" style="text-align:center;">
资源正在更新，请不要关闭窗口！
</div>
<?php } ?>


<div class="table-list" id="i_table">
<table width="100%" cellspacing="0">
   <thead><tr>
<th style="width:150px;" align="center">VID</th>
<th align="center">分集名</th>
<th align="center" style="width:150px;">资源类型</th>
<th align="center" style="width:100px;">第几集</th>
<?php if(isset($v['image']) && $v['image']) { ?>
<th style="width:100px;" align="center">海报</th>
<?php } ?>

<th style="width:150px;" align="center">操作</th>
    </tr></thead>
    <tbody>
<?php if(isset($video['eps']) && is_array($video['eps'])) { foreach($video['eps'] as $k => $v) { ?>
<tr>
<td align="center"><?php if(isset($v['VID'])) echo $v['VID'];?></td>
<td align="left">
<?php if(isset($v['vurls']) && is_array($v['vurls'])) { foreach($v['vurls'] as $iik => $iiv) { ?>
<?php if(isset($iik) && $iik) { ?><br /><?php } ?>
<a href="<?php if(isset($iiv['apiurl'])) echo $iiv['apiurl'];?>" target="_blank"><?php if(isset($v['title'])) echo $v['title'];?></a>
<?php } } ?>
</td>
<td align="center"><?php if(isset($v['vurls']) && is_array($v['vurls'])) { foreach($v['vurls'] as $iik => $iiv) { ?>
<?php if($iiv['path'] == 'segment') { ?>
<?php $iiv['path']="?m=go3c&c=importssoo&a=showsegment&id=" .$iiv['id'] . '&pc_hash=' . $_SESSION['pc_hash']?>
<?php } ?>

<p><a href="<?php if(isset($iiv['path'])) echo $iiv['path'];?>" target="_blank" id="i_path_a_<?php if(isset($iiv['id'])) echo $iiv['id'];?>"><?php if(isset($iiv['sourcename'])) echo $iiv['sourcename'];?> : <?php if(isset($iiv['videotype'])) echo $iiv['videotype'];?></a></p>
<?php } } ?>
</td>
<td align="center"><?php if(isset($v['episode_number'])) echo $v['episode_number'];?></td>
<?php if(isset($v['image']) && $v['image']) { ?>
<td align="center"><img src="<?php if(isset($v['image'])) echo $v['image'];?>" style="width:90px;" /></td>
<?php } ?>



<td align="center">
<?php if(isset($indb_list[$v['VID']]) && $indb_list[$v['VID']]) { ?>
<a onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importssoo&a=importdetaill&id=<?php echo urlencode($video['id']);?>&vid[]=<?php echo urlencode($v['VID']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">已导入</a>
<?php } else { ?>
<a onclick="doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importssoo&a=importdetaill&id=<?php echo urlencode($video['id']);?>&vid[]=<?php echo urlencode($v['VID']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">导入</a>
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



<script>
function doUPVURL(obj){
if(window.doScrollTopBody) $("html,body").animate({scrollTop:$(obj).offset().top},0);
obj.innerHTML = '执行中' ;
$.post(obj.getAttribute('href'),'',function(t){
if(t.indexOf('ok') == 0){
obj.innerHTML = '成功' ;
$('#' + obj.id.replace('i_path_up_a_','i_path_a_')).innerHTML = t.substr(2) ;
if(window.doPush){
if(window.doPush.length > 0){
doUPVURL(window.doPush.shift()) ;
}else{
doShowOk() ;
}
}


}else{
obj.innerHTML = '失败' ;
}
});
return false ;
}

function doShowOk(){
if(window.top.NOWDOOBJ) window.top.NOWDOOBJ.innerHTML = '成功' ;
if(window.top.downUPAdLFunc) window.top.downUPAdLFunc() ;
window.location.href = '?m=go3c&c=importssoo&a=showOK&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>' ;
}

<?php if(request('doimport','GET')) { ?>
window.doScrollTopBody = true ;
setTimeout(function(){
window.doPush = [] ;
var dds = document.getElementById('i_table').getElementsByTagName('a') ;
for(var i=0;i<dds.length;i++){
if(dds[i].className != 'pathupa') continue ;
window.doPush.push(dds[i]) ;
}

if(window.doPush.length > 0){
var obj = window.doPush.shift() ;
doUPVURL(obj) ;
}else{
doShowOk() ;
}
},1000) ;
<?php } ?>
</script>