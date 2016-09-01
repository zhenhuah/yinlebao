<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>
<style type="text/css">
	body{margin:0 10px;}
	.table-list{margin-top:10px;}
</style>

<div class="explain-col">
	<strong>待发布项目：</strong>
	<?php foreach($atlist as $k=>$v){ ?>
	<a href="?m=go3c&c=publish&a=job&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>&mtype=<?php print $k; ?>"<?php if($k == $mtype){?> style="font-weight:bold;"<?php } ?>><?php print $v; ?>(<?php print $atsum[$k]; ?>)</a>
	&nbsp;
	<?php }?>
</div>

<form action="?m=go3c&c=publish&a=jobrun&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>" method="POST">
<h2 style="padding:10px 0;">
<span style="float:right;">
<input class="button" type="submit" name="allonline" value="全部<?php print $off_online_txt; ?>" /> &nbsp; 
<input class="button" type="submit" name="allcanc"   value="全部取消  " /> 
</span>
<?php print $off_online_txt; ?>申请
<?php if(in_array($mtype,array('video'))){ ?><span style="color:red; font-size:12px;">只有 已经审核通过 且 已经到期 的视频才能成功发布!</span><?php }?>
<div class="clear"></div>
</h2>

<div class="table-list">
<table cellspacing="0" cellpadding="0" border="0" class="table-list" style="width:100%;">
<thead>
	<tr>
		<th style="width:210px;">ID</th>
		<th class="ln">名称</th>
		<?php if(in_array($mtype,array('pre_adverts'))){ ?>
			<th class="ln">文字</th>
			<th class="ln">spid</th>
		<?php }?>
		<?php if(in_array($mtype,array('pre_task'))){ ?>
			<th class="ln">推荐位</th>
		<?php }?>
		<?php if(in_array($mtype,array('video','information','off_information'))){ ?>
			<th>发布日期</th>
		<?php }?>
		<?php if(in_array($mtype,array('video','channel','channelepg'))){ ?>
			<th style="width:60px;">预览</th>
		<?php }?>
		<th style="width:100px;">状态</th>
		<th style="width:60px;">操作</th>
	</tr>
</thead><tbody>
	<?php foreach($online['list'] as $v){ 
		if (strpos($mtype,'pre_adverts') === 0){
			$v['title'] = $v['position']; 
			$v['id'] = $v['adId'];
		}
		if (strpos($mtype,'pre_task') === 0){
			$v['title'] = $v['videoTitle']; 
			$v['id'] = $v['taskId'];
		}
	?>
	<tr>
		<td class="cn"><?php echo $v[$atlist_id[$mtype]]; ?></td>
		<td class="ln"><?php echo $v['title']; ?></td>
		<?php if(in_array($mtype,array('pre_adverts'))){ ?>
			<td class="ln"><?php echo $v['adDesc']?></td>
			<td class="ln"><?php echo $v['spid']?></td>
		<?php }?>
		<?php if(in_array($mtype,array('pre_task'))){ ?>
			<td class="ln"><?php echo $v['posidInfo']?></td>
		<?php }?>
		<?php if(in_array($mtype,array('video'))){ ?>
			<td style="width:80px;"><?php echo $v['pub_date']?></td>
		<?php }?>
		<?php if(in_array($mtype,array('information','off_information'))){ ?>
			<td style="width:80px;"><?php echo date('Y-m-d H:i:s',$v['updatetime'])?></td>
		<?php }?>
		<?php if(in_array($mtype,array('video','channel','channelepg'))){ ?>
			<td class="cn"><a href="javascript:edit('<?php echo $v['id']?>');void(0);">预览</a></td>
		<?php }?>
		<td class="cn"><?php online_status($v['online_status']); ?></td>
		<td class="cn"><input class="button" type="button" onclick="letsCa(<?php print $v['id']; ?>,this);" value="取消" />
		<input type="hidden" name="ids[]" value="<?php print $v['id']; ?>" id="i_ids_<?php print $v['id']; ?>" />
		</td>
	</tr>

	<?php }?>
	
	<?php if(!empty($online['pager'])){ ?>
	<tr>
		<td colspan="10">
		<?php print $online['pager']; ?>
		</td>
	</tr>
	<?php }?>
</tbody>
</table>
</div>
<input type="hidden" value="<?php print $mtype; ?>" name="mtype" />
</form>

<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function letsCa(id,obj){
	if(!letsCa.xding) letsCa.xding = {} ;
	if(letsCa.xding['t'+id]) return ;
	
	letsCa.xding['t'+id] = true ;
	
	var isurl = '?m=go3c&c=publish&a=canc&mtype=<?php print $mtype ; ?>&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>&id='+id ;
	loadJson(isurl,function(){
		letsCa.xding['t'+id] = false ;
		if(window['icanc_'+id]){
			obj.value = '已取消' ;
			obj.disabled = true ;
			i$('i_ids_'+id).disabeld = true ;	
		}
	}) ;
	
}
function edit(id) {
<?php if(strpos($mtype,'position') === 0){ ?>
	location.href ='?m=go3c&c=position&a=position_list&posid='+id+'&pc_hash='+pc_hash+'&view=0';
<?php } else {?>
    location.href ='?m=content&c=content&a=edit&catid=54&id='+id+'&pc_hash='+pc_hash+'&view=0';
<?php } ?>
}
</script>

