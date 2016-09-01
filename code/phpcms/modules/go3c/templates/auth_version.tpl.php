<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');

function get_version($v) {
	$res = '';
	if ($v == 'debug') {
		$res = '调试';
	} else if ($v == 'demo') {
		$res = '演示';
	} else if ($v == 'commercial') {
		$res = '正式';
	} else if ($v == 'manu') {
		$res = '工厂';
	}
	return $res;
}

?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="version" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=auth&a=version"><?php echo L('all')?></a>&nbsp;
			 <?php echo L('auth_id')?>：<select name="ID">
            <option value='' <?php if($_GET['ID']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($spid_list as $v){?>
           		 <option value='<?php echo $v['ID']?>' <?php if($_GET['ID']==$v['ID']) echo 'selected';?>><?php echo $v['ID']?></option>
			<?php }} ?>
			</select>
			<?php echo L('term_type')?>：<select name="term_type">
            <option value='' <?php if($_GET['term_type']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($aterm as $key=>$v){?>
           		 <option value='<?php echo $v['term_type']?>' <?php if($_GET['term_type']==$v['term_type']) echo 'selected';?>><?php echo $v['term_type']?></option>
			<?php }} ?>
			</select>
			RMAC: <input type="text" name="rmac" value="<?php if ($_GET['rmac']) echo $_GET['rmac']?>" />&nbsp;
			排序字段：<select name="field">
				<option value='auth_time' <?php if($_GET['field']=='auth_time') echo 'selected';?>>鉴权时间</option>
				<option value='area' <?php if($_GET['field']=='area') echo 'selected';?>>区域</option>
			</select>&nbsp;
            排序方法：<select name="order">
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
            <th style="width:10px;" align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width='30' align="center"><?php echo L('auth_id')?></th>
			<th width='20' align="center"><?php echo L('term_type')?></th>
			<th width='20' align="center">调试模式</th>
			<th width='20' align="center">遥控器</th>
			<th width='40' align="center">局域网</th>
			<th width='40' align="center">RMAC</th>
			<th width='10' align="center">版本</th>
			<th width='10' align="center">区域</th>
			<th width='30' align="center">鉴权时间</th>
			<th width='150' align="center">版本描述</th>
			<th width='20' align="center">允许重复注册</th>
			<th width="30" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){
	//$notice = substr($value['version_notice'], 0, 20);
	?>   
	<tr>
		<td align="center"><input type="checkbox" name="vid" value="<?php echo $value['vid']?>"  /></td>
		<td align="center"><?php echo $value['ID']?></td>
		<td align="center"><?php echo $value['term_type']?></td>
		<td align="center"><?php if ($value['debug_mode']) echo '是'; else echo '否'?></td>
		<td align="center"><?php echo $value['remote_name'] ? $value['remote_name'] : '默认'?></td>
		<td align="center"><?php echo $value['localip']?></td>
		<td align="center"><?php echo $value['rmac']?></td>
		<td align="center"><?php echo get_version($value['version'])?></td>
		<td align="center"><?php echo $value['area']?></td>
		<td align="center"><?php echo $value['auth_time']?></td>
		<td align="center"><p style="height: 20px; overflow: hidden; width: 100%;"><?php echo $value['version_notice']?></p></td>
		<td align="center"><?php if ($value['reg_repeat']) echo '是'; else echo '否' ?></td>
		<td align="center">
		<a href="javascript:edit('<?php echo $value['vid']?>')"><?php echo L('edit')?></a>
		</td> 
	</tr>
	<?php }} ?>
	<tr>
	<td align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="debug">调试</option>
		<option value="demo">演示</option>
		<option value="commercial">正式</option>
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
		if(doitem == 'debug'){  //调试
			location.href ='?m=go3c&c=auth&a=update_clientall&vid='+str+'&doitem='+doitem+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == 'demo'){  //演示 
			location.href ='?m=go3c&c=auth&a=update_clientall&vid='+str+'&doitem='+doitem+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == 'commercial'){  //正式
			location.href ='?m=go3c&c=auth&a=update_clientall&vid='+str+'&doitem='+doitem+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);	
			}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function edit(vid) {
    location.href ='?m=go3c&c=auth&a=edit_version&vid='+vid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
