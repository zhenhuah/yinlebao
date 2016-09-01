<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>

<form name="myform" id="myform" action="?m=go3c&c=auth&a=edit_authdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" enctype="multipart/form-data" onSubmit="return subtitle();">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">客户ID:</th>
		  <td>		
		  <?php echo $client['ID']?>
		</td>
		</tr>
		<tr>
		  <th width="80">终端标识(MAC地址):</th>
		  <td>		
		  <?php echo $client['rmac']?>
		</td>
		</tr>
		<tr>
		  <th width="80">终端分类:</th>
		  <td>
		 <?php echo $client['term_type']?>
		  </td>
		</tr>	
		<tr>
		  <th width="80">状态:</th>
		  <td>
		 <?php 
		 if($client['status']=='-1'){
		 	echo '注册';
		 }elseif($client['status']=='1'){
		 	echo '鉴权成功';
		 }elseif($client['status']=='0'){
		 	echo '注册确认';
		 }
		 ?>
		  </td>
		</tr>
		<tr>
		  <th width="80">区域:</th>
		  <td>
		 <?php echo $client['area']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">ip:</th>
		  <td>
		 <?php echo $client['ip']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">注册时间:</th>
		  <td>
		 <?php echo $client['apply_time']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">鉴权时间:</th>
		  <td>
		 <?php echo $client['auth_time']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">wifi:</th>
		  <td>
		 <?php echo $client['wifi']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">随机数:</th>
		  <td>
		 <?php echo $client['random_num']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">请求鉴权次数:</th>
		  <td>
		 <?php echo $client['request_count']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">localip:</th>
		  <td>
		 <?php echo $client['localip']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">名称:</th>
		  <td>
		 <?php echo $client['name']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">网络:</th>
		  <td>
		 <?php echo $client['network']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">版本:</th>
		  <td>
		 <?php 
		 if($client['version']=='debug'){
		 	echo '调试';
		 }elseif($client['version']=='demo'){
		 	echo '演示';
		 }elseif($client['version']=='commercial'){
		 	echo '正式';
		 }
			?>
		  </td>
		</tr>
		<tr>
		  <th width="80">版本信息:</th>
		  <td>
		 <?php echo $client['version_notice']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">是否准许重复注册:</th>
		  <td>
		 <?php echo $client['reg_repeat']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">网页代码路径:</th>
		  <td>
		 <?php echo $client['verpath']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">firmware版本:</th>
		  <td>
		 <?php echo $client['version_firmware']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">apk版本:</th>
		  <td>
		 <?php echo $client['version_apk']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">是否在线:</th>
		  <td>
		 <?php 
		 if($client['online']==1){
		 	echo '在线';
		 }else{
		 	echo '不在线';
		 }
		 ?>
		  </td>
		</tr>
		<tr>
		  <th width="80">芯片:</th>
		  <td>
		 <?php echo $client['chip']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">RAM:</th>
		  <td>
		 <?php echo $client['ram']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">ROM:</th>
		  <td>
		 <?php echo $client['rom']?>
		  </td>
		</tr>	
		<tr>
		  <th width="80">bt:</th>
		  <td>
		 <?php echo $client['bt']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">spdif:</th>
		  <td>
		 <?php echo $client['spdif']?>
		  </td>
		</tr>
		<tr>
		  <th width="80">audio:</th>
		  <td>
		 <?php echo $client['audio']?>
		  </td>
		</tr>		
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input class="button" type="button" onclick="history.back()" value="返回" />
</div> 
</form>
</body>
</html>
