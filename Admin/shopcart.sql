-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 09:59 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopcart`
--

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(12) NOT NULL,
  `address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyers`
--

INSERT INTO `buyers` (`id`, `fname`, `lname`, `email`, `password`, `contact_number`, `address`) VALUES
(71, 'Mathew', 'Laresma', 'matl@gmail.com', '$2y$10$qvyWz2RSVzjrcyrrIB4AbuQM6L3X07hLh0BMbjUrVnl4z4D2pe/jm', '9100005148', 'Tampaan, Aloguinsan, Cebu City'),
(72, 'Mathew', 'Laresma', 'mat@gmail.com', '$2y$10$xTt.nVcGmTFfPNh7nNMwjOowaFv3v55iuUmUDmVaCpKa/3Yc5vwM2', '9100005146', 'Aloguinsan'),
(73, 'Uzumaki', 'Naruto', 'naruto@gmail.com', '$2y$10$hLcHZQML8VbimjOylKPoE.QDXtZxv4urrZx6Us5NUEKnSgtJN3Wea', '9100005146', 'Hidden Leaf'),
(74, 'Uchiha', 'Sasuke', 'sas@gmail.com', '$2y$10$nDQlglAeBjc7PiuS.QYxqeaj4ZBSHT.H8mqK6U3R.ZWwoxn/VvR1W', '', ''),
(75, 'Uchiha', 'Itachi', 'tac@gmail.com', '$2y$10$uG3Sdjndow7cn32VWV9JVuMEfpzxRWi7bRMVMC8BP2tjfkMOCpiy2', '9100005146', 'Aloguinsan'),
(76, 'Edogawa', 'Conan', 'conan@gmail.com', '$2y$10$/AQMCr5ym7wz3V9C8OFu7.TJ.m52TnFU8YOuUGzWm4xusYROAbHM6', '', ''),
(77, 'Steve', 'Wozniak', 'steve@gmail.com', '$2y$10$ZHJ.UgtPHW76U0s6p2g2hO.dVHxg6a6QEGLHtNVVKyvxlzERvYIda', '', ''),
(78, 'James', 'Gosling', 'java@gmail.com', '$2y$10$1SqBX7g4Hgxac0YMYl1Uvet8f2/F0sFiEgFzuzuIJycIeEHrZVpCy', '23063597', 'Cogon,Pardo');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `product_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_image` longblob NOT NULL,
  `item_price` int(11) NOT NULL,
  `item_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`product_id`, `item_name`, `item_image`, `item_price`, `item_quantity`) VALUES
(333, 'MSI Laptop', 0x3030773675784546476658564f6873417059326b3032652d35342e77656270, 50000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `total_products` varchar(255) NOT NULL,
  `total_price` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `name`, `contact_number`, `email`, `payment_method`, `address`, `total_products`, `total_price`, `order_date`) VALUES
(215, 'Uchiha Itachi', '9100005146', 'tac@gmail.com', 'cash on delivery', 'Aloguinsan', 'MSI Laptop (1)', '50000', '2024-12-16 18:27:13'),
(216, 'Uchiha Itachi', '9100005146', 'tac@gmail.com', 'cash on delivery', 'Aloguinsan', 'Herschel Backpack (1), MSI Laptop (1)', '51299', '2024-12-16 18:27:13'),
(217, 'Mathew Laresma', '9100005148', 'matl@gmail.com', 'cash on delivery', 'Tampaan, Aloguinsan, Cebu City', 'MSI Laptop (1)', '50000', '2024-12-16 18:27:13'),
(218, 'Mathew Laresma', '9100005148', 'matl@gmail.com', 'cash on delivery', 'Tampaan, Aloguinsan, Cebu City', 'MSI Laptop (1)', '50000', '2024-12-16 18:27:13'),
(219, 'Uchiha Itachi', '9100005146', 'tac@gmail.com', 'cash on delivery', 'Aloguinsan', 'Wilson Basketball (2)', '1998', '2024-12-16 18:27:13'),
(220, 'Uchiha Itachi', '9100005146', 'tac@gmail.com', 'cash on delivery', 'Aloguinsan', 'MSI Laptop (1)', '50000', '2024-12-16 18:27:13'),
(221, 'Mathew Laresma', '9100005148', 'matl@gmail.com', 'gcash', 'Tampaan, Aloguinsan, Cebu City', 'MSI Laptop (3)', '150000', '2024-12-16 18:27:13'),
(222, 'Mathew Laresma', '9100005148', 'matl@gmail.com', 'cash on delivery', 'Tampaan, Aloguinsan, Cebu City', 'Herschel Backpack (2)', '2598', '2024-12-16 18:27:13'),
(223, 'Mathew Laresma', '9100005148', 'matl@gmail.com', 'cash on delivery', 'Tampaan, Aloguinsan, Cebu City', 'MSI Laptop (1)', '50000', '2024-12-16 18:27:13'),
(224, 'Mathew Laresma', '9100005148', 'matl@gmail.com', 'cash on delivery', 'Tampaan, Aloguinsan, Cebu City', 'MSI Laptop (1)', '50000', '2024-12-16 18:27:13'),
(225, 'Mathew Laresma', '9100005148', 'matl@gmail.com', 'cash on delivery', 'Tampaan, Aloguinsan, Cebu City', 'Herschel Backpack (1)', '1299', '2024-12-16 18:27:13'),
(226, 'Uchiha Itachi', '9100005146', 'tac@gmail.com', 'cash on delivery', 'Aloguinsan', 'MSI Laptop (1), Herschel Backpack (1), Steph Curry x Bruce Lee Basketball shoes (1)', '52598', '2024-12-16 18:39:42'),
(230, 'Uzumaki Naruto', '9100005146', 'naruto@gmail.com', 'cash on delivery', 'Hidden Leaf', 'Herschel Backpack (1), MSI Laptop (1), Steph Curry x Bruce Lee Basketball shoes (1), Fountain Pen (1)', '52613', '2024-12-17 08:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `item_image` longblob NOT NULL,
  `item_desc` varchar(255) NOT NULL,
  `item_price` int(11) NOT NULL,
  `stocks` int(11) NOT NULL,
  `product_category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `item_name`, `item_image`, `item_desc`, `item_price`, `stocks`, `product_category`) VALUES
(82, 'Wilson Basketball', 0x696d6167657320283132292e6a7067, 'durable and has original weight', 999, 489, 'Sports and Hobbies'),
(83, 'Herschel Backpack', 0x696d61676573202834292e6a7067, 'durable bag and fits with any occasion', 1299, 182, 'Bags'),
(85, 'MSI Laptop', 0x3030773675784546476658564f6873417059326b3032652d35342e77656270, 'high end laptop for programming', 50000, 772, 'Gadgets and Appliances'),
(88, 'Steph Curry x Bruce Lee Basketball shoes', 0x6375727279206272756365206c65652073686f652e6a7067, 'durable and high traction', 1299, 494, 'Sports and Hobbies'),
(89, 'Fountain Pen', 0x696d61676573202831292e706e67, 'best for speed writing', 15, 99, 'School and Office Supplies');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `seller_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(12) NOT NULL,
  `address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`seller_id`, `first_name`, `last_name`, `email`, `password`, `contact_number`, `address`) VALUES
(28, 'Mathew', 'Laresma', 'tac@gmail.com', '$2y$10$uA1csP878WkqUhNhnsyKjuYMdk9ibALxHActyW6B8/EieYBU2yCJi', '9100005146', 'Tampaan'),
(29, 'Naruto', 'Uzumaki', 'naruto@gmail.com', '$2y$10$7a4vrYyH0bYu7p9ptWmFreSKDr5f5fzcfAgYEPIMaUwRcPklbczKy', '9100005146', 'Konohagakure'),
(30, 'Mathew', 'Laresma', 'mat@gmail.com', '$2y$10$GiwOS5C6ZJ4JcQ5MkP9W0uUponNI3GwBq.g9K3qeMacu/4Wa8v8eu', '', ''),
(31, 'Rasmus', 'Lerdorf', 'rasmus@gmail.com', '$2y$10$FCK2eBN8x2N3gqGiqIDQMORrKz42vvQINNJsvKzW64rcOi2sAFWJ6', '9332874674', 'Bulacao, Riverside Cebu'),
(32, 'Lebrown', 'James', 'leb@gmail.com', '$2y$10$vY5Te.7YtRRhDQF9.fcCuOlN0Qc2QTq42XAY4rmFAWgX23XqPuoFK', '', ''),
(33, 'Lebrown', 'Jones', 'lebrown@gmail.com', '$2y$10$WNadrkDBCm9Sts2C8c15TOXFcy4Uv122DKQHp2afWXomacDkYERxy', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`seller_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
