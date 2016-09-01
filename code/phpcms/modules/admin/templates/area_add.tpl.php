<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<div class="table-list" id="load_priv">
<form name="myform" id="myform" action="?m=admin&c=area&a=addareado&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return addarea();">
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<tr>
<th width="90">区域ID</th>
<td>
<input type="text" value="" name="id" id="id" size="25" >
<span id="ad_id" style="display:none"><font color="red">请正确填写区域id</font>
</td>
</tr>
<tr>
<th width="90">区域名称</th>
<td>
<input type="text" value="" name="name" id="name" size="25" >
<span id="ad_name" style="display:none"><font color="red">请填写区域名称</font>
</td>
</tr>
<tr>
<th width="90">区域分级</th>
<td>
<select name="mf" id="mf">
		  <option value="0">请选择</option>
		  <option value="m">省网</option>
		  <option value="c">市网</option>
		  <option value="a">县网</option>
		  </select>
<span id="ad_mf" style="display:none"><font color="red">请选择区域分级</font>
</td>
</tr>
<tr>
<th width="90">上级区域名</th>
<td>
<select name="fname" id="fname">
		<option value="0">请选择</option>
		<?php if(is_array($limitInfo)) foreach ($limitInfo as $key=>$tvalue) {?>
		<option value='<?php echo $tvalue['id']?>'><?php echo $tvalue['name']?></option>
		<?php }?>
		</select>
<span id="ad_fname" style="display:none"><font color="red">请填写上级区域名称</font>
</td>
</tr>
</tbody>
</table>
<div class="btn"><input type="submit"  class="button" name="dosubmit" id="dosubmit" value="<?php echo L('submit');?>" /></div>
</form>
<tr>注释:区域id->省市县(00-00-00) 如:南京市->0301,玄武区->030101</tr>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
//切换省市获取相应的数据
$('#mf').change(function(){
	var mf = $.trim($('#mf').val());
	if(mf=='c'||mf=='a'){
		$.ajax({ 
	        type: "post",
	        url: 'index.php?m=admin&c=area&a=area_sellist&mf='+mf+'&pc_hash='+pc_hash,
	        dataType: "html", 
	        success: function (data) { 
		        if(data){
		        	var data = eval('('+data+')');
            		$('#fname').find('option').remove();
            		$('#singerlist').html('<option value="" selected>请选择</option>');
            		for(var i=0;i<data.length;i++)
            		{	
            		    var item = data[i];
            		    item.name;
            		    tt="<option value="+item.name+">"+item.name+"</option>";
            		    $('#fname').append(tt);
            		}
			    }
	        }
		})
	}else if(mf==''){
		alert('请选择类型再操作!');	
	}
})
//添加信息表单验证
function addarea()
{
	var id = $.trim($('#id').val());
	if (id != '')
	{
		$('#ad_id').hide();
	}else{
		$('#ad_id').show();
		return false;
	}
	var name = $.trim($('#name').val());
	if (name != '')
	{
		$('#ad_name').hide();
	}else{
		$('#ad_name').show();
		return false;
	}
	var mf = $.trim($('#mf').val());
	if (mf != '0')
	{
		$('#ad_mf').hide();
	}else{
		$('#ad_mf').show();
		return false;
	}
	if(mf !='m'){
		var fname = $.trim($('#fname').val());
		if (fname != '')
		{
			$('#ad_fname').hide();
		}else{
			$('#ad_fname').show();
			return false;
		}
	}
	return true;
}
</script>
</body>
</html>
