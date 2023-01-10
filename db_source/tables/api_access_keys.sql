CREATE TABLE `api_access_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT 'Unique API key used by the integrator',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT 'Password associated with this key',
  `name` varchar(64) NOT NULL COMMENT 'Name/Description of the integrator',
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amended_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_api_access_keys_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Access Key used by Integrator (Bol) and others. It is 1 key per environment, per integrator'