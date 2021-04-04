-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2021 at 12:38 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Description` text CHARACTER SET utf8 DEFAULT NULL,
  `Parent_Catg` int(11) NOT NULL DEFAULT 0,
  `Ordering` int(11) NOT NULL,
  `Visibility` tinyint(1) NOT NULL DEFAULT 1,
  `Allow_comments` tinyint(1) NOT NULL DEFAULT 1,
  `Allow_Advs` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Parent_Catg`, `Ordering`, `Visibility`, `Allow_comments`, `Allow_Advs`) VALUES
(1, 'Vehicles', 'cars , bus , vans , truks', 0, 1, 1, 1, 1),
(2, 'Books', 'All Books', 0, 2, 1, 1, 1),
(3, 'Computers', 'Computers and laptops and tools', 0, 3, 1, 1, 1),
(4, 'Clothing', 'Women and Men and Babies', 0, 4, 1, 1, 1),
(5, 'Apps And Games', 'Apps and Games', 0, 5, 1, 1, 1),
(6, 'Smart Home', 'all smart device home ', 0, 6, 1, 1, 1),
(7, 'Hand Made', 'all Hand Made', 0, 7, 1, 0, 1),
(8, 'Mobiles-Tablets', 'Mobail and tablet Category', 0, 5, 1, 1, 1),
(9, 'Samsung', 'Samsung Mobiles and tablets', 8, 5, 1, 1, 1),
(11, 'Apple ', 'Apple Mobiles and tablets', 8, 6, 1, 1, 1),
(12, 'OPPO', 'ssssssssss', 8, 50, 1, 1, 1),
(13, 'Hand', 'MackBook Version 10.5', 0, 22, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Comment_ID` int(11) NOT NULL,
  `Comment` text CHARACTER SET utf8 NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `Comment_Date` datetime NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Comment_ID`, `Comment`, `Status`, `Comment_Date`, `item_id`, `user_id`) VALUES
(1, 'hello man ', 1, '2020-10-06 15:10:20', 2, 5),
(2, 'very nice', 1, '2020-10-05 17:27:21', 1, 5),
(3, 'very good very good very good ', 1, '2020-10-05 17:27:21', 1, 5),
(16, 'def', 1, '2020-10-19 16:16:10', 11, 5),
(17, 'Good Price ^^', 1, '2020-10-19 16:16:32', 11, 16),
(18, 'I will Buy it , it is very good price', 1, '2020-10-19 16:16:58', 11, 13),
(19, 'Where Are you Live ?', 1, '2020-10-19 16:17:19', 11, 20),
(21, 'I like it', 1, '2020-10-19 18:24:49', 11, 11),
(22, 'How Are You', 1, '2020-10-19 18:25:04', 11, 11),
(23, 'hello', 1, '2020-10-19 20:13:33', 11, 11),
(24, 'hi', 1, '2020-10-20 14:50:26', 11, 11),
(25, 'hi', 1, '2020-10-20 14:50:28', 11, 11),
(26, 'Good Price', 1, '2020-10-20 23:06:17', 9, 11),
(27, 'nice', 1, '2020-10-20 23:18:46', 4, 11),
(28, 'hello', 1, '2020-10-22 01:47:40', 13, 28),
(29, 'Good Price', 1, '2020-10-22 01:59:25', 15, 28),
(30, 'good', 1, '2020-10-22 23:23:29', 15, 29),
(31, 'hello', 1, '2020-10-27 20:07:38', 11, 30);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `items_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` int(11) NOT NULL,
  `Add_Date` date NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `accepte` tinyint(4) NOT NULL DEFAULT 0,
  `Tags` varchar(255) DEFAULT NULL,
  `Catg_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`items_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Image`, `Country_Made`, `Status`, `Rating`, `accepte`, `Tags`, `Catg_ID`, `Member_ID`) VALUES
(1, 'HP Laptop 1005', 'Ram 4GB , HardDisk 500GB , SSD 50GB, Cpu Intel Core i5 , Gbu AMD , Battery 8000 , ', 300, '2020-10-13', '', 'United States', 'New', 0, 1, NULL, 3, 5),
(2, 'Dell Laptop', 'Ram 8GB , HardDisk 1TB', 400, '2020-10-13', '', 'United States', 'New', 0, 1, NULL, 3, 5),
(3, 'I Will', 'good book to reading', 10, '2020-10-13', '', 'Angola', 'New', 0, 1, NULL, 2, 5),
(4, 'Bmw M5 2016', 'Turbo and 500 HP', 100000, '2020-10-13', '', 'Germany', 'Used', 0, 1, NULL, 1, 5),
(5, 'Amazon Echo', 'Smart Speaker and Microphone', 100, '2020-10-13', '', 'United States', 'Used', 0, 1, NULL, 6, 5),
(9, 'HP Laptop 2005', '8GB Ram  ,  And 1000Gb HardDisk', 400, '2020-10-17', '', 'United States', 'New', 0, 1, NULL, 3, 5),
(10, 'uftss', 'jackets and shirts and pants and sweaters', 400, '2020-10-17', '', 'Albania', 'Used', 0, 1, NULL, 4, 5),
(11, 'MackBook Version 10.5', '500GB Storage', 350, '2020-10-19', '', 'United States', 'Used', 0, 1, NULL, 3, 5),
(13, 'MackBook 8', '8GB Ram  ,  And 1000Gb HardDisk', 400, '2020-10-21', '', 'United States', 'Used', 0, 1, NULL, 3, 22),
(14, 'HP Laptop 3005', '16GB Ram 2TB Hard', 600, '2020-10-21', '', 'United States', 'Used', 0, 1, NULL, 3, 22),
(15, 'I Phone X', 'Good Phone', 350, '2020-10-22', '', 'Jordan', 'Used', 0, 1, NULL, 8, 28),
(16, 'I Phone 8+', 'I phone 8+ 256GB', 350, '2020-10-22', '', 'United States', 'Used', 0, 1, 'iphone,iphone8,mobile', 11, 9),
(17, 'HP Laptop 1000', '8GB Ram  ,  And 500Gb HardDisk', 290, '2020-10-22', '', 'Jordan', 'Used', 0, 1, 'Laptops , Hp , Computers', 3, 28),
(18, 'I Phone Xs', '4GB Ram color is red', 400, '2020-10-22', '', 'Turkey', 'Used', 0, 1, 'iphone,iphonexs,mobile', 11, 11),
(19, 'I Pad Pro', 'Good PR', 400, '2020-10-22', '', 'Cyprus', 'New', 0, 1, 'iphone,iphone8,mobile', 11, 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'ID of user',
  `UserName` varchar(255) NOT NULL COMMENT 'username to login',
  `Password` varchar(255) CHARACTER SET utf8mb4 NOT NULL COMMENT 'password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `TypeUser` int(11) NOT NULL DEFAULT 0 COMMENT '0 : that mean user , 1 : that mean admin',
  `AccepteAccount` int(11) NOT NULL DEFAULT 0,
  `SellerRank` int(11) NOT NULL DEFAULT 0,
  `RegDate` datetime DEFAULT NULL,
  `Image_Profile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Password`, `Email`, `FullName`, `TypeUser`, `AccepteAccount`, `SellerRank`, `RegDate`, `Image_Profile`) VALUES
(1, 'user', 'user', 'user@user.com', 'user', 0, 0, 0, NULL, 'null');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Comment_ID`),
  ADD KEY `comment_item` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`items_ID`),
  ADD KEY `member` (`Member_ID`),
  ADD KEY `catg` (`Catg_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Comment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `items_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of user', AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`items_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `catg` FOREIGN KEY (`Catg_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
