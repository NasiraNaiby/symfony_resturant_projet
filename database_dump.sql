/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: sf_district
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-0ubuntu0.24.04.2

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_nom` varchar(80) NOT NULL,
  `cat_description` longtext NOT NULL,
  `cat_image` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'Tacos','<div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique harum nostrum tempora eos non velit illum voluptatem officia, sit cupiditate cumque mollitia fugit quis praesentium repudiandae consectetur soluta laborum! Autem?</div>','2f3e4eb9e3a9b171609318541a496bdfafd09062.jpg'),
(2,'Burgers','<div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique harum nostrum tempora eos non velit illum voluptatem officia, sit cupiditate cumque mollitia fugit quis praesentium repudiandae consectetur soluta laborum! Autem?</div>','a856b7845ad635cd6fda82b29673f1e38997765a.jpg'),
(3,'Pizza','<div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique harum nostrum tempora eos non velit illum voluptatem officia, sit cupiditate cumque mollitia fugit quis praesentium repudiandae consectetur soluta laborum! Autem?</div>','b7c3d8b4fd48660181ac69e7a4afed746a3bf6d1.jpg'),
(4,'Petit-déjeuner','<div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique harum nostrum tempora eos non velit illum voluptatem officia, sit cupiditate cumque mollitia fugit quis praesentium repudiandae consectetur soluta laborum! Autem?</div>','bb038dbcbcc00646f2f42148664ba1367b83e1c0.jpg'),
(5,'SeaFoods','<div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique harum nostrum tempora eos non velit illum voluptatem officia, sit cupiditate cumque mollitia fugit quis praesentium repudiandae consectetur soluta laborum! Autem?</div>','a432205893fc0ae044ffe9ca9c642a10ede0007a.jpg'),
(6,'Salads','<div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique harum nostrum tempora eos non velit illum voluptatem officia, sit cupiditate cumque mollitia fugit quis praesentium repudiandae consectetur soluta laborum! Autem?</div>','e254823af33f6ee6780ac9ffa78d2d0724a593c1.jpg'),
(7,'Boissons','<div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique harum nostrum tempora eos non velit illum voluptatem officia, sit cupiditate cumque mollitia fugit quis praesentium repudiandae consectetur soluta laborum! Autem?</div>','b8eb2893e172bae284f106d3470610a12ac596c1.jpg');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commands`
--

DROP TABLE IF EXISTS `commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command_etat` varchar(50) NOT NULL,
  `command_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(6,2) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `delivery_addresse` varchar(100) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9A3E132CA76ED395` (`user_id`),
  CONSTRAINT `FK_9A3E132CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commands`
--

LOCK TABLES `commands` WRITE;
/*!40000 ALTER TABLE `commands` DISABLE KEYS */;
INSERT INTO `commands` VALUES
(14,'pending','2025-03-26',25,10.99,'',NULL,NULL),
(15,'confirmed','2025-03-27',24,13.00,'credit_card',NULL,NULL),
(17,'confirmed','2025-03-27',24,10.99,'bank_transfer',NULL,NULL),
(18,'confirmed','2025-03-28',24,5.00,'paypal',NULL,NULL),
(25,'pending','2025-03-31',24,1.00,'paypal','109 rue de fbg',NULL),
(28,'pending','2025-03-31',24,5.99,'bank_transfer','44 rue du prof ch cabrol','80000'),
(29,'pending','2025-03-31',24,6.00,'cash','109 rue de fbg','80000'),
(31,'confirmed','2025-03-31',24,2.00,'bank_transfer','109 rue de fbg','80000'),
(33,'pending','2025-04-01',22,2.00,'cash','Address 515687','0'),
(34,'pending','2025-04-01',24,5.99,'paypal','109 rue de fbg','80000');
/*!40000 ALTER TABLE `commands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail`
--

DROP TABLE IF EXISTS `detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) DEFAULT NULL,
  `plat_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2E067F9382EA2E54` (`commande_id`),
  KEY `IDX_2E067F93D73DB560` (`plat_id`),
  CONSTRAINT `FK_2E067F9382EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commands` (`id`),
  CONSTRAINT `FK_2E067F93D73DB560` FOREIGN KEY (`plat_id`) REFERENCES `plats` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail`
--

LOCK TABLES `detail` WRITE;
/*!40000 ALTER TABLE `detail` DISABLE KEYS */;
INSERT INTO `detail` VALUES
(16,14,4,1),
(17,14,3,1),
(18,15,4,1),
(19,15,10,1),
(20,15,18,1),
(21,15,20,1),
(23,17,3,1),
(24,17,4,1),
(25,18,4,1),
(32,25,20,1),
(35,28,3,1),
(36,29,10,1),
(37,29,19,1),
(40,31,20,1),
(41,31,22,1),
(43,33,20,1),
(44,33,21,1),
(45,34,3,1);
/*!40000 ALTER TABLE `detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20250305152450','2025-03-05 15:25:14',175),
('DoctrineMigrations\\Version20250305153225','2025-03-05 15:32:29',68),
('DoctrineMigrations\\Version20250305154206','2025-03-05 15:42:12',90),
('DoctrineMigrations\\Version20250306090509','2025-03-06 09:05:39',110),
('DoctrineMigrations\\Version20250306105258','2025-03-06 10:53:28',106),
('DoctrineMigrations\\Version20250306110044','2025-03-06 11:00:50',91),
('DoctrineMigrations\\Version20250306111553','2025-03-06 11:15:59',166),
('DoctrineMigrations\\Version20250310094808','2025-03-10 09:48:19',20),
('DoctrineMigrations\\Version20250314102047','2025-03-14 10:21:22',39),
('DoctrineMigrations\\Version20250320124451','2025-03-20 12:45:01',39),
('DoctrineMigrations\\Version20250320141226','2025-03-20 14:12:35',82),
('DoctrineMigrations\\Version20250320142101','2025-03-20 14:21:11',133),
('DoctrineMigrations\\Version20250325141135','2025-03-25 14:11:38',34),
('DoctrineMigrations\\Version20250326095710','2025-03-26 09:57:32',26),
('DoctrineMigrations\\Version20250327125307','2025-03-27 12:53:27',28),
('DoctrineMigrations\\Version20250331080538','2025-03-31 08:06:01',28),
('DoctrineMigrations\\Version20250331084909','2025-03-31 08:49:28',28),
('DoctrineMigrations\\Version20250331115105','2025-03-31 11:51:23',41),
('DoctrineMigrations\\Version20250401090352','2025-04-01 09:04:13',30),
('DoctrineMigrations\\Version20250403112253','2025-04-03 11:23:24',99);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES
(1,'Nasira Naeibi','0123456789','nasira3795@gmail.com','your restuarant is the best'),
(2,'Nasira Naeibi','0123456789','nasira3795@gmail.com','your restuarant is the best'),
(3,'Nasira Naeibi','0123456789','nasira3795@gmail.com','your restuarant is the best'),
(4,'Nasira Naeibi','0123456789','nasira3795@gmail.com','your restuarant is the best'),
(5,'Nasira Naeibi','0123456789','nasira3795@gmail.com','your restuarant is the best'),
(6,'Nasira Naeibi','0123456789','nasira3795@gmail.com','your restuarant is the best'),
(7,'Nasira Naeibi','0123456789','user1@example.com','this is a test from you ideas part ');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plats`
--

DROP TABLE IF EXISTS `plats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_id` int(11) NOT NULL,
  `plat_nom` varchar(100) NOT NULL,
  `plat_description` longtext NOT NULL,
  `plat_prix` double NOT NULL,
  `plat_photo` longtext NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `IDX_854A620ABCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_854A620ABCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plats`
--

LOCK TABLES `plats` WRITE;
/*!40000 ALTER TABLE `plats` DISABLE KEYS */;
INSERT INTO `plats` VALUES
(1,4,'Break fast','this is a break fast&nbsp',5.95,'e85537507264459f0936a89fa4662c7b9bd09b82.jpg',1),
(3,1,'Taco Mexicain','delicious tacos',5.99,'b89546480b329952ad90045f749521a03e0cc918.jpg',1),
(4,1,'Traditional Tacos','Traditional Tacos',5,'a4d20045f642175a73f6b63e133a233559a040ee.jpg',1),
(5,2,'Cheeseburger','Dégustez notre cheeseburger savoureux ! Un steak de bœuf juteux, du cheddar fondu, de la laitue fraîche, des tomates mûres, des cornichons croquants et une sauce spéciale, le tout dans un pain brioché toasté. Un classique qui saura vous satisfaire.',4,'5f6c4cb77a6c69096cae49b5e2e9312d15727114.jpg',1),
(6,2,'Pizza Hawaïenne au Poulet','Une pizza garnie de morceaux de poulet, d&amp;#039;ananas, d&amp;#039;oignons rouges, de fromage fondu, de sauce tomate et de feuilles de coriandre, le tout disposé sur une planche en bois',6,'61e4f6b7bd3b59ebf7e361b594853e448f15b52e.jpg',1),
(7,3,'Pizza au Saumon Fumé et Épinards','Cette pizza est garnie de saumon fumé, de feuilles d&amp;#039;épinards fraîches et d&amp;#039;un quartier de citron. La pâte est légèrement dorée et croustillante, avec une base de fromage fondu.',7,'f1bfb04deef335c643b8bd830cc3192eb736b057.png',1),
(8,2,'Wrap aux Pois Chiches et Légumes','Ce wrap est composé de pois chiches épicés, de carottes râpées et d&amp;#039;autres légumes, le tout enveloppé dans une tortilla grillée. Il est servi avec une sauce crémeuse en accompagnement',7,'a7f50563c9976a20211f0307818c1976196ff19a.jpg',1),
(9,5,'Filet de Poisson Rôti','Ce plat met en avant un filet de poisson rôti, accompagné d&amp;#039;un brin de thym frais, de légumes grillés (poivrons et courgettes) et d&amp;#039;une purée crémeuse de pommes de terre. Le tout est assaisonné d&amp;#039;une sauce parfumée à l&amp;#039;origan',5,'80df9f72bf48119a3253116ccf9fa3c0643d918a.jpg',1),
(10,5,'Moules marinières','Les moules marinières sont un plat de fruits de mer composé de moules cuites dans une sauce à base de vin blanc, d&amp;#039;ail, d&amp;#039;oignons et de persil. Les moules sont ouvertes et garnies de persil frais haché, ce qui leur donne une saveur délicieuse et aromatique.',5,'075aae31320c7b4d4b3eaa5e16051e6c46acb530.jpg',1),
(11,5,'Ceviche Marin','Ce plat est composé de crevettes, de poulpe, de morceaux de poisson, de tranches de concombre et de quartiers de citron vert, le tout assaisonné de coriandre fraîche et de poivre noir, et servi dans une sauce tomate.',5,'60993b9b1a7388d9ced225502d3b4b19560fe248.jpg',1),
(12,5,'Saumon aux Légumes','Ce plat est composé de tranches de saumon cru, disposées sur un lit de légumes mélangés, notamment différents types de laitue. Le saumon affiche une couleur orange vibrante et un marbrage délicat, offrant une présentation visuellement attrayante et saine.',7,'a0eba112d1f228d0686e7f76aa1cbff32c1f7432.jpg',1),
(13,4,'Gaufre aux Fruits et Yaourt','Une gaufre dorée garnie de yaourt, de fraises fraîches, de myrtilles et de granola croquant.',4,'6c0a1eb4b6b6c7226bb48157971ce78167141008.jpg',1),
(14,4,'Pancakes aux Myrtilles','Une pile de pancakes moelleux garnis de myrtilles fraîches et arrosés de sirop d&amp;#039;érable.',4,'b09ea6eb8eeede942b05abe0a6980479dca71fd5.jpg',1),
(15,4,'Tartine à l&#039;Œuf et Légumes','Une tartine composée d&amp;#039;une tranche de pain grillé garnie de légumes frais, d&amp;#039;une tranche de tomate et d&amp;#039;un œuf au plat, agrémentée de fines herbes.',4,'93bc20c7915d8c9e246476bbeb0245951c969511.jpg',1),
(16,4,'Œufs au Plat et Légumes Grillés','Cette assiette contient des tranches d&amp;#039;aubergine, de tomate, de champignon et d&amp;#039;oignon, toutes grillées à la perfection et garnies de deux œufs au plat, parsemés de fines herbes fraîches.',5,'5ea8bdbe3530d136dab49c4017d83980bd16a560.jpg',1),
(17,6,'Salade grecque','La salade grecque est composée de laitue croquante, de poivrons rouges, d&amp;#039;oignons rouges, de fromage feta, d&amp;#039;olives vertes et d&amp;#039;herbes fraîches. Elle est souvent assaisonnée d&amp;#039;huile d&amp;#039;olive et de vinaigre.',2,'c6aaf6cc3c6b05f86f54407db7446720a673fc55.jpg',1),
(18,6,'Salade Mixte','Une salade composée avec des tranches de concombre, des morceaux de carotte, des feuilles de laitue, des rondelles d&amp;#039;œuf dur, du chou rouge et des grains de maïs',2,'c880cc94a882fc2f4bb78197e28d76c05eea9312.jpg',1),
(19,7,'Coca-Cola','Une boisson gazeuse rafraîchissante.',1,'a5d8e6f97a024b4a51ee281d895cb42f018dee4f.jpg',1),
(20,7,'Pepsi','Une boisson gazeuse populaire au goût de cola.',1,'1e41714047fa92a69684747a2caf42afbacc9593.webp',1),
(21,7,'Orange Juice','Un jus de fruit sain et rafraîchissant.',1,'30359c65db8b679c8e1204f63bf5245c4e0a1098.jpg',1),
(22,7,'Lemonade','A sweet and tangy lemon-flavored drink.',1,'dbc0697ea553eba6de5c83cffe3145bccd3e5991.jpg',1),
(23,7,'Eau minérale','Eau pure et rafraîchissante, naturellement riche en minéraux essentiels pour hydrater et revitaliser votre corps. Parfaite pour une consommation quotidienne.',1,'2d9adeb3a61741b9b80928018a60a6ac30a6484c.jpg',1);
/*!40000 ALTER TABLE `plats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_password_request`
--

LOCK TABLES `reset_password_request` WRITE;
/*!40000 ALTER TABLE `reset_password_request` DISABLE KEYS */;
INSERT INTO `reset_password_request` VALUES
(1,57,'xOuWGeXgHppkF2Bukl0u','W+wflAqVBvRki/MUPYOcnFQkw9Ym/3rlNkXiKYmd0yk=','2025-04-03 11:27:57','2025-04-03 12:27:57'),
(2,24,'iAORLoOCP2fKDYxhzdpY','Qvk8nBOer1S1nscg9NI7SU22fSKFixMxvMtyl5Trpdg=','2025-04-03 11:57:38','2025-04-03 12:57:38'),
(3,45,'BDYD8OfoReMe4Bcegz96','0F+IUNMZNOll5PA9PWr/y/c8axsdUcjQefm3mfX85vU=','2025-04-03 12:04:20','2025-04-03 13:04:20'),
(4,42,'QytCRXxY0U9wgwSutUQw','lJsapDYJgFv8LwfaEfZlwQWib8a9EGQPapAjNdBu+Qo=','2025-04-03 12:06:19','2025-04-03 13:06:19'),
(5,26,'7tuCbWppTmK3yPnDsxNr','CSeQyerxHR07Zz9x85mj7aHWDZd3mYTJLbQ+ARvzS10=','2025-04-03 12:10:51','2025-04-03 13:10:51'),
(6,47,'7myrUeMaaGVGBvBSAa63','u0RZ1fnuZrnery4m++uPan7QPiejUcALMS7/IXR5nno=','2025-04-03 12:11:15','2025-04-03 13:11:15'),
(7,49,'930NUeNT1N85CXu194T0','7hvlbPnMRCkVHH04QIQw8ogCCq0TrWlm4o70AzevNF0=','2025-04-03 12:14:35','2025-04-03 13:14:35'),
(8,53,'ERSWjYGbFwHCib3b5WW0','EhHyVJNOIqQNaG3T0ztlB738H2oT7vRPgSgPeoj/cg4=','2025-04-03 12:18:18','2025-04-03 13:18:18'),
(9,52,'E1bure2c7LyaOEHMwIK5','izo89LL0f+2Vof4VmjQhAO4t29DarQex8rkhZ+2vqb4=','2025-04-03 12:19:55','2025-04-03 13:19:55'),
(10,54,'pT0yKhrqsRcE0fD7IsXI','bSQUgb+02U+frq72Eh4XtaU0pZ2fCmCc95I6qz4cc+8=','2025-04-03 12:21:25','2025-04-03 13:21:25'),
(11,56,'nEx180i2C0nJszGVC7Zv','PxX7L1BMNiTUAy4n9g6joa6mx54rFk8qgsN/JoLhpAM=','2025-04-03 12:24:50','2025-04-03 13:24:50'),
(12,32,'K7Yxit2dIpaevk5aiQCa','940pfAyJiq8Q2p35/gEGnsQDCZ51w0KEtAy0sZ10Z5M=','2025-04-03 13:04:26','2025-04-03 14:04:26'),
(13,44,'3oOZcKzKRw3GkQArPzsR','GKPLTKKblYwILFdkmGy3WSyclX5o/YXbdhdECNifHUA=','2025-04-03 13:13:19','2025-04-03 14:13:19');
/*!40000 ALTER TABLE `reset_password_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `user_nom` varchar(100) NOT NULL,
  `addresse` varchar(255) NOT NULL,
  `user_photo` longtext DEFAULT NULL,
  `tel` varchar(20) NOT NULL,
  `cp` int(11) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(22,'user1@example.com','[\"ROLE_ADMIN\"]','$2y$13$WRsEkP3s4lr48rJ7pJQpYe7BOM0jYl2SWSllxBtzwC.1RM7D.Gid.','User 1','Address 515687','1dd9973eaaef84ca7dba5122931c471bd780df49.png','123-456-7891',0,0),
(23,'user2@example.com','[\"ROLE_ADMIN\"]','123','User 2','Address 16','3dec62002b64627c742f5f2d2ff26f2a620c55ed.png','123-456-7892',0,0),
(24,'user3@example.com','[\"ROLE_USER\"]','$2y$13$KQAxwiu1R/yrDin3R/WDROZoKI97lIa4quxh4c3Km21FLTZml0LTS','Hannah','109 rue de fbg 1234456987','5719e514fb305cce7c260f763687ebae8e27e87e.png','123-456-7893',80000,0),
(25,'user4@example.com','[\"ROLE_USER\"]','$2y$13$niNquQGIX0py2fclRVcPeeBi/QuBpHCFPjSWeRR8e1HCGmiMBKkoe','Nasira Naeibi','Address 14','67e3f2e8788ce.png','123-4226-8988',80000,0),
(26,'user5@example.com','[]','$2y$13$Bd3vWSAO.Kry/QLWabkABue2sOIkW5oVZaK6Mvqlwp2XZwhFXjA4m','User 5','Address 88','299d96567acb1d8393ed6d9ca6ef01859e62c546.png','123-456-7895',0,0),
(27,'user6@example.com','[]','$2y$13$lDe3rjF3PJ8wf6Lkznc/MOJOYumsYMpU5Q6eKcklCxMSgVlmzQa0O','User 6','Address 99',NULL,'123-456-7896',0,0),
(28,'user7@example.com','[]','$2y$13$iKE9DaoOIMOPOny4nfo8uOwTQUq7ZMPNsBvBlFOKTB8RZFV.LYF9C','User 7','Address 62',NULL,'123-456-7897',0,0),
(29,'user8@example.com','[]','$2y$13$L9Sl9rvxYu8/Ea4/KpH/4O1ryJmpDjqRgB54v3q.2ygXpiSXoE.C.','User 8','Address 37',NULL,'123-456-7898',0,0),
(30,'user9@example.com','[]','$2y$13$lWcYpUusozJnuSF7gX3Ut.RUfVY4c0oXMyXpvhuJTT32lU920NpJe','User 9','Address 65',NULL,'123-456-7899',0,0),
(31,'user10@example.com','[]','$2y$13$5IJmRKX1JeELMYE0gSX6SuFNnGey3woXi/2F0iFi09ek2lwHcN4h2','User 10','Address 73',NULL,'123-456-78910',0,0),
(32,'user11@example.com','[]','$2y$13$dQk2OOAh6v7aHqE2DYaJZuYNXX4huoFVn76rh3DoYQTQq7BV0WsxO','User 11','Address 18',NULL,'123-456-78911',0,0),
(41,'nasiranaeibi@gmail.com','[]','$2y$13$dheIw6L8lqjIPFU/lggxY.WFiG3L7HNrd2LhdHOFowCQdPo1FgNp2','Nasira Naeibi','44 rue du pr chr cb','67ea823092f46.png','0744221060',80000,0),
(42,'nasira3795@gmail.com','[]','$2y$13$NPfH6hnnp.4xymlG3K847eXSYIeOb.QVLEJDJ3Xynvps5GN/Y1LBy','Nasira Naeibi','109 rue de fbg','67ea8323d0413.png','0744221060',80000,0),
(43,'nasira5@gmail.com','[]','$2y$13$HxB6zyox7M.6vIg670Vz.uC6IgCnkPsSF1555PvUNLk5CmlJ79dtK','Nasira Naeibi','109 rue de fbg','67eb9caa455e8.png','0744221060',80000,0),
(44,'nazari@gmail.com','[]','$2y$13$fNKclK9uLq00uGoNflNexORsd2NVYVDajcnnL99vMsfDzdj5ROVSG','raziqnazari','109 rue de fbg','67eb9defb94a4.png','0744221060',80000,0),
(45,'raziqnazari@gmail.com','[]','$2y$13$XLIN02elOBevRUXcmbC5be2aS8eWkRSwn/2neCfspzaXfIiP3ONhK','raziqnazari','44 rue du pr chr cb','67eb9fcf98aef.png','0744221060',80000,0),
(46,'nasira123456@gmail.com','[]','$2y$13$2r/erIbsfzXvPin4G3qGQ.7jy.SL1UsyZCZNEOU55K.emZj3aksOW','Aliadil','44 rue du pr chr cb','67eba3a96d4be.png','0744221060',80000,0),
(47,'adil@gmail.com','[\"ROLE_USER\"]','$2y$13$YnDbOYL3MMxKN1ZjJj89buAdMK7P/JNR98nHo2CCvFZ1nDxtDGuMm','Aliadil','44 rue du pr chr cb','67eba6c6d10bf.png','0744221060',80000,0),
(48,'hannah@gmail.com','[]','$2y$13$22f1lSGTk1QyObJjguYm3.8QBPQ.SPY5kbkfabE5xke5qsmrZm7ta','hannah','109 rue de fbg','67ebb7913bde9.png','0744221060',80000,0),
(49,'hannah23@gmail.com','[]','$2y$13$97E0kWuIsuzfCE5S.k9jKO9Fo/A04Avg8hdeBAyeAnngutXgWofBe','hannah','44 rue du pr chr cb','67ebb813e74ed.png','0744221060',80000,0),
(50,'nazarihannah@gmail.com','[]','$2y$13$FD3PZVajOLpjPkVy.20KUutvHX/PKSytJc1kqAqjx6fnDbUrpRQ02','hannah','44 rue du pr chr cb','67ebb8bd2590a.png','0744221060',80000,0),
(51,'hannahjhd@gmail.com','[]','$2y$13$ir6R4ugLgF1MI5zx0FEWXufXbPLysIR.1LKojlmaEqpyWjOtiDcZK','hannah nazari','109 rue de fbg','67ebb9e8cbcd8.png','0744221060',80000,0),
(52,'nasira37@gmail.com','[]','$2y$13$tkkUcvWnFhmD5PTIjWCrIO3FNS7Rue6QBOq9qp/zp/CxisDngcQ9S','Nasira Naeibi','44 rue du pr chr cb','67ebbca4a25d9.png','0744221060',80000,0),
(53,'nasira35@gmail.com','[]','$2y$13$mXZwEri5zXP7Ko.SKsZIM.BH6fo4VZCCkQ5AtLrUsWInqsxgWYf2m','raziqnazari','44 rue du pr chr cb','67ebbd2309845.png','0744221060',80000,0),
(54,'sfkjhds@gmail.com','[]','$2y$13$2qjTOPx5SIL1NexQr5NcPuX68pPBqSGpaYDWyNH/krbFYrmXnAhVW','Nasira Naeibi','44 rue du pr chr cb','67ebc3aab0387.png','0744221060',80000,1),
(55,'nasira375@gmail.com','[]','$2y$13$0DI9V/o0NX7p/1SihRueJeTG0tgVsObquC0M7Z6u/9OTsT6.yAEue','Nasira Naeibi','44 rue du pr chr cb','67ebc5e398439.png','0744221060',80000,0),
(56,'hannah4554@gmail.com','[]','$2y$13$zLGOJpcsQPhIMKOl9kgfsekgBOYuZDYs/PwUNOOMhVN.RJhYbFEIO','hannah jan','109 rue de fbg','67ebc63e29ab6.png','0744221060',80000,0),
(57,'naeibinazari@gmail.com','[]','$2y$13$9fLyTstl1XeFhygtW8n5M.uLygtC6DILGOJEWmBrpIjQB.xEAXZim','jasmin','44 rue du pr chr cb','67ebc6d0c935f.png','0744221060',80000,1),
(58,'naeibinazari12@gmail.com','[]','$2y$13$Ha5zDuoMrMZzwoHstW16..0ReWqHMXTPxzbrL.CsjhXa6ijVbXgTC','hannahnazari','44 rue du pr chr cb','67ebc7984e0ab.png','0744221060',80000,1),
(59,'nasi5@gmail.com','[]','$2y$13$cw4kmzRBs6jr6WHwvSj.y.y7UZGbHs9hH8wWedYYRrfV.Y/OnKXXq','Nasira Naeibi','44 rue du pr chr cb','67ee752f8ae95.png','0744221060',80000,1);
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

-- Dump completed on 2025-06-02 10:12:16
