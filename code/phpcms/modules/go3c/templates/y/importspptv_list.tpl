{template 'header.tpl'}

<div style="padding:10px;">
<!-- 
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:doimportspptv();" ><em>导入PPTV源数据</em></a>
	<span id="a_doimportspptv" style="color:red"></span>
</div>
 -->
<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="importspptv" name="c">
	<input type="hidden" value="showlist" name="a">
	
 关键词：<input type="text" name="w[lk_title]" value="{$w[lk_title]}" style="width:80px;" />

  {if !empty($aconf[vcolumn])}
类型:<select name="w[vcolumn]" id="i_w_vcolumn">
				<option value="">不限</option>
				{loop $aconf[vcolumn] $v}
				<option value="{$v}"{if !empty($w[vcolumn]) && $w[vcolumn] == $v} selected="selected"{/if}>{$v}</option>
				{/loop}
			</select>  &nbsp; 
	
	<span id="i_w_cate_in"></span>
  {/if}
	
	{if !empty($aconf[area])}
	地区：<select name="w[area]" id="i_w_area">
				<option value="">不限</option>
				{loop $aconf[area] $v}
				<option value="{$v}"{if !empty($w[area]) && $w[area] == $v} selected="selected"{/if}>{$v}</option>
				{/loop}
			</select>  &nbsp; 
	{/if}
		
	{if !empty($aconf[year])}
	年代：<select name="w[year]" id="i_w_year">
				<option value="">不限</option>
				{loop $aconf[year] $v}
				<option value="{$v}"{if !empty($w[year]) && $w[year] == $v} selected="selected"{/if}>{$v}</option>
				{/loop}
			</select>  &nbsp; 
	{/if}
	{if !empty($aconf[staut])}
	是否导入:<select name="w[staut]" id="i_w_staut">
			<option value="">不限</option>
			<option value="1" {if !empty($w[staut]) && $w[staut] == 1} selected="selected"{/if}>已导入</option>
			<option value="0" {if !empty($w[staut]) && $w[staut] == 0} selected="selected"{/if}>未导入</option>
			</select>  &nbsp;
	{/if}
	{php $spages=array(10,20,30,50,100)}
 每页显示：<select name="ps" id="i_w_ps">
				{loop $spages $v}
				<option value="{$v}"{if !empty($page_size) && $page_size == $v} selected="selected"{/if}>{$v}</option>
				{/loop}
			</select>  &nbsp;

	

	<input type="submit" value="查询" class="button" name="search"> &nbsp;&nbsp;	

	<input name="pc_hash" type="hidden" value="{$_SESSION['pc_hash']}">
	</form> 
</div>
<br />

<div style="display:none;" id="i_w_cate_in_display_none">

{loop $aconf[cate] $k $vs}
<span id="i_w_cate_{$k}_span">

类别：
<select id="i_w_cate_{$k}" name="w[vcate]">
	<option value="">不限</option>
	{loop $vs $v}
	<option value="{$v}">{$v}</option>
	{/loop}
</select>  &nbsp; 


</span>
{/loop}

</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script>
function doimportspptv(){
		$('#a_doimportspptv').html('正在导入') ;
		location.href = '?m=go3c&c=importspptv&a=doimportspptv&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
}
function i$(i){ return document.getElementById(i); }

if(i$('i_w_cate_'+'{$w[vcolumn]}')){
	i$('i_w_cate_'+'{$w[vcolumn]}').value = '{$w[vcate]}' ;
}

function clickCKB(a){
	$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}


</script>

<div class="table-list">
<table width="100%" cellspacing="0" id="i_table_list_1">
   <thead><tr>
		<th style="width:50px;" align="center">全选<input type="checkbox" onclick="clickCKB(this);"  /></th>
		<th style="width:50px;" align="center">VID</th>
		<th style="width:130px;" align="center">片名</th>
		<th align="center" style="width:100px;">图片</th>
		<th align="center" style="width:50px;">类别</th>
		<th align="center" style="width:50px;">分类</th>
		{if !empty($aconf[area])}
		<th style="width:50px;" align="center">地区</th>
		{/if}
		{if !empty($aconf[year])}
		<th style="width:100px;" align="center">年代</th>
		{/if}
		<th style="width:250px;" align="center">操作</th>
		<th style="width:230px;" align="center">操作</th>
    </tr></thead>
    <tbody>
		{loop $list $v}
		{php $v[image]=$pics[$v[id]][path]}
		<tr id="i_import_tr_{$v[id]}">
			<td align="center"><input type="checkbox" value="{$v[id]}"  /></td>
			<td align="center">{$v[VID]}</td>
			<td align="left"><a href="{$v[apiurl]}" target="_blank">{$v[title]}</a></td>
			<td align="center"><img src="{$v[image]}" style="width:40px;" /></td>
			<td align="center">{$v[vcolumn]}</td>
			<td align="center">{$v[vcate]}</td>
			{if !empty($aconf[area])}
			<td align="center">{$v[area]}</td>
			{/if}
			{if !empty($aconf[year])}
			<td align="center">{$v[year]}</td>
			{/if}
			
			<td align="center">
				<a href="?m=go3c&c=importspptv&a=showdetail&id={urlencode($v[id])}&pc_hash={$_SESSION[pc_hash]}" onclick="popShowDetail(this.href);return false;">详情</a>
				&nbsp;&nbsp;
				{if $v[vcolumn] != '电影'}
				<a href="?m=go3c&c=importspptv&a=showdetaillist&id={urlencode($v[id])}&pc_hash={$_SESSION[pc_hash]}" onclick="popShowDetail(this.href);return false;">查看分集</a>
				&nbsp;&nbsp;
				<a href="?m=go3c&c=importspptv&a=importdetaill&id={urlencode($v[id])}&vid=all&skipin=1&pc_hash={$_SESSION[pc_hash]}" onclick="doImportPPTVDetaill(this);return false;">导入新增分集</a>
				&nbsp;&nbsp;
				{/if}
	
			</td>
			<td align="center">
			{if $indb_list[$v['VID']]}
			<a href="?m=go3c&c=importspptv&a=importdetail&id={urlencode($v[id])}&pc_hash={$_SESSION[pc_hash]}" id="a_import_{$v[id]}" onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;">已导入</a>
			{else}
			<a href="?m=go3c&c=importspptv&a=importdetail&id={urlencode($v[id])}&pc_hash={$_SESSION[pc_hash]}" id="a_import_{$v[id]}" onclick="doImportPPTVDetaill(this);return false;">导入</a>
			{/if}
			&nbsp;
			<a href="?m=go3c&c=importspptv&a=dels&ids[]={urlencode($v[id])}&pc_hash={$_SESSION[pc_hash]}" id="a_del_{$v[id]}" onclick="if(confirm('确认？')) doImportPPTVDetaill(this);return false;">删除</a>
			

			&nbsp;
			<a href="?m=go3c&c=importspptv&a=upAV&id={urlencode($v[id])}&pc_hash={$_SESSION[pc_hash]}" id="a_upv_{$v[id]}" at="upva" class="aaupv" onclick="doImportPPTVDetaill(this);return false;">更新</a>
			
			</td>
		</tr>
		{/loop}
		
		{if count($list) > 0}
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
				ap.obj.innerHTML = '成功' ;
			}else{
				ap.obj.innerHTML = '失败' ;
			}
			doPushDo() ;
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
		{/if}
	
		{if $pager}
		<tr>
			<td colspan="20" style="text-align:center;padding:10px;">
				{$pager}
			</td>
		</tr>
		{/if}
	</tbody>
</table>

</div>

<script>
function popShowDetail(url){
	window.top.art.dialog({title:'视频资源详情', id:'edit', iframe:url,width:'800px',height:'450px'},'', function(){window.top.art.dialog({id:'edit'}).close()});
}


function doUPVDetaillList(obj){
	return ;
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