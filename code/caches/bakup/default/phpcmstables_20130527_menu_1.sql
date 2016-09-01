# phpcms bakfile
# version:PHPCMS V9
# time:2013-05-27 10:37:46
# type:phpcms
# phpcms:http://www.phpcms.cn
# --------------------------------------------------------


DROP TABLE IF EXISTS `v9_member_menu`;
CREATE TABLE `v9_member_menu` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` char(40) NOT NULL default '',
  `parentid` smallint(6) NOT NULL default '0',
  `m` char(20) NOT NULL default '',
  `c` char(20) NOT NULL default '',
  `a` char(20) NOT NULL default '',
  `data` char(100) NOT NULL default '',
  `listorder` smallint(6) unsigned NOT NULL default '0',
  `display` enum('1','0') NOT NULL default '1',
  `isurl` enum('1','0') NOT NULL default '0',
  `url` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `module` (`m`,`c`,`a`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `v9_member_menu` VALUES('1','member_init','0','member','index','init','t=0','0','1','','');
INSERT INTO `v9_member_menu` VALUES('2','account_manage','0','member','index','account_manage','t=1','0','1','','');
INSERT INTO `v9_member_menu` VALUES('3','favorite','0','member','index','favorite','t=2','0','1','','');

DROP TABLE IF EXISTS `v9_menu`;
CREATE TABLE `v9_menu` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` char(40) NOT NULL default '',
  `parentid` smallint(6) NOT NULL default '0',
  `m` char(20) NOT NULL default '',
  `c` char(20) NOT NULL default '',
  `a` char(20) NOT NULL default '',
  `data` char(100) NOT NULL default '',
  `listorder` smallint(6) unsigned NOT NULL default '0',
  `display` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `module` (`m`,`c`,`a`)
) ENGINE=MyISAM AUTO_INCREMENT=2338 DEFAULT CHARSET=utf8;

INSERT INTO `v9_menu` VALUES('1','sys_setting','0','admin','setting','init','','1','1');
INSERT INTO `v9_menu` VALUES('872','sync_release_point','873','release','index','init','','3','1');
INSERT INTO `v9_menu` VALUES('4','content','0','content','content','init','','2','0');
INSERT INTO `v9_menu` VALUES('5','members','0','member','member','init','','5','0');
INSERT INTO `v9_menu` VALUES('6','userinterface','0','template','style','init','','6','0');
INSERT INTO `v9_menu` VALUES('30','correlative_setting','1','admin','admin','admin','','0','1');
INSERT INTO `v9_menu` VALUES('31','menu_manage','977','admin','menu','init','','0','1');
INSERT INTO `v9_menu` VALUES('32','posid_manage','2262','admin','position','init','','6','1');
INSERT INTO `v9_menu` VALUES('29','module_manage','977','admin','module','init','','0','1');
INSERT INTO `v9_menu` VALUES('9','plugin','0','admin','plugin','init','','8','0');
INSERT INTO `v9_menu` VALUES('10','panel','0','admin','index','public_main','','0','1');
INSERT INTO `v9_menu` VALUES('35','menu_add','31','admin','menu','add','','0','1');
INSERT INTO `v9_menu` VALUES('826','template_manager','6','','','','','0','1');
INSERT INTO `v9_menu` VALUES('54','admin_manage','2225','admin','admin_manage','init','','0','1');
INSERT INTO `v9_menu` VALUES('43','category_manage','975','admin','category','init','module=admin','4','1');
INSERT INTO `v9_menu` VALUES('42','add_category','975','admin','category','add','s=0','1','1');
INSERT INTO `v9_menu` VALUES('44','edit_category','43','admin','category','edit','','0','0');
INSERT INTO `v9_menu` VALUES('45','badword_manage','977','admin','badword','init','','10','0');
INSERT INTO `v9_menu` VALUES('46','posid_add','32','admin','position','add','','0','0');
INSERT INTO `v9_menu` VALUES('50','role_manage','2225','admin','role','init','','0','1');
INSERT INTO `v9_menu` VALUES('51','role_add','2292','admin','role','add','','0','1');
INSERT INTO `v9_menu` VALUES('52','category_cache','43','admin','category','public_cache','module=admin','4','1');
INSERT INTO `v9_menu` VALUES('55','manage_member','5','member','member','manage','','0','1');
INSERT INTO `v9_menu` VALUES('58','admin_add','2293','admin','admin_manage','add','','99','1');
INSERT INTO `v9_menu` VALUES('59','model_manage','977','content','sitemodel','init','','1','1');
INSERT INTO `v9_menu` VALUES('64','site_management','30','admin','site','init','','2','1');
INSERT INTO `v9_menu` VALUES('81','member_add','72','member','member','add','','2','0');
INSERT INTO `v9_menu` VALUES('62','add_model','59','content','sitemodel','add','','0','0');
INSERT INTO `v9_menu` VALUES('65','release_point_management','30','admin','release_point','init','','3','0');
INSERT INTO `v9_menu` VALUES('66','badword_export','45','admin','badword','export','','0','1');
INSERT INTO `v9_menu` VALUES('67','add_site','64','admin','site','add','','0','0');
INSERT INTO `v9_menu` VALUES('68','badword_import','45','admin','badword','import','','0','1');
INSERT INTO `v9_menu` VALUES('812','member_group_manage','76','member','member_group','manage','','0','1');
INSERT INTO `v9_menu` VALUES('74','member_verify','55','member','member_verify','manage','s=0','3','1');
INSERT INTO `v9_menu` VALUES('76','manage_member_group','5','member','member_group','manage','','0','1');
INSERT INTO `v9_menu` VALUES('77','manage_member_model','5','member','member_model','manage','','0','1');
INSERT INTO `v9_menu` VALUES('78','member_group_add','812','member','member_group','add','','0','0');
INSERT INTO `v9_menu` VALUES('79','member_model_add','813','member','member_model','add','','1','0');
INSERT INTO `v9_menu` VALUES('80','member_model_import','77','member','member_model','import','','2','0');
INSERT INTO `v9_menu` VALUES('72','member_manage','55','member','member','manage','','1','1');
INSERT INTO `v9_menu` VALUES('813','member_model_manage','77','member','member_model','manage','','0','1');
INSERT INTO `v9_menu` VALUES('814','site_edit','64','admin','site','edit','','0','0');
INSERT INTO `v9_menu` VALUES('815','site_del','64','admin','site','del','','0','0');
INSERT INTO `v9_menu` VALUES('816','release_point_add','65','admin','release_point','add','','0','0');
INSERT INTO `v9_menu` VALUES('817','release_point_del','65','admin','release_point','del','','0','0');
INSERT INTO `v9_menu` VALUES('818','release_point_edit','65','admin','release_point','edit','','0','0');
INSERT INTO `v9_menu` VALUES('821','content_publish','4','content','content','init','','0','1');
INSERT INTO `v9_menu` VALUES('822','content_manage','821','content','content','init','','0','1');
INSERT INTO `v9_menu` VALUES('867','linkage','977','admin','linkage','init','','13','0');
INSERT INTO `v9_menu` VALUES('827','template_style','826','template','style','init','','0','1');
INSERT INTO `v9_menu` VALUES('828','import_style','827','template','style','import','','0','0');
INSERT INTO `v9_menu` VALUES('831','template_export','827','template','style','export','','0','0');
INSERT INTO `v9_menu` VALUES('830','template_file','827','template','file','init','','0','0');
INSERT INTO `v9_menu` VALUES('832','template_onoff','827','template','style','disable','','0','0');
INSERT INTO `v9_menu` VALUES('833','template_updatename','827','template','style','updatename','','0','0');
INSERT INTO `v9_menu` VALUES('834','member_lock','72','member','member','lock','','0','0');
INSERT INTO `v9_menu` VALUES('835','member_unlock','72','member','member','unlock','','0','0');
INSERT INTO `v9_menu` VALUES('836','member_move','72','member','member','move','','0','0');
INSERT INTO `v9_menu` VALUES('837','member_delete','72','member','member','delete','','0','0');
INSERT INTO `v9_menu` VALUES('842','verify_ignore','74','member','member_verify','manage','s=2','0','1');
INSERT INTO `v9_menu` VALUES('839','member_setting','55','member','member_setting','manage','','4','1');
INSERT INTO `v9_menu` VALUES('841','verify_pass','74','member','member_verify','manage','s=1','0','1');
INSERT INTO `v9_menu` VALUES('843','verify_delete','74','member','member_verify','manage','s=3','0','0');
INSERT INTO `v9_menu` VALUES('844','verify_deny','74','member','member_verify','manage','s=4','0','1');
INSERT INTO `v9_menu` VALUES('845','never_pass','74','member','member_verify','manage','s=5','0','1');
INSERT INTO `v9_menu` VALUES('846','template_file_list','827','template','file','init','','0','0');
INSERT INTO `v9_menu` VALUES('847','template_file_edit','827','template','file','edit_file','','0','0');
INSERT INTO `v9_menu` VALUES('848','file_add_file','827','template','file','add_file','','0','0');
INSERT INTO `v9_menu` VALUES('850','listorder','76','member','member_group','sort','','0','0');
INSERT INTO `v9_menu` VALUES('852','priv_setting','2292','admin','role','priv_setting','','0','0');
INSERT INTO `v9_menu` VALUES('853','role_priv','2292','admin','role','role_priv','','0','0');
INSERT INTO `v9_menu` VALUES('857','attachment_manage','821','attachment','manage','init','','0','1');
INSERT INTO `v9_menu` VALUES('868','special','821','special','special','init','','0','0');
INSERT INTO `v9_menu` VALUES('869','template_editor','827','template','file','edit_file','','0','0');
INSERT INTO `v9_menu` VALUES('870','template_visualization','827','template','file','visualization','','0','0');
INSERT INTO `v9_menu` VALUES('871','pc_tag_edit','827','template','file','edit_pc_tag','','0','0');
INSERT INTO `v9_menu` VALUES('873','release_manage','4','release','html','init','','0','0');
INSERT INTO `v9_menu` VALUES('874','type_manage','975','content','type_manage','init','','6','0');
INSERT INTO `v9_menu` VALUES('875','add_type','874','content','type_manage','add','','0','0');
INSERT INTO `v9_menu` VALUES('876','linkageadd','867','admin','linkage','add','','0','0');
INSERT INTO `v9_menu` VALUES('877','failure_list','872','release','index','failed','','0','1');
INSERT INTO `v9_menu` VALUES('879','member_search','72','member','member','search','','0','0');
INSERT INTO `v9_menu` VALUES('880','queue_del','872','release','index','del','','0','0');
INSERT INTO `v9_menu` VALUES('881','member_model_delete','813','member','member_model','delete','','0','0');
INSERT INTO `v9_menu` VALUES('882','member_model_edit','813','member','member_model','edit','','0','0');
INSERT INTO `v9_menu` VALUES('885','workflow','977','content','workflow','init','','7','0');
INSERT INTO `v9_menu` VALUES('888','add_workflow','885','content','workflow','add','','0','1');
INSERT INTO `v9_menu` VALUES('889','member_modelfield_add','813','member','member_modelfield','add','','0','0');
INSERT INTO `v9_menu` VALUES('890','member_modelfield_edit','813','member','member_modelfield','edit','','0','0');
INSERT INTO `v9_menu` VALUES('891','member_modelfield_delete','813','member','member_modelfield','delete','','0','0');
INSERT INTO `v9_menu` VALUES('892','member_modelfield_manage','813','member','member_modelfield','manage','','0','0');
INSERT INTO `v9_menu` VALUES('895','sitemodel_import','59','content','sitemodel','import','','0','1');
INSERT INTO `v9_menu` VALUES('896','pay','29','pay','payment','pay_list','s=3','0','0');
INSERT INTO `v9_menu` VALUES('897','payments','896','pay','payment','init','','0','1');
INSERT INTO `v9_menu` VALUES('898','paylist','896','pay','payment','pay_list','','0','1');
INSERT INTO `v9_menu` VALUES('899','add_content','822','content','content','add','','0','0');
INSERT INTO `v9_menu` VALUES('900','modify_deposit','896','pay','payment','modify_deposit','s=1','0','1');
INSERT INTO `v9_menu` VALUES('901','check_content','822','content','content','pass','','0','0');
INSERT INTO `v9_menu` VALUES('902','dbsource','977','dbsource','data','init','','99','0');
INSERT INTO `v9_menu` VALUES('905','create_content_html','873','content','create_html','show','','2','1');
INSERT INTO `v9_menu` VALUES('904','external_data_sources','902','dbsource','dbsource_admin','init','','0','1');
INSERT INTO `v9_menu` VALUES('906','update_urls','873','content','create_html','update_urls','','1','1');
INSERT INTO `v9_menu` VALUES('960','node_add','957','collection','node','add','','0','1');
INSERT INTO `v9_menu` VALUES('909','fulltext_search','29','search','search_type','init','','0','0');
INSERT INTO `v9_menu` VALUES('910','manage_type','909','search','search_type','init','','0','0');
INSERT INTO `v9_menu` VALUES('911','search_setting','909','search','search_admin','setting','','0','1');
INSERT INTO `v9_menu` VALUES('912','createindex','909','search','search_admin','createindex','','0','1');
INSERT INTO `v9_menu` VALUES('913','add_search_type','909','search','search_type','add','','0','0');
INSERT INTO `v9_menu` VALUES('914','database_toos','1','admin','database','export','','6','1');
INSERT INTO `v9_menu` VALUES('915','database_export','914','admin','database','export','','0','1');
INSERT INTO `v9_menu` VALUES('916','database_import','914','admin','database','import','','0','1');
INSERT INTO `v9_menu` VALUES('917','dbsource_add','902','dbsource','dbsource_admin','add','','0','0');
INSERT INTO `v9_menu` VALUES('918','dbsource_edit','902','dbsource','dbsource_admin','edit','','0','0');
INSERT INTO `v9_menu` VALUES('919','dbsource_del','902','dbsource','dbsource_admin','del','','0','0');
INSERT INTO `v9_menu` VALUES('920','dbsource_data_add','902','dbsource','data','add','','0','0');
INSERT INTO `v9_menu` VALUES('921','dbsource_data_edit','902','dbsource','data','edit','','0','0');
INSERT INTO `v9_menu` VALUES('922','dbsource_data_del','902','dbsource','data','del','','0','0');
INSERT INTO `v9_menu` VALUES('926','add_special','868','special','special','add','','0','1');
INSERT INTO `v9_menu` VALUES('927','edit_special','868','special','special','edit','','0','0');
INSERT INTO `v9_menu` VALUES('928','special_list','868','special','special','init','','0','0');
INSERT INTO `v9_menu` VALUES('929','special_elite','868','special','special','elite','','0','0');
INSERT INTO `v9_menu` VALUES('930','delete_special','868','special','special','delete','','0','0');
INSERT INTO `v9_menu` VALUES('931','badword_add','45','admin','badword','add','','0','0');
INSERT INTO `v9_menu` VALUES('932','badword_edit','45','admin','badword','edit','','0','0');
INSERT INTO `v9_menu` VALUES('933','badword_delete','45','admin','badword','delete','','0','0');
INSERT INTO `v9_menu` VALUES('934','special_listorder','868','special','special','listorder','','0','0');
INSERT INTO `v9_menu` VALUES('935','special_content_list','868','special','content','init','','0','0');
INSERT INTO `v9_menu` VALUES('936','special_content_add','935','special','content','add','','0','0');
INSERT INTO `v9_menu` VALUES('937','special_content_list','935','special','content','init','','0','0');
INSERT INTO `v9_menu` VALUES('938','special_content_edit','935','special','content','edit','','0','0');
INSERT INTO `v9_menu` VALUES('939','special_content_delete','935','special','content','delete','','0','0');
INSERT INTO `v9_menu` VALUES('940','special_content_listorder','935','special','content','listorder','','0','0');
INSERT INTO `v9_menu` VALUES('941','special_content_import','935','special','special','import','','0','0');
INSERT INTO `v9_menu` VALUES('942','history_version','827','template','template_bak','init','','0','0');
INSERT INTO `v9_menu` VALUES('943','restore_version','827','template','template_bak','restore','','0','0');
INSERT INTO `v9_menu` VALUES('944','del_history_version','827','template','template_bak','del','','0','0');
INSERT INTO `v9_menu` VALUES('945','block','821','block','block_admin','init','','0','0');
INSERT INTO `v9_menu` VALUES('946','block_add','945','block','block_admin','add','','0','0');
INSERT INTO `v9_menu` VALUES('950','block_edit','945','block','block_admin','edit','','0','0');
INSERT INTO `v9_menu` VALUES('951','block_del','945','block','block_admin','del','','0','0');
INSERT INTO `v9_menu` VALUES('952','block_update','945','block','block_admin','block_update','','0','0');
INSERT INTO `v9_menu` VALUES('953','block_restore','945','block','block_admin','history_restore','','0','0');
INSERT INTO `v9_menu` VALUES('954','history_del','945','block','block_admin','history_del','','0','0');
INSERT INTO `v9_menu` VALUES('978','urlrule_manage','977','admin','urlrule','init','','0','0');
INSERT INTO `v9_menu` VALUES('957','collection_node','821','collection','node','manage','','0','0');
INSERT INTO `v9_menu` VALUES('979','safe_config','30','admin','setting','init','&tab=2','11','0');
INSERT INTO `v9_menu` VALUES('959','basic_config','30','admin','setting','init','','10','1');
INSERT INTO `v9_menu` VALUES('961','position_edit','32','admin','position','edit','','0','0');
INSERT INTO `v9_menu` VALUES('962','collection_node_edit','957','collection','node','edit','','0','0');
INSERT INTO `v9_menu` VALUES('963','collection_node_delete','957','collection','node','del','','0','0');
INSERT INTO `v9_menu` VALUES('990','col_url_list','957','collection','node','col_url_list','','0','0');
INSERT INTO `v9_menu` VALUES('965','collection_node_publish','957','collection','node','publist','','0','0');
INSERT INTO `v9_menu` VALUES('966','collection_node_import','957','collection','node','node_import','','0','0');
INSERT INTO `v9_menu` VALUES('967','collection_node_export','957','collection','node','export','','0','0');
INSERT INTO `v9_menu` VALUES('968','collection_node_collection_content','957','collection','node','col_content','','0','0');
INSERT INTO `v9_menu` VALUES('969','googlesitemap','977','admin','googlesitemap','set','','11','0');
INSERT INTO `v9_menu` VALUES('970','admininfo','10','admin','admin_manage','init','','0','1');
INSERT INTO `v9_menu` VALUES('971','editpwd','970','admin','admin_manage','public_edit_pwd','','1','1');
INSERT INTO `v9_menu` VALUES('972','editinfo','970','admin','admin_manage','public_edit_info','','0','1');
INSERT INTO `v9_menu` VALUES('973','keylink','977','admin','keylink','init','','12','0');
INSERT INTO `v9_menu` VALUES('974','add_keylink','973','admin','keylink','add','','0','0');
INSERT INTO `v9_menu` VALUES('975','content_settings','4','content','content_settings','init','','0','1');
INSERT INTO `v9_menu` VALUES('977','extend_all','1','admin','extend_all','init','','0','1');
INSERT INTO `v9_menu` VALUES('980','sso_config','30','admin','setting','init','&tab=3','12','0');
INSERT INTO `v9_menu` VALUES('981','email_config','30','admin','setting','init','&tab=4','13','0');
INSERT INTO `v9_menu` VALUES('982','module_manage','977','admin','module','init','','0','0');
INSERT INTO `v9_menu` VALUES('983','ipbanned','2265','admin','ipbanned','init','','100','0');
INSERT INTO `v9_menu` VALUES('984','add_ipbanned','983','admin','ipbanned','add','','0','0');
INSERT INTO `v9_menu` VALUES('993','collection_content_import','957','collection','node','import','','0','0');
INSERT INTO `v9_menu` VALUES('991','copy_node','957','collection','node','copy','','0','0');
INSERT INTO `v9_menu` VALUES('992','content_del','957','collection','node','content_del','','0','0');
INSERT INTO `v9_menu` VALUES('989','downsites','977','admin','downservers','init','','0','0');
INSERT INTO `v9_menu` VALUES('994','import_program_add','957','collection','node','import_program_add','','0','0');
INSERT INTO `v9_menu` VALUES('995','import_program_del','957','collection','node','import_program_del','','0','0');
INSERT INTO `v9_menu` VALUES('996','import_content','957','collection','node','import_content','','0','0');
INSERT INTO `v9_menu` VALUES('997','log','977','admin','log','init','','0','0');
INSERT INTO `v9_menu` VALUES('998','add_page','43','admin','category','add','s=1','2','1');
INSERT INTO `v9_menu` VALUES('999','add_cat_link','43','admin','category','add','s=2','2','1');
INSERT INTO `v9_menu` VALUES('1000','count_items','43','admin','category','count_items','','5','1');
INSERT INTO `v9_menu` VALUES('1001','cache_all','977','admin','cache_all','init','','0','0');
INSERT INTO `v9_menu` VALUES('1002','create_list_html','873','content','create_html','category','','0','1');
INSERT INTO `v9_menu` VALUES('1003','create_html_quick','10','content','create_html_opt','index','','0','0');
INSERT INTO `v9_menu` VALUES('1004','create_index','1003','content','create_html','public_index','','0','1');
INSERT INTO `v9_menu` VALUES('1005','scan','977','scan','index','init','','0','0');
INSERT INTO `v9_menu` VALUES('1006','scan_report','1005','scan','index','scan_report','','0','1');
INSERT INTO `v9_menu` VALUES('1007','md5_creat','1005','scan','index','md5_creat','','0','1');
INSERT INTO `v9_menu` VALUES('1011','edit_content','822','content','content','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1012','push_to_special','822','content','push','init','','0','0');
INSERT INTO `v9_menu` VALUES('1023','delete_log','997','admin','log','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1024','delete_ip','983','admin','ipbanned','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1026','delete_keylink','973','admin','keylink','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1027','edit_keylink','973','admin','keylink','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1034','operation_pass','74','member','member_verify','pass','','0','0');
INSERT INTO `v9_menu` VALUES('1035','operation_delete','74','member','member_verify','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1039','operation_ignore','74','member','member_verify','ignore','','0','0');
INSERT INTO `v9_menu` VALUES('1038','settingsave','30','admin','setting','save','','0','0');
INSERT INTO `v9_menu` VALUES('1040','admin_edit','2293','admin','admin_manage','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1041','operation_reject','74','member','member_verify','reject','','0','0');
INSERT INTO `v9_menu` VALUES('1042','admin_delete','2293','admin','admin_manage','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1043','card','2293','admin','admin_manage','card','','0','0');
INSERT INTO `v9_menu` VALUES('1044','creat_card','2293','admin','admin_manage','creat_card','','0','0');
INSERT INTO `v9_menu` VALUES('1045','remove_card','2293','admin','admin_manage','remove_card','','0','0');
INSERT INTO `v9_menu` VALUES('1049','member_group_edit','812','member','member_group','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1048','member_edit','72','member','member','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1050','role_edit','2292','admin','role','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1051','member_group_delete','812','member','member_group','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1052','member_manage','2292','admin','role','member_manage','','0','0');
INSERT INTO `v9_menu` VALUES('1053','role_delete','2292','admin','role','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1054','member_model_export','77','member','member_model','export','','0','0');
INSERT INTO `v9_menu` VALUES('1055','member_model_sort','77','member','member_model','sort','','0','0');
INSERT INTO `v9_menu` VALUES('1056','member_model_move','77','member','member_model','move','','0','0');
INSERT INTO `v9_menu` VALUES('1057','payment_add','897','pay','payment','add','','0','0');
INSERT INTO `v9_menu` VALUES('1058','payment_delete','897','pay','payment','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1059','payment_edit','897','pay','payment','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1060','spend_record','896','pay','spend','init','','0','1');
INSERT INTO `v9_menu` VALUES('1061','pay_stat','896','pay','payment','pay_stat','','0','1');
INSERT INTO `v9_menu` VALUES('1062','fields_manage','59','content','sitemodel_field','init','','0','0');
INSERT INTO `v9_menu` VALUES('1063','edit_model_content','59','content','sitemodel','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1064','disabled_model','59','content','sitemodel','disabled','','0','0');
INSERT INTO `v9_menu` VALUES('1065','delete_model','59','content','sitemodel','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1066','export_model','59','content','sitemodel','export','','0','0');
INSERT INTO `v9_menu` VALUES('1067','delete','874','content','type_manage','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1068','edit','874','content','type_manage','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1069','add_urlrule','978','admin','urlrule','add','','0','0');
INSERT INTO `v9_menu` VALUES('1070','edit_urlrule','978','admin','urlrule','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1071','delete_urlrule','978','admin','urlrule','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1072','edit_menu','31','admin','menu','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1073','delete_menu','31','admin','menu','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1074','edit_workflow','885','content','workflow','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1075','delete_workflow','885','content','workflow','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1076','creat_html','868','special','special','html','','0','1');
INSERT INTO `v9_menu` VALUES('1093','connect_config','30','admin','setting','init','&tab=5','14','0');
INSERT INTO `v9_menu` VALUES('1102','view_modelinfo','74','member','member_verify','modelinfo','','0','0');
INSERT INTO `v9_menu` VALUES('1202','create_special_list','868','special','special','create_special_list','','0','1');
INSERT INTO `v9_menu` VALUES('1240','view_memberlinfo','72','member','member','memberinfo','','0','0');
INSERT INTO `v9_menu` VALUES('1239','copyfrom_manage','977','admin','copyfrom','init','','0','0');
INSERT INTO `v9_menu` VALUES('1241','move_content','822','content','content','move','','0','0');
INSERT INTO `v9_menu` VALUES('1243','create_index','873','content','create_html','public_index','','0','1');
INSERT INTO `v9_menu` VALUES('1244','add_othors','822','content','content','add_othors','','0','1');
INSERT INTO `v9_menu` VALUES('1295','attachment_manager_dir','857','attachment','manage','dir','','2','1');
INSERT INTO `v9_menu` VALUES('1296','attachment_manager_db','857','attachment','manage','init','','1','1');
INSERT INTO `v9_menu` VALUES('1346','attachment_address_replace','857','attachment','address','init','','3','1');
INSERT INTO `v9_menu` VALUES('1347','attachment_address_update','857','attachment','address','update','','0','0');
INSERT INTO `v9_menu` VALUES('1348','delete_content','822','content','content','delete','','0','1');
INSERT INTO `v9_menu` VALUES('1349','member_menu_manage','977','member','member_menu','manage','','0','0');
INSERT INTO `v9_menu` VALUES('1350','member_menu_add','1349','member','member_menu','add','','0','1');
INSERT INTO `v9_menu` VALUES('1351','member_menu_edit','1349','member','member_menu','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1352','member_menu_delete','1349','member','member_menu','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1353','batch_show','822','content','create_html','batch_show','','0','1');
INSERT INTO `v9_menu` VALUES('1354','pay_delete','898','pay','payment','pay_del','','0','0');
INSERT INTO `v9_menu` VALUES('1355','pay_cancel','898','pay','payment','pay_cancel','','0','0');
INSERT INTO `v9_menu` VALUES('1356','discount','898','pay','payment','discount','','0','0');
INSERT INTO `v9_menu` VALUES('1360','category_batch_edit','43','admin','category','batch_edit','','6','1');
INSERT INTO `v9_menu` VALUES('1361','plugin','9','admin','plugin','init','','0','1');
INSERT INTO `v9_menu` VALUES('1362','appcenter','1361','admin','plugin','appcenter','','0','1');
INSERT INTO `v9_menu` VALUES('1365','appcenter_detail','1362','admin','plugin','appcenter_detail','','0','0');
INSERT INTO `v9_menu` VALUES('1366','install_online','1362','admin','plugin','install_online','','0','0');
INSERT INTO `v9_menu` VALUES('1363','plugin_import','1361','admin','plugin','import','','2','1');
INSERT INTO `v9_menu` VALUES('1364','plugin_list','1361','admin','plugin','init','','1','1');
INSERT INTO `v9_menu` VALUES('1367','plugin_close','1364','admin','plugin','status','','0','0');
INSERT INTO `v9_menu` VALUES('1368','uninstall_plugin','1364','admin','plugin','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1500','listorder','822','content','content','listorder','','0','0');
INSERT INTO `v9_menu` VALUES('1507','comment','29','comment','comment_admin','init','','0','0');
INSERT INTO `v9_menu` VALUES('1508','comment_manage','821','comment','comment_admin','listinfo','','0','0');
INSERT INTO `v9_menu` VALUES('1509','comment_check','1508','comment','check','checks','','0','1');
INSERT INTO `v9_menu` VALUES('1510','comment_list','1507','comment','comment_admin','lists','','0','0');
INSERT INTO `v9_menu` VALUES('1583','comment_list','1580','comment','comment_admin','lists','','0','0');
INSERT INTO `v9_menu` VALUES('1582','comment_check','1581','comment','check','checks','','0','1');
INSERT INTO `v9_menu` VALUES('1578','comment_check','1577','comment','check','checks','','0','1');
INSERT INTO `v9_menu` VALUES('1579','comment_list','1576','comment','comment_admin','lists','','0','0');
INSERT INTO `v9_menu` VALUES('1584','announce','29','announce','admin_announce','init','s=1','0','0');
INSERT INTO `v9_menu` VALUES('1585','announce_add','1584','announce','admin_announce','add','','0','0');
INSERT INTO `v9_menu` VALUES('1586','edit_announce','1584','announce','admin_announce','edit','s=1','0','0');
INSERT INTO `v9_menu` VALUES('1587','check_announce','1584','announce','admin_announce','init','s=2','0','1');
INSERT INTO `v9_menu` VALUES('1588','overdue','1584','announce','admin_announce','init','s=3','0','1');
INSERT INTO `v9_menu` VALUES('1589','del_announce','1584','announce','admin_announce','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1590','vote','2319','vote','vote','init','','0','1');
INSERT INTO `v9_menu` VALUES('1591','add_vote','1590','vote','vote','add','','0','0');
INSERT INTO `v9_menu` VALUES('1592','edit_vote','1590','vote','vote','edit','','0','0');
INSERT INTO `v9_menu` VALUES('1593','delete_vote','1590','vote','vote','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1594','vote_setting','1590','vote','vote','setting','','0','1');
INSERT INTO `v9_menu` VALUES('1595','statistics_vote','1590','vote','vote','statistics','','0','0');
INSERT INTO `v9_menu` VALUES('1596','statistics_userlist','1590','vote','vote','statistics_userlist','','0','0');
INSERT INTO `v9_menu` VALUES('1597','create_js','1590','vote','vote','create_js','','0','1');
INSERT INTO `v9_menu` VALUES('1598','message','970','message','message','init','','99','1');
INSERT INTO `v9_menu` VALUES('1599','send_one','1598','message','message','send_one','','0','1');
INSERT INTO `v9_menu` VALUES('1600','delete_message','1598','message','message','delete','','0','0');
INSERT INTO `v9_menu` VALUES('1601','message_send','1598','message','message','message_send','','0','0');
INSERT INTO `v9_menu` VALUES('1602','message_group_manage','1598','message','message','message_group_manage','','0','0');
INSERT INTO `v9_menu` VALUES('2272','go3c_position_pad','2258','go3c','task','task','term_id=2','2','1');
INSERT INTO `v9_menu` VALUES('1922','dbmover_export','1919','dbmover','export','init','','2','1');
INSERT INTO `v9_menu` VALUES('2283','go3c_config_term','2262','go3c','config','term','','1','1');
INSERT INTO `v9_menu` VALUES('1931','dbmover_xml_cate','1923','dbmover','xml','cate','','1','1');
INSERT INTO `v9_menu` VALUES('1925','dbmover_import_channel','1921','dbmover','import','channel','','1','1');
INSERT INTO `v9_menu` VALUES('1921','dbmover_import','1919','dbmover','import','init','','1','1');
INSERT INTO `v9_menu` VALUES('2290','go3c_member_edit','2263','go3c','member','edit','','3','1');
INSERT INTO `v9_menu` VALUES('1934','dbmover_xml_export','1923','dbmover','xml','export','','4','1');
INSERT INTO `v9_menu` VALUES('2289','go3c_member_unlock','2263','go3c','member','unlock','','2','1');
INSERT INTO `v9_menu` VALUES('1929','dbmover_export_program','1922','dbmover','export','program','','2','1');
INSERT INTO `v9_menu` VALUES('1924','dbmover_clear_tags','2262','dbmover','index','init','','99','1');
INSERT INTO `v9_menu` VALUES('2284','go3c_config_column','2262','go3c','config','column','','2','1');
INSERT INTO `v9_menu` VALUES('1926','dbmover_import_program','1921','dbmover','import','program','','2','1');
INSERT INTO `v9_menu` VALUES('1927','dbmover_import_asset','1921','dbmover','import','asset','','3','1');
INSERT INTO `v9_menu` VALUES('2268','go3c_video_delete','2256','go3c','video','delete','','3','1');
INSERT INTO `v9_menu` VALUES('2264','go3c_cp','2255','go3c','cp','init','','19','0');
INSERT INTO `v9_menu` VALUES('1933','dbmover_xml_epg','1923','dbmover','xml','epg','','3','1');
INSERT INTO `v9_menu` VALUES('2265','go3c_admin','2255','go3c','admins','init','','20','1');
INSERT INTO `v9_menu` VALUES('2286','go3c_config_column_mapping','2262','go3c','config','column_mapping','','5','1');
INSERT INTO `v9_menu` VALUES('2280','go3c_publish_job','2260','go3c','publish','job','','1','1');
INSERT INTO `v9_menu` VALUES('2285','go3c_config_channel_category','2262','go3c','config','channel_category','','3','1');
INSERT INTO `v9_menu` VALUES('2274','go3c_position_pc','2258','go3c','task','task','term_id=4','4','1');
INSERT INTO `v9_menu` VALUES('1932','dbmover_xml_channel','1923','dbmover','xml','channel','','2','1');
INSERT INTO `v9_menu` VALUES('1930','dbmover_export_asset','1922','dbmover','export','asset','','3','1');
INSERT INTO `v9_menu` VALUES('1920','dbmover_clear','1919','dbmover','clear','init','','4','1');
INSERT INTO `v9_menu` VALUES('2275','go3c_verify_online','2259','go3c','verify','online','','1','1');
INSERT INTO `v9_menu` VALUES('2259','go3c_verify','2254','go3c','verify','init','','15','1');
INSERT INTO `v9_menu` VALUES('2262','go3c_config','2255','go3c','config','init','','17','1');
INSERT INTO `v9_menu` VALUES('1928','dbmover_export_channel','1922','dbmover','export','channel','','1','1');
INSERT INTO `v9_menu` VALUES('1923','dbmover_xml','1919','dbmover','xml','init','','0','1');
INSERT INTO `v9_menu` VALUES('2279','go3c_verify_category','2259','go3c','verify','category','','4','1');
INSERT INTO `v9_menu` VALUES('2269','go3c_channel_channel','2257','go3c','channel','channel','','1','1');
INSERT INTO `v9_menu` VALUES('1864','video_online','1865','video_online','video_online','init','','0','1');
INSERT INTO `v9_menu` VALUES('1865','video_index','1863','video_index','video_index','init','','0','1');
INSERT INTO `v9_menu` VALUES('2261','go3c_import','2254','go3c','import','init','','10','1');
INSERT INTO `v9_menu` VALUES('1867','channel_index','1863','channel_index','channel_index','init','','0','1');
INSERT INTO `v9_menu` VALUES('1868','channel_channel','1867','channel_channel','channel_channel','init','','0','1');
INSERT INTO `v9_menu` VALUES('1869','channel_epg','1867','channel_epg','channel_epg','init','','0','1');
INSERT INTO `v9_menu` VALUES('2277','go3c_verify_delete','2259','go3c','verify','delete','','3','1');
INSERT INTO `v9_menu` VALUES('2257','go3c_channel','2254','go3c','channel','init','','12','1');
INSERT INTO `v9_menu` VALUES('2292','go3c_admin_group','2265','admin','role','init','','2','1');
INSERT INTO `v9_menu` VALUES('2276','go3c_verify_offline','2259','go3c','verify','offline','','2','1');
INSERT INTO `v9_menu` VALUES('2291','go3c_cp_manager','2264','go3c','cp','manager','','1','1');
INSERT INTO `v9_menu` VALUES('2255','go3c_admin','0','go3c','index','inits','','11','1');
INSERT INTO `v9_menu` VALUES('2293','go3c_admin_member','2265','admin','admin_manage','init','','3','1');
INSERT INTO `v9_menu` VALUES('2260','go3c_publish','2254','go3c','publish','init','','16','1');
INSERT INTO `v9_menu` VALUES('2288','go3c_member_lock','2263','go3c','member','lock','','1','1');
INSERT INTO `v9_menu` VALUES('2256','go3c_video','2254','go3c','video','init','','11','1');
INSERT INTO `v9_menu` VALUES('2267','go3c_video_offline','2256','go3c','video','offline','','2','1');
INSERT INTO `v9_menu` VALUES('2282','go3c_import_run','2261','go3c','import','run','','1','1');
INSERT INTO `v9_menu` VALUES('2258','go3c_position','2254','go3c','position','init','','13','1');
INSERT INTO `v9_menu` VALUES('2278','go3c_task_verifyTask','2259','go3c','task','verifyTask','','7','1');
INSERT INTO `v9_menu` VALUES('2271','go3c_position_stb','2258','go3c','task','task','term_id=1','1','1');
INSERT INTO `v9_menu` VALUES('2273','go3c_position_phone','2258','go3c','task','task','term_id=3','3','1');
INSERT INTO `v9_menu` VALUES('2270','go3c_channel_epg','2257','go3c','channel','epg','','2','1');
INSERT INTO `v9_menu` VALUES('2263','go3c_member','2255','go3c','member','init','','18','0');
INSERT INTO `v9_menu` VALUES('2254','go3c_editor','0','go3c','index','init','','10','1');
INSERT INTO `v9_menu` VALUES('2281','go3c_publish_his','2260','go3c','publish','his','','2','1');
INSERT INTO `v9_menu` VALUES('2266','go3c_video_online','2256','go3c','video','online','','1','1');
INSERT INTO `v9_menu` VALUES('2294','client','2255','go3c','client','init','','98','1');
INSERT INTO `v9_menu` VALUES('2295','client_list','2294','go3c','client','client_list','','0','1');
INSERT INTO `v9_menu` VALUES('2296','verify_client_online','2259','go3c','verify','client_online','','10','1');
INSERT INTO `v9_menu` VALUES('2297','verify_client_delete','2259','go3c','verify','client_delete','','11','1');
INSERT INTO `v9_menu` VALUES('2298','pic_server','30','go3c','server','init','','99','1');
INSERT INTO `v9_menu` VALUES('2299','go3c_video_category','2256','go3c','video','category','','4','1');
INSERT INTO `v9_menu` VALUES('2300','go3c_crontab_import','2301','go3c','crontab','import','','0','1');
INSERT INTO `v9_menu` VALUES('2301','go3c_crontab_init','2255','go3c','crontab','init','','0','1');
INSERT INTO `v9_menu` VALUES('2302','go3c_crontab_publish','2301','go3c','crontab','publish','','0','1');
INSERT INTO `v9_menu` VALUES('2303','go3c_crontab_offline','2301','go3c','crontab','offline','','0','1');
INSERT INTO `v9_menu` VALUES('2319','pc_vote','2318','go3c','pc','init','','0','1');
INSERT INTO `v9_menu` VALUES('2323','tvuser_list','2322','go3c','tvuser','init','','1','1');
INSERT INTO `v9_menu` VALUES('2320','hot_actor','2262','go3c','hot_actor','init','','0','1');
INSERT INTO `v9_menu` VALUES('2321','hot_tag','2262','go3c','hot_tag','init','','0','1');
INSERT INTO `v9_menu` VALUES('2325','tvuser_channel_list','2322','go3c','tvuser','channel_list','','3','1');
INSERT INTO `v9_menu` VALUES('2326','tvuser_video_list','2322','go3c','tvuser','video_list','','4','1');
INSERT INTO `v9_menu` VALUES('2327','tvuser_lock_list','2322','go3c','tvuser','lock_list','','0','1');
INSERT INTO `v9_menu` VALUES('2324','tvuser_active_list','2322','go3c','tvuser','active_list','','2','1');
INSERT INTO `v9_menu` VALUES('2318','pc_index','0','go3c','pc','init','','20','1');
INSERT INTO `v9_menu` VALUES('2322','tvuser','2255','go3c','tvuser','init','','0','1');
INSERT INTO `v9_menu` VALUES('2317','adverts_list_1','2262','go3c','adverts','adverts_list','','8','1');
INSERT INTO `v9_menu` VALUES('2328','game','2318','go3c','game','init','','0','1');
INSERT INTO `v9_menu` VALUES('2329','game_type_list','2328','go3c','game','type_list','','0','1');
INSERT INTO `v9_menu` VALUES('2330','game_list','2328','go3c','game','game_list','','0','1');
INSERT INTO `v9_menu` VALUES('2332','go3c_task_verifyAdvert','2259','go3c','task','verifyAdvert','','8','1');
INSERT INTO `v9_menu` VALUES('2333','go3c_task_advert_init','2254','go3c','task','init','','14','1');
INSERT INTO `v9_menu` VALUES('2334','go3c_task_advert_stb','2333','go3c','task','advert','term_id=1','0','1');
INSERT INTO `v9_menu` VALUES('2335','go3c_task_advert_pc','2333','go3c','task','advert','term_id=4','0','1');
INSERT INTO `v9_menu` VALUES('2336','admins_add','2265','admin','admin_manage','add','','99','1');
INSERT INTO `v9_menu` VALUES('2337','video_source','2262','go3c','video','video_source_list','','4','1');

