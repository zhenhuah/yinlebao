-- 
-- Editor SQL for DB table t_import_log
-- Created by http://editor.datatables.net/generator
-- 

CREATE TABLE `t_import_log` (
	`id` int(10) NOT NULL auto_increment,
	`f_task_id` varchar(255),
	`f_starttime` varchar(255),
	`f_duration` varchar(255),
	`f_responser` varchar(255),
	`f_success` varchar(255),
	`f_failed` varchar(255),
	PRIMARY KEY( `id` )
);