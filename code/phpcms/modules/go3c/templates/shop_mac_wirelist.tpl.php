<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="auth" name="c">
<input type="hidden" value="mac_wirelist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
    </tbody>
</table>
<?php }?>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
				<th width='30' align="center">序号id</th>
				<th width='30' align="center">有线mac地址</th>
				<th width='20' align="center">时间</th>
				<th width='10' align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
	<td align="center"><?php echo $v['id']?></td>
	<td align="center"><?php echo $v['mac_wire']?></td>
	<td align="center"><?php echo  date("Y-m-d H:i:s",$v['createtime'])?></td>
	<td align="center">
		<a href="javascript:delmac('<?php echo $v['id']?>')">删除
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
function delmac(id) {
    location.href ='?m=go3c&c=auth&a=shop_delmac&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
