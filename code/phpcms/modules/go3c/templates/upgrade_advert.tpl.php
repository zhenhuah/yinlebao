<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
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
	<input type="hidden" value="upgrade_advert" name="a">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	<?php echo L('advert_spid');?>:
	<select id="spid" name="spid">
		  <option value=''><?php echo L('advert_all');?></option>
	      <?php  foreach ($spid_list as $spid) {?>
		  <option value='<?php echo $spid['a_cid']?>' <?php if($_GET['spid']==$spid['a_cid']) echo 'selected';?>><?php echo $spid['a_cid']?></option>
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
			<th width='50' align="center"><?php echo L('advert_spid');?></th>
			<th width='50' align="center"><?php echo L('advert_pos');?></th>
			<th width='50' align="center"><?php echo L('advert_t_url');?></th>
			<th width='50' align="center"><?php echo L('advert_t_sh');?></th>
			<th width='50' align="center"><?php echo L('advert_t_shurl');?></th>
			<th width='50' align="center"><?php echo L('advert_pic');?></th>
			<th width='200' align="center"><?php echo L('advert_content');?></th>
			<th width='50' align="center"><?php echo L('advert_dis_time');?></th>
			<th width="80" align="center"><?php echo L('ktv_oper');?></th>
            </tr>
        </thead>
    <tbody>
    
    <?php //print_r($advert_list);?>
    
	<?php if(!empty($data)){foreach($data as $v){?>
		<tr>
		<td align="center"><?php echo $v['a_cid']?></td>
		<td align="center"><?php echo $v['a_type'] == 1 ? L('advert_bot_pop') : L('advert_top_pop')?></td>
		<td align="center"><?php echo $v['a_link'] ? $v['a_link'] : L('advert_null')?></td>
		<td align="center"><?php echo $v['a_app_name'] ? $v['a_app_name'] : L('advert_null')?></td>
		<td align="center"><?php echo $v['a_app_url'] ? $v['a_app_url'] : L('advert_null')?></td>
		<td align="center"><?php if ($v['a_img']) echo '<a href="' . $v['a_img'] . '" target="_blank" style="color:green">预览</a>'; else echo L('advert_null')?></td>
		<td align="center"><p title="<?php echo $v['a_content']?>" style="overflow: hidden; height: 30px; line-height: 30px; max-width: 200px;"><?php echo $v['a_content']?></p></td>
		<td align="center"><?php echo $v['a_show_time']?></td>
		<td align="center">
		<a style="color:green" href="javascript:edit('<?php echo $v['a_id'];?>')"><?php echo L('ktv_edit');?></a> | 
		<a style="color:red" href="javascript:confirmurl('?m=go3c&c=client&a=upgradeAdvertDelete&id=<?php echo $v['a_id'];?>','<?php echo L('advert_1');?>')"><?php echo L('ktv_del');?></a>
		</td>
		</tr>
	<?php }}else{echo "<tr><td align='center' colspan='9'>暂无数据</td></tr>";}?>
	</tbody>
    </table> 	
	</div>
    <div id="pages"><?php echo $pages;?></div>
    <input type="hidden" id="zipurl" value="<?php echo $_GET['zipurl']?>">
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
//添加开机任务
function add()
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=client&a=upgradeAdvertAdd&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
				content: msg,
				title:'<?php echo L('advert_add_ad');?>',
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
		url: 'index.php?m=go3c&c=client&a=upgradeAdvertEdit&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
				content: msg,
				title:'<?php echo L('advert_edit_ad');?>',
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