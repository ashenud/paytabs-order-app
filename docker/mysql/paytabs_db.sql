-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: paytabs_mysql:3306
-- Generation Time: May 30, 2025 at 05:31 PM
-- Server version: 5.7.44
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paytabs`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `shipping_method` enum('shipping','pickup') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `status`, `shipping_method`, `created_at`) VALUES
(1, 'pending', 'shipping', '2025-05-30 15:14:44'),
(2, 'pending', 'shipping', '2025-05-30 15:31:37'),
(3, 'pending', 'shipping', '2025-05-30 15:51:49'),
(4, 'pending', 'shipping', '2025-05-30 16:27:09'),
(5, 'pending', 'shipping', '2025-05-30 16:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 2, 1, 1),
(5, 2, 2, 1),
(6, 3, 1, 1),
(7, 3, 2, 1),
(8, 3, 3, 1),
(9, 3, 4, 1),
(10, 4, 1, 1),
(11, 4, 2, 1),
(12, 4, 3, 1),
(13, 5, 1, 1),
(14, 5, 2, 1),
(15, 5, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('initiated','success','failed','refunded') DEFAULT 'initiated',
  `payment_request` text,
  `payment_response` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `status`, `payment_request`, `payment_response`, `created_at`) VALUES
(1, 1, 'initiated', '{\"order_id\":1,\"items\":[{\"product_id\":1,\"name\":\"USB Keyboard\",\"quantity\":1,\"price\":\"19.99\",\"subtotal\":19.99},{\"product_id\":2,\"name\":\"Wireless Mouse\",\"quantity\":1,\"price\":\"24.50\",\"subtotal\":24.5},{\"product_id\":3,\"name\":\"HDMI Cable\",\"quantity\":1,\"price\":\"8.99\",\"subtotal\":8.99}],\"total\":53.48}', NULL, '2025-05-30 15:18:54'),
(2, 2, 'initiated', '{\"order_id\":2,\"items\":[{\"product_id\":1,\"name\":\"USB Keyboard\",\"quantity\":1,\"price\":\"19.99\",\"subtotal\":19.99},{\"product_id\":2,\"name\":\"Wireless Mouse\",\"quantity\":1,\"price\":\"24.50\",\"subtotal\":24.5}],\"total\":44.489999999999995}', NULL, '2025-05-30 15:31:49'),
(3, 4, 'initiated', '{\"order_id\":\"4\",\"items\":[{\"product_id\":1,\"name\":\"USB Keyboard\",\"quantity\":1,\"price\":\"19.99\",\"subtotal\":19.99},{\"product_id\":2,\"name\":\"Wireless Mouse\",\"quantity\":1,\"price\":\"24.50\",\"subtotal\":24.5},{\"product_id\":3,\"name\":\"HDMI Cable\",\"quantity\":1,\"price\":\"8.99\",\"subtotal\":8.99}],\"total\":53.48,\"customerDetails\":{\"name\":\"Ashen Udithamal\",\"email\":\"udithamal.lk@gmail.com\",\"phone\":\"0712782201\",\"shipping_method\":\"pickup\",\"street1\":\"\",\"city\":\"\",\"state\":\"N\\/A\",\"country\":\"EG\",\"zip\":\"\"}}', NULL, '2025-05-30 16:28:27'),
(6, 5, 'initiated', '{\"order_id\":\"5\",\"items\":[{\"product_id\":1,\"name\":\"USB Keyboard\",\"quantity\":1,\"price\":\"19.99\",\"subtotal\":19.99},{\"product_id\":2,\"name\":\"Wireless Mouse\",\"quantity\":1,\"price\":\"24.50\",\"subtotal\":24.5},{\"product_id\":3,\"name\":\"HDMI Cable\",\"quantity\":1,\"price\":\"8.99\",\"subtotal\":8.99}],\"total\":53.48,\"customerDetails\":{\"name\":\"Ashen Udithamal\",\"email\":\"udithamal.lk@gmail.com\",\"phone\":\"0712782201\",\"shipping_method\":\"pickup\",\"street1\":\"\",\"city\":\"\",\"state\":\"N\\/A\",\"country\":\"EG\",\"zip\":\"\"}}', NULL, '2025-05-30 16:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`) VALUES
(1, 'USB Keyboard', 19.99),
(2, 'Wireless Mouse', 24.50),
(3, 'HDMI Cable', 8.99),
(4, 'Portable SSD 1TB', 109.99),
(5, 'Laptop Stand', 34.00);

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `refund_request` text,
  `refund_response` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
