<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
$messageCount = count($data);
?>
<form name="myform" id="myform" action="?m=go3c&c=message&a=mange&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post"  enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			消息标题：<input type="text" name="PUSH_TITLE" <?php if ($messageCount > 0) echo 'value="' .$_POST['PUSH_TITLE']. '"'?> />
			消息源：<select name="PUSH_SOURCE">
           		 <option value=''>选择</option>
				<option value='BIGTV' <?php if($messageCount > 0 && $_POST['PUSH_SOURCE']=='BIGTV') echo 'selected';?>>BIGTV</option>
				<option value='KTV' <?php if($messageCount > 0 && $_POST['PUSH_SOURCE']=='KTV') echo 'selected';?>>KTV</option>
			</select>&nbsp;
            消息状态：<select name="PUSH_SIGN">
           		 <option value=''>选择</option>
				<option value='0' <?php if($messageCount > 0 && $_POST['PUSH_SIGN']=='0') echo 'selected';?>>未推送</option>
				<option value='1' <?php if($messageCount > 0 && $_POST['PUSH_SIGN']=='1') echo 'selected';?>>已推送</option>
				<option value='2' <?php if($messageCount > 0 && $_POST['PUSH_SIGN']=='2') echo 'selected';?>>推送中</option>
			</select>&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			</td>
		</tr>
    </tbody>
</table>
 <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr>
           <td width="80">消息内容</td>
            <td width="30">消息源</td>
            <td width="30">推送状态</td>
            <td width="80">推送时间</td>
            <td width="50">推送类型</td>
            <td width="50">推送总条数</td>
            <td width="50">推送成功条数</td>
            <td width="50">推送失败条数</td>
            <td width="80">操作</td>
          </tr>
		  <?php
		  if(is_array($data)) {
		  	//print_r($cms_video_poster);
		  	foreach($data as $key => $value1) {
		  		$messageArr = json_decode($value1['MSG_CONTENT'], true);
		  ?>
          <tr>
          	<td><?=$messageArr['title']?></td>
            <td><?=$value1['PUSH_SOURCE']?></td>
            <td><?php echo mantype($value1['PUSH_SIGN'])?></td>
            <td><?=$value1['CREATE_TIME']?></td>
            <td><?php echo pubtype($value1['PUSH_TYPE'])?></td>
            <td><?php echo $value1['SUCCESS_RECORDS']+$value1['FAIL_RECORDS'] ?></td>
            <td><?=$value1['SUCCESS_RECORDS']?></td>
            <td><?=$value1['FAIL_RECORDS']?></td>
            <td><a href="javascript:video_mange_show('<?php echo $value1['MSG_ID']?>');void(0);">详情</a>
            </td>
          </tr>
          <?php }}?>
	</table>
<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
	function video_mange_show(MSG_ID){
		location.href ='?m=go3c&c=message&a=mes_show&MSG_ID='+ MSG_ID +'&pc_hash='+pc_hash
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
</script>
	</div>
</div>      
</div>
<div id="pages"><?php echo $this->msg_t_msg->pages;?></div>
<div class="bk10"></div>
</form>
</body>
</html>
