<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
function importlog($type){
	switch($type){
	case on:
		return '上线';
	case off:
		return '下线';
	case import:
		return '导入';
	case reject:
		return '拒绝';
	default:
		return '未知';
	}
}
?>
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="importlog" name="a">
<input type="hidden" value="1" name="search">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			应用id:<input name="app_id" type="text" value="<?php if(isset($app_id)) echo $app_id;?>" class="input-text" />&nbsp;
   			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
   			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr>
            <td width="50">应用id</td>
            <td width="150">包名</td>
            <td width="50">类型</td>
            <td width="80">操作时间</td>
          </tr>
		  <?php
		  if(is_array($data)) {
		  	//print_r($cms_video_poster);
		  	foreach($data as $key => $value1) {
		  ?>
          <tr>
            <td><?=$value1['app_id']?></td>
            <td><?=$value1['packagename']?></td>
            <td><?php echo importlog($value1['status'])?></td>
			<td align="center"><?php echo date('Y-m-d H:i:s',$value1['createtime'])?></td>
          </tr>
          <?php }}?>
        </table>
      </td>
    </tr>
		</tbody>
	</table>
	<div id="pages"><?php echo $this->app_onlinelog->pages;?></div>
<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
</script>
	</div>
</div>      
</div>
<div class="bk10"></div>
</form>
</body>
</html>
