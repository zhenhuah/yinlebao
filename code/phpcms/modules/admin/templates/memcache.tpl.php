<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="addContent" style="background:#FFF; width:100%;">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">清除54测试服大电视网站指定模块memcache缓存(请先清除缓存和同步数据后再生成静态页)
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="col-1">
<div class="content pad-6">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb" href="javascript:memcache('recommend')"><em>推荐位模板</em></a> |
		<a class="add fb" href="javascript:memcache('rank')"><em>排行榜模块</em></a>|
		<a class="add fb" href="javascript:memcache('advert')"><em>广告位模块</em></a>|
		<a class="add fb" href="javascript:memcache('list')"><em>电视剧,电视栏目检索模块</em></a>|
		<a class="add fb" href="javascript:memcache('epg')"><em>epg模块</em></a>|
		<a class="add fb" href="javascript:memcache('esource')"><em>清除全三屏缓存</em></a>|
		<a class="add fb" href="javascript:memcache('all')"><em>清除全部缓存</em></a>|
		<a class="add fb" href="javascript:memcache('html')"><em style="color:black;">生成网站静态页</em></a>
	</div>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">清除www大电视网站指定模块memcache缓存(请先清除缓存和同步数据后再生成静态页)
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="content-menu ib-a blue line-x">
		<a class="add fb" href="javascript:memcachewww('recommend')"><em>推荐位模板</em></a> |
		<a class="add fb" href="javascript:memcachewww('rank')"><em>排行榜模块</em></a>|
		<a class="add fb" href="javascript:memcachewww('advert')"><em>广告位模块</em></a>|
		<a class="add fb" href="javascript:memcachewww('list')"><em>电视剧,电视栏目检索模块</em></a>|
		<a class="add fb" href="javascript:memcachewww('epg')"><em>epg模块</em></a>|
		<a class="add fb" href="javascript:memcachewww('esource')"><em>清除全三屏缓存</em></a>|
		<a class="add fb" href="javascript:memcachewww('all')"><em>清除全部缓存</em></a>|
		<a class="add fb" href="javascript:memcachewww('html')"><em style="color:black;">生成网站静态页</em></a>
	</div>
</br>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			文件夹名称：<input id="filename" name="filename" type="text" value="<?php if(isset($filename)) echo $filename;?>" class="input-text" size="15" />&nbsp;
			<input type="submit" name="search" onclick="copybigtv();" class="button" value="复制" />	(注意需要输入的复制的专题的文件夹名称的大小写,确保正确!)
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
	function copybigtv(){
		var filename = $("#filename").attr("value");
		if(filename!=''){
			location.href ='?m=admin&c=clear_memcache&a=copybigtv&filename='+filename
			+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
		}else{
			alert('你还没有输入文件夹名称！');
		}
	}
	function memcache(type){
		location.href ='?m=admin&c=clear_memcache&a=memcache_bigtv&type='+type
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
	function memcachewww(type){
		location.href ='?m=admin&c=clear_memcache&a=memcache_www&type='+type
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
</script>
	</div>
</div>      
</div>
</div>
</body>
</html>
