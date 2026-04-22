-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3101
-- Generation Time: Apr 20, 2026 at 06:19 AM
-- Server version: 5.7.33
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lazismu`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `kode_setoran`
--

CREATE TABLE `kode_setoran` (
  `id` int(11) NOT NULL,
  `jenis_setoran` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `link`, `parent_id`, `role`, `seq`, `icon`, `created_at`, `updated_at`, `deleted_at`, `module`) VALUES
(1, 'Dashboard', 'dashboard', NULL, ';superadmin;admin;hrd;pengurus;keuangan;direktur;adminpt;marketing;adminproject;', 1, 'bi-speedometer2', '2025-10-31 09:22:40', '2026-03-31 03:41:00', NULL, NULL),
(2, 'Master', '', NULL, ';superadmin;admin;hrd;pengurus;keuangan;direktur;adminpt;', 2, 'bi-database-gear', '2025-10-31 09:22:40', NULL, NULL, 'master'),
(3, 'PT', '', NULL, ';superadmin;admin;hrd;pengurus;keuangan;direktur;manager;adminpt;adminproject;', 2, 'bi-building', '2025-10-31 09:22:40', NULL, NULL, 'project');

-- --------------------------------------------------------

--
-- Table structure for table `mobilemenu`
--

CREATE TABLE `mobilemenu` (
  `idmenu` int(11) NOT NULL,
  `namamenu` varchar(100) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `status` enum('drawer','shortcut') DEFAULT 'drawer',
  `userakses` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `mobilemenu`
--

INSERT INTO `mobilemenu` (`idmenu`, `namamenu`, `link`, `icon`, `color`, `level`, `status`, `userakses`) VALUES
(1, 'Home', '/mobile/home', 'home-outline', '', 'user', 'drawer', NULL),
(2, 'Profile', '/mobile/presensi/editprofile', 'person-circle-outline', '', 'user', 'drawer', NULL),
(3, 'Presensi', '/mobile/presensi/create', 'finger-print-outline', '', 'user', 'drawer', NULL),
(4, 'Visit', '/mobile/presensi/visit', 'location-outline', '', 'user', 'drawer', NULL),
(5, 'Izin', '/mobile/presensi/izin', 'document-text-outline', '', 'user', 'drawer', NULL),
(6, 'Kalender', '/mobile/kalender', 'calendar-outline', '', 'user', 'drawer', NULL),
(7, 'Payroll', '/mobile/payroll', 'cash-outline', '', 'user', 'drawer', NULL),
(8, 'Bonus', '/mobile/bonus', 'gift-outline', '', 'user', 'drawer', NULL),
(9, 'Lembur', '/mobile/presensi/lembur', 'time-outline', '', 'user', 'drawer', NULL),
(10, 'Approval Izin', '/mobile/presensi/approvalizin', 'checkmark-circle-outline', '', 'user', 'drawer', '2'),
(11, 'Performance', '/mobile/kalender/statistik', 'stats-chart-outline', '', 'user', 'drawer', NULL),
(12, 'Approval Izin', '/mobile/presensi/approvalizin', 'checkmark-circle-outline', '', 'user', 'drawer', '1'),
(13, 'Buat Agenda', '/mobile/presensi/agenda', 'calendar-number-outline', '', 'user', 'drawer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 2),
(6, 'App\\Models\\User', 2),
(7, 'App\\Models\\User', 2),
(8, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(7, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6),
(5, 'App\\Models\\User', 6),
(14, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(5, 'App\\Models\\User', 8),
(4, 'App\\Models\\User', 9),
(5, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(3, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(6, 'App\\Models\\User', 14),
(7, 'App\\Models\\User', 14),
(1, 'App\\Models\\User', 182),
(1, 'App\\Models\\User', 183),
(1, 'App\\Models\\User', 185),
(1, 'App\\Models\\User', 186),
(3, 'App\\Models\\User', 187),
(3, 'App\\Models\\User', 188),
(7, 'App\\Models\\User', 189),
(5, 'App\\Models\\User', 190),
(5, 'App\\Models\\User', 191),
(5, 'App\\Models\\User', 192);

-- --------------------------------------------------------

--
-- Table structure for table `muzaki`
--

CREATE TABLE `muzaki` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(50) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `terkumpul` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2025-04-19 14:45:30', '2025-04-19 14:45:30'),
(2, 'direktur', 'web', '2025-04-19 14:48:51', '2025-04-19 14:48:51'),
(3, 'manager', 'web', '2025-09-12 07:34:28', NULL),
(4, 'adminpt', 'web', '2025-09-12 07:34:28', NULL),
(5, 'adminproject', 'web', '2025-09-12 07:34:28', NULL),
(6, 'hrd', 'web', '2025-09-12 07:34:28', NULL),
(7, 'keuangan', 'web', '2025-09-12 07:34:28', NULL),
(8, 'it', 'web', '2025-09-12 07:34:28', NULL),
(14, 'marketing', 'web', '2025-12-14 01:31:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4kaFxmTwtWLI3Owt2k8SgaPFAObXhRIwLUvpXMA7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOG54MUFuNzlGVjl2VjhlaWF6RUtEd3ZzSHJBOE9lRGJHUm9aMzZkNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776665914),
('5hDRAgI8Fn0YktSAj6yaIiOpJagEuZv9piXVdsPY', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaDQwTkNnTm5aSzhTbDRjVHV0ZHdhRW1VTm5kZXNYd0MySGZqZU9CaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1776656794);

-- --------------------------------------------------------

--
-- Table structure for table `setoran`
--

CREATE TABLE `setoran` (
  `id` int(11) NOT NULL,
  `idmuzaki` int(255) DEFAULT NULL,
  `idkode_setoran` int(255) DEFAULT NULL,
  `idprogram` int(255) DEFAULT NULL,
  `nominal` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_perusahaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `nama_perusahaan`, `alamat`, `telepon`, `path_logo`, `created_at`, `updated_at`) VALUES
(1, 'SOLAR SYSTEM', 'Sarirejo, 5/6 Kaliwungu Kendal', '085740052284', 'logopt/1770191759_logosolar.png', NULL, '2025-08-08 20:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nip` varchar(20) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nohp` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `id_unitkerja` int(11) DEFAULT NULL,
  `ui` enum('admin','user') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `nip`, `username`, `name`, `password`, `foto`, `jabatan`, `tanggal_masuk`, `status`, `email`, `nohp`, `alamat`, `email_verified_at`, `remember_token`, `updated_at`, `created_at`, `deleted_at`, `id_unitkerja`, `ui`) VALUES
(1, '3324081203900002', 'ID00007', 'budi', 'DZULFIKAR BUDI ANDREANTO', '$2y$12$BmLsKFQIdhJ43KjXQOHoo.6gjfcjuJTEA79wvWoFPVGOvspep4Nre', '3324081203900002.jpeg', 'IT', '2025-05-06', 'aktif', 'buddyandreanto@gmail.com', '0811328884', 'Jalan Raya Boja Limbangan Salamsari Kec. Boja Kabupaten Kendal Jawa Tengah 51381', '2025-11-02 23:11:15', 'lghU0CE4ny9FEUCLtCZ1PyRNp9D8zh1rN8excpkoDyjNk5u0MHQ7ZvsMiETy', '2025-11-03 00:46:16', '2025-04-19 14:37:25', NULL, 4, 'admin'),
(2, '3324081705910006', 'ID00010', 'amal', 'IKHLASUL AMAL AHYANI', '$2y$12$.a5lzCFs3pzyfLYXx.udS.nN3m0Oj3K/sUhzBQgGKP/pRZmWp/5FK', '332455678.jpg', 'Direktur', '2025-09-15', 'aktif', 'eekhlas@gmail.com', NULL, NULL, '2025-09-01 05:54:59', NULL, '2025-11-03 04:20:45', '2025-09-15 09:56:22', NULL, 1, 'admin'),
(3, '3324201907840001', 'ID00001', '3324201907840001', 'SUKAMTO', '$2y$12$59AeVVnm62Hn1R14yFK3EufXzZ6IWa0JbxuG9i4uh0b3i/PL42t6i', NULL, 'Project Manager', '2023-06-01', 'aktif', 'sukamto190785@gmail.com', '085952867273', 'DK.PENJALIN RT 001/RW 006, PROTOMULYO, KALIWUNGU SELATAN, KENDAL', '2025-11-02 23:18:42', NULL, '2025-11-02 23:18:42', NULL, NULL, 3, 'admin'),
(4, '3324136806960001', 'ID00002', '3324136806960001', 'SAFAATUL AZIZAH', '$2y$12$AxH5sUljkSFnGDFvrXn1M.s3.blkq5WC4KLNfG643s5wW03Yz41DK', '3324136806960001.jpg', 'Project Manager', '2023-02-01', 'aktif', 'saafaatul9@gmail.com', '089625430673', 'DUSUN KRAJAN RT 001/RW 002, TRUKO, KANGKUNG, KENDAL', '2025-11-02 23:25:12', NULL, '2026-02-14 04:18:58', NULL, NULL, 3, 'admin'),
(5, '3303114405000001', 'ID00003', '3303114405000001', 'TRIA INDAH LESTARI', '$2y$12$I6aYe38Hgqg1nBqIIsEknu2TBFV1.gWFkPZ.SFodfsaZox3VsSBh2', '3303114405000001.jpg', 'Accounting', '2025-05-01', 'aktif', 'triaindah.lst@gmail.com', '085640243486', 'SAWANGAN RT 001/RW 001, KALIORI, KARANGANYAR, PURBALINGGA', '2025-11-02 23:25:29', NULL, '2025-11-02 23:25:29', NULL, NULL, 1, 'admin'),
(6, '3324136403950001', 'ID00004', '3324136403950001', 'DEFI NOFI PERMATASARI', '$2y$12$59AeVVnm62Hn1R14yFK3EufXzZ6IWa0JbxuG9i4uh0b3i/PL42t6i', '3324136403950001.jpg', 'Marketing', '2025-08-11', 'aktif', 'definofi069@gmail.com', '085335806177', 'PODOSARI RT 003/RW 001, PODOSARI, CEPIRING, KENDAL', '2025-11-02 23:25:46', NULL, '2025-11-02 23:25:46', NULL, NULL, 4, 'admin'),
(7, '1807226106920003', 'ID00005', '1807226106920003', 'MARISCHA WIDYA FURYANTO', '$2y$12$XcLFGyZsomZG50HSsqsCvuBc8xvIZLzc27fA9FrmZPjNa9AAvwwz2', '1807226106920003.jpg', 'Project Administrator', '2025-08-19', 'aktif', 'marischawidy21@gmail.com', '081377880052', 'PERUM SRENDENG GREEN RESIDENCE DUSUN DAKAH RT 005/RW 004, GEDONG, PATEAN, KENDAL', '2025-11-02 23:25:57', NULL, '2025-11-21 09:10:27', NULL, NULL, 4, 'admin'),
(8, '3324015112000001', 'ID00006', '3324015112000001', 'IKA LAILI ISTIQO', '$2y$12$59AeVVnm62Hn1R14yFK3EufXzZ6IWa0JbxuG9i4uh0b3i/PL42t6i', '3324015112000001.jpg', 'Project Administrator', '2025-10-01', 'aktif', 'ika89lailiistiqo@gmail.com', '085182662110', 'PUNDUNG RT 005/ RW 002, BENDOSARI, PLANTUNGAN, KENDAL', '2025-11-02 23:26:14', NULL, '2025-11-02 23:26:14', NULL, NULL, 1, 'admin'),
(9, '3324115502940002', 'ID00008', '3324115502940002', 'MIFTAHUL JANAH', '$2y$12$LHB0btqCP.9hmULPmVIM6.vJt391ysWr4Eus476/r/CWsvdT1Iqai', NULL, 'Project Administrator', '2025-11-01', 'aktif', 'miftahuljanah1231@gmail.com', '089512053305', 'Gemuhblanten Rt 03 Rw 03, Kec. Gemuh', '2025-11-03 02:50:41', NULL, '2025-11-03 02:50:41', '2025-11-03 02:17:20', NULL, 2, 'admin'),
(10, '3324082405930001', 'ID00009', 'ghozali', 'AHMAD GHOZALI', '$2y$12$TU7ou5uk0ah/q4CMFSmFueeiVvEYEE1xTzuk2giJBSDnKR5/pgmmi', NULL, 'Komisaris', '2025-11-01', 'aktif', 'ghozali@gmail.com', NULL, NULL, '2025-11-03 00:44:58', NULL, '2025-11-02 22:37:03', '2025-11-02 22:26:39', NULL, 1, 'admin'),
(11, '3324082812860001', 'ID00011', 'ipul', 'M. SAIFUL HIDAYAT', '$2y$12$bh7VQiPSMNXRxYKtzoJc8OEkWnisePfVF3r3Qos2ZTXYEj8Pknfmi', '3324082812860001.jpg', 'Direktur Utama', '2025-11-01', 'aktif', 'ipul.knight2@gmail.com', '085740052284', NULL, '2025-11-03 00:44:58', NULL, '2025-11-02 22:37:08', '2025-11-02 22:28:30', NULL, 1, 'admin'),
(12, '3324201010740001', 'ID00012', 'edyhansa', 'EDY HANSA', '$2y$12$GzkH50DvacM5nivHxVus6edbHgpEhLKQy7/KPKeSrSFhx13hFdzGi', NULL, 'Komisaris Utama', '2025-11-01', 'aktif', 'hansa74@gmail.com', NULL, NULL, '2025-11-03 00:44:58', NULL, '2025-11-02 22:37:14', '2025-11-02 22:30:11', NULL, 1, 'admin'),
(16, '1207335108940001', 'ID00009', '1207335108940001', 'MUSTIKA', '$2y$12$2K.ERW0Sv7gvFrVBCgDE0OtLYTeZKBsacN/K0b9MG6XPzVlznnex6', NULL, 'Project Manager', '2026-04-16', 'aktif', NULL, NULL, 'TEJOREJO RT 005 / RW 002 TEJOREJO, RINGINARUM, KAB.KENDAL', '2026-04-16 03:25:56', NULL, '2026-04-16 03:25:56', '2026-04-16 03:25:56', NULL, 3, 'admin'),
(17, '3171045012940003', 'ID00010', '3171045012940003', 'DWI PUTRI NOVALIA', '$2y$12$xto6Dp0d.p2u/Rll9.vFf.3v7vMQjlUF6/Dhqde3EBofD8fLrli/W', NULL, 'Marketing', '2026-04-16', 'aktif', NULL, NULL, 'DESA PAMPANGAN RT 002 / RW 002. PAMPANGAN, GEDONG TATAAN, KAB.KENDAL', '2026-04-16 03:28:20', NULL, '2026-04-16 03:28:20', '2026-04-16 03:28:20', NULL, 4, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`) USING BTREE;

--
-- Indexes for table `kode_setoran`
--
ALTER TABLE `kode_setoran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `mobilemenu`
--
ALTER TABLE `mobilemenu`
  ADD PRIMARY KEY (`idmenu`) USING BTREE;

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`) USING BTREE,
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`) USING BTREE;

--
-- Indexes for table `muzaki`
--
ALTER TABLE `muzaki`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`) USING BTREE;

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`) USING BTREE,
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`) USING BTREE;

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `sessions_user_id_index` (`user_id`) USING BTREE,
  ADD KEY `sessions_last_activity_index` (`last_activity`) USING BTREE;

--
-- Indexes for table `setoran`
--
ALTER TABLE `setoran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`nip`) USING BTREE,
  ADD KEY `users_nomoranggota_unique` (`nip`,`username`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mobilemenu`
--
ALTER TABLE `mobilemenu`
  MODIFY `idmenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
