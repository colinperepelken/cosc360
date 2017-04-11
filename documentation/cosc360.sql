-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2017 at 11:25 PM
-- Server version: 5.6.33-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cosc360`
--

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `forum_id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_name` varchar(50) NOT NULL,
  PRIMARY KEY (`forum_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`forum_id`, `forum_name`) VALUES
(1, 'Technical'),
(2, 'Pictures'),
(3, 'Videos'),
(4, 'Engines'),
(5, 'Classifieds');

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE IF NOT EXISTS `threads` (
  `thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `poster_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(3000) DEFAULT NULL,
  `posted_time` varchar(20) DEFAULT NULL,
  `forum_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`thread_id`),
  KEY `poster_id` (`poster_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`thread_id`, `poster_id`, `title`, `content`, `posted_time`, `forum_id`, `points`) VALUES
(12, 6, 'Post in Engines', '<p>I need some help my distributor got wet!!!</p>', '2017/04/08', 4, 8),
(13, 6, 'No spark on cylinders #3 and #4', '<p>Issue with my coil pack?</p>', '2017/04/08', 1, 11),
(14, 13, '1996 Subaru Legacy Wagon for sale', '<p>Check out my leggy</p>\r\n<p>402666kms, 5spd, basket drilled into roof, rusty, leaky exhaust that slowly kills you as you drive it.</p>\r\n<p>&nbsp;</p>\r\n<p><img src="https://scontent-sea1-1.xx.fbcdn.net/v/t1.0-0/p180x540/17883920_773261066184240_549180688967622542_n.jpg?oh=6ad7df25ab1af59a812de83bc851d858&amp;oe=598EF370" alt="Ad picture" width="720" height="540" /></p>\r\n<p>$500 OBO</p>\r\n<p>Please message me on R3DLINE!</p>', '2017/04/11', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `thread_replies`
--

CREATE TABLE IF NOT EXISTS `thread_replies` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `poster_id` int(11) NOT NULL,
  `posted_time` varchar(20) DEFAULT NULL,
  `content` varchar(3000) DEFAULT NULL,
  `thread_id` int(11) NOT NULL,
  PRIMARY KEY (`reply_id`),
  KEY `thread_id` (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `thread_replies`
--

INSERT INTO `thread_replies` (`reply_id`, `poster_id`, `posted_time`, `content`, `thread_id`) VALUES
(40, 14, '2017/04/11', '<p>Very interested!!! Please message me soon! <br data-mce-bogus="1"></p><p><br data-mce-bogus="1"></p><p>Has the timing belt and headgasket been done?<br data-mce-bogus="1"></p><p><br data-mce-bogus="1"></p><p>John<br data-mce-bogus="1"></p>', 14),
(23, 6, '2017/04/07', '<p>test timestamp on reply</p>', 11),
(24, 6, '2017/04/07', '<p>hey nice reply</p>', 11),
(25, 6, '2017/04/07', '<p>anoter nice reply</p>', 11),
(26, 6, '2017/04/07', 'niuce reply', 11),
(27, 6, '2017/04/07', 'afdsafdsaf', 11),
(28, 6, '2017/04/07', 'adfasdfasf', 11),
(29, 6, '2017/04/07', '<p>testing<br data-mce-bogus="1"></p>', 11),
(30, 6, '2017/04/07', '<p>test<br data-mce-bogus="1"></p>', 11),
(31, 6, '2017/04/07', '<p>test<br data-mce-bogus="1"></p>', 11),
(32, 6, '2017/04/07', '<p>dfdsafdsafadsfdsaf<br data-mce-bogus="1"></p>', 11),
(33, 6, '2017/04/08', '<p>another test<br data-mce-bogus="1"></p>', 11),
(34, 6, '2017/04/08', '<p>new comment<br data-mce-bogus="1"></p>', 9),
(35, 6, '2017/04/08', '<p>another comment from your favourite admin!<br data-mce-bogus="1"></p>', 9),
(36, 6, '2017/04/08', '<p>another sick comment from your fav admin<br data-mce-bogus="1"></p>', 11),
(37, 6, '2017/04/08', '<p>asdfsdfd<br data-mce-bogus="1"></p>', 11),
(38, 6, '2017/04/08', '<p>nice comment from the admin here<br data-mce-bogus="1"></p>', 7),
(39, 12, '2017/04/09', '<p>Check your ignitor.<br data-mce-bogus="1"></p>', 13);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(32) NOT NULL,
  `profile_image_path` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `bio` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `profile_image_path`, `is_admin`, `is_banned`, `bio`, `location`) VALUES
(9, 'britdawg666', 'brit@neopets.gov', '1a1dc91c907325c69271ddf0c944bc72', 'images/profiles/tumblr_n9veb4DG4n1rp76n8o1_400.png', 0, 0, '', ''),
(3, 'tester', 'c1dolinjbernard@hotmail.com', '098f6bcd4621d373cade4e832627b4f6', '', 0, 0, '', ''),
(4, 'dude', 'lalalal@email.com', '1a1dc91c907325c69271ddf0c944bc72', '', 0, 1, '', ''),
(5, 'dude1', '1lalalal@email.com', 'c4ca4238a0b923820dcc509a6f75849b', '', 0, 0, '', ''),
(6, 'admin', 'admin@a.com', '084e0343a0486ff05530df6c705c8bb4', 'images/profiles/Selection_020.png', 1, 0, 'im an admin!!1', 'AdminTown'),
(7, 'bernardx', 'bernard@hotmail.com', '6ecc7acfdda63a003b5953b5b3d05000', 'images/profiles/ryan.jpg', 0, 0, 'like my pic?', 'Kelowna, BC'),
(8, '3', '3', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', '', 0, 1, '', ''),
(10, 'mackenzie', 'mackenziesalloum@gmail.com', '32250170a0dca92d53ec9624f336ca24', '', 0, 0, '', ''),
(11, 'plswork', 'plswork@email.com', '32250170a0dca92d53ec9624f336ca24', ' ', 0, 0, '', ''),
(12, 'colinbernard', 'colin@email.com', '32250170a0dca92d53ec9624f336ca24', ' ', 0, 0, 'No bio set.', 'No location set.'),
(13, 'Suhff', 'internal.coordinator.qscu@gmail.com', '32250170a0dca92d53ec9624f336ca24', 'images/profiles/terminal2.jpg', 0, 0, '1996 Legacy 2.2, 1998 Outback', 'Kelowna, BC'),
(14, 'JohnBoy', 'jogn@emasil.com', '32250170a0dca92d53ec9624f336ca24', 'images/profiles/terminal1.jpg', 0, 0, '325is LSD', 'Vernon, BC, Canada');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
