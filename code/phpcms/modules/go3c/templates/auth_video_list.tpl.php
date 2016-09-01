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
	<input type="hidden" value="authserach" name="c">
	<input type="hidden" value="video" name="a">
	<input type="hidden" value="query" name="mode">
	<div class="lst_lxpg clearfix" style="position:relative;">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
  名称：<input type="text" class="input-text" value="<?php echo $videoTitle; ?>" name="title" id="title" autocomplete="off">&nbsp;
  	<input type="submit" value="搜索" class="button" name="search"> &nbsp;<span id="tips"></span>
	</div>
</form>
<!-- study_jquery.autocomplete.js jquery.autocomplete.js-->

<script type="text/javascript" src="<?php echo JS_PATH?>jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>jquery.autocomplete.css">
<!-- 底层过来拿数据的地方 start -->
<input type="hidden" value="" name="set_asset_id" id="set_asset_id" />
<input type="hidden" value="" name="set_title" id="set_title" />
<!-- 底层过来拿数据的地方 end -->

</div>

<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="35" align="center">ID</th>
			<th width="50" align="center">视频名称</th>	
			<th width="80" align="center">设置</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($video_list)){foreach($video_list as $video){?>
		<tr>
		<td align="center"><?php echo $video['asset_id']?></td>		
		<td align="center"><?php echo $video['title'];?></td>
		<td align="center">
		<span style="color:green" >设置</span> <input type="radio" name="new_asset_id[]" onclick="selectVideo('<?php echo $video['asset_id'];?>','<?php echo $video['title'];?>');">
		</td>
		</tr>
	<?php }}else{echo "<tr><td align='center' colspan='4'>暂无数据</td></tr>";}?>
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
function selectVideo(asset_id,title)
{
	if ((asset_id != '') && (title != ''))
	{	
		//设置视频ID
		jQuery("#set_asset_id").val(asset_id);
	
		//设置视频名称
		jQuery("#set_title").val(title);
		
		//alert('设置成功!');
		//window.top.art.dialog({id:'testIframe'}).submit();
		//jQuery('.aui_state_highlight').click();
		//window.top.art.dialog({id:'testIframe'}).close();
	}
}

//自动查询效果
jQuery(function() {
	jQuery("#title").autocomplete({
		url: "?m=go3c&c=authserach&a=serach&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
		sortFunction: function(a, b, filter) {
			var f = filter.toLowerCase();
			var fl = f.length;
			var a1 = a.value.toLowerCase().substring(0, fl) == f ? '0' : '1';
			var a1 = a1 + String(a.data[0]).toLowerCase();
			var b1 = b.value.toLowerCase().substring(0, fl) == f ? '0' : '1';
			var b1 = b1 + String(b.data[0]).toLowerCase();
			if (a1 > b1) {
				return 1;
			}
			if (a1 < b1) {
				return -1;
			}
			return 0;
		},
		showResult: function(value, data) {
			//return '<span style="color:red">' + value + '</span>';
			return value;
		},
		
		maxItemsToShow: 15	//显示条数
	});

});

//请求处理
function activate()
{
	var p = jQuery('#title').val();
	
    if (p != '')
    {
		var setEncodeQuery = encodeURIComponent(p);
		var get_url = "?m=go3c&c=authserach&a=serach&pc_hash=<?php echo $_SESSION['pc_hash'];?>";
		jQuery.get(	//请求开始
			get_url+'&q='+setEncodeQuery, 
            function(data){	//start
				if(data.length)
				{
					$autocomplete.empty();
                    var arrList = data.substring(0, data.length - 1).split(',');
                    jQuery.each(arrList, function(index, term){
						jQuery("<li></li>").text(term).appendTo($autocomplete).mouseover(function(){
                          //jQuery(this).css("background", "#ddd");
							jQuery(this).addClass("acSelect");
                         }).mouseout(function(){
							 //jQuery(this).css("background", "white");
							jQuery(this).removeClass("acSelect");
                           }).click(function(){
							   jQuery("#title").val(term);
                               $autocomplete.hide();
                            });
                          });
                          
						  $autocomplete.show();

                  }else{
                      $autocomplete.empty();
                      $autocomplete.hide();
                  }
             }	//end
        );	//请求结束
	}
}
//-->
</script>