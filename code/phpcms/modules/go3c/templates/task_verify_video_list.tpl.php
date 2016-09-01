<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>

<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="task" name="c">
	<input type="hidden" value="verifyVideo" name="a">
	<input type="hidden" value="query" name="mode">
	<input type="hidden" value="<?php echo $taskId; ?>" name="taskId">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	名称：<input type="text" class="input-text" value="<?php echo $videoTitle; ?>" name="title" id="title">
	<input type="submit" value="搜索" class="button" name="doSearch">&nbsp;&nbsp;<a style="color:green;" href="?m=go3c&c=task&a=verifyTask">返回审核任务列表</a>
</form> 

</div>

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="10" align="center">排序</th>
			<th width="30" align="center">序号</th>
			<th width="30" align="center">任务ID</th>
			<th width="100" align="center">所属推荐位</th>			
			<th width="40" align="center">VID</th>
			<th width="100" align="center">名称</th>		
			<th width='50' align="center">视频-清晰度</th>			
			<th width='30' align="center">图片</th>	
			<th width='30' align="center">状态</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($video_list)){foreach($video_list as $video){?>
	<tr>
	<td align="center"><?php echo $video['videoSort']?><!--input type="text" class="input-text-c input-text" value="<?php echo $video['videoSort']?>" size="3" name="listorders[<?php echo $video['preId']?>]"--></td>
	<td align="center"><?php echo $video['preId']?></td>
	<td align="center"><?php echo $video['taskId']?></td>
	<td align="center"><?php echo $video['posidInfo']?></td>
	<td align="center"><?php echo $video['videoId']?></td>
	<td align="center"><?php echo $video['videoTitle']?></td>	

	<td align="center">
	<?php if(!empty($video['videoClarity'])){ ?>
		<a target="_blank" style="color:green" href="<?php echo $video['videoPlayUrl'];?>">浏览播放</a>-<?php echo $video['videoClarity']?>
		<?php }else{echo '无';}?>
	</td>	
	<td align="center">
		<a href="<?php echo $video['videoImg']?>" style="color:green" target="_blank">浏览</a>
	</td>
	<td align="center"><?php if($video['status'] =='Y'){echo '可用';}else{echo "<font color='red'>不可用</font>";}?></td>
	
	</tr>
	<?php }} ?>

	<?php if(empty($video_list)){?>
	<tr>
	<td align="center" colspan="10">暂无数据</td>
	</tr>
	<?php }?>
	</tbody>
    </table>
</div>

<div id="pages"><?php echo $pages;?></div>

</div>
</body>
</html>
<script type="text/javascript">

//修改己添加的视频
function editVideoTask(preId)
{
	if (preId > 0)
	{
		$.ajax({
			type: "GET",
			url: '?m=go3c&c=task&a=editVideo&preId='+preId+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
				content: msg,
				title:'修改视频信息',
				id:'viewOnlyDiv',
				lock:true,
				width:'650'
				});
			}
		});
	}
}

//修改具体视频任务表单验证
function checkVideoTaskFrom()
{
	//视频名称
	var videoTitle = $.trim($('#videoTitle').val());
	if (videoTitle == '')
	{
		var status = '1';
		$('#videoTitle_error').show();
	}else{
		$('#videoTitle_error').hide();
	}

		//海报方式
	var selectImgTypeOne = $('#isDataBase').attr('checked');	//方式一
	var selectImgTypeTwo = $('#isUserUpload').attr('checked');	//方式二

	if (selectImgTypeOne)	//从数据库选择尺寸列表
	{
		//imgType
		var videoImg = $.trim($('#videoImg').val());

		if (videoImg != '')
		{
			$('#videoImg_error').hide();
		}else{
			$('#videoImg_error').show();
			return false;
		}
	}else if (selectImgTypeTwo){//自己上传
		var videoImgUrl = $.trim($('#videoImgUrl').val());
		if (videoImgUrl != '')
		{
			$('#videoImgUrl_error').hide();
		}else{
			$('#videoImgUrl_error').show();
			return false;
		}
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
	if (status == '1')
	{
		return false;
	}else{
		return true;
	}

}
</script>