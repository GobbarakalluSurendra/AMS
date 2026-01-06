-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 06, 2026 at 06:59 PM
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
-- Database: `attendancemsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `student_requests`
--

CREATE TABLE `student_requests` (
  `request_id` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `admissionNumber` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `classId` varchar(10) DEFAULT NULL,
  `classArmId` varchar(10) DEFAULT NULL,
  `dateCreated` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `email`, `password`, `role`) VALUES
(101, 'Bhasakara', 'Rao', 'admin@mail.com', 'Chinnu', 'HOD');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance_btech`
--

CREATE TABLE `tblattendance_btech` (
  `Id` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `subjectId` int(11) DEFAULT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `period` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblattendance_btech`
--

INSERT INTO `tblattendance_btech` (`Id`, `studentId`, `subjectId`, `teacherId`, `period`, `date`, `status`) VALUES
(13, 30, 5, 1, 1, '2026-01-06', 1),
(14, 29, 5, 1, 1, '2026-01-06', 0),
(15, 28, 5, 1, 1, '2026-01-06', 1),
(16, 30, 5, 1, 2, '2026-01-06', 1),
(17, 29, 5, 1, 2, '2026-01-06', 0),
(18, 28, 5, 1, 2, '2026-01-06', 1),
(19, 30, 5, 1, 1, '2026-01-07', 1),
(20, 29, 5, 1, 1, '2026-01-07', 1),
(21, 28, 5, 1, 1, '2026-01-07', 1),
(22, 30, 5, 1, 2, '2026-01-07', 1),
(23, 29, 5, 1, 2, '2026-01-07', 1),
(24, 28, 5, 1, 2, '2026-01-07', 1),
(25, 27, 5, 2, 1, '2026-01-06', 1),
(26, 26, 5, 2, 1, '2026-01-06', 1),
(27, 25, 5, 2, 1, '2026-01-06', 0),
(28, 27, 5, 2, 2, '2026-01-06', 1),
(29, 26, 5, 2, 2, '2026-01-06', 1),
(30, 25, 5, 2, 2, '2026-01-06', 0),
(31, 27, 5, 2, 2, '2026-01-07', 0),
(32, 26, 5, 2, 2, '2026-01-07', 1),
(33, 25, 5, 2, 2, '2026-01-07', 1),
(34, 27, 5, 2, 3, '2026-01-07', 0),
(35, 26, 5, 2, 3, '2026-01-07', 1),
(36, 25, 5, 2, 3, '2026-01-07', 1),
(37, 23, 5, 3, 1, '2026-01-06', 1),
(38, 20, 5, 3, 1, '2026-01-06', 1),
(39, 21, 5, 3, 1, '2026-01-06', 0),
(40, 22, 5, 3, 1, '2026-01-06', 1),
(41, 24, 5, 3, 1, '2026-01-06', 1),
(42, 23, 5, 3, 2, '2026-01-06', 1),
(43, 20, 5, 3, 2, '2026-01-06', 1),
(44, 21, 5, 3, 2, '2026-01-06', 0),
(45, 22, 5, 3, 2, '2026-01-06', 1),
(46, 24, 5, 3, 2, '2026-01-06', 1),
(47, 23, 5, 3, 1, '2026-01-07', 1),
(48, 20, 5, 3, 1, '2026-01-07', 1),
(49, 21, 5, 3, 1, '2026-01-07', 1),
(50, 22, 5, 3, 1, '2026-01-07', 0),
(51, 24, 5, 3, 1, '2026-01-07', 1),
(52, 23, 5, 3, 2, '2026-01-07', 1),
(53, 20, 5, 3, 2, '2026-01-07', 1),
(54, 21, 5, 3, 2, '2026-01-07', 1),
(55, 22, 5, 3, 2, '2026-01-07', 0),
(56, 24, 5, 3, 2, '2026-01-07', 1),
(57, 30, 4, 4, 1, '2026-01-06', 1),
(58, 29, 4, 4, 1, '2026-01-06', 1),
(59, 28, 4, 4, 1, '2026-01-06', 1),
(60, 30, 4, 4, 2, '2026-01-06', 1),
(61, 29, 4, 4, 2, '2026-01-06', 1),
(62, 28, 4, 4, 2, '2026-01-06', 1),
(63, 30, 4, 4, 4, '2026-01-07', 1),
(64, 29, 4, 4, 4, '2026-01-07', 1),
(65, 28, 4, 4, 4, '2026-01-07', 1),
(66, 30, 4, 4, 5, '2026-01-07', 1),
(67, 29, 4, 4, 5, '2026-01-07', 1),
(68, 28, 4, 4, 5, '2026-01-07', 1),
(69, 27, 4, 5, 3, '2026-01-06', 1),
(70, 26, 4, 5, 3, '2026-01-06', 1),
(71, 25, 4, 5, 3, '2026-01-06', 1),
(72, 27, 4, 5, 4, '2026-01-06', 1),
(73, 26, 4, 5, 4, '2026-01-06', 1),
(74, 25, 4, 5, 4, '2026-01-06', 1),
(75, 27, 4, 5, 3, '2026-01-07', 0),
(76, 26, 4, 5, 3, '2026-01-07', 1),
(77, 25, 4, 5, 3, '2026-01-07', 1),
(78, 27, 4, 5, 4, '2026-01-07', 0),
(79, 26, 4, 5, 4, '2026-01-07', 1),
(80, 25, 4, 5, 4, '2026-01-07', 1),
(81, 23, 4, 6, 3, '2026-01-06', 0),
(82, 20, 4, 6, 3, '2026-01-06', 1),
(83, 21, 4, 6, 3, '2026-01-06', 1),
(84, 22, 4, 6, 3, '2026-01-06', 1),
(85, 24, 4, 6, 3, '2026-01-06', 1),
(86, 23, 4, 6, 4, '2026-01-06', 0),
(87, 20, 4, 6, 4, '2026-01-06', 1),
(88, 21, 4, 6, 4, '2026-01-06', 1),
(89, 22, 4, 6, 4, '2026-01-06', 1),
(90, 24, 4, 6, 4, '2026-01-06', 1),
(91, 23, 4, 6, 3, '2026-01-07', 1),
(92, 20, 4, 6, 3, '2026-01-07', 0),
(93, 21, 4, 6, 3, '2026-01-07', 1),
(94, 22, 4, 6, 3, '2026-01-07', 1),
(95, 24, 4, 6, 3, '2026-01-07', 1),
(96, 23, 4, 6, 4, '2026-01-07', 1),
(97, 20, 4, 6, 4, '2026-01-07', 0),
(98, 21, 4, 6, 4, '2026-01-07', 1),
(99, 22, 4, 6, 4, '2026-01-07', 1),
(100, 24, 4, 6, 4, '2026-01-07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblchapters`
--

CREATE TABLE `tblchapters` (
  `Id` int(11) NOT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `chapterName` varchar(255) DEFAULT NULL,
  `fileName` varchar(255) DEFAULT NULL,
  `fileType` varchar(50) DEFAULT NULL,
  `uploadedOn` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblchapters`
--

INSERT INTO `tblchapters` (`Id`, `teacherId`, `subject`, `chapterName`, `fileName`, `fileType`, `uploadedOn`) VALUES
(5, 2, 'Machine Learning', 'unit-1', '1767717345_ML UNIT 1.pdf', 'pdf', '2026-01-06'),
(6, 2, 'Machine Learning', 'Unit-2', '1767717365_ML Unit-2.pdf', 'pdf', '2026-01-06'),
(7, 2, 'Machine Learning', 'Unit-3', '1767718167_unit 3 ml.pdf', 'pdf', '2026-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `Id` int(10) NOT NULL,
  `className` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`Id`, `className`) VALUES
(6, '3RD YEAR_CSE'),
(5, '3RD YEAR-CSEDS');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassarms`
--

CREATE TABLE `tblclassarms` (
  `Id` int(10) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmName` varchar(255) NOT NULL,
  `isAssigned` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassarms`
--

INSERT INTO `tblclassarms` (`Id`, `classId`, `classArmName`, `isAssigned`) VALUES
(7, '5', 'A', '0'),
(8, '5', 'B', '0'),
(9, '5', 'C', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tblfaculty_subject`
--

CREATE TABLE `tblfaculty_subject` (
  `Id` int(11) NOT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `subjectId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfaculty_subject`
--

INSERT INTO `tblfaculty_subject` (`Id`, `teacherId`, `subjectId`) VALUES
(3, 4, 4),
(4, 5, 4),
(5, 6, 4),
(6, 3, 5),
(7, 1, 5),
(8, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionterm`
--

CREATE TABLE `tblsessionterm` (
  `Id` int(10) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `admissionNumber` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`Id`, `firstName`, `lastName`, `admissionNumber`, `password`, `classId`, `classArmId`, `dateCreated`) VALUES
(30, 'G', 'Suguna', '23091A3203', '12345', '5', '7', '2026-01-06'),
(29, 'G', 'Sowmya', '23091A3202', '12345', '5', '7', '2026-01-06'),
(28, 'Sri', 'Vardhan', '23091A3201', '12345', '5', '7', '2026-01-06'),
(27, 'B.', 'Uday Shankar Reddy', '23091A32H3', '12345', '5', '8', '2026-01-06'),
(26, 'MUNUGALA', 'UDAY', '23091A32H0', '12345', '5', '8', '2026-01-06'),
(25, 'S', 'Deekshitha', '23091A32J0', '12345', '5', '8', '2026-01-06'),
(24, 'k Venkata', 'Sai kathayani', '23091A32J2', '12345', '5', '9', '2026-01-06'),
(23, 'G', 'Venkatesh', '23091A32J5', '12345', '5', '9', '2026-01-06'),
(22, 'Gorlla ', 'SAINATH', '23091A32J3', '12345', '5', '9', '2026-01-06'),
(20, 'Gobbarakallu', 'Surendra', '23091A32F9', '12345', '5', '9', '2026-01-06'),
(21, 'Golla', 'Sujith Yadav', '23091A32F4', '12345', '5', '9', '2026-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_teacher`
--

CREATE TABLE `tblstudent_teacher` (
  `Id` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `teacherId` int(11) NOT NULL,
  `subjectId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent_teacher`
--

INSERT INTO `tblstudent_teacher` (`Id`, `studentId`, `teacherId`, `subjectId`, `createdAt`) VALUES
(13, 23, 6, 4, '2026-01-06 17:31:32'),
(14, 20, 6, 4, '2026-01-06 17:31:39'),
(15, 21, 6, 4, '2026-01-06 17:31:44'),
(16, 22, 6, 4, '2026-01-06 17:31:51'),
(17, 24, 6, 4, '2026-01-06 17:32:03'),
(18, 23, 3, 5, '2026-01-06 17:32:13'),
(19, 20, 3, 5, '2026-01-06 17:32:20'),
(20, 21, 3, 5, '2026-01-06 17:32:29'),
(21, 22, 3, 5, '2026-01-06 17:32:43'),
(22, 24, 3, 5, '2026-01-06 17:32:49'),
(23, 27, 5, 4, '2026-01-06 17:35:32'),
(24, 26, 5, 4, '2026-01-06 17:35:43'),
(25, 25, 5, 4, '2026-01-06 17:35:50'),
(26, 27, 2, 5, '2026-01-06 17:35:59'),
(27, 26, 2, 5, '2026-01-06 17:36:07'),
(28, 25, 2, 5, '2026-01-06 17:36:13'),
(29, 30, 4, 4, '2026-01-06 17:38:26'),
(30, 30, 1, 5, '2026-01-06 17:38:31'),
(31, 29, 4, 4, '2026-01-06 17:38:38'),
(32, 29, 1, 5, '2026-01-06 17:38:47'),
(33, 28, 4, 4, '2026-01-06 17:38:55'),
(34, 28, 1, 5, '2026-01-06 17:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `Id` int(11) NOT NULL,
  `subjectCode` varchar(20) DEFAULT NULL,
  `subjectName` varchar(100) DEFAULT NULL,
  `classId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`Id`, `subjectCode`, `subjectName`, `classId`) VALUES
(4, NULL, 'FULL STACK DEVLEPMENT-1', 5),
(5, NULL, 'DEEP LEARNING', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher`
--

CREATE TABLE `tblteacher` (
  `teacher_id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblteacher`
--

INSERT INTO `tblteacher` (`teacher_id`, `employee_id`, `full_name`, `email`, `phone`, `gender`, `qualification`, `department`, `password`, `status`, `created_at`) VALUES
(1, '111', 'Mr. G Viswanath', 'Viswanath@gmail.com', '09347040429', 'Male', 'M.Tech', 'CSEDS', '$2y$10$V8fULfmxa1QgpPggrwqOXezRWIdVEHbOMeLFiQli/I2F0fcS8.bqq', 'Active', '2026-01-06 13:19:19'),
(2, '101', 'Mr Shakeer', 'Shakeer@gmail.com', '09059416816', 'Male', 'M.Tech', 'CSEDS', '$2y$10$5pUIqjUQpRwYD7hiI90.duFD2suVBp7N2OmPpPhwYwACXG85e3tPu', 'Active', '2026-01-06 13:33:07'),
(3, 'EMP6130', 'Mr Penchal Prasad', 'Penchal@gmail.com', '09059416816', 'Male', 'M.Tech & Ph.D', 'CSEDS', '$2y$10$eAYLzr9OUhLt56xhQJw8yewfWACSNlVdPIAE12tPF.jNTwAo6BTLq', 'Active', '2026-01-06 16:53:47'),
(4, '102', 'Dr. K. Rangaswamy', 'Rangaswamy@gmail.com', '09347040429', 'Male', 'M.Tech & Ph.D', 'CSEDS', '$2y$10$LO209YXQFprp.KkMOPWquuFVM7Yu5oiWusX0N4/zewZv9yTO7BvpS', 'Active', '2026-01-06 17:23:28'),
(5, '103', 'Dr. M. Suleman Basha', 'Suleman@gmail.com', '09347040429', 'Male', 'M.Tech & Ph.D', 'CSEDS', '$2y$10$K.yQWh5e0HCFOZbHflCeD.HW.taJCVvGmXxvSMOBQ3sYZGwGIyVIi', 'Active', '2026-01-06 17:24:31'),
(6, '104', 'Dr. P Kiran Rao', 'Kiran@gmail.com', '09347040429', 'Male', 'M.Tech & Ph.D', 'CSEDS', '$2y$10$y5ffLKZ0K29o9Mm3FKdDB.6JMzapAzOmrJic2Wl4TOoM59Hcyj.fW', 'Active', '2026-01-06 17:26:12');

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher_classarm`
--

CREATE TABLE `tblteacher_classarm` (
  `Id` int(11) NOT NULL,
  `teacherId` int(11) NOT NULL,
  `classId` int(11) NOT NULL,
  `classArmId` int(11) NOT NULL,
  `dateAssigned` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblteacher_classarm`
--

INSERT INTO `tblteacher_classarm` (`Id`, `teacherId`, `classId`, `classArmId`, `dateAssigned`) VALUES
(4, 4, 5, 7, '2026-01-06'),
(5, 5, 5, 8, '2026-01-06'),
(6, 3, 5, 9, '2026-01-06'),
(7, 2, 5, 7, '2026-01-06'),
(8, 1, 5, 8, '2026-01-06'),
(9, 6, 5, 9, '2026-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `tblterm`
--

CREATE TABLE `tblterm` (
  `Id` int(10) NOT NULL,
  `termName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_requests`
--

CREATE TABLE `teacher_requests` (
  `request_id` int(11) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_requests`
--
ALTER TABLE `student_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tblattendance_btech`
--
ALTER TABLE `tblattendance_btech`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `unique_attendance` (`studentId`,`date`,`period`,`subjectId`),
  ADD UNIQUE KEY `unique_period_attendance` (`studentId`,`teacherId`,`subjectId`,`date`,`period`);

--
-- Indexes for table `tblchapters`
--
ALTER TABLE `tblchapters`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblfaculty_subject`
--
ALTER TABLE `tblfaculty_subject`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `unique_teacher` (`teacherId`);

--
-- Indexes for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudent_teacher`
--
ALTER TABLE `tblstudent_teacher`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `unique_student_subject` (`studentId`,`subjectId`),
  ADD KEY `fk_student_teacher_subject` (`subjectId`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblteacher`
--
ALTER TABLE `tblteacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tblteacher_classarm`
--
ALTER TABLE `tblteacher_classarm`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `unique_teacher_arm` (`teacherId`,`classId`,`classArmId`),
  ADD UNIQUE KEY `unique_teacher_class` (`teacherId`,`classId`);

--
-- Indexes for table `tblterm`
--
ALTER TABLE `tblterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `teacher_requests`
--
ALTER TABLE `teacher_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_requests`
--
ALTER TABLE `student_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `tblattendance_btech`
--
ALTER TABLE `tblattendance_btech`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tblchapters`
--
ALTER TABLE `tblchapters`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblfaculty_subject`
--
ALTER TABLE `tblfaculty_subject`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblstudent_teacher`
--
ALTER TABLE `tblstudent_teacher`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblteacher_classarm`
--
ALTER TABLE `tblteacher_classarm`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teacher_requests`
--
ALTER TABLE `teacher_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblfaculty_subject`
--
ALTER TABLE `tblfaculty_subject`
  ADD CONSTRAINT `fk_faculty_teacher` FOREIGN KEY (`teacherId`) REFERENCES `tblteacher` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblstudent_teacher`
--
ALTER TABLE `tblstudent_teacher`
  ADD CONSTRAINT `fk_student_teacher_subject` FOREIGN KEY (`subjectId`) REFERENCES `tblsubjects` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `tblteacher_classarm`
--
ALTER TABLE `tblteacher_classarm`
  ADD CONSTRAINT `fk_teacher` FOREIGN KEY (`teacherId`) REFERENCES `tblteacher` (`teacher_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
