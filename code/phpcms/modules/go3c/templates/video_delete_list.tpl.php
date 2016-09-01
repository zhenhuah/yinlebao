<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
	<form name="myform" action="" method="GET">
		<input type="hidden" value="go3c" name="m"> <input type="hidden"
			value="video" name="c"> <input type="hidden" value="delete" name="a">
		<input type="hidden" value="1" name="search"> <input type="hidden"
			value="<?php echo $pc_hash;?>" name="pc_hash">
		<table width="100%" cellspacing="0" class="search-form">
			<tbody>
				<tr>
					<td>
						<div class="explain-col">
							<a href="?m=go3c&c=video&a=delete"><?php echo L('all_data');?></a>&nbsp; VID：<input
								name="asset_id" type="text"
								value="<?php if(isset($asset_id)) echo $asset_id;?>"
								class="input-text" />&nbsp; <?php echo L('name');?>：<input name="keyword" type="text"
								value="<?php if(isset($keyword)) echo $keyword;?>"
								class="input-text" />&nbsp; <?php echo L('status');?>：<select name="online_status">
								<option value=''
								<?php if($_GET['online_status']==0) echo 'selected';?>><?php echo L('all');?></option>
								<option value='20'
								<?php if($_GET['online_status']==20) echo 'selected';?>><?php echo L('for_deletion');?></option>
							</select>&nbsp; <?php echo L('type');?>：<select name="column_id">
								<option value=''
								<?php if($_GET['column_id']==0) echo 'selected';?>><?php echo L('all');?></option>
								<?php {foreach($cms_column as $key=>$column){?>
								<option value='<?php echo $column['id']?>'
								<?php if($_GET['column_id']==$column['id']) echo 'selected';?>>
									<?php echo $column['title']?>
								</option>
								<?php }} ?>
							</select>&nbsp; <?php echo L('collections');?>：<select name="ispackage">
								<option value=''
								<?php if($_GET['ispackage']==0) echo 'selected';?>><?php echo L('all');?></option>
								<option value='1'
								<?php if($_GET['ispackage']==1) echo 'selected';?>><?php echo L('collections');?></option>
							</select> <?php echo L('each page');?>：<input name="perpage" type="text"
								value="<?php if(isset($perpage)) echo $perpage;?>"
								class="input-text" size="3" />&nbsp; <input type="submit"
								name="search" class="button" value="<?php echo L('search');?>" />
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<div class="table-list">
		<table width="100%" cellspacing="0" id="i_table_list_1">
		<tbody>
			<thead>
				<tr>
					<th style="width: 50px;" align="right"><?php echo L('check_all');?><input type="checkbox"
						onclick="clickCKB(this);" /></th>
					<th width="50" align="center"><?php echo L('number');?></th>
					<th width="88" align="center"><?php echo L('import_time');?></th>
					<th width='128' align="center"><?php echo L('name');?></th>
					<th width='50' align="center"><?php echo L('type');?></th>
					<th width="80" align="center"><?php echo L('status');?></th>
					<th width="128" align="center"><?php echo L('operation');?></th>
				</tr>
			</thead>
			<?php if(is_array($data)){$i = 1+($page-1)*$pagesize; foreach($data as $channel){?>
				<tr>
					<td align="right"><input type="checkbox" name="asset_id"
						value="<?php echo $channel['asset_id']?>" /></td>
					<td align="center"><?php echo $i++;?></td>
					<td align="center"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?>
					</td>
					<td align="center"><?php echo $channel['title']?></td>
					<td align="center"><?php echo columnid2name($channel['column_id'],$channel['ispackage'])?>
					</td>
					<td align="center"><?php echo online_status($channel['online_status']);?>
					</td>
					<td align="center"><?php if($channel['online_status'] != 20 && $channel['online_status'] != 21) {?>
						<a style="color: red" title="置为提交删除状态"
						href="javascript:doconfirmurl(<?php echo $channel['id']?>, '<?php echo L('tfor_delete');?>')"><?php echo L('tfor_delete');?></a>
						<input type="hidden" value="<?php echo $channel['id']?>"
						name="ids[]" /> <?php }?>
					</td>
				</tr>
				<?php }} ?>
				<tr>
					<td align="right"><?php echo L('check_all');?><input type="checkbox"
						onclick="clickCKB(this);" value="" /></td>
					<td colspan="20"><?php echo L('selected item');?><input type="button" onclick="doCKALL();"
						value="<?php echo L('del');?>" />
					</td>
				</tr>
			</tbody>
		</table>
		<div id="pages">
		<?php echo $this->db->pages;?>
		</div>
	</div>
	</body>
	</html>
	<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
function doconfirmurl(id,title) {
    confirmurl('?m=go3c&c=video&a=delete_appy&id='+id+'&goback='+BASE64.encode(location.search),title);
}
function clickCKB(a){
	$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}
function doCKALL(){
	 var arr =[];    
     var str='';
     $("input[name=asset_id]:checked").each(function(){    
         arr.push({asset_id:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	}
    if(str!=''){
    	location.href ='?m=go3c&c=video&a=delete_allto&asset_id='+str+'&pc_hash='+pc_hash
    	+'&goback='+BASE64.encode(location.search);
    }else{
    	alert('你还没有选择任何内容！');
    }
}
</script>
