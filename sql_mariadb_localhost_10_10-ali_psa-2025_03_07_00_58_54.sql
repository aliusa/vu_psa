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
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) DEFAULT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_73A716FE7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrators`
--

LOCK TABLES `administrators` WRITE;
/*!40000 ALTER TABLE `administrators` DISABLE KEYS */;
INSERT INTO `administrators` VALUES
('info@example.lt','[\"ROLE_ADMIN\"]','$argon2id$v=19$m=65536,t=4,p=1$Qnf6cTqPs99rF/OenGKDPA$GqfAD2Wxi113Gxj46ssjR6ae9su/oYdplISyLGdvNOE',1,'2025-03-02 17:02:01','2025-03-02 17:02:01');
/*!40000 ALTER TABLE `administrators` ENABLE KEYS */;
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
  `iso` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES
('Afganistanas',1,'2025-03-05 21:46:01','2025-03-05 21:46:42','AFG','AF'),
('Airija',2,'2025-03-05 21:46:50','2025-03-05 21:46:50','IRL','IE'),
('Albanija',3,'2025-03-05 21:46:55','2025-03-05 21:46:55','ALB','AL'),
('Alžyras',4,'2025-03-05 21:47:02','2025-03-05 21:47:02','ALG','DZ'),
('Andora',5,'2025-03-05 21:47:06','2025-03-05 21:47:06','AND','AD'),
('Angola',6,'2025-03-05 21:47:09','2025-03-05 21:47:09','ANG','AO'),
('Antigva ir Barbuda',7,'2025-03-05 21:47:13','2025-03-05 21:47:13','ANT','AG'),
('Argentina',8,'2025-03-05 21:47:16','2025-03-05 21:47:16','ARG','AR'),
('Armėnija',9,'2025-03-05 21:47:19','2025-03-05 21:47:19','ARM','AM'),
('Australija',10,'2025-03-05 21:47:22','2025-03-05 21:47:22','AUS','AU'),
('Austrija',11,'2025-03-05 21:47:30','2025-03-05 21:47:30','AUT','AT'),
('Azerbaidžanas',12,'2025-03-05 21:47:33','2025-03-05 21:47:33','AZE','AZ'),
('Bahamos',13,'2025-03-05 21:47:39','2025-03-05 21:47:39','BAH','BS'),
('Bahreinas',14,'2025-03-05 21:47:42','2025-03-05 21:47:42','BRN','BH'),
('Baltarusija',15,'2025-03-05 21:47:45','2025-03-05 21:47:45','BLR','BY'),
('Bangladešas',16,'2025-03-05 21:47:48','2025-03-05 21:47:48','BAN','BD'),
('Barbadosas',17,'2025-03-05 21:47:55','2025-03-05 21:47:55','BAR','BB'),
('Belgija',18,'2025-03-05 21:47:58','2025-03-05 21:47:58','BEL','BE'),
('Belizas',19,'2025-03-05 21:48:02','2025-03-05 21:48:02','BIZ','BZ'),
('Beninas',20,'2025-03-05 21:48:06','2025-03-05 21:48:06','BEN','BJ'),
('Bisau Gvinėja',21,'2025-03-05 21:48:09','2025-03-05 21:48:09','GBS','GW'),
('Bolivija',22,'2025-03-05 21:48:12','2025-03-05 21:48:12','BOL','BO'),
('Bosnija ir Hercegovina',23,'2025-03-05 21:48:15','2025-03-05 21:48:15','BIH','BA'),
('Botsvana',24,'2025-03-05 21:48:18','2025-03-05 21:48:18','BOT','BW'),
('Brazilija',25,'2025-03-05 21:48:21','2025-03-05 21:48:21','BRA','BR'),
('Brunėjus',26,'2025-03-05 21:48:28','2025-03-05 21:48:28','BRU','BN'),
('Bulgarija',27,'2025-03-05 21:48:31','2025-03-05 21:48:31','BUL','BG'),
('Burkina Fasas',28,'2025-03-05 21:48:33','2025-03-05 21:48:33','BUR','BF'),
('Burundis',29,'2025-03-05 21:48:36','2025-03-05 21:48:36','BDI','BI'),
('Butanas',30,'2025-03-05 21:48:39','2025-03-05 21:48:39','BHU','BT'),
('Centrinės Afrikos Respublika',31,'2025-03-05 21:48:43','2025-03-05 21:48:43','CAF','CF'),
('Čadas',32,'2025-03-05 21:48:46','2025-03-05 21:48:46','CHA','TD'),
('Čekija',33,'2025-03-05 21:48:49','2025-03-05 21:48:49','CZE','CZ'),
('Čilė',34,'2025-03-05 21:48:51','2025-03-05 21:48:51','CHI','CL'),
('Danija',35,'2025-03-05 21:48:54','2025-03-05 21:48:54','DEN','DK'),
('Dominika',36,'2025-03-05 21:48:58','2025-03-05 21:48:58','DMA','DM'),
('Dominikos Respublika',37,'2025-03-05 21:49:02','2025-03-05 21:49:02','DOM','DO'),
('Dramblio Kaulo Krantas',38,'2025-03-05 21:49:05','2025-03-05 21:49:05','CIV','CI'),
('Džersis',39,'2025-03-05 21:49:08','2025-03-05 21:49:08',NULL,'JE'),
('Džibutis',40,'2025-03-05 21:49:11','2025-03-05 21:49:11','DJI','DJ'),
('Egiptas',41,'2025-03-05 21:49:15','2025-03-05 21:49:15','EGY','EG'),
('Ekvadoras',42,'2025-03-05 21:49:37','2025-03-05 21:49:37','ECU','EC'),
('Eritrėja',43,'2025-03-05 21:49:38','2025-03-05 21:49:38','ERI','ER'),
('Estija',44,'2025-03-05 21:49:42','2025-03-05 21:49:42','EST','EE'),
('Etiopija',45,'2025-03-05 21:49:44','2025-03-05 21:49:44','ETH','ET'),
('Fidžis',46,'2025-03-05 21:49:49','2025-03-05 21:49:49','FIJ','FJ'),
('Filipinai',47,'2025-03-05 21:49:51','2025-03-05 21:49:51','PHI','PH'),
('Gabonas',48,'2025-03-05 21:49:54','2025-03-05 21:49:54','GAB','GA'),
('Gajana',49,'2025-03-05 21:49:57','2025-03-05 21:49:57','GUY','GY'),
('Gambija',50,'2025-03-05 21:50:00','2025-03-05 21:50:00','GAM','GM'),
('Gana',51,'2025-03-05 21:50:05','2025-03-05 21:50:05','GHA','GH'),
('Graikija',52,'2025-03-05 21:50:08','2025-03-05 21:50:08','GRE','GR'),
('Grenada',53,'2025-03-05 21:50:11','2025-03-05 21:50:11','GRN','GD'),
('Gvatemala',54,'2025-03-05 21:50:15','2025-03-05 21:50:15','GUA','GT'),
('Gvinėja',55,'2025-03-05 21:51:22','2025-03-05 21:51:22','GUI','GN'),
('Haitis',56,'2025-03-05 21:51:25','2025-03-05 21:51:25','HAI','HT'),
('Hondūras',57,'2025-03-05 21:51:28','2025-03-05 21:51:28','HON','HN'),
('Indija',58,'2025-03-05 21:51:31','2025-03-05 21:51:31','IND','IN'),
('Indonezija',59,'2025-03-05 21:52:07','2025-03-05 21:52:07','INA','ID'),
('Irakas',60,'2025-03-05 21:55:45','2025-03-05 21:55:45','IRQ','IQ'),
('Iranas',61,'2025-03-05 21:55:54','2025-03-05 21:55:54','IRN','IR'),
('Islandija',62,'2025-03-05 21:56:03','2025-03-05 21:56:03','ISL','IS'),
('Ispanija',63,'2025-03-05 22:04:45','2025-03-05 22:04:45','ESP','ES'),
('Italija',64,'2025-03-05 22:04:52','2025-03-05 22:04:52','ITA','IT'),
('Izraelis',65,'2025-03-05 22:05:00','2025-03-05 22:05:00','ISR','IL'),
('Jamaika',66,'2025-03-05 22:05:08','2025-03-05 22:05:08','JAM','JM'),
('Japonija',67,'2025-03-05 22:05:18','2025-03-05 22:05:18','JPN','JP'),
('Jemenas',68,'2025-03-05 22:05:26','2025-03-05 22:05:26','YEM','YE'),
('Jordanija',69,'2025-03-05 22:05:32','2025-03-05 22:05:32','JOR','JO'),
('Jungtiniai Arabų Emyratai',70,'2025-03-05 22:05:38','2025-03-05 22:05:38','UAE','AE'),
('Jungtinė Karalystė',71,'2025-03-05 22:05:46','2025-03-05 22:05:46','GBR','GB'),
('JAV',72,'2025-03-05 22:05:51','2025-03-05 22:05:51','USA','US'),
('Juodkalnija',73,'2025-03-05 22:05:57','2025-03-05 22:05:57','MNE','ME'),
('Kambodža',74,'2025-03-05 22:06:14','2025-03-05 22:06:14','CAM','KH'),
('Kamerūnas',75,'2025-03-05 22:06:21','2025-03-05 22:06:21','CMR','CM'),
('Kanada',76,'2025-03-05 22:06:27','2025-03-05 22:06:27','CAN','CA'),
('Kataras',77,'2025-03-05 22:06:33','2025-03-05 22:06:33','QAT','QA'),
('Kazachstanas',78,'2025-03-05 22:06:41','2025-03-05 22:06:41','KAZ','KZ'),
('Kenija',79,'2025-03-05 22:06:49','2025-03-05 22:06:49','KEN','KE'),
('Kinija',80,'2025-03-05 22:06:57','2025-03-05 22:06:57','CHN','CN'),
('Kipras',81,'2025-03-05 22:07:05','2025-03-05 22:07:05','CYP','CY'),
('Kirgizija',82,'2025-03-05 22:07:13','2025-03-05 22:07:13','KGZ','KG'),
('Kiribatis',83,'2025-03-05 22:07:19','2025-03-05 22:07:19','KIR','KI'),
('Kolumbija',84,'2025-03-05 22:07:28','2025-03-05 22:07:28','COL','CO'),
('Komorai',85,'2025-03-05 22:07:34','2025-03-05 22:07:34','COM','KM'),
('Kongo Demokratinė Respublika',86,'2025-03-05 22:07:41','2025-03-05 22:07:41','COD','CD'),
('Kongas',87,'2025-03-05 22:07:48','2025-03-05 22:07:48','COG','CG'),
('Kosovas',88,'2025-03-05 22:07:53','2025-03-05 22:07:53',NULL,'KS'),
('Kosta Rika',89,'2025-03-05 22:07:58','2025-03-05 22:07:58','CRC','CR'),
('Kroatija',90,'2025-03-05 22:08:06','2025-03-05 22:08:06','CRO','HR'),
('Kuba',91,'2025-03-05 22:08:14','2025-03-05 22:08:14','CUB','CU'),
('Kuveitas',92,'2025-03-05 22:08:24','2025-03-05 22:08:24','KUW','KW'),
('Laosas',93,'2025-03-05 22:08:41','2025-03-05 22:08:41','LAO','LA'),
('Latvija',94,'2025-03-05 22:09:02','2025-03-05 22:09:02','LAT','LV'),
('Lenkija',95,'2025-03-05 22:09:10','2025-03-05 22:09:10','POL','PL'),
('Lesotas',96,'2025-03-05 22:09:18','2025-03-05 22:09:18','LES','LS'),
('Libanas',97,'2025-03-05 22:09:25','2025-03-05 22:09:25','LIB','LB'),
('Liberija',98,'2025-03-05 22:09:34','2025-03-05 22:09:34','LBR','LR'),
('Libija',99,'2025-03-05 22:09:42','2025-03-05 22:09:42','LBA','LY'),
('Lichtenšteinas',100,'2025-03-05 22:09:49','2025-03-05 22:09:49','LIE','LI'),
('Lietuva',101,'2025-03-05 22:09:56','2025-03-05 22:09:56','LTU','LT'),
('Liuksemburgas',102,'2025-03-05 22:10:03','2025-03-05 22:10:03','LUX','LU'),
('Madagaskaras',103,'2025-03-05 22:10:14','2025-03-05 22:10:14','MAD','MG'),
('Malaizija',104,'2025-03-05 22:10:24','2025-03-05 22:10:24','MAS','MY'),
('Malavis',105,'2025-03-05 22:10:31','2025-03-05 22:10:31','MAW','MW'),
('Maldyvai',106,'2025-03-05 22:10:37','2025-03-05 22:10:37','MDV','MV'),
('Malis',107,'2025-03-05 22:12:28','2025-03-05 22:12:28','MLI','ML'),
('Malta',108,'2025-03-05 22:12:33','2025-03-05 22:12:33','MLT','MT'),
('Marokas',109,'2025-03-05 22:12:39','2025-03-05 22:12:39','MAR','MA'),
('Maršalo Salos',110,'2025-03-05 22:12:48','2025-03-05 22:12:48','MHL','MH'),
('Mauricijus',111,'2025-03-05 22:12:56','2025-03-05 22:12:56','MRI','MU'),
('Mauritanija',112,'2025-03-05 22:13:04','2025-03-05 22:13:04','MTN','MR'),
('Meksika',113,'2025-03-05 22:13:10','2025-03-05 22:13:10','MEX','MX'),
('Mianmaras',114,'2025-03-05 22:13:18','2025-03-05 22:13:18','MYA','MM'),
('Mikronezijos Federacinės Valstijos',115,'2025-03-05 22:13:26','2025-03-05 22:13:26','FSM','FM'),
('Moldavija',116,'2025-03-05 22:13:33','2025-03-05 22:13:33','MDA','MD'),
('Monakas',117,'2025-03-05 22:13:41','2025-03-05 22:13:41','MON','MC'),
('Mongolija',118,'2025-03-05 22:13:51','2025-03-05 22:13:51','MGL','MN'),
('Mozambikas',119,'2025-03-05 22:13:59','2025-03-05 22:13:59','MOZ','MZ'),
('Namibija',120,'2025-03-05 22:14:08','2025-03-05 22:14:08','NAM','NA'),
('Naujoji Zelandija',121,'2025-03-05 22:14:18','2025-03-05 22:14:18','NZL','NZ'),
('Nauru',122,'2025-03-05 22:14:24','2025-03-05 22:14:24','NRU','NR'),
('Nepalas',123,'2025-03-05 22:14:31','2025-03-05 22:14:31','NEP','NP'),
('Nyderlandai',124,'2025-03-05 22:14:40','2025-03-05 22:14:40','NED','NL'),
('Nigerija',125,'2025-03-05 22:14:48','2025-03-05 22:14:48','NGR','NG'),
('Nigeris',126,'2025-03-05 22:14:54','2025-03-05 22:14:54','NIG','NE'),
('Nikaragva',127,'2025-03-05 22:15:02','2025-03-05 22:15:02','NCA','NI'),
('Norvegija',128,'2025-03-05 22:15:08','2025-03-05 22:15:08','NOR','NO'),
('Omanas',129,'2025-03-05 22:15:15','2025-03-05 22:15:15','OMA','OM'),
('Pakistanas',130,'2025-03-05 22:15:24','2025-03-05 22:15:24','PAK','PK'),
('Palau',131,'2025-03-05 22:15:33','2025-03-05 22:15:33','PLW','PW'),
('Panama',132,'2025-03-05 22:15:39','2025-03-05 22:15:39','PAN','PA'),
('Papua Naujoji Gvinėja',133,'2025-03-05 22:15:48','2025-03-05 22:15:48','PNG','PG'),
('Paragvajus',134,'2025-03-05 22:15:59','2025-03-05 22:15:59','PAR','PY'),
('Peru',135,'2025-03-05 22:16:05','2025-03-05 22:16:05','PER','PE'),
('Pietų Afrikos Respublika',136,'2025-03-05 22:16:14','2025-03-05 22:16:14','RSA','ZA'),
('Pietų Korėja',137,'2025-03-05 22:16:19','2025-03-05 22:16:19','KOR','KR'),
('Pietų Sudanas',138,'2025-03-05 22:16:24','2025-03-05 22:16:24',NULL,NULL),
('Portugalija',139,'2025-03-05 22:16:30','2025-03-05 22:16:30','POR','PT'),
('Prancūzija',140,'2025-03-05 22:16:35','2025-03-05 22:16:35','FRA','FR'),
('Pusiaujo Gvinėja',141,'2025-03-05 22:16:42','2025-03-05 22:16:42','GEQ','GQ'),
('Ruanda',142,'2025-03-05 22:16:52','2025-03-05 22:16:52','RWA','RW'),
('Rumunija',143,'2025-03-05 22:16:59','2025-03-05 22:16:59','ROU','RO'),
('Rusija',144,'2025-03-05 22:17:07','2025-03-05 22:17:07','RUS','RU'),
('Rytų Timoras',145,'2025-03-05 22:17:15','2025-03-05 22:17:15','TLS','TP'),
('Saliamono salos',146,'2025-03-05 22:17:23','2025-03-05 22:17:23','SOL','SB'),
('Salvadoras',147,'2025-03-05 22:17:29','2025-03-05 22:17:29','ESA','SV'),
('Samoa',148,'2025-03-05 22:17:34','2025-03-05 22:17:34','SAM','WS'),
('San Marinas',149,'2025-03-05 22:17:40','2025-03-05 22:17:40','SMR','SM'),
('San Tomė ir Prinsipė',150,'2025-03-05 22:17:47','2025-03-05 22:17:47','STP','ST'),
('Saudo Arabija',151,'2025-03-05 22:17:53','2025-03-05 22:17:53','KSA','SA'),
('Seišeliai',152,'2025-03-05 22:17:59','2025-03-05 22:17:59','SEY','SC'),
('Senegalas',153,'2025-03-05 22:18:04','2025-03-05 22:18:04','SEN','SN'),
('Sent Kitsas ir Nevis',154,'2025-03-05 22:18:10','2025-03-05 22:18:10','SKN','KN'),
('Sent Lusija',155,'2025-03-05 22:18:17','2025-03-05 22:18:17','LCA','LC'),
('Sent Vinsentas ir Grenadinai',156,'2025-03-05 22:18:24','2025-03-05 22:18:24','VIN','VC'),
('Serbija',157,'2025-03-05 22:18:31','2025-03-05 22:18:31','SRB','RS'),
('Siera Leonė',158,'2025-03-05 22:18:38','2025-03-05 22:18:38','SLE','SL'),
('Singapūras',159,'2025-03-05 22:18:45','2025-03-05 22:18:45','SIN','SG'),
('Sirija',160,'2025-03-05 22:18:51','2025-03-05 22:18:51','SYR','SY'),
('Slovakija',161,'2025-03-05 22:18:57','2025-03-05 22:18:57','SVK','SK'),
('Slovėnija',162,'2025-03-05 22:19:03','2025-03-05 22:19:03','SLO','SI'),
('Somalis',163,'2025-03-05 22:19:09','2025-03-05 22:19:09','SOM','SO'),
('Sudanas',164,'2025-03-05 22:19:15','2025-03-05 22:19:15','SUD','SD'),
('Suomija',165,'2025-03-05 22:19:21','2025-03-05 22:19:21','FIN','FI'),
('Surinamas',166,'2025-03-05 22:19:28','2025-03-05 22:19:28','SUR','SR'),
('Svazilandas',167,'2025-03-05 22:19:35','2025-03-05 22:19:35','SWZ','SZ'),
('Šiaurės Makedonija',168,'2025-03-05 22:19:41','2025-03-05 22:19:41','MKD','MK'),
('Šiaurės Korėja',169,'2025-03-05 22:19:50','2025-03-05 22:19:50','PRK','KP'),
('Šri Lanka',170,'2025-03-05 22:19:59','2025-03-05 22:19:59','SRI','LK'),
('Švedija',171,'2025-03-05 22:20:06','2025-03-05 22:20:06','SWE','SE'),
('Šveicarija',172,'2025-03-05 22:20:13','2025-03-05 22:20:13','SUI','CH'),
('Tadžikija',173,'2025-03-05 22:20:22','2025-03-05 22:20:22','TJK','TJ'),
('Tailandas',174,'2025-03-05 22:20:28','2025-03-05 22:20:28','THA','TH'),
('Taivanas',175,'2025-03-05 22:20:35','2025-03-05 22:20:35','TPE','TW'),
('Tanzanija',176,'2025-03-05 22:20:40','2025-03-05 22:20:40','TAN','TZ'),
('Tonga',177,'2025-03-05 22:20:49','2025-03-05 22:20:49','TGA','TO'),
('Trinidadas ir Tobagas',178,'2025-03-05 22:20:56','2025-03-05 22:20:56','TRI','TT'),
('Tunisas',179,'2025-03-05 22:21:08','2025-03-05 22:21:08','TUN','TN'),
('Turkija',180,'2025-03-05 22:21:13','2025-03-05 22:21:13','TUR','TR'),
('Turkmėnija',181,'2025-03-05 22:21:20','2025-03-05 22:21:20','TKM','TM'),
('Tuvalu',182,'2025-03-05 22:21:26','2025-03-05 22:21:26','TUV','TV'),
('Uganda',183,'2025-03-05 22:21:32','2025-03-05 22:21:32','UGA','UG'),
('Ukraina',184,'2025-03-05 22:21:40','2025-03-05 22:21:40','UKR','UA'),
('Urugvajus',185,'2025-03-05 22:21:47','2025-03-05 22:21:47','URU','UY'),
('Vanuatu',186,'2025-03-05 22:21:54','2025-03-05 22:21:54','VAN','VU'),
('Vatikanas',187,'2025-03-05 22:22:00','2025-03-05 22:22:00','VAT','VA'),
('Venesuela',188,'2025-03-05 22:22:06','2025-03-05 22:22:06','VEN','VE'),
('Vengrija',189,'2025-03-05 22:22:12','2025-03-05 22:22:12','HUN','HU'),
('Vietnamas',190,'2025-03-05 22:22:18','2025-03-05 22:22:18','JIE','VN'),
('Vokietija',191,'2025-03-05 22:22:25','2025-03-05 22:22:25','GER','DE'),
('Zambija',192,'2025-03-05 22:22:33','2025-03-05 22:22:33','ZAM','ZM'),
('Zimbabvė',193,'2025-03-05 22:22:39','2025-03-05 22:22:39','ZIM','ZW'),
('Žaliasis Kyšulys',194,'2025-03-05 22:22:47','2025-03-05 22:22:47','CPV','CV');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
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
  `active_from` datetime NOT NULL DEFAULT current_timestamp(),
  `active_to` datetime DEFAULT current_timestamp(),
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `description` longtext DEFAULT NULL,
  `advertise` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
('Internetas MINI',4030,'2025-03-02 12:00:00',NULL,1,'2025-03-02 20:35:13','2025-03-06 21:51:23','Turėsite iki 500 MBPS',1),
('Modemo nuoma',400,'2025-03-02 12:00:00',NULL,2,'2025-03-02 20:58:59','2025-03-02 20:58:59',NULL,0),
('Internetas MIDI',1257,'2025-03-02 12:00:00',NULL,3,'2025-03-02 21:32:53','2025-03-06 21:51:22','Turėsite iki 1000 MBPS',1),
('Internetas MAXI',1568,'2025-03-02 12:00:00',NULL,4,'2025-03-02 21:35:30','2025-03-06 21:51:21','Turėsite iki 1500 MBPS',1),
('Tinklo paslaugų mokestis',428,'2025-03-06 12:00:00',NULL,5,'2025-03-06 20:53:09','2025-03-06 20:53:09',NULL,0);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
('tomas.tomauskas@example.com','$2y$13$lCVu0iYaJ/cfXzQas.TIuOxkBPjkqwLCM/yYI4hWY6rhOiF1kQFSG','+37060000001','Tomas','Tomaitis',2,'2025-03-05 22:12:00','2025-03-05 22:12:00'),
('jonas.jonaitis@example.com','$2y$13$1SAtHlvETik/G6u3Xc9eb.lKPEVqACm4IYQo/o4x0.atRBWWLvTSa','+37060000002','Jonas','Jonaitis',3,'2025-03-06 02:50:11','2025-03-06 02:50:11');
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
  PRIMARY KEY (`id`),
  KEY `IDX_421AB83967B3B43D` (`users_id`),
  KEY `IDX_421AB839F92F3E70` (`country_id`),
  CONSTRAINT `FK_421AB83967B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_421AB839F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_objects`
--

LOCK TABLES `users_objects` WRITE;
/*!40000 ALTER TABLE `users_objects` DISABLE KEYS */;
INSERT INTO `users_objects` VALUES
(2,'2025-03-05 22:12:05','2025-03-05 22:36:31',2,101,'Vilnius','Laisvės pr.','13','20','08945');
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
  `total_price` int(11) NOT NULL,
  `active_to` datetime DEFAULT current_timestamp(),
  `users_objects_services_bundles_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5B4D7775AEF5A6C1` (`services_id`),
  KEY `IDX_5B4D777527605F7A` (`users_objects_services_bundles_id`),
  CONSTRAINT `FK_5B4D777527605F7A` FOREIGN KEY (`users_objects_services_bundles_id`) REFERENCES `users_objects_services_bundles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_5B4D7775AEF5A6C1` FOREIGN KEY (`services_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_objects_services`
--

LOCK TABLES `users_objects_services` WRITE;
/*!40000 ALTER TABLE `users_objects_services` DISABLE KEYS */;
INSERT INTO `users_objects_services` VALUES
(1,'2025-03-06 02:28:02','2025-03-06 03:06:36',3,4,1245,4980,'2026-03-01 00:00:00',1);
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
  `active_to` datetime DEFAULT NULL,
  `users_object_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9D954DDC45502CCA` (`users_object_id`),
  CONSTRAINT `FK_9D954DDC45502CCA` FOREIGN KEY (`users_object_id`) REFERENCES `users_objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_objects_services_bundles`
--

LOCK TABLES `users_objects_services_bundles` WRITE;
/*!40000 ALTER TABLE `users_objects_services_bundles` DISABLE KEYS */;
INSERT INTO `users_objects_services_bundles` VALUES
(1,'2025-03-06 00:32:44','2025-03-06 00:32:44','2026-03-01 00:00:00',2);
/*!40000 ALTER TABLE `users_objects_services_bundles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-07  0:58:54
