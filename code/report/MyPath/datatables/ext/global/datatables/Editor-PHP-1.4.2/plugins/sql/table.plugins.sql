-- 
-- Editor SQL for DB table plugins
-- Created by http://editor.datatables.net/generator
-- 

CREATE TABLE `plugins` (
	`id` int(10) NOT NULL auto_increment,
	`text` varchar(255),
	`password` varchar(255),
	`textarea` text,
	`selector` varchar(255),
	`checkbox` varchar(255),
	`radio` varchar(255),
	`date` date,
	`readonly` varchar(255),
	PRIMARY KEY( `id` )
);