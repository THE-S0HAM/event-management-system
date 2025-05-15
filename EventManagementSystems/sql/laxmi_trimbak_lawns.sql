-- phpMyAdmin SQL Dump
-- Database: `evm_db`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `EventID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `StartDate` varchar(255) NOT NULL,
  `EndDate` varchar(255) NOT NULL,
  `Cost` int(11) NOT NULL,
  `LocationID` int(11) NOT NULL,
  `CeremonyType` varchar(100) DEFAULT NULL,
  `DecorationTheme` varchar(100) DEFAULT NULL,
  `CateringOption` varchar(100) DEFAULT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`EventID`, `Title`, `Description`, `StartDate`, `EndDate`, `Cost`, `LocationID`, `CeremonyType`, `DecorationTheme`, `CateringOption`, `ClientID`, `ImagePath`) VALUES
(1, 'Sharma Wedding', 'Traditional Marathi Wedding Ceremony with complete arrangements for havan, mandap, and all traditional customs. The venue will be decorated in Marathi Traditional theme with marigold flowers and traditional elements.', '15-Dec-2023', '18-Dec-2023', 150000, 1, 'Wedding', 'Marathi Traditional', 'Veg Deluxe', 1, 'images/tradinational-wed.jpg'),
(2, 'Patel Engagement', 'Traditional Sakharpuda Ceremony with Royal Rajasthani decoration theme. The venue will be decorated with vibrant colors, mirror work and Rajasthani elements. Veg Standard catering will be provided for all guests.', '10-Jan-2024', '10-Jan-2024', 50000, 2, 'Sakharpuda', 'Royal Rajasthani', 'Veg Standard', 2, 'images/sarkharpuda.jpg'),
(3, 'Desai Reception', 'Evening Reception Party with Bollywood Glam decoration theme. The spacious Lawn A will be transformed with modern styling and glamorous elements inspired by Bollywood. Veg & Non-Veg Deluxe catering will be provided.', '12-Feb-2024', '12-Feb-2024', 75000, 3, 'Reception', 'Bollywood Glam', 'Veg & Non-Veg Deluxe', 3, 'images/reception.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `LocationID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `ManagerFName` varchar(255) NOT NULL,
  `ManagerLName` varchar(255) NOT NULL,
  `ManagerEmail` varchar(255) NOT NULL,
  `ManagerNumber` varchar(15) NOT NULL,
  `MaxCapacity` int(11) NOT NULL,
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`LocationID`, `Name`, `Address`, `ManagerFName`, `ManagerLName`, `ManagerEmail`, `ManagerNumber`, `MaxCapacity`, `ImagePath`) VALUES
(1, 'Mangal Karyalay', 'JM5G+VP5, Waladgaon, Shrirampur, Maharashtra 413709', 'Sunil', 'Patil', 'laxmitribaklawns@gmail.com', '9876543210', 500, 'images/tradinational-wed.jpg'),
(2, 'Vivah Hall', 'JM5G+VP5, Waladgaon, Shrirampur, Maharashtra 413709', 'Rahul', 'Sharma', 'laxmitribaklawns@gmail.com', '9876543210', 300, 'images/sarkharpuda.jpg'),
(3, 'Lawn A', 'JM5G+VP5, Waladgaon, Shrirampur, Maharashtra 413709', 'Amit', 'Desai', 'laxmitribaklawns@gmail.com', '9876543210', 800, 'images/reception.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'admin123', 'admin'),
('manager', 'manager123', 'manager'),
('staff', 'staff123', 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `ClientID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `SpecialInstructions` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`ClientID`, `FirstName`, `LastName`, `Email`, `Phone`, `Address`, `SpecialInstructions`) VALUES
(1, 'Rajesh', 'Sharma', 'rajesh.sharma@example.com', '9876543210', 'Pune, Maharashtra', 'Require pandits for ceremony'),
(2, 'Ankit', 'Patel', 'ankit.patel@example.com', '8765432109', 'Mumbai, Maharashtra', 'Need special flower arrangements'),
(3, 'Neha', 'Desai', 'neha.desai@example.com', '7654321098', 'Nashik, Maharashtra', 'Require DJ setup and dance floor');

-- --------------------------------------------------------

--
-- Table structure for table `decoration_themes`
--

CREATE TABLE `decoration_themes` (
  `ThemeID` int(11) NOT NULL,
  `ThemeName` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `BaseCost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `decoration_themes`
--

INSERT INTO `decoration_themes` (`ThemeID`, `ThemeName`, `Description`, `BaseCost`) VALUES
(1, 'Marathi Traditional', 'Traditional Maharashtrian style with marigold flowers and traditional elements', 25000),
(2, 'Royal Rajasthani', 'Vibrant colors with mirror work and Rajasthani elements', 35000),
(3, 'Bollywood Glam', 'Modern styling with glamorous elements inspired by Bollywood', 30000),
(4, 'South Indian', 'Traditional South Indian style with banana leaves and jasmine flowers', 25000),
(5, 'Punjabi Vibrant', 'Colorful and energetic Punjabi style decorations', 28000);

-- --------------------------------------------------------

--
-- Table structure for table `ceremony_types`
--

CREATE TABLE `ceremony_types` (
  `CeremonyID` int(11) NOT NULL,
  `CeremonyName` varchar(100) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ceremony_types`
--

INSERT INTO `ceremony_types` (`CeremonyID`, `CeremonyName`, `Description`) VALUES
(1, 'Wedding', 'Full wedding ceremony with all rituals'),
(2, 'Reception', 'Post-wedding celebration party'),
(3, 'Engagement', 'Ring ceremony and engagement celebration'),
(4, 'Sakharpuda', 'Traditional Maharashtrian engagement ceremony'),
(5, 'Haldi', 'Pre-wedding turmeric ceremony'),
(6, 'Sangeet', 'Music and dance celebration before wedding'),
(7, 'Mehendi', 'Henna application ceremony');

-- --------------------------------------------------------

--
-- Table structure for table `catering_options`
--

CREATE TABLE `catering_options` (
  `CateringID` int(11) NOT NULL,
  `OptionName` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `CostPerPlate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `catering_options`
--

INSERT INTO `catering_options` (`CateringID`, `OptionName`, `Description`, `CostPerPlate`) VALUES
(1, 'Veg Standard', 'Basic vegetarian menu with starters, main course and dessert', 350),
(2, 'Veg Deluxe', 'Premium vegetarian menu with extensive options', 500),
(3, 'Veg & Non-Veg Standard', 'Mixed menu with basic options', 450),
(4, 'Veg & Non-Veg Deluxe', 'Premium mixed menu with extensive options', 650),
(5, 'Maharashtrian Special', 'Traditional Maharashtrian cuisine', 400);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(11) NOT NULL,
  `EventID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `Rating` int(1) NOT NULL,
  `Comments` text,
  `FeedbackDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FeedbackID`, `EventID`, `ClientID`, `Rating`, `Comments`, `FeedbackDate`) VALUES
(1, 1, 1, 5, 'Excellent service and beautiful venue!', '2023-12-20'),
(2, 2, 2, 4, 'Great decorations but food could be improved', '2024-01-12');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `EventID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `Amount` int(11) NOT NULL,
  `PaymentDate` date NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `PaymentStatus` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentID`, `EventID`, `ClientID`, `Amount`, `PaymentDate`, `PaymentMethod`, `PaymentStatus`) VALUES
(1, 1, 1, 50000, '2023-11-15', 'Bank Transfer', 'Advance'),
(2, 1, 1, 100000, '2023-12-18', 'Cash', 'Completed'),
(3, 2, 2, 25000, '2023-12-10', 'Bank Transfer', 'Advance');

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `LocationID` (`LocationID`),
  ADD KEY `ClientID` (`ClientID`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`LocationID`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`ClientID`);

--
-- Indexes for table `decoration_themes`
--
ALTER TABLE `decoration_themes`
  ADD PRIMARY KEY (`ThemeID`);

--
-- Indexes for table `ceremony_types`
--
ALTER TABLE `ceremony_types`
  ADD PRIMARY KEY (`CeremonyID`);

--
-- Indexes for table `catering_options`
--
ALTER TABLE `catering_options`
  ADD PRIMARY KEY (`CateringID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `LocationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `ClientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `decoration_themes`
--
ALTER TABLE `decoration_themes`
  MODIFY `ThemeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ceremony_types`
--
ALTER TABLE `ceremony_types`
  MODIFY `CeremonyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `catering_options`
--
ALTER TABLE `catering_options`
  MODIFY `CateringID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`LocationID`) REFERENCES `locations` (`LocationID`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;