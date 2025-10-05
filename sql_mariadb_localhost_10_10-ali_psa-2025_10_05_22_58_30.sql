-- MariaDB dump 10.19  Distrib 10.9.1-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: ali_psa
-- ------------------------------------------------------
-- Server version	10.10.2-MariaDB-1:10.10.2+maria~ubu2204

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
-- Table structure for table `administrators`
--

DROP TABLE IF EXISTS `administrators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrators` (
  `email` varchar(100) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_73A716FE7927C74` (`email`),
  KEY `IDX_73A716F642B8210` (`admin_id`),
  CONSTRAINT `FK_73A716F642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrators`
--

LOCK TABLES `administrators` WRITE;
/*!40000 ALTER TABLE `administrators` DISABLE KEYS */;
INSERT INTO `administrators` VALUES
('info@example.lt','[\"ROLE_ADMIN\"]','$2y$13$am582HAnKA6xFn3DX6igrOlEhC5Yg2Yp2ZC7Ir/KHqyFAwEoZiyIu',1,'2025-03-02 17:02:01','2025-03-10 21:30:37',NULL);
/*!40000 ALTER TABLE `administrators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configs`
--

DROP TABLE IF EXISTS `configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configs` (
  `key` varchar(255) NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`value`)),
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42697833642B8210` (`admin_id`),
  CONSTRAINT `FK_42697833642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configs`
--

LOCK TABLES `configs` WRITE;
/*!40000 ALTER TABLE `configs` DISABLE KEYS */;
INSERT INTO `configs` VALUES
('vat',NULL,2,'2025-03-30 21:26:44','2025-03-30 21:26:44',1),
('questions_can_ask',NULL,3,'2025-03-30 21:57:02','2025-03-30 21:57:02',1);
/*!40000 ALTER TABLE `configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `title` varchar(255) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ioc` varchar(3) DEFAULT NULL,
  `iso` varchar(2) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5D66EBAD642B8210` (`admin_id`),
  CONSTRAINT `FK_5D66EBAD642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES
('Afganistanas',1,'2025-03-05 21:46:01','2025-03-05 21:46:42','AFG','AF',1),
('Airija',2,'2025-03-05 21:46:50','2025-03-05 21:46:50','IRL','IE',1),
('Albanija',3,'2025-03-05 21:46:55','2025-03-05 21:46:55','ALB','AL',1),
('Alžyras',4,'2025-03-05 21:47:02','2025-03-05 21:47:02','ALG','DZ',1),
('Andora',5,'2025-03-05 21:47:06','2025-03-05 21:47:06','AND','AD',1),
('Angola',6,'2025-03-05 21:47:09','2025-03-05 21:47:09','ANG','AO',1),
('Antigva ir Barbuda',7,'2025-03-05 21:47:13','2025-03-05 21:47:13','ANT','AG',1),
('Argentina',8,'2025-03-05 21:47:16','2025-03-05 21:47:16','ARG','AR',1),
('Armėnija',9,'2025-03-05 21:47:19','2025-03-05 21:47:19','ARM','AM',1),
('Australija',10,'2025-03-05 21:47:22','2025-03-05 21:47:22','AUS','AU',1),
('Austrija',11,'2025-03-05 21:47:30','2025-03-05 21:47:30','AUT','AT',1),
('Azerbaidžanas',12,'2025-03-05 21:47:33','2025-03-05 21:47:33','AZE','AZ',1),
('Bahamos',13,'2025-03-05 21:47:39','2025-03-05 21:47:39','BAH','BS',1),
('Bahreinas',14,'2025-03-05 21:47:42','2025-03-05 21:47:42','BRN','BH',1),
('Baltarusija',15,'2025-03-05 21:47:45','2025-03-05 21:47:45','BLR','BY',1),
('Bangladešas',16,'2025-03-05 21:47:48','2025-03-05 21:47:48','BAN','BD',1),
('Barbadosas',17,'2025-03-05 21:47:55','2025-03-05 21:47:55','BAR','BB',1),
('Belgija',18,'2025-03-05 21:47:58','2025-03-05 21:47:58','BEL','BE',1),
('Belizas',19,'2025-03-05 21:48:02','2025-03-05 21:48:02','BIZ','BZ',1),
('Beninas',20,'2025-03-05 21:48:06','2025-03-05 21:48:06','BEN','BJ',1),
('Bisau Gvinėja',21,'2025-03-05 21:48:09','2025-03-05 21:48:09','GBS','GW',1),
('Bolivija',22,'2025-03-05 21:48:12','2025-03-05 21:48:12','BOL','BO',1),
('Bosnija ir Hercegovina',23,'2025-03-05 21:48:15','2025-03-05 21:48:15','BIH','BA',1),
('Botsvana',24,'2025-03-05 21:48:18','2025-03-05 21:48:18','BOT','BW',1),
('Brazilija',25,'2025-03-05 21:48:21','2025-03-05 21:48:21','BRA','BR',1),
('Brunėjus',26,'2025-03-05 21:48:28','2025-03-05 21:48:28','BRU','BN',1),
('Bulgarija',27,'2025-03-05 21:48:31','2025-03-05 21:48:31','BUL','BG',1),
('Burkina Fasas',28,'2025-03-05 21:48:33','2025-03-05 21:48:33','BUR','BF',1),
('Burundis',29,'2025-03-05 21:48:36','2025-03-05 21:48:36','BDI','BI',1),
('Butanas',30,'2025-03-05 21:48:39','2025-03-05 21:48:39','BHU','BT',1),
('Centrinės Afrikos Respublika',31,'2025-03-05 21:48:43','2025-03-05 21:48:43','CAF','CF',1),
('Čadas',32,'2025-03-05 21:48:46','2025-03-05 21:48:46','CHA','TD',1),
('Čekija',33,'2025-03-05 21:48:49','2025-03-05 21:48:49','CZE','CZ',1),
('Čilė',34,'2025-03-05 21:48:51','2025-03-05 21:48:51','CHI','CL',1),
('Danija',35,'2025-03-05 21:48:54','2025-03-05 21:48:54','DEN','DK',1),
('Dominika',36,'2025-03-05 21:48:58','2025-03-05 21:48:58','DMA','DM',1),
('Dominikos Respublika',37,'2025-03-05 21:49:02','2025-03-05 21:49:02','DOM','DO',1),
('Dramblio Kaulo Krantas',38,'2025-03-05 21:49:05','2025-03-05 21:49:05','CIV','CI',1),
('Džersis',39,'2025-03-05 21:49:08','2025-03-05 21:49:08',NULL,'JE',1),
('Džibutis',40,'2025-03-05 21:49:11','2025-03-05 21:49:11','DJI','DJ',1),
('Egiptas',41,'2025-03-05 21:49:15','2025-03-05 21:49:15','EGY','EG',1),
('Ekvadoras',42,'2025-03-05 21:49:37','2025-03-05 21:49:37','ECU','EC',1),
('Eritrėja',43,'2025-03-05 21:49:38','2025-03-05 21:49:38','ERI','ER',1),
('Estija',44,'2025-03-05 21:49:42','2025-03-05 21:49:42','EST','EE',1),
('Etiopija',45,'2025-03-05 21:49:44','2025-03-05 21:49:44','ETH','ET',1),
('Fidžis',46,'2025-03-05 21:49:49','2025-03-05 21:49:49','FIJ','FJ',1),
('Filipinai',47,'2025-03-05 21:49:51','2025-03-05 21:49:51','PHI','PH',1),
('Gabonas',48,'2025-03-05 21:49:54','2025-03-05 21:49:54','GAB','GA',1),
('Gajana',49,'2025-03-05 21:49:57','2025-03-05 21:49:57','GUY','GY',1),
('Gambija',50,'2025-03-05 21:50:00','2025-03-05 21:50:00','GAM','GM',1),
('Gana',51,'2025-03-05 21:50:05','2025-03-05 21:50:05','GHA','GH',1),
('Graikija',52,'2025-03-05 21:50:08','2025-03-05 21:50:08','GRE','GR',1),
('Grenada',53,'2025-03-05 21:50:11','2025-03-05 21:50:11','GRN','GD',1),
('Gvatemala',54,'2025-03-05 21:50:15','2025-03-05 21:50:15','GUA','GT',1),
('Gvinėja',55,'2025-03-05 21:51:22','2025-03-05 21:51:22','GUI','GN',1),
('Haitis',56,'2025-03-05 21:51:25','2025-03-05 21:51:25','HAI','HT',1),
('Hondūras',57,'2025-03-05 21:51:28','2025-03-05 21:51:28','HON','HN',1),
('Indija',58,'2025-03-05 21:51:31','2025-03-05 21:51:31','IND','IN',1),
('Indonezija',59,'2025-03-05 21:52:07','2025-03-05 21:52:07','INA','ID',1),
('Irakas',60,'2025-03-05 21:55:45','2025-03-05 21:55:45','IRQ','IQ',1),
('Iranas',61,'2025-03-05 21:55:54','2025-03-05 21:55:54','IRN','IR',1),
('Islandija',62,'2025-03-05 21:56:03','2025-03-05 21:56:03','ISL','IS',1),
('Ispanija',63,'2025-03-05 22:04:45','2025-03-05 22:04:45','ESP','ES',1),
('Italija',64,'2025-03-05 22:04:52','2025-03-05 22:04:52','ITA','IT',1),
('Izraelis',65,'2025-03-05 22:05:00','2025-03-05 22:05:00','ISR','IL',1),
('Jamaika',66,'2025-03-05 22:05:08','2025-03-05 22:05:08','JAM','JM',1),
('Japonija',67,'2025-03-05 22:05:18','2025-03-05 22:05:18','JPN','JP',1),
('Jemenas',68,'2025-03-05 22:05:26','2025-03-05 22:05:26','YEM','YE',1),
('Jordanija',69,'2025-03-05 22:05:32','2025-03-05 22:05:32','JOR','JO',1),
('Jungtiniai Arabų Emyratai',70,'2025-03-05 22:05:38','2025-03-05 22:05:38','UAE','AE',1),
('Jungtinė Karalystė',71,'2025-03-05 22:05:46','2025-03-05 22:05:46','GBR','GB',1),
('JAV',72,'2025-03-05 22:05:51','2025-03-05 22:05:51','USA','US',1),
('Juodkalnija',73,'2025-03-05 22:05:57','2025-03-05 22:05:57','MNE','ME',1),
('Kambodža',74,'2025-03-05 22:06:14','2025-03-05 22:06:14','CAM','KH',1),
('Kamerūnas',75,'2025-03-05 22:06:21','2025-03-05 22:06:21','CMR','CM',1),
('Kanada',76,'2025-03-05 22:06:27','2025-03-05 22:06:27','CAN','CA',1),
('Kataras',77,'2025-03-05 22:06:33','2025-03-05 22:06:33','QAT','QA',1),
('Kazachstanas',78,'2025-03-05 22:06:41','2025-03-05 22:06:41','KAZ','KZ',1),
('Kenija',79,'2025-03-05 22:06:49','2025-03-05 22:06:49','KEN','KE',1),
('Kinija',80,'2025-03-05 22:06:57','2025-03-05 22:06:57','CHN','CN',1),
('Kipras',81,'2025-03-05 22:07:05','2025-03-05 22:07:05','CYP','CY',1),
('Kirgizija',82,'2025-03-05 22:07:13','2025-03-05 22:07:13','KGZ','KG',1),
('Kiribatis',83,'2025-03-05 22:07:19','2025-03-05 22:07:19','KIR','KI',1),
('Kolumbija',84,'2025-03-05 22:07:28','2025-03-05 22:07:28','COL','CO',1),
('Komorai',85,'2025-03-05 22:07:34','2025-03-05 22:07:34','COM','KM',1),
('Kongo Demokratinė Respublika',86,'2025-03-05 22:07:41','2025-03-05 22:07:41','COD','CD',1),
('Kongas',87,'2025-03-05 22:07:48','2025-03-05 22:07:48','COG','CG',1),
('Kosovas',88,'2025-03-05 22:07:53','2025-03-05 22:07:53',NULL,'KS',1),
('Kosta Rika',89,'2025-03-05 22:07:58','2025-03-05 22:07:58','CRC','CR',1),
('Kroatija',90,'2025-03-05 22:08:06','2025-03-05 22:08:06','CRO','HR',1),
('Kuba',91,'2025-03-05 22:08:14','2025-03-05 22:08:14','CUB','CU',1),
('Kuveitas',92,'2025-03-05 22:08:24','2025-03-05 22:08:24','KUW','KW',1),
('Laosas',93,'2025-03-05 22:08:41','2025-03-05 22:08:41','LAO','LA',1),
('Latvija',94,'2025-03-05 22:09:02','2025-03-05 22:09:02','LAT','LV',1),
('Lenkija',95,'2025-03-05 22:09:10','2025-03-05 22:09:10','POL','PL',1),
('Lesotas',96,'2025-03-05 22:09:18','2025-03-05 22:09:18','LES','LS',1),
('Libanas',97,'2025-03-05 22:09:25','2025-03-05 22:09:25','LIB','LB',1),
('Liberija',98,'2025-03-05 22:09:34','2025-03-05 22:09:34','LBR','LR',1),
('Libija',99,'2025-03-05 22:09:42','2025-03-05 22:09:42','LBA','LY',1),
('Lichtenšteinas',100,'2025-03-05 22:09:49','2025-03-05 22:09:49','LIE','LI',1),
('Lietuva',101,'2025-03-05 22:09:56','2025-03-05 22:09:56','LTU','LT',1),
('Liuksemburgas',102,'2025-03-05 22:10:03','2025-03-05 22:10:03','LUX','LU',1),
('Madagaskaras',103,'2025-03-05 22:10:14','2025-03-05 22:10:14','MAD','MG',1),
('Malaizija',104,'2025-03-05 22:10:24','2025-03-05 22:10:24','MAS','MY',1),
('Malavis',105,'2025-03-05 22:10:31','2025-03-05 22:10:31','MAW','MW',1),
('Maldyvai',106,'2025-03-05 22:10:37','2025-03-05 22:10:37','MDV','MV',1),
('Malis',107,'2025-03-05 22:12:28','2025-03-05 22:12:28','MLI','ML',1),
('Malta',108,'2025-03-05 22:12:33','2025-03-05 22:12:33','MLT','MT',1),
('Marokas',109,'2025-03-05 22:12:39','2025-03-05 22:12:39','MAR','MA',1),
('Maršalo Salos',110,'2025-03-05 22:12:48','2025-03-05 22:12:48','MHL','MH',1),
('Mauricijus',111,'2025-03-05 22:12:56','2025-03-05 22:12:56','MRI','MU',1),
('Mauritanija',112,'2025-03-05 22:13:04','2025-03-05 22:13:04','MTN','MR',1),
('Meksika',113,'2025-03-05 22:13:10','2025-03-05 22:13:10','MEX','MX',1),
('Mianmaras',114,'2025-03-05 22:13:18','2025-03-05 22:13:18','MYA','MM',1),
('Mikronezijos Federacinės Valstijos',115,'2025-03-05 22:13:26','2025-03-05 22:13:26','FSM','FM',1),
('Moldavija',116,'2025-03-05 22:13:33','2025-03-05 22:13:33','MDA','MD',1),
('Monakas',117,'2025-03-05 22:13:41','2025-03-05 22:13:41','MON','MC',1),
('Mongolija',118,'2025-03-05 22:13:51','2025-03-05 22:13:51','MGL','MN',1),
('Mozambikas',119,'2025-03-05 22:13:59','2025-03-05 22:13:59','MOZ','MZ',1),
('Namibija',120,'2025-03-05 22:14:08','2025-03-05 22:14:08','NAM','NA',1),
('Naujoji Zelandija',121,'2025-03-05 22:14:18','2025-03-05 22:14:18','NZL','NZ',1),
('Nauru',122,'2025-03-05 22:14:24','2025-03-05 22:14:24','NRU','NR',1),
('Nepalas',123,'2025-03-05 22:14:31','2025-03-05 22:14:31','NEP','NP',1),
('Nyderlandai',124,'2025-03-05 22:14:40','2025-03-05 22:14:40','NED','NL',1),
('Nigerija',125,'2025-03-05 22:14:48','2025-03-05 22:14:48','NGR','NG',1),
('Nigeris',126,'2025-03-05 22:14:54','2025-03-05 22:14:54','NIG','NE',1),
('Nikaragva',127,'2025-03-05 22:15:02','2025-03-05 22:15:02','NCA','NI',1),
('Norvegija',128,'2025-03-05 22:15:08','2025-03-05 22:15:08','NOR','NO',1),
('Omanas',129,'2025-03-05 22:15:15','2025-03-05 22:15:15','OMA','OM',1),
('Pakistanas',130,'2025-03-05 22:15:24','2025-03-05 22:15:24','PAK','PK',1),
('Palau',131,'2025-03-05 22:15:33','2025-03-05 22:15:33','PLW','PW',1),
('Panama',132,'2025-03-05 22:15:39','2025-03-05 22:15:39','PAN','PA',1),
('Papua Naujoji Gvinėja',133,'2025-03-05 22:15:48','2025-03-05 22:15:48','PNG','PG',1),
('Paragvajus',134,'2025-03-05 22:15:59','2025-03-05 22:15:59','PAR','PY',1),
('Peru',135,'2025-03-05 22:16:05','2025-03-05 22:16:05','PER','PE',1),
('Pietų Afrikos Respublika',136,'2025-03-05 22:16:14','2025-03-05 22:16:14','RSA','ZA',1),
('Pietų Korėja',137,'2025-03-05 22:16:19','2025-03-05 22:16:19','KOR','KR',1),
('Pietų Sudanas',138,'2025-03-05 22:16:24','2025-03-05 22:16:24',NULL,NULL,1),
('Portugalija',139,'2025-03-05 22:16:30','2025-03-05 22:16:30','POR','PT',1),
('Prancūzija',140,'2025-03-05 22:16:35','2025-03-05 22:16:35','FRA','FR',1),
('Pusiaujo Gvinėja',141,'2025-03-05 22:16:42','2025-03-05 22:16:42','GEQ','GQ',1),
('Ruanda',142,'2025-03-05 22:16:52','2025-03-05 22:16:52','RWA','RW',1),
('Rumunija',143,'2025-03-05 22:16:59','2025-03-05 22:16:59','ROU','RO',1),
('Rusija',144,'2025-03-05 22:17:07','2025-03-05 22:17:07','RUS','RU',1),
('Rytų Timoras',145,'2025-03-05 22:17:15','2025-03-05 22:17:15','TLS','TP',1),
('Saliamono salos',146,'2025-03-05 22:17:23','2025-03-05 22:17:23','SOL','SB',1),
('Salvadoras',147,'2025-03-05 22:17:29','2025-03-05 22:17:29','ESA','SV',1),
('Samoa',148,'2025-03-05 22:17:34','2025-03-05 22:17:34','SAM','WS',1),
('San Marinas',149,'2025-03-05 22:17:40','2025-03-05 22:17:40','SMR','SM',1),
('San Tomė ir Prinsipė',150,'2025-03-05 22:17:47','2025-03-05 22:17:47','STP','ST',1),
('Saudo Arabija',151,'2025-03-05 22:17:53','2025-03-05 22:17:53','KSA','SA',1),
('Seišeliai',152,'2025-03-05 22:17:59','2025-03-05 22:17:59','SEY','SC',1),
('Senegalas',153,'2025-03-05 22:18:04','2025-03-05 22:18:04','SEN','SN',1),
('Sent Kitsas ir Nevis',154,'2025-03-05 22:18:10','2025-03-05 22:18:10','SKN','KN',1),
('Sent Lusija',155,'2025-03-05 22:18:17','2025-03-05 22:18:17','LCA','LC',1),
('Sent Vinsentas ir Grenadinai',156,'2025-03-05 22:18:24','2025-03-05 22:18:24','VIN','VC',1),
('Serbija',157,'2025-03-05 22:18:31','2025-03-05 22:18:31','SRB','RS',1),
('Siera Leonė',158,'2025-03-05 22:18:38','2025-03-05 22:18:38','SLE','SL',1),
('Singapūras',159,'2025-03-05 22:18:45','2025-03-05 22:18:45','SIN','SG',1),
('Sirija',160,'2025-03-05 22:18:51','2025-03-05 22:18:51','SYR','SY',1),
('Slovakija',161,'2025-03-05 22:18:57','2025-03-05 22:18:57','SVK','SK',1),
('Slovėnija',162,'2025-03-05 22:19:03','2025-03-05 22:19:03','SLO','SI',1),
('Somalis',163,'2025-03-05 22:19:09','2025-03-05 22:19:09','SOM','SO',1),
('Sudanas',164,'2025-03-05 22:19:15','2025-03-05 22:19:15','SUD','SD',1),
('Suomija',165,'2025-03-05 22:19:21','2025-03-05 22:19:21','FIN','FI',1),
('Surinamas',166,'2025-03-05 22:19:28','2025-03-05 22:19:28','SUR','SR',1),
('Svazilandas',167,'2025-03-05 22:19:35','2025-03-05 22:19:35','SWZ','SZ',1),
('Šiaurės Makedonija',168,'2025-03-05 22:19:41','2025-03-05 22:19:41','MKD','MK',1),
('Šiaurės Korėja',169,'2025-03-05 22:19:50','2025-03-05 22:19:50','PRK','KP',1),
('Šri Lanka',170,'2025-03-05 22:19:59','2025-03-05 22:19:59','SRI','LK',1),
('Švedija',171,'2025-03-05 22:20:06','2025-03-05 22:20:06','SWE','SE',1),
('Šveicarija',172,'2025-03-05 22:20:13','2025-03-05 22:20:13','SUI','CH',1),
('Tadžikija',173,'2025-03-05 22:20:22','2025-03-05 22:20:22','TJK','TJ',1),
('Tailandas',174,'2025-03-05 22:20:28','2025-03-05 22:20:28','THA','TH',1),
('Taivanas',175,'2025-03-05 22:20:35','2025-03-05 22:20:35','TPE','TW',1),
('Tanzanija',176,'2025-03-05 22:20:40','2025-03-05 22:20:40','TAN','TZ',1),
('Tonga',177,'2025-03-05 22:20:49','2025-03-05 22:20:49','TGA','TO',1),
('Trinidadas ir Tobagas',178,'2025-03-05 22:20:56','2025-03-05 22:20:56','TRI','TT',1),
('Tunisas',179,'2025-03-05 22:21:08','2025-03-05 22:21:08','TUN','TN',1),
('Turkija',180,'2025-03-05 22:21:13','2025-03-05 22:21:13','TUR','TR',1),
('Turkmėnija',181,'2025-03-05 22:21:20','2025-03-05 22:21:20','TKM','TM',1),
('Tuvalu',182,'2025-03-05 22:21:26','2025-03-05 22:21:26','TUV','TV',1),
('Uganda',183,'2025-03-05 22:21:32','2025-03-05 22:21:32','UGA','UG',1),
('Ukraina',184,'2025-03-05 22:21:40','2025-03-05 22:21:40','UKR','UA',1),
('Urugvajus',185,'2025-03-05 22:21:47','2025-03-05 22:21:47','URU','UY',1),
('Vanuatu',186,'2025-03-05 22:21:54','2025-03-05 22:21:54','VAN','VU',1),
('Vatikanas',187,'2025-03-05 22:22:00','2025-03-05 22:22:00','VAT','VA',1),
('Venesuela',188,'2025-03-05 22:22:06','2025-03-05 22:22:06','VEN','VE',1),
('Vengrija',189,'2025-03-05 22:22:12','2025-03-05 22:22:12','HUN','HU',1),
('Vietnamas',190,'2025-03-05 22:22:18','2025-03-05 22:22:18','JIE','VN',1),
('Vokietija',191,'2025-03-05 22:22:25','2025-03-05 22:22:25','GER','DE',1),
('Zambija',192,'2025-03-05 22:22:33','2025-03-05 22:22:33','ZAM','ZM',1),
('Zimbabvė',193,'2025-03-05 22:22:39','2025-03-05 22:22:39','ZIM','ZW',1),
('Žaliasis Kyšulys',194,'2025-03-05 22:22:47','2025-03-05 22:22:47','CPV','CV',1);
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `due_date` date DEFAULT NULL,
  `is_paid` datetime DEFAULT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `users_objects_services_bundles_id` bigint(20) DEFAULT NULL,
  `series` varchar(255) NOT NULL,
  `no` int(11) NOT NULL,
  `period_start` date NOT NULL DEFAULT curdate(),
  `period_end` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A2F2F9527605F7A` (`users_objects_services_bundles_id`),
  CONSTRAINT `FK_6A2F2F9527605F7A` FOREIGN KEY (`users_objects_services_bundles_id`) REFERENCES `users_objects_services_bundles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=566 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES
('2024-02-16',NULL,534,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',1,'2024-01-01','2024-01-31'),
('2024-03-16',NULL,535,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',2,'2024-02-01','2024-02-29'),
('2024-04-16',NULL,536,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',3,'2024-03-01','2024-03-31'),
('2024-05-16',NULL,537,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',4,'2024-04-01','2024-04-30'),
('2024-06-16',NULL,538,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',5,'2024-05-01','2024-05-31'),
('2024-07-16',NULL,539,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',6,'2024-06-01','2024-06-30'),
('2024-08-16',NULL,540,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',7,'2024-07-01','2024-07-31'),
('2024-09-16',NULL,541,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',8,'2024-08-01','2024-08-31'),
('2024-10-16',NULL,542,'2025-04-13 21:29:15','2025-04-13 21:29:15',1,'SAS',9,'2024-09-01','2024-09-30'),
('2024-11-16',NULL,543,'2025-04-13 21:29:16','2025-04-13 21:29:16',1,'SAS',10,'2024-10-01','2024-10-31'),
('2024-12-16',NULL,544,'2025-04-13 21:29:16','2025-04-13 21:29:16',1,'SAS',11,'2024-11-01','2024-11-30'),
('2025-01-16',NULL,545,'2025-04-13 21:29:16','2025-04-13 21:29:16',1,'SAS',12,'2024-12-01','2024-12-31'),
('2025-02-16',NULL,546,'2025-04-13 21:29:16','2025-04-13 21:29:16',1,'SAS',13,'2025-01-01','2025-01-31'),
('2025-03-16',NULL,547,'2025-04-13 21:29:16','2025-04-13 21:29:16',1,'SAS',14,'2025-02-01','2025-02-28'),
('2025-04-16','2025-05-14 22:00:18',548,'2025-04-13 21:29:16','2025-05-14 22:00:18',1,'SAS',15,'2025-03-01','2025-03-31'),
('2024-02-16',NULL,549,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',16,'2024-01-01','2024-01-31'),
('2024-03-16',NULL,550,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',17,'2024-02-01','2024-02-29'),
('2024-04-16',NULL,551,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',18,'2024-03-01','2024-03-31'),
('2024-05-16',NULL,552,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',19,'2024-04-01','2024-04-30'),
('2024-06-16',NULL,553,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',20,'2024-05-01','2024-05-31'),
('2024-07-16',NULL,554,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',21,'2024-06-01','2024-06-30'),
('2024-08-16',NULL,555,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',22,'2024-07-01','2024-07-31'),
('2024-09-16',NULL,556,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',23,'2024-08-01','2024-08-31'),
('2024-10-16',NULL,557,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',24,'2024-09-01','2024-09-30'),
('2024-11-16',NULL,558,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',25,'2024-10-01','2024-10-31'),
('2024-12-16',NULL,559,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',26,'2024-11-01','2024-11-30'),
('2025-01-16',NULL,560,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',27,'2024-12-01','2024-12-31'),
('2025-02-16',NULL,561,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',28,'2025-01-01','2025-01-31'),
('2025-03-16',NULL,562,'2025-04-13 21:29:16','2025-04-13 21:29:16',2,'SAS',29,'2025-02-01','2025-02-28'),
('2025-04-16','2025-04-13 21:36:14',563,'2025-04-13 21:29:16','2025-04-13 21:36:14',2,'SAS',30,'2025-03-01','2025-03-31'),
('2025-05-16',NULL,564,'2025-05-14 22:01:39','2025-05-14 22:01:39',1,'SAS',31,'2025-04-01','2025-04-30'),
('2025-05-16',NULL,565,'2025-05-14 22:01:39','2025-05-14 22:01:39',2,'SAS',32,'2025-04-01','2025-04-30');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `invoices_id` bigint(20) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `raw_request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_request_data`)),
  `redirect_url` varchar(255) NOT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`request_data`)),
  `payment_method` varchar(100) NOT NULL,
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_data`)),
  `return_data` longtext DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_65D29B322454BA75` (`invoices_id`),
  CONSTRAINT `FK_65D29B322454BA75` FOREIGN KEY (`invoices_id`) REFERENCES `invoices` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES
(100,'2025-04-13 21:36:14','2025-04-13 21:36:14',563,'success',1732,'{\"orderid\":100,\"amount\":1732,\"currency\":\"EUR\",\"paytext\":\"Apmok\\u0117jimas u\\u017e s\\u0105skait\\u0105 SAS-00030\",\"accepturl\":\"http:\\/\\/itis.localhost\\/payments\\/accept?control=921b064ad22a4de83c11f58fbbb69eff&id=100&h=c46c07fecf17b0f727856ea14bf5990b6ab388b8\",\"cancelurl\":\"http:\\/\\/itis.localhost\\/payments\\/cancel?control=c436ba71a5174ee9bb6aac4b6665e293&id=100&h=c46c07fecf17b0f727856ea14bf5990b6ab388b8\",\"callbackurl\":\"http:\\/\\/itis.localhost\\/payments\\/callback?control=47e0909dd957e504e35fd1c803c3222b&id=100&h=c46c07fecf17b0f727856ea14bf5990b6ab388b8\"}','http://itis.localhost/payments/test','{\"data\":\"b3JkZXJpZD0xMDAmYW1vdW50PTE3MzImY3VycmVuY3k9RVVSJnBheXRleHQ9QXBtb2slQzQlOTdqaW1hcyt1JUM1JUJFK3MlQzQlODVza2FpdCVDNCU4NStTQVMtMDAwMzAmYWNjZXB0dXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGYWNjZXB0JTNGY29udHJvbCUzRDkyMWIwNjRhZDIyYTRkZTgzYzExZjU4ZmJiYjY5ZWZmJTI2aWQlM0QxMDAlMjZoJTNEYzQ2YzA3ZmVjZjE3YjBmNzI3ODU2ZWExNGJmNTk5MGI2YWIzODhiOCZjYW5jZWx1cmw9aHR0cCUzQSUyRiUyRml0aXMubG9jYWxob3N0JTJGcGF5bWVudHMlMkZjYW5jZWwlM0Zjb250cm9sJTNEYzQzNmJhNzFhNTE3NGVlOWJiNmFhYzRiNjY2NWUyOTMlMjZpZCUzRDEwMCUyNmglM0RjNDZjMDdmZWNmMTdiMGY3Mjc4NTZlYTE0YmY1OTkwYjZhYjM4OGI4JmNhbGxiYWNrdXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FsbGJhY2slM0Zjb250cm9sJTNENDdlMDkwOWRkOTU3ZTUwNGUzNWZkMWM4MDNjMzIyMmIlMjZpZCUzRDEwMCUyNmglM0RjNDZjMDdmZWNmMTdiMGY3Mjc4NTZlYTE0YmY1OTkwYjZhYjM4OGI4\",\"sign\":\"8ade3b8817e599c9569f25e2576292cc\"}','test','{\"orderid\":\"100\",\"amount\":\"1732\",\"currency\":\"EUR\",\"payment\":\"\",\"sign\":\"8ade3b8817e599c9569f25e2576292cc\"}','OK:100','2025-04-13 21:36:14','c46c07fecf17b0f727856ea14bf5990b6ab388b8'),
(101,'2025-05-14 21:58:33','2025-05-14 21:58:33',548,'pending',3881,'{\"orderid\":101,\"amount\":3881,\"currency\":\"EUR\",\"paytext\":\"Apmok\\u0117jimas u\\u017e s\\u0105skait\\u0105 SAS-00015\",\"accepturl\":\"http:\\/\\/itis.localhost\\/payments\\/accept?control=2bda57a9fe2b5dce7d452c94a6d51d0b&id=101&h=6d8460e69b3e3b162d13f4f14d52296a4b713f88\",\"cancelurl\":\"http:\\/\\/itis.localhost\\/payments\\/cancel?control=ec77e1fabf7e624c05dbc09ce809f2b0&id=101&h=6d8460e69b3e3b162d13f4f14d52296a4b713f88\",\"callbackurl\":\"http:\\/\\/itis.localhost\\/payments\\/callback?control=8eefd8243a44decec847c9b112b390ee&id=101&h=6d8460e69b3e3b162d13f4f14d52296a4b713f88\"}','http://itis.localhost/payments/test','{\"data\":\"b3JkZXJpZD0xMDEmYW1vdW50PTM4ODEmY3VycmVuY3k9RVVSJnBheXRleHQ9QXBtb2slQzQlOTdqaW1hcyt1JUM1JUJFK3MlQzQlODVza2FpdCVDNCU4NStTQVMtMDAwMTUmYWNjZXB0dXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGYWNjZXB0JTNGY29udHJvbCUzRDJiZGE1N2E5ZmUyYjVkY2U3ZDQ1MmM5NGE2ZDUxZDBiJTI2aWQlM0QxMDElMjZoJTNENmQ4NDYwZTY5YjNlM2IxNjJkMTNmNGYxNGQ1MjI5NmE0YjcxM2Y4OCZjYW5jZWx1cmw9aHR0cCUzQSUyRiUyRml0aXMubG9jYWxob3N0JTJGcGF5bWVudHMlMkZjYW5jZWwlM0Zjb250cm9sJTNEZWM3N2UxZmFiZjdlNjI0YzA1ZGJjMDljZTgwOWYyYjAlMjZpZCUzRDEwMSUyNmglM0Q2ZDg0NjBlNjliM2UzYjE2MmQxM2Y0ZjE0ZDUyMjk2YTRiNzEzZjg4JmNhbGxiYWNrdXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FsbGJhY2slM0Zjb250cm9sJTNEOGVlZmQ4MjQzYTQ0ZGVjZWM4NDdjOWIxMTJiMzkwZWUlMjZpZCUzRDEwMSUyNmglM0Q2ZDg0NjBlNjliM2UzYjE2MmQxM2Y0ZjE0ZDUyMjk2YTRiNzEzZjg4\",\"sign\":\"1a09dacb486cf2f7fcba9c31a004060d\"}','test',NULL,NULL,NULL,'6d8460e69b3e3b162d13f4f14d52296a4b713f88'),
(102,'2025-05-14 21:58:41','2025-05-14 21:58:41',548,'pending',3881,'{\"orderid\":102,\"amount\":3881,\"currency\":\"EUR\",\"paytext\":\"Apmok\\u0117jimas u\\u017e s\\u0105skait\\u0105 SAS-00015\",\"accepturl\":\"http:\\/\\/itis.localhost\\/payments\\/accept?control=2bda57a9fe2b5dce7d452c94a6d51d0b&id=102&h=2912421cedea4ebe14b6cc36bcd3ce227f4c38db\",\"cancelurl\":\"http:\\/\\/itis.localhost\\/payments\\/cancel?control=ec77e1fabf7e624c05dbc09ce809f2b0&id=102&h=2912421cedea4ebe14b6cc36bcd3ce227f4c38db\",\"callbackurl\":\"http:\\/\\/itis.localhost\\/payments\\/callback?control=8eefd8243a44decec847c9b112b390ee&id=102&h=2912421cedea4ebe14b6cc36bcd3ce227f4c38db\"}','http://itis.localhost/payments/test','{\"data\":\"b3JkZXJpZD0xMDImYW1vdW50PTM4ODEmY3VycmVuY3k9RVVSJnBheXRleHQ9QXBtb2slQzQlOTdqaW1hcyt1JUM1JUJFK3MlQzQlODVza2FpdCVDNCU4NStTQVMtMDAwMTUmYWNjZXB0dXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGYWNjZXB0JTNGY29udHJvbCUzRDJiZGE1N2E5ZmUyYjVkY2U3ZDQ1MmM5NGE2ZDUxZDBiJTI2aWQlM0QxMDIlMjZoJTNEMjkxMjQyMWNlZGVhNGViZTE0YjZjYzM2YmNkM2NlMjI3ZjRjMzhkYiZjYW5jZWx1cmw9aHR0cCUzQSUyRiUyRml0aXMubG9jYWxob3N0JTJGcGF5bWVudHMlMkZjYW5jZWwlM0Zjb250cm9sJTNEZWM3N2UxZmFiZjdlNjI0YzA1ZGJjMDljZTgwOWYyYjAlMjZpZCUzRDEwMiUyNmglM0QyOTEyNDIxY2VkZWE0ZWJlMTRiNmNjMzZiY2QzY2UyMjdmNGMzOGRiJmNhbGxiYWNrdXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FsbGJhY2slM0Zjb250cm9sJTNEOGVlZmQ4MjQzYTQ0ZGVjZWM4NDdjOWIxMTJiMzkwZWUlMjZpZCUzRDEwMiUyNmglM0QyOTEyNDIxY2VkZWE0ZWJlMTRiNmNjMzZiY2QzY2UyMjdmNGMzOGRi\",\"sign\":\"ea7873b722a65a7270807a6deb3d5736\"}','test',NULL,NULL,NULL,'2912421cedea4ebe14b6cc36bcd3ce227f4c38db'),
(103,'2025-05-14 21:58:45','2025-05-14 21:58:45',548,'pending',3881,'{\"orderid\":103,\"amount\":3881,\"currency\":\"EUR\",\"paytext\":\"Apmok\\u0117jimas u\\u017e s\\u0105skait\\u0105 SAS-00015\",\"accepturl\":\"http:\\/\\/itis.localhost\\/payments\\/accept?control=2bda57a9fe2b5dce7d452c94a6d51d0b&id=103&h=4966b6f1fd9591c89b6723e09bc2c23519955ce7\",\"cancelurl\":\"http:\\/\\/itis.localhost\\/payments\\/cancel?control=ec77e1fabf7e624c05dbc09ce809f2b0&id=103&h=4966b6f1fd9591c89b6723e09bc2c23519955ce7\",\"callbackurl\":\"http:\\/\\/itis.localhost\\/payments\\/callback?control=8eefd8243a44decec847c9b112b390ee&id=103&h=4966b6f1fd9591c89b6723e09bc2c23519955ce7\"}','http://itis.localhost/payments/test','{\"data\":\"b3JkZXJpZD0xMDMmYW1vdW50PTM4ODEmY3VycmVuY3k9RVVSJnBheXRleHQ9QXBtb2slQzQlOTdqaW1hcyt1JUM1JUJFK3MlQzQlODVza2FpdCVDNCU4NStTQVMtMDAwMTUmYWNjZXB0dXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGYWNjZXB0JTNGY29udHJvbCUzRDJiZGE1N2E5ZmUyYjVkY2U3ZDQ1MmM5NGE2ZDUxZDBiJTI2aWQlM0QxMDMlMjZoJTNENDk2NmI2ZjFmZDk1OTFjODliNjcyM2UwOWJjMmMyMzUxOTk1NWNlNyZjYW5jZWx1cmw9aHR0cCUzQSUyRiUyRml0aXMubG9jYWxob3N0JTJGcGF5bWVudHMlMkZjYW5jZWwlM0Zjb250cm9sJTNEZWM3N2UxZmFiZjdlNjI0YzA1ZGJjMDljZTgwOWYyYjAlMjZpZCUzRDEwMyUyNmglM0Q0OTY2YjZmMWZkOTU5MWM4OWI2NzIzZTA5YmMyYzIzNTE5OTU1Y2U3JmNhbGxiYWNrdXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FsbGJhY2slM0Zjb250cm9sJTNEOGVlZmQ4MjQzYTQ0ZGVjZWM4NDdjOWIxMTJiMzkwZWUlMjZpZCUzRDEwMyUyNmglM0Q0OTY2YjZmMWZkOTU5MWM4OWI2NzIzZTA5YmMyYzIzNTE5OTU1Y2U3\",\"sign\":\"689dcf5e6a37047cfc2d148a4abd536a\"}','test',NULL,NULL,NULL,'4966b6f1fd9591c89b6723e09bc2c23519955ce7'),
(104,'2025-05-14 21:59:03','2025-05-14 21:59:03',548,'pending',3881,'{\"orderid\":104,\"amount\":3881,\"currency\":\"EUR\",\"paytext\":\"Apmok\\u0117jimas u\\u017e s\\u0105skait\\u0105 SAS-00015\",\"accepturl\":\"http:\\/\\/itis.localhost\\/payments\\/accept?control=2bda57a9fe2b5dce7d452c94a6d51d0b&id=104&h=5def8a090df5c670e0643b8068bb7305f074793f\",\"cancelurl\":\"http:\\/\\/itis.localhost\\/payments\\/cancel?control=ec77e1fabf7e624c05dbc09ce809f2b0&id=104&h=5def8a090df5c670e0643b8068bb7305f074793f\",\"callbackurl\":\"http:\\/\\/itis.localhost\\/payments\\/callback?control=8eefd8243a44decec847c9b112b390ee&id=104&h=5def8a090df5c670e0643b8068bb7305f074793f\"}','http://itis.localhost/payments/test','{\"data\":\"b3JkZXJpZD0xMDQmYW1vdW50PTM4ODEmY3VycmVuY3k9RVVSJnBheXRleHQ9QXBtb2slQzQlOTdqaW1hcyt1JUM1JUJFK3MlQzQlODVza2FpdCVDNCU4NStTQVMtMDAwMTUmYWNjZXB0dXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGYWNjZXB0JTNGY29udHJvbCUzRDJiZGE1N2E5ZmUyYjVkY2U3ZDQ1MmM5NGE2ZDUxZDBiJTI2aWQlM0QxMDQlMjZoJTNENWRlZjhhMDkwZGY1YzY3MGUwNjQzYjgwNjhiYjczMDVmMDc0NzkzZiZjYW5jZWx1cmw9aHR0cCUzQSUyRiUyRml0aXMubG9jYWxob3N0JTJGcGF5bWVudHMlMkZjYW5jZWwlM0Zjb250cm9sJTNEZWM3N2UxZmFiZjdlNjI0YzA1ZGJjMDljZTgwOWYyYjAlMjZpZCUzRDEwNCUyNmglM0Q1ZGVmOGEwOTBkZjVjNjcwZTA2NDNiODA2OGJiNzMwNWYwNzQ3OTNmJmNhbGxiYWNrdXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FsbGJhY2slM0Zjb250cm9sJTNEOGVlZmQ4MjQzYTQ0ZGVjZWM4NDdjOWIxMTJiMzkwZWUlMjZpZCUzRDEwNCUyNmglM0Q1ZGVmOGEwOTBkZjVjNjcwZTA2NDNiODA2OGJiNzMwNWYwNzQ3OTNm\",\"sign\":\"39bd1e46ddeb54afd9e8feb0343ca349\"}','test',NULL,NULL,NULL,'5def8a090df5c670e0643b8068bb7305f074793f'),
(105,'2025-05-14 22:00:03','2025-05-14 22:00:03',548,'pending',3881,'{\"orderid\":105,\"amount\":3881,\"currency\":\"EUR\",\"paytext\":\"Apmok\\u0117jimas u\\u017e s\\u0105skait\\u0105 SAS-00015\",\"accepturl\":\"http:\\/\\/itis.localhost\\/payments\\/accept?control=2bda57a9fe2b5dce7d452c94a6d51d0b&id=105&h=c7b19c6890fd99379267090a9710f7a6e9e642a7\",\"cancelurl\":\"http:\\/\\/itis.localhost\\/payments\\/cancel?control=ec77e1fabf7e624c05dbc09ce809f2b0&id=105&h=c7b19c6890fd99379267090a9710f7a6e9e642a7\",\"callbackurl\":\"http:\\/\\/itis.localhost\\/payments\\/callback?control=8eefd8243a44decec847c9b112b390ee&id=105&h=c7b19c6890fd99379267090a9710f7a6e9e642a7\"}','http://itis.localhost/payments/test','{\"data\":\"b3JkZXJpZD0xMDUmYW1vdW50PTM4ODEmY3VycmVuY3k9RVVSJnBheXRleHQ9QXBtb2slQzQlOTdqaW1hcyt1JUM1JUJFK3MlQzQlODVza2FpdCVDNCU4NStTQVMtMDAwMTUmYWNjZXB0dXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGYWNjZXB0JTNGY29udHJvbCUzRDJiZGE1N2E5ZmUyYjVkY2U3ZDQ1MmM5NGE2ZDUxZDBiJTI2aWQlM0QxMDUlMjZoJTNEYzdiMTljNjg5MGZkOTkzNzkyNjcwOTBhOTcxMGY3YTZlOWU2NDJhNyZjYW5jZWx1cmw9aHR0cCUzQSUyRiUyRml0aXMubG9jYWxob3N0JTJGcGF5bWVudHMlMkZjYW5jZWwlM0Zjb250cm9sJTNEZWM3N2UxZmFiZjdlNjI0YzA1ZGJjMDljZTgwOWYyYjAlMjZpZCUzRDEwNSUyNmglM0RjN2IxOWM2ODkwZmQ5OTM3OTI2NzA5MGE5NzEwZjdhNmU5ZTY0MmE3JmNhbGxiYWNrdXJsPWh0dHAlM0ElMkYlMkZpdGlzLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FsbGJhY2slM0Zjb250cm9sJTNEOGVlZmQ4MjQzYTQ0ZGVjZWM4NDdjOWIxMTJiMzkwZWUlMjZpZCUzRDEwNSUyNmglM0RjN2IxOWM2ODkwZmQ5OTM3OTI2NzA5MGE5NzEwZjdhNmU5ZTY0MmE3\",\"sign\":\"1703885abb7399a5fad6e83d173755c2\"}','test',NULL,NULL,NULL,'c7b19c6890fd99379267090a9710f7a6e9e642a7'),
(106,'2025-05-14 22:00:16','2025-05-14 22:00:18',548,'success',3881,'{\"orderid\":106,\"amount\":3881,\"currency\":\"EUR\",\"paytext\":\"Apmok\\u0117jimas u\\u017e s\\u0105skait\\u0105 SAS-00015\",\"accepturl\":\"http:\\/\\/vu_psa.localhost\\/payments\\/accept?control=2bda57a9fe2b5dce7d452c94a6d51d0b&id=106&h=ddfa037c38f640b141b843b5b15c875320ea6b83\",\"cancelurl\":\"http:\\/\\/vu_psa.localhost\\/payments\\/cancel?control=ec77e1fabf7e624c05dbc09ce809f2b0&id=106&h=ddfa037c38f640b141b843b5b15c875320ea6b83\",\"callbackurl\":\"http:\\/\\/vu_psa.localhost\\/payments\\/callback?control=8eefd8243a44decec847c9b112b390ee&id=106&h=ddfa037c38f640b141b843b5b15c875320ea6b83\"}','http://vu_psa.localhost/payments/test','{\"data\":\"b3JkZXJpZD0xMDYmYW1vdW50PTM4ODEmY3VycmVuY3k9RVVSJnBheXRleHQ9QXBtb2slQzQlOTdqaW1hcyt1JUM1JUJFK3MlQzQlODVza2FpdCVDNCU4NStTQVMtMDAwMTUmYWNjZXB0dXJsPWh0dHAlM0ElMkYlMkZ2dV9wc2EubG9jYWxob3N0JTJGcGF5bWVudHMlMkZhY2NlcHQlM0Zjb250cm9sJTNEMmJkYTU3YTlmZTJiNWRjZTdkNDUyYzk0YTZkNTFkMGIlMjZpZCUzRDEwNiUyNmglM0RkZGZhMDM3YzM4ZjY0MGIxNDFiODQzYjViMTVjODc1MzIwZWE2YjgzJmNhbmNlbHVybD1odHRwJTNBJTJGJTJGdnVfcHNhLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FuY2VsJTNGY29udHJvbCUzRGVjNzdlMWZhYmY3ZTYyNGMwNWRiYzA5Y2U4MDlmMmIwJTI2aWQlM0QxMDYlMjZoJTNEZGRmYTAzN2MzOGY2NDBiMTQxYjg0M2I1YjE1Yzg3NTMyMGVhNmI4MyZjYWxsYmFja3VybD1odHRwJTNBJTJGJTJGdnVfcHNhLmxvY2FsaG9zdCUyRnBheW1lbnRzJTJGY2FsbGJhY2slM0Zjb250cm9sJTNEOGVlZmQ4MjQzYTQ0ZGVjZWM4NDdjOWIxMTJiMzkwZWUlMjZpZCUzRDEwNiUyNmglM0RkZGZhMDM3YzM4ZjY0MGIxNDFiODQzYjViMTVjODc1MzIwZWE2Yjgz\",\"sign\":\"8f2f719c92d05f0c67458064a8cf5f25\"}','test','{\"orderid\":\"106\",\"amount\":\"3881\",\"currency\":\"EUR\",\"payment\":\"\",\"sign\":\"8f2f719c92d05f0c67458064a8cf5f25\"}','OK:106','2025-05-14 22:00:18','ddfa037c38f640b141b843b5b15c875320ea6b83');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `question` text NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `users_id` bigint(20) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `questions_categories_id` bigint(20) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8ADC54D567B3B43D` (`users_id`),
  KEY `IDX_8ADC54D52852561A` (`questions_categories_id`),
  CONSTRAINT `FK_8ADC54D52852561A` FOREIGN KEY (`questions_categories_id`) REFERENCES `questions_categories` (`id`),
  CONSTRAINT `FK_8ADC54D567B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES
('test',34,'2025-03-25 23:04:22','2025-03-25 23:04:22',NULL,'test@test.lt',1,NULL),
('Kaip užsisakyti namų telefoną?',35,'2025-03-25 23:47:48','2025-03-25 23:47:48',2,'tomas.tomauskas@example.com',1,NULL),
('2test34',41,'2025-09-08 23:54:08','2025-09-09 00:14:08',3,'jonas.jonaitis@example.com',1,NULL),
('2test',42,'2025-09-10 21:53:48','2025-09-10 21:54:15',3,'jonas.jonaitis@example.com',NULL,NULL),
('test',43,'2025-09-10 21:54:09','2025-09-10 21:54:09',3,'jonas.jonaitis@example.com',NULL,NULL),
('te',44,'2025-09-10 21:54:39','2025-09-10 21:54:39',3,'jonas.jonaitis@example.com',1,NULL),
('test',45,'2025-09-11 00:09:13','2025-09-11 00:09:13',3,'jonas.jonaitis@example.com',1,'+370600000023');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions_answers`
--

DROP TABLE IF EXISTS `questions_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions_answers` (
  `answer` text NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` bigint(20) DEFAULT NULL,
  `questions_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2355E6C0642B8210` (`admin_id`),
  KEY `IDX_2355E6C0BCB134CE` (`questions_id`),
  CONSTRAINT `FK_2355E6C0642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`),
  CONSTRAINT `FK_2355E6C0BCB134CE` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions_answers`
--

LOCK TABLES `questions_answers` WRITE;
/*!40000 ALTER TABLE `questions_answers` DISABLE KEYS */;
INSERT INTO `questions_answers` VALUES
('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Bork Nam Pyrrho, Aristo, Erillus iam diu abiecti. Item de contrariis, a quibus ad genera formasque generum venerunt. Duo Reges: constructio interrete. \r\n\r\nTum Torquatus: Prorsus, inquit, assentior; Sic enim censent, oportunitatis esse beate vivere. \r\n\r\nNos commodius agimus. Sed quot homines, tot sententiae; Quid, quod res alia tota est? \r\n\r\nBork Sit enim idem caecus, debilis. At enim hic etiam dolore. Quid iudicant sensus? Bork \r\n\r\nQuid ad utilitatem tantae pecuniae? Sed haec omittamus; At iam decimum annum in spelunca iacet. Et nemo nimium beatus est;',2,'2025-03-31 22:36:15','2025-03-31 22:36:15',1,35);
/*!40000 ALTER TABLE `questions_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions_categories`
--

DROP TABLE IF EXISTS `questions_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions_categories` (
  `title` varchar(255) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A787FFE4642B8210` (`admin_id`),
  CONSTRAINT `FK_A787FFE4642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions_categories`
--

LOCK TABLES `questions_categories` WRITE;
/*!40000 ALTER TABLE `questions_categories` DISABLE KEYS */;
INSERT INTO `questions_categories` VALUES
('Interneto klausimai',1,'2025-09-10 21:17:03','2025-09-10 21:17:03',1),
('Kiti klausimai',2,'2025-09-10 21:49:23','2025-09-10 21:49:23',1);
/*!40000 ALTER TABLE `questions_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `title` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `active_from` date NOT NULL DEFAULT curdate(),
  `active_to` date DEFAULT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `description` longtext DEFAULT NULL,
  `advertise` tinyint(1) NOT NULL DEFAULT 0,
  `admin_id` bigint(20) DEFAULT NULL,
  `services_categories_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7332E169642B8210` (`admin_id`),
  KEY `IDX_7332E169C3149E55` (`services_categories_id`),
  CONSTRAINT `FK_7332E169642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`),
  CONSTRAINT `FK_7332E169C3149E55` FOREIGN KEY (`services_categories_id`) REFERENCES `services_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
('Internetas MINI',4030,'2025-03-02',NULL,1,'2025-03-02 20:35:13','2025-03-29 22:44:28','Turėsite iki 500 MBPS',1,1,1),
('Modemo nuoma',400,'2025-03-02',NULL,2,'2025-03-02 20:58:59','2025-03-29 22:46:12',NULL,0,1,3),
('Internetas MIDI',1257,'2025-03-02',NULL,3,'2025-03-02 21:32:53','2025-03-29 22:45:39','Turėsite iki 1000 MBPS',1,1,1),
('Internetas MAXI',1568,'2025-03-02',NULL,4,'2025-03-02 21:35:30','2025-03-29 22:45:44','Turėsite iki 1500 MBPS',1,1,1),
('Tinklo paslaugų mokestis',428,'2025-03-06',NULL,5,'2025-03-06 20:53:09','2025-03-06 20:53:09',NULL,0,1,NULL),
('Televizija BAZINIS',1245,'2025-03-13',NULL,6,'2025-03-13 17:47:05','2025-03-29 22:46:22',NULL,1,1,2),
('Televizija PLIUS',1965,'2025-03-13',NULL,7,'2025-03-13 17:47:25','2025-03-29 22:46:29',NULL,1,1,2),
('Kanalų grupė PUKAS',250,'2025-03-13',NULL,8,'2025-03-13 17:56:53','2025-03-14 01:13:02','Kanalai:\r\n* Pūkas\r\n* Pūkas Plius',0,1,NULL),
('Televizijos smart kortelė',100,'2025-03-24',NULL,9,'2025-03-24 00:18:17','2025-03-29 22:46:35','Kortelė būtina skaitmeninei televizijai',0,1,3);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services_categories`
--

DROP TABLE IF EXISTS `services_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services_categories` (
  `title` varchar(255) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_881906C5642B8210` (`admin_id`),
  CONSTRAINT `FK_881906C5642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services_categories`
--

LOCK TABLES `services_categories` WRITE;
/*!40000 ALTER TABLE `services_categories` DISABLE KEYS */;
INSERT INTO `services_categories` VALUES
('Interneto planai',1,'2025-03-29 22:28:15','2025-03-29 22:28:15',1),
('Televizijos planai',2,'2025-03-29 22:45:56','2025-03-29 22:45:56',1),
('Įranga',3,'2025-03-29 22:46:01','2025-03-29 22:46:01',1);
/*!40000 ALTER TABLE `services_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services_promotions`
--

DROP TABLE IF EXISTS `services_promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services_promotions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` bigint(20) DEFAULT NULL,
  `services_id` bigint(20) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT 0,
  `months` int(11) NOT NULL,
  `active_from` date NOT NULL DEFAULT curdate(),
  `active_to` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_58F17099642B8210` (`admin_id`),
  KEY `IDX_58F17099AEF5A6C1` (`services_id`),
  CONSTRAINT `FK_58F17099642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`),
  CONSTRAINT `FK_58F17099AEF5A6C1` FOREIGN KEY (`services_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services_promotions`
--

LOCK TABLES `services_promotions` WRITE;
/*!40000 ALTER TABLE `services_promotions` DISABLE KEYS */;
INSERT INTO `services_promotions` VALUES
(1,'2025-03-28 00:48:33','2025-04-02 01:28:44',1,1,0.1,3,'2025-04-01','2026-03-31'),
(2,'2025-03-30 21:20:14','2025-04-02 01:30:40',1,3,0.1,3,'2025-03-30','2026-03-31'),
(3,'2025-04-02 01:28:12','2025-04-02 01:30:47',1,4,0.1,3,'2025-04-02','2026-04-01'),
(4,'2025-04-02 01:30:14','2025-04-02 01:31:17',1,8,0.2,3,'2025-04-02','2026-04-01'),
(5,'2025-04-04 00:33:51','2025-04-04 00:34:37',1,3,0.15,3,'2025-04-04','2026-04-03');
/*!40000 ALTER TABLE `services_promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `structures`
--

DROP TABLE IF EXISTS `structures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structures` (
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` bigint(20) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5BBEC55A642B8210` (`admin_id`),
  CONSTRAINT `FK_5BBEC55A642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `structures`
--

LOCK TABLES `structures` WRITE;
/*!40000 ALTER TABLE `structures` DISABLE KEYS */;
INSERT INTO `structures` VALUES
('Slapukų naudojimo taisyklės','<div>Svetainėje naudojame slapukus svetainėje (toliau &bdquo;Paslaugos&ldquo;). Naudokite paslaugas tik jei Jūs sutinkate su slapukų naudojimu. Jei naudojate Paslaugas, tuomet sutinkate su slapukų naudojimu.&nbsp;<br />\r\n<br />\r\nMūsų slapukų naudojimo politika paai&scaron;kina kokie slapukai ir kokiu tikslu naudojami.&nbsp;</div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<h2>Kas yra slapukai</h2>\r\n\r\n<div>Slapukai yra maži tekstiniai parametrai kuriuos jūsų interneto nar&scaron;yklė gali i&scaron;saugoti jūsų kompiuteryje. Slapukai leidžia Paslaugoms ar trečiai &scaron;aliai identifikuoti jus kad lengviau ir patogiau suteiktų prieigą bei tinkamesnes paslaugas.&nbsp;<br />\r\n&nbsp;</div>\r\n\r\n<div>Yra dviejų rū&scaron;ių slapukai: sesijos ir ilgalaikiai.<br />\r\n&nbsp;</div>\r\n\r\n<h2>Kaip mes naudojame slapukus</h2>\r\n\r\n<div>Mes galime i&scaron;saugoti slapukus jūsų interneto nar&scaron;yklėje (ir tuo pačiu jūsų kompiuteryje) kuomet Jūs naudojate Paslaugas.</div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div><strong>Mes naudojame slapukus &scaron;iais tikslais:</strong><br />\r\nįgalinti tam tikras funkcijas Paslaugose</div>\r\n\r\n<ol>\r\n	<li>įgalinti Paslaugų naudojimo apskaitos statistikas</li>\r\n	<li>i&scaron;saugoti jūsų pasirinkimus (pavyzdžiui kalbos pasirinkimą, paie&scaron;ko filtrą ir pan.)</li>\r\n</ol>\r\n\r\n<div><strong>Teikdami Paslaugas mes naudojame sesijos ir ilgalaikius slapukus:&nbsp;</strong></div>\r\n\r\n<ol>\r\n	<li>techninius slapukus &rarr; jie sukuriami tam tikro funkcionalumo Paslaugose įgalinimui ir pa&scaron;alinami sesijos pabaigoje</li>\r\n	<li>trečių &scaron;alių slapukai &rarr; jie gali būti naudojami i&scaron;orinėms paslaugoms tokioms kaip puslapių lankomumo statistikoms (pavyzdžiui Google Analytics).</li>\r\n</ol>\r\n\r\n<div><strong>Slapukų sąra&scaron;as:</strong>\r\n\r\n<table class=\"table table-bordered table-hover\" style=\"width:100%\">\r\n	<thead>\r\n		<tr>\r\n			<th scope=\"col\"><strong>Slapuko pavadinimas</strong></th>\r\n			<th scope=\"col\"><strong>Sukūrimo momentas</strong></th>\r\n			<th scope=\"col\"><strong>Galiojimo laikas</strong></th>\r\n			<th scope=\"col\"><strong>Apra&scaron;ymas</strong></th>\r\n		</tr>\r\n	</thead>\r\n	<tbody>\r\n		<tr>\r\n			<td>main_deauth_profile_token</td>\r\n			<td>Sukuriamas įeinant į puslapį</td>\r\n			<td>Sesija</td>\r\n			<td>Slapukas naudojamas Paslaugų funkcionalumui</td>\r\n		</tr>\r\n		<tr>\r\n			<td>PHPSESSID</td>\r\n			<td>Sukuriamas įeinant į puslapį</td>\r\n			<td>Sesija</td>\r\n			<td>Slapukas naudojamas Paslaugų funkcionalumui įgalinti ir saugo unikalią simbolių reik&scaron;mę</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<h2>Kokie yra jūsų pasirinkimai dėl slapukų</h2>\r\n\r\n<p>Jei patys norite i&scaron;trinti slapukus arba nurodyti savo nar&scaron;yklei i&scaron;trinti arba atsisakyti slapukų, apsilankykite interneto nar&scaron;yklės instrukcijų ar pagalbos puslapiuose.</p>\r\n\r\n<p>Tačiau atminkite, kad jei i&scaron;trinate slapukus arba atsisakysite juos priimti, galimai negalėsite naudotis visomis mūsų Paslaugų funkcijomis, gali būti, kad negalėsite i&scaron;saugoti savo nuostatų ir kai kurie mūsų Paslaugų puslapiai gali būti nekorekti&scaron;kai rodomi.</p>\r\n\r\n<h2>Kur galite rasti daugiau informacijos apie slapukus</h2>\r\n\r\n<p>&nbsp;&nbsp;&nbsp; https://ec.europa.eu/info/cookies_lt</p>\r\n\r\n<p>&nbsp;</p>\r\n</div>',1,'2025-04-06 23:09:57','2025-05-14 23:32:56',1,'slapukai',1);
/*!40000 ALTER TABLE `structures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`roles`)),
  `admin_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`),
  KEY `IDX_1483A5E9642B8210` (`admin_id`),
  CONSTRAINT `FK_1483A5E9642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
('tomas.tomauskas@example.com','$2y$13$QBfK87Q9xr5CZvBtIP0wTekA66sfa3hVtvr56Jaf6eNiWSgBUdE5i','+37060000001','Tomas','Tomaitis',2,'2025-03-05 22:12:00','2025-03-14 09:53:11',NULL,1),
('jonas.jonaitis@example.com','$2y$13$GCNfFmMgm2SKW7s2GSq1m.jcVjSSu5HneASlLeNLmHgg1fXJtC16C','+370600000023','Jonas','Jonaitis',3,'2025-03-06 02:50:11','2025-09-10 23:33:26',NULL,1),
('petras.petraitis@example.lt','$2y$13$MZtBqtfvnT9YXHhZQv9ghepRQbiYQElsErGEgKtusdJRpk8ARKo3G',NULL,'Petras','Petraitis',8,'2025-03-26 00:17:43','2025-03-26 00:17:43','[]',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_objects`
--

DROP TABLE IF EXISTS `users_objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_objects` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `users_id` bigint(20) DEFAULT NULL,
  `country_id` bigint(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `house` varchar(100) DEFAULT NULL,
  `flat` varchar(10) DEFAULT NULL,
  `zip` varchar(5) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `coordinates` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_421AB83967B3B43D` (`users_id`),
  KEY `IDX_421AB839F92F3E70` (`country_id`),
  KEY `IDX_421AB839642B8210` (`admin_id`),
  CONSTRAINT `FK_421AB839642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`),
  CONSTRAINT `FK_421AB83967B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_421AB839F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_objects`
--

LOCK TABLES `users_objects` WRITE;
/*!40000 ALTER TABLE `users_objects` DISABLE KEYS */;
INSERT INTO `users_objects` VALUES
(2,'2025-03-05 22:12:05','2025-05-14 22:35:10',2,101,'Vilnius','Architektų g.','81','10','04208',1,'54.681805, 25.210029,18'),
(3,'2025-03-11 14:23:55','2025-05-14 22:34:12',3,101,'Vilnius','Ukmergės g.','186','10','07170',1,'54.710609, 25.246164,18'),
(4,'2025-03-14 01:44:31','2025-05-14 22:31:49',2,101,'Vilnius','S. Konarskio g.','49',NULL,'03123',1,'54.677739, 25.250133,17');
/*!40000 ALTER TABLE `users_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_objects_services`
--

DROP TABLE IF EXISTS `users_objects_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_objects_services` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `services_id` bigint(20) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `total_price` double NOT NULL,
  `active_to` date NOT NULL,
  `users_objects_services_bundles_id` bigint(20) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `active_from` date NOT NULL DEFAULT curdate(),
  `unit_vat` double NOT NULL DEFAULT 0,
  `unit_price_vat` double NOT NULL,
  `total_price_vat` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5B4D7775AEF5A6C1` (`services_id`),
  KEY `IDX_5B4D777527605F7A` (`users_objects_services_bundles_id`),
  KEY `IDX_5B4D7775642B8210` (`admin_id`),
  CONSTRAINT `FK_5B4D777527605F7A` FOREIGN KEY (`users_objects_services_bundles_id`) REFERENCES `users_objects_services_bundles` (`id`),
  CONSTRAINT `FK_5B4D7775642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`),
  CONSTRAINT `FK_5B4D7775AEF5A6C1` FOREIGN KEY (`services_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_objects_services`
--

LOCK TABLES `users_objects_services` WRITE;
/*!40000 ALTER TABLE `users_objects_services` DISABLE KEYS */;
INSERT INTO `users_objects_services` VALUES
(1,'2025-01-06 00:32:44','2025-04-04 02:14:17',3,1,1246,1246,'2025-08-07',1,1,'2024-01-10',0.21,984.34,984.34),
(2,'2025-01-06 00:32:44','2025-03-30 01:27:17',2,1,2635,2635,'2026-02-05',1,1,'2024-01-10',0.21,2081.65,2081.65),
(3,'2025-01-11 14:24:18','2025-03-30 01:27:09',6,1,1245,1245,'2026-03-31',2,1,'2024-01-19',0.21,983.55,983.55),
(4,'2025-01-11 14:24:18','2025-03-30 01:27:01',8,1,250,250,'2026-03-31',2,1,'2024-01-19',0.21,197.5,197.5),
(5,'2025-03-24 00:19:06','2025-03-30 01:26:55',9,3,79,237,'2026-03-31',2,1,'2024-01-19',0.21,62.41,187.23),
(6,'2025-03-26 20:59:21','2025-03-30 01:26:48',8,1,250,250,'2024-08-15',1,1,'2024-01-10',0.21,197.5,197.5);
/*!40000 ALTER TABLE `users_objects_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_objects_services_bundles`
--

DROP TABLE IF EXISTS `users_objects_services_bundles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_objects_services_bundles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `active_to` date DEFAULT NULL,
  `users_object_id` bigint(20) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `active_from` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id`),
  KEY `IDX_9D954DDC45502CCA` (`users_object_id`),
  KEY `IDX_9D954DDC642B8210` (`admin_id`),
  CONSTRAINT `FK_9D954DDC45502CCA` FOREIGN KEY (`users_object_id`) REFERENCES `users_objects` (`id`),
  CONSTRAINT `FK_9D954DDC642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_objects_services_bundles`
--

LOCK TABLES `users_objects_services_bundles` WRITE;
/*!40000 ALTER TABLE `users_objects_services_bundles` DISABLE KEYS */;
INSERT INTO `users_objects_services_bundles` VALUES
(1,'2025-01-06 00:32:44','2025-01-06 00:32:44',NULL,2,1,'2024-01-10'),
(2,'2025-01-11 14:24:18','2025-01-11 14:24:18',NULL,3,1,'2024-01-19');
/*!40000 ALTER TABLE `users_objects_services_bundles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_objects_services_promotions`
--

DROP TABLE IF EXISTS `users_objects_services_promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_objects_services_promotions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `users_objects_services_id` bigint(20) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `services_promotions_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3521ABDC5C68BDCC` (`users_objects_services_id`),
  KEY `IDX_3521ABDC642B8210` (`admin_id`),
  KEY `IDX_3521ABDC7106FD6B` (`services_promotions_id`),
  CONSTRAINT `FK_3521ABDC5C68BDCC` FOREIGN KEY (`users_objects_services_id`) REFERENCES `users_objects_services` (`id`),
  CONSTRAINT `FK_3521ABDC642B8210` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`id`),
  CONSTRAINT `FK_3521ABDC7106FD6B` FOREIGN KEY (`services_promotions_id`) REFERENCES `services_promotions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_objects_services_promotions`
--

LOCK TABLES `users_objects_services_promotions` WRITE;
/*!40000 ALTER TABLE `users_objects_services_promotions` DISABLE KEYS */;
INSERT INTO `users_objects_services_promotions` VALUES
(16,'2025-04-06 15:43:05','2025-04-06 15:43:05',1,1,2);
/*!40000 ALTER TABLE `users_objects_services_promotions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-05 22:58:30
