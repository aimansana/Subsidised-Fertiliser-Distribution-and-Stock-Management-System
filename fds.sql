-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 12:17 PM
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
-- Database: `fds`
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
  `addy` text DEFAULT NULL,
  `category` varchar(5) DEFAULT NULL,
  `adhar` varchar(12) DEFAULT NULL,
  `registeredBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`farmerID`, `FName`, `LName`, `phone_no`, `age`, `sex`, `addy`, `category`, `adhar`, `registeredBy`) VALUES
(1, 'Alice', 'Wonder', '5551112234', 24, 'Female', '123 Green Road, City A', 'SC', '123654789741', 7),
(2, 'Bob', 'Miller', '5552223344', 45, 'Male', '456 Blue Street, City B', 'ST', '125478965854', 8),
(3, 'Charlie', 'Andy', '5553334455', 29, 'Male', '789 Red Lane, City C', 'OBC', '478965852654', 7),
(5, 'girly', 'pop', '1245877852', 25, 'Female', 'blr', 'SC', '123654987258', 8),
(6, 'mariam', 'noor', '4525556585', 18, 'Female', 'kora south', 'OBC', '321526365214', 7),
(8, 'san', 'ahm', '1548592635', 45, 'Other', 'outer zone b', 'ST', '357159852654', 10),
(10, 'vedh', 'jibu', '7676543434', 43, 'Female', 'blr zone b', 'SC', '693582471456', 9),
(17, 'Ramesh', 'Kumar', '9876543210', 45, 'Male', 'Village Road, Bihar', 'SC', '123456789012', 8),
(18, 'Sita', 'Devi', '9123456780', 38, 'Female', 'Main Street, Punjab', 'ST', '234567890123', 7),
(19, 'Amit', 'Sharma', '9988776655', 50, 'Male', 'Green Farms, UP', 'OBC', '345678901234', 10),
(20, 'Sunita', 'Yadav', '8765432109', 42, 'Female', 'Hillview Colony, Haryana', 'GENER', '456789012345', 9),
(21, 'Karan', 'Singh', '7890123456', 30, 'Male', 'Desert Road, Rajasthan', 'ST', '567890123456', 8),
(22, 'Pooja', 'Patel', '9988001122', 35, 'Female', 'River Side, Gujarat', 'GENER', '678901234567', 7);

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
(1, 1, 5.20, 'Farm Zone A', 'Loamy', 7),
(2, 2, 3.80, 'Farm Zone B', 'Clay', 8),
(3, 3, 4.50, 'Farm Zone C', 'Sandy', 7),
(9, 6, 35.00, 'hennur', 'black', 7),
(10, 2, 35.00, 'hennur', 'black', 8),
(11, 10, 67.00, 'zonc kamman', 'red', 9),
(13, 1, 53.00, 'blr south', 'black', 7),
(114, 17, 2.50, 'Village Road, Bihar', 'Loamy', 8),
(115, 18, 3.00, 'Main Street, Punjab', 'Clayey', 7),
(116, 19, 5.00, 'Green Farms, UP', 'Sandy', 10),
(117, 20, 1.80, 'Hillview Colony, Haryana', 'Alluvial', 9);

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
(3, 'charlie55', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(5, 'gpoppy', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(6, 'marinoor', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(8, 'saniiaa', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(10, 'vedj', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(17, 'rameshkumar01', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(18, 'sitadevi02', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(19, 'amitsharma03', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(20, 'sunitayadav04', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(21, 'karansingh05', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(22, 'poojapatel06', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.');

-- --------------------------------------------------------

--
-- Table structure for table `fertilizers`
--

CREATE TABLE `fertilizers` (
  `fertID` int(11) NOT NULL,
  `fertName` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `availableStock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizers`
--

INSERT INTO `fertilizers` (`fertID`, `fertName`, `description`, `availableStock`) VALUES
(1, 'Urea', 'Nitrogen-rich fertilizer that promotes leaf growth and overall plant development.', 5000),
(2, 'DAP', 'Di-Ammonium Phosphate, ideal for root development and early plant growth.', 3000),
(3, 'MOP', 'Muriate of Potash, enhances fruit quality and strengthens plant resistance.', 2000),
(4, 'NPK 10:26:26', 'Balanced fertilizer containing Nitrogen, Phosphorus, and Potassium for overall growth.', 4000),
(5, 'Super Phosphate', 'Promotes strong root systems and improves crop yield.', 2500),
(6, 'Organic Compost', 'Eco-friendly fertilizer that improves soil fertility and plant health.', 1500),
(7, 'Ammonium Sulfate', 'Provides essential nitrogen and sulfur for crop growth.', 1800),
(8, 'Vermicompost', 'Organic fertilizer made from decomposed plant and animal waste.', 1200);

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
(1000, 2, 2, 20, '2025-03-06 18:30:00', 7),
(2000, 4, 4, 400, '2025-03-07 18:30:00', 8),
(3000, 6, 6, 600, '2025-03-08 18:30:00', 9);

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
  `registeredBy` int(11) DEFAULT NULL,
  `reviewedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_requests`
--

INSERT INTO `fertilizer_requests` (`requestID`, `farmerID`, `landID`, `fertID`, `quantityRequested`, `requestDate`, `status`, `registeredBy`, `reviewedBy`) VALUES
(1, 17, 114, 1, 100, '2025-02-28 18:30:00', 'Approved', 7, 3),
(2, 18, 115, 2, 20, '2025-03-01 18:30:00', 'Approved', 9, 4),
(3, 19, 116, 3, 30, '2025-03-02 18:30:00', 'Rejected', 11, 5),
(4, 20, 117, 4, 400, '2025-03-03 18:30:00', 'Approved', 13, 6),
(5, 17, 114, 5, 550, '2025-03-04 18:30:00', 'Pending', 8, 3),
(6, 18, 115, 6, 600, '2025-03-05 18:30:00', 'Approved', 10, 4),
(13, 1, 13, 2, 15, '2025-03-13 08:42:34', 'Pending', 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `offID` int(11) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
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

INSERT INTO `officers` (`offID`, `Fname`, `Lname`, `role`, `phone_no`, `email`, `age`, `sex`, `supervisorID`) VALUES
(1, 'Rajesh', 'Verma', 'Senior Officer', '9876543210', 'rajesh.verma@gov.in', 50, 'Male', NULL),
(2, 'Anita', 'Sharma', 'Senior Officer', '9123456780', 'anita.sharma@gov.in', 48, 'Female', NULL),
(3, 'Vikram', 'Singh', 'Junior Officer', '9988776655', 'vikram.singh@gov.in', 42, 'Male', 1),
(4, 'Meera', 'Kapoor', 'Junior Officer', '8765432109', 'meera.kapoor@gov.in', 40, 'Female', 1),
(5, 'Suresh', 'Yadav', 'Junior Officer', '9876123456', 'suresh.yadav@gov.in', 43, 'Male', 2),
(6, 'Priya', 'Rao', 'Junior Officer', '9123456789', 'priya.rao@gov.in', 39, 'Female', 2),
(7, 'Amit', 'Kumar', 'Field Officer', '7896541230', 'amit.kumar@gov.in', 35, 'Male', 3),
(8, 'Sneha', 'Patil', 'Field Officer', '7023456789', 'sneha.patil@gov.in', 32, 'Female', 3),
(9, 'Ravi', 'Das', 'Field Officer', '8009123456', 'ravi.das@gov.in', 34, 'Male', 4),
(10, 'Poonam', 'Joshi', 'Field Officer', '9988771122', 'poonam.joshi@gov.in', 31, 'Female', 4),
(11, 'Manoj', 'Sharma', 'Field Officer', '7665432109', 'manoj.sharma@gov.in', 36, 'Male', 5),
(12, 'Anjali', 'Nair', 'Field Officer', '7896123450', 'anjali.nair@gov.in', 33, 'Female', 5),
(13, 'Karan', 'Gupta', 'Field Officer', '8080765432', 'karan.gupta@gov.in', 37, 'Male', 6),
(14, 'Divya', 'Menon', 'Field Officer', '7554236789', 'divya.menon@gov.in', 30, 'Female', 6),
(15, 'Arun', 'Mishra', 'Quality Control Officer', '8123456789', 'arun.mishra@gov.in', 45, 'Male', NULL),
(16, 'Neha', 'Gupta', 'Subsidy Payment Officer', '9098765432', 'neha.gupta@gov.in', 41, 'Female', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `officer_login`
--

CREATE TABLE `officer_login` (
  `offID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer_login`
--

INSERT INTO `officer_login` (`offID`, `username`, `password`) VALUES
(1, 'rajesh.verma', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(2, 'anita.sharma', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(3, 'vikram.singh', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(4, 'meerakapoor', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(5, 'sureshyadav', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(6, 'priya.rao', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(7, 'amitk', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(8, 'snehap', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(9, 'dasravi', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(10, 'poonamj', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(11, 'manojsharma', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(12, 'anjalinair', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(13, 'karangupta', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(14, 'divyam', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(15, 'arunm', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.'),
(16, 'nehagupta', '$2y$10$Fw3TrXSDpf5Vxfj8AMhPA.Zmv2hHHpL3RAV2NzfrxtzLjCcZ.NIW.');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`farmerID`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `adhar` (`adhar`),
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
  ADD PRIMARY KEY (`fertID`);

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
  ADD KEY `fieldOfficer` (`registeredBy`),
  ADD KEY `reviewedBy` (`reviewedBy`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`offID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `supervisorID` (`supervisorID`);

--
-- Indexes for table `officer_login`
--
ALTER TABLE `officer_login`
  ADD PRIMARY KEY (`offID`),
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `farmerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `farmer_land`
--
ALTER TABLE `farmer_land`
  MODIFY `landID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `fertilizers`
--
ALTER TABLE `fertilizers`
  MODIFY `fertID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `fertilizer_distribution`
--
ALTER TABLE `fertilizer_distribution`
  MODIFY `distributionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3001;

--
-- AUTO_INCREMENT for table `fertilizer_requests`
--
ALTER TABLE `fertilizer_requests`
  MODIFY `requestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `offID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  ADD CONSTRAINT `fertilizer_requests_ibfk_4` FOREIGN KEY (`registeredBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fertilizer_requests_ibfk_5` FOREIGN KEY (`reviewedBy`) REFERENCES `officers` (`offID`) ON DELETE SET NULL;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_1` FOREIGN KEY (`supervisorID`) REFERENCES `officers` (`offID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `officer_login`
--
ALTER TABLE `officer_login`
  ADD CONSTRAINT `officer_login_ibfk_1` FOREIGN KEY (`offID`) REFERENCES `officers` (`offID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
