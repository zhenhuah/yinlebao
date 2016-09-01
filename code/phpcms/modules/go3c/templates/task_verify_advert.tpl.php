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
<style type="text/css">
	body{margin:0 10px;}
	.table-list{margin-top:10px;}
</style>

<div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="task" name="c">
	<input type="hidden" value="verifyAdvert" name="a">
	<input type="hidden" value="<?php echo $term_id;?>" name="term_id" id="term_id">
	<input type="hidden" value="query" name="mode">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">

	终端类型：<select name="term_id" id="term_id" onChange="termType(this.value,'posid')">
					<option value="">请选择</option>
					<?php if(!empty($term_list)){foreach($term_list as $row){?>
					
					<option value="<?php echo $row['id'];?>" 
					<?php if($term_id == $row['id']){
						echo 'selected';
					}?>><?php echo $row['title']?></option>
					<?php }} ?>
				</select>&nbsp;
	项目代号：<select name="spid" id="spid" onChange="termType(this.value,'posid')">
					<option value="">请选择</option>
					<?php if(!empty($sp_list)){foreach($sp_list as $row){?>					
					<option value="<?php echo $row['spid'];?>" 
					<?php if($_GET['spid'] == $row['spid']){
						echo 'selected';
					}?>><?php echo $row['spid']?></option>
					<?php }} ?>
				</select>&nbsp;	
  广告类型：<select name="adType" id="adType">
				<option value="">请选择</option>
				<option value="1" <?php if($adType == '1'){
						echo 'selected';
					}?>>文字</option>
				<option value="2" <?php if($adType == '2'){
						echo 'selected';
					}?>>图片</option>
				<option value="3" <?php if($adType == '3'){
						echo 'selected';
					}?>>视频</option>
				<option value="0" <?php if($adType == '0'){
						echo 'selected';
					}?>>引导图</option>
			</select>  &nbsp; 
 显示方式：<select name="viewType" id="viewType">
				<option value="">请选择</option>
				<option value="1" <?php if($viewType == '1'){
						echo 'selected';
					}?>>水平跑马灯</option>
				<option value="2" <?php if($viewType == '2'){
						echo 'selected';
					}?>>垂直跑马灯</option>
				<option value="3" <?php if($viewType == '3'){
						echo 'selected';
					}?>>图片翻转</option>
				<option value="4" <?php if($viewType == '4'){
						echo 'selected';
					}?>>嵌入小视频</option>
				<option value="5" <?php if($viewType == '5'){
						echo 'selected';
					}?>>全屏视频</option>
				<option value="6" <?php if($viewType == '6'){
						echo 'selected';
					}?>>顶部跑马灯</option>
				<option value="7" <?php if($viewType == '7'){
						echo 'selected';
					}?>>底部跑马灯</option>
				<option value="8" <?php if($viewType == '8'){
						echo 'selected';
					}?>>右下角弹窗</option>
			</select>  &nbsp;
	预发布时间：<input type="text" value="<?php echo $taskDate;?>" name="taskDate" id="taskDate" size="10" class="date" >
	<script type="text/javascript">
		Calendar.setup({
		weekNumbers: true,
		inputField : "taskDate",
		trigger    : "taskDate",
		dateFormat: "%Y-%m-%d",
		showTime: false,
		minuteStep: 1,
		onSelect   : function() {this.hide();}
		});
	</script> &nbsp;
	<input type="submit" value="搜索" class="button" name="search">
</form> 
</div>

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="35" align="center">ID</th>
			<th width="50" align="center">广告推荐位</th>
			<th width='50' align="center">标题</th>			
			<th width='50' align="center">广告类型</th>
			<th width='50' align="center">显示方式</th>	
			<th width='50' align="center">预发布时间</th>			
			<th width='50' align="center">图片预览</th>
			<th width='50' align="center">链接预览</th>
			<th width='50' align="center">状态</th>
			<th width="80" align="left">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($advert_list)){foreach($advert_list as $advert){?>
		<tr>
		<td align="center"><?php echo $advert['adId']?></td>
		<td align="center"><?php echo $advert['position']?></td>
		<td align="center" style="color:blue"><?php echo $advert['title'];?></td>
		<td align="center">
			<?php 
				if($advert['adType'] == '1'){
					echo '文字';
				}elseif($advert['adType'] == '2'){
					echo '图片';
				}elseif($advert['adType'] == '3'){
					echo '视频';
				}elseif($advert['adType'] == '0'){
					echo '引导图';
				}
			?>
		</td>		
		<td align="center">
			<?php 
				if($advert['viewType'] == '1'){
					echo '水平跑马灯';
				}elseif($advert['viewType'] == '2'){
					echo '垂直跑马灯';
				}elseif($advert['viewType'] == '3'){
					echo '图片翻转';
				}elseif($advert['viewType'] == '4'){
					echo '嵌入小视频';
				}elseif($advert['viewType'] == '5'){
					echo '全屏视频';
				}elseif($advert['viewType'] == '6'){
					echo '顶部跑马灯';
				}elseif($advert['viewType'] == '7'){
					echo '底部跑马灯';
				}elseif($advert['viewType'] == '8'){
					echo '右下角弹窗';
				}
			?>
		</td>		
		<td align="center"><?php echo date('Y-m-d',$advert['taskDate']);?></td>
		<td align="center"><a href="<?php echo $advert['imgUrl'];?>"  target="_blank" style="color:green">预览</a></td>
		<td align="center">
			<?php if($advert['linkUrl']){ ?><a href="<?php echo $advert['linkUrl'];?>" target="_blank" style="color:green">预览</a><?php }else{ ?>无链接<?php }?>
		</td>
		<td align="center"><?php echo $taskStatus_data[$advert['adStatus']];?></td>
		<td align="center">
		<?php if(($advert['adStatus'] > 0) && ($advert['adStatus'] < 4)) { ?>
		<a style="color:green" href="javascript:confirmurl('?m=go3c&c=task&a=verifyAdvert&term_id=<?php echo $advert['term_id'];?>&adId=<?php echo $advert['adId'];?>&status=Y','你确定要执行该操作吗？')">通过</a>  |
		<a style="color:red" href="javascript:confirmurl('?m=go3c&c=task&a=verifyAdvert&term_id=<?php echo $advert['term_id'];?>&adId=<?php echo $advert['adId'];?>&status=N','你确定要执行该操作吗？')">拒绝</a> 
		<?php } ?>
		</td>
		</tr>
	<?php }}else{echo "<tr><td align='center' colspan='7'>暂无数据</td></tr>";}?>
	</tbody>
    </table>
	</div>
    <div id="pages"><?php echo $pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
<!--
//添加广告任务
function addAdTask()
{
	//终端
	var term_id = $.trim($('#term_id').val());
	if (term_id > 0)
	{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=task&a=addAdvert&term_id='+term_id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'添加广告任务',
					id:'viewOnlyDiv',
					lock:true,
					width:'550'
				});
			}
		});
	}else{
		alert('请选择终端类型进来再操作');
	}
}

//名称 ad_position
function checkParentId()
{	
	var ad_position = $.trim($('#ad_position').val());
	if (ad_position != '')
	{
		$('#ad_position_error').hide();
		$(".data").hide();
		
		//判断数据类型
		var ad_type = $('#parentId_'+ad_position).val();

		if (ad_type == '1')	//文字
		{
			$("#data_1").show();
		}else if (ad_type == '2'){	//图片
			$("#data_2").show();
		}else if (ad_type == '3'){	//视频
			$("#data_3").show();
		}
	}else{
		$(".data").hide();
		$('#ad_position_error').show();
	}
}


//添加广告任务表单验证
function checkAdTask()
{
	//名称 ad_position
	var ad_position = $.trim($('#ad_position').val());

	if (ad_position != '')
	{
		$('#ad_position_error').hide();
		
		//判断数据类型
		var ad_type = $('#parentId_'+ad_position).val();

		if (ad_type == '1')	//文字
		{
			var ad_adDesc = $.trim($('#ad_adDesc').val());
			if (ad_adDesc != '')
			{
				$('#ad_adDesc_error').hide();
			}else{
				$('#ad_adDesc_error').show();
				return false;
			}
		}else if (ad_type == '2'){	//图片
			var ad_imgUrl = $.trim($('#ad_imgUrl').val());
			if (ad_imgUrl != '')
			{
				$('#ad_imgUrl_error').hide();
			}else{
				$('#ad_imgUrl_error').show();
				return false;
			}
		}else if (ad_type == '3'){	//视频
			var ad_linkUrl = $.trim($('#ad_linkUrl').val());
			if (ad_linkUrl != '')
			{
				$('#ad_linkUrl_error').hide();
			}else{
				$('#ad_linkUrl_error').show();
				return false;
			}
		}
	}else{
		$('#ad_position_error').show();
		return false;
	}
	


	/*
	//广告类型 ad_adType
	var ad_adType = $.trim($('#ad_adType').val());
	if (ad_adType != '')
	{
		$('#ad_adType_error').hide();
	}else{
		$('#ad_adType_error').show();
		return false;
	}


	//显示方式 ad_viewType
	var ad_viewType = $.trim($('#ad_viewType').val());
	if (ad_viewType != '')
	{
		$('#ad_viewType_error').hide();
	}else{
		$('#ad_viewType_error').show();
		return false;
	}
	
	
	//文字 ad_viewType
	var ad_adDesc = $.trim($('#ad_adDesc').val());
	if (ad_adDesc != '')
	{
		$('#ad_adDesc_error').hide();
	}else{
		$('#ad_adDesc_error').show();
		return false;
	}*/
	

	//预发布日期 task_taskDate
	var ad_taskDate = $.trim($('#ad_taskDate').val());
	if (ad_taskDate != '')
	{
		$('#ad_taskDate_error').hide();
	}else{
		$('#ad_taskDate_error').show();
		return false;
	}
	
	return true;
}

//修改广告
function editAdvert(adId)
{
	if (adId > 0)
	{
		var term_id = $.trim($('#term_id').val());

		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=task&a=editAdvert&term_id='+term_id+'&adId=' + adId + '&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'修改广告任务',
					id:'viewOnlyDiv',
					lock:true,
					width:'550'
				});
			}
		});
	}
}

//添加广告任务表单验证
function checkEditAdTask()
{
	//判断数据类型
	var ad_type = $('#ad_type').val();

	if (ad_type == '1')	//文字
	{
		var ad_adDesc = $.trim($('#ad_adDesc').val());
		if (ad_adDesc != '')
		{
			$('#ad_adDesc_error').hide();
		}else{
			$('#ad_adDesc_error').show();
			return false;
		}
	}else if (ad_type == '2'){	//图片
		var ad_imgUrl = $.trim($('#ad_imgUrl').val());
		if (ad_imgUrl != '')
		{
			$('#ad_imgUrl_error').hide();
		}else{
			$('#ad_imgUrl_error').show();
			return false;
		}
	}else if (ad_type == '3'){	//视频
		var ad_linkUrl = $.trim($('#ad_linkUrl').val());
		if (ad_linkUrl != '')
		{
			$('#ad_linkUrl_error').hide();
		}else{
			$('#ad_linkUrl_error').show();
			return false;
		}
	}
	

	//预发布日期 task_taskDate
	var ad_taskDate = $.trim($('#ad_taskDate').val());
	if (ad_taskDate != '')
	{
		$('#ad_taskDate_error').hide();
	}else{
		$('#ad_taskDate_error').show();
		return false;
	}
	
	return true;
}

//-->
</script>