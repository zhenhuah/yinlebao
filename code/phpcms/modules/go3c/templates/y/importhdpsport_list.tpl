{template 'header.tpl'}

<div style="padding:10px;">

<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="importhdpsport" name="c">
	<input type="hidden" value="hdplist" name="a">
	

  类型：<select name="w[type]" id="i_w_type" onchange="doChangeType(this.value);">
				<option value="">请选择</option>
				{loop $base_data[type] $k $v}
				<option value="{$v[name]}"{if $w[type] == $v[name]} selected="selected"{/if}>{$v[name]}</option>
				{/loop}
		</select>  &nbsp; 
	
	类别：
	<span id="i_w_cate_in"></span>
			
			
	
			

	<input type="submit" value="查询" class="button" name="search"> &nbsp;&nbsp;	

	<input name="pc_hash" type="hidden" value="{$_SESSION['pc_hash']}">
	
	</form> 
</div>
<br />
<div style="display:none;" id="i_w_cate_in_display_none">
{loop $base_data[cate] $k $vs}
<select id="i_w_cate_{$vs[t][name]}" name="url">
	{loop $vs[l] $k $v}
	<option value="{$v[url]}">{$v[name]}</option>
	{/loop}
</select>  &nbsp; 
{/loop}
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
doChangeType('{$w[type]}') ;
if(i$('i_w_cate_'+'{$w[type]}')){
	i$('i_w_cate_'+'{$w[type]}').value = '{$apiurl}' ;
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
		{loop $list $k $v}
		<tr>
			<td align="center">{$v[id]}</td>
			<td align="center"><a href="{$v[url]}" target="_blank">{$v[title]}</a></td>
			<td align="center"><img src="{$v[image]}" style="width:40px;" /></td>
			<td align="center"><?php echo hdp_type($v['type']);?></td>
			<td align="center">
				<a href="?m=go3c&c=importhdpsport&a=hdpdetail&url={urlencode($v[url])}&t={$w[type]}" onclick="popShowDetail(this.href);return false;">详情</a>
				&nbsp;&nbsp;
			</td>
			<td align="center">
			{if $indb_list[$v['id']]}
			<a href="?m=go3c&c=importhdpsport&a=hdpimportdetail&url={urlencode($v[url])}&t={$w[type]}" onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this,'?m=go3c&c=importhdpsport&a=hdpdetaillist&url={urlencode($v[url])}&vid=all&t={$w[type]}&pc_hash={$_SESSION['pc_hash']}');return false;">已导入</a>
			<a href="?m=go3c&c=importhdpsport&a=hdpimportdetail&url={urlencode($v[url])}&t={$w[type]}&pc_hash={$_SESSION[pc_hash]}" onclick="doImportHDPDetaill(this);return false;">更新</a>	
			{else}
			<a href="?m=go3c&c=importhdpsport&a=hdpimportdetail&url={urlencode($v[url])}&t={$w[type]}" onclick="doImportPPTVDetaill(this,'?m=go3c&c=importhdpsport&a=hdpdetaillist&url={urlencode($v[url])}&vid=all&t={$w[type]}&pc_hash={$_SESSION['pc_hash']}');return false;">导入</a>
			{/if}		
			</td>
		</tr>
		{/loop}
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