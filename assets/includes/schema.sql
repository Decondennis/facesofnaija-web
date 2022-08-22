-- ---------------------------------------------------------
--
-- Table structure for table : `Wo_Community_Request`
--
-- ---------------------------------------------------------

CREATE TABLE `Wo_Community_Request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(50) NOT NULL DEFAULT '',
  `state` varchar(50) NOT NULL DEFAULT '',
  `lga` varchar(50) NOT NULL DEFAULT '',
  `about` varchar(1000) NOT NULL DEFAULT '',
  `privacy` int(2) NOT NULL DEFAULT '1',
  `requested_at` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`)
);