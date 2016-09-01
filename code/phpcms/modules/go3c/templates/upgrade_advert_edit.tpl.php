<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
function formatType($type, $bg) {
	if ($type == 'BOOTANIMATION')
		return '开机动画';
	else if ($type == 'APP_WIZARDS')
		return '开机引导图';
	else {
		if ($bg == 0)
			return '应用启动背景图';
		else if ($bg == 1)
			return '应用重启背景图';
		else if ($bg == 2)
			return '应用切回背景图';
		else 
			return '视频暂停背景图';
	}
}
?>
<link href="<?php echo CSS_PATH?>jquery.bigcolorpicker.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.bigcolorpicker.js"></script>
<form name="myform" id="myform" action="?m=go3c&c=client&a=upgradeAdvertEdit&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data">
<input type="hidden" value="<?php echo $data['a_id']?>" name="id">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		    <th width="80"><?php echo L('advert_spid');?>:</th>
	        <td><?php echo $data['a_cid']?></td>
		</tr>
		<tr>
			<th><?php echo L('advert_pos');?></th>
			<td><?php echo $data['a_type'] == 1 ? L('advert_bot_pop') : L('advert_top_pop')?></td>
		</tr>
		<tr>
			<th><?php echo L('advert_pic');?></th>
			<td><input type="file" name="advertImg"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_content');?></th>
			<td><textarea name="advertContent" rows="4" cols="62"><?php echo $data['a_content']?></textarea></td>
		</tr>
		<tr>
			<th><?php echo L('advert_t_link');?></th>
			<td>
				<input type="radio" class="link-way" name="advertClickMode" value="0" <?php if ($data['a_click_mode'] == '0') echo "checked='checked'"?>><?php echo L('advert_t_url');?>
				<input type="radio" class="link-way" name="advertClickMode" value="1" <?php if ($data['a_click_mode'] == '1') echo "checked='checked'"?>><?php echo L('advert_ClickMode');?>
			</td>
		</tr>
		<tr class="tr-link">
		  <th><?php echo L('advert_2');?></th>
		  <td><input type="text" name="advertLink" value="<?php echo $data['a_link']?>"></td>
		</tr>
		<tr class="tr-app" style="display:none">
			<th><?php echo L('advert_app_x');?></th>
			<td>
				<?php echo L('advert_AppName');?>:<input type="text" name="advertAppName" value="<?php echo $data['a_app_name']?>"><br>
				<?php echo L('advert_AppPackage');?>:<input type="text" name="advertAppPackage" value="<?php echo $data['a_app_package']?>"><br>
				<?php echo L('advert_AppUrl');?>:<input type="text" name="advertAppUrl" value="<?php echo $data['a_app_url']?>"><br>
			</td>
		</tr>
		<tr>
			<th><?php echo L('advert_txt_co');?></th>
			<td><input type="text" name="advertColor" id="advertColor" value="<?php echo $data['a_color']?>"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_txt_sub');?></th>
			<td><input type="text" name="advertBtn" value="<?php echo $data['a_btn_txt']?>"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_dis_time');?></th>
			<td><input type="text" name="advertShowTime" value="<?php echo $data['a_show_time']?>"></td>
		</tr>
		<tr>
			<th><?php echo L('advert_roll');?></th>
			<td><select name="advertSpeed">
					 <option value="0" <?php if ($data['a_speed'] == 0) echo 'selected'?>><?php echo L('advert_Slow');?></option>
					 <option value="1" <?php if ($data['a_speed'] == 1) echo 'selected'?>><?php echo L('advert_nor');?></option>
					 <option value="2" <?php if ($data['a_speed'] == 2) echo 'selected'?>><?php echo L('advert_fast');?></option>
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
	$("#advertColor").bigColorpicker("advertColor","L",10);	
	var way = $(".link-way:checked").val();
	if (way == '1') {
		$('.tr-link').hide();
		$('.tr-app').show();
	}
})

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