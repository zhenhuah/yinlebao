<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
function formatforce($flag) {
	if ($flag == -1) {
		$force = '禁止升级';
	} else if ($flag == 0) {
		$force = '非强制升级';
	} else if ($flag == 1) {
		$force = '强制升级';
	}
	return $force;
}
function formatstatus($status) {
	if ($status == -1) {
		$res = '审核未通过';
	} else if ($status == 0) {
		$res = '历史版本';
	} else if ($status == 1) {
		$res = '最新版本';
	} else if ($status == 2) {
		$res = '待审核';
	}
	return $res;
}
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="operation_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<a href="?m=go3c&c=client&a=operation_list"><?php echo L('all')?></a>&nbsp;
			<?php  if ($_SESSION['upgrade_role'] == 'ALL') {?>
			升级类型：<select id="upgrade_type" name="upgrade_type">
						<option value="">全部</option>
						<option value="APP_BOOT" <?php if ($_GET['upgrade_type'] == 'APP_BOOT') echo 'selected'?>>开机数据</option>
						<option value="APP_HOTKEY" <?php if ($_GET['upgrade_type'] == 'APP_HOTKEY') echo 'selected'?>>业务数据</option>
						<option value="SONG_HOT" <?php if ($_GET['upgrade_type'] == 'SONG_HOT') echo 'selected'?>>歌曲数据</option>
					</select>
			<?php }?>
			<?php echo L('term_type')?>：<select name="term_type">
						<option value="">全部</option>
						<option value="A20" <?php if ($_GET['term_type'] == 'A20') echo 'selected'?>>A20</option>
						<option value="MX8726" <?php if ($_GET['term_type'] == 'MX8726') echo 'selected'?>>MX8726</option>
						<option value="A31S" <?php if ($_GET['term_type'] == 'A31S') echo 'selected'?>>A31S</option>
						</select>
			 <?php echo L('auth_id')?>：<select name="ID">
            <option value='' <?php if($_GET['ID']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($ainfo as $key=>$v){?>
           		 <option value='<?php echo $v['ID']?>' <?php if($_GET['ID']==$v['ID']) echo 'selected';?>><?php echo $v['ID']?></option>
			<?php }} ?>
			</select>
			版本状态：<select name="status">
				<option value="1" <?php if (isset($_GET['status']) && $_GET['status'] == 1) echo 'selected'?>>最新版本</option>
				<option value="0" <?php if (isset($_GET['status']) && $_GET['status'] == 0) echo 'selected'?>>历史版本</option>
				<option value="2" <?php if (isset($_GET['status']) && $_GET['status'] == 2) echo 'selected'?>>待审核</option>
				<option value="-1" <?php if (isset($_GET['status']) && $_GET['status'] == -1) echo 'selected'?>>审核未通过</option>
			</select>
			<?php echo L('page_list')?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			<div class="content-menu ib-a blue line-x" style="float:right">
				<a class="add fb" href="javascript:addnew()" ><em><?php echo L('add')?></em></a>
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
	            <th width='20' align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
				<th width='10' align="center">类型</th>
				<th width='30' align="center">是否强制</th>
				<th width='20' align="center"><?php echo L('term_type')?></th>
				<th width='20' align="center"><?php echo L('auth_id')?></th>
				<th width='35' align="center">文件链接</th>
				<th width='10' align="center">包大小(B)</th>
				<th width='10' align="center">Versioncode</th>
				<th width='10' align="center">版本名</th>
				<th width='30' align="center">升级时间</th>
				<th width='100' align="center">描述</th>
				<th width='30' align="center">版本状态</th>
				<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="cid" value="<?php echo $value['id']?>"  /></td>
	<td align="center"><?php echo $value['upgrade_type']?></td>
	<td align="center"><?php echo formatforce($value['is_force'])?></td>
	<td align="center"><?php if ($value['term_type'] == 'ALL_TERMS') echo '所有平台'; else echo $value['term_type']?></td>
	<td align="center"><?php if ($value['cid'] == 'ALL_CIDS') echo '所有项目'; else echo $value['cid']?></td>
	<td align="center"><a style="color:blue" href="<?php echo $value['url']?>">下载文件</a></td>
	<td align="center"><?php if ($value['zip_size']) echo $value['zip_size']; else echo $value['size']?></td>
	<td align="center"><?php echo $value['versioncode']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php echo $value['upgrade_time']?></td>
	<td align="center"><p style="overflow: hidden; height: 30px; line-height: 30px; max-width: 100px;" title="<?php echo $value['description']?>"><?php echo $value['description']?></p></td>
	<td align="center"><?php echo formatstatus($value['u_status'])?></td>
	<td align="center">
	<a href="javascript:edit('<?php echo $value['id']?>')"><?php echo L('edit')?></a> | 
	<a href="javascript:dodelete('<?php echo $value['id']?>')"><?php echo L('delete')?></a>
	<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
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
			location.href ='?m=go3c&c=client&a=delete_upgrade_multi&cid='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function addnew() {
    location.href ='?m=go3c&c=client&a=add_upgrade&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function edit(cid) {
    location.href ='?m=go3c&c=client&a=edit_upgrade&cid='+cid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dodelete(cid) {
	if (confirm('确定要删除该版本吗?')) {
		location.href ='?m=go3c&c=client&a=delete_upgrade&cid='+cid+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
}
</script>
