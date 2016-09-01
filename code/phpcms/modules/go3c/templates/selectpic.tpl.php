<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform" action="?m=go3c&c=video&a=editpic&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return selectpic('<?php echo $asset_id; ?>','<?php echo $id; ?>');">
<div class="table-list" style="margin-top:10px;">
    <table width="750" cellspacing="0">
        <thead>
            <tr>
            <th width="40" align="center"><?php echo L('options');?></th>
			<th width="40" align="center"><?php echo L('pic_name');?></th>
			<th width="35" align="center"><?php echo L('pic_with');?></th>	
			<th width='35' align="center"><?php echo L('pic_height');?></th>
			<th width='60' align="center"><?php echo L('preview');?></th>
			<th width='70' align="center"><?php echo L('operation');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($data)){$i =1+($page-1)*$pagesize;foreach($data as $v){?>
		<tr>
		<td align="center"><input type="radio" id="path" name="path" value="<?php echo $v['path']?>"></td>
		<td align="center"><?php echo $v['name']?></td>
		<td align="center"><?php echo $v['width']?></td>
		<td align="center"><?php echo $v['height']?></td>		
		<td align="center"><a href="<?php echo $v['path']?>" target="_blank">
		<img style="width:50px; height:50px;border:solid 1px gray; padding:2px;" src="<?php echo $v['path']?>" /></a></td>
		<td align="center">
		<!--  a href="javascript:confirmurl('?m=go3c&c=video&a=deletepic&id=<?php echo $v['id'];?>','你确定要执行该操作吗？')+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);">删除</a-->
	    <a href="javascript:deletet('<?php echo $v['id']?>')"><?php echo L('del');?></a></td>
		</tr>
	<?php }}else{echo "<tr><td align='center' colspan='7'>暂无数据</td></tr>";}?>
	</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
 <div class="bk10"></div>
<div  style="float:right;margin-right:50px;">	
	<input type="hidden" name="mode" id="mode" value="selectpic" />
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id; ?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo L('cl_submit');?>" />&nbsp;
</div> 
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function deletet(id){
	location.href ='?m=go3c&c=video&a=deletepic&id='+ id +'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
	
}
</script>