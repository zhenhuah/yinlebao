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

?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="upgrade_verify" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=client&a=upgrade_verify"><?php echo L('all')?></a>&nbsp;
		    升级类型：<select id="upgrade_type" name="upgrade_type">
						<option value="">全部</option>
						<option value="APK" <?php if ($_GET['upgrade_type'] == 'APK') echo 'selected'?>>APK</option>
						<option value="FIRMWARE" <?php if ($_GET['upgrade_type'] == 'FIRMWARE') echo 'selected'?>>FIRMWARE</option>
						<option value="SONG_DB" <?php if ($_GET['upgrade_type'] == 'SONG_DB') echo 'selected'?>>曲库数据</option>
						<option value="WEB_PHONE" <?php if ($_GET['upgrade_type'] == 'WEB_PHONE') echo 'selected'?>>手机客户端</option>
						<option value="APP_BOOT" <?php if ($_GET['upgrade_type'] == 'APP_BOOT') echo 'selected'?>>开机数据</option>
						<option value="APP_HOTKEY" <?php if ($_GET['upgrade_type'] == 'APP_HOTKEY') echo 'selected'?>>业务数据</option>
						<option value="SONG_HOT" <?php if ($_GET['upgrade_type'] == 'SONG_HOT') echo 'selected'?>>歌曲数据</option>
					</select>
			 <?php echo L('auth_id')?>：<select name="ID">
            <option value='' <?php if($_GET['ID']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($ainfo as $key=>$v){?>
           		 <option value='<?php echo $v['ID']?>' <?php if($_GET['ID']==$v['ID']) echo 'selected';?>><?php echo $v['ID']?></option>
			<?php }} ?>
			</select>
			<?php if ($_SESSION['roleid'] != '19' && $_SESSION['roleid'] != '21') {?>
			<?php echo L('term_type')?>：<select name="term_type">
						<option value="">全部</option>
						<option value="A20" <?php if ($_GET['term_type'] == 'A20') echo 'selected'?>>A20</option>
						<option value="MX8726" <?php if ($_GET['term_type'] == 'MX8726') echo 'selected'?>>MX8726</option>
						<option value="A31S" <?php if ($_GET['term_type'] == 'A31S') echo 'selected'?>>A31S</option>
						</select>
			<?php }?>
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
	            <th width='20' align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
				<th width='10' align="center">类型</th>
				<th width='30' align="center">型号</th>
				<th width='30' align="center">是否强制</th>
				<th width='20' align="center"><?php echo L('term_type')?></th>
				<th width='20' align="center"><?php echo L('auth_id')?></th>
				<th width='35' align="center">文件链接</th>
				<th width='10' align="center">大小(B)</th>
				<th width='10' align="center">版本名</th>
				<th width='30' align="center">升级时间</th>
				<th width='100' align="center">描述</th>
				<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="cid" value="<?php echo $value['id']?>"  /></td>
	<td align="center"><?php echo $value['upgrade_type']?></td>
	<td align="center"><?php echo $value['firmware_config'] ? $value['firmware_config'] : '无'?></td>
	<td align="center"><?php echo formatforce($value['is_force'])?></td>
	<td align="center"><?php if ($value['term_type'] == 'ALL_TERMS') echo '所有平台'; else echo $value['term_type']?></td>
	<td align="center"><?php if ($value['cid'] == 'ALL_CIDS') echo '所有项目'; else echo $value['cid']?></td>
	<td align="center"><a style="color:blue" href="<?php echo $value['url']?>">点击验证升级文件</a></td>
	<td align="center"><?php if ($value['zip_size']) echo $value['zip_size']; else echo $value['size']?></td>
	<td align="center"><?php echo $value['version']?></td>
	<td align="center"><?php echo $value['upgrade_time']?></td>
	<td align="center"><p style="overflow: hidden; height: 30px; line-height: 30px; max-width: 100px;" title="<?php echo $value['description']?>"><?php echo $value['description']?></p></td>
	<td align="center">
	<a href="javascript:upgradeApprove('<?php echo $value['id']?>', '<?php echo $value['upgrade_type']?>', '<?php echo $value['term_type']?>', '<?php echo $value['cid']?>', '<?php echo $value['firmware_config']?>')" style="color:blue;">通过</a> | 
	<a href="javascript:upgradeRefuse('<?php echo $value['id']?>')" style="color:red;">拒绝</a>
	<input type="hidden" value="<?php echo $value['id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
	<tr>
	<td align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="1">通过</option>
		<option value="2">拒绝</option>
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
		if(doitem == '1'){  //批量通过审核
			location.href ='?m=go3c&c=client&a=upgradeApproveMulti&cid='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		} else if(doitem == '2'){  //批量拒绝审核
			location.href ='?m=go3c&c=client&a=upgradeRefuseMulti&cid='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
     }
}

function upgradeApprove(id, type, term, cid, fwconfig) {
	if (confirm('确定要通过该版本升级吗?')) {
		location.href ='?m=go3c&c=client&a=upgradeApprove&id='+id+'&type='+type+'&term='+term+'&cid='+cid+'&fwconfig='+fwconfig+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
}
function upgradeRefuse(id) {
	if (confirm('确定要拒绝该版本升级吗?')) {
		location.href ='?m=go3c&c=client&a=upgradeRefuse&id='+id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
}
</script>
