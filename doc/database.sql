CREATE DATABASE IF NOT EXISTS `beauty_dht`;

CREATE TABLE IF NOT EXISTS `infohash`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`hash` varchar(100) NOT NULL DEFAULT '',
	`name` varchar(500) NOT NULL DEFAULT '' COMMENT '种子文件名',
	`total_size` int(15) NOT NULL DEFAULT 0,
	`creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`),
	UNIQUE KEY `infohash_hash`(`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `filelist`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`infohash_id` int(11) NOT NULL DEFAULT 0,
	`filepath` varchar(300) NOT NULL DEFAULT '',
	`length` varchar(30) NOT NULL DEFAULT '' COMMENT '文件大小',
	PRIMARY KEY (`id`),
	INDEX `filelist_infohash_id`(`infohash_id`),
	INDEX `filelist_filepath`(`filepath`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;