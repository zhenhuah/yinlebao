<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad_10">
<div class="common-form">
<form name="myform" action="?m=go3c&c=position_game&a=add" method="post" id="myform">
<table width="100%" class="table_form contentWrap">
<tr>
<td width="80" style="color:blue;">终端类型</td> 
<td><?php echo form::select($term_type_array, '', 'name="info[term_id]"', '请选择');?>
</tr>
<tr>
<td width="80"><?php echo L('posid_name')?></td> 
<td><input type="text" name="info[name]" value="<?php echo $info[name]?>" class="input-text" id="name"></input></td>
</tr>
<tr>
<td width="80" style="color:blue;">推荐位类型</td> 
<td><?php echo form::select($postion_type_array, '', 'name="info[type_id]"', '请选择');?>
</tr>
<tr>
<td width="80" style="color:blue;">SPID</td> 
<td>
<select name="info[spid]">
<?php foreach($spid_array as $spid){?>
<option value="<?php echo $spid['spid']?>"><?php echo $spid['spid']?></option>
<?php }	?>
</select>
</td>
</tr>
<!--tr>
<td><?php echo L('posid_modelid')?></td> 
<td><?php echo form::select($modelinfo,'','name="info[modelid]" onchange="category_load(this);"',L('posid_select_model'));?>
</tr-->
<tr style="display:none;">
<td><?php echo L('posid_catid')?></td> 
<td id="load_catid"></td>
</tr>
<tr style="display:none;">
<td><?php echo L('listorder')?></td> 
<td><input type="text" name="info[listorder]" id="listorder" class="input-text" size="5" value=""></input></td>
</tr> 
<tr>
<td>最少保存条数</td> 
<td><input type="text" name="info[minnum]" id="minnum" class="input-text" size="5" value="1"></input>条</td>
</tr>
<tr>
<td>最多保存条数</td> 
<td><input type="text" name="info[maxnum]" id="maxnum" class="input-text" size="5" value="5"></input>条</td>
</tr> 
<tr style="display:none;">
<td><?php echo L('extention_name')?></td> 
<td><input type="text" name="info[extention]" id="extention" class="input-text" size="20" value=""></input></td>
</tr>
</table>
    <div class="bk15"></div>
    <input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="dialog" id="dosubmit">
</form>
<div class="explain-col" style="display:none;">
<?php echo L('position_tips')?><br/>
<?php echo L('extention_name_tips')?>
</div>
</div></div>
</body>
</html>
<script type="text/javascript">
function category_load(obj)
{
	var modelid = $(obj).attr('value');
	$.get('?m=admin&c=position&a=public_category_load&modelid='+modelid,function(data){
			$('#load_catid').html(data);
		  });
}
</script>


