/*
SQLyog Community v12.4.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - db_sepatu
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_sepatu` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_sepatu`;

/*Table structure for table `akun` */

DROP TABLE IF EXISTS `akun`;

CREATE TABLE `akun` (
  `idAkun` int(11) NOT NULL AUTO_INCREMENT,
  `Email_Akun` varchar(100) NOT NULL,
  `Password_Akun` varchar(255) NOT NULL,
  `Customer_IdCustomer` varchar(20) NOT NULL,
  PRIMARY KEY (`idAkun`),
  UNIQUE KEY `Email_Akun` (`Email_Akun`),
  KEY `Customer_IdCustomer` (`Customer_IdCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `akun` */

LOCK TABLES `akun` WRITE;

insert  into `akun`(`idAkun`,`Email_Akun`,`Password_Akun`,`Customer_IdCustomer`) values 
(1,'budi@email.com','$2y$10$Vl6d1Z5p8W7p5Y1U3XQ0.ev2VZ7tT7b1w8b6V7f8d4g5h6j7k8l9m','1'),
(2,'siti@email.com','$2y$10$Vl6d1Z5p8W7p5Y1U3XQ0.ev2VZ7tT7b1w8b6V7f8d4g5h6j7k8l9m','2'),
(3,'agus@email.com','$2y$10$Vl6d1Z5p8W7p5Y1U3XQ0.ev2VZ7tT7b1w8b6V7f8d4g5h6j7k8l9m','3'),
(4,'dewi@email.com','$2y$10$Vl6d1Z5p8W7p5Y1U3XQ0.ev2VZ7tT7b1w8b6V7f8d4g5h6j7k8l9m','4'),
(5,'rudi@email.com','$2y$10$Vl6d1Z5p8W7p5Y1U3XQ0.ev2VZ7tT7b1w8b6V7f8d4g5h6j7k8l9m','5'),
(9,'syahrulpermana665@gmail.com','$2y$10$Ps.5QigN/kdJzDlQBsElaO4FZc3VQbATKoPVNnR6v9s4JzyiNPrLa','CP17494682118942'),
(10,'1@gmail.com','$2y$10$YSn6xzn0xj5LzaSzU/YdMOFtrr5LhcSnDNMB1J0CyvwFjV5vZg/W.','CPC5A7C6DA'),
(11,'Sneak&Treat@gmail.com','$2y$10$cD0GM6s8scNBZF/BIxy31.fGKyIs.HvOVI.HMmzZmm/N8UcQeB2FW','CP0AC0E667');

UNLOCK TABLES;

/*Table structure for table `customer` */

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `IdCustomer` varchar(20) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `Alamat` text DEFAULT NULL,
  `No_Telepon` varchar(15) DEFAULT NULL,
  `Tanggal_Daftar` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`IdCustomer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `customer` */

LOCK TABLES `customer` WRITE;

insert  into `customer`(`IdCustomer`,`Nama`,`Alamat`,`No_Telepon`,`Tanggal_Daftar`) values 
('1','Budi Santoso','Jl. Merdeka No. 123, Jakarta','081234567890','2025-06-09 17:07:49'),
('2','Siti Rahayu','Jl. Sudirman No. 456, Bandung','082345678901','2025-06-09 17:07:49'),
('3','Agus Wijaya','Jl. Gatot Subroto No. 789, Surabaya','083456789012','2025-06-09 17:07:49'),
('4','Dewi Lestari','Jl. Thamrin No. 101, Yogyakarta','084567890123','2025-06-09 17:07:49'),
('5','Rudi Hartono','Jl. Diponegoro No. 202, Semarang','085678901234','2025-06-09 17:07:49'),
('6','',NULL,NULL,'2025-06-09 17:47:44'),
('7','',NULL,NULL,'2025-06-09 18:03:45'),
('91','',NULL,NULL,'2025-06-09 18:17:50'),
('CP0AC0E667','',NULL,NULL,'2025-06-10 13:15:58'),
('CP17494682118942','',NULL,NULL,'2025-06-09 18:23:31'),
('CPC5A7C6DA','Syahrul Permana','jalan raung','087758247862','2025-06-09 18:52:36');

UNLOCK TABLES;

/*Table structure for table `layanan` */

DROP TABLE IF EXISTS `layanan`;

CREATE TABLE `layanan` (
  `idLayanan` int(11) NOT NULL AUTO_INCREMENT,
  `Nama_Layanan` varchar(100) NOT NULL,
  `Harga` decimal(10,2) NOT NULL,
  `Durasi_Pengerjaan` varchar(50) NOT NULL,
  `Durasi_Pengerjaan_express` varchar(50) NOT NULL,
  PRIMARY KEY (`idLayanan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `layanan` */

LOCK TABLES `layanan` WRITE;

insert  into `layanan`(`idLayanan`,`Nama_Layanan`,`Harga`,`Durasi_Pengerjaan`,`Durasi_Pengerjaan_express`) values 
(1,'Reguler (2-3 hari)',30000.00,'2-3 hari','1 hari'),
(2,'Express (1 hari)',50000.00,'1 hari','6 jam'),
(3,'Deep Clean',40000.00,'3-4 hari','2 hari'),
(4,'Unyellowing',40000.00,'3-4 hari','2 hari'),
(5,'Repaint',65000.00,'5-7 hari','3 hari'),
(6,'Reglue',30000.00,'4-5 hari','2 hari');

UNLOCK TABLES;

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `IdPembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `Metode_Pembayaran` varchar(50) NOT NULL,
  `Jumlah_Pembayaran` decimal(10,2) NOT NULL,
  `Status_Pembayaran` enum('Lunas','Pending','Gagal') DEFAULT 'Pending',
  `Tanggal_Pembayaran` datetime DEFAULT current_timestamp(),
  `Pesanan_IdPesanan` int(11) NOT NULL,
  PRIMARY KEY (`IdPembayaran`),
  KEY `Pesanan_IdPesanan` (`Pesanan_IdPesanan`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`Pesanan_IdPesanan`) REFERENCES `pesanan` (`idPesanan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pembayaran` */

LOCK TABLES `pembayaran` WRITE;

insert  into `pembayaran`(`IdPembayaran`,`Metode_Pembayaran`,`Jumlah_Pembayaran`,`Status_Pembayaran`,`Tanggal_Pembayaran`,`Pesanan_IdPesanan`) values 
(1,'Transfer Bank',30000.00,'Lunas','2025-06-09 17:07:49',1),
(2,'E-Wallet',50000.00,'Lunas','2025-06-09 17:07:49',2),
(3,'Tunai',40000.00,'Lunas','2025-06-09 17:07:49',3),
(4,'Kartu Kredit',65000.00,'Gagal','2025-06-09 17:07:49',4),
(5,'Transfer Bank',30000.00,'Pending','2025-06-09 17:07:49',5);

UNLOCK TABLES;

/*Table structure for table `pesanan` */

DROP TABLE IF EXISTS `pesanan`;

CREATE TABLE `pesanan` (
  `idPesanan` int(11) NOT NULL AUTO_INCREMENT,
  `idLayanan` int(11) NOT NULL,
  `Customer_idCustomer` varchar(20) NOT NULL,
  `Tanggal_Pesanan` datetime NOT NULL,
  `Tanggal_Antar` date NOT NULL,
  `Status_Pesanan` enum('Menunggu','Diproses','Selesai','Dibatalkan') DEFAULT 'Menunggu',
  `Total_harga` decimal(10,2) NOT NULL,
  `jenis_sepatu` varchar(50) NOT NULL,
  `Catatan_Khusus` text DEFAULT NULL,
  PRIMARY KEY (`idPesanan`),
  KEY `pesanan_ibfk_1` (`Customer_idCustomer`),
  KEY `pesanan_ibfk_2` (`idLayanan`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`Customer_idCustomer`) REFERENCES `customer` (`IdCustomer`),
  CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`idLayanan`) REFERENCES `layanan` (`idLayanan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pesanan` */

LOCK TABLES `pesanan` WRITE;

insert  into `pesanan`(`idPesanan`,`idLayanan`,`Customer_idCustomer`,`Tanggal_Pesanan`,`Tanggal_Antar`,`Status_Pesanan`,`Total_harga`,`jenis_sepatu`,`Catatan_Khusus`) values 
(1,1,'1','2025-06-10 08:30:00','2025-06-13','Menunggu',30000.00,'Sneakers','Sepatu warna putih, harap dibersihkan dengan hati-hati'),
(2,2,'2','2025-06-10 09:15:00','2025-06-11','Diproses',50000.00,'Canvas','Prioritaskan, diperlukan untuk acara besok'),
(3,3,'3','2025-06-09 14:20:00','2025-06-13','Selesai',40000.00,'Kulit',NULL),
(4,5,'4','2025-06-08 11:45:00','2025-06-15','Dibatalkan',65000.00,'Boots','Warna biru navy, batalkan karena perubahan rencana'),
(5,6,'5','2025-06-10 10:00:00','2025-06-15','Menunggu',30000.00,'Sepatu Anak','Ukuran kecil, motif karakter kartun'),
(8,1,'CPC5A7C6DA','2025-06-10 12:14:41','2025-07-22','Menunggu',30000.00,'Sneakers','tes'),
(9,3,'CPC5A7C6DA','2025-06-10 12:15:34','2025-08-27','Menunggu',40000.00,'Sepatu Anak','tes');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
