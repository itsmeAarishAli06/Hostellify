-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 03:44 PM
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
-- Database: `hostel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `contact`, `address`) VALUES
(1, 'Aarish Ali', 'itsmeaarish07@gmail.com', 'i58gen', '03001234567', 'Hyderabad');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `hostel_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room_type` enum('AC','Non AC') NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `student_id`, `room_id`, `hostel_id`, `booking_date`, `start_date`, `end_date`, `status`, `created_at`, `room_type`, `monthly_price`) VALUES
(78, 40, 34, 42, '0000-00-00', '0000-00-00', NULL, 'Approved', '2026-04-01 11:13:30', 'Non AC', 6300.00),
(82, 62, 34, 42, '0000-00-00', '0000-00-00', NULL, 'Approved', '2026-04-02 03:51:33', 'Non AC', 6300.00),
(83, 61, 35, 42, '0000-00-00', '0000-00-00', NULL, 'Approved', '2026-04-02 04:25:06', 'AC', 9500.00),
(86, 65, NULL, 44, '0000-00-00', '0000-00-00', NULL, 'Pending', '2026-04-07 13:35:49', 'AC', 0.00),
(88, 78, 33, 40, '0000-00-00', '0000-00-00', NULL, 'Approved', '2026-04-09 14:24:13', 'Non AC', 4500.00),
(89, 79, 31, 40, '0000-00-00', '0000-00-00', NULL, 'Approved', '2026-04-14 05:16:45', 'AC', 8000.00),
(90, 80, 31, 40, '0000-00-00', '0000-00-00', NULL, 'Approved', '2026-04-15 08:39:43', 'AC', 8000.00);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `hostel_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','resolved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `level` enum('low','medium','urgent') DEFAULT 'low'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `student_id`, `hostel_id`, `subject`, `message`, `status`, `created_at`, `updated_at`, `level`) VALUES
(19, 61, 42, 'Poor cleanliness conditions', 'when i entered in the room it was smelly.', 'resolved', '2026-04-01 18:33:52', '2026-04-02 03:50:26', 'urgent'),
(21, 78, 40, 'me ashbal masla hoo raha hay dust ka', 'Not clean rooms', 'pending', '2026-04-09 14:29:01', '2026-04-09 14:29:01', 'medium'),
(22, 78, 40, 'managment', 'wait', 'resolved', '2026-04-09 14:37:12', '2026-04-09 14:38:01', 'urgent');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `full_name`, `email`, `phone`, `subject`, `message`, `submitted_at`, `is_read`) VALUES
(12, 'Aarish ALi', 'itsmeaarish06@gmail.com', '0387373333', 'complaint', 'To easy to not to understand', '2026-03-31 12:00:24', 1),
(14, 'Ahemd', 'Ahemd@55gmail.com', '0333144532', 'other', 'I am  not logged into the account ? why ???', '2026-04-03 19:26:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hostel`
--

CREATE TABLE `hostel` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `hostel_name` varchar(80) NOT NULL,
  `city` varchar(80) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `hostel_type` enum('boys','girls','both') DEFAULT 'boys',
  `capacity` int(11) DEFAULT 0,
  `description` longtext NOT NULL,
  `amenities` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostel`
--

INSERT INTO `hostel` (`id`, `owner_id`, `hostel_name`, `city`, `address`, `contact`, `email`, `hostel_type`, `capacity`, `description`, `amenities`, `created_at`, `updated_at`, `image_path`) VALUES
(40, 27, 'Indus Paredise', 'Hyderabad', 'Hyderabad Pakistan', '+9234344521', 'itsmeaarish06@gmail.com', 'boys', 30, 'Indus Paredise Hostel is a well-managed student accommodation in Karachi. We offer clean rooms, high-speed WiFi, meals, laundry, and 24/7 security.', '', '2026-03-31 18:01:33', '2026-04-07 13:47:28', 'hostel_27_1775566886.png'),
(42, 7, 'Mehran Hostel', 'Hyderabad', 'Naseem Naghar , Hyderabad Pakistan', '+9234344667', 'itsmenazar72@gmail.com', 'boys', 160, 'Mehran Hostel is a well-managed student accommodation in Karachi. We offer clean rooms, high-speed WiFi, meals, laundry, and 24/7 security.', 'WiFi,Laundry,AC Rooms', '2026-04-01 07:21:17', '2026-04-07 13:47:48', 'hostel_7_1775569573.png'),
(43, 6, 'Indus View Hostel', 'Hyderabad', 'Opposite Pakora Stop Qasimabad', '+9234344223', 'ALi110@33.com', 'boys', 110, 'Indus View Hostel is a well-managed student accommodation in Karachi. We offer clean rooms, high-speed WiFi, meals, laundry, and 24/7 security.', '', '2026-04-02 05:51:55', '2026-04-07 13:48:22', 'hostel_6_1775566950.png'),
(44, 15, 'Hassan Hostel', 'Karachi', 'iqbal town 4 no , opposite oxford high school , Karachi', '+92343422315', 'saad3131@email.com', 'boys', 110, 'Hassan Hostel Hostel is a well-managed student accommodation in Karachi. We offer clean rooms, high-speed WiFi, meals, laundry, and 24/7 security.', '', '2026-04-02 05:56:56', '2026-04-07 13:48:54', 'hostel_15_1775567149.png');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id`, `name`, `email`, `password`, `contact`, `address`) VALUES
(6, 'Muhammad Ali', 'ALi110@33.com', '123456', '+340943855', 'Hyderabad Pakistan'),
(7, 'Nazar Muhammad', 'itsmenazar72@gmail.com', 'aarishali', '+9234344111', 'Hyderabad Pakistan'),
(15, 'Saad', 'saad3131@email.com', '107238', '+9234344345', 'Hyderabad Pakistan'),
(27, 'Aarish Ali', 'itsmeaarish06@gmail.com', '@Memon123', '+923434765', 'Hyderabad Pakistan'),
(28, 'ali', 'takermovies254@gmail.com', '90498347', '0369948522', 'hyderabad');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `hostel_id` int(11) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `room_type` enum('AC','Non AC') NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 1,
  `occupied` int(11) NOT NULL DEFAULT 0,
  `price_monthly` decimal(10,2) NOT NULL,
  `status` enum('Available','Full','Maintenance') NOT NULL DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `hostel_id`, `room_number`, `room_type`, `capacity`, `occupied`, `price_monthly`, `status`, `created_at`) VALUES
(31, 40, '3', 'AC', 4, 2, 8000.00, 'Available', '2026-03-31 18:02:27'),
(33, 40, '4', 'Non AC', 4, 1, 4500.00, 'Available', '2026-03-31 18:22:02'),
(34, 42, '3', 'Non AC', 5, 2, 6300.00, 'Available', '2026-04-01 07:33:02'),
(35, 42, '5', 'AC', 3, 2, 9500.00, 'Available', '2026-04-01 10:19:33'),
(36, 44, '4', 'AC', 4, 0, 5000.00, 'Available', '2026-04-02 05:57:10'),
(37, 43, '4', 'AC', 5, 0, 6600.00, 'Available', '2026-04-07 13:04:41');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(15) NOT NULL,
  `university` varchar(100) DEFAULT NULL,
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `name`, `email`, `password`, `address`, `contact`, `university`, `status`, `created_at`) VALUES
(6, 'Ahemd', 'Ahemd@55gmail.com', 'aliali', 'Hyderabad Pakistan', '+9234344523', NULL, 'active', '2026-03-31 06:46:38'),
(14, 'Ahmed Kazmi', 'bhularakhtahonkhula@gmail.com', '944370', 'Hyderabad Pakistan', '+9234343232', NULL, 'active', '2026-03-31 06:46:38'),
(40, 'Nazar Muhammad', 'itsmenazar72@gmail.com', '14199523', 'Bittai town , B block Hyderbad', '0399948585', NULL, 'active', '2026-03-31 06:46:38'),
(52, 'Sulaiman', 'msulee110@gmail.com', '59404414', 'Qas', '039994855', NULL, 'active', '2026-03-31 06:46:38'),
(61, 'Tanzeel ur Rehman', 'tanzeel@5.com', 'tanzeel', 'Hyderabad Pakistan', '0321144543', NULL, 'active', '2026-04-01 18:31:37'),
(62, 'Muhammad Ali', 'Mali110@gmail.com', 'muhammad', 'opp to jijal hospital', '+923434432', NULL, 'active', '2026-04-02 03:51:33'),
(64, 'John', 'itsjohn@gmail.com', 'johnjohn', 'Hyderabad Pakistan', '033826482', NULL, 'active', '2026-04-07 13:33:49'),
(65, 'John', 'john@gmail.com', 'johnjohn', 'Hyderabad Pakistan', '0322784443', NULL, 'active', '2026-04-07 13:35:49'),
(75, 'Aarish Ali', 'ali@gmail.com', '30143333', 'Hyderabad Pakistan', '+923434887', NULL, 'active', '2026-04-08 13:27:35'),
(78, 'Ashbal', 'ashbal@gmail.com', 'ashbal', 'Qasimabad Hyderabad Pakistan', '0347617183', NULL, 'active', '2026-04-09 14:24:13'),
(79, 'Ahmed Ali Kazmi', 'kazmi@gmail.com', 'kazmi123', 'Hyderabad Pakistan', '0334234763', NULL, 'active', '2026-04-14 05:16:45'),
(80, 'Saad Qadri', 'qadri@gmail.com', 'qadri123', 'Hyderabad Pakistan', '0377474744', NULL, 'active', '2026-04-15 08:39:43'),
(81, 'Aarish Ali', 'ali@gmail110.com', '74430826', 'Hyderabad Pakistan', '+9234344554', NULL, 'active', '2026-04-18 06:19:37'),
(83, 'Aarish Ali', 'ali77576@gmail.com', '35820129', 'Hyderabad Pakistan', '+92343447878', NULL, 'active', '2026-04-18 06:25:55'),
(84, 'Aarish Ali', 'ali775764@gmail.com', '58912747', 'Hyderabad Pakistan', '+92343447878', NULL, 'active', '2026-04-18 07:03:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `contact` (`contact`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `hostel_id` (`hostel_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `hostel_id` (`hostel_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostel`
--
ALTER TABLE `hostel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contact` (`contact`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hostel_id` (`hostel_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hostel`
--
ALTER TABLE `hostel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`hostel_id`) REFERENCES `hostel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`hostel_id`) REFERENCES `hostel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hostel`
--
ALTER TABLE `hostel`
  ADD CONSTRAINT `hostel_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`hostel_id`) REFERENCES `hostel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
