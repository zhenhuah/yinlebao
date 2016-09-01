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
<input type="hidden" value="feedbacklist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			身份证号：<input name="cardid" type="text" value="<?php if(isset($cardid)) echo $cardid;?>" class="input-text" size="20" />&nbsp;
			QQ：<input name="qq" type="text" value="<?php if(isset($qq)) echo $qq;?>" class="input-text" size="10" />&nbsp;
			电话：<input name="phone" type="text" value="<?php if(isset($phone)) echo $phone;?>" class="input-text" size="12" />&nbsp;
			版本号：<input name="version" type="text" value="<?php if(isset($version)) echo $version;?>" class="input-text" size="10" />&nbsp;
			地区：<input name="city" type="text" value="<?php if(isset($city)) echo $city;?>" class="input-text" size="10" />&nbsp;
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
			<th width='5' align="center">序号</th>
			<th width='60' align="center">信息内容</th>
			<th width='10' align="center">日期</th>
			<th width='10' align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>  
	<tr>
	<td align="center"><?php echo $value['id']?></td>
	<td align="center"><?php echo $value['content']?></td>
	<td align="center"><?php echo  date("Y-m-d H:i:s",$v['createtime'])?></td>
	<td align="center"><a href="javascript:detail('<?php echo $value['id']?>')">详情</a></td>
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
function detail(id){
	$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=auth&a=add_feedbackdetail&id='+id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'详情',
					id:'viewOnlyDiv',
					lock:true,
					width:'400'
				});
			}
		});
}
</script>
