<?php include ytemplate('header.tpl');?>

<div style="padding:10px;">

<div class="explain-col">
<form name="myfrom" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="importhdpls" name="c">
<input type="hidden" value="hdplist" name="a">


  类型：<select name="w[type]" id="i_w_type" onchange="doChangeType(this.value);">
<option value="">请选择</option>
<?php if(isset($base_data['type']) && is_array($base_data['type'])) { foreach($base_data['type'] as $k => $v) { ?>
<option value="<?php if(isset($v['name'])) echo $v['name'];?>"<?php if($w['type'] == $v['name']) { ?> selected="selected"<?php } ?>><?php if(isset($v['name'])) echo $v['name'];?></option>
<?php } } ?>
</select>  &nbsp; 

类别：
<span id="i_w_cate_in"></span>





<input type="submit" value="查询" class="button" name="search"> &nbsp;&nbsp;	

<input name="pc_hash" type="hidden" value="<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">

</form> 
</div>
<br />
<div style="display:none;" id="i_w_cate_in_display_none">
<?php if(isset($base_data['cate']) && is_array($base_data['cate'])) { foreach($base_data['cate'] as $k => $vs) { ?>
<select id="i_w_cate_<?php if(isset($vs['t']['name'])) echo $vs['t']['name'];?>" name="url">
<?php if(isset($vs['l']) && is_array($vs['l'])) { foreach($vs['l'] as $k => $v) { ?>
<option value="<?php if(isset($v['url'])) echo $v['url'];?>"><?php if(isset($v['name'])) echo $v['name'];?></option>
<?php } } ?>
</select>  &nbsp; 
<?php } } ?>
</div>
<script>
function i$(i){ return document.getElementById(i); }
function doChangeType(vv){
var dds = i$('i_w_cate_in').getElementsByTagName('select') ;
for(var i=0;i<dds.length;i++){
i$('i_w_cate_in_display_none').appendChild(dds[i]) ;
}
if(vv == ''){
return ;
}
var t = i$('i_w_cate_'+vv)
if(t) i$('i_w_cate_in').appendChild(t) ;
}
doChangeType('<?php if(isset($w['type'])) echo $w['type'];?>') ;
if(i$('i_w_cate_'+'<?php if(isset($w['type'])) echo $w['type'];?>')){
i$('i_w_cate_'+'<?php if(isset($w['type'])) echo $w['type'];?>').value = '<?php if(isset($apiurl)) echo $apiurl;?>' ;
}
</script>

<div class="table-list">
<table width="100%" cellspacing="0">
   <thead><tr>
<th style="width:150px;" align="center">VID</th>
<th style="width:150px;" align="center">片名</th>
<th align="center" style="width:40px;">图片</th>
<th align="center" style="width:40px;">类型</th>
<th style="width:230px;" align="center">操作</th>
<th style="width:60px;" align="center">导入</th>
    </tr></thead>
    <tbody>
<?php if(isset($list) && is_array($list)) { foreach($list as $k => $v) { ?>
<tr>
<td align="center"><?php if(isset($v['id'])) echo $v['id'];?></td>
<td align="center"><a href="<?php if(isset($v['url'])) echo $v['url'];?>" target="_blank"><?php if(isset($v['title'])) echo $v['title'];?></a></td>
<td align="center"><img src="<?php if(isset($v['image'])) echo $v['image'];?>" style="width:40px;" /></td>
<td align="center"><?php echo hdp_type($v['type']);?></td>
<td align="center">
<a href="?m=go3c&c=importhdpls&a=hdpdetail&url=<?php echo urlencode($v['url']);?>&t=<?php if(isset($w['type'])) echo $w['type'];?>" onclick="popShowDetail(this.href);return false;">详情</a>
&nbsp;&nbsp;
</td>
<td align="center">
<?php if(isset($indb_list[$v['id']]) && $indb_list[$v['id']]) { ?>
<a href="?m=go3c&c=importhdpls&a=hdpimportdetail&url=<?php echo urlencode($v['url']);?>&t=<?php if(isset($w['type'])) echo $w['type'];?>" onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this,'?m=go3c&c=importhdpls&a=hdpdetaillist&url=<?php echo urlencode($v['url']);?>&vid=all&t=<?php if(isset($w['type'])) echo $w['type'];?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>');return false;">已导入</a>
<a href="?m=go3c&c=importhdpls&a=hdpimportdetail&url=<?php echo urlencode($v['url']);?>&t=<?php if(isset($w['type'])) echo $w['type'];?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" onclick="doImportHDPDetaill(this);return false;">更新</a>	
<?php } else { ?>
<a href="?m=go3c&c=importhdpls&a=hdpimportdetail&url=<?php echo urlencode($v['url']);?>&t=<?php if(isset($w['type'])) echo $w['type'];?>" onclick="doImportPPTVDetaill(this,'?m=go3c&c=importhdpls&a=hdpdetaillist&url=<?php echo urlencode($v['url']);?>&vid=all&t=<?php if(isset($w['type'])) echo $w['type'];?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>');return false;">导入</a>
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


function doImportPPTVDetaill(obj,u){
obj.innerHTML = '正在导入' ;
$.post(obj.getAttribute('href'),'',function(t){
if(t == 'ok'){
obj.innerHTML = '成功' ;
return ;
}

if(t == 'dourl'){
popShowDetail(u + '&doimport=1') ;
window.top.NOWDOOBJ = obj ;
return ;
}

obj.innerHTML = '失败' ;
});
return false ;
}
function doImportHDPDetaill(obj){
obj.innerHTML = '正在执行' ;
$.post(obj.getAttribute('href'),'',function(t){
if(t == 'ok'){
obj.innerHTML = '成功' ;
}
});
return false ;
}
</script>

</div>