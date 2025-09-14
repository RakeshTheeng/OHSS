-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 05:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ohss`
--

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `metric_name` varchar(255) NOT NULL,
  `metric_type` varchar(255) NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_request_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `total_amount` decimal(8,2) NOT NULL,
  `payment_method` enum('esewa','khalti','cash') DEFAULT NULL,
  `payment_status` enum('awaiting_payment','pending','paid','failed','refunded') DEFAULT 'pending',
  `status` enum('pending_payment','confirmed','in_progress','completed','cancelled') DEFAULT 'confirmed',
  `special_instructions` text DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `completion_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `service_request_id`, `customer_id`, `provider_id`, `scheduled_date`, `duration`, `total_amount`, `payment_method`, `payment_status`, `status`, `special_instructions`, `started_at`, `completed_at`, `completion_notes`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 4, '2025-08-21 14:28:00', 2, 600.00, 'cash', 'pending', 'completed', 'testing', NULL, '2025-08-08 17:45:28', 'completed', '2025-08-08 11:59:05', '2025-08-08 12:00:28'),
(2, 3, 15, 10, '2025-08-20 15:13:00', 6, 1800.00, 'cash', 'pending', 'completed', 'ok will do it on time', NULL, '2025-08-10 09:35:06', 'completed service', '2025-08-10 03:48:06', '2025-08-10 03:50:06'),
(5, 5, 15, 4, '2025-08-19 22:19:00', 4, 1200.00, 'esewa', 'awaiting_payment', 'pending_payment', 'dfd', NULL, NULL, NULL, '2025-08-10 10:50:10', '2025-08-10 10:50:10'),
(6, 6, 15, 9, '2025-08-28 21:07:00', 3, 750.00, 'esewa', 'awaiting_payment', 'pending_payment', NULL, NULL, NULL, NULL, '2025-08-11 09:37:33', '2025-08-11 09:37:33'),
(7, 8, 15, 4, '2025-08-12 21:21:00', 3, 900.00, 'esewa', 'awaiting_payment', 'pending_payment', 'sdsd', NULL, NULL, NULL, '2025-08-11 09:52:15', '2025-08-11 09:52:15'),
(8, 9, 5, 9, '2025-08-13 22:02:00', 2, 500.00, 'khalti', 'awaiting_payment', 'completed', 'tesing', NULL, '2025-08-11 16:28:35', 'completed', '2025-08-11 10:38:41', '2025-08-11 10:43:35'),
(9, 10, 5, 4, '2025-08-14 22:15:00', 1, 300.00, 'khalti', 'awaiting_payment', 'completed', NULL, NULL, '2025-08-11 16:36:46', 'completed', '2025-08-11 10:46:03', '2025-08-11 10:51:46'),
(10, 11, 5, 4, '2025-08-16 22:24:00', 2, 600.00, 'khalti', 'awaiting_payment', 'completed', 'testing', NULL, '2025-08-11 17:20:30', 'completed', '2025-08-11 10:54:27', '2025-08-11 11:35:30'),
(12, 12, 17, 13, '2025-08-14 18:37:00', 3, 900.00, 'esewa', 'awaiting_payment', 'completed', 'testing', NULL, '2025-08-13 12:55:03', 'completed', '2025-08-13 07:04:55', '2025-08-13 07:10:03'),
(13, 13, 18, 9, '2025-08-28 18:47:00', 3, 750.00, 'khalti', 'awaiting_payment', 'pending_payment', 'testing', NULL, NULL, NULL, '2025-08-13 07:18:00', '2025-08-13 07:18:00');

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
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `booking_id`, `customer_id`, `provider_id`, `is_active`, `created_at`, `updated_at`) VALUES
(3, NULL, 5, 4, 1, '2025-08-08 11:56:37', '2025-08-08 11:56:37'),
(4, 1, 5, 4, 1, '2025-08-08 11:59:06', '2025-08-08 11:59:06'),
(5, 1, 5, 4, 1, '2025-08-08 21:42:05', '2025-08-08 21:42:05'),
(6, NULL, 11, 4, 1, '2025-08-08 21:43:40', '2025-08-08 22:01:57'),
(7, NULL, 14, 13, 1, '2025-08-08 22:50:30', '2025-08-08 23:12:32'),
(8, NULL, 15, 10, 1, '2025-08-10 03:42:20', '2025-08-10 03:43:11'),
(9, 2, 15, 10, 1, '2025-08-10 03:48:06', '2025-08-10 03:48:28'),
(12, NULL, 15, 4, 1, '2025-08-10 10:23:48', '2025-08-10 10:23:48'),
(13, NULL, 15, 9, 1, '2025-08-11 09:35:27', '2025-08-11 09:35:27'),
(14, NULL, 5, 9, 1, '2025-08-11 10:32:13', '2025-08-11 10:32:13'),
(15, NULL, 17, 13, 1, '2025-08-13 07:03:55', '2025-08-13 07:08:32'),
(16, NULL, 18, 9, 1, '2025-08-13 07:17:34', '2025-08-13 07:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_request_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `kyc_documents`
--

CREATE TABLE `kyc_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` enum('citizenship','license','passport','other') NOT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `message`, `file_path`, `file_name`, `file_type`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 6, 11, 'Test message from customer', NULL, NULL, NULL, '2025-08-10 16:24:30', '2025-08-08 21:43:40', '2025-08-10 10:39:30'),
(2, 6, 11, 'Test message from customer', NULL, NULL, NULL, '2025-08-10 16:24:30', '2025-08-08 21:44:55', '2025-08-10 10:39:30'),
(3, 6, 11, 'Test message from customer', NULL, NULL, NULL, '2025-08-10 16:24:30', '2025-08-08 21:49:21', '2025-08-10 10:39:30'),
(4, 6, 11, 'Hello! This should work now.', NULL, NULL, NULL, '2025-08-10 16:24:30', '2025-08-08 22:00:28', '2025-08-10 10:39:30'),
(5, 6, 11, 'Great! The chat is working perfectly now!', NULL, NULL, NULL, '2025-08-10 16:24:30', '2025-08-08 22:01:57', '2025-08-10 10:39:30'),
(6, 7, 13, 'hello', NULL, NULL, NULL, '2025-08-09 04:36:06', '2025-08-08 22:50:43', '2025-08-08 22:51:06'),
(7, 7, 14, 'hello', NULL, NULL, NULL, '2025-08-09 04:36:20', '2025-08-08 22:51:18', '2025-08-08 22:51:20'),
(8, 7, 13, 'hello testing', NULL, NULL, NULL, '2025-08-09 04:57:20', '2025-08-08 23:12:17', '2025-08-08 23:12:20'),
(9, 7, 14, 'hello testing', NULL, NULL, NULL, '2025-08-09 04:57:37', '2025-08-08 23:12:32', '2025-08-08 23:12:37'),
(10, 8, 10, 'now you can make book for me', NULL, NULL, NULL, '2025-08-10 09:28:02', '2025-08-10 03:42:40', '2025-08-10 03:43:02'),
(11, 8, 15, 'ok', NULL, NULL, NULL, '2025-08-10 09:28:12', '2025-08-10 03:43:11', '2025-08-10 03:43:12'),
(12, 9, 15, 'hello', NULL, NULL, NULL, '2025-08-10 09:34:04', '2025-08-10 03:48:28', '2025-08-10 03:49:04'),
(13, 15, 13, 'hello', NULL, NULL, NULL, '2025-08-13 12:53:50', '2025-08-13 07:08:32', '2025-08-13 07:08:50');

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
(4, '2024_01_01_000001_create_service_categories_table', 1),
(5, '2024_01_01_000002_create_provider_services_table', 1),
(6, '2024_01_01_000003_create_service_requests_table', 1),
(7, '2024_01_01_000004_create_bookings_table', 1),
(8, '2024_01_01_000005_create_reviews_table', 1),
(9, '2024_01_01_000006_create_chat_messages_table', 1),
(10, '2024_01_01_000007_create_payments_table', 1),
(11, '2024_01_01_000008_create_kyc_documents_table', 1),
(12, '2024_01_01_000009_create_notifications_table', 1),
(13, '2025_08_07_023512_create_provider_availability_table', 1),
(14, '2025_08_07_023537_create_service_areas_table', 1),
(15, '2025_08_07_023559_create_analytics_table', 1),
(16, '2025_08_07_023946_add_additional_fields_to_users_table', 1),
(17, '2025_08_07_041623_add_profile_fields_to_users_table', 1),
(18, '2025_08_07_065208_add_budget_and_hours_fields_to_service_requests_table', 1),
(19, '2025_08_07_160113_create_testimonials_table', 1),
(20, '2025_08_08_025330_update_existing_users_email_verified', 1),
(21, '2025_08_08_030437_create_favorites_table', 1),
(22, '2025_08_08_161935_create_chats_table', 1),
(23, '2025_08_08_162027_create_messages_table', 1),
(24, '2025_08_09_fix_provider_availability', 2),
(27, '2025_08_10_161534_add_pending_payment_status_to_bookings_table', 3),
(28, '2025_08_10_161623_add_awaiting_payment_status_to_payments_table', 3),
(29, '2025_08_10_163249_update_booking_payment_status_enum', 4),
(30, '2025_08_11_161800_add_khalti_to_payment_method_enums', 5),
(31, '2025_08_11_181258_create_settings_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Rakesh Theeng. You can now proceed to book the service.', '{\"service_request_id\":1}', 0, NULL, '2025-08-08 11:56:37', '2025-08-08 11:56:37'),
(2, 5, 'service_completed', 'Service Completed', 'Your service has been completed by Rakesh Theeng', '{\"service_request_id\":1}', 0, NULL, '2025-08-08 12:00:28', '2025-08-08 12:00:28'),
(3, 4, 'message_received', 'New Message', 'You have a new message from Test Customer', '{\"chat_id\":6}', 0, NULL, '2025-08-08 22:00:29', '2025-08-08 22:00:29'),
(4, 4, 'message_received', 'New Message', 'You have a new message from Test Customer', '{\"chat_id\":6}', 0, NULL, '2025-08-08 22:01:57', '2025-08-08 22:01:57'),
(5, 14, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Chhiring Lama. You can now proceed to book the service.', '{\"service_request_id\":2}', 0, NULL, '2025-08-08 22:50:30', '2025-08-08 22:50:30'),
(6, 14, 'message_received', 'New Message', 'You have a new message from Chhiring Lama', '{\"chat_id\":7}', 0, NULL, '2025-08-08 22:50:43', '2025-08-08 22:50:43'),
(7, 13, 'message_received', 'New Message', 'You have a new message from Kamal Tamang', '{\"chat_id\":7}', 0, NULL, '2025-08-08 22:51:18', '2025-08-08 22:51:18'),
(8, 14, 'message_received', 'New Message', 'You have a new message from Chhiring Lama', '{\"chat_id\":7}', 0, NULL, '2025-08-08 23:12:17', '2025-08-08 23:12:17'),
(9, 13, 'message_received', 'New Message', 'You have a new message from Kamal Tamang', '{\"chat_id\":7}', 0, NULL, '2025-08-08 23:12:32', '2025-08-08 23:12:32'),
(10, 14, 'service_completed', 'Service Completed', 'Your service has been completed by Chhiring Lama', '{\"service_request_id\":2}', 0, NULL, '2025-08-08 23:24:47', '2025-08-08 23:24:47'),
(11, 15, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Nupur Khadgi. You can now proceed to book the service.', '{\"service_request_id\":3}', 0, NULL, '2025-08-10 03:42:20', '2025-08-10 03:42:20'),
(12, 15, 'message_received', 'New Message', 'You have a new message from Nupur Khadgi', '{\"chat_id\":8}', 0, NULL, '2025-08-10 03:42:40', '2025-08-10 03:42:40'),
(13, 10, 'message_received', 'New Message', 'You have a new message from Sonam Tamang', '{\"chat_id\":8}', 0, NULL, '2025-08-10 03:43:11', '2025-08-10 03:43:11'),
(14, 10, 'message_received', 'New Message', 'You have a new message from Sonam Tamang', '{\"chat_id\":9}', 0, NULL, '2025-08-10 03:48:28', '2025-08-10 03:48:28'),
(15, 15, 'service_completed', 'Service Completed', 'Your service has been completed by Nupur Khadgi', '{\"service_request_id\":3}', 0, NULL, '2025-08-10 03:50:06', '2025-08-10 03:50:06'),
(16, 15, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Abinash shrestha. You can now proceed to book the service.', '{\"service_request_id\":4}', 0, NULL, '2025-08-10 09:47:30', '2025-08-10 09:47:30'),
(17, 15, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Rakesh Theeng. You can now proceed to book the service.', '{\"service_request_id\":5}', 0, NULL, '2025-08-10 10:23:48', '2025-08-10 10:23:48'),
(18, 15, 'service_completed', 'Service Completed', 'Your service has been completed by Abinash shrestha', '{\"service_request_id\":4}', 0, NULL, '2025-08-11 07:33:14', '2025-08-11 07:33:14'),
(19, 15, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Wangbu Tamang. You can now proceed to book the service.', '{\"service_request_id\":6}', 0, NULL, '2025-08-11 09:35:27', '2025-08-11 09:35:27'),
(20, 15, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Rakesh Theeng. You can now proceed to book the service.', '{\"service_request_id\":8}', 0, NULL, '2025-08-11 09:51:21', '2025-08-11 09:51:21'),
(21, 5, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Wangbu Tamang. You can now proceed to book the service.', '{\"service_request_id\":9}', 0, NULL, '2025-08-11 10:32:13', '2025-08-11 10:32:13'),
(22, 5, 'service_completed', 'Service Completed', 'Your service has been completed by Wangbu Tamang', '{\"service_request_id\":9}', 0, NULL, '2025-08-11 10:43:35', '2025-08-11 10:43:35'),
(23, 5, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Rakesh Theeng. You can now proceed to book the service.', '{\"service_request_id\":10}', 0, NULL, '2025-08-11 10:45:16', '2025-08-11 10:45:16'),
(24, 5, 'service_completed', 'Service Completed', 'Your service has been completed by Rakesh Theeng', '{\"service_request_id\":10}', 0, NULL, '2025-08-11 10:51:46', '2025-08-11 10:51:46'),
(25, 5, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Rakesh Theeng. You can now proceed to book the service.', '{\"service_request_id\":11}', 0, NULL, '2025-08-11 10:53:52', '2025-08-11 10:53:52'),
(26, 5, 'service_completed', 'Service Completed', 'Your service has been completed by Rakesh Theeng', '{\"service_request_id\":11}', 0, NULL, '2025-08-11 11:35:30', '2025-08-11 11:35:30'),
(27, 17, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Chhiring Lama. You can now proceed to book the service.', '{\"service_request_id\":12}', 0, NULL, '2025-08-13 07:03:55', '2025-08-13 07:03:55'),
(28, 17, 'message_received', 'New Message', 'You have a new message from Chhiring Lama', '{\"chat_id\":15}', 0, NULL, '2025-08-13 07:08:32', '2025-08-13 07:08:32'),
(29, 17, 'service_completed', 'Service Completed', 'Your service has been completed by Chhiring Lama', '{\"service_request_id\":12}', 0, NULL, '2025-08-13 07:10:03', '2025-08-13 07:10:03'),
(30, 18, 'service_request_accepted', 'Request Accepted!', 'Your service request has been accepted by Wangbu Tamang. You can now proceed to book the service.', '{\"service_request_id\":13}', 0, NULL, '2025-08-13 07:17:34', '2025-08-13 07:17:34');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_method` enum('esewa','khalti','cash') DEFAULT NULL,
  `status` enum('awaiting_payment','pending','processing','completed','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `esewa_ref_id` varchar(255) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `paid_at` datetime DEFAULT NULL,
  `failure_reason` text DEFAULT NULL,
  `refund_amount` decimal(8,2) DEFAULT NULL,
  `refunded_at` datetime DEFAULT NULL,
  `refund_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `customer_id`, `provider_id`, `amount`, `payment_method`, `status`, `transaction_id`, `esewa_ref_id`, `gateway_response`, `paid_at`, `failure_reason`, `refund_amount`, `refunded_at`, `refund_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 4, 600.00, 'cash', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-08 11:59:06', '2025-08-08 11:59:06'),
(2, 2, 15, 10, 1800.00, 'cash', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-10 03:48:06', '2025-08-10 03:48:06'),
(4, 5, 15, 4, 1200.00, 'esewa', 'awaiting_payment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-10 10:50:10', '2025-08-10 10:50:10'),
(5, 6, 15, 9, 750.00, 'esewa', 'awaiting_payment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 09:37:33', '2025-08-11 09:37:33'),
(6, 7, 15, 4, 900.00, 'esewa', 'awaiting_payment', 'OHSS-1754927199-6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 09:52:16', '2025-08-11 10:01:43'),
(7, 8, 5, 9, 500.00, 'khalti', 'awaiting_payment', 'OHSS-KH-1754932342-7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 10:38:41', '2025-08-11 11:27:22'),
(8, 9, 5, 4, 300.00, 'khalti', 'awaiting_payment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 10:46:03', '2025-08-11 10:46:03'),
(9, 10, 5, 4, 600.00, 'khalti', 'awaiting_payment', 'OHSS-KH-1754932309-9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 10:54:27', '2025-08-11 11:26:49'),
(11, 12, 17, 13, 900.00, 'esewa', 'awaiting_payment', 'OHSS-1755089395-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-13 07:04:55', '2025-08-13 07:04:56'),
(12, 13, 18, 9, 750.00, 'khalti', 'awaiting_payment', 'OHSS-KH-1755090180-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-13 07:18:00', '2025-08-13 07:18:00');

-- --------------------------------------------------------

--
-- Table structure for table `provider_availability`
--

CREATE TABLE `provider_availability` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_services`
--

CREATE TABLE `provider_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_category_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_services`
--

INSERT INTO `provider_services` (`id`, `user_id`, `service_category_id`, `price`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 9, 8, NULL, NULL, 1, '2025-08-08 21:31:41', '2025-08-08 21:31:41'),
(3, 10, 5, NULL, NULL, 1, '2025-08-08 21:39:55', '2025-08-08 21:39:55'),
(4, 4, 4, NULL, NULL, 1, '2025-08-08 21:46:08', '2025-08-08 21:46:08'),
(6, 13, 3, NULL, NULL, 1, '2025-08-08 21:53:45', '2025-08-08 21:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `provider_response` text DEFAULT NULL,
  `provider_responded_at` datetime DEFAULT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `flag_reason` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `booking_id`, `customer_id`, `provider_id`, `rating`, `comment`, `provider_response`, `provider_responded_at`, `is_flagged`, `flag_reason`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 4, 4, 'nice service', NULL, NULL, 0, NULL, 1, '2025-08-08 12:00:51', '2025-08-08 12:00:51'),
(2, 2, 15, 10, 3, 'Could be better', 'thank you for you feedback', '2025-08-10 09:36:51', 0, NULL, 1, '2025-08-10 03:51:08', '2025-08-10 03:51:51'),
(4, 12, 17, 13, 5, 'Very Good Service', NULL, NULL, 0, NULL, 1, '2025-08-13 07:11:08', '2025-08-13 07:11:08');

-- --------------------------------------------------------

--
-- Table structure for table `service_areas`
--

CREATE TABLE `service_areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `radius_km` int(11) NOT NULL DEFAULT 5,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `slug`, `description`, `icon`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Plumbing', 'plumbing', 'Professional plumbing services including pipe repair, installation, and maintenance', 'fas fa-wrench', NULL, 1, 1, '2025-08-08 11:32:18', '2025-08-08 11:32:18'),
(2, 'Electrical', 'electrical', 'Electrical installation, repair, and maintenance services', 'fas fa-bolt', NULL, 1, 2, '2025-08-08 11:32:18', '2025-08-08 11:32:18'),
(3, 'Cleaning', 'cleaning', 'House cleaning, deep cleaning, and maintenance services', 'fas fa-broom', NULL, 1, 3, '2025-08-08 11:32:18', '2025-08-08 11:32:18'),
(4, 'Carpentry', 'carpentry', 'Wood work, furniture repair, and custom carpentry services', 'fas fa-hammer', NULL, 1, 4, '2025-08-08 11:32:18', '2025-08-08 11:32:18'),
(5, 'Painting', 'painting', 'Interior and exterior painting services', 'fas fa-paint-roller', NULL, 1, 5, '2025-08-08 11:32:18', '2025-08-08 11:32:18'),
(6, 'Gardening', 'gardening', 'Garden maintenance, landscaping, and plant care services', 'fas fa-seedling', NULL, 1, 6, '2025-08-08 11:32:18', '2025-08-08 11:32:18'),
(7, 'AC Repair', 'ac-repair', 'Air conditioning installation, repair, and maintenance', 'fas fa-snowflake', NULL, 1, 7, '2025-08-08 11:32:18', '2025-08-08 11:32:18'),
(8, 'Appliance Repair', 'appliance-repair', 'Home appliance repair and maintenance services', 'fas fa-tools', NULL, 1, 8, '2025-08-08 11:32:18', '2025-08-08 11:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `service_category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `address` text NOT NULL,
  `budget_min` decimal(8,2) DEFAULT NULL,
  `budget_max` decimal(8,2) DEFAULT NULL,
  `required_hours` decimal(5,2) DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `total_budget` decimal(8,2) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `preferred_date` datetime DEFAULT NULL,
  `preferred_time` varchar(255) DEFAULT NULL,
  `estimated_duration` int(11) DEFAULT NULL,
  `estimated_price` decimal(8,2) DEFAULT NULL,
  `status` enum('pending','accepted','rejected','booked','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `provider_response` text DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `urgency` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `responded_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `customer_id`, `provider_id`, `service_category_id`, `title`, `description`, `address`, `budget_min`, `budget_max`, `required_hours`, `hourly_rate`, `total_budget`, `latitude`, `longitude`, `preferred_date`, `preferred_time`, `estimated_duration`, `estimated_price`, `status`, `provider_response`, `additional_notes`, `urgency`, `responded_at`, `created_at`, `updated_at`) VALUES
(1, 5, 4, 1, 'plumbing', 'testing', 'Kathmandu', NULL, NULL, 2.00, 300.00, 600.00, NULL, NULL, '2025-08-15 00:00:00', 'morning', NULL, NULL, 'completed', 'accepted', NULL, 'low', '2025-08-08 17:41:37', '2025-08-08 11:55:41', '2025-08-08 12:00:28'),
(2, 14, 13, 1, 'plumbing', 'testing', 'Sundarijal', NULL, NULL, 3.00, 300.00, 900.00, NULL, NULL, '2025-08-14 00:00:00', 'morning', NULL, NULL, 'completed', 'i want to accept it', 'testing', 'medium', '2025-08-09 04:35:30', '2025-08-08 22:48:52', '2025-08-08 23:24:47'),
(3, 15, 10, 5, 'Paint for House', 'i need painter for my house painting, i want to hire you for my this service', 'Nayabasti', NULL, NULL, 6.00, 300.00, 1800.00, NULL, NULL, '2025-08-20 00:00:00', 'afternoon', NULL, NULL, 'completed', 'i have accepted it', 'make sure ?', 'low', '2025-08-10 09:27:20', '2025-08-10 03:40:47', '2025-08-10 03:50:06'),
(5, 15, 4, 3, 'testing', 'testing', 'Nayabasti', NULL, NULL, 2.00, 300.00, 600.00, NULL, NULL, '2025-08-14 00:00:00', 'morning', NULL, NULL, 'accepted', 'accepted', 'testing', 'medium', '2025-08-10 16:08:48', '2025-08-10 10:22:58', '2025-08-10 10:23:48'),
(6, 15, 9, 2, 'testing', 'testing', 'Nayabasti', NULL, NULL, 3.00, 250.00, 750.00, NULL, NULL, '2025-08-20 00:00:00', 'afternoon', NULL, NULL, 'accepted', 'accepted', NULL, 'medium', '2025-08-11 15:20:27', '2025-08-11 09:34:15', '2025-08-11 09:35:27'),
(8, 15, 4, 1, 'dsd', 'dsd', 'Nayabasti', NULL, NULL, 2.00, 300.00, 600.00, NULL, NULL, '2025-08-16 00:00:00', 'afternoon', NULL, NULL, 'accepted', 'accepted', 'sdsd', 'medium', '2025-08-11 15:36:21', '2025-08-11 09:50:44', '2025-08-11 09:51:21'),
(9, 5, 9, 2, 'testing', 'testing', 'Kathmandu', NULL, NULL, 3.00, 250.00, 750.00, NULL, NULL, '2025-08-16 00:00:00', 'morning', NULL, NULL, 'completed', 'accepted', 'testing', 'low', '2025-08-11 16:17:13', '2025-08-11 10:31:27', '2025-08-11 10:43:35'),
(10, 5, 4, 4, 'testing', 'testing', 'Kathmandu', NULL, NULL, 3.00, 300.00, 900.00, NULL, NULL, '2025-08-22 00:00:00', 'afternoon', NULL, NULL, 'completed', 'accepted', NULL, 'low', '2025-08-11 16:30:16', '2025-08-11 10:44:31', '2025-08-11 10:51:46'),
(11, 5, 4, 3, 'fgfg', 'fgfg', 'Kathmandu', NULL, NULL, 3.00, 300.00, 900.00, NULL, NULL, '2025-08-23 00:00:00', 'afternoon', NULL, NULL, 'completed', 'accepted', 'dfdf', 'low', '2025-08-11 16:38:52', '2025-08-11 10:53:34', '2025-08-11 11:35:30'),
(12, 17, 13, 3, 'Kitchen', 'Testing kitchen', 'Chabel', NULL, NULL, 3.00, 300.00, 900.00, NULL, NULL, '2025-08-14 00:00:00', 'morning', NULL, NULL, 'completed', 'Testing', 'testing', 'low', '2025-08-13 12:48:55', '2025-08-13 07:03:02', '2025-08-13 07:10:03'),
(13, 18, 9, 8, 'Kitchen', 'Testing', 'Boudha', NULL, NULL, 3.00, 250.00, 750.00, NULL, NULL, '2025-08-14 00:00:00', 'morning', NULL, NULL, 'accepted', 'accepted', 'testing', 'low', '2025-08-13 13:02:34', '2025-08-13 07:16:08', '2025-08-13 07:17:34');

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

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Online Household Service System', 'string', NULL, '2025-08-11 12:34:45', '2025-08-11 12:34:45'),
(2, 'site_description', 'Professional household services at your doorstep', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(3, 'contact_email', 'admin@ohss.com', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(4, 'contact_phone', '+977-1-4444444', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(5, 'contact_address', 'Kathmandu, Nepal', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(6, 'commission_rate', '10', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(7, 'min_booking_amount', '500', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(8, 'max_booking_amount', '50000', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(9, 'booking_advance_hours', '2', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(10, 'cancellation_hours', '24', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(11, 'esewa_merchant_id', NULL, 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(12, 'esewa_secret_key', NULL, 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(13, 'khalti_public_key', NULL, 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(14, 'khalti_secret_key', NULL, 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(15, 'maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(16, 'max_login_attempts', '5', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(17, 'session_timeout', '120', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(18, 'log_retention_days', '30', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(19, 'auto_approve_providers', '0', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(20, 'enable_esewa', '1', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(21, 'enable_khalti', '0', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(22, 'enable_cash_payment', '1', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(23, 'email_notifications', '1', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(24, 'sms_notifications', '0', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(25, 'push_notifications', '1', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(26, 'admin_email_alerts', '1', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(27, 'require_email_verification', '1', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(28, 'require_phone_verification', '0', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(29, 'enable_two_factor', '0', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(30, 'maintenance_mode', '0', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46'),
(31, 'backup_frequency', 'daily', 'string', NULL, '2025-08-11 12:34:46', '2025-08-11 12:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `review_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `provider_name` varchar(255) NOT NULL,
  `service_category` varchar(255) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `role` enum('admin','provider','customer') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `kyc_document` varchar(255) DEFAULT NULL,
  `citizenship_number` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `services` text DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `provider_status` enum('awaiting','approved','verified','rejected') DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `address`, `bio`, `experience_years`, `kyc_document`, `citizenship_number`, `latitude`, `longitude`, `profile_image`, `status`, `services`, `hourly_rate`, `provider_status`, `rejection_reason`, `rating`, `total_reviews`, `is_available`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@householdservices.com', '2025-08-08 11:32:19', '$2y$12$Oj5nM4FZYvMOd1RYzo8ZN.cG2w5srPyyWBmxVeCYrPl5m4YfCNSE.', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, 'h1oMWsBv5KJ2zRLkOeJhoIpIG56tGFlYp52Od4YDy9fQ7zgrmgmwbOffG6Cz', '2025-08-08 11:32:19', '2025-08-08 11:32:19'),
(4, 'Rakesh Theeng', 'theengrakesh55@gmail.com', NULL, '$2y$12$VMrzFK/XhAqLhlNnCvjLRe3BScisBn0M7OAsLmJxBWoe4lz3duHN.', 'provider', '9803650653', 'Kageshwori-1, Kathmandu', 'Rakesh Theeng is a skilled carpenter, known for his craftsmanship and precision in working with wood. He designs, builds, and repairs wooden structures and furniture, combining traditional techniques with modern tools to create durable and functional pieces. Whether it’s crafting custom furniture, installing fittings, or repairing wooden frameworks, Rakesh focuses on accuracy, quality, and fine finishing. His work reflects patience, creativity, and attention to detail, turning raw materials into practical and beautiful products that meet the needs of his clients.', 3, 'kyc-documents/SlYDY4nodfTvSTrajYkshh627dXJRlwHkwmmcmn6.png', '12-23-45-12345', NULL, NULL, 'profile-images/WxFDjXxVeBZP51HS4n4p2nLPaqeJPZlc0ARh1gdz.png', 'active', NULL, 300.00, 'approved', NULL, 4.00, 1, 1, NULL, '2025-08-08 11:48:25', '2025-08-08 23:19:00'),
(5, 'Ram Tamang', 'ram@gmail.com', NULL, '$2y$12$G4TAyUmo1Mt1PcjYzvxJ7.Nq9Ny64f53JKun80pVWjBnhe4nrsABO', 'customer', '9876452341', 'Kathmandu', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-images/dV2ZXi2pzS1dzJ7Mm2s8AV2saAJgRsjn5UnpYvXU.png', 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, NULL, '2025-08-08 11:50:48', '2025-08-08 11:50:48'),
(9, 'Wangbu Tamang', 'wangbutamang@gmail.com', NULL, '$2y$12$XuorqftVWJn72d9pY4FkeOo0gciW0X68ATmKVun.X4CqksmpMlOJu', 'provider', '9812345678', 'Boudha', 'Wangbu is a skilled and knowledgeable technician, someone who works hands-on to repair, maintain, or install equipment and systems. As a technician, he is practical, detail-oriented, and solution-driven, often applying both technical know-how and problem-solving skills to get tasks done efficiently. Whether it’s handling electrical work, troubleshooting mechanical issues, setting up devices, or ensuring that systems run smoothly, Wangbu focuses on precision and quality. His work often involves using specialized tools, following safety procedures, and staying updated with new techniques or technologies.', 3, 'kyc-documents/tnCn59upOzrDgCRbNpPvx0tlguIbuwwvStfHCxNu.png', '23-56-32-3254', NULL, NULL, 'profile-images/gWPXiRnfztig8CFwJ3veArOoRUHaW9Zlsc9Bhd8t.png', 'active', NULL, 250.00, 'approved', NULL, 0.00, 0, 1, '3rGTctZIjOL6VeQiNB4bEcm37woDIw32s6ncoAsB1aLBnFaJMYhkilonAm8G', '2025-08-08 21:31:41', '2025-09-14 09:19:49'),
(10, 'Nupur Khadgi', 'nupurkhadgi@gmail.com', NULL, '$2y$12$w8OT8vTNrD8qIPgjtyig1OH29pPcivnSZT8gbewqo5dmnfEibtWV6', 'provider', '9812345678', 'Jorpati', 'Nupur Khadgi is a talented painter, known for her creativity, steady hand, and attention to detail. As a painter, she brings colors to life—whether it’s decorating walls, restoring old surfaces, or adding artistic touches to a space. Her work involves careful preparation, from choosing the right paints and tools to ensuring smooth, even finishes that last. Nupur combines technical skill with an artistic sense, making her projects not only neat and professional but also visually appealing. Her dedication, patience, and eye for color allow her to transform ordinary surfaces into vibrant and beautiful works.', 4, 'kyc-documents/zDqoyz8HJvaGaKKM6OQhPSaouJj0GgU82P45KtVz.png', '34-54-67-1234', NULL, NULL, 'profile-images/wuNs5xWROqv5gJpLAmSh9AMhwq9bZ56WakJr0EDZ.png', 'active', NULL, 300.00, 'approved', NULL, 3.00, 1, 0, 'FqkJwSPtPS8z8SuuoY2uyFQh5VtrMYS8h0smxWL5GQztxsAqCiZseKDtpMwT', '2025-08-08 21:39:54', '2025-09-14 09:19:10'),
(11, 'Test Customer', 'test@customer.com', NULL, '$2y$12$jzUK8o7mOiTK/7iueW1BPOAHYF.oxuz.LNOu5TQenXzzNI6iq9J5S', 'customer', '1234567890', 'Test Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, NULL, '2025-08-08 21:43:40', '2025-08-08 21:43:40'),
(13, 'Chhiring Lama', 'chhiringlama@gmail.com', NULL, '$2y$12$Dba8xwDUwSUAQeqC85nIjeK1XCl2wYIPDV08LQS9R/IKAL1iMF/rW', 'provider', '9812345687', 'Gokarna', 'Chhiring Lama is a dedicated cleaner, known for keeping spaces neat, hygienic, and welcoming. With attention to detail and a strong work ethic, he ensures that floors, surfaces, and surroundings are spotless and well-maintained. Chhiring uses the right cleaning tools and techniques to remove dust, dirt, and stains, creating a safe and pleasant environment. Whether it’s homes, offices, or public areas, his commitment to cleanliness and order helps improve comfort, hygiene, and overall appearance.', 5, NULL, NULL, NULL, NULL, 'profile-images/8Y6doPQucbs4NqjLOyuhHx7LiMlSmZz7c0AUC8e3.png', 'active', NULL, 300.00, 'approved', NULL, 5.00, 1, 1, NULL, '2025-08-08 21:53:45', '2025-09-14 09:18:10'),
(14, 'Kamal Tamang', 'kamaltamang33@gmail.com', NULL, '$2y$12$lhQBcdAgh1gb5fPuqHjzY.WBzkxF7hhjrnzA8w.2NYnTWKIrqjtiu', 'customer', '9812345687', 'Sundarijal', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-images/b3rEV0UzyRdWdFEbmeZKcvB8MfTWdMU2nSOioHk1.png', 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, NULL, '2025-08-08 21:55:47', '2025-08-08 21:55:47'),
(15, 'Sonam Tamang', 'sonamtamang@gmail.com', NULL, '$2y$12$vEvYamy43..49nYBGack5e/b8drfzr4DDNDMrxbF4ZNO1qw6nc2e2', 'customer', '98121435687', 'Nayabasti', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-images/Rsx9yRALXOk4fkKojMvkdF0732qybwt4JEKIxkEo.png', 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, NULL, '2025-08-10 03:35:21', '2025-08-10 03:35:21'),
(16, 'Test Customer', 'testcustomer@example.com', NULL, '$2y$12$8I6ITuuR/VObtmdVQxGIBOJvwm.Briu/bHdRFgWpzHarm3YG8CUeW', 'customer', '9876543210', 'Kathmandu, Nepal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, NULL, '2025-08-11 09:44:35', '2025-08-11 09:44:35'),
(17, 'John Tamang', 'john@gmail.com', NULL, '$2y$12$WJubaWfdWUhjrX0R7NWXP.FFWuREf3LyXxMD4B3oYdDj1aHKkQt2a', 'customer', '9812435678', 'Chabel', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-images/aeVns88rLdtts9k8YHr9oeUz94Cq4BV5K6QrYzW0.png', 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, NULL, '2025-08-13 07:00:44', '2025-08-13 07:00:44'),
(18, 'Ang Tesring Gurung', 'angtesring@gmail.com', NULL, '$2y$12$tHv3stvbFDrIg3cy/9js7OnK0Agh1GEkDA6g5DWbp/D4mzTQR7IyK', 'customer', '9812345678', 'Boudha', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-images/tcruLjurA8Mh0f6rnAwUhYx7A00jIb9rddNaRKyr.png', 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, 'SCORXfBd69KySxGedXxizWfm5vW99NX1AOVYPuMJVeX0Huk3ZhIy5OvGXN1a', '2025-08-13 07:14:41', '2025-08-13 07:14:41'),
(19, 'Admin User', 'admin@admin.com', NULL, '$2y$12$oJIN9mymWFcsG3jXDUKhG./X62pJeCzzSDQfhBQ5eKZdf/nNNzuja', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, 0.00, 0, 1, NULL, '2025-08-13 08:16:00', '2025-08-13 08:16:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `analytics_metric_name_date_index` (`metric_name`,`date`),
  ADD KEY `analytics_date_category_index` (`date`,`category`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_service_request_id_foreign` (`service_request_id`),
  ADD KEY `bookings_customer_id_foreign` (`customer_id`),
  ADD KEY `bookings_provider_id_foreign` (`provider_id`);

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
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_provider_id_foreign` (`provider_id`),
  ADD KEY `chats_customer_id_provider_id_index` (`customer_id`,`provider_id`),
  ADD KEY `chats_booking_id_index` (`booking_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_service_request_id_foreign` (`service_request_id`),
  ADD KEY `chat_messages_sender_id_foreign` (`sender_id`),
  ADD KEY `chat_messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_customer_id_provider_id_unique` (`customer_id`,`provider_id`),
  ADD KEY `favorites_customer_id_index` (`customer_id`),
  ADD KEY `favorites_provider_id_index` (`provider_id`);

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
-- Indexes for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kyc_documents_user_id_foreign` (`user_id`),
  ADD KEY `kyc_documents_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_chat_id_created_at_index` (`chat_id`,`created_at`),
  ADD KEY `messages_sender_id_index` (`sender_id`),
  ADD KEY `messages_read_at_index` (`read_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_booking_id_foreign` (`booking_id`),
  ADD KEY `payments_customer_id_foreign` (`customer_id`),
  ADD KEY `payments_provider_id_foreign` (`provider_id`);

--
-- Indexes for table `provider_availability`
--
ALTER TABLE `provider_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_availability_provider_id_day_of_week_unique` (`provider_id`,`day_of_week`);

--
-- Indexes for table `provider_services`
--
ALTER TABLE `provider_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_services_user_id_service_category_id_unique` (`user_id`,`service_category_id`),
  ADD KEY `provider_services_service_category_id_foreign` (`service_category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`),
  ADD KEY `reviews_customer_id_foreign` (`customer_id`),
  ADD KEY `reviews_provider_id_foreign` (`provider_id`);

--
-- Indexes for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_areas_provider_id_foreign` (`provider_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_categories_slug_unique` (`slug`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_requests_customer_id_foreign` (`customer_id`),
  ADD KEY `service_requests_provider_id_foreign` (`provider_id`),
  ADD KEY `service_requests_service_category_id_foreign` (`service_category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`),
  ADD KEY `settings_key_index` (`key`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonials_review_id_foreign` (`review_id`),
  ADD KEY `testimonials_customer_id_foreign` (`customer_id`),
  ADD KEY `testimonials_is_active_rating_index` (`is_active`,`rating`),
  ADD KEY `testimonials_provider_id_is_active_index` (`provider_id`,`is_active`),
  ADD KEY `testimonials_is_featured_rating_index` (`is_featured`,`rating`),
  ADD KEY `testimonials_service_category_index` (`service_category`);

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
-- AUTO_INCREMENT for table `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `provider_availability`
--
ALTER TABLE `provider_availability`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_services`
--
ALTER TABLE `provider_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_areas`
--
ALTER TABLE `service_areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  ADD CONSTRAINT `kyc_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kyc_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_availability`
--
ALTER TABLE `provider_availability`
  ADD CONSTRAINT `provider_availability_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_services`
--
ALTER TABLE `provider_services`
  ADD CONSTRAINT `provider_services_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD CONSTRAINT `service_areas_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `testimonials_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `testimonials_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
