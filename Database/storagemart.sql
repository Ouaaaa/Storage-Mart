-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 04:04 PM
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
(10, NULL, NULL, NULL, 6, 'PF510588', 'Ideapad I3 Ultra Slim', 'UNASSIGNED', '2', 'OE-25002', '2025', '2025-10-27 20:03:37', 'josh'),
(11, NULL, NULL, NULL, 6, 'N/A', 'Ideapad 3i (15\'\')', 'UNASSIGNED', '3', 'OE-24003', '2024', '2025-10-29 10:06:45', 'test'),
(12, NULL, NULL, NULL, 6, 'N/A', 'Ideapad i1 (14\'\')', 'UNASSIGNED', '4', 'OE-24004', '2024', '2025-10-29 10:10:36', 'test'),
(15, NULL, NULL, NULL, 6, 'PF546T39', 'IdeaPad 1 151JL7', 'UNASSIGNED', '7', 'OE-25007', '2025', '2025-10-29 11:58:57', 'test'),
(16, NULL, NULL, NULL, 6, 'PF510588', 'IdeaPad 1 151JL7', 'UNASSIGNED', '8', 'OE-25008', '2025', '2025-10-29 11:59:27', 'test');

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
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblticket_technical`
--
ALTER TABLE `tblticket_technical`
  MODIFY `tech_id` int(11) NOT NULL AUTO_INCREMENT;

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
