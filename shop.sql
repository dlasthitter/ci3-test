/*
SQLyog Enterprise - MySQL GUI v6.03
Host - 5.5.5-10.1.25-MariaDB : Database - shop
*********************************************************************
Server version : 5.5.5-10.1.25-MariaDB
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `shop`;

USE `shop`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `items` */

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `items` */

insert  into `items`(`item_id`,`name`,`qty`,`price`,`thumb`,`status`) values (1,'Kindle',92,'100.00','kindle.jpg',1),(2,'iPad',79,'500.00','ipad.jpg',1),(3,'iPhone',87,'1000.00','iphone.jpg',1);

/*Table structure for table `transaction_details` */

DROP TABLE IF EXISTS `transaction_details`;

CREATE TABLE `transaction_details` (
  `txn_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `txn_id` bigint(20) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`txn_detail_id`),
  KEY `SECONDARY` (`txn_detail_id`,`txn_id`),
  KEY `FK_transaction_details_item` (`item_id`),
  KEY `FK_transaction_details_txn` (`txn_id`),
  CONSTRAINT `FK_transaction_details_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  CONSTRAINT `FK_transaction_details_txn` FOREIGN KEY (`txn_id`) REFERENCES `transactions` (`txn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_details` */

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `txn_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `email` varchar(225) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`txn_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `transactions` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
