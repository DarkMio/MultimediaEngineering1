-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tattooliste
-- ------------------------------------------------------
-- Server version	5.5.5-10.0.17-MariaDB-log

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

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` int(11) NOT NULL,
  `street_name` varchar(100) NOT NULL,
  `stree_nr` char(6) DEFAULT NULL,
  `geo_long` double NOT NULL,
  `geo_lat` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `location_id_idx` (`location`),
  CONSTRAINT `location_id` FOREIGN KEY (`location`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,1356,'Kaiserin-Augusta-Straße','16',13.382144272327423,52.460245705805484),(2,1288,'Templiner Str.','7',13.40859,52.53304),(3,5222,'Saalburgstraße','12',8.53147,50.34146),(4,1429,'Genter Straße','66',52.54893,13.3518),(5,1356,'Studuo','15',12.382144272327423,50.460245705805484),(6,1356,'Blumenthalstraße','12',53,13),(7,1356,'Blumenthalstraße','12',53,13),(8,1356,'Blumenthalstraße','12',53,13),(9,1356,'Seinestr.','12',44,22),(10,1356,'Blumenthalstraße','12',53,13),(11,1356,'Seinestr.','12',44,22),(12,1356,'Blumenthalstraße','12',53,13),(13,1356,'Seinestr.','12',44,22),(14,1356,'Blumenthalstraße','12',53,13),(15,1356,'Seinestr.','12',44,22),(16,1356,'Blumenthalstraße','12',53,13),(17,1356,'Seinestr.','12',44,22),(18,1356,'Blumenthalstraße','12',53,13),(19,1356,'Seinestr.','12',44,22),(20,1356,'Blumenthalstraße','12',53,13),(21,1356,'Seinestr.','12',44,22),(22,1356,'Blumenthalstraße','12',53,13),(23,1356,'Seinestr.','12',44,22),(24,1356,'Blumenthalstraße','12',53,13),(25,1356,'Seinestr.','12',44,22),(26,1356,'Blumenthalstraße','12',53,13),(27,1356,'Seinestr.','12',44,22),(28,1356,'Blumenthalstraße','12',53,13),(29,1356,'Seinestr.','12',44,22),(30,1356,'Blumenthalstraße','12',53,13),(31,1356,'Seinestr.','12',44,22),(32,1356,'Blumenthalstraße','12',53,13),(33,1356,'Seinestr.','12',44,22),(34,1356,'Blumenthalstraße','12',53,13),(35,1356,'Seinestr.','12',44,22),(36,1356,'Blumenthalstraße','12',53,13);
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-06 20:17:40
