-- Service Section CMS Database - UPDATED
-- Phase 4: Service Section (3 Cards with Dual Images + Link URL)

CREATE TABLE IF NOT EXISTS `service_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text,
  `main_image` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert 3 sample service cards
INSERT INTO `service_section` (`category`, `title`, `description`, `main_image`, `icon`, `link_url`, `order_position`, `is_active`) VALUES 
('Building Upgrades', 'Mosque Development', 'Lorem ipsum, dolor sit amet sectetur adipisicing elit. Vel dicta beatae del voluptas apelas de.', 'assets/images/service/01.jpg', 'assets/images/service/01.png', '#mosque-development', 1, 1),
('Help Poor', 'Charity And Donation', 'Lorem ipsum, dolor sit amet sectetur adipisicing elit. Vel dicta beatae del voluptas apelas de.', 'assets/images/service/02.jpg', 'assets/images/service/02.png', '#charity', 2, 1),
('Donate & Help', 'Poor Woman Marriage', 'Lorem ipsum, dolor sit amet sectetur adipisicing elit. Vel dicta beatae del voluptas apelas de.', 'assets/images/service/03.jpg', 'assets/images/service/03.png', '#marriage-support', 3, 1);

-- Add link_url column if table already exists (migration)
ALTER TABLE `service_section` ADD COLUMN `link_url` varchar(255) DEFAULT NULL AFTER `icon`;
