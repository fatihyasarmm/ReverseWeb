-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: db:3306
-- Üretim Zamanı: 28 Haz 2025, 13:15:46
-- Sunucu sürümü: 8.0.42
-- PHP Sürümü: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `reverseweb_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Electronics'),
(2, 'Furniture'),
(3, 'Accessories');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `coupon_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `uses_left` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_code`, `discount_amount`, `uses_left`) VALUES
(1, 'SPRING50', 50.00, 1),
(2, 'DISCOUNT2025', 50.00, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `flags`
--

CREATE TABLE `flags` (
  `id` int NOT NULL,
  `vuln_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vuln_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `flag_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `flags`
--

INSERT INTO `flags` (`id`, `vuln_key`, `vuln_name`, `flag_value`) VALUES
(2, 'sqli_union', 'SQLi - UNION Attack', 'FLAG{UNION_ATTACK_DATA_LEAKED}'),
(3, 'xss_reflected', 'XSS - Reflected', 'FLAG{XSS_FLAG_WAS_HERE}'),
(4, 'xss_stored', 'Stored XSS (no flag is needed)', 'FLAG{STORED_XSS_IN_PROFILE_NAME}'),
(5, 'idor_invoice', 'IDOR - Invoice Leak', 'IDOR_WAS_DETECTED'),
(6, 'jwt_privesc', 'JWT - Privilege Escalation', 'FLAG{JWT_PRIVILEGE_ESCALATION_COMPLETE}'),
(7, 'file_upload', 'File Upload - RCE', 'FLAG_RCE_VIA_FILE_UPLOAD'),
(8, 'race_condition', 'Race Condition (no flag is needed)', 'FLAG{RACE_CONDITION_EXPLOITED}');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `invoice_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `billing_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `invoices`
--

INSERT INTO `invoices` (`id`, `user_id`, `invoice_number`, `invoice_date`, `total_amount`, `shipping_address`, `billing_email`) VALUES
(1, 1, 'inv_8e7f3b1a4c9d', '2025-06-15', 3400.00, 'Management Floor, CEO Office, Cyber-Security St. No:1, WebCity\n\nFLAG: IDOR_WAS_DETECTED', 'admin@reverseweb.com'),
(2, 2, 'inv_c3b2a1d9e8f7', '2025-06-18', 1250.00, '12 Vulnerability St, Apt 3, Exploit Building, WebCity', 'ali.veli@example.com'),
(3, 3, 'inv_a9f8e7d6c5b4', '2025-06-20', 2100.00, 'Secret Address, 404 Boulevard, AnotherCity', 'secret.user@email.com'),
(4, 1, 'inv_d4e5f6a7b8c9', '2025-06-21', 2100.00, 'Management Floor, CEO Office, Cyber-Security St. No:1, WebCity', 'admin@reverseweb.com');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_path`, `category_id`) VALUES
(1, 'Wireless Headphones', 'Bluetooth headphones offering high-quality sound.', 1250.00, 'images/products/kulaklik.jpg', 1),
(2, 'Modern Work Desk', 'A wide and useful work desk.', 3400.00, 'images/products/masa.jpg', 2),
(3, 'Smart Watch', 'Stylish design, compatible with all smartphones.', 2100.00, 'images/products/saat.jpg', 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `avatar_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'images/avatars/default.png',
  `progress` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `username`, `password`, `role`, `two_factor_enabled`, `balance`, `avatar_path`, `progress`) VALUES
(1, 'Admin Manager', 'admin@reverseweb.com', 'admin', 'FLAG{UNION_ATTACK_DATA_LEAKED}', 'admin', 0, 0.00, 'images/avatars/default.png', NULL),
(7, 'fatih', 'fatih@gmail.com', 'fatih', 'fatih123', 'user', 0, 0.00, 'images/avatars/default.png', NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`);

--
-- Tablo için indeksler `flags`
--
ALTER TABLE `flags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vuln_key` (`vuln_key`);

--
-- Tablo için indeksler `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `flags`
--
ALTER TABLE `flags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
