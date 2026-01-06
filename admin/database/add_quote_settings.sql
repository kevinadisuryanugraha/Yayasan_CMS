-- Add quote_settings table for background image
CREATE TABLE IF NOT EXISTS `quote_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `background_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default entry
INSERT INTO `quote_settings` (`id`, `background_image`) VALUES (1, NULL);
