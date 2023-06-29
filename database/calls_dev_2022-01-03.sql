-- MySQL dump 10.13  Distrib 8.0.21, for Linux (x86_64)
--
-- Host: localhost    Database: sdCalls
-- ------------------------------------------------------
-- Server version	8.0.21-0ubuntu0.20.04.4

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
CREATE DATABASE /*!32312 IF NOT EXISTS*/ `calls` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `calls`;

--
-- Table structure for table `call_group`
--

DROP TABLE IF EXISTS `call_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `call_group` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `call_group_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `call_group`
--

LOCK TABLES `call_group` WRITE;
/*!40000 ALTER TABLE `call_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `call_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,1,'Category 1','category-1','2021-11-08 08:00:38','2021-11-08 08:00:38'),(2,NULL,1,'Category 2','category-2','2021-11-08 08:00:38','2021-11-08 08:00:38');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_rows`
--

DROP TABLE IF EXISTS `data_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_rows` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `data_type_id` int unsigned NOT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `browse` tinyint(1) NOT NULL DEFAULT '1',
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `add` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `details` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `data_rows_data_type_id_foreign` (`data_type_id`),
  CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_rows`
--

LOCK TABLES `data_rows` WRITE;
/*!40000 ALTER TABLE `data_rows` DISABLE KEYS */;
INSERT INTO `data_rows` VALUES (1,1,'id','number','ID',1,0,0,0,0,0,NULL,1),(2,1,'name','text','Name',1,1,1,1,1,1,NULL,2),(3,1,'email','text','Email',1,1,1,1,1,1,NULL,3),(4,1,'password','password','Password',1,0,0,1,1,0,NULL,4),(5,1,'remember_token','text','Remember Token',0,0,0,0,0,0,NULL,5),(6,1,'created_at','timestamp','Created At',0,1,1,0,0,0,NULL,6),(7,1,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,7),(8,1,'avatar','image','Avatar',0,1,1,1,1,1,NULL,8),(9,1,'user_belongsto_role_relationship','relationship','Role',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"roles\",\"pivot\":0}',10),(10,1,'user_belongstomany_role_relationship','relationship','voyager::seeders.data_rows.roles',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"0\"}',11),(11,1,'settings','hidden','Settings',0,0,0,0,0,0,NULL,12),(12,2,'id','number','ID',1,0,0,0,0,0,NULL,1),(13,2,'name','text','Name',1,1,1,1,1,1,NULL,2),(14,2,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3),(15,2,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4),(16,3,'id','number','ID',1,0,0,0,0,0,NULL,1),(17,3,'name','text','Name',1,1,1,1,1,1,NULL,2),(18,3,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3),(19,3,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4),(20,3,'display_name','text','Display Name',1,1,1,1,1,1,NULL,5),(21,1,'role_id','text','Role',1,1,1,1,1,1,NULL,9),(22,4,'id','number','ID',1,0,0,0,0,0,NULL,1),(23,4,'parent_id','select_dropdown','Parent',0,0,1,1,1,1,'{\"default\":\"\",\"null\":\"\",\"options\":{\"\":\"-- None --\"},\"relationship\":{\"key\":\"id\",\"label\":\"name\"}}',2),(24,4,'order','text','Order',1,1,1,1,1,1,'{\"default\":1}',3),(25,4,'name','text','Name',1,1,1,1,1,1,NULL,4),(26,4,'slug','text','Slug',1,1,1,1,1,1,'{\"slugify\":{\"origin\":\"name\"}}',5),(27,4,'created_at','timestamp','Created At',0,0,1,0,0,0,NULL,6),(28,4,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,7),(29,5,'id','number','ID',1,0,0,0,0,0,NULL,1),(30,5,'author_id','text','Author',1,0,1,1,0,1,NULL,2),(31,5,'category_id','text','Category',1,0,1,1,1,0,NULL,3),(32,5,'title','text','Title',1,1,1,1,1,1,NULL,4),(33,5,'excerpt','text_area','Excerpt',1,0,1,1,1,1,NULL,5),(34,5,'body','rich_text_box','Body',1,0,1,1,1,1,NULL,6),(35,5,'image','image','Post Image',0,1,1,1,1,1,'{\"resize\":{\"width\":\"1000\",\"height\":\"null\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',7),(36,5,'slug','text','Slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\",\"forceUpdate\":true},\"validation\":{\"rule\":\"unique:posts,slug\"}}',8),(37,5,'meta_description','text_area','Meta Description',1,0,1,1,1,1,NULL,9),(38,5,'meta_keywords','text_area','Meta Keywords',1,0,1,1,1,1,NULL,10),(39,5,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"DRAFT\",\"options\":{\"PUBLISHED\":\"published\",\"DRAFT\":\"draft\",\"PENDING\":\"pending\"}}',11),(40,5,'created_at','timestamp','Created At',0,1,1,0,0,0,NULL,12),(41,5,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,13),(42,5,'seo_title','text','SEO Title',0,1,1,1,1,1,NULL,14),(43,5,'featured','checkbox','Featured',1,1,1,1,1,1,NULL,15),(44,6,'id','number','ID',1,0,0,0,0,0,NULL,1),(45,6,'author_id','text','Author',1,0,0,0,0,0,NULL,2),(46,6,'title','text','Title',1,1,1,1,1,1,NULL,3),(47,6,'excerpt','text_area','Excerpt',1,0,1,1,1,1,NULL,4),(48,6,'body','rich_text_box','Body',1,0,1,1,1,1,NULL,5),(49,6,'slug','text','Slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\"},\"validation\":{\"rule\":\"unique:pages,slug\"}}',6),(50,6,'meta_description','text','Meta Description',1,0,1,1,1,1,NULL,7),(51,6,'meta_keywords','text','Meta Keywords',1,0,1,1,1,1,NULL,8),(52,6,'status','select_dropdown','Status',1,1,1,1,1,1,'{\"default\":\"INACTIVE\",\"options\":{\"INACTIVE\":\"INACTIVE\",\"ACTIVE\":\"ACTIVE\"}}',9),(53,6,'created_at','timestamp','Created At',1,1,1,0,0,0,NULL,10),(54,6,'updated_at','timestamp','Updated At',1,0,0,0,0,0,NULL,11),(55,6,'image','image','Page Image',0,1,1,1,1,1,NULL,12),(56,8,'id','text','Id',1,1,0,0,0,0,'{}',1),(57,8,'program_id','text','Program Id',1,1,1,1,1,1,'{}',2),(58,8,'call_id','text','Call Id',1,1,1,1,1,1,'{}',4),(59,8,'start_end_formation_id','text','Start End Formation Id',0,1,1,1,1,1,'{}',5),(60,9,'id','text','Id',1,0,0,0,0,0,'{}',1),(61,9,'definition_id','text','Definition Id',1,1,1,1,1,1,'{}',2),(62,9,'fragment_id','text','Fragment Id',1,1,1,1,1,1,'{}',3),(63,9,'seq_no','text','Seq No',1,1,1,1,1,1,'{}',4),(64,10,'id','text','Id',1,1,0,0,0,0,'{}',1),(65,10,'type_id','number','Type Id',1,1,1,1,1,1,'{}',2),(66,10,'text','text','Text',1,1,1,1,1,1,'{}',3),(67,11,'id','text','Id',1,0,0,0,0,0,'{}',1),(68,11,'name','text','Name',1,1,1,1,1,1,'{}',2),(69,12,'id','text','Id',1,0,0,0,0,0,'{}',1),(70,12,'name','text','Name',1,1,1,1,1,1,'{}',2),(71,13,'id','text','Id',1,0,0,0,0,0,'{}',1),(72,13,'name','text','Name',1,1,1,1,1,1,'{}',2),(73,14,'id','text','Id',1,1,0,0,0,0,'{}',1),(74,14,'name','text','Name',1,1,1,1,1,1,'{}',2),(75,15,'id','text','Id',1,1,0,0,0,0,'{}',1),(76,15,'start_formation_id','text','Start Formation Id',0,1,1,1,1,1,'{}',2),(77,15,'end_formation_id','text','End Formation Id',0,1,1,1,1,1,'{}',3),(78,8,'definition_belongsto_program_relationship','relationship','program',1,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\Program\",\"table\":\"program\",\"type\":\"belongsTo\",\"column\":\"program_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',3),(79,8,'definition_belongsto_sd_call_relationship','relationship','sd_call',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\SdCall\",\"table\":\"sd_call\",\"type\":\"belongsTo\",\"column\":\"call_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',6),(80,8,'definition_belongsto_start_end_formation_relationship','relationship','start_end_formation',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\StartEndFormation\",\"table\":\"start_end_formation\",\"type\":\"belongsTo\",\"column\":\"start_end_formation_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',8),(82,9,'definition_fragment_belongsto_fragment_relationship','relationship','fragment',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\Fragment\",\"table\":\"fragment\",\"type\":\"belongsTo\",\"column\":\"fragment_id\",\"key\":\"id\",\"label\":\"text\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":null}',5),(83,8,'definition_hasmany_definition_fragment_relationship','relationship','definition_fragments',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\DefinitionFragment\",\"table\":\"definition_fragments\",\"type\":\"hasMany\",\"column\":\"definition_id\",\"key\":\"id\",\"label\":\"fragment_id\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',10),(84,8,'definition_hasmany_definition_relationship','relationship','definition text',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\Definition\",\"table\":\"definition\",\"type\":\"hasMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"all_fragment_texts\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',11),(85,8,'definition_belongsto_sd_call_relationship_1','relationship',':id',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\SdCall\",\"table\":\"sd_call\",\"type\":\"belongsTo\",\"column\":\"call_id\",\"key\":\"id\",\"label\":\"id\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',7),(86,8,'definition_belongsto_start_end_formation_relationship_1','relationship',':id',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\StartEndFormation\",\"table\":\"start_end_formation\",\"type\":\"belongsTo\",\"column\":\"start_end_formation_id\",\"key\":\"id\",\"label\":\"id\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',9),(87,15,'start_end_formation_hasone_start_end_formation_relationship','relationship','name',0,1,1,1,1,1,'{\"model\":\"App\\\\Models\\\\StartEndFormation\",\"table\":\"start_end_formation\",\"type\":\"hasOne\",\"column\":\"id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"categories\",\"pivot\":\"0\",\"taggable\":\"0\"}',4);
/*!40000 ALTER TABLE `data_rows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_types`
--

DROP TABLE IF EXISTS `data_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_types_name_unique` (`name`),
  UNIQUE KEY `data_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_types`
--

LOCK TABLES `data_types` WRITE;
/*!40000 ALTER TABLE `data_types` DISABLE KEYS */;
INSERT INTO `data_types` VALUES (1,'users','users','User','Users','voyager-person','TCG\\Voyager\\Models\\User','TCG\\Voyager\\Policies\\UserPolicy','TCG\\Voyager\\Http\\Controllers\\VoyagerUserController','',1,0,NULL,'2021-11-08 08:00:38','2021-11-08 08:00:38'),(2,'menus','menus','Menu','Menus','voyager-list','TCG\\Voyager\\Models\\Menu',NULL,'','',1,0,NULL,'2021-11-08 08:00:38','2021-11-08 08:00:38'),(3,'roles','roles','Role','Roles','voyager-lock','TCG\\Voyager\\Models\\Role',NULL,'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController','',1,0,NULL,'2021-11-08 08:00:38','2021-11-08 08:00:38'),(4,'categories','categories','Category','Categories','voyager-categories','TCG\\Voyager\\Models\\Category',NULL,'','',1,0,NULL,'2021-11-08 08:00:38','2021-11-08 08:00:38'),(5,'posts','posts','Post','Posts','voyager-news','TCG\\Voyager\\Models\\Post','TCG\\Voyager\\Policies\\PostPolicy','','',1,0,NULL,'2021-11-08 08:00:38','2021-11-08 08:00:38'),(6,'pages','pages','Page','Pages','voyager-file-text','TCG\\Voyager\\Models\\Page',NULL,'','',1,0,NULL,'2021-11-08 08:00:38','2021-11-08 08:00:38'),(8,'definition','definition','Definition','Definitions',NULL,'App\\Models\\Definition',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2021-11-08 18:02:29','2021-11-11 14:58:23'),(9,'definition_fragments','definition-fragments','Definition Fragment','Definition Fragments',NULL,'App\\Models\\DefinitionFragment',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2021-11-08 18:04:31','2021-11-08 18:04:31'),(10,'fragment','fragment','Fragment','Fragments',NULL,'App\\Models\\Fragment',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2021-11-08 18:06:08','2021-11-11 15:03:47'),(11,'formation','formation','Formation','Formations',NULL,'App\\Models\\Formation',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2021-11-08 18:07:33','2021-11-08 18:07:33'),(12,'fragment_type','fragment-type','Fragment Type','Fragment Types',NULL,'App\\Models\\FragmentType',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2021-11-08 18:24:46','2021-11-08 18:24:46'),(13,'program','program','Program','Programs',NULL,'App\\Models\\Program',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2021-11-08 18:25:14','2021-11-08 18:25:14'),(14,'sd_call','sd-call','Sd Call','Sd Calls',NULL,'App\\Models\\SdCall',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2021-11-08 18:26:41','2021-11-08 18:28:52'),(15,'start_end_formation','start-end-formation','Start End Formation','Start End Formations',NULL,'App\\Models\\StartEndFormation',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2021-11-08 18:27:26','2021-11-09 19:47:03');
/*!40000 ALTER TABLE `data_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `definition`
--

DROP TABLE IF EXISTS `definition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `definition` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint unsigned NOT NULL,
  `call_id` bigint unsigned NOT NULL,
  `start_end_formation_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `definition_uix_1` (`program_id`,`call_id`,`start_end_formation_id`),
  KEY `definition_FK_1` (`call_id`),
  KEY `definition_FK_2` (`start_end_formation_id`),
  CONSTRAINT `definition_FK` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `definition_FK_1` FOREIGN KEY (`call_id`) REFERENCES `sd_call` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `definition_FK_2` FOREIGN KEY (`start_end_formation_id`) REFERENCES `start_end_formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `definition`
--

LOCK TABLES `definition` WRITE;
/*!40000 ALTER TABLE `definition` DISABLE KEYS */;
INSERT INTO `definition` VALUES (1,3,1,1,NULL,NULL),(2,5,1,2,NULL,NULL),(3,6,1,1,NULL,NULL),(4,4,2,3,NULL,NULL),(5,4,2,4,NULL,NULL),(6,6,3,4,NULL,NULL);
/*!40000 ALTER TABLE `definition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `definition_fragments`
--

DROP TABLE IF EXISTS `definition_fragments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `definition_fragments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `definition_id` bigint unsigned NOT NULL,
  `fragment_id` bigint unsigned NOT NULL,
  `seq_no` tinyint unsigned NOT NULL DEFAULT '1',
  `part_no` tinyint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `definition_fragments_uix_1` (`definition_id`,`fragment_id`,`seq_no`),
  KEY `definition_fragments_FK` (`fragment_id`),
  CONSTRAINT `definition_fragments_FK` FOREIGN KEY (`fragment_id`) REFERENCES `fragment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `definition_fragments_FK_1` FOREIGN KEY (`definition_id`) REFERENCES `definition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `definition_fragments`
--

LOCK TABLES `definition_fragments` WRITE;
/*!40000 ALTER TABLE `definition_fragments` DISABLE KEYS */;
INSERT INTO `definition_fragments` VALUES (1,1,1,1,NULL),(2,1,2,2,NULL),(3,1,3,3,NULL),(4,2,4,1,NULL),(5,3,5,1,1),(6,3,6,2,2),(7,3,7,4,3),(8,4,8,1,NULL),(9,4,9,2,NULL),(10,5,8,1,NULL),(11,5,9,2,NULL),(13,6,11,1,NULL);
/*!40000 ALTER TABLE `definition_fragments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formation`
--

DROP TABLE IF EXISTS `formation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formation` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `formation_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formation`
--

LOCK TABLES `formation` WRITE;
/*!40000 ALTER TABLE `formation` DISABLE KEYS */;
INSERT INTO `formation` VALUES (1,'An Eight Chain Thru or Parallel Right-Hand Waves',NULL,NULL),(2,'Parallel Right-Hand Two-Faced Lines',NULL,NULL),(3,'An Eight Chain Thru or Parallel Left-Hand Waves',NULL,NULL),(4,'Parallel Left-Hand Two-Faced Lines',NULL,NULL),(5,'A Two-Faced Line',NULL,NULL),(6,'A Two by two',NULL,NULL),(7,'Parallel Lines with the Centers Back-to-Back',NULL,NULL),(8,'An Eight Chain Thru',NULL,NULL),(9,'Parallel Lines with the Ends in Tandem or from Promenaid',NULL,NULL),(10,'A 3 quarter Tag or other formations',NULL,NULL),(11,'Facing Lines',NULL,NULL),(12,'A generalized quarter Tag',NULL,NULL),(13,'Parallel Waves',NULL,NULL),(14,'Parallel Lines and other formations.',NULL,NULL),(15,'Usually ends in a Right-Hand quarter Line or a Left-Hand quarter Tag',NULL,NULL),(16,'A wave',NULL,NULL),(17,'Back-to-Back Couples',NULL,NULL),(18,'Columns',NULL,NULL),(19,'Parallel Lines or Parallellogram  Lines',NULL,NULL),(20,'A non T-Bone 2 x 2',NULL,NULL),(21,'A Right-Hand Wave',NULL,NULL),(22,'A 2 x 2',NULL,NULL),(23,'Parallel Lines with the Ends Back-to-Back, or other formations',NULL,NULL),(24,'Facing Couples',NULL,NULL),(26,'Back-to-Back Couples or Tandem Couples',NULL,NULL),(27,'A Right-Hand Mini-Wave Box',NULL,NULL),(28,'A Left-Hand Mini-Wave Box.',NULL,NULL),(29,'A Tidal Wave',NULL,NULL),(30,'Parallel Waves or Eight Chain Thru',NULL,NULL),(31,'Opposite-handed Parallel Waves',NULL,NULL),(32,'Parallel Waves or other formations',NULL,NULL),(36,'A Mini-Wave Box or other  2 x 2 formations',NULL,NULL),(37,'Parallel Two-Faced Lines or other formations',NULL,NULL),(38,'Parallel Lines with the Centers in a Mini-Wave Box or from other formations',NULL,NULL),(39,'A Wave or Facing Couples',NULL,NULL),(40,'A two by four',NULL,NULL),(41,'Parallel Waves, applicable 2 x 4 T-Bones, or the formation obtained after All 4 Couples Touch 1/4',NULL,NULL),(42,'Parallel Waves, Point-to-Point Diamonds, or a Thar',NULL,NULL),(43,'Parallel Lines, Columns, or other formations',NULL,NULL),(44,'A Mini-Wave Box ends in a Mini-Wave Box',NULL,NULL),(45,'Parallel Lines end in Parallel Lines',NULL,NULL),(46,'Parallel Lines or Columns end in Columns',NULL,NULL),(47,'A non-T-Bone 2 x 4 ends in Parallel Lines',NULL,NULL),(48,'Columns with ends facing out',NULL,NULL),(49,'A mini-wave box',NULL,NULL),(50,'Twin Diamonds, a 3/4 Tag, or other formations',NULL,NULL),(51,'Twin Diamonds end in Parallel Inverted Lines',NULL,NULL),(52,'A Static Set with two opposite couples Facing Out or from the 2 x 4 formation obtained from a Static Set after Heads Pass Thru',NULL,NULL),(53,'Parallel Waves end in Parallel Waves',NULL,NULL),(54,'A 3/4 Tag',NULL,NULL),(55,'A 1/4 Tag',NULL,NULL),(56,'An Eight Chain Thru, the formation obtained from a Static Set after Heads Pass Thru, or from other formations',NULL,NULL),(57,'Parallel Waves, applicable Parallel Lines, Eight Chain Thru, 2 x 4 T-Bones, or other formations',NULL,NULL),(58,'Usually ends in Back-to-Back Lines or T-Bones (with everyone facing out)',NULL,NULL),(59,'A Mini-Wave Box, Tandem Couples, or applicable C',NULL,NULL),(60,'Any Line of 4 or other formations',NULL,NULL),(61,'A Line of 4 ends in a Line of 4',NULL,NULL),(62,'A Mini-Wave Box or applicable T-Bone 2 x 2',NULL,NULL),(63,'A R-H or L-H Mini-Wave Box ends in a Diamond, a T-Bone 2 x 2 usually ends in a Wave',NULL,NULL),(64,'An hourglass',NULL,NULL),(65,'Usually ends in Parallel Lines',NULL,NULL),(66,'A Couple, Mini-Wave, or other applicable formation',NULL,NULL),(67,'A Double Pass Thru or a R-H 1/4 Tag',NULL,NULL),(68,'Parallel Lines or a Thar | Promenaide',NULL,NULL),(69,'Applicable formations with the Centers in a 2 x 2.',NULL,NULL),(70,'A non-T-Bone ends in Facing Couples',NULL,NULL),(71,'Applicable formations including all 2 x 4 setups, Twin Diamonds, 1/4 Tag',NULL,NULL),(72,'Parallel Two-Faced Lines or Promenaide',NULL,NULL),(73,'A 3/4 Tag ends in an Eight Chain Thru',NULL,NULL),(74,'Parallel Lines with the Ends in Tandem, or from other formations',NULL,NULL),(75,'Parallel Two-Faced Lines',NULL,NULL),(76,'A Static Set (or from the formation obtained from a Static Set after Heads Step into the Center) or from a Wave between and perpendicular to Facing Couples',NULL,NULL),(77,'Parallell right-hand waves',NULL,NULL),(78,'Applicable Tidal Lines or applicable Parallel Lines',NULL,NULL),(79,'Parallel Lines, Twin Diamonds, a Generalized Dhar, or other applicable formations.',NULL,NULL),(80,'Applicable 3- or 4-dancer formations',NULL,NULL),(81,'Usually ends in the same formation as the starting formation.',NULL,NULL),(82,'A Generalized 1/4 Tag in which the Very Centers are directly facing an outside dancer',NULL,NULL),(83,'Parallel Lines',NULL,NULL),(84,'Parallel Waves, Eight Chain Thru, or other applicable formations',NULL,NULL),(85,'Parallel Waves, applicable Parallel Lines, Trade By, applicable 2 x 4 T-Bones, or other formations',NULL,NULL),(86,'Parallel Lines|Waves (or a Parallelogram if dancers end on the same spot)',NULL,NULL),(87,'Double Pass Thru, Eight Chain Thru, Facing Lines, or other formations',NULL,NULL),(88,'Facing Couples or T-Bone 2 x 2s in which everyone is a Trailer',NULL,NULL),(89,'Facing Couples end in Back-to-Back Couples',NULL,NULL),(90,'Applicable formations',NULL,NULL),(91,'Facing Dancers',NULL,NULL),(92,'A Thar, a Squared Set after everyone Face In, or from other applicable formations',NULL,NULL),(93,'A Thar',NULL,NULL),(94,'A 4-dancer formation such as a R-H Mini-Wave Box, a R-H Facing Diamond (Centers with R-H, Ends with L-H), or other applicable formations',NULL,NULL),(95,'A Wave. This is a 2-part call',NULL,NULL),(96,'Twin Diamonds, an Hourglass, or other applicable formations',NULL,NULL),(97,'The same formation as the starting formation, unless two dancers meet on the same spot',NULL,NULL),(98,'A generalized Couple or other applicable formation',NULL,NULL),(99,'Applicable Facing Couples or an applicable R-H Wave',NULL,NULL),(100,'Normal Facing Couples',NULL,NULL),(101,'Parallel Lines with the Ends in Tandem or from Promenade (in which case designated dancers act as Leaders in Parallel Two-Faced Lines)',NULL,NULL),(102,'A 1/4 Tag formation only (at A1 and A2)',NULL,NULL),(103,'A 1/4 Tag formation ends in Parallel Waves.',NULL,NULL),(104,'Applicable formations with one or more Out-Facing Couples not directly looking at any other dancers',NULL,NULL),(105,'A Generalized 2 x 4 (usually Parallel Lines), Generalized Thar, or other applicable formations',NULL,NULL),(106,'Facing Couples (or a R-H Wave)',NULL,NULL),(107,'Any Line of 4',NULL,NULL),(108,'Either Facing Couples (if the Ends start facing opposite directions), or Tandem Couples (if the Ends start facing the same direction)',NULL,NULL),(109,'Normal Facing Couples (Boy on Left, Girl on Right)',NULL,NULL),(110,'Half-sashayed Back-to-Back Couples',NULL,NULL),(111,'A Line of 4, or other applicable formations',NULL,NULL),(112,'Applicable formations consisting of more than 4 dancers',NULL,NULL),(113,'Parallel Columns of {n}, where n is greater than or equal to 3',NULL,NULL),(114,'A Wave of 2n',NULL,NULL),(115,'Columns with the Ends Facing Out, or from other applicable formations',NULL,NULL),(116,'Columns end in an Eight Chain Thru.',NULL,NULL),(117,'A Wave, applicable Line of 4, or applicable Diamond',NULL,NULL),(118,'Usually ends in the same formation, but rotated 90°.',NULL,NULL),(119,'Any Line of 4 or other applicable formations',NULL,NULL),(120,'A Line of 4 ends in a Line of 4.',NULL,NULL),(121,'Applicable formations (Facing Lines, Eight Chain Thru, or designated dancers (Heads or Sides) from a Squared Set)',NULL,NULL),(122,'A Mini-Wave or Couple only',NULL,NULL),(123,'A Mini-Wave',NULL,NULL),(124,'A Couple or Mini-Wave',NULL,NULL),(125,'Back-to-Back Dancers',NULL,NULL),(126,'Facing Dancers, a R-H Mini-Wave, or other applicable 8-dancer formations',NULL,NULL),(127,'A Couple',NULL,NULL),(128,'A L-H Wave',NULL,NULL),(129,'A Generalized Tandem (i.e., Front-to-Back Dancers, Back-to-Back Dancers, or Facing Dancers)',NULL,NULL),(130,'A Mini-Wave Box ends in Back-to-Back Couples.',NULL,NULL),(131,'The T-Bone formation obtained from Facing Couples after one Couple does a turn 1/4 to face one another (1/4 In), or from other applicable formations',NULL,NULL),(132,'A Line of 4 ends in a 2 x 2 or a \"Z\".',NULL,NULL),(133,'Facing Couples, or from a T-Bone 2 x 2 in which everyone is a Trailer',NULL,NULL),(134,'Facing Couples end in Back-to-Back Couples.',NULL,NULL),(135,'Mini-Wave Columns',NULL,NULL),(136,'A 1/4 Box ends in Mini-Wave Columns; Mini-Wave Columns end in a 3/4 Box.',NULL,NULL),(137,'A Starting Double Pass Thru formation in which the Centers are normal Couples and the Outsides are Half-Sashayed, from a normal Eight Chain Thru formation, or from other applicable formations',NULL,NULL),(138,'The aforementioned Starting Double Pass Thru formation ends in normal Back-to-Back Lines.',NULL,NULL),(139,'A 1 x 8 Line or other applicable 8-dancer formation',NULL,NULL),(140,'Same formation as starting formation',NULL,NULL);
/*!40000 ALTER TABLE `formation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fragment`
--

DROP TABLE IF EXISTS `fragment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fragment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_id` bigint unsigned NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fragment_uidx` (`type_id`,`text`(100)),
  CONSTRAINT `fragment_FK` FOREIGN KEY (`type_id`) REFERENCES `fragment_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fragment`
--

LOCK TABLES `fragment` WRITE;
/*!40000 ALTER TABLE `fragment` DISABLE KEYS */;
INSERT INTO `fragment` VALUES (1,1,'Ends Cross Fold',NULL,NULL),(2,2,'to face furthest centers',NULL,NULL),(3,1,'as Centers Fold behind Ends, follow them partway, then adjust to form a Couple',NULL,NULL),(4,1,'Beaus 1/2 Box Circulate and Right-face U-Turn Back as Belles Left Dodge and Veer Left.',NULL,NULL),(5,1,'Centers Fold behind Ends and all adjust to a Mini-Wave Box',NULL,NULL),(6,1,'Box Counter Rotate 1/4',NULL,NULL),(7,1,'Roll',NULL,NULL),(8,1,'Ends Cloverleaf as Centers Partner Tag',NULL,NULL),(9,2,'turn 1/4 to face each other & Pass Thru',NULL,NULL),(11,1,'Outside Triangles Circulate as the Very Centers Trade',NULL,NULL),(12,1,'Turn 90° in place toward the adjacent dancer.','2021-11-24 08:26:01','2021-11-24 08:26:01'),(13,1,'Turn 90° in place away from the adjacent dancer.','2021-11-24 08:26:01','2021-11-24 08:26:01'),(14,1,'Arm Turn 1/2; Move forward (Centers in a star, Outsides around the outside) in a Circular path to meet the n-th dancer, where n = 1 for 1/4 Top, n = 2 for 1/2 Top, or n = 3 for 3/4 Top.','2021-11-24 08:26:01','2021-11-24 08:26:01'),(15,1,'Outside 6 Circulate as Very Center 2 Trade.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(16,1,'The Beau is the Left-side dancer and the Belle is the Right-side dancer.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(17,1,'Ends: 1/2 Zoom, Cast Off 3/4 and Spread (to become Ends of Lines); Out-Facing Centers: Cloverleaf; In-Facing Centers: Extend, Arm Turn 1/4, and Extend.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(18,1,'Adjust as necessary to end in Parallel Lines.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(19,1,'Out-Facing Couples (Step Ahead if necessary, and) Cloverleaf. The Others (move in to the center if necessary, and) do the anything call.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(20,1,'Out-Facing Couples Cross Cloverleaf (as one movement Half Sashay and Cloverleaf). The Others (move in to the center if necessary, and) do the anything call.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(21,1,'As one movement, Circulate and Half Sashay.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(22,1,'Pass Thru and Half Sashay.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(23,1,'Mini-Wave dancers Recycle as the Couple dancers Wheel and Deal.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(24,1,'If both Ends start facing the same direction, the rightmost End and adjacent dancer go in front of the leftmost End and adjacent dancer.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(25,1,'As one movement, the Ends','2021-11-24 08:26:02','2021-11-24 08:26:02'),(26,1,'That is, Ends do the Ends part of Bend The Line.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(27,1,'Explode; then do the anything call.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(28,1,'As one movement, Centers Step Ahead and 1/4 In as Ends 1/4 In and Step Ahead. Adjust as necessary to end in Facing Couples.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(29,1,'Explode; Right Pull By.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(30,1,'#1 dancer, as one movement, 1/2 Circulate and U-Turn Back (Roll twice) as the Others 1/2 Circulate and Arm Turn 3/4.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(31,1,'Ends Cloverleaf as Centers Partner Tag (turn 1/4 to face each other and Pass Thru).','2021-11-24 08:26:02','2021-11-24 08:26:02'),(32,1,'Centers Arm Turn 1/4 as the Ends move up 1/4 of a Circle around the center point of the 4-dancer formation (i.e., Ends do your part Fan The Top).','2021-11-24 08:26:02','2021-11-24 08:26:02'),(33,1,'Walk forward if necessary to meet the facing dancer, then Face Out (individually turn 1/4 to face away from the center of the set).','2021-11-24 08:26:02','2021-11-24 08:26:02'),(34,1,'Do 1/2 of a Trade.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(35,1,'Turn 1/4 in place to face the adjacent dancer (1/4 In); Pass Thru.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(36,1,'Pass Thru; Face In (individually turn 1/4 toward the center of the set).','2021-11-24 08:26:02','2021-11-24 08:26:02'),(37,1,'Pass Thru; Face Out (individually turn 1/4 away from the center of the set).','2021-11-24 08:26:02','2021-11-24 08:26:02'),(38,1,'As one movement, Leaders Right-face U-Turn Back and all Step To A Right-Hand Mini-Wave.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(39,1,'As one movement, Leaders Left-face U-Turn Back and all Step To A Left-Hand Mini-Wave.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(40,1,'Leaders Dodge (sidestep into the adjacent position as in Walk and Dodge) as Trailers do your part Scoot Back (Extend, Trade, and Extend).','2021-11-24 08:26:02','2021-11-24 08:26:02'),(41,1,'Those who can Right Pull By and turn 1/4 to face the inactives (1/4 In); all Finish a Square Thru n (i.e., Left Square Thru n - 1).','2021-11-24 08:26:02','2021-11-24 08:26:02'),(42,1,'Centers Step Ahead as Ends Slide into the vacated adjacent Center position.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(43,1,'Belles walk forward (Box Circulate) as Beaus flip (Run) into the adjacent Belle\'s position.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(44,1,'Beaus walk forward (Box Circulate) as Belles flip (Run) into the adjacent Beau\'s position.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(45,1,'First two dancers in the Column (#1 and #2) move forward in Single File around the outside of the other Column until parallel with the Center Box and then individually Face In (to end as a Couple) as the Last Two dancers (#3 and #4) Circulate (to form a Center Box) and Cast Off 3/4; all Extend.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(46,1,'From a 1/4 Box, Mini-Wave Columns, or other applicable formations.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(47,1,'The six who can diagonally Pull By using outside hands.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(48,1,'Center Six (3 pairs of dancers) Trade.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(49,1,'As one movement, 1/2 Tag then turn 1/4 in place toward your initial turning direction.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(50,1,'Belles 1/4 Right as Beaus diagonal Pass Thru while turning 1/4 to the right.','2021-11-24 08:26:02','2021-11-24 08:26:02'),(51,1,'Beaus 1/4 Left as Belles diagonal left-shoulder Pass Thru while turning 1/4 to the left.','2021-11-24 08:26:02','2021-11-24 08:26:02');
/*!40000 ALTER TABLE `fragment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fragment_type`
--

DROP TABLE IF EXISTS `fragment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fragment_type` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fragment_type_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fragment_type`
--

LOCK TABLES `fragment_type` WRITE;
/*!40000 ALTER TABLE `fragment_type` DISABLE KEYS */;
INSERT INTO `fragment_type` VALUES (1,'Normal text',NULL,NULL),(2,'Paranthesis',NULL,NULL);
/*!40000 ALTER TABLE `fragment_type` ENABLE KEYS */;
UNLOCK TABLES;



--
-- Table structure for table `program`
--

DROP TABLE IF EXISTS `program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `program` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program`
--

LOCK TABLES `program` WRITE;
/*!40000 ALTER TABLE `program` DISABLE KEYS */;
INSERT INTO `program` VALUES (1,'B',NULL,NULL),(2,'P',NULL,NULL),(3,'MS',NULL,NULL),(4,'A1',NULL,NULL),(5,'A2',NULL,NULL),(6,'C1',NULL,NULL),(7,'C2',NULL,NULL),(8,'C3a',NULL,NULL),(9,'C3b',NULL,NULL),(10,'C4',NULL,NULL);
/*!40000 ALTER TABLE `program` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `sd_call`
--

DROP TABLE IF EXISTS `sd_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sd_call` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New_call_name',
  `call_group_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sd_call_name_unique` (`name`),
  KEY `sd_call_FK` (`call_group_id`),
  CONSTRAINT `sd_call_FK` FOREIGN KEY (`call_group_id`) REFERENCES `call_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sd_call`
--

LOCK TABLES `sd_call` WRITE;
/*!40000 ALTER TABLE `sd_call` DISABLE KEYS */;
INSERT INTO `sd_call` VALUES (1,'Recycle',NULL,NULL,NULL),(2,'Horseshoe turn',NULL,NULL,NULL),(3,'3 By 2 Acey Deucey',NULL,NULL,NULL),(4,'1/4 | 1/2 | 3/4 Top',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(5,'1/4 (or 3/4) Thru',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(6,'6 By 2 Acey Deucey',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(7,'anything and Cross',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(8,'Beau | Belle',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(9,'Brace Thru',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(10,'Cast A Shadow',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(11,'Chain Reaction',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(12,'Crossover Circulate',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(13,'Cross Trail Thru',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(14,'Cycle and Wheel',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(15,'Double Star Thru',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(16,'Ends Bend',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(17,'Explode And anything',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(18,'Explode',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(19,'Explode The Line',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(20,'Grand 1/4 (or 3/4) Thru',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(21,'Grand Follow Your Neighbor',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(22,'Lockit',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(23,'Mix',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(24,'Pair Off',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(25,'Partner Hinge',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(26,'Partner Tag',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(27,'Pass The Sea',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(28,'Scoot and Dodge',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(29,'Split Square Thru (n)',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(30,'Square Chain Thru',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(31,'Step and Slide',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(32,'Transfer The Column',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(33,'Triple Cross',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(34,'Triple Star Thru',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(35,'Triple Trade',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02'),(36,'Turn and Deal',NULL,'2021-11-24 08:26:02','2021-11-24 08:26:02');
/*!40000 ALTER TABLE `sd_call` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `start_end_formation`
--

DROP TABLE IF EXISTS `start_end_formation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `start_end_formation` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `start_formation_id` bigint unsigned DEFAULT NULL,
  `end_formation_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `start_end_formation_uix_1` (`start_formation_id`,`end_formation_id`),
  KEY `start_end_formation_FK_2` (`end_formation_id`),
  CONSTRAINT `start_end_formation_FK_1` FOREIGN KEY (`start_formation_id`) REFERENCES `formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `start_end_formation_FK_2` FOREIGN KEY (`end_formation_id`) REFERENCES `formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `start_end_formation`
--

LOCK TABLES `start_end_formation` WRITE;
/*!40000 ALTER TABLE `start_end_formation` DISABLE KEYS */;
INSERT INTO `start_end_formation` VALUES (1,16,24),(40,24,17),(2,24,21),(3,48,8),(32,62,130),(5,66,91),(4,90,NULL),(6,92,93),(7,94,95),(8,96,97),(9,98,NULL),(10,99,100),(11,101,83),(12,102,103),(13,104,NULL),(14,105,NULL),(15,106,17),(30,106,128),(20,107,17),(39,107,22),(19,107,24),(16,107,108),(17,109,110),(18,111,NULL),(21,112,NULL),(22,113,114),(23,115,116),(24,117,118),(25,119,120),(34,119,132),(26,121,NULL),(27,122,123),(28,124,125),(29,126,127),(31,129,NULL),(33,131,17),(35,133,134),(36,135,13),(37,137,138),(38,139,140);
/*!40000 ALTER TABLE `start_end_formation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (1,'data_types','display_name_singular',5,'pt','Post','2021-11-08 08:00:38','2021-11-08 08:00:38'),(2,'data_types','display_name_singular',6,'pt','Página','2021-11-08 08:00:38','2021-11-08 08:00:38'),(3,'data_types','display_name_singular',1,'pt','Utilizador','2021-11-08 08:00:38','2021-11-08 08:00:38'),(4,'data_types','display_name_singular',4,'pt','Categoria','2021-11-08 08:00:38','2021-11-08 08:00:38'),(5,'data_types','display_name_singular',2,'pt','Menu','2021-11-08 08:00:38','2021-11-08 08:00:38'),(6,'data_types','display_name_singular',3,'pt','Função','2021-11-08 08:00:39','2021-11-08 08:00:39'),(7,'data_types','display_name_plural',5,'pt','Posts','2021-11-08 08:00:39','2021-11-08 08:00:39'),(8,'data_types','display_name_plural',6,'pt','Páginas','2021-11-08 08:00:39','2021-11-08 08:00:39'),(9,'data_types','display_name_plural',1,'pt','Utilizadores','2021-11-08 08:00:39','2021-11-08 08:00:39'),(10,'data_types','display_name_plural',4,'pt','Categorias','2021-11-08 08:00:39','2021-11-08 08:00:39'),(11,'data_types','display_name_plural',2,'pt','Menus','2021-11-08 08:00:39','2021-11-08 08:00:39'),(12,'data_types','display_name_plural',3,'pt','Funções','2021-11-08 08:00:39','2021-11-08 08:00:39'),(13,'categories','slug',1,'pt','categoria-1','2021-11-08 08:00:39','2021-11-08 08:00:39'),(14,'categories','name',1,'pt','Categoria 1','2021-11-08 08:00:39','2021-11-08 08:00:39'),(15,'categories','slug',2,'pt','categoria-2','2021-11-08 08:00:39','2021-11-08 08:00:39'),(16,'categories','name',2,'pt','Categoria 2','2021-11-08 08:00:39','2021-11-08 08:00:39'),(17,'pages','title',1,'pt','Olá Mundo','2021-11-08 08:00:39','2021-11-08 08:00:39'),(18,'pages','slug',1,'pt','ola-mundo','2021-11-08 08:00:39','2021-11-08 08:00:39'),(19,'pages','body',1,'pt','<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>','2021-11-08 08:00:39','2021-11-08 08:00:39'),(20,'menu_items','title',1,'pt','Painel de Controle','2021-11-08 08:00:39','2021-11-08 08:00:39'),(21,'menu_items','title',2,'pt','Media','2021-11-08 08:00:39','2021-11-08 08:00:39'),(22,'menu_items','title',12,'pt','Publicações','2021-11-08 08:00:39','2021-11-08 08:00:39'),(23,'menu_items','title',3,'pt','Utilizadores','2021-11-08 08:00:39','2021-11-08 08:00:39'),(24,'menu_items','title',11,'pt','Categorias','2021-11-08 08:00:39','2021-11-08 08:00:39'),(25,'menu_items','title',13,'pt','Páginas','2021-11-08 08:00:39','2021-11-08 08:00:39'),(26,'menu_items','title',4,'pt','Funções','2021-11-08 08:00:39','2021-11-08 08:00:39'),(27,'menu_items','title',5,'pt','Ferramentas','2021-11-08 08:00:39','2021-11-08 08:00:39'),(28,'menu_items','title',6,'pt','Menus','2021-11-08 08:00:39','2021-11-08 08:00:39'),(29,'menu_items','title',7,'pt','Base de dados','2021-11-08 08:00:39','2021-11-08 08:00:39'),(30,'menu_items','title',10,'pt','Configurações','2021-11-08 08:00:39','2021-11-08 08:00:39');
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Temporary view structure for view `v_call`
--

DROP TABLE IF EXISTS `v_call`;
/*!50001 DROP VIEW IF EXISTS `v_call`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_call` AS SELECT 
 1 AS `definition_id`,
 1 AS `program_id`,
 1 AS `program_name`,
 1 AS `call_id`,
 1 AS `call_name`,
 1 AS `start_end_formation_id`,
 1 AS `start_formation_id`,
 1 AS `end_formation_id`,
 1 AS `start_formation_name`,
 1 AS `end_formation_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_call_def`
--

DROP TABLE IF EXISTS `v_call_def`;
/*!50001 DROP VIEW IF EXISTS `v_call_def`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_call_def` AS SELECT 
 1 AS `definition_id`,
 1 AS `program_id`,
 1 AS `program_name`,
 1 AS `call_id`,
 1 AS `call_name`,
 1 AS `start_end_formation_id`,
 1 AS `start_formation_id`,
 1 AS `end_formation_id`,
 1 AS `start_formation_name`,
 1 AS `end_formation_name`,
 1 AS `definition_fragments_id`,
 1 AS `fragment_id`,
 1 AS `seq_no`,
 1 AS `type_id`,
 1 AS `text`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_definition_fragments`
--

DROP TABLE IF EXISTS `v_definition_fragments`;
/*!50001 DROP VIEW IF EXISTS `v_definition_fragments`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_definition_fragments` AS SELECT 
 1 AS `definition_fragments_id`,
 1 AS `definition_id`,
 1 AS `fragment_id`,
 1 AS `seq_no`,
 1 AS `type_id`,
 1 AS `text`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'sdCalls'
--

--
-- Final view structure for view `v_call`
--

/*!50001 DROP VIEW IF EXISTS `v_call`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`trygve`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_call` AS select `d`.`id` AS `definition_id`,`d`.`program_id` AS `program_id`,`p`.`name` AS `program_name`,`d`.`call_id` AS `call_id`,`c`.`name` AS `call_name`,`d`.`start_end_formation_id` AS `start_end_formation_id`,`sef`.`start_formation_id` AS `start_formation_id`,`sef`.`end_formation_id` AS `end_formation_id`,`sf`.`name` AS `start_formation_name`,`ef`.`name` AS `end_formation_name` from (((((`definition` `d` left join `program` `p` on((`p`.`id` = `d`.`program_id`))) left join `sd_call` `c` on((`c`.`id` = `d`.`call_id`))) left join `start_end_formation` `sef` on((`sef`.`id` = `d`.`start_end_formation_id`))) left join `formation` `sf` on((`sf`.`id` = `sef`.`start_formation_id`))) left join `formation` `ef` on((`ef`.`id` = `sef`.`end_formation_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_call_def`
--

/*!50001 DROP VIEW IF EXISTS `v_call_def`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`trygve`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_call_def` AS select `d`.`id` AS `definition_id`,`d`.`program_id` AS `program_id`,`p`.`name` AS `program_name`,`d`.`call_id` AS `call_id`,`c`.`name` AS `call_name`,`d`.`start_end_formation_id` AS `start_end_formation_id`,`sef`.`start_formation_id` AS `start_formation_id`,`sef`.`end_formation_id` AS `end_formation_id`,`sf`.`name` AS `start_formation_name`,`ef`.`name` AS `end_formation_name`,`df`.`id` AS `definition_fragments_id`,`df`.`fragment_id` AS `fragment_id`,`df`.`seq_no` AS `seq_no`,`f`.`type_id` AS `type_id`,`f`.`text` AS `text` from (((((((`definition` `d` left join `program` `p` on((`p`.`id` = `d`.`program_id`))) left join `sd_call` `c` on((`c`.`id` = `d`.`call_id`))) left join `start_end_formation` `sef` on((`sef`.`id` = `d`.`start_end_formation_id`))) left join `formation` `sf` on((`sf`.`id` = `sef`.`start_formation_id`))) left join `formation` `ef` on((`ef`.`id` = `sef`.`end_formation_id`))) left join `definition_fragments` `df` on((`df`.`definition_id` = `d`.`id`))) left join `fragment` `f` on((`f`.`id` = `df`.`fragment_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_definition_fragments`
--

/*!50001 DROP VIEW IF EXISTS `v_definition_fragments`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`trygve`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_definition_fragments` AS select `df`.`id` AS `definition_fragments_id`,`df`.`definition_id` AS `definition_id`,`df`.`fragment_id` AS `fragment_id`,`df`.`seq_no` AS `seq_no`,`f`.`type_id` AS `type_id`,`f`.`text` AS `text` from ((`definition_fragments` `df` left join `v_call` `vc` on((`vc`.`definition_id` = `df`.`definition_id`))) left join `fragment` `f` on((`f`.`id` = `df`.`fragment_id`))) */;
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

-- Dump completed on 2022-01-03 19:32:59
