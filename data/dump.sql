-- MySQL dump 10.13  Distrib 5.6.45, for FreeBSD12.0 (i386)
--
-- Host: localhost    Database: simba4
-- ------------------------------------------------------
-- Server version	5.6.45-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Table structure for table `storage_gallery`
--

DROP TABLE IF EXISTS `storage_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storage_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` char(50) NOT NULL COMMENT 'Имя раздела',
  `razdel_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ID раздела',
  `gallery_index` int(11) NOT NULL DEFAULT '0' COMMENT 'номер галереи, начиная с 0',
    `storage_gallery_name` char(100) DEFAULT NULL COMMENT 'Имя эл-та хранилища обработки отдельных фото галереи',
  `date_public` datetime DEFAULT NULL COMMENT 'дата публикации',
    `alt` char(255) COMMENT 'Подпись фото',
  `public` int(11) DEFAULT NULL COMMENT 'флаг публикации',
  `poz` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_public` (`date_public`),
  KEY `razdel` (`razdel`,`razdel_id`),
  KEY `public` (`public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='галерея в хранилище';
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-17 14:07:12
