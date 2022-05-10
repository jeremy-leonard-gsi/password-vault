-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 10, 2022 at 09:15 PM
-- Server version: 10.5.12-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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

-- --------------------------------------------------------

--
-- Table structure for table `acls`
--

CREATE TABLE `acls` (
  `aclId` bigint(20) NOT NULL,
  `accountId` bigint(20) NOT NULL,
  `group` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
('authLDAPBaseDN', NULL),
('authLDAPBindDN', NULL),
('authLDAPFilter', NULL),
('authLDAPFullnameAttribute', NULL),
('authLDAPSecret', NULL),
('authLDAPURI', NULL),
('authLDAPUserAttribute', 'samaccountname'),
('authType', 'LDAP'),
('base', '/pwv-dev'),
('debug', '1'),
('globalAdminGroupDN', NULL),
('groupDNs', NULL),
('logoURI', 'images/logo.png'),
('pwvDSN', NULL),
('pwvPassword', NULL),
('pwvUser', NULL),
('requireSSL', NULL),
('title', 'Password Vault'),
('userSource', 'LDAP');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
