-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2024 at 05:15 PM
-- Server version: 10.4.33-MariaDB-log
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartpayrollsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `HoursWorked` decimal(5,2) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `EmployeeID`, `Date`, `HoursWorked`, `Status`) VALUES
(1, 12, '2024-12-11', 5.00, 'present'),
(2, 12, '2024-12-11', 5.00, 'present');

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `DeductionID` int(11) NOT NULL,
  `PayrollID` int(11) DEFAULT NULL,
  `Tax` decimal(10,2) NOT NULL,
  `Insurance` decimal(10,2) NOT NULL,
  `OtherDeductions` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `MiddleName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) NOT NULL,
  `BirthDate` date NOT NULL,
  `Age` int(11) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Sex` varchar(255) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Barangay` varchar(255) NOT NULL,
  `StreetNBuildingHouseNo` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Postal_zip_code` int(11) NOT NULL,
  `Active_ind` tinyint(1) DEFAULT 1,
  `MaxicareType` enum('not_applicable','silver','gold','platinum','platinum_plus') NOT NULL,
  `SalaryLoan_ind` enum('No','Yes') NOT NULL,
  `DateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `PositionID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `FirstName`, `MiddleName`, `LastName`, `BirthDate`, `Age`, `Status`, `Sex`, `Email`, `Phone`, `Barangay`, `StreetNBuildingHouseNo`, `City`, `Postal_zip_code`, `Active_ind`, `MaxicareType`, `SalaryLoan_ind`, `DateAdded`, `PositionID`) VALUES
(1, 'Juliana Mae', NULL, 'llom', '1999-10-10', 23, 'Single', 'Female', 'julinana.mae@example.com', '123-456-7890', 'Gen. T. Deleon', '4339 L. Bernardino st.', 'Valenzuela City', 1442, 1, 'silver', 'No', '2024-12-10 10:20:20', 5),
(2, 'Kyla', NULL, 'Rugayan', '1999-10-10', 23, 'Single', 'Female', 'kyla.rugayan@example.com', '987-654-3210', 'Gen. T. Deleon', '4339 L. Bernardino st.', 'Valenzuela City', 1442, 1, 'silver', 'No', '2024-12-10 10:20:20', 5),
(12, 'f', 'm', 'l', '2024-12-11', 12, 'Married', '0', 'seancvpugosa@gmail.com', '639533180925', 'b', 's', 'c', 1, 1, 'not_applicable', 'No', '2024-12-10 16:08:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `leavebalance`
--

CREATE TABLE `leavebalance` (
  `BalanceID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `LeaveType` varchar(50) NOT NULL,
  `DaysAvailable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leavebalance`
--

INSERT INTO `leavebalance` (`BalanceID`, `EmployeeID`, `LeaveType`, `DaysAvailable`) VALUES
(1, 12, 'Sick Leave', 10),
(2, 12, 'Vacation Leave', 10),
(3, 12, 'Emergency Leave', 10),
(4, 12, 'Paternity Leave', 7);

-- --------------------------------------------------------

--
-- Table structure for table `leavefile`
--

CREATE TABLE `leavefile` (
  `LeaveFileID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `DateUploaded` datetime DEFAULT current_timestamp(),
  `LeaveType` varchar(100) NOT NULL,
  `LeaveStartDate` date NOT NULL,
  `LeaveEndDate` date NOT NULL,
  `indicator` enum('PENDING','APPROVED','REJECTED') NOT NULL,
  `Reason` varchar(1000) DEFAULT NULL,
  `Attachment` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `PayrollID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `PayDate` date NOT NULL,
  `TotalHours` decimal(5,2) NOT NULL,
  `GrossPay` decimal(10,2) NOT NULL,
  `NetPay` decimal(10,2) NOT NULL,
  `SocialSecuritySystem` decimal(5,2) DEFAULT NULL,
  `PagIbig` decimal(5,2) DEFAULT NULL,
  `PhilHealth` decimal(5,2) DEFAULT NULL,
  `Tax` decimal(5,2) DEFAULT NULL,
  `Maxicare` decimal(10,2) DEFAULT NULL,
  `SalaryLoan` decimal(10,2) DEFAULT NULL,
  `OvertimePay` decimal(5,2) DEFAULT NULL,
  `OvertimeHours` int(11) DEFAULT NULL,
  `Flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `PositionID` int(11) NOT NULL,
  `PositionName` varchar(100) NOT NULL,
  `SalaryPosition` decimal(10,2) NOT NULL,
  `Indicator` enum('Fixed','Daily') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`PositionID`, `PositionName`, `SalaryPosition`, `Indicator`) VALUES
(1, 'General Manager', 25000.00, 'Fixed'),
(2, 'Operation Management', 22000.00, 'Fixed'),
(3, 'HR Manager', 25000.00, 'Fixed'),
(4, 'Finance Surpervisor', 22000.00, 'Fixed'),
(5, 'Admin', 22000.00, 'Fixed'),
(6, 'Accounting Staff', 22000.00, 'Fixed'),
(7, 'Collectors', 645.00, 'Daily'),
(8, 'Drivers', 645.00, 'Daily'),
(9, 'Pesticide Handler', 645.00, 'Daily'),
(10, 'Liaison Officer', 645.00, 'Daily'),
(11, 'Office Staff', 645.00, 'Daily'),
(12, 'Supervisor/Team', 645.00, 'Daily');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_maxicare`
--

CREATE TABLE `tbl_maxicare` (
  `maxicareID` int(11) NOT NULL,
  `maxicare_type` varchar(50) DEFAULT NULL,
  `maxicare_age` int(11) DEFAULT NULL,
  `maxicare_annual_amt` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_maxicare`
--

INSERT INTO `tbl_maxicare` (`maxicareID`, `maxicare_type`, `maxicare_age`, `maxicare_annual_amt`) VALUES
(1, 'not_applicable', 6, 0.00),
(2, 'not_applicable', 7, 0.00),
(3, 'not_applicable', 8, 0.00),
(4, 'not_applicable', 9, 0.00),
(5, 'not_applicable', 10, 0.00),
(6, 'not_applicable', 11, 0.00),
(7, 'not_applicable', 12, 0.00),
(8, 'not_applicable', 13, 0.00),
(9, 'not_applicable', 14, 0.00),
(10, 'not_applicable', 15, 0.00),
(11, 'not_applicable', 16, 0.00),
(12, 'not_applicable', 17, 0.00),
(13, 'not_applicable', 18, 0.00),
(14, 'not_applicable', 19, 0.00),
(15, 'not_applicable', 20, 0.00),
(16, 'not_applicable', 21, 0.00),
(17, 'not_applicable', 22, 0.00),
(18, 'not_applicable', 23, 0.00),
(19, 'not_applicable', 24, 0.00),
(20, 'not_applicable', 25, 0.00),
(21, 'not_applicable', 26, 0.00),
(22, 'not_applicable', 27, 0.00),
(23, 'not_applicable', 28, 0.00),
(24, 'not_applicable', 29, 0.00),
(25, 'not_applicable', 30, 0.00),
(26, 'not_applicable', 31, 0.00),
(27, 'not_applicable', 32, 0.00),
(28, 'not_applicable', 33, 0.00),
(29, 'not_applicable', 34, 0.00),
(30, 'not_applicable', 35, 0.00),
(31, 'not_applicable', 36, 0.00),
(32, 'not_applicable', 37, 0.00),
(33, 'not_applicable', 38, 0.00),
(34, 'not_applicable', 39, 0.00),
(35, 'not_applicable', 40, 0.00),
(36, 'not_applicable', 41, 0.00),
(37, 'not_applicable', 42, 0.00),
(38, 'not_applicable', 43, 0.00),
(39, 'not_applicable', 44, 0.00),
(40, 'not_applicable', 45, 0.00),
(41, 'not_applicable', 46, 0.00),
(42, 'not_applicable', 47, 0.00),
(43, 'not_applicable', 48, 0.00),
(44, 'not_applicable', 49, 0.00),
(45, 'not_applicable', 50, 0.00),
(46, 'not_applicable', 51, 0.00),
(47, 'not_applicable', 52, 0.00),
(48, 'not_applicable', 53, 0.00),
(49, 'not_applicable', 54, 0.00),
(50, 'not_applicable', 55, 0.00),
(51, 'not_applicable', 56, 0.00),
(52, 'not_applicable', 57, 0.00),
(53, 'not_applicable', 58, 0.00),
(54, 'not_applicable', 59, 0.00),
(55, 'not_applicable', 60, 0.00),
(56, 'platinum_plus', 6, 56459.00),
(57, 'platinum_plus', 7, 56459.00),
(58, 'platinum_plus', 8, 56459.00),
(59, 'platinum_plus', 9, 56459.00),
(60, 'platinum_plus', 10, 56459.00),
(61, 'platinum_plus', 11, 46526.00),
(62, 'platinum_plus', 12, 46526.00),
(63, 'platinum_plus', 13, 46526.00),
(64, 'platinum_plus', 14, 46526.00),
(65, 'platinum_plus', 15, 46526.00),
(66, 'platinum_plus', 16, 45069.00),
(67, 'platinum_plus', 17, 45069.00),
(68, 'platinum_plus', 18, 45069.00),
(69, 'platinum_plus', 19, 45069.00),
(70, 'platinum_plus', 20, 45069.00),
(71, 'platinum_plus', 21, 44814.00),
(72, 'platinum_plus', 22, 44814.00),
(73, 'platinum_plus', 23, 44814.00),
(74, 'platinum_plus', 24, 44814.00),
(75, 'platinum_plus', 25, 44814.00),
(76, 'platinum_plus', 26, 46526.00),
(77, 'platinum_plus', 27, 46526.00),
(78, 'platinum_plus', 28, 46526.00),
(79, 'platinum_plus', 29, 46526.00),
(80, 'platinum_plus', 30, 46526.00),
(81, 'platinum_plus', 31, 55755.00),
(82, 'platinum_plus', 32, 55755.00),
(83, 'platinum_plus', 33, 55755.00),
(84, 'platinum_plus', 34, 55755.00),
(85, 'platinum_plus', 35, 55755.00),
(86, 'platinum_plus', 36, 70098.00),
(87, 'platinum_plus', 37, 70098.00),
(88, 'platinum_plus', 38, 70098.00),
(89, 'platinum_plus', 39, 70098.00),
(90, 'platinum_plus', 40, 70098.00),
(91, 'platinum_plus', 41, 89036.00),
(92, 'platinum_plus', 42, 89036.00),
(93, 'platinum_plus', 43, 89036.00),
(94, 'platinum_plus', 44, 89036.00),
(95, 'platinum_plus', 45, 89036.00),
(96, 'platinum_plus', 46, 106059.00),
(97, 'platinum_plus', 47, 106059.00),
(98, 'platinum_plus', 48, 106059.00),
(99, 'platinum_plus', 49, 106059.00),
(100, 'platinum_plus', 50, 106059.00),
(101, 'platinum_plus', 51, 119664.00),
(102, 'platinum_plus', 52, 119664.00),
(103, 'platinum_plus', 53, 119664.00),
(104, 'platinum_plus', 54, 119664.00),
(105, 'platinum_plus', 55, 119664.00),
(106, 'platinum_plus', 56, 132136.00),
(107, 'platinum_plus', 57, 132136.00),
(108, 'platinum_plus', 58, 132136.00),
(109, 'platinum_plus', 59, 132136.00),
(110, 'platinum_plus', 60, 132136.00),
(111, 'platinum', 6, 32457.00),
(112, 'platinum', 7, 32457.00),
(113, 'platinum', 8, 32457.00),
(114, 'platinum', 9, 32457.00),
(115, 'platinum', 10, 32457.00),
(116, 'platinum', 11, 26062.00),
(117, 'platinum', 12, 26062.00),
(118, 'platinum', 13, 26062.00),
(119, 'platinum', 14, 26062.00),
(120, 'platinum', 15, 26062.00),
(121, 'platinum', 16, 24068.00),
(122, 'platinum', 17, 24068.00),
(123, 'platinum', 18, 24068.00),
(124, 'platinum', 19, 24068.00),
(125, 'platinum', 20, 24068.00),
(126, 'platinum', 21, 25109.00),
(127, 'platinum', 22, 25109.00),
(128, 'platinum', 23, 25109.00),
(129, 'platinum', 24, 25109.00),
(130, 'platinum', 25, 25109.00),
(131, 'platinum', 26, 27764.00),
(132, 'platinum', 27, 27764.00),
(133, 'platinum', 28, 27764.00),
(134, 'platinum', 29, 27764.00),
(135, 'platinum', 30, 27764.00),
(136, 'platinum', 31, 32908.00),
(137, 'platinum', 32, 32908.00),
(138, 'platinum', 33, 32908.00),
(139, 'platinum', 34, 32908.00),
(140, 'platinum', 35, 32908.00),
(141, 'platinum', 36, 43354.00),
(142, 'platinum', 37, 43354.00),
(143, 'platinum', 38, 43354.00),
(144, 'platinum', 39, 43354.00),
(145, 'platinum', 40, 43354.00),
(146, 'platinum', 41, 58946.00),
(147, 'platinum', 42, 58946.00),
(148, 'platinum', 43, 58946.00),
(149, 'platinum', 44, 58946.00),
(150, 'platinum', 45, 58946.00),
(151, 'platinum', 46, 79548.00),
(152, 'platinum', 47, 79548.00),
(153, 'platinum', 48, 79548.00),
(154, 'platinum', 49, 79548.00),
(155, 'platinum', 50, 79548.00),
(156, 'platinum', 51, 96949.00),
(157, 'platinum', 52, 96949.00),
(158, 'platinum', 53, 96949.00),
(159, 'platinum', 54, 96949.00),
(160, 'platinum', 55, 96949.00),
(161, 'platinum', 56, 109786.00),
(162, 'platinum', 57, 109786.00),
(163, 'platinum', 58, 109786.00),
(164, 'platinum', 59, 109786.00),
(165, 'platinum', 60, 109786.00),
(166, 'gold', 6, 28014.00),
(167, 'gold', 7, 28014.00),
(168, 'gold', 8, 28014.00),
(169, 'gold', 9, 28014.00),
(170, 'gold', 10, 28014.00),
(171, 'gold', 11, 23049.00),
(172, 'gold', 12, 23049.00),
(173, 'gold', 13, 23049.00),
(174, 'gold', 14, 23049.00),
(175, 'gold', 15, 23049.00),
(176, 'gold', 16, 22056.00),
(177, 'gold', 17, 22056.00),
(178, 'gold', 18, 22056.00),
(179, 'gold', 19, 22056.00),
(180, 'gold', 20, 22056.00),
(181, 'gold', 21, 21546.00),
(182, 'gold', 22, 21546.00),
(183, 'gold', 23, 21546.00),
(184, 'gold', 24, 21546.00),
(185, 'gold', 25, 21546.00),
(186, 'gold', 26, 25278.00),
(187, 'gold', 27, 25278.00),
(188, 'gold', 28, 25278.00),
(189, 'gold', 29, 25278.00),
(190, 'gold', 30, 25278.00),
(191, 'gold', 31, 30485.00),
(192, 'gold', 32, 30485.00),
(193, 'gold', 33, 30485.00),
(194, 'gold', 34, 30485.00),
(195, 'gold', 35, 30485.00),
(196, 'gold', 36, 40013.00),
(197, 'gold', 37, 40013.00),
(198, 'gold', 38, 40013.00),
(199, 'gold', 39, 40013.00),
(200, 'gold', 40, 40013.00),
(201, 'gold', 41, 51238.00),
(202, 'gold', 42, 51238.00),
(203, 'gold', 43, 51238.00),
(204, 'gold', 44, 51238.00),
(205, 'gold', 45, 51238.00),
(206, 'gold', 46, 61423.00),
(207, 'gold', 47, 61423.00),
(208, 'gold', 48, 61423.00),
(209, 'gold', 49, 61423.00),
(210, 'gold', 50, 61423.00),
(211, 'gold', 51, 64249.00),
(212, 'gold', 52, 64249.00),
(213, 'gold', 53, 64249.00),
(214, 'gold', 54, 64249.00),
(215, 'gold', 55, 64249.00),
(216, 'gold', 56, 74914.00),
(217, 'gold', 57, 74914.00),
(218, 'gold', 58, 74914.00),
(219, 'gold', 59, 74914.00),
(220, 'gold', 60, 74914.00),
(221, 'silver', 6, 22094.00),
(222, 'silver', 7, 22094.00),
(223, 'silver', 8, 22094.00),
(224, 'silver', 9, 22094.00),
(225, 'silver', 10, 22094.00),
(226, 'silver', 11, 18697.00),
(227, 'silver', 12, 18697.00),
(228, 'silver', 13, 18697.00),
(229, 'silver', 14, 18697.00),
(230, 'silver', 15, 18697.00),
(231, 'silver', 16, 17785.00),
(232, 'silver', 17, 17785.00),
(233, 'silver', 18, 17785.00),
(234, 'silver', 19, 17785.00),
(235, 'silver', 20, 17785.00),
(236, 'silver', 21, 17785.00),
(237, 'silver', 22, 17785.00),
(238, 'silver', 23, 17785.00),
(239, 'silver', 24, 17785.00),
(240, 'silver', 25, 17785.00),
(241, 'silver', 26, 20233.00),
(242, 'silver', 27, 20233.00),
(243, 'silver', 28, 20233.00),
(244, 'silver', 29, 20233.00),
(245, 'silver', 30, 20233.00),
(246, 'silver', 31, 21794.00),
(247, 'silver', 32, 21794.00),
(248, 'silver', 33, 21794.00),
(249, 'silver', 34, 21794.00),
(250, 'silver', 35, 21794.00),
(251, 'silver', 36, 26539.00),
(252, 'silver', 37, 26539.00),
(253, 'silver', 38, 26539.00),
(254, 'silver', 39, 26539.00),
(255, 'silver', 40, 26539.00),
(256, 'silver', 41, 39785.00),
(257, 'silver', 42, 39785.00),
(258, 'silver', 43, 39785.00),
(259, 'silver', 44, 39785.00),
(260, 'silver', 45, 39785.00),
(261, 'silver', 46, 47625.00),
(262, 'silver', 47, 47625.00),
(263, 'silver', 48, 47625.00),
(264, 'silver', 49, 47625.00),
(265, 'silver', 50, 47625.00),
(266, 'silver', 51, 47638.00),
(267, 'silver', 52, 47638.00),
(268, 'silver', 53, 47638.00),
(269, 'silver', 54, 47638.00),
(270, 'silver', 55, 47638.00),
(271, 'silver', 56, 52925.00),
(272, 'silver', 57, 52925.00),
(273, 'silver', 58, 52925.00),
(274, 'silver', 59, 52925.00),
(275, 'silver', 60, 52925.00);

-- --------------------------------------------------------

--
-- Table structure for table `timerecord`
--

CREATE TABLE `timerecord` (
  `RecordID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `CheckInTime` time DEFAULT NULL,
  `CheckOutTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `timerecord`
--

INSERT INTO `timerecord` (`RecordID`, `EmployeeID`, `Date`, `CheckInTime`, `CheckOutTime`) VALUES
(2, 12, '2024-12-11', '00:11:43', '00:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `FaceData` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `PasswordHash`, `Role`, `EmployeeID`, `FaceData`) VALUES
(3, 'seancvpugosa@gmail.com', 'seancvpugosa@gmail.com', 'Employee', 12, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`DeductionID`),
  ADD KEY `PayrollID` (`PayrollID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `PositionID` (`PositionID`);

--
-- Indexes for table `leavebalance`
--
ALTER TABLE `leavebalance`
  ADD PRIMARY KEY (`BalanceID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `leavefile`
--
ALTER TABLE `leavefile`
  ADD PRIMARY KEY (`LeaveFileID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`PayrollID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`PositionID`);

--
-- Indexes for table `tbl_maxicare`
--
ALTER TABLE `tbl_maxicare`
  ADD PRIMARY KEY (`maxicareID`);

--
-- Indexes for table `timerecord`
--
ALTER TABLE `timerecord`
  ADD PRIMARY KEY (`RecordID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `DeductionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `leavebalance`
--
ALTER TABLE `leavebalance`
  MODIFY `BalanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leavefile`
--
ALTER TABLE `leavefile`
  MODIFY `LeaveFileID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `PayrollID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `PositionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_maxicare`
--
ALTER TABLE `tbl_maxicare`
  MODIFY `maxicareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `timerecord`
--
ALTER TABLE `timerecord`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `deductions`
--
ALTER TABLE `deductions`
  ADD CONSTRAINT `deductions_ibfk_1` FOREIGN KEY (`PayrollID`) REFERENCES `payroll` (`PayrollID`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`PositionID`) REFERENCES `position` (`PositionID`);

--
-- Constraints for table `leavebalance`
--
ALTER TABLE `leavebalance`
  ADD CONSTRAINT `leavebalance_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `leavefile`
--
ALTER TABLE `leavefile`
  ADD CONSTRAINT `leavefile_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `timerecord`
--
ALTER TABLE `timerecord`
  ADD CONSTRAINT `timerecord_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
