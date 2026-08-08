-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2023 at 01:35 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikrishiporibar`
--

-- --------------------------------------------------------

--
-- Table structure for table `buy_stocks`
--

CREATE TABLE `buy_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `payment_id` bigint(20) NOT NULL,
  `sceenshort` varchar(255) NOT NULL,
  `pay_from_number` varchar(255) NOT NULL,
  `trx_number` varchar(255) NOT NULL,
  `stock_id` bigint(20) NOT NULL,
  `buy_quantiy` bigint(20) NOT NULL,
  `buyed_price` double(8,2) NOT NULL,
  `buyed_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(255) NOT NULL COMMENT 'aproved,pending,sellpending,sellaproved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contuct_messages`
--

CREATE TABLE `contuct_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_number` varchar(255) DEFAULT NULL,
  `customer_message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_infos`
--

CREATE TABLE `employee_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `e_nid_number` varchar(255) DEFAULT NULL,
  `e_nid_img` varchar(255) DEFAULT NULL,
  `e_bath_number` varchar(255) DEFAULT NULL,
  `e_bath_img` varchar(255) DEFAULT NULL,
  `e_office_id_number` varchar(255) DEFAULT NULL,
  `e_office_id_img` varchar(255) DEFAULT NULL,
  `e_signature` varchar(255) DEFAULT NULL,
  `e_cv` varchar(255) DEFAULT NULL,
  `e_father_name` varchar(255) DEFAULT NULL,
  `e_mother_name` varchar(255) DEFAULT NULL,
  `e_gender` varchar(255) DEFAULT NULL,
  `e_age` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `e_permission_id` int(11) DEFAULT NULL,
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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_02_24_064423_create_site_settings_table', 1),
(6, '2023_03_14_184022_create_stocks_table', 1),
(7, '2023_03_14_184200_create_buy_stocks_table', 1),
(8, '2023_03_14_184211_create_sell_stocks_table', 1),
(9, '2023_03_14_184325_create_user_carts_table', 1),
(10, '2023_03_14_184653_create_employee_infos_table', 1),
(11, '2023_03_14_184851_create_contuct_messages_table', 1),
(12, '2023_03_14_185332_create_stock_galleries_table', 1),
(13, '2023_03_14_185443_create_site_payment_systems_table', 1),
(14, '2023_03_14_185507_create_user_payment_systems_table', 1),
(15, '2023_03_14_190335_create_stock_prices_table', 1),
(16, '2023_03_14_192128_create_user_profites_table', 1),
(17, '2023_03_14_210301_create_withdraws_table', 1),
(18, '2023_03_19_151556_create_permissions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_list` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sell_stocks`
--

CREATE TABLE `sell_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `buy_id` bigint(20) DEFAULT NULL,
  `stock_id` bigint(20) NOT NULL,
  `selled_price` double(8,2) NOT NULL,
  `selled_quantiy` bigint(20) NOT NULL,
  `selled_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(255) NOT NULL COMMENT 'aproved,pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_payment_systems`
--

CREATE TABLE `site_payment_systems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pay_s_name` varchar(255) NOT NULL,
  `pay_s_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'title', 'ikrishiporibar', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(2, 'logo', '1681391281zoPqs.png', '2023-04-10 11:44:19', '2023-04-13 13:08:01'),
(3, 'favicon', '1681488856i6gXi.png', '2023-04-10 11:44:19', '2023-04-14 16:14:16'),
(4, 'address1', 'Head Office:Kunjaban', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(5, 'address2', ', Mohadevpur, Naogaon.', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(6, 'phone1', '+8801976131504', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(7, 'phone2', NULL, '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(8, 'email1', 'iqbal.jafariqbal1757@hotmail.com', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(9, 'email2', NULL, '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(10, 'about_us_text', 'This is about us', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(11, 'about_us_text2', 'This is about us', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(12, 'slider1_text', 'ATTA', '2023-04-10 11:44:19', '2023-04-14 16:11:10'),
(13, 'slider2_text', 'This is Slider 2', '2023-04-10 11:44:19', '2023-04-14 16:11:10'),
(14, 'slider3_text', 'This is Slider 3', '2023-04-10 11:44:19', NULL),
(15, 'slider4_text', 'This is Slider 4', '2023-04-10 11:44:19', NULL),
(16, 'slider1_description', 'Whole Wheat Atta 1Kg Pack                                                   ThisisSlider4', '2023-04-10 11:44:19', '2023-04-14 16:11:10'),
(17, 'slider2_description', 'This is Slider 4', '2023-04-10 11:44:19', '2023-04-14 16:11:10'),
(18, 'slider3_description', 'This is Slider 4', '2023-04-10 11:44:19', NULL),
(19, 'slider4_description', 'This is Slider 4', '2023-04-10 11:44:19', NULL),
(20, 'slider1_img', '1681488670wHdZM.jpg', '2023-04-10 11:44:19', '2023-04-14 16:11:10'),
(21, 'slider2_img', 'slider2_img.png', '2023-04-10 11:44:19', NULL),
(22, 'slider3_img', 'slider3_img.png', '2023-04-10 11:44:19', NULL),
(23, 'slider4_img', 'slider4_img.png', '2023-04-10 11:44:19', NULL),
(24, 'facbook_link', 'http:://facebook.com', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(25, 'linkedin_link', 'http:://Linkedin.com', '2023-04-10 11:44:19', '2023-04-15 05:34:44'),
(26, 'twitter_link', 'http:://twitter.com', '2023-04-10 11:44:19', '2023-04-15 05:34:44');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `stock_quantity` bigint(20) NOT NULL,
  `sold_quantity` bigint(20) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active' COMMENT 'active,inactive',
  `published_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `stock_name`, `description`, `stock_quantity`, `sold_quantity`, `status`, `published_date`, `created_at`, `updated_at`) VALUES
(1, 'Whole Wheat Atta', 'Whole Wheat Atta 1Kg pack', 100, NULL, 'active', '2023-04-14 19:00:59', '2023-04-14 16:21:44', '2023-04-14 19:00:59'),
(2, 'Whole Wheat Atta', '1 kg', 100, NULL, 'active', '2023-04-14 16:22:37', '2023-04-14 16:22:37', '2023-04-14 16:22:37'),
(3, 'FLOUR', NULL, 100, NULL, 'active', '2023-04-14 16:31:36', '2023-04-14 16:31:36', '2023-04-14 16:31:36'),
(4, 'Whole Wheat Atta', '1 KG', 100, NULL, 'active', '2023-04-14 16:35:38', '2023-04-14 16:35:38', '2023-04-14 16:35:38'),
(5, 'কাঠবাদাম', 'কাঠবাদাম উপকরি হল একটি উদ্যোগ প্রকল্প যা মুক্ত সম্পদ ও বন্য বাস্তবতার সংরক্ষণ এবং স্থায়ীভাবে সংরক্ষণ এর মাধ্যমে প্রাকৃতিক সম্পদ ও প্রাণীজাতির উন্নয়নের লক্ষ্য রাখে। এই উপকরি প্রকল্পের উদ্দেশ্য হল কাঠবাদাম গাছগুলির বৃদ্ধি এবং তাদের উৎপাদনশীলতা বৃদ্ধি করে বন্য জীবনকে সংরক্ষণ করা। এছাড়াও এই প্রকল্পের মাধ্যমে কাঠবাদাম উৎপাদন বৃদ্ধি হবে যা একটি অর্থনৈতিক সম্পদ হিসাবে প্রকাশ করা যাবে।', 10, NULL, 'active', '2023-04-14 18:32:12', '2023-04-14 18:32:12', '2023-04-14 18:32:12'),
(6, 'আটা', 'আটার উপকরি হল একটি প্রকল্প যা মুক্ত সম্পদ ও বন্য জীবনকে সংরক্ষণ এবং স্থায়ীভাবে সংরক্ষণ এর মাধ্যমে প্রাকৃতিক সম্পদ ও প্রাণীজাতির উন্নয়নের লক্ষ্য রাখে। এই উপকরি প্রকল্পের উদ্দেশ্য হল আটা গাছগুলির বৃদ্ধি এবং তাদের উৎপাদনশীলতা বৃদ্ধি করে বন্য জীবনকে সংরক্ষণ করা। এছাড়াও এই প্রকল্পের মাধ্যমে আটা উৎপাদন বৃদ্ধি হবে যা একটি অর্থনৈতিক সম্পদ হিসাবে প্রকাশ করা যাবে।', 10, NULL, 'active', '2023-04-14 18:40:18', '2023-04-14 18:40:18', '2023-04-14 18:40:18'),
(7, 'আটা', 'আটা হল একটি প্রধান খাদ্য উপকরণ, যা গমের চুলা গুড়ো হয়ে তৈরি করা হয়। এটি খাদ্য বিপণিতে একটি বিশাল গুরুত্ব রাখে এবং বিভিন্ন খাবার তৈরির জন্য ব্যবহৃত হয়, যেমন রুটি, পাউরুটি, কেক, বিস্কুট, পিঠা ইত্যাদি। আটা ব্যবহার করে একটি মজাদার পাউরুটি বা রুটি বানানো যায় এবং এর উপর বিভিন্ন প্রকারের সবজি এবং মাংস স্লাইস করে বিছিয়ে খেতে পারেন।', 10, NULL, 'active', '2023-04-14 18:49:08', '2023-04-14 18:49:08', '2023-04-14 18:49:08'),
(8, 'আটা', 'আটা হল একটি প্রধান খাদ্য উপকরণ, যা গমের চুলা গুড়ো হয়ে তৈরি করা হয়। এটি খাদ্য বিপণিতে একটি বিশাল গুরুত্ব রাখে এবং বিভিন্ন খাবার তৈরির জন্য ব্যবহৃত হয়, যেমন রুটি, পাউরুটি, কেক, বিস্কুট, পিঠা ইত্যাদি। আটা ব্যবহার করে একটি মজাদার পাউরুটি বা রুটি বানানো যায় এবং এর উপর বিভিন্ন প্রকারের সবজি এবং মাংস স্লাইস করে বিছিয়ে খেতে পারেন।', 10, NULL, 'active', '2023-04-14 19:16:09', '2023-04-14 18:49:30', '2023-04-14 19:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `stock_galleries`
--

CREATE TABLE `stock_galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) NOT NULL,
  `image` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_prices`
--

CREATE TABLE `stock_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) NOT NULL,
  `buying_price` double(8,2) NOT NULL,
  `selling_price` double(8,2) NOT NULL,
  `pricing_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_prices`
--

INSERT INTO `stock_prices` (`id`, `stock_id`, `buying_price`, `selling_price`, `pricing_date`, `created_at`, `updated_at`) VALUES
(1, 1, 120.00, 100.00, '2023-04-14 16:21:44', '2023-04-14 16:21:44', '2023-04-14 16:21:44'),
(2, 2, 120.00, 100.00, '2023-04-14 16:22:37', '2023-04-14 16:22:37', '2023-04-14 16:22:37'),
(3, 3, 120.00, 100.00, '2023-04-14 16:31:36', '2023-04-14 16:31:36', '2023-04-14 16:31:36'),
(4, 4, 120.00, 100.00, '2023-04-14 16:35:38', '2023-04-14 16:35:38', '2023-04-14 16:35:38'),
(5, 5, 1250.00, 1200.00, '2023-04-14 18:32:12', '2023-04-14 18:32:12', '2023-04-14 18:32:12'),
(6, 6, 1250.00, 1200.00, '2023-04-14 18:40:18', '2023-04-14 18:40:18', '2023-04-14 18:40:18'),
(7, 7, 1250.00, 1200.00, '2023-04-14 18:49:08', '2023-04-14 18:49:08', '2023-04-14 18:49:08'),
(8, 8, 1250.00, 1200.00, '2023-04-14 18:49:30', '2023-04-14 18:49:30', '2023-04-14 18:49:30'),
(9, 6, 100.00, 120.00, '2023-04-14 19:19:40', NULL, NULL),
(10, 7, 100.00, 120.00, '2023-04-14 19:20:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `balance` double(8,2) NOT NULL DEFAULT 0.00,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user' COMMENT 'Admin,User,Employee',
  `referral_id` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default-user.png',
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `balance`, `name`, `role`, `referral_id`, `email`, `avatar`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 0.00, 'Telly Lesch Sr.', 'admin', '0', 'admin@gmail.com', 'default-user.png', '01976131504', '2023-04-10 11:44:19', '$2y$10$BiQCPTgbDfiTqqB0JKOW1OrjJp8DWnxjicvwvz1ztTH2m3HFmrn9O', 'MvnEYkQ9yFXaRHk5dnYer1ffaHhiIPn8QZh8mrZCihBbTb7ssezeAZs2qr2t', '2023-04-10 11:44:19', '2023-04-14 19:17:58'),
(2, 'nazmu64', 0.00, 'Nazmul Hossain', 'user', '1', 'nazmulhossain3691215@gmail.com', 'default-user.png', '12345678', '2023-04-14 18:41:23', '$2y$10$/oITf13UjnVyQsHwufOhmOWQUNePO1vrByAf3vYjSvI5JhStsXNAK', NULL, '2023-04-14 18:41:23', NULL),
(3, 'Nazmul65', 0.00, 'Nazmul Hossain', 'user', NULL, 'nazmulhossain3691215@gmail.com', 'default-user.png', '01706640864', '2023-04-14 18:43:21', '$2y$10$YO29kz.6fpj7ndO4jXaxRuM1ubZmpE46d//WHrlu/xgqgaMvIseo6', NULL, '2023-04-14 18:43:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_carts`
--

CREATE TABLE `user_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `stock_id` bigint(20) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quantity` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_systems`
--

CREATE TABLE `user_payment_systems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `pay_s_name` varchar(255) NOT NULL,
  `pay_s_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profites`
--

CREATE TABLE `user_profites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `from_stock_id` bigint(20) DEFAULT NULL,
  `profit` double(8,2) NOT NULL,
  `entry_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `method_id` bigint(20) NOT NULL COMMENT 'User Method ID',
  `status` varchar(255) NOT NULL DEFAULT 'pending' COMMENT 'aproved,pending,rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buy_stocks`
--
ALTER TABLE `buy_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contuct_messages`
--
ALTER TABLE `contuct_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_infos`
--
ALTER TABLE `employee_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sell_stocks`
--
ALTER TABLE `sell_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_payment_systems`
--
ALTER TABLE `site_payment_systems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_galleries`
--
ALTER TABLE `stock_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_prices`
--
ALTER TABLE `stock_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `user_carts`
--
ALTER TABLE `user_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_payment_systems`
--
ALTER TABLE `user_payment_systems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profites`
--
ALTER TABLE `user_profites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buy_stocks`
--
ALTER TABLE `buy_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contuct_messages`
--
ALTER TABLE `contuct_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_infos`
--
ALTER TABLE `employee_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_stocks`
--
ALTER TABLE `sell_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_payment_systems`
--
ALTER TABLE `site_payment_systems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock_galleries`
--
ALTER TABLE `stock_galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_prices`
--
ALTER TABLE `stock_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_carts`
--
ALTER TABLE `user_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_payment_systems`
--
ALTER TABLE `user_payment_systems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profites`
--
ALTER TABLE `user_profites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
