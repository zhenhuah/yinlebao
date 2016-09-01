<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em><?php echo L('add')?></em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="client_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=auth&a=client_list"><?php echo L('all')?></a>&nbsp;
			 <?php echo L('auth_id')?>：<select name="ID">
            <option value='' <?php if($_GET['ID']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($ainfo as $key=>$v){?>
           		 <option value='<?php echo $v['ID']?>' <?php if($_GET['ID']==$v['ID']) echo 'selected';?>><?php echo $v['ID']?></option>
			<?php }} ?>
			</select>
			<?php echo L('term_type')?>：<select name="term_type">
            <option value='' <?php if($_GET['term_type']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($aterm as $key=>$v){?>
           		 <option value='<?php echo $v['term_type']?>' <?php if($_GET['term_type']==$v['term_type']) echo 'selected';?>><?php echo $v['term_type']?></option>
			<?php }} ?>
			</select>
			<?php echo L('page_list')?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
             <th style="width:50px;" align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width='30' align="center"><?php echo L('auth_id')?></th>
			<th width='20' align="center"><?php echo L('term_type')?></th>
			<th width='10' align="center"><?php echo L('limit_max')?></th>
			<th width='10' align="center"><?php echo L('expiry_date')?></th>
			<th width='10' align="center"><?php echo L('auth_start')?></th>
			<th width='10' align="center"><?php echo L('auth_end')?></th>
			<th width='5' align="center"><?php echo L('area')?></th>
			<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="cid" value="<?php echo $value['cid']?>"  /></td>
	<td align="center"><?php echo $value['ID']?></td>
	<td align="center"><?php echo $value['term_type']?></td>
	<td align="center"><?php echo $value['limit_max']?></td>
	<td align="center"><?php echo $value['expiry_date']?></td>
	<td align="center"><?php echo $value['auth_start']?></td>
	<td align="center"><?php echo $value['auth_end']?></td>
	<td align="center"><?php echo $value['area'];?></td>
	<td align="center">
	<a href="javascript:edit('<?php echo $value['cid']?>')"><?php echo L('edit')?></a> | 
	<a href="javascript:dodelete('<?php echo $value['cid']?>')"><?php echo L('delete')?></a>
	<input type="hidden" value="<?php echo $value['cid']?>" name="ids[]" />
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
     $("input[name=cid]:checked").each(function(){    
         arr.push({cid:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
		if(doitem == '2'){  //删除
			location.href ='?m=go3c&c=auth&a=delete_custall&cid='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function addnew() {
    location.href ='?m=go3c&c=auth&a=add_auth&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(cid) {
    location.href ='?m=go3c&c=auth&a=edit_auth&cid='+cid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(cid) {
    location.href ='?m=go3c&c=auth&a=delete_auth&cid='+cid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
