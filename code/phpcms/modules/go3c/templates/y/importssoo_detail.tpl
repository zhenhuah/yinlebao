{template 'header.tpl'}
<style>
.table_form tbody td, .table_form tbody th { border:0; }
</style>


<form name="myform" action="?m=go3c&c=importssoo&a=importdetail&id={urlencode($video[id])}" method="post" id="myform">
<table width="100%"><tbody>
	<tr>
		<td style="width:50%;">
			<table width="100%" class="table_form contentWrap"><tbody>
				<tr>
					<th style="width:50px;">vid</th>
					<td><input type="text" value="{$video[VID]}" style="width:150px;" readonly="readonly" />

					</td>
				</tr>
				<tr>
					<th>片名</th>
					<td><input type="text" value="{$video[title]}" style="width:300px;" name="d[title]" /></td>
				</tr>
				<tr>
					<th>导演</th>
					<td><input type="text" value="{$video[director]}" style="width:300px;" name="d[director]" /></td>
				</tr>
				<tr>
					<th>演员</th>
					<td><input type="text" value="{$video[actor]}" style="width:300px;" name="d[actor]" /></td>
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
					<td>
					{loop $video[pics] $v}
					<p>{if $v[pictype]}{$v[pictype]}:{/if} <input type="text" style="width:220px;" value="{$v[path]}" /></p>
					<br />
					<img src="{$v[path]}" style="height:120px;" />
					{/loop}
					</td>

				</tr>
				<tr>
					<th>播放链接</th>
					<td>
						{loop $video[vurls] $v}
						{php $v[spath]=$v['path']}
						{if $v['spath'] == 'segment'}
							{php $v['spath']="?m=go3c&c=importssoo&a=showsegment&id=" .$v['id'] . '&pc_hash=' . $_SESSION['pc_hash']}
						{/if}
						<p><input type="checkbox" value="{$v[path]}" name="indbvtype[{$v[videotype]}][{$v[sourcename]}]" checked="checked" /><input type="hidden" value="1" name="uscktype" /><a href="{$v[spath]}" target="_blank">{$v[sourcename]}</a>: <input type="text" style="width:180px;" readonly="readonly" value="{$v[path]}" /></p>
						{/loop}
					</td>
				</tr>
									
				{if $video[total_episodes]}
				<tr>
					<th>总集数</th>
					<td>{$video[total_episodes]}</td>
				</tr>
			
				
				{if $video[latest_episode_num]}
				<tr>
					<th>已更新集数</th>
					<td>{$video[latest_episode_num]}</td>
				</tr>
				{/if}
				
				{/if}
				
				{if $videotypes}
				<tr>
					<th>资源类型</th>
					<td>{loop $videotypes $k $vs}
						{loop $vs $ik $iv}
						<p><input type="checkbox" value="1" name="indbvtype[{$ik}][$iv]" checked="checked" /><input type="hidden" value="1" name="uscktype" />{$k} : {$ik}</p>
						{/loop}
					{/loop}
					</td>
				</tr>
				{/if}
				
				
				<tr>
					<td colspan="10" style="text-align:center;">
						{if $video[total_episodes]}
						<input type="button" style="width:82px;height:25px;" href="?m=go3c&c=importssoo&a=showdetaillist&id={urlencode($video[id])}&pc_hash={$_SESSION['pc_hash']}" onclick="window.location.href=this.getAttribute('href')" value="查看分集" />
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