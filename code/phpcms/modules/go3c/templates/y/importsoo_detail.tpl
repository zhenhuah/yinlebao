{template 'header.tpl'}
<style>
.table_form tbody td, .table_form tbody th { border:0; }
</style>

<form name="myform" action="?m=go3c&c=importsoo&a=sooimportdetail&id={urlencode($video[id])}&ftype={$video[type]}" method="post" id="myform">
<table width="100%"><tbody>
	<tr>
		<td style="width:50%;">
			<table width="100%" class="table_form contentWrap"><tbody>
				<tr>
					<th style="width:50px;">vid</th>
					<td><input type="text" value="{$video[id]}" style="width:150px;" readonly="readonly" name="d[vid]" /></td>
				</tr>
				<tr>
					<th>片名</th>
					<td><input type="text" value="{$video[title]}" style="width:300px;" name="d[title]" /></td>
				</tr>
				<tr>
					<th>导演</th>
					<td><input type="text" value="{$video[directors]}" style="width:300px;" name="d[directors]" /></td>
				</tr>
				<tr>
					<th>演员</th>
					<td><input type="text" value="{$video[actors]}" style="width:300px;" name="d[actors]" /></td>
				</tr>

				<tr>
					<th>地区</th>
					<td><input type="text" value="{$video[area]}" style="width:300px;" name="d[area]" /></td>
				</tr>
				<tr>
					<th>tags</th>
					<td><input type="text" value="{$video[tags]}" style="width:300px;" name="d[tags]" /></td>
				</tr>
				<tr>
					<th>剧情</th>
					<td><textarea style="width:300px;height:110px;" name="d[description]">{$video[description]}</textarea></td>
				</tr>
			</tbody></table>
		</td>
		
		<td>
			<table width="100%" class="table_form"><tbody>
				<tr>
					<th style="width:100px;">图片</th>
					<td><input type="text" style="width:220px;" value="{$video[image]}" /></td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td><img src="{$video[image]}" style="height:120px;" /></td>
				</tr>
				<tr>
					<th>播放链接</th>
					<td>
						{loop $video[urls] $v}
						<p>{$v[stagname]}: <input type="text" style="width:220px;" value="{$v[url]}" /></p>
						{/loop}
					</td>
				</tr>
				
				<tr>
					<th>集数</th>
					<td>{count($video[eps])}</td>
				</tr>
								
				{if $video[count]}
				<tr>
					<th>总集数</th>
					<td>{$video[count]}</td>
				</tr>
				
				{*
				<tr>
					<th>已更新集数</th>
					<td>{$video[episodes_updated_count]}</td>
				</tr>
				*}
				{/if}
				<tr>
					<td colspan="10" style="text-align:center;">
						{if $video[count]}
						<input type="button" style="width:82px;height:25px;" href="?m=go3c&c=importsoo&a=soodetaillist&id={urlencode($video[id])}&ftype={$video[type]}&pc_hash={$_SESSION['pc_hash']}" onclick="window.location.href=this.getAttribute('href')" value="查看分集" />
						&nbsp;&nbsp;
						{/if}
						
						<input type="hidden" name="showok" value="1" />
						<input type="submit" style="width:62px;height:25px;" value="导入" />
						&nbsp;&nbsp;
						<input type="button" style="width:62px;height:25px;" onclick="window.top.art.dialog({id:'edit'}).close();" value="返回" />
					</td>
				</tr>
				
				
			</tbody></table>
		</td>
	</tr>
</tbody></table>

    <div class="bk15"></div>
 	
	<input name="pc_hash" type="hidden" value="{$_SESSION['pc_hash']}">

	
</form>