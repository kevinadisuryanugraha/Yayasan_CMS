-- Quote Section Database Schema
-- Single table for hadith/quotes with Swiper slider support

CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_text` text NOT NULL,
  `author` varchar(150) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample quote/hadith
INSERT INTO `quotes` (`id`, `quote_text`, `author`, `source`, `order_position`, `is_active`) VALUES
(1, 'It is Better For Any Of You To Carry A Load Of Firewood On His Own Back Than To Beg From Someone Else', 'Hazrat Mohammod (s)', 'Riyadh-Us-Saleheen, Chapter 59, hadith 540', 1, 1),
(2, 'The best among you are those who have the best manners and character', 'Prophet Muhammad (SAW)', 'Sahih Bukhari, Book 56, Hadith 759', 2, 1),
(3, 'Seeking knowledge is an obligation upon every Muslim', 'Prophet Muhammad (SAW)', 'Sunan Ibn Majah, Book 1, Hadith 224', 3, 1);
