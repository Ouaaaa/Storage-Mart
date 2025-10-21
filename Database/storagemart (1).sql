-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2025 at 10:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `storagemart`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `account_id` int(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`account_id`, `username`, `password`, `usertype`, `status`, `createdby`, `datecreated`) VALUES
(1, 'admin', '123', 'ADMIN', 'ACTIVE', 'ADMIN', '8/19/2025'),
(8, 'Lester', '123', 'ADMIN', 'INACTIVE', 'admin', '09/17/2025'),
(11, 'sales', '123', 'HR', 'ACTIVE', 'admin', '09/24/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_assignment`
--

CREATE TABLE `tblassets_assignment` (
  `assignment_id` int(11) NOT NULL,
  `employee_id` int(50) NOT NULL,
  `assignedTo` varchar(150) NOT NULL,
  `dateIssued` varchar(50) NOT NULL,
  `transferDetails` varchar(200) NOT NULL,
  `dateReturned` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL,
  `createdby` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblassets_assignment`
--

INSERT INTO `tblassets_assignment` (`assignment_id`, `employee_id`, `assignedTo`, `dateIssued`, `transferDetails`, `dateReturned`, `datecreated`, `createdby`) VALUES
(1, 230005109, 'Ricafort, Roland Josh M.', '08/10/2025', 'From Abueva to Ricafort ', '', '08/10/2025', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_category`
--

CREATE TABLE `tblassets_category` (
  `category_id` int(11) NOT NULL,
  `ic_code` varchar(100) NOT NULL,
  `categoryName` varchar(100) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblassets_category`
--

INSERT INTO `tblassets_category` (`category_id`, `ic_code`, `categoryName`, `createdby`, `datecreated`) VALUES
(1, 'OE', 'Office Equipment', '1', '2025-09-24 13:27:25'),
(2, 'OE', 'Office Equipment', '1', '2025-09-24 13:27:46'),
(3, 'OE', 'Office Equipment', '1', '2025-09-24 13:30:28'),
(4, 'OE', 'Office Equipment', '1', '2025-09-24 13:30:35'),
(5, 'OE', 'Office Equipment', '1', '2025-09-24 13:32:04'),
(6, 'CA', 'Company Attire', '', '2025-09-24 13:55:15'),
(7, 'CA', 'Company Attire', '', '2025-09-24 14:35:43'),
(8, 'CA', 'Company Attire', '', '2025-09-24 14:37:06'),
(9, 'OE', 'Office Equipment', '', '2025-09-24 14:37:56'),
(10, 'OE', 'Office Equipment', '', '2025-09-24 14:40:18'),
(11, 'OE', 'Office Equipment', '', '2025-09-24 14:42:40'),
(12, 'OE', 'Office Equipment', 'admin', '2025-09-24 14:44:48'),
(13, 'FF', 'Fixture & Furniture', 'admin', '2025-09-25 09:06:40'),
(14, 'CM', 'Communication', 'admin', '2025-09-25 17:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_directory`
--

CREATE TABLE `tblassets_directory` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `ic_code` varchar(100) NOT NULL,
  `itemNumber` varchar(20) NOT NULL,
  `itemInfo` varchar(300) NOT NULL,
  `itemModel` varchar(100) NOT NULL,
  `serialNumber` varchar(50) NOT NULL,
  `itemCount` int(11) NOT NULL,
  `status` enum('ACTIVE','DISPOSED','LOST') NOT NULL,
  `year_purchased` year(4) NOT NULL,
  `datecreated` varchar(50) NOT NULL,
  `createdby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblassets_directory`
--

INSERT INTO `tblassets_directory` (`item_id`, `category_id`, `ic_code`, `itemNumber`, `itemInfo`, `itemModel`, `serialNumber`, `itemCount`, `status`, `year_purchased`, `datecreated`, `createdby`) VALUES
(1, 2, 'OE', 'OE-24-001', 'asdad', 'adas', '', 1, 'DISPOSED', '2024', '2025-09-24 16:51:50', 'admin'),
(2, 1, 'OE', 'OE-24001', 'asdsa', 'dasdas', '', 1, 'LOST', '2024', '2025-09-24 17:21:24', 'admin'),
(3, 11, 'OE', 'OE-24001', 'asdasda', 'sdasdasd', '', 1, 'ACTIVE', '2024', '2025-09-24 17:25:35', 'admin'),
(4, 2, 'OE', 'OE-24004', 'asdasd', 'adsada', '', 4, 'ACTIVE', '2024', '2025-09-24 17:32:36', 'admin'),
(5, 6, 'CA', 'CA-24005', 'asdas', 'dasdsa', '', 5, 'ACTIVE', '2024', '2025-09-24 17:33:27', 'admin'),
(6, 7, 'CA', 'CA-24006', 'asdsa', 'dasdad', '', 6, 'ACTIVE', '2024', '2025-09-24 17:56:11', 'admin'),
(7, 7, 'CA', 'CA-24007', 'asdsa', 'dasdad', '', 7, 'DISPOSED', '2024', '2025-09-24 17:59:35', 'admin'),
(8, 13, 'FF', 'FF-24008', 'LED Flat Round Pannel', 'asdsad', '', 8, 'ACTIVE', '2024', '2025-09-25 09:07:13', 'admin'),
(9, 14, 'CM', 'CM-25009', 'Mobile Phone - Samsung', 'Galaxy S24', '', 9, 'ACTIVE', '2025', '2025-09-25 17:41:34', 'admin'),
(10, 4, 'OE', 'OE-24010', 'asd', 'asdasd', '', 10, 'ACTIVE', '2024', '2025-10-02 11:37:49', 'admin'),
(11, 13, 'FF', 'FF-24011', 'asd', 'asdsad', '', 11, 'ACTIVE', '2024', '2025-10-02 11:38:11', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_group`
--

CREATE TABLE `tblassets_group` (
  `group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `ic_code` varchar(20) NOT NULL,
  `groupName` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `datecreated` varchar(20) NOT NULL,
  `createdby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblassets_group`
--

INSERT INTO `tblassets_group` (`group_id`, `category_id`, `ic_code`, `groupName`, `description`, `datecreated`, `createdby`) VALUES
(1, 11, 'OE', 'Lenovo-Laptop', 'Lenovo Laptop Intel Core I5', '10/21/25', 'admin'),
(2, 13, 'FF', 'Asus - Gaming Chair ', 'Gaming Chair 123123', '2025-10-21 13:52:03', 'admin'),
(3, 6, 'CA', 'Test Group', 'TEST \r\nTest Description TRY', '2025-10-21 14:16:32', 'admin'),
(4, 5, 'OE', 'Test', 'Hello', '2025-10-21 15:12:14', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_inventory`
--

CREATE TABLE `tblassets_inventory` (
  `inventory_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `serialNumber` varchar(100) NOT NULL,
  `itemInfo` varchar(150) NOT NULL,
  `status` varchar(30) NOT NULL,
  `assetCode` varchar(200) NOT NULL,
  `assetNumber` varchar(200) NOT NULL,
  `datecreated` varchar(50) NOT NULL,
  `createdby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblassets_inventory`
--

INSERT INTO `tblassets_inventory` (`inventory_id`, `assignment_id`, `employee_id`, `branch_id`, `group_id`, `serialNumber`, `itemInfo`, `status`, `assetCode`, `assetNumber`, `datecreated`, `createdby`) VALUES
(1, 1, 230005109, 1, 1, '', '', 'ASSIGNED', '001', 'OE-24001-HOS001', '08/10/2025', 'admin'),
(2, 1, 1, 1, 2, 'SN-1234', 'Gaming Chair ASUS', 'UNASSIGNED', '002', 'HOS-237977', '10/21/25', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblbranch`
--

CREATE TABLE `tblbranch` (
  `branch_id` int(11) NOT NULL,
  `branchCode` varchar(20) NOT NULL,
  `branchName` varchar(100) NOT NULL,
  `branchAddress` varchar(150) NOT NULL,
  `datecreated` varchar(50) NOT NULL,
  `createdby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbranch`
--

INSERT INTO `tblbranch` (`branch_id`, `branchCode`, `branchName`, `branchAddress`, `datecreated`, `createdby`) VALUES
(1, 'HOS', 'Head Office', 'Iran', '08/10/2025', 'admin'),
(2, 'DAR', 'Don Roces', 'StorageMart Don A Roces Storage Space Rental Quezon City Philippines', '2025-10-08 13:34:22', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblemployee`
--

CREATE TABLE `tblemployee` (
  `employee_id` int(50) NOT NULL,
  `account_id` int(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblemployee`
--

INSERT INTO `tblemployee` (`employee_id`, `account_id`, `lastname`, `firstname`, `middlename`, `department`, `position`, `branch`, `email`, `createdby`, `datecreated`) VALUES
(1, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'IT', '', 'ERAN', 'maedandoy04@gmail.com', 'ADMIN', '8/20/2025'),
(123123, 8, '123', '213', '213', 'IT', '', 'ERAN', 'lehzter@gmail.com', 'admin', '09/17/2025'),
(230005109, 11, 'Abueva', 'Ann Mercy', 'Faura', 'IT', '', 'ERAN', 'itstoragemart@gmail.com', 'admin', '09/24/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `datelog` varchar(20) NOT NULL,
  `timelog` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `module` varchar(50) NOT NULL,
  `ID` varchar(20) NOT NULL,
  `performedby` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbllogs`
--

INSERT INTO `tbllogs` (`datelog`, `timelog`, `action`, `module`, `ID`, `performedby`) VALUES
('08/21/2025', '12:11:34am', 'Create', 'Employee Management', '5', 'admin'),
('08/21/2025', '12:16:17am', 'Create', 'Employee Management', '6', 'admin'),
('08/21/2025', '12:21:51am', 'Create', 'Employee Management', '7', 'admin'),
('2025-09-03', '13:45:22', 'Create', 'Ticket Management', '2', '1'),
('2025-09-03', '13:46:24', 'Create', 'Ticket Management', '3', '1'),
('2025-09-03', '14:07:37', 'Create', 'Ticket Management', '4', '1'),
('2025-09-03', '14:36:57', 'Create', 'Ticket Management', '5', ''),
('2025-09-03', '14:43:27', 'Create', 'Ticket Management', '6', ''),
('2025-09-03', '14:47:13', 'Create', 'Ticket Management', '7', '6'),
('09/17/2025', '10:13:19am', 'Create', 'Employee Management', '8', 'admin'),
('09/17/2025', '10:35:09am', 'Create', 'Employee Management', '0', 'admin'),
('09/17/2025', '10:36:19am', 'Create', 'Employee Management', '0', 'admin'),
('09/17/2025', '02:09:04pm', 'Create', 'Employee Management', '0', 'admin'),
('2025-09-17', '14:11:35', 'Create', 'Ticket Management', '8', '1'),
('09/17/2025', '02:25:13pm', 'Create', 'Employee Management', '0', 'admin'),
('2025-09-17', '02:52:53pm', 'Updated an Account', 'Employee Management', '10', 'admin'),
('2025-09-17', '02:53:01pm', 'Updated an Account', 'Employee Management', '123213123', 'admin'),
('2025-09-17', '02:53:10pm', 'Updated an Account', 'Employee Management', '123213123', 'admin'),
('2025-09-17', '02:53:31pm', 'Updated an Account', 'Employee Management', '123213123', 'admin'),
('2025-09-17', '03:26:16pm', 'Updated an Account', 'Employee Management', '123213123', 'admin'),
('2025-09-17', '03:26:57pm', 'Updated an Account', 'Employee Management', '123213123', 'admin'),
('2025-09-17', '03:33:02pm', 'Updated an Account', 'Employee Management', '123213123', 'admin'),
('2025-09-17', '03:33:15pm', 'Updated an Account', 'Employee Management', '123213123', 'admin'),
('2025-09-17', '03:44:26pm', 'Deleted an Account', 'Employee Management', '6', 'admin'),
('2025-09-17', '03:45:12pm', 'Updated an Account', 'Employee Management', '1', 'admin'),
('2025-09-17', '03:45:26pm', 'Deleted an Account', 'Employee Management', '5', 'admin'),
('2025-09-17', '04:31:38pm', 'Updated an Account', 'Employee Management', '123123', 'admin'),
('2025-09-17', '17:53:45', 'Create', 'Ticket Management', '9', '1'),
('2025-09-17', '06:02:38pm', 'Create', 'Ticket Management', '10', '1'),
('2025-09-24', '09:47:44am', 'Create', 'Ticket Management', '11', '1'),
('09/24/2025', '10:00:12am', 'Create', 'Employee Management', '0', 'admin'),
('2025-09-24', '13:32:04', 'Create', 'Asset Management', '5', '1'),
('2025-09-24', '13:55:15', 'Create', 'Asset Management', '6', ''),
('2025-09-24', '14:37:06', 'Create', 'Asset Management', '1', ''),
('2025-09-24', '14:37:56', 'Create', 'Asset Management', '1', ''),
('2025-09-24', '14:40:18', 'Create', 'Asset Management', '1', ''),
('2025-09-24', '14:42:40', 'Create', 'Asset Management', '1', ''),
('2025-09-24', '14:44:48', 'Create', 'Asset Management', '1', 'admin'),
('2025-09-24', '17:59:35', 'Create Item', 'Asset Management', '1', 'admin'),
('2025-09-25', '09:06:40', 'Create Category', 'Asset Management', '1', 'admin'),
('2025-09-25', '09:07:13', 'Create Item', 'Asset Management', '1', 'admin'),
('2025-09-25', '17:40:55', 'Create Category', 'Asset Management', '1', 'admin'),
('2025-09-25', '17:41:34', 'Create Item', 'Asset Management', '1', 'admin'),
('2025-09-30', '02:54:17pm', 'Updated an Asset Dir', 'Asset Management', '<br />\r\n<b>Warning</', ''),
('2025-09-30', '02:57:19pm', 'Updated an Asset Dir', 'Asset Management', '<br />\r\n<b>Warning</', ''),
('2025-09-30', '02:58:47pm', 'Updated an Asset Dir', 'Asset Management', '<br />\r\n<b>Warning</', ''),
('2025-09-30', '03:03:16pm', 'Update Directory', 'Asset Management', '<br />\r\n<b>Warning</', ''),
('2025-09-30', '03:21:15pm', 'Update Directory', 'Asset Management', '1', 'admin'),
('2025-09-30', '03:21:52pm', 'Update Directory', 'Asset Management', '1', 'admin'),
('2025-09-30', '03:22:54pm', 'Update Directory', 'Asset Management', '1', 'admin'),
('2025-10-02', '11:37:49', 'Create Item', 'Asset Management', '1', 'admin'),
('2025-10-02', '11:38:11', 'Create Item', 'Asset Management', '1', 'admin'),
('2025-10-08', '13:34:22', 'Create Branch', 'Branch Management', '1', ''),
('2025-10-21', '13:52:03', 'Create Group', 'Group Asset Management', '1', 'admin'),
('2025-10-21', '14:16:32', 'Create Group', 'Group Asset Management', '1', 'admin'),
('2025-10-21', '02:52:58pm', 'Update Asset Group', 'Group Asset Management', '1', 'admin'),
('2025-10-21', '02:53:14pm', 'Update Asset Group', 'Group Asset Management', '1', 'admin'),
('2025-10-21', '02:53:27pm', 'Update Asset Group', 'Group Asset Management', '1', 'admin'),
('2025-10-21', '15:12:14', 'Create Group', 'Group Asset Management', '1', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbltickets`
--

CREATE TABLE `tbltickets` (
  `ticket_id` int(100) NOT NULL,
  `employee_id` int(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `ticket_assign` varchar(100) NOT NULL,
  `technical_purpose` varchar(150) NOT NULL,
  `concern_details` varchar(500) NOT NULL,
  `action` varchar(500) NOT NULL,
  `result` varchar(500) NOT NULL,
  `status` varchar(100) NOT NULL,
  `priority` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `datecreated` varchar(100) NOT NULL,
  `dateupdated` varchar(100) NOT NULL,
  `attachments` varchar(500) NOT NULL,
  `remarks` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbltickets`
--

INSERT INTO `tbltickets` (`ticket_id`, `employee_id`, `lastname`, `firstname`, `middlename`, `branch`, `department`, `ticket_assign`, `technical_purpose`, `concern_details`, `action`, `result`, `status`, `priority`, `category`, `created_by`, `datecreated`, `dateupdated`, `attachments`, `remarks`) VALUES
(1, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'Eran', 'IT', 'Josh', 'laptop', 'BSOD', 'reset', 'not BSOD anymore', 'closed', 'High', 'Software,Hardware', 'Employee', '8/27/2025', '8/27/2025', '', 'none'),
(5, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'ERAN', 'IT', '6', 'CCTV & MAINTAINANCE', 'asdasd                           ', 'asdasd', 'asdasd    ', 'PENDING', 'low', 'Software,Hardware', '', '2025-09-03 14:36:57', '', '', 'asdasdas                                   '),
(6, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'ERAN', 'IT', '6', 'CCTV & MAINTAINANCE', 'adasd   ', 'asdasd                 ', 'dasd                 ', 'PENDING', 'medium', 'Software,Hardware', '', '2025-09-03 14:43:27', '', '', 'asdasda                           '),
(7, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'ERAN', 'IT', '6', 'CCTV & MAINTAINANCE', 'asdasd                      ', 'asdasd\r\n                                                ', 'asdasdas   ', 'Approved', 'low', 'Software,Hardware', '3', '2025-09-03 14:47:13', '', '', 'asdasdas                                     '),
(9, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'ERAN', 'IT', '1', 'CCTV & MAINTAINANCE', '\r\n                     asdasdasd                           ', 'asdasd\r\n                                                ', '\r\n              dasdsadsadasdassa                                  ', 'Approved', 'low', 'Software,Hardware', '1', '2025-09-17 17:53:45', '', '', '\r\n           asdsadsad                                     '),
(10, 123123, '123', '213', '213', 'ERAN', 'IT', '1', 'CCTV & MAINTAINANCE', 'asdadasd                                ', 'adasds\r\n                                                ', 'asdasdasdd  a                            ', 'PENDING', 'low', 'Software,Hardware', '1', '2025-09-17 18:02:38', '', '', 'asdasdasa                            '),
(11, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'ERAN', 'IT', '1', 'CCTV & MAINTAINANCE', 'das', 'dasdad', 'asdasd           ', 'Declined', 'low', 'Software,Hardware', '1', '2025-09-24 09:47:44', '', '', 'asdasda\r\n                                                ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `tblassets_assignment`
--
ALTER TABLE `tblassets_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `employeeID` (`employee_id`);

--
-- Indexes for table `tblassets_category`
--
ALTER TABLE `tblassets_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tblassets_directory`
--
ALTER TABLE `tblassets_directory`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tblassets_group`
--
ALTER TABLE `tblassets_group`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `assetCategory_id` (`category_id`);

--
-- Indexes for table `tblassets_inventory`
--
ALTER TABLE `tblassets_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `assetEmployee_id` (`employee_id`),
  ADD KEY `fk_inventory_branch` (`branch_id`),
  ADD KEY `fk_inventory_group` (`group_id`);

--
-- Indexes for table `tblbranch`
--
ALTER TABLE `tblbranch`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `tblemployee`
--
ALTER TABLE `tblemployee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `tbltickets`
--
ALTER TABLE `tbltickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  MODIFY `account_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblassets_assignment`
--
ALTER TABLE `tblassets_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblassets_category`
--
ALTER TABLE `tblassets_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblassets_directory`
--
ALTER TABLE `tblassets_directory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblassets_group`
--
ALTER TABLE `tblassets_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblassets_inventory`
--
ALTER TABLE `tblassets_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblbranch`
--
ALTER TABLE `tblbranch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbltickets`
--
ALTER TABLE `tbltickets`
  MODIFY `ticket_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblassets_assignment`
--
ALTER TABLE `tblassets_assignment`
  ADD CONSTRAINT `employeeID` FOREIGN KEY (`employee_id`) REFERENCES `tblemployee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblassets_directory`
--
ALTER TABLE `tblassets_directory`
  ADD CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `tblassets_category` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tblassets_group`
--
ALTER TABLE `tblassets_group`
  ADD CONSTRAINT `fk_group_category` FOREIGN KEY (`category_id`) REFERENCES `tblassets_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblassets_inventory`
--
ALTER TABLE `tblassets_inventory`
  ADD CONSTRAINT `assetEmployee_id` FOREIGN KEY (`employee_id`) REFERENCES `tblemployee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assignment_id` FOREIGN KEY (`assignment_id`) REFERENCES `tblassets_assignment` (`assignment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_branch` FOREIGN KEY (`branch_id`) REFERENCES `tblbranch` (`branch_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inventory_group` FOREIGN KEY (`group_id`) REFERENCES `tblassets_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblemployee`
--
ALTER TABLE `tblemployee`
  ADD CONSTRAINT `account_id` FOREIGN KEY (`account_id`) REFERENCES `tblaccounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbltickets`
--
ALTER TABLE `tbltickets`
  ADD CONSTRAINT `employee_id` FOREIGN KEY (`employee_id`) REFERENCES `tblemployee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
