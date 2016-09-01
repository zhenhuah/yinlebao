<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<div class="pad_10">
<div class="common-form">
<form name="myform" action="?m=admin&c=position&a=edit" method="post" id="myform">
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
<!--tr>
<td>推荐位类型</td> 
<td><?php echo form::select($postion_type_array,$type_id,'name="info[type_id]"', '请选择');?></td>
</tr-->
<tr>
<td>SPID</td> 
<td>
<select name="info[spid]">
<?php foreach($spid_array as $sp){?>
<option value="<?php echo $sp['spid']?>" <?php echo (($spid==$sp['spid']) ? 'selected="selected"' : '')?>><?php echo $sp['spid']?></option>
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
<td><input type="text" name="info[minnum]" id="minnum" class="input-text" size="5" value="<?php echo $minnum?>"></input>最少保存条数</td>
</tr> 
<tr>
<td><?php echo L('maxnum')?></td> 
<td><input type="text" name="info[maxnum]" id="maxnum" class="input-text" size="5" value="<?php echo $maxnum?>"></input><?php echo L('posid_num')?></td>
</tr> 
<tr>
<td>类型ID</td> 
<td><input type="text" name="info[type_id]" id="minnum" class="input-text" size="5" value="<?php echo $type_id?>"></input></td>
</tr>
<tr>
<td>所属区域</td> 
<td>
<select id="areasj" name="infoa[area_ids]">
<option value=''>请选择</option>
<?php foreach ($area_list as $key=>$tvalue) {?>
<option value='<?php echo $tvalue['id']?>'><?php echo $tvalue['name']?></option>
<?php }?>
</select>
<select id="areacj" name="infoa[area_idc]" style="display:none;">
<option value=''>请选择</option>
<?php foreach ($area_list as $key=>$tvalue) {?>
<option value='<?php echo $tvalue['id']?>'><?php echo $tvalue['name']?></option>
<?php }?>
</select>
<select id="areaqj" name="infoa[area_idq]" style="display:none;">
<option value=''>请选择</option>
<?php foreach ($area_list as $key=>$tvalue) {?>
<option value='<?php echo $tvalue['id']?>'><?php echo $tvalue['name']?></option>
<?php }?>
</select>
</td>
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
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function category_load(obj)
{
	var modelid = $(obj).attr('value');
	$.get('?m=admin&c=position&a=public_category_load&modelid='+modelid,function(data){
			$('#load_catid').html(data);
		  });
}
$('#areasj').change(function(){
	var areasj = $.trim($('#areasj').val());
	if (areasj!=''){
		$.ajax({ 
	        type: "post",
	        url: 'index.php?m=admin&c=position&a=selarea&areaid='+areasj+'&pc_hash='+pc_hash,
	        dataType: "html", 
	        success: function (data) { 
	        	$("#areacj").show();
	        	var data = eval('('+data+')');
	        	$('#areacj').html('<option value="" selected>请选择</option>');
	    		for(var i=0;i<data.length;i++)
	    		{	
	    		    var item = data[i];
	    		    item.id;
        		    item.name;
	    		    tt="<option value="+item.id+">"+item.name+"</option>";
	    		    $('#areacj').append(tt);
	    		}
	        }
	    });
		}
})
$('#areacj').change(function(){
	var areacj = $.trim($('#areacj').val());
	if (areacj!=''){
		$.ajax({ 
	        type: "post",
	        url: 'index.php?m=admin&c=position&a=selarea&areaid='+areacj+'&pc_hash='+pc_hash,
	        dataType: "html", 
	        success: function (data) { 
	        	$("#areaqj").show();
	        	var data = eval('('+data+')');
	        	$('#areaqj').html('<option value="" selected>请选择</option>');
	    		for(var i=0;i<data.length;i++)
	    		{	
	    		    var item = data[i];
	    		    item.id;
        		    item.name;
	    		    tt="<option value="+item.id+">"+item.name+"</option>";
	    		    $('#areaqj').append(tt);
	    		}
	        }
	    });
		}
})
</script>


