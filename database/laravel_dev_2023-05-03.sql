-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: laravel
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
-- Current Database: `laravel`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `laravel` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `laravel`;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2022_01_03_155331_create_schedule_table',1),(6,'2022_01_03_180829_create_schedule_date_table',1),(7,'2022_01_03_181850_create_member_schedule_table',1),(8,'2022_01_03_183519_create_member_schedule_date_table',1),(9,'2022_01_16_140535_create_groupsize_table',2),(10,'2022_01_16_175857_create_view_member_schedule',2),(11,'2022_01_16_182550_create_view_member_schedule_date',2),(12,'2022_01_27_082709_modify_view_member_schedule_date',2),(13,'2023_02_02_112703_add_column_schema_name_to_member_schedule_table',3),(14,'2023_02_02_150653_create_view_member_schedule_1',4),(15,'2023_02_09_095002_refactor_user_names',5),(16,'2023_02_09_150144_add_column_complete_name',5),(17,'2023_02_09_162009_update_user_names',6),(18,'2023_02_09_184226_rename_user_columns',7),(19,'2023_02_20_104401_add_column_default_weekday_to_schedule',8),(20,'2023_02_26_092322_add_column_default_start_time_to_schedule',8),(21,'2023_03_16_072832_create_view_member_schedule_2',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `complete_name` varchar(72) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (concat(`first_name`,_utf8mb4' ',coalesce(`middle_name`,_utf8mb4''),_utf8mb4' ',`family_name`)) VIRTUAL,
  `first_name` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'first_name',
  `middle_name` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `family_name` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'family_name',
  `authority` tinyint unsigned NOT NULL DEFAULT '0',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `first_name`, `middle_name`, `family_name`, `authority`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (1,'Kalle',NULL,'Anka',0,'kalle.anka@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.',NULL,'2023-04-04 06:46:47','2023-04-04 06:46:47'),(2,'Kajsa',NULL,'Anka',0,'kajsa.anka@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.',NULL,'2023-04-04 06:46:47','2023-04-04 06:46:47'),(3,'Knatte',NULL,'Anka',0,'knatte.anka@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.','mShywWrhJXPIGR0Ru7gIm9XcR0Riz8eZ39JE2xrFMEfPa7dxVGwNGPGQgDqo','2023-04-04 06:46:47','2023-04-04 06:46:47'),(4,'Fnatte',NULL,'Anka',0,'fnatte.anka@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.',NULL,'2023-04-04 06:46:47','2023-04-04 06:46:47'),(5,'Tjatte',NULL,'Anka',0,'tjatte.anka@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.',NULL,'2023-04-04 06:46:47','2023-04-04 06:46:47'),(6,'Mårten',NULL,'Gås',0,'morten.gas@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.',NULL,'2023-04-04 06:46:47','2023-04-04 06:46:47'),(7,'Jan',NULL,'Långben',0,'jan.langben@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.',NULL,'2023-04-04 06:46:47','2023-04-04 06:46:47'),(8,'Musse',NULL,'Pigg',0,'musse.pigg@sqd.se','2023-04-04 06:46:47','$2y$10$Imx2nKgjH1bWkXWvf8FHS.JLfZCNqyumQjngrrnsWmGnv4a8piBZ.',NULL,'2023-04-04 06:46:47','2023-04-04 06:46:47'),(9,'Trygve','G','Botnen',2,'trygve.g.botnen@gmail.com','2023-04-04 06:50:02','$2y$10$gG0kMqsmEKHOVFroJ2B2oOcqD/4L3dt34ggxumgrQ3/Z/kj97XSWu','dzAyyPYdNFSPRBKKrGiCCzuxsxhRHYIZZpyMKoVvJogtdp42n2oKhjvoDFdz','2023-04-04 06:49:16','2023-04-04 06:50:02'),(10,'Farmor',NULL,'Anka',1,'farmor@sqd.se','2023-04-04 06:56:46','$2y$10$3VaVUlQzgn81h1CZYql4iOgYFmAO.4Ula2Em9NGnksKfVIuD2THaW','0oMVVUhVa6PnQcnQAeZ8cVsAJyMoym535Dn3rOiCVzMsbYNaaIgsRRAEisYX','2023-04-04 06:56:23','2023-04-04 06:56:46'),(11,'Joakim','von','Anka',1,'joakim@sqd.se','2023-04-04 07:01:42','$2y$10$XytnXA8eLErs4bXstzZZI.tJFIZffdj2nhhGzoM.ilsnX3V7KR626',NULL,'2023-04-04 07:01:35','2023-04-04 07:01:42');
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

-- Dump completed on 2023-05-03  4:40:31
