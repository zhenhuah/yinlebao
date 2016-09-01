<?php include ytemplate('header.tpl');?>

<div style="padding:10px;">

<div class="explain-col">
<form name="myfrom" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="importsso" name="c">
<input type="hidden" value="showlist" name="a">

 关键词：<input type="text" name="w[lk_title]" value="<?php if(isset($w['lk_title'])) echo $w['lk_title'];?>" style="width:80px;" />

  <?php if(!empty($aconf['vcolumn'])) { ?>
  <select name="w[vcolumn]" id="i_w_vcolumn" onchange="doChangeType(this.value);">
<option value="">不限</option>
<?php if(isset($aconf['vcolumn']) && is_array($aconf['vcolumn'])) { foreach($aconf['vcolumn'] as $v) { ?>
<option value="<?php if(isset($v)) echo $v;?>"<?php if(!empty($w['vcolumn']) && $w['vcolumn'] == $v) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp; 

<span id="i_w_cate_in"></span>



视频资源类别：
<?php $videotypes=array('flv'=>'flv','m3u8'=>'m3u8','mp4'=>'mp4','segment'=>'多段','hdpfans'=>'跳转','other'=>'其他','none'=>'无')?>
<select name="w[vt]" id="i_vt">
<option value="">不限</option>
<?php if(isset($videotypes) && is_array($videotypes)) { foreach($videotypes as $k => $v) { ?>
<option value="<?php if(isset($k)) echo $k;?>"<?php if(!empty($w['vt']) && $w['vt'] == $k) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp;


  <?php } ?>

<?php if(!empty($aconf['sourcenames'])) { ?>
来源网站：<select name="w[sname]" id="i_w_sname">
<option value="">不限</option>
<?php if(isset($aconf['sourcenames']) && is_array($aconf['sourcenames'])) { foreach($aconf['sourcenames'] as $v) { ?>
<?php if(empty($v)) $v='未知'?>
<?php $k=$v?>

<option value="<?php if(isset($k)) echo $k;?>"<?php if(!empty($w['sname']) && $w['sname'] == $k) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp; 

<?php } ?>

<?php if(!empty($aconf['area'])) { ?>
地区：<select name="w[area]" id="i_w_area">
<option value="">不限</option>
<?php if(isset($aconf['area']) && is_array($aconf['area'])) { foreach($aconf['area'] as $v) { ?>
<option value="<?php if(isset($v)) echo $v;?>"<?php if(!empty($w['area']) && $w['area'] == $v) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp; 
<?php } ?>

<?php if(!empty($aconf['year'])) { ?>
年代：<select name="w[year]" id="i_w_year">
<option value="">不限</option>
<?php if(isset($aconf['year']) && is_array($aconf['year'])) { foreach($aconf['year'] as $v) { ?>
<option value="<?php if(isset($v)) echo $v;?>"<?php if(!empty($w['year']) && $w['year'] == $v) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp; 
<?php } ?>
<?php if(!empty($aconf['staut'])) { ?>
是否导入:<select name="w[staut]" id="i_w_staut">
<option value="">不限</option>
<option value="1" <?php if(!empty($w['staut']) && $w['staut'] == 1) { ?> selected="selected"<?php } ?>>已导入</option>
<option value="0" <?php if(!empty($w['staut']) && $w['staut'] == 0) { ?> selected="selected"<?php } ?>>未导入</option>
</select>  &nbsp;
<?php } ?>		
<?php $spages=array(10,20,30,50,100)?>
 每页显示：<select name="ps" id="i_w_ps">
<?php if(isset($spages) && is_array($spages)) { foreach($spages as $v) { ?>
<option value="<?php if(isset($v)) echo $v;?>"<?php if(!empty($page_size) && $page_size == $v) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp;



<input type="submit" value="查询" class="button" name="search"> &nbsp;&nbsp;	

<input name="pc_hash" type="hidden" value="<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">

</form> 
</div>
<br />

<div style="display:none;" id="i_w_cate_in_display_none">

<?php if(isset($aconf['cate']) && is_array($aconf['cate'])) { foreach($aconf['cate'] as $k => $vs) { ?>
<span id="i_w_cate_<?php if(isset($k)) echo $k;?>_span">

类别：
<select id="i_w_cate_<?php if(isset($k)) echo $k;?>" name="w[vcate]">
<option value="">不限</option>
<?php if(isset($vs) && is_array($vs)) { foreach($vs as $v) { ?>
<option value="<?php if(isset($v)) echo $v;?>"><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp; 


</span>
<?php } } ?>

</div>
<script>
function i$(i){ return document.getElementById(i); }
function doChangeType(vv){
var dds = i$('i_w_cate_in').getElementsByTagName('span') ;
for(var i=0;i<dds.length;i++){
i$('i_w_cate_in_display_none').appendChild(dds[i]) ;
}

var t = i$('i_w_cate_'+vv+'_span')
if(t) i$('i_w_cate_in').appendChild(t) ;
}
doChangeType('<?php if(isset($w['vcolumn'])) echo $w['vcolumn'];?>') ;
if(i$('i_w_cate_'+'<?php if(isset($w['vcolumn'])) echo $w['vcolumn'];?>')){
i$('i_w_cate_'+'<?php if(isset($w['vcolumn'])) echo $w['vcolumn'];?>').value = '<?php if(isset($w['vcate'])) echo $w['vcate'];?>' ;
}

function clickCKB(a){
$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}


</script>

<div class="table-list">
<table width="100%" cellspacing="0" id="i_table_list_1">
   <thead><tr>
<th style="width:50px;" align="center">全选<input type="checkbox" onclick="clickCKB(this);"  /></th>
<th style="width:150px;" align="center">VID</th>
<th align="center">片名</th>
<th align="center" style="width:100px;">图片</th>
<th align="center" style="width:100px;">类别</th>
<th align="center" style="width:100px;">分类</th>
<?php if(!empty($aconf['area'])) { ?>
<th style="width:100px;" align="center">地区</th>
<?php } ?>
<?php if(!empty($aconf['year'])) { ?>
<th style="width:100px;" align="center">年代</th>
<?php } ?>
<th style="width:230px;" align="center">操作</th>
<th style="width:130px;" align="center">操作</th>
    </tr></thead>
    <tbody>
<?php if(isset($list) && is_array($list)) { foreach($list as $v) { ?>
<?php $v['image']=$pics[$v['id']]['path']?>
<tr id="i_import_tr_<?php if(isset($v['id'])) echo $v['id'];?>">
<td align="center"><input type="checkbox" value="<?php if(isset($v['id'])) echo $v['id'];?>"  /></td>
<td align="center"><?php if(isset($v['VID'])) echo $v['VID'];?></td>
<td align="left"><a href="<?php if(isset($v['apiurl'])) echo $v['apiurl'];?>" target="_blank"><?php if(isset($v['title'])) echo $v['title'];?></a></td>
<td align="center"><img src="<?php if(isset($v['image'])) echo $v['image'];?>" style="width:90px;" /></td>
<td align="center"><?php if(isset($v['vcolumn'])) echo $v['vcolumn'];?></td>
<td align="center"><?php if(isset($v['vcate'])) echo $v['vcate'];?></td>
<?php if(!empty($aconf['area'])) { ?>
<td align="center"><?php if(isset($v['area'])) echo $v['area'];?></td>
<?php } ?>
<?php if(!empty($aconf['year'])) { ?>
<td align="center"><?php if(isset($v['year'])) echo $v['year'];?></td>
<?php } ?>
<td align="center">
<a href="?m=go3c&c=importsso&a=showdetail&id=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" onclick="popShowDetail(this.href);return false;">详情</a>
&nbsp;&nbsp;
<?php if($v['vcolumn'] != '电影') { ?>
<a href="?m=go3c&c=importsso&a=showdetaillist&id=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" onclick="popShowDetail(this.href);return false;">查看分集</a>
&nbsp;&nbsp;
<a href="?m=go3c&c=importsso&a=importdetaill&id=<?php echo urlencode($v['id']);?>&vid=all&skipin=1&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" onclick="doImportPPTVDetaill(this);return false;">导入新增分集</a>
&nbsp;&nbsp;
<?php } ?>

</td>
<td align="center">
<?php if(isset($indb_list[$v['VID']]) && $indb_list[$v['VID']]) { ?>
<a href="?m=go3c&c=importsso&a=importdetail&id=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" id="a_import_<?php if(isset($v['id'])) echo $v['id'];?>" onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;">已导入</a>
<?php } else { ?>
<a href="?m=go3c&c=importsso&a=importdetail&id=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" id="a_import_<?php if(isset($v['id'])) echo $v['id'];?>" onclick="doImportPPTVDetaill(this);return false;">导入</a>
<?php } ?>
&nbsp;
<a href="?m=go3c&c=importsso&a=dels&ids[]=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" id="a_del_<?php if(isset($v['id'])) echo $v['id'];?>" onclick="if(confirm('确认？')) doImportPPTVDetaill(this);return false;">删除</a>

<?php if($v['vcolumn'] == '电影') { ?>
&nbsp;
<a href="?m=go3c&c=importsso&a=upAVRSS&ids[]=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" id="a_upv_<?php if(isset($v['id'])) echo $v['id'];?>" at="upva" class="aaupv" onclick="doImportPPTVDetaill(this);return false;">更新</a>
<?php } else { ?>
&nbsp;
<a href="?m=go3c&c=importsso&a=showdetaillist&id=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" hrefinit="?m=go3c&c=importsso&a=upAVC&id=<?php echo urlencode($v['id']);?>&pc_hash=<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>" id="a_upv_<?php if(isset($v['id'])) echo $v['id'];?>" at="upvs" class="aaupv" onclick="doUPVDetaillList(this);return false;">更新</a>
<?php } ?>
</td>
</tr>
<?php } } ?>

<?php if(count($list) > 0) { ?>
<tr>
<td align="center">全选<input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
<td colspan="20">
选中项
<select id="i_select_ckall">
<option value="1">导入</option>
<option value="2">删除</option>
<option value="3">更新</option>
</select>

<input type="button" onclick="doCKALL();" value="确定" />
<span id="i_in_loading_ok" style="display:none;">执行完毕</span>
<span id="i_in_loading" style="display:none;background:url(statics/images/admin_img/onLoad.gif) left center no-repeat;line-height:16px;padding:10px 20px;">执行中...</span>
</td>
</tr>

<script>
window.doPushList = [] ;

function doCKALL(){
var doitem = $('#i_select_ckall').val() ;

//选中项目
$.each($('#i_table_list_1 tbody input[type=checkbox]'),function(i,o){
if(!$(o).attr('checked')) return ;
if(!$(o).val()) return ;

var ap = {} ;
ap.id = $(o).val() ;
//删除
if(doitem == '2'){
ap.url = $('#a_del_' +ap.id).attr('href') ;
ap.ap = 'del' ;
}else if(doitem == '1'){
ap.url = $('#a_import_' +ap.id).attr('href') ;
ap.ap = 'import' ;
}else if(doitem == '3'){
//批量更新
ap.obj = $('#a_upv_' +ap.id)[0] ;
ap.url = $('#a_upv_' +ap.id).attr('href') ;
ap.ap = $('#a_upv_' +ap.id).attr('at') ;
}

doPushList.push(ap) ;
})



doPushDo() ;
}

window.top.downUPAdLFunc = false ;

function doPushDo(){
if(doPushList.length < 1){
$('#i_in_loading').hide() ;
$('#i_in_loading_ok').show() ;
return ;
}
$('#i_in_loading_ok').hide() ;
var ap = doPushList.shift() ;

if(ap.ap == 'import'){

$('#a_import_' + ap.id ).html('正在导入') ;
$('#i_in_loading').show() ;
$.post(ap.url,'',function(t){
if(t == 'ok'){
$('#a_import_' + ap.id ).html('成功') ;

doPushDo() ;
}
});
}else if(ap.ap == 'del'){
$('#i_in_loading').show() ;
$.post(ap.url,'',function(t){
if(t == 'ok'){
$('#i_import_tr_'+ap.id).hide('fast') ;
$('#i_import_tr_'+ap.id).remove() ;
doPushDo() ;
}
});
}else if(ap.ap == 'upva'){
$('#i_in_loading').show() ;
$.post(ap.url,'',function(t){
if(t.indexOf('ok') == 0){
doPushDo() ;
}
});
}else if(ap.ap == 'upvs'){
$('#i_in_loading').show() ;


doUPVDetaillList(ap.obj) ;
window.top.downUPAdLFunc = function(){
window.top.downUPAdLFunc = false ;
window.top.art.dialog({id:'edit'}).close();
doPushDo() ;
}

}

}
</script>
<?php } ?>

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


function doUPVDetaillList(obj){
obj.innerHTML = '执行中' ;
$.post($(obj).attr('hrefinit'),'',function(t){
if(t.indexOf('ok') == 0){
var u = $(obj).attr('href') ;
popShowDetail(u + '&doimport=1') ;
window.top.NOWDOOBJ = obj ;
obj.innerHTML = '更新' ;
}
});



return false ;
}

function doImportPPTVDetaill(obj){
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