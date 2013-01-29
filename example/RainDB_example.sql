-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 19, 2013 at 07:20 AM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `RainDB_example`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_in_group`
--

CREATE TABLE `user_in_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_in_group`
--

INSERT INTO `user_in_group` (`user_id`, `group_id`) VALUES
(1, 1),
(2, 2),
(3, 1),
(5, 2),
(6, 2),
(7, 1);
