<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');?>
<script type="text/javascript">
<!--
var charset = '<?php echo CHARSET;?>';
var uploadurl = '<?php echo pc_base::load_config('system','upload_url')?>';
//-->
</script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>colorpicker.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>hotkeys.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>cookie.js"></script>
<script type="text/javascript">var catid=<?php echo $catid;?></script>
<form name="myform" id="myform" action="?m=content&c=content&a=edit" method="post" enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<!--div class="pad-10">
<div class="content-menu ib-a blue line-x">
<a class="add fb" href="?m=content&c=content&a=add&menuid=&catid=<?php echo $catid;?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>"><em><?php echo L('add_content');?></em></a>
<a href="?m=content&c=content&a=init&catid=<?php echo $catid;?>&pc_hash=<?php echo $pc_hash;?>" <?php if($steps==0 && !isset($_GET['reject'])) echo 'class=on';?>><em><?php echo L('check_passed');?></em></a>
<?php if($category['ishtml']) {?>
<span>|</span><a href="?m=content&c=create_html&a=category&pagesize=30&dosubmit=1&modelid=0&catids[0]=<?php echo $catid;?>&pc_hash=<?php echo $pc_hash;?>&referer=<?php echo urlencode($_SERVER['QUERY_STRING']);?>"><em><?php echo L('update_htmls',array('catname'=>$category['catname']));?></em></a>
<?php }?>
</div>

</div-->
<div class="col-right" style="display:none;">
    	<div class="col-1">
        	<div class="content pad-6">
<?php
if(is_array($forminfos['senior'])) {
	foreach($forminfos['senior'] as $field=>$info) {
		if($info['isomnipotent']) continue;
		if($info['formtype']=='omnipotent') {
			foreach($forminfos['base'] as $_fm=>$_fm_value) {
				if($_fm_value['isomnipotent']) {
					$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
				}
			}
			foreach($forminfos['senior'] as $_fm=>$_fm_value) {
				if($_fm_value['isomnipotent']) {
					$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
				}
			}
		}
 ?>
	<h6><?php if($info['star']){ ?> <font color="red">*</font><?php } ?> <?php echo $info['name']?></h6>
	 <?php echo $info['form']?><?php echo $info['tips']?> 
<?php
	} }
?>
          </div>
        </div>
    </div>
    
    <div class="col-auto">
    	<div class="col-1">
        	<div class="content pad-6">
<table width="100%" cellspacing="0" class="table_form">
	<tbody>	
<?php
if(is_array($forminfos['base'])) {

	/*  echo '<pre>';
	print_r($forminfos['base']);
	echo '</pre>';*/

	foreach($forminfos['base'] as $field=>$info) {
		if($info['isomnipotent']) continue;
		if($info['formtype']=='omnipotent') {
			foreach($forminfos['base'] as $_fm=>$_fm_value) {
				if($_fm_value['isomnipotent']) {
					$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
				}
			}
			foreach($forminfos['senior'] as $_fm=>$_fm_value) {
				if($_fm_value['isomnipotent']) {
					$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
				}
			}
		}
 	?>
	<tr>
      <th width="80"><?php if($info['star']){ ?> <font color="red">*</font><?php } ?> <?php echo $info['name']?>
	  </th>
      <td><?php echo $info['form']?>  <?php echo $info['tips']?></td>
    </tr>
	<?php
	} }
    ?>

<!-- 自动搜索结果接口数据处理 start -->
<?php if($catid == '54') { ?>
<tr>
    <th width="80">支持终端:</th>
	<td>
	PAD<input type="checkbox" name="PAD" value="1" <?php if($rv['PAD']==1) echo("checked");?>>&nbsp;&nbsp;&nbsp;&nbsp;
	STB<input type="checkbox" name="STB" value="1" <?php if($rv['STB']==1) echo("checked");?>>&nbsp;&nbsp;&nbsp;&nbsp;
	PHONE<input type="checkbox" name="PHONE" value="1" <?php if($rv['PHONE']==1) echo("checked");?>>&nbsp;&nbsp;&nbsp;&nbsp;
	PC<input type="checkbox" name="PC" value="1" <?php if($rv['PC']==1) echo("checked");?>>
	</td>
</tr>
<tr>
<th width="80">区域选择:</th>
<td>
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<?php echo $categorys;?>
</tbody>
</table>
</td>
</tr>
<link href="<?php echo CSS_PATH?>jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.treetable.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dnd-example").treeTable({
    	indent: 20
    	});
  });
  function checknode(obj)
  {
      var chk = $("input[type='checkbox']");
      var count = chk.length;
      var num = chk.index(obj);
      var level_top = level_bottom =  chk.eq(num).attr('level')
      for (var i=num; i>=0; i--)
      {
              var le = chk.eq(i).attr('level');
              if(eval(le) < eval(level_top)) 
              {
                  chk.eq(i).attr("checked",true);
                  var level_top = level_top-1;
              }
      }
      for (var j=num+1; j<count; j++)
      {
              var le = chk.eq(j).attr('level');
              if(chk.eq(num).attr("checked")==true) {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",true);
                  else if(eval(le) == eval(level_bottom)) break;
              }
              else {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",false);
                  else if(eval(le) == eval(level_bottom)) break;
              }
      }
  }
</script>
<script type="text/javascript">

//视频上线
//var tagsType = jQuery("input[name='info[column_id]']:checked").val();
//alert(tagsType);
jQuery("input[id='tag_pop_menu']").click(function(){
	//alert(jQuery("input[name='info[column_id]'][@checked]").val());
	window.top.art.dialog(
	{
		id:'testIframe',
		iframe:"?m=go3c&c=authserach&a=tags&mode=query&tagsType="+jQuery("input[name='info[column_id]']:checked").val()+"&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
		title:'编辑TAG标签',
		width:'700',
		height:'600'
	},
	function(){
		var ifram_page_data = window.top.art.dialog({id:'testIframe'}).data.iframe;
		var setAllTags = ifram_page_data.document.getElementById('setAllTags').value;
		if (setAllTags != '') {
			//当前是否己经存在值了
			var currentTemp = jQuery.trim(jQuery("input[name='info[tag]']").val());
			if (currentTemp != '')
			{
				//设置视频ID
				jQuery("input[name='info[tag]']").val(currentTemp+','+setAllTags);
			}else{
				//设置视频ID
				jQuery("input[name='info[tag]']").val(setAllTags);
			}

			//设置视频名称
			//jQuery("input[name='info[title]']").val(title);
			//alert('设置成功!');

		}else{
			//title.focus();
			//ifram_page_data.document.getElementById('tips').innerHTML = "<font color='red'>请设置tags</font>";
			alert('请设置!');
			return false; // 返回false即可阻止对话框关闭

		}
	}
	);
});

//javascript:_MP(2273,'?m=go3c&c=task&a=task&term_id=3');
jQuery("input[id='parent_id_pop_menu']").click(function(){
	window.top.art.dialog(
	{
		id:'testIframe',
		iframe:"?m=go3c&c=authserach&a=video&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
		title:'总集搜索',
		width:'700',
		height:'600'
	},
	function(){
		var ifram_page_data = window.top.art.dialog({id:'testIframe'}).data.iframe;
		var asset_id = ifram_page_data.document.getElementById('set_asset_id').value;
		var title = ifram_page_data.document.getElementById('set_title').value;
		if ((asset_id != '') && (title != '')) {
			//设置视频ID
			jQuery("input[name='info[parent_id]']").val(asset_id);
			//设置视频名称
			//jQuery("input[name='info[title]']").val(title);
			//alert('设置成功!');
		}else{
			//title.focus();
			ifram_page_data.document.getElementById('tips').innerHTML = "<font color='red'>必须填写关键字！</font>";

			return false; // 返回false即可阻止对话框关闭

		}
	}/*,
	function(){
	//alert('ddd');
	window.top.art.dialog({id:'testIframe'}).close()
	}*/
	);
});
</script> 
<?php } ?>

<!-- PPTV链接搜索开始 -->
<?php if($catid == '64') { ?>
<script type="text/javascript">
jQuery("input[id='path_pop_menu']").click(function(){
	window.top.art.dialog(
	{
		id:'testIframe',
		iframe:"?m=go3c&c=pptvserach&a=video&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
		title:'PPTV链接搜索',
		width:'1000',
		height:'600'
	},
	function(){
		var ifram_page_data = window.top.art.dialog({id:'testIframe'}).data.iframe;
		var asset_id = ifram_page_data.document.getElementById('set_asset_id').value;
		if ((asset_id != '')) {
			//设置视频链接
			jQuery("input[name='info[path]']").val(asset_id);
		}else{
			//title.focus();
			ifram_page_data.document.getElementById('tips').innerHTML = "<font color='red'>必须填写关键字！</font>";
			return false; // 返回false即可阻止对话框关闭
		}
	}
	);
});
</script> 
<?php } ?>
<!-- PPTV链接搜索结束 -->

<!-- 频道分类一对多 start -->
<?php if($catid == '63') {?>
<tr>
    <th width="80">节目类型:</th>
	<td>
	<?php {foreach($channel_category as $key=>$channel){?>
	<?=$channel['title']?><input type="checkbox" name="channel_category" value="<?php echo $channel['id']?>"<?php if($channel['kt']) echo("checked");?>>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php }} ?>
	</td>
 </tr>
<tr>
    <th width="80">支持终端:</th>
	<td>
	PAD<input type="checkbox" name="PAD" value="1" <?php if($limitInfo['PAD']==1) echo("checked");?>>&nbsp;&nbsp;&nbsp;&nbsp;
	STB<input type="checkbox" name="STB" value="1" <?php if($limitInfo['STB']==1) echo("checked");?>>&nbsp;&nbsp;&nbsp;&nbsp;
	PHONE<input type="checkbox" name="PHONE" value="1" <?php if($limitInfo['PHONE']==1) echo("checked");?>>&nbsp;&nbsp;&nbsp;&nbsp;
	PC<input type="checkbox" name="PC" value="1" <?php if($limitInfo['PC']==1) echo("checked");?>>
	</td>
</tr>
<tr>
<th width="80">区域选择:</th>
<td>
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<?php echo $categorys;?>
</tbody>
</table>
</td>
</tr>
<link href="<?php echo CSS_PATH?>jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.treetable.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dnd-example").treeTable({
    	indent: 20
    	});
  });
  function checknode(obj)
  {
      var chk = $("input[type='checkbox']");
      var count = chk.length;
      var num = chk.index(obj);
      var level_top = level_bottom =  chk.eq(num).attr('level')
      for (var i=num; i>=0; i--)
      {
              var le = chk.eq(i).attr('level');
              if(eval(le) < eval(level_top)) 
              {
                  chk.eq(i).attr("checked",true);
                  var level_top = level_top-1;
              }
      }
      for (var j=num+1; j<count; j++)
      {
              var le = chk.eq(j).attr('level');
              if(chk.eq(num).attr("checked")==true) {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",true);
                  else if(eval(le) == eval(level_bottom)) break;
              }
              else {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",false);
                  else if(eval(le) == eval(level_bottom)) break;
              }
      }
  }
</script>
<?php }?>
<!-- 频道分类一对多 end -->

<!-- 自动搜索结果接口数据处理 start -->
	<?php if($catid == '54') {?>
	
	
	<tr>
      <th width="80">视频链接</th>
      <td>
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr style="background:#DDDDDD;">
            <td width="50">clarity</td>
            <!--td width="50">格式</td>
            <td width="50">码率</td>
            <td width="100">终端类型</td>
            <td width="80">清晰度</td-->
            <td width="80">视频来源</td>
            <td>预览(<a style="color:blue" href="?m=go3c&c=mediaplay&a=mediaplay_multiview&assetid=<?=$data[asset_id]?>">多屏播放</a>)</td>
            <?php if($editenable){?>
            <td width="40"></td>
            <td width="40">
              <a href="javascript:video_content_add('<?php echo $data['asset_id'];?>');void(0);"><strong style="color:red;">添加</strong></a>
            </td>
            <?php }?>
          </tr>
		  <?php
		  if(is_array($cms_video_content)) {
		  	foreach($cms_video_content as $key => $value) {
		  		//print_r($dbmover_source_type_array);
		  ?>
          <tr>
            <td><?=$value['clarity']?></td>
            <!--td><?=$dbmover_link_type_array[$value['clarity']]['format']?></td>
            <td><?=$dbmover_link_type_array[$value['clarity']]['code_rate']?></td>
            <td><?=$dbmover_link_type_array[$value['clarity']]['description']?></td>
            <td><?=$dbmover_link_type_array[$value['clarity']]['display_width']?>x<?=$dbmover_link_type_array[$value['clarity']]['display_height']?></td-->
			<td><?=$dbmover_source_type_array[$value['source_id']]['title']?></td>
            <td style="width:300px; word-break: break-all; word-wrap:break-word;">
              <a href="?m=go3c&c=mediaplay&a=mediaplay_view&assetid=<?=$data[asset_id]?>&id=<?=$value['id']?>"><?=$value['path']?></a>
            </td>
			<?php if($editenable){?>
            <!--td><a href="javascript:video_content_edit('<?php echo $value['id']?>');void(0);">编辑</a></td>-->
			<td><a href="javascript:video_content_delete('<?php echo $value['id']?>');void(0);">删除</a></td>
			<?php }?>
          </tr>
          <?php }}?>
        </table>
      </td>
    </tr>
    
    
	<tr>
      <th width="80">视频海报</th>
      <td>
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr style="background:#DDDDDD;">
            <td width="50">type</td>
            <td width="150">用途</td>
            <td width="50">格式</td>
            <td width="80">尺寸</td>
            <td>地址</td>
            <td>预览</td>
            <?php if($editenable){?>
            <td width="40"></td>
            <td width="40"><a href="javascript:video_poster_add('<?php echo $data['asset_id'];?>');void(0);"><strong style="color:red;">添加</strong></a></td>
            <?php }?>
          </tr>
		  <?php
		  if(is_array($cms_video_poster)) {
		  	//print_r($cms_video_poster);
		  	foreach($cms_video_poster as $key => $value1) {
		  ?>
          <tr>
            <td><?=$value1['type']?></td>
            <td><?=$dbmover_poster_type_array[$value1['type']]['description']?></td>
            <td><?=$dbmover_poster_type_array[$value1['type']]['file_format']?></td>
            <td><?=$dbmover_poster_type_array[$value1['type']]['resolution_ratio']?></td>
            <td style="width:600px; word-break: break-all; word-wrap:break-word;"><a href="<?=$value1['path']?>" target="_blank"><?=$value1['path']?></a></td>
            <td><a href="<?=$value1['path']?>" target="_blank"><img style="width:80px; border:solid 1px gray; padding:2px;" src="<?=$value1['path']?>" /></a></td>
			<?php if($editenable){?>
            <td><a href="javascript:video_poster_edit('<?php echo $value1['id']?>');void(0);">编辑</a></td>
			<td><a href="javascript:video_poster_delete('<?php echo $value1['id']?>');void(0);">删除</a></td>
			<?php }?>
          </tr>
          <?php }}?>
        </table>
      </td>
    </tr>
    
	<tr>
      <th width="80">视频字幕</th>
      <td>
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr style="background:#DDDDDD;">
            <td width="50">视频id</td>
            <td width="150">语言</td>
            <td width="50">优先适配来源</td>
            <td width="80">优先适配时长</td>
            <td>字幕文件</td>
            <?php if($editenable){?>
            <td width="40"></td>
            <td width="40"><a href="javascript:video_sub_add('<?php echo $data['asset_id'];?>');void(0);"><strong style="color:red;">添加</strong></a></td>
          	<?php }?>
          </tr>
		  <?php
		  if(is_array($video_subtitle)) {
		  	//print_r($cms_video_poster);
		  	foreach($video_subtitle as $key => $value1) {
		  ?>
          <tr>
            <td><?=$value1['asset_id']?></td>
            <td><?=$value1['language']?></td>
            <td><?=$value1['source']?></td>
            <td><?=$value1['run_time']?></td>
            <td style="width:600px; word-break: break-all; word-wrap:break-word;"><a href="<?='video/subtitle/'.$value1['url']?>" target="_blank"><?=$value1['url']?></a></td>
			<?php if($editenable){?>
            <td><a href="javascript:video_sub_edit('<?php echo $value1['id']?>');void(0);">编辑</a></td>
			<td><a href="javascript:video_sub_delete('<?php echo $value1['id']?>');void(0);">删除</a></td>
			<?php }?>
          </tr>
          <?php }}?>
        </table>
      </td>
    </tr>
	<script type="text/javascript" src="statics/js/base64.js"></script>
	<script type="text/javascript">
	function video_sub_delete(id){
		location.href ='?m=go3c&c=task&a=sub_delete&catid=64&id='+ id +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
	function video_sub_edit(id){
		location.href ='?m=go3c&c=task&a=subedit&id='+ id +'&pc_hash='+pc_hash
		+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(location.search);
	}
	function video_sub_add(asset_id){
		location.href ='?m=go3c&c=task&a=subadd&catid=64&asset_id='+ asset_id +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}
	function video_content_add(asset_id){
		location.href ='?m=content&c=content&a=add&catid=64&asset_id='+ asset_id +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}

	function video_poster_add(asset_id){
		location.href ='?m=go3c&c=video&a=video_poster_list&asset_id='+ asset_id +'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}

	function video_content_edit(id) {
		location.href ='?m=content&c=content&a=edit&catid=64&id='+id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}

	function video_content_delete(id){
		if(confirm("确定删除此播放链接?")){
			location.href ='?m=go3c&c=video&a=video_content_delete&id='+id+'&pc_hash='+pc_hash
			+'&goback='+BASE64.encode(location.search);
		}
	}

	function video_poster_edit(id) {
		location.href ='?m=content&c=content&a=edit&catid=65&id='+id+'&pc_hash='+pc_hash
		+'&goback='+BASE64.encode(location.search);
	}

	function video_poster_delete(id){
		if(confirm("确定删除此海报?")){
			location.href ='?m=go3c&c=video&a=video_poster_delete&id='+id+'&pc_hash='+pc_hash
			+'&goback='+BASE64.encode(location.search);
		}
	}
	</script>

	<?php  } ?>


    </tbody></table>
                </div>
        	</div>
        </div>
        
    </div>
</div>
<div class="bk10"></div>
	<div class="btn" style="float:right">
	<input value="<?php if($r['upgrade']) echo $r['url'];?>" type="hidden" name="upgrade">
	<input value="<?php echo $id;?>" type="hidden" name="id">
	<input value=0 type="hidden" name="verify" id="verify">
	<input value=0 type="hidden" name="doaftersave" id="doaftersave">
	<?php if($editenable){?>
	<?php if($catid=='54' || $catid=='63') {?>
		<input type="button" class="button" name="dopass" onclick="do_online_appy(<?php echo $id;?>,<?php echo $catid;?>)" value="<?php  if($catid=='63'){echo '申请发布';}else{echo '申请审核';}?>" />&nbsp;&nbsp;
		<input type="button" class="button" name="dosave" onclick="do_online_submit(<?php echo $id;?>,<?php echo $catid;?>)" value="<?php echo '保存';?>" />&nbsp;&nbsp;
		<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo '保存';?>" style="display:none" />&nbsp;&nbsp;
	<?php }else{?>
		<input type="submit" class="button" name="dosubmit" id="dosubmit" value="<?php echo '保存';?>" />&nbsp;&nbsp;
	<?php }?>
	<input type="button" class="button" name="goback" onclick="history.go(-1)" value="<?php echo '取消';?>" />
	<?php }else{?>
	<input type="button" class="button" name="goback" onclick="history.go(-1)" value="<?php echo '返回';?>" />
	<?php }?>
	</div> 

</form>

</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript" src="statics/js/yzyscript.js"></script>
<script type="text/javascript"> 
function do_online_appy(id,catid){
	var isurl = '';
	if(catid=='54'){
		var str='';
		$("input[name='video']").each(function(){ 
			   if(this.checked) str+=$(this).val()+",";        
		})
		str=str.replace(/,$/gi,"");
		isurl = '?m=go3c&c=video&a=ajax_online_pass&id='+id+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(document.referrer);
		document.getElementById('doaftersave').value = isurl;
	}else if(catid=='63'){
		var str='';
		$("input[name='channel_category']").each(function(){ 
			   if(this.checked) str+=$(this).val()+",";        
		})
		str=str.replace(/,$/gi,"");
		if(str.length > 0){
			isurl = '?m=go3c&c=channel&a=ajax_online_pass&id='+id+'&channel_category='+str+'&pc_hash='+pc_hash+'&goback='+BASE64.encode(document.referrer);
		}else{
			alert("频道类型至少要选一项");
			return;
		}
		document.getElementById('doaftersave').value = isurl;
	}

	document.getElementById('dosubmit').click();
}

function do_online_submit(id,catid){
	var isurl = '';
	if(catid==54)
	isurl = '?m=go3c&c=video&a=ajax_online_save&id='+id+'&pc_hash='+pc_hash;
	else if(catid==63){
		var str='';
		$("input[name='channel_category']").each(function(){ 
			   if(this.checked) str+=$(this).val()+",";        
		})
		str=str.replace(/,$/gi,"");
		if(str.length>0){
			isurl = '?m=go3c&c=channel&a=ajax_online_save&id='+id+'&channel_category='+str+'&pc_hash='+pc_hash;
		}else{
			alert("频道类型至少要选一项");
		}
	}
	if(isurl.length>0){
		loadJson(isurl,function(){
			//alert(isurl);
			document.getElementById('dosubmit').click();
		}) ;
	}
}

$(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, 	function(){$(obj).focus();
	boxid = $(obj).attr('id');
	if($('#'+boxid).attr('boxid')!=undefined) {
		check_content(boxid);
	}
	})}});
	<?php echo $formValidator;?>
	jQuery(document).bind('keydown', 'Alt+x', function (){close_window();});

	/*
	<?php if(!$editenable){?>
	$(":input").attr('disabled',true);
	$(":select").attr('disabled',true);
	$(":input[@type=text]").attr('disabled',false);
	<?php }?>
	*/
})
</script>
