-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2019 at 10:12 AM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kavine`
--

-- --------------------------------------------------------

--
-- Table structure for table `patiekalai`
--

CREATE TABLE `patiekalai` (
  `id` int(10) UNSIGNED NOT NULL,
  `pav` varchar(256) COLLATE utf8_lithuanian_ci NOT NULL,
  `trukme_ruosimo` int(11) NOT NULL,
  `trukme_kaitinimo` int(11) NOT NULL,
  `kaina` decimal(12,2) UNSIGNED NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `patiekalai`
--

INSERT INTO `patiekalai` (`id`, `pav`, `trukme_ruosimo`, `trukme_kaitinimo`, `kaina`) VALUES
(1, 'Blyneliai su bananais', 15, 10, '26.00'),
(2, 'Bulv?s free su daržov?mis', 5, 5, '12.00'),
(3, 'Bulviniai blynai', 10, 15, '28.00'),
(4, 'Cezario salotos', 15, 0, '19.00'),
(5, 'Kava Juoda', 0, 0, '5.00'),
(6, 'Kava Late', 0, 0, '6.00'),
(7, 'Kepta duona', 10, 10, '27.00'),
(8, 'Ledai', 0, 0, '8.00'),
(9, 'Ledai vaisiniai', 0, 0, '9.00'),
(10, 'Obuoliu pyragas su braskemis', 0, 0, '10.00'),
(11, 'Saltibarsciai su bulvemis', 15, 5, '31.00'),
(12, 'Vaisin? arbata', 0, 0, '12.00'),
(13, 'Varškėčiai', 20, 10, '43.00');

-- --------------------------------------------------------

--
-- Table structure for table `uzsakymai`
--

CREATE TABLE `uzsakymai` (
  `id` int(10) UNSIGNED NOT NULL,
  `pav` varchar(256) COLLATE utf8_lithuanian_ci NOT NULL,
  `trukme_ruosimo` int(10) UNSIGNED DEFAULT NULL,
  `trukme_kaitinimo` int(10) UNSIGNED DEFAULT NULL,
  `busena` enum('uzsakytas','anuliuotas','ivykdytas') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'uzsakytas',
  `laikas_uzsakymo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `laikas_patekimo` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `klientas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `id_patiekalo` int(10) UNSIGNED DEFAULT NULL,
  `kaina` decimal(12,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `uzsakymai`
--

INSERT INTO `uzsakymai` (`id`, `pav`, `trukme_ruosimo`, `trukme_kaitinimo`, `busena`, `laikas_uzsakymo`, `laikas_patekimo`, `klientas`, `id_patiekalo`, `kaina`) VALUES
(1, 'Ledai', 0, 0, 'ivykdytas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 8, '0.00'),
(2, 'Kava Juoda', 0, 0, 'ivykdytas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 5, '0.00'),
(4, 'Cezario salotos', 15, 0, 'uzsakytas', '2019-08-05 07:31:11', '2019-08-06 09:11:52', 'Gabrielius', NULL, '19.00'),
(6, 'Obuoliu pyragas su braskemis', 0, 0, 'anuliuotas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 10, '0.00'),
(7, 'Kava Late', 0, 0, 'anuliuotas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 6, '0.00'),
(9, 'Saltibarsciai su bulvemis', 15, 5, 'ivykdytas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 11, '0.00'),
(10, 'Obuoliu pyragas su braskemis', 0, 0, 'uzsakytas', '2019-08-05 07:31:11', '2019-08-06 09:19:21', 'Gabrielius', NULL, '10.00'),
(11, 'Saltibarsciai su bulvemis', 15, 5, 'uzsakytas', '2019-08-05 07:31:11', '2019-08-06 09:16:03', 'Gabrielius', NULL, '31.00'),
(12, 'Blyneliai su bananais', 15, 10, 'uzsakytas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 1, '0.00'),
(13, 'Varškėčiai', 20, 10, 'anuliuotas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 13, '0.00'),
(14, 'Kepta duona', 10, 10, 'uzsakytas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 7, '0.00'),
(15, 'Bulv?s free su daržov?mis', 5, 5, 'ivykdytas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 2, '0.00'),
(16, 'Ledai vaisiniai', 0, 0, 'anuliuotas', '2019-08-05 07:31:11', '2019-08-05 07:45:35', '', 9, '0.00'),
(17, 'Bulviniai blynai', 10, 15, 'uzsakytas', '2019-08-05 10:38:31', NULL, 'Deividas', NULL, '28.00'),
(18, 'Kepta duona', 10, 10, 'uzsakytas', '2019-08-05 09:30:37', NULL, 'Gabrielius', 7, '27.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patiekalai`
--
ALTER TABLE `patiekalai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_patiekalo` (`id_patiekalo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patiekalai`
--
ALTER TABLE `patiekalai`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD CONSTRAINT `uzsakymai_ibfk_1` FOREIGN KEY (`id_patiekalo`) REFERENCES `patiekalai` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
