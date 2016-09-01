<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em>添加游戏</em></a>
</div>
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="games" name="c">
<input type="hidden" value="verify" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			游戏类型:<select id="type" name="type">
	            <?php foreach ($type_name_array as $ptvalue) {?>
				<option value='<?php echo $ptvalue?>' <?php if($type==$ptvalue) echo 'selected';?>><?php echo $ptvalue?></option>
				<?php }?>
			</select>
			名称：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
			<th style="width:20px;" align="right">全选<input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width='10' align="center">ID</th>
			<th width='10' align="center">平台</th>
			<th width='30' align="center">名称</th>
			<th width='20' align="center">类型</th>
			<th width='10' align="center">创建时间</th>
			<th width='10' align="center">更新时间</th>
			<th width='10' align="center">预发布时间</th>
			<th width='5' align="center">状态</th>
			<th width="70" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="id" value="<?php echo $value['id']?>"  /></td>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo game_term($value['term'])?></td>
	<td align="center"><?php echo $value['title']?></td>
	<td align="center"><?php echo $value['channel']?></td>
	<td align="center"><?php echo $value['create_time']?></td>
	<td align="center"><?php echo $value['update_time']?></td>
	<td align="center"><?php echo $value['yufabu_date']?></td>
	<td align="center"><?php echo online_status($value['status']);?></td>
	<td align="center">
	<?php    if($value['status'] == 2){?>
		<a style="color:red" href="javascript:verify_on(<?php echo $value['id']?>, '同意上线')">同意上线</a> | 
		<a style="color:red" href="javascript:verify_off(<?php echo $value['id']?>, '拒绝上线')">拒绝上线</a>
	<?php } ?>		
		<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
	<tr>
	<td align="right">全选<input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	选中项
	<select id="i_select_ckall">
		<option value="1">同意上线</option>
		<option value="2">拒绝上线</option>
		</select>
	<input type="button" onclick="doCKALL();" value="确定" />
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
     $("input[name=id]:checked").each(function(){    
         arr.push({id:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
		if(doitem == '2'){  //拒绝上线
			location.href ='?m=go3c&c=games&a=delete_allto&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '1'){  //同意上线 
			location.href ='?m=go3c&c=games&a=online_pass_all&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function verify_on(id) {
    location.href ='?m=go3c&c=games&a=verify_on&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function verify_off(id) {
    location.href ='?m=go3c&c=games&a=verify_off&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
