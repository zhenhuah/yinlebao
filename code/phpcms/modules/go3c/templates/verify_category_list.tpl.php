<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" action="" method="post">
<div style="text-align:right;padding:0 50px 10px 0;">
  <input class="button" type="button" onclick="dopassall('确认批量通过属性申请？')" value="批量通过属性申请" />&nbsp;&nbsp;
  <input class="button" type="button" onclick="dorefuseall('确认批量拒绝属性申请？')" value="批量拒绝属性申请" />
</div>
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="verify" name="c">
<input type="hidden" value="category" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<!--table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=verify&a=category">全部数据</a>&nbsp;
			名称：<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />
           		 类型：<select name="type">
				<option value='' <?php if($_GET['type']==0) echo 'selected';?>>全部</option>
				<option value='1' <?php if($_GET['type']==1) echo 'selected';?>>地区</option>
				<option value='2' <?php if($_GET['type']==2) echo 'selected';?>>栏目分类</option>
				<option value='3' <?php if($_GET['type']==3) echo 'selected';?>>年代</option>
			</select>
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table-->
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center">序号</th>
			<th width="120" align="center">导入时间</th>
			<th width='68' align="center">名称</th>
			<th width='50' align="center">类型</th>
			<th width='50' align="center">编辑</th>
			<?php foreach($belong_type_array as $key => $ptvalue){?>
			<th width='30' align="center"><?php echo $ptvalue; ?></th>
			<?php } ?>
			<th width='50' align="center">状态</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){$i = 1+($page-1)*$pagesize;foreach($data as $channel){
	$belong_array = explode(",",$channel['belong']);	
	?>   
	<tr>
	<td align="center"><?php echo $i++?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<td align="center"><?php echo $channel['title']?></td>
	<td align="center"><?php echo cattype2name($channel['type'])?></td>
	<td align="center"><?php echo $channel['username']?></td>
	<?php foreach($belong_type_array as $key => $ptvalue){?>
	<td align="center"><input type="checkbox" disabled="true"  <?php if(in_array($key,$belong_array)) echo "checked='checked' ";?> value='<?=$key?>' name='<?php echo 'belongs'.$channel['id']?>'/></td>
	<?php } ?>
	<td align="center"><?php echo online_status($channel['online_status']);?></td>
	<td align="center">
		<a style="color:green" href="javascript:dopass(<?php echo $channel['id']?>, '通过审核')">通过</a>
		&nbsp;&nbsp;
		<a style="color:red" href="javascript:dorefuse(<?php echo $channel['id']?>, '驳回审核')">拒绝</a>
		<input type="hidden" value="<?php echo $channel['id']?>" name="ids[]" />
	</td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function dopass(id, title) {
    location.href ='?m=go3c&c=verify&a=category_pass&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dorefuse(id, title) {
    location.href ='?m=go3c&c=verify&a=category_refuse&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dopassall(title){
	if(confirm(title)){		
		document.myform.action="?m=go3c&c=verify&a=category_pass_all&goback="+BASE64.encode(location.search);
		document.myform.submit(); 
	}
}
function dorefuseall(title){
	if(confirm(title)){		
		document.myform.action="?m=go3c&c=verify&a=category_refuse_all&goback="+BASE64.encode(location.search);
		document.myform.submit(); 
	}
}
</script>
