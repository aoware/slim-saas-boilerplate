CREATE TABLE `communication_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `communication_id` int(11) NOT NULL DEFAULT '0',
  `event` char(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `call_datetime` datetime NOT NULL,
  `event_datetime` datetime NOT NULL,
  `event_id` char(64) NOT NULL DEFAULT '',
  `ip_address` char(40) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `payload` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_communication_tracking_event_id` (`event_id`),
  KEY `IDX_communication_tracking_communication_id` (`communication_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Record all data provided by sendgrid in relation to an existing email'