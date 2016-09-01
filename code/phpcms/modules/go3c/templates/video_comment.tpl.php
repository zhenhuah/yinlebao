<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="video" name="c">
<input type="hidden" value="showVideoComment" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $_GET['id']?>" name="id">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a style="color:blue" href="?m=go3c&c=auth&a=showVideoComment"><?php echo L('all')?></a>&nbsp;
		    用户名: <input type="text" name="user" value="<?php if (isset($_GET['user'])) echo $_GET['user']?>" >&nbsp;
		    排序字段：<select name="field">
				<option value='c_time' <?php if($_GET['field']=='c_time') echo 'selected';?>>评论时间</option>
				<option value='c_user' <?php if($_GET['field']=='c_user') echo 'selected';?>>用户名</option>
			</select>&nbsp;
            排序顺序: <select name="order">
				<option value='DESC' <?php if($_GET['order']=='DESC') echo 'selected';?>>降序</option>
				<option value='ASC' <?php if($_GET['order']=='ASC') echo 'selected';?>>升序</option>
			</select>&nbsp;
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
            <th style="width:10%;" align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width='10%' align="center">用户</th>
			<th width='10%' align="center">时间</th>
			<th width='70%' align="center">内容</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){
	?>   
	<tr>
		<td align="center"><input type="checkbox" name="vid" value="<?php echo $value['c_id']?>"  /></td>
		<td align="center"><?php echo $value['c_user']?></td>
		<td align="center"><?php echo $value['c_time']?></td>
		<td align="center"><?php echo $value['c_content']?></td>
	</tr>
	<?php }} ?>
	<tr>
	<td align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="deleteAll">删除</option>
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
     $("input[name=vid]:checked").each(function(){    
         arr.push({vid:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
		if(doitem == 'deleteAll'){
			location.href ='?m=go3c&c=video&a=deleteVideoComment&vid='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
</script>
