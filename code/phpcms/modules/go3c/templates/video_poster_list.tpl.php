<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform" id="myform" action="" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
        <tr><th width="50" align="center" colspan="3"><?php echo L('vvideo');?>VID:<span style="color:red"><?php echo $limitInfo['asset_id']?></span></th> 
       <th width="350" align="center" colspan="2"><?php echo L('name');?>:<span style="color:red"><?php echo $limitInfo['title']?></span></th>
       <th width="50" align="center"> <input  type="button"   id="btnUpload"  value="<?php echo L('upload_pic');?>" /> | 
       <a href="javascript:btnpipei('<?php echo $limitInfo['asset_id']?>');"><?php echo L('matching');?></a></th>
            </tr>
            <tr>
			<th width="50" align="center"><?php echo L('type_name');?></th>
			<th width="10" align="center"><?php echo L('type');?></th>
			<th width='10' align="center"><?php echo L('resolution_ratio');?></th>
			<th width='10' align="center"><?php echo L('vlink');?></th>
			<th width='50' align="center"><?php echo L('preview');?></th>
			<th width="70" align="center"><?php echo L('operation');?></th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $channel){?>   
	<tr>
	<td align="center"><?php echo $channel['description']?></td>
	<td align="center"><?php echo $channel['id']?></td>
	<td align="center"><?php echo $channel['resolution_ratio']?></td>
	<td align="left" style="width:300px; word-break: break-all; word-wrap:break-word;"><a href="<?php echo $channel['path']?>" target="_blank"><?php echo $channel['path']?></a></td>
	<td align="left"><a href="<?php echo $channel['path']?>" target="_blank"><img style="width:80px; border:solid 1px gray; padding:2px;" src="<?php echo $channel['path']?>" /></a></td>
	<td align="left">
		<input type="hidden" id="asset_id" name="asset_id" value="<?php echo $channel['asset_id']?>" />
		<a style="color:green" href="javascript:selectpic('<?php echo $channel['asset_id']?>','<?php echo $channel['id']?>');"><?php echo L('selec_pic');?></a> | 
		<a href="javascript:deleteit('<?php echo $channel['id']?>','<?php echo $limitInfo['asset_id']?>');"><?php echo L('del');?></a>
	</td>
	</tr>
	<?php }} ?>
</tbody>
    </table>
	 <a class="add fb" style="float:right;color:red;margin-right:80px;" href="javascript:fhhistory('<?php echo $limitInfo['id']?>')"><em>返回</em></a>&nbsp;
	</div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function fhhistory(id) {
    location.href ='?m=content&c=content&a=edit&catid=54&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function selectpic(asset_id,id)
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=video&a=selectpp&asset_id='+encodeURIComponent(asset_id)+'&id='+id+'&pc_hash='+pc_hash,
		success: function(msg){
		art.dialog({
				content: msg,
				title:'图片列表',
				id:'viewOnlyDiv',
				lock:true,
				width:'750'
			});
		}
	});
}
function addnew(asset_id){
	location.href ='?m=content&c=content&a=add&catid=65&asset_id='+encodeURIComponent(asset_id) +'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function btnpipei(asset_id){
	location.href ='?m=go3c&c=video&a=btnpipei&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function deleteit(id,asset_id){
		location.href ='?m=go3c&c=video&a=videodelete&id='+ id +'&asset_id='+ encodeURIComponent(asset_id) +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
}
jQuery("input[id='btnUpload']").click(function(){
	//alert(jQuery("input[name='info[column_id]'][@checked]").val());
	window.top.art.dialog(
	{
		id:'testIframe',
		iframe:"?m=go3c&c=video&a=poster_upload&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
		title:'多图片上传',
		width:'400',
		height:'400'
	},
	function(){
		var ifram_page_data = window.top.art.dialog({id:'testIframe'}).data.iframe;
		window.top.art.dialog({id:'testIframe'}).close();
	}
	);
});

function addnew(asset_id){
	location.href ='?m=go3c&c=video&a=btnpipei&asset_id='+asset_id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>