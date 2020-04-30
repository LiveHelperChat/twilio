CREATE TABLE `lhc_twilio_chat` (  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `chat_id` bigint(20) unsigned NOT NULL, `phone` varchar(35) NOT NULL, `ctime` int(11) NOT NULL, `utime` int(11) NOT NULL, `tphone_id` int(11) NOT NULL, PRIMARY KEY (`id`),  KEY `phone_utime` (`phone`,`utime`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `lhc_twilio_phone` (  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `phone` varchar(35) NOT NULL, `base_phone` varchar(35) NOT NULL, `originator` varchar(35) NOT NULL, `account_sid` varchar(35), `auth_token` varchar(35), `dep_id` int(11) unsigned NOT NULL, `chat_timeout` int(11) unsigned NOT NULL, `responder_timeout` int(11) unsigned NOT NULL, PRIMARY KEY (`id`), KEY `phone` (`phone`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
