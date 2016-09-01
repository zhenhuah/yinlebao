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
		case 10:
			echo "已提交审核";
			break;
		case 11:
			echo "已审核通过";
			break;
		case 12:
			echo "审核未通过";
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
	if($id < 1 || $id >10 ) return 'Unknown';
	if($id == 3){
		return ($ispackage > 0)?'电视栏目(总集)':'电视栏目(分集)';
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
	$columnname = array('','首页推荐','电视直播','电视栏目','电视剧','电影','乐酷','动漫','纪录片','音乐');
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
	case '0':
		return '全部推送';
	case '100':
		return '操作系统推送';
	case '200':
		return '客户端推送';
	case '300':
		return '平台推送';
	case '400':
		return '指定推送';
	case '500':
		return '区域推送';
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