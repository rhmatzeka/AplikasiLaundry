-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2025 at 05:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundrygo`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `driver_id` int DEFAULT NULL,
  `berat_kg` decimal(5,2) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `alamat_jemput` text NOT NULL,
  `alamat_antar` text,
  `lat_jemput` double DEFAULT NULL,
  `lng_jemput` double DEFAULT NULL,
  `status` enum('menunggu','dijemput','proses','diantar','selesai','dibatalkan') DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `driver_id`, `berat_kg`, `total_harga`, `alamat_jemput`, `alamat_antar`, `lat_jemput`, `lng_jemput`, `status`, `created_at`) VALUES
(1, 6, 7, '33.00', '231000.00', 'asfdafsdf', NULL, -6.2088, 106.8456, 'selesai', '2025-12-10 04:39:26'),
(2, 3, 7, '4.00', '28000.00', 'jalan angkasa nomo5', NULL, -6.272801836459965, 106.83838963505879, 'selesai', '2025-12-10 04:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text,
  `role` enum('customer','driver','admin') DEFAULT 'customer',
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `no_hp`, `alamat`, `role`, `lat`, `lng`, `created_at`) VALUES
(1, 'Admin Utama', 'admin@laundrygo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456789', NULL, 'admin', NULL, NULL, '2025-12-10 04:13:13'),
(2, 'Driver Joni', 'driver@laundrygo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '085712345678', NULL, 'driver', NULL, NULL, '2025-12-10 04:13:13'),
(3, 'rahmat', 'matsganz@gmail.com', '$2y$10$qF9pF7mre5aXIaZ4Y44QcOtuKeBB/xE5f7hETf7lnT6VJ8b7ek/xe', '098765', NULL, 'customer', NULL, NULL, '2025-12-10 04:19:56'),
(5, 'Rahmat Eka', 'rahmat@gmail.com', '$2y$10$aN.e.71onskiK1cSQvFptOEd692B9htWWXYus8dhJJvBom7SSqbv.', '098765', NULL, 'customer', NULL, NULL, '2025-12-10 04:30:45'),
(6, 'admin', 'admin@admin', '$2y$10$hfL3Z2HR7SD116Li.plPp.hRuU65HybHC6rHVaswUikJQC6tOIoTy', '098765', NULL, 'admin', NULL, NULL, '2025-12-10 04:33:07'),
(7, 'driver', 'driver@driver', '$2y$10$cvrLDpe4vyz4Mf1VJMCG.e0rgp.45bk251wutwJrTGP109IFLO4Hq', '098765', NULL, 'driver', NULL, NULL, '2025-12-10 04:56:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
