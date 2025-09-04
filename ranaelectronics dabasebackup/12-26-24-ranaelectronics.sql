-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 26, 2024 at 04:31 PM
-- Server version: 10.6.20-MariaDB-cll-lve
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oraclefo_rana_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MICROWAVE OVEN', '2024-12-23 15:28:22', '2024-12-23 15:38:18', NULL),
(2, 'WASHING MACHINE', '2024-12-23 15:34:15', '2024-12-23 15:34:15', NULL),
(3, 'REFRIGRATOR', '2024-12-23 15:34:43', '2024-12-23 15:34:43', NULL),
(4, 'LED TV', '2024-12-23 15:35:16', '2024-12-23 15:35:16', NULL),
(5, 'REMORT', '2024-12-23 16:25:47', '2024-12-23 16:25:47', NULL),
(6, 'STAND', '2024-12-23 16:26:09', '2024-12-24 13:25:10', NULL),
(7, 'KITCHEN APPLIANCES', '2024-12-24 08:53:21', '2024-12-24 15:59:10', NULL),
(8, 'Juicer,Blender,Chopper and food factory', '2024-12-24 08:53:40', '2024-12-24 08:53:40', NULL),
(9, 'Room Coller/Fans', '2024-12-24 08:54:08', '2024-12-26 11:23:17', NULL),
(10, 'ELECTRONICS PARTS & ACCESSORIES', '2024-12-24 08:57:57', '2024-12-24 08:57:57', NULL),
(11, 'DISH TV', '2024-12-24 08:59:22', '2024-12-24 08:59:22', NULL),
(12, 'OLD ITEM', '2024-12-24 16:25:41', '2024-12-24 16:25:41', NULL),
(13, 'Hetter', '2024-12-25 12:15:59', '2024-12-25 12:15:59', NULL),
(14, 'Parts', '2024-12-25 15:02:26', '2024-12-25 15:02:26', NULL),
(15, 'FOAM', '2024-12-25 15:24:09', '2024-12-25 15:24:09', NULL),
(16, 'Solar Pannel and accessories', '2024-12-25 16:04:25', '2024-12-25 16:04:25', NULL),
(17, 'Water Hetter', '2024-12-25 16:13:12', '2024-12-26 11:22:44', NULL),
(18, 'Sewing Machine', '2024-12-26 09:09:05', '2024-12-26 09:09:05', NULL),
(19, 'stablizer', '2024-12-26 09:29:38', '2024-12-26 09:29:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `cnic` text NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `debit` varchar(255) NOT NULL DEFAULT '0',
  `credit` varchar(255) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `mobile_number`, `cnic`, `address`, `debit`, `credit`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'GHULAM ABBAS', '000000000', '0000000', 'KOT MOMIN', '0', '0', '2024-12-24 15:49:42', '2024-12-24 15:49:42', NULL),
(3, 'TOQEER ABBAS', '03238846735', '3840603710033', 'KOT MOMIN,SARGODHA,PUNJAB,PAKISTAN', '0', '0', '2024-12-25 14:22:40', '2024-12-25 14:22:40', NULL),
(4, 'NASIR SB', '03445532284', '3840103523769', 'P/o Box Same Chak No 09 SB', '0', '0', '2024-12-25 15:42:27', '2024-12-25 15:42:27', NULL),
(5, 'GHULAM SHABBIER', '0000000000', '3840118352041', 'KOT MOMIN', '0', '0', '2024-12-25 17:44:20', '2024-12-25 17:44:20', NULL),
(6, 'Ibrar Ahemad', '03497694381', '0000000000000', 'Kot momin', '0', '0', '2024-12-26 10:04:08', '2024-12-26 10:04:08', NULL),
(7, 'Ibrar Ahemad', '03497694381', '000000000000', 'Kot Momin', '0', '0', '2024-12-26 10:06:20', '2024-12-26 10:06:20', NULL),
(8, 'IKRAM ULLAH', '03456000630', '3840603867591', 'kOT MOMIN', '0', '0', '2024-12-26 12:16:03', '2024-12-26 12:16:03', NULL),
(9, 'WAQAS', '03457654505', '000000000001', 'Kot Momin', '0', '0', '2024-12-26 15:10:00', '2024-12-26 15:10:00', NULL),
(10, 'MUHAMMAD NAWAZ', '03441775269', '0000000000002', 'PULL MUHAMMAD KOT MOMIN', '0', '0', '2024-12-26 15:12:04', '2024-12-26 15:12:04', NULL),
(11, 'MUGHAL ELECTRONICS', '03450357540', '0000000000003', 'KOT MOMIN', '0', '0', '2024-12-26 17:04:32', '2024-12-26 17:22:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manual_payments`
--

CREATE TABLE `manual_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `manual_payments`
--

INSERT INTO `manual_payments` (`id`, `customer_id`, `payment_type`, `payment`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 11, 'You Give', 4900.00, '2024-12-26 17:22:36', '2024-12-26 17:22:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_12_11_160002_create_categories_table', 1),
(5, '2024_12_12_103359_create_products_table', 1),
(6, '2024_12_13_050858_create_suppliers_table', 1),
(7, '2024_12_14_052932_create_customers_table', 1),
(8, '2024_12_14_074110_create_transactions_table', 1),
(9, '2024_12_14_160018_create_sales_table', 1),
(10, '2024_12_20_233009_create_manual_payments_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `item_code` varchar(200) DEFAULT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `profit` varchar(100) DEFAULT NULL,
  `selling_price` varchar(100) DEFAULT NULL,
  `original_price` varchar(100) DEFAULT NULL,
  `packeges` varchar(100) DEFAULT NULL,
  `packing_price` varchar(100) DEFAULT NULL,
  `packing_qty` varchar(100) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `qty_sold` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `expiry_date` varchar(500) DEFAULT NULL,
  `date_arrival` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `supplier_id`, `item_code`, `item_name`, `profit`, `selling_price`, `original_price`, `packeges`, `packing_price`, `packing_qty`, `discount`, `qty_sold`, `qty`, `expiry_date`, `date_arrival`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1', '1', '20mxp6', 'microwave oven', NULL, '18700', '14000', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-23 15:33:08', '2024-12-25 14:44:26', NULL),
(2, '4', '1', 'C40K6FG', 'LED', NULL, '60000', '52000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-23 15:36:35', '2024-12-25 08:52:59', NULL),
(3, '3', '1', '368IAPA', 'refrigrator', NULL, '110000', '99000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-23 15:37:53', '2024-12-23 15:37:53', NULL),
(4, '2', '1', '80-BP12929S3', 'Washing machine', NULL, '155000', '135000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-23 15:41:00', '2024-12-23 15:41:00', NULL),
(5, '2', '1', '80-35', 'Washing machine(grey)', NULL, '22000', '18000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-23 15:42:40', '2024-12-23 15:42:40', NULL),
(6, '2', '2', 'RWM 202', 'RWM 202', NULL, '25000', '19800', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 13:39:25', '2024-12-24 15:55:11', NULL),
(7, '2', '2', 'RWM 203', 'RWM 203', NULL, '24000', '18900', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-24 13:41:06', '2024-12-24 15:48:04', NULL),
(8, '6', '11', '22*22', 'Refrigrator stand(22*22)', NULL, '800', '420', NULL, NULL, NULL, NULL, NULL, 59, NULL, NULL, '2024-12-24 16:11:28', '2024-12-24 16:15:28', NULL),
(9, '6', '11', '24*27', 'Refrigrator stand(24*27)', NULL, '1000', '550', NULL, NULL, NULL, NULL, NULL, 48, NULL, NULL, '2024-12-24 16:15:05', '2024-12-25 14:42:04', NULL),
(10, '6', '11', '23*25', 'Refrigrator stand(23*25)', NULL, '900', '480', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-24 16:16:57', '2024-12-24 16:16:57', NULL),
(11, '6', '11', '24', 'Refrigrator stand(steel rod)', NULL, '1200', '650', NULL, NULL, NULL, NULL, NULL, 23, NULL, NULL, '2024-12-24 16:19:25', '2024-12-24 16:19:25', NULL),
(12, '6', '11', '14*14', 'water dispenser stand', NULL, '500', '280', NULL, NULL, NULL, NULL, NULL, 24, NULL, NULL, '2024-12-24 16:21:12', '2024-12-24 16:21:12', NULL),
(13, '9', '2', '5576', 'ROOM COOLER(5576)', NULL, '24000', '17900', NULL, NULL, NULL, NULL, NULL, 15, NULL, NULL, '2024-12-24 16:31:43', '2024-12-24 16:31:43', NULL),
(14, '2', '2', 'RWM 970', 'Washing machine(970)', NULL, '20000', '15200', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 16:37:39', '2024-12-24 16:37:39', NULL),
(16, '2', '2', 'RWM 960', 'Washing machine', NULL, '19000', '14600', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 17:06:34', '2024-12-24 17:06:34', NULL),
(17, '2', '2', 'RWM 6100', 'Washing machine', NULL, '18500', '14500', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-24 17:07:37', '2024-12-26 13:31:57', NULL),
(18, '2', '2', 'RD 403', 'Spinner Machine', NULL, '15500', '12300', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 17:09:29', '2024-12-24 17:09:29', NULL),
(19, '2', '2', 'RWM 6150', 'Washing machine', NULL, '18500', '14500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-24 17:11:28', '2024-12-26 13:30:42', NULL),
(20, '2', '2', 'RD 365', 'SPINNER MACHINE', NULL, '19000', '14900', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-24 17:20:17', '2024-12-26 13:14:30', NULL),
(21, '2', '2', 'RWM 360', 'Washing machine', NULL, '19000', '14900', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-24 17:22:04', '2024-12-26 13:17:21', NULL),
(22, '2', '2', 'RWM 506', 'Washing machine', NULL, '26000', '20400', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 17:23:12', '2024-12-24 17:23:12', NULL),
(23, '2', '3', 'SUPER UNITED METEL', 'Washing machine', NULL, '18000', '13500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 17:34:35', '2024-12-24 17:34:35', NULL),
(24, '2', '2', 'RD 362', 'Spinner Machine', NULL, '15500', '12300', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-24 17:37:03', '2024-12-26 13:20:46', NULL),
(25, '2', '2', 'RWM 850', 'Washing machine', NULL, '16000', '11000', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-24 17:44:04', '2024-12-24 17:44:04', NULL),
(26, '2', '2', 'RD 276', 'Spinner Machine', NULL, '16000', '14000', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-24 17:46:17', '2024-12-24 17:46:17', NULL),
(27, '2', '2', 'RWM 776', 'Washing machine', NULL, '21000', '16700', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-24 17:47:01', '2024-12-26 13:31:40', NULL),
(28, '2', '2', 'RWM 320', 'Washing machine', NULL, '18000', '13900', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-24 17:49:17', '2024-12-26 13:30:27', NULL),
(29, '2', '2', 'RWM 404', 'Washing machine', NULL, '18500', '14900', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 17:50:33', '2024-12-24 17:50:33', NULL),
(30, '2', '2', 'RD 503', 'Spinner Machine', NULL, '25000', '19600', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-24 17:51:48', '2024-12-24 17:51:48', NULL),
(31, '7', '14', '2mm', 'GAS PIPE', NULL, '25', '10', NULL, NULL, NULL, NULL, NULL, 1300, NULL, NULL, '2024-12-25 08:46:22', '2024-12-25 08:46:22', NULL),
(32, '7', '14', '2kg', 'cylinder', NULL, '2500', '1600', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 08:48:56', '2024-12-25 08:48:56', NULL),
(33, '7', '14', '4kg', 'cylinder', NULL, '3000', '2200', NULL, NULL, NULL, NULL, NULL, 11, NULL, NULL, '2024-12-25 08:50:36', '2024-12-26 12:23:16', NULL),
(34, '9', '2', '7576', 'ROOM COOLER', NULL, '27000', '18500', NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, '2024-12-25 09:05:42', '2024-12-25 15:38:54', NULL),
(35, '9', '2', '4076', 'ROOM COOLER', NULL, '20000', '15900', NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, '2024-12-25 09:07:24', '2024-12-25 15:37:12', NULL),
(36, '9', '2', '1876 AC/DC', 'room coller', NULL, '20000', '15600', NULL, NULL, NULL, NULL, NULL, 20, NULL, NULL, '2024-12-25 09:09:56', '2024-12-25 15:39:15', NULL),
(37, '9', '2', '3576 12V', 'Room Coller', NULL, '18500', '13900', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 09:10:52', '2024-12-25 09:10:52', NULL),
(38, '9', '2', '2276 220v', 'Room Coller', NULL, '21000', '15900', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 09:12:04', '2024-12-25 09:12:04', NULL),
(39, '9', '2', 'GF-6700', 'Room Coller', NULL, '30000', '24500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 09:13:55', '2024-12-26 13:31:01', NULL),
(40, '12', '13', 'old 24', 'Pedistal Fan', NULL, '5000', '4000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 09:15:12', '2024-12-25 09:15:12', NULL),
(41, '13', '9', '12\" Dish National', 'Electric Hetter', NULL, '1800', '980', NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, '2024-12-25 12:20:44', '2024-12-26 12:18:40', NULL),
(42, '13', '9', '12\" Dish moon', 'Electric Hetter', NULL, '1600', '950', NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, '2024-12-25 12:23:30', '2024-12-26 15:09:05', NULL),
(43, '13', '9', '12\" apple', 'Electric Hetter', NULL, '1500', '750', NULL, NULL, NULL, NULL, NULL, 15, NULL, NULL, '2024-12-25 12:54:08', '2024-12-26 12:19:27', NULL),
(44, '13', '2', 'RH 130', 'Electric Hetter', NULL, '3500', '2000', NULL, NULL, NULL, NULL, NULL, 31, NULL, NULL, '2024-12-25 13:17:34', '2024-12-25 13:17:34', NULL),
(45, '13', '2', 'RH 410', 'Electric Hetter', NULL, '6500', '4600', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-25 13:20:50', '2024-12-26 17:09:06', NULL),
(46, '13', '9', 'Fish Hetter', 'Electric Hetter', NULL, '1500', '700', NULL, NULL, NULL, NULL, NULL, 27, NULL, NULL, '2024-12-25 13:23:19', '2024-12-25 13:23:19', NULL),
(47, '13', '9', '14\" moving', 'Electric Hetter', NULL, '2500', '1530', NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, '2024-12-25 13:24:17', '2024-12-25 13:24:17', NULL),
(48, '13', '2', 'RH 210', 'Electric Hetter', NULL, '5000', '3600', NULL, NULL, NULL, NULL, NULL, 8, NULL, NULL, '2024-12-25 13:25:02', '2024-12-26 10:05:42', NULL),
(49, '10', '15', '12 Watt B22', 'tuff blub', NULL, '150', '115', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 13:50:08', '2024-12-26 12:28:18', NULL),
(50, '13', '9', '16\" Dish Seko Simple', 'Electric Hetter', NULL, '5000', '3050', NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, '2024-12-25 14:16:52', '2024-12-26 13:38:37', NULL),
(51, '2', '1', '80-AS', 'Washing machine', NULL, '33000', '27500', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2024-12-25 14:20:48', '2024-12-25 14:21:13', NULL),
(52, '7', '10', '1061', 'National Hot Plate', NULL, '7500', '5600', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 14:25:45', '2024-12-25 14:28:10', NULL),
(53, '7', '3', '315 GG', 'GFC HOB', NULL, '20000', '14500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 14:26:58', '2024-12-25 14:26:58', NULL),
(54, '7', '10', 'BT 3308', 'BOSS TECH HOT PLATE', NULL, '7500', '5100', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 14:29:19', '2024-12-25 14:29:19', NULL),
(55, '4', '5', '21.5\" chaina samsung', 'LED TV', NULL, '12500', '8100', NULL, NULL, NULL, NULL, NULL, 8, NULL, NULL, '2024-12-25 14:48:23', '2024-12-26 13:05:29', NULL),
(56, '2', '2', 'RWM 930', 'Washing machine', NULL, '14000', '10200', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 14:53:53', '2024-12-25 14:53:53', NULL),
(57, '10', '4', 'Extension Lead', 'Extension Lead', NULL, '400', '180', NULL, NULL, NULL, NULL, NULL, 23, NULL, NULL, '2024-12-25 14:55:35', '2024-12-25 14:55:35', NULL),
(58, '5', '4', 'AC Universal', 'Remort', NULL, '1500', '650', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-25 14:56:41', '2024-12-25 14:56:41', NULL),
(59, '5', '1', 'Haier Ac', 'Haier AC Remort', NULL, '3300', '1500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 14:58:18', '2024-12-25 14:58:18', NULL),
(60, '10', '4', 'AAA double charge', 'Cell double charge', NULL, '25', '12', NULL, NULL, NULL, NULL, NULL, 528, NULL, NULL, '2024-12-25 15:00:49', '2024-12-25 15:00:49', NULL),
(61, '14', '2', 'washing moter', 'Washing machine moter', NULL, '5000', '3000', NULL, NULL, NULL, NULL, NULL, 14, NULL, NULL, '2024-12-25 15:03:39', '2024-12-25 15:03:39', NULL),
(62, '11', '16', '4Ft-single stand', 'Dish', NULL, '1800', '1350', NULL, NULL, NULL, NULL, NULL, 15, NULL, NULL, '2024-12-25 15:17:41', '2024-12-25 15:17:41', NULL),
(63, '11', '16', '4Ft-double stand', 'Dish', NULL, '2400', '1750', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 15:18:38', '2024-12-25 15:18:38', NULL),
(64, '11', '16', '2ft', 'Dish', NULL, '700', '380', NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, '2024-12-25 15:19:32', '2024-12-25 15:19:32', NULL),
(65, '11', '4', 'RG-6', 'Cable', NULL, '20', '12', NULL, NULL, NULL, NULL, NULL, 479, NULL, NULL, '2024-12-25 15:20:32', '2024-12-25 15:20:32', NULL),
(66, '11', '4', 'RG-7', 'Cable', NULL, '30', '18', NULL, NULL, NULL, NULL, NULL, 242, NULL, NULL, '2024-12-25 15:21:28', '2024-12-25 15:21:28', NULL),
(67, '15', '17', 'MILANO', 'MILANO(78*66*6)', NULL, '16000', '14000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 15:25:20', '2024-12-25 15:25:20', NULL),
(68, '15', '17', 'ENGLANDER FOAM', 'ENGLANDER(78*66*4)', NULL, '12000', '9000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 15:26:23', '2024-12-25 15:26:23', NULL),
(69, '4', '6', '50\" Chaina samsung', 'LED TV', NULL, '55000', '48000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 15:27:22', '2024-12-25 15:27:22', NULL),
(70, '15', '17', 'Sofa seats', 'Sofa Foam', NULL, '8500', '6800', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 15:28:21', '2024-12-25 15:28:21', NULL),
(71, '15', '17', 'PILLOW', 'PILLOW', NULL, '450', '300', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 15:29:13', '2024-12-25 15:29:13', NULL),
(72, '9', '13', 'P-Y 850', 'Room Coller', NULL, '14000', '11500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 15:32:17', '2024-12-25 15:32:17', NULL),
(73, '9', '13', 'SA5000', 'Room Coller', NULL, '10000', '6000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 15:36:26', '2024-12-25 15:36:26', NULL),
(74, '9', '2', '4076 12v', 'Room Cooler', NULL, '18000', '14000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 15:38:28', '2024-12-25 15:38:28', NULL),
(75, '7', '9', 'Madhani Medium (Copper)', 'Madhani', NULL, '6000', '4250', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-25 15:40:45', '2024-12-25 15:46:51', NULL),
(76, '7', '9', 'Madhani Medium (Havey duty)', 'Madhani', NULL, '5500', '3450', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 15:48:52', '2024-12-25 15:48:52', NULL),
(77, '7', '9', 'Madhani small (Havey duty)', 'Madhani', NULL, '3500', '2600', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 15:50:14', '2024-12-25 15:50:14', NULL),
(78, '7', '9', 'Madhani Jumbo Two way (Copper)', 'Madhani', NULL, '7000', '4850', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 15:51:36', '2024-12-25 15:53:25', NULL),
(79, '7', '9', 'Madhani Jumbo (Havey Duty)', 'Madhani', NULL, '6000', '4050', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 15:53:07', '2024-12-25 15:53:07', NULL),
(80, '9', '2', '5076', 'Room Coller', NULL, '25000', '19500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 16:01:30', '2024-12-25 16:01:30', NULL),
(81, '9', '13', 'AH-5000', 'Ahemad Room Coller', NULL, '14000', '12000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 16:03:08', '2024-12-25 16:03:08', NULL),
(82, '16', '18', '180Watt', 'Solar Plate', NULL, '12000', '10800', NULL, NULL, NULL, NULL, NULL, 18, NULL, NULL, '2024-12-25 16:12:44', '2024-12-25 16:12:44', NULL),
(83, '17', '3', '35Gallon exelent', 'Water Hetter', NULL, '35000', '30000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 16:14:51', '2024-12-25 16:14:51', NULL),
(84, '7', '3', 'Indus 727 Glass', 'HOB', NULL, '20000', '14500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 16:16:02', '2024-12-25 16:16:02', NULL),
(85, '7', '3', 'Indus 320 Steel', 'HOB', NULL, '19000', '14500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 16:17:01', '2024-12-25 16:17:01', NULL),
(86, '7', '12', 'GIFT 777', 'Stove auto', NULL, '5000', '3500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 16:48:27', '2024-12-25 16:48:27', NULL),
(87, '13', '12', 'wall Gas hetter', 'Gas hetter', NULL, '3500', '2500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 16:50:34', '2024-12-25 16:50:34', NULL),
(88, '7', '12', 'GIFT 504 M', 'Stove', NULL, '5000', '3250', NULL, NULL, NULL, NULL, NULL, 8, NULL, NULL, '2024-12-25 16:51:54', '2024-12-26 10:12:16', NULL),
(89, '7', '12', 'GIFT 403', 'Stove', NULL, '2500', '1800', NULL, NULL, NULL, NULL, NULL, 11, NULL, NULL, '2024-12-25 16:53:08', '2024-12-25 16:53:08', NULL),
(90, '7', '12', 'GIFT 401', 'Stove Single Burner', NULL, '1800', '1200', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 16:54:06', '2024-12-25 16:54:06', NULL),
(91, '7', '12', 'GIFT 505 TWIN', 'Stove', NULL, '6500', '5350', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 16:57:00', '2024-12-25 16:57:00', NULL),
(92, '7', '12', 'GIFT 505 Toster', 'Stove', NULL, '6500', '5350', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-25 16:58:04', '2024-12-25 16:58:04', NULL),
(93, '7', '12', 'GIFT 505', 'Stove', NULL, '5800', '4650', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 16:58:45', '2024-12-25 16:58:45', NULL),
(94, '2', '13', 'Ahemad Spinner', 'Spinner Machine', NULL, '12000', '10000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 16:59:58', '2024-12-25 16:59:58', NULL),
(95, '12', '13', '422', 'Room Coller', NULL, '10000', '10000', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-25 17:01:29', '2024-12-25 17:01:29', NULL),
(96, '2', '3', 'GF 910', 'Washing machine GFC', NULL, '16000', '14000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 17:03:24', '2024-12-25 17:03:24', NULL),
(97, '2', '3', 'Super Gernal', 'Washing machine', NULL, '14000', '12000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 17:16:59', '2024-12-25 17:16:59', NULL),
(98, '2', '3', 'united washing', 'Washing machine', NULL, '14000', '12000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 17:19:35', '2024-12-25 17:19:35', NULL),
(99, '8', '10', 'Kattel 231', 'Electric Kattel', NULL, '3000', '1600', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 17:21:13', '2024-12-25 17:25:26', NULL),
(100, '8', '10', 'Kettel 8030', 'Electric Kattel', NULL, '3000', '1800', NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, '2024-12-25 17:22:42', '2024-12-25 17:22:42', NULL),
(101, '8', '10', 'Kattel 102', 'Electric Kattel', NULL, '1800', '920', NULL, NULL, NULL, NULL, NULL, 8, NULL, NULL, '2024-12-25 17:25:13', '2024-12-25 17:25:13', NULL),
(102, '8', '10', 'Kattel 03W', 'Electric Kattel', NULL, '3000', '1600', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 17:26:42', '2024-12-25 17:26:42', NULL),
(103, '8', '10', 'Kattel 424', 'Electric Kattel', NULL, '3000', '1600', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-25 17:27:41', '2024-12-25 17:27:41', NULL),
(104, '8', '10', 'Kattel 404', 'Elecrtic Kattel', NULL, '3500', '1750', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-25 17:29:00', '2024-12-25 17:29:00', NULL),
(105, '8', '10', 'Kattel 0014', 'Electric Kattel', NULL, '3500', '1750', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-25 17:30:07', '2024-12-25 17:30:07', NULL),
(106, '8', '10', 'Kattel 224ST', 'Electric Kattel', NULL, '3500', '1840', NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, '2024-12-25 17:31:41', '2024-12-25 17:31:41', NULL),
(107, '8', '10', 'DN-516', 'Electric Kattel', NULL, '4500', '3000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 17:32:33', '2024-12-25 17:32:33', NULL),
(108, '8', '10', 'Kattel 317', 'Electric Kattel', NULL, '2000', '1160', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-25 17:33:36', '2024-12-26 11:50:21', NULL),
(109, '7', '12', 'GIFT 102 Auto', 'Stove', NULL, '2500', '1800', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-25 17:40:23', '2024-12-25 17:40:23', NULL),
(110, '7', '10', 'GIFT 112', 'Stove', NULL, '1800', '1350', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-25 17:41:10', '2024-12-25 17:41:10', NULL),
(111, '7', '9', 'Super delux Medium', 'Madhani', NULL, '5000', '4000', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-25 17:48:50', '2024-12-25 17:48:50', NULL),
(112, '5', '4', 'Stick 24\"', 'Stick Dish', NULL, '250', '105', NULL, NULL, NULL, NULL, NULL, 63, NULL, NULL, '2024-12-25 17:50:14', '2024-12-25 17:50:14', NULL),
(113, '13', '10', 'Lider halogyne Rod', 'Electric Hetter', NULL, '4500', '2600', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 09:01:15', '2024-12-26 09:01:15', NULL),
(114, '6', '20', 'Ultra Slim Wall Mount', 'LED Stand', NULL, '1000', '400', NULL, NULL, NULL, NULL, NULL, 49, NULL, NULL, '2024-12-26 09:05:11', '2024-12-26 09:05:11', NULL),
(115, '6', '20', '32\"-50\" Wall Mount', 'LED Stand', NULL, '400', '150', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-26 09:06:53', '2024-12-26 09:06:53', NULL),
(116, '6', '20', 'Reciver Stand Simple', 'Reciver Stand', NULL, '350', '220', NULL, NULL, NULL, NULL, NULL, 16, NULL, NULL, '2024-12-26 09:07:57', '2024-12-26 09:07:57', NULL),
(117, '18', '21', 'Top One', 'Sewing Machine', NULL, '13000', '10500', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-26 09:11:06', '2024-12-26 09:11:06', NULL),
(118, '18', '21', 'Sagwan', 'Sewing Machine', NULL, '13000', '10800', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2024-12-26 09:11:48', '2024-12-26 15:11:13', NULL),
(119, '6', '20', '32-50 Moving Royal', 'LED Stand', NULL, '1500', '650', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-26 09:13:28', '2024-12-26 09:13:28', NULL),
(120, '19', '2', 'E-300', 'Rimco Stablizer', NULL, '7000', '5300', NULL, NULL, NULL, NULL, NULL, 15, NULL, NULL, '2024-12-26 09:31:17', '2024-12-26 09:31:17', NULL),
(121, '19', '2', 'E-400', 'Rimco Stablizer', NULL, '9000', '7300', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-26 09:32:39', '2024-12-26 10:48:12', NULL),
(122, '17', '8', 'Shanghani Rod 1200watt', 'Water Hetter', NULL, '500', '300', NULL, NULL, NULL, NULL, NULL, 16, NULL, NULL, '2024-12-26 09:34:40', '2024-12-26 09:34:40', NULL),
(123, '17', '8', 'Big Boss 1500 watt', 'Water Hetter', NULL, '800', '400', NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, '2024-12-26 09:35:36', '2024-12-26 09:35:36', NULL),
(124, '17', '8', 'Xtion Rod 1000watt', 'Water Hetter', NULL, '500', '250', NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, '2024-12-26 09:40:04', '2024-12-26 09:40:04', NULL),
(125, '10', '15', 'DC 12watt E27', 'Tuff Bulb', NULL, '150', '115', NULL, NULL, NULL, NULL, NULL, 114, NULL, NULL, '2024-12-26 09:41:50', '2024-12-26 12:26:45', NULL),
(126, '10', '15', '18watt E-27', 'Tuff Bulb', NULL, '280', '215', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 09:43:57', '2024-12-26 09:43:57', NULL),
(127, '19', '2', 'E-350', 'Rimco Stablizer', NULL, '8000', '6200', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 09:47:03', '2024-12-26 09:47:03', NULL),
(128, '9', '3', 'GFC 24\" AC/DC BLACK', 'Pedistal Fan', NULL, '15000', '12800', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 09:49:04', '2024-12-26 09:49:04', NULL),
(129, '12', '13', '3500watt old', 'Stablizer', NULL, '3000', '2000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 09:58:12', '2024-12-26 09:58:12', NULL),
(130, '12', '13', 'celling 56\"', 'Celling fan Old', NULL, '5000', '2500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 09:59:09', '2024-12-26 09:59:09', NULL),
(131, '13', '12', 'Gas Hetter Single Knob', 'Gas hetter', NULL, '2000', '1100', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:00:49', '2024-12-26 10:00:49', NULL),
(132, '7', '13', 'Hob Local Glass', 'HOB', NULL, '8000', '6500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:09:13', '2024-12-26 10:09:13', NULL),
(133, '7', '12', 'GIFT 666', 'Stove', NULL, '5000', '4400', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:11:44', '2024-12-26 10:11:44', NULL),
(134, '4', '5', '19\" wide Samsung Chaina', 'LED TV', NULL, '9000', '6500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:13:42', '2024-12-26 10:13:42', NULL),
(135, '16', '22', '70/76 Solar Wire', 'Wire', NULL, '150', '83', NULL, NULL, NULL, NULL, NULL, 90, NULL, NULL, '2024-12-26 10:28:33', '2024-12-26 10:28:33', NULL),
(136, '16', '22', '100/76 Solar Wire', 'Wire', NULL, '180', '106', NULL, NULL, NULL, NULL, NULL, 180, NULL, NULL, '2024-12-26 10:29:59', '2024-12-26 10:29:59', NULL),
(137, '13', '10', 'CA-02', 'Fan Hetter', NULL, '2500', '1800', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:34:10', '2024-12-26 10:34:10', NULL),
(138, '13', '13', 'OC-3000G', 'Gas Hetter Cooker', NULL, '3000', '2500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:35:32', '2024-12-26 10:35:32', NULL),
(139, '19', '2', 'E-450', 'Rimco Stablizer', NULL, '10000', '8300', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 10:36:35', '2024-12-26 13:02:29', NULL),
(140, '12', '13', 'Madhani full Old', 'Madhani', NULL, '3000', '1500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:37:42', '2024-12-26 10:37:42', NULL),
(141, '10', '4', 'AAA power tec', 'cell', NULL, '20', '10', NULL, NULL, NULL, NULL, NULL, 2160, NULL, NULL, '2024-12-26 10:39:34', '2024-12-26 10:39:34', NULL),
(142, '6', '20', '32-55 wall Mount', 'LED Stand', NULL, '500', '300', NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, '2024-12-26 10:45:36', '2024-12-26 10:45:36', NULL),
(143, '6', '20', '40-75 wall mount', 'LED Stand', NULL, '1200', '700', NULL, NULL, NULL, NULL, NULL, 11, NULL, NULL, '2024-12-26 10:47:43', '2024-12-26 10:47:43', NULL),
(144, '16', '19', 'AS-55 No Solar wire', 'Wire', NULL, '200', '120', NULL, NULL, NULL, NULL, NULL, 66, NULL, NULL, '2024-12-26 10:49:50', '2024-12-26 10:49:50', NULL),
(145, '16', '19', '70/09 Solar Wire', 'Wire', NULL, '250', '140', NULL, NULL, NULL, NULL, NULL, 231, NULL, NULL, '2024-12-26 10:50:42', '2024-12-26 10:50:42', NULL),
(146, '13', '10', 'West Point WP-5308', 'Electric Hetter', NULL, '7500', '5500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 10:54:57', '2024-12-26 10:54:57', NULL),
(147, '9', '3', 'Exhaust 10\" Metel', 'Gfc Exhaust', NULL, '5500', '4075', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 11:07:10', '2024-12-26 11:07:10', NULL),
(148, '9', '3', 'Wall Bracket Mayga 24\"', 'Bracket Fan', NULL, '14000', '11000', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 11:09:12', '2024-12-26 11:09:12', NULL),
(149, '9', '3', 'Karishma 24\"', 'Pedistal fan', NULL, '10000', '8000', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 11:10:55', '2024-12-26 13:32:14', NULL),
(150, '9', '3', 'GFC-Sapphire Plus 56 220v', 'Celling Fan', NULL, '9500', '8255', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 11:20:54', '2024-12-26 11:20:54', NULL),
(151, '9', '3', 'GFC-Marvel/Crown 56\" 220V', 'Celling Fan', NULL, '9000', '7815', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 11:24:40', '2024-12-26 11:25:38', NULL),
(152, '9', '3', 'GFC-Mansion 220V', 'Celling Fan', NULL, '9000', '7730', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-26 11:30:04', '2024-12-26 11:30:04', NULL),
(153, '9', '3', 'GFC-dominat/Gallnet AC220V', 'Celling Fan', NULL, '11500', '9456', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 11:32:46', '2024-12-26 12:36:26', NULL),
(154, '9', '3', 'Karishma Celling 56\" 220V', 'Celling Fan', NULL, '7500', '6000', NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, '2024-12-26 11:34:10', '2024-12-26 12:34:55', NULL),
(155, '9', '3', 'GFC-Iconic 56\" AC/DC', 'Celling Fan', NULL, '10000', '8600', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 11:36:06', '2024-12-26 11:36:06', NULL),
(156, '9', '3', 'GFC-Delux 56\" 220V', 'Celling Fan', NULL, '9000', '7310', NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, '2024-12-26 11:52:20', '2024-12-26 11:52:33', NULL),
(157, '9', '3', 'GFC-Supreem 220V/30watt', 'Celling Fan', NULL, '10500', '9000', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 11:54:20', '2024-12-26 12:31:39', NULL),
(158, '9', '22', 'Khursheed Water proof 56\" AC/DC', 'Celling Fan', NULL, '13000', '11600', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 11:55:22', '2024-12-26 11:56:51', NULL),
(159, '9', '3', 'GFC-Apex AC/DC', 'Celling Fan', NULL, '10000', '7850', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-26 11:59:02', '2024-12-26 12:33:55', NULL),
(160, '9', '15', 'SKM- celling 56\"', 'Celling Fan', NULL, '9000', '7000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 11:59:59', '2024-12-26 11:59:59', NULL),
(161, '9', '15', 'Digital 56\" Celling 220V', 'Celling Fan', NULL, '7000', '5500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 12:00:48', '2024-12-26 12:35:18', NULL),
(162, '9', '3', 'GFC-AC/DC 20\" BLDC', 'Pedistal Fan', NULL, '11000', '9000', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-26 12:02:33', '2024-12-26 12:02:33', NULL),
(163, '9', '3', 'GFC-Nabeel Water Proof 220V', 'Celling Fan', NULL, '8500', '7400', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:04:11', '2024-12-26 12:04:11', NULL),
(164, '9', '3', 'GFC-Delux 220V 30watt Celling 56\"', 'Celling Fan', NULL, '10500', '9000', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 12:07:02', '2024-12-26 12:33:37', NULL),
(165, '9', '22', 'KHURSHEED ICON AC/DC 56\"', 'Celling Fan', NULL, '10000', '9100', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 12:21:41', '2024-12-26 12:21:41', NULL),
(166, '9', '3', 'GFC-circumatic 18\" 220V', 'Circumatic Fan', NULL, '8500', '6800', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:22:50', '2024-12-26 12:22:50', NULL),
(167, '6', '20', '32-75 Dimond wall mount', 'LED Stand', NULL, '1300', '600', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:24:35', '2024-12-26 12:24:35', NULL),
(168, '9', '3', 'GFC-Mist Fan 24\" 220V', 'Pedistal Fan', NULL, '24000', '20500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:26:18', '2024-12-26 12:26:18', NULL),
(169, '10', '8', 'DP-9035', 'Torch Light', NULL, '800', '650', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-26 12:27:38', '2024-12-26 12:27:48', NULL),
(170, '10', '15', 'Tuff 12watt E27', 'Bulb', NULL, '150', '115', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:29:38', '2024-12-26 12:29:38', NULL),
(171, '7', '12', 'Guttka Stove', 'Stove', NULL, '3000', '2500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 12:31:16', '2024-12-26 12:31:16', NULL),
(172, '9', '3', 'GFC-Supreem AC/DC', 'Celling Fan', NULL, '10500', '8800', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:32:31', '2024-12-26 12:32:31', NULL),
(173, '9', '3', 'Blaze celling 56\" 220V', 'Celling Fan', NULL, '7000', '6500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:36:09', '2024-12-26 12:36:09', NULL),
(174, '9', '3', 'GFC- Iconic 56\" 30watt', 'Celling Fan', NULL, '10500', '9000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:37:30', '2024-12-26 12:37:30', NULL),
(175, '19', '4', 'Gas Compressor 3000', 'Gas compressor', NULL, '1500', '980', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 12:38:52', '2024-12-26 12:38:52', NULL),
(176, '11', '4', 'Reciver F1F2 Plastic', 'Reciver', NULL, '2500', '1800', NULL, NULL, NULL, NULL, NULL, 14, NULL, NULL, '2024-12-26 12:50:39', '2024-12-26 12:50:39', NULL),
(177, '11', '4', 'Reciver F1F2 Metal', 'Reciver', NULL, '2700', '1950', NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, '2024-12-26 12:51:26', '2024-12-26 12:51:26', NULL),
(178, '11', '4', 'Reciver Star Box ST-20', 'Reciver', NULL, '5500', '3700', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:52:32', '2024-12-26 12:52:32', NULL),
(179, '6', '20', 'Reciver Stand Moving', 'Reciver Stand', NULL, '700', '400', NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, '2024-12-26 12:53:36', '2024-12-26 12:53:36', NULL),
(180, '11', '4', 'RG-6 Conector', 'Conetor', NULL, '6', '3', NULL, NULL, NULL, NULL, NULL, 1000, NULL, NULL, '2024-12-26 12:54:21', '2024-12-26 12:54:21', NULL),
(181, '6', '20', '17-24 Wall Mount Big', 'LED Stand', NULL, '250', '90', NULL, NULL, NULL, NULL, NULL, 24, NULL, NULL, '2024-12-26 12:55:31', '2024-12-26 12:55:31', NULL),
(182, '11', '20', '17-24 Wall mount', 'LED Stand', NULL, '150', '65', NULL, NULL, NULL, NULL, NULL, 17, NULL, NULL, '2024-12-26 12:56:11', '2024-12-26 12:56:11', NULL),
(183, '7', '3', 'DW-220', 'MicroWave Oven Dawnlance', NULL, '22000', '18000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:57:55', '2024-12-26 12:57:55', NULL),
(184, '17', '2', '15 Litter Rimco', 'Water Hetter', NULL, '18500', '14900', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 12:59:41', '2024-12-26 12:59:41', NULL),
(185, '17', '3', 'GF-708 Electric', 'Water Hetter', NULL, '21000', '16830', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:02:09', '2024-12-26 13:02:09', NULL),
(186, '17', '2', 'Rimco 40L Electric', 'Water Hetter', NULL, '21000', '17900', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:04:00', '2024-12-26 13:04:00', NULL),
(187, '17', '2', 'Rimco 25L Electric', 'Water Hetter', NULL, '23000', '17500', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2024-12-26 13:05:04', '2024-12-26 13:05:04', NULL),
(188, '4', '5', '19\" samsung Chaina Front Boofer', 'LED TV', NULL, '9500', '7600', NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, '2024-12-26 13:07:03', '2024-12-26 13:07:03', NULL),
(189, '4', '5', '17\" Samsung Chaina', 'LED TV', NULL, '8500', '7100', NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, '2024-12-26 13:08:07', '2024-12-26 13:08:07', NULL),
(190, '4', '5', '24\" Samsung Chaina Androide', 'LED TV', NULL, '18000', '13500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 13:09:10', '2024-12-26 13:09:10', NULL),
(191, '4', '5', '24\" Samsung Chaina', 'LED TV', NULL, '14500', '10000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:09:56', '2024-12-26 13:09:56', NULL),
(192, '4', '5', '30\" samsung Chania', 'LED TV', NULL, '20000', '16000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:10:47', '2024-12-26 13:10:47', NULL),
(193, '2', '3', 'Super United Spinner Metal', 'Spinner Machine', NULL, '13000', '10000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:12:23', '2024-12-26 13:12:23', NULL),
(194, '9', '15', 'Pak Fan 8\" Plastic Exhaust Glass', 'Exhaust Fan', NULL, '4500', '3200', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 13:13:53', '2024-12-26 13:13:53', NULL),
(195, '2', '2', 'RWM-576', 'Spinner Machine', NULL, '15000', '12500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:15:27', '2024-12-26 13:15:27', NULL),
(196, '2', '2', 'RWM 965', 'Spinner Machine', NULL, '16000', '12100', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 13:18:38', '2024-12-26 13:18:38', NULL),
(197, '2', '2', 'RWM 500', 'Spinner Machine', NULL, '15000', '14500', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:19:38', '2024-12-26 13:19:38', NULL),
(198, '2', '2', 'RWM 376', 'Spinner Machine', NULL, '15000', '13900', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:20:30', '2024-12-26 13:20:30', NULL),
(199, '2', '2', 'GF-435', 'Washing Machine', NULL, '25000', '22000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:21:44', '2024-12-26 13:21:44', NULL),
(200, '2', '2', 'GF-340', 'Spinner machine', NULL, '23500', '20000', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:29:03', '2024-12-26 13:29:03', NULL),
(201, '2', '2', 'RWM-325', 'Spinner Machine', NULL, '19000', '14900', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2024-12-26 13:30:06', '2024-12-26 13:30:06', NULL),
(202, '9', '3', 'GFC standrd Motor 24', 'MOTOR', NULL, '5000', '4500', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2024-12-26 13:36:05', '2024-12-26 13:36:05', NULL),
(203, '13', '9', '16\" Seko Medos LED Light', 'Electric Hetter', NULL, '5500', '3350', NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, '2024-12-26 13:37:48', '2024-12-26 13:37:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`transaction_id`)),
  `customer_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`customer_id`)),
  `total_discount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `cash` decimal(10,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `transaction_id`, `customer_id`, `total_discount`, `total_amount`, `note`, `cash`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '[\"1\",\"2\"]', '1', 3000.00, 82000.00, NULL, 1000.00, '2024-12-24 15:47:38', '2024-12-24 15:47:38', NULL),
(2, '[\"3\",\"4\"]', '2', 6000.00, 43000.00, '5 YEAR MOTOR WARRANTY\nCOPPER MOTOR', 43000.00, '2024-12-24 15:49:42', '2024-12-24 15:50:21', NULL),
(3, '[\"5\"]', '3', 5000.00, 28000.00, NULL, 28000.00, '2024-12-25 14:22:40', '2024-12-25 14:22:40', NULL),
(4, '[\"7\"]', '4', 1200.00, 4800.00, 'FIVE YEAR MOTOR WARRANTY', 4800.00, '2024-12-25 15:42:27', '2024-12-25 15:43:04', NULL),
(5, '[\"8\"]', '5', 300.00, 1500.00, 'NO WARRANTY', 1500.00, '2024-12-25 17:44:20', '2024-12-25 17:44:37', NULL),
(6, '[\"9\"]', '6', 500.00, 4500.00, 'No warranty/gurntee', 4500.00, '2024-12-26 10:04:08', '2024-12-26 10:04:27', NULL),
(7, '[\"10\"]', '7', 500.00, 4500.00, 'No warrnty/gurntee', 4500.00, '2024-12-26 10:06:20', '2024-12-26 10:06:41', NULL),
(8, '[\"12\"]', '8', 900.00, 4500.00, 'No warranty / Gurntee', 4500.00, '2024-12-26 12:16:03', '2024-12-26 12:16:26', NULL),
(9, '[\"13\"]', '9', 100.00, 1500.00, 'NO WARRANTY/GURNTEE', 1500.00, '2024-12-26 15:10:00', '2024-12-26 15:10:16', NULL),
(10, '[\"14\"]', '10', 1300.00, 11700.00, '05 YEAR WARRANTY', 11700.00, '2024-12-26 15:12:04', '2024-12-26 15:12:17', NULL),
(11, '[\"15\"]', '11', 1600.00, 4900.00, NULL, 9800.00, '2024-12-26 17:04:32', '2024-12-26 17:04:32', NULL),
(12, '[\"16\"]', '11', 1600.00, 4900.00, NULL, 4900.00, '2024-12-26 17:21:08', '2024-12-26 17:21:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8Yp6Nt6zUlX3xPMomyFoJs92CKHMWo8zHqURdv1I', NULL, '139.59.81.242', 'Mozilla/5.0 (compatible)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGx3UUtBQ1Bhb1FUZUR1SzVFVU85b3hFT2xGWHphaVdEczJ5QWpHbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly93d3cucmFuYWVsZWN0cm9uaWNzLm9yYWNsZWZvcmNlLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1735217934),
('9UAJNXygUrzuizmDJTL5Xtk5kaZNmLtXiCotwAD3', 1, '116.71.167.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHAwUVU5RDB4djBWSVdvZWhhT1RvSFRqeUhLWDdra3RCcnBGTHdaaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vcmFuYWVsZWN0cm9uaWNzLm9yYWNsZWZvcmNlLmNvbS9wcm9kdWN0L3Nob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1735226935),
('JLqm4ZQY6H0x7UZbHTQsCzOeNp5dXe2ZyV2fx7On', 1, '202.69.43.162', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWks1RHNxaGMxcVN3VXJoSWJjdnlnem5vbUdBdUpvQ3BiNjVOSVp1byI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1NjoiaHR0cHM6Ly9yYW5hZWxlY3Ryb25pY3Mub3JhY2xlZm9yY2UuY29tL3RyYW5zYWN0aW9uL3Nob3ciO319', 1735219362),
('sTKu80BhuhIAO7To5XW0S0RhQtLr82uJI2Wt58Hi', 1, '116.71.164.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXhsbHJmWk45Z2VKMXh1empSZ0dSN3JnUG1IM3h6MXpFanh3U3FXayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1NjoiaHR0cHM6Ly9yYW5hZWxlY3Ryb25pY3Mub3JhY2xlZm9yY2UuY29tL3RyYW5zYWN0aW9uL3Nob3ciO319', 1735219486),
('YxN1xWrwPcmvbQ76JLi6wOBvEIq9nXoNUHVcsAMx', NULL, '139.59.81.242', 'Mozilla/5.0 (compatible)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFl3cm5vdFpoNVVsb1BsM3BwN3JISVlMUHVoOFkwN1pya0E5Z1BORCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vd3d3LnJhbmFlbGVjdHJvbmljcy5vcmFjbGVmb3JjZS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1735217935);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier`, `contact_person`, `address`, `contact_no`, `note`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'HAIER(market purchase)', 'nill', 'nill', '000000000000', 'nill', '2024-12-23 15:32:10', '2024-12-23 15:32:10', NULL),
(2, 'RIMCO PVT LTD', 'AAMIR SHEHZAD', 'Gujranwala(GRW)', '03006434176', 'NILL', '2024-12-23 16:27:41', '2024-12-23 16:27:41', NULL),
(3, 'UMER TRADERS', 'HAFIZ AHEMAD', 'Sargodha(SGD)', '03007567865', 'NILL', '2024-12-23 16:29:31', '2024-12-23 16:29:31', NULL),
(4, 'ZAHAID REMORT & SOULOTION', 'ZAHAID', 'Lahore(LHR)', '03214020453', 'NILL', '2024-12-24 08:43:31', '2024-12-24 08:43:31', NULL),
(5, 'HARIS LED CENTER', 'MANSOOR KHAN', 'Lahore(LHR)', '03088508514', 'NILL', '2024-12-24 08:44:25', '2024-12-24 08:44:25', NULL),
(6, 'NOOR ELECTRONICS', 'ABDUL ALI', 'Lahore(LHR)', '03458903435', 'NILL', '2024-12-24 08:45:17', '2024-12-24 08:45:17', NULL),
(7, 'ADNAN LED CENTER', 'ADNAN KHAN', 'Lahore(LHR)', '03054463808', 'NILL', '2024-12-24 08:46:38', '2024-12-24 08:46:38', NULL),
(8, 'MUSTAFA ELECTRONICS', 'MUSTAFA', 'Lahore(LHR)', '03234668043', 'NILL', '2024-12-24 08:47:34', '2024-12-24 08:47:34', NULL),
(9, 'M.S TRADERS', 'SUFYAN SHAKEEL', 'Gujranwala(GRW)', '03246134431', 'NILL', '2024-12-24 08:50:55', '2024-12-24 08:50:55', NULL),
(10, 'GHUZNAFAR ELECTRONICS', 'YASIR', 'Lahore(LHR)', '03034444085', 'NILL', '2024-12-24 08:52:30', '2024-12-24 08:52:30', NULL),
(11, 'mukhtar stand maker', 'MUKHTAR', 'Gujranwala(GRW)', '03454581100', 'NILL', '2024-12-24 16:06:50', '2024-12-24 16:06:50', NULL),
(12, 'GIFT GAS APPLIANCES', 'QASIM GONDAL', 'Gujranwala(GRW)', '03008719646', 'NILL', '2024-12-24 16:09:55', '2024-12-24 16:09:55', NULL),
(13, 'OLD ITEM', 'nill', 'KOT MOMIN', '000000000000000', 'NILL', '2024-12-24 16:26:56', '2024-12-24 16:26:56', NULL),
(14, 'UMER GAS MILL', 'UMER', 'Gujranwala(GRW)', '03016606011', 'NILL', '2024-12-25 08:44:17', '2024-12-25 08:44:17', NULL),
(15, 'PAK FAN', 'FARUKH', 'Sargodha(SGD)', '03013050852', 'NILL', '2024-12-25 13:41:43', '2024-12-25 13:41:43', NULL),
(16, 'Ghazi Dish Center', 'Ghazi', 'Bhalwal(BLW)', '03056376471', 'NILL', '2024-12-25 15:16:13', '2024-12-25 15:16:13', NULL),
(17, 'BAGGA FOAM CENTER', 'AAMIR SHEHZAD', 'Sargodha(SGD)', '03016750597', 'NILL', '2024-12-25 15:23:14', '2024-12-25 15:23:14', NULL),
(18, 'ASAD ULLAH SOLAR HOUSE', 'ASAD ULLAH', 'Rawalpindi(RWP)', '03429429679', 'NILL', '2024-12-25 16:07:31', '2024-12-25 16:07:31', NULL),
(19, 'AL MADINA AUTOS', 'QASIM', 'KOT MOMIN', '03437752496', 'nill', '2024-12-25 17:52:51', '2024-12-25 17:52:51', NULL),
(20, 'MIAN TARADING', 'MIAN MUHAMMAD USMAN', 'Lahore(LHR)', '03008165180', 'NILL', '2024-12-26 09:03:29', '2024-12-26 09:03:29', NULL),
(21, 'SHAHID SEWING', 'SHAHID', 'Lahore(LHR)', '03004236853', 'NILL', '2024-12-26 09:10:11', '2024-12-26 09:10:11', NULL),
(22, 'Khursheed Fan', 'Nasir Lateef', 'Sargodha(SGD)', '03016221369', 'nill', '2024-12-26 10:21:32', '2024-12-26 10:21:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL DEFAULT '1',
  `discount` varchar(255) NOT NULL DEFAULT '0',
  `service_charges` varchar(255) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `product_id`, `quantity`, `discount`, `service_charges`, `amount`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, '6', '1', '3000', '0', 25000.00, 22000.00, 'inactive', '2024-12-24 15:46:55', '2024-12-24 15:47:38'),
(2, '2', '1', '0', '0', 60000.00, 60000.00, 'inactive', '2024-12-24 15:47:16', '2024-12-24 15:47:38'),
(3, '7', '1', '03000', '0', 24000.00, 21000.00, 'inactive', '2024-12-24 15:48:04', '2024-12-24 15:49:42'),
(4, '6', '1', '03000', '0', 25000.00, 22000.00, 'inactive', '2024-12-24 15:48:31', '2024-12-24 15:49:42'),
(5, '51', '1', '5000', '0', 33000.00, 28000.00, 'inactive', '2024-12-25 14:21:13', '2024-12-25 14:22:40'),
(7, '75', '1', '1200', '0', 6000.00, 4800.00, 'inactive', '2024-12-25 15:41:11', '2024-12-25 15:42:27'),
(8, '41', '1', '300', '0', 1800.00, 1500.00, 'inactive', '2024-12-25 17:43:21', '2024-12-25 17:44:20'),
(9, '48', '1', '500', '0', 5000.00, 4500.00, 'inactive', '2024-12-26 10:03:16', '2024-12-26 10:04:08'),
(10, '48', '1', '500', '0', 5000.00, 4500.00, 'inactive', '2024-12-26 10:05:42', '2024-12-26 10:06:20'),
(12, '41', '3', '900', '0', 5400.00, 4500.00, 'inactive', '2024-12-26 12:14:03', '2024-12-26 12:16:03'),
(13, '42', '1', '100', '0', 1600.00, 1500.00, 'inactive', '2024-12-26 15:09:05', '2024-12-26 15:10:00'),
(14, '118', '1', '1300', '0', 13000.00, 11700.00, 'inactive', '2024-12-26 15:11:13', '2024-12-26 15:12:04'),
(15, '45', '1', '01600', '0', 6500.00, 4900.00, 'inactive', '2024-12-26 17:02:51', '2024-12-26 17:04:32'),
(16, '45', '1', '01600', '0', 6500.00, 4900.00, 'inactive', '2024-12-26 17:09:06', '2024-12-26 17:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Rana Electronics', 'ranaelectronics@gmail.com', '2024-12-23 10:41:29', '$2y$12$8.syzPCo/rfhRhEYYK2bLeEwS.MAGeIka8/gfk1ryu/vG1xFAxUZ2', 'admin', 'Y1OSDMUAuq', '2024-12-23 10:41:29', '2024-12-23 10:41:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_payments`
--
ALTER TABLE `manual_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manual_payments_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manual_payments`
--
ALTER TABLE `manual_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `manual_payments`
--
ALTER TABLE `manual_payments`
  ADD CONSTRAINT `manual_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
