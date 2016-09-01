<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="playcount" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=playcount&a=init">全部数据</a>&nbsp;
			VID：<input name="vid" type="text" value="<?php if(isset($vid)) echo $vid;?>" class="input-text" />&nbsp;
			名称：<input name="name" type="text" value="<?php if(isset($name)) echo $name;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="20" align="center">VID</th>
			<th width="30" align="left">名称</th>
			<th width="60" align="left">导演</th>
			<th width='130' align="left">发布日期</th>
			<th width='50' align="left">最后更新</th>
			<th width="60" align="left">播放次数</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){$i =1+($page-1)*$pagesize; foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['vid']?></td>
	<td align="left"><?php echo $value['name']?></td>
	<td align="left"><?php echo $value['director']?></td>
	<td align="left"><?php echo $value['time_created']?></td>
	<td align="left"><?php echo $value['time_updated']?></td>
	<td align="left">
	  <form name="myform" action="" method="GET">
		<input type="hidden" value="go3c" name="m">
		<input type="hidden" value="playcount" name="c">
		<input type="hidden" value="edit" name="a">
		<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
		<input type="hidden" value="<?php echo $value['vid']?>" name="vid">
		<input type="text" name="play_count" value="<?php echo $value['play_count']?>" />
		<input type="submit" name="search" class="button" value="提交">
	  </form>
	</td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function doconfirmurl(id,title) {
    confirmurl('?m=go3c&c=video&a=delete_appy&id='+id+'&goback='+BASE64.encode(location.search),title);
}
</script>
