-- Faith Section Database Schema
-- Two tables: faith_header (section header) and faith_pillars (5 pillars with dual images)

-- Table 1: Faith Section Header
CREATE TABLE IF NOT EXISTS `faith_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subtitle` varchar(150) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default header
INSERT INTO `faith_header` (`id`, `subtitle`, `title`) VALUES
(1, 'The Pillars of Islam', 'Ethical And Moral Beliefs That Guides To The Straight Path!');

-- Table 2: Faith Pillars (5 pillars with dual images)
CREATE TABLE IF NOT EXISTS `faith_pillars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pillar_name` varchar(100) NOT NULL,
  `subtitle` varchar(100) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `tab_icon` varchar(255) DEFAULT NULL,
  `description` text,
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert 5 Islamic Pillars with sample data
INSERT INTO `faith_pillars` (`id`, `pillar_name`, `subtitle`, `main_image`, `tab_icon`, `description`, `order_position`, `is_active`) VALUES
(1, 'Shahadah', 'Faith', 'assets/images/faith/01.png', 'assets/images/faith/faith-icons/01.png', 
 'The Shahadah, is an Islamic creed, one of the Five Pillars of Islam and part of the Adhan. It reads: \"I bear witness that there is no deity but God, and I bear witness that Muhammad is the messenger of God.\"', 
 1, 1),

(2, 'Salah', 'Prayer', 'assets/images/faith/02.png', 'assets/images/faith/faith-icons/02.png',
 'Each Muslim should pray five times a day: in the morning, at noon, in the afternoon, after sunset, and early at night. These prayers can be said anywhere, prayers that are said in company of others are better than those said alone.',
 2, 1),

(3, 'Sawm', 'Fasting', 'assets/images/faith/03.png', 'assets/images/faith/faith-icons/03.png',
 'During the holy month of Ramadan, Muslims fast from dawn to sunset. This practice teaches self-discipline, empathy for the less fortunate, and spiritual reflection. It is a time of increased devotion and worship.',
 3, 1),

(4, 'Zakat', 'Almsgiving', 'assets/images/faith/04.png', 'assets/images/faith/faith-icons/04.png',
 'Zakat is the giving of a fixed portion of accumulated wealth to help the poor and needy. This charitable practice purifies wealth and redistributes resources in the community, fostering social equality and compassion.',
 4, 1),

(5, 'Hajj', 'Pilgrimage', 'assets/images/faith/05.png', 'assets/images/faith/faith-icons/05.png',
 'Every Muslim who is financially and physically able must make the pilgrimage to Mecca at least once in their lifetime. The Hajj occurs during the Islamic month of Dhu al-Hijjah and commemorates the actions of Prophet Ibrahim and his family.',
 5, 1);
