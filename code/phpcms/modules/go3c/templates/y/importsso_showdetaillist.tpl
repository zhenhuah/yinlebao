{template 'header.tpl'}

{if request('doimport','GET')}
<div class="explain-col" style="text-align:center;">
	资源正在更新，请不要关闭窗口！
</div>
{/if}


<div class="table-list" id="i_table">
<table width="100%" cellspacing="0">
   <thead><tr>
		<th style="width:150px;" align="center">VID</th>
		<th align="center">分集名</th>
		<th align="center" style="width:150px;">资源类型</th>
		<th align="center" style="width:100px;">第几集</th>
		{if $v[image]}
		<th style="width:100px;" align="center">海报</th>
		{/if}
		
		<th style="width:150px;" align="center">操作</th>
    </tr></thead>
    <tbody>
		{loop $video[eps] $k $v}
		<tr>
			<td align="center">{$v[VID]}</td>
			<td align="left">
				{loop $v[vurls] $iik $iiv}
				{if $iik}<br />{/if}
				<a href="{$iiv[apiurl]}" target="_blank">{$v[title]}</a>
				{/loop}
			</td>
			<td align="center">{loop $v[vurls] $iik $iiv}
			{if $iik}<br />{/if}
			<a href="{$iiv[path]}" target="_blank" id="i_path_a_{$iiv[id]}">{$iiv[sourcename]} : {$iiv[videotype]}</a> <a href="?m=go3c&c=importsso&a=upAVRS&id={urlencode($iiv[id])}&pc_hash={$_SESSION['pc_hash']}" id="i_path_up_a_{$iiv[id]}" class="pathupa" onclick="doUPVURL(this);return false;">更新</a>
			{/loop}
			</td>
			<td align="center">{$v[episode_number]}</td>
			{if $v[image]}
			<td align="center"><img src="{$v[image]}" style="width:90px;" /></td>
			{/if}
			
			
			
			<td align="center">
				{if $indb_list[$v[VID]]}
				<a onclick="if(confirm('确定重新导入？')) doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importsso&a=importdetaill&id={urlencode($video[id])}&vid[]={urlencode($v[id])}&pc_hash={$_SESSION['pc_hash']}">已导入</a>
				{else}
				<a onclick="doImportPPTVDetaill(this);return false;" href="?m=go3c&c=importsso&a=importdetaill&id={urlencode($video[id])}&vid[]={urlencode($v[id])}&pc_hash={$_SESSION['pc_hash']}">导入</a>
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
	window.location.href = '?m=go3c&c=importsso&a=showOK&pc_hash={$_SESSION['pc_hash']}' ;
}

{if request('doimport','GET')}
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
{/if}
</script>