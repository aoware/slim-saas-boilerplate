CREATE TABLE `email_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` int(11) NOT NULL DEFAULT '0',
  `sequence` int(11) NOT NULL DEFAULT '0',
  `mime_type` varchar(128) NOT NULL DEFAULT '',
  `filename` varchar(128) NOT NULL DEFAULT '',
  `encoding` varchar(128) NOT NULL DEFAULT '',
  `content` mediumblob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_email_attachments_email_id_sequence` (`email_id`,`sequence`),
  KEY `IDX_email_attachments_email_id` (`email_id`),
  CONSTRAINT `email_attachments_ibfk_1` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='save attachments for a given email'