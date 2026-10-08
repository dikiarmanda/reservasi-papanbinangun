-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 22, 2026 at 12:13 PM
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
-- Database: `homestay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `nama`, `username`, `password`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$03yEb7LyKGVhVc7mtGGhvuCIIHF3pdaIMUHqvCCRA6c40HbT2GNk6', '2026-09-22 10:31:10');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `kode_booking` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `homestay_id` int NOT NULL,
  `nama_tamu` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `jumlah_tamu` int NOT NULL DEFAULT '1',
  `malam` int NOT NULL,
  `harga_per_malam` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','dikonfirmasi','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `kode_booking`, `homestay_id`, `nama_tamu`, `whatsapp`, `checkin`, `checkout`, `jumlah_tamu`, `malam`, `harga_per_malam`, `total`, `catatan`, `status`, `created_at`) VALUES
(1, 'HS2609225140DE', 3, 'asaksk', '085746880092', '2026-09-22', '2026-09-23', 1, 1, '180000.00', '180000.00', '', 'dikonfirmasi', '2026-09-22 10:45:49'),
(2, 'HS2609222BF9A4', 3, 'nazu', '085746880092', '2026-09-22', '2026-09-23', 4, 1, '180000.00', '180000.00', 'bersihkan jedeng nya', 'dikonfirmasi', '2026-09-22 12:01:29'),
(3, 'HS2609221CA142', 3, 'nazu', '085746880092', '2026-09-22', '2026-09-23', 1, 1, '180000.00', '180000.00', 'bersihkan jedeng', 'dikonfirmasi', '2026-09-22 12:10:43');

-- --------------------------------------------------------

--
-- Table structure for table `homestays`
--

CREATE TABLE `homestays` (
  `id` int NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `kapasitas` int NOT NULL DEFAULT '1',
  `fasilitas` text COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `homestays`
--

INSERT INTO `homestays` (`id`, `nama`, `lokasi`, `deskripsi`, `harga`, `kapasitas`, `fasilitas`, `gambar`, `status`, `created_at`) VALUES
(1, 'Homestay Papan Binangun', 'Desa Binangun', 'Homestay nyaman dengan suasana tenang dan cocok untuk liburan keluarga.', '250000.00', 4, '0', 'assets/images/homestay-1790078290-ce73a1af.jpg', 'aktif', '2026-09-22 10:31:10'),
(2, 'Villa Pinus Asri', 'Area Perbukitan', 'Penginapan dengan suasana alam dan area santai untuk keluarga.', '200000.00', 3, '0', 'assets/images/homestay-1790076809-aa9695f9.jpg', 'aktif', '2026-09-22 10:31:10'),
(3, 'Rumah Singgah Desa', 'https://maps.app.goo.gl/Z7nJHH4RJHZRD9hs6', 'Pilihan ekonomis untuk perjalanan singkat maupun keluarga kecil.', '180000.00', 4, '0', 'assets/images/homestay-1790077163-c6ee3a50.jpg', 'aktif', '2026-09-22 10:31:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `fk_booking_homestay` (`homestay_id`);

--
-- Indexes for table `homestays`
--
ALTER TABLE `homestays`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `homestays`
--
ALTER TABLE `homestays`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_booking_homestay` FOREIGN KEY (`homestay_id`) REFERENCES `homestays` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
