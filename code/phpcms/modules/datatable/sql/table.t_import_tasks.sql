-- 
-- Editor SQL for DB table t_import_tasks
-- Created by http://editor.datatables.net/generator
-- 

CREATE TABLE `t_import_tasks` (
	`id` int(10) NOT NULL auto_increment,
	`f_task_id` varchar(255),
	`f_task_name` varchar(255),
	`f_data_id` varchar(255),
	`f_template_id` varchar(255),
	`f_peroid` varchar(255),
	`f_time` varchar(255),
	`f_auto` varchar(255),
	`f_success` varchar(255),
	`f_failed` varchar(255),
	`f_pre_scripts` varchar(255),
	`f_scripts` varchar(255),
	`f_post_scripts` varchar(255),
	`f_mid` varchar(255),
	PRIMARY KEY( `id` )
);