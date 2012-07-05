<?php

/**
 * Create the main table
 *
 * @package migrations
 */

class Flags extends Akrabat_Db_Schema_AbstractChange
{
    public function up(){
        $sql = "CREATE TABLE `flags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active_on_dev` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active_on_prod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_name` (`name`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `flags` (`id`,`name`,`description`,`active_on_dev`,`active_on_prod`)
VALUES
	(1, 'backoffice-flags', 'Allows user to manage the flags', 1, 0),
	(2, 'backoffice-groups', 'Allows user to manage the user groups', 1, 0),
	(3, 'backoffice-index', 'Default entry point in the application', 1, 0),
	(4, 'backoffice-privileges', 'Allows the users to perform CRUD operations on privileges', 1, 0),
	(5, 'backoffice-profile', 'Allows user to manage their profile data', 1, 0),
	(6, 'backoffice-system', 'Allow the admins to manage critical info, users, groups, permissions, etc.', 1, 0),
	(7, 'backoffice-users', 'Allows the users to perform CRUD operations on other users', 1, 0),
	(8, 'frontend-index', 'Default entry point in the application', 1, 0),
	(9, 'backoffice-testing', 'Some testing permissions', 1, 0),
	(10, 'frontend-testing', 'Some testing permissions', 1, 0);
";
        $this->_db->query($sql);
    }
    
    public function down(){
        $sql = "DROP TABLE IF EXISTS `flags`";
        $this->_db->query($sql);
    }
}