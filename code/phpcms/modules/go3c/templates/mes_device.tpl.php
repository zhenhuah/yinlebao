<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
$messageCount = count($data);
?>
<form name="myform" id="myform" action="?m=go3c&c=message&a=mes_device&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			在线用户：<input type="text" name="USER_ID" <?php echo 'value="' .$_POST['USER_ID']. '"'?> />
			采集来源：<select name="BELONGTO">
           		 <option value=''>选择</option>
				<option value='BIGTV' <?php if($messageCount > 0 && $_POST['BELONGTO']=='BIGTV') echo 'selected';?>>BIGTV</option>
				<option value='KTV' <?php if($messageCount > 0 && $_POST['BELONGTO']=='KTV') echo 'selected';?>>KTV</option>
			</select>&nbsp;
            设备类型：<select name="DEVICE_TYPE">
           		 <option value=''>选择</option>
				<option value='101' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='101') echo 'selected';?>>apple tv</option>
				<option value='102' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='102') echo 'selected';?>>atv(Android stb)</option>
				<option value='103' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='103') echo 'selected';?>>ltv(Linux stb)</option>
				<option value='201' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='201') echo 'selected';?>>ipad</option>
				<option value='202' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='202') echo 'selected';?>>apad</option>
				<option value='203' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='203') echo 'selected';?>>winpad</option>
				<option value='301' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='301') echo 'selected';?>>iphone</option>
				<option value='302' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='302') echo 'selected';?>>aphone</option>
				<option value='303' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='303') echo 'selected';?>>win8 phone</option>
				<option value='401' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='401') echo 'selected';?>>pc web</option>
				<option value='402' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='402') echo 'selected';?>>pc client</option>
				<option value='403' <?php if($messageCount > 0 && $_POST['DEVICE_TYPE']=='403') echo 'selected';?>>win8 pc</option>
			</select>&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			</td>
		</tr>
    </tbody>
</table>
 <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr>
           <td width="50">设备编号</td>
            <td width="50">设备类型</td>
            <td width="40">设备IP</td>
            <td width="80">所属地区</td>
            <td width="50">关联用户</td>
            <td width="50">采集来源</td>
          </tr>
		  <?php
		  if(is_array($data)) {
		  	//print_r($cms_video_poster);
		  	foreach($data as $key => $value1) {
		  ?>
          <tr>
          	<td><?=$value1['DEVICE_ID']?></td>
          	<td><?php echo devicetype($value1['DEVICE_TYPE'])?></td>
            <td><?=$value1['DEVICE_IP']?></td>
            <td><?=$value1['AREA_NAME']?></td>
            <td><?=$value1['USER_ID']?></td>
            <td><?=$value1['BELONGTO']?></td>
          </tr>
          <?php }}?>
	</table>
<div id="pages"><?php echo $this->db->pages;?></div>
	</div>
</div>      
</div>
<div class="bk10"></div>
</form>
</body>
</html>
