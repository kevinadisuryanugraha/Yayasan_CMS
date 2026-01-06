-- Events Section Database Schema
-- Two tables: events_header (section header) and events (event items)

-- Table 1: Events Section Header
CREATE TABLE IF NOT EXISTS `events_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subtitle` varchar(150) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default header
INSERT INTO `events_header` (`id`, `subtitle`, `title`) VALUES
(1, 'Upcoming Events', 'Ethical And Moral Beliefs That Guides To The Straight Path!');

-- Table 2: Events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `countdown_enabled` tinyint(1) DEFAULT 0,
  `countdown_date` datetime DEFAULT NULL,
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample events (1 featured + 3 regular)
INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `location`, `image`, `is_featured`, `countdown_enabled`, `countdown_date`, `order_position`, `is_active`) VALUES
(1, 'Helping Hands For Poor People Marriage Event', 'Join us for a special charity event supporting those in need.', '2024-12-24', '10:00:00', 'New York AK United States', 'assets/images/event/01.jpg', 1, 1, '2024-12-24 10:00:00', 1, 1),
(2, 'If Islam Teaches Peace, Why Are there Radical Muslims?', 'Educational seminar discussing common misconceptions.', '2024-12-24', '14:00:00', 'New York AK United States', 'assets/images/event/02.jpg', 0, 0, NULL, 2, 1),
(3, 'American Muslim: Choosing Remain Still This Ramadan', 'Community gathering during the holy month.', '2024-12-24', '18:00:00', 'New York AK United States', 'assets/images/event/03.jpg', 0, 0, NULL, 3, 1),
(4, 'Monthly Community Iftar Dinner', 'Breaking fast together as a community.', '2024-12-24', '19:30:00', 'New York AK United States', 'assets/images/event/04.jpg', 0, 0, NULL, 4, 1);
