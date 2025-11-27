-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 07:07 AM
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
(2200425, 'smiran.rolandjosh', '123', 'EMPLOYEE', 'ACTIVE', 'admin', '10/27/2025'),
(2200426, 'admin', '123', 'ADMIN', 'ACTIVE', 'josh', '10/27/2025'),
(2200427, 'sm.roseanne', 'Madla@2025', 'ADMIN', 'ACTIVE', 'josh', '10/27/2025'),
(2200428, 'smiran.kenneth', 'Dador@2025', 'IT', 'ACTIVE', 'test', '10/28/2025'),
(2200429, 'smdelta.jeremiah', 'Beazar@2025', 'EMPLOYEE', 'ACTIVE', '', '11/07/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tblassets_assignment`
--

CREATE TABLE `tblassets_assignment` (
  `assignment_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
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

INSERT INTO `tblassets_assignment` (`assignment_id`, `inventory_id`, `employee_id`, `assignedTo`, `dateIssued`, `transferDetails`, `transferCount`, `dateReturned`, `datecreated`, `createdby`) VALUES
(39, 9, 2200424, 'Ricafort, Roland Josh Manalo', '2025-10-27', 'New Asset to Mr. Ricafort', '001', '', '2025-10-27 18:40:12', 'josh'),
(49, 13, NULL, 'Unassigned / Returned', '2025-10-29', 'Test', '', '2025-10-29', '2025-10-29 11:17:22', 'test'),
(50, 13, NULL, 'Unassigned / LOST', '2025-10-29', 'Reason for LOST: Return Test', '', '2025-10-29', '2025-10-29 11:31:09', 'test'),
(51, 13, NULL, 'Unassigned / RETURNED', '2025-10-29', 'Reason for RETURNED: Return Test', '', '2025-10-29', '2025-10-29 11:32:22', 'test'),
(52, 13, NULL, 'Unassigned / RETURNED', '2025-10-29', 'Reason for RETURNED: Test', '', '2025-10-29', '2025-10-29 11:35:37', 'test'),
(53, 13, NULL, 'Unassigned / DISPOSED', '2025-10-29', 'Reason for DISPOSED: Test', '', '2025-10-29', '2025-10-29 11:36:47', 'test'),
(54, 13, NULL, 'Unassigned / RETURNED', '2025-10-29', 'Reason for RETURNED: adsad', '', '2025-10-29', '2025-10-29 11:40:10', 'test'),
(55, 13, NULL, 'Unassigned / RETURNED', '2025-10-29', 'Reason for RETURNED: REST', '', '2025-10-29', '2025-10-29 11:46:16', 'test'),
(56, 13, NULL, 'Unassigned / LOST', '2025-10-29', 'Reason for LOST: asdasdas', '', '2025-10-29', '2025-10-29 11:52:40', 'test'),
(57, 13, 202501071, 'Dador, Kenneth ', '2025-10-29', 'Test', '002', '', '2025-10-29 13:38:03', 'admin'),
(58, 13, NULL, 'Unassigned / RETURNED', '2025-10-29', 'Reason for RETURNED: Nothing', '', '2025-10-29', '2025-10-29 13:38:46', 'admin'),
(61, 13, NULL, 'Unassigned / RETURNED', '2025-10-29', 'Reason for RETURNED: TRY', '', '2025-10-29', '2025-10-29 14:36:35', 'admin'),
(62, 13, NULL, 'Unassigned / LOST', '2025-10-29', 'Reason for LOST: TRY AGAIN', '', '2025-10-29', '2025-10-29 14:43:12', 'admin'),
(63, 13, 230005486, 'Madla, Rose Anne Solas', '2025-10-29', 'Mr Ricafort to Ms. Madla', '003', '', '2025-10-29 16:03:57', 'admin'),
(64, 14, 2200424, 'Ricafort, Roland Josh Manalo', '2025-10-29', 'From Unaasign to Mr Ricafort', '004', '', '2025-10-29 16:05:39', 'admin'),
(65, 17, 350141768, 'Beazar, Jeremiah Onrubia', '2025-11-07', 'From Unassgin to Mr. Jeremiah', '005', '', '2025-11-07', 'admin'),
(66, 17, NULL, 'Unassigned / DISPOSED', '2025-11-07', 'Reason for DISPOSED: Unused', '', '2025-11-07', '2025-11-07 13:59:08', 'admin'),
(67, 17, 350141768, 'Beazar, Jeremiah Onrubia', '2025-11-07', 'From Unused to Mr. Jeremiah', '006', '', '2025-11-07', 'admin'),
(68, 17, 2200425, 'Howard, Sy ', '2025-11-07', 'Try', '007', '', '2025-11-07', 'admin'),
(69, 17, 350141768, 'Beazar, Jeremiah Onrubia', '2025-11-07', 'From Unused to Mr. Jeremiah', '008', '', '2025-11-07', 'admin'),
(70, 17, NULL, 'Unassigned / DISPOSED', '2025-11-07', 'Reason for DISPOSED: Test Disposed', '', '2025-11-07', '2025-11-07 14:13:37', 'admin'),
(71, 17, 350141768, 'Beazar, Jeremiah Onrubia', '2025-11-07', 'From Test to Mr. Jeremiah', '009', '', '2025-11-07', 'admin'),
(72, 17, 2200424, 'Ricafort, Roland Josh Manalo', '2025-11-20', 'Test Transfer', '010', '', '2025-11-20', 'admin');

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
(6, 16, 'OE', 'Lenovo', 'Laptop', '2025-10-27 18:32:24', 'josh'),
(7, 16, 'OE', 'HP', 'Laptop', '2025-10-29 16:13:19', 'admin'),
(8, 16, 'OE', 'DERE', 'Laptop', '2025-11-07 13:42:49', 'admin');

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
(10, NULL, NULL, NULL, 6, 'PF510588', 'Ideapad I3 Ultra Slim', 'UNASSIGNED', '2', 'OE-25002', '2025', '2025-10-27 20:03:37', 'josh'),
(11, NULL, NULL, NULL, 6, 'N/A', 'Ideapad 3i (15\'\')', 'UNASSIGNED', '3', 'OE-24003', '2024', '2025-10-29 10:06:45', 'test'),
(12, NULL, NULL, NULL, 6, 'N/A', 'Ideapad i1 (14\'\')', 'UNASSIGNED', '4', 'OE-24004', '2024', '2025-10-29 10:10:36', 'test'),
(13, 63, 230005486, 1, 6, 'PF4T4KJS', 'Idea Pad 1 151JL7', 'ASSIGNED', '5', 'OE-25005-HO003', '2025', '2025-10-29 10:18:47', 'test'),
(14, 64, 2200424, 1, 6, 'PF4XBQC', 'IdeaPad 1 151JL7', 'ASSIGNED', '6', 'OE-25006-HO004', '2025', '2025-10-29 10:20:40', 'test'),
(15, NULL, NULL, NULL, 6, 'PF546T39', 'IdeaPad 1 151JL7', 'UNASSIGNED', '7', 'OE-25007', '2025', '2025-10-29 11:58:57', 'test'),
(16, NULL, NULL, NULL, 6, 'PF510588', 'IdeaPad 1 151JL7', 'UNASSIGNED', '8', 'OE-25008', '2025', '2025-10-29 11:59:27', 'test'),
(17, 72, 2200424, 1, 8, 'N/A', 'Unknown', 'ASSIGNED', '9', 'OE-24009-HO010', '2024', '2025-11-07 13:45:18', 'admin');

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
(1, 'HO', 'Head Office', '3112 Iran Street, Makati City, 1213 Metro Manila', '10/27/2025', 'admin'),
(5, 'DAR', 'Don Roces', '127 Don A. Roces Ave, Diliman, Quezon City, 1103 Metro Manila', '2025-10-29 09:53:20', 'test'),
(6, 'SCT', 'Sucat', 'Dr Arcadio Santos Ave, Parañaque, 1700 Metro Manila', '2025-10-29 09:53:54', 'test'),
(7, 'QAB', 'Banawe', '388 Quezon Ave, Quezon City, 1113 Metro Manila', '2025-10-29 09:54:38', 'test'),
(8, 'STL', 'Santolan', 'Little Bagui, 298 Col. ?????????????? San Juan City, 1500 Metro Manila', '2025-10-29 09:55:42', 'test'),
(9, 'PSG', 'Pasig', 'MP Building, Jose C.Cruz, Pasig, 1604 Metro Manila', '2025-10-29 09:56:07', 'test'),
(10, 'BKL', 'EDSA', '19 Epifanio de los Santos Ave, Makati City, Metro Manila', '2025-10-29 09:56:54', 'test'),
(11, 'QAD', 'Delta', '1231 Quezon Avenue, corner Jose Abad Santos, Quezon City, 1104', '2025-10-29 09:57:52', 'test'),
(12, 'BND', 'Binondo', '407 Dasmarinas, Cor Burke St, Binondo, Manila, 1006 Metro Manila', '2025-10-29 09:58:27', 'test'),
(13, 'IRN', 'Eran', '3112 Iran Street, Makati City, 1213 Metro Manila', '2025-10-29 09:58:49', 'test'),
(14, 'KTP', 'Katipunan', '311 Katipunan Ave, Quezon City, 1108 Metro Manila', '2025-10-29 09:59:15', 'test'),
(15, 'FVW', 'Fairview', 'Block 63 Lot 12, Brgy, 14 Commonwealth Ave, Quezon City, 1121 Metro Manila', '2025-10-29 09:59:43', 'test'),
(16, 'JBD', 'Jabad', '3F, WNC Building, 15 Jose Abad Santos, San Juan City, 1500 Metro Manila', '2025-10-29 10:00:25', 'test'),
(17, 'YKL', 'Yakal', 'Warehouse C, 7452 Yakal, Village, Makati City, 1203 Metro Manila', '2025-10-29 10:00:54', 'test'),
(18, 'CLC', 'Caloocan', '152 D. Aquino St, Grace Park West, Caloocan, 1406 Metro Manila', '2025-10-29 10:02:30', 'test');

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
(2200425, 2200426, 1, 'Howard', 'Sy', '', 'Storage Mart', 'Chief executive officer', 'rj.ricafort21@nullsto.edu.pl', 'josh', '10/27/2025'),
(202501071, 2200428, 1, 'Dador', 'Kenneth', '', 'IT', 'IT Support Associate', 'storagemart.it@gmail.com', 'test', '10/28/2025'),
(230005486, 2200427, 1, 'Madla', 'Rose Anne', 'Solas', 'HRMD', 'Head of HRMD', 'roseanne.madla@storagemart.com', 'josh', '10/27/2025'),
(350141768, 2200429, 11, 'Beazar', 'Jeremiah', 'Onrubia', 'Operations', 'Facility Officer', 'jeremiah.beazar@storagemart.com', '', '11/07/2025');

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
('2025-10-29', '09:53:20', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:53:54', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:54:38', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:55:42', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:56:07', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:56:54', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:57:52', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:58:27', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:58:49', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:59:15', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '09:59:43', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '10:00:25', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '10:00:54', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '10:02:30', 'Add New Branch', 'Branch Management', '2200426', 'test'),
('2025-10-29', '10:06:45', 'Added new asset: OE-', 'Asset Inventory', '2200426', 'test'),
('2025-10-29', '10:10:36', 'Added new asset: OE-', 'Asset Inventory', '2200426', 'test'),
('2025-10-29', '10:18:47', 'Added new asset: OE-', 'Asset Inventory', '2200426', 'test'),
('2025-10-29', '10:20:40', 'Added new asset: OE-', 'Asset Inventory', '2200426', 'test'),
('2025-10-29', '10:24:13am', 'Updated Item Asset (', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:34:14am', 'Updated Item Asset (', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:37:26am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:38:30am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:41:59am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:42:55am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:44:20am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:46:07am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:46:44am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:47:01am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:48:21am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:48:47am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:49:02am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:51:21am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:51:33am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:52:08am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:52:34am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:52:44am', 'Updated Item Asset', 'Item Asset Management', '0', 'test'),
('2025-10-29', '10:56:16am', 'Updated Item Asset', 'Item Asset Management', '0', 'test'),
('2025-10-29', '10:56:39am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:57:17am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '10:59:33am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:00:05am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:09:35am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:10:36am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:10:52am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:13:14am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:16:05am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:17:22am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:31:09am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:32:22am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:35:24am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:35:37am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:36:18am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:36:47am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:38:37am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:40:10am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:40:46am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:46:16am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:52:15am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:52:40am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:56:29am', 'Updated Item Asset', 'Item Asset Management', '13', 'test'),
('2025-10-29', '11:57:43am', 'Updated Item Asset', 'Item Asset Management', '14', 'test'),
('2025-10-29', '11:58:57', 'Added new asset: OE-', 'Asset Inventory', '2200426', 'test'),
('2025-10-29', '11:59:27', 'Added new asset: OE-', 'Asset Inventory', '2200426', 'test'),
('2025-10-29', '12:02:24pm', 'Create', 'Ticket Management', '11', 'josh'),
('2025-10-29', '12:07:08pm', 'Approve & Assign', 'Ticket Management', '11', 'test'),
('2025-10-29', '01:14:07pm', 'Updated an Account', 'Employee Management', '2200425', 'test'),
('2025-10-29', '01:14:30pm', 'Updated an Account', 'Employee Management', '2200424', 'admin'),
('2025-10-29', '01:20:35pm', 'Updated an Account', 'Employee Management', '230005486', 'admin'),
('2025-10-29', '13:38:03', 'Transferred asset OE', 'Asset Inventory', '13', 'admin'),
('2025-10-29', '01:38:46pm', 'Updated Item Asset', 'Item Asset Management', '13', 'admin'),
('2025-10-29', '01:39:09pm', 'Updated Item Asset', 'Item Asset Management', '13', 'admin'),
('2025-10-29', '14:06:39', 'Transferred asset OE', 'Asset Inventory', '13', 'admin'),
('2025-10-29', '02:13:12pm', 'Updated Item Asset', 'Item Asset Management', '13', 'admin'),
('2025-10-29', '14:14:56', 'Transferred asset OE', 'Asset Inventory', '13', 'admin'),
('2025-10-29', '02:34:18pm', 'Updated Item Asset', 'Item Asset Management', '13', 'admin'),
('2025-10-29', '02:36:17pm', 'Updated Item Asset', 'Item Asset Management', '13', 'admin'),
('2025-10-29', '02:36:35pm', 'Updated Item Asset', 'Item Asset Management', '13', 'admin'),
('2025-10-29', '02:43:12pm', 'Updated Item Asset', 'Item Asset Management', '13', 'admin'),
('2025-10-29', '16:03:57', 'Transferred asset OE', 'Asset Inventory', '13', 'admin'),
('2025-10-29', '16:05:39', 'Transferred asset OE', 'Asset Inventory', '14', 'admin'),
('2025-10-29', '04:06:39pm', 'Create', 'Ticket Management', '12', 'smiran.rolandjosh'),
('2025-10-29', '04:07:54pm', 'Approve & Assign', 'Ticket Management', '12', 'admin'),
('2025-10-29', '04:10:25pm', 'Resolved Ticket', 'Ticket Management', '12', 'smiran.kenneth'),
('2025-10-29', '16:13:19', 'Create Group', 'Group Asset Management', '7', 'admin'),
('2025-11-07', '09:30:50am', 'Create', 'Ticket Management', '13', 'smiran.rolandjosh'),
('2025-11-07', '09:32:21am', 'Approve & Assign', 'Ticket Management', '13', 'smiran.rolandjosh'),
('2025-11-07', '01:40:45pm', 'Update Asset Group', 'Group Asset Management', '2200426', 'admin'),
('2025-11-07', '01:40:58pm', 'Update Asset Group', 'Group Asset Management', '2200426', 'admin'),
('2025-11-07', '01:41:07pm', 'Update Asset Group', 'Group Asset Management', '2200426', 'admin'),
('2025-11-07', '13:42:49', 'Create Group', 'Group Asset Management', '8', 'admin'),
('2025-11-07', '13:45:18', 'Added new asset: OE-', 'Asset Inventory', '2200426', 'admin'),
('11/07/2025', '01:52:29pm', 'Create', 'Employee Management', '0', ''),
('2025-11-07', '01:55:11pm', 'Updated an Account', 'Employee Management', '350141768', ''),
('2025-11-07', '01:55:20pm', 'Updated an Account', 'Employee Management', '350141768', ''),
('2025-11-07', '13:57:46', 'Transferred asset OE', 'Asset Inventory', '17', 'admin'),
('2025-11-07', '01:58:18pm', 'Updated Item Asset', 'Item Asset Management', '17', 'admin'),
('2025-11-07', '01:59:08pm', 'Updated Item Asset', 'Item Asset Management', '17', 'admin'),
('2025-11-07', '14:01:26', 'Transferred asset OE', 'Asset Inventory', '17', 'admin'),
('2025-11-07', '14:01:58', 'Transferred asset OE', 'Asset Inventory', '17', 'admin'),
('2025-11-07', '02:02:47pm', 'Updated Item Asset', 'Item Asset Management', '17', 'admin'),
('2025-11-07', '14:03:37', 'Transferred asset OE', 'Asset Inventory', '17', 'admin'),
('2025-11-07', '02:13:20pm', 'Updated Item Asset', 'Item Asset Management', '17', 'admin'),
('2025-11-07', '02:13:37pm', 'Updated Item Asset', 'Item Asset Management', '17', 'admin'),
('2025-11-07', '14:14:03', 'Transferred asset OE', 'Asset Inventory', '17', 'admin'),
('2025-11-20', '10:08:52', 'Transferred asset OE', 'Asset Inventory', '17', 'admin'),
('2025-11-25', '02:39:56pm', 'Create', 'Ticket Management', '14', 'smiran.rolandjosh'),
('2025-11-25', '02:44:02pm', 'Approve & Assign', 'Ticket Management', '14', 'admin'),
('2025-11-25', '03:49:20pm', 'Create', 'Ticket Management', '15', 'smiran.rolandjosh');

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
(11, 'TCK-20251029120224-531', 2200424, 9, 1, 'IT', 'Hardware', 'Not Display', 'Low', 'In Progress', '', 202501071, 2200426, '2025-10-29 12:07:08', NULL, NULL, NULL, '2025-10-29 12:02:24', '2025-10-29 12:07:08', 2200425),
(12, 'TCK-20251029160639-420', 2200424, 14, 1, 'IT', 'Hardware', 'No display', 'Low', 'Resolved', 'None', 202501071, 2200426, '2025-10-29 16:07:54', NULL, NULL, NULL, '2025-10-29 16:06:39', '2025-10-29 16:10:25', 2200425),
(13, 'TCK-20251107093050-242', 2200424, 9, 1, 'IT', 'Software', 'No display', 'High', 'In Progress', '', 202501071, 2200425, '2025-11-07 09:32:21', NULL, NULL, NULL, '2025-11-07 09:30:50', '2025-11-07 09:32:21', 2200425),
(14, 'TCK-20251125143956-279', 2200424, 9, 1, 'IT', 'Software', 'No Display', 'Medium', 'In Progress', 'N/A', 202501071, 2200426, '2025-11-25 14:44:01', NULL, NULL, NULL, '2025-11-25 14:39:56', '2025-11-25 14:44:01', 2200425),
(15, 'STM-20251125-333', 2200424, 17, 1, 'IT', 'Software', 'asdasd', 'Low', 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-25 15:49:20', '2025-11-25 15:49:20', 2200425);

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
(1, 11, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-29 12:02:24'),
(2, 11, 'Approved', 'Ticket approved and assigned to IT staff ID: 202501071', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-29 12:07:08'),
(3, 12, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-10-29 16:06:39'),
(4, 12, 'Approved', 'Ticket approved and assigned to IT staff ID: 202501071', 'Pending', 'In Progress', 2200426, 'Admin', '2025-10-29 16:07:54'),
(5, 12, 'Resolved', 'Ticket Resolved by IT Staff (Account ID: 2200428)', 'In Progress', 'Resolved', 2200428, 'IT Staff', '2025-10-29 16:10:25'),
(6, 13, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-11-07 09:30:50'),
(7, 13, 'Approved', 'Ticket approved and assigned to IT staff ID: 202501071', 'Pending', 'In Progress', 2200425, 'Admin', '2025-11-07 09:32:21'),
(8, 14, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-11-25 14:39:56'),
(9, 14, 'Approved', 'Ticket approved and assigned to IT staff ID: 202501071', 'Pending', 'In Progress', 2200426, 'Admin', '2025-11-25 14:44:01'),
(10, 15, 'Created', 'Ticket filed by employee', NULL, 'Pending', 2200425, 'Employee', '2025-11-25 15:49:20');

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
(1, 12, 202501071, 'Desktop / Laptop Issue', 'Reformat', 'There is display', '2025-10-29 16:10:25', 'None');

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
  ADD KEY `employeeID` (`employee_id`),
  ADD KEY `tblassignment_fk_inventoryID` (`inventory_id`);

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
  MODIFY `account_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2200430;

--
-- AUTO_INCREMENT for table `tblassets_assignment`
--
ALTER TABLE `tblassets_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

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
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblassets_inventory`
--
ALTER TABLE `tblassets_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblbranch`
--
ALTER TABLE `tblbranch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbltickets`
--
ALTER TABLE `tbltickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblticket_history`
--
ALTER TABLE `tblticket_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `employeeID` FOREIGN KEY (`employee_id`) REFERENCES `tblemployee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblassignment_fk_inventoryID` FOREIGN KEY (`inventory_id`) REFERENCES `tblassets_inventory` (`inventory_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
