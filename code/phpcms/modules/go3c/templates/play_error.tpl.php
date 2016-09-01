<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="play_error" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=tvuser&a=play_error">全部数据</a>&nbsp;
			 终端类型：<select name="term_name" id="sect">
			<option value='' <?php if($_GET['term_name']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($aterm as $key=>$v){?>
           		 <option value='<?php echo $v['term_name']?>' <?php if($_GET['term_name']==$v['term_name']) echo 'selected';?>><?php echo $v['term_name']?></option>
			<?php }} ?>
			</select>
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		<a class="content-menu ib-a blue line-x add fb" style="float:right;" href="javascript:doconfirmurl('', '清除数据')">清除数据</a>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="5%" align="left">时间</th>
			<th width='5%' align="left">终端名称</th>
			<th width='5%' align="left">操作系统</th>
			<th width='5%' align="left">终端硬件</th>
			<th width='5%' align="left">终端硬件配置</th>
			<th width='5%' align="left">客户端版本</th>
			<th width='5%' align="left">问题类型</th>
			<th width='5%' align="left">是否下线</th>
			<th width='5%' align="left">视频名</th>
			<th style="width:300px; word-break: break-all; word-wrap:break-word;" align="left">问题描述</th>
            <th width='10%' align="left">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td width="5%" align="left"><?php echo $value['time_added']?></td>
	<td width='5%' align="left"><?php echo $value['term_name'];?></td>
	<td width='5%' align="left"><?php echo $value['os']?></td>
	<td width='5%' align="left"><?php echo $value['hw_model']?></td>
	<td width='5%' align="left"><?php echo $value['hw_config']?></td>
	<td width='5%' align="left"><?php echo $value['client_version']?></td>
	<td width='5%' align="left"><?php echo $value['issue_type']?></td>
	<td width='5%' align="left"><?php if($value['isoff']==1) echo "否"?><?php if($value['isoff']==2) echo "是"?></td>
	<td width='5%' align="left"><?php echo $value['video_name']?></td>
	<td style="width:300px; word-break: break-all; word-wrap:break-word;" align="left"><?php echo $value['description']?></td>
	<td width='10%' align="left">
	<?php if($value['isoff'] == 1 ){?>
	<a href="javascript:play_off('<?php echo $value['id']?>')">链接下线</a>
	<?php }?>
	</td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
   <div id="pages"><?php echo $multipage;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function play_off(id) {
    location.href ='?m=go3c&c=tvuser&a=play_off&id='+encodeURIComponent(id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function doconfirmurl(id,title) {
	//获取选中的select的值
	var term_name = document.getElementById("sect").value; 
    confirmurl('?m=go3c&c=tvuser&a=droup_playinfo&term_name='+encodeURIComponent(term_name)+'&goback='+BASE64.encode(location.search),title);
}
</script>
