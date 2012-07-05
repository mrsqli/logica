<?php

/**
 * Create the main table
 *
 * @package migrations
 */

class Groups extends Akrabat_Db_Schema_AbstractChange
{
    public function up(){
        $sql = "CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `groups` (`id`,`name`,`parent_id`)
VALUES
	(1, 'administrators', 0),
	(2, 'guests', 0),
	(3, 'members', 0);
";
        $this->_db->query($sql);
    }
    
    public function down(){
        $sql = "DROP TABLE IF EXISTS `groups`";
        $this->_db->query($sql);
    }
}