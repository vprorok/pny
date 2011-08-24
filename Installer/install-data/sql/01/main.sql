--
-- phpPennyAuction version 2.4.2
-- Database Schema
-- Copyright Scriptmatix


SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `bids` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `accounts`
--


-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_address_type_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `suburb` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL,
  `phone` varchar(80) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address_type_id` (`user_address_type_id`),
  KEY `user_id` (`user_id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Table structure for table `affiliates`
--

CREATE TABLE IF NOT EXISTS `affiliates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `credit` int(11) NOT NULL,
  `debit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_codes`
--

CREATE TABLE IF NOT EXISTS `affiliate_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `credit` decimal(30,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE IF NOT EXISTS `auctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `max_end` tinyint(1) NOT NULL,
  `max_end_time` datetime NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `autolist` tinyint(1) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `peak_only` tinyint(1) NOT NULL,
  `nail_bitter` tinyint(1) NOT NULL,
  `penny` tinyint(1) NOT NULL,
  `free` tinyint(1) NOT NULL DEFAULT '0',
  `hidden_reserve` decimal(30,2) NOT NULL,
  `beginner` tinyint(1) NOT NULL,
  `reverse` tinyint(1) NOT NULL,
  `autobids` int(11) NOT NULL,
  `random` decimal(30,2) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `winner_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `closed` tinyint(1) NOT NULL,
  `closed_status` tinyint(4) NOT NULL,
  `bid_debit` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=97 ;


-- --------------------------------------------------------

--
-- Table structure for table `auction_emails`
--

CREATE TABLE IF NOT EXISTS `auction_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `autobids`
--

CREATE TABLE IF NOT EXISTS `autobids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `deploy` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2673941 ;

-- --------------------------------------------------------

--
-- Table structure for table `bidbutlers`
--

CREATE TABLE IF NOT EXISTS `bidbutlers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `maximum_price` decimal(30,2) NOT NULL,
  `bids` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `credit` int(11) NOT NULL,
  `debit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1047647 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `featured` (`featured`),
  KEY `featured_2` (`featured`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `lft`, `rght`, `name`, `featured`, `meta_description`, `meta_keywords`, `image`, `created`, `modified`) VALUES
(2, 0, 3, 4, 'Coffee & Espresso', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(3, 0, 5, 6, 'Television', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(4, 0, 7, 8, 'Cash and Coupons', 1, '', '', '', '2008-09-29 12:02:00', '2010-12-01 11:50:47'),
(5, 0, 9, 10, 'iPods, MP3 & Audio', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(6, 0, 11, 12, 'Consoles & Games', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(7, 0, 13, 14, 'House & Garden', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(8, 0, 15, 16, 'Laptops & Notebooks', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(9, 0, 17, 18, 'Cellphones & Telephones', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(10, 0, 19, 20, 'PCs & Accessories', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(11, 0, 21, 22, 'Photography', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(12, 0, 23, 24, 'GPS', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(13, 0, 25, 26, 'Watches & Sunglasses', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(14, 0, 27, 28, 'Health & Fitness', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(15, 0, 29, 30, 'Fragrances', 0, '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(16, 0, 31, 32, 'Kids Toys', 1, '', '', '', '2008-09-29 12:02:00', '2010-11-19 10:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `created`, `modified`) VALUES
(1, 'GB', 'United Kingdom', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '', 'United States of America', '2010-08-12 11:47:17', '2010-08-12 11:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `saving` decimal(30,2) NOT NULL,
  `coupon_type_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `saving`, `coupon_type_id`, `created`, `modified`) VALUES
(1, 'TENPERCENT', 10.00, 1, '2010-08-12 11:49:34', '2010-08-12 11:49:34');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_types`
--

CREATE TABLE IF NOT EXISTS `coupon_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `coupon_types`
--

INSERT INTO `coupon_types` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Percentage', '2008-12-10 15:18:06', '2008-12-10 15:18:06'),
(2, 'Total Off', '2008-12-10 15:18:06', '2008-12-10 15:18:06'),
(3, 'Free Bids', '2008-12-10 15:18:06', '2008-12-10 15:18:06'),
(4, 'Percentage Free Bids', '2009-03-01 20:53:03', '0000-00-00 00:00:00'),
(5, 'Free Rewards', '2009-03-06 18:24:03', '2009-03-06 18:24:07'),
(6, 'Free Registration Bids', '2009-05-04 23:58:49', '2009-05-04 23:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `credits`
--

CREATE TABLE IF NOT EXISTS `credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `credit` decimal(30,2) NOT NULL,
  `debit` decimal(30,2) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) NOT NULL,
  `rate` decimal(30,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency`, `rate`) VALUES
(1, 'USD', 1.0000),
(2, 'GBP', 0.5000);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `email`) VALUES
(1, 'Sales', ''),
(2, 'Abuse', ''),
(3, 'Investors', '');

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE IF NOT EXISTS `genders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_default_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

INSERT INTO `images` (`id`, `product_id`, `image`, `image_default_id`, `order`, `created`, `modified`) VALUES
(1, 1, '0b1c505f688d457796c522972f7541ed8a7bbd51.jpg', 0, 0, '2008-10-21 10:31:20', '2008-10-21 10:31:20'),
(2, 1, '84063b2d90511e94785d5a99bde2074a96841612.jpg', 0, 1, '2008-10-21 10:31:29', '2008-10-21 10:31:29'),
(3, 1, '99b3ccaf0e3efc5ac52d0cdb116206216219539f.jpg', 0, 2, '2008-10-21 10:31:37', '2008-10-21 10:31:37'),
(5, 2, 'fde59360d7804d2bde72b2119583e9c1a7a5d359.jpg', 0, 0, '2008-10-28 09:30:29', '2008-10-28 09:30:29'),
(6, 2, 'b076521359dc53f6e5c1e2db0838d2de1c4b2ba3.jpg', 0, 1, '2008-10-28 09:30:52', '2008-10-28 09:30:52');


-- --------------------------------------------------------

--
-- Table structure for table `image_defaults`
--

CREATE TABLE IF NOT EXISTS `image_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `image_defaults`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `server_name` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `limits`
--

CREATE TABLE IF NOT EXISTS `limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `brief` text NOT NULL,
  `content` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `meta_description`, `meta_keywords`, `brief`, `content`, `created`, `modified`) VALUES
(5, 'Example News Article For Your Website', 'Example News Article Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna ', 'example,news,article,1', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2010-08-12 11:24:10', '2010-08-12 11:24:10'),
(6, 'test Another New Article', 'Example News Article', 'add,news,article,2', 'This is just an example. You can add your own news here. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."', 'This is just an example. You can add your own news here. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."', '2010-08-12 11:25:00', '2010-10-09 15:02:50'),
(7, 'test', 'test', 'test', 'test', '<p>\r\n	test</p>\r\n', '2010-11-23 14:31:46', '2010-11-23 14:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `sent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) NOT NULL,
  `method` varchar(25) NOT NULL,
  `model` varchar(25) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fulfilled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bids` int(11) NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `gateway_url` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `bids`, `price`, `gateway_url`, `created`, `modified`) VALUES
(1, 'Basic Package', 100, 10.00, '', '2008-09-25 17:28:34', '2008-09-25 17:28:34'),
(2, 'Premium package', 500, 40.00, '', '2010-08-12 11:49:03', '2010-08-12 11:49:03');

-- --------------------------------------------------------

--
-- Table structure for table `package_points`
--

CREATE TABLE IF NOT EXISTS `package_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `top_show` tinyint(1) NOT NULL,
  `top_order` int(11) NOT NULL,
  `bottom_show` tinyint(1) NOT NULL,
  `bottom_order` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `title`, `meta_description`, `meta_keywords`, `content`, `slug`, `top_show`, `top_order`, `bottom_show`, `bottom_order`, `created`, `modified`) VALUES
(3, 'Privacy', 'Privacy', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '', '<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'privacy', 0, 0, 0, 0, '2010-08-12 11:32:16', '2010-08-12 11:32:16'),
(4, 'About', 'About', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '<p>\r\n	Sample About Us Page here. Can be edited from your Admin Panel under Manage Content -&gt; Pages. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'about', 0, 0, 0, 0, '2010-08-12 11:33:11', '2010-08-12 11:33:11'),
(5, 'Terms', 'Terms', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia volupta', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '<p>\r\n	Your Terms of Use go here. We do NOT provide a default template for this. You can edit your Terms of Use page (this page!) in your Admin Panel under Manage Content -&gt; Pages. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?v</p>', 'terms', 0, 0, 0, 0, '2010-08-12 11:34:13', '2010-08-12 11:34:13'),
(6, 'Guide', 'Guide', '', '', '<p>\r\n	Your Sample Guide/Intro page here.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>', 'guide', 0, 0, 0, 0, '2010-08-12 11:35:12', '2010-08-12 11:35:12'),
(7, 'Sitemap', 'Sitemap', '', '', '<p>\r\n	Sitemap generator coming soon! Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'sitemap', 0, 0, 0, 0, '2010-08-12 11:35:39', '2010-08-12 11:35:39'),
(8, 'Start', 'Start', '', '', '<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'start', 0, 0, 0, 0, '2010-08-12 11:37:47', '2010-08-12 11:37:47');

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE IF NOT EXISTS `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `brief` text NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `rrp` decimal(30,2) NOT NULL,
  `start_price` decimal(30,2) NOT NULL,
  `delivery_cost` decimal(30,2) NOT NULL,
  `delivery_information` text NOT NULL,
  `fixed` tinyint(1) NOT NULL,
  `fixed_price` decimal(30,2) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `autobid` tinyint(1) NOT NULL,
  `autobid_limit` int(11) NOT NULL,
  `limit_id` int(11) NOT NULL,
  `stock` tinyint(1) NOT NULL,
  `stock_number` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `buy_now` decimal(30,2) NOT NULL,
  `auto_bid_credit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `meta_description`, `meta_keywords`, `brief`, `description`, `category_id`, `rrp`, `start_price`, `delivery_cost`, `delivery_information`, `fixed`, `fixed_price`, `minimum_price`, `autobid`, `autobid_limit`, `limit_id`, `stock`, `stock_number`, `code`, `buy_now`, `auto_bid_credit`, `created`, `modified`, `deleted`) VALUES
(1, 'Lorem ipsum nostro vocent posidonium ut', '', '', 'Lorem ipsum nostro vocent posidonium ut eum, ei quo error salutandi. Mea debitis detraxit rationibus ut, eros simul atomorum ea eam. Eu sit aliquam atomorum facilisis, est cu mutat oblique tractatos. Vel et tota vitae deseruisse, vis et accusata eleifend sapientem. An suscipit lobortis intellegebat his, affert volumus appellantur has id.', '<p>Lorem ipsum nostro vocent posidonium ut eum, ei quo error salutandi. Mea debitis detraxit rationibus ut, eros simul atomorum ea eam. Eu sit aliquam atomorum facilisis, est cu mutat oblique tractatos. Vel et tota vitae deseruisse, vis et accusata eleifend sapientem. An suscipit lobortis intellegebat his, affert volumus appellantur has id.</p>\r\n<p>His ea quidam voluptatibus, dolore verterem accommodare vim ex, mei te stet iisque interpretaris. Usu no nostrum argumentum, omnis reprimique consequuntur ex pri, illum clita melius id pro. Velit everti in vel, an eum magna adipisci comprehensam. Vix mutat dicunt splendide at, est ad dico graeci regione, per timeam urbanitas et. Ei impedit appetere est, pro zzril affert corpora in.</p>', 2, 1212.00, 1000.00, 12.00, 'sdfasda', 0, 0.00, 1575.60, 0, 0, 0, 0, 0, '', 0.00, 0, '2008-10-21 10:29:32', '2008-10-28 11:46:30', 0),
(2, 'Allergy Manager', '', '', 'as', '', 2, 232.00, 100.00, 12.00, 'asas', 0, 0.00, 301.60, 0, 0, 0, 0, 0, '', 0.00, 0, '2008-10-27 09:59:31', '2010-02-22 11:43:27', 1),
(3, 'ASUS N10Jh', 'The ASUS N10Jh  notebook epitomizes style and productivity through smart technologies and innovative product design. Powered by an Intel Atom processor for energy efficient', 'asus,nj10h', 'The ASUS N10Jh notebook epitomizes style and productivity', '<p class="product-desc">\r\n	The ASUS N10Jh notebook epitomizes style and productivity through smart technologies and innovative product design. Powered by an Intel Atom processor for energy efficient and versatile multitasking capabilities, the ASUS N10Jh combines portability in the form of extended battery life and high-speed mobile Internet access, with extensive storage options and typing ergonomics, all encapsulated in an ultra-stylish and undeniably trendy design.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<span style="font-weight: bold;">Features:</span></p>\r\n<ul>\r\n	General<br />\r\n	<li>\r\n		System Type: Netbook</li>\r\n	<li>\r\n		Built-in Devices: Speaker, wireless LAN aerial, Bluetooth aerial</li>\r\n	<li>\r\n		Embedded Security: Fingerprint reader</li>\r\n	<li>\r\n		Width: 27.6 cm</li>\r\n	<li>\r\n		Depth: 19.5 cm</li>\r\n	<li>\r\n		Height: 3.7 cm</li>\r\n	<li>\r\n		Weight: 1.4 kg<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Processor</span></li>\r\n	<li>\r\n		Processor: Intel Atom N280 / 1.66 GHz</li>\r\n	<li>\r\n		Data Bus Speed: 667 MHz</li>\r\n	<li>\r\n		Chipset Type: Mobile Intel 945GSE Express</li>\r\n	<li>\r\n		Cache Memory</li>\r\n	<li>\r\n		Type: L2 Cache</li>\r\n	<li>\r\n		Installed Size: 512 KB<br />\r\n		<br />\r\n		<span style="font-weight: bold;">RAM</span></li>\r\n	<li>\r\n		Installed Size: 2 GB / 2 GB (max)</li>\r\n	<li>\r\n		Technology: DDR2 SDRAM - 667 MHz</li>\r\n	<li>\r\n		Form Factor: SO DIMM 200-pin</li>\r\n	<li>\r\n		Configuration Features: 1 x 2 GB<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Storage Controller</span></li>\r\n	<li>\r\n		Type: Serial ATA</li>\r\n	<li>\r\n		Serial ATA Interface: Serial ATA-150<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Storage</span></li>\r\n	<li>\r\n		Hard Drive: 250 GB - Serial ATA-150 - 5400 rpm<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Card Reader</span></li>\r\n	<li>\r\n		Type: 8 in 1 card reader</li>\r\n	<li>\r\n		Supported Flash Memory Cards: SD Memory Card, Memory Stick, Memory Stick PRO, MultiMediaCard, Memory Stick Duo<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Display</span></li>\r\n	<li>\r\n		Display Type: 10.2&quot; TFT</li>\r\n	<li>\r\n		Max Resolution: 1024 x 600 ( WSVGA )</li>\r\n	<li>\r\n		Widescreen Display: Yes</li>\r\n	<li>\r\n		Features: Color Shine, LED-backlit, ASUS Splendid Video Intelligence Technology, glare<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Video</span></li>\r\n	<li>\r\n		Graphics Processor / Vendor: NVIDIA GeForce G105M / Intel GMA 950 Dynamic Video Memory Technology 3.0</li>\r\n	<li>\r\n		Multi-GPU Configuration: 1 single GPU card / integrated GPU (Hybrid SLI)</li>\r\n	<li>\r\n		Video Memory: 512 MB<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Audio</span></li>\r\n	<li>\r\n		Audio Output: Sound card</li>\r\n	<li>\r\n		Audio Input: Microphone<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Notebook Camera</span></li>\r\n	<li>\r\n		Camera Type: Integrated</li>\r\n	<li>\r\n		Sensor Resolution: 1.3 Megapixel<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Input Device(s)</span></li>\r\n	<li>\r\n		Type: Keyboard, touchpad<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Networking</span></li>\r\n	<li>\r\n		Networking: Network adapter</li>\r\n	<li>\r\n		Wireless LAN Supported: Yes</li>\r\n	<li>\r\n		Data Link Protocol: Ethernet, Fast Ethernet, IEEE 802.11b, IEEE 802.11g, IEEE 802.11n (draft), Bluetooth 2.0 EDR</li>\r\n	<li>\r\n		Compliant Standards: IEEE 802.11b, IEEE 802.11g, IEEE 802.11n (draft), Bluetooth 2.0<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Expansion / Connectivity</span></li>\r\n	<li>\r\n		Expansion Slots Total (Free): 1 ( 0 ) x memory - SO DIMM 200-pin 1 ( 1 ) x ExpressCard</li>\r\n	<li>\r\n		Interfaces: 1 x display / video - HDMI - 19 pin HDMI Type A 1 x display / video - VGA - 15 pin HD D-Sub (HD-15) 3 x Hi-Speed USB - 4 PIN USB Type A 1 x microphone - input - mini-phone 3.5mm 1 x audio - SPDIF output/headphones - mini-phone 3.5mm 1 x network - Ethernet 10Base-T/100Base-TX - RJ-45<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Miscellaneous</span></li>\r\n	<li>\r\n		Features: Security lock slot (cable lock sold separately), system password, hard drive password, Express Gate<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Power</span></li>\r\n	<li>\r\n		Power Device: External</li>\r\n	<li>\r\n		Voltage Required: AC 120/230 V ( 50/60 Hz )<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Battery</span></li>\r\n	<li>\r\n		Technology: 6-cell</li>\r\n	<li>\r\n		Installed Qty: 1</li>\r\n	<li>\r\n		Capacity: 4800 mAh<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Operating System / Software</span></li>\r\n	<li>\r\n		OS Provided: Microsoft Windows Vista Business / XP Professional downgrade</li>\r\n	<li>\r\n		OS Preinstalled: Windows Vista</li>\r\n	<li>\r\n		Microsoft Office Ready: Includes a preinstalled image of select 2007 Microsoft Office suites. Purchase a Medialess License Kit (MLK) to activate the software.<br />\r\n		Software: CyberLink Power2Go, ASUS Splendid, ASUS Zoom In, ASUS Live Update, ASUS Screen Saver, ASUS Fancy Start, Trend Micro Internet Security 2009, ASUS Wireless Console, ASUS Net4Switch, ASUS MultiFrame, ASUS WinFlash, Adobe Reader 8.0, ASUS NB Probe +, ASUS Power4 Gear eXtreme, ASUS LifeFrame3, ASUS SmartLogon, ASUSDVD 6-in-1<br />\r\n		<br />\r\n		<span style="font-weight: bold;">Manufacturer Warranty</span></li>\r\n	<li>\r\n		Service &amp; Support: 2 years warranty</li>\r\n	<li>\r\n		Service &amp; Support Details &nbsp;&nbsp; &nbsp;Limited warranty - 2 years Limited warranty - battery - 1 year&nbsp;</li>\r\n</ul>\r\n<br />\r\n<p>\r\n	<strong>What&#39;s in the box:</strong></p>\r\n<ul>\r\n	<li>\r\n		Notebook</li>\r\n	<li>\r\n		Cables</li>\r\n	<li>\r\n		Documentation</li>\r\n</ul>\r\n', 10, 700.00, 10.00, 15.00, 'Within 14 days', 0, 0.00, 300.00, 1, 999, 0, 0, 0, 'asus-nj10h', 650.00, 0, '2010-02-22 11:49:10', '2010-08-12 10:56:44', 0),
(4, 'Apple iPhone 3GS 32GB (BLACK)', '', '', 'Apple iPhone 3GS 32GB', '<div class="pd_header">&nbsp;</div>\r\n<div style="font-size: 14px;">&nbsp;</div>\r\n<p><strong>Description:</strong></p>\r\n<p><font color="#000000">Get the powerful </font><font color="#000000"><strong>Apple iPhone 3GS 32GB</strong></font> Black<br />\r\n<br />\r\nThe first thing you&rsquo;ll notice about  iPhone 3GS is how quickly you can launch applications. Web pages render  in a fraction of the time, and you can view email attachments faster.  Improved performance and updated 3D graphics deliver an incredible  gaming experience, too. In fact, everything you do on iPhone 3GS is up  to 2x faster and more responsive than iPhone 3G.<br />\r\n<span style="font-weight: bold;"><br />\r\n<br />\r\nFeatures:</span> </p>\r\n<ul>\r\n    <li><span style="font-weight: bold;">Video - </span>Now you can shoot video, edit  it and share it &mdash; all on your iPhone 3GS. Shoot high-quality VGA video  in portrait or landscape. Trim your footage by adjusting start and end  points. Then share your video in an email, post it to your MobileMe  gallery, publish it on YouTube, or sync it back to your Mac or PC using  iTunes.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">3-Megapixel  Camera - </span>The new 3-megapixel camera takes great still photos,  too, thanks to built-in autofocus and a handy new feature that lets you  tap the display to focus on anything (or anyone) you want.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Voice Control </span>- Voice Control  recognises the names in your Contacts and knows the music on your iPod.  So if you want to place a call or play a song, all you have to do is  ask.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Compass - </span>With  a built-in digital compass, iPhone 3GS can point the way. Use the new  Compass app, or watch as it automatically reorients maps to match the  direction you&rsquo;re facing.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Cut, Copy &amp; Paste - </span>Cut, copy and paste words and  photos, even between applications. Copy and paste images and content  from the web, too.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Landscape  Keyboard - </span>Want more room to type on the intelligent software  keyboard? Rotate iPhone to landscape to use a larger keyboard in Mail,  Messages, Notes and Safari.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Messages - </span>Send messages with text, video, photos, audio,  locations and contact information. You can even forward one or more  messages to others. <br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Search  - </span>Find what you&rsquo;re looking for across your iPhone, all from one  convenient place. Spotlight searches all your contacts, email, calendars  and notes, as well as everything in your iPod.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Accessibility - </span>iPhone 3GS offers  accessibility features to assist users who are visually or hearing  impaired. These features include the VoiceOver screen reader, a Zoom  feature, White on Black display options, Mono Audio and more.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Internet Tethering - </span>Surf the web  from practically anywhere. Now you can share the 3G connection on your  iPhone with your Mac notebook or PC laptop. <br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Voice Memos - </span>Capture and share a  thought, a memo, a meeting or any audio recording on the go with the new  Voice Memos application.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Nike + iPod - </span>iPhone includes built-in Nike + iPod  support. Just slip the Nike + iPod Sensor (available separately) into  your Nike+ shoe and start your workout.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Stocks - S</span>tocks on iPhone shows you  charts, financial details and headline news for any stock you choose.  Rotate iPhone to see even more detailed information.<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">YouTube - </span>Watch YouTube videos  wherever you are. Log in to your YouTube account to save and sync  bookmarks and rate your favourites.</li>\r\n</ul>\r\n<span style="font-weight: bold;"><br />\r\nSpecifcations:</span>\r\n<ul>\r\n    <li><span style="font-weight: bold;">Size and weight</span><br />\r\n    - Height: 115.5 mm (4.5 inches)<br />\r\n    -  Width: 62.1 mm (2.4 inches)<br />\r\n    - Depth: 12.3 mm (0.48 inch)<br />\r\n    - Weight:  35 grams (4.8 ounces) <br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Mobile  and wireless</span><br />\r\n    - UMTS/HSDPA (850, 1900, 2100 MHz)<br />\r\n    -&nbsp;GSM/EDGE  (850, 900, 1800, 1900 MHz)<br />\r\n    - Wi-Fi (802.11b/g)<br />\r\n    - Bluetooth 2.1 +  EDR<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Location</span><br />\r\n    -  Assisted GPS<br />\r\n    - Digital compass<br />\r\n    - Wi-Fi<br />\r\n    - Mobile<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Power and battery</span><br />\r\n    - Built-in  rechargeable lithium-ion battery<br />\r\n    - Charging via USB to computer  system or power adapter<br />\r\n    &nbsp;&nbsp;&nbsp;&nbsp;</li>\r\n    <li><span style="font-weight: bold;">Talk time:</span><br />\r\n    - Up to 12 hours on 2G<br />\r\n    - Up to 5 hours on  3G<br />\r\n    - Standby time: Up to 300 hours<br />\r\n    - Internet use:<br />\r\n    &nbsp; - Up to 5  hours on 3G<br />\r\n    &nbsp; - Up to 9 hours on Wi-Fi<br />\r\n    - Video playback: Up to 10  hours<br />\r\n    -&nbsp;Audio playback: Up to 30 hours<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Display</span><br />\r\n    - 3.5-inch (diagonal)  widescreen Multi-Touch display<br />\r\n    -&nbsp;480-by-320-pixel resolution at 163  ppi<br />\r\n    -&nbsp;Fingerprint-resistant oleophobic coating<br />\r\n    -&nbsp;Support for  display of multiple languages and characters simultaneously<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Audio playback</span><br />\r\n    - Frequency  response: 20Hz to 20,000Hz<br />\r\n    - Audio formats supported: AAC, Protected  AAC, MP3, MP3 VBR, Audible (formats 2, 3 and 4), Apple Lossless, AIFF  and WAV<br />\r\n    - User-configurable maximum volume limit<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Camera, photos and video</span><br />\r\n    - 3  megapixels<br />\r\n    - Autofocus<br />\r\n    - Tap to focus<br />\r\n    -&nbsp;Video recording, VGA up  to 30 fps with audio<br />\r\n    - Photo and video geotagging<br />\r\n    - iPhone and  third-party application integration<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Sensors</span><br />\r\n    - Accelerometer<br />\r\n    -&nbsp;Proximity  sensor<br />\r\n    - Ambient light sensor<br />\r\n    &nbsp;</li>\r\n    <li><span style="font-weight: bold;">Headphones</span><br />\r\n    - Apple Earphones with  Remote and Mic<br />\r\n    - Volume control<br />\r\n    - Frequency response: 20Hz to  20,000Hz<br />\r\n    - Impedance: 32 ohms<br />\r\n    &nbsp;</li>\r\n</ul>\r\n<strong>What''s in  the box:</strong><br />\r\n<ul>\r\n    <li>Apple iPhone 3GS 32GB</li>\r\n    <li>Apple  Earphones with Remote and Mic</li>\r\n    <li>Dock Connector to USB Cable</li>\r\n    <li>USB  Power Adapter</li>\r\n    <li>Documentation</li>\r\n    <li>SIM eject tool</li>\r\n</ul>', 9, 800.00, 0.01, 12.66, 'Within 6 days', 0, 0.00, 422.00, 1, 9999, 0, 0, 0, '', 750.00, 0, '2010-02-22 11:52:07', '2010-11-04 11:19:41', 1),
(5, 'Apple MB950B/A 21.5" iMac', 'The new MB413B/A iMac 21.5" is ideal for any home but especially those that work with graphics-based applications or enjoy multimedia as a hobby. Its stunning design is sure to please even the most demanding of users.\r\n\r\n', 'imac,apple,penny,auction,cheap', 'The new MB413B/A iMac 21.5" is ideal for any home but especially those that work with graphics-based applications or enjoy multimedia as a hobby. Its stunning design is sure to please even the most demanding of users.\r\n\r\nIdeal for watching movies, TV shows, viewing photos and much more, it has a striking 21.5 display, with 1920 x 1080 HD resolution and 16:9 widescreen all housed within a striking glass and aluminium design. The high resolution means that your videos, TV shows, digital images and more will look beautifully clear, crisp, detailed and vibrant on the glass screen. The seamless design will bring a cool, contemporary look to any office space.\r\n\r\nThanks to dual core processing with the 3.06 GHz Intel Core 2 Duo Processor and 4GB Memory, you can enjoy excellent multi-tasking capabilities on the Apple MB413B/A iMac 21.5" even with complicated applications. Apple''s Mac OS X Snow Leopard gives you the opportunity to work with your apps and multimedia in a unique way, while the generous 500GB hard drive gives you ample room for all of your digital images, video, mp3 tracks and more.\r\n\r\nAnother superb feature of this Apple iMac All-In-One is the integrated NVIDIA GeForce 9400M graphics processor, an advanced graphics processor that easily displays your multimedia and design-based applications.\r\n\r\nEco-lovers out there will fully appreciate this 21.5 Apple iMac''s environmentally conscious design, which uses highly recyclable materials that are free from many harmful toxins. It also meets ENERGY STAR 5.0 and EPEAT Gold requirements.\r\n\r\nOther notable features of the Apple MB413B/A iMac 21.5" include a wireless keyboard, wireless Magic Mouse and integrated iSight camera. This tiny camera lets you take snapshots, shoot video and video chat  ideal for staying in contact with friends, family and work colleagues. ', '<p>\r\n	The all-new, all-in-one iMac packs a complete, high-performance computer into a beautifully thin design. It includes built-in wireless, Mac OS X, and the iLife &#39;08. So within minutes of opening the box, you&#39;ll be doing everything from sharing photos to creating movies and building websites.<br />\r\n	<br />\r\n	<br />\r\n	<span style="font-weight: bold;">Features:</span></p>\r\n<ul>\r\n	<li>\r\n		Product Description:&nbsp;Apple iMac Core 2 Duo 3.06 GHz - 21.5&quot; TFT</li>\r\n	<li>\r\n		Type:&nbsp;Personal computer</li>\r\n	<li>\r\n		Form Factor:&nbsp;All-in-one</li>\r\n	<li>\r\n		Dimensions (WxDxH):&nbsp;52.8 cm x 18.9 cm x 45.1 cm</li>\r\n	<li>\r\n		Weight:&nbsp;9.3 kg</li>\r\n	<li>\r\n		Localisation:&nbsp; English / United Kingdom</li>\r\n	<li>\r\n		Processor:&nbsp;1 x Intel Core 2 Duo 3.06 GHz ( Dual-Core )</li>\r\n	<li>\r\n		Cache Memory:&nbsp;3 MB L2 Cache</li>\r\n	<li>\r\n		Cache Per Processor:&nbsp;3 MB</li>\r\n	<li>\r\n		RAM:&nbsp;4 GB (installed) / 16 GB (max) - DDR3 SDRAM - 1066 MHz - PC3-8500</li>\r\n	<li>\r\n		Storage Controller:&nbsp;Serial ATA ( Serial ATA-300 )</li>\r\n	<li>\r\n		Hard Drive:&nbsp;1 x 500 GB - standard - Serial ATA-300</li>\r\n	<li>\r\n		Optical Storage:&nbsp;DVD&plusmn;RW (&plusmn;R DL)</li>\r\n	<li>\r\n		Monitor:&nbsp;LCD display - 21.5&quot; - TFT active matrix</li>\r\n	<li>\r\n		Graphics Controller:&nbsp; NVIDIA GeForce 9400M Shared Video Memory (UMA)</li>\r\n	<li>\r\n		Audio Output:&nbsp; Sound card - stereo</li>\r\n	<li>\r\n		Networking:&nbsp;Network adapter - Ethernet, Fast Ethernet, Gigabit Ethernet, IEEE 802.11b, IEEE 802.11a, IEEE 802.11g, IEEE 802.11n, Bluetooth 2.1 EDR</li>\r\n	<li>\r\n		Power:&nbsp;AC 120/230 V ( 50/60 Hz )</li>\r\n	<li>\r\n		OS Provided:&nbsp;Apple MacOS X 10.6</li>\r\n	<li>\r\n		Environmental Standards:&nbsp;ENERGY STAR Qualified</li>\r\n	<li>\r\n		Manufacturer Warranty:&nbsp;1 year warranty</li>\r\n</ul>\r\n', 10, 999.00, 25.00, 22.00, 'Within 10 days', 0, 0.00, 650.00, 1, 9999, 0, 0, 0, 'apple-mb950ba-21.5-iMac', 995.00, 0, '2010-02-22 11:54:28', '2010-08-12 11:27:53', 0),
(6, 'Apple Macbook Air 1.6GHz Core 2 Duo Notebook', '', '', 'MacBook Air is ultrathin, ultraportable, and ultra unlike anything else. But you don''t lose inches and', '<br />\r\n<p>MacBook Air is ultrathin, ultraportable, and ultra unlike  anything else. But you don''t lose inches and pounds overnight. It''s the  result of rethinking conventions. Of multiple wireless innovations. And  of breakthrough design. With MacBook Air, mobile computing suddenly has a  new standard.</p>\r\n<p>MacBook Air is nearly as thin as your index  finger. Practically every detail that could be streamlined has been. Yet  it still has a 13.3-inch widescreen LED display, full-size keyboard,  and large multi-touch trackpad. It''s incomparably portable without the  usual ultraportable screen and keyboard compromises.</p>\r\n<p>The  incredible thinness of MacBook Air is the result of numerous size- and  weight-shaving innovations. From a slimmer hard drive to strategically  hidden I/O ports to a lower-profile battery, everything has been  considered and reconsidered with thinness in mind. MacBook Air  performance is as impressive as its form, thanks to its Intel Core 2 Duo  processor. This chip was custom-built to fit within the compact  dimensions of MacBook Air.</p>\r\n<span style="font-style: italic;">Amazingly  thin. Amazingly full-size.</span>\r\n<p>The thinness of MacBook Air is  stirring. But perhaps more impressive, there&rsquo;s a full-size notebook  encased in the 0.16 to 0.76 inch of sleek, sturdy anodized aluminum.</p>\r\n<p>The glossy 13.3-inch, widescreen LED backlit MacBook Air display is  the same viewable size as the screen on MacBook. The 1280-by-800  resolution gives you vibrant images and rich colors at full brightness  the moment you open MacBook Air. So you get full-screen performance with  all the benefits of a slim design.</p>\r\n<p style="font-style: italic;">The  brilliance of multi-touch</p>\r\n<p>MacBook Air includes an oversize  trackpad with multi-touch technology. You can pinch, swipe, or rotate to  zoom in on text, advance through a photo album, or adjust an image.  This gesture-based input so successful on iPhone and iPod touch now  comes to MacBook.</p>\r\n<span style="font-style: italic;">Full-size,  full-feature keyboard</span>\r\n<p>The keyboard is full-size with crisp  keys just like the ones on MacBook. But MacBook Air goes further by  adding backlit key illumination, making it easy to work in low-light  settings such as airplanes and conference halls. A built-in ambient  light sensor automatically adjusts keyboard and display brightness for  optimal visibility. And with the oversize multi-touch trackpad, it just  keeps getting better for fingers.</p>\r\n<span style="font-style: italic;">A  smart LED display</span>\r\n<p>The backlit LED display allows for an even  thinner build. It provides instant full-screen brightness the moment you  open MacBook Air. The mercury- and arsenic-free display is also more  power efficient, which translates to longer battery life.</p>\r\n<span style="font-style: italic;">Thin is in the details</span>\r\n<p>The  innovative now-you-see-it, now-you-don&rsquo;t port hatch flips down to reveal  (and closes to hide) all the ports you really need: a USB 2.0 port, a  headphone jack, and a micro-DVI port that supports DVI, VGA, composite,  and S-video output. Even the MagSafe power connection has been  reconsidered and slimmed to fit MacBook Air.</p>\r\n<span style="font-style: italic;">So thin yet so expansive</span>\r\n<p>MacBook  Air comes with a way-more-than-generous 2GB of RAM built in &mdash; ample  memory for working with your favorite applications. The 80GB hard drive  provides plenty of storage space.</p>\r\n<span style="font-style: italic;">Micro.  Chip<br />\r\n<br />\r\n</span>\r\n<p>MacBook Air performance is as impressive as its  form, thanks to its 1.6GHz Core 2 Duo processor. This chip was  custom-built to fit within the compact dimensions of MacBook Air.</p>\r\n<p style="font-style: italic;">Built-in iSight camera</p>\r\n<p>Unlike most  other ultraportable notebooks, MacBook Air includes a built-in iSight  camera. It&rsquo;s so smartly integrated, you hardly notice it&rsquo;s there. The  iSight camera and iChat software make video chatting easy anywhere  there&rsquo;s a wireless network.</p>\r\n<p style="font-style: italic;">The  battery is slimmer. The performance isn&rsquo;t</p>\r\n<p>The MacBook Air  battery is our thinnest ever, yet it doesn&rsquo;t compromise power. You can  access the web wirelessly for five full hours.</p>\r\n<span style="font-style: italic;">Without wires, you&rsquo;re free to go anywhere</span>\r\n<p>MacBook Air is the notebook that allows for a fully wireless  lifestyle. It all starts with the fastest-available, next-generation  802.11n Wi-Fi and Bluetooth 2.1 + EDR built in. And that&rsquo;s just the  beginning of the unprecedented wireless capabilities of MacBook Air.</p>\r\n<span style="font-style: italic;">Ahead of the curve.</span>\r\n<p>In  redefining thin, MacBook Air has shed something you no longer need: the  optical drive. That&rsquo;s because MacBook Air is built for the wireless  world. So instead of watching DVDs, you can rent movies wirelessly from  the iTunes Store. And instead of backing up files to a stack of discs,  you can back up files wirelessly using Apple&rsquo;s new Time Capsule.  However, for those times when you still need to install software on  MacBook Air from a CD or DVD, a new feature called Remote Disc lets you  wirelessly use or &ldquo;borrow&rdquo; the optical drive of a Mac or PC in the  vicinity. So you can have full access to an optical drive without having  to haul one around.</p>\r\n<ul><strong>General</strong>\r\n    <li>Width: 12.8 in</li>\r\n    <li>Depth: 8.94 in</li>\r\n    <li>Height:  0.16-0.76 in</li>\r\n    <li>Weight: 3.0 lbs</li>\r\n    <li>Power: 100V to 240V  AC - Frequency 50Hz to 60Hz</li>\r\n    <li>Model : MB003LL/A<strong><br />\r\n    <br />\r\n    Processor</strong></li>\r\n    <li>Processor: Intel Core 2 Duo  processor</li>\r\n    <li>Databus speed: 800MHz frontside bus</li>\r\n    <li>Speed:  1.6GHz<strong><br />\r\n    <br />\r\n    Memory cache</strong></li>\r\n    <li><strong>Type:</strong>  L2</li>\r\n    <li><strong>Size:</strong> 4MB<strong><br />\r\n    <br />\r\n    Connectivity</strong></li>\r\n    <li>Outputs: DVI output using micro-DVI to  DVI adapter (included) , VGA output using micro-DVI to VGA adapter  (included), Composite output using micro-DVI to video adapter (extra) ,  S-video output using micro-DVI to video adapter (extra)</li>\r\n    <strong>Operating  System/Software</strong>\r\n    <li>Included Software: iLife ''08 (includes  iTunes, iPhoto, iMovie, iDVD, iWeb, GarageBand), Time Machine, Quick  Look, Spaces, Spotlight, Dashboard, Mail, iChat, Safari, Address Book,  QuickTime, iCal, DVD Player, Photo Booth, Front Row, Xcode Developer  Tools</li>\r\n    <li>Included Operating System: Mac OS X v10.5 Leopard<strong><br />\r\n    <br />\r\n    Display</strong></li>\r\n    <li>Maximum resolution: 1280 by 800</li>\r\n    <li>Widescreen display: Yes</li>\r\n    <li>Type: Glossy TFT LED backlit  display</li>\r\n    <li>Diagonal Size: 13.3&quot;<strong><br />\r\n    <br />\r\n    Audio</strong></li>\r\n    <li>Audio output: Analog audio output/headphone out</li>\r\n    <li>Details:  Built-in mono speaker, Built-in omnidirectional microphone<strong><br />\r\n    <br />\r\n    Battery</strong></li>\r\n    <li>Run-Time: 5 hours wireless  productivity</li>\r\n    <li>Type: Integrated 37-watt-hour lithium-polymer  battery<strong><br />\r\n    <br />\r\n    Memory</strong></li>\r\n    <li>Memory Speed  (MHz): 667</li>\r\n    <li>Memory Type: DDR2 SDRAM</li>\r\n    <li>Memory Size:  2GB<strong><br />\r\n    <br />\r\n    Drives</strong></li>\r\n    <li>Hard Drive Capacity  (GB): 80</li>\r\n    <li>Hard Drive Type: Parallel ATA hard disk drive</li>\r\n    <li>Hard Drive Cache (MB): 4200<strong><br />\r\n    <br />\r\n    Graphics</strong></li>\r\n    <li>Details: Intel GMA X3100 graphics processor</li>\r\n    <li>Memory:  144MB of DDR2 SDRAM shared with main memory<strong><br />\r\n    <br />\r\n    Connectivity</strong></li>\r\n    <li>Wireless LAN Type: Built-in AirPort  Extreme Wi-Fi wireless networking</li>\r\n    <li>Bluetooth: Yes<strong><br />\r\n    <br />\r\n    Built-in Camera</strong></li>\r\n    <li>Details: Built-in iSight  camera</li>\r\n</ul>', 10, 1799.00, 20.00, 25.99, 'Within 14 days, worldwide. DEMO PRODUCT ONLY', 0, 0.00, 350.00, 1, 9999, 0, 0, 0, '', 1500.00, 0, '2010-02-22 11:59:00', '2010-02-22 11:59:00', 0),
(7, 'Nintendo DSi Console (Black)', '', '', 'Nintendo DSi Console (Black)', '<p>\r\n	Nintendo DSi is a new hand held portable game system for anytime, anywhere fun and more. Enjoy the largest screen size, best audio quality and thinnest design of any system in the Nintendo DS family. Download exclusive games, clocks and calendars via the Nintendo DSi Shop. Take pictures using either an inward or outward facing camera and chose from 11 different lenses to customize your shots. Connect wirelessly to browse the internet, share photos and play with others. Access your music in the AAC format off any standard SD Card.<br />\r\n	<br />\r\n	<br />\r\n	<strong>Features:</strong></p>\r\n<ul>\r\n	<li>\r\n		Have fun with sight, sound and downloadable games - and customise your experience</li>\r\n	<li>\r\n		The Nintendo DSi system comes with the first truly interactive digital camera in a video system with 10 different interactive &quot;lenses&quot; that can manipulate your photos.</li>\r\n	<li>\r\n		Change the world one photo at a time by colouring pictures the way you want them to be.</li>\r\n	<li>\r\n		Download the games and Nintendo DSi applications of your choice.</li>\r\n</ul>\r\n<p>\r\n	<span style="font-weight: bold;">Specifications</span><strong>:</strong></p>\r\n<ul>\r\n	General<br />\r\n	<li>\r\n		Name: Nintendo DSi</li>\r\n	<li>\r\n		Type: Handheld game system</li>\r\n	<li>\r\n		Form: Factor Handheld</li>\r\n	<li>\r\n		Width: 5.4 in</li>\r\n	<li>\r\n		Depth: 0.7 in</li>\r\n	<li>\r\n		Height: 2.9 in</li>\r\n	<li>\r\n		Weight: 7.5 oz</li>\r\n	<br />\r\n	Game Console<br />\r\n	<li>\r\n		Color Support: Color</li>\r\n	<li>\r\n		Media Type: Cartridge</li>\r\n	<li>\r\n		Features: Built-in microphone , Built-in VGA digital camera</li>\r\n	<br />\r\n	Display<br />\r\n	<li>\r\n		Type: LCD display - 3.25 in TFT active matrix - Color - Integrated</li>\r\n	<li>\r\n		Color Support: 260000 color(s)</li>\r\n	<br />\r\n	Display (2nd)<br />\r\n	<li>\r\n		Type: LCD display</li>\r\n	<li>\r\n		Diagonal Size: 3.25 in</li>\r\n	<li>\r\n		Color Support: Color</li>\r\n	<li>\r\n		Features: Touch screen</li>\r\n	<br />\r\n	Audio<br />\r\n	<li>\r\n		Built-in Speakers: Speaker(s)</li>\r\n	<li>\r\n		Sound Output: Mode Stereo</li>\r\n	<br />\r\n	Communications<br />\r\n	<li>\r\n		Connectivity Features: IEEE 802.11</li>\r\n	<br />\r\n	Input Device<br />\r\n	<li>\r\n		Type: 4-way cross keypad - Integrated - 6 button(s)</li>\r\n	<br />\r\n	Connections<br />\r\n	<li>\r\n		Expansion Slots: Total (Free) 1 Nintendo DS cartridge slot , 1 SD Memory Card</li>\r\n	<br />\r\n	Power<br />\r\n	<li>\r\n		Battery: Game console battery - Rechargeable</li>\r\n</ul>\r\n', 6, 150.00, 10.00, 14.99, 'DEMO PRODUCT ONLY', 0, 0.00, 125.00, 1, 999, 0, 0, 0, '', 145.00, 0, '2010-02-22 12:03:52', '2010-08-12 11:14:29', 0),
(16, 'Xbox 360 Slim 250GB (New)', 'The Xbox 360 250GB video game and entertainment system is back in black, with a sleek and lean look. Quieter than ever, the Xbox 360 comes with built-in Wi-Fi, a 250 GB hard drive and is designed for the easiest connection to controller-free fun with the Xbox 360 Kinect Sensor coming soon in 2010 (sold separately).\r\n', 'xbox,360', 'The Xbox 360 250 GB video game and entertainment system is back in black, with a sleek and lean look. Quieter than ever, the Xbox 360 comes with built-in Wi-Fi, a 250 GB hard drive and is designed for the easiest connection to controller-free fun with the Xbox 360 Kinect Sensor coming soon in 2010 (sold separately).\r\n\r\n \r\n\r\n    * 802.11n Wi-Fi is built-in for easier connection to the world of entertainment on Xbox LIVE, where HD movies and TV stream in an instant and you can play with your friends from all over the world.\r\n    * Its fully ready for the controller-free experiences of Kinect.\r\n    * With the huge 250GB hard drive you''ll have plenty of space to store your favourite games, movies and music.\r\n    * Connect more accessories and storage solutions with added USB ports. Now with a total of 5, (3 back/2 front) you''ll find more places to plug and play.\r\n    * Contains an additional integrated optical audio out port for an easier connection to the sound of your A/V receiver.\r\n', '<p>\r\n	The Xbox 360 250 GB video game and entertainment system is back in black, with a sleek and lean look. Quieter than ever, the Xbox 360 comes with built-in Wi-Fi, a 250 GB hard drive and is designed for the easiest connection to controller-free fun with the Xbox 360 Kinect&trade; Sensor coming soon in 2010 (sold separately).</p>\r\n<ul>\r\n	<li>\r\n		802.11n Wi-Fi is built-in for easier connection to the world of entertainment on Xbox LIVE, where HD movies and TV stream in an instant and you can play with your friends from all over the world.</li>\r\n	<li>\r\n		Its fully ready for the controller-free experiences of Kinect.</li>\r\n	<li>\r\n		With the huge 250GB hard drive you&#39;ll have plenty of space to store your favourite games, movies and music.</li>\r\n	<li>\r\n		Connect more accessories and storage solutions with added USB ports. Now with a total of 5, (3 back/2 front) you&#39;ll find more places to plug and play.</li>\r\n	<li>\r\n		Contains an additional integrated optical audio out port for an easier connection to the sound of your A/V receiver.</li>\r\n</ul>\r\n', 6, 400.00, 200.00, 10.00, 'Within 14 days, anywhere', 0, 0.00, 400.00, 1, 9999, 0, 0, 0, 'xbox-360-slim-250gb', 450.00, 0, '2010-08-12 11:21:42', '2010-08-12 11:21:42', 0),
(8, 'Nokia N97 (Unlocked)', '', '', 'Nokia N97 (Unlocked)', '<div class="pd_header"><strong><br />\r\n</strong>Nokia N97 combines a large 3.5&quot; touch display with a full  QWERTY keyboard, providing an ''always open'' window to favorite social  networking sites and Internet destinations. <br />\r\n<br />\r\nNokia''s flagship  Nseries device introduces leading technology - including multiple  sensors, memory, processing power and connection speeds - for people to  create a personal Internet and share their ''social location. <span style="font-weight: bold;"><br />\r\n<br />\r\nFeatures: </span></div>\r\n<ul><span style="font-weight: bold;">Web browsing &amp; sharing</span><br />\r\n    <li>Experience  the full power of the internet with fast WLAN and HSDPA connections.</li>\r\n    <li>Fast internet access makes sharing photos and videos easy &ndash;  just upload them to Ovi Share.</li>\r\n    <li>View real web pages on the  beautiful 16:9 sliding tilt display &ndash; with Flash support for internet  videos.</li>\r\n    <li>Use touch control and the full keyboard to surf the  web straight from the 3.5&quot; screen</li>\r\n    <li>Access content directly  from the customisable homescreen.<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Email</span></li>\r\n    <li>Have your email at your fingertips on  the 3.5&rdquo; touchscreen.</li>\r\n    <li>Set up your email easily.</li>\r\n    <li>Connect  via Gmail, Ovi Mail and Mail for Exchange.</li>\r\n    <li>Use the full  keyboard to type messages quickly and easily.<br />\r\n    <br />\r\n    <span style="font-weight: bold;">&nbsp;Maps</span></li>\r\n    <li>Explore the cities  of the world with Maps, A-GPS and multimedia city guides.</li>\r\n    <li>Use  the Walk pedestrian navigation to find your way.</li>\r\n    <li>Let the  built-in compass keep you pointing in the right direction &ndash; the map  adapts to point the same way you do.</li>\r\n    <li>Use voice guided Drive  navigation to find the best route.<br />\r\n    <br />\r\n    <br />\r\n    <span style="font-weight: bold;">Photography</span></li>\r\n    <li>Use the Carl  Zeiss optics and 5 megapixel camera to take great photos of the places  you visit.</li>\r\n    <li>Capture the moment, day or night, with the dual  LED flash.</li>\r\n    <li>Make the most of the large storage capacity (32  GB) to take your photos with you.</li>\r\n    <li>Upload your photos to Ovi  Share and share them online.<br />\r\n    <br />\r\n    <br />\r\n    <br />\r\n    <span style="font-weight: bold;">Calendar</span></li>\r\n    <li>Organise your  life with Calendar - hit the Calendar icon to keep everything  synchronised and up-to-date.</li>\r\n    <li>Plan your day, organise your  to-do list and set reminders for important events.</li>\r\n    <li>Customise  the homescreen to fit your lifestyle - have Calendar just a touch away.</li>\r\n    <li>Keep useful applications, widgets, and media at your  fingertips.<br />\r\n    <br />\r\n    <br />\r\n    <span style="font-weight: bold;">Contacts</span></li>\r\n    <li>Access your contacts and messages straight from the Contacts  icon on the homescreen.</li>\r\n    <li>Stay in touch any way you want -  via phone, email, feeds, chat, instant messaging and widgets.</li>\r\n    <li>Connect  on social networking sites using widgets from Facebook and other sites.</li>\r\n    <li>Keep things running with Ovi Contacts.<br />\r\n    <br />\r\n    <br />\r\n    <span style="font-weight: bold;">TV &amp; video</span></li>\r\n    <li>Watch  high-quality video on the large 3.5 &quot; 16:9 widescreen or on TV using the  TV-out.</li>\r\n    <li>Take high quality 16:9 videos with the 5 megapixel  camera and share them online.</li>\r\n    <li>Download and stream videos  on the move.</li>\r\n    <li>Keep your favourite videos with you using the  32 GB of memory.<br />\r\n    <br />\r\n    <br />\r\n    <br />\r\n    <br />\r\n    <span style="font-weight: bold;">&nbsp;Music</span></li>\r\n    <li>Enjoy a great Nokia music experience  with Comes with Music.</li>\r\n    <li>Control your music with a touch of  your fingers and access music directly from your homescreen.</li>\r\n    <li>Load  all your favourite tracks into the built-in memory (32 GB).</li>\r\n    <li>Manage  and rip tracks with Nokia Music Manager on your PC.</li>\r\n    <li>Enjoy  excellent sound quality using headphones via Bluetooth or the 3.5 mm  audio connector.<br />\r\n    <br />\r\n    <br />\r\n    <span style="font-weight: bold;">Ovi  Store</span></li>\r\n    <li>Enhance and personalize your phone with  content that matters to you.</li>\r\n    <li>Enjoy much more than just  apps: widgets, ringtones, images, videos, games, and feeds.</li>\r\n    <li>Get  recommendations on relevant global and local content.</li>\r\n</ul>\r\n<br />\r\n<strong>Details:</strong><br />\r\n<ul>\r\n    <li>Network: Quadband GSM  850/900/1800/1900<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Size:&nbsp;</span></li>\r\n    <li>Dimensions  &nbsp;&nbsp; &nbsp;117.2 x 55.3 x 18.3 mm</li>\r\n    <li>Net Weight: 150 g<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Display:&nbsp;</span></li>\r\n    <li>Type:&nbsp;TTFT  touchscreen, 16M colors</li>\r\n    <li>Display size: 360 x 640 pixels, 3.5  inches<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Camera:&nbsp;</span></li>\r\n    <li>Resolution:&nbsp;5MP</li>\r\n    <li>Video: Yes</li>\r\n    <li>Flashlight: Yes</li>\r\n    <li>Connectivity:  GPRS:&nbsp;Yes</li>\r\n    <li>HSDPA: Yes</li>\r\n    <li>EDGE: Yes</li>\r\n    <li>3G:  HSDPA 900 / 2100 MHz (3G Is NOT Compatible In US &amp; CA)</li>\r\n    <li>WiFi:  Yes</li>\r\n    <li>Bluetooth: Yes</li>\r\n    <li>A2DP: Yes</li>\r\n    <li>USB:  Yes<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Memory:&nbsp;</span></li>\r\n    <li>Card  Slot &nbsp;&nbsp; &nbsp;MicroSD (TransFlash)</li>\r\n    <li>Internal Memory: 32 GB<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Ringtones:&nbsp;</span></li>\r\n    <li>Type &nbsp;&nbsp;  &nbsp;Polyphonic (64 channels), MP3<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Battery:&nbsp;</span></li>\r\n    <li>Stand-by  time &nbsp;&nbsp; &nbsp;Up to 430 hours</li>\r\n    <li>Talk time: Up to 6 hours 40 min<br />\r\n    <br />\r\n    <span style="font-weight: bold;">Features:&nbsp;</span></li>\r\n    <li>Messaging  &nbsp;&nbsp; &nbsp;SMS, MMS, Email, Push Email, IM</li>\r\n    <li>FM radio: Yes</li>\r\n    <li>Games:  Yes</li>\r\n    <li>Speaker phone: Yes</li>\r\n    <li>Operating System:  Symbian OS v9.4, Series 60 rel. 5</li>\r\n    <li>Touch-screen: Yes</li>\r\n</ul>\r\n<br />\r\n<strong>What''s in the box:</strong><br />\r\n<ul>\r\n    <li>Nokia N97</li>\r\n    <li>Nokia  Battery (BP-4L)</li>\r\n    <li>Travel charger (AC-10U)</li>\r\n    <li>Connectivity  cable (CA-101)</li>\r\n    <li>Wired headset (AD-54, HS-45)</li>\r\n    <li>Charger  adapter (CA-146)</li>\r\n    <li>Cleaning cloth</li>\r\n</ul>', 9, 488.00, 1.00, 15.00, 'DEMO PRODUCT ONLY', 0, 0.00, 55.00, 1, 999, 0, 0, 0, '', 0.00, 0, '2010-02-22 12:07:52', '2010-02-22 12:07:52', 0),
(9, 'DualShock 3 PS3 controller (PS3)', 'The Dualshock 3 wireless controller for the PlayStation 3 system provides the most intuitive game play experience with pressure sensors in each action button and the inclusion of the highly sensitive SIXAXIS  motion sensing technology. Each hit, crash and explosion is more realistic when the user feels the rumble right in the palm of their hand. It can even detect natural movements for real-time and high precision interactive play, acting as a natural extension of the users body. Dualshock 3 wireless controller utilizes Bluetooth technology for wireless game play and the controllers USB cable to seamlessly and automatically charge the controller through the PlayStation 3 system at anytime.', 'dualshock,ps3,controller,sony', 'DualShock 3 PS3 controller (PS3)', '<p>\r\n	The Dualshock 3 wireless controller for the PlayStation 3 system provides the most intuitive game play experience with pressure sensors in each action button and the inclusion of the highly sensitive SIXAXIS motion sensing technology. Each hit, crash and explosion is more realistic when the user feels the rumble right in the palm of their hand. It can even detect natural movements for real-time and high precision interactive play, acting as a natural extension of the user&rsquo;s body. Dualshock 3 wireless controller utilizes Bluetooth technology for wireless game play and the controller&rsquo;s USB cable to seamlessly and automatically charge the controller through the PlayStation 3 system at anytime.<br />\r\n	<br />\r\n	<span style="font-weight: bold;">Key Features:</span></p>\r\n<ul>\r\n	<li>\r\n		Pressure sensors that rumble with each action making every impact feel like you&#39;re right in the game</li>\r\n	<li>\r\n		Sixaxis highly sensitive motion technology senses your every move</li>\r\n	<li>\r\n		Features Bluetooth technology for wireless game play</li>\r\n	<li>\r\n		The PlayStation 3 system can support up to seven wireless controllers at one time</li>\r\n	<li>\r\n		Can be charged at any time through the PlayStation 3 system using the controller&#39;s USB cable</li>\r\n</ul>\r\n<br />\r\n<p>\r\n	<strong>What&#39;s in the box:</strong></p>\r\n<ul>\r\n	<li>\r\n		Dualshock 3 Wireless controller.</li>\r\n</ul>\r\n', 6, 30.00, 0.01, 2.00, 'Within 14 days', 0, 0.00, 15.00, 1, 9999, 0, 0, 0, 'dualshock-ps3-controller', 22.00, 0, '2010-02-22 12:10:03', '2010-08-12 11:11:32', 0),
(10, 'Norton Internet Security 2010', '', '', 'Norton Internet Security 2010', '<p>\r\n	<strong>Description:</strong></p>\r\n<p>\r\n	Norton Internet Security 2010 delivers fast and light comprehensive online threat protection. It guards your PC, network, online activities and your identity with innovative, intelligent detection technologies optimized to combat today&#39;s aggressive, rapid-fire attacks. Improved Norton Safe Web technology blocks Internet threats before they can infect your PC. So you can browse, buy and bank online with confidence. It even warns you of unsafe web sites right in your search results. Plus, unlike other Internet security suites, it provides easy-to-understand threat and performance information to help you avoid future threats and keep your PC running fast.<br />\r\n	<br />\r\n	<span style="font-weight: bold;"> Features:</span></p>\r\n<ul>\r\n	<li>\r\n		Stops online identity theft, viruses, spyware, bots, Trojans and more--Guards your PC, online activities and your identity with comprehensive award-winning protection against all types of Internet threats.<br />\r\n		&nbsp;</li>\r\n	<li>\r\n		Stops attacks before they get on your PC--Blocks hackers from accessing your PC and prevents dangerous software that could harm your computer or steal your identity from automatically downloading onto your PC when you surf the web.<br />\r\n		&nbsp;</li>\r\n	<li>\r\n		Delivers clear performance and threat explanations--Tells you how files and applications affect PC performance, what actions threats have taken, and where they came from to help you avoid future attacks.<br />\r\n		&nbsp;</li>\r\n	<li>\r\n		Identifies unsafe web sites right in your search results--Provides safety ratings for web sites listed in your search results so you can avoid visiting sites that are likely to cause problems.<br />\r\n		&nbsp;</li>\r\n	<li>\r\n		Uses intelligence-driven technology for faster, fewer, shorter scans--Identifies and scans only files at risk to detect and eliminate dangerous software.</li>\r\n</ul>\r\n<p>\r\n	<strong>What&#39;s in the box:</strong></p>\r\n<ul>\r\n	<li>\r\n		Norton Internet Security 2010 - 1 User 3 Computers Upgrade (PC CD)</li>\r\n</ul>\r\n', 10, 50.00, 0.01, 5.99, 'demo product ONLY', 0, 0.00, 25.00, 1, 999, 0, 0, 0, 'norton-internet-security', 0.00, 0, '2010-02-22 12:12:50', '2010-08-12 11:26:01', 0),
(11, 'test', '', '', '', '<p>\r\n	test</p>\r\n', 15, 200.00, 2.00, 0.00, '', 0, 0.00, 200.00, 1, 9999, 0, 0, 0, '', 100.00, 0, '2010-07-29 11:55:07', '2010-08-12 10:47:53', 1);
INSERT INTO `products` (`id`, `title`, `meta_description`, `meta_keywords`, `brief`, `description`, `category_id`, `rrp`, `start_price`, `delivery_cost`, `delivery_information`, `fixed`, `fixed_price`, `minimum_price`, `autobid`, `autobid_limit`, `limit_id`, `stock`, `stock_number`, `code`, `buy_now`, `auto_bid_credit`, `created`, `modified`, `deleted`) VALUES
(12, 'Apple iPad 64GB Wifi', 'apple ipad 64 GB', 'ipad,64,gb', 'Applie iPad, brand new, 64GB with Wifi', '<p>\r\n	<strong>Description:</strong></p>\r\n<p>\r\n	A large, high-resolution LED-backlit IPS display. An incredibly responsive Multi-Touch screen. And an amazingly powerful Apple-designed chip. All in a design that&rsquo;s thin and light enough to take anywhere. iPad isn&rsquo;t just the best device of its kind. It&rsquo;s a whole new kind of device.<br />\r\n	<br />\r\n	<strong>LED-Backlit IPS Display</strong><br />\r\n	<br />\r\n	The high-resolution, 9.7-inch LED-backlit IPS display on iPad is remarkably crisp and vivid. Which makes it perfect for web browsing, watching movies or viewing photos. With iPad, there is no up or down. It&rsquo;s designed to show off your content in portrait or landscape orientation with every turn. And because it uses a display technology called IPS (in-plane switching), it has a wide, 178 &deg; viewing angle. So you can hold it almost any way you want and still get a brilliant picture, with excellent colour and contrast.<br />\r\n	<br />\r\n	<strong>Multi-Touch</strong><br />\r\n	<br />\r\n	The Multi-Touch screen on iPad is based on the same revolutionary technology on iPhone. But the technology has been completely reengineered for the larger iPad surface, making it extremely precise and responsive. So whether you&rsquo;re zooming in on a map, flicking through your photos or deleting an email, iPad responds with incredible accuracy. And it does just what you want it to.<br />\r\n	<br />\r\n	<strong>Thin and Light</strong><br />\r\n	<br />\r\n	One of the first things you&rsquo;ll notice about iPad is how thin and light it is. The screen is 9.7 inches measured diagonally. So overall, it&rsquo;s slightly smaller than a magazine. At just 0.68 kg (1.5 lbs) and 13.4 mm (0.5 inches) thin,1 you can use it anywhere. And a slight curve to the back makes it easy to pick up and comfortable to hold.<br />\r\n	<br />\r\n	<strong>Up to 10 Hours of Battery Life</strong><br />\r\n	<br />\r\n	To maximise battery life, Apple engineers took the same lithium polymer battery technology they developed for Mac notebook computers and applied it to the iPad. As a result, you can use iPad for up to 10 hours while surfing the web on Wi-Fi, watching videos or listening to music.<br />\r\n	<br />\r\n	<strong>Performance</strong><br />\r\n	<br />\r\n	The A4 chip inside iPad was custom-designed by Apple engineers to be extremely powerful yet extremely power efficient. The performance is unlike anything you&rsquo;ve ever seen on a touch-based device. Which makes iPad fantastic for everything from productivity apps to games. At the same time, the A4 chip is so power efficient that it helps iPad get up to 10 hours of battery life on a single charge. And iPad is available with a choice of 16GB, 32GB or 64GB of flash storage,4 giving you lots of room for your photos, movies, music, apps and more.<br />\r\n	<br />\r\n	<strong>Connectivity</strong><br />\r\n	<br />\r\n	The dock connector port on the bottom of iPad allows you to dock and charge it. It also lets you connect to accessories like the iPad Camera Connection Kit and the iPad Keyboard Dock. You&rsquo;ll find many accessories designed to be compatible with the dock connector port.<br />\r\n	Audio<br />\r\n	<br />\r\n	The powerful built-in speaker produces rich, full sound, perfect for watching a movie or listening to music. iPad also comes with a headphone jack and a built-in microphone.<br />\r\n	<br />\r\n	<br />\r\n	<strong>What&#39;s in the box:</strong></p>\r\n<ul>\r\n	<li>\r\n		iPad</li>\r\n	<li>\r\n		Dock Connector to USB Cable</li>\r\n	<li>\r\n		10W USB Power Adapter</li>\r\n	<li>\r\n		Documentation</li>\r\n</ul>\r\n<p>\r\n	<font size="-2">Contents of package may vary from those pictured</font></p>\r\n', 5, 1000.00, 24.00, 20.00, 'Delivery within 12 days', 0, 0.00, 800.00, 1, 9999, 0, 0, 0, 'iPad64wifi', 550.00, 0, '2010-08-12 10:47:42', '2010-08-12 10:47:42', 0),
(13, 'Windows 7 Home Premium', 'Windows 7 Home Premium offers a rich, dynamic entertainment experience on your PC, making it easy to create a home network and share all of your favourite photos, videos, and music. You can even watch, pause, and rewind TV or record it to watch whenever and wherever you want.', 'windows,7,home,premium,win,penny,auction', 'Windows 7 Home Premium offers a rich, dynamic entertainment experience on your PC, making it easy to create a home network and share all of your favourite photos, videos, and music. You can even watch, pause, and rewind TV or record it to watch whenever and wherever you want.', '<p>\r\n	The best entertainment experience on your PC. Windows 7 Home Premium offers a rich, dynamic entertainment experience on your PC, making it easy to create a home network and share all of your favourite photos, videos, and music. You can even watch, pause, and rewind TV or record it to watch whenever and wherever you want.</p>\r\n<ul>\r\n	<li>\r\n		Windows 7 Home Premium (includes 32-bit &amp; 64-bit versions) makes it easy to create a home network and share all of your favorite photos, videos, and music--you can even watch, pause, rewind, and record TV</li>\r\n	<li>\r\n		Make the things you do every day easier with improved desktop navigation</li>\r\n	<li>\r\n		Start programs faster and more easily, and quickly find the documents you use most often</li>\r\n	<li>\r\n		Make your web experience faster, easier and safer than ever with Internet Explorer 8</li>\r\n	<li>\r\n		Easily create a home network and connect your PCs to a printer with HomeGroup</li>\r\n</ul>\r\n<br />\r\n<p>\r\n	<span style="font-weight: bold;">Features:</span></p>\r\n<br />\r\n<ul>\r\n	<li>\r\n		Record TV on your PC: Watch, pause, rewind, and record TV, movies, and other video content with Windows Media Center, updated to manage a single TV guide containing both standard and digital high definition TV shows.</li>\r\n	<li>\r\n		Personalise your desktop: Easily change backgrounds, window colours, and sounds to reflect your personal style. You can even turn your desktop into a slide show of your favourite photos.</li>\r\n	<li>\r\n		Share files across the various PCs in your home: Use HomeGroup to connect your PCs running Windows 7 to a single printer. Specify exactly what you want to share from each PC with all the PCs in the HomeGroup.</li>\r\n	<li>\r\n		Pin any program to the taskbar: Any program is always just a click away &ndash; and you can rearrange the icons on the taskbar just by clicking and dragging.</li>\r\n	<li>\r\n		Stream music files on any network-connected device: Just open Windows Media Player, right-click on what you&#39;d like to hear, select Play To, and you&#39;ll see a list of devices and PCs on which you can play your music.</li>\r\n	<li>\r\n		Find virtually anything on your PC &ndash; from documents to photos to e-mail: Just click on the Start button, and enter a word or few letters in the name or file you want into the search box, and you&rsquo;ll get an organised list of results.</li>\r\n	<li>\r\n		Access recently used files with just two clicks: Right-click the relevant program icon (such as Word) on your taskbar and Jump List will show your most recent, frequently used, and pinned Word documents.<br />\r\n		<br />\r\n		<br />\r\n		<strong>System Requirements</strong></li>\r\n	<li>\r\n		Processor: 1GHz or faster 32-bit (x86) or 64-bit (x64) processor</li>\r\n	<li>\r\n		Hard Disk: 16GB available disk space (32-bit) / 20 GB (64-bit</li>\r\n	<li>\r\n		Media Drive: DVD/CD authoring requires a compatible optical drive.</li>\r\n	<li>\r\n		Video Card: Depending on resolution, video playback may require additional memory and advanced graphics hardware.</li>\r\n	<li>\r\n		Connectivity: Internet access required to use some features (fees may apply).</li>\r\n	<li>\r\n		Memory: 1GB RAM (32-bit) / 2GB RAM (64-bit)</li>\r\n	<li>\r\n		Display: DirectX 9 graphics processor with WDDM 1.0 or later driver</li>\r\n	<li>\r\n		Sound Card: Music and sound require audio output.<br />\r\n		&nbsp;</li>\r\n</ul>\r\n<p>\r\n	<br />\r\n	<strong>What&#39;s in the box:</strong></p>\r\n<ul>\r\n	<li>\r\n		Microsoft Windows 7 Home Premium - Single license, 1 installation</li>\r\n</ul>\r\n', 10, 500.00, 2.00, 24.00, 'Within 4 days', 0, 0.00, 400.00, 1, 9999, 0, 0, 0, 'win7hp', 220.00, 0, '2010-08-12 10:54:04', '2010-08-12 10:54:04', 0),
(14, '$50 HomeDepot Gift Card', 'With more than 40,000 products to choose from, The Home Depot is the world''s largest home improvement retailer. Visit The Home Depot for flooring, paint, bath, kitchen, outdoors living products, appliances as well as tools and hardware!', 'home,depot,50,dollars,card', 'With more than 40,000 products to choose from, The Home Depot is the world''s largest home improvement retailer. Visit The Home Depot for flooring, paint, bath, kitchen, outdoors living products, appliances as well as tools and hardware!', '<p>\r\n	With more than 40,000 products to choose from, The Home Depot is the world&#39;s largest home improvement retailer. Visit The Home Depot for flooring, paint, bath, kitchen, outdoors living products, appliances as well as tools and hardware!</p>\r\n<p>\r\n	<br />\r\n	<span style="font-weight: bold;">What&#39;s in the box:</span></p>\r\n<ul>\r\n	<li>\r\n		$50 Home Depot Gift Card</li>\r\n</ul>\r\n', 4, 100.00, 0.20, 20.00, 'Within 10 days', 0, 0.00, 50.00, 1, 9999, 0, 0, 0, 'homedepotgiftcard50', 49.00, 0, '2010-08-12 11:00:42', '2010-08-12 11:00:42', 0),
(15, 'Monster Hunt Tri (Wii)', 'One of the most strikingly beautiful titles ever developed for the Wii, Monster Hunter 3: Tri depicts a living, breathing ecosystem where man co-exists with the fantastic beasts that roam both the dry land and the brand new sub-aqua environment - a first for the series.\r\n', 'monster,hunt,wii,tri,nintendo,wi', 'One of the most strikingly beautiful titles ever developed for the Wii, Monster Hunter 3: Tri depicts a living, breathing ecosystem where man co-exists with the fantastic beasts that roam both the dry land and the brand new sub-aqua environment - a first for the series.', '<p>\r\n	One of the most strikingly beautiful titles ever developed for the Wii, Monster Hunter 3: Tri depicts a living, breathing ecosystem where man co-exists with the fantastic beasts that roam both the dry land and the brand new sub-aqua environment - a first for the series.<br />\r\n	<br />\r\n	As a Hunter you will need track, set traps, and either capture or slay a variety of majestic monsters. When a monster is slain, the hunter can carve off their horns, scales and bones, which can be used to create a huge variety of weapons and armour.<br />\r\n	<br />\r\n	Monster Hunter use&#39;s the Wii functionality to its fullest, showcasing beautiful graphics and a dynamic ecosystem. Monster Hunter Tri allows for unparalleled co-op gameplay, both on and offline. Hunt with up to four players online or two players offline in arena mode. Also, in Arena mode, you&#39;ll have the ability to save your character&#39;s information to the Wii Remote directly so you&#39;re always ready to play at a friend&#39;s house!<br />\r\n	<br />\r\n	Do you have the courage to hunt the monsters that roam this world? It won&#39;t be easy - the monsters are strong and numerous. Yet by hunting together with your friends, or braving it solo with your ChaCha Fighting Companions, there is no monster that cannot be defeated. Sharpen your favourite weapon and invite your friends, hunting season is about to begin...<br />\r\n	&nbsp;</p>\r\n', 6, 80.00, 1.00, 2.50, 'Within 15 days, anywhere in the world!', 0, 0.00, 25.00, 1, 9999, 0, 0, 0, 'monster-hunt-tri-wii', 80.00, 0, '2010-08-12 11:08:06', '2010-08-12 11:08:06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE IF NOT EXISTS `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `referrer_id` int(11) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `referrer_id` (`referrer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `referrals`
--


-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE IF NOT EXISTS `reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE IF NOT EXISTS `rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rrp` decimal(30,2) NOT NULL,
  `points` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `options` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `description`, `type`, `options`) VALUES
(1, 'auction_peak_start', '9:00', 'The time (in 24 hour time) that the peak time should begin.', 0, ''),
(2, 'auction_peak_end', '22:30', 'The time (in 24 hour time) that the peak time should end.', 0, ''),
(3, 'bid_butler_time', '864000', 'The number of seconds from the auction closing that the bid butler bids should be placed.  We recommend setting this to at least 30 seconds.', 0, ''),
(4, 'free_referral_bids', '20', 'The number of free bids a user receives for referring another user.  This only gets given when the new user purchase bids.', 0, ''),
(5, 'site_live', '1', 'Use this setting to turn off the website for any reason.  Change the value to ''no'' to turn off the website, and ''yes'' to turn the website on.', 4, ''),
(6, 'free_registeration_bids', '20', 'The number of free bids a user gets for registering on the website (given once their account is activated.)', 0, ''),
(7, 'free_bid_packages_bids', '30%', 'The number of free bids a user gets the first time they purchase a bid package.  Alternatively make this a % for the user to receive x% more bids instead.', 0, ''),
(8, 'free_won_auction_bids', '5', 'The number of free bids a user gets for paying for an auction. Alternatively make this a % for the user to receive a % of the bids back that they bid on the auction.', 0, ''),
(9, 'offline_message', 'We are currently experiencing a higher number of visitors than usual. The website is currently down, please try again later.', 'The message that should be displayed when the website is offline.', 0, ''),
(10, 'default_meta_title', 'Reverse Auctions', 'Used as part of Search Engine Optimisation, this is the default meta title.', 0, ''),
(11, 'default_meta_description', '', 'Used as part of Search Engine Optimisation, this is the default meta description.', 0, ''),
(12, 'default_meta_keywords', '', 'Used as part of Search Engine Optimisation, this is the default meta keywords.', 0, ''),
(13, 'user_invite_message', 'Hi There2\\n\\nSign up at SITENAME to receive great deals on products.\\n\\nURL\\n\\nCheck it out if you can!\\nSENDER', 'This is the default message that the user will send when inviting friends to the website.', 0, ''),
(14, 'autolist_expire_time', '1440', 'This is the number of minutes after an auction has closed that an autolist will set the expire time.  This time will be the current time (unless an autolist delay is used), plus the number of minutes set here.', 0, ''),
(15, 'autobid_time', '60', 'The number of seconds from the auction closing that the autobidders should start bidding.  Set this to 0 to make them always bid.', 0, ''),
(16, 'mark_up', '30', 'This is the mark up that you aim to make on each product.  The number should be a percentage, e.g. ''30'' for 30%.  This is used to automatically calculate the minimum price. ', 0, ''),
(17, 'autolist_delay_time', '3', 'Use the autolist delay time to delay the start time of auto relisting auctions.  This feature will delay the start time of the new auction by the number of minutes set here.', 0, ''),
(18, 'local_license_key', '', 'Private, do not edit.', 0, ''),
(31, 'autobidders', '1', 'Turn on to allow site testing using autobidders.', 2, ''),
(32, 'home_page_future_auctions', '1', 'Show future auctions on the home page?', 4, ''),
(30, 'phppa_version', '2.4.2', 'Internal use only, modifying this value can cause software instability!', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `setting_increments`
--

CREATE TABLE IF NOT EXISTS `setting_increments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `lower_price` decimal(30,2) NOT NULL,
  `upper_price` decimal(30,2) NOT NULL,
  `bid_debit` int(11) NOT NULL,
  `price_increment` decimal(30,2) NOT NULL,
  `time_increment` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `setting_increments`
--

INSERT INTO `setting_increments` (`id`, `product_id`, `lower_price`, `upper_price`, `bid_debit`, `price_increment`, `time_increment`) VALUES
(1, 130, 0.00, 0.00, 1, 0.20, 30),
(2, 0, 0.01, 9.99, 1, 2.00, 10),
(3, 0, 10.00, 24.99, 1, 4.00, 8);

-- --------------------------------------------------------

--
-- Table structure for table `smartbids`
--

CREATE TABLE IF NOT EXISTS `smartbids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94716 ;


-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE IF NOT EXISTS `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `extra` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`id`, `name`, `order`, `extra`) VALUES
(1, 'Google', 1, 0),
(3, 'Yahoo', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `message`) VALUES
(1, 'Unpaid', 'This auction has not been paid for.  Please pay for the auction to continue with the transaction.'),
(2, 'Paid, Awaiting Shipment', 'We have received your payment and will be shipping the item shortly.'),
(3, 'Shipped & Completed', 'Your auction has been shipped.'),
(4, 'Refunded', 'This auction has been refunded.'),
(5, 'Declined', 'This auction has been declined.'),
(6, 'Investigating Problem', 'We are currently investigating a problem in relation to this auction.');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `brief` text NOT NULL,
  `description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `delivery_information` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender_id` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `key` varchar(40) NOT NULL,
  `newsletter` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `autobidder` tinyint(1) NOT NULL,
  `source_id` int(11) NOT NULL,
  `source_extra` varchar(255) NOT NULL,
  `tax_number` varchar(255) NOT NULL,
  `bid_balance` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gender_id` (`gender_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `mobile`, `date_of_birth`, `gender_id`, `email`, `active`, `key`, `newsletter`, `admin`, `autobidder`, `source_id`, `source_extra`, `tax_number`, `bid_balance`, `ip`, `created`, `modified`) VALUES
(6, 'autobidder1', '', '', '', '', '0000-00-00', 0, '', 1, '', 0, 0, 1, 0, '', '', 0, '', '2008-11-02 08:32:24', '2008-11-02 08:32:24'),
(7, 'autobidder2', '', '', '', '', '0000-00-00', 0, '', 1, '', 0, 0, 1, 0, '', '', 0, '', '2008-11-02 08:32:34', '2008-11-02 08:32:34');

-- --------------------------------------------------------

--
-- Table structure for table `watchlists`
--

CREATE TABLE IF NOT EXISTS `watchlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;