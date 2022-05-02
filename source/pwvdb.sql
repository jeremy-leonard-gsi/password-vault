-- phpMyAdmin SQL Dump
-- version 5.0.4deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 02, 2022 at 04:22 PM
-- Server version: 10.5.15-MariaDB-0+deb11u1
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pwvdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `accountId` bigint(20) NOT NULL,
  `system` varchar(512) DEFAULT NULL,
  `url` varchar(2048) DEFAULT NULL,
  `accountName` varchar(254) NOT NULL,
  `accountCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `accountCreatedBy` varchar(128) NOT NULL,
  `accountModified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `accountModifiedBy` varchar(255) NOT NULL,
  `accountNotes` varchar(1024) NOT NULL,
  `accountDeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `key` varchar(256) NOT NULL,
  `value` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`key`, `value`) VALUES
('apikey', NULL),
('authLDAPBaseDN', ''),
('authLDAPBindDN', ''),
('authLDAPFilter', '(&(objectclass=user)(!(objectclass=computer))(!(UserAccountControl:1.2.840.113556.1.4.803:=2))(|(memberof=CN=Domain Admins,CN=Users,DC=kellogg,DC=kcc)))'),
('authLDAPFullnameAttribute', 'displayname'),
('authLDAPSecret', ''),
('authLDAPURI', ''),
('authLDAPUserAttribute', 'samaccountname'),
('authType', 'LDAP'),
('base', '/password-vault'),
('enableAPI', '0'),
('logoURI', ''),
('pwvPassword', ''),
('requireSSL', '1'),
('title', 'Password Vault');

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE `passwords` (
  `passwordId` bigint(20) NOT NULL,
  `accountId` bigint(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `passwordCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `passwordCreatedBy` varchar(128) NOT NULL,
  `passwordActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `passwords`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountId`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`passwordId`),
  ADD KEY `userid` (`accountId`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `passwords`
--
ALTER TABLE `passwords`
  MODIFY `passwordId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `passwords`
--
ALTER TABLE `passwords`
  ADD CONSTRAINT `passwords_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
