CREATE TABLE `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_online_id` char(32) NOT NULL,
  `recipient_email` varchar(255) NOT NULL DEFAULT '',
  `recipient_name` varchar(64) NOT NULL,
  `creation_date` datetime NOT NULL,
  `trigger_date` datetime NOT NULL COMMENT 'Different than creation_date when required to send in the future',
  `sent_date` datetime DEFAULT NULL COMMENT 'date when the communication is sent to the SMTP server or the SMS gateway',
  `status` enum('awaiting','processing','sent','error') NOT NULL COMMENT 'Communication status. Possible value are ''awaiting'', ''processing'', ''sent'' and ''error''',
  `status_description` varchar(256) DEFAULT NULL COMMENT 'status description. Should only be populated when status = ''error''',
  `subject` varchar(256) NOT NULL COMMENT 'email subject',
  `content` text NOT NULL COMMENT 'rendered version ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_emails_view_online_id` (`view_online_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Record all emails sent from the system'