-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2023 at 05:27 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gymproducts`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_cart`
--

CREATE TABLE `add_cart` (
  `id` int(11) NOT NULL,
  `product_id` int(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `quantity` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(30) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `user_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `total_price`, `user_id`) VALUES
(1, '935.97', 1),
(2, '1200.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `product_id`, `quantity`, `price`, `order_id`) VALUES
(1, 1, 3, '311.99', 1),
(2, 3, 3, '400.00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `products_items`
--

CREATE TABLE `gym_equipment` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `thumbnail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products_items`
--

INSERT INTO `gym_equipment` (`id`, `title`, `description`, `price`, `thumbnail`) VALUES
(1, 'Treadmill', 'A treadmill is a device generally used for walking running or climbing while staying in the same place. Treadmills were introduced before the development of powered machines to harness the power of animals or humans to do work often a type of mill operate', '150.00', 'https://images.pexels.com/photos/1954524/pexels-photo-1954524.jpeg'),
(3, 'Exercise Bike', 'A stationary bicycle (also known as exercise bicycle exercise bike spinning bike spin bike or exercycle) is a device used as exercise equipment for indoor cycling.', '400.00', 'https://images.pexels.com/photos/4162578/pexels-photo-4162578.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
(4, 'Weight Bench', 'A weight training bench is a piece of exercise equipment used for weight training. Weight training benches may be of various designs: fixed horizontal fixed inclined fixed in a folded position with one adjustable portion with two or more adjustable portio', '950.00', 'https://media.istockphoto.com/id/640079198/photo/adjustable-weight-bench-isolated-on-white-background.jpg?s=1024x1024&w=is&k=20&c=105COKT69wTaVUS5OA-ji8S2Ojp-mIaP81QlySsu2Zk='),
(5, 'Resistance Bands', 'A resistance band is an elastic band used for strength training. They are also commonly used in physical therapy specifically by convalescents of muscular injuries including cardiac rehab patients to allow slow rebuilding of strength.', '350.00', 'https://images.pexels.com/photos/4397831/pexels-photo-4397831.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
(6, 'Protein Shake', 'Guilt-free milkshakes are now a thing! Don’t believe us? Grab a Phab & shake off unhealthy drinks because this sugar-free protein-rich shake will impress your taste buds like no other without loading you with calories. Thick rich & refreshingly creamy the', '120.00', 'https://images.pexels.com/photos/6174870/pexels-photo-6174870.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
(7, 'Gym Bag', 'This stylish bag features innovative storage solutions including a two-way zip opening into a spacious main compartment a side pocket for extra storage options lining pockets side mesh compartments and more to keep even the most active player\'s organisati', '350.00', 'https://images.pexels.com/photos/9391902/pexels-photo-9391902.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
(8, 'Running Shoes', 'A pair of teal running shoes has regular Styling lace-ups detail Mesh upper Cushioned footbed Textured and patterned outsole', '650.00', 'https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
(9, 'Yoga Mat', 'A Yoga Mat bag is included with your Boldfit EVA all-purpose premium exercise yoga mat Easy strapping and light weight feature are added to this mat for easy Transport and storage.', '450.00', 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?q=80&w=1780&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(10, 'Jump Rope', 'Skipping Rope is made from durable PVC material that won\'t twist or break during your workout. The comfortable grips are designed so that the product has a long-lasting life.', '280.00', 'https://images.unsplash.com/photo-1516876345887-6dd74f80787a?q=80&w=1780&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(18, 'Dumbbells', 'The dumbbell a type of free weight is a piece of equipment used in weight training. It is usually used individually or in pairs with one in each hand.', '100.00', 'https://images.pexels.com/photos/39671/physiotherapy-weight-training-dumbbell-exercise-balls-39671.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `usertype` varchar(50) DEFAULT 'user',
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `usertype`, `username`, `email`, `password`) VALUES
(1, 'user', 'user12', 'aj.empiric@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'admin', 'admin12', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(3, 'user', 'newuser123', 'aj.empiric12@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_cart`
--
ALTER TABLE `add_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products_items`
--
ALTER TABLE `gym_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_cart`
--
ALTER TABLE `add_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products_items`
--
ALTER TABLE `gym_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_cart`
--
ALTER TABLE `add_cart`
  ADD CONSTRAINT `add_cart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
