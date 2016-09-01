<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

//Top Level
$parentid_root = $menu_db->insert(
	array(
		'name'=>'数据同步', 
		'parentid'=>0, 
		'm'=>'dbmover', 
		'c'=>'index', 
		'a'=>'init', 
		'data'=>'',
		'listorder'=>5, 
		'display'=>'1'
	), true);

//一级菜单
//$parend_index = $menu_db->insert(array('name'=>'dbmover_index', 'parentid'=>$parentid_root, 'm'=>'dbmover', 'c'=>'index', 'a'=>'init', 'data'=>'', 'listorder'=>3, 'display'=>'1'), true);
$parend_clear = $menu_db->insert(array('name'=>'dbmover_clear', 'parentid'=>$parentid_root, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'init', 'data'=>'', 'listorder'=>4, 'display'=>'1'), true);
$parend_import = $menu_db->insert(array('name'=>'dbmover_import','parentid'=>$parentid_root, 'm'=>'dbmover', 'c'=>'import', 'a'=>'init', 'data'=>'', 'listorder'=>1, 'display'=>'1'), true);
$parend_export = $menu_db->insert(array('name'=>'dbmover_export','parentid'=>$parentid_root, 'm'=>'dbmover', 'c'=>'export', 'a'=>'init', 'data'=>'', 'listorder'=>2, 'display'=>'1'), true);
$parend_xml    = $menu_db->insert(array('name'=>'dbmover_xml','parentid'=>$parentid_root, 'm'=>'dbmover', 'c'=>'xml', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);

//正向同步
/*
$menu_db->insert(array('name'=>'dbmover_index_tags', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'tags', 'data'=>'', 'listorder'=>2, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_index_channel_category', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'channel_category', 'data'=>'', 'listorder'=>3, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_index_term_type', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'term_type', 'data'=>'', 'listorder'=>4, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_index_column', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'column', 'data'=>'', 'listorder'=>5, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_index_column_content_area', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'column_content_area', 'data'=>'', 'listorder'=>6, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_index_recomm_video_type', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'recomm_video_type', 'data'=>'', 'listorder'=>7, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_index_column_content_cate', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'column_content_cate', 'data'=>'', 'listorder'=>8, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_index_column_mapping', 'parentid'=>$parend_index, 'm'=>'dbmover', 'c'=>'index', 'a'=>'column_mapping', 'data'=>'', 'listorder'=>9, 'display'=>'1'));*/

//反向同步
$menu_db->insert(array('name'=>'dbmover_clear_tags', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'tags', 'data'=>'', 'listorder'=>2, 'display'=>'1'));
/*
$menu_db->insert(array('name'=>'dbmover_clear_channel_category', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'channel_category', 'data'=>'', 'listorder'=>3, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_clear_term_type', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'term_type', 'data'=>'', 'listorder'=>4, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_clear_column', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'column', 'data'=>'', 'listorder'=>5, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_clear_column_content_area', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'column_content_area', 'data'=>'', 'listorder'=>6, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_clear_recomm_video_type', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'recomm_video_type', 'data'=>'', 'listorder'=>7, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_clear_column_content_cate', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'column_content_cate', 'data'=>'', 'listorder'=>8, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_clear_column_mapping', 'parentid'=>$parend_clear, 'm'=>'dbmover', 'c'=>'clear', 'a'=>'column_mapping', 'data'=>'', 'listorder'=>9, 'display'=>'1'));*/

//数据采集与导入
$menu_db->insert(array('name'=>'dbmover_import_channel', 'parentid'=>$parend_import, 'm'=>'dbmover', 'c'=>'import', 'a'=>'channel', 'data'=>'', 'listorder'=>1, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_import_program', 'parentid'=>$parend_import, 'm'=>'dbmover', 'c'=>'import', 'a'=>'program', 'data'=>'', 'listorder'=>2, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_import_asset', 'parentid'=>$parend_import, 'm'=>'dbmover', 'c'=>'import', 'a'=>'asset', 'data'=>'', 'listorder'=>3, 'display'=>'1'));

//数据采集与导入
$menu_db->insert(array('name'=>'dbmover_export_channel', 'parentid'=>$parend_export, 'm'=>'dbmover', 'c'=>'export', 'a'=>'channel', 'data'=>'', 'listorder'=>1, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_export_program', 'parentid'=>$parend_export, 'm'=>'dbmover', 'c'=>'export', 'a'=>'program', 'data'=>'', 'listorder'=>2, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_export_asset', 'parentid'=>$parend_export, 'm'=>'dbmover', 'c'=>'export', 'a'=>'asset', 'data'=>'', 'listorder'=>3, 'display'=>'1'));

//数据采集与导入
$menu_db->insert(array('name'=>'dbmover_xml_cate', 'parentid'=>$parend_xml, 'm'=>'dbmover', 'c'=>'xml', 'a'=>'cate', 'data'=>'', 'listorder'=>1, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_xml_channel', 'parentid'=>$parend_xml, 'm'=>'dbmover', 'c'=>'xml', 'a'=>'channel', 'data'=>'', 'listorder'=>2, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_xml_epg', 'parentid'=>$parend_xml, 'm'=>'dbmover', 'c'=>'xml', 'a'=>'epg', 'data'=>'', 'listorder'=>3, 'display'=>'1'));
$menu_db->insert(array('name'=>'dbmover_xml_export', 'parentid'=>$parend_xml, 'm'=>'dbmover', 'c'=>'xml', 'a'=>'export', 'data'=>'', 'listorder'=>4, 'display'=>'1'));


$language = array(

	'dbmover_index'=>'基础数据同步',
	'dbmover_clear'=>'反向清理数据', 
	'dbmover_import'=>'数据采集与导入', 
	'dbmover_export'=>'前端数据同步', 
	
/*	'dbmover_index_tags'=>'TAG', 
	'dbmover_index_channel_category'=>'直播频道分类', 
	'dbmover_index_term_type'=>'终端类型', 
	'dbmover_index_column'=>'栏目分类', 
	'dbmover_index_column_content_area'=>'栏目地域分类', 
	'dbmover_index_recomm_video_type'=>'推荐类型', 
	'dbmover_index_column_content_cate'=>'栏目内容分类', 
	'dbmover_index_column_mapping'=>'栏目终端映射', */
	
	'dbmover_clear_tags'=>'数据同步', 
/*	'dbmover_clear_channel_category'=>'直播频道分类', 
	'dbmover_clear_term_type'=>'终端类型', 
	'dbmover_clear_column'=>'栏目分类', 
	'dbmover_clear_column_content_area'=>'栏目地域分类', 
	'dbmover_clear_recomm_video_type'=>'推荐类型', 
	'dbmover_clear_column_content_cate'=>'栏目内容分类', 
	'dbmover_clear_column_mapping'=>'栏目终端映射', */
	
	'dbmover_import_channel'=>'导入直播媒体', 
	'dbmover_import_program'=>'导入EPG', 
	'dbmover_import_asset'=>'导入视频', 
	
	'dbmover_export_channel'=>'发布频道', 
	'dbmover_export_program'=>'发布EPG', 
	'dbmover_export_asset'=>'发布视频', 
	
	'dbmover_xml'=>'导入XML数据',
	'dbmover_xml_cate'=>'导入资源分类',
	'dbmover_xml_channel'=>'导入channel数据',
	'dbmover_xml_epg'=>'导入epg数据',
	'dbmover_xml_export'=>'导入视频节目',
	
);
?>