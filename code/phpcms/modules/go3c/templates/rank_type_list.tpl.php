<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="ktv" name="c">
<input type="hidden" value="rank_type" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=ktv&a=rank_type"><?php echo L('all_data');?></a>&nbsp;
			<?php echo L('ktv_name');?>：<input name="ranktype_name" type="text" value="<?php if(isset($ranktype_name)) echo $ranktype_name;?>" class="input-text" />&nbsp;
			<?php echo L('ktv_lang');?>：
			<select id="langtype_id" name="langtype_id">
		  	<option value='' <?php if($_GET['langtype_id']==0) echo 'selected';?>><?php echo L('ktv_all');?></option>
	      	<?php  foreach ($langInfo as $key => $var) {?>
		  	<option value='<?php echo $var['langtype_id']?>' <?php if($_GET['langtype_id']==$var['langtype_id']) echo 'selected';?>><?php echo $var['langtype_name']?></option>
		  	<?php }?>
			</select>
			<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<div class="content-menu ib-a blue line-x" style="float:right">
	<a class="add fb" href="javascript:addRankType()" ><em><?php echo L('ktv_add');?></em></a>
</div>
		</div>
		</td>
		</tr>
    </tbody>
</table>

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center">ID</th>
			<th width='50' align="center"><?php echo L('ktv_name');?></th>
			<th width='50' align="center"><?php echo L('ktv_lang');?></th>
			<th width="68" align="center">icon</th>
			<th width="68" align="center"><?php echo L('ktv_seq');?></th>
			<th width="68" align="center"><?php echo L('ktv_oper');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['ranktype_id']?></td>
	<td align="center"><?php echo $value['ranktype_name']?></td>
	<td align="center">
	<?php  foreach ($langInfo as $key => $var) {?>
	<?php if($value['langtype_id']==$var['langtype_id']) echo $var['langtype_name'];?>
	<?php }?>
	</td>
	<td align="center">
	<?php if(!empty($value['icon'])) {?>
	<a href="<?php echo $value['icon']?>" target="_blank"><img style="width:30px; border:solid 1px gray; padding:2px;" src="<?php echo $value['icon']?>" /></a></td>
	<?php }?>
	<td align="center"><?php echo $value['seq_num'];?></td>
	<td align="center">
		<a href="javascript:editRankType('<?php echo $value['id']?>')"><?php echo L('ktv_edit');?></a> | 
		<a href="javascript:deleteRankType('<?php echo $value['id']?>')"><?php echo L('ktv_del');?></a>
		<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
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
function addRankType()
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=ktv&a=addRankType&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'<?php echo L('ktv_addrank');?>',
					id:'viewOnlyDiv',
					lock:true,
					width:'400'
				});
			}
		});
}
function editRankType(id)
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=ktv&a=editRankType&id='+id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'<?php echo L('ktv_editrank');?>',
					id:'viewOnlyDiv',
					lock:true,
					width:'400'
				});
			}
		});
}
function deleteRankType(id) {
    location.href ='?m=go3c&c=ktv&a=deleteRankType&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
