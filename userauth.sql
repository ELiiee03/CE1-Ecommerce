-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 09:09 AM
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
-- Database: `userauth`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `unit_number` varchar(50) DEFAULT NULL,
  `street_address` varchar(50) NOT NULL,
  `city` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jw_token`
--

CREATE TABLE `jw_token` (
  `id` int(11) NOT NULL,
  `token` varchar(500) NOT NULL,
  `invalidated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jw_token`
--

INSERT INTO `jw_token` (`id`, `token`, `invalidated_at`) VALUES
(6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xIiwiaWF0IjoxNzI5OTM3NjUxLCJleHAiOjE3Mjk5NDEyNTEsImRhdGEiOnsiaWQiOiJkZDNiMzNiOTgwZjQ2YmY3ZmRhNTFjMzQ2MTZjYTQ3MyIsImZpcnN0X25hbWUiOiJKYW1lcyIsImxhc3RfbmFtZSI6IlRhbWJvbmciLCJyb2xlIjoiY3VzdG9tZXIifX0.l3iplc9-f-ijz9teZG_Scu3_Rh3ZL-aaWgcFFaem9L8', '2024-10-26 10:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 1 hour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `size` varchar(20) NOT NULL,
  `color` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `user_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `size`, `color`, `price`, `category_id`, `image_url`, `user_id`) VALUES
(4, 'Voltes 9', 'Lightweight racket for beginners.', '27 inches', 'Black and White', 599.99, 1, 'https://example.com/racket.jpg', '79a3cc537162446877d4d9b9cf9cdb61'),
(5, 'sample1', 'Lightweight racket for beginners.', '27 inches', 'Black and White', 100.99, 1, 'https://example.com/sample.jpg', '79a3cc537162446877d4d9b9cf9cdb61'),
(6, 'sample4', 'Lightweight racket for beginners.', '27 inches', 'Black and White', 110.99, 1, 'https://example.com/sample.jpg', '79a3cc537162446877d4d9b9cf9cdb61'),
(7, 'aample4', 'Lightweight racket for beginners.', '27 inches', 'Black and White', 111.99, 1, 'https://example.com/sample.jpg', '79a3cc537162446877d4d9b9cf9cdb61'),
(8, 'another sample', 'Lightweight racket for beginners.', '27 inches', 'Black and White', 111.99, 1, 'https://example.com/sample.jpg', '99dae12b9a063a3ee4a921c58acd5b6f');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`category_id`, `category_name`) VALUES
(1, 'Clothing'),
(2, 'Electronics'),
(3, 'Books'),
(4, 'Home Stuff');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventory`
--

CREATE TABLE `product_inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `cart_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `user_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_item`
--

CREATE TABLE `shopping_cart_item` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_order`
--

CREATE TABLE `shop_order` (
  `order_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `user_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `order_date` date NOT NULL,
  `payment_method_id` varchar(36) NOT NULL,
  `address_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','processing','shipped','completed','canceled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `role` enum('customer','admin','vendor') NOT NULL DEFAULT 'customer',
  `is_verified` tinyint(1) DEFAULT 0,
  `profile_image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `reg_date`, `date_of_birth`, `role`, `is_verified`, `profile_image_url`) VALUES
('1e03f14be00f1214914f40c23375e151', 'Dimples', 'Tinto', 'dimples.tinto@gmail.com', '$2y$10$IduhUzCp7mYllkjysKxfxueoRtH0g40dKSQ.AbB19YyCvN.YbQxZa', '2024-10-26', '2002-04-24', 'customer', 1, NULL),
('21974d810200074d855b47fea0ce5bf4', 'April Joy', 'Tambong', 'dimtinto@gmail.com', '$2y$10$V9rLPkkqVC4lisV.qIGyouC4XYdom/kP5PY61NXsRL4hg.TwxM1s2', '2024-10-26', '2002-04-24', 'customer', 1, NULL),
('59ba24461bd09bb97398785a9ed90997', 'Alma', 'Tandoy', 'almatandoy23@gmail.com', '$2y$10$S0jkHelwJfAwKhZ46AWFOu3pO6p3GZaden1NBhdgObK6pMWknOBHa', '2024-10-26', '2002-04-24', 'admin', 1, NULL),
('79a3cc537162446877d4d9b9cf9cdb61', 'Dan', 'Atibagos', 'danatibagos@gmail.com', '$2y$10$bJURpyy.LqLCjRC7yic2IuEUXr.B8FbOrVFoHhKBxD9AXWkp5vIUu', '2024-11-20', '2002-04-24', 'vendor', 1, NULL),
('99dae12b9a063a3ee4a921c58acd5b6f', 'Imee', 'Atibagos', 'imeeatibagos@gmail.com', '$2y$10$XjLbFcT5a9MzaiOe9urL8OMNVXpYg9ZIeRJlz9MbjI576P91uWc.C', '2024-11-20', '2002-04-24', 'vendor', 1, NULL),
('dd3b33b980f46bf7fda51c34616ca473', 'James', 'Tambong', 'james.tambong55@gmail.com', '$2y$10$KJMcHkgaR7sX6yBuV8JuA.CU.yH4VXMgOYeBat.PyIGkLdWusQKs6', '2024-10-26', '2001-01-24', 'customer', 1, '/uploads/671cc0d819f80_meow.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `user_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `address_id` int(11) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_method`
--

CREATE TABLE `user_payment_method` (
  `payment_method_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `user_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `payment_type` enum('credit_card','gcash','cash_on_delivery') DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_review`
--

CREATE TABLE `user_review` (
  `user_review_id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL DEFAULT 'uuid()',
  `rating_value` decimal(5,2) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `user_token_id` int(11) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `token_id` varchar(36) DEFAULT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 1 hour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`user_token_id`, `user_id`, `token_id`, `issued_at`, `expired_at`) VALUES
(85, '59ba24461bd09bb97398785a9ed90997', '6aa5121793cb4bff4f0cb05c6bdda873', '2024-10-26 09:36:40', '2024-10-26 10:36:40'),
(86, '21974d810200074d855b47fea0ce5bf4', 'ed168c2541de439d8e4687286704802e', '2024-10-26 09:44:06', '2024-10-26 10:44:06'),
(87, 'dd3b33b980f46bf7fda51c34616ca473', 'fbdf1fe45ab57a90f556ef6a255ede24', '2024-10-26 09:49:48', '2024-10-26 10:49:48'),
(88, '1e03f14be00f1214914f40c23375e151', 'e3cac8d6c877d307b03603a19d04d9dd', '2024-10-26 12:25:37', '2024-10-26 13:25:37'),
(89, '79a3cc537162446877d4d9b9cf9cdb61', 'ba7874c604fd3dc681af9c53b5638d06', '2024-11-20 06:33:36', '2024-11-20 07:33:36'),
(90, '99dae12b9a063a3ee4a921c58acd5b6f', 'e30edc8814e8868a3bb41f2fde2595d1', '2024-11-20 06:52:27', '2024-11-20 07:52:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `jw_token`
--
ALTER TABLE `jw_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shopping_cart_item`
--
ALTER TABLE `shopping_cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shop_order`
--
ALTER TABLE `shop_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `user_payment_method`
--
ALTER TABLE `user_payment_method`
  ADD PRIMARY KEY (`payment_method_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_review`
--
ALTER TABLE `user_review`
  ADD PRIMARY KEY (`user_review_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`user_token_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jw_token`
--
ALTER TABLE `jw_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopping_cart_item`
--
ALTER TABLE `shopping_cart_item`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_review`
--
ALTER TABLE `user_review`
  MODIFY `user_review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `user_token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`category_id`);

--
-- Constraints for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `shopping_cart_item`
--
ALTER TABLE `shopping_cart_item`
  ADD CONSTRAINT `shopping_cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`cart_id`),
  ADD CONSTRAINT `shopping_cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `shop_order`
--
ALTER TABLE `shop_order`
  ADD CONSTRAINT `shop_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `shop_order_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `user_payment_method` (`payment_method_id`),
  ADD CONSTRAINT `shop_order_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `shop_order_ibfk_4` FOREIGN KEY (`payment_method_id`) REFERENCES `user_payment_method` (`payment_method_id`),
  ADD CONSTRAINT `shop_order_ibfk_5` FOREIGN KEY (`address_id`) REFERENCES `user_address` (`address_id`);

--
-- Constraints for table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `user_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_address_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`),
  ADD CONSTRAINT `user_address_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_address_ibfk_4` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`);

--
-- Constraints for table `user_payment_method`
--
ALTER TABLE `user_payment_method`
  ADD CONSTRAINT `user_payment_method_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_payment_method_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_review`
--
ALTER TABLE `user_review`
  ADD CONSTRAINT `user_review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_review_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_tokens_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
