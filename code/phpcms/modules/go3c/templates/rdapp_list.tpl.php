<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em><?php echo L('shop_add');?></em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="shop" name="c">
<input type="hidden" value="rdapp_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=shop&a=rdapp_list"><?php echo L('shop_data');?></a>&nbsp;
			<?php echo L('shop_name');?>：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
			<?php echo L('shop_type');?>:<select id="sect" name="type">
	            <option value='' <?php if($_GET['type']==0) echo 'selected';?>><?php echo L('all');?></option>
	            <option value='live' <?php if($_GET['type']=='live') echo 'selected';?>><?php echo L('shop_live');?></option>
				<option value='vod' <?php if($_GET['type']=='vod') echo 'selected';?>><?php echo L('shop_video');?></option>
				<option value='music' <?php if($_GET['type']=='music') echo 'selected';?>><?php echo L('shop_muise');?></option>
			</select>			
			<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" /> |
			<a class="add fb" href="javascript:confiurl('', '你确定要执行该操作吗?')"><em><?php echo L('shop_cjson');?></em></a>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width='20' align="center"><?php echo L('shop_name');?></th>
			<th width='20' align="center"><?php echo L('shop_type');?></th>
			<th width='20' align="center"><?php echo L('shop_pagename');?></th>
			<th width='15' align="center"><?php echo L('shop_term');?></th>
			<th width='30' align="center">icon</th>
			<th width='50' align="center"><?php echo L('shop_apage');?></th>
			<th width="50" align="center"><?php echo L('ktv_oper');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($infor_list)){foreach($infor_list as $value){?>   
	<tr>
	<td align="center"><?php echo $value['name']?></td>
	<td align="center"><?php echo $value['type']?></td>
	<td align="center"><?php echo $value['classname']?></td>
	<td align="center"><?php echo $value['os']?></td>
	<td align="center"><a href="<?php echo $value['icon_url']?>" target="_blank"><img style="width:30px; border:solid 1px gray; padding:2px;" src="<?php echo $value['icon_url']?>" /></a></td>
	<td align="center"><a href="<?php echo $value['install_url']?>" target="_blank"><?php echo $value['install_url']?></a></td>
	<td align="center">
		<a href="javascript:edit('<?php echo $value['id']?>')"><?php echo L('ktv_edit');?></a> | 
		<a href="javascript:dodelete('<?php echo $value['id']?>')"><?php echo L('ktv_del');?></a>
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
function confiurl(id,title) {
	//获取选中的select的值
	var type = document.getElementById("sect").value; 
    confirmurl('?m=go3c&c=shop&a=createrdapjson&type='+type+'&goback='+BASE64.encode(location.search),title);
}
function edit(id) {
    location.href ='?m=go3c&c=task_shop&a=edit_rdapp&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=task_shop&a=delete_rdapp&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=go3c&c=task_shop&a=add_rdapp&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
