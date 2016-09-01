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
	.table-list div{ float:left;}
</style>

<!--div class="explain-col">
<form name="myfrom" action="" method="GET">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="authserach" name="c">
	<input type="hidden" value="tags" name="a">
	<input type="hidden" value="query" name="mode">
	<div class="lst_lxpg clearfix" style="position:relative;">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	视频分类:
	<select name="tagsType" id="tagsType">
		<option value="">请选择视频分类</option>
		<option value="3" <?php if($tagsType == '3')echo 'selected';?>>电视栏目</option>
		<option value="4" <?php if($tagsType == '4')echo 'selected';?>>电视剧</option>
		<option value="5" <?php if($tagsType == '5')echo 'selected';?>>电影</option>
		<option value="6" <?php if($tagsType == '6')echo 'selected';?>>乐酷</option>
	</select>
  	<input type="submit" value="搜索" class="button" name="search"> &nbsp;<span id="tips"></span>
	</div>
</form>
</div-->
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>jquery.autocomplete.css">
<!-- 底层过来拿数据的地方 start -->
<input type="hidden" value="" name="setAllTags" id="setAllTags" />
<!-- 底层过来拿数据的地方 end -->
<div class="table-list">   
	<?php if(!empty($tags_list)){foreach($tags_list as $tags){?>
		<div><span><?php echo $tags['title'];?>
		<input type="checkbox" name="new_tags_id[]" value="<?php echo $tags['title'];?>" onclick="selectTags();">
		</span>&nbsp;&nbsp;&nbsp;</div>
	<?php }}else{echo "<span>暂无数据</span>";}?>

	</div>
    <div id="pages"><?php echo $pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
<!--
function selectTags()
{
	var setLastAllTags = getCheckTags('new_tags_id');
	//alert(setLastAllTags);

}

//标签Tags处理
function getCheckTags(set_name)   
{
	//清空
	jQuery('#setAllTags').val('');
	
	var obj = jQuery('input[name^='+set_name+']');
		jQuery(obj).each(function(i){
			checkedStatus = jQuery(this).attr("checked");
			if (checkedStatus)
			{
				var setAllTags = jQuery.trim(jQuery('#setAllTags').val());
				
				if (setAllTags == '')
				{
					var setCheckTags = this.value; 
					jQuery('#setAllTags').val(setCheckTags);
				}else{
					var setCheckTags = setAllTags+','+this.value; 
					jQuery('#setAllTags').val(setCheckTags);
				}
			}
		});
	var setLastAllTags = jQuery.trim(jQuery('#setAllTags').val());
	return setLastAllTags;
}


//自动查询效果
jQuery(function() {
	jQuery("#title").autocomplete({
		url: "?m=go3c&c=authserach&a=serachTags&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
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
