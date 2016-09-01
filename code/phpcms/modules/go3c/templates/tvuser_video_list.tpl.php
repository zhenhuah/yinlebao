<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center">排名</th>
			<th width="100" align="left">视频ID</th>
			<th width='200' align="left">视频名称</th>
			<th width='200' align="center">播放次数</th>
            </tr>
        </thead>
    <tbody>
	<?php 
	if(is_array($data)){
		foreach($data as $key=>$value){
			$count = $key + 1;
	?>   
	<tr>
	<td align="center"><?php echo $count?></td>
	<td align="left"><?php echo $value['vid'];?></td>
	<td align="left"><?php echo $value['name'];?></td>
	<td align="center"><?php echo $value['count'];?></td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $multipage;?></div>
</div>
</body>
</html>
