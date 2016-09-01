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
			<th width="15" align="center">时间</th>
			<th width="15" align="center">服务器ip</th>
			<th width="10" align="center">进程数</th>
			<th width='5' align="center">cpu百分比</th>
			<th width='10' align="center">内存百分比</th>
			<th width='5' align="center">nginx并发</th>
			<th width='10' align="center">剩余空间</th>
			<th width='10' align="center">接口</th>
			<th width='10' align="center">tomcat</th>
			<th width='10' align="center">图片服务器</th>
            </tr>
        </thead>
    <tbody>
    <?php if(is_array($data)){foreach($data as $logpc){?>   
	<tr>
	<td align="center"><?php print date('Y-m-d H:i:s',$logpc['created']);?></td>
	<td align="center"><?php echo $logpc['ip']?></td>
	<td align="center"><?php echo $logpc['tast_running']?></td>
	<td align="center"><?php echo $logpc['cpu_usage']?></td>
	<td align="center"><?php echo $logpc['mem_usage']?></td>
	<td align="center"><?php echo $logpc['ngnix_running']?></td>
	<td align="center"><?php echo $logpc['hd_avail']?></td>
	<td align="center"><?php echo go3cpc($logpc['go3cci'])?></td>
	<td align="center"><?php echo $logpc['freeMemory']?></td>
	<td align="center"><?php echo go3cpc($logpc['image'])?></td>
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
