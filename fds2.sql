-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 09:00 PM
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
-- Database: `fds2`
--

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `farmerID` int(11) NOT NULL,
  `FName` varchar(100) NOT NULL,
  `LName` varchar(100) NOT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('Male','Female','Other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registeredBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`farmerID`, `FName`, `LName`, `phone_no`, `age`, `sex`, `address`, `registeredBy`) VALUES
(1, 'Alice', 'Williams', '5551112233', 35, 'Female', '123 Green Road, City A', 3),
(2, 'Bob', 'Miller', '5552223344', 42, 'Male', '456 Blue Street, City B', 3),
(3, 'Charlie', 'Anderson', '5553334455', 29, 'Male', '789 Red Lane, City C', 3);

-- --------------------------------------------------------

--
-- Table structure for table `farmer_land`
--

CREATE TABLE `farmer_land` (
  `landID` int(11) NOT NULL,
  `farmerID` int(11) DEFAULT NULL,
  `landSize` decimal(10,2) DEFAULT NULL,
  `landLocation` text DEFAULT NULL,
  `soilType` varchar(50) DEFAULT NULL,
  `registeredBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_land`
--

INSERT INTO `farmer_land` (`landID`, `farmerID`, `landSize`, `landLocation`, `soilType`, `registeredBy`) VALUES
(1, 1, 5.20, 'Farm Zone A', 'Loamy', 3),
(2, 2, 3.80, 'Farm Zone B', 'Clay', 3),
(3, 3, 4.50, 'Farm Zone C', 'Sandy', 3);

-- --------------------------------------------------------

--
-- Table structure for table `farmer_login`
--

CREATE TABLE `farmer_login` (
  `farmerID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_login`
--

INSERT INTO `farmer_login` (`farmerID`, `username`, `password`) VALUES
(1, 'alice_w', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(2, 'bobbymil', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(3, 'charlie55', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.');

-- --------------------------------------------------------

--
-- Table structure for table `fertilizers`
--

CREATE TABLE `fertilizers` (
  `fertID` int(11) NOT NULL,
  `fertName` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `availableStock` int(11) NOT NULL DEFAULT 0,
  `managedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizers`
--

INSERT INTO `fertilizers` (`fertID`, `fertName`, `description`, `availableStock`, `managedBy`) VALUES
(1, 'Urea', 'High nitrogen content fertilizer', 500, 1),
(2, 'DAP', 'Di-Ammonium Phosphate for soil health', 400, 1),
(3, 'MOP', 'Muriate of Potash for plant growth', 300, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_distribution`
--

CREATE TABLE `fertilizer_distribution` (
  `distributionID` int(11) NOT NULL,
  `requestID` int(11) DEFAULT NULL,
  `fertID` int(11) DEFAULT NULL,
  `quantityDispatched` int(11) NOT NULL,
  `dispatchDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `receivedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_distribution`
--

INSERT INTO `fertilizer_distribution` (`distributionID`, `requestID`, `fertID`, `quantityDispatched`, `dispatchDate`, `receivedBy`) VALUES
(1, 1, 1, 20, '2025-02-26 12:55:45', 3),
(2, 2, 2, 15, '2025-02-26 12:55:45', 3);

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_requests`
--

CREATE TABLE `fertilizer_requests` (
  `requestID` int(11) NOT NULL,
  `farmerID` int(11) DEFAULT NULL,
  `landID` int(11) DEFAULT NULL,
  `fertID` int(11) DEFAULT NULL,
  `quantityRequested` int(11) NOT NULL,
  `requestDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected','Dispatched','Delivered') DEFAULT 'Pending',
  `fieldOfficer` int(11) DEFAULT NULL,
  `reviewedBy` int(11) DEFAULT NULL,
  `approvedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_requests`
--

INSERT INTO `fertilizer_requests` (`requestID`, `farmerID`, `landID`, `fertID`, `quantityRequested`, `requestDate`, `status`, `fieldOfficer`, `reviewedBy`, `approvedBy`) VALUES
(1, 1, 1, 1, 20, '2025-02-26 12:55:26', 'Pending', 3, 2, 1),
(2, 2, 2, 2, 15, '2025-02-26 12:55:26', 'Approved', 3, 2, 1),
(3, 3, 3, 3, 10, '2025-02-26 12:55:26', 'Rejected', 3, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `offID` int(11) NOT NULL,
  `offName` varchar(100) NOT NULL,
  `role` enum('Field Officer','Junior Officer','Senior Officer','Quality Control Officer','Subsidy Payment Officer') NOT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('Male','Female','Other') DEFAULT NULL,
  `supervisorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`offID`, `offName`, `role`, `phone_no`, `email`, `age`, `sex`, `supervisorID`) VALUES
(1, 'John Doe', 'Senior Officer', '1234567890', 'john@example.com', 45, 'Male', NULL),
(2, 'Jane Smith', 'Junior Officer', '9876543210', 'jane@example.com', 38, 'Female', 1),
(3, 'Michael Johnson', 'Field Officer', '1122334455', 'michael@example.com', 30, 'Male', 2),
(4, 'Emily Davis', 'Quality Control Officer', '2233445566', 'emily@example.com', 34, 'Female', 1),
(5, 'Robert Brown', 'Subsidy Payment Officer', '3344556677', 'robert@example.com', 40, 'Male', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quality_inspections`
--

CREATE TABLE `quality_inspections` (
  `inspectionID` int(11) NOT NULL,
  `fertID` int(11) DEFAULT NULL,
  `officerID` int(11) DEFAULT NULL,
  `inspectionDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `result` enum('Passed','Failed') NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quality_inspections`
--

INSERT INTO `quality_inspections` (`inspectionID`, `fertID`, `officerID`, `inspectionDate`, `result`, `remarks`) VALUES
(1, 1, 4, '2025-02-26 12:56:46', 'Passed', 'Meets required standards'),
(2, 2, 4, '2025-02-26 12:56:46', 'Failed', 'Moisture content too high');

-- --------------------------------------------------------

--
-- Table structure for table `subsidy_payments`
--

CREATE TABLE `subsidy_payments` (
  `paymentID` int(11) NOT NULL,
  `farmerID` int(11) DEFAULT NULL,
  `requestID` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Paid','Rejected') DEFAULT 'Pending',
  `processedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subsidy_payments`
--

INSERT INTO `subsidy_payments` (`paymentID`, `farmerID`, `requestID`, `amount`, `status`, `processedBy`) VALUES
(1, 1, 1, 500.00, 'Pending', 5),
(2, 2, 2, 400.00, 'Paid', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`farmerID`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD KEY `registeredBy` (`registeredBy`);

--
-- Indexes for table `farmer_land`
--
ALTER TABLE `farmer_land`
  ADD PRIMARY KEY (`landID`),
  ADD KEY `farmerID` (`farmerID`),
  ADD KEY `registeredBy` (`registeredBy`);

--
-- Indexes for table `farmer_login`
--
ALTER TABLE `farmer_login`
  ADD PRIMARY KEY (`farmerID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `fertilizers`
--
ALTER TABLE `fertilizers`
  ADD PRIMARY KEY (`fertID`),
  ADD KEY `managedBy` (`managedBy`);

--
-- Indexes for table `fertilizer_distribution`
--
ALTER TABLE `fertilizer_distribution`
  ADD PRIMARY KEY (`distributionID`),
  ADD KEY `requestID` (`requestID`),
  ADD KEY `fertID` (`fertID`),
  ADD KEY `receivedBy` (`receivedBy`);

--
-- Indexes for table `fertilizer_requests`
--
ALTER TABLE `fertilizer_requests`
  ADD PRIMARY KEY (`requestID`),
  ADD KEY `farmerID` (`farmerID`),
  ADD KEY `landID` (`landID`),
  ADD KEY `fertID` (`fertID`),
  ADD KEY `fieldOfficer` (`fieldOfficer`),
  ADD KEY `reviewedBy` (`reviewedBy`),
  ADD KEY `approvedBy` (`approvedBy`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`offID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `supervisorID` (`supervisorID`);

--
-- Indexes for table `quality_inspections`
--
ALTER TABLE `quality_inspections`
  ADD PRIMARY KEY (`inspectionID`),
  ADD KEY `fertID` (`fertID`),
  ADD KEY `officerID` (`officerID`);

--
-- Indexes for table `subsidy_payments`
--
ALTER TABLE `subsidy_payments`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `farmerID` (`farmerID`),
  ADD KEY `requestID` (`requestID`),
  ADD KEY `processedBy` (`processedBy`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `farmerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `farmer_land`
--
ALTER TABLE `farmer_land`
  MODIFY `landID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fertilizers`
--
ALTER TABLE `fertilizers`
  MODIFY `fertID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fertilizer_distribution`
--
ALTER TABLE `fertilizer_distribution`
  MODIFY `distributionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fertilizer_requests`
--
ALTER TABLE `fertilizer_requests`
  MODIFY `requestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `offID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quality_inspections`
--
ALTER TABLE `quality_inspections`
  MODIFY `inspectionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subsidy_payments`
--
ALTER TABLE `subsidy_payments`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `farmers`
--
ALTER TABLE `farmers`
  ADD CONSTRAINT `farmers_ibfk_1` FOREIGN KEY (`registeredBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `farmer_land`
--
ALTER TABLE `farmer_land`
  ADD CONSTRAINT `farmer_land_ibfk_1` FOREIGN KEY (`farmerID`) REFERENCES `farmers` (`farmerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `farmer_land_ibfk_2` FOREIGN KEY (`registeredBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `farmer_login`
--
ALTER TABLE `farmer_login`
  ADD CONSTRAINT `farmer_login_ibfk_1` FOREIGN KEY (`farmerID`) REFERENCES `farmers` (`farmerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fertilizers`
--
ALTER TABLE `fertilizers`
  ADD CONSTRAINT `fertilizers_ibfk_1` FOREIGN KEY (`managedBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fertilizer_distribution`
--
ALTER TABLE `fertilizer_distribution`
  ADD CONSTRAINT `fertilizer_distribution_ibfk_1` FOREIGN KEY (`requestID`) REFERENCES `fertilizer_requests` (`requestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fertilizer_distribution_ibfk_2` FOREIGN KEY (`fertID`) REFERENCES `fertilizers` (`fertID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fertilizer_distribution_ibfk_3` FOREIGN KEY (`receivedBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL;

--
-- Constraints for table `fertilizer_requests`
--
ALTER TABLE `fertilizer_requests`
  ADD CONSTRAINT `fertilizer_requests_ibfk_1` FOREIGN KEY (`farmerID`) REFERENCES `farmers` (`farmerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fertilizer_requests_ibfk_2` FOREIGN KEY (`landID`) REFERENCES `farmer_land` (`landID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fertilizer_requests_ibfk_3` FOREIGN KEY (`fertID`) REFERENCES `fertilizers` (`fertID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fertilizer_requests_ibfk_4` FOREIGN KEY (`fieldOfficer`) REFERENCES `officers` (`offID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fertilizer_requests_ibfk_5` FOREIGN KEY (`reviewedBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fertilizer_requests_ibfk_6` FOREIGN KEY (`approvedBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_1` FOREIGN KEY (`supervisorID`) REFERENCES `officers` (`offID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quality_inspections`
--
ALTER TABLE `quality_inspections`
  ADD CONSTRAINT `quality_inspections_ibfk_1` FOREIGN KEY (`fertID`) REFERENCES `fertilizers` (`fertID`) ON DELETE CASCADE,
  ADD CONSTRAINT `quality_inspections_ibfk_2` FOREIGN KEY (`officerID`) REFERENCES `officers` (`offID`) ON DELETE SET NULL;

--
-- Constraints for table `subsidy_payments`
--
ALTER TABLE `subsidy_payments`
  ADD CONSTRAINT `subsidy_payments_ibfk_1` FOREIGN KEY (`farmerID`) REFERENCES `farmers` (`farmerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `subsidy_payments_ibfk_2` FOREIGN KEY (`requestID`) REFERENCES `fertilizer_requests` (`requestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `subsidy_payments_ibfk_3` FOREIGN KEY (`processedBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
