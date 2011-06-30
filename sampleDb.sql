-- phpMyAdmin SQL Dump
-- version 2.8.0.3
-- http://www.phpmyadmin.net
-- 
-- Generation Time: Jul 29, 2006 at 11:12 PM
-- Server version: 5.0.18
-- PHP Version: 4.4.2
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `categories`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 11:02 PM
-- 

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `cid` int(11) unsigned NOT NULL auto_increment,
  `catSortOrder` int(11) NOT NULL default '0',
  `userid` varchar(15) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `linkname` varchar(255) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `catSubDescription` text NOT NULL,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `categories`
-- 

INSERT INTO `categories` (`cid`, `catSortOrder`, `userid`, `name`, `description`, `linkname`, `linkurl`, `catSubDescription`) VALUES (1, -10000, 'admin', 'Items Under Consideration', '', '', '', 'You are the only person who can view items in this category.'),
(2, -10000, 'test', 'Items Under Consideration', '', '', '', 'You are the only person who can view items in this category.'),
(3, 0, 'test', 'Movies', '', '', '', 'Example comment for category'),
(4, 0, 'admin', 'DVD', 'Widescreen Only', '', '', ''),
(5, 1, 'admin', 'Books', '', '', '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `comments`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 10:55 PM
-- 

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `userid` varchar(15) NOT NULL default '',
  `comment_userid` varchar(15) NOT NULL default '',
  `comment` text NOT NULL,
  `commentId` int(11) unsigned NOT NULL auto_increment,
  `date` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`commentId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `favorites`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 10:55 PM
-- 

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `userid` varchar(15) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`userid`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `favorites`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `itemPriceHistory`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 10:55 PM
-- 

DROP TABLE IF EXISTS `itemPriceHistory`;
CREATE TABLE IF NOT EXISTS `itemPriceHistory` (
  `dateChanged` datetime NOT NULL default '0000-00-00 00:00:00',
  `iid` int(11) unsigned NOT NULL default '0',
  `price` double(10,2) NOT NULL default '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- 
-- Dumping data for table `itemPriceHistory`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `items`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 11:02 PM
-- 

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `iid` int(11) unsigned NOT NULL auto_increment,
  `cid` int(11) unsigned NOT NULL default '0',
  `itemSortOrder` int(11) NOT NULL default '0',
  `addStar` char(1) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` varchar(255) default '',
  `price` decimal(10,2) default '0.00',
  `quantity` smallint(11) unsigned default '0',
  `subdesc` text,
  `allowCheck` varchar(5) default 'false',
  `link2` varchar(255) default '',
  `link2url` varchar(255) default '',
  `link3` varchar(255) default '',
  `link3url` varchar(255) default '',
  `link1` varchar(255) default '',
  `link1url` varchar(255) default '',
  PRIMARY KEY  (`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `items`
-- 

INSERT INTO `items` (`iid`, `cid`, `itemSortOrder`, `addStar`, `title`, `description`, `price`, `quantity`, `subdesc`, `allowCheck`, `link2`, `link2url`, `link3`, `link3url`, `link1`, `link1url`) VALUES (1, 4, 0, '0', 'Star Wars Episode IV - A New Hope', '(1977 & 2004 Versions, 2-Disc Widescreen Edition)', 19.49, 1, '', 'true', '', '', '', '', 'Amazon', 'http://www.amazon.com/gp/product/B000FQJAIW/ref=pd_rvi_gw_1/103-7406758-1613402?%5Fencoding=UTF8&v=glance&n=130'),
(2, 5, 0, '0', 'Never Cry Wolf', 'Farley Mowat', 11.65, 1, '', 'true', '', '', '', '', 'bn.com', 'http://search.barnesandnoble.com/booksearch/isbnInquiry.asp?z=y&isbn=0316881791&itm=1'),
(3, 5, 2, '0', 'Godfather', '', 0.00, 1, '', 'true', '', '', '', '', 'bn.com', 'http://search.barnesandnoble.com/booksearch/isbnInquiry.asp?z=y&isbn=0451167716&itm=1'),
(4, 5, 1, '0', 'To Kill a Mockingbird', 'Harper Lee', 18.83, 1, 'Exmple comment here', 'true', '', '', '', '', 'Amazon', 'http://www.amazon.com/gp/product/B0009X7664/sr=8-2/qid=1154239185/ref=pd_bbs_2/103-7406758-1613402?ie=UTF8'),
(5, 3, 0, '0', 'Three Days of the Condor', 'Robert Redford, Faye Dunaway', 9.98, 1, '', 'true', '', '', '', '', 'Amazon.com', 'http://www.amazon.com/gp/product/6305511055/sr=1-1/qid=1154239310/ref=pd_bbs_1/103-7406758-1613402?ie=UTF8&s=dvd');

-- --------------------------------------------------------

-- 
-- Table structure for table `people`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 11:07 PM
-- 

DROP TABLE IF EXISTS `people`;
CREATE TABLE IF NOT EXISTS `people` (
  `userid` varchar(15) NOT NULL default '',
  `admin` char(1) NOT NULL default '0',
  `registered` char(1) NOT NULL default '0',
  `lastLoginDate` datetime default '0000-00-00 00:00:00',
  `lastModDate` datetime default '0000-00-00 00:00:00',
  `lastname` varchar(100) default '',
  `firstname` varchar(100) default '',
  `suffix` varchar(100) default '',
  `street` varchar(100) default '',
  `city` varchar(100) default '',
  `state` varchar(15) default '',
  `zip` int(11) default '0',
  `email` varchar(100) default '',
  `phone` varchar(100) default '',
  `mobilephone` varchar(100) default '',
  `bmonth` varchar(15) default '',
  `bday` tinyint(11) default '0',
  `url` varchar(255) default '',
  `password` varchar(100) default NULL,
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `people`
-- 

INSERT INTO `people` (`userid`, `admin`, `registered`, `lastLoginDate`, `lastModDate`, `lastname`, `firstname`, `suffix`, `street`, `city`, `state`, `zip`, `email`, `phone`, `mobilephone`, `bmonth`, `bday`, `url`, `password`) VALUES ('admin', '1', '0', '2006-07-29 23:07:39', '2006-07-29 23:00:48', 'Admin', 'Jerry', '', '555 SomeStreet', 'Somewhere', 'Mo', 55555, 'e@e.com', '', '(555) 555-5555', 'August', 1, 'http://cnn.com', '21232f297a57a5a743894a0e4a801fc3'),
('test', '0', '0', '2006-07-29 23:01:34', '2006-07-29 23:02:56', 'Taylor', 'Gillian', '', '886 Cannery Row', 'Sausalito', 'CA', 93940, 'g@g.com', '123-123-1234', '123-123-1234', 'January', 1, 'http://www.mbayaq.org/vi/', '098f6bcd4621d373cade4e832627b4f6');

-- --------------------------------------------------------

-- 
-- Table structure for table `purchaseHistory`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 10:55 PM
-- 

DROP TABLE IF EXISTS `purchaseHistory`;
CREATE TABLE IF NOT EXISTS `purchaseHistory` (
  `purchaseId` int(11) unsigned NOT NULL auto_increment,
  `iid` int(11) unsigned NOT NULL default '0',
  `userid` varchar(15) NOT NULL default '',
  `quantity` smallint(11) unsigned NOT NULL default '0',
  `boughtDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`purchaseId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `purchaseHistory`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `viewList`
-- 
-- Creation: Jul 29, 2006 at 10:55 PM
-- Last update: Jul 29, 2006 at 11:07 PM
-- 

DROP TABLE IF EXISTS `viewList`;
CREATE TABLE IF NOT EXISTS `viewList` (
  `lastViewDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `viewContactInfo` char(1) NOT NULL default '0',
  `readOnly` char(1) NOT NULL default '1',
  `pid` varchar(15) NOT NULL default '0',
  `viewer` varchar(15) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `viewList`
-- 

INSERT INTO `viewList` (`lastViewDate`, `viewContactInfo`, `readOnly`, `pid`, `viewer`) VALUES ('2005-09-18 17:09:00', '1', '0', 'test', 'test'),
('2006-07-29 23:01:09', '1', '0', 'test', 'admin'),
('2006-01-19 18:34:45', '1', '0', 'admin', 'admin'),
('2006-07-29 23:07:14', '1', '0', 'admin', 'test');
