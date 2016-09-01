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
	<input type="hidden" value="boot" name="c">
	<input type="hidden" value="bootlist" name="a">
	<input type="hidden" value="<?php echo $term_id;?>" name="term_id" id="term_id">
	<input type="hidden" value="query" name="mode">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	终端:<select id="term" name="term">
		  <option value=''>全部</option>
	      <?php  foreach ($term_type_list as $key => $term) {?>
		  <option value='<?php echo $term['id']?>' <?php if($term_id==$term['id']) echo 'selected';?>><?php echo $term['title']?></option>
		  <?php }?>
	</select>
	项目代号：
	<select id="spid" name="spid">
		  <option value=''>全部</option>
	      <?php  foreach ($spid_list as $key => $spid) {?>
		  <option value='<?php echo $spid['spid']?>' <?php if($_GET['spid']==$spid['spid']) echo 'selected';?>><?php echo $spid['spid']?></option>
		  <?php }?>
	</select>
	<input type="submit" value="搜索" class="button" name="search"> &nbsp;&nbsp;	
<a class="button" style="float:right; text-decoration: none;" href="javascript:addAdTask()">添加</a>
</form> 
</div>

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width='10' align="center"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width="50" align="center">名称</th>
			<th width="50" align="center">背景图类型</th>
			<th width="20" align="center">终端</th>				
			<th width='50' align="center">类型</th>
			<th width='50' align="center">显示方式</th>
			<th width='50' align="center">项目代号</th>
			<th width='50' align="center">图片预览</th>
			<th width='50' align="center">链接</th>
			<th width="80" align="left">操作</th>
            </tr>
        </thead>
    <tbody>
    
    <?php //print_r($advert_list);?>
    
	<?php if(!empty($advert_list)){foreach($advert_list as $advert){?>
		<tr>
		<td align="right"><input type="checkbox" name="cid" value="<?php echo $advert['adId']?>"  /></td>
		<td align="center"><?php echo $advert['position']?></td>
		<td align="center"><?php 
				if($advert['app_bg_img_type'] == '1'){
					echo '应用启动';
				}elseif($advert['app_bg_img_type'] == '2'){
					echo '应用重启';
				}elseif($advert['app_bg_img_type'] == '3'){
					echo '应用切回';
				}elseif($advert['app_bg_img_type'] == '4'){
					echo '视频暂停';
				}else
					echo '无';
		?></td>
		<td align="center">
		<?php 
				if($advert['term_id'] == '1'){
					echo 'STB';
				}elseif($advert['term_id'] == '2'){
					echo 'PAD';
				}elseif($advert['term_id'] == '3'){
					echo 'PHONE';
				}elseif($advert['term_id'] == '4'){
					echo 'PC';
				}
			?>
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
				}elseif($advert['viewType'] == '9'){
					echo '背景图片';
				}
			?>
		</td>		
		<td align="center"><?php echo $advert['spid'];?></td>
		<td align="center"><a href="<?php echo $advert['imgUrl'];?>"  target="_blank" style="color:green">浏览</a></td>
		<td align="center">
			<?php if($advert['linkUrl']){ ?><a href="<?php echo $advert['linkUrl'];?>" target="_blank" style="color:green">浏览</a><?php }else{ ?>无链接<?php }?>
		</td>
		<td align="left">
		<a style="color:green" href="javascript:editAdvert('<?php echo $advert['adId'];?>')">修改</a> | 
		<a href="javascript:confirmurl('?m=go3c&c=task&a=deleteAdvert&adId=<?php echo  $advert['adId'];?>','你确定要执行该操作吗?')">删除</a>
		</td>
		</tr>
	<?php }}else{echo "<tr><td align='center' colspan='7'>暂无数据</td></tr>";}?>
		<tr>
		<td align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
		<td colspan="20">
		<input type="button" onclick="doCKALL();" value="<?php echo '生成升级包'?>" />
		</td>
		</tr>
	</tbody>
    </table> 	
	</div>
    <div id="pages"><?php echo $pages;?></div>
    <input type="hidden" id="zipurl" value="<?php echo $_GET['zipurl']?>">
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
$(function(){
	var url = $('#zipurl').val();
	if (url)
		location.href = url;
		
})
function clickCKB(a){
	$('table input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}

function doCKALL(){
	var customerid = $('#spid').val();
	if (!customerid) {
		alert('请先根据要打包的项目号搜索');
		$('#spid').focus();
		return false;
	}
     var str='';
     $("input[name=cid]:checked").each(function(){    
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //去掉最后一个逗号
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
        //打包选中项
		location.href ='?m=go3c&c=boot&a=generateZip&ids='+str+'&cid='+customerid+'&pc_hash='+pc_hash
    	+'&goback='+BASE64.encode(location.search);
    }else{
    	alert('请勾选打包项');
     }
}

function doconfirmurl(title) {
	//href="http://www.go3c.tv:8060/test/jiaoben/index3.php"
	//获取选中的select的值
	var spid = document.getElementById("spid").value; 
	if(spid==''){
		alert('请选择右上角项目代号再开始预览!');
	}else{
		var url = "http://www.go3c.tv:8060/go3ccms/jiaoben/index3.php?spid="+spid;
		window.open(url);
	}
}
<!--
//添加开机任务
function addAdTask()
{
	//终端
	var term_id = $.trim($('#term_id').val());
	if (term_id > 0)
	{
		$.ajax({
			type: "GET",
			url: 'index.php?m=go3c&c=boot&a=addAdvert&term_id='+term_id+'&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'添加开机任务',
					id:'viewOnlyDiv',
					lock:true,
					width:'600'
				});
			}
		});
	}else{
		alert('请选择终端类型进来再操作');
	}
}
//预览开机图
function showview(term_id, spid) {
    location.href ='?m=go3c&c=boot&a=showview&term_id='+term_id+'&spid='+spid+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
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

//名称 ad_position
function checkpc()
{	
	var regtype = $.trim($('#regtype').val());
	if (regtype == '1')
	{
		$('#ad_regtype_error').hide();
	}else{
		$('#ad_regtype_error').show();
	}
}
//添加广告任务表单验证
function checkAdTaskad()
{
	var ad_imgUrl = $('#ad_imgUrl').val();
	if (ad_imgUrl==''){
		$('#ad_imgUrl_error').show();
		return false ;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
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
			url: 'index.php?m=go3c&c=boot&a=editAdvert&term_id='+term_id+'&adId=' + adId + '&pc_hash='+pc_hash,
			success: function(msg){
				art.dialog({
					content: msg,
					title:'修改开机任务',
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
	var ad_imgUrl = $('#ad_imgUrl').val();
	if (ad_imgUrl==''){
		$('#ad_ad_imgUrl_error').show();
		return false ;
	}
	if (!$('#regtype').attr('checked')){
		$('#ad_regtype_error').show();
		return false ;
	}
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
		var adImgTypeOne = $('#ImgText').attr('checked');	//方式一
		var adImgTypeTwo = $('#ImgFile').attr('checked');	//方式二
		if (adImgTypeOne)
		{
			var ad_imgUrl = $.trim($('#ad_imgUrl').val());
			if (ad_imgUrl != '')
			{
				$('#ad_imgUrl_error').hide();
			}else{
				$('#ad_imgUrl_error').show();
				return false;
			}
		}else if (adImgTypeTwo){
			var ad_imgUrlFile = $.trim($('#ad_imgUrlFile').val());
			if (ad_imgUrlFile != '')
			{
				$('#ad_imgUrlFile_error').hide();
			}else{
				$('#ad_imgUrlFile_error').show();
				return false;
			}
		}
		/*
		var ad_imgUrl = $.trim($('#ad_imgUrl').val());
		if (ad_imgUrl != '')
		{
			$('#ad_imgUrl_error').hide();
		}else{
			$('#ad_imgUrl_error').show();
			return false;
		}
		*/
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
