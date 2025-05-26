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

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `nama_admin` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `admin` */

LOCK TABLES `admin` WRITE;

insert  into `admin`(`id_admin`,`nama_admin`,`email`,`password`) values 

(1,'Admin Utama','admin@example.com','password123');

UNLOCK TABLES;

/*Table structure for table `akun` */

DROP TABLE IF EXISTS `akun`;

CREATE TABLE `akun` (
  `idAkun` int(11) NOT NULL AUTO_INCREMENT,
  `Email_Akun` varchar(100) NOT NULL,
  `Password_Akun` varchar(255) NOT NULL,
  `Customer_idCustomer` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAkun`),
  UNIQUE KEY `Email_Akun` (`Email_Akun`),
  KEY `Customer_idCustomer` (`Customer_idCustomer`),
  CONSTRAINT `akun_ibfk_1` FOREIGN KEY (`Customer_idCustomer`) REFERENCES `customer` (`idCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `akun` */

LOCK TABLES `akun` WRITE;

insert  into `akun`(`idAkun`,`Email_Akun`,`Password_Akun`,`Customer_idCustomer`) values 

(1,'andi@email.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1),

(2,'budi@email.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',2),

(3,'citra@email.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',3),

(4,'dian@email.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',4),

(5,'eko@email.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',5);

UNLOCK TABLES;

/*Table structure for table `customer` */

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `idCustomer` int(11) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(100) NOT NULL,
  `Alamat` text DEFAULT NULL,
  `Kode_Unik` varchar(10) DEFAULT NULL,
  `No_Telepon` varchar(15) DEFAULT NULL,
  `Tanggal_Daftar` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `customer` */

LOCK TABLES `customer` WRITE;

insert  into `customer`(`idCustomer`,`Nama`,`Alamat`,`Kode_Unik`,`No_Telepon`,`Tanggal_Daftar`) values 

(1,'Andi Wijaya','Jl. Merdeka No. 10, Jakarta','CUST001','08123456789','2023-01-15 10:00:00'),

(2,'Budi Santoso','Jl. Sudirman No. 25, Bandung','CUST002','08234567890','2023-02-20 11:30:00'),

(3,'Citra Dewi','Jl. Gatot Subroto No. 5, Surabaya','CUST003','08345678901','2023-03-10 09:15:00'),

(4,'Dian Pratama','Jl. Thamrin No. 15, Medan','CUST004','08456789012','2023-04-05 14:20:00'),

(5,'Eko Nugroho','Jl. Diponegoro No. 30, Yogyakarta','CUST005','08567890123','2023-05-12 16:45:00');

UNLOCK TABLES;

/*Table structure for table `layanan` */

DROP TABLE IF EXISTS `layanan`;

CREATE TABLE `layanan` (
  `idLayanan` int(11) NOT NULL AUTO_INCREMENT,
  `Nama_Layanan` varchar(100) NOT NULL,
  `Harga` decimal(10,2) NOT NULL,
  `Durasi_Pengerjaan` varchar(255) DEFAULT NULL,
  `Durasi_Pengerjaan_express` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idLayanan`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `layanan` */

LOCK TABLES `layanan` WRITE;

insert  into `layanan`(`idLayanan`,`Nama_Layanan`,`Harga`,`Durasi_Pengerjaan`,`Durasi_Pengerjaan_express`) values 

(1,'Cuci Biasa',50000.00,'1 Hari','2-3 hari'),

(2,'Cuci Premium',80000.00,'2 Hari','3-4 hari'),

(3,'Cuci Express',100000.00,'1 Hari','1-2 hari'),

(4,'Repaint',150000.00,'5 hari','4-5 hari'),

(5,'Waterproofing',120000.00,'2 hari','2 hari');

UNLOCK TABLES;

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `idPembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `Metode_Pembayaran` varchar(50) DEFAULT NULL,
  `Jumlah_Pembayaran` decimal(10,2) DEFAULT NULL,
  `Status_Pembayaran` enum('Menunggu','Lunas','Gagal') DEFAULT NULL,
  `Tanggal_Pembayaran` datetime DEFAULT NULL,
  `Pesanan_idPesanan` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPembayaran`),
  KEY `fk_pembayaran_pesanan` (`Pesanan_idPesanan`),
  CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`Pesanan_idPesanan`) REFERENCES `pesanan` (`idPesanan`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`Pesanan_idPesanan`) REFERENCES `pesanan` (`idPesanan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pembayaran` */

LOCK TABLES `pembayaran` WRITE;

insert  into `pembayaran`(`idPembayaran`,`Metode_Pembayaran`,`Jumlah_Pembayaran`,`Status_Pembayaran`,`Tanggal_Pembayaran`,`Pesanan_idPesanan`) values 

(1,'Transfer Bank',130000.00,'Lunas','2023-06-01 11:00:00',1),

(2,'E-Wallet',180000.00,'Lunas','2023-06-02 14:30:00',2),

(4,'Kartu Kredit',230000.00,'Lunas','2023-06-04 17:00:00',4);

UNLOCK TABLES;

/*Table structure for table `pesanan` */

DROP TABLE IF EXISTS `pesanan`;

CREATE TABLE `pesanan` (
  `idPesanan` int(11) NOT NULL AUTO_INCREMENT,
  `Tanggal_Pesanan` datetime DEFAULT current_timestamp(),
  `Status_Pesanan` enum('Menunggu','Diproses','Selesai','Dibatalkan') DEFAULT NULL,
  `Total_Harga` decimal(10,2) DEFAULT NULL,
  `Catatan_Khusus` text DEFAULT NULL,
  `Customer_idCustomer` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPesanan`),
  KEY `fk_customer_pesanan` (`Customer_idCustomer`),
  CONSTRAINT `fk_customer_pesanan` FOREIGN KEY (`Customer_idCustomer`) REFERENCES `customer` (`idCustomer`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`Customer_idCustomer`) REFERENCES `customer` (`idCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pesanan` */

LOCK TABLES `pesanan` WRITE;

insert  into `pesanan`(`idPesanan`,`Tanggal_Pesanan`,`Status_Pesanan`,`Total_Harga`,`Catatan_Khusus`,`Customer_idCustomer`) values 

(1,'2023-06-01 10:30:00','Selesai',130000.00,'Harap dikeringkan dengan baik',1),

(2,'2023-06-02 14:15:00','Diproses',180000.00,'Warna merah jangan luntur',3),

(4,'2023-06-04 16:45:00','Selesai',230000.00,'Perawatan khusus untuk kulit',5);

UNLOCK TABLES;

/*Table structure for table `sepatu` */

DROP TABLE IF EXISTS `sepatu`;

CREATE TABLE `sepatu` (
  `idSepatu` int(11) NOT NULL AUTO_INCREMENT,
  `Jenis_Sepatu` varchar(45) DEFAULT NULL,
  `Merk_Sepatu` varchar(45) DEFAULT NULL,
  `Warna_Sepatu` varchar(30) DEFAULT NULL,
  `Ukuran_Sepatu` varchar(10) DEFAULT NULL,
  `Customer_idCustomer` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSepatu`),
  KEY `Customer_idCustomer` (`Customer_idCustomer`),
  CONSTRAINT `sepatu_ibfk_1` FOREIGN KEY (`Customer_idCustomer`) REFERENCES `customer` (`idCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `sepatu` */

LOCK TABLES `sepatu` WRITE;

insert  into `sepatu`(`idSepatu`,`Jenis_Sepatu`,`Merk_Sepatu`,`Warna_Sepatu`,`Ukuran_Sepatu`,`Customer_idCustomer`) values 

(1,'Sneakers','Nike','Putih','42',1),

(2,'Running','Adidas','Hitam','40',1),

(3,'Casual','Converse','Biru','39',2),

(4,'Basket','Air Jordan','Merah','45',3),

(5,'Sport','Puma','Abu-abu','41',4),

(6,'Boots','Dr. Martens','Coklat','43',5),

(7,'Slip-on','Vans','Hitam','38',2);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
