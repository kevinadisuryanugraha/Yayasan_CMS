-- About Section CMS Database
-- Phase 2: About Section

CREATE TABLE IF NOT EXISTS `about_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subtitle` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `sub_heading` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `about_section` (`subtitle`, `title`, `sub_heading`, `description`, `image`, `button_text`, `button_link`, `is_active`) VALUES 
('About Our History', 'Islamic Center For Muslims To Achieve Spiritual Goals', 'Our Promise To Uphold The Trust Placed.', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi molestias culpa reprehenderit delectus, ullam harum, voluptatum numquam ati nesciunt odit quis corrupti magni quam consequatur sint ipsum tecto exercitationem, illo quisquam. Reprehenderit ut placeat cum adantium nam magnam blanditiis sequi modi! Nesciunt, repudiandae eos eniam quod maxime corrupti eligendi ea in animi.', 'assets/images/about/02.png', 'Ask About Islam', '#', 1);
