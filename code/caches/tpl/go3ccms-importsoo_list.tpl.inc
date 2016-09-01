<?php include ytemplate('header.tpl');?>

<div style="padding:10px;">

<div class="explain-col">
<form name="myfrom" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="importsoo" name="c">
<input type="hidden" value="soolist" name="a">

 关键词：<input type="text" name="w[search]" value="<?php if(isset($w['search'])) echo $w['search'];?>" style="width:80px;" />

  类型：<select name="w[type]" id="i_w_type">
<option value="">不限</option>
<?php if(isset($base_data['type']) && is_array($base_data['type'])) { foreach($base_data['type'] as $k => $v) { ?>
<option value="<?php if(isset($k)) echo $k;?>"<?php if($w['type'] == $k) { ?> selected="selected"<?php } ?>><?php if(isset($v['name'])) echo $v['name'];?></option>
<?php } } ?>
</select>  &nbsp; 


地区：<select name="w[area]" id="i_w_area">
<option value="">不限</option>
<?php if(isset($base_data['area']) && is_array($base_data['area'])) { foreach($base_data['area'] as $k => $v) { ?>
<option value="<?php if(isset($k)) echo $k;?>"<?php if($w['area'] == $k) { ?> selected="selected"<?php } ?>><?php if(isset($v['name'])) echo $v['name'];?></option>
<?php } } ?>
</select>  &nbsp; 


年代：<select name="w[year]" id="i_w_year">
<option value="">不限</option>
<?php if(isset($base_data['year']) && is_array($base_data['year'])) { foreach($base_data['year'] as $k => $v) { ?>
<option value="<?php if(isset($k)) echo $k;?>"<?php if($w['year'] == $k) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp; 

<?php $spages=array(10,20,50,100)?>
 每页显示：<select name="w[PS]" id="i_w_PS">
<?php if(isset($spages) && is_array($spages)) { foreach($spages as $v) { ?>
<option value="<?php if(isset($v)) echo $v;?>"<?php if($w['PS'] == $v) { ?> selected="selected"<?php } ?>><?php if(isset($v)) echo $v;?></option>
<?php } } ?>
</select>  &nbsp;

<input type="submit" value="查询" class="button" name="search"> &nbsp;&nbsp;	

<input name="pc_hash" type="hidden" value="<?php if(isset($_SESSION['pc_hash'])) echo $_SESSION['pc_hash'];?>">

</form> 
</div>
<br />
<script>
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
<th style="width:100px;" align="center">地区</th>
<th style="width:100px;" align="center">年代</th>
<th style="width:230px;" align="center">操作</th>
<th style="width:60px;" align="center">导入</th>
    </tr></thead>
    <tbody>
<?php if(isset($list) && is_array($list)) { foreach($list as $k => $v) { ?>
<tr id="i_import_tr_<?php if(isset($v['id'])) echo $v['id'];?>">
<td align="center"><input type="checkbox" value="<?php if(isset($v['id'])) echo $v['id'];?>"  /></td>
<td align="center"><?php if(isset($v['id'])) echo $v['id'];?></td>
<td align="left"><a href="<?php if(isset($v['apiurl'])) echo $v['apiurl'];?>" target="_blank"><?php if(isset($v['title'])) echo $v['title'];?></a></td>
<td align="center"><img src="<?php if(isset($v['image'])) echo $v['image'];?>" style="width:90px;" /></td>
<td align="center"><?php if(isset($base_data['type'][$v['type_id']]['name'])) echo $base_data['type'][$v['type_id']]['name'];?></td>
<td align="center"><?php if(isset($v['area'])) echo $v['area'];?></td>
<td align="center"><?php if(isset($v['year'])) echo $v['year'];?></td>
<td align="center">
<a href="?m=go3c&c=importsoo&a=soodetail&id=<?php echo urlencode($v['id']);?>" onclick="popShowDetail(this.href);return false;">详情</a>
&nbsp;&nbsp;
<?php if(isset($v['count']) && $v['count']) { ?>
<a href="?m=go3c&c=importsoo&a=soodetaillist&id=<?php echo urlencode($v['id']);?>" onclick="popShowDetail(this.href);return false;">查看分集</a>
&nbsp;&nbsp;
<a href="?m=go3c&c=importsoo&a=sooimportdetaill&id=<?php echo urlencode($v['id']);?>&vid=all" onclick="doImportPPTVDetaill(this);return false;">导入新增分集</a>
&nbsp;&nbsp;
<?php } ?>

</td>
<td align="center">
<?php if(isset($indb_list[$v['id']]) && $indb_list[$v['id']]) { ?>
<a href="?m=go3c&c=importsoo&a=sooimportdetail&id=<?php echo urlencode($v['id']);?>" id="a_import_<?php if(isset($v['id'])) echo $v['id'];?>" onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;">已导入</a>
<?php } else { ?>
<a href="?m=go3c&c=importsoo&a=sooimportdetail&id=<?php echo urlencode($v['id']);?>" id="a_import_<?php if(isset($v['id'])) echo $v['id'];?>" onclick="doImportPPTVDetaill(this);return false;">导入</a>
<?php } ?>
</td>
</tr>
<?php } } ?>
<tr>
<td align="center">全选<input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
<td colspan="20">
选中项
<select id="i_select_ckall">
<option value="1">导入</option>
</select>

<input type="button" onclick="doCKALL();" value="确定" />
<span id="i_in_loading_ok" style="display:none;">执行完毕</span>
<span id="i_in_loading" style="display:none;background:url(statics/images/admin_img/onLoad.gif) left center no-repeat;line-height:16px;padding:10px 20px;">执行中...</span>
</td>
</tr>
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
}		
doPushList.push(ap) ;
})
doPushDo() ;
}
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
}
}
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