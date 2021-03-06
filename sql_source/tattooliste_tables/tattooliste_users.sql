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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password_hash` char(64) NOT NULL,
  `user_role` int(11) NOT NULL,
  `person` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `user_role_id_idx` (`user_role`),
  CONSTRAINT `user_role_id` FOREIGN KEY (`user_role`) REFERENCES `user_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='password_hash for SHA-256 - User CAN create a person set BUT don''t have to.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'darkmio','$2y$11$SYs6fSpJ7IPXpIOGukFBReTi2lSooZWEAvUjOvagixjQe2zCVBCSe',1,NULL,'2015-11-23 20:22:05',NULL,1),(6,'fab','$2y$11$oY.0dV46k8ZAtgI5lJwQNOpZbIXnIwjaH4RctD96gXXOc6EPPOMDq',1,NULL,'0000-00-00 00:00:00',NULL,1),(7,'penisman','$2y$11$VJ184AGxnq5p2nkT/7Ol9uJE9g6Um5VDLvAZ3bb1mzHaRUxlGaIm.',1,NULL,'0000-00-00 00:00:00',NULL,1),(8,'deimudda','$2y$11$uVQ4JevlceGUplYQvW2VGOpUzXPEAhDqoEtbpV.V5p1XWmVzgiki6',1,NULL,'2015-11-24 12:44:20',NULL,1),(9,'pavlista','$2y$11$U3jvHm4AS3CVklxTQlEMzu8sO6qvNCVMk0dfBYyKPvEE8QndjnoCu',1,NULL,'2015-11-24 13:20:51',NULL,1),(10,'username','$2y$11$xeEWGXOm4pS4jXGfW17fbeUyaa64ieFfgROoWySOXPUHsJrlAWjPG',1,NULL,'2015-12-01 12:29:34',NULL,1),(11,'name','$2y$11$YNOailvjPpEC/9EnbjkqyepWS/UlIcTtBWiG6cIwecYTuepjrVndS',1,NULL,'2015-12-05 12:04:09',NULL,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-15 12:22:33
