<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" id="myform"
	action="?m=go3c&c=message&a=service&pc_hash=<?php echo $_SESSION['pc_hash'];?>"
	method="post" enctype="multipart/form-data">
	<div class="addContent" style="background: #FFF; width: 100%;">
		<div class="col-1">
			<div class="content pad-6">
				<table width="100%" cellspacing="0" class="search-form">
					<tbody>
						<tr>
							<td>
								<div class="explain-col">
									名称：<input name="SERVER_NAME" type="text"
										value="<?php if(isset($SERVER_NAME)) echo $SERVER_NAME;?>"
										class="input-text" />&nbsp; 
									服务器类型：<input name="SERVER_TYPE" type="text" 
										value="<?php if (isset($SERVER_TYPE)) echo $SERVER_TYPE;?>"
										class="input-text" />&nbsp; 
									<input type="submit" name="search"
										class="button" value="<?php echo L('search');?>" />
							
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellspacing="0" class="table_form">
					<tbody>
						<tr>
							<div class="content-menu ib-a blue line-x">
								<a class="add fb"
									href="javascript:message_service_add()"><em>添加服务器</em>
								</a>
							</div>
						</tr>
						<tr>
							<td>
								<table width="100%" cellspacing="0" class="table_form"
									style="margin-top: 20px;">
									<tr>
										<td width="50">服务器名称</td>
										<td width="150">类型</td>
										<td width="80">服务器IP</td>
										<td width="40">端口</td>
										<td width="80">范围</td>
										<td width="50">状态</td>
										<td width="80">操作</td>
									</tr>
									<?php
									if(is_array($data)) {
										//print_r($cms_video_poster);
										foreach($data as $key => $value1) {
											?>
									<tr>
										<td><?=$value1['SERVER_NAME']?></td>
										<td><?php echo $value1['SERVER_TYPE']?></td>
										<td><?=$value1['SERVER_IP']?></td>
										<td><?=$value1['SERVER_PORT']?></td>
										<td><?=$value1['SERVER_SCOPE']?></td>
										<td><?php echo subSTATU($value1['SERVER_STATU'])?></td>
										<td><a
											href="javascript:message_service_edit('<?php echo $value1['SERVER_ID']?>');void(0);">编辑</a>|
											<a
											href="javascript:message_service_delete('<?php echo $value1['SERVER_ID']?>');void(0);">删除</a>
										</td>
									</tr>
									<?php }}?>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<script type="text/javascript" src="statics/js/base64.js"></script>
				<script type="text/javascript">
					function message_service_add(){
						location.href ='?m=go3c&c=message&a=addservice'
						+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
					}
					function message_service_edit(SERVER_ID){
						location.href ='?m=go3c&c=message&a=editservice&SERVER_ID='+ SERVER_ID
						+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
					}
					function message_service_delete(SERVER_ID){
						if (confirm('你确定要删除这条服务器信息吗?')) {
							location.href ='?m=go3c&c=message&a=deleteservice&SERVER_ID='+ SERVER_ID +'&pc_hash='+pc_hash
							+'&goback='+BASE64.encode(location.search);
						}
					}
				</script>
			</div>
		</div>
	</div>
	<div class="bk10"></div>
</form>
</body>
</html>
