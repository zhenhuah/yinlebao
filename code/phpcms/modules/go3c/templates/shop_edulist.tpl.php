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
<input type="hidden" value="shop_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<?php echo L('shop_name');?>：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
			<?php echo L('ktv_isonline');?>:<select id="status" name="status">
			<option value=''><?php echo L('ktv_all');?></option>
			<option value='4' <?php if($status==4) echo 'selected';?>><?php echo L('ktv_online');?></option>
			<option value='1' <?php if($status==1) echo 'selected';?>><?php echo L('ktv_offline');?></option>
			</select>
			<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>

<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
            <th style="width:20px;" align="right"><?php echo L('ktv_12');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width='5' align="center">ID</th>
			<th width='30' align="center"><?php echo L('shop_name');?></th>
			<th width='20' align="center"><?php echo L('ktv_uptime');?></th>
			<th width='20' align="center"><?php echo L('shop_type');?></th>
			<th width='10' align="center"><?php echo L('shop_version');?></th>
			<th width='15' align="center"><?php echo L('shop_z_term');?></th>
			<th width="40" align="center"><?php echo L('ktv_oper');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="app_id" value="<?php echo $value['app_id']?>"  /></td>
	<td align="center"><?php echo $value['app_id']?></td>
	<td align="center"><?php echo $value['app_name']?></td>
	<td align="center"><?php echo $value['update_time']?></td>
	<td align="center"><?php echo $value['channel']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php if($value['STB']==1) echo "STB|"?><?php if($value['SSB']==1) echo "SSB|"?><?php if($value['IOS']==1) echo "IOS|"?><?php if($value['PC']==1) echo "PC|"?><?php if($value['ANDROID']==1) echo "ANDROID"?></td>
	<td align="center">
	<?php    if($value['status'] == 1 || $value['status'] == 0){?>
		<a href="javascript:edit('<?php echo $value['app_id']?>')"><?php echo L('ktv_edit');?></a> | 
		<a style="color:blue" href="javascript:spic_list('<?php echo $value['app_id']?>')"><?php echo L('ktv_pic');?></a> | 
		<a style="color:red" href="javascript:sd_info('<?php echo $value['app_id']?>')"><?php echo L('vlink');?></a> | 
		<a style="color:green" href="javascript:infor_pass('<?php echo $value['app_id']?>')">申请审核</a> |
		<a href="javascript:dodelete('<?php echo $value['app_id']?>')"><?php echo L('ktv_del');?></a>
		<?php } ?>
	<?php    if($value['status'] == 4){?>
	<a style="color:red" href="javascript:infor_off(<?php echo $value['app_id']?>, '<?php echo L('for_offline');?>')"><?php echo L('ktv_pass_off');?></a>
	<?php } ?>		
		<input type="hidden" value="<?php echo $value['app_id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
	<?php    if($status == 1||$status == 4||$status == ''){?>
	<tr>
	<td align="right"><?php echo L('ktv_12');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<?php    if($status == 1||$status == ''){?>
		<option value="1">申请审核</option>
		<option value="3"><?php echo L('ktv_del');?></option>
		<?php } ?>
		<?php    if($status == 4){?>
		<option value="2"><?php echo L('ktv_pass_off');?></option>
		<?php } ?>	
		</select>
	<input type="button" onclick="doCKALL();" value="<?php echo L('ok');?>" />
	</td>
	</tr>
	<?php } ?>	
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
function clickCKB(a){
	$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}
function doCKALL(){
	var doitem = $('#i_select_ckall').val() ;
	 var arr =[];    
     var str='';
     $("input[name=app_id]:checked").each(function(){    
         arr.push({app_id:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
		if(doitem == '2'){  //下线
			location.href ='?m=go3c&c=shop&a=delete_allto&app_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '1'){  //上线 
			location.href ='?m=go3c&c=shop&a=online_pass_all&app_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '3'){  //删除
			location.href ='?m=go3c&c=shop&a=online_error&app_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);	
			}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function edit(id) {
    location.href ='?m=go3c&c=task_shop&a=edit_app&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=shop&a=delete_app&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=go3c&c=task_shop&a=add_appedu&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function infor_pass(id) {
    location.href ='?m=go3c&c=shop&a=shop_pass&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function infor_off(id) {
    location.href ='?m=go3c&c=shop&a=shop_off&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function spic_list(app_id) {
    location.href ='?m=go3c&c=task_shop&a=spic_list&app_id='+app_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function sd_info(app_id) {
    location.href ='?m=go3c&c=task_shop&a=sd_info&app_id='+app_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
