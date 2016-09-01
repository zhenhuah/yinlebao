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
<style type="text/css">
	body{margin:0 10px;}
</style>
<div class="explain-col">
<form name="myfrom" action="?m=go3c&c=task&a=video" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="task" name="c">
	<input type="hidden" value="video" name="a">
	<input type="hidden" value="query" name="mode">
	<input type="hidden" value="<?php echo $taskId; ?>" name="taskId">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">


<!--	预发布时间：<input type="text" value="" name="crontab_date" id="crontab_date" size="10" class="date" readonly>	
	<script type="text/javascript">
		Calendar.setup({
		weekNumbers: true,
		inputField : "crontab_date",
		trigger    : "crontab_date",
		dateFormat: "%Y-%m-%d",
		showTime: false,
		minuteStep: 1,
		onSelect   : function() {this.hide();}
		});
	</script> 
	
	终端类型：<select name="term_id" id="term_id" onChange="termType(this.value)">
					<option value="">请选择</option>
					<?php if(!empty($term_list)){foreach($term_list as $row){?>
					
					<option value="<?php echo $row['id'];?>" 
					<?php if($term_id == $row['id']){
						echo 'selected';
					}?>><?php echo $row['title']?></option>
					<?php }} ?>
				</select>&nbsp;		
   任务：<select name="posid" id="posid">
				<option value="">请选择</option>
				<?php if(!empty($posid_list)){foreach($posid_list as $row){?>
					
					<option value="<?php echo $row['posid'];?>" 
					<?php if($posid == $row['posid']){
						echo 'selected';
					}?>><?php echo $row['name']?></option>
					<?php }} ?>
			</select>&nbsp;
	可用状态：<select name="filter">
				<option selected="" value="">全部</option>
				<option value="Y">可用</option>
				<option value="N">不可用</option>
			</select>
	-->
	名称：<input type="text" class="input-text" value="<?php echo $videoTitle; ?>" name="title" id="title">
	<input type="submit" value="搜索" class="button" name="doSearch">&nbsp;&nbsp;<a style="color:green;" href="?m=go3c&c=task&a=task&term_id=<?php echo $taskInfo['term_id'];?>">返回任务列表</a>&nbsp;&nbsp;<a style="color:green;" href="?m=go3c&c=task&a=showVideo&taskId=<?php echo $taskId;?>">添加视频</a>
</form> 

</div>

<div class="table-list">
<form action="?m=go3c&c=task&a=listorder" method="post" name="myform2">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="10" align="center">排序</th>
			<th width="20" align="center">序号</th>
			<th width="20" align="center">任务ID</th>
			<th width="80" align="center">所属推荐位</th>			
			<th width="40" align="center">VID</th>
			<th width="100" align="center">名称</th>		
			<th width='30' align="center">视频-清晰度</th>			
			<th width='90' align="center">图片(类型尺寸)</th>	
			<th width='30' align="center">状态</th>
			<th width='30' align="center">是否外链</th>
			<th width="50" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($video_list)){foreach($video_list as $video){?>
	<tr>
	<td align="center"><input type="text" class="input-text-c input-text" value="<?php echo $video['videoSort']?>" size="3" name="listorders[<?php echo $video['preId']?>]"></td>
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
		<a href="<?php echo $video['videoImg']?>" style="color:green" target="_blank">浏览(<?php echo imgtype($video['imgType'])?>)</a>
	</td>
	<td align="center"><?php if($video['status'] =='Y'){echo '可用';}else{echo "<font color='red'>不可用</font>";}?></td>
	<td align="center"><?php if($video['isout'] =='0'){echo '不是';}else{echo "<font color='red'>是</font>";}?></td>
	<td align="center">
	<a style="color:green" href="javascript:editVideoTask('<?php echo $video['preId'];?>','<?php echo $video['isout'];?>');">修改 </a>&nbsp;
	<a style="color:red" href="javascript:confirmurl('?m=go3c&c=task&a=deleteVideo&preId=<?php echo $video['preId'];?>&taskId=<?php echo $video['taskId'];?>','你确定要执行该操作吗？')">删除 </a>
	</td>
	</tr>
	<?php }} ?>

	<?php if(empty($video_list)){?>
	<tr>
	<td align="center" colspan="10">暂无数据</td>
	</tr>
	<?php }?>
	</tbody>
    </table>
	<?php if(!empty($video_list)){?>
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	<div class="btn"><input type="submit" value="排序" name="dosubmit" class="button"></div>
	<?php }?>
	
</form>
</div>

<div id="pages"><?php echo $pages;?></div>

</div>
</body>
</html>
<script type="text/javascript">

//修改己添加的视频
function editVideoTask(preId,isout)
{
	if (preId > 0)
	{
		$.ajax({
			type: "GET",
			url: '?m=go3c&c=task&a=editVideo&preId='+preId+'&isout='+isout+'&pc_hash='+pc_hash,
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
		var videoImgUrl2 = $.trim($('#videoImgUrl2').val());
		var videoImgUrl3 = $.trim($('#videoImgUrl3').val());
		if (videoImgUrl != ''||videoImgUrl2 != ''||videoImgUrl3 != '')
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