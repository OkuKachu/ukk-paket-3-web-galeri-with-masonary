-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2025 at 01:12 AM
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
-- Database: `db_galeri`
--

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `AlbumID` int(11) NOT NULL,
  `NamaAlbum` varchar(255) NOT NULL,
  `Deskripsi` text NOT NULL,
  `TanggalDibuat` date NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`AlbumID`, `NamaAlbum`, `Deskripsi`, `TanggalDibuat`, `UserID`) VALUES
(12, 'Kartun Kucing', 'Ini deskripsi', '2025-01-21', 10),
(14, 'Kucing Kampung', 'Ini kucing kampung', '2025-01-21', 10),
(15, 'Kucing Persia', 'Ini deskripsi', '2025-01-21', 10),
(16, 'Kucing Anggora', 'Ini deskripsi', '2025-01-21', 10);

-- --------------------------------------------------------

--
-- Table structure for table `foto`
--

CREATE TABLE `foto` (
  `FotoID` int(11) NOT NULL,
  `JudulFoto` varchar(255) NOT NULL,
  `Deskripsi` text NOT NULL,
  `TanggalUnggah` date NOT NULL,
  `Gambar` varchar(255) NOT NULL,
  `Album_ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foto`
--

INSERT INTO `foto` (`FotoID`, `JudulFoto`, `Deskripsi`, `TanggalUnggah`, `Gambar`, `Album_ID`, `UserID`) VALUES
(30, 'logo', 'Ini deskripsi', '2025-01-21', 'foto1737443553.jpg', 12, 10),
(31, 'Magenta Heart', 'Ini deskripsi', '2025-01-21', 'foto1737443572.jpg', 12, 10),
(32, 'Hitam', 'Ini deskripsi', '2025-01-21', 'foto1737443585.jpg', 12, 10),
(40, 'Oren', 'Ini deskripsi', '2025-01-21', 'foto1737445724.jpg', 14, 10),
(41, 'Belang', 'Ini deskripsi', '2025-01-21', 'foto1737445738.jpg', 14, 10),
(42, 'Mujair', 'Ini deskripsi', '2025-01-21', 'foto1737445755.jpg', 14, 10),
(43, 'Tuxedo', 'Ini deskripsi', '2025-01-21', 'foto1737445773.jpg', 14, 10),
(44, 'Mujair 2', 'Ini deskripsi', '2025-01-21', 'foto1737445791.jpg', 14, 10),
(45, 'Belang Oren', 'Ini deskripsi', '2025-01-21', 'foto1737445807.jpg', 14, 10),
(46, 'Putih Abu', 'Ini deskripsi', '2025-01-21', 'foto1737445933.jpg', 15, 10),
(47, 'Mujair', 'Ini deskripsi', '2025-01-21', 'foto1737445943.jpg', 15, 10),
(48, 'Putih', 'Ini deskripsi', '2025-01-21', 'foto1737445959.jpg', 15, 10),
(49, 'Abu', 'Ini deskripsi', '2025-01-21', 'foto1737445974.jpg', 15, 10),
(50, 'Oren Abu', 'Ini deskripsi', '2025-01-21', 'foto1737445994.jpg', 15, 10),
(51, 'Oren', 'Ini deskripsi', '2025-01-21', 'foto1737446007.jpg', 15, 10),
(52, 'Putih Abu', 'Ini deskripsi', '2025-01-21', 'foto1737446102.jpg', 16, 10),
(53, 'Putih', 'Ini deskripsi', '2025-01-21', 'foto1737446114.jpg', 16, 10),
(54, 'Oren', 'Ini deskripsi', '2025-01-21', 'foto1737446127.jpg', 16, 10),
(55, 'Oren Putih', 'Ini deskripsi', '2025-01-21', 'foto1737446145.jpg', 16, 10),
(56, 'Putih Abu 2', 'Ini deskripsi', '2025-01-21', 'foto1737446158.jpg', 16, 10);

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `KomentarID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `IsiKomentar` text NOT NULL,
  `TanggalKomentar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`KomentarID`, `FotoID`, `UserID`, `IsiKomentar`, `TanggalKomentar`) VALUES
(7, 12, 10, 'pembohong publik', '2025-01-15'),
(8, 12, 10, 'pembohong publik', '2025-01-15'),
(9, 12, 10, 'pembohong publik', '2025-01-15'),
(10, 12, 10, 'pembohong publik', '2025-01-15'),
(11, 12, 10, 'pembohong publik', '2025-01-15'),
(12, 12, 10, 'pembohong publik', '2025-01-15'),
(13, 12, 10, 'pembohong publik', '2025-01-15'),
(14, 12, 10, 'pembohong publik', '2025-01-15'),
(16, 12, 10, 'afa iyah', '2025-01-15'),
(17, 12, 10, 'afa iyah', '2025-01-15'),
(18, 12, 10, 'afa iyah', '2025-01-15'),
(19, 12, 10, 'afa iyah', '2025-01-15'),
(20, 12, 10, 'afa iyah', '2025-01-15'),
(21, 12, 10, 'afa iyah', '2025-01-15'),
(22, 12, 10, 'afa iyah', '2025-01-15'),
(23, 12, 10, 'afa iyah', '2025-01-15'),
(24, 12, 10, 'afa iyah', '2025-01-15'),
(25, 12, 10, 'afa iyah', '2025-01-15'),
(26, 12, 10, 'afa iyah', '2025-01-15'),
(27, 12, 10, 'afa iyah', '2025-01-15'),
(28, 12, 10, 'afa iyah', '2025-01-15'),
(29, 12, 10, 'afa iyah', '2025-01-15'),
(30, 12, 10, 'afa iyah', '2025-01-15'),
(31, 12, 10, 'afa iyah', '2025-01-15'),
(32, 12, 10, 'afa iyah', '2025-01-15'),
(33, 12, 10, 'afa iyah', '2025-01-15'),
(34, 12, 10, 'zjljsj', '2025-01-15'),
(35, 12, 10, 'zjljsj', '2025-01-15'),
(36, 12, 10, 'zjljsj', '2025-01-15'),
(37, 12, 10, 'sxmssssssss', '2025-01-15'),
(38, 12, 10, '123', '2025-01-15'),
(39, 12, 10, '123', '2025-01-15'),
(40, 12, 10, '123', '2025-01-15'),
(41, 12, 10, '123', '2025-01-15'),
(42, 12, 10, '123', '2025-01-15'),
(43, 12, 10, '123', '2025-01-15'),
(45, 16, 10, 'Hahah lucu lu', '2025-01-20'),
(46, 19, 10, 'Punya gweh', '2025-01-20'),
(47, 27, 11, 'hai pohon', '2025-01-20'),
(48, 27, 10, 'hai sapi', '2025-01-20'),
(49, 27, 11, 'kok gak bisa hapus komenmu min ayam', '2025-01-20'),
(50, 27, 10, 'ini perintah admini', '2025-01-20'),
(51, 29, 10, 'afa iyah', '2025-01-21'),
(52, 35, 10, 'halo', '2025-01-21'),
(53, 37, 10, 'halo', '2025-01-21');

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `LikeID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TanggalLike` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`LikeID`, `FotoID`, `UserID`, `TanggalLike`) VALUES
(31, 16, 10, '2025-01-15'),
(32, 16, 10, '2025-01-20'),
(35, 19, 10, '2025-01-20'),
(41, 23, 10, '2025-01-20'),
(47, 27, 11, '2025-01-20'),
(48, 27, 10, '2025-01-20'),
(49, 20, 10, '2025-01-21'),
(54, 56, 10, '2025-01-21'),
(55, 56, 10, '2025-01-21'),
(56, 56, 10, '2025-01-21'),
(57, 56, 10, '2025-01-21'),
(58, 56, 10, '2025-01-21'),
(59, 56, 10, '2025-01-21'),
(60, 56, 10, '2025-01-21'),
(61, 56, 10, '2025-01-21'),
(62, 56, 10, '2025-01-21'),
(63, 56, 10, '2025-01-21'),
(64, 56, 10, '2025-01-21'),
(65, 56, 10, '2025-01-21'),
(66, 56, 10, '2025-01-21'),
(67, 56, 10, '2025-01-21'),
(68, 56, 10, '2025-01-21'),
(69, 56, 10, '2025-01-21'),
(70, 56, 10, '2025-01-21'),
(71, 56, 10, '2025-01-21'),
(72, 56, 10, '2025-01-21'),
(73, 56, 10, '2025-01-21'),
(74, 55, 10, '2025-01-21'),
(75, 55, 10, '2025-01-21'),
(76, 55, 10, '2025-01-21'),
(77, 51, 10, '2025-01-21'),
(78, 51, 10, '2025-01-21'),
(79, 55, 10, '2025-01-21'),
(85, 55, 10, '2025-01-21');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `NamaLengkap` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `NamaLengkap`, `Alamat`, `role`) VALUES
(4, 'user1', '$2y$10$QuiYB/PkwIIg/mN4G635LuOAbM4OouOSyHT5dOGIoZdswuVH0xrL6', '12345@gmail.com', 'user21', '12345', 'user'),
(6, 'admin', '$2y$10$4fv/b0kj4G7.XFy6R9fJFeTZOEZUbqPAEr6FXUfqiKZju/tGP9VzW', 'admin@example.com', 'Admin Galeri', 'Jl. Contoh No. 123', 'user'),
(10, 'ayam', '$2y$10$TepmXKbwUO8b2wYxGQoIcOmgVZ.f4d9UkY7A.vx12/yJ1oacwRkP2', 'pendu77@gmail.com', 'Ayam', 'jalan kanan kiri', 'admin'),
(11, 'sapi', '$2y$10$C6BPMH7zWkIFlxKTlrTJ.e8rTF6aEQVdL7FJx7/8NQ6msdlXOvgfC', 'Silvia@gmail.com', 'Sapi', 'Amba', 'user'),
(12, 'bebek', '$2y$10$WtF1grjeqlTY0LAz77GBu.gjydqNOZFA8A95olJSZa66qxTNY3.hi', 'rusdyngawi123@gmail.com', 'bebek', '', 'admin'),
(14, '꧁ℭ℟Åℤ¥༒₭ÏḼḼ℥℟꧂', '$2y$10$SZRvPfgZhlwp01s3pKEb1enn8TTWkQFLGWVnXjtft.abiS6dtDQVq', 'pendu77@gmail.com', 'Wawa Ganteng', '123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`AlbumID`),
  ADD KEY `album_ibfk_1` (`UserID`);

--
-- Indexes for table `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`FotoID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `foto_ibfk_1` (`Album_ID`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`KomentarID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `komentar_ibfk_1` (`FotoID`);

--
-- Indexes for table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`LikeID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `like_ibfk_1` (`FotoID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `AlbumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `foto`
--
ALTER TABLE `foto`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `KomentarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `foto_ibfk_1` FOREIGN KEY (`Album_ID`) REFERENCES `album` (`AlbumID`) ON DELETE CASCADE,
  ADD CONSTRAINT `foto_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`FotoID`) REFERENCES `foto` (`FotoID`) ON DELETE CASCADE,
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
