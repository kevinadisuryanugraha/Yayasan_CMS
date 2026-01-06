-- Website Settings Database Schema
-- Single-row configuration table for centralized site settings

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  
  -- Site Information
  `site_name` varchar(255) DEFAULT 'Hafsa Islamic Center',
  `site_tagline` varchar(255) DEFAULT 'Path to Harmony and Faith',
  `site_description` text,
  
  -- Contact Information
  `phone_primary` varchar(20) DEFAULT '+88019 339 702 520',
  `phone_secondary` varchar(20) DEFAULT NULL,
  `email_primary` varchar(100) DEFAULT 'admin@hafsa.com',
  `email_secondary` varchar(100) DEFAULT NULL,
  `address` text,
  
  -- Social Media
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  
  -- Branding
  `logo_light` varchar(255) DEFAULT 'assets/images/logo/01.png',
  `logo_dark` varchar(255) DEFAULT 'assets/images/logo/01.png',
  `favicon` varchar(255) DEFAULT NULL,
  
  -- Business Information
  `working_hours` text,
  `map_embed_url` text,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  
  -- Footer
  `footer_text` text,
  `copyright_text` varchar(255) DEFAULT '©2024 Hafsa - Islamic Center',
  
  -- Timestamps
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  CONSTRAINT `chk_single_row` CHECK (`id` = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings with values for TEXT fields
INSERT INTO `site_settings` (
  `id`, 
  `address`, 
  `site_description`, 
  `footer_text`, 
  `working_hours`
) VALUES (
  1,
  '30 North West New York 240',
  'Hafsa Islamic Center - Path to Harmony and Faith',
  'Hafsa is a nonprofit organization supported by community leaders',
  NULL
) ON DUPLICATE KEY UPDATE `id` = 1;
