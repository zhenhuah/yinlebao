<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<!--  
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加项目</em></a>
</div>
-->
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="proclient" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			有线MAC地址：<input name="mac_wire" type="text" value="<?php if(isset($mac_wire)) echo $mac_wire;?>" class="input-text" size="18"/>&nbsp;
			设备id：<input name="device_id" type="text" value="<?php if(isset($device_id)) echo $device_id;?>" class="input-text" size="18"/>&nbsp;
			设备sn号：<input name="boxsn" type="text" value="<?php if(isset($boxsn)) echo $boxsn;?>" class="input-text" size="18"/>&nbsp;
			版本：<input name="version" type="text" value="<?php if(isset($version)) echo $version;?>" class="input-text" size="10"/>&nbsp;
			<?php echo L('each page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
            <th style="width:10px;" align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width='10' align="center">设备id</th>
			<th width='30' align="center">有线MAC</th>
			<!--<th width='20' align="center">无限MAC</th>-->
			<th width='10' align="center">设备guid</th>
			<th width='10' align="center">设备sn号</th>
			<th width='10' align="center">版本</th>
			<th width='10' align="center">时间</th>
			<th width="70" align="center"><?php echo L('operation');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><input type="checkbox" name="id" value="<?php echo $value['id']?>"/></td>
	<td align="center"><?php echo $value['device_id']?></td>
	<td align="center"><?php echo $value['mac_wire']?></td>
	<!--<td align="center"><?php echo $value['mac_address']?></td>-->
	<td align="center"><?php echo $value['guid']?></td>
	<td align="center"><?php echo $value['boxsn']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$value['first_time'])?></td>
	<td align="center">
	<a href="javascript:dodelete('<?php echo $value['id']?>')"onclick="return confirm('确定删除么？')"><?php echo L('del')?></a>
	<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
	<tr>
	<td align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="2"><?php echo L('del');?></option>
		</select>
	<input type="button" onclick="doCKALL();" value="<?php echo L('determine');?>" />
	</td>
	</tr>
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
     $("input[name=id]:checked").each(function(){    
         arr.push({vid:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
		if(doitem == '2'){  
    		if(window.confirm('确定删除吗')){//删除
			location.href ='?m=go3c&c=auth&a=delete_clientall&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
			}
    	}	
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function edit(id) {
    location.href ='?m=go3c&c=auth&a=edit_cilent&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=auth&a=delete_cilent&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
