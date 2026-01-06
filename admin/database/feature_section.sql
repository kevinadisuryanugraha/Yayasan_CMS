-- Feature Section CMS Database
-- Phase 3: Feature Section (4 Cards)

CREATE TABLE IF NOT EXISTS `feature_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  `icon` varchar(255) DEFAULT NULL,
  `link_text` varchar(50) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert 4 sample feature cards
INSERT INTO `feature_section` (`title`, `description`, `icon`, `link_text`, `link_url`, `order_position`, `is_active`) VALUES 
('Quran Studies', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'assets/images/feature/01.png', 'Sponsor Now!', '#', 1, 1),
('Islamic Classes', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'assets/images/feature/02.png', 'Donate Now!', '#', 2, 1),
('Islamic Awareness', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'assets/images/feature/03.png', 'Join Us!', '#', 3, 1),
('Islamic Services', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'assets/images/feature/04.png', 'Get Involved!', '#', 4, 1);
