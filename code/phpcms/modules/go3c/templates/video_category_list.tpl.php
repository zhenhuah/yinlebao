<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="video" name="c">
<input type="hidden" value="category" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
           		<?php echo L('type');?>：<select name="type" onchange="change_position(this)">
				<option value='1' <?php if($_GET['type']==1) echo 'selected';?>><?php echo L('darea');?></option>
				<option value='2' <?php if($_GET['type']==2) echo 'selected';?>><?php echo L('clumn');?></option>
				<option value='3' <?php if($_GET['type']==3) echo 'selected';?>><?php echo L('year');?></option>
			</select>
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div style="text-align:right;">
<?php echo L('name');?>：<input id="new_word" type="text" value="" class="input-text" />
 <?php echo L('type');?>：<select id="new_type" disabled="true">
	<option value='1'  <?php if($_GET['type']==1) echo 'selected';?> ><?php echo L('darea');?></option>
	<option value='2'  <?php if($_GET['type']==2) echo 'selected';?> ><?php echo L('clumn');?></option>
	<option value='3'  <?php if($_GET['type']==3) echo 'selected';?> ><?php echo L('year');?></option>
</select>
<input type="button" name="add" class="button" onclick="addnew()" value="<?php echo L('add');?>" />
</div>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><?php echo L('number');?></th>
			<th width="120" align="center"><?php echo L('import_time');?></th>
			<th width='68' align="center"><?php echo L('name');?></th>
			<th width='50' align="center"><?php echo L('type');?></th>
			<th width='50' align="center"><?php echo L('status');?></th>
			<?php foreach($belong_type_array as $key => $ptvalue){?>
			<th width='30' align="center"><?php echo $ptvalue; ?></th>
			<?php } ?>
			<th width="68" align="center"><?php echo L('operation');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){$i = 1+($page-1)*$pagesize;foreach($data as $channel){
	$belong_array = explode(",",$channel['belong']);
	?>   
	<tr>
	<td align="center"><?php echo $i++?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<td align="center"><?php echo $channel['title']?></td>
	<td align="center"><?php echo cattype2name($channel['type'])?></td>
	<td align="center"  id='<?php echo 'status'.$channel['id']?>'><?php echo online_status($channel['online_status']);?></td>
	<?php foreach($belong_type_array as $key => $ptvalue){?>
	<td align="center"><input type="checkbox" <?php if($_GET['type']!=2) echo "disabled='true'";?> <?php if(in_array($key,$belong_array)) echo "checked='checked' ";?> value='<?=$key?>' name='<?php echo 'belongs'.$channel['id']?>'/></td>
	<?php } ?>
	<td align="center" id='<?php echo 'action'.$channel['id']?>'>
		<?php //if($channel['online_status'] < 10 || $channel['online_status'] == 12){?>
		<a style="color:green" href="javascript:dopass(<?php echo $channel['id']?>, '<?php echo L('application_for');?>')"><?php echo L('application_for');?></a>
		<?php //}?>
		<!--a style="color:red" href="javascript:dorefuse(<?php echo $channel['id']?>, '申请删除')">申请删除</a-->
	</td>
	<input type="hidden" value="<?php echo $channel['id']?>" name="ids[]" />
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript" src="statics/js/yzyscript.js"></script>
<script type="text/javascript">
var selected_typeid = "1";
function change_position(obj){
	//selected_typeid  = obj.options[obj.options.selectedIndex].value;
	document.myform.submit();
}
function dopass(id, title) {
     var str='';
     $("input[name='belongs"+id+"']").each(function(){ 
           if(this.checked) str+=$(this).val()+",";        
     })
   	str=str.replace(/,$/gi,"");
  //  location.href ='?m=go3c&c=video&a=category_pass&id='+id+'&belong='+str+'&pc_hash='+pc_hash
//	+'&goback='+BASE64.encode(location.search);
	isurl = '?m=go3c&c=video&a=category_pass&id='+id+'&belong='+str+'&pc_hash='+pc_hash;

	loadJson(isurl,function(){
		//document.getElementById('dosubmit').click();
		$('#status'+id).html('已提交审核');
		$('#action'+id).html('');
	}) ;
}
function dorefuse(id, title) {
    location.href ='?m=go3c&c=verify&a=category_refuse&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
   	if($('#new_word').val().length>0){
	    location.href ='?m=go3c&c=video&a=category_add&word='+$('#new_word').val()+"&type="+$('#new_type').val()+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}else
	alert("名称不能为空");
}
</script>
