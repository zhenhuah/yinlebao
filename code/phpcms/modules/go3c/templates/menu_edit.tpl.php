<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<style type="text/css">
.demo{width:450px}
.select_side{float:left; width:200px}
select{width:180px; height:150px}
.select_opt{float:left; width:40px; height:100%; margin-top:36px}
.select_opt p{width:26px; height:26px; margin-top:6px; background:url(<?php echo IMG_PATH;?>arr.gif) no-repeat; cursor:pointer; text-indent:-999em}
.select_opt p#toright{background-position:2px 0}
.select_opt p#toleft{background-position:2px -22px}
.sub_btn{clear:both; height:42px; line-height:42px; padding-top:10px; text-align:center}
</style>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="menu_edit_do" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<?php echo L('auth_id')?>: <?php echo $_GET['cid']?>
			<input type="hidden" value="<?php echo $_GET['cid']?>" id="cid" name="cid">
		</div>
		</td>
		</tr>
    </tbody>
</table>
  <div class="demo">
     <div class="select_side">
     <p><?php echo L('ktvmenu_menu_blank')?></p>
     <select id="selectL" name="selectL" multiple="multiple">
     	<?php foreach ($menuDisabled as $k => $v) {?>
     		<option value="<?php echo $k?>"><?php echo $v?></option>
     	<?php }?>
     </select>
     </div>
     <div class="select_opt">
        <p id="toright" title="添加">&gt;</p>
        <p id="toleft" title="移除">&lt;</p>
     </div>
     <div class="select_side">
     <p><?php echo L('ktvmenu_menu_show')?></p>
     <select id="selectR" name="selectR" multiple="multiple">
     	<?php foreach ($menuArr as $k => $v) {?>
     		<option value="<?php echo $k?>"><?php echo $v?></option>
     	<?php }?>
     </select>
     </div>
     <div class="sub_btn"><input type="submit" id="sub" value="<?php echo L('ktv_sub')?>" /></div>
     <input type="hidden" value="" id="menuStr" name="menuStr">
  </div>

</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
$(function(){
    var leftSel = $("#selectL");
	var rightSel = $("#selectR");
	$("#toright").bind("click",function(){		
		leftSel.find("option:selected").each(function(){
			$(this).remove().appendTo(rightSel);
		});
	});
	$("#toleft").bind("click",function(){	
		rightSel.find("option:selected").each(function(){
			if ($(this).attr('value') != 'dgt')
				$(this).remove().appendTo(leftSel);
		});
	});
	leftSel.dblclick(function(){
		$(this).find("option:selected").each(function(){
			$(this).remove().appendTo(rightSel);
		});
	});
	rightSel.dblclick(function(){
		$(this).find("option:selected").each(function(){
			if ($(this).attr('value') != 'dgt')
				$(this).remove().appendTo(leftSel);
		});
	});
	$("#sub").click(function(){
		var selVal = [];
		rightSel.find("option").each(function(){
			selVal.push(this.value);
		});
		console.log(selVal);
		selVals = selVal.join(",");
		//selVals = rightSel.val();
		if (selVals == '')
			selVals = 'dgt';
		$('#menuStr').val(selVals);
	});
});
</script>
