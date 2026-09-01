-- ========================================================
-- ESTILO BOUTIQUE COMPLETE DATABASE EXPORT DUMP
-- Generated: 2026-09-01 07:28:07
-- Target Database: boutique_app
-- Contains all tables: Users, Products, Categories, Orders, Images, Coupons, Reviews, Sales Associates
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `subcategories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`subcategories`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `categories`
INSERT INTO `categories` (`id`, `slug`, `name`, `tagline`, `image`, `subcategories`, `created_at`, `updated_at`) VALUES ('1', 'kurtis', 'Kurtis & Suits', 'Timeless Grace & Modern Cuts', '/images/categories/kurtis-suits.jpg', '[\"Designer Kurtis\",\"Cotton Kurtis\",\"Chikankari Kurtis\",\"Straight Kurtis\",\"Printed Kurtis\",\"Anarkali Suits\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `categories` (`id`, `slug`, `name`, `tagline`, `image`, `subcategories`, `created_at`, `updated_at`) VALUES ('2', 'sarees', 'Luxury Sarees', 'Six Yards of Royal Heritage', '/images/categories/luxury-sarees.jpg', '[\"Banarasi Sarees\",\"Silk Sarees\",\"Organza Sarees\",\"Cotton Sarees\",\"Linen Sarees\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `categories` (`id`, `slug`, `name`, `tagline`, `image`, `subcategories`, `created_at`, `updated_at`) VALUES ('3', 'coord-sets', 'Co-Ord Sets', 'Effortless Chic & Modern Ethnic', '/images/categories/coord-sets.jpg', '[\"Boutique Co-Ords\",\"Silk Co-Ords\",\"Printed Co-Ords\",\"Festive Co-Ords\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `categories` (`id`, `slug`, `name`, `tagline`, `image`, `subcategories`, `created_at`, `updated_at`) VALUES ('4', 'ethnic-dresses', 'Ethnic & Boutique Dresses', 'Fusion Elegance for Every Affair', '/images/categories/ethnic-dresses.jpg', '[\"Ethnic Dresses\",\"Boutique Dresses\",\"Party Dresses\",\"Maxi Dresses\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `categories` (`id`, `slug`, `name`, `tagline`, `image`, `subcategories`, `created_at`, `updated_at`) VALUES ('5', 'festive-wedding', 'Festive & Wedding Couture', 'Grand Celebrations & Bridal Radiance', '/images/categories/festive-wedding.jpg', '[\"Wedding Collection\",\"Festive Wear\",\"Heavy Anarkalis\",\"Lehenga Sarees\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');

-- --------------------------------------------------------
-- Table structure for table `coupons`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `discount_type` varchar(255) NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(8,2) NOT NULL DEFAULT 10.00,
  `min_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `campaign_type` varchar(255) NOT NULL DEFAULT 'festival',
  `valid_until` date DEFAULT NULL,
  `usage_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `failed_jobs`
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

-- --------------------------------------------------------
-- Table structure for table `images`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `images_slug_unique` (`slug`),
  KEY `images_category_section_index` (`category`,`section`),
  KEY `images_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `images`
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('1', 'Hero Main Model', 'hero-main', '/images/hero/hero-main.jpg', 'hero', 'home-hero', 'Estilo Wear — Model wearing Luxury Royal Kurti', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('2', 'Circle — New Arrivals', 'circle-new-arrivals', '/images/circles/new-arrivals.jpg', 'circle', 'home-circles', 'New Arrivals', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('3', 'Circle — Kurtis', 'circle-kurtis', '/images/circles/kurtis.jpg', 'circle', 'home-circles', 'Kurtis', NULL, NULL, '1', '2', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('4', 'Circle — Cotton Kurtis', 'circle-cotton-kurtis', '/images/circles/cotton-kurtis.jpg', 'circle', 'home-circles', 'Cotton Kurtis', NULL, NULL, '1', '3', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('5', 'Circle — Designer Sarees', 'circle-designer-sarees', '/images/circles/designer-sarees.jpg', 'circle', 'home-circles', 'Designer Sarees', NULL, NULL, '1', '4', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('6', 'Circle — Anarkali', 'circle-anarkali', '/images/circles/anarkali.jpg', 'circle', 'home-circles', 'Anarkali', NULL, NULL, '1', '5', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('7', 'Circle — Co-Ord Sets', 'circle-coord-sets', '/images/circles/coord-sets.jpg', 'circle', 'home-circles', 'Co-Ord Sets', NULL, NULL, '1', '6', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('8', 'Circle — Wedding Collection', 'circle-wedding', '/images/circles/wedding.jpg', 'circle', 'home-circles', 'Wedding Collection', NULL, NULL, '1', '7', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('9', 'Circle — Festive Wear', 'circle-festive', '/images/circles/festive.jpg', 'circle', 'home-circles', 'Festive Wear', NULL, NULL, '1', '8', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('10', 'Circle — Office Wear', 'circle-office', '/images/circles/office.jpg', 'circle', 'home-circles', 'Office Wear', NULL, NULL, '1', '9', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('11', 'Circle — Party Wear', 'circle-party', '/images/circles/party.jpg', 'circle', 'home-circles', 'Party Wear', NULL, NULL, '1', '10', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('12', 'Circle — Ethnic Dresses', 'circle-ethnic', '/images/circles/ethnic-dresses.jpg', 'circle', 'home-circles', 'Ethnic Dresses', NULL, NULL, '1', '11', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('13', 'Circle — Designer Collection', 'circle-designer', '/images/circles/designer-collection.jpg', 'circle', 'home-circles', 'Designer Collection', NULL, NULL, '1', '12', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('14', 'Category — Kurtis & Suits', 'cat-kurtis', '/images/categories/kurtis-suits.jpg', 'category', 'home-categories', 'Kurtis & Suits', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('15', 'Category — Luxury Sarees', 'cat-sarees', '/images/categories/luxury-sarees.jpg', 'category', 'home-categories', 'Luxury Sarees', NULL, NULL, '1', '2', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('16', 'Category — Co-Ord Sets', 'cat-coord', '/images/categories/coord-sets.jpg', 'category', 'home-categories', 'Co-Ord Sets', NULL, NULL, '1', '3', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('17', 'Category — Ethnic Dresses', 'cat-ethnic', '/images/categories/ethnic-dresses.jpg', 'category', 'home-categories', 'Ethnic & Boutique Dresses', NULL, NULL, '1', '4', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('18', 'Category — Festive & Wedding', 'cat-festive-wedding', '/images/categories/festive-wedding.jpg', 'category', 'home-categories', 'Festive & Wedding Couture', NULL, NULL, '1', '5', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('19', 'Product — Chikankari Anarkali', 'product-est-001', '/images/products/est-001-chikankari-anarkali.jpg', 'product', 'products', 'Gulzar Handcrafted Chikankari Anarkali Set', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('20', 'Product — Banarasi Silk Saree', 'product-est-002', '/images/products/est-002-banarasi-saree.jpg', 'product', 'products', 'Varanasi Royal Zari Banarasi Silk Saree', NULL, NULL, '1', '2', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('21', 'Product — Organza Saree', 'product-est-003', '/images/products/est-003-organza-saree.jpg', 'product', 'products', 'Noor Hand-Painted Floral Organza Saree', NULL, NULL, '1', '3', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('22', 'Product — Peplum Co-Ord Set', 'product-est-004', '/images/products/est-004-coord-set.jpg', 'product', 'products', 'Raysha Silk Blend Printed Peplum Co-Ord Set', NULL, NULL, '1', '4', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('23', 'Product — Cotton Straight Kurti', 'product-est-005', '/images/products/est-005-cotton-kurti.jpg', 'product', 'products', 'Aarya Hand Block Printed Cotton Straight Kurti', NULL, NULL, '1', '5', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('24', 'Product — Zardozi Silk Anarkali', 'product-est-006', '/images/products/est-006-silk-anarkali.jpg', 'product', 'products', 'Sultana Royal Zardozi Embroidered Silk Anarkali', NULL, NULL, '1', '6', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('25', 'Product — Chanderi Boutique Dress', 'product-est-007', '/images/products/est-007-boutique-dress.jpg', 'product', 'products', 'Kashvi Chanderi Silk Foil Printed Boutique Dress', NULL, NULL, '1', '7', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('26', 'Product — Handloom Linen Saree', 'product-est-008', '/images/products/est-008-linen-saree.jpg', 'product', 'products', 'Manjari Organic Handloom Linen Saree', NULL, NULL, '1', '8', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('27', 'Product — Georgette Designer Kurti', 'product-est-009', '/images/products/est-009-designer-kurti.jpg', 'product', 'products', 'Reeva Sequin Embroidered Georgette Designer Kurti', NULL, NULL, '1', '9', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('28', 'Product — Mulmul Jamdani Saree', 'product-est-010', '/images/products/est-010-jamdani-saree.jpg', 'product', 'products', 'Meera Handloom Mulmul Cotton Jamdani Saree', NULL, NULL, '1', '10', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('29', 'Product — Angrakha Ethnic Dress', 'product-est-011', '/images/products/est-011-ethnic-dress.jpg', 'product', 'products', 'Tarang Printed Angrakha Style Ethnic Dress', NULL, NULL, '1', '11', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('30', 'Product — Kanjivaram Silk Saree', 'product-est-012', '/images/products/est-012-kanjivaram-saree.jpg', 'product', 'products', 'Bhavya Pure Kanjivaram Golden Zari Silk Saree', NULL, NULL, '1', '12', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('31', 'Editorial — Chikankari Banner', 'editorial-chikankari', '/images/editorial/chikankari-banner.jpg', 'editorial', 'home-editorial', 'Lucknowi Chikankari Artistry', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('32', 'Occasion — Wedding', 'occasion-wedding', '/images/occasions/wedding.jpg', 'occasion', 'home-occasions', 'Wedding Collection', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('33', 'Occasion — Festive', 'occasion-festive', '/images/occasions/festive.jpg', 'occasion', 'home-occasions', 'Festive Wear', NULL, NULL, '1', '2', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('34', 'Occasion — Office', 'occasion-office', '/images/occasions/office.jpg', 'occasion', 'home-occasions', 'Office Wear', NULL, NULL, '1', '3', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('35', 'Occasion — Casual', 'occasion-casual', '/images/occasions/casual.jpg', 'occasion', 'home-occasions', 'Casual Wear', NULL, NULL, '1', '4', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('36', 'Occasion — Party', 'occasion-party', '/images/occasions/party.jpg', 'occasion', 'home-occasions', 'Party Wear', NULL, NULL, '1', '5', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('37', 'Avatar — Ananya Sharma', 'avatar-ananya', '/images/testimonials/ananya.jpg', 'testimonial', 'home-testimonials', 'Ananya Sharma', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('38', 'Avatar — Priyanka Sen', 'avatar-priyanka', '/images/testimonials/priyanka.jpg', 'testimonial', 'home-testimonials', 'Priyanka Sen', NULL, NULL, '1', '2', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('39', 'Avatar — Dr. Radhika Menon', 'avatar-radhika', '/images/testimonials/radhika.jpg', 'testimonial', 'home-testimonials', 'Dr. Radhika Menon', NULL, NULL, '1', '3', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('40', 'Instagram — Look 1', 'insta-1', '/images/instagram/look-1.jpg', 'instagram', 'home-instagram', 'Instagram Look 1 #EstiloWomen', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('41', 'Instagram — Look 2', 'insta-2', '/images/instagram/look-2.jpg', 'instagram', 'home-instagram', 'Instagram Look 2 #SlayEveryLook', NULL, NULL, '1', '2', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('42', 'Instagram — Look 3', 'insta-3', '/images/instagram/look-3.jpg', 'instagram', 'home-instagram', 'Instagram Look 3 #ChikankariLove', NULL, NULL, '1', '3', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('43', 'Instagram — Look 4', 'insta-4', '/images/instagram/look-4.jpg', 'instagram', 'home-instagram', 'Instagram Look 4 #BoutiqueCouture', NULL, NULL, '1', '4', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `images` (`id`, `name`, `slug`, `url`, `category`, `section`, `alt_text`, `width`, `height`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('44', 'Logo', 'site-logo', '/images/hero/hero-main.jpg', 'branding', 'global', 'Estilo Wear Logo', NULL, NULL, '1', '1', '2026-08-18 09:52:25', '2026-08-18 09:52:25');

-- --------------------------------------------------------
-- Table structure for table `job_batches`
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

-- --------------------------------------------------------
-- Table structure for table `jobs`
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

-- --------------------------------------------------------
-- Table structure for table `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_08_11_151849_create_personal_access_tokens_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_08_12_082038_add_role_to_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_08_12_143607_create_products_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_08_12_143608_create_categories_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_08_12_144000_create_orders_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_08_12_144001_create_order_items_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_08_17_095213_create_images_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_08_20_140000_create_role_and_management_tables', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_08_20_180000_create_auth_tokens_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_08_27_120000_add_sales_price_to_products_table', '3');

-- --------------------------------------------------------
-- Table structure for table `order_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `est_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'INR',
  `payment_id` varchar(255) DEFAULT NULL,
  `razorpay_order_id` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_no_unique` (`order_no`),
  KEY `orders_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `orders`
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('1', 'EST-308973', NULL, 'arpita Gaikwad', 'arpitagaikwad93@gmail.com', '9999999999', 'निगडी मार्ग\nnear Akurdi Railway Station Road\nSector No. 26, Pradhikaran, Nigdi', 'Pimpri-Chinchwad', 'Maharashtra', '411044', '2698.00', '0.00', '0.00', '2698.00', 'INR', 'UPI-CONFIRMED', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-004\\\",\\\"name\\\":\\\"Raysha Silk Blend Printed Peplum Co-Ord Set\\\",\\\"price\\\":1499,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-004-coord-set.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Co-Ord Sets\\\"},{\\\"id\\\":\\\"est-009\\\",\\\"name\\\":\\\"Reeva Sequin Embroidered Georgette Designer Kurti\\\",\\\"price\\\":1199,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-009-designer-kurti.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Designer Kurtis\\\"}]\"', 'Payment Mode: UPI', '2026-08-31 10:54:30', '2026-08-31 10:54:30');
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('2', 'EST-775728', NULL, 'arpita Gaikwad', 'arpitagaikwad93@gmail.com', '9999999999', 'निगडी मार्ग\nnear Akurdi Railway Station Road\nSector No. 26, Pradhikaran, Nigdi', 'Pimpri-Chinchwad', 'Maharashtra', '411044', '2698.00', '0.00', '0.00', '2698.00', 'INR', 'COD-PENDING', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-004\\\",\\\"name\\\":\\\"Raysha Silk Blend Printed Peplum Co-Ord Set\\\",\\\"price\\\":1499,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-004-coord-set.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Co-Ord Sets\\\"},{\\\"id\\\":\\\"est-009\\\",\\\"name\\\":\\\"Reeva Sequin Embroidered Georgette Designer Kurti\\\",\\\"price\\\":1199,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-009-designer-kurti.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Designer Kurtis\\\"}]\"', 'Payment Mode: COD', '2026-08-31 10:54:31', '2026-08-31 10:54:31');
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('3', 'EST-239045', NULL, 'Arpita Pratap Gaikwad', 'arpitagaikwad93@gmail.com', '+919373872264', 'near om supermarket', 'Pune', 'Maharashtra', '411044', '6097.00', '0.00', '0.00', '6097.00', 'INR', 'UPI-CONFIRMED', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-003\\\",\\\"name\\\":\\\"Noor Hand-Painted Floral Organza Saree\\\",\\\"price\\\":1699,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-003-organza-saree.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Organza Sarees\\\"},{\\\"id\\\":\\\"est-001\\\",\\\"name\\\":\\\"Gulzar Handcrafted Chikankari Anarkali Set\\\",\\\"price\\\":1899,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-001-chikankari-anarkali.jpg\\\",\\\"color\\\":\\\"Antique Rose\\\",\\\"size\\\":\\\"XS\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Chikankari Kurtis\\\"},{\\\"id\\\":\\\"est-002\\\",\\\"name\\\":\\\"Varanasi Royal Zari Banarasi Silk Saree\\\",\\\"price\\\":2499,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-002-banarasi-saree.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Banarasi Sarees\\\"}]\"', 'Payment Mode: UPI', '2026-09-01 06:13:50', '2026-09-01 06:13:50');
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('4', 'EST-994787', NULL, 'Arpita Pratap Gaikwad', 'arpitagaikwad93@gmail.com', '+919373872264', 'near om supermarket', 'Pune', 'Maharashtra', '411044', '6097.00', '0.00', '0.00', '6097.00', 'INR', 'UPI-CONFIRMED', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-003\\\",\\\"name\\\":\\\"Noor Hand-Painted Floral Organza Saree\\\",\\\"price\\\":1699,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-003-organza-saree.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Organza Sarees\\\"},{\\\"id\\\":\\\"est-001\\\",\\\"name\\\":\\\"Gulzar Handcrafted Chikankari Anarkali Set\\\",\\\"price\\\":1899,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-001-chikankari-anarkali.jpg\\\",\\\"color\\\":\\\"Antique Rose\\\",\\\"size\\\":\\\"XS\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Chikankari Kurtis\\\"},{\\\"id\\\":\\\"est-002\\\",\\\"name\\\":\\\"Varanasi Royal Zari Banarasi Silk Saree\\\",\\\"price\\\":2499,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-002-banarasi-saree.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Banarasi Sarees\\\"}]\"', 'Payment Mode: UPI', '2026-09-01 06:13:51', '2026-09-01 06:13:51');
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('5', 'EST-405147', NULL, 'Arpita Pratap Gaikwad', 'arpitagaikwad93@gmail.com', '+919373872264', 'near om supermarket', 'Pune', 'Maharashtra', '411044', '6097.00', '0.00', '0.00', '6097.00', 'INR', 'UPI-CONFIRMED', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-003\\\",\\\"name\\\":\\\"Noor Hand-Painted Floral Organza Saree\\\",\\\"price\\\":1699,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-003-organza-saree.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Organza Sarees\\\"},{\\\"id\\\":\\\"est-001\\\",\\\"name\\\":\\\"Gulzar Handcrafted Chikankari Anarkali Set\\\",\\\"price\\\":1899,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-001-chikankari-anarkali.jpg\\\",\\\"color\\\":\\\"Antique Rose\\\",\\\"size\\\":\\\"XS\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Chikankari Kurtis\\\"},{\\\"id\\\":\\\"est-002\\\",\\\"name\\\":\\\"Varanasi Royal Zari Banarasi Silk Saree\\\",\\\"price\\\":2499,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-002-banarasi-saree.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Banarasi Sarees\\\"}]\"', 'Payment Mode: UPI', '2026-09-01 06:13:51', '2026-09-01 06:13:51');
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('6', 'EST-422188', '1', 'Test User', 'test@example.com', '+919373872264', 'near om supermarket', 'Pune', 'Maharashtra', '411044', '2499.00', '0.00', '0.00', '2499.00', 'INR', 'UPI-CONFIRMED', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-002\\\",\\\"name\\\":\\\"Varanasi Royal Zari Banarasi Silk Saree\\\",\\\"price\\\":2499,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-002-banarasi-saree.jpg\\\",\\\"color\\\":\\\"Blush Pink\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Banarasi Sarees\\\"}]\"', 'Payment Mode: UPI', '2026-09-01 06:14:48', '2026-09-01 06:14:48');
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('7', 'EST-117300', '1', 'Test User', 'test@example.com', '+919373872264', 'near om supermarket', 'Pune', 'India', '411044', '1499.00', '199.00', '0.00', '1698.00', 'INR', 'UPI-CONFIRMED', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-004\\\",\\\"name\\\":\\\"Raysha Silk Blend Printed Peplum Co-Ord Set\\\",\\\"price\\\":1499,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-004-coord-set.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Co-Ord Sets\\\"}]\"', 'Payment Mode: UPI', '2026-09-01 06:18:40', '2026-09-01 06:18:40');
INSERT INTO `orders` (`id`, `order_no`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `subtotal`, `shipping`, `discount`, `total`, `currency`, `payment_id`, `razorpay_order_id`, `signature`, `status`, `items`, `note`, `created_at`, `updated_at`) VALUES ('8', 'EST-485615', '1', 'Test User', 'test@example.com', '+919373872264', 'near om supermarket', 'Pune', 'India', '411044', '1699.00', '199.00', '0.00', '1898.00', 'INR', 'UPI-CONFIRMED', NULL, NULL, 'confirmed', '\"[{\\\"id\\\":\\\"est-003\\\",\\\"name\\\":\\\"Noor Hand-Painted Floral Organza Saree\\\",\\\"price\\\":1699,\\\"image\\\":\\\"\\\\\\/images\\\\\\/products\\\\\\/est-003-organza-saree.jpg\\\",\\\"color\\\":\\\"Standard\\\",\\\"size\\\":\\\"Free Size\\\",\\\"qty\\\":1,\\\"category\\\":\\\"Organza Sarees\\\"}]\"', 'Payment Mode: UPI', '2026-09-01 07:01:55', '2026-09-01 07:01:55');

-- --------------------------------------------------------
-- Table structure for table `otp_codes`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `otp_codes`;
CREATE TABLE `otp_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL,
  `code` varchar(10) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otp_codes_phone_index` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `personal_access_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `est_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `main_category` varchar(255) NOT NULL,
  `sub_category` varchar(255) NOT NULL,
  `fabric` varchar(255) NOT NULL,
  `occasion` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sales_price` decimal(10,2) DEFAULT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `discount` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `review_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `in_stock` tinyint(1) NOT NULL DEFAULT 1,
  `sku` varchar(255) NOT NULL,
  `colors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`colors`)),
  `sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sizes`)),
  `description` text NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`details`)),
  `care` text NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_est_id_unique` (`est_id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `products`
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('1', 'est-001', 'Gulzar Handcrafted Chikankari Anarkali Set', 'Chikankari Kurtis', 'Kurtis', 'Anarkali Suits', 'Chikankari Cotton', 'Festive Wear', '1899.00', NULL, '2599.00', '27', '4.90', '42', '1', '1', '1', '1', '1', 'EST-GUL-001', '[{\"name\":\"Antique Rose\",\"hex\":\"#C87D87\"},{\"name\":\"Bisque\",\"hex\":\"#E5BCA9\"},{\"name\":\"Pure Ivory\",\"hex\":\"#FFF9F5\"}]', '[\"XS\",\"S\",\"M\",\"L\",\"XL\",\"XXL\"]', 'Imbued with royal Nawabi elegance, our Gulzar Anarkali Set is meticulously hand-embroidered by artisan women in Lucknow. Crafted from breathable cotton mulmul with delicate shadow work and subtle sequin accents.', '[\"Fabric: 100% Breathable Cotton Mulmul\",\"Work: Handcrafted Lucknowi Chikankari & Mukaish Sequins\",\"Includes: Anarkali Kurta, Churidar & Chiffon Dupatta\",\"Neckline: Graceful Sweetheart Neckline with Potli Buttons\",\"Sleeve: Full Length Sheer Embroidered Sleeves\"]', 'Dry Clean Only. Cool Iron on reverse side.', '[\"\\/images\\/products\\/est-001-chikankari-anarkali.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('2', 'est-002', 'Varanasi Royal Zari Banarasi Silk Saree', 'Banarasi Sarees', 'Sarees', 'Silk Sarees', 'Banarasi Brocade', 'Wedding Collection', '2499.00', NULL, '3499.00', '29', '5.00', '38', '1', '1', '1', '1', '1', 'EST-BAN-002', '[{\"name\":\"Blush Pink\",\"hex\":\"#F0C4CB\"},{\"name\":\"Royal Emerald\",\"hex\":\"#6B7556\"},{\"name\":\"Crimson Red\",\"hex\":\"#A25964\"}]', '[\"Free Size\"]', 'A masterpiece from the heritage looms of Varanasi. Handwoven in pure Katan silk with gold electro-plated Kadwa weaves, featuring intricate floral Jaal motifs and a stately Zari pallu.', '[\"Saree Fabric: Pure Katan Silk\",\"Blouse Piece: Included (Unstitched 80cm Brocade)\",\"Weave: Kadwa Handloom Zari Weave\",\"Saree Length: 5.5 Meters\",\"Certificate of Authenticity Included\"]', 'Dry Clean Only. Preserve wrapped in pure cotton muslin cloth.', '[\"\\/images\\/products\\/est-002-banarasi-saree.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('3', 'est-003', 'Noor Hand-Painted Floral Organza Saree', 'Organza Sarees', 'Sarees', 'Organza Sarees', 'Organza', 'Party Wear', '1699.00', NULL, '2299.00', '26', '4.80', '29', '1', '0', '1', '1', '1', 'EST-ORG-003', '[{\"name\":\"Champagne Gold\",\"hex\":\"#FBEAD6\"},{\"name\":\"Rose Blush\",\"hex\":\"#F0C4CB\"}]', '[\"Free Size\"]', 'Ethereal and whisper-light. Hand-painted botanical motifs gracefully flow across pure sheer organza fabric, framed with handmade Gota Patti scallop border work.', '[\"Fabric: 100% Sheer Mulberry Organza Silk\",\"Work: Artisan Hand-painting & Scalloped Gota Edge\",\"Blouse Piece: Included (Raw Silk 80cm embroidered)\",\"Weight: Ultra Light (approx 350 grams)\"]', 'Dry Clean Only.', '[\"\\/images\\/products\\/est-003-organza-saree.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('4', 'est-004', 'Raysha Silk Blend Printed Peplum Co-Ord Set', 'Co-Ord Sets', 'Co-Ord Sets', 'Boutique Co-Ords', 'Pure Silk', 'Casual Wear', '1499.00', NULL, '1999.00', '25', '4.70', '31', '0', '1', '1', '0', '1', 'EST-COO-004', '[{\"name\":\"Dried Thyme Green\",\"hex\":\"#6B7556\"},{\"name\":\"Bisque Sand\",\"hex\":\"#E5BCA9\"}]', '[\"S\",\"M\",\"L\",\"XL\"]', 'Designed for the modern woman who craves statement elegance. Features a high-low structured peplum top tailored with elasticated flared trousers in soft art silk.', '[\"Fabric: Premium Soft Art Silk Blend\",\"Set Includes: Peplum Tunic Top & Straight Fit Pants\",\"Neckline: V-Neck with Dori Tassels\",\"Pockets: Dual side pockets on pants\"]', 'Gentle Hand Wash or Dry Clean.', '[\"\\/images\\/products\\/est-004-coord-set.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('5', 'est-005', 'Aarya Hand Block Printed Cotton Straight Kurti', 'Cotton Kurtis', 'Kurtis', 'Straight Kurtis', 'Mulmul Cotton', 'Office Wear', '1099.00', NULL, '1499.00', '27', '4.90', '54', '1', '1', '0', '1', '1', 'EST-STR-005', '[{\"name\":\"Sage Thyme\",\"hex\":\"#6B7556\"},{\"name\":\"Dusty Rose\",\"hex\":\"#C87D87\"}]', '[\"S\",\"M\",\"L\",\"XL\",\"XXL\"]', 'Artisanal Sanganeri hand block print crafted on breathable super-fine cotton. Designed for executive comfort with a flattering straight silhouette.', '[\"Fabric: 100% Organic Handloom Cotton\",\"Print: Traditional Bagru Wooden Block Print\",\"Fit: Straight Cut with Side Slits\",\"Length: Calf Length (44 inches)\"]', 'Hand wash separately with mild detergent.', '[\"\\/images\\/products\\/est-005-cotton-kurti.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('6', 'est-006', 'Sultana Royal Zardozi Embroidered Silk Anarkali', 'Anarkali Suits', 'Kurtis', 'Anarkali Suits', 'Pure Silk', 'Wedding Collection', '2399.00', NULL, '3299.00', '27', '5.00', '19', '1', '0', '0', '1', '1', 'EST-SUL-006', '[{\"name\":\"Antique Crimson\",\"hex\":\"#A25964\"},{\"name\":\"Rich Gold\",\"hex\":\"#D4AF37\"}]', '[\"S\",\"M\",\"L\",\"XL\"]', 'Heavy bridal couture. Intricately embellished with real French wire Dabka, sequins, and Kashmiri Tilla hand embroidery along the 48-kalidaar royal flared silhouette.', '[\"Fabric: Heavy Chanderi Silk with Shantoon Lining\",\"Flare: 4.5 Meters Dramatic Kalidar Gher\",\"Dupatta: Embroidered Pure Organza Dupatta with Kiran border\"]', 'Strictly Dry Clean Only.', '[\"\\/images\\/products\\/est-006-silk-anarkali.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('7', 'est-007', 'Kashvi Chanderi Silk Foil Printed Boutique Dress', 'Boutique Dresses', 'Ethnic Dresses', 'Boutique Dresses', 'Pure Silk', 'Party Wear', '1599.00', NULL, '2199.00', '27', '4.80', '33', '0', '1', '1', '0', '1', 'EST-KAS-007', '[{\"name\":\"Champagne Beige\",\"hex\":\"#FBEAD6\"},{\"name\":\"Rose Petal\",\"hex\":\"#F0C4CB\"}]', '[\"XS\",\"S\",\"M\",\"L\",\"XL\"]', 'A versatile silhouette designed for evening soirees and boutique parties.', '[\"Fabric: Chanderi Silk with metallic golden foil accents\",\"Length: Midi Length Flare Dress\",\"Belt: Detachable hand-embroidered waist belt included\"]', 'Dry Clean Only.', '[\"\\/images\\/products\\/est-007-boutique-dress.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('8', 'est-008', 'Manjari Organic Handloom Linen Saree', 'Linen Sarees', 'Sarees', 'Linen Sarees', 'Organic Linen', 'Casual Wear', '1299.00', NULL, '1799.00', '28', '4.70', '22', '0', '0', '0', '0', '1', 'EST-LIN-008', '[{\"name\":\"Dried Thyme\",\"hex\":\"#6B7556\"},{\"name\":\"Bisque Peach\",\"hex\":\"#E5BCA9\"}]', '[\"Free Size\"]', '100 count pure organic handspun linen saree with woven silver zari borders.', '[\"Fabric: 100 Count Organic Handloom Linen\",\"Blouse Piece: Contrast Linen Blouse Included\"]', 'Hand Wash with mild liquid detergent or Dry Clean.', '[\"\\/images\\/products\\/est-008-linen-saree.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('9', 'est-009', 'Reeva Sequin Embroidered Georgette Designer Kurti', 'Designer Kurtis', 'Kurtis', 'Designer Kurtis', 'Pure Silk', 'Party Wear', '1199.00', NULL, '1599.00', '25', '4.80', '36', '1', '0', '1', '1', '1', 'EST-REE-009', '[{\"name\":\"Antique Rose\",\"hex\":\"#C87D87\"},{\"name\":\"Midnight Ebony\",\"hex\":\"#1A1818\"}]', '[\"S\",\"M\",\"L\",\"XL\"]', 'Contemporary evening glamour.', '[\"Fabric: Poly-Georgette with Soft Crepe Lining\",\"Work: Micro Ton-sur-ton sequin clusters\"]', 'Dry Clean Recommended.', '[\"\\/images\\/products\\/est-009-designer-kurti.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('10', 'est-010', 'Meera Handloom Mulmul Cotton Jamdani Saree', 'Cotton Sarees', 'Sarees', 'Cotton Sarees', 'Mulmul Cotton', 'Office Wear', '1799.00', NULL, '2399.00', '25', '4.90', '27', '0', '1', '0', '0', '1', 'EST-JAM-010', '[{\"name\":\"Champagne Ivory\",\"hex\":\"#FBEAD6\"},{\"name\":\"Soft Coral\",\"hex\":\"#F0C4CB\"}]', '[\"Free Size\"]', 'Heritage Bengal Jamdani handloom weaving on featherlight mulmul cotton.', '[\"Fabric: Hand-spun Dhakai Mulmul Cotton\",\"Weave: Extra Weft Jamdani Floral Motif Weaving\"]', 'Dry clean or gentle cold water hand wash.', '[\"\\/images\\/products\\/est-010-jamdani-saree.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('11', 'est-011', 'Tarang Printed Angrakha Style Ethnic Dress', 'Ethnic Dresses', 'Ethnic Dresses', 'Ethnic Dresses', 'Chikankari Cotton', 'Festive Wear', '1399.00', NULL, '1899.00', '26', '4.90', '45', '1', '0', '1', '0', '1', 'EST-ANG-011', '[{\"name\":\"Thyme Green\",\"hex\":\"#6B7556\"},{\"name\":\"Antique Rose\",\"hex\":\"#C87D87\"}]', '[\"XS\",\"S\",\"M\",\"L\",\"XL\"]', 'Regal royal Angrakha neck wrap closure tied with opulent beaded latkans. Designed with tiered flares that cascade gracefully.', '[\"Fabric: Hand-Block Printed Cotton Silk\",\"Neckline: Angrakha Wrap with Handmade Fabric Ties\",\"Includes: Matching Cotton Slip\"]', 'Hand Wash or Dry Clean.', '[\"\\/images\\/products\\/est-011-ethnic-dress.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('12', 'est-012', 'Bhavya Pure Kanjivaram Golden Zari Silk Saree', 'Silk Sarees', 'Sarees', 'Silk Sarees', 'Pure Silk', 'Wedding Collection', '2299.00', NULL, '3199.00', '28', '5.00', '51', '1', '1', '1', '1', '1', 'EST-KAN-012', '[{\"name\":\"Antique Crimson\",\"hex\":\"#A25964\"},{\"name\":\"Imperial Gold\",\"hex\":\"#D4AF37\"}]', '[\"Free Size\"]', 'Handwoven in Kanchipuram with pure silver-gold electroplated Zari thread. Features traditional temple borders and grand peacock motifs.', '[\"Fabric: 100% Pure Mulberry Kanjivaram Silk\",\"Zari Quality: Tested Pure Gold Zari Weave\",\"Silk Mark Certified: Yes\"]', 'Dry Clean Only. Store in Cotton Saree Cover.', '[\"\\/images\\/products\\/est-012-kanjivaram-saree.jpg\"]', '2026-08-18 09:52:25', '2026-08-18 09:52:25');
INSERT INTO `products` (`id`, `est_id`, `name`, `category`, `main_category`, `sub_category`, `fabric`, `occasion`, `price`, `sales_price`, `old_price`, `discount`, `rating`, `review_count`, `is_new_arrival`, `is_best_seller`, `is_trending`, `is_featured`, `in_stock`, `sku`, `colors`, `sizes`, `description`, `details`, `care`, `images`, `created_at`, `updated_at`) VALUES ('13', 'est-013', 'Organza saree', 'Luxury Sarees', 'Women Couture', 'Luxury Sarees', 'Organza', 'Festive / Wedding', '2299.00', '2500.00', '2873.75', '20', '5.00', '1', '1', '0', '0', '0', '1', 'EST-DZKS-365', '[\"Rose Blush\",\"Royal Navy\",\"Golden Ochre\"]', '[\"XS\",\"S\",\"M\",\"L\",\"XL\",\"XXL\"]', 'special for events, functions', '{\"Craft\":\"Handloom Artisanal\",\"Origin\":\"Lucknow \\/ Varanasi\"}', 'Dry Clean Only. Steam iron on reverse.', '[\"\\/storage\\/products\\/pSXhDJuSf1h4sjZo14jdDqBiNu88ayflspAWlc4V.jpg\"]', '2026-08-27 08:58:11', '2026-08-27 08:58:11');

-- --------------------------------------------------------
-- Table structure for table `referral_sales`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `referral_sales`;
CREATE TABLE `referral_sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `associate_id` bigint(20) unsigned NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `sale_amount` decimal(10,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_earned` decimal(10,2) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referral_sales_associate_id_foreign` (`associate_id`),
  KEY `referral_sales_order_no_index` (`order_no`),
  CONSTRAINT `referral_sales_associate_id_foreign` FOREIGN KEY (`associate_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `reviews`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_est_id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_product_est_id_index` (`product_est_id`),
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `sessions`
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

-- Dumping data for table `sessions`
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('HQn0UaCsVlkWqzeiZyYx683d66Mo0DFW8siSvQbK', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZlpERVdqV043OFJvODBNbjl4YlY2QjV2YmdnRDYySVAyYU1qMlRGMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', '1788246865');

-- --------------------------------------------------------
-- Table structure for table `user_logins`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `user_logins`;
CREATE TABLE `user_logins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `auth_method` varchar(255) NOT NULL DEFAULT 'email',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `logged_in_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_logins_user_id_foreign` (`user_id`),
  CONSTRAINT `user_logins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `user_logins`
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('1', '2', 'admin', 'email', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-27 05:19:06');
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('2', '3', 'sales_associate', 'email', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-27 05:57:04');
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('3', '1', 'customer', 'email', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-27 06:45:48');
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('4', '3', 'sales_associate', 'email', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-27 08:54:18');
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('5', '2', 'admin', 'email', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-27 08:55:15');
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('6', '1', 'customer', 'email', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 13; SM-G981B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', '2026-08-27 09:14:51');
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('7', '1', 'customer', 'email', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-31 08:41:41');
INSERT INTO `user_logins` (`id`, `user_id`, `role`, `auth_method`, `ip_address`, `user_agent`, `logged_in_at`) VALUES ('8', '1', 'customer', 'email', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-01 06:14:15');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `referral_code` varchar(255) DEFAULT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 10.00,
  `earnings` decimal(10,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `upi_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `referral_code`, `commission_rate`, `earnings`, `balance`, `upi_id`) VALUES ('1', 'Test User', 'test@example.com', NULL, NULL, '$2y$12$3YpXB13sDHgAYLgxri9pCe9sUmrpw2kO/gTb1XozSyXSZtnaTBDqO', NULL, '2026-08-18 09:52:25', '2026-08-18 09:52:25', 'customer', NULL, '10.00', '0.00', '0.00', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `referral_code`, `commission_rate`, `earnings`, `balance`, `upi_id`) VALUES ('2', 'Boutique Admin', 'admin@estilo.com', NULL, NULL, '$2y$12$CM3rmWHZt4cZHcktjOUHzewZDccrzvNEeGSRdo2lA51dbuo6BXOLS', NULL, '2026-08-18 09:52:26', '2026-08-18 09:52:26', 'admin', NULL, '10.00', '0.00', '0.00', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `referral_code`, `commission_rate`, `earnings`, `balance`, `upi_id`) VALUES ('3', 'Pooja Verma', 'associate@estilo.com', '9876543211', NULL, '$2y$12$AsopvmzGJQbtquoYdlXDbO/xH42AuqJUH2nGWev.ABcv2nRTo0LH2', NULL, '2026-08-27 05:57:04', '2026-08-27 05:57:04', 'sales_associate', 'ESTILO-SA01', '10.00', '0.00', '0.00', NULL);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
