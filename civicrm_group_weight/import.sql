  CREATE TABLE IF NOT EXISTS `mtl_civicrm_group_weight` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Default MySQL primary key',
    `entity_id` int(10) unsigned NOT NULL COMMENT 'Group Id',
    `weight` int(10) unsigned DEFAULT NULL COMMENT 'weight Value',
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_entity_id` (`entity_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  
  
  CREATE TABLE IF NOT EXISTS `mtl_civicrm_group_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `terms` varchar(255) NOT NULL,
  `send_email` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 


CREATE TABLE IF NOT EXISTS `mtl_civicrm_group_sent_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `date_sent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `mtl_civicrm_group_weight_daily_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;