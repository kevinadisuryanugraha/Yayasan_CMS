-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Jan 2026 pada 16.09
-- Versi server: 8.4.3
-- Versi PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yayasan_cms`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_features`
--

CREATE TABLE `about_features` (
  `id` int NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_hero`
--

CREATE TABLE `about_hero` (
  `id` int NOT NULL,
  `badge_text` varchar(255) DEFAULT 'Yayasan Indonesia Bijak Bestari',
  `badge_icon` varchar(50) DEFAULT 'icofont-heart-alt',
  `title` varchar(255) DEFAULT 'Tentang Kami',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_hero`
--

INSERT INTO `about_hero` (`id`, `badge_text`, `badge_icon`, `title`, `updated_at`) VALUES
(1, 'Yayasan Indonesia Bijak Bestari', 'icofont-heart-alt', 'Tentang Kami', '2026-01-09 06:45:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_section`
--

CREATE TABLE `about_section` (
  `id` int NOT NULL,
  `subtitle` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `sub_heading` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stat_number` varchar(50) DEFAULT NULL,
  `stat_text` varchar(100) DEFAULT NULL,
  `stat_icon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_section`
--

INSERT INTO `about_section` (`id`, `subtitle`, `title`, `sub_heading`, `description`, `image`, `button_text`, `button_link`, `is_active`, `created_at`, `updated_at`, `stat_number`, `stat_text`, `stat_icon`) VALUES
(1, 'Tentang Islam Kini', 'Islamic Center For Muslims To Achieve Spiritual Goals', 'Our Promise To Uphold The Trust Placed.', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi molestias culpa reprehenderit delectus, ullam harum, voluptatum numquam ati nesciunt odit quis corrupti magni quam consequatur sint ipsum tecto exercitationem, illo quisquam. Reprehenderit ut placeat cum adantium nam magnam blanditiis sequi modi! Nesciunt, repudiandae eos eniam quod maxime corrupti eligendi ea in animi.', 'uploads/about/about_1767500646_6959eb66dfcd7.jpg', 'Tanya Tentang Islam', 'https://github.com/', 0, '2026-01-04 03:49:25', '2026-01-06 03:50:00', NULL, NULL, NULL),
(3, 'Tentang Sejarah Yayasan Kami', 'Yayasan yang penuh dengan kebersamaan dan keharmonisan antara satu sama lain.', 'Membangun Ilmu dan Karakter yang beriman.', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi molestias culpa reprehenderit delectus, ullam harum, voluptatum numquam ati nesciunt odit quis corrupti magni quam consequatur sint ipsum tecto exercitationem, illo quisquam. Reprehenderit ut placeat cum adantium nam magnam blanditiis sequi modi! Nesciunt, repudiandae eos eniam quod maxime corrupti eligendi ea in animi.', 'uploads/about/about_1767670860_695c844c1db40.png', 'Gabung Bersama Kami', 'https://github.com/', 1, '2026-01-06 03:41:00', '2026-01-06 03:49:54', NULL, NULL, NULL),
(4, 'About Our History', 'Islamic Center For Muslims To Achieve Spiritual Goals', 'Our Promise To Uphold The Trust Placed.', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi molestias culpa reprehenderit delectus, ullam harum, voluptatum numquam ati nesciunt odit quis corrupti magni quam consequatur sint ipsum tecto exercitationem, illo quisquam. Reprehenderit ut placeat cum adantium nam magnam blanditiis sequi modi! Nesciunt, repudiandae eos eniam quod maxime corrupti eligendi ea in animi.', 'assets/images/about/02.png', 'Ask About Islam', '#', 1, '2026-01-06 08:39:51', '2026-01-06 08:39:51', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `appearance_settings`
--

CREATE TABLE `appearance_settings` (
  `id` int NOT NULL DEFAULT '1',
  `primary_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '#2E7D32',
  `secondary_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '#1565C0',
  `accent_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '#FF9800',
  `font_family` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Poppins',
  `button_style` enum('rounded','square','pill') COLLATE utf8mb4_unicode_ci DEFAULT 'rounded',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `appearance_settings`
--

INSERT INTO `appearance_settings` (`id`, `primary_color`, `secondary_color`, `accent_color`, `font_family`, `button_style`, `updated_at`) VALUES
(1, '#2e7d32', '#1565c0', '#ff9800', 'Poppins', 'rounded', '2026-01-08 16:09:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `campaign_main`
--

CREATE TABLE `campaign_main` (
  `id` int NOT NULL,
  `subtitle` varchar(100) DEFAULT 'Urgent Campaign',
  `title` varchar(255) NOT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `amount_raised` decimal(10,2) DEFAULT '0.00',
  `goal_amount` decimal(10,2) NOT NULL,
  `button_text` varchar(50) DEFAULT 'Donate Now',
  `button_link` varchar(255) DEFAULT '#',
  `is_active` tinyint(1) DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `campaign_main`
--

INSERT INTO `campaign_main` (`id`, `subtitle`, `title`, `background_image`, `amount_raised`, `goal_amount`, `button_text`, `button_link`, `is_active`, `updated_at`) VALUES
(1, 'Keadaan Darurat', 'Gratis dan Komplit untuk seluruh muslim', 'uploads/campaigns/campaign_bg_1767507506.jpg', 20000.00, 34900.00, 'Donasi Sekarang', 'https://htmlrev.com/', 1, '2026-01-04 06:20:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `campaign_programs`
--

CREATE TABLE `campaign_programs` (
  `id` int NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `amount_raised` decimal(10,2) DEFAULT '0.00',
  `goal_amount` decimal(10,2) NOT NULL,
  `link_url` varchar(255) DEFAULT '#',
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `campaign_programs`
--

INSERT INTO `campaign_programs` (`id`, `image`, `category`, `title`, `amount_raised`, `goal_amount`, `link_url`, `order_position`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'uploads/campaigns/program_1767677877_695c9fb588172.jpg', 'food distribution', 'American Muslim: Choosing Remain Still This Ramadan', 31000.00, 34900.00, 'https://www.brandcrowd.com/maker/logo/digital-tech-software-837946?text=HorizonFuture%20Solutions&colorPalette=grayscale&isVariation=True', 1, 1, '2026-01-04 06:02:22', '2026-01-06 05:38:28'),
(2, 'uploads/campaigns/program_1767510295_695a11170aca1.png', 'food distribution', 'How to Teach The Kids Ramadan Isn\'t About Food', 24000.00, 34900.00, '#program2', 2, 1, '2026-01-04 06:02:22', '2026-01-04 07:04:55'),
(3, 'uploads/campaigns/program_1767509886_695a0f7e880bc.jpg', 'Peningkatan Bangunan', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, quaerat.', 100000.00, 800000.00, 'https://www.brandcrowd.com/maker/logo/digital-tech-software-837946?text=HorizonFuture%20Solutions&colorPalette=grayscale&isVariation=True', 3, 1, '2026-01-04 06:58:06', '2026-01-04 06:58:06'),
(4, 'uploads/campaigns/program_1767518372_695a30a4eb422.jpg', 'Donate & Help', 'lorem ipusisdisi isancianc iscnianciac iscisdcnsdnisndcisndc iscdismcosdmc.', 100000.00, 200000.00, '#program1', 4, 1, '2026-01-04 09:19:32', '2026-01-04 09:19:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `campaign_sidebar`
--

CREATE TABLE `campaign_sidebar` (
  `id` int NOT NULL,
  `title` varchar(100) DEFAULT 'Help The Poor',
  `headline` varchar(255) DEFAULT 'Donations For The Nobel Causes',
  `background_image` varchar(255) DEFAULT NULL,
  `description` text,
  `button_text` varchar(50) DEFAULT 'See All Causes',
  `button_link` varchar(255) DEFAULT '#',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `campaign_sidebar`
--

INSERT INTO `campaign_sidebar` (`id`, `title`, `headline`, `background_image`, `description`, `button_text`, `button_link`, `updated_at`) VALUES
(1, 'Help The Poor', 'Donations For The Nobel Causes', 'uploads/campaigns/sidebar_bg_1767675923_695c98135c702.jpg', 'Give the best quality of security systems and facility of latest technlogy for the people get awesome.', 'See All Causes', '#causes', '2026-01-06 05:05:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cms_settings`
--

CREATE TABLE `cms_settings` (
  `id` int NOT NULL,
  `section` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(100) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `cms_settings`
--

INSERT INTO `cms_settings` (`id`, `section`, `title`, `subtitle`, `description`, `created_at`, `updated_at`) VALUES
(1, 'service', 'What We Offer', 'Our Services', 'How We Serve The Community', '2026-01-05 10:17:09', '2026-01-05 10:17:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cta_settings`
--

CREATE TABLE `cta_settings` (
  `id` int NOT NULL,
  `page_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'about',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `btn_primary_text` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_primary_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_secondary_text` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_secondary_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `countdown_enabled` tinyint(1) DEFAULT '0',
  `countdown_date` datetime DEFAULT NULL,
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `location`, `image`, `is_featured`, `countdown_enabled`, `countdown_date`, `order_position`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Menolong sesama saudara islam yang ada di Sumatra', 'Join us for a special charity event supporting those in need.', '2026-01-06', '10:00:00', 'Pulau Sumatra', 'uploads/events/event_1767523682.jpg', 0, 1, '2026-01-06 10:00:00', 1, 1, '2026-01-04 10:30:41', '2026-01-04 11:39:50'),
(2, 'If Islam Teaches Peace, Why Are there Radical Muslims?', 'Educational seminar discussing common misconceptions.', '2026-01-04', '14:00:00', 'New York AK United States', 'uploads/events/event_1767524923.jpg', 0, 0, '2026-01-04 18:09:00', 2, 1, '2026-01-04 10:30:41', '2026-01-06 06:50:56'),
(3, 'American Muslim: Choosing Remain Still This Ramadan', 'Community gathering during the holy month.', '2024-12-24', '18:00:00', 'New York AK United States', 'uploads/events/event_3_1767682440.jpg', 0, 0, NULL, 3, 1, '2026-01-04 10:30:41', '2026-01-06 06:54:00'),
(5, 'Maulid Nabi Muhammad SAW', 'Maulid Nabi memperingati kelahiran Nabi Muhammad SAW setiap tanggal 12 Rabiul Awal, sebagai wujud cinta dan penghormatan umat Islam terhadapnya sebagai teladan agung pembawa risalah Islam.', '2026-01-08', '09:00:00', 'Masjid Nurul Iman Gunuk Raya', 'uploads/events/event_1767681879_695caf575b0dc.png', 1, 1, '2026-01-08 09:00:00', 1, 1, '2026-01-06 06:44:39', '2026-01-06 06:44:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `events_header`
--

CREATE TABLE `events_header` (
  `id` int NOT NULL,
  `subtitle` varchar(150) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `events_header`
--

INSERT INTO `events_header` (`id`, `subtitle`, `title`, `updated_at`) VALUES
(1, 'Acara yang akan datang', 'Ethical And Moral Beliefs That Guides To The Straight Path!!', '2026-01-04 11:38:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `faith_header`
--

CREATE TABLE `faith_header` (
  `id` int NOT NULL,
  `subtitle` varchar(150) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `faith_header`
--

INSERT INTO `faith_header` (`id`, `subtitle`, `title`, `updated_at`) VALUES
(1, 'Simbol Kekuatan Islam.1', 'Etika dan adab dalam islam yang diajarkan oleh Nabi Muhammad SAW', '2026-01-06 06:04:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `faith_pillars`
--

CREATE TABLE `faith_pillars` (
  `id` int NOT NULL,
  `pillar_name` varchar(100) NOT NULL,
  `subtitle` varchar(100) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `tab_icon` varchar(255) DEFAULT NULL,
  `description` text,
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `faith_pillars`
--

INSERT INTO `faith_pillars` (`id`, `pillar_name`, `subtitle`, `main_image`, `tab_icon`, `description`, `order_position`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Salah', 'Prayer', 'assets/images/faith/02.png', 'assets/images/faith/faith-icons/02.png', 'Each Muslim should pray five times a day: in the morning, at noon, in the afternoon, after sunset, and early at night. These prayers can be said anywhere, prayers that are said in company of others are better than those said alone.', 1, 1, '2026-01-04 09:32:46', '2026-01-06 06:01:01'),
(3, 'Sawm', 'Fasting', 'assets/images/faith/03.png', 'assets/images/faith/faith-icons/03.png', 'During the holy month of Ramadan, Muslims fast from dawn to sunset. This practice teaches self-discipline, empathy for the less fortunate, and spiritual reflection. It is a time of increased devotion and worship.', 2, 1, '2026-01-04 09:32:46', '2026-01-06 06:01:11'),
(4, 'Zakat', 'Almsgiving', 'assets/images/faith/04.png', 'assets/images/faith/faith-icons/04.png', 'Zakat is the giving of a fixed portion of accumulated wealth to help the poor and needy. This charitable practice purifies wealth and redistributes resources in the community, fostering social equality and compassion.', 3, 1, '2026-01-04 09:32:46', '2026-01-06 06:01:21'),
(5, 'Hajj', 'Pilgrimage', 'assets/images/faith/05.png', 'assets/images/faith/faith-icons/05.png', 'Every Muslim who is financially and physically able must make the pilgrimage to Mecca at least once in their lifetime. The Hajj occurs during the Islamic month of Dhu al-Hijjah and commemorates the actions of Prophet Ibrahim and his family.', 4, 1, '2026-01-04 09:32:46', '2026-01-06 06:01:53'),
(6, 'Al-Qur\'an', 'Kitab Suci', 'uploads/faith/faith_main_1767678869_695ca395d5c52.png', 'uploads/faith/faith_icon_6_1767679249.png', 'Al-Qur\'an adalah kitab suci umat Islam yang merupakan firman Allah SWT, diturunkan secara bertahap kepada Nabi Muhammad SAW melalui Malaikat Jibril, berfungsi sebagai pedoman hidup, petunjuk, dan sumber ajaran pokok Islam untuk kebahagiaan dunia akhirat.', 5, 1, '2026-01-06 05:54:29', '2026-01-06 06:02:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feature_section`
--

CREATE TABLE `feature_section` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `icon` varchar(255) DEFAULT NULL,
  `link_text` varchar(50) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `feature_section`
--

INSERT INTO `feature_section` (`id`, `title`, `description`, `icon`, `link_text`, `link_url`, `order_position`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Quran Studies', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'assets/images/feature/01.png', 'Sponsor Now!', '#', 2, 1, '2026-01-04 04:32:49', '2026-01-04 04:42:32'),
(2, 'Program Islami', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'uploads/features/feature_1767674153_695c9129cc7ef.png', 'Donasi Sekarang', 'https://www.bilibili.tv/id', 3, 1, '2026-01-04 04:32:49', '2026-01-06 04:35:53'),
(3, 'Islamic Awareness', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'assets/images/feature/03.png', 'Join Us!', '#', 1, 1, '2026-01-04 04:32:49', '2026-01-06 04:36:30'),
(4, 'Islamic Services', 'Lorem ipsum dolor sit, amet is consectetur adipisicing elit. Its expedita porro natus', 'assets/images/feature/04.png', 'Get Involved!', '#', 4, 1, '2026-01-04 04:32:49', '2026-01-04 04:32:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery_items`
--

CREATE TABLE `gallery_items` (
  `id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hero_section`
--

CREATE TABLE `hero_section` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `hero_section`
--

INSERT INTO `hero_section` (`id`, `title`, `subtitle`, `description`, `image`, `button_text`, `button_link`, `order_position`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Upload Test Fixed', '', 'Testing after path correction', 'uploads/hero/hero_1767470449_695975715dd2a.png', 'Test Button', '#test', 0, 0, '2026-01-03 19:57:29', '2026-01-06 02:41:06'),
(4, 'Selamat Datang di Yayasan Indonesia Bijak Bestari', 'Simbol Kekuatan Islam', 'YAYASAN INDONESIA BIJAK BESTARI MENDIDIK GENERASI MERAIH RIDHO ILLAHI Alhamdulillah wasyukurillah', 'uploads/hero/hero_1767667245_695c762d6a968.png', 'Pelajari Lebih Lanjut', 'https://www.instagram.com/?hl=id', 0, 1, '2026-01-06 02:40:45', '2026-01-06 03:25:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `history_timeline`
--

CREATE TABLE `history_timeline` (
  `id` int NOT NULL,
  `year` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `action`, `display_name`) VALUES
(1, 'dashboard', 'view', 'Lihat Dashboard'),
(2, 'users', 'view', 'Lihat Pengguna'),
(3, 'users', 'create', 'Tambah Pengguna'),
(4, 'users', 'edit', 'Ubah Pengguna'),
(5, 'users', 'delete', 'Hapus Pengguna'),
(6, 'roles', 'view', 'Lihat Role'),
(7, 'roles', 'create', 'Tambah Role'),
(8, 'roles', 'edit', 'Ubah Role'),
(9, 'roles', 'delete', 'Hapus Role'),
(10, 'hero', 'view', 'Lihat Hero Section'),
(11, 'hero', 'create', 'Tambah Hero Section'),
(12, 'hero', 'edit', 'Ubah Hero Section'),
(13, 'hero', 'delete', 'Hapus Hero Section'),
(14, 'about', 'view', 'Lihat Tentang'),
(15, 'about', 'create', 'Tambah Tentang'),
(16, 'about', 'edit', 'Ubah Tentang'),
(17, 'about', 'delete', 'Hapus Tentang'),
(18, 'events', 'view', 'Lihat Acara'),
(19, 'events', 'create', 'Tambah Acara'),
(20, 'events', 'edit', 'Ubah Acara'),
(21, 'events', 'delete', 'Hapus Acara'),
(22, 'features', 'view', 'Lihat Fitur'),
(23, 'features', 'create', 'Tambah Fitur'),
(24, 'features', 'edit', 'Ubah Fitur'),
(25, 'features', 'delete', 'Hapus Fitur'),
(26, 'services', 'view', 'Lihat Layanan'),
(27, 'services', 'create', 'Tambah Layanan'),
(28, 'services', 'edit', 'Ubah Layanan'),
(29, 'services', 'delete', 'Hapus Layanan'),
(30, 'programs', 'view', 'Lihat Program'),
(31, 'programs', 'create', 'Tambah Program'),
(32, 'programs', 'edit', 'Ubah Program'),
(33, 'programs', 'delete', 'Hapus Program'),
(34, 'quotes', 'view', 'Lihat Kutipan'),
(35, 'quotes', 'create', 'Tambah Kutipan'),
(36, 'quotes', 'edit', 'Ubah Kutipan'),
(37, 'quotes', 'delete', 'Hapus Kutipan'),
(38, 'faith', 'view', 'Lihat Pilar Iman'),
(39, 'faith', 'create', 'Tambah Pilar Iman'),
(40, 'faith', 'edit', 'Ubah Pilar Iman'),
(41, 'faith', 'delete', 'Hapus Pilar Iman'),
(42, 'settings', 'view', 'Lihat Pengaturan'),
(43, 'settings', 'edit', 'Ubah Pengaturan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `quotes`
--

CREATE TABLE `quotes` (
  `id` int NOT NULL,
  `quote_text` text NOT NULL,
  `author` varchar(150) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `quotes`
--

INSERT INTO `quotes` (`id`, `quote_text`, `author`, `source`, `order_position`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'It is Better For Any Of You To Carry A Load Of Firewood On His Own Back Than To Beg From Someone Else', 'Hazrat Mohammad', 'Riyadh-Us-Saleheen, Chapter 59, hadith 540', 1, 1, '2026-01-04 10:05:33', '2026-01-04 10:17:44'),
(2, 'The best among you are those who have the best manners and character', 'Prophet Muhammad (SAW)', 'Sahih Bukhari, Book 56, Hadith 759', 2, 1, '2026-01-04 10:05:33', '2026-01-04 10:18:16'),
(3, 'Seeking knowledge is an obligation upon every Muslim', 'Prophet Muhammad (SAW)', 'Sunan Ibn Majah, Book 1, Hadith 224', 3, 1, '2026-01-04 10:05:33', '2026-01-04 10:05:33'),
(5, 'Tetaplah menjadi terserah (Tersesat Segala Arah) Agar kau mampu berpikir kepada pikiran yang tidak di sangka - sangka.', 'Budi AS (Aaa Siapp)', 'HR. Jekop', 4, 1, '2026-01-06 07:56:06', '2026-01-06 07:56:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `quote_settings`
--

CREATE TABLE `quote_settings` (
  `id` int NOT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `quote_settings`
--

INSERT INTO `quote_settings` (`id`, `background_image`, `updated_at`) VALUES
(1, 'uploads/quotes/quote_bg_1767521811.jpg', '2026-01-04 10:16:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `is_system`, `created_at`) VALUES
(1, 'super_admin', 'Super Admin', 'Akses penuh ke semua fitur termasuk manajemen role', 1, '2026-01-06 08:44:32'),
(2, 'admin', 'Admin', 'Akses penuh ke konten dan pengaturan', 1, '2026-01-06 08:44:32'),
(3, 'editor', 'Editor', 'Hanya dapat mengedit konten', 1, '2026-01-06 08:44:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(1, 2),
(2, 2),
(1, 3),
(2, 3),
(1, 4),
(2, 4),
(1, 5),
(2, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(2, 10),
(3, 10),
(1, 11),
(2, 11),
(1, 12),
(2, 12),
(3, 12),
(1, 13),
(2, 13),
(1, 14),
(2, 14),
(3, 14),
(1, 15),
(2, 15),
(1, 16),
(2, 16),
(3, 16),
(1, 17),
(2, 17),
(1, 18),
(2, 18),
(3, 18),
(1, 19),
(2, 19),
(1, 20),
(2, 20),
(3, 20),
(1, 21),
(2, 21),
(1, 22),
(2, 22),
(3, 22),
(1, 23),
(2, 23),
(1, 24),
(2, 24),
(3, 24),
(1, 25),
(2, 25),
(1, 26),
(2, 26),
(3, 26),
(1, 27),
(2, 27),
(1, 28),
(2, 28),
(3, 28),
(1, 29),
(2, 29),
(1, 30),
(2, 30),
(3, 30),
(1, 31),
(2, 31),
(1, 32),
(2, 32),
(3, 32),
(1, 33),
(2, 33),
(1, 34),
(2, 34),
(3, 34),
(1, 35),
(2, 35),
(1, 36),
(2, 36),
(3, 36),
(1, 37),
(2, 37),
(1, 38),
(2, 38),
(3, 38),
(1, 39),
(2, 39),
(1, 40),
(2, 40),
(3, 40),
(1, 41),
(2, 41),
(1, 42),
(2, 42),
(1, 43),
(2, 43);

-- --------------------------------------------------------

--
-- Struktur dari tabel `section_headers`
--

CREATE TABLE `section_headers` (
  `id` int NOT NULL,
  `page_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_header`
--

CREATE TABLE `service_header` (
  `id` int NOT NULL,
  `subtitle` varchar(100) DEFAULT 'Islamic Center Services',
  `title` varchar(255) DEFAULT 'Ethical And Moral Beliefs That Guides To The Straight Path!',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `service_header`
--

INSERT INTO `service_header` (`id`, `subtitle`, `title`, `updated_at`) VALUES
(1, 'Islamic Center ', 'Ethical And Moral Beliefs That Guides To The Straight Path!', '2026-01-04 05:52:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_section`
--

CREATE TABLE `service_section` (
  `id` int NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text,
  `main_image` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `service_section`
--

INSERT INTO `service_section` (`id`, `category`, `title`, `description`, `main_image`, `icon`, `link_url`, `order_position`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Peningkatan Bangunan', 'Pembangunan Masjid', 'Lorem ipsum, dolor sit amet sectetur adipisicing elit. Vel dicta beatae del voluptas apelas de.', 'uploads/services/main_1767504158_6959f91e317e3.png', 'uploads/services/icon_1767504158_6959f91e31a72.png', 'https://google.com', 1, 1, '2026-01-04 05:03:02', '2026-01-04 05:53:15'),
(11, 'Help Poor', 'Charity And Donation', 'Lorem ipsum, dolor sit amet sectetur adipisicing elit. Vel dicta beatae del voluptas apelas de.', 'uploads/services/main_1767504233_6959f969d5c04.jpg', 'uploads/services/icon_1767504233_6959f969d5efa.jpg', NULL, 2, 1, '2026-01-04 05:05:12', '2026-01-04 05:23:53'),
(12, 'Donate & Help', 'Poor Woman Marriage', 'Lorem ipsum, dolor sit amet sectetur adipisicing elit. Vel dicta beatae del voluptas apelas de.', 'uploads/services/main_1767504314_6959f9ba4d6b1.jpg', 'uploads/services/icon_1767504314_6959f9ba4d974.jpg', NULL, 3, 1, '2026-01-04 05:05:12', '2026-01-04 05:25:14'),
(13, 'Ibadah', 'Kajian Rutin Mingguan', 'Lorem ipsum, dolor sit amet sectetur adipisicing elit. Vel dicta beatae del voluptas apelas de.', 'uploads/services/main_1767674815_695c93bfbd8f0.jpeg', 'uploads/services/icon_1767674815_695c93bfbdf88.jpeg', 'https://www.youtube.com/', 4, 0, '2026-01-06 04:46:55', '2026-01-06 04:51:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL DEFAULT '1',
  `site_name` varchar(255) DEFAULT 'Hafsa Islamic Center',
  `site_tagline` varchar(255) DEFAULT 'Path to Harmony and Faith',
  `site_description` text,
  `phone_primary` varchar(20) DEFAULT '+88019 339 702 520',
  `phone_secondary` varchar(20) DEFAULT NULL,
  `email_primary` varchar(100) DEFAULT 'admin@hafsa.com',
  `email_secondary` varchar(100) DEFAULT NULL,
  `address` text,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `logo_light` varchar(255) DEFAULT 'assets/images/logo/01.png',
  `logo_dark` varchar(255) DEFAULT 'assets/images/logo/01.png',
  `favicon` varchar(255) DEFAULT NULL,
  `working_hours` text,
  `map_embed_url` text,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `footer_text` text,
  `copyright_text` varchar(255) DEFAULT '©2024 Hafsa - Islamic Center',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data untuk tabel `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_tagline`, `site_description`, `phone_primary`, `phone_secondary`, `email_primary`, `email_secondary`, `address`, `facebook_url`, `instagram_url`, `twitter_url`, `youtube_url`, `whatsapp_number`, `logo_light`, `logo_dark`, `favicon`, `working_hours`, `map_embed_url`, `latitude`, `longitude`, `footer_text`, `copyright_text`, `updated_at`) VALUES
(1, 'Yayasan Indonesia Bijak Bestari', 'Bersama Sucikan Hati', 'Yayasan Indonesia Bijak Bestari adalah suatu lembaga agama yang didalamnya terdapat banyak sekali pembelajaran maupun naungan bagi mereka yang ingin belajar islam lebih dalam.', '+6289616682955', '', 'hansco@gmail.com', '', 'Jakarta Selatan DKI JAKARTA INDONESIA', 'https://www.facebook.com/sandhikagalih/?locale=id_ID', 'https://www.instagram.com/kvn.ads/?hl=id', 'https://x.com/sandhikagalih', 'https://www.youtube.com/@sandhikagalihWPU', '+6289616682955', 'assets/images/logo/01.png', 'assets/images/logo/01.png', '', 'Senin-Jumat 08:00-17:00', 'https://maps.app.goo.gl/15K42utcKbwzqwQb8', '-6.294000007499499', '106.85028745767127', 'Bijak Bestari Indonesia is a nonprofit organization supported by community leaders', '©2026 Bijak Bestari Indonesia - by hansco_official', '2026-01-08 01:53:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `team_members`
--

CREATE TABLE `team_members` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int DEFAULT '1',
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `full_name`, `phone`, `photo`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'k.nugraha01', 'habeelkevin@gmail.com', '$2y$10$MChZvwKvw0pr/sGkKQHeTOBMF7wnxXktqfDkpYVw8LeSfa//n0AXC', 1, NULL, NULL, NULL, '2026-01-07 09:15:35', '2026-01-03 17:44:39', '2026-01-07 02:15:35'),
(2, 'roysihan v2', 'roysihan@gmail.com', '$2y$10$dN9CCqJJwdf24KNJcsZeEOS8.0ARGWfDlMnCPwG67fwUqom7qZYyq', 1, NULL, NULL, NULL, '2026-01-06 15:50:17', '2026-01-03 17:49:15', '2026-01-06 08:50:17'),
(3, 'admin - edited', 'admin@example.com', '$2y$10$zhFsaNf2cOjoXBzCJQPBkuGBREY7Iz33VRkJJDt5RDJpdJpPEq9Em', 1, NULL, NULL, NULL, NULL, '2026-01-03 18:39:24', '2026-01-03 19:13:19'),
(4, 'admin', 'admin@gmail.com', '$2y$10$FA2oAe62igRZuaCzZpxpl.u/5tPaZlATk/mVG/ndkhBfLH0Q1Q9Sa', 1, NULL, NULL, NULL, NULL, '2026-01-03 19:35:41', NULL),
(5, 'tester', 'tester@gmail.com', '$2y$10$fl0SLpKkR2Eb4CYAr7LVIe.zmekNSYKRIlTQAjgVRHRrOkAMDX.sa', 1, NULL, NULL, NULL, NULL, '2026-01-03 19:49:04', NULL),
(6, 'verified', 'verified@test.com', '$2y$10$gMVz44Yypg6dpIw8yj.6kOPVlLMmB.1fNttXbuhU1hEndWl0PKKOm', 1, NULL, NULL, NULL, NULL, '2026-01-04 03:54:52', NULL),
(7, 'testuser', 'test@test.com', '$2y$10$2DhguaRPXz/xr/N.cqIIYeZLHJyL7QkhZCsNCwi2yJNCe4Fz3GWw2', 1, NULL, NULL, NULL, NULL, '2026-01-04 05:11:31', NULL),
(8, 'test', 'test@gmail.com', '$2y$10$K18ifVct2JDo.tieLLdEtONsNhxqtJxznUX7C1PJEv2WmqgakriES', 1, NULL, NULL, NULL, NULL, '2026-01-04 05:37:05', NULL),
(9, 'jetski_ui', 'jetski_unique_123@gmail.com', '$2y$10$EEhpYdAUEoSc34YR25onXefbiIj7GbvjP9wkiJsNK2jYSZ11ToN9S', 1, NULL, NULL, NULL, NULL, '2026-01-04 11:33:17', '2026-01-06 07:22:31'),
(10, 'testadmin', 'testadmin@gmail.com', '$2y$10$xdWWyGmMKGO1mBfUnN8NCOp0ajMVkfEPTY1YEQnYxPfJd6YrqMAA.', 1, NULL, NULL, NULL, NULL, '2026-01-07 03:09:14', NULL),
(11, 'jetski_admin', 'jetski_admin@test.com', '$2y$10$DE2sUSrDcehnTRTaG5EQSuPLes6H9.oCyxyBMPe9PurHIioGTDKBe', 1, NULL, NULL, NULL, NULL, '2026-01-09 03:34:21', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `vision_mission_items`
--

CREATE TABLE `vision_mission_items` (
  `id` int NOT NULL,
  `type` enum('vision','mission') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `points_json` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `about_features`
--
ALTER TABLE `about_features`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_hero`
--
ALTER TABLE `about_hero`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_section`
--
ALTER TABLE `about_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `appearance_settings`
--
ALTER TABLE `appearance_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `campaign_main`
--
ALTER TABLE `campaign_main`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `campaign_programs`
--
ALTER TABLE `campaign_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `campaign_sidebar`
--
ALTER TABLE `campaign_sidebar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cms_settings`
--
ALTER TABLE `cms_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_section` (`section`);

--
-- Indeks untuk tabel `cta_settings`
--
ALTER TABLE `cta_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `events_header`
--
ALTER TABLE `events_header`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `faith_header`
--
ALTER TABLE `faith_header`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `faith_pillars`
--
ALTER TABLE `faith_pillars`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `feature_section`
--
ALTER TABLE `feature_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hero_section`
--
ALTER TABLE `hero_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `history_timeline`
--
ALTER TABLE `history_timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_permission` (`module`,`action`);

--
-- Indeks untuk tabel `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `quote_settings`
--
ALTER TABLE `quote_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indeks untuk tabel `section_headers`
--
ALTER TABLE `section_headers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_section` (`page_name`,`section_name`);

--
-- Indeks untuk tabel `service_header`
--
ALTER TABLE `service_header`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `service_section`
--
ALTER TABLE `service_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `vision_mission_items`
--
ALTER TABLE `vision_mission_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `about_features`
--
ALTER TABLE `about_features`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `about_hero`
--
ALTER TABLE `about_hero`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `about_section`
--
ALTER TABLE `about_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `campaign_main`
--
ALTER TABLE `campaign_main`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `campaign_programs`
--
ALTER TABLE `campaign_programs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `campaign_sidebar`
--
ALTER TABLE `campaign_sidebar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `cms_settings`
--
ALTER TABLE `cms_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `cta_settings`
--
ALTER TABLE `cta_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `events_header`
--
ALTER TABLE `events_header`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `faith_header`
--
ALTER TABLE `faith_header`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `faith_pillars`
--
ALTER TABLE `faith_pillars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `feature_section`
--
ALTER TABLE `feature_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `gallery_items`
--
ALTER TABLE `gallery_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hero_section`
--
ALTER TABLE `hero_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `history_timeline`
--
ALTER TABLE `history_timeline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `quote_settings`
--
ALTER TABLE `quote_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `section_headers`
--
ALTER TABLE `section_headers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `service_header`
--
ALTER TABLE `service_header`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `service_section`
--
ALTER TABLE `service_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `vision_mission_items`
--
ALTER TABLE `vision_mission_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
