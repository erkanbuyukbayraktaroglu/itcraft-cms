-- --------------------------------------------------------
-- ITCRAFT CMS Base SQL Dump
-- Database: itcraftcloudcom_larav42
-- Created At: 2026-05-04 13:20:38
-- This dump is intended for clean first installation.
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

-- Data skipped for clean install.


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

-- Data skipped for clean install.


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

-- Data skipped for clean install.


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

-- Data skipped for clean install.


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

-- Data skipped for clean install.


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

-- Data skipped for clean install.


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

-- Data skipped for clean install.


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

-- Data skipped for clean install.


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

-- Data for `menu_items`

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', '1', NULL, 'Anasayfa', '/', '_self', 'custom', NULL, '1', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', '1', NULL, 'Hakkımızda', '/hakkimizda', '_self', 'page', NULL, '2', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', '1', NULL, 'Hizmetler', '/hizmetler', '_self', 'custom', NULL, '3', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', '1', NULL, 'Blog', '/blog', '_self', 'custom', NULL, '4', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', '1', NULL, 'İletişim', '/iletisim', '_self', 'page', NULL, '5', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('6', '2', NULL, 'Anasayfa', '/', '_self', 'custom', NULL, '1', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('7', '2', NULL, 'Hakkımızda', '/hakkimizda', '_self', 'page', NULL, '2', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('8', '2', NULL, 'Hizmetler', '/hizmetler', '_self', 'custom', NULL, '3', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `title`, `url`, `target`, `item_type`, `reference_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('9', '2', NULL, 'İletişim', '/iletisim', '_self', 'page', NULL, '4', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');


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

-- Data for `menus`

INSERT INTO `menus` (`id`, `name`, `location`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Üst Menü', 'header', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `menus` (`id`, `name`, `location`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Footer Menü', 'footer', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');


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

-- Data for `migrations`

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

-- Data for `pages`

INSERT INTO `pages` (`id`, `title`, `slug`, `summary`, `content`, `image`, `template`, `show_in_menu`, `sort_order`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('1', 'Hakkımızda', 'hakkimizda', 'Kurumsal yapımız, değerlerimiz ve hizmet anlayışımız hakkında bilgi alın.', '<p>Bu alan yönetim panelinden düzenlenebilir. Kurumunuzun geçmişi, vizyonu, misyonu ve değerleri burada yer alır.</p>', NULL, 'default', '1', '1', '1', '2026-04-29 14:00:22', 'Hakkımızda', 'Kurumsal yapımız ve hizmet anlayışımız hakkında bilgi alın.', NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'Organization', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `pages` (`id`, `title`, `slug`, `summary`, `content`, `image`, `template`, `show_in_menu`, `sort_order`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('2', 'İletişim', 'iletisim', 'Bizimle iletişime geçin.', '<p>İletişim bilgilerimiz ve form alanı bu sayfada yer alacaktır.</p>', NULL, 'contact', '1', '99', '1', '2026-04-29 14:00:22', 'İletişim', 'Bizimle iletişime geçin.', NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'ContactPage', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `pages` (`id`, `title`, `slug`, `summary`, `content`, `image`, `template`, `show_in_menu`, `sort_order`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('3', 'Test Sayfası', 'test-sayfasi', 'Test Deneme Bir İki Üç', 'Test Deneme Bir İki Üç', NULL, 'wide', '1', '0', '1', '2026-04-29 14:23:00', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'WebPage', '2026-04-29 14:23:38', '2026-04-29 14:24:39');


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

-- Data skipped for clean install.


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

-- Data for `post_categories`

INSERT INTO `post_categories` (`id`, `title`, `slug`, `description`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES ('1', 'Genel', 'genel', 'Genel blog ve haber kategorisi.', '1', '1', 'Genel Blog Yazıları', 'Genel blog ve haber içerikleri.', NULL, '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `post_categories` (`id`, `title`, `slug`, `description`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES ('2', 'Duyurular', 'duyurular', 'Kurumsal Duyurular ve Güncel Haberler', '0', '1', NULL, NULL, NULL, '2026-04-29 14:34:33', '2026-04-29 14:34:33');


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

-- Data for `posts`

INSERT INTO `posts` (`id`, `post_category_id`, `title`, `slug`, `summary`, `content`, `image`, `author_name`, `sort_order`, `is_featured`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('1', NULL, 'İlk Blog Yazımız', 'ilk-blog-yazimiz', 'Bu yazı CMS panelinden oluşturulan ilk blog yazısıdır.', '<p>Bu içerik admin panel üzerinden oluşturuldu.</p>', NULL, NULL, '0', '0', '1', '2026-04-29 14:34:00', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'Article', '2026-04-29 14:35:03', '2026-04-29 14:35:32');
INSERT INTO `posts` (`id`, `post_category_id`, `title`, `slug`, `summary`, `content`, `image`, `author_name`, `sort_order`, `is_featured`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('2', NULL, 'İlk Blog Yazımız Kopya', 'ilk-blog-yazimiz-kopya', 'Bu yazı CMS panelinden oluşturulan ilk blog yazısıdır.', '<p>Bu içerik admin panel üzerinden oluşturuldu.</p>', NULL, NULL, '0', '0', '1', '2026-04-29 14:34:00', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'Article', '2026-04-29 22:04:39', '2026-04-29 22:05:03');


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

-- Data for `services`

INSERT INTO `services` (`id`, `title`, `slug`, `summary`, `content`, `image`, `icon`, `sort_order`, `is_featured`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('1', 'Kurumsal Danışmanlık', 'kurumsal-danismanlik', 'İşletmenizin ihtiyaçlarına özel profesyonel danışmanlık hizmetleri.', '<p>Bu hizmet içeriği yönetim panelinden düzenlenebilir.</p>', NULL, 'briefcase', '1', '1', '1', '2026-04-29 14:00:22', 'Kurumsal Danışmanlık', 'İşletmenize özel kurumsal danışmanlık hizmetleri.', NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'Service', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `services` (`id`, `title`, `slug`, `summary`, `content`, `image`, `icon`, `sort_order`, `is_featured`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('2', 'Web Çözümleri', 'web-cozumleri', 'Modern, hızlı ve SEO uyumlu web çözümleri.', '<p>Bu hizmet içeriği yönetim panelinden düzenlenebilir.</p>', NULL, 'globe', '2', '1', '1', '2026-04-29 14:00:22', 'Web Çözümleri', 'Modern ve SEO uyumlu web çözümleri.', NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'Service', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `services` (`id`, `title`, `slug`, `summary`, `content`, `image`, `icon`, `sort_order`, `is_featured`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('3', 'Süreç Yönetimi', 'surec-yonetimi', 'Operasyonel süreçlerinizi daha verimli hale getiren çözümler.', '<p>Bu hizmet içeriği yönetim panelinden düzenlenebilir.</p>', NULL, 'settings', '3', '1', '1', '2026-04-29 14:00:22', 'Süreç Yönetimi', 'Operasyonel süreç yönetimi çözümleri.', NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'Service', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `services` (`id`, `title`, `slug`, `summary`, `content`, `image`, `icon`, `sort_order`, `is_featured`, `is_active`, `published_at`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES ('4', 'Test Hizmeti', 'test-hizmeti', 'Test Hizmeti', NULL, NULL, NULL, '0', '1', '1', '2026-04-29 14:30:00', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 'Service', '2026-04-29 14:30:29', '2026-04-29 14:30:29');


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

-- Data skipped for clean install.


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

-- Data for `site_settings`

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('1', 'site_name', 'ITCRAFT Corporate CMS', 'text', 'general', 'Site Adı', '1', '1', '2026-04-29 14:00:22', '2026-05-04 09:57:45', '#111827', '#b45309', '#d97706', '#111827', '#000000', '#ffffff', '#000000', '#ffffff', '#000000', '#374151', '#111827', 'Inter, Arial, sans-serif', '0px', '8px', '1200px', 'none', '#ffffff', '#d1d5db', '#f5f5f5', '#fbbf24', '#27272a', '#fbbf24', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('2', 'site_slogan', 'Laravel tabanlı kurumsal web altyapısı', 'text', 'general', 'Site Sloganı', '2', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('3', 'site_description', 'Sınırsız sayfa destekli, SEO uyumlu, dinamik renk yönetimli kurumsal web sitesi altyapısı.', 'textarea', 'general', 'Site Açıklaması', '3', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('4', 'logo', 'https://www.fortigateturkiye.com/img/Fortigate-Turkiye-Logo-Guncel.png', 'text', 'general', 'Logo URL / Path', '4', '1', '2026-04-29 14:00:22', '2026-04-29 15:14:07', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('5', 'favicon', 'https://www.itcraft.com.tr/wp-content/uploads/2025/09/itcraft-logo-beyaz.webp', 'text', 'general', 'Favicon URL / Path', '5', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('6', 'primary_color', '#0f172a', 'color', 'colors', 'Ana Renk', '1', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('7', 'secondary_color', '#1e293b', 'color', 'colors', 'İkincil Renk', '2', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('8', 'accent_color', '#c9a227', 'color', 'colors', 'Vurgu Rengi', '3', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('9', 'background_color', '#ffffff', 'color', 'colors', 'Arka Plan Rengi', '4', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('10', 'text_color', '#111827', 'color', 'colors', 'Metin Rengi', '5', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('11', 'header_color', '#ffffff', 'color', 'colors', 'Header Rengi', '6', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('12', 'footer_color', '#0f172a', 'color', 'colors', 'Footer Rengi', '7', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('13', 'button_color', '#0f172a', 'color', 'colors', 'Buton Rengi', '8', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('14', 'button_text_color', '#ffffff', 'color', 'theme', 'Buton Yazı Rengi', '18', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('15', 'phone', '+90 212 000 00 00', 'text', 'contact', 'Telefon', '2', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('16', 'email', 'info@example.com', 'email', 'contact', 'E-posta', '1', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('17', 'address', 'İstanbul, Türkiye', 'textarea', 'contact', 'Adres', '3', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('18', 'whatsapp', '', 'text', 'contact', 'WhatsApp', '33', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('19', 'google_maps_iframe', '', 'textarea', 'contact', 'Google Maps Iframe', '34', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('20', 'linkedin_url', 'https://www.linkedin.com/company/itcraft-bilgi-teknolojileri/', 'text', 'social', 'LinkedIn URL', '1', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('21', 'instagram_url', NULL, 'text', 'social', 'Instagram URL', '2', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('22', 'facebook_url', NULL, 'text', 'social', 'Facebook URL', '3', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('23', 'youtube_url', NULL, 'text', 'social', 'YouTube URL', '5', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('24', 'default_meta_title', 'ITCRAFT Corporate CMS', 'text', 'seo', 'Varsayılan Meta Başlık', '1', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('25', 'default_meta_description', 'SEO uyumlu, hızlı ve yönetilebilir kurumsal web sitesi altyapısı.', 'textarea', 'seo', 'Varsayılan Meta Açıklama', '2', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('26', 'default_meta_keywords', 'kurumsal web sitesi, laravel cms, seo uyumlu web sitesi', 'text', 'seo', 'Varsayılan Meta Anahtar Kelimeler', '3', '1', '2026-04-29 14:00:22', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('27', 'working_hours', NULL, 'text', 'contact', 'Çalışma Saatleri', '4', '1', '2026-04-29 15:12:55', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('28', 'default_og_image', NULL, 'text', 'seo', 'Varsayılan OG Görseli', '4', '1', '2026-04-29 15:12:55', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('29', 'x_url', NULL, 'text', 'social', 'X / Twitter URL', '4', '1', '2026-04-29 15:12:55', '2026-04-29 15:12:55', '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('30', 'mail_enabled', '0', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('31', 'mail_host', '', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('32', 'mail_port', '587', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('33', 'mail_username', '', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('34', 'mail_password', '', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('35', 'mail_encryption', 'tls', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('36', 'mail_from_address', '', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('37', 'mail_from_name', '', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `label`, `sort_order`, `is_public`, `created_at`, `updated_at`, `theme_primary_color`, `theme_secondary_color`, `theme_accent_color`, `theme_button_color`, `theme_button_hover_color`, `theme_header_bg`, `theme_footer_bg`, `theme_body_bg`, `theme_heading_color`, `theme_text_color`, `theme_link_color`, `theme_font_family`, `theme_button_radius`, `theme_card_radius`, `theme_container_width`, `theme_shadow_level`, `theme_footer_heading_color`, `theme_footer_text_color`, `theme_footer_link_color`, `theme_footer_link_hover_color`, `theme_footer_border_color`, `theme_footer_social_color`, `theme_slider_badge_bg`, `theme_slider_badge_text`, `theme_slider_badge_dot`, `theme_slider_dot_active`, `theme_slider_dot_passive`, `theme_slider_accent_color`) VALUES ('38', 'contact_recipient_email', '', 'text', 'general', NULL, '0', '1', NULL, NULL, '#1d4ed8', '#f59e0b', '#0ea5e9', '#1d4ed8', '#1e40af', '#ffffff', '#111827', '#ffffff', '#111827', '#374151', '#1d4ed8', 'Inter, Arial, sans-serif', '12px', '18px', '1200px', 'medium', '#ffffff', '#d1d5db', '#e5e7eb', '#ffffff', '#374151', '#ffffff', '#1e293b', '#ffffff', '#0ea5e9', '#0ea5e9', '#94a3b8', '#0ea5e9');


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

-- Data for `sliders`

INSERT INTO `sliders` (`id`, `title`, `subtitle`, `description`, `image`, `button_text`, `button_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Kurumsal Web Altyapınızı Modernleştirin', 'Laravel tabanlı hızlı ve yönetilebilir sistem', 'Sınırsız sayfa, SEO yönetimi ve dinamik renk desteği ile kurumsal web sitenizi kolayca yönetin.', '', 'Detaylı Bilgi', '/hakkimizda', '1', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `sliders` (`id`, `title`, `subtitle`, `description`, `image`, `button_text`, `button_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'SEO Uyumlu ve Hafif Yapı', 'Temiz kod, sade yönetim paneli', 'WordPress bağımlılığı olmadan hızlı, sade ve yönetilebilir bir kurumsal web deneyimi sunun.', '', 'Hizmetleri İncele', '/hizmetler', '2', '1', '2026-04-29 14:00:22', '2026-04-29 14:00:22');
INSERT INTO `sliders` (`id`, `title`, `subtitle`, `description`, `image`, `button_text`, `button_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Yeni Nesil Kurumsal Web Altyapısı', 'Hızlı, sade ve SEO uyumlu CMS', 'Kurumsal web sitenizi panel üzerinden kolayca yönetin.', 'https://www.itcraft.com.tr/wp-content/uploads/2026/01/anasayfa-slogan-yeni.png', NULL, NULL, '3', '1', '2026-04-29 14:51:53', '2026-04-29 14:53:51');


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

-- No data.


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

-- Data skipped for clean install.

SET FOREIGN_KEY_CHECKS=1;
