<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform" id="myform" action="" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center">视频id</th>
			<th width="10" align="center">名称</th>
			<th width='10' align="center">时间</th>
            </tr>
        </thead>
    <tbody>
    <?php if(is_array($data)){foreach($data as $logdetail){?>   
	<tr>
	<td align="center"><?php echo $logdetail['pid']?></td>
	<td align="center"><?php echo $logdetail['title']?></td>
	<td align="center"><?php print date('Y-m-d H:i:s',$logdetail['created']);?></td>
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
