CREATE TABLE IF NOT EXISTS `about_hero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `badge_text` varchar(255) DEFAULT 'Yayasan Indonesia Bijak Bestari',
  `badge_icon` varchar(50) DEFAULT 'icofont-heart-alt',
  `title` varchar(255) DEFAULT 'Tentang Kami',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `about_hero` (`badge_text`, `badge_icon`, `title`) 
SELECT 'Yayasan Indonesia Bijak Bestari', 'icofont-heart-alt', 'Tentang Kami'
WHERE NOT EXISTS (SELECT * FROM `about_hero`);
