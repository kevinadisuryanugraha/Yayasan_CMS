-- Campaign Section CMS Database
-- Phase 5: Program/Campaign Section (Progress Bars + Donation Tracking)

-- Table 1: Main Campaign (Single-entry, full-width progress bar)
CREATE TABLE IF NOT EXISTS `campaign_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subtitle` varchar(100) DEFAULT 'Urgent Campaign',
  `title` varchar(255) NOT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `amount_raised` decimal(10,2) DEFAULT 0,
  `goal_amount` decimal(10,2) NOT NULL,
  `button_text` varchar(50) DEFAULT 'Donate Now',
  `button_link` varchar(255) DEFAULT '#',
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default main campaign
INSERT INTO `campaign_main` (`subtitle`, `title`, `background_image`, `amount_raised`, `goal_amount`, `button_text`, `button_link`, `is_active`) VALUES 
('Urgent Campaign', 'Free And Complete Guide To All Muslims', 'assets/images/program/bg.jpg', 24000.00, 34900.00, 'Donate Now', '#donate', 1);

-- Table 2: Campaign Sidebar (Single-entry, donation call-to-action)
CREATE TABLE IF NOT EXISTS `campaign_sidebar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT 'Help The Poor',
  `headline` varchar(255) DEFAULT 'Donations For The Nobel Causes',
  `description` text,
  `button_text` varchar(50) DEFAULT 'See All Causes',
  `button_link` varchar(255) DEFAULT '#',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default sidebar
INSERT INTO `campaign_sidebar` (`title`, `headline`, `description`, `button_text`, `button_link`) VALUES 
('Help The Poor', 'Donations For The Nobel Causes', 'Give the best quality of security systems and facility of latest technlogy for the people get awesome.', 'See All Causes', '#causes');

-- Table 3: Campaign Programs (Multi-entry, slider cards)
CREATE TABLE IF NOT EXISTS `campaign_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `amount_raised` decimal(10,2) DEFAULT 0,
  `goal_amount` decimal(10,2) NOT NULL,
  `link_url` varchar(255) DEFAULT '#',
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert 2 sample program cards
INSERT INTO `campaign_programs` (`image`, `category`, `title`, `amount_raised`, `goal_amount`, `link_url`, `order_position`, `is_active`) VALUES 
('assets/images/program/02.jpg', 'food distribution', 'American Muslim: Choosing Remain Still This Ramadan', 24000.00, 34900.00, '#program1', 1, 1),
('assets/images/program/03.jpg', 'food distribution', 'How to Teach The Kids Ramadan Isn\'t About Food', 24000.00, 34900.00, '#program2', 2, 1);
