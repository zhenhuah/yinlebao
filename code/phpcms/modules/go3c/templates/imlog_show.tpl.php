<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>


<table cellspacing="0" cellpadding="0" border="0" class="table_form" width="500" align="center">
	<caption>导入报告</caption>
	<tr>
		<th style="width: 150px;">导入时间</th>
		<td>
			<?php print date('Y-m-d H:i:s',$imlog['created']); ?>
		</td>
	</tr>
	<tr>
		<th>操作用户</th>
		<td>
			<?php print get_uname_by_sid($imlog['sid']); ?>
		</td>
	</tr>
	
	<?php if(!empty($imlog['iimchannel']['result'])) { ?>
	<tr>
	<th>电视频道</th>
	<td>
	<a style= 'color:blue;text-decoration:underline' href="?m=go3c&c=channel&a=channel&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>"><?php echo $imlog['iimchannel']['total'];?></a> 条
	</td>
	</tr>
	<?php } ?>

	<?php if(!empty($imlog['iimepg']['result'])) { ?>
	<tr>
	<th>EPG</th>
	<td>
	<a style= 'color:blue;text-decoration:underline' href="?m=go3c&c=channel&a=epg&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>"><?php print $imlog['iimepg']['total'];?></a> 条
	</td>
	</tr>
	<?php } ?>

	<?php if(!empty($imlog['iimasset']['result'])) { ?>
	<tr>
	<th>视频资源</th>
	<td>
	<a style= 'color:blue;text-decoration:underline' href="?m=go3c&c=video&a=online&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>"><?php print $imlog['iimasset']['as_total'];?></a> 条
	</td>
	</tr>
	<?php } ?>
	</table>

