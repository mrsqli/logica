<?php

/**
 * Create the main table
 *
 * @package migrations
 */

class BackofficeUsersGroups extends Akrabat_Db_Schema_AbstractChange
{
    public function up(){
        $sql = "CREATE TABLE `backoffice_users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_group` (`group_id`,`user_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `backoffice_users_groups` (`id`,`group_id`,`user_id`)
VALUES
	(1, 1, 1);
";
        $this->_db->query($sql);
    }
    
    public function down(){
        $sql = "DROP TABLE IF EXISTS `backoffice_users_groups`";
        $this->_db->query($sql);
    }
}