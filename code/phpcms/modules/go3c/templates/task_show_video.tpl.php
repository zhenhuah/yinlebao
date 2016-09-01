<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<script type="text/javascript">
	var current_taskId = '<?php echo $taskInfo['taskId'];?>';
</script>
<div class="explain-col">
<div class="pad_10">
<div class="common-form">
<table width="100%" class="table_form">
	<tbody>
	<tr>
	<td >
		<b>任务ID：</b> <?php echo $taskInfo['taskId'];?> &nbsp;
		<b>推荐位类型：</b> <?php echo $taskInfo['taskBase'][0].'-'.$taskInfo['taskBase'][1];?>&nbsp;
		<b>预发布日期：</b> <?php echo date('Y-m-d',$taskInfo['taskDate']);?>&nbsp;
		<b>视频类型：</b> <?php 
				if ($taskInfo['videoSource'] == '1')
				{
					echo '直播';
				}elseif ($taskInfo['videoSource'] == '2')
				{
					echo '点播';
				}elseif ($taskInfo['videoSource'] == '3')
				{
					echo 'EPG';
				}
		  ?>&nbsp;
		 <b>视频上下限范围：</b><?php echo $taskInfo['start_end_nums'];?>
		<b>己添加视频：</b><?php echo $taskInfo['videoNums'];?>个
		<a style="color:green;" href="?m=go3c&c=task&a=task&term_id=<?php echo $taskInfo['term_id'];?>">返回任务列表</a>&nbsp;<a style="color:green;" href="?m=go3c&c=task&a=video&mode=query&taskId=<?php echo $taskInfo['taskId'];?>">返回详细列表</a>
	</td> 
	</tr>
	</tbody>
</table>    
</div>
</div>
</div>
<div class="bk10"></div>
<div class="explain-col">
	<form action="?m=go3c&c=task&a=showVideo&taskId=<?php echo $taskInfo['taskId'];?>" method="get">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="task" name="c">
	<input type="hidden" value="showVideo" name="a">
	<input type="hidden" value="<?php echo $taskInfo['taskId'];?>" name="taskId">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	<input type="hidden" value="query" name="mode">

	<!-- 直播 start -->
	<?php if($taskInfo['videoSource'] == '1'){ ?>
		   	频道ID：<input type="text" class="input-text" value="<?php echo $channel_id;?>" name="channel_id">
			频道名：<input type="text" class="input-text" value="<?php echo $channelName;?>" name="channelName">
            状态：<select name="published_online_status">
				<option value="">全部</option>
				<option value="1" <?php if($published_online_status == '1'){ echo 'selected';}?>>己上线</option>
				<option value="11" <?php if($published_online_status == '11'){ echo 'selected';}?>>已审核通过</option>
			</select>
			
		<?php } ?>
	<!-- 直播 end -->
	
	<!-- 点播 start -->
	<?php if($taskInfo['videoSource'] == '2'){ ?>
		   	VID：<input type="text" class="input-text" value="<?php echo $asset_id;?>" name="asset_id">
			名称：<input type="text" class="input-text" value="<?php echo $keyword;?>" name="keyword">
            状态：<select name="published_online_status">
				<option value="">全部</option>
				<option value="1" <?php if($published_online_status == '1'){ echo 'selected';}?>>己上线</option>
				<option value="11" <?php if($published_online_status == '11'){ echo 'selected';}?>>已审核通过</option>
			</select>
            类型：<select name="column_id">
				<option value="">全部</option>
				 <?php {foreach($cms_column as $key=>$column){?>
           		 <option value='<?php echo $column['id']?>' <?php if($_GET['column_id']==$column['id']) echo 'selected';?>><?php echo $column['title']?></option>
				<?php }} ?>
			</select>
	总集：<select name="ispackage">
				<option value='' <?php if($_GET['ispackage']==0) echo 'selected';?>>全部</option>
				<option value='1' <?php if($_GET['ispackage']==1) echo 'selected';?>>总集</option>
			</select>		
		<?php } ?>
	<!-- 点播 end -->

	<!-- EPG start -->
	<!--频道号：文本框    频道名： 文本框   节目名： 文本框     日期：日期插件-->
	<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
	<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
	<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
	<?php if($taskInfo['videoSource'] == '3'){ ?>
		   	频道ID：<input type="text" class="input-text" value="<?php echo $channel_id;?>" name="channel_id">
			频道名：<input type="text" class="input-text" value="<?php echo $channelName;?>" name="channelName">
			节目名称：<input type="text" class="input-text" value="<?php echo $keyword;?>" name="keyword">
            日期：<input type="text" value="<?php echo $starttime;?>" name="starttime" id="starttime" size="10" class="date" readonly>	
	<script type="text/javascript">
		Calendar.setup({
		weekNumbers: true,
		inputField : "starttime",
		trigger    : "starttime",
		dateFormat: "%Y-%m-%d",
		showTime: false,
		minuteStep: 1,
		onSelect   : function() {this.hide();}
		});
	</script> 
		<?php } ?>
	<!-- EPG end -->
	<input type="submit" value="搜索" class="button" name="search">  &nbsp;<a style="color:green;" href="?m=go3c&c=task&a=showVideo&taskId=<?php echo $taskInfo['taskId'];?>">支持此终端的全部数据</a>
	</form>
</div>

<div class="table-list">
<table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="35" align="center">序号</th>		
			<th width="40" align="center">
			<?php 
			
				if($videoSource == '1')
				{
					echo '频道ID';
				}elseif($videoSource == '2'){
					echo 'VID';
				}elseif($videoSource == '3'){
					echo '开始时间';
				}
			?>
			</th>
			<?php if($videoSource == '3'){ ?>
				<th width="100" align="center">结束时间</th>
			<?php } ?>
			<th width="100" align="center">名称</th>			
			<?php if($videoSource == '3'){?>
			<th width="50" align="center">频道名称-ID</th>
			<?php }?>

			<th width="50" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($video_list)){foreach($video_list as $key => $video){?>
	<tr>
	<td align="center"><?php echo $key+1;?></td>
	<td align="center">
		<?php 
			if($videoSource == '1')
			{
				echo $video['channel_id'];
			}elseif($videoSource == '2'){
				echo $video['asset_id'];
			}elseif($videoSource == '3'){
				//echo $video['epgid'];
				echo $video['endtime'];
			}
		?>
	</td>
	<?php if($videoSource == '3'){ ?>
	<td align="center"><?php echo $video['starttime']; ?></td>
	<?php } ?>
	<td align="center">
		<?php 
			if($videoSource == '1')
			{
				echo $video['title'];
			}elseif($videoSource == '2'){
				echo $video['title'];
			}elseif($videoSource == '3'){
				echo $video['text'];
			}
		?></td>	
	
	<?php if($videoSource == '3'){?>
	<td  align="center"><?php echo $video['title'].'-'.$video['channel_id']?></td>
	<?php }?>

	<td align="center">
	<a style="color:green" href="javascript:addVideo('<?php echo intval($videoSource);?>','
		<?php 
			if($videoSource == '1')
			{
				echo $video['channel_id'];
			}elseif($videoSource == '2'){
				echo $video['asset_id'];
			}elseif($videoSource == '3'){
				//echo $video['epgid'];
				echo $video['id'];
			}
		?>');">选择 </a>
	</td>
	</tr>
	<?php }} ?>
	</tbody>
</table>
</div>
<div id="pages"><?php echo $pages;?></div>
</div>
</body>
</html>


<script type="text/javascript">
//添加到当前任务下
function addVideo(dataType,videoId)
{	
	if ((dataType > 0) && (videoId != ''))
	{
		switch(dataType)
		{
			case '1':	//直播或频道
				var status = '1';
			break;

			case '2':	//点播
				var status = '1';
			break;
			
			case '3'://EGP
				var status = '1';
			break;
			
			default:
				return false;
			break;

		}
	}else{
		return false;
	}
		
	$.ajax({
		type: "GET",
		url: 'index.php?m=go3c&c=task&a=addVideo&taskId='+current_taskId+'&videoId='+videoId+'&dataType='+dataType+'&pc_hash='+pc_hash,
		success: function(msg){
			if (msg == '1')
			{
				var msg = '当前选择的数据己经添加了，请不要重复操作(或到任务详细列表中修改)!';
			}
			art.dialog({
				content: msg,
				title:'添加视频',
				id:'viewOnlyDiv',
				lock:true,
				width:'680'
			});
		}
	});

}
</script>