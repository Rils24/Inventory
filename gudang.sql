-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2024 at 08:49 AM
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
-- Database: `gudang`
--

-- --------------------------------------------------------

--
-- Table structure for table `keluar`
--

CREATE TABLE `keluar` (
  `id` int NOT NULL,
  `Nama` varchar(100) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `Tanggal_keluar` date DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `keluar`
--

INSERT INTO `keluar` (`id`, `Nama`, `jenis`, `Tanggal_keluar`, `jumlah`, `harga`) VALUES
(26, 'Ball JKLO', 'sweter', '2024-11-30', 30, 80000),
(27, 'Ball KGH', 'rok', '2024-12-05', 10, 60000),
(28, 'Ball KGH', 'rok', '2024-12-05', 10, 50000);

-- --------------------------------------------------------

--
-- Table structure for table `masuk`
--

CREATE TABLE `masuk` (
  `id` int NOT NULL,
  `Nama` varchar(100) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `Tanggal_masuk` date DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `masuk`
--

INSERT INTO `masuk` (`id`, `Nama`, `jenis`, `Tanggal_masuk`, `jumlah`, `harga`) VALUES
(66, 'Ball LCBL', 'rok', '2024-11-20', 10, 2000000),
(67, 'Ball JKLO', 'Sweter', '2024-11-28', 100, 100000),
(68, 'Ball KGH', 'rok', '2024-11-30', 20, 50000),
(69, 'Ball LCBL', 'celana', '2024-12-01', 5, 70000),
(70, 'Ball KGH', 'rok', '2024-12-04', 20, 400000),
(71, 'Ball JKLO', 'sweter', '2024-12-04', 56, 80000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(136, 'admin', '$2y$10$EN3DCyI0m5oJZccBFrEb0uX7q0ZgSRKvM6mYhQvM1bwu6HrC9hqVy'),
(137, 'suci ', '$2y$10$bwZ/iyGRlB0DVmDkC5U5NuYmYVIqHDg9aeXB3dWsc3qvZLSStaIuK'),
(138, 'hasna', '$2y$10$YcQn4PJdfT6TPgfOxYP9JOYq7TpXqk72LBHwRaUSsM1reGp4Uhwqi'),
(139, 'admin', '$2y$10$BGpci68fwYkSawgiJ51mEO5Axql/XlokHyz8YzgRdMpRk6dIWqts.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keluar`
--
ALTER TABLE `keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `masuk`
--
ALTER TABLE `masuk`
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
-- AUTO_INCREMENT for table `keluar`
--
ALTER TABLE `keluar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `masuk`
--
ALTER TABLE `masuk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
