-- Service Header CMS (Section Title & Subtitle)
-- Single-entry table for Service Section header

CREATE TABLE IF NOT EXISTS `service_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subtitle` varchar(100) DEFAULT 'Islamic Center Services',
  `title` varchar(255) DEFAULT 'Ethical And Moral Beliefs That Guides To The Straight Path!',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default header
INSERT INTO `service_header` (`subtitle`, `title`) VALUES 
('Islamic Center Services', 'Ethical And Moral Beliefs That Guides To The Straight Path!');
