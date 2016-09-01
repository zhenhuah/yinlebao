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
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="show_lesson" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
			<select name="plan" id="plan" >
			<option value="">请选择</option>
			<?php if(is_array($plan_data) ){foreach($plan_data as $v){?> 
				<option value="<?php echo $v['name'] ?>" <?php if($plan == $v['name']){ echo 'selected';}?> >
					<?php echo $v['name'] ?>
				</option> 	
			<?php }} ?>	
			</select>
			课程名称：<input id= "title" name="title" type="text" value="<?php if(isset($title)) echo $title;?>" class="input-text" size="15"/>&nbsp;
			<?php echo L('每页显示');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			
			<input type="button" value="添加" onclick="addnew()" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<?php }?>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
            	<th style="width:10px;" align="left"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
				<th width='30' align="left">名称</th>
				<th width='30' align="left">更新时间</th>
				<th width='20' align="left">套餐类型</th>
				<th width='20' align="left">期数</th>
				<th width="70" align="left"><?php echo L('operation');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $v){?>  
	<tr>
		<td align="left"><input type="checkbox" name="id" value="<?php echo $v['id']?>"/></td>
		<td align="left"><?php echo $v['title']?></td>
		<td align="left"><?php echo $v['created_time'] ?></td>
		<td align="left"><?php echo $v['name'] ?></td>
		<td align="left"><?php echo $v['episode']?></td>
		<td align="left">
			<div class="fabu">
				<a href="javascript:edit('<?php echo $v['id']?>')"><?php echo L('编辑')?></a>
				<a href="javascript:fabu('<?php echo $v['id']?>')" onclick="return confirm('确定发布么？')">发布</a>
				<a href="javascript:dodelete('<?php echo $v['id']?>')"onclick="return confirm('确定删除么？')"><?php echo L('del')?></a>
			</div>
		</td>
	</tr>
	<?php }} ?>
	<tr>
	<td align="left"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="1">发布</option>
		<option value="2"><?php echo L('del');?></option>
	</select>
	<input type="button" onclick="doCKALL();"  value="<?php echo L('determine');?>" />
	</td>
	</tr>
</tbody>
  	</table>	
	</div>
    <div id="pages"><?php echo $multipage;?> </div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function clickCKB(a){
	$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}
function doCKALL(){
	var doitem = $('#i_select_ckall').val() ;
	 var arr =[];    
     var str='';
     $("input[name=id]:checked").each(function(){    
         arr.push({vid:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
    	//event.returnValue = confirm("删除是不可恢复的，你确认要删除吗？");
    	if(doitem == '1'){  
    		if(window.confirm('确定发布吗')){//发布
			location.href ='';
			alert("发布成功");
			}
    	}	
    	if(doitem == '2'){  
    		if(window.confirm('确定删除吗')){//删除
			location.href ='?m=go3c&c=tvuser&a=delete_lessonall&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
			}
    	}	
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function fabu(id){
	alert("发布成功");
}
function addnew(){
	location.href ='?m=go3c&c=tvuser&a=addLesson&catid=&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(id) {
    location.href ='?m=go3c&c=tvuser&a=delete_lesson&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(id){
	location.href ='?m=go3c&c=tvuser&a=lesson_edit&id='+id+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
}
</script>

<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
</script>
