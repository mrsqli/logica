<?php

/**
 * Create the main table
 *
 * @package migrations
 */

class Flippers extends Akrabat_Db_Schema_AbstractChange
{
    public function up(){
        $sql = "CREATE TABLE `flippers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `flag_id` int(11) unsigned NOT NULL,
  `privilege_id` int(11) unsigned NOT NULL,
  `allow` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_group_id` (`group_id`),
  KEY `idx_flag_id` (`flag_id`),
  KEY `idx_privilege_id` (`privilege_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;";
        $this->_db->query($sql);

        $sql = "INSERT INTO `flippers` (`id`,`group_id`,`flag_id`,`privilege_id`,`allow`)
VALUES
	(1, 3, 8, 26, 1),
	(2, 3, 8, 27, 1),
	(3, 2, 8, 26, 1),
	(4, 2, 8, 27, 1);
";
        $this->_db->query($sql);
    }
    
    public function down(){
        $sql = "DROP TABLE IF EXISTS `flippers`";
        $this->_db->query($sql);
    }
}