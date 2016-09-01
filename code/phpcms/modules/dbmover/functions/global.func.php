<?php
/**
 * 页面跳转
 *
 * @param str $model
 * @param str $control
 * @param str $action
 * @param int $next_page
 */
function page_jump($model = 'dbmover', $control = 'export', $action = 'export_epg', $next_page){
	echo "<script language='javascript'>";
	echo "location.href='?m=".$model."&c=".$control."&a=".$action."&page=".$next_page."&pc_hash=".$_SESSION['pc_hash']."';";
	echo "</script>";
}

/**
 * 功能按钮 - 基础数据同步 - 正向同步
 */
function dbmover_index_link(){
	echo <<<EOT
	<div id="admin_panel">
	<ul>
	<li style="text-align:center; padding-top:10px;">
	  <a href="javascript:if(confirm('此操作有风险，请确认基础数据设置正确!')) location='?m=dbmover&c=clear&a=channel_category&pc_hash={$_SESSION[pc_hash]}'">基础数据同步</a>
	</li>
	<!--
	<li><a target="right" href="?m=dbmover&c=index&a=channel_category&pc_hash={$_SESSION['pc_hash']}">同步 直播频道分类</a></li>
	<li><a target="right" href="?m=dbmover&c=index&a=term_type&pc_hash={$_SESSION['pc_hash']}">同步 终端类型</a></li>
	<li><a target="right" href="?m=dbmover&c=index&a=column&pc_hash={$_SESSION['pc_hash']}">同步 栏目分类</a></li>
	<li><a target="right" href="?m=dbmover&c=index&a=column_content_area&pc_hash={$_SESSION['pc_hash']}">同步 栏目下按地域分类</a></li>
	<li><a target="right" href="?m=dbmover&c=index&a=recomm_video_type&pc_hash={$_SESSION['pc_hash']}">同步 推荐类型</a></li>
	<li><a target="right" href="?m=dbmover&c=index&a=column_content_cate&pc_hash={$_SESSION['pc_hash']}">同步 栏目下按内容类型分类</a></li>
	<li><a target="right" href="?m=dbmover&c=index&a=column_mapping&pc_hash={$_SESSION['pc_hash']}">同步 栏目终端类型映射</a></li>
	-->
	</ul>
	(直播频道分类、终端类型、栏目分类、栏目下按地域分类、栏目下按内容类型分类、栏目终端类型映射、推荐类型)
	</div>
EOT;
}

/**
 * 功能按钮 - 基础数据同步 - 反向清理
 */
function dbmover_clear_link(){
	echo <<<EOT
	<div id="admin_panel">
	<ul>
	<li><a target="right" href="?m=dbmover&c=clear&a=tags&pc_hash={$_SESSION['pc_hash']}">反向同步清理 Tags</a></li>
	<li><a target="right" href="?m=dbmover&c=clear&a=channel_category&pc_hash={$_SESSION['pc_hash']}">反向同步清理 直播频道分类</a></li>
	<li><a target="right" href="?m=dbmover&c=clear&a=term_type&pc_hash={$_SESSION['pc_hash']}">反向同步清理 终端类型</a></li>
	<li><a target="right" href="?m=dbmover&c=clear&a=column&pc_hash={$_SESSION['pc_hash']}">反向同步清理 栏目分类</a></li>
	<li><a target="right" href="?m=dbmover&c=clear&a=column_content_area&pc_hash={$_SESSION['pc_hash']}">反向同步清理 栏目下按地域分类</a></li>
	<li><a target="right" href="?m=dbmover&c=clear&a=recomm_video_type&pc_hash={$_SESSION['pc_hash']}">反向同步清理 推荐类型</a></li>
	<li><a target="right" href="?m=dbmover&c=clear&a=column_content_cate&pc_hash={$_SESSION['pc_hash']}">反向同步清理 栏目下按内容类型分类</a></li>
	<li><a target="right" href="?m=dbmover&c=clear&a=column_mapping&pc_hash={$_SESSION['pc_hash']}">反向同步清理 栏目终端类型映射</a></li>
	</ul>
	</div>
EOT;
}

/**
 * 功能按钮 - 导入
 */
function dbmover_import_link(){
	echo <<<EOT
		<div id="admin_panel">
		<ul>
		<li><a target="right" href="?m=dbmover&c=import&a=channel&pc_hash={$_SESSION['pc_hash']}">导入频道</a></li>
		<li><a target="right" href="?m=dbmover&c=import&a=program&pc_hash={$_SESSION['pc_hash']}">导入EPG</a></li>
		<li><a target="right" href="?m=dbmover&c=import&a=asset&pc_hash={$_SESSION['pc_hash']}">导入视频</a></li>
		</ul>
		</div>
EOT;
}

/**
 * 功能按钮 - 导出
 */
function dbmover_export_link(){
	echo <<<EOT
	<div id="admin_panel">
	<ul>
	<li><a target="right" href="?m=dbmover&c=export&a=channel&pc_hash={$_SESSION['pc_hash']}">发布频道</a></li>
	<li><a target="right" href="?m=dbmover&c=export&a=epg&pc_hash={$_SESSION['pc_hash']}">发布EPG</a></li>
	<li><a target="right" href="?m=dbmover&c=export&a=video&pc_hash={$_SESSION['pc_hash']}">发布视频</a></li>
	</ul>
	</div>
EOT;
}

/**
 *样式表
 */
function dbmover_css(){
	echo '
		<style type="text/css">
		*{
			font-size:12px;
		}
		#syn_msg{
			padding-top:10px;
			padding-left:20px;
			line-height:18px;
		}
		#syn_msg em{
			color:#fff;
			font-size:14px;
			background:green;
			padding:2px 4px;
		}
		ul{
			padding:0;
			margin:0 auto;
		}
		ul li{
			width:300px;
			height:30px;
			background:#DDDDDD;
			border-right: solid 1px #666666;
			border-bottom: solid 1px #666666;
			margin:2px 5px 10px 0;
		}
		ul li a{
		    text-decoration:none;
		  	padding:5px;
		  	color:#000;
		  	font-size:16px;
		  	font-family:"微软雅黑";
		}
		</style>';
}

/**
 * 二位数组变一维数组
 *
 * @param arr $arr
 * @return arr
 */
function rebuild_array($arr){
  static $tmp=array();
  foreach($arr as $key=>$val){
    if(is_array($val)){
      rebuild_array($val);
    }else{
      $tmp[] = $val;
    }
  }
  return $tmp;
}

/**
*   uuid转直播链接
*/
function get_live_url($uuidtype,$uuid){
	if($uuid){
		if(strstr($uuidtype,"IOS")){
			return "http://ipad.vsdn.new.bigtv.com.cn/".$uuid."/1.m3u8";		
		}else if(strstr($uuidtype,"STB")){
			return "http://biz.vsdn.new.bigtv.com.cn/playlive.js?uuid=".$uuid;
		}else if(strstr($uuidtype,"PC")){
			return "http://biz.vsdn.new.bigtv.com.cn/playlive.do?uuid=".$uuid;
		}else{
			return $uuid;
		}
	}
}

/**
*   uuid转时移链接
*/
function get_timeshift_url($uuidtype,$uuid){
	if(strstr($uuidtype,"IOS")){
		return "http://ipad.vsdn.new.bigtv.com.cn/".$uuid."/1.m3u8";		
	}else if(strstr($uuidtype,"STB")){
		return "http://biz.vsdn.new.bigtv.com.cn/ts.js?uuid=".$uuid;
	}else if(strstr($uuidtype,"PC")){
		return "http://biz.vsdn.new.bigtv.com.cn/ts.do?uuid=".$uuid;
	}else{
		return $uuid;
	}
}

/**
*   相对路径转点播链接
*/
function get_vod_url($type,$path){
	if(strstr($type,"IOS")){
		return "http://biz.vsdn.new.bigtv.com.cn/playvod.do?url=http://111.208.56.197/".$path;	
	}else if(strstr($type,"STB")){
		return "http://biz.vsdn.new.bigtv.com.cn/playvod.js?url=http://111.208.56.197/".$path;
	}else if(strstr($type,"PC")){
		return "http://biz.vsdn.new.bigtv.com.cn/playvod.do?url=http://111.208.56.197/".$path;
	}else{
		return $path;
	}
}

/**
*   相对路径转图片链接
*/
function get_img_url($path){
	return "http://111.208.56.197/poster".$path;
}

?>
