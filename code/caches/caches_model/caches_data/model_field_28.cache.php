<?php
return array (
  'catid' => 
  array (
    'fieldid' => '527',
    'modelid' => '28',
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
    'fieldid' => '529',
    'modelid' => '28',
    'siteid' => '1',
    'field' => 'title',
    'name' => '广告位名称',
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
  'term_id' => 
  array (
    'fieldid' => '657',
    'modelid' => '28',
    'siteid' => '1',
    'field' => 'term_id',
    'name' => '终端类型',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'linkfield',
    'setting' => 'array (
  \'link_type\' => \'1\',
  \'table_name\' => \'v9_term_type\',
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
    'table_name' => 'v9_term_type',
    'select_title' => 'id,title',
    'like_title' => 'title',
    'set_title' => 'title',
    'set_id' => 'id',
    'insert_type' => 'id',
  ),
  'ad_type' => 
  array (
    'fieldid' => '548',
    'modelid' => '28',
    'siteid' => '1',
    'field' => 'ad_type',
    'name' => '广告类型',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'文本|1
图片|2
视频|3
引导图|0\',
  \'boxtype\' => \'radio\',
  \'fieldtype\' => \'varchar\',
  \'minnumber\' => \'1\',
  \'width\' => \'80\',
  \'size\' => \'1\',
  \'defaultvalue\' => \'1\',
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
    'listorder' => '3',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => '文本|1
图片|2
视频|3
引导图|0',
    'boxtype' => 'radio',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '1',
    'outputtype' => '1',
    'filtertype' => '1',
  ),
  'display_type' => 
  array (
    'fieldid' => '549',
    'modelid' => '28',
    'siteid' => '1',
    'field' => 'display_type',
    'name' => '显示方式',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'水平跑马灯|1
垂直跑马灯|2
图片翻转|3
嵌入小视频|4
全屏视频|5
顶部跑马灯|6
底部跑马灯|7
右下角弹窗|8
背景图片|9\',
  \'boxtype\' => \'radio\',
  \'fieldtype\' => \'varchar\',
  \'minnumber\' => \'1\',
  \'width\' => \'80\',
  \'size\' => \'1\',
  \'defaultvalue\' => \'1\',
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
    'listorder' => '4',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => '水平跑马灯|1
垂直跑马灯|2
图片翻转|3
嵌入小视频|4
全屏视频|5
顶部跑马灯|6
底部跑马灯|7
右下角弹窗|8
背景图片|9',
    'boxtype' => 'radio',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '1',
    'outputtype' => '1',
    'filtertype' => '1',
  ),
  'board_type' => 
  array (
    'fieldid' => '739',
    'modelid' => '28',
    'siteid' => '1',
    'field' => 'board_type',
    'name' => '项目平台',
    'tips' => '同项目不同的板子(如:A20,M8726)',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'A20|A20
M8726|M8726\',
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
    'issearch' => '0',
    'isadd' => '1',
    'isfulltext' => '0',
    'isposition' => '1',
    'listorder' => '6',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => 'A20|A20
M8726|M8726',
    'boxtype' => 'select',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '',
    'outputtype' => '1',
  ),
  'ad_belong' => 
  array (
    'fieldid' => '737',
    'modelid' => '28',
    'siteid' => '1',
    'field' => 'ad_belong',
    'name' => '所属类型',
    'tips' => '',
    'css' => '',
    'minlength' => '0',
    'maxlength' => '0',
    'pattern' => '',
    'errortips' => '',
    'formtype' => 'box',
    'setting' => 'array (
  \'options\' => \'广告|1
开机图|2\',
  \'boxtype\' => \'radio\',
  \'fieldtype\' => \'varchar\',
  \'minnumber\' => \'1\',
  \'width\' => \'80\',
  \'size\' => \'1\',
  \'defaultvalue\' => \'1\',
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
    'listorder' => '8',
    'disabled' => '0',
    'isomnipotent' => '0',
    'options' => '广告|1
开机图|2',
    'boxtype' => 'radio',
    'fieldtype' => 'varchar',
    'minnumber' => '1',
    'width' => '80',
    'size' => '1',
    'defaultvalue' => '1',
    'outputtype' => '1',
    'filtertype' => '1',
  ),
  'genus' => 
  array (
    'fieldid' => '738',
    'modelid' => '28',
    'siteid' => '1',
    'field' => 'genus',
    'name' => '所属位置',
    'tips' => '只对开机图有效',
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
    'isposition' => '0',
    'listorder' => '9',
    'disabled' => '0',
    'isomnipotent' => '0',
    'size' => '50',
    'defaultvalue' => '',
    'ispassword' => '0',
  ),
  'updatetime' => 
  array (
    'fieldid' => '532',
    'modelid' => '28',
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
    'listorder' => '13',
    'disabled' => '0',
    'isomnipotent' => '0',
    'dateformat' => 'int',
    'format' => 'Y-m-d H:i:s',
    'defaulttype' => '1',
    'defaultvalue' => '',
  ),
  'inputtime' => 
  array (
    'fieldid' => '537',
    'modelid' => '28',
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
    'listorder' => '17',
    'disabled' => '0',
    'isomnipotent' => '0',
    'fieldtype' => 'int',
    'format' => 'Y-m-d H:i:s',
    'defaulttype' => '0',
  ),
  'url' => 
  array (
    'fieldid' => '540',
    'modelid' => '28',
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
    'fieldid' => '541',
    'modelid' => '28',
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
    'fieldid' => '542',
    'modelid' => '28',
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
    'fieldid' => '544',
    'modelid' => '28',
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
  'username' => 
  array (
    'fieldid' => '546',
    'modelid' => '28',
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