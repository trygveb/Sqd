-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: schedule_test
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `schedule`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `schedule` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `schedule`;

--
-- Table structure for table `groupsize`
--

DROP TABLE IF EXISTS `groupsize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupsize` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned NOT NULL,
  `size` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `groupsize_FK_2` (`schedule_id`),
  KEY `groupsize_user_id_schedule_id_index` (`user_id`,`schedule_id`),
  CONSTRAINT `groupsize_FK_1` FOREIGN KEY (`user_id`) REFERENCES `laravel`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `groupsize_FK_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupsize`
--

LOCK TABLES `groupsize` WRITE;
/*!40000 ALTER TABLE `groupsize` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupsize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_schedule`
--

DROP TABLE IF EXISTS `member_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_schedule` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned NOT NULL,
  `name_in_schema` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_size` tinyint unsigned NOT NULL DEFAULT '1',
  `admin` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_schedule_UX_1` (`user_id`,`schedule_id`),
  KEY `member_schedule_FK_1` (`user_id`),
  KEY `member_schedule_FK_2` (`schedule_id`),
  CONSTRAINT `member_schedule_FK_1` FOREIGN KEY (`user_id`) REFERENCES `laravel`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `member_schedule_FK_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='This is a many-to-many relationship connecting members and schedules';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_schedule`
--

LOCK TABLES `member_schedule` WRITE;
/*!40000 ALTER TABLE `member_schedule` DISABLE KEYS */;
INSERT INTO `member_schedule` VALUES (1,10,1,'Farmor',1,2,'2023-04-04 07:04:21','2023-04-04 07:04:21'),(2,4,1,'Fnatte',1,1,'2023-04-05 05:04:45','2023-04-05 05:20:21'),(3,3,1,'Knatte',1,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(4,5,1,'Tjatte',1,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(5,11,2,'Jocke',1,2,'2023-04-05 05:22:37','2023-04-05 05:22:37'),(6,4,2,'Fnatte',1,0,'2023-04-05 05:24:23','2023-04-05 05:24:23'),(7,7,2,'Jan',1,0,'2023-04-05 05:24:23','2023-04-05 05:24:23'),(8,2,2,'Kajsa',1,0,'2023-04-05 05:24:23','2023-04-05 05:24:23'),(9,1,2,'Kalle',1,0,'2023-04-05 05:24:23','2023-04-05 05:24:23'),(10,3,2,'Knatte',1,0,'2023-04-05 05:24:23','2023-04-05 05:24:23'),(11,6,2,'Mårten',2,0,'2023-04-05 05:24:23','2023-04-05 05:24:23'),(12,5,2,'Tjatte',1,0,'2023-04-05 05:24:23','2023-04-05 05:24:23'),(13,8,2,'Musse',2,0,'2023-04-05 05:29:34','2023-04-05 05:29:34'),(14,1,1,'Kalle',2,0,'2023-04-05 13:52:57','2023-04-05 13:52:57'),(15,8,1,'Musse',1,0,'2023-04-05 13:52:58','2023-04-05 13:52:58');
/*!40000 ALTER TABLE `member_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_schedule_date`
--

DROP TABLE IF EXISTS `member_schedule_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_schedule_date` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `schedule_date_id` bigint unsigned NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_schedule_date_FK_1` (`user_id`),
  KEY `member_schedule_date_FK_2` (`schedule_date_id`),
  CONSTRAINT `member_schedule_date_FK_1` FOREIGN KEY (`user_id`) REFERENCES `laravel`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `member_schedule_date_FK_2` FOREIGN KEY (`schedule_date_id`) REFERENCES `schedule_date` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='This table contains the status for each member on each schedule date';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_schedule_date`
--

LOCK TABLES `member_schedule_date` WRITE;
/*!40000 ALTER TABLE `member_schedule_date` DISABLE KEYS */;
INSERT INTO `member_schedule_date` VALUES (1,10,1,0,'2023-04-04 07:09:57','2023-04-04 07:09:57'),(6,10,6,1,'2023-04-05 05:02:09','2023-04-05 05:13:00'),(7,10,7,1,'2023-04-05 05:02:51','2023-04-05 05:13:00'),(13,10,13,1,'2023-04-05 05:03:47','2023-04-05 05:13:00'),(14,10,14,1,'2023-04-05 05:03:47','2023-04-05 05:13:00'),(15,10,15,1,'2023-04-05 05:03:47','2023-04-05 05:13:00'),(18,4,1,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(19,4,6,1,'2023-04-05 05:04:45','2023-04-05 05:19:30'),(20,4,7,1,'2023-04-05 05:04:45','2023-04-05 05:19:30'),(21,4,13,1,'2023-04-05 05:04:45','2023-04-05 05:19:30'),(22,4,14,1,'2023-04-05 05:04:45','2023-04-05 05:19:30'),(23,4,15,1,'2023-04-05 05:04:45','2023-04-05 05:19:30'),(26,3,1,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(27,3,6,1,'2023-04-05 05:04:45','2023-04-05 05:13:38'),(28,3,7,3,'2023-04-05 05:04:45','2023-04-05 05:13:38'),(29,3,13,4,'2023-04-05 05:04:45','2023-04-05 05:13:38'),(30,3,14,1,'2023-04-05 05:04:45','2023-04-05 05:13:38'),(31,3,15,3,'2023-04-05 05:04:45','2023-04-05 05:13:38'),(34,5,1,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(35,5,6,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(36,5,7,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(37,5,13,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(38,5,14,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(39,5,15,0,'2023-04-05 05:04:45','2023-04-05 05:04:45'),(42,10,18,1,'2023-04-05 05:12:47','2023-04-05 05:13:00'),(43,4,18,1,'2023-04-05 05:12:47','2023-04-05 05:19:30'),(44,3,18,4,'2023-04-05 05:12:47','2023-04-05 05:13:38'),(45,5,18,0,'2023-04-05 05:12:47','2023-04-05 05:12:47'),(50,11,20,1,'2023-04-05 05:26:05','2023-04-05 05:26:22'),(51,4,20,1,'2023-04-05 05:26:05','2023-04-05 05:26:48'),(52,7,20,3,'2023-04-05 05:26:05','2023-04-05 05:28:27'),(53,2,20,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(54,1,20,1,'2023-04-05 05:26:05','2023-04-05 05:28:53'),(55,3,20,1,'2023-04-05 05:26:05','2023-04-05 05:28:00'),(56,6,20,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(57,5,20,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(58,11,21,1,'2023-04-05 05:26:05','2023-04-05 05:26:22'),(59,4,21,1,'2023-04-05 05:26:05','2023-04-05 05:26:48'),(60,7,21,1,'2023-04-05 05:26:05','2023-04-05 05:28:27'),(61,2,21,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(62,1,21,1,'2023-04-05 05:26:05','2023-04-05 05:28:53'),(63,3,21,1,'2023-04-05 05:26:05','2023-04-05 05:28:00'),(64,6,21,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(65,5,21,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(66,11,22,1,'2023-04-05 05:26:05','2023-04-05 05:26:22'),(67,4,22,3,'2023-04-05 05:26:05','2023-04-05 05:26:48'),(68,7,22,1,'2023-04-05 05:26:05','2023-04-05 05:28:27'),(69,2,22,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(70,1,22,1,'2023-04-05 05:26:05','2023-04-05 05:28:53'),(71,3,22,3,'2023-04-05 05:26:05','2023-04-05 05:28:00'),(72,6,22,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(73,5,22,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(74,11,23,1,'2023-04-05 05:26:05','2023-04-05 05:26:22'),(75,4,23,3,'2023-04-05 05:26:05','2023-04-05 05:26:48'),(76,7,23,1,'2023-04-05 05:26:05','2023-04-05 05:28:27'),(77,2,23,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(78,1,23,1,'2023-04-05 05:26:05','2023-04-05 05:28:53'),(79,3,23,4,'2023-04-05 05:26:05','2023-04-05 05:28:00'),(80,6,23,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(81,5,23,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(82,11,24,1,'2023-04-05 05:26:05','2023-04-05 05:26:22'),(83,4,24,4,'2023-04-05 05:26:05','2023-04-05 05:26:48'),(84,7,24,1,'2023-04-05 05:26:05','2023-04-05 05:28:27'),(85,2,24,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(86,1,24,1,'2023-04-05 05:26:05','2023-04-05 05:28:53'),(87,3,24,1,'2023-04-05 05:26:05','2023-04-05 05:28:00'),(88,6,24,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(89,5,24,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(90,11,25,1,'2023-04-05 05:26:05','2023-04-05 05:26:22'),(91,4,25,1,'2023-04-05 05:26:05','2023-04-05 05:26:48'),(92,7,25,1,'2023-04-05 05:26:05','2023-04-05 05:28:27'),(93,2,25,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(94,1,25,1,'2023-04-05 05:26:05','2023-04-05 05:28:53'),(95,3,25,1,'2023-04-05 05:26:05','2023-04-05 05:28:00'),(96,6,25,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(97,5,25,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(98,11,26,1,'2023-04-05 05:26:05','2023-04-05 05:26:22'),(99,4,26,1,'2023-04-05 05:26:05','2023-04-05 05:26:48'),(100,7,26,1,'2023-04-05 05:26:05','2023-04-05 05:28:27'),(101,2,26,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(102,1,26,1,'2023-04-05 05:26:05','2023-04-05 05:28:53'),(103,3,26,1,'2023-04-05 05:26:05','2023-04-05 05:28:00'),(104,6,26,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(105,5,26,0,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(106,8,20,2,'2023-04-05 05:29:34','2023-04-05 05:30:32'),(107,8,21,2,'2023-04-05 05:29:34','2023-04-05 05:30:32'),(108,8,22,3,'2023-04-05 05:29:34','2023-04-05 05:30:32'),(109,8,23,3,'2023-04-05 05:29:34','2023-04-05 05:30:32'),(110,8,24,2,'2023-04-05 05:29:34','2023-04-05 05:30:32'),(111,8,25,1,'2023-04-05 05:29:34','2023-04-05 05:30:32'),(112,8,26,4,'2023-04-05 05:29:34','2023-04-05 05:30:32'),(117,1,1,0,'2023-04-05 13:52:57','2023-04-05 13:52:57'),(118,1,6,1,'2023-04-05 13:52:58','2023-04-05 13:53:38'),(119,1,7,2,'2023-04-05 13:52:58','2023-04-05 13:53:38'),(120,1,13,2,'2023-04-05 13:52:58','2023-04-05 13:53:38'),(121,1,14,1,'2023-04-05 13:52:58','2023-04-05 13:53:38'),(122,1,15,0,'2023-04-05 13:52:58','2023-04-05 13:52:58'),(123,1,18,3,'2023-04-05 13:52:58','2023-04-05 13:53:38'),(124,8,1,0,'2023-04-05 13:52:58','2023-04-05 13:52:58'),(125,8,6,0,'2023-04-05 13:52:58','2023-04-05 13:52:58'),(126,8,7,0,'2023-04-05 13:52:58','2023-04-05 13:52:58'),(127,8,13,0,'2023-04-05 13:52:58','2023-04-05 13:52:58'),(128,8,14,0,'2023-04-05 13:52:58','2023-04-05 13:52:58'),(129,8,15,0,'2023-04-05 13:52:58','2023-04-05 13:52:58'),(130,8,18,0,'2023-04-05 13:52:58','2023-04-05 13:52:58');
/*!40000 ALTER TABLE `member_schedule_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedule` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `default_weekday` tinyint unsigned NOT NULL DEFAULT '0',
  `default_start_time` time NOT NULL DEFAULT '19:00:00',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schedule_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='This table should contain a (unique) name for each schedule/course';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule`
--

LOCK TABLES `schedule` WRITE;
/*!40000 ALTER TABLE `schedule` DISABLE KEYS */;
INSERT INTO `schedule` VALUES (1,'Farmors   schedule','Mainstream',2,'20:30:00',NULL,'2023-04-04 07:04:21','2023-04-05 05:33:49'),(2,'Joakims schedule','Advanced',3,'19:00:00',NULL,'2023-04-05 05:22:37','2023-04-05 05:33:49');
/*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule_date`
--

DROP TABLE IF EXISTS `schedule_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedule_date` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint unsigned NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL DEFAULT '19:00:00',
  `comment` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedule_date_FK` (`schedule_id`),
  CONSTRAINT `schedule_date_FK` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='This table contains all dates for all schedules, with start-time and comment';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule_date`
--

LOCK TABLES `schedule_date` WRITE;
/*!40000 ALTER TABLE `schedule_date` DISABLE KEYS */;
INSERT INTO `schedule_date` VALUES (1,1,'2023-04-03','19:00:00',NULL,'2023-04-04 07:09:57','2023-04-04 07:09:57'),(6,1,'2023-04-05','19:00:00','1','2023-04-05 05:02:09','2023-04-05 05:04:10'),(7,1,'2023-04-10','19:00:00','2','2023-04-05 05:02:51','2023-04-05 05:04:10'),(13,1,'2023-04-17','19:00:00','3','2023-04-05 05:03:47','2023-04-05 05:04:10'),(14,1,'2023-04-24','19:00:00','4','2023-04-05 05:03:47','2023-04-05 05:04:10'),(15,1,'2023-05-01','19:00:00','5','2023-04-05 05:03:47','2023-04-05 05:04:10'),(18,1,'2023-05-02','19:00:00','6','2023-04-05 05:12:47','2023-04-05 13:52:27'),(20,2,'2023-04-05','19:00:00',NULL,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(21,2,'2023-04-12','19:00:00',NULL,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(22,2,'2023-04-19','19:00:00',NULL,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(23,2,'2023-04-26','19:00:00',NULL,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(24,2,'2023-05-03','19:00:00',NULL,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(25,2,'2023-05-10','19:00:00',NULL,'2023-04-05 05:26:05','2023-04-05 05:26:05'),(26,2,'2023-05-17','19:00:00',NULL,'2023-04-05 05:26:05','2023-04-05 05:26:05');
/*!40000 ALTER TABLE `schedule_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `v_member_schedule`
--

DROP TABLE IF EXISTS `v_member_schedule`;
/*!50001 DROP VIEW IF EXISTS `v_member_schedule`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_member_schedule` AS SELECT 
 1 AS `user_id`,
 1 AS `user_name`,
 1 AS `name_in_schema`,
 1 AS `email`,
 1 AS `schedule_id`,
 1 AS `schedule_name`,
 1 AS `schedule_description`,
 1 AS `default_weekday`,
 1 AS `default_start_time`,
 1 AS `password`,
 1 AS `group_size`,
 1 AS `admin`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_member_schedule_date`
--

DROP TABLE IF EXISTS `v_member_schedule_date`;
/*!50001 DROP VIEW IF EXISTS `v_member_schedule_date`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_member_schedule_date` AS SELECT 
 1 AS `user_id`,
 1 AS `schedule_date_id`,
 1 AS `status`,
 1 AS `user_name`,
 1 AS `schedule_id`,
 1 AS `group_size`,
 1 AS `schedule_date`*/;
SET character_set_client = @saved_cs_client;

--
-- Current Database: `schedule`
--

USE `schedule`;

--
-- Final view structure for view `v_member_schedule`
--

/*!50001 DROP VIEW IF EXISTS `v_member_schedule`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`trygve`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_member_schedule` AS select `u`.`id` AS `user_id`,`u`.`complete_name` AS `user_name`,`mt`.`name_in_schema` AS `name_in_schema`,`u`.`email` AS `email`,`t`.`id` AS `schedule_id`,`t`.`name` AS `schedule_name`,`t`.`description` AS `schedule_description`,`t`.`default_weekday` AS `default_weekday`,time_format(`t`.`default_start_time`,'%H:%i') AS `default_start_time`,`t`.`password` AS `password`,`mt`.`group_size` AS `group_size`,`mt`.`admin` AS `admin` from ((`member_schedule` `mt` left join `schedule` `t` on((`t`.`id` = `mt`.`schedule_id`))) left join `laravel`.`users` `u` on((`u`.`id` = `mt`.`user_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_member_schedule_date`
--

/*!50001 DROP VIEW IF EXISTS `v_member_schedule_date`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_member_schedule_date` AS select `msd`.`user_id` AS `user_id`,`msd`.`schedule_date_id` AS `schedule_date_id`,`msd`.`status` AS `status`,`vms`.`user_name` AS `user_name`,`vms`.`schedule_id` AS `schedule_id`,`vms`.`group_size` AS `group_size`,`sd`.`schedule_date` AS `schedule_date` from (((`member_schedule_date` `msd` left join `v_member_schedule` `vms` on((`vms`.`user_id` = `msd`.`user_id`))) left join `schedule_date` `sd` on((`sd`.`id` = `msd`.`schedule_date_id`))) left join `laravel`.`users` `u` on((`u`.`id` = `msd`.`user_id`))) where (`vms`.`schedule_id` = `sd`.`schedule_id`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-03  5:21:19
