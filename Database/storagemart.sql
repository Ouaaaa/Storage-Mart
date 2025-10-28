-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 08:57 AM
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
(2200425, 'josh', '123', 'EMPLOYEE', 'ACTIVE', 'admin', '10/27/2025'),
(2200426, 'test', '123', 'ADMIN', 'ACTIVE', 'josh', '10/27/2025'),
(2200427, 'sm.roseanne', 'Madla@2025', 'HR', 'ACTIVE', 'josh', '10/27/2025'),
(2200428, 'smiran.kenneth', 'Dador@2025', 'IT', 'ACTIVE', 'test', '10/28/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_assignment`
--

CREATE TABLE `tblassets_assignment` (
  `assignment_id` int(11) NOT NULL,
  `employee_id` int(50) DEFAULT NULL,
  `assignedTo` varchar(150) NOT NULL,
  `dateIssued` varchar(50) NOT NULL,
  `transferDetails` varchar(200) NOT NULL,
  `transferCount` varchar(50) NOT NULL,
  `dateReturned` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL,
  `createdby` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblassets_assignment`
--

INSERT INTO `tblassets_assignment` (`assignment_id`, `employee_id`, `assignedTo`, `dateIssued`, `transferDetails`, `transferCount`, `dateReturned`, `datecreated`, `createdby`) VALUES
(39, 2200424, 'Ricafort, Roland Josh Manalo', '2025-10-27', 'New Asset to Mr. Ricafort', '001', '', '2025-10-27 18:40:12', 'josh');

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
(16, 'OE', 'Office Equipment', 'josh', '2025-10-27 18:31:42'),
(17, 'FF', 'Fixture & Furniture', 'josh', '2025-10-27 19:49:19'),
(18, 'OA', 'Other Assets', 'josh', '2025-10-27 19:50:44'),
(19, 'CM', 'Communication', 'josh', '2025-10-27 19:51:38'),
(20, 'IS', 'IT Assets', 'josh', '2025-10-27 19:52:23'),
(21, 'CA', 'Company Attire', 'josh', '2025-10-27 19:52:49');

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
(6, 16, 'OE', 'Laptop', 'Lenovo', '2025-10-27 18:32:24', 'josh');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_inventory`
--

CREATE TABLE `tblassets_inventory` (
  `inventory_id` int(11) NOT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `serialNumber` varchar(100) NOT NULL,
  `itemInfo` varchar(150) NOT NULL,
  `status` varchar(30) NOT NULL,
  `assetCode` varchar(200) NOT NULL,
  `assetNumber` varchar(200) NOT NULL,
  `year_purchased` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL,
  `createdby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblassets_inventory`
--

INSERT INTO `tblassets_inventory` (`inventory_id`, `assignment_id`, `employee_id`, `branch_id`, `group_id`, `serialNumber`, `itemInfo`, `status`, `assetCode`, `assetNumber`, `year_purchased`, `datecreated`, `createdby`) VALUES
(9, 39, 2200424, 1, 6, 'PG01GTB2', 'IdeaPad 3 15IRH8 Slim 3 (83EM000EPH) Intel® Core™ i5 Laptop (Arctic Grey)', 'ASSIGNED', '1', 'OE-25001-HO001', '2025', '2025-10-27 18:38:38', 'josh'),
(10, NULL, NULL, NULL, 6, 'PF510588', 'Ideapad I3 Ultra Slim', 'UNASSIGNED', '2', 'OE-25002', '2025', '2025-10-27 20:03:37', 'josh');

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
(1, 'HO', 'Head Office', '3112 Iran Street, Makati City, 1213 Metro Manila', '10/27/2025', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblemployee`
--

CREATE TABLE `tblemployee` (
  `employee_id` int(50) NOT NULL,
  `account_id` int(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblemployee`
--

INSERT INTO `tblemployee` (`employee_id`, `account_id`, `branch_id`, `lastname`, `firstname`, `middlename`, `department`, `position`, `email`, `createdby`, `datecreated`) VALUES
(2200424, 2200425, 1, 'Ricafort', 'Roland Josh', 'Manalo', 'IT', 'Intern', 'rj.ricafort21@nullsto.edu.pl', 'admin', '10/27/2025'),
(2200425, 2200426, 1, 'Juan', 'DelaCruz', '', 'IT', 'Web Associate', 'rj.ricafort21@nullsto.edu.pl', 'josh', '10/27/2025'),
(202501071, 2200428, 1, 'Dador', 'Kenneth', '', 'IT', 'IT Support Associate', 'storagemart.it@gmail.com', 'test', '10/28/2025'),
(230005486, 2200427, 1, 'Madla', 'Rose Anne', 'Solas', 'IT', 'Head of HRMD', 'roseanne.madla@storagemart.com', 'josh', '10/27/2025');

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
('2025-10-27', '18:15:57', 'Add New Branch', 'Branch Management', '2200424', 'admin'),
('10/27/2025', '06:17:24pm', 'Create', 'Employee Management', '0', 'admin'),
('2025-10-27', '06:17:35pm', 'Deleted an Account', 'Employee Management', '2200424', 'admin'),
('2025-10-27', '18:31:42', 'Create Category', 'Asset Management', '2200425', 'josh'),
('2025-10-27', '18:32:24', 'Create Group', 'Group Asset Management', '6', 'josh'),
('2025-10-27', '18:38:38', 'Added new asset: OE-', 'Asset Inventory', '2200425', 'josh'),
('2025-10-27', '18:40:12', 'Transferred asset OE', 'Asset Inventory', '9', 'josh'),
('10/27/2025', '06:49:57pm', 'Create', 'Employee Management', '0', 'josh'),
('2025-10-27', '19:49:19', 'Create Category', 'Asset Management', '2200425', 'josh'),
('2025-10-27', '19:50:44', 'Create Category', 'Asset Management', '2200425', 'josh'),
('2025-10-27', '19:51:38', 'Create Category', 'Asset Management', '2200425', 'josh'),
('2025-10-27', '19:52:23', 'Create Category', 'Asset Management', '2200425', 'josh'),
('2025-10-27', '19:52:49', 'Create Category', 'Asset Management', '2200425', 'josh'),
('10/27/2025', '07:58:08pm', 'Create', 'Employee Management', '0', 'josh'),
('2025-10-27', '07:58:31pm', 'Updated an Account', 'Employee Management', '2200425', 'josh'),
('2025-10-27', '07:58:38pm', 'Updated an Account', 'Employee Management', '2200425', 'josh'),
('2025-10-27', '20:03:37', 'Added new asset: OE-', 'Asset Inventory', '2200425', 'josh'),
('2025-10-27', '08:05:37pm', 'Updated an Account', 'Employee Management', '2200425', 'josh'),
('2025-10-27', '08:05:53pm', 'Updated an Account', 'Employee Management', '2200424', 'josh'),
('2025-10-27', '08:06:05pm', 'Updated an Account', 'Employee Management', '230005486', 'josh'),
('2025-10-27', '11:17:44pm', 'Create', 'Ticket Management', '1', ''),
('2025-10-28', '12:46:11am', 'Approve', 'Ticket Management', '1', 'test'),
('2025-10-28', '12:53:56am', 'Decline', 'Ticket Management', '1', 'test'),
('2025-10-28', '12:54:14am', 'Decline', 'Ticket Management', '1', 'test'),
('2025-10-28', '12:58:46am', 'Decline', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:01:07am', 'Decline', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:03:01am', 'Approve', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:11:29am', 'Decline', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:16:27am', 'Decline', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:17:16am', 'Approve', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:22:11am', 'Approve', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:22:14am', 'Approve', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:22:29am', 'Approve', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:22:32am', 'Approve', 'Ticket Management', '1', 'test'),
('2025-10-28', '01:23:14am', 'Create', 'Ticket Management', '2', ''),
('2025-10-28', '01:24:33am', 'Decline', 'Ticket Management', '2', 'test'),
('2025-10-28', '01:25:16am', 'Approve', 'Ticket Management', '2', 'test'),
('2025-10-28', '01:34:20am', 'Approve', 'Ticket Management', '2', 'test'),
('2025-10-28', '01:34:23am', 'Approve', 'Ticket Management', '2', 'test'),
('2025-10-28', '01:34:46am', 'Create', 'Ticket Management', '3', ''),
('2025-10-28', '01:34:55am', 'Approve', 'Ticket Management', '3', 'test'),
('2025-10-28', '01:39:18am', 'Create', 'Ticket Management', '4', ''),
('2025-10-28', '01:42:19am', 'Create', 'Ticket Management', '5', ''),
('2025-10-28', '01:43:02am', 'Create', 'Ticket Management', '6', ''),
('2025-10-28', '01:43:12am', 'Decline', 'Ticket Management', '6', 'test'),
('2025-10-28', '04:30:42am', 'Create', 'Ticket Management', '7', ''),
('2025-10-28', '04:41:57am', 'Approve & Assign', 'Ticket Management', '7', 'test'),
('2025-10-28', '04:43:12am', 'Create', 'Ticket Management', '8', ''),
('2025-10-28', '04:43:24am', 'Approve & Assign', 'Ticket Management', '8', 'test'),
('10/28/2025', '04:56:47am', 'Create', 'Employee Management', '0', 'test'),
('2025-10-28', '05:37:54am', 'Create', 'Ticket Management', '9', 'josh'),
('2025-10-28', '05:38:23am', 'Approve & Assign', 'Ticket Management', '9', 'test'),
('2025-10-28', '03:11:10pm', 'Resolved Ticket', 'Ticket Management', '9', 'smiran.kenneth'),
('2025-10-28', '03:14:19pm', 'Create', 'Ticket Management', '10', ''),
('2025-10-28', '03:14:34pm', 'Approve & Assign', 'Ticket Management', '10', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbltickets`
--

CREATE TABLE `tbltickets` (
  `ticket_id` int(11) NOT NULL,
  `ticket_number` varchar(50) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `concern_details` text DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Low',
  `status` enum('Pending','In Progress','On Hold','Resolved','Closed','Reopened','Unresolved','Decline','Approve') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  `declined_by` int(11) DEFAULT NULL,
  `date_declined` datetime DEFAULT NULL,
  `decline_reason` text DEFAULT NULL,
  `date_filed` datetime DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbltickets`
--

INSERT INTO `tbltickets` (`ticket_id`, `ticket_number`, `employee_id`, `inventory_id`, `branch_id`, `department`, `category`, `concern_details`, `priority`, `status`, `remarks`, `assigned_to`, `approved_by`, `date_approved`, `declined_by`, `date_declined`, `decline_reason`, `date_filed`, `last_updated`, `created_by`) VALUES
(2, 'TCK-20251028012314-993', 2200424, 9, 1, 'IT', 'Hardware', 'PSU not working', 'Low', 'In Progress', 'On hold', NULL, 2200426, '2025-10-28 01:34:23', 2200426, '2025-10-28 01:24:33', 'Duplicate', '2025-10-28 01:23:14', '2025-10-28 01:34:23', 2200425),
(5, 'TCK-20251028014219-186', 2200424, 9, 1, 'IT', 'Hardware', 'TEST', 'Low', 'In Progress', NULL, NULL, 2200426, '2025-10-28 01:43:06', NULL, NULL, NULL, '2025-10-28 01:42:19', '2025-10-28 01:43:06', 2200425),
(6, 'TCK-20251028014302-398', 2200424, 9, 1, 'IT', 'Hardware', 'TEST AGAIN', 'Low', 'Closed', 'TEST', NULL, NULL, NULL, 2200426, '2025-10-28 01:43:12', 'TEST', '2025-10-28 01:43:02', '2025-10-28 01:43:12', 2200425),
(7, 'TCK-20251028043042-249', 2200424, 9, 1, 'IT', 'Hardware', 'Cant Type', 'Medium', 'In Progress', '', 2200424, 2200426, '2025-10-28 04:41:57', NULL, NULL, NULL, '2025-10-28 04:30:42', '2025-10-28 04:41:57', 2200425),
(8, 'TCK-20251028044312-450', 2200424, 9, 1, 'IT', 'Network', 'Test', 'High', 'In Progress', 'Test', 230005486, 2200426, '2025-10-28 04:43:24', NULL, NULL, NULL, '2025-10-28 04:43:12', '2025-10-28 04:43:24', 2200425),
(9, 'TCK-20251028053754-695', 2200424, 9, 1, 'IT', 'Software', 'Test', 'Medium', 'Resolved', 'Test Remarks', 202501071, 2200426, '2025-10-28 05:38:23', NULL, NULL, NULL, '2025-10-28 05:37:54', '2025-10-28 15:11:10', 2200425),
(10, 'TCK-20251028151419-397', 2200424, 9, 1, 'IT', 'Software', 'update software', 'High', 'In Progress', '', 202501071, 2200425, '2025-10-28 15:14:34', NULL, NULL, NULL, '2025-10-28 15:14:19', '2025-10-28 15:14:34', 2200425);

-- --------------------------------------------------------

--
-- Table structure for table `tblticket_history`
--

CREATE TABLE `tblticket_history` (
  `history_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `action_type` enum('Approved','Created','Assigned','Updated','Resolved','Reopened','Closed','On Hold','Unresolved') DEFAULT 'Updated',
  `action_details` text DEFAULT NULL,
  `old_status` enum('Pending','In Progress','On Hold','Resolved','Closed','Reopened','Unresolved') DEFAULT NULL,
  `new_status` enum('Pending','In Progress','On Hold','Resolved','Closed','Reopened','Unresolved') DEFAULT NULL,
  `performed_by` int(11) NOT NULL,
  `performed_role` varchar(50) DEFAULT NULL,
  `date_logged` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblticket_history`
--

INSERT INTO `tblticket_history` (`history_id`, `ticket_id`, `action_type`, `action_details`, `old_status`, `new_status`, `performed_by`, `performed_role`, `date_logged`) VALUES
(15, 2, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-28 01:23:14'),
(16, 2, 'Closed', 'Ticket Declined by Admin', 'Pending', 'Closed', 2200426, 'Employee', '2025-10-28 01:24:33'),
(17, 2, '', 'Ticket approved by admin', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-28 01:25:16'),
(18, 2, '', 'Ticket Approved by Admin', 'Pending', 'In Progress', 2200426, 'Employee', '2025-10-28 01:34:20'),
(19, 2, '', 'Ticket Approved by Admin', 'Pending', 'In Progress', 2200426, 'Employee', '2025-10-28 01:34:23'),
(28, 5, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-28 01:42:19'),
(29, 5, 'Approved', 'Ticket approved by admin', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-28 01:42:24'),
(30, 6, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-28 01:43:02'),
(31, 5, 'Approved', 'Ticket approved by admin', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-28 01:43:06'),
(32, 6, 'Closed', 'Ticket Declined by Admin', 'Pending', 'Closed', 2200426, 'Admin', '2025-10-28 01:43:12'),
(33, 7, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-28 04:30:42'),
(34, 7, 'Approved', 'Ticket approved and assigned to IT staff ID: 2200424', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-28 04:41:57'),
(35, 8, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-28 04:43:12'),
(36, 8, 'Approved', 'Ticket approved and assigned to IT staff ID: 230005486', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-28 04:43:24'),
(37, 9, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-28 05:37:54'),
(38, 9, 'Approved', 'Ticket approved and assigned to IT staff ID: 202501071', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-28 05:38:23'),
(39, 9, 'Resolved', 'Ticket Resolved by IT Staff (Account ID: 2200428)', 'In Progress', 'Resolved', 2200428, 'IT Staff', '2025-10-28 15:11:10'),
(40, 10, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-28 15:14:19'),
(41, 10, 'Approved', 'Ticket approved and assigned to IT staff ID: 202501071', 'Pending', 'In Progress', 2200425, 'Admin', '2025-10-28 15:14:34');

-- --------------------------------------------------------

--
-- Table structure for table `tblticket_technical`
--

CREATE TABLE `tblticket_technical` (
  `tech_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `performed_by` int(11) NOT NULL,
  `technical_purpose` varchar(255) DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `result` text DEFAULT NULL,
  `date_performed` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblticket_technical`
--

INSERT INTO `tblticket_technical` (`tech_id`, `ticket_id`, `performed_by`, `technical_purpose`, `action_taken`, `result`, `date_performed`, `remarks`) VALUES
(1, 9, 202501071, 'Desktop / Laptop Issue', 'Test Action Taken', 'Test Note', '2025-10-28 15:11:10', 'Test Remarks');

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
  ADD KEY `account_id` (`account_id`),
  ADD KEY `fk_branch_id` (`branch_id`);

--
-- Indexes for table `tbltickets`
--
ALTER TABLE `tbltickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `inventory_id` (`inventory_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `tblticket_history`
--
ALTER TABLE `tblticket_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `tblticket_technical`
--
ALTER TABLE `tblticket_technical`
  ADD PRIMARY KEY (`tech_id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `performed_by` (`performed_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  MODIFY `account_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2200429;

--
-- AUTO_INCREMENT for table `tblassets_assignment`
--
ALTER TABLE `tblassets_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tblassets_category`
--
ALTER TABLE `tblassets_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblassets_directory`
--
ALTER TABLE `tblassets_directory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblassets_group`
--
ALTER TABLE `tblassets_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblassets_inventory`
--
ALTER TABLE `tblassets_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblbranch`
--
ALTER TABLE `tblbranch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbltickets`
--
ALTER TABLE `tbltickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblticket_history`
--
ALTER TABLE `tblticket_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tblticket_technical`
--
ALTER TABLE `tblticket_technical`
  MODIFY `tech_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `account_id` FOREIGN KEY (`account_id`) REFERENCES `tblaccounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `tblbranch` (`branch_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbltickets`
--
ALTER TABLE `tbltickets`
  ADD CONSTRAINT `tbltickets_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `tblemployee` (`employee_id`),
  ADD CONSTRAINT `tbltickets_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `tblassets_inventory` (`inventory_id`),
  ADD CONSTRAINT `tbltickets_ibfk_3` FOREIGN KEY (`assigned_to`) REFERENCES `tblemployee` (`employee_id`);

--
-- Constraints for table `tblticket_history`
--
ALTER TABLE `tblticket_history`
  ADD CONSTRAINT `tblticket_history_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tbltickets` (`ticket_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblticket_technical`
--
ALTER TABLE `tblticket_technical`
  ADD CONSTRAINT `tblticket_technical_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tbltickets` (`ticket_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblticket_technical_ibfk_2` FOREIGN KEY (`performed_by`) REFERENCES `tblemployee` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
