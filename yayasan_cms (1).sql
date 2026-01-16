-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 14 Jan 2026 pada 16.06
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
-- Struktur dari tabel `about_cta_section`
--

CREATE TABLE `about_cta_section` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `btn_primary_text` varchar(100) DEFAULT NULL,
  `btn_primary_link` varchar(255) DEFAULT NULL,
  `btn_outline_text` varchar(100) DEFAULT NULL,
  `btn_outline_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_cta_section`
--

INSERT INTO `about_cta_section` (`id`, `title`, `description`, `btn_primary_text`, `btn_primary_link`, `btn_outline_text`, `btn_outline_link`) VALUES
(1, 'Mari Ikut Bersama Kami', 'Jadilah bagian dari perubahan positif. Bersama kita wujudkan generasi Indonesia yang lebih baik sekali.', 'Hubungi Kami', 'https://www.instagram.com/?hl=id', 'Donasi Sekarang', 'https://www.instagram.com/?hl=id');

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

--
-- Dumping data untuk tabel `about_features`
--

INSERT INTO `about_features` (`id`, `icon`, `text`, `sort_order`) VALUES
(1, 'icofont-star', 'Pendidikan Terhebat', 1),
(2, 'icofont-chart-growth', 'Program Unggulan', 2),
(3, 'icofont-book-alt', 'Kurikulum Terbaik', 3),
(4, 'icofont-graduate-alt', 'Prestasi Terbanyak', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_gallery_items`
--

CREATE TABLE `about_gallery_items` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_gallery_items`
--

INSERT INTO `about_gallery_items` (`id`, `title`, `category`, `image`, `sort_order`) VALUES
(1, 'Pendidikan Bersama 2024', 'Pendidikan', 'uploads/gallery/1768021548_490.jpg', 1),
(2, 'Bakti Sosial', 'Sosial', 'uploads/gallery/1768021752_852.png', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_gallery_section`
--

CREATE TABLE `about_gallery_section` (
  `id` int NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_gallery_section`
--

INSERT INTO `about_gallery_section` (`id`, `subtitle`, `title`, `description`) VALUES
(1, 'Dokumentasi', 'Galeri Kegiatan', 'Momen-momen berharga dari berbagai kegiatan dan program yayasan kami.');

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
(1, 'Yayasan Indonesia Bijak Bestari', 'icofont-heart-alt', 'Tentang Kita', '2026-01-10 02:58:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_history_items`
--

CREATE TABLE `about_history_items` (
  `id` int NOT NULL,
  `year` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_history_items`
--

INSERT INTO `about_history_items` (`id`, `year`, `title`, `description`, `sort_order`) VALUES
(1, '2014', 'Pendirian Yayasan', 'Yayasan Indonesia Bijak Bestari didirikan oleh sekelompok tokoh masyarakat yang memiliki visi untuk memajukan pendidikan.', 1),
(2, '2016', 'Program Pendidikan Pertama', 'Meluncurkan program beasiswa pendidikan untuk anak-anak kurang mampu di berbagai daerah Indonesia.', 2),
(3, '2019', 'Perluasan Jaringan', 'Membuka cabang di 5 provinsi dan menjalin kerjasama dengan berbagai lembaga pendidikan dan sosial.', 3),
(5, '2024', 'Program Masjid Ke Masjid', 'Program Masjid Ke Masjid adalah program yang dilakukan untuk melakukan Survei Kebersihan terhadap Masjid - masjid yang ada di sekitar daerah Aceh.', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_history_section`
--

CREATE TABLE `about_history_section` (
  `id` int NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_history_section`
--

INSERT INTO `about_history_section` (`id`, `subtitle`, `title`, `description`) VALUES
(1, 'Perjalanan Kami', 'Sejarah Yayasan', 'Perjalanan panjang dalam membangun dan mengembangkan yayasan untuk kemaslahatan umat.');

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
(5, 'Ilmu dan adab yang bermanfaat', 'Asal Usul Yayasan Kami Dibuat', '', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae velit maiores itaque voluptates recusandae error laudantium aliquam non nisi corrupti!     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae velit maiores itaque voluptates recusandae error laudantium aliquam non nisi corrupti!', 'uploads/about/about_intro_1767953616_6960d4d03275d.jpg', 'Gabung Bersama Kami', 'https://chat.openai.com/', 1, '2026-01-09 10:00:57', '2026-01-09 10:19:15', '20', 'Progam yang sudah berjalan', 'icofont-award');

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_team_items`
--

CREATE TABLE `about_team_items` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link_facebook` varchar(255) DEFAULT NULL,
  `link_twitter` varchar(255) DEFAULT NULL,
  `link_linkedin` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_team_items`
--

INSERT INTO `about_team_items` (`id`, `name`, `role`, `image`, `link_facebook`, `link_twitter`, `link_linkedin`, `sort_order`) VALUES
(1, 'Kevin Adisurya Nugraha', 'IT Developer', 'uploads/team/1768020398_445.png', 'https://www.instagram.com/?hl=id', 'https://www.instagram.com/?hl=id', 'https://www.instagram.com/?hl=id', 1),
(2, 'Roysihan', 'Project Manager', 'uploads/team/1768020446_796.jpg', 'https://www.instagram.com/?hl=id', 'https://www.instagram.com/?hl=id', 'https://www.instagram.com/?hl=id', 2),
(3, 'Shaila Agustin Safitri', 'Graphic Designer', 'uploads/team/1768020547_360.jpg', 'https://www.instagram.com/?hl=id', 'https://www.instagram.com/?hl=id', 'https://www.instagram.com/?hl=id', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_team_section`
--

CREATE TABLE `about_team_section` (
  `id` int NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `about_team_section`
--

INSERT INTO `about_team_section` (`id`, `subtitle`, `title`, `description`) VALUES
(1, 'Tim Kita', 'Struktur Tim Kami', 'Tim profesional dan berdedikasi yang menggerakkan visi dan misi yayasan.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_vision_mission_items`
--

CREATE TABLE `about_vision_mission_items` (
  `id` int NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `list_items` text COLLATE utf8mb4_unicode_ci COMMENT 'Newline separated items',
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `about_vision_mission_items`
--

INSERT INTO `about_vision_mission_items` (`id`, `icon`, `title`, `description`, `list_items`, `sort_order`) VALUES
(1, 'icofont-flag-alt-2', 'Visi Kami', 'Menjadi lembaga pendidikan dan sosial terkemuka yang melahirkan generasi Indonesia yang cerdas, berakhlak mulia, dan berdaya saing global.', 'Pendidikan yang merata dan berkualitas\r\nKarakter islami yang kuat\r\nKontribusi nyata untuk masyarakat', 2),
(2, 'icofont-flag-alt-2', 'Misi Kami', 'Melaksanakan program-program pendidikan, pembinaan, dan kegiatan sosial yang berdampak positif bagi masyarakat luas.', 'Menyelenggarakan pendidikan berkualitas\nMembina akhlak dan spiritual\nMengembangkan potensi generasi muda\nMelakukan kegiatan sosial kemasyarakatan', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_vision_mission_section`
--

CREATE TABLE `about_vision_mission_section` (
  `id` int NOT NULL,
  `subtitle` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `about_vision_mission_section`
--

INSERT INTO `about_vision_mission_section` (`id`, `subtitle`, `title`, `description`) VALUES
(1, 'Panduan Kita Semua', 'Visi & Misi', 'Komitmen kami untuk membangun generasi Indonesia yang berilmu, berkarakter, dan bermanfaat bagi masyarakat.');

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
(1, '#2e7d32', '#1565c0', '#ff9800', 'Poppins', 'rounded', '2026-01-11 04:33:49');

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
-- Struktur dari tabel `donations`
--

CREATE TABLE `donations` (
  `id` int NOT NULL,
  `donation_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `event_id` int DEFAULT NULL,
  `program_type` enum('event','general') COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `program_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'General',
  `description` text,
  `event_date` date NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `location_address` text,
  `location_maps` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_cover` varchar(255) DEFAULT NULL,
  `speaker_name` varchar(255) DEFAULT NULL,
  `speaker_title` varchar(255) DEFAULT NULL,
  `speaker_bio` text,
  `speaker_image` varchar(255) DEFAULT NULL,
  `quota` int DEFAULT '100',
  `registered` int DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_whatsapp` varchar(20) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `countdown_enabled` tinyint(1) DEFAULT '0',
  `countdown_date` datetime DEFAULT NULL,
  `order_position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `status` enum('published','draft','ended') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `title`, `slug`, `category`, `description`, `event_date`, `date_end`, `event_time`, `location`, `location_address`, `location_maps`, `image`, `image_cover`, `speaker_name`, `speaker_title`, `speaker_bio`, `speaker_image`, `quota`, `registered`, `price`, `contact_phone`, `contact_whatsapp`, `is_featured`, `countdown_enabled`, `countdown_date`, `order_position`, `is_active`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Menolong sesama saudara islam yang ada di Sumatra', 'menolong-sesama-saudara-islam-yang-ada-di-sumatra', 'Kajian', 'Join us for a special charity event supporting those in need.', '2026-01-06', NULL, '10:00:00', 'Pulau Sumatra', 'Jl. Utama Yayasan No. 1', NULL, 'uploads/events/event_1767523682.jpg', NULL, NULL, NULL, NULL, NULL, 200, 0, 0.00, '6281234567890', '6281234567890', 0, 1, '2026-01-06 10:00:00', 1, 1, 'published', '2026-01-04 10:30:41', '2026-01-13 11:31:33'),
(2, 'If Islam Teaches Peace, Why Are there Radical Muslims?', 'if-islam-teaches-peace,-why-are-there-radical-muslims?', 'Kajian', 'Educational seminar discussing common misconceptions.', '2026-01-04', NULL, '14:00:00', 'New York AK United States', 'Jl. Utama Yayasan No. 1', NULL, 'uploads/events/event_1767524923.jpg', NULL, NULL, NULL, NULL, NULL, 200, 0, 0.00, '6281234567890', '6281234567890', 0, 0, '2026-01-04 18:09:00', 2, 1, 'published', '2026-01-04 10:30:41', '2026-01-13 11:31:33'),
(3, 'American Muslim: Choosing Remain Still This Ramadan', 'american-muslim:-choosing-remain-still-this-ramadan', 'Kajian', 'Community gathering during the holy month.', '2024-12-24', NULL, '18:00:00', 'New York AK United States', 'Jl. Utama Yayasan No. 1', NULL, 'uploads/events/event_3_1767682440.jpg', NULL, NULL, NULL, NULL, NULL, 200, 0, 0.00, '6281234567890', '6281234567890', 0, 0, NULL, 3, 1, 'published', '2026-01-04 10:30:41', '2026-01-13 11:31:33'),
(5, 'Maulid Nabi Muhammad SAW', 'maulid-nabi-muhammad-saw', 'Kajian', 'Maulid Nabi memperingati kelahiran Nabi Muhammad SAW setiap tanggal 12 Rabiul Awal, sebagai wujud cinta dan penghormatan umat Islam terhadapnya sebagai teladan agung pembawa risalah Islam.', '2026-01-08', NULL, '09:00:00', 'Masjid Nurul Iman Gunuk Raya', 'Jl. Utama Yayasan No. 1', NULL, 'uploads/events/event_1767681879_695caf575b0dc.png', NULL, NULL, NULL, NULL, NULL, 200, 0, 0.00, '6281234567890', '6281234567890', 1, 1, '2026-01-08 09:00:00', 1, 1, 'published', '2026-01-06 06:44:39', '2026-01-13 11:31:33');

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
-- Struktur dari tabel `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int NOT NULL,
  `ticket_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_range` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket_quantity` int DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Struktur dari tabel `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int NOT NULL,
  `type` enum('bank','ewallet','qris') COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `type`, `provider_name`, `account_number`, `account_name`, `logo_image`, `is_active`, `sort_order`) VALUES
(1, 'bank', 'Bank Central Asia (BCA)', '1234567890', 'Yayasan Indonesia Bijak Bestari', 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg', 1, 1),
(2, 'bank', 'Bank Mandiri', '9876543210', 'Yayasan Indonesia Bijak Bestari', 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg', 1, 2),
(3, 'ewallet', 'GoPay', '081234567890', 'Yayasan IBB', NULL, 1, 3),
(4, 'ewallet', 'OVO', '081234567890', 'Yayasan IBB', NULL, 1, 4);

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
-- Indeks untuk tabel `about_cta_section`
--
ALTER TABLE `about_cta_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_features`
--
ALTER TABLE `about_features`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_gallery_items`
--
ALTER TABLE `about_gallery_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_gallery_section`
--
ALTER TABLE `about_gallery_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_hero`
--
ALTER TABLE `about_hero`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_history_items`
--
ALTER TABLE `about_history_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_history_section`
--
ALTER TABLE `about_history_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_section`
--
ALTER TABLE `about_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_team_items`
--
ALTER TABLE `about_team_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_team_section`
--
ALTER TABLE `about_team_section`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_vision_mission_items`
--
ALTER TABLE `about_vision_mission_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `about_vision_mission_section`
--
ALTER TABLE `about_vision_mission_section`
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
-- Indeks untuk tabel `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_donation_code` (`donation_code`),
  ADD KEY `fk_donations_event` (`event_id`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indeks untuk tabel `events_header`
--
ALTER TABLE `events_header`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_ticket_id` (`ticket_id`),
  ADD KEY `idx_event_id` (`event_id`);

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
-- Indeks untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
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
-- AUTO_INCREMENT untuk tabel `about_cta_section`
--
ALTER TABLE `about_cta_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `about_features`
--
ALTER TABLE `about_features`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `about_gallery_items`
--
ALTER TABLE `about_gallery_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `about_gallery_section`
--
ALTER TABLE `about_gallery_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `about_hero`
--
ALTER TABLE `about_hero`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `about_history_items`
--
ALTER TABLE `about_history_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `about_history_section`
--
ALTER TABLE `about_history_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `about_section`
--
ALTER TABLE `about_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `about_team_items`
--
ALTER TABLE `about_team_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `about_team_section`
--
ALTER TABLE `about_team_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `about_vision_mission_items`
--
ALTER TABLE `about_vision_mission_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `about_vision_mission_section`
--
ALTER TABLE `about_vision_mission_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT untuk tabel `donations`
--
ALTER TABLE `donations`
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
-- AUTO_INCREMENT untuk tabel `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Ketidakleluasaan untuk tabel `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `fk_donations_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `fk_registrations_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

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
