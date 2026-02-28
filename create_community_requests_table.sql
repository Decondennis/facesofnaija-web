-- Create table for community creation requests
CREATE TABLE IF NOT EXISTS `Wo_Community_Requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `community_name` varchar(100) NOT NULL,
  `community_title` varchar(100) NOT NULL,
  `about` text,
  `category` int(11) DEFAULT '1',
  `sub_category` varchar(250) DEFAULT '',
  `privacy` int(11) DEFAULT '1',
  `reason` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `time` int(11) NOT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` int(11) DEFAULT NULL,
  `admin_notes` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
