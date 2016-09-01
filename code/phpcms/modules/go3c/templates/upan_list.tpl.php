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
<input type="hidden" value="upan_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<a href="?m=go3c&c=client&a=upan_list"><?php echo L('all')?></a>&nbsp;
			<?php echo L('term_type')?>：<select name="term_type">
						<option value="">全部</option>
						<option value="A20" <?php if ($_GET['term_type'] == 'A20') echo 'selected'?>>A20</option>
						<option value="MX8726" <?php if ($_GET['term_type'] == 'MX8726') echo 'selected'?>>MX8726</option>
						<option value="A31S" <?php if ($_GET['term_type'] == 'A31S') echo 'selected'?>>A31S</option>
						</select>
			 <?php echo '型号'?>：<select name="config">
            <option value='' <?php if(!isset($_GET['config']) || $_GET['config'] == '') echo 'selected';?>><?php echo L('all');?></option>
	          <option value="KT601B-HK-003" <?php if ($_GET['config'] == 'KT601B-HK-003') echo 'selected'?>>KT601B-HK-003</option>
			  <option value="KT601B-CH-002" <?php if ($_GET['config'] == 'KT601B-CH-002') echo 'selected'?>>KT601B-CH-002</option>
			  <option value="KT601A-BX-003" <?php if ($_GET['config'] == 'KT601A-BX-003') echo 'selected'?>>KT601A-BX-003</option>
			  <option value="KT801B-HK-003" <?php if ($_GET['config'] == 'KT801B-HK-003') echo 'selected'?>>KT801B-HK-003</option>
			</select>
			版本状态：<select name="status">
				<option value="1" <?php if (isset($_GET['status']) && $_GET['status'] == 1) echo 'selected'?>>最新版本</option>
				<option value="0" <?php if (isset($_GET['status']) && $_GET['status'] == 0) echo 'selected'?>>历史版本</option>
				<option value="2" <?php if (isset($_GET['status']) && $_GET['status'] == 2) echo 'selected'?>>待审核</option>
				<option value="-1" <?php if (isset($_GET['status']) && $_GET['status'] == -1) echo 'selected'?>>审核未通过</option>
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
	            <th width='20' align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
				<th width='10' align="center">类型</th>
				<th width='30' align="center">型号</th>
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
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="right"><input type="checkbox" name="cid" url="<?php echo $value['url']?>" value="<?php echo $value['id']?>"  /></td>
	<td align="center"><?php echo $value['upgrade_type']?></td>
	<td align="center"><?php echo $value['firmware_config'] ? $value['firmware_config'] : '无'?></td>
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
	</tr>
	<?php }} ?>
	<tr>
	<td align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="1">生成配置</option>
		<option value="2">下载文件</option>
	</select>
	<input type="button" onclick="doCKALL();" value="<?php echo '确定'?>" />
	</td>
	</tr>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $multipage;?></div>
</form>
<input type="hidden" id="configurl" value="<?php echo $_GET['configurl']?>">
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
$(function(){
	var url = $('#configurl').val();
	if (url)
		location.href = url;
		
})
function clickCKB(a){
	$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}
function doCKALL(){
	var doitem = $('#i_select_ckall').val() ;
	 var arr =[];    
    var str='';
    $("input[name=cid]:checked").each(function(){    
        arr.push($(this).attr('url'));
        str += $(this).val()+",";
    });
    if (str.length > 0) {
	    //得到选中的checkbox值序列
   	 str = str.substring(0,str.length - 1);
	} 
    if(str!=''){
    	if(doitem == '1'){
    		//生成配置文件
    		location.href ='?m=go3c&c=client&a=generateConfig&cid='+str+'&pc_hash='+pc_hash
        	+'&goback='+BASE64.encode(location.search);
    	} else if (doitem == '2') {
    		for (i in arr) {
    			var a = '<a style="display:none" id="d'+i+'" href="'+arr[i]+'" target=_blank>x</a>';
    			$('form').after(a);
    			document.getElementById('d'+i).click();
    		};
    		location.href = '?m=go3c&c=client&a=upan_list&pc_hash='+pc_hash
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
