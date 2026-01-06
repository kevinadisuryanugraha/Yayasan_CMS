-- Hero Section CMS Database
-- Phase 1: Hero/Banner Section

CREATE TABLE IF NOT EXISTS `hero_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `hero_section` (`title`, `subtitle`, `description`, `image`, `button_text`, `button_link`, `order_position`, `is_active`) VALUES 
('And Allah Invites To The Home Of Peace', NULL, 'The most beloved actions to Allah are those performed consistently, even if they are few', 'assets/images/banner/01.png', 'Donate Now', '#donate', 0, 1);
