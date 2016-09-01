<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="bk10"></div>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="ktv" name="c">
<input type="hidden" value="runsonglist" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="28" align="center">ID</th>
			<th width="28" align="center">任务ID</th>
			<th width='28' align="center">所属推荐位</th>
			<th width='28' align="center">名字</th>
			<th width='28' align="center">图片</th>
			<th width="88" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center"><?php echo $value['vid'];?></td>
	<td align="center"><?php echo $value['rid_id'];?></td>
	<td align="center"><?php echo $value['posidInfo'];?></td>
	<td align="center"><?php echo $value['title']?></td>
	<td align="center"><a href="<?php echo $value['img_file']?>" target="_blank"><img style="width:40px; border:solid 1px gray; padding:2px;" src="<?php echo $value['img_file']?>"/></a></td>	
	<td align="center">
	<a href="javascript:editrun_recomm('<?php echo $value['id']?>','<?php echo $value['rid_id']?>')">编辑</a> |
	<a href="javascript:delrun_recomm('<?php echo $value['id']?>','<?php echo $value['rid_id']?>')">删除</a>
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
function editrun_recomm(id,rid_id)
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=ktv&a=editrun_recomm&id='+id+'&rid_id='+rid_id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'编辑',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
}
function delrun_recomm(id,rid_id){
	 location.href ='?m=go3c&c=ktv&a=delrun_recomm&id='+id+'&rid_id='+rid_id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
}
</script>
