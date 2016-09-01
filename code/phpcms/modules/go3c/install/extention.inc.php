<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');
//Top Level
$go3c_editor  = $menu_db->insert(array('name'=>'go3c_editor', 'parentid'=>0, 'm'=>'go3c', 'c'=>'index', 'a'=>'init', 'data'=>'', 'listorder'=>10, 'display'=>'1'), true);
$go3c_admin   = $menu_db->insert(array('name'=>'go3c_admin', 'parentid'=>0, 'm'=>'go3c', 'c'=>'index', 'a'=>'init', 'data'=>'', 'listorder'=>11, 'display'=>'1'), true);
//Second level
$go3c_video	  = $menu_db->insert(array('name'=>'go3c_video', 'parentid'=>$go3c_editor, 'm'=>'go3c', 'c'=>'video', 'a'=>'init', 'data'=>'', 'listorder'=>11, 'display'=>'1'), true);
$go3c_channel = $menu_db->insert(array('name'=>'go3c_channel', 'parentid'=>$go3c_editor, 'm'=>'go3c', 'c'=>'channel', 'a'=>'init', 'data'=>'', 'listorder'=>12, 'display'=>'1'), true);
$go3c_position = $menu_db->insert(array('name'=>'go3c_position','parentid'=>$go3c_editor, 'm'=>'go3c', 'c'=>'position', 'a'=>'init', 'data'=>'', 'listorder'=>13, 'display'=>'1'), true);
$go3c_verify  = $menu_db->insert(array('name'=>'go3c_verify','parentid'=>$go3c_editor, 'm'=>'go3c', 'c'=>'verify', 'a'=>'init', 'data'=>'', 'listorder'=>14, 'display'=>'1'), true);
$go3c_publish = $menu_db->insert(array('name'=>'go3c_publish','parentid'=>$go3c_editor, 'm'=>'go3c', 'c'=>'publish', 'a'=>'init', 'data'=>'', 'listorder'=>15, 'display'=>'1'), true);
$go3c_import  = $menu_db->insert(array('name'=>'go3c_import','parentid'=>$go3c_admin, 'm'=>'go3c', 'c'=>'import', 'a'=>'init', 'data'=>'', 'listorder'=>16, 'display'=>'1'), true);
$go3c_config  = $menu_db->insert(array('name'=>'go3c_config','parentid'=>$go3c_admin, 'm'=>'go3c', 'c'=>'config', 'a'=>'init', 'data'=>'', 'listorder'=>17, 'display'=>'1'), true);
$go3c_member  = $menu_db->insert(array('name'=>'go3c_member','parentid'=>$go3c_admin, 'm'=>'go3c', 'c'=>'member', 'a'=>'init', 'data'=>'', 'listorder'=>18, 'display'=>'1'), true);
$go3c_cp      = $menu_db->insert(array('name'=>'go3c_cp','parentid'=>$go3c_admin, 'm'=>'go3c', 'c'=>'cp', 'a'=>'init', 'data'=>'', 'listorder'=>19, 'display'=>'1'), true);
$go3c_admin   = $menu_db->insert(array('name'=>'go3c_admin','parentid'=>$go3c_admin, 'm'=>'go3c', 'c'=>'admins', 'a'=>'init', 'data'=>'', 'listorder'=>20, 'display'=>'1'), true);
//视频管理
$menu_db->insert(array('name'=>'go3c_video_online', 'parentid'=>$go3c_video, 'm'=>'go3c', 'c'=>'video', 'a'=>'online', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_video_offline', 'parentid'=>$go3c_video, 'm'=>'go3c', 'c'=>'video', 'a'=>'offline', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_video_delete', 'parentid'=>$go3c_video, 'm'=>'go3c', 'c'=>'video', 'a'=>'delete', 'data'=>'', 'listorder'=>3, 'display'=>'1'), true);
//直播管理
$menu_db->insert(array('name'=>'go3c_channel_channel', 'parentid'=>$go3c_channel, 'm'=>'go3c', 'c'=>'channel', 'a'=>'channel', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_channel_epg', 'parentid'=>$go3c_channel, 'm'=>'go3c', 'c'=>'channel', 'a'=>'epg', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
//推荐管理
$menu_db->insert(array('name'=>'go3c_position_stb', 'parentid'=>$go3c_position, 'm'=>'go3c', 'c'=>'position', 'a'=>'stb', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_position_pad', 'parentid'=>$go3c_position, 'm'=>'go3c', 'c'=>'position', 'a'=>'pad', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_position_phone', 'parentid'=>$go3c_position, 'm'=>'go3c', 'c'=>'position', 'a'=>'phone', 'data'=>'', 'listorder'=>3, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_position_pc', 'parentid'=>$go3c_position, 'm'=>'go3c', 'c'=>'position', 'a'=>'pc', 'data'=>'', 'listorder'=>4, 'display'=>'1'), true);
//审核申请
$menu_db->insert(array('name'=>'go3c_verify_online', 'parentid'=>$go3c_verify, 'm'=>'go3c', 'c'=>'verify', 'a'=>'online', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_verify_offline', 'parentid'=>$go3c_verify, 'm'=>'go3c', 'c'=>'verify', 'a'=>'offline', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_verify_delete', 'parentid'=>$go3c_verify, 'm'=>'go3c', 'c'=>'verify', 'a'=>'delete', 'data'=>'', 'listorder'=>3, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_verify_position', 'parentid'=>$go3c_verify, 'm'=>'go3c', 'c'=>'verify', 'a'=>'position', 'data'=>'', 'listorder'=>4, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_verify_category', 'parentid'=>$go3c_verify, 'm'=>'go3c', 'c'=>'verify', 'a'=>'category', 'data'=>'', 'listorder'=>5, 'display'=>'1'), true);
//发布管理
$menu_db->insert(array('name'=>'go3c_publish_job', 'parentid'=>$go3c_publish, 'm'=>'go3c', 'c'=>'publish', 'a'=>'job', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_publish_his', 'parentid'=>$go3c_publish, 'm'=>'go3c', 'c'=>'publish', 'a'=>'his', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_publish_check', 'parentid'=>$go3c_publish, 'm'=>'go3c', 'c'=>'publish', 'a'=>'check', 'data'=>'', 'listorder'=>3, 'display'=>'1'), true);
//资源导入
$menu_db->insert(array('name'=>'go3c_import_run', 'parentid'=>$go3c_import, 'm'=>'go3c', 'c'=>'import', 'a'=>'run', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
//系统设置
$menu_db->insert(array('name'=>'go3c_config_term', 'parentid'=>$go3c_config, 'm'=>'go3c', 'c'=>'config', 'a'=>'term', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_config_column', 'parentid'=>$go3c_config, 'm'=>'go3c', 'c'=>'config', 'a'=>'column', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_config_channel_category', 'parentid'=>$go3c_config, 'm'=>'go3c', 'c'=>'config', 'a'=>'channel_category', 'data'=>'', 'listorder'=>3, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_config_column_mapping', 'parentid'=>$go3c_config, 'm'=>'go3c', 'c'=>'config', 'a'=>'column_mapping', 'data'=>'', 'listorder'=>4, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_config_position', 'parentid'=>$go3c_config, 'm'=>'admin', 'c'=>'position', 'a'=>'init', 'data'=>'', 'listorder'=>5, 'display'=>'1'), true);
//用户管理
$menu_db->insert(array('name'=>'go3c_member_lock', 'parentid'=>$go3c_member, 'm'=>'go3c', 'c'=>'member', 'a'=>'lock', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_member_unlock', 'parentid'=>$go3c_member, 'm'=>'go3c', 'c'=>'member', 'a'=>'unlock', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_member_edit', 'parentid'=>$go3c_member, 'm'=>'go3c', 'c'=>'member', 'a'=>'edit', 'data'=>'', 'listorder'=>3, 'display'=>'1'), true);
//CP管理
$menu_db->insert(array('name'=>'go3c_cp_manager', 'parentid'=>$go3c_cp, 'm'=>'go3c', 'c'=>'cp', 'a'=>'manager', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
//站点管理
$menu_db->insert(array('name'=>'go3c_admin_group',  'parentid'=>$go3c_admin, 'm'=>'admin', 'c'=>'role', 'a'=>'init', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'go3c_admin_member', 'parentid'=>$go3c_admin, 'm'=>'admin', 'c'=>'admin_manage', 'a'=>'init', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);

$language = array(
	'go3c_editor'	=>'业务管理',
	'go3c_admin'	=>'系统管理',
	'go3c_video'	=>'视频管理',
	'go3c_channel'	=>'直播管理', 
	'go3c_position'	=>'推荐管理', 
	'go3c_verify'	=>'审核申请', 
	'go3c_publish'	=>'发布管理',
	'go3c_import'	=>'资源导入', 
	'go3c_config'	=>'系统设置', 
	'go3c_member'	=>'注册用户', 
	'go3c_cp'		=>'CP管理',
	'go3c_admin'	=>'站点管理', 
	'go3c_video_online'		=>'上线管理', 
	'go3c_video_offline'	=>'下线管理',
	'go3c_video_delete'		=>'删除', 
	'go3c_channel_channel'	=>'频道', 
	'go3c_channel_epg'		=>'EPG', 
	'go3c_position_stb'		=>'STB', 
	'go3c_position_pad'		=>'PAD',
	'go3c_position_phone'	=>'PHONE', 
	'go3c_position_pc'		=>'PC', 
	'go3c_verify_online'	=>'视频上线', 
	'go3c_verify_offline'	=>'视频下线',
	'go3c_verify_delete'	=>'视频删除', 
	'go3c_verify_position'	=>'推荐', 
	'go3c_verify_category'	=>'栏目设置', 
	'go3c_publish_job'		=>'待发布项目', 
	'go3c_publish_his'		=>'发布历史', 
	'go3c_import_run'		=>'资源导入', 
	'go3c_config_term'		=>'终端类型', 
	'go3c_config_column'	=>'一级栏目',
	'go3c_config_channel_category'	=>'直播频道分类', 
	'go3c_config_column_mapping'	=>'终端栏目映射', 
	'go3c_config_position'	=>'推荐位管理', 
	'go3c_member_lock'		=>'用户锁定', 
	'go3c_member_unlock'	=>'用户解锁',
	'go3c_member_edit'		=>'修改密码',
	'go3c_cp_manager'		=>'CP资料管理', 
	'go3c_admin_group'		=>'角色管理', 
	'go3c_admin_member'		=>'用户管理', 
);
?>