# ************************************************************
# Sequel Pro SQL dump
# Version 4728
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 192.168.106.100 (MySQL 5.6.29-76.2)
# Datenbank: hrzgwidget
# Erstellt am: 2016-05-13 14:54:18 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle app_hrzg_widget_template
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_hrzg_widget_template`;

CREATE TABLE `app_hrzg_widget_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `json_schema` text NOT NULL,
  `editor_settings` text,
  `form` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `app_hrzg_widget_template` WRITE;
/*!40000 ALTER TABLE `app_hrzg_widget_template` DISABLE KEYS */;

INSERT INTO `app_hrzg_widget_template` (`id`, `name`, `json_schema`, `editor_settings`, `form`)
VALUES
	(1,'Block Widget','{\r\n  \"title\": \"Block Widget\",\r\n  \"type\": \"object\",\r\n  \"properties\": {\r\n    \"blocks\": {\r\n      \"type\": \"array\",\r\n      \"format\": \"table\",\r\n      \"title\": \"Block\",\r\n      \"uniqueItems\": true,\r\n      \"items\": {\r\n        \"type\": \"object\",\r\n        \"title\": \"Block\",\r\n        \"properties\": {\r\n          \"above_subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"headline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"image_url\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"text_html\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"button\": {\r\n            \"type\": \"string\"\r\n          }\r\n        }\r\n      }\r\n    }\r\n  }\r\n}','',''),
	(2,'Slider Widget','{\r\n  \"title\": \"Slider Widget\",\r\n  \"type\": \"object\",\r\n  \"properties\": {\r\n    \"slides\": {\r\n      \"type\": \"array\",\r\n      \"format\": \"table\",\r\n      \"title\": \"Slides\",\r\n      \"uniqueItems\": true,\r\n      \"items\": {\r\n        \"type\": \"object\",\r\n        \"title\": \"Slide\",\r\n        \"properties\": {\r\n          \"above_subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"picture_url\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"headline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"text_html\": {\r\n            \"type\": \"string\"\r\n          }\r\n        }\r\n      }\r\n    }\r\n  }\r\n}','',''),
	(3,'Video Background Widget','{\r\n  \"title\": \"Video Background Widget\",\r\n  \"type\": \"object\",\r\n  \"properties\": {\r\n          \"youtube_id\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"above_subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"picture_url\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"headline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"button\": {\r\n            \"type\": \"string\"\r\n          }\r\n  }\r\n}','',''),
	(4,'Icon Slider Widget','{\r\n  \"title\": \"Icon Slider Widget\",\r\n  \"type\": \"object\",\r\n  \"properties\": {\r\n\"above_subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"headline\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"subline\": {\r\n            \"type\": \"string\"\r\n          },\r\n    \"blocks\": {\r\n      \"type\": \"array\",\r\n      \"format\": \"table\",\r\n      \"title\": \"Icons\",\r\n      \"uniqueItems\": true,\r\n      \"items\": {\r\n        \"type\": \"object\",\r\n        \"title\": \"Icons\",\r\n        \"properties\": {\r\n          \"picture_url\": {\r\n            \"type\": \"string\"\r\n          }\r\n        }\r\n      }\r\n    }\r\n  }\r\n}\r\n','',''),
	(5,'Gallery Widget','{\r\n  \"title\": \"Gallery Widget\",\r\n  \"type\": \"object\",\r\n  \"properties\": {\r\n    \"blocks\": {\r\n      \"type\": \"array\",\r\n      \"format\": \"table\",\r\n      \"title\": \"Images\",\r\n      \"uniqueItems\": true,\r\n      \"items\": {\r\n        \"type\": \"object\",\r\n        \"title\": \"Image\",\r\n        \"properties\": {\r\n          \"picture_url\": {\r\n            \"type\": \"string\"\r\n          },\r\n          \"text_html\": {\r\n            \"type\": \"string\"\r\n          }\r\n        }\r\n      }\r\n    }\r\n  }\r\n}\r\n','','');

/*!40000 ALTER TABLE `app_hrzg_widget_template` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
