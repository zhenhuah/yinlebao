<?php
return array (
  'catid' => 
  array (
    'fieldid' => '616',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'catid',
    'name' => '栏目',
    'tips' => '',
    'css' => '',
    'minlength' => '1',
    'maxlength' => '6',
    'pattern' => '/^[0-9]{1,6}$/',
    'errortips' => '请选择栏目',
    'formtype' => 'catid',
    'setting' => 'array (
  \'defaultvalue\' => \'\',
)',
    'formattribute' => '',
    'unsetgroupids' => '-99',
    'unsetroleids' => '-99',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '0',
    'listorder' => '0',
    'disabled' => '0',
    'isomnipotent' => '0',
    'defaultvalue' => '',
  ),
  'title' => 
  array (
    'fieldid' => '618',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'title',
    'name' => '游戏名称',
    'tips' => '',
    'css' => 'inputtitle',
    'minlength' => '1',
    'maxlength' => '80',
    'pattern' => '',
    'errortips' => '请输入标题',
    'formtype' => 'title',
    'setting' => '',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '1',
    'disabled' => '0',
    'isomnipotent' => '0',
  ),
  'game_type' => 
  array (
    'fieldid' => '637',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'game_type',
    'name' => '游戏类型',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'linkfield',
    'setting' => 'array (
  \'link_type\' => \'1\',
  \'table_name\' => \'v9_game_type\',
  \'select_title\' => \'id,title\',
  \'like_title\' => \'title\',
  \'set_title\' => \'title\',
  \'set_id\' => \'id\',
  \'insert_type\' => \'id\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '2',
    'disabled' => '0',
    'isomnipotent' => '0',
    'link_type' => '1',
    'table_name' => 'v9_game_type',
    'select_title' => 'id,title',
    'like_title' => 'title',
    'set_title' => 'title',
    'set_id' => 'id',
    'insert_type' => 'id',
  ),
  'game_theme' => 
  array (
    'fieldid' => '638',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'game_theme',
    'name' => '游戏题材',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '3',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_recom_level' => 
  array (
    'fieldid' => '639',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_recom_level',
    'name' => '推荐指数',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'1|1
2|2
3|3
4|4
5|5\',
  \'boxtype\' => \'select\',
  \'fieldtype\' => \'varchar\',
  \'minnumber\' => \'1\',
  \'width\' => \'80\',
  \'size\' => \'1\',
  \'defaultvalue\' => \'\',
  \'outputtype\' => \'1\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '4',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => '1|1
2|2
3|3
4|4
5|5',
    'boxtype' => 'select',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '',
    'outputtype' => '1',
  ),
  'gm_recom_popular' => 
  array (
    'fieldid' => '640',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_recom_popular',
    'name' => '推荐人气',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'1|1
2|2
3|3
4|4
5|5\',
  \'boxtype\' => \'select\',
  \'fieldtype\' => \'varchar\',
  \'minnumber\' => \'1\',
  \'width\' => \'80\',
  \'size\' => \'1\',
  \'defaultvalue\' => \'\',
  \'outputtype\' => \'1\',
  \'filtertype\' => \'1\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '5',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => '1|1
2|2
3|3
4|4
5|5',
    'boxtype' => 'select',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '',
    'outputtype' => '1',
    'filtertype' => '1',
  ),
  'gm_deve_company' => 
  array (
    'fieldid' => '641',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_deve_company',
    'name' => '开发厂商',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '6',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_oper_company' => 
  array (
    'fieldid' => '642',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_oper_company',
    'name' => '运营厂商',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '7',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_title' => 
  array (
    'fieldid' => '643',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_title',
    'name' => '游戏宣传标题',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '8',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_description' => 
  array (
    'fieldid' => '644',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_description',
    'name' => '游戏描述',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'textarea',
    'setting' => 'array (
  \'width\' => \'100%\',
  \'height\' => \'46\',
  \'defaultvalue\' => \'\',
  \'enablehtml\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '0',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '9',
    'disabled' => '0',
    'isomnipotent' => '0',
    'width' => '100%',
    'height' => '46',
    'defaultvalue' => '',
    'enablehtml' => '0',
  ),
  'gm_register_url' => 
  array (
    'fieldid' => '645',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_register_url',
    'name' => '注册链接',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '10',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_login_url' => 
  array (
    'fieldid' => '646',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_login_url',
    'name' => '登录链接',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '11',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_official_url' => 
  array (
    'fieldid' => '647',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_official_url',
    'name' => '游戏官网',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '12',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_tag' => 
  array (
    'fieldid' => '648',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_tag',
    'name' => '游戏推荐标签',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => 'array (
  \'size\' => \'50\',
  \'defaultvalue\' => \'\',
  \'ispassword\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '1',
    'isposition' => '1',
    'listorder' => '13',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'gm_logo' => 
  array (
    'fieldid' => '649',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_logo',
    'name' => '游戏logo',
    'tips' => ' 允许png/jpg 类型 (191 * 90)',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'image',
    'setting' => 'array (
  \'size\' => \'\',
  \'defaultvalue\' => \'\',
  \'show_type\' => \'0\',
  \'upload_maxsize\' => \'\',
  \'upload_allowext\' => \'gif|jpg|jpeg|png|bmp\',
  \'watermark\' => \'0\',
  \'isselectimage\' => \'0\',
  \'images_width\' => \'\',
  \'images_height\' => \'\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '14',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '',
    'defaultvalue' => '',
    'show_type' => '0',
    'upload_maxsize' => '',
    'upload_allowext' => 'gif|jpg|jpeg|png|bmp',
    'watermark' => '0',
    'isselectimage' => '0',
    'images_width' => '',
    'images_height' => '',
  ),
  'gm_name_icon' => 
  array (
    'fieldid' => '650',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_name_icon',
    'name' => '游戏名字前的小图标',
    'tips' => '允许png/jpg 类型 (16 * 16)',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'image',
    'setting' => 'array (
  \'size\' => \'\',
  \'defaultvalue\' => \'\',
  \'show_type\' => \'0\',
  \'upload_maxsize\' => \'\',
  \'upload_allowext\' => \'gif|jpg|jpeg|png|bmp\',
  \'watermark\' => \'0\',
  \'isselectimage\' => \'0\',
  \'images_width\' => \'\',
  \'images_height\' => \'\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '15',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '',
    'defaultvalue' => '',
    'show_type' => '0',
    'upload_maxsize' => '',
    'upload_allowext' => 'gif|jpg|jpeg|png|bmp',
    'watermark' => '0',
    'isselectimage' => '0',
    'images_width' => '',
    'images_height' => '',
  ),
  'gm_cover_image' => 
  array (
    'fieldid' => '651',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_cover_image',
    'name' => '游戏封面图片',
    'tips' => '允许png/jpg 类型 (210 * 118)',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'image',
    'setting' => 'array (
  \'size\' => \'\',
  \'defaultvalue\' => \'\',
  \'show_type\' => \'0\',
  \'upload_maxsize\' => \'\',
  \'upload_allowext\' => \'gif|jpg|jpeg|png|bmp\',
  \'watermark\' => \'0\',
  \'isselectimage\' => \'0\',
  \'images_width\' => \'\',
  \'images_height\' => \'\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '16',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '',
    'defaultvalue' => '',
    'show_type' => '0',
    'upload_maxsize' => '',
    'upload_allowext' => 'gif|jpg|jpeg|png|bmp',
    'watermark' => '0',
    'isselectimage' => '0',
    'images_width' => '',
    'images_height' => '',
  ),
  'gm_repre_image' => 
  array (
    'fieldid' => '652',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_repre_image',
    'name' => '游戏代表图片',
    'tips' => '允许png/jpg 类型 (374 * 435)',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'image',
    'setting' => 'array (
  \'size\' => \'\',
  \'defaultvalue\' => \'\',
  \'show_type\' => \'0\',
  \'upload_maxsize\' => \'\',
  \'upload_allowext\' => \'gif|jpg|jpeg|png|bmp\',
  \'watermark\' => \'0\',
  \'isselectimage\' => \'0\',
  \'images_width\' => \'\',
  \'images_height\' => \'\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '17',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '',
    'defaultvalue' => '',
    'show_type' => '0',
    'upload_maxsize' => '',
    'upload_allowext' => 'gif|jpg|jpeg|png|bmp',
    'watermark' => '0',
    'isselectimage' => '0',
    'images_width' => '',
    'images_height' => '',
  ),
  'gm_is_recom' => 
  array (
    'fieldid' => '653',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_is_recom',
    'name' => '是否推荐',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'推荐|1
不推荐|0\',
  \'boxtype\' => \'radio\',
  \'fieldtype\' => \'varchar\',
  \'minnumber\' => \'1\',
  \'width\' => \'80\',
  \'size\' => \'1\',
  \'defaultvalue\' => \'0\',
  \'outputtype\' => \'1\',
  \'filtertype\' => \'1\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '18',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => '推荐|1
不推荐|0',
    'boxtype' => 'radio',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '0',
    'outputtype' => '1',
    'filtertype' => '1',
  ),
  'gm_is_popularize' => 
  array (
    'fieldid' => '654',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'gm_is_popularize',
    'name' => '是否推广',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'推广|1
不推广|0\',
  \'boxtype\' => \'radio\',
  \'fieldtype\' => \'varchar\',
  \'minnumber\' => \'1\',
  \'width\' => \'80\',
  \'size\' => \'1\',
  \'defaultvalue\' => \'0\',
  \'outputtype\' => \'1\',
  \'filtertype\' => \'1\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '1',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '19',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => '推广|1
不推广|0',
    'boxtype' => 'radio',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '0',
    'outputtype' => '1',
    'filtertype' => '1',
  ),
  'open_service' => 
  array (
    'fieldid' => '655',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'open_service',
    'name' => '游戏开服',
    'tips' => '请用  日期|服务器名称  例如：2012-10-11|234服',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'textarea',
    'setting' => 'array (
  \'width\' => \'100%\',
  \'height\' => \'46\',
  \'defaultvalue\' => \'\',
  \'enablehtml\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '0',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '20',
    'disabled' => '0',
    'isomnipotent' => '0',
    'width' => '100%',
    'height' => '46',
    'defaultvalue' => '',
    'enablehtml' => '0',
  ),
  'url' => 
  array (
    'fieldid' => '629',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'url',
    'name' => 'URL',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '100',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => '',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '1',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '0',
    'isfulltext' => '0',
    'isposition' => '0',
    'listorder' => '50',
    'disabled' => '0',
    'isomnipotent' => '0',
  ),
  'listorder' => 
  array (
    'fieldid' => '630',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'listorder',
    'name' => '排序',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '6',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'number',
    'setting' => '',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '1',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '0',
    'isfulltext' => '0',
    'isposition' => '0',
    'listorder' => '51',
    'disabled' => '0',
    'isomnipotent' => '0',
  ),
  'template' => 
  array (
    'fieldid' => '631',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'template',
    'name' => '内容页模板',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '30',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'template',
    'setting' => 'array (
  \'size\' => \'\',
  \'defaultvalue\' => \'\',
)',
    'formattribute' => '',
    'unsetgroupids' => '-99',
    'unsetroleids' => '-99',
    'iscore' => '0',
    'issystem' => '0',
    'isunique' => '0',
    'isbase' => '0',
    'issearch' => '0',
    'isadd' => '0',
    'isfulltext' => '0',
    'isposition' => '0',
    'listorder' => '53',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '',
    'defaultvalue' => '',
  ),
  'status' => 
  array (
    'fieldid' => '633',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'status',
    'name' => '状态',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '2',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => '',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '1',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '0',
    'isfulltext' => '0',
    'isposition' => '0',
    'listorder' => '55',
    'disabled' => '0',
    'isomnipotent' => '0',
  ),
  'updatetime' => 
  array (
    'fieldid' => '621',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'updatetime',
    'name' => '更新时间',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'datetime',
    'setting' => 'array (
  \'dateformat\' => \'int\',
  \'format\' => \'Y-m-d H:i:s\',
  \'defaulttype\' => \'1\',
  \'defaultvalue\' => \'\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '1',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '0',
    'isfulltext' => '0',
    'isposition' => '0',
    'listorder' => '60',
    'disabled' => '0',
    'isomnipotent' => '0',
    'dateformat' => 'int',
    'format' => 'Y-m-d H:i:s',
    'defaulttype' => '1',
    'defaultvalue' => '',
  ),
  'inputtime' => 
  array (
    'fieldid' => '626',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'inputtime',
    'name' => '发布时间',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'datetime',
    'setting' => 'array (
  \'fieldtype\' => \'int\',
  \'format\' => \'Y-m-d H:i:s\',
  \'defaulttype\' => \'0\',
)',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '0',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '0',
    'issearch' => '0',
    'isadd' => '0',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '61',
    'disabled' => '0',
    'isomnipotent' => '0',
    'fieldtype' => 'int',
    'format' => 'Y-m-d H:i:s',
    'defaulttype' => '0',
  ),
  'username' => 
  array (
    'fieldid' => '635',
    'modelid' => '32',
    'siteid' => '1',
    'field' => 'username',
    'name' => '用户名',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '20',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'text',
    'setting' => '',
    'formattribute' => '',
    'unsetgroupids' => '',
    'unsetroleids' => '',
    'iscore' => '1',
    'issystem' => '1',
    'isunique' => '0',
    'isbase' => '1',
    'issearch' => '0',
    'isadd' => '0',
    'isfulltext' => '0',
    'isposition' => '0',
    'listorder' => '98',
    'disabled' => '0',
    'isomnipotent' => '0',
  ),
);
?>