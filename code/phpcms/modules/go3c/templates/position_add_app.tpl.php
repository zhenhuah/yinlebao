<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<div class="pad_10">
<div class="common-form">
<form name="myform" action="?m=go3c&c=position_app&a=add" method="post" id="myform">
<input type="hidden" name="posid" value="<?php echo $posid?>"></input>
<table width="100%" class="table_form">
<tr>
<td>终端类型</td> 
<td><?php echo form::select($term_type_array,$term_id,'name="info[term_id]"', '请选择');?></td>
</tr>
<tr>
<td width="80"><?php echo L('posid_name')?></td> 
<td>
	<input type="text" name="info[name]" class="input-text" value="<?php echo $name?>" id="name"></input>
	请按照 <span style="color:red;">终端类型-名称-SPID</span> 格式修改
</td>
</tr>
<tr>
<td>推荐位类型</td> 
<td><?php echo form::select($postion_type_array,$type_id,'name="info[type_id]"', '请选择');?></td>
</tr>
<tr>
<td>SPID</td> 
<td>
<select name="info[spid]">
<?php foreach($spid_list as $sp){?>
<option value="<?php echo $sp['spid']?>"><?php echo $sp['spid']?></option>
<?php }	?>
</select>
</td>
</tr>
<tr>
<td>板子型号</td> 
<td>
<select name="info[board_type]">
<?php foreach($board_list as $sp){?>
<option value="<?php echo $sp['board_type']?>"><?php echo $sp['board_type']?></option>
<?php }	?>
</select>
</td>
</tr>
<!--tr>
<td><?php echo L('posid_modelid')?></td> 
<td><?php echo form::select($modelinfo,$modelid,'name="info[modelid]" onchange="category_load(this);"', L('choose_model'));?></td>
</tr-->
<tr style="display:none;">
<td><?php echo L('posid_catid')?></td> 
<td id="load_catid"><?php echo form::select_category('',$catid,'name="info[catid]"',L('please_select_parent_category'));?></td>
</tr>

<tr style="display:none;">
<td><?php echo L('listorder')?></td> 
<td><input type="text" name="info[listorder]" class="input-text" size="5" value="<?php echo $listorder?>"></input></td>
</tr> 
<tr>
<td>最少保存条数</td> 
<td><input type="text" name="info[minnum]" id="minnum" class="input-text" size="5" value="<?php echo $minnum?>"></input>条</td>
</tr> 
<tr>
<td>最多保存条数</td> 
<td><input type="text" name="info[maxnum]" id="maxnum" class="input-text" size="5" value="<?php echo $maxnum?>"></input>条</td>
</tr> 
<tr style="display:none;">
<td><?php echo L('extention_name')?></td> 
<td><input type="text" name="info[extention]" id="extention" class="input-text" size="30" value="<?php echo $extention?>"></input></td>
</tr> 
</table>

    <div class="bk15"></div>
    <input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="dialog" id="dosubmit">
</form>
<div class="explain-col" style="display:none;">
<?php echo L('position_tips')?><br/>
<?php echo L('extention_name_tips')?>
</div>
</div>
</div>
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


