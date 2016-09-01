{template 'header.tpl'}


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
		{loop $video[eps] $k $v}
		<tr>
			<td align="center">{$v[id]}</td>
			<td align="left">{$v[title]}</td>
			<td align="center">{$v[seq]}</td>
			<td align="center"><img src="{$v[image]}" style="width:90px;" /></td>
			<td align="center">
				{if $indb_list[$v[id]]}
				<a onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importpptv&a=pptvimportdetaill&id={urlencode($video[id])}&ftype={$video[type]}&vid[]={urlencode($v[id])}&pc_hash={$_SESSION['pc_hash']}">已导入</a>
				{else}
				<a onclick="doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importpptv&a=pptvimportdetaill&id={urlencode($video[id])}&ftype={$video[type]}&vid[]={urlencode($v[id])}&pc_hash={$_SESSION['pc_hash']}">导入</a>
				{/if}
			</td>
		</tr>
		{/loop}
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