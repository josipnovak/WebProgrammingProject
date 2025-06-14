-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 08:28 PM
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
-- Database: `kinoclub`
--

-- --------------------------------------------------------

--
-- Table structure for table `hall`
--

CREATE TABLE `hall` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hall`
--

INSERT INTO `hall` (`id`, `name`, `capacity`) VALUES
(1, 'Main hall', 260),
(3, 'New hall', 130);

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` (`id`, `title`, `description`, `duration`, `genre`, `poster_url`) VALUES
(1, 'Primjer', 'Opis', 43, 'Comedy', 'url'),
(2, 'Primjer', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `hall_id` int(11) DEFAULT NULL,
  `seat_row` varchar(5) DEFAULT NULL,
  `seat_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `hall_id`, `seat_row`, `seat_number`) VALUES
(1, 1, 'A', 1),
(2, 1, 'A', 2),
(3, 1, 'A', 3),
(4, 1, 'A', 4),
(5, 1, 'A', 5),
(6, 1, 'A', 6),
(7, 1, 'A', 7),
(8, 1, 'A', 8),
(9, 1, 'A', 9),
(10, 1, 'A', 10),
(11, 1, 'A', 11),
(12, 1, 'A', 12),
(13, 1, 'A', 13),
(14, 1, 'A', 14),
(15, 1, 'A', 15),
(16, 1, 'A', 16),
(17, 1, 'A', 17),
(18, 1, 'A', 18),
(19, 1, 'A', 19),
(20, 1, 'A', 20),
(21, 1, 'A', 21),
(22, 1, 'A', 22),
(23, 1, 'A', 23),
(24, 1, 'A', 24),
(25, 1, 'A', 25),
(26, 1, 'A', 26),
(27, 1, 'B', 1),
(28, 1, 'B', 2),
(29, 1, 'B', 3),
(30, 1, 'B', 4),
(31, 1, 'B', 5),
(32, 1, 'B', 6),
(33, 1, 'B', 7),
(34, 1, 'B', 8),
(35, 1, 'B', 9),
(36, 1, 'B', 10),
(37, 1, 'B', 11),
(38, 1, 'B', 12),
(39, 1, 'B', 13),
(40, 1, 'B', 14),
(41, 1, 'B', 15),
(42, 1, 'B', 16),
(43, 1, 'B', 17),
(44, 1, 'B', 18),
(45, 1, 'B', 19),
(46, 1, 'B', 20),
(47, 1, 'B', 21),
(48, 1, 'B', 22),
(49, 1, 'B', 23),
(50, 1, 'B', 24),
(51, 1, 'B', 25),
(52, 1, 'B', 26),
(53, 1, 'C', 1),
(54, 1, 'C', 2),
(55, 1, 'C', 3),
(56, 1, 'C', 4),
(57, 1, 'C', 5),
(58, 1, 'C', 6),
(59, 1, 'C', 7),
(60, 1, 'C', 8),
(61, 1, 'C', 9),
(62, 1, 'C', 10),
(63, 1, 'C', 11),
(64, 1, 'C', 12),
(65, 1, 'C', 13),
(66, 1, 'C', 14),
(67, 1, 'C', 15),
(68, 1, 'C', 16),
(69, 1, 'C', 17),
(70, 1, 'C', 18),
(71, 1, 'C', 19),
(72, 1, 'C', 20),
(73, 1, 'C', 21),
(74, 1, 'C', 22),
(75, 1, 'C', 23),
(76, 1, 'C', 24),
(77, 1, 'C', 25),
(78, 1, 'C', 26),
(79, 1, 'D', 1),
(80, 1, 'D', 2),
(81, 1, 'D', 3),
(82, 1, 'D', 4),
(83, 1, 'D', 5),
(84, 1, 'D', 6),
(85, 1, 'D', 7),
(86, 1, 'D', 8),
(87, 1, 'D', 9),
(88, 1, 'D', 10),
(89, 1, 'D', 11),
(90, 1, 'D', 12),
(91, 1, 'D', 13),
(92, 1, 'D', 14),
(93, 1, 'D', 15),
(94, 1, 'D', 16),
(95, 1, 'D', 17),
(96, 1, 'D', 18),
(97, 1, 'D', 19),
(98, 1, 'D', 20),
(99, 1, 'D', 21),
(100, 1, 'D', 22),
(101, 1, 'D', 23),
(102, 1, 'D', 24),
(103, 1, 'D', 25),
(104, 1, 'D', 26),
(105, 1, 'E', 1),
(106, 1, 'E', 2),
(107, 1, 'E', 3),
(108, 1, 'E', 4),
(109, 1, 'E', 5),
(110, 1, 'E', 6),
(111, 1, 'E', 7),
(112, 1, 'E', 8),
(113, 1, 'E', 9),
(114, 1, 'E', 10),
(115, 1, 'E', 11),
(116, 1, 'E', 12),
(117, 1, 'E', 13),
(118, 1, 'E', 14),
(119, 1, 'E', 15),
(120, 1, 'E', 16),
(121, 1, 'E', 17),
(122, 1, 'E', 18),
(123, 1, 'E', 19),
(124, 1, 'E', 20),
(125, 1, 'E', 21),
(126, 1, 'E', 22),
(127, 1, 'E', 23),
(128, 1, 'E', 24),
(129, 1, 'E', 25),
(130, 1, 'E', 26),
(131, 1, 'F', 1),
(132, 1, 'F', 2),
(133, 1, 'F', 3),
(134, 1, 'F', 4),
(135, 1, 'F', 5),
(136, 1, 'F', 6),
(137, 1, 'F', 7),
(138, 1, 'F', 8),
(139, 1, 'F', 9),
(140, 1, 'F', 10),
(141, 1, 'F', 11),
(142, 1, 'F', 12),
(143, 1, 'F', 13),
(144, 1, 'F', 14),
(145, 1, 'F', 15),
(146, 1, 'F', 16),
(147, 1, 'F', 17),
(148, 1, 'F', 18),
(149, 1, 'F', 19),
(150, 1, 'F', 20),
(151, 1, 'F', 21),
(152, 1, 'F', 22),
(153, 1, 'F', 23),
(154, 1, 'F', 24),
(155, 1, 'F', 25),
(156, 1, 'F', 26),
(157, 1, 'G', 1),
(158, 1, 'G', 2),
(159, 1, 'G', 3),
(160, 1, 'G', 4),
(161, 1, 'G', 5),
(162, 1, 'G', 6),
(163, 1, 'G', 7),
(164, 1, 'G', 8),
(165, 1, 'G', 9),
(166, 1, 'G', 10),
(167, 1, 'G', 11),
(168, 1, 'G', 12),
(169, 1, 'G', 13),
(170, 1, 'G', 14),
(171, 1, 'G', 15),
(172, 1, 'G', 16),
(173, 1, 'G', 17),
(174, 1, 'G', 18),
(175, 1, 'G', 19),
(176, 1, 'G', 20),
(177, 1, 'G', 21),
(178, 1, 'G', 22),
(179, 1, 'G', 23),
(180, 1, 'G', 24),
(181, 1, 'G', 25),
(182, 1, 'G', 26),
(183, 1, 'H', 1),
(184, 1, 'H', 2),
(185, 1, 'H', 3),
(186, 1, 'H', 4),
(187, 1, 'H', 5),
(188, 1, 'H', 6),
(189, 1, 'H', 7),
(190, 1, 'H', 8),
(191, 1, 'H', 9),
(192, 1, 'H', 10),
(193, 1, 'H', 11),
(194, 1, 'H', 12),
(195, 1, 'H', 13),
(196, 1, 'H', 14),
(197, 1, 'H', 15),
(198, 1, 'H', 16),
(199, 1, 'H', 17),
(200, 1, 'H', 18),
(201, 1, 'H', 19),
(202, 1, 'H', 20),
(203, 1, 'H', 21),
(204, 1, 'H', 22),
(205, 1, 'H', 23),
(206, 1, 'H', 24),
(207, 1, 'H', 25),
(208, 1, 'H', 26),
(209, 1, 'I', 1),
(210, 1, 'I', 2),
(211, 1, 'I', 3),
(212, 1, 'I', 4),
(213, 1, 'I', 5),
(214, 1, 'I', 6),
(215, 1, 'I', 7),
(216, 1, 'I', 8),
(217, 1, 'I', 9),
(218, 1, 'I', 10),
(219, 1, 'I', 11),
(220, 1, 'I', 12),
(221, 1, 'I', 13),
(222, 1, 'I', 14),
(223, 1, 'I', 15),
(224, 1, 'I', 16),
(225, 1, 'I', 17),
(226, 1, 'I', 18),
(227, 1, 'I', 19),
(228, 1, 'I', 20),
(229, 1, 'I', 21),
(230, 1, 'I', 22),
(231, 1, 'I', 23),
(232, 1, 'I', 24),
(233, 1, 'I', 25),
(234, 1, 'I', 26),
(235, 1, 'J', 1),
(236, 1, 'J', 2),
(237, 1, 'J', 3),
(238, 1, 'J', 4),
(239, 1, 'J', 5),
(240, 1, 'J', 6),
(241, 1, 'J', 7),
(242, 1, 'J', 8),
(243, 1, 'J', 9),
(244, 1, 'J', 10),
(245, 1, 'J', 11),
(246, 1, 'J', 12),
(247, 1, 'J', 13),
(248, 1, 'J', 14),
(249, 1, 'J', 15),
(250, 1, 'J', 16),
(251, 1, 'J', 17),
(252, 1, 'J', 18),
(253, 1, 'J', 19),
(254, 1, 'J', 20),
(255, 1, 'J', 21),
(256, 1, 'J', 22),
(257, 1, 'J', 23),
(258, 1, 'J', 24),
(259, 1, 'J', 25),
(260, 1, 'J', 26),
(262, 3, 'A', 1),
(263, 3, 'A', 2),
(264, 3, 'A', 3),
(265, 3, 'A', 4),
(266, 3, 'A', 5),
(267, 3, 'A', 6),
(268, 3, 'A', 7),
(269, 3, 'A', 8),
(270, 3, 'A', 9),
(271, 3, 'A', 10),
(272, 3, 'A', 11),
(273, 3, 'A', 12),
(274, 3, 'A', 13),
(275, 3, 'B', 1),
(276, 3, 'B', 2),
(277, 3, 'B', 3),
(278, 3, 'B', 4),
(279, 3, 'B', 5),
(280, 3, 'B', 6),
(281, 3, 'B', 7),
(282, 3, 'B', 8),
(283, 3, 'B', 9),
(284, 3, 'B', 10),
(285, 3, 'B', 11),
(286, 3, 'B', 12),
(287, 3, 'B', 13),
(288, 3, 'C', 1),
(289, 3, 'C', 2),
(290, 3, 'C', 3),
(291, 3, 'C', 4),
(292, 3, 'C', 5),
(293, 3, 'C', 6),
(294, 3, 'C', 7),
(295, 3, 'C', 8),
(296, 3, 'C', 9),
(297, 3, 'C', 10),
(298, 3, 'C', 11),
(299, 3, 'C', 12),
(300, 3, 'C', 13),
(301, 3, 'D', 1),
(302, 3, 'D', 2),
(303, 3, 'D', 3),
(304, 3, 'D', 4),
(305, 3, 'D', 5),
(306, 3, 'D', 6),
(307, 3, 'D', 7),
(308, 3, 'D', 8),
(309, 3, 'D', 9),
(310, 3, 'D', 10),
(311, 3, 'D', 11),
(312, 3, 'D', 12),
(313, 3, 'D', 13),
(314, 3, 'E', 1),
(315, 3, 'E', 2),
(316, 3, 'E', 3),
(317, 3, 'E', 4),
(318, 3, 'E', 5),
(319, 3, 'E', 6),
(320, 3, 'E', 7),
(321, 3, 'E', 8),
(322, 3, 'E', 9),
(323, 3, 'E', 10),
(324, 3, 'E', 11),
(325, 3, 'E', 12),
(326, 3, 'E', 13),
(327, 3, 'F', 1),
(328, 3, 'F', 2),
(329, 3, 'F', 3),
(330, 3, 'F', 4),
(331, 3, 'F', 5),
(332, 3, 'F', 6),
(333, 3, 'F', 7),
(334, 3, 'F', 8),
(335, 3, 'F', 9),
(336, 3, 'F', 10),
(337, 3, 'F', 11),
(338, 3, 'F', 12),
(339, 3, 'F', 13),
(340, 3, 'G', 1),
(341, 3, 'G', 2),
(342, 3, 'G', 3),
(343, 3, 'G', 4),
(344, 3, 'G', 5),
(345, 3, 'G', 6),
(346, 3, 'G', 7),
(347, 3, 'G', 8),
(348, 3, 'G', 9),
(349, 3, 'G', 10),
(350, 3, 'G', 11),
(351, 3, 'G', 12),
(352, 3, 'G', 13),
(353, 3, 'H', 1),
(354, 3, 'H', 2),
(355, 3, 'H', 3),
(356, 3, 'H', 4),
(357, 3, 'H', 5),
(358, 3, 'H', 6),
(359, 3, 'H', 7),
(360, 3, 'H', 8),
(361, 3, 'H', 9),
(362, 3, 'H', 10),
(363, 3, 'H', 11),
(364, 3, 'H', 12),
(365, 3, 'H', 13),
(366, 3, 'I', 1),
(367, 3, 'I', 2),
(368, 3, 'I', 3),
(369, 3, 'I', 4),
(370, 3, 'I', 5),
(371, 3, 'I', 6),
(372, 3, 'I', 7),
(373, 3, 'I', 8),
(374, 3, 'I', 9),
(375, 3, 'I', 10),
(376, 3, 'I', 11),
(377, 3, 'I', 12),
(378, 3, 'I', 13),
(379, 3, 'J', 1),
(380, 3, 'J', 2),
(381, 3, 'J', 3),
(382, 3, 'J', 4),
(383, 3, 'J', 5),
(384, 3, 'J', 6),
(385, 3, 'J', 7),
(386, 3, 'J', 8),
(387, 3, 'J', 9),
(388, 3, 'J', 10),
(389, 3, 'J', 11),
(390, 3, 'J', 12),
(391, 3, 'J', 13);

-- --------------------------------------------------------

--
-- Table structure for table `showtime`
--

CREATE TABLE `showtime` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `hall_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtime`
--

INSERT INTO `showtime` (`id`, `movie_id`, `hall_id`, `start_time`, `price`, `end_time`) VALUES
(1, 1, 1, '2025-06-05 20:00:00', 50.00, '2025-06-05 20:43:00'),
(2, 1, 1, '2025-06-05 21:00:00', 10.00, '2025-06-05 21:43:00'),
(3, 1, 3, '2025-06-06 20:00:00', 15.00, '2025-06-06 20:43:00'),
(4, 1, 3, '2025-06-13 21:00:00', 15.00, '2025-06-13 21:43:00'),
(5, 1, 1, '2025-06-07 20:00:00', 50.00, '2025-06-07 20:43:00');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `showtime_id` int(11) DEFAULT NULL,
  `seat_id` int(11) DEFAULT NULL,
  `purchase_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `showtime_id`, `seat_id`, `purchase_time`) VALUES
(2, 2, 3, 320, '2025-06-05 12:29:06'),
(3, 2, 1, 10, '2025-06-05 13:26:52'),
(4, 2, 1, 11, '2025-06-05 13:26:52'),
(5, 2, 1, 12, '2025-06-05 13:26:52'),
(6, 2, 1, 260, '2025-06-05 13:29:55'),
(7, 2, 1, 2, '2025-06-05 13:30:32'),
(8, 2, 1, 3, '2025-06-05 13:30:32'),
(9, 2, 1, 4, '2025-06-05 13:30:32'),
(10, 2, 1, 5, '2025-06-05 13:30:32'),
(11, 2, 1, 6, '2025-06-05 13:30:32'),
(12, 2, 1, 7, '2025-06-05 13:30:32'),
(13, 2, 1, 8, '2025-06-05 13:30:32'),
(14, 2, 1, 9, '2025-06-05 13:30:32'),
(15, 2, 1, 14, '2025-06-05 13:58:19'),
(16, 2, 1, 15, '2025-06-05 13:58:19'),
(17, 2, 1, 13, '2025-06-05 14:04:02'),
(18, 2, 4, 267, '2025-06-05 15:08:41'),
(19, 2, 4, 295, '2025-06-05 15:08:41'),
(20, 2, 4, 297, '2025-06-05 15:08:41'),
(21, 2, 4, 298, '2025-06-05 15:08:41'),
(22, 2, 4, 309, '2025-06-05 15:08:41'),
(23, 4, 5, 1, '2025-06-06 08:42:19'),
(24, 4, 5, 2, '2025-06-06 08:42:19'),
(25, 4, 1, 16, '2025-06-06 10:45:47'),
(26, 4, 1, 17, '2025-06-06 10:45:47'),
(27, 4, 1, 18, '2025-06-06 10:45:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'Josip123', '$2y$10$ap2YXi38Ikb5v6Lh1/zoBeQRy0w7wQanT996f6gqG4HDJgQQYtkiC', 'josip@josip.com', 'user', '2025-06-01 17:34:33'),
(2, 'Josip1', '$2y$10$EjIirz5SzzAIqj.yRKyY6uFdUnf.6MKALuW11W3B8sNUqJl2zPXYW', 'josip1@josip1.com', 'user', '2025-06-01 17:36:23'),
(3, 'admin', '$2y$10$w36CP5pcvXsQ7fF7S3azy.AyUyMpvDcDfnfrPm/01PDuKL0muFa8m', 'admin@admin.com', 'admin', '2025-06-02 10:46:10'),
(4, 'User', '$2y$10$MEjm1lmvs4kvqvEZBeLIXuqTACw6V1LTr.u7ugeyw8UyOeGz8ylYO', 'user@user.com', 'user', '2025-06-06 06:41:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hall`
--
ALTER TABLE `hall`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hall_id` (`hall_id`);

--
-- Indexes for table `showtime`
--
ALTER TABLE `showtime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `hall_id` (`hall_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `showtime_id` (`showtime_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hall`
--
ALTER TABLE `hall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=396;

--
-- AUTO_INCREMENT for table `showtime`
--
ALTER TABLE `showtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`hall_id`) REFERENCES `hall` (`id`);

--
-- Constraints for table `showtime`
--
ALTER TABLE `showtime`
  ADD CONSTRAINT `showtime_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`),
  ADD CONSTRAINT `showtime_ibfk_2` FOREIGN KEY (`hall_id`) REFERENCES `hall` (`id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtime` (`id`),
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
