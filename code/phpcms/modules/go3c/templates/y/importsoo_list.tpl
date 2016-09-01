{template 'header.tpl'}

<div style="padding:10px;">

<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="importsoo" name="c">
	<input type="hidden" value="soolist" name="a">
	
 关键词：<input type="text" name="w[search]" value="{$w[search]}" style="width:80px;" />

  类型：<select name="w[type]" id="i_w_type">
				<option value="">不限</option>
				{loop $base_data[type] $k $v}
				<option value="{$k}"{if $w[type] == $k} selected="selected"{/if}>{$v[name]}</option>
				{/loop}
			</select>  &nbsp; 
			
			
	地区：<select name="w[area]" id="i_w_area">
				<option value="">不限</option>
				{loop $base_data[area] $k $v}
				<option value="{$k}"{if $w[area] == $k} selected="selected"{/if}>{$v[name]}</option>
				{/loop}
			</select>  &nbsp; 
			
			
	年代：<select name="w[year]" id="i_w_year">
				<option value="">不限</option>
				{loop $base_data[year] $k $v}
				<option value="{$k}"{if $w[year] == $k} selected="selected"{/if}>{$v}</option>
				{/loop}
			</select>  &nbsp; 
			
	{php $spages=array(10,20,50,100)}
 每页显示：<select name="w[PS]" id="i_w_PS">
				{loop $spages $v}
				<option value="{$v}"{if $w[PS] == $v} selected="selected"{/if}>{$v}</option>
				{/loop}
			</select>  &nbsp;

	<input type="submit" value="查询" class="button" name="search"> &nbsp;&nbsp;	

	<input name="pc_hash" type="hidden" value="{$_SESSION['pc_hash']}">
	
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
		{loop $list $k $v}
		<tr id="i_import_tr_{$v[id]}">
		<td align="center"><input type="checkbox" value="{$v[id]}"  /></td>
			<td align="center">{$v[id]}</td>
			<td align="left"><a href="{$v[apiurl]}" target="_blank">{$v[title]}</a></td>
			<td align="center"><img src="{$v[image]}" style="width:90px;" /></td>
			<td align="center">{$base_data[type][$v[type_id]][name]}</td>
			<td align="center">{$v[area]}</td>
			<td align="center">{$v[year]}</td>
			<td align="center">
				<a href="?m=go3c&c=importsoo&a=soodetail&id={urlencode($v[id])}" onclick="popShowDetail(this.href);return false;">详情</a>
				&nbsp;&nbsp;
				{if $v[count]}
				<a href="?m=go3c&c=importsoo&a=soodetaillist&id={urlencode($v[id])}" onclick="popShowDetail(this.href);return false;">查看分集</a>
				&nbsp;&nbsp;
				<a href="?m=go3c&c=importsoo&a=sooimportdetaill&id={urlencode($v[id])}&vid=all" onclick="doImportPPTVDetaill(this);return false;">导入新增分集</a>
				&nbsp;&nbsp;
				{/if}
	
			</td>
			<td align="center">
			{if $indb_list[$v['id']]}
			<a href="?m=go3c&c=importsoo&a=sooimportdetail&id={urlencode($v[id])}" id="a_import_{$v[id]}" onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;">已导入</a>
			{else}
			<a href="?m=go3c&c=importsoo&a=sooimportdetail&id={urlencode($v[id])}" id="a_import_{$v[id]}" onclick="doImportPPTVDetaill(this);return false;">导入</a>
			{/if}
			</td>
		</tr>
		{/loop}
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