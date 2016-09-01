<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
if(!isset($show_header)) { ?>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <?php if(isset($big_menu)) { echo '<a class="add fb" href="'.$big_menu[0].'"><em>'.$big_menu[1].'</em></a>　';} else {$big_menu = '';} ?>
    <?php //echo admin::submenu($_GET['menuid'],$big_menu); ?>
    </div>
</div>
<?php } ?>
<div class="pad_10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="position_game" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=position_game&a=init">全部数据</a>&nbsp;
		    <select id="term" name="term">
		    	<option value='0'>全部</option>
	            <?php  foreach ($term_type_list as $key => $term) {?>
				<option value='<?php echo $term['id']?>' <?php if($term_id==$term['id']) echo 'selected';?>><?php echo $term['title']?></option>
				<?php }?>
			</select>
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
            <th width="13%">操作</th>
            <th width="7%">管理员</th>
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
	<a href="javascript:confirmurl('?m=go3c&c=position_game&a=delete&posid=<?php echo $info['posid']?>', '确定要删除吗')"><?php echo L('delete')?></a>	
	<?php } ?>
    </td>
    <td align="center"><?php echo $admin_username?></td>
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
	window.top.art.dialog({title:'<?php echo L('edit')?>--'+name, id:'edit', iframe:'?m=go3c&c=position_game&a=edit&posid='+id ,width:'500px',height:'360px'}, 	function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
//-->
</script>
