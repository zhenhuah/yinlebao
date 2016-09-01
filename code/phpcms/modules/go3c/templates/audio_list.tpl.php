<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="audio_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			<a href="?m=go3c&c=client&a=audio_list"><?php echo L('all')?></a>&nbsp;
			 <?php echo L('auth_id')?>：<select name="cid">
            <option value='' <?php if($_GET['cid']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($ainfo as $key=>$v){?>
           		 <option value='<?php echo $v['ID']?>' <?php if($_GET['cid']==$v['ID']) echo 'selected';?>><?php echo $v['ID']?></option>
			<?php }} ?>
			</select>
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
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
				<th width='30' align="center">项目号</th>
				<th width='20' align="center">音频类型</th>
				<th width="70" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($menuArr)){foreach($menuArr as $k => $v){
		if ($v == 'ALL')
			$v = 'HDMI/AV';
		elseif ($v == '')
			$v = '空';
	?>   
	<tr>
	<td align="center"><?php echo $k?></td>
	<td align="center"><?php echo $v ? $v : '空'?></td>
	<td align="center">
	<a href="javascript:edit('<?php echo $k?>')"><?php echo L('edit')?></a>
	</td> 
	</tr>
	<?php }} ?>
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
function edit(cid) {
    location.href ='?m=go3c&c=client&a=audio_edit&cid='+cid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
