<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>jquery.bigcolorpicker.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.bigcolorpicker.js"></script>
<style type="text/css">
.demo{width:450px}
.select_side{float:left; width:200px}
#selectL,#selectR{width:180px; height:150px}
.select_opt{float:left; width:40px; height:100%; margin-top:36px}
.select_opt p{width:26px; height:26px; margin-top:6px; background:url(<?php echo IMG_PATH;?>arr.gif) no-repeat; cursor:pointer; text-indent:-999em}
.select_opt p#toright{background-position:2px 0}
.select_opt p#toleft{background-position:2px -22px}
.sub_btn{clear:both; height:42px; line-height:42px; padding-top:10px; text-align:center}
</style>
<form name="myform" id="myform" action="?m=go3c&c=client&a=upgradeAdvertAdd&pc_hash=<?php echo $_SESSION['pc_hash'];?>" onSubmit="return verifyForm()" method="post" enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80"><?php echo L('advert_spid');?>:</th>
			<td>
				  <div class="demo">
				     <div class="select_side">
				     <p><?php echo L('advert_fav_spid');?></p>
				     <?php 
						if($_SESSION['roleid'] != '1')
							$cidarr = explode(',', $_SESSION['spid']);
						else
							$cidarr = array('GO3CKTV','GO3CKTVTEST','ZYDQ','WMMY','FSTLM','SSDQ');
				     ?>
				     <select id="selectL" multiple="multiple">
				     	<?php foreach ($cidarr as $v) {?>
				     		<option value="<?php echo $v?>"><?php echo $v?></option>
				     	<?php }?>
				     </select>
				     </div>
				     <div class="select_opt">
				        <p id="toright" title="添加">&gt;</p>
				        <p id="toleft" title="移除">&lt;</p>
				     </div>
				     <div class="select_side">
				     <p><?php echo L('advert_chec_spid');?></p>
				     <select id="selectR" multiple="multiple">
				     	
				     </select>
				     </div>
				     <input type="hidden" value="" id="cidStr" name="cidStr">
				  </div>
			</td>
		</tr>
		<tr>
			<th><?php echo L('advert_pos');?></th>
			<td>
				<select name="advertType" id="advertType">
					<option value="0"><?php echo L('advert_top_pop');?></option>
					<option value="1"><?php echo L('advert_bot_pop');?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo L('advert_pic');?></th>
			<td><input type="file" name="advertImg"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_content');?></th>
			<td><textarea name="advertContent" rows="4" cols="62"></textarea></td>
		</tr>
		<tr>
			<th><?php echo L('advert_t_link');?></th>
			<td>
				<input type="radio" class="link-way" name="advertClickMode" value="0" checked="checked"> <?php echo L('advert_t_url');?>
				<input type="radio" class="link-way" name="advertClickMode" value="1"><?php echo L('advert_ClickMode');?>
			</td>
		</tr>
		<tr class="tr-link">
		  <th><?php echo L('advert_2');?></th>
		  <td><input type="text" name="advertLink"></td>
		</tr>
		<tr class="tr-app" style="display:none">
			<th><?php echo L('advert_app_x');?></th>
			<td>
				<?php echo L('advert_AppName');?>:<input type="text" name="advertAppName"><br>
				<?php echo L('advert_AppPackage');?>:<input type="text" name="advertAppPackage"><br>
				<?php echo L('advert_AppUrl');?>:<input type="text" name="advertAppUrl"><br>
			</td>
		</tr>
		<tr>
			<th><?php echo L('advert_txt_co');?></th>
			<td><input type="text" name="advertColor" id="advertColor"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_txt_sub');?></th>
			<td><input type="text" name="advertBtn"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_dis_time');?></th>
			<td><input type="text" name="advertShowTime"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_roll');?></th>
			<td><select name="advertSpeed">
					 <option value="0"><?php echo L('advert_Slow');?></option>
					 <option value="1"><?php echo L('advert_nor');?></option>
					 <option value="2"><?php echo L('advert_fast');?></option>
					 </select></td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>

<div class="bk10"></div>
<div  style="float:right">	
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('ktv_sub');?>" />&nbsp;
</div> 

</form>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
$(function(){
	//color picker
	$("#advertColor").bigColorpicker("advertColor","L",10);	
	
	var leftSel = $("#selectL");
	var rightSel = $("#selectR");
	$("#toright").bind("click",function(){		
		leftSel.find("option:selected").each(function(){
			$(this).remove().appendTo(rightSel);
		});
	});
	$("#toleft").bind("click",function(){	
		rightSel.find("option:selected").each(function(){
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
			$(this).remove().appendTo(leftSel);
		});
	});
})

function verifyForm() {
	var selVal = [];
	var rightSel = $("#selectR");
	rightSel.find("option").each(function(){
		selVal.push(this.value);
	});
	selVals = selVal.join(",");
	$('#cidStr').val(selVals);
}

$('.link-way').click(function(){
	var v = $(this).val();
	if (v == 0) {
		$('.tr-link').show();
		$('.tr-app').hide();
	} else {
		$('.tr-link').hide();
		$('.tr-app').show();
	}
})
</script>
</body>
</html>