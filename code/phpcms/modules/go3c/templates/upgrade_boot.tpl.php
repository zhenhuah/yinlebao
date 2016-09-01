<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
function formatType($type, $bg) {
	if ($type == 'BOOTANIMATION')
		return '开机动画';
	else if ($type == 'APP_WIZARDS')
		return '开机引导图';
	else {
		if ($bg == 0)
			return '应用启动背景图';
		else if ($bg == 1)
			return '应用重启背景图';
		else if ($bg == 2)
			return '应用切回背景图';
		else 
			return '视频暂停背景图';
	}
}
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<style type="text/css">
	body{margin:0 10px;}
	.table-list{margin-top:10px;}
</style>

<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="client" name="c">
	<input type="hidden" value="upgrade_boot" name="a">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	<input type="hidden" id="zipurl" value="<?php echo $_GET['zipurl']?>">
	<?php echo L('advert_spid');?>:
	<select id="spid" name="spid">
		  <option value=''><?php echo L('advert_all');?></option>
	      <?php  foreach ($spid_list as $spid) {?>
		  <option value='<?php echo $spid['b_cid']?>' <?php if($_GET['spid']==$spid['b_cid']) echo 'selected';?>><?php echo $spid['b_cid']?></option>
		  <?php }?>
	</select>
	<input type="submit" value="<?php echo L('advert_serch');?>" class="button" name="search"> &nbsp;&nbsp;	
<a class="button" style="float:right; text-decoration: none;" href="javascript:add()"><?php echo L('advert_add');?></a>
</form> 
</div>

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width='10' align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width="50" align="center"><?php echo L('advert_spid');?></th>
			<th width="50" align="center"><?php echo L('advert_type');?></th>
			<th width='50' align="center"><?php echo L('advert_file_Pre');?></th>
			<th width='50' align="center"><?php echo L('advert_addtime');?></th>
			<th width="80" align="center"><?php echo L('ktv_oper');?></th>
            </tr>
        </thead>
    <tbody>
    
    <?php //print_r($advert_list);?>
    
	<?php if(!empty($data)){foreach($data as $v){?>
		<tr>
		<td align="right"><input type="checkbox" name="cid" value="<?php echo $v['b_id']?>"  /></td>
		<td align="center"><?php echo $v['b_cid']?></td>
		<td align="center"><?php echo formatType($v['b_type'], $v['b_bg_type'])?></td>
		<td align="center"><a href="<?php echo $v['b_url']?>" target="_blank" style="color:green"><?php echo L('ktv_pre');?></a></td>
		<td align="center"><?php echo $v['b_time']?></td>
		<td align="center">
		<a style="color:green" href="javascript:edit('<?php echo $v['b_id'];?>')"><?php echo L('ktv_edit');?></a> | 
		<a style="color:red" href="javascript:confirmurl('?m=go3c&c=client&a=upgradeBootDelete&id=<?php echo $v['b_id'];?>','你确定要执行该操作吗?')"><?php echo L('ktv_del');?></a>
		</td>
		</tr>
	<?php }}else{echo "<tr><td align='center' colspan='7'>暂无数据</td></tr>";}?>
		<tr>
		<td align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
		<td colspan="20">
		<input type="button" onclick="doCKALL();" value="<?php echo L('advert_upgrade_package');?>" />
		</td>
		</tr>
	</tbody>
    </table> 	
	</div>
    <div id="pages"><?php echo $pages;?></div>
    <input type="hidden" id="zipurl" value="<?php echo $_GET['zipurl']?>">
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
$(function(){
	var url = $('#zipurl').val();
	if (url)
		location.href = url;
		
})
function clickCKB(a){
	$('table input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}

function doCKALL(){
	var customerid = $('#spid').val();
	if (!customerid) {
		alert('请先根据要打包的项目号搜索');
		$('#spid').focus();
		return false;
	}
     var str='';
     $("input[name=cid]:checked").each(function(){    
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //去掉最后一个逗号
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
        //打包选中项
		location.href ='?m=go3c&c=client&a=upgradeBootGetZip&ids='+str+'&cid='+customerid+'&pc_hash='+pc_hash
    	+'&goback='+BASE64.encode(location.search);
    }else{
    	alert('请勾选打包项');
     }
}

//添加开机任务
function add()
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=client&a=upgradeBootAdd&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
				content: msg,
				title:'添加开机资源',
				id:'viewOnlyDiv',
				lock:true,
				width:'600'
			});
		}
	});
}

//修改广告
function edit(id)
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=client&a=upgradeBootEdit&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
				content: msg,
				title:'修改开机资源',
				id:'viewOnlyDiv',
				lock:true,
				width:'600'
			});
		}
	});
}
</script>
</body>
</html>