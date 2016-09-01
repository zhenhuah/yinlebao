{template 'header.tpl'}

{if request('doimport','GET')}
<div class="explain-col" style="text-align:center;">
	资源正在导入，请不要关闭窗口！
</div>
{/if}

<div class="table-list" id="i_table">
<table width="100%" cellspacing="0">
   <thead><tr>
		<th style="width:150px;" align="center">VID</th>
		<th align="center">分集名</th>
		<th align="center" style="width:100px;">第几集</th>
		<th style="width:150px;" align="center">操作</th>
    </tr></thead>
    <tbody>
		{loop $video[eps] $k $v}
		<tr>
			<td align="center">{$v[id]}</td>
			<td align="left">{$v[title]}</td>
			<td align="center">{$v[series]}</td>
			<td align="center" class="atd">
				{if $indb_list[$v[id]]}
				<a onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importhdpyk&a=hdpimportdetaill&url={urlencode($video[url])}&t={$video[type]}&vurl[]={urlencode($v[url])}&pc_hash={$_SESSION['pc_hash']}">已导入</a>
				{else}
				<a onclick="doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importgapk&a=hdpimportdetaill&url={urlencode($video[url])}&t={$video[type]}&vurl[]={urlencode($v[url])}&pc_hash={$_SESSION['pc_hash']}">导入</a>
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
			if(window.doPush){
				if(window.doPush.length > 0){
					doImportPPTVDetaill(window.doPush.shift()) ;
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
	window.location.href = '?m=go3c&c=importgapk&a=showOK&pc_hash={$_SESSION['pc_hash']}' ;
}

{if request('doimport','GET')}
setTimeout(function(){
	window.doPush = [] ;
	var dds = document.getElementById('i_table').getElementsByTagName('a') ;
	for(var i=0;i<dds.length;i++){
		if(dds[i].parentNode.className != 'atd') continue ;
		window.doPush.push(dds[i]) ;
	}
	
	if(window.doPush.length > 0){
		doImportPPTVDetaill(window.doPush.shift()) ;
	}else{
		doShowOk() ;
	}
},1000) ;
{/if}
</script>