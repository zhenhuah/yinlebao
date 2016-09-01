<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="shop" name="c">
<input type="hidden" value="shoponline" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<?php echo L('shop_type');?>:<select id="type" name="type">
	            <?php foreach ($type_name_array as $key => $ptvalue) {?>
				<option value='<?php echo $key?>' <?php if($type==$key) echo 'selected';?>><?php echo $ptvalue?></option>
				<?php }?>
			</select>
			<?php echo L('shop_name');?>：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
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
	<?php    if($value['status'] == 2 ){?>
		<a style="color:red" href="javascript:agree('<?php echo $value['app_id']?>')">上线</a> | 
		<a style="color:blue" href="javascript:refuse('<?php echo $value['app_id']?>')">拒绝</a>
		<?php } ?>
		<input type="hidden" value="<?php echo $value['app_id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
	<?php    if($status == 2||$status == 4||$status == ''){?>
	<tr>
	<td align="right"><?php echo L('ktv_12');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="4">同意上线</option>
		<option value="1">拒绝上线</option>
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
		if(doitem == '4'){  //同意上线
			location.href ='?m=go3c&c=shop&a=shop_agreeto&app_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '1'){  //拒绝上线 
			location.href ='?m=go3c&c=shop&a=shop_refuseto&app_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function agree(id) {
    location.href ='?m=go3c&c=shop&a=shop_agree&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function refuse(id) {
    location.href ='?m=go3c&c=shop&a=shop_refuse&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
