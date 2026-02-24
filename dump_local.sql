-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: pi_db
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-test_user_unique@example.com|172.21.0.1','i:1;',1771440840),('laravel-cache-test_user_unique@example.com|172.21.0.1:timer','i:1771440840;',1771440840);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_09_150000_create_products_table',1),(5,'2026_01_23_145751_create_personal_access_tokens_table',1),(6,'2026_01_23_160000_create_reviews_table',1),(7,'2026_02_16_170000_add_sustainability_fields_to_products',2),(8,'2026_02_18_000001_create_orders_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,2,'Intel Core i5-13600K',319.95,2,'2026-02-18 18:51:38','2026-02-18 18:51:38'),(2,2,3,'ASUS Dual GeForce RTX 4070 Super',659.90,2,'2026-02-18 19:02:40','2026-02-18 19:02:40'),(3,2,4,'Gigabyte GeForce RTX 4090 Gaming OC',1899.00,1,'2026-02-18 19:02:40','2026-02-18 19:02:40'),(4,3,4,'Gigabyte GeForce RTX 4090 Gaming OC',1899.00,1,'2026-02-19 14:00:13','2026-02-19 14:00:13'),(5,4,1,'AMD Ryzen 7 7800X3D',399.90,1,'2026-02-19 14:15:01','2026-02-19 14:15:01');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'card',
  `card_last_four` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,12,'AP-20260218-E3878','delivered',639.90,134.38,774.28,'card','4242','Pau Albero Mora','AUGsuyfASYFGA','Alcoi','03801','+34601713704',NULL,'2026-02-18 18:51:38','2026-02-18 18:59:21'),(2,12,'AP-20260218-ACEEE','cancelled',3218.80,675.95,3894.75,'card','4242','Pau Albero Mora','WESR','Alcoi','03801','+34601713704',NULL,'2026-02-18 19:02:40','2026-02-18 19:03:13'),(3,12,'AP-20260219-43CE4','shipped',1899.00,398.79,2297.79,'card','5213','Pau Albero Mora','adsdasdas','Alcoi','03801','+34601713704',NULL,'2026-02-19 14:00:13','2026-02-19 14:02:24'),(4,12,'AP-20260219-E1652','shipped',399.90,83.98,483.88,'card','4864','Pau Albero Mora','wSAEDASdsaD','Alcoi','03801','+34601713704',NULL,'2026-02-19 14:15:01','2026-02-19 14:22:01');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
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
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eco_score` tinyint unsigned NOT NULL DEFAULT '0',
  `is_refurbished` tinyint(1) NOT NULL DEFAULT '0',
  `is_recyclable` tinyint(1) NOT NULL DEFAULT '0',
  `has_eco_packaging` tinyint(1) NOT NULL DEFAULT '0',
  `is_local_supplier` tinyint(1) NOT NULL DEFAULT '0',
  `carbon_footprint` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_index` (`category`),
  KEY `products_price_index` (`price`),
  KEY `products_eco_score_index` (`eco_score`),
  KEY `products_is_refurbished_index` (`is_refurbished`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'CPU-AMD-7800X3D','AMD Ryzen 7 7800X3D','Procesador Gaming con tecnología 3D V-Cache 8 núcleos',399.90,29,'img/productos/CPU-AMD-7800X3D.png','Processadors',75,0,0,1,0,NULL,'2026-02-04 20:42:47','2026-02-19 14:15:01'),(2,'CPU-INT-13600K','Intel Core i5-13600K','Procesador Intel 13ª Gen 14 núcleos (6P+8E) hasta 5.1GHz',319.95,43,'img/productos/CPU-INT-13600K.png','Processadors',70,1,0,0,0,NULL,'2026-02-04 20:42:47','2026-02-18 18:51:38'),(3,'GPU-NV-4070S','ASUS Dual GeForce RTX 4070 Super','Gráfica 12GB GDDR6X ideal para 1440p con Ray Tracing',659.90,18,'img/productos/GPU-NV-4070S.png','Targetes Gràfiques',75,0,0,1,0,NULL,'2026-02-04 20:42:47','2026-02-18 19:02:40'),(4,'GPU-NV-4090','Gigabyte GeForce RTX 4090 Gaming OC','La tarjeta gráfica más potente del mercado 24GB GDDR6X',1899.00,5,'img/productos/GPU-NV-4090.png','Targetes Gràfiques',65,0,0,0,0,15.50,'2026-02-04 20:42:47','2026-02-19 14:51:17'),(5,'GPU-AMD-7800XT','Sapphire Pulse AMD Radeon RX 7800 XT','Gráfica AMD 16GB GDDR6 rendimiento excelente en rasterización',549.90,15,'img/productos/GPU-AMD-7800XT.png','Targetes Gràfiques',82,0,0,0,1,NULL,'2026-02-04 20:42:47','2026-02-17 19:39:42'),(6,'MB-ASUS-B650','ASUS TUF GAMING B650-PLUS WIFI','Placa base Socket AM5 DDR5 PCIe 5.0 para Ryzen 7000',199.99,40,'img/productos/MB-ASUS-B650.png','Plaques Base',78,1,0,0,0,NULL,'2026-02-04 20:42:47','2026-02-17 19:39:42'),(7,'RAM-COR-32D5','Corsair Vengeance DDR5 32GB (2x16GB)','Kit de memoria RAM DDR5 6000MHz CL30 optimizado AMD/Intel',124.99,60,'img/productos/RAM-COR-32D5.png','Memòria RAM',88,0,0,1,0,NULL,'2026-02-04 20:42:47','2026-02-17 19:39:42'),(8,'SSD-SAM-990','Samsung 990 PRO 2TB','SSD NVMe M.2 PCIe 4.0 velocidades de hasta 7450 MB/s',179.99,55,'img/productos/SSD-SAM-990.png','Emmagatzematge',85,0,0,0,0,0.50,'2026-02-04 20:42:47','2026-02-17 19:39:42'),(9,'PSU-COR-850','Corsair RM850x Shift 850W 80 Plus Gold','Fuente de alimentación modular con conectores laterales ATX 3.0',159.90,25,'img/productos/PSU-COR-850.png','Fonts Alimentació',92,0,0,1,0,NULL,'2026-02-04 20:42:47','2026-02-17 19:39:42'),(10,'CASE-NZXT-H5','NZXT H5 Flow RGB Blanco','Semitorre ATX con alto flujo de aire y ventiladores RGB',109.99,30,'img/productos/CASE-NZXT-H5.png','Caixes',76,1,0,0,0,NULL,'2026-02-04 20:42:47','2026-02-17 19:39:42'),(11,'CPU-AMD-5800X3D','AMD Ryzen 7 5800X3D','El mejor procesador para gaming en plataforma AM4 con 3D V-Cache',289.90,15,'img/productos/ryzen.png','Processadors',90,0,0,0,1,NULL,'2026-02-17 19:31:47','2026-02-17 19:39:42'),(12,'CPU-INT-14900K','Intel Core i9-14900K','Potencia extrema para creadores y gamers, hasta 6.0GHz',599.99,10,'img/productos/CPU-INT-14900K.png','Processadors',60,0,0,0,0,12.50,'2026-02-17 19:31:47','2026-02-17 19:39:42'),(13,'CPU-AMD-7600X','AMD Ryzen 5 7600X','Rendimiento excelente en gaming con la nueva arquitectura Zen 4',229.50,50,'img/productos/ryzen.png','Processadors',75,0,0,0,0,NULL,'2026-02-17 19:31:47','2026-02-17 19:39:42'),(14,'MB-MSI-Z790','MSI MPG Z790 EDGE WIFI','Placa base Intel Z790 LGA 1700 DDR5 para 12ª/13ª/14ª Gen',359.90,25,'img/productos/MB-MSI-Z790.png','Plaques Base',70,0,0,0,0,NULL,'2026-02-17 19:39:42','2026-02-17 19:39:42'),(15,'RAM-KIN-16D4','Kingston FURY Beast DDR4 16GB (2x8GB)','Memoria RAM DDR4 3200MHz CL16 ideal para actualizaciones',45.99,100,'img/productos/RAM-KIN-16D4.png','Memòria RAM',90,0,0,0,1,NULL,'2026-02-17 19:39:42','2026-02-17 19:39:42'),(16,'SSD-WD-SN850X','WD_BLACK SN850X 1TB','SSD NVMe Gaming PCIe 4.0 con disipador térmico',99.99,40,'img/productos/SSD-WD-SN850X.png','Emmagatzematge',72,0,0,0,0,NULL,'2026-02-17 19:39:42','2026-02-17 19:39:42'),(17,'PSU-MSI-1000','MSI MPG A1000G PCIE5 1000W 80 Plus Gold','Fuente 1000W modular preparada para RTX 40 Series',189.90,15,'img/productos/PSU-MSI-1000.png','Fonts Alimentació',80,0,0,0,0,NULL,'2026-02-17 19:39:42','2026-02-17 19:39:42'),(18,'CASE-COR-4000D','Corsair 4000D Airflow Cristal Templado','Caja ATX semitorre con panel frontal de alto flujo de aire',104.90,50,'img/productos/CASE-COR-4000D.png','Caixes',88,0,0,0,1,NULL,'2026-02-17 19:39:42','2026-02-17 19:39:42');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (3,9,2,'asda',5,'2026-02-11 18:21:06','2026-02-11 18:21:06'),(4,11,2,'SDASD',1,'2026-02-16 18:58:18','2026-02-16 18:58:18'),(5,12,2,'asdasdas',5,'2026-02-18 18:23:36','2026-02-18 18:23:36'),(6,12,4,'adsafasd',5,'2026-02-18 18:36:14','2026-02-18 18:36:14'),(7,12,1,'TOP',5,'2026-02-18 18:48:47','2026-02-18 18:48:47'),(8,12,7,'asdasdsa',5,'2026-02-18 18:49:33','2026-02-18 18:49:33'),(9,12,1,'fadsfsad',5,'2026-02-19 14:34:46','2026-02-19 14:34:46'),(10,12,1,'ivan',5,'2026-02-19 14:34:54','2026-02-19 14:34:54'),(11,12,1,'sfdfds',1,'2026-02-19 14:34:59','2026-02-19 14:34:59'),(12,12,12,'wer',5,'2026-02-19 14:43:24','2026-02-19 14:43:24'),(13,12,8,'ereqwrew',5,'2026-02-19 14:50:30','2026-02-19 14:50:30');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('cB8ETBMMIvKB0OG6fF5GXrKVHlNRYIjyh4b9BqES',12,'172.21.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNXlNOUhRVXAxYnVJMGs1cjlFUW9vMEN5RXA2TDVhSENuU0F0MmdqdyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImRkZWRhZjc1ZmFkMWFlZjNhZGY2MDljNjM1ZmE1NzQwNzlhMGI0MGI0NmEzZmJmMzk1MzQ1YzdiNGJmNDQ0YTkiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjEwNDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FwaS9wcm9kdWN0cz9sb2NhbF9vbmx5PWZhbHNlJnBhZ2U9MSZyZWZ1cmJpc2hlZF9vbmx5PWZhbHNlJnN1c3RhaW5hYmxlX29ubHk9ZmFsc2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771524684),('SlpPx3ZxjMbxaj52zopNXnbGxPZ6v7FIFYXUSH2u',12,'172.21.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoicmVyTW5veld2YWEzd3lmdU9qMW9jZDBWYVZSallGVlJVUzFERzJaRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvcHJvZHVjdHM/cGFnZT0xIjtzOjU6InJvdXRlIjtOO31zOjU6InN0YXRlIjtzOjQwOiJQSWhDbW1RYnJ0REtYZ1E1NFhsV3o2SjRiZnJLZncycE56enR5VWVTIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiZGRlZGFmNzVmYWQxYWVmM2FkZjYwOWM2MzVmYTU3NDA3OWEwYjQwYjQ2YTNmYmYzOTUzNDVjN2I0YmY0NDRhOSI7fQ==',1771512677),('xMUboOSxGffV1JDqHSCnZEiBCX1zrQPszHxQsZFm',12,'172.21.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVE1nV2IwSmpnNm8yc0lNOWVEY0NaYkpZN0trODRkMks2bEx6WTFhOSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImRkZWRhZjc1ZmFkMWFlZjNhZGY2MDljNjM1ZmE1NzQwNzlhMGI0MGI0NmEzZmJmMzk1MzQ1YzdiNGJmNDQ0YTkiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjUyOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3Byb2R1Y3RzLzIvcmVsYXRlZD9saW1pdD00IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1771533392);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin',NULL,NULL,'user','admin@pi-laravel.test',NULL,'$2y$12$M.J46hot8tKcEHN9WtY0HeIf.PEDF116TfW4vuZgU/L4LpDbp3/86',NULL,'2026-02-04 20:42:47','2026-02-04 20:42:47'),(2,'Joaquin Aufderhar',NULL,NULL,'user','lera.west@example.org','2026-02-04 20:42:47','$2y$12$TFtoHpuU9Sb.ojoE4RExReZWumvmuffEtK72iCyqIm8ZSXhSn1zDy','WLmPD3vYfc','2026-02-04 20:42:47','2026-02-04 20:42:47'),(3,'Lane Sipes',NULL,NULL,'user','mertz.kiana@example.net','2026-02-04 20:42:47','$2y$12$TFtoHpuU9Sb.ojoE4RExReZWumvmuffEtK72iCyqIm8ZSXhSn1zDy','zFriNmmr8v','2026-02-04 20:42:47','2026-02-04 20:42:47'),(4,'Darrell Jenkins',NULL,NULL,'user','mose63@example.net','2026-02-04 20:42:47','$2y$12$TFtoHpuU9Sb.ojoE4RExReZWumvmuffEtK72iCyqIm8ZSXhSn1zDy','k3C3iu0NaS','2026-02-04 20:42:47','2026-02-04 20:42:47'),(5,'Mrs. Carlotta Waelchi Sr.',NULL,NULL,'user','melisa.runolfsdottir@example.org','2026-02-04 20:42:47','$2y$12$TFtoHpuU9Sb.ojoE4RExReZWumvmuffEtK72iCyqIm8ZSXhSn1zDy','YmF2MFFHqB','2026-02-04 20:42:47','2026-02-04 20:42:47'),(6,'Prof. Ruthe Huel II',NULL,NULL,'user','vkiehn@example.org','2026-02-04 20:42:47','$2y$12$TFtoHpuU9Sb.ojoE4RExReZWumvmuffEtK72iCyqIm8ZSXhSn1zDy','wHEXs1dqpJ','2026-02-04 20:42:47','2026-02-04 20:42:47'),(7,'Pau Albero Mora',NULL,NULL,'user','pau.albero.mora@gmail.com',NULL,'$2y$12$wuNDLqWUfXG1D5j5TPnJYOA/rnv.J6LWHuuJ4RzRi/RGis.Q9xmqK',NULL,'2026-02-04 21:29:46','2026-02-04 21:29:46'),(9,'Pau Albero Mora',NULL,NULL,'user','pau@gmail.com',NULL,'$2y$12$sMo6NbCbE8U1k2RV8NGMFeK2dUCG8V/rGefOfCbUyLPfFyIUu/vxa',NULL,'2026-02-11 18:21:00','2026-02-11 18:21:00'),(10,'Supe',NULL,NULL,'user','supe@supe.supe',NULL,'$2y$12$wucRG7CYzyq48got2hNrfeDMihGKjcIBVioYrm0FMRmDze77UBQbu',NULL,'2026-02-13 14:27:04','2026-02-13 14:27:04'),(11,'nombre@gmail.com',NULL,NULL,'user','nombre@gmail.com',NULL,'$2y$12$ACNj9hDdZDHeqVtBE/0lN.NP2be08rOtyi5qQ1uV3x2vW5H6ojE4S',NULL,'2026-02-16 18:06:58','2026-02-16 18:06:58'),(12,'Admin',NULL,NULL,'admin','admin@admin.test',NULL,'$2y$12$wBXnQ3Dfh6XSVymxn4H64OIBYclff3lv07YSQ24u6yuknDs9.7mF.','burdFPpzV3WtQtcmSJOB0FnuIQ5WDfWXiucORvwAgNDPjLdjUHCCsWlkUR7R','2026-02-17 18:24:55','2026-02-17 18:24:55'),(13,'Prof. Florida Jaskolski',NULL,NULL,'user','viva24@example.org','2026-02-17 18:24:55','$2y$12$FoXQDRqrQGQNVxiKDzZ71.uXy6R8pHS/qDjIKDX24A/A5qyUlsoxu','tzx3TAhpfK','2026-02-17 18:24:55','2026-02-17 18:24:55'),(14,'Briana Nader',NULL,NULL,'user','funk.kayleigh@example.org','2026-02-17 18:24:55','$2y$12$FoXQDRqrQGQNVxiKDzZ71.uXy6R8pHS/qDjIKDX24A/A5qyUlsoxu','wfqQbl2Yle','2026-02-17 18:24:55','2026-02-17 18:24:55'),(15,'Tatum O\'Kon',NULL,NULL,'user','gleason.petra@example.org','2026-02-17 18:24:55','$2y$12$FoXQDRqrQGQNVxiKDzZ71.uXy6R8pHS/qDjIKDX24A/A5qyUlsoxu','WrAOskFNzP','2026-02-17 18:24:55','2026-02-17 18:24:55'),(16,'Dr. Danny Brown',NULL,NULL,'user','lwilkinson@example.com','2026-02-17 18:24:55','$2y$12$FoXQDRqrQGQNVxiKDzZ71.uXy6R8pHS/qDjIKDX24A/A5qyUlsoxu','waPIbCeDNl','2026-02-17 18:24:55','2026-02-17 18:24:55'),(17,'Jacey Wyman III',NULL,NULL,'user','emery.kuhic@example.org','2026-02-17 18:24:55','$2y$12$FoXQDRqrQGQNVxiKDzZ71.uXy6R8pHS/qDjIKDX24A/A5qyUlsoxu','Dyf8m1WmmU','2026-02-17 18:24:55','2026-02-17 18:24:55'),(18,'Prueba',NULL,NULL,'user','prueba@prueba.prueba',NULL,'$2y$12$y41RJCgb2P1263UQGxeVDOfjhhgUeMPt12y4oo3cOSC7cF6ccjfy.','Zj11ltIiyjyDYaUhDkoB1EIoGEw9WdXiVpgsGP4X67IjYklKH1OcJ5FKiDfd','2026-02-17 19:10:26','2026-02-17 19:16:51'),(19,'Test User',NULL,NULL,'user','final_test@example.com',NULL,'$2y$12$iUez8TyJIf7x8jIBiVdlEuKd19AAd/xWln5EsTC/65E0dsstwMekq',NULL,'2026-02-18 18:53:25','2026-02-18 18:53:25');
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

-- Dump completed on 2026-02-19 20:49:12
