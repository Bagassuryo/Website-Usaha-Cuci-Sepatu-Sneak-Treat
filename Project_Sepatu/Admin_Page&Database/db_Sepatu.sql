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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `layanan` */

LOCK TABLES `layanan` WRITE;

insert  into `layanan`(`idLayanan`,`Nama_Layanan`,`Harga`,`Durasi_Pengerjaan`,`Durasi_Pengerjaan_express`) values 

(7,'Deep Clean',30000.00,'2-3 Hari','1 Hari');

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
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`Pesanan_IdPesanan`) REFERENCES `pesanan` (`idPesanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pembayaran` */

LOCK TABLES `pembayaran` WRITE;

UNLOCK TABLES;

/*Table structure for table `pesanan` */

DROP TABLE IF EXISTS `pesanan`;

CREATE TABLE `pesanan` (
  `idPesanan` int(11) NOT NULL AUTO_INCREMENT,
  `idLayanan` int(11) NOT NULL,
  `Customer_idCustomer` varchar(20) NOT NULL,
  `IdPembayaran` int(11) DEFAULT NULL,
  `Tanggal_Pesanan` datetime NOT NULL,
  `Tanggal_Antar` date NOT NULL,
  `Status_Pesanan` enum('Menunggu','Diproses','Selesai','Dibatalkan') DEFAULT 'Menunggu',
  `Total_harga` decimal(10,2) NOT NULL,
  `jenis_sepatu` varchar(50) NOT NULL,
  `Catatan_Khusus` text DEFAULT NULL,
  PRIMARY KEY (`idPesanan`),
  KEY `pesanan_ibfk_1` (`Customer_idCustomer`),
  KEY `pesanan_ibfk_2` (`idLayanan`),
  KEY `pesanan_ibfk_3` (`IdPembayaran`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`Customer_idCustomer`) REFERENCES `customer` (`IdCustomer`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`idLayanan`) REFERENCES `layanan` (`idLayanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pesanan_ibfk_3` FOREIGN KEY (`IdPembayaran`) REFERENCES `pembayaran` (`IdPembayaran`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pesanan` */

LOCK TABLES `pesanan` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
