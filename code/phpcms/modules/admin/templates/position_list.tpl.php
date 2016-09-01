<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>

<!--form name="myform" action="?m=admin&c=position&a=listorder" method="post"-->
<div class="pad_10">
<form name="myform"  action="" method="get">
<input type="hidden" value="admin" name="m">
<input type="hidden" value="position" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=admin&a=position">全部数据</a>&nbsp;
			名称：<input name="name" type="text" value="<?php if(isset($name)) echo $name;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <!--th width="10%"  align="left"><?php echo L('listorder');?></th-->
            <th width="5%"  align="left">ID</th>
            <th width="15%"  align="left"><?php echo L('posid_name');?></th>
            <th width="5%"  align="left">终端类型</th>
            <th width="5%"  align="left">SPID</th>
            <th width="7%"  align="left">类型ID</th>
            <th width="7%"  align="left">最少保存条数</th>
            <th width="7%"  align="left">最多保存条数</th>
            <!--
            <th width="15%"><?php echo L('posid_catid');?></th>
            <th width="15%"><?php echo L('posid_modelid');?></th>
            -->
            <th width="20%"><?php echo L('posid_operation');?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($infos)){
	foreach($infos as $info){
?>   
	<tr>
	<!--td>
	<input name='listorders[<?php echo $info['posid']?>]' type='text' size='2' value='<?php echo $info['listorder']?>' class="input-text-c">
	</td-->
	<td align="left"><?php echo $info['posid']?></td>
	<td align="left"><?php echo $info['name']?></td>
	<td align="left"><?php echo term2name($info['term_id']);?></td>
	<td align="left"><?php echo $info['spid']?></td>
	<td align="left"><?php echo $info['type_id']?></td>
	<td align="left"><?php echo $info['minnum']?></td>
	<td align="left"><?php echo $info['maxnum']?></td>
    <!--
	<td width="15%" align="center"><?php echo $info['catid'] ? $category[$info['catid']]['catname'] : L('posid_all')?></td>
	<td width="15%" align="center"><?php echo $info['modelid'] ? $model[$info['modelid']]['name'] : L('posid_all')?></td>
	-->
    <td width="20%" align="center">
    <!--a href="?m=admin&c=position&a=public_item&posid=<?php echo $info['posid']?>&menuid=<?php echo $_GET['menuid']?>"><?php echo L('posid_item_manage')?></a> |-->
	<a href="javascript:edit(<?php echo $info['posid']?>, '<?php echo new_addslashes($info['name'])?>')"><?php echo L('edit')?></a>
     | 
	<?php if($info['siteid']=='0' && $_SESSION['roleid'] != 1) {?>
	<font color="#ccc"><?php echo L('delete')?></font>
	<?php } else {?>
	<a href="javascript:confirmurl('?m=admin&c=position&a=delete&posid=<?php echo $info['posid']?>', '<?php echo L('posid_del_cofirm')?>')"><?php echo L('delete')?></a>	
	<?php } ?>
    </td>
	</tr>
<?php 
	}
}
?>
    </tbody>
    </table>
  
    <!--div class="btn"><input type="submit" class="button" name="dosubmit" value="<?php echo L('listorder')?>" /></div-->  
</div>

 <div id="pages"> <?php echo $pages?></div>
</div>
</div>
<!--/form-->
</body>
<a href="javascript:edit(<?php echo $v['siteid']?>, '<?php echo $v['name']?>')">
</html>
<script type="text/javascript">
<!--
	window.top.$('#display_center_id').css('display','none');
	function edit(id, name) {
	window.top.art.dialog({title:'<?php echo L('edit')?>--'+name, id:'edit', iframe:'?m=admin&c=position&a=edit&posid='+id ,width:'500px',height:'360px'}, 	function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
//-->
</script>
