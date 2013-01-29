-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 28, 2013 at 08:38 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.6-1ubuntu1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `h4`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_achievements_metadata`
--

CREATE TABLE IF NOT EXISTS `ci_achievements_metadata` (
  `id` smallint(5) NOT NULL,
  `gamer_points` smallint(5) NOT NULL,
  `locked_description` varchar(128) CHARACTER SET utf8 NOT NULL,
  `unlocked_description` varchar(128) CHARACTER SET utf8 NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `unlocked_url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `unlocked_asset` varchar(64) CHARACTER SET utf8 NOT NULL,
  `locked_url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `locked_asset` varchar(64) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ci_gamertags`
--

CREATE TABLE IF NOT EXISTS `ci_gamertags` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `Expiration` int(10) NOT NULL,
  `Rank` int(3) NOT NULL,
  `RankImage` varchar(16) NOT NULL,
  `Specialization` varchar(64) NOT NULL,
  `SpecializationLevel` int(3) NOT NULL,
  `TotalGameWins` int(6) NOT NULL,
  `TotalGameQuits` int(6) NOT NULL,
  `KDRatio` decimal(6,2) NOT NULL,
  `MedalData` blob NOT NULL,
  `NextRankStartXP` int(10) NOT NULL,
  `RankStartXP` int(10) NOT NULL,
  `Gamertag` varchar(15) CHARACTER SET utf8 NOT NULL,
  `HashedGamertag` varchar(64) CHARACTER SET utf8 NOT NULL,
  `Xp` int(10) NOT NULL,
  `SpartanPoints` int(6) NOT NULL,
  `TotalChallengesCompleted` int(6) NOT NULL,
  `TotalCommendationProgress` decimal(6,2) NOT NULL,
  `TotalLoadoutItemsPurchased` int(6) NOT NULL,
  `TotalMedalsEarned` int(10) NOT NULL,
  `TotalGameplay` int(10) NOT NULL,
  `TotalKills` int(10) NOT NULL,
  `TotalDeaths` int(10) NOT NULL,
  `TotalGamesStarted` int(6) NOT NULL,
  `ServiceTag` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_news`
--

CREATE TABLE IF NOT EXISTS `ci_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
