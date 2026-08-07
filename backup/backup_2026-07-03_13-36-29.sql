-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: kasir_pos_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Makanan',NULL,1,'2026-07-02 06:06:48','2026-07-02 06:06:48'),(2,'Minuman',NULL,1,'2026-07-02 06:06:48','2026-07-02 06:06:48'),(3,'Snack',NULL,1,'2026-07-02 06:06:48','2026-07-02 06:06:48'),(4,'Elektronik',NULL,1,'2026-07-02 06:06:48','2026-07-02 06:06:48'),(5,'ATK','Alat Tulis Kantor',1,'2026-07-02 06:06:48','2026-07-02 11:36:44'),(6,'Peralatan',NULL,1,'2026-07-02 06:06:48','2026-07-02 06:06:48'),(7,'Lainnya',NULL,1,'2026-07-02 06:06:48','2026-07-02 06:06:48'),(8,'Obat','obat2an',1,'2026-07-02 10:27:54','2026-07-02 10:27:54'),(9,'Alat dapur','Alat dapur',1,'2026-07-02 10:30:33','2026-07-02 11:39:43'),(10,'Mainan','Mainan anak2',1,'2026-07-02 11:36:27','2026-07-02 11:36:27'),(11,'Perkakas RT','rmh tungga',1,'2026-07-02 11:37:59','2026-07-02 11:37:59'),(12,'Frozen food','Aneka Frozen food',1,'2026-07-02 11:38:38','2026-07-02 11:38:38'),(13,'Perawatan Pribadi','sabun shampoo odol dsb',1,'2026-07-02 11:46:25','2026-07-02 11:46:25');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_18_040010_create_products_table',1),(5,'2026_06_18_040016_create_transactions_table',1),(6,'2026_06_18_040021_create_transaction_details_table',1),(7,'2026_06_18_081911_add_barcode_to_products_table',2),(8,'2026_06_23_101154_create_stock_movements_table',3),(9,'2026_06_26_151041_create_stock_opnames_table',4),(10,'2026_06_26_151051_create_stock_opname_details_table',4),(11,'2026_06_30_002012_add_status_finished_to_stock_opnames_table',5),(12,'2026_07_01_085237_add_role_to_users_table',6),(13,'2026_07_01_134154_add_status_to_users_table',7),(14,'2026_07_02_124222_create_categories_table',8),(15,'2026_07_02_124259_add_new_columns_to_products_table',8),(16,'2026_07_02_191048_create_suppliers_table',9),(17,'2026_07_02_203229_create_purchase_orders_table',10),(18,'2026_07_02_203235_create_purchase_order_items_table',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `harga` decimal(15,0) NOT NULL,
  `harga_beli` decimal(15,0) DEFAULT NULL,
  `harga_diskon` decimal(15,0) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `min_stok` int(11) NOT NULL DEFAULT 5,
  `satuan` varchar(255) NOT NULL DEFAULT 'pcs',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_kode_barang_unique` (`kode_barang`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'psu20a','546314124457457','Power Supply CCTV 12V 20A',NULL,100000,NULL,NULL,9,5,'pcs','2026-06-18 01:18:19','2026-07-01 16:51:09'),(2,'pkcctvhk45','1231231312312','Paket CCTV Hilook 4 Chanel 500 Gb',4,2900000,NULL,NULL,4,5,'pcs','2026-06-18 01:18:19','2026-07-02 13:25:50'),(3,'keyb-quint','119000197276616','keyboard Quinton',4,52000,NULL,NULL,69,5,'pcs','2026-06-25 05:54:06','2026-07-02 11:57:44'),(4,'mou-lgtech1','182736817263','Mouse Logitech',NULL,78000,NULL,NULL,106,5,'pcs','2026-06-25 05:55:13','2026-06-30 14:46:18'),(5,'RinsoBbk500','123123123123123987','Rinso Detergent 500 gr',NULL,17500,NULL,NULL,97,5,'pcs','2026-06-25 05:56:24','2026-06-30 15:30:23'),(6,'sbn-giv','98712309871268','Sabun Giv',NULL,3500,NULL,3100,88,5,'pcs','2026-06-25 05:56:59','2026-07-01 14:22:09'),(7,'lap-asus-01','23123123123','Laptop Asus M14',NULL,13500000,NULL,NULL,9,5,'pcs','2026-06-25 05:58:44','2026-06-25 06:12:56'),(8,'ram-ddr4-1','21390802309850','RAM DDR4 12 Gb',NULL,800000,NULL,870000,19,5,'pcs','2026-06-25 10:05:32','2026-06-25 10:05:57'),(9,'kopi-kpl-api-01','1089012583497','kopi kapal api 20 gram',2,26500,NULL,25000,45,5,'pcs','2026-06-25 23:39:43','2026-07-02 11:57:52'),(10,'chiki-keju01','923749293492692','Chiki rasa keju tanggung',1,13000,NULL,NULL,120,5,'pcs','2026-06-25 23:40:40','2026-07-02 06:50:05'),(11,'kecap-bango01','1230120','Kecap Bango 250ml',1,14000,NULL,NULL,28,5,'pcs','2026-06-25 23:41:16','2026-07-02 11:57:33'),(12,'snack-mixmax100','98123871628','mix max 100 gr',3,3000,NULL,NULL,100,5,'pcs','2026-06-30 14:51:52','2026-07-02 11:58:10'),(13,'milo150','129391','Milo 150gr',2,12000,NULL,NULL,20,5,'pcs','2026-06-30 14:52:20','2026-07-02 11:58:18'),(14,'sbn-lux-biru','751203802131','sabun lux batang biru',NULL,4500,NULL,NULL,20,5,'pcs','2026-06-30 14:52:52','2026-07-01 17:00:09'),(15,'deo-rexo-men-ijo','92139193871','rexona men hijau deodoran',NULL,13500,NULL,NULL,20,5,'pcs','2026-06-30 14:53:36','2026-06-30 14:53:36'),(16,'kurma01','12738716861826','kurma ya\'lam',NULL,87500,NULL,NULL,20,5,'pcs','2026-07-01 14:22:59','2026-07-01 14:24:01'),(17,'permen-rlx-01','1293912739127','relaxa biru 100gr',NULL,12000,NULL,NULL,15,5,'pcs','2026-07-01 16:49:48','2026-07-01 16:49:48'),(18,'energen01','0123876816','Energen ori saset',2,2800,NULL,NULL,50,5,'pcs','2026-07-02 06:51:36','2026-07-02 11:57:18'),(19,'kopiya01','127391287','kopi Ya! 150gr',NULL,15000,NULL,NULL,20,5,'pcs','2026-07-02 06:59:45','2026-07-02 06:59:45'),(20,'DesakuKari01','87168101879','Desaku Kari',1,2200,NULL,NULL,20,5,'pcs','2026-07-02 07:00:53','2026-07-02 11:57:09'),(21,'sbnmedcar-01','6872348268346','sabun medicare biru',NULL,3500,NULL,NULL,40,5,'pcs','2026-07-02 07:01:40','2026-07-02 07:01:40'),(22,'johnsonbabyoil01','29371927','johnson baby oil',13,19000,NULL,NULL,10,5,'pcs','2026-07-02 07:02:30','2026-07-02 11:57:27'),(23,'bktulis-SD','721391729','Buku Tulis Sinar dunia',5,9000,NULL,NULL,15,5,'pcs','2026-07-02 12:23:45','2026-07-02 13:24:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(15,0) NOT NULL,
  `subtotal` decimal(15,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
INSERT INTO `purchase_order_items` VALUES (14,1,12,15,3000,45000,'2026-07-03 03:36:00','2026-07-03 03:36:00'),(15,1,19,10,15000,150000,'2026-07-03 03:36:00','2026-07-03 03:36:00'),(16,1,20,15,2200,33000,'2026-07-03 03:36:00','2026-07-03 03:36:00');
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `po_date` date NOT NULL,
  `status` enum('DRAFT','ORDERED','RECEIVED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
  `total` decimal(15,0) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
INSERT INTO `purchase_orders` VALUES (1,'PO-20260703-0001',3,'2026-07-03','DRAFT',228000,NULL,2,'2026-07-02 17:04:42','2026-07-03 03:36:00');
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES ('CgbzGwtE6AD0ylerUW7hUNo1afoFJyIsfhxMj45W',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQTBvMVBJWlZ5QTdjbFRLSHoxaW9ieXBwS01ScWlFY2xJUmw0OWZ3biI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvcHVyY2hhc2luZy8xL2VkaXQiO3M6NToicm91dGUiO3M6MTU6InB1cmNoYXNpbmcuZWRpdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1783049763);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `stock_before` int(11) NOT NULL,
  `stock_after` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES (1,1,'SALE',-1,35,34,'INV-20260624-00001',NULL,'2026-06-24 07:58:38','2026-06-24 07:58:38'),(2,1,'SALE',-2,34,32,'INV-20260624-00002',NULL,'2026-06-24 07:59:18','2026-06-24 07:59:18'),(3,2,'SALE',-1,15,14,'INV-20260624-00002',NULL,'2026-06-24 07:59:18','2026-06-24 07:59:18'),(4,2,'SALE',-1,14,13,'INV-20260624-00003',NULL,'2026-06-24 08:03:33','2026-06-24 08:03:33'),(5,2,'SALE',-1,13,12,'INV-20260624-00004',NULL,'2026-06-24 08:06:25','2026-06-24 08:06:25'),(6,1,'SALE',-2,32,30,'INV-20260624-00004',NULL,'2026-06-24 08:06:25','2026-06-24 08:06:25'),(7,1,'SALE',-2,30,28,'INV-20260625-00001',NULL,'2026-06-24 19:04:12','2026-06-24 19:04:12'),(8,1,'SALE',-6,28,22,'INV-20260625-00002',NULL,'2026-06-25 02:29:48','2026-06-25 02:29:48'),(9,1,'SALE',-1,22,21,'INV-20260625-00003',NULL,'2026-06-25 02:40:10','2026-06-25 02:40:10'),(10,2,'SALE',-1,12,11,'INV-20260625-00003',NULL,'2026-06-25 02:40:10','2026-06-25 02:40:10'),(11,1,'SALE',-8,21,13,'INV-20260625-00004',NULL,'2026-06-25 03:47:37','2026-06-25 03:47:37'),(12,2,'SALE',-2,11,9,'INV-20260625-00004',NULL,'2026-06-25 03:47:37','2026-06-25 03:47:37'),(13,2,'SALE',-2,9,7,'INV-20260625-00005',NULL,'2026-06-25 03:50:39','2026-06-25 03:50:39'),(14,1,'SALE',-2,13,11,'INV-20260625-00006',NULL,'2026-06-25 03:57:55','2026-06-25 03:57:55'),(15,2,'SALE',-1,7,6,'INV-20260625-00007',NULL,'2026-06-25 04:03:02','2026-06-25 04:03:02'),(16,2,'SALE',-1,6,5,'INV-20260625-00008',NULL,'2026-06-25 04:04:29','2026-06-25 04:04:29'),(17,1,'SALE',-1,11,10,'INV-20260625-00009',NULL,'2026-06-25 04:05:56','2026-06-25 04:05:56'),(18,4,'SALE',-1,110,109,'INV-20260625-00010',NULL,'2026-06-25 06:12:56','2026-06-25 06:12:56'),(19,7,'SALE',-1,10,9,'INV-20260625-00010',NULL,'2026-06-25 06:12:56','2026-06-25 06:12:56'),(20,8,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-06-25 10:05:32','2026-06-25 10:05:32'),(21,8,'SALE',-1,20,19,'INV-20260625-00011',NULL,'2026-06-25 10:05:57','2026-06-25 10:05:57'),(22,6,'SALE',-1,100,99,'INV-20260626-00001',NULL,'2026-06-25 23:36:53','2026-06-25 23:36:53'),(23,5,'SALE',-1,100,99,'INV-20260626-00002',NULL,'2026-06-25 23:37:23','2026-06-25 23:37:23'),(24,9,'OPENING',50,0,50,'OPENING','Stok awal produk','2026-06-25 23:39:43','2026-06-25 23:39:43'),(25,10,'OPENING',130,0,130,'OPENING','Stok awal produk','2026-06-25 23:40:40','2026-06-25 23:40:40'),(26,11,'OPENING',30,0,30,'OPENING','Stok awal produk','2026-06-25 23:41:16','2026-06-25 23:41:16'),(27,9,'SALE',-2,50,48,'INV-20260626-00003',NULL,'2026-06-25 23:49:37','2026-06-25 23:49:37'),(28,6,'SALE',-5,99,94,'INV-20260626-00003',NULL,'2026-06-25 23:49:37','2026-06-25 23:49:37'),(29,10,'SALE',-1,130,129,'INV-20260626-00003',NULL,'2026-06-25 23:49:37','2026-06-25 23:49:37'),(30,3,'STOCK_OPNAME',10,60,70,'OPN-20260626101602','salah input','2026-06-26 03:16:02','2026-06-26 03:16:02'),(31,6,'STOCK_OPNAME',-1,94,93,'OPN-20260626140125','Tidak terlacak','2026-06-26 07:01:25','2026-06-26 07:01:25'),(32,6,'STOCK_OPNAME',2,93,91,'SO-20260630-0001','tak terlacak','2026-06-29 18:43:17','2026-06-29 18:43:17'),(33,5,'STOCK_OPNAME',0,99,99,'SO-20260630-0001',NULL,'2026-06-29 18:43:58','2026-06-29 18:43:58'),(34,3,'STOCK_OPNAME',1,70,69,'SO-20260630-0001','tdk tau','2026-06-29 18:44:25','2026-06-29 18:44:25'),(35,2,'STOCK_OPNAME',0,5,5,'SO-20260630-0001',NULL,'2026-06-30 12:42:40','2026-06-30 12:42:40'),(36,10,'STOCK_OPNAME',4,129,125,'SO-20260630-0001','tdk terlacak','2026-06-30 12:53:55','2026-06-30 12:53:55'),(37,11,'STOCK_OPNAME',2,30,28,'SO-20260630-0001','ndak tau','2026-06-30 13:12:26','2026-06-30 13:12:26'),(38,1,'STOCK_OPNAME',1,10,9,'SO-20260630-0001','tdk tau','2026-06-30 13:58:01','2026-06-30 13:58:01'),(39,4,'STOCK_OPNAME',3,109,106,'SO-20260630-0001',NULL,'2026-06-30 14:46:18','2026-06-30 14:46:18'),(40,8,'STOCK_OPNAME',0,19,19,'SO-20260630-0001',NULL,'2026-06-30 14:46:43','2026-06-30 14:46:43'),(41,9,'STOCK_OPNAME',1,48,47,'SO-20260630-0001',NULL,'2026-06-30 14:48:29','2026-06-30 14:48:29'),(42,7,'STOCK_OPNAME',0,9,9,'SO-20260630-0001',NULL,'2026-06-30 14:48:44','2026-06-30 14:48:44'),(43,12,'OPENING',100,0,100,'OPENING','Stok awal produk','2026-06-30 14:51:52','2026-06-30 14:51:52'),(44,13,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-06-30 14:52:20','2026-06-30 14:52:20'),(45,14,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-06-30 14:52:52','2026-06-30 14:52:52'),(46,15,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-06-30 14:53:36','2026-06-30 14:53:36'),(47,15,'STOCK_OPNAME',0,20,20,'SO-20260630-0001',NULL,'2026-06-30 14:54:00','2026-06-30 14:54:00'),(48,6,'STOCK_OPNAME',1,91,90,'SO-20260630-0002',NULL,'2026-06-30 15:30:06','2026-06-30 15:30:06'),(49,5,'STOCK_OPNAME',2,99,97,'SO-20260630-0002',NULL,'2026-06-30 15:30:23','2026-06-30 15:30:23'),(50,2,'STOCK_OPNAME',0,5,5,'SO-20260630-0002',NULL,'2026-06-30 15:31:04','2026-06-30 15:31:04'),(51,6,'SALE',-1,90,89,'INV-20260701-00001',NULL,'2026-07-01 01:51:06','2026-07-01 01:51:06'),(52,9,'SALE',-1,47,46,'INV-20260701-00001',NULL,'2026-07-01 01:51:06','2026-07-01 01:51:06'),(53,10,'SALE',-5,125,120,'INV-20260701-00002',NULL,'2026-07-01 14:22:09','2026-07-01 14:22:09'),(54,9,'SALE',-1,46,45,'INV-20260701-00002',NULL,'2026-07-01 14:22:09','2026-07-01 14:22:09'),(55,6,'SALE',-1,89,88,'INV-20260701-00002',NULL,'2026-07-01 14:22:09','2026-07-01 14:22:09'),(56,16,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-07-01 14:22:59','2026-07-01 14:22:59'),(57,17,'OPENING',15,0,15,'OPENING','Stok awal produk','2026-07-01 16:49:48','2026-07-01 16:49:48'),(58,18,'OPENING',50,0,50,'OPENING','Stok awal produk','2026-07-02 06:51:36','2026-07-02 06:51:36'),(59,19,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-07-02 06:59:45','2026-07-02 06:59:45'),(60,20,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-07-02 07:00:53','2026-07-02 07:00:53'),(61,21,'OPENING',40,0,40,'OPENING','Stok awal produk','2026-07-02 07:01:40','2026-07-02 07:01:40'),(62,22,'OPENING',10,0,10,'OPENING','Stok awal produk','2026-07-02 07:02:30','2026-07-02 07:02:30'),(63,23,'OPENING',20,0,20,'OPENING','Stok awal produk','2026-07-02 12:23:45','2026-07-02 12:23:45'),(64,23,'SALE',-5,20,15,'INV-20260702-00001',NULL,'2026-07-02 13:24:15','2026-07-02 13:24:15'),(65,2,'SALE',-1,5,4,'INV-20260702-00002',NULL,'2026-07-02 13:25:50','2026-07-02 13:25:50');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_opname_details`
--

DROP TABLE IF EXISTS `stock_opname_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_opname_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_opname_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `stock_system` int(11) NOT NULL,
  `stock_physical` int(11) NOT NULL,
  `difference` int(11) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_opname_details_stock_opname_id_foreign` (`stock_opname_id`),
  KEY `stock_opname_details_product_id_foreign` (`product_id`),
  CONSTRAINT `stock_opname_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `stock_opname_details_stock_opname_id_foreign` FOREIGN KEY (`stock_opname_id`) REFERENCES `stock_opnames` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_opname_details`
--

LOCK TABLES `stock_opname_details` WRITE;
/*!40000 ALTER TABLE `stock_opname_details` DISABLE KEYS */;
INSERT INTO `stock_opname_details` VALUES (1,1,6,93,91,-2,'tak terlacak','2026-06-29 18:43:17','2026-06-29 18:43:17'),(2,1,5,99,99,0,NULL,'2026-06-29 18:43:58','2026-06-29 18:43:58'),(3,1,3,70,69,-1,'tdk tau','2026-06-29 18:44:25','2026-06-29 18:44:25'),(4,1,2,5,5,0,NULL,'2026-06-30 12:42:40','2026-06-30 12:42:40'),(5,1,10,129,125,-4,'tdk terlacak','2026-06-30 12:53:55','2026-06-30 12:53:55'),(6,1,11,30,28,-2,'ndak tau','2026-06-30 13:12:26','2026-06-30 13:12:26'),(7,1,1,10,9,-1,'tdk tau','2026-06-30 13:58:01','2026-06-30 13:58:01'),(8,1,4,109,106,-3,NULL,'2026-06-30 14:46:18','2026-06-30 14:46:18'),(9,1,8,19,19,0,NULL,'2026-06-30 14:46:43','2026-06-30 14:46:43'),(10,1,9,48,47,-1,NULL,'2026-06-30 14:48:29','2026-06-30 14:48:29'),(11,1,7,9,9,0,NULL,'2026-06-30 14:48:44','2026-06-30 14:48:44'),(12,1,15,20,20,0,NULL,'2026-06-30 14:54:00','2026-06-30 14:54:00'),(13,2,6,91,90,-1,NULL,'2026-06-30 15:30:06','2026-06-30 15:30:06'),(14,2,5,99,97,-2,NULL,'2026-06-30 15:30:23','2026-06-30 15:30:23'),(15,2,2,5,5,0,NULL,'2026-06-30 15:31:04','2026-06-30 15:31:04');
/*!40000 ALTER TABLE `stock_opname_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_opnames`
--

DROP TABLE IF EXISTS `stock_opnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_opnames` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `opname_no` varchar(255) NOT NULL,
  `opname_date` datetime NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'OPEN',
  `finished_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_opnames_opname_no_unique` (`opname_no`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_opnames`
--

LOCK TABLES `stock_opnames` WRITE;
/*!40000 ALTER TABLE `stock_opnames` DISABLE KEYS */;
INSERT INTO `stock_opnames` VALUES (1,'SO-20260630-0001','2026-06-30 01:42:42','Admin',NULL,'POSTED','2026-06-30 15:20:32','2026-06-29 18:42:42','2026-06-30 15:20:32'),(2,'SO-20260630-0002','2026-06-30 22:29:54','Admin',NULL,'POSTED','2026-06-30 15:31:13','2026-06-30 15:29:54','2026-06-30 15:31:13'),(3,'SO-20260630-0003','2026-06-30 22:55:25','Admin',NULL,'POSTED','2026-06-30 16:00:33','2026-06-30 15:55:25','2026-06-30 16:00:33'),(4,'SO-20260702-0001','2026-07-02 21:15:36','Admin',NULL,'OPEN',NULL,'2026-07-02 14:15:36','2026-07-02 14:15:36');
/*!40000 ALTER TABLE `stock_opnames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'wings01','PT Wings','Pak Slamet','08128782734','slamet@wings.com','manukan, sby',1,'2026-07-02 12:38:48','2026-07-02 12:39:54'),(2,'garuda01','PT Kacang Garuda','Pak Hendri','0819283746826','hend@garuda.com','pati jateng',1,'2026-07-02 12:40:32','2026-07-02 12:40:32'),(3,'unil01','PT Unilever','Rudi','08136576235',NULL,NULL,1,'2026-07-02 12:41:10','2026-07-02 12:41:10'),(4,'elekt02','PT Elektro Media','andik','082836822323',NULL,NULL,1,'2026-07-02 12:41:43','2026-07-02 12:41:43');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_details`
--

DROP TABLE IF EXISTS `transaction_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga` decimal(15,0) NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` decimal(15,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_details_product_id_foreign` (`product_id`),
  CONSTRAINT `transaction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_details`
--

LOCK TABLES `transaction_details` WRITE;
/*!40000 ALTER TABLE `transaction_details` DISABLE KEYS */;
INSERT INTO `transaction_details` VALUES (1,1,1,'psu20a','Power Supply CCTV 12V 20A',100000,1,100000,'2026-06-24 07:58:38','2026-06-24 07:58:38'),(2,2,1,'psu20a','Power Supply CCTV 12V 20A',100000,2,200000,'2026-06-24 07:59:18','2026-06-24 07:59:18'),(3,2,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,1,2900000,'2026-06-24 07:59:18','2026-06-24 07:59:18'),(4,3,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,1,2900000,'2026-06-24 08:03:33','2026-06-24 08:03:33'),(5,4,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,1,2900000,'2026-06-24 08:06:25','2026-06-24 08:06:25'),(6,4,1,'psu20a','Power Supply CCTV 12V 20A',100000,2,200000,'2026-06-24 08:06:25','2026-06-24 08:06:25'),(7,5,1,'psu20a','Power Supply CCTV 12V 20A',100000,2,200000,'2026-06-24 19:04:12','2026-06-24 19:04:12'),(8,6,1,'psu20a','Power Supply CCTV 12V 20A',100000,6,600000,'2026-06-25 02:29:48','2026-06-25 02:29:48'),(9,7,1,'psu20a','Power Supply CCTV 12V 20A',100000,1,100000,'2026-06-25 02:40:10','2026-06-25 02:40:10'),(10,7,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,1,2900000,'2026-06-25 02:40:10','2026-06-25 02:40:10'),(11,8,1,'psu20a','Power Supply CCTV 12V 20A',100000,8,800000,'2026-06-25 03:47:37','2026-06-25 03:47:37'),(12,8,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,2,5800000,'2026-06-25 03:47:37','2026-06-25 03:47:37'),(13,9,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,2,5800000,'2026-06-25 03:50:39','2026-06-25 03:50:39'),(14,10,1,'psu20a','Power Supply CCTV 12V 20A',100000,2,200000,'2026-06-25 03:57:55','2026-06-25 03:57:55'),(15,11,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,1,2900000,'2026-06-25 04:03:02','2026-06-25 04:03:02'),(16,12,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,1,2900000,'2026-06-25 04:04:29','2026-06-25 04:04:29'),(17,13,1,'psu20a','Power Supply CCTV 12V 20A',100000,1,100000,'2026-06-25 04:05:56','2026-06-25 04:05:56'),(18,14,4,'mou-lgtech1','Mouse Logitech',78000,1,78000,'2026-06-25 06:12:56','2026-06-25 06:12:56'),(19,14,7,'lap-asus-01','Laptop Asus M14',13500000,1,13500000,'2026-06-25 06:12:56','2026-06-25 06:12:56'),(20,15,8,'ram-ddr4-1','RAM DDR4 12 Gb',800000,1,800000,'2026-06-25 10:05:57','2026-06-25 10:05:57'),(21,16,6,'sbn-giv','Sabun Giv',3500,1,3500,'2026-06-25 23:36:53','2026-06-25 23:36:53'),(22,17,5,'RinsoBbk500','Rinso Detergent 500 gr',17500,1,17500,'2026-06-25 23:37:23','2026-06-25 23:37:23'),(23,18,9,'kopi-kpl-api-01','kopi kapal api 20 gram',26500,2,53000,'2026-06-25 23:49:37','2026-06-25 23:49:37'),(24,18,6,'sbn-giv','Sabun Giv',3500,5,17500,'2026-06-25 23:49:37','2026-06-25 23:49:37'),(25,18,10,'chiki-keju01','Chiki rasa keju besar',13000,1,13000,'2026-06-25 23:49:37','2026-06-25 23:49:37'),(26,19,6,'sbn-giv','Sabun Giv',3500,1,3500,'2026-07-01 01:51:06','2026-07-01 01:51:06'),(27,19,9,'kopi-kpl-api-01','kopi kapal api 20 gram',26500,1,26500,'2026-07-01 01:51:06','2026-07-01 01:51:06'),(28,20,10,'chiki-keju01','Chiki rasa keju besar',13000,5,65000,'2026-07-01 14:22:09','2026-07-01 14:22:09'),(29,20,9,'kopi-kpl-api-01','kopi kapal api 20 gram',26500,1,26500,'2026-07-01 14:22:09','2026-07-01 14:22:09'),(30,20,6,'sbn-giv','Sabun Giv',3500,1,3500,'2026-07-01 14:22:09','2026-07-01 14:22:09'),(31,21,23,'bktulis-SD','Buku Tulis Sinar dunia',9000,5,45000,'2026-07-02 13:24:15','2026-07-02 13:24:15'),(32,22,2,'pkcctvhk45','Paket CCTV Hilook 4 Chanel 500 Gb',2900000,1,2900000,'2026-07-02 13:25:50','2026-07-02 13:25:50');
/*!40000 ALTER TABLE `transaction_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_nota` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `pelanggan` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `subtotal` decimal(15,0) NOT NULL DEFAULT 0,
  `diskon` decimal(15,0) NOT NULL DEFAULT 0,
  `grand_total` decimal(15,0) NOT NULL DEFAULT 0,
  `cash` decimal(15,0) NOT NULL DEFAULT 0,
  `voucher` decimal(15,0) NOT NULL DEFAULT 0,
  `card` decimal(15,0) NOT NULL DEFAULT 0,
  `kembalian` decimal(15,0) NOT NULL DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_no_nota_unique` (`no_nota`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'INV-20260624-00001',1,NULL,NULL,100000,0,100000,110000,0,0,10000,NULL,'2026-06-24 07:58:38','2026-06-24 07:58:38'),(2,'INV-20260624-00002',1,NULL,NULL,3100000,0,3100000,3150000,0,0,50000,NULL,'2026-06-24 07:59:18','2026-06-24 07:59:18'),(3,'INV-20260624-00003',1,NULL,NULL,2900000,0,2900000,2900000,0,0,0,NULL,'2026-06-24 08:03:33','2026-06-24 08:03:33'),(4,'INV-20260624-00004',1,NULL,NULL,3100000,0,3100000,3100000,0,0,0,NULL,'2026-06-24 08:06:25','2026-06-24 08:06:25'),(5,'INV-20260625-00001',1,NULL,NULL,200000,0,200000,220000,0,0,20000,NULL,'2026-06-24 19:04:12','2026-06-24 19:04:12'),(6,'INV-20260625-00002',1,NULL,NULL,600000,0,600000,650000,0,0,50000,NULL,'2026-06-25 02:29:48','2026-06-25 02:29:48'),(7,'INV-20260625-00003',1,NULL,NULL,3000000,0,2985000,3000000,10000,5000,15000,NULL,'2026-06-25 02:40:10','2026-06-25 02:40:10'),(8,'INV-20260625-00004',1,NULL,NULL,6600000,0,6585000,6600000,3000,12000,15000,NULL,'2026-06-25 03:47:37','2026-06-25 03:47:37'),(9,'INV-20260625-00005',1,NULL,NULL,5800000,0,5799000,5800000,1000,0,1000,NULL,'2026-06-25 03:50:39','2026-06-25 03:50:39'),(10,'INV-20260625-00006',1,NULL,NULL,200000,0,200000,200000,0,0,0,NULL,'2026-06-25 03:57:55','2026-06-25 03:57:55'),(11,'INV-20260625-00007',1,NULL,NULL,2900000,0,2900000,3000000,0,0,100000,NULL,'2026-06-25 04:03:02','2026-06-25 04:03:02'),(12,'INV-20260625-00008',1,NULL,NULL,2900000,0,2900000,2900000,0,0,0,NULL,'2026-06-25 04:04:29','2026-06-25 04:04:29'),(13,'INV-20260625-00009',1,NULL,NULL,100000,0,100000,100000,0,0,0,NULL,'2026-06-25 04:05:56','2026-06-25 04:05:56'),(14,'INV-20260625-00010',1,NULL,NULL,13578000,0,13578000,13600000,0,0,22000,NULL,'2026-06-25 06:12:56','2026-06-25 06:12:56'),(15,'INV-20260625-00011',1,NULL,NULL,800000,0,800000,800000,0,0,0,NULL,'2026-06-25 10:05:57','2026-06-25 10:05:57'),(16,'INV-20260626-00001',1,NULL,NULL,3500,0,3500,10000,0,0,6500,NULL,'2026-06-25 23:36:53','2026-06-25 23:36:53'),(17,'INV-20260626-00002',1,NULL,NULL,17500,0,17500,20000,0,0,2500,NULL,'2026-06-25 23:37:23','2026-06-25 23:37:23'),(18,'INV-20260626-00003',1,NULL,NULL,83500,0,79500,100000,1000,3000,20500,NULL,'2026-06-25 23:49:37','2026-06-25 23:49:37'),(19,'INV-20260701-00001',1,NULL,NULL,30000,0,30000,30000,0,0,0,NULL,'2026-07-01 01:51:06','2026-07-01 01:51:06'),(20,'INV-20260701-00002',1,NULL,NULL,95000,0,93500,100000,1000,500,6500,NULL,'2026-07-01 14:22:09','2026-07-01 14:22:09'),(21,'INV-20260702-00001',1,NULL,NULL,45000,0,45000,50000,0,0,5000,NULL,'2026-07-02 13:24:15','2026-07-02 13:24:15'),(22,'INV-20260702-00002',1,NULL,NULL,2900000,0,2900000,3000000,0,0,100000,NULL,'2026-07-02 13:25:50','2026-07-02 13:25:50');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Kasir',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Superuser','super@gmail.com','Admin',0,NULL,'$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,'2026-06-23 10:22:40','2026-07-01 10:11:36'),(2,'Admin','admin@gmail.com','Admin',1,NULL,'$2y$12$xP4ybmyUYuDLkYYr1n0z/Oc/g3QiEqPDpvYyhN53ZVdnCgJxSApba',NULL,'2026-06-30 17:09:35','2026-06-30 17:09:35'),(3,'samidi','samidi@gmail.com','Kasir',1,NULL,'$2y$12$2yhZHa6gLMzklgW/uRJcx.9X41mZ1ZwOm3xQAIi7QK20xuPP.EdbC',NULL,'2026-07-01 06:08:31','2026-07-01 06:08:31'),(4,'spv','spv@gmail.com','Supervisor',1,NULL,'$2y$12$FiewjjyF.nLPJ.3979p6qO0r3lUg1QJFzHwHywQn7QlVIEKUNypm6',NULL,'2026-07-01 06:35:26','2026-07-01 06:35:26'),(5,'Dian Adi Pratama','dian@gmail.com','Supervisor',1,NULL,'$2y$12$NM7jfI5cAVVlE6TnJFIB3.U3N9q7ZtCrTi/E6yrdrSqrZBJ392tb.',NULL,'2026-07-01 08:41:24','2026-07-01 10:15:57');
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

-- Dump completed on 2026-07-03 13:36:30
