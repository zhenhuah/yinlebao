<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="infor" name="c">
<input type="hidden" value="infor_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=infor&a=infor_list">全部数据</a>&nbsp;
			类型:<select id="type" name="type">
	            <?php foreach ($type_name_array as $key => $ptvalue) {?>
				<option value='<?php echo $key?>' <?php if($_GET['type_name']==$key) echo 'selected';?>><?php echo $ptvalue?></option>
				<?php }?>
			</select>
			名称：<input name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" />&nbsp;
			是否上线:<select id="online_status" name="online_status">
			<option value=''>全部</option>
			<option value='14' <?php if($online_status==14) echo 'selected';?>>已上线</option>
			<option value='1' <?php if($online_status==1) echo 'selected';?>>未上线</option>
			</select>
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<div class="content-menu ib-a blue line-x" style="float:right">
	<a class="add fb" href="javascript:addnew()" ><em>添加</em></a>
</div>
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
			<th width="50" align="center">ID</th>
			<th width='108' align="center">名称</th>
			<th width='28' align="center">资讯类型</th>
			<th width='50' align="center">更新时间</th>
			<th width='50' align="center">缩列图</th>
			<th width="68" align="center">状态</th>
			<th width="148" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="id" value="<?php echo $value['id']?>"  /></td>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['title']?></td>
	<td align="center"><?php echo $type_name_array[$value['type']]?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $value['updatetime'])?></td>
	<td align="center"><img src="<?php echo $value['thumb'];?>" style="width:50px;" /></td>
	<td align="center"><?php echo online_status($value['online_status']);?></td>
	<td align="center">
	<?php    if($value['online_status'] == 1||$value['online_status'] == 12){?>
	<a href="javascript:edit('<?php echo $value['id']?>')">编辑</a> | 
		<a style="color:green" href="javascript:infor_pass('<?php echo $value['id']?>')">申请审核</a> |
		<a href="javascript:dodelete('<?php echo $value['id']?>')">删除</a>
		<?php } ?>
	<?php    if($value['online_status'] == 14){?>
	<a style="color:red" href="javascript:infor_off(<?php echo $value['id']?>, '申请下线')">下线</a>
	<?php } ?>		
		<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
	<?php    if($online_status == 1||$online_status == 14||$online_status == ''){?>
	<tr>
	<td align="right">全选<input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	选中项
	<select id="i_select_ckall">
		<?php    if($online_status == 1||$online_status == ''){?>
		<option value="1">上线</option>
		<option value="3">删除</option>
		<?php } ?>
		<?php    if($online_status == 14){?>
		<option value="2">下线</option>
		<?php } ?>	
		</select>
	<input type="button" onclick="doCKALL();" value="确定" />
	</td>
	</tr>
	<?php } ?>
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
		if(doitem == '2'){  //下线
			location.href ='?m=go3c&c=infor&a=delete_allto&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '1'){  //上线 
			location.href ='?m=go3c&c=infor&a=online_pass_all&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '3'){  //删除
			location.href ='?m=go3c&c=infor&a=online_error&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);	
			}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function edit(id) {
    location.href ='?m=admin&c=area&a=editinfor&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=infor&a=deleteinfor&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function addnew(){
	location.href ='?m=admin&c=area&a=addinfor&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function infor_pass(id) {
    location.href ='?m=go3c&c=infor&a=infor_pass&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function infor_off(id) {
    location.href ='?m=go3c&c=infor&a=infor_off&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
