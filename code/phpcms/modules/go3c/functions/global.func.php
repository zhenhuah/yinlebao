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
 * Go3C状态
 *
 * @param unknown_type $id
 */
function online_status($online_status,$published = 0){
	if($published) return '在线';
	switch ($online_status)
	{
		case 1:
			echo "导入";
			break;
		case 2:
			echo "正在编辑";
			break;
		case 3:
			echo "编辑未通过";
			break;
		case 4:
			echo "已提交审核";
			break;
		case 5:
			echo "已申请下线";
			break;
		case 6:
			echo "已审核通过";
			break;
		case 10:
			echo "已提交审核";
			break;
		case 11:
			echo "已审核通过";
			break;
		case 12:
			echo "审核未通过";
			break;
		case 14:
			echo "在线";
			break;
		case 20:
			echo "已提交删除";
			break;
		case 21:
			echo "删除通过";
			break;

		case 99:
			echo "异常";
			break;	
		case 0:
			echo "导入";
			break;	
	}
}

function offline_status($offline_status, $published = 1){
	if($offline_status == 0 && $published == 1){
		echo '在线';
	}elseif($offline_status == 1 && $published == 1){
		echo '已申请下线';
	}elseif($offline_status == 2 && $published == 1){
		echo '下线申请已通过';
	}elseif($offline_status == 3 && $published == 0){
		echo '已下线';
	}else{
		echo 'Error: ' .$offline_status.' : '.$published;
	}
}

function columnid2name($id,$ispackage){
	if($id < 1 || $id >13 ) return 'Unknown';
	if($id == 3){
		return ($ispackage > 0)?'综艺(总集)':'综艺(分集)';
	}
	if($id == 4){
		return ($ispackage > 0)?'电视剧(总集)':'电视剧(分集)';	
	}
	if($id == 7){
		return ($ispackage > 0)?'动漫(总集)':'动漫(分集)';	
	}
	if($id == 8){
		return ($ispackage > 0)?'纪录片(总集)':'纪录片(分集)';	
	}
	if($id == 9){
		return ($ispackage > 0)?'音乐(专辑)':'音乐(单曲)';
	}
	if($id == 10){
		return '优酷高清';
	}
	if($id == 11){
		return ($ispackage > 0)?'搜狐(总集)':'搜狐(分集)';
	}
	if($id == 12){
		return ($ispackage > 0)?'公开课(总集)':'公开课(分集)';
	}
	if($id == 13){
		return '体育';
	}
	$columnname = array('','首页推荐','电视直播','综艺','电视剧','电影','乐酷','动漫','纪录片','音乐','优酷高清','搜狐','公开课','体育');
	return $columnname[$id];
}

function cattype2name($type){
	switch($type){
	case 1:
		return '地区';
	case 2:
		return '栏目分类';
	case 3:
		return '年代';
	default:
		return '未知';
	}
}

function videopic($type){
	switch($type){
	case 1:
		return '无图';
	default:
		return '有图';
	}
}
function subtype($type){
	switch($type){
	case 1:
		return 'andoird服务器';
	default:
		return 'ios服务器';
	}
}
function subSTATU($type){
	switch($type){
	case 1:
		return '未启动';
	default:
		return '已启动';
	}
}
function mestype($type){
	switch($type){
	case 1:
		return '用户定制';
	case 2:
		return '非用户定制';
	default:
		return '未知';
	}
}
function mesch($type){
	switch($type){
	case 0:
		return '有';
	case 1:
		return '没有';
	default:
		return '未知';
	}
}
function mantype($type){
	switch($type){
	case 0:
		return '未推送';
	case 1:
		return '已推送';
	case 2:
		return '推送中';
	default:
		return '未知';
	}
}
function pubtype($type){
	switch($type){
	case 0:
		return '全部推送';
	case 100:
		return '操作系统推送';
	case 200:
		return '客户端推送';
	case 300:
		return '平台推送';
	case 400:
		return '指定推送';
	case 500:
		return '区域推送';
	default:
		return '未知';
	}
}
function devicetype($type){
	switch($type){
	case 101:
		return 'apple tv';
	case 102:
		return 'atv(Android stb)';
	case 103:
		return 'ltv(Linux stb)';
	case 201:
		return 'ipad';
	case 202:
		return 'apad';
	case 203:
		return 'winpad';
	case 301:
		return 'iphone';
	case 302:
		return 'aphone';
	case 303:
		return 'win8 phone';
	case 401:
		return 'pc web';
	case 402:
		return 'pc client';
	case 403:
		return 'win8 pc';
	default:
		return '未知';
	}
}
function go3cpc($type){
	switch($type){
	case 1:
		return '畅通';
	default:
		return '不通';
	}
}
function isnewuser($type){
	switch($type){
		case 0:
			return '否';
		case 1:
			return '是';
		default:
			return '未知';
	}
}
function adtype2name($type){
	switch($type){
	case 0:
		return '引导图';
	case 1:
		return '文本';
	case 2:
		return '图片';
	case 3:
		return '视频';
	default:
		return '未知';
	}
}

function addisplay2name($type){
	switch($type){
	case 1:
		return '水平跑马灯';
	case 2:
		return '垂直跑马灯';
	case 3:
		return '图片翻转';
	case 4:
		return '嵌入小视频';
	case 5:
		return '全屏视频';
	case 6:
		return '顶部跑马灯';
	case 7:
		return '底部跑马灯';
	case 8:
		return '右下角弹窗';
	case 9:
		return '背景图片';
	default:
		return '未知';
	}
}
function channel_play($type){
	switch($type){
	case 1:
		return '有效';
	default:
		return '无效';
	}
}
function hdp_type($type){
	switch($type){
	case movie:
		return '电影';
	case tv:
		return '电视剧';
	case zy:
		return '综艺';
	case comic:
		return '动漫';
	case open:
		return '公开课';
	case sports:
		return '体育';
	case video:
		return '影视';
	case game:
		return '游戏';
	case app:
		return '软件';
	case bdmusic:
		return '音乐';
	case fun:
		return '搞笑视频';
	default:
		return '其他';
	}
}
function istask($type){
	switch($type){
		case 0:
			return '下线状态';
		case 1:
			return '编辑状态';
		case 2:
			return '审核状态';
		case 3:
			return '审核未通过';
		case 4:
			return '已审核通过';
		case 100:
			return '在线状态';
		default:
			return '未知';
	}
}
function image_type($type){
	switch($type){
		case 1:
			return 'ICON';
		case 2:
			return '横图';
		case 3:
			return '竖图';
		default:
			return '无效';
	}
}
function shop_play($type){
	switch($type){
		case 1:
			return '已上线';
		default:
			return '未上线';
	}
}
function link_status($type){
	switch($type){
		case 1:
			return '未上线';
		case 2:
			return '未审核';
		case 3:
			return '已审核';
		case 4:
			return '已上线';
		default:
			return '未知';
	}
}
function imgtype($type){
	switch($type){
		case 121:
			return '正方形(360*360)';
		case 102:
			return '竖图(240*360)';
		case 103:
			return '横图(360*240)';
		case 111:
			return '背景大图(1280*720)';
		case 122:
			return 'icon(72*72)';
		default:
			return '未知';
	}
}
function cmslog_type($type){
	switch($type){
	case up_database:
		return '同步数据操作';
	case epg_online:
		return 'epg申请审核';
	case channel_online:
		return '频道批量申请审核';
	case epg_allline:
		return 'epg批量审核';
	case channel_error:
		return '频道设为异常数据';
	case channel_offline:
		return '频道申请下线';
	case channel_listorder:
		return '频道调整顺序';
	case channel_delete:
		return '删除频道';
	case msgservice_add:
		return '添加消息推送服务器';
	case msgservice_edit:
		return '修改消息推送服务器';
	case msgservice_delete:
		return '删除消息推送服务器';
	case msg_add:
		return '添加推送消息';
	case msg_edit:
		return '修改推送消息';
	case msg_delete:
		return '删除推送消息';
	case msg_taken:
		return '推送消息';
	case task_add:
		return '添加推送任务';
	case task_edit:
		return '编辑推送任务';
	case task_addvideo:
		return '添加推荐视频';
	case task_delete:
		return '删除推荐任务';
	case addAdvert:
		return '添加广告';
	case editAdvert:
		return '修改广告';
	case subtitleadd:
		return '添加字幕';
	case subtitleedit:
		return '修改字幕';
	case sub_delete:
		return '删除字幕';
	case task_addout:
		return '添加推荐外链';
	case online_pass:
		return '同意审核上线';
	case online_refuse:
		return '拒绝审核上线';
	case offline_pass:
		return '通过下线申请';
	case offline_refuse:
		return '拒绝下线申请';
	case delete_pass:
		return '同意删除申请';
	case delete_refuse:
		return '拒绝删除申请';
	case delete_poster:
		return '删除海报';
	case delete_content:
		return '删除播放链接';
	case tags_add:
		return '新增栏目';
	case tags_online:
		return '栏目申请审核';
	case offine_vid:
		return '视频申请下线';
	case delete_vid:
		return '视频申请删除';
	case online_vid:
		return '视频申请审核';
	case data_error:
		return '视频设为异常';
	case btnpipei_poster:
		return '自动匹配海报';
	case video_add:
		return '添加视频';
	case channel_add:
		return '添加频道';
	case video_edit:
		return '修改视频';
	case channel_edit:
		return '修改频道';
	case content_add:
		return '添加链接';
	case content_edit:
		return '修改链接';
	case add_poster:
		return '添加海报';
	case edit_online:
		return '在线修改视频';
	case up_memcache:
		return '清除memcache';
	case edit_password:
		return '修改密码';
	case add_ip:
		return '锁定ip';
	case edit_system:
		return '修改管理员信息';
	case delete_info:
		return '删除短信息';
	case edit_user:
		return '修改用户密码';
	case edit_www:
		return '修改站点';
	case add_www:
		return '添加站点';
	case delete_www:
		return '删除站点';
	case add_menu:
		return '添加菜单';
	case edit_menu:
		return '修改菜单';
	case del_menu:
		return '删除菜单';
	case insert_module:
		return '安装模块';
	case uninstall:
		return '卸载模块';
	case add_mod:
		return '添加模型';
	case edit_mod:
		return '修改模型';
	case del_mod:
		return '删除模型';
	case disabled_mod:
		return '禁用模型';
	case export_mod:
		return '导出模型';
	case import_mod:
		return '导入模型字段';
	case add_field:
		return '添加字段';
	case edit_field:
		return '修改字段';
	case disabled_field:
		return '禁用字段';
	case listorder_field:
		return '排序字段';
	case del_field:
		return '删除字段';
	case cro_import:
		return '自动导入设置';
	case cro_publish:
		return '自动发布设置';
	case cro_offline:
		return '自动下线设置';
	case add_area:
		return '添加区域';
	case del_area:
		return '删除区域';
	case edit_area:
		return '修改区域';
	case add_tag:
		return '添加tag';
	case del_tag:
		return '删除tag';
	case updata_actor:
		return '同步演员';
	case add_actor:
		return '添加演员';
	case del_actor:
		return '删除演员';
	case add_column:
		return '添加栏目';
	case del_column:
		return '删除栏目';
	case edit_column:
		return '修改栏目';
	case category_sync:
		return '同步栏目数据';
	case source_sync:
		return '同步视频来源数据';
	case add_rem:
		return '添加推荐位';
	case edit_rem:
		return '修改推荐位';
	case del_rem:
		return '删除推荐位';
	case del_adv:
		return '删除广告位';
	case db_sysc:
		return '基础数据同步';
	case add_role:
		return '添加角色';
	case edit_role:
		return '修改角色';
	case order_role:
		return '角色排序';
	case del_role:
		return '删除角色';
	case edit_ropriv:
		return '角色付权限';
	case change_status:
		return '更新角色状态';
	case add_user:
		return '添加管理员';
	case client_pass:
		return '版本申请上线';
	case client_del:
		return '版本删除';
	case client_del:
		return '版本删除';
	case client_del:
		return '版本删除';
	case client_del:
		return '版本删除';
	case client_del:
		return '版本删除';
	default:
		return '未知';
	}
}
function term_type($type){
	switch($type){
		case 1:
			return 'STB';
		case 2:
			return 'PAD';
		case 3:
			return 'PHONE';
		case 4:
			return 'PC';
		default:
			return '未知';
	}
}
function song_type($type){
	switch($type){
		case song:
			return '歌曲';
		case singer:
			return '歌星';
		case album:
			return '专辑';
		default:
			return '未知';
	}
}
function song_status($type){
	switch($type){
		case 1:
			return '编辑';
		case 2:
			return '审核';
		case 4:
			return '上线';
		default:
			return '未知';
	}
}
function song_langtype($type){
	switch($type){
		case 1:
			return '国语';
		case 2:
			return '粤语';
		case 3:
			return '台语';
		case 4:
			return '英语';
		case 5:
			return '日语';
		case 6:
			return '韩语';
		default:
			return '未知';
	}
}
function up_status($type){
	switch($type){
		case 1:
			return '未审核';
		case 2:
			return '已审核上线';
		case 3:
			return '审核未通过';
		default:
			return '未知';
	}
}
function game_status($type){
	switch($type){
	case 1:
		return '未上线';
	case 2:
		return '已提交审核';
	case 4:
		return '已上线';
	default:
		return '未知';
	}
}
function cattypeplay($type){
	switch($type){
	case vod:
		return '点播';
	case live:
		return '直播';
	case epg:
		return '时移';
	case timeshift:
		return '回看';
	default:
		return '未知';
	}
}
function systype($type){
	switch($type){
	case on:
		return '上线';
	case off:
		return '下线';
	case import:
		return '导入';
	case reject:
		return '拒绝';
	case add:
		return '添加';
	case edit:
		return '编辑';
	case del:
		return '删除';
	case login:
		return '登陆';
	case audit:
		return '审核';
	default:
		return '未知';
	}
}

/*
* 解析xml到数组
*/
function xml_to_array($xml){
	$reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches))
	{
		$count = count($matches[0]);
		$arr = array();
		for($i = 0; $i < $count; $i++)
		{
			$key = $matches[1][$i];
			$val = xml_to_array( $matches[2][$i] );  // 递归
			if(array_key_exists($key, $arr))
			{
				if(is_array($arr[$key]))
				{
					if(!array_key_exists(0,$arr[$key]))
					{
						$arr[$key] = array($arr[$key]);
					}
				}else{
					$arr[$key] = array($arr[$key]);
				}
				$arr[$key][] = $val;
			}else{
				$arr[$key] = $val;
			}
		}
		return $arr;
	}else{
		return $xml;
	}
}


function array_sort($arr,$keys,$type='desc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}

?>