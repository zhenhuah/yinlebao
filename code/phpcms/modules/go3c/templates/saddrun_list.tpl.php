<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad_10">
<div class="common-form">
<table width="100%" class="table_form">
	<tbody>
	<tr>
	<td >
		<b>任务ID：</b> <?php echo $info['rid_id'];?> &nbsp;
		<b>推荐位类型：</b> <?php echo song_type($info['type_id']);?>&nbsp;
		 <b>上下限范围：</b><?php echo $info['minnum'].'-'.$info['maxnum'];?>
		<b>己添加：</b><?php echo $info['nums'];?>个
		<a style="color:green;" href="?m=go3c&c=ktv&a=recommrun">返回任务列表</a>&nbsp;<a style="color:green;" href="?m=go3c&c=ktv&a=runsonglist&rid=<?php echo $info['rid'];?>">返回详细列表</a>
	</td> 
	</tr>
	</tbody>
</table>    
</div>
</div>
<div class="bk10"></div>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="ktv" name="c">
<input type="hidden" value="addrun" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		 ID：<input name="vid" type="text" value="<?php if(isset($vid)) echo $vid;?>" class="input-text" />&nbsp;
		    名称：<input name="name" type="text" value="<?php if(isset($name)) echo $name;?>" class="input-text" />&nbsp;
			拼音：<input name="spell" type="text" value="<?php if(isset($spell)) echo $spell;?>" class="input-text" />&nbsp;
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
			<th width="28" align="center">ID</th>
			<th width='28' align="center">名字</th>
			<th width='28' align="center">拼音</th>
			<th width="38" align="center">更新时间</th>
			<th width="38" align="center">是否已添加推荐位</th>
			<th width="88" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(is_array($data)){foreach($data as $value){?>   
	<tr>
	<td align="center">
	<?php 
				if ($info['type_id'] == 'song')
				{
					echo $value['vid'];
				}elseif ($info['type_id'] == 'singer')
				{
					echo $value['sid'];
				}elseif ($info['type_id'] == 'album')
				{
					echo $value['aid'];
				}
		  ?></td>
	<td align="center"><?php echo $value['name']?></td>
	<td align="center"><?php echo $value['spell']?></td>	
	<td align="center"><?php echo $value['time_created']?></td>
	<td align="center"><?php 
	if ($value['rid'] == '')
	{
		echo '否';
	}else{
		echo '是';
	}
	?></td>
	<td align="center">
	<a href="javascript:addrun_recomm('<?php echo $info['rid_id']?>','<?php 
	if ($info['type_id'] == 'song')
	{
		echo $value['vid'];
	}elseif ($info['type_id'] == 'singer')
	{
		echo $value['sid'];
	}elseif ($info['type_id'] == 'album')
	{
		echo $value['aid'];
	}
	?>','<?php echo $info['type_id']?>')">修改添加</a>
	</td> 
	</tr>
	<?php }} ?>
</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $multipage;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function addrun_recomm(rid_id,vid,type_id) {
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=ktv&a=addrun_song&rid_id='+rid_id+'&vid='+vid+'&type_id='+type_id+'&pc_hash='+pc_hash,
		success: function(msg){
			art.dialog({
				content: msg,
				title:'添加内容',
				id:'viewOnlyDiv',
				lock:true,
				width:'600'
			});
		}
	});
}
</script>
