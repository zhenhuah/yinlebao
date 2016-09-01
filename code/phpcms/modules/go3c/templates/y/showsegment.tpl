{template 'header.tpl'}


	<ul style="padding:10px;">
	{loop $list $v}
		<li style="line-height:260%;"><a href="{$v}" target="_blank">{$v}</a></li>
	{/loop}
	</ul>

