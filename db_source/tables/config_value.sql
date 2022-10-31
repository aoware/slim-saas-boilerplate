CREATE TABLE `config_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_definition_id` int(11) NOT NULL,
  `profile` varchar(16) NOT NULL DEFAULT '',
  `effective_start_date` datetime DEFAULT NULL,
  `effective_end_date` datetime DEFAULT NULL,
  `key` varchar(16) NOT NULL DEFAULT '' COMMENT 'Only required for ENGINE array',
  `value` text,
  PRIMARY KEY (`id`),
  KEY `IDX_config_value_config_definition_id` (`config_definition_id`),
  CONSTRAINT `CONST_config_value_config_definition` FOREIGN KEY (`config_definition_id`) REFERENCES `config_definition` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3