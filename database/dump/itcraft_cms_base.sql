-- --------------------------------------------------------
-- ITCRAFT CMS Dummy Base SQL Dump
-- Database: itcraftcloudcom_larav42
-- Created At: 2026-05-04 15:34:32
-- This dump includes dummy content for first installation.
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;


-- --------------------------------------------------------
-- Table: `admin_login_attempts`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `admin_login_attempts`;
CREATE TABLE `admin_login_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(150) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `successful` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_login_attempts_identifier_ip_index` (`identifier`(100),`ip_address`),
  KEY `admin_login_attempts_successful_index` (`successful`),
  KEY `admin_login_attempts_created_at_index` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `cache`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `cache_locks`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `contact_messages`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` longtext NOT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `referer_url` text DEFAULT NULL,
  `source_url` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_messages_is_read_index` (`is_read`),
  KEY `contact_messages_ip_created_index` (`ip_address`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `content_versions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `content_versions`;
CREATE TABLE `content_versions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `record_id` bigint(20) unsigned NOT NULL,
  `record_title` varchar(255) DEFAULT NULL,
  `record_slug` varchar(255) DEFAULT NULL,
  `version_data` longtext NOT NULL,
  `changed_by` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_versions_table_record_index` (`table_name`,`record_id`),
  KEY `content_versions_table_index` (`table_name`),
  KEY `content_versions_created_at_index` (`created_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `failed_jobs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `job_batches`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `jobs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `menu_items`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `target` varchar(20) NOT NULL DEFAULT '_self',
  `item_type` varchar(50) NOT NULL DEFAULT 'custom',
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_index` (`menu_id`),
  KEY `menu_items_parent_id_index` (`parent_id`),
  KEY `menu_items_sort_order_index` (`sort_order`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `menu_items`

INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', '1', 'Ana Sayfa', '/', '_self', NULL, '1', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', '1', 'Hakkımızda', '/hakkimizda', '_self', NULL, '2', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', '1', 'Hizmetler', '/hizmetler', '_self', NULL, '3', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', '1', 'Ekibimiz', '/ekibimiz', '_self', NULL, '4', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', '1', 'Blog', '/blog', '_self', NULL, '5', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('6', '1', 'İletişim', '/iletisim', '_self', NULL, '6', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('7', '2', 'Gizlilik Politikası', '/gizlilik-politikasi', '_self', NULL, '1', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('8', '2', 'İletişim', '/iletisim', '_self', NULL, '2', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `menus`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL DEFAULT 'header',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_location_index` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `menus`

INSERT INTO `menus` (`id`, `name`, `location`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Ana Menü', 'header', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `menus` (`id`, `name`, `location`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Footer Menü', 'footer', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `migrations`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Existing safe data for `migrations`

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');


-- --------------------------------------------------------
-- Table: `pages`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `template` varchar(100) NOT NULL DEFAULT 'default',
  `show_in_menu` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `robots_index` varchar(20) NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) NOT NULL DEFAULT 'follow',
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` varchar(500) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `schema_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_is_active_index` (`is_active`),
  KEY `pages_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `pages`

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('1', 'Ana Sayfa', 'anasayfa', '<p>Bu sayfa ITCRAFT CMS demo kurulumu ile birlikte oluşturulmuştur.</p><p>Müşteri bu içeriği admin panelden kendi kurumsal bilgileriyle değiştirebilir.</p>', 'Ana Sayfa | ITCRAFT Demo Company', 'Ana Sayfa sayfası için demo meta açıklamasıdır.', '1', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('2', 'Hakkımızda', 'hakkimizda', '<p>Bu sayfa ITCRAFT CMS demo kurulumu ile birlikte oluşturulmuştur.</p><p>Müşteri bu içeriği admin panelden kendi kurumsal bilgileriyle değiştirebilir.</p>', 'Hakkımızda | ITCRAFT Demo Company', 'Hakkımızda sayfası için demo meta açıklamasıdır.', '1', '2', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('3', 'Hizmetler', 'hizmetler', '<p>Bu sayfa ITCRAFT CMS demo kurulumu ile birlikte oluşturulmuştur.</p><p>Müşteri bu içeriği admin panelden kendi kurumsal bilgileriyle değiştirebilir.</p>', 'Hizmetler | ITCRAFT Demo Company', 'Hizmetler sayfası için demo meta açıklamasıdır.', '1', '3', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('4', 'Ekibimiz', 'ekibimiz', '<p>Bu sayfa ITCRAFT CMS demo kurulumu ile birlikte oluşturulmuştur.</p><p>Müşteri bu içeriği admin panelden kendi kurumsal bilgileriyle değiştirebilir.</p>', 'Ekibimiz | ITCRAFT Demo Company', 'Ekibimiz sayfası için demo meta açıklamasıdır.', '1', '4', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('5', 'Blog', 'blog', '<p>Bu sayfa ITCRAFT CMS demo kurulumu ile birlikte oluşturulmuştur.</p><p>Müşteri bu içeriği admin panelden kendi kurumsal bilgileriyle değiştirebilir.</p>', 'Blog | ITCRAFT Demo Company', 'Blog sayfası için demo meta açıklamasıdır.', '1', '5', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('6', 'İletişim', 'iletisim', '<p>Bu sayfa ITCRAFT CMS demo kurulumu ile birlikte oluşturulmuştur.</p><p>Müşteri bu içeriği admin panelden kendi kurumsal bilgileriyle değiştirebilir.</p>', 'İletişim | ITCRAFT Demo Company', 'İletişim sayfası için demo meta açıklamasıdır.', '1', '6', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('7', 'Gizlilik Politikası', 'gizlilik-politikasi', '<p>Bu sayfa ITCRAFT CMS demo kurulumu ile birlikte oluşturulmuştur.</p><p>Müşteri bu içeriği admin panelden kendi kurumsal bilgileriyle değiştirebilir.</p>', 'Gizlilik Politikası | ITCRAFT Demo Company', 'Gizlilik Politikası sayfası için demo meta açıklamasıdır.', '1', '7', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `password_reset_tokens`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `post_categories`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `post_categories`;
CREATE TABLE `post_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `post_categories`

INSERT INTO `post_categories` (`id`, `title`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('1', 'Dijital Dönüşüm', 'dijital-donusum', 'Demo blog kategorisidir.', '1', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `post_categories` (`id`, `title`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('2', 'Siber Güvenlik', 'siber-guvenlik', 'Demo blog kategorisidir.', '1', '2', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `post_categories` (`id`, `title`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('3', 'Kurumsal Web', 'kurumsal-web', 'Demo blog kategorisidir.', '1', '3', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `posts`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `robots_index` varchar(20) NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) NOT NULL DEFAULT 'follow',
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` varchar(500) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `schema_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_post_category_id_index` (`post_category_id`),
  KEY `posts_is_active_index` (`is_active`),
  CONSTRAINT `posts_post_category_id_foreign` FOREIGN KEY (`post_category_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `posts`

INSERT INTO `posts` (`id`, `post_category_id`, `title`, `slug`, `content`, `image`, `is_active`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('1', '1', 'Kurumsal Web Sitesi Neden Önemlidir?', 'kurumsal-web-sitesi-neden-onemlidir', '<p>Bu yazı ITCRAFT CMS demo kurulumu için oluşturulmuştur.</p><p>Müşteri bu alanı panel üzerinden kendi blog içeriğiyle değiştirebilir.</p>', '/assets/itcraft-cms-demo/blog-1.svg', '1', '2026-05-04 15:34:32', 'Kurumsal Web Sitesi Neden Önemlidir?', 'Bu içerik demo amaçlı oluşturulmuş örnek blog yazısıdır.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `posts` (`id`, `post_category_id`, `title`, `slug`, `content`, `image`, `is_active`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('2', '2', 'Siber Güvenlikte Temel Kontroller', 'siber-guvenlikte-temel-kontroller', '<p>Bu yazı ITCRAFT CMS demo kurulumu için oluşturulmuştur.</p><p>Müşteri bu alanı panel üzerinden kendi blog içeriğiyle değiştirebilir.</p>', '/assets/itcraft-cms-demo/blog-2.svg', '1', '2026-05-04 15:34:32', 'Siber Güvenlikte Temel Kontroller', 'Bu içerik demo amaçlı oluşturulmuş örnek blog yazısıdır.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `posts` (`id`, `post_category_id`, `title`, `slug`, `content`, `image`, `is_active`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('3', '3', 'İçerik Yönetim Sisteminde Esneklik', 'icerik-yonetim-sisteminde-esneklik', '<p>Bu yazı ITCRAFT CMS demo kurulumu için oluşturulmuştur.</p><p>Müşteri bu alanı panel üzerinden kendi blog içeriğiyle değiştirebilir.</p>', '/assets/itcraft-cms-demo/blog-3.svg', '1', '2026-05-04 15:34:32', 'İçerik Yönetim Sisteminde Esneklik', 'Bu içerik demo amaçlı oluşturulmuş örnek blog yazısıdır.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `services`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `robots_index` varchar(20) NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) NOT NULL DEFAULT 'follow',
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` varchar(500) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `schema_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_is_active_index` (`is_active`),
  KEY `services_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `services`

INSERT INTO `services` (`id`, `title`, `slug`, `content`, `image`, `icon`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('1', 'Kurumsal Web Tasarımı', 'kurumsal-web-tasarimi', '<p>Modern, hızlı ve yönetilebilir kurumsal web siteleri.</p><p>Bu hizmet açıklaması dummy içeriktir. Müşteri kendi hizmet detaylarını panel üzerinden düzenleyebilir.</p>', '/assets/itcraft-cms-demo/service-network.svg', 'circle-check', '1', '1', 'Kurumsal Web Tasarımı | ITCRAFT Demo Company', 'Modern, hızlı ve yönetilebilir kurumsal web siteleri.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `services` (`id`, `title`, `slug`, `content`, `image`, `icon`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('2', 'Siber Güvenlik Danışmanlığı', 'siber-guvenlik-danismanligi', '<p>Firewall, güvenlik politikaları ve altyapı danışmanlığı.</p><p>Bu hizmet açıklaması dummy içeriktir. Müşteri kendi hizmet detaylarını panel üzerinden düzenleyebilir.</p>', '/assets/itcraft-cms-demo/service-security.svg', 'circle-check', '2', '1', 'Siber Güvenlik Danışmanlığı | ITCRAFT Demo Company', 'Firewall, güvenlik politikaları ve altyapı danışmanlığı.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `services` (`id`, `title`, `slug`, `content`, `image`, `icon`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('3', 'Bulut ve Hosting Çözümleri', 'bulut-ve-hosting-cozumleri', '<p>Web hosting, VDS, e-posta ve bulut altyapı çözümleri.</p><p>Bu hizmet açıklaması dummy içeriktir. Müşteri kendi hizmet detaylarını panel üzerinden düzenleyebilir.</p>', '/assets/itcraft-cms-demo/service-cloud.svg', 'circle-check', '3', '1', 'Bulut ve Hosting Çözümleri | ITCRAFT Demo Company', 'Web hosting, VDS, e-posta ve bulut altyapı çözümleri.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `services` (`id`, `title`, `slug`, `content`, `image`, `icon`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('4', 'Network Altyapı Yönetimi', 'network-altyapi-yonetimi', '<p>Kablolu/kablosuz ağ tasarımı, kurulum ve yönetim hizmetleri.</p><p>Bu hizmet açıklaması dummy içeriktir. Müşteri kendi hizmet detaylarını panel üzerinden düzenleyebilir.</p>', '/assets/itcraft-cms-demo/service-network.svg', 'circle-check', '4', '1', 'Network Altyapı Yönetimi | ITCRAFT Demo Company', 'Kablolu/kablosuz ağ tasarımı, kurulum ve yönetim hizmetleri.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `services` (`id`, `title`, `slug`, `content`, `image`, `icon`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('5', 'Yedekleme ve Felaket Kurtarma', 'yedekleme-ve-felaket-kurtarma', '<p>Veri koruma, yedekleme planı ve iş sürekliliği çözümleri.</p><p>Bu hizmet açıklaması dummy içeriktir. Müşteri kendi hizmet detaylarını panel üzerinden düzenleyebilir.</p>', '/assets/itcraft-cms-demo/service-cloud.svg', 'circle-check', '5', '1', 'Yedekleme ve Felaket Kurtarma | ITCRAFT Demo Company', 'Veri koruma, yedekleme planı ve iş sürekliliği çözümleri.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `services` (`id`, `title`, `slug`, `content`, `image`, `icon`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES ('6', 'IT Danışmanlık Hizmetleri', 'it-danismanlik-hizmetleri', '<p>Kurumların teknik ihtiyaçlarına özel uçtan uca danışmanlık.</p><p>Bu hizmet açıklaması dummy içeriktir. Müşteri kendi hizmet detaylarını panel üzerinden düzenleyebilir.</p>', '/assets/itcraft-cms-demo/service-security.svg', 'circle-check', '6', '1', 'IT Danışmanlık Hizmetleri | ITCRAFT Demo Company', 'Kurumların teknik ihtiyaçlarına özel uçtan uca danışmanlık.', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `sessions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.


-- --------------------------------------------------------
-- Table: `site_settings`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(191) NOT NULL,
  `setting_value` longtext DEFAULT NULL,
  `setting_type` varchar(50) NOT NULL DEFAULT 'text',
  `setting_group` varchar(100) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `theme_primary_color` varchar(20) DEFAULT '#1d4ed8',
  `theme_secondary_color` varchar(20) DEFAULT '#f59e0b',
  `theme_accent_color` varchar(20) DEFAULT '#0ea5e9',
  `theme_button_color` varchar(20) DEFAULT '#1d4ed8',
  `theme_button_hover_color` varchar(20) DEFAULT '#1e40af',
  `theme_header_bg` varchar(20) DEFAULT '#ffffff',
  `theme_footer_bg` varchar(20) DEFAULT '#111827',
  `theme_body_bg` varchar(20) DEFAULT '#ffffff',
  `theme_heading_color` varchar(20) DEFAULT '#111827',
  `theme_text_color` varchar(20) DEFAULT '#374151',
  `theme_link_color` varchar(20) DEFAULT '#1d4ed8',
  `theme_font_family` varchar(100) DEFAULT 'Inter, Arial, sans-serif',
  `theme_button_radius` varchar(20) DEFAULT '12px',
  `theme_card_radius` varchar(20) DEFAULT '18px',
  `theme_container_width` varchar(20) DEFAULT '1200px',
  `theme_shadow_level` varchar(30) DEFAULT 'medium',
  `theme_footer_heading_color` varchar(20) DEFAULT '#ffffff',
  `theme_footer_text_color` varchar(20) DEFAULT '#d1d5db',
  `theme_footer_link_color` varchar(20) DEFAULT '#e5e7eb',
  `theme_footer_link_hover_color` varchar(20) DEFAULT '#ffffff',
  `theme_footer_border_color` varchar(20) DEFAULT '#374151',
  `theme_footer_social_color` varchar(20) DEFAULT '#ffffff',
  `theme_slider_badge_bg` varchar(20) DEFAULT '#1e293b',
  `theme_slider_badge_text` varchar(20) DEFAULT '#ffffff',
  `theme_slider_badge_dot` varchar(20) DEFAULT '#0ea5e9',
  `theme_slider_dot_active` varchar(20) DEFAULT '#0ea5e9',
  `theme_slider_dot_passive` varchar(20) DEFAULT '#94a3b8',
  `theme_slider_accent_color` varchar(20) DEFAULT '#0ea5e9',
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_setting_key_unique` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `site_settings`

INSERT INTO `site_settings` (`id`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_footer_bg`, `created_at`, `updated_at`) VALUES ('1', '#1d4ed8', '#0f172a', '#38bdf8', '#1d4ed8', '#1e40af', '#0f172a', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `sliders`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_url` varchar(500) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sliders_is_active_index` (`is_active`),
  KEY `sliders_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `sliders`

INSERT INTO `sliders` (`id`, `title`, `subtitle`, `description`, `button_text`, `button_url`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Kurumsal Web Sitenizi Dakikalar İçinde Yayına Alın', 'ITCRAFT CMS demo slider alanı', 'Bu slider içeriği örnek olarak oluşturulmuştur. Admin panelden başlık, açıklama, buton ve görsel alanlarını kolayca değiştirebilirsiniz.', 'Hizmetleri İncele', '/hizmetler', '/assets/itcraft-cms-demo/hero-corporate.svg', '1', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `sliders` (`id`, `title`, `subtitle`, `description`, `button_text`, `button_url`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Sektörünüze Uygun Esnek İçerik Yönetimi', 'Çok amaçlı demo kurulum', 'Hizmetler, ekip, blog, iletişim ve tema ayarları hazır gelir. Müşteri sadece kendi içeriklerini girerek sistemi kullanmaya başlayabilir.', 'İletişime Geç', '/iletisim', '/assets/itcraft-cms-demo/hero-service.svg', '2', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `team_members`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `team_members`;
CREATE TABLE `team_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `bio` longtext DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `linkedin_url` varchar(500) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `robots_index` varchar(20) NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) NOT NULL DEFAULT 'follow',
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` varchar(500) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `schema_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_members_slug_unique` (`slug`),
  KEY `team_members_is_active_index` (`is_active`),
  KEY `team_members_sort_order_index` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for `team_members`

INSERT INTO `team_members` (`id`, `name`, `slug`, `title`, `email`, `phone`, `bio`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Ayşe Demir', 'ayse-demir', 'Genel Müdür', 'ayse.demir@example.com', '+90 212 000 00 01', 'Bu kişi demo ekip üyesidir. Müşteri kendi ekip bilgileriyle değiştirebilir.', '/assets/itcraft-cms-demo/team-1.svg', '1', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `team_members` (`id`, `name`, `slug`, `title`, `email`, `phone`, `bio`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Mehmet Kaya', 'mehmet-kaya', 'Teknik Danışman', 'mehmet.kaya@example.com', '+90 212 000 00 02', 'Bu kişi demo ekip üyesidir. Müşteri kendi ekip bilgileriyle değiştirebilir.', '/assets/itcraft-cms-demo/team-2.svg', '2', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `team_members` (`id`, `name`, `slug`, `title`, `email`, `phone`, `bio`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Elif Yılmaz', 'elif-yilmaz', 'Proje Yöneticisi', 'elif.yilmaz@example.com', '+90 212 000 00 03', 'Bu kişi demo ekip üyesidir. Müşteri kendi ekip bilgileriyle değiştirebilir.', '/assets/itcraft-cms-demo/team-1.svg', '3', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');
INSERT INTO `team_members` (`id`, `name`, `slug`, `title`, `email`, `phone`, `bio`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'Can Arslan', 'can-arslan', 'Dijital Pazarlama Uzmanı', 'can.arslan@example.com', '+90 212 000 00 04', 'Bu kişi demo ekip üyesidir. Müşteri kendi ekip bilgileriyle değiştirebilir.', '/assets/itcraft-cms-demo/team-2.svg', '4', '1', '2026-05-04 15:34:32', '2026-05-04 15:34:32');


-- --------------------------------------------------------
-- Table: `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure only. Data skipped for clean install.

SET FOREIGN_KEY_CHECKS=1;
