<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="client" name="c">
<input type="hidden" value="version_list" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=client&a=client_list">全部数据</a>&nbsp;
			版本：<input name="version" type="text" value="<?php if(isset($version)) echo $version;?>" class="input-text" />&nbsp;
			操作用户：<input name="username" type="text" value="<?php if(isset($username)) echo $username;?>" class="input-text" />&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="50" align="center">序号</th>
			<th width='50' align="center">终端</th>
			<th width='50' align="center">版本</th>
			<th width='50' align="center">操作用户</th>
			<th width="50" align="center">时间</th>
			<th width="68" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $client_version){?>   
	<tr>
	<td align="center"><?php echo $client_version['logid']?></td>
	<td align="center">
	<?php if ($client_version['term'] == '1')
				{
					echo 'STB';
				}elseif ($client_version['term'] == '2')
				{
					echo 'PAD';
				}elseif ($client_version['term'] == '3')
				{
					echo 'PHONE';
				}else{
					echo 'PC';
				}
				?></td>
	<td align="center"><?php echo $client_version['version'];?></td>
	<td align="center"><?php echo $client_version['username'];?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s',$client_version['createtime']);?></td>
	<td align="center">
		<a style="color:green" href="javascript:versionlist('<?php echo $client_version['version'];?>','<?php echo $client_version['term'];?>','<?php echo $client_version['spid'];?>')">详情</a> | 
		<a style="color:blue" href="javascript:preview('<?php echo $client_version['version'];?>','<?php echo $client_version['term'];?>','<?php echo $client_version['spid'];?>')">浏览</a> |
		<a style="color:blue" href="<?php 
		if ($client_version['term'] == '1')
		{
			$xm =  'stb';
		}elseif ($client_version['term'] == '2')
		{
			$xm =  'pad';
		}elseif ($client_version['term'] == '3')
		{
			$xm =  'phone';
		}else{
			$xm = 'pc';
		}
		echo $xmlurl = 'http://www.go3c.tv:8060/go3ccms/xml/'.$client_version['spid'].'/'.$xm.'/resources.xml';
		?>" target="_blank">查看升级xml</a>
		<input type="hidden" value="<?php echo $client_version['logid']?>" name="ids[]" />
	</td> 
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
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function versionlist(version,term,spid) {
    location.href ='?m=go3c&c=client&a=versionlist&version='+version+'&term='+term+'&spid='+spid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function preview(version,term,spid)
{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=client&a=preview&version='+version+'&term='+term+'&spid='+spid+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'图片预览',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
}
</script>
