<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
$data = $data[0];
$messageInfo = json_decode($data['MSG_CONTENT'], true);
?>
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="90">消息标题:</th>
		  <td>
		  <?php echo $messageInfo['title'];?>
		  <span id="ad_MSG_TITLE" style="display:none"><font color="red">请填写标题</font> 
		  </td>
		</tr>
		<tr>
		  <th width="90">URI:</th>
		  <td>
		  <?php echo $messageInfo['uri'];?>
		  </td>
		</tr>
		<tr>
		  <th width="80">消息源:</th>
		  <td>
		  <?php echo $data['PUSH_SOURCE'];?>
		  </td>
		</tr>
		<tr>
		  <th width="80">推送状态:</th>
		  <td>
		  <?php echo mantype($data['PUSH_SIGN']);?>
		  </td>
		</tr>
		<tr>
		  <th width="80">推送时间:</th>
		  <td>
		  <?php echo $data['CREATE_TIME'];?>
		  </td>
		</tr>
		<tr>
		  <th width="80">推送类型:</th>
		  <td>
		  <?php echo pubtype($data['PUSH_TYPE']);?>
		  </td>
		</tr>
		<tr>
		  <th width="80">推送总条数:</th>
		  <td>
		  <?php echo $data['SUCCESS_RECORDS']+$data['FAIL_RECORDS'];?>
		  </td>
		</tr>
		<tr>
		  <th width="80">推送失败条数:</th>
		  <td>
		  <?php echo $data['FAIL_RECORDS'];?>
		  </td>
		</tr>
		<tr>
		  <th width="80">推送成功条数:</th>
		  <td>
		  <?php echo $data['SUCCESS_RECORDS'];?>
		  </td>
		</tr>
		<tr>
		  <th width="90">消息内容</th>
		  <td>
		  <textarea rows="5" cols="50" name="MSG_CONTENT" id="MSG_CONTENT"><?php echo $messageInfo['content'];?></textarea>
		  </td>
		</tr>
		</tbody>
	</table>
</div>
</div>      
</div>

</body>
</html>
