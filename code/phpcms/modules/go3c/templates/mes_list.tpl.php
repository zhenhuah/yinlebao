<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="message" name="c">
<input type="hidden" value="mes" name="a">
<input type="hidden" value="1" name="search">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			消息标题：<input name="MSG_TITLE" type="text" value="<?php if(isset($MSG_TITLE)) echo $MSG_TITLE;?>" class="input-text" />&nbsp;
  			 消息内容：<input name="MSG_CONTENT" type="text" value="<?php if(isset($MSG_CONTENT)) echo $MSG_CONTENT;?>" class="input-text" />&nbsp;
   			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
   			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			</td>
		</tr>
    </tbody>
</table>
		<div class="content-menu ib-a blue line-x">
		<a class="add fb" href="javascript:video_mes_add()"><em>添加消息模板</em></a> |
		<a class="add fb" href="javascript:video_mes_taken()"><em>消息推送</em></a>
		</div>
<div class="table-list">
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr>
            <td width="50">消息标题</td>
            <td width="150">消息内容</td>
            <td width="50">是否定制</td>
            <td width="80">url</td>
            <td width="80">操作</td>
          </tr>
		  <?php
		  if(is_array($data)) {
		  	//print_r($cms_video_poster);
		  	foreach($data as $key => $value1) {
		  ?>
          <tr>
            <td><?=$value1['MSG_TITLE']?></td>
            <td><?=$value1['MSG_CONTENT']?></td>
            <td><?php echo mestype($value1['ISTRIGGER'])?></td>
            <td><?=$value1['MSG_URI']?></td>
            <td><a href="javascript:video_mes_edit('<?php echo $value1['MODEL_ID']?>');void(0);">编辑</a>|
			<a href="javascript:video_mes_delete('<?php echo $value1['MODEL_ID']?>');void(0);">删除</a></td>
          </tr>
          <?php }}?>
        </table>
      </td>
    </tr>
		</tbody>
	</table>
	<div id="pages"><?php echo $this->db->pages;?></div>
<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
	function video_mes_add(){
		location.href ='?m=go3c&c=message&a=addmes&pc_hash='+pc_hash
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
	function video_mes_edit(MODEL_ID){
		location.href ='?m=go3c&c=message&a=mes_edit&MODEL_ID='+ MODEL_ID +'&pc_hash='+pc_hash
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
	function video_mes_delete(MODEL_ID){
		location.href ='?m=go3c&c=message&a=mes_del&catid=64&MODEL_ID='+ MODEL_ID +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
	function video_mes_taken(){
		location.href ='?m=go3c&c=message&a=mes_taken&catid=64&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
</script>
	</div>
</div>      
</div>
<div class="bk10"></div>
</form>
</body>
</html>
