/*
SQLyog Community v12.2.6 (64 bit)
MySQL - 5.7.11-log : Database - peringkat_pt
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `tb_nilai` */

DROP TABLE IF EXISTS `tb_nilai`;

CREATE TABLE `tb_nilai` (
  `thn` int(4) NOT NULL,
  `kd_pt` varchar(6) NOT NULL,
  `n_sdm` float DEFAULT NULL,
  `n_manajemen` float DEFAULT NULL,
  `n_mhs` float DEFAULT NULL,
  `n_pen_pub` float DEFAULT NULL,
  `skor` float DEFAULT NULL,
  `peringkat` int(11) DEFAULT NULL,
  `cluster` int(11) DEFAULT NULL,
  `t_entry` datetime DEFAULT NULL,
  PRIMARY KEY (`thn`,`kd_pt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_nilai` */

/*Table structure for table `tb_pt` */

DROP TABLE IF EXISTS `tb_pt`;

CREATE TABLE `tb_pt` (
  `kd_pt` varchar(6) NOT NULL,
  `nm_pt` varchar(255) DEFAULT NULL,
  `t_entry` datetime DEFAULT NULL,
  PRIMARY KEY (`kd_pt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_pt` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
