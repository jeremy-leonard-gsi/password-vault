-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: srv1.elite4god.com
-- Generation Time: Jan 13, 2023 at 04:12 PM
-- Server version: 10.5.18-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

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
CREATE DATABASE IF NOT EXISTS `pwvdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pwvdb`;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `accountId` bigint(20) NOT NULL,
  `system` varchar(512) DEFAULT NULL,
  `accountName` varchar(254) NOT NULL,
  `accountCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `accountCreatedBy` varchar(128) NOT NULL,
  `accountModified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `accountModifiedBy` varchar(255) NOT NULL,
  `accountNotes` varchar(1024) NOT NULL,
  `accountDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `url` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Table structure for table `acls`
--

DROP TABLE IF EXISTS `acls`;
CREATE TABLE `acls` (
  `aclId` bigint(20) NOT NULL,
  `accountId` bigint(20) NOT NULL,
  `group` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `key` varchar(256) NOT NULL,
  `value` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `config`
--

TRUNCATE TABLE `config`;
--
-- Dumping data for table `config`
--

INSERT INTO `config` (`key`, `value`) VALUES
('apikey', ''),
('authLDAPBaseDN', ''),
('authLDAPBindDN', ''),
('authLDAPFilter', '(&(objectclass=user)(!(objectclass=computer))(!(UserAccountControl:1.2.840.113556.1.4.803:=2)))'),
('authLDAPFullnameAttribute', 'displayname'),
('authLDAPSecret', ''),
('authLDAPURI', ''),
('authLDAPUserAttribute', 'samaccountname'),
('authType', 'LDAP'),
('base', ''),
('baseURI', '/'),
('debug', '0'),
('globalAdminGroupDN', ''),
('groupDNs', ''),
('logoURI', 'images/logo.png'),
('pwvDSN', ''),
('pwvPassword', ''),
('pwvUser', ''),
('requireSSL', '1'),
('title', 'Password Database'),
('userSource', 'LDAP');

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

DROP TABLE IF EXISTS `passwords`;
CREATE TABLE `passwords` (
  `passwordId` bigint(20) NOT NULL,
  `accountId` bigint(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `passwordCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `passwordCreatedBy` varchar(128) NOT NULL,
  `passwordActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountId`);

--
-- Indexes for table `acls`
--
ALTER TABLE `acls`
  ADD PRIMARY KEY (`aclId`),
  ADD UNIQUE KEY `accountId` (`accountId`,`group`);

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
  MODIFY `accountId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acls`
--
ALTER TABLE `acls`
  MODIFY `aclId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passwords`
--
ALTER TABLE `passwords`
  MODIFY `passwordId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acls`
--
ALTER TABLE `acls`
  ADD CONSTRAINT `acls_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`);

--
-- Constraints for table `passwords`
--
ALTER TABLE `passwords`
  ADD CONSTRAINT `passwords_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`);


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for table accounts
--

--
-- Metadata for table acls
--

--
-- Metadata for table config
--

--
-- Metadata for table passwords
--

--
-- Metadata for database pwvdb
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
