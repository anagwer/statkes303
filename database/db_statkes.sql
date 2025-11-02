-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 04:36 PM
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
-- Database: `db_statkes`
--

-- --------------------------------------------------------

--
-- Table structure for table `ketersediaan`
--

CREATE TABLE `ketersediaan` (
  `id` int(11) NOT NULL,
  `jenis` enum('obat','alkes') NOT NULL,
  `nama` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(50) NOT NULL,
  `expired` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ketersediaan`
--

INSERT INTO `ketersediaan` (`id`, `jenis`, `nama`, `stok`, `satuan`, `expired`, `keterangan`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'obat', 'test', 4, 'tablet', '2025-12-06', 'mantap', 'obat_1762094904.png', '2025-11-02 21:47:20', '2025-11-02 21:48:24'),
(3, 'alkes', 'test', 2, 'botol', NULL, 'nop', 'alkes_1762095128.jpg', '2025-11-02 21:52:08', '2025-11-02 21:52:08');

-- --------------------------------------------------------

--
-- Table structure for table `pemeriksaan`
--

CREATE TABLE `pemeriksaan` (
  `id` int(11) NOT NULL,
  `anggota` int(11) NOT NULL,
  `gula` varchar(50) DEFAULT NULL,
  `kolestrol` varchar(50) DEFAULT NULL,
  `asam` varchar(50) DEFAULT NULL,
  `tekanan` varchar(50) DEFAULT NULL,
  `nadi` varchar(50) DEFAULT NULL,
  `saturasi` varchar(50) DEFAULT NULL,
  `rr` varchar(50) DEFAULT NULL,
  `suhu` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `inputed_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemeriksaan`
--

INSERT INTO `pemeriksaan` (`id`, `anggota`, `gula`, `kolestrol`, `asam`, `tekanan`, `nadi`, `saturasi`, `rr`, `suhu`, `keterangan`, `created_at`, `updated_at`, `inputed_by`, `updated_by`) VALUES
(1, 2, '120', '200', '6.5', '120/81', '72', '98', '16', '36.5', 'sehat walafiat', '2025-11-02 22:35:19', '2025-11-02 22:35:58', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `foto` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nip`, `password`, `nama`, `jabatan`, `tempat_lahir`, `tanggal_lahir`, `role`, `foto`) VALUES
(1, '123', '$2y$10$isxl3Hme3tAfWJxj5EVmu.tsSXCCf83BWkbpSQMk748aVQJTKMRsS', 'Admin', 'admin', 'magelang', '2025-10-01', 'admin', 'user.jfif'),
(2, '1231', '$2y$10$GIgZWs1NPPXmetws6UkvQuy5dgXLqjGUTCb5lGoB7ibog7p0VYn5m', 'marsella', 'anggota', 'magelang', '2025-10-27', 'user', '2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ketersediaan`
--
ALTER TABLE `ketersediaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemeriksaan`
--
ALTER TABLE `pemeriksaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota` (`anggota`),
  ADD KEY `inputed_by` (`inputed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ketersediaan`
--
ALTER TABLE `ketersediaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemeriksaan`
--
ALTER TABLE `pemeriksaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
