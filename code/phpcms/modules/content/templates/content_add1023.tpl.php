<?php
defined('IN_ADMIN') or exit('No permission resources.');$addbg=1;
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
<form name="myform" id="myform" action="?m=content&c=content&a=add" method="post" enctype="multipart/form-data">
<div class="addContent" style="background:#FFF; width:100%;">
<!--div class="pad-10">
<div class="content-menu ib-a blue line-x">
<a class="add fb" href="?m=content&c=content&a=add&menuid=&catid=<?php echo $catid;?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>"><em><?php echo L('add_content');?></em></a>
<a class="add fb" href="javascript:;" onclick=javascript:openwinx('?m=content&c=content&a=add&menuid=&catid=<?php echo $catid;?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>','')><em><?php echo L('add_content');?></em></a>>
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
<?php if($_SESSION['roleid']==1 || $priv_status) {?>
<h6><?php echo L('c_status');?></h6>
<span class="ib" style="width:90px"><label><input type="radio" name="status" value="99" checked/> <?php echo L('c_publish');?> </label></span>
<?php if($workflowid) { ?><label><input type="radio" name="status" value="1" > <?php echo L('c_check');?> </label><?php }?>
<?php }?>
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
	PAD<input type="checkbox" name="PAD" value="1">&nbsp;&nbsp;&nbsp;&nbsp;
	STB<input type="checkbox" name="STB" value="1">&nbsp;&nbsp;&nbsp;&nbsp;
	PHONE<input type="checkbox" name="PHONE" value="1">&nbsp;&nbsp;&nbsp;&nbsp;
	PC<input type="checkbox" name="PC" value="1">
	</td>
 </tr>
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
<?php if($catid == '63') {?>
<tr>
    <th width="80">节目类型:</th>
	<td>
	<?php {foreach($channel_category as $key=>$channel){?>
	<?=$channel['title']?><input type="checkbox" name="channel_category[]" value="<?php echo $channel['id']?>">&nbsp;&nbsp;&nbsp;&nbsp;
	<?php }} ?>
	</td>
 </tr>
<tr>
    <th width="80">支持终端:</th>
	<td>
	PAD<input type="checkbox" name="PAD" value="1">&nbsp;&nbsp;&nbsp;&nbsp;
	STB<input type="checkbox" name="STB" value="1">&nbsp;&nbsp;&nbsp;&nbsp;
	PHONE<input type="checkbox" name="PHONE" value="1">&nbsp;&nbsp;&nbsp;&nbsp;
	PC<input type="checkbox" name="PC" value="1">
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
    </tbody></table>
                </div>
        	</div>
        </div>        
    </div>
	<div class="bk10"></div>
	<div class="btn">
	<input type="submit" class="button" name="dosubmit" value="<?php echo '保存';?>" />&nbsp;&nbsp;
	<input type="button" class="button" name="goback" onclick="history.go(-1)" value="<?php echo '取消';?>" />
	</div> 
	
</form>
</body>
</html>
<script type="text/javascript" src="statics/js/yzyscript.js"></script>
<script type="text/javascript"> 
<!--
$(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({id:'check_content_id',content:msg,lock:true,width:'200',height:'50'}, 	function(){$(obj).focus();
	boxid = $(obj).attr('id');
	if($('#'+boxid).attr('boxid')!=undefined) {
		check_content(boxid);
	}
	})}});
	<?php echo $formValidator;?>
	$('#linkurl').attr('disabled',true);
	$('#islink').attr('checked',false);
	$('.edit_content').hide();
	jQuery(document).bind('keydown', 'Alt+x', function (){close_window();});
})
function refersh_window() {
	setcookie('refersh_time', 1);
}
//-->
</script>
