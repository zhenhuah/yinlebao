<?php
defined('IN_ADMIN') or exit('No permission resources.');
$pc_hash = $_SESSION['pc_hash'];
echo '<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li class="sidebar-toggler-wrapper">
					<div class="sidebar-toggler">
					</div>
				</li>';
$icon_arr=array('icon-bar-chart','icon-bulb','icon-graph','icon-home','icon-basket','icon-tag','icon-handbag','icon-pencil','icon-check','icon-user-following','icon-question','icon-calendar','icon-flag','icon-speech','icon-link','icon-eye','icon-bell','icon-user','icon-pencil','icon-handbag');
foreach($datas as $_value) {
	//echo '<h3 class="f14"><span class="switchs cu on" title="'.L('expand_or_contract').'"></span>'.L($_value['name']).'</h3>';
	$start = rand(0, count($icon_arr)-1);
	echo '<li class="start ">
					<a href="javascript:;">
					<i class="'.$icon_arr[$start].'"></i>
					<span class="title" title="'.L('expand_or_contract').'">'.L($_value['name']).'</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">';
	$sub_array = admin::admin_menu($_value['id']);
	foreach($sub_array as $_key=>$_m) {
		//附加参数
		$data = $_m['data'] ? '&'.$_m['data'] : '';
		if($menuid == 5) { //左侧菜单不显示选中状态
			$classname = 'class="sub_menu"';
		} else {
			$classname = 'class="sub_menu"';
		}
		//echo '<li id="_MP'.$_m['id'].'" '.$classname.'><a href="javascript:_MP('.$_m['id'].',\'?m='.$_m['m'].'&c='.$_m['c'].'&a='.$_m['a'].$data.'\');" hidefocus="true" style="outline:none;">'.L($_m['name']).'</a></li>';
		$index = rand(0, count($icon_arr)-1);
		echo '<li id="_MP'.$_m['id'].'" '.$classname.'><a href="javascript:_MP('.$_m['id'].',\'?m='.$_m['m'].'&c='.$_m['c'].'&a='.$_m['a'].$data.'\');" hidefocus="true" style="outline:none;"><i class="'.$icon_arr[$index].'"></i>'.L($_m['name']).'</a></li>';
		//echo '<li id="_MP'.$_m['id'].'" '.$classname.'><a href="http://www.go3c.tv:8060/go3co2o/backend/templates/t_model.html" hidefocus="true" style="outline:none;" target="right"><i class="'.$icon_arr[$index].'"></i>'.L($_m['name']).'</a></li>';
	}
	echo '</ul></li>';
}
?>
<script type="text/javascript">
$(".switchs").each(function(i){
	var ul = $(this).parent().next();
	$(this).click(
	function(){
		if(ul.is(':visible')){
			ul.hide();
			$(this).removeClass('on');
				}else{
			ul.show();
			$(this).addClass('on');
		}
	})
});
</script>