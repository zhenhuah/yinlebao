<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>


<div style="padding:10px 20px;width:500px;margin: 0 auto;">
<div class="explain-col"><strong><?php print $tt['title']; ?>资源导入,不要关闭浏览器!</strong></div>
<table cellspacing="0" cellpadding="0" border="0" class="table_form">
	<tr>
		<td class="cn">
		<div id="i_doing_in" class="cn"></div>
		</td>
	</tr>
</table>
</div>
<script type="text/javascript">
$("#i_doing_in").html("<img src='/go3cadmin/statics/images/045.gif' />");
$("#i_doing_in").load("/go3cadmin/index.php?m=go3c&c=shop&a=doscriptdo&id=<?php print $tt['id'];?>&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>");
</script>
