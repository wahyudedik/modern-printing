-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table modern-printing.alats
CREATE TABLE IF NOT EXISTS `alats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `nama_alat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spesifikasi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','maintenance','rusak') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pembelian` date NOT NULL,
  `kapasitas_cetak_per_jam` int NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alats_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `alats_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.alats: ~4 rows (approximately)
REPLACE INTO `alats` (`id`, `vendor_id`, `nama_alat`, `merk`, `model`, `spesifikasi`, `status`, `tanggal_pembelian`, `kapasitas_cetak_per_jam`, `keterangan`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Consequatur consequatur et odit consectetur nesciunt tempore aliquam', 'Officiis deleniti dolore consectetur obcaecati eos et commodo ab consequat Minus qui et labore numquam sit maiores in magna sed', 'Reprehenderit deserunt iusto quisquam in', 'Ullam quis non paria', 'aktif', '2024-02-23', 13, 'Excepturi voluptate ', '2024-12-04 12:31:52', '2024-12-04 12:31:52'),
	(2, 1, 'Iure minima ut hic recusandae', 'Ea dolorem error facilis aute ea culpa sed excepturi aperiam eum commodo labore sit et non enim vero non', 'Quas quia blanditiis ad laboriosam eaque ex natus deleniti aperiam non voluptatem esse quaerat dolorum nostrud veritatis porro', 'Illum consequuntur ', 'aktif', '1980-03-09', 17, 'Repudiandae dolor ma', '2024-12-04 12:32:03', '2024-12-04 12:32:03'),
	(3, 1, 'Beatae porro sunt id quaerat reiciendis fugit recusandae Est eveniet vel ad ex amet quia veniam', 'Id anim explicabo Excepturi adipisci', 'Aute officiis aperiam libero nemo tempor vero ipsum laborum Ex veniam culpa nihil', 'Iusto culpa dolorum ', 'rusak', '1986-02-13', 58, 'Numquam duis amet p', '2024-12-04 12:32:10', '2024-12-04 12:32:10'),
	(4, 1, 'Cillum molestiae hic illo pariatur', 'Aliquam accusamus excepteur aut nostrud exercitation dolore placeat ipsum porro velit tenetur optio temporibus', 'Dolore itaque duis vel sed quidem ex dolor est corporis earum dolore suscipit omnis voluptatibus', 'Quod esse nemo volup', 'maintenance', '1984-04-24', 87, 'Id explicabo Porro ', '2024-12-04 12:32:16', '2024-12-04 12:32:16');

-- Dumping structure for table modern-printing.bahans
CREATE TABLE IF NOT EXISTS `bahans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `nama_bahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `spesifikasi` json DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bahans_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `bahans_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.bahans: ~2 rows (approximately)
REPLACE INTO `bahans` (`id`, `vendor_id`, `nama_bahan`, `deskripsi`, `spesifikasi`, `supplier`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Explicabo Molestias dolore sed placeat cumque mollit tempore non veniam quas inventore qui ipsum reprehenderit aspernatur est', 'Architecto ea anim asperiores in asperiores qui ex esse ex similique quis soluta qui porro magna voluptatum id odit', '{"Ad doloremque vel ad": "Nulla et velit enim "}', 'Ut repudiandae est eum elit qui sed repudiandae ratione', 1, '2024-12-04 12:31:23', '2024-12-04 12:31:23'),
	(2, 1, 'Sed facere voluptatibus ex ut quibusdam ullam est exercitationem ipsum consectetur recusandae Molestiae est asperiores esse vitae eveniet cupidatat aliquid', 'Ut vel adipisicing vel aut quis laboris porro est', '{"Ea sint voluptatem": "Ut dolor placeat re"}', 'Vel incididunt repudiandae aperiam fugiat', 1, '2024-12-04 12:31:39', '2024-12-04 12:31:39');

-- Dumping structure for table modern-printing.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.cache: ~7 rows (approximately)
REPLACE INTO `cache` (`key`, `value`, `expiration`) VALUES
	('356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1733692736),
	('356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1733692736;', 1733692736),
	('a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1733817056),
	('a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1733817056;', 1733817056),
	('spatie.permission.cache', 'a:3:{s:5:"alias";a:5:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";s:1:"j";s:9:"vendor_id";}s:11:"permissions";a:85:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:9:"view_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:13:"view_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:11:"create_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:11:"update_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:12:"restore_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:16:"restore_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:14:"replicate_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:12:"reorder_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:11:"delete_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:15:"delete_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:17:"force_delete_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:11;a:4:{s:1:"a";i:12;s:1:"b";s:21:"force_delete_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:12;a:4:{s:1:"a";i:13;s:1:"b";s:10:"view_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:13;a:4:{s:1:"a";i:14;s:1:"b";s:14:"view_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:14;a:4:{s:1:"a";i:15;s:1:"b";s:12:"create_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:15;a:4:{s:1:"a";i:16;s:1:"b";s:12:"update_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:16;a:4:{s:1:"a";i:17;s:1:"b";s:13:"restore_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:17;a:4:{s:1:"a";i:18;s:1:"b";s:17:"restore_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:18;a:4:{s:1:"a";i:19;s:1:"b";s:15:"replicate_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:19;a:4:{s:1:"a";i:20;s:1:"b";s:13:"reorder_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:20;a:4:{s:1:"a";i:21;s:1:"b";s:12:"delete_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:21;a:4:{s:1:"a";i:22;s:1:"b";s:16:"delete_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:22;a:4:{s:1:"a";i:23;s:1:"b";s:18:"force_delete_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:23;a:4:{s:1:"a";i:24;s:1:"b";s:22:"force_delete_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:24;a:4:{s:1:"a";i:25;s:1:"b";s:14:"view_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:25;a:4:{s:1:"a";i:26;s:1:"b";s:18:"view_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:26;a:4:{s:1:"a";i:27;s:1:"b";s:16:"create_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:27;a:4:{s:1:"a";i:28;s:1:"b";s:16:"update_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:28;a:4:{s:1:"a";i:29;s:1:"b";s:17:"restore_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:29;a:4:{s:1:"a";i:30;s:1:"b";s:21:"restore_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:30;a:4:{s:1:"a";i:31;s:1:"b";s:19:"replicate_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:31;a:4:{s:1:"a";i:32;s:1:"b";s:17:"reorder_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:32;a:4:{s:1:"a";i:33;s:1:"b";s:16:"delete_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:33;a:4:{s:1:"a";i:34;s:1:"b";s:20:"delete_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:34;a:4:{s:1:"a";i:35;s:1:"b";s:22:"force_delete_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:35;a:4:{s:1:"a";i:36;s:1:"b";s:26:"force_delete_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:36;a:4:{s:1:"a";i:37;s:1:"b";s:11:"view_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:37;a:4:{s:1:"a";i:38;s:1:"b";s:15:"view_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:38;a:4:{s:1:"a";i:39;s:1:"b";s:13:"create_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:39;a:4:{s:1:"a";i:40;s:1:"b";s:13:"update_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:40;a:4:{s:1:"a";i:41;s:1:"b";s:14:"restore_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:41;a:4:{s:1:"a";i:42;s:1:"b";s:18:"restore_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:42;a:4:{s:1:"a";i:43;s:1:"b";s:16:"replicate_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:43;a:4:{s:1:"a";i:44;s:1:"b";s:14:"reorder_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:44;a:4:{s:1:"a";i:45;s:1:"b";s:13:"delete_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:45;a:4:{s:1:"a";i:46;s:1:"b";s:17:"delete_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:46;a:4:{s:1:"a";i:47;s:1:"b";s:19:"force_delete_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:47;a:4:{s:1:"a";i:48;s:1:"b";s:23:"force_delete_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:48;a:4:{s:1:"a";i:49;s:1:"b";s:9:"view_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:49;a:4:{s:1:"a";i:50;s:1:"b";s:13:"view_any_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:50;a:4:{s:1:"a";i:51;s:1:"b";s:11:"create_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:51;a:4:{s:1:"a";i:52;s:1:"b";s:11:"update_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:52;a:4:{s:1:"a";i:53;s:1:"b";s:11:"delete_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:53;a:4:{s:1:"a";i:54;s:1:"b";s:15:"delete_any_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:54;a:4:{s:1:"a";i:55;s:1:"b";s:9:"view_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:55;a:4:{s:1:"a";i:56;s:1:"b";s:13:"view_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:56;a:4:{s:1:"a";i:57;s:1:"b";s:11:"create_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:57;a:4:{s:1:"a";i:58;s:1:"b";s:11:"update_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:58;a:4:{s:1:"a";i:59;s:1:"b";s:12:"restore_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:59;a:4:{s:1:"a";i:60;s:1:"b";s:16:"restore_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:60;a:4:{s:1:"a";i:61;s:1:"b";s:14:"replicate_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:61;a:4:{s:1:"a";i:62;s:1:"b";s:12:"reorder_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:62;a:4:{s:1:"a";i:63;s:1:"b";s:11:"delete_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:63;a:4:{s:1:"a";i:64;s:1:"b";s:15:"delete_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:64;a:4:{s:1:"a";i:65;s:1:"b";s:17:"force_delete_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:65;a:4:{s:1:"a";i:66;s:1:"b";s:21:"force_delete_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:66;a:4:{s:1:"a";i:67;s:1:"b";s:16:"page_PointOfSale";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:67;a:4:{s:1:"a";i:68;s:1:"b";s:11:"page_Themes";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:68;a:4:{s:1:"a";i:69;s:1:"b";s:14:"view_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:69;a:4:{s:1:"a";i:70;s:1:"b";s:18:"view_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:70;a:4:{s:1:"a";i:71;s:1:"b";s:16:"create_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:71;a:4:{s:1:"a";i:72;s:1:"b";s:16:"update_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:72;a:4:{s:1:"a";i:73;s:1:"b";s:17:"restore_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:73;a:4:{s:1:"a";i:74;s:1:"b";s:21:"restore_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:74;a:4:{s:1:"a";i:75;s:1:"b";s:19:"replicate_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:75;a:4:{s:1:"a";i:76;s:1:"b";s:17:"reorder_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:76;a:4:{s:1:"a";i:77;s:1:"b";s:16:"delete_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:77;a:4:{s:1:"a";i:78;s:1:"b";s:20:"delete_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:78;a:4:{s:1:"a";i:79;s:1:"b";s:22:"force_delete_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:79;a:4:{s:1:"a";i:80;s:1:"b";s:26:"force_delete_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:80;a:4:{s:1:"a";i:81;s:1:"b";s:12:"page_Laporan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:81;a:4:{s:1:"a";i:82;s:1:"b";s:8:"page_Pos";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:82;a:4:{s:1:"a";i:83;s:1:"b";s:29:"widget_DashboardStatsOverview";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:83;a:4:{s:1:"a";i:84;s:1:"b";s:30:"widget_DashboardTransaksiChart";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:84;a:4:{s:1:"a";i:85;s:1:"b";s:27:"widget_DashboardProdukChart";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}}s:5:"roles";a:1:{i:0;a:4:{s:1:"j";N;s:1:"a";i:1;s:1:"b";s:11:"super_admin";s:1:"c";s:3:"web";}}}', 1733904496),
	('theme', 's:6:"sunset";', 2048779207),
	('theme_color', 's:6:"indigo";', 2048779245);

-- Dumping structure for table modern-printing.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.cache_locks: ~0 rows (approximately)

-- Dumping structure for table modern-printing.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Dumping data for table modern-printing.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table modern-printing.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
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

-- Dumping data for table modern-printing.jobs: ~0 rows (approximately)

-- Dumping structure for table modern-printing.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
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

-- Dumping data for table modern-printing.job_batches: ~0 rows (approximately)

-- Dumping structure for table modern-printing.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.migrations: ~15 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2024_11_24_205045_create_vendors_table', 1),
	(5, '2024_11_24_232157_create_vendor_activities_table', 1),
	(6, '2024_12_02_233239_create_permission_tables', 1),
	(7, '2024_12_03_153836_add_themes_settings_to_users_table', 1),
	(8, '2024_12_03_155801_create_bahans_table', 1),
	(9, '2024_12_03_155819_create_alats_table', 1),
	(21, '2024_12_04_042349_create_produks_table', 2),
	(22, '2024_12_04_192529_create_produk_bahan_table', 2),
	(23, '2024_12_04_192611_create_produk_alat_table', 2),
	(56, '2024_12_06_000036_create_pelanggans_table', 3),
	(60, '2024_12_06_031123_create_transaksis_table', 4),
	(61, '2024_12_06_140315_create_transaksi_produk_table', 4),
	(62, '2024_12_06_140316_create_transaksi_pelanggan_table', 4);

-- Dumping structure for table modern-printing.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table modern-printing.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.model_has_roles: ~0 rows (approximately)
REPLACE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1);

-- Dumping structure for table modern-printing.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table modern-printing.pelanggans
CREATE TABLE IF NOT EXISTS `pelanggans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaksi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pelanggans_kode_unique` (`kode`),
  KEY `pelanggans_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `pelanggans_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.pelanggans: ~27 rows (approximately)
REPLACE INTO `pelanggans` (`id`, `vendor_id`, `kode`, `nama`, `alamat`, `no_telp`, `email`, `transaksi_terakhir`, `created_at`, `updated_at`) VALUES
	(1, 1, 'PLG-20241209052704-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 22:27:04', '2024-12-08 22:27:04', '2024-12-08 22:27:04'),
	(2, 1, 'PLG-20241209053434-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 22:34:34', '2024-12-08 22:34:34', '2024-12-08 22:34:34'),
	(3, 1, 'PLG-20241209053545-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 22:35:45', '2024-12-08 22:35:45', '2024-12-08 22:35:45'),
	(4, 1, 'PLG-20241209053619-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 22:36:19', '2024-12-08 22:36:19', '2024-12-08 22:36:19'),
	(5, 1, 'PLG-20241209054319-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 22:43:19', '2024-12-08 22:43:19', '2024-12-08 22:43:19'),
	(6, 1, 'PLG-20241209054440-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 22:44:40', '2024-12-08 22:44:40', '2024-12-08 22:44:40'),
	(7, 1, 'PLG-20241209055545-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 22:55:45', '2024-12-08 22:55:45', '2024-12-08 22:55:45'),
	(8, 1, 'PLG-20241209055844-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 14:52:00', '2024-12-08 22:58:44', '2024-12-09 14:52:00'),
	(9, 1, 'PLG-20241209055944-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'fdsfsdf@gmail.com', '2024-12-08 22:59:44', '2024-12-08 22:59:44', '2024-12-08 22:59:44'),
	(10, 1, 'PLG-20241209060310-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 23:03:10', '2024-12-08 23:03:10', '2024-12-08 23:03:10'),
	(11, 1, 'PLG-20241209060447-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 23:04:47', '2024-12-08 23:04:47', '2024-12-08 23:04:47'),
	(12, 1, 'PLG-20241209060713-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 23:07:13', '2024-12-08 23:07:13', '2024-12-08 23:07:13'),
	(13, 1, 'PLG-20241209061029-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-10 03:38:35', '2024-12-08 23:10:29', '2024-12-10 03:38:35'),
	(14, 1, 'PLG-20241209061155-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 15:08:10', '2024-12-08 23:11:55', '2024-12-09 15:08:10'),
	(15, 1, 'PLG-20241209061213-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 15:20:53', '2024-12-08 23:12:13', '2024-12-09 15:20:53'),
	(16, 1, 'PLG-20241209061300-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 15:20:01', '2024-12-08 23:13:00', '2024-12-09 15:20:01'),
	(17, 1, 'PLG-20241209061409-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 15:13:40', '2024-12-08 23:14:09', '2024-12-09 15:13:40'),
	(18, 1, 'PLG-20241209061519-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 15:25:07', '2024-12-08 23:15:19', '2024-12-09 15:25:07'),
	(19, 1, 'PLG-20241209061612-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 15:05:31', '2024-12-08 23:16:12', '2024-12-09 15:05:31'),
	(20, 1, 'PLG-20241209061650-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 23:16:50', '2024-12-08 23:16:50', '2024-12-08 23:16:50'),
	(21, 1, 'PLG-20241209061740-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 23:17:40', '2024-12-08 23:17:40', '2024-12-08 23:17:40'),
	(22, 1, 'PLG-20241209062157-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 23:21:57', '2024-12-08 23:21:57', '2024-12-08 23:21:57'),
	(23, 1, 'PLG-20241209062314-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-08 23:23:14', '2024-12-08 23:23:14', '2024-12-08 23:23:14'),
	(24, 1, 'PLG-20241209213532-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 14:35:32', '2024-12-09 14:35:32', '2024-12-09 14:35:32'),
	(25, 1, 'PLG-20241209213646-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 15:02:01', '2024-12-09 14:36:46', '2024-12-09 15:02:01'),
	(26, 1, 'PLG-20241209213648-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 14:36:48', '2024-12-09 14:36:48', '2024-12-09 14:36:48'),
	(28, 1, 'PLG-20241209213719-1', 'Wahyu Dedik Dwi Astono', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '424234234234', 'admin@gmail.com', '2024-12-09 14:37:19', '2024-12-09 14:37:19', '2024-12-09 14:37:19');

-- Dumping structure for table modern-printing.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.permissions: ~85 rows (approximately)
REPLACE INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(2, 'view_any_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(3, 'create_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(4, 'update_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(5, 'restore_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(6, 'restore_any_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(7, 'replicate_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(8, 'reorder_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(9, 'delete_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(10, 'delete_any_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(11, 'force_delete_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(12, 'force_delete_any_alat', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(13, 'view_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(14, 'view_any_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(15, 'create_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(16, 'update_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(17, 'restore_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(18, 'restore_any_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(19, 'replicate_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(20, 'reorder_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(21, 'delete_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(22, 'delete_any_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(23, 'force_delete_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(24, 'force_delete_any_bahan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(25, 'view_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(26, 'view_any_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(27, 'create_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(28, 'update_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(29, 'restore_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(30, 'restore_any_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(31, 'replicate_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(32, 'reorder_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(33, 'delete_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(34, 'delete_any_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(35, 'force_delete_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(36, 'force_delete_any_pelanggan', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(37, 'view_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(38, 'view_any_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(39, 'create_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(40, 'update_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(41, 'restore_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(42, 'restore_any_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(43, 'replicate_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(44, 'reorder_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(45, 'delete_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(46, 'delete_any_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(47, 'force_delete_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(48, 'force_delete_any_produk', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(49, 'view_role', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(50, 'view_any_role', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(51, 'create_role', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(52, 'update_role', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(53, 'delete_role', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(54, 'delete_any_role', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(55, 'view_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(56, 'view_any_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(57, 'create_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(58, 'update_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(59, 'restore_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(60, 'restore_any_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(61, 'replicate_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(62, 'reorder_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(63, 'delete_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(64, 'delete_any_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(65, 'force_delete_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(66, 'force_delete_any_user', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(67, 'page_PointOfSale', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(68, 'page_Themes', 'web', '2024-12-05 17:15:48', '2024-12-05 17:15:48'),
	(69, 'view_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(70, 'view_any_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(71, 'create_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(72, 'update_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(73, 'restore_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(74, 'restore_any_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(75, 'replicate_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(76, 'reorder_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(77, 'delete_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(78, 'delete_any_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(79, 'force_delete_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(80, 'force_delete_any_transaksi', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(81, 'page_Laporan', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(82, 'page_Pos', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(83, 'widget_DashboardStatsOverview', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(84, 'widget_DashboardTransaksiChart', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24'),
	(85, 'widget_DashboardProdukChart', 'web', '2024-12-10 08:00:24', '2024-12-10 08:00:24');

-- Dumping structure for table modern-printing.produks
CREATE TABLE IF NOT EXISTS `produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `gambar` json DEFAULT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bahan` json DEFAULT NULL,
  `alat` json DEFAULT NULL,
  `harga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diskon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimal_qty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_harga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produks_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `produks_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.produks: ~3 rows (approximately)
REPLACE INTO `produks` (`id`, `vendor_id`, `gambar`, `nama_produk`, `slug`, `deskripsi`, `kategori`, `bahan`, `alat`, `harga`, `diskon`, `minimal_qty`, `total_harga`, `created_at`, `updated_at`) VALUES
	(1, 1, '["produk-images/01JEC8KZGWZPNCW425HGW1CDRW.jpg", "produk-images/01JEC8KZGZK1T554QRFNHJFG0A.png"]', 'Consequatur Quis veniam cum rerum quae sed dolor officia est ut unde eum ratione', 'consequatur-quis-veniam-cum-rerum-quae-sed-dolor-officia-est-ut-unde-eum-ratione', '<p>Ab sit sunt, vero di.</p>', 'Iste at ea cupidatat maxime fugit dolorem amet obcaecati nulla eum ut nostrud totam culpa', NULL, NULL, '19', '18', '794', '1', '2024-12-05 20:36:40', '2024-12-08 21:58:39'),
	(2, 1, '["produk-images/01JEM1886SX4N6HK0068C23WX4.PNG"]', 'Non commodo qui consequatur incidunt distinctio Aperiam laboriosam ut sequi consequatur nihil reprehenderit voluptatibus reprehenderit consequuntur', 'non-commodo-qui-consequatur-incidunt-distinctio-aperiam-laboriosam-ut-sequi-consequatur-nihil-reprehenderit-voluptatibus-reprehenderit-consequuntur', '<p>Ipsum, lorem qui exp.</p>', 'Aspernatur fugiat aut distinctio Nulla sit tempora consequuntur vel est ratione eiusmod nesciunt aspernatur cupidatat', NULL, NULL, '320', '319', '250', '1', '2024-12-08 21:01:51', '2024-12-08 22:11:28'),
	(3, 1, '["produk-images/01JEM2651JF9CH0PN3A5S3NK4R.png"]', 'Ut Nam esse consequatur incididunt sit occaecat sed', 'ut-nam-esse-consequatur-incididunt-sit-occaecat-sed', '<p>Similique labore ess.</p>', 'Quia et commodo dolores sed dolorem eius eum dolore qui ea suscipit et est quasi fugiat', NULL, NULL, '94', '93', '527', '1', '2024-12-08 21:18:11', '2024-12-08 22:11:55');

-- Dumping structure for table modern-printing.produk_alat
CREATE TABLE IF NOT EXISTS `produk_alat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `produk_id` bigint unsigned NOT NULL,
  `alat_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produk_alat_produk_id_foreign` (`produk_id`),
  KEY `produk_alat_alat_id_foreign` (`alat_id`),
  CONSTRAINT `produk_alat_alat_id_foreign` FOREIGN KEY (`alat_id`) REFERENCES `alats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produk_alat_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.produk_alat: ~9 rows (approximately)
REPLACE INTO `produk_alat` (`id`, `produk_id`, `alat_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 4, NULL, NULL),
	(2, 1, 3, NULL, NULL),
	(3, 1, 2, NULL, NULL),
	(4, 2, 3, NULL, NULL),
	(5, 2, 4, NULL, NULL),
	(6, 2, 2, NULL, NULL),
	(7, 2, 1, NULL, NULL),
	(8, 3, 3, NULL, NULL),
	(9, 3, 4, NULL, NULL),
	(10, 3, 2, NULL, NULL),
	(11, 3, 1, NULL, NULL);

-- Dumping structure for table modern-printing.produk_bahan
CREATE TABLE IF NOT EXISTS `produk_bahan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `produk_id` bigint unsigned NOT NULL,
  `bahan_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produk_bahan_produk_id_foreign` (`produk_id`),
  KEY `produk_bahan_bahan_id_foreign` (`bahan_id`),
  CONSTRAINT `produk_bahan_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produk_bahan_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.produk_bahan: ~3 rows (approximately)
REPLACE INTO `produk_bahan` (`id`, `produk_id`, `bahan_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, NULL),
	(2, 2, 1, NULL, NULL),
	(3, 3, 1, NULL, NULL),
	(4, 3, 2, NULL, NULL);

-- Dumping structure for table modern-printing.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `vendor_id` bigint unsigned DEFAULT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`),
  KEY `roles_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `roles_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.roles: ~0 rows (approximately)
REPLACE INTO `roles` (`vendor_id`, `id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(NULL, 1, 'super_admin', 'web', '2024-12-04 12:32:43', '2024-12-04 12:32:43');

-- Dumping structure for table modern-printing.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.role_has_permissions: ~85 rows (approximately)
REPLACE INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(26, 1),
	(27, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(31, 1),
	(32, 1),
	(33, 1),
	(34, 1),
	(35, 1),
	(36, 1),
	(37, 1),
	(38, 1),
	(39, 1),
	(40, 1),
	(41, 1),
	(42, 1),
	(43, 1),
	(44, 1),
	(45, 1),
	(46, 1),
	(47, 1),
	(48, 1),
	(49, 1),
	(50, 1),
	(51, 1),
	(52, 1),
	(53, 1),
	(54, 1),
	(55, 1),
	(56, 1),
	(57, 1),
	(58, 1),
	(59, 1),
	(60, 1),
	(61, 1),
	(62, 1),
	(63, 1),
	(64, 1),
	(65, 1),
	(66, 1),
	(67, 1),
	(68, 1),
	(69, 1),
	(70, 1),
	(71, 1),
	(72, 1),
	(73, 1),
	(74, 1),
	(75, 1),
	(76, 1),
	(77, 1),
	(78, 1),
	(79, 1),
	(80, 1),
	(81, 1),
	(82, 1),
	(83, 1),
	(84, 1),
	(85, 1);

-- Dumping structure for table modern-printing.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
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

-- Dumping data for table modern-printing.sessions: ~1 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('2Zb5BmVjnOerItYib3OMV8uIKsY52btTNl3UQvYE', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoib1pBYTRqc3JyZnZmalkwSWlXWm5ES3lxaERFNkFVTmsxRjQ0b0FjSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBwL2plYW5ldHRlLXJhbmRvbHBoIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEZrWHc2WU5kTFloTGNxUUYxVmVBQ3VpZ21FZUxEcExCcnY1enhYVW9taUk0NDNmaDF0Ny8yIjt9', 1733847322);

-- Dumping structure for table modern-printing.transaksis
CREATE TABLE IF NOT EXISTS `transaksis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` int DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` enum('transfer','cash','qris') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaksis_kode_unique` (`kode`),
  KEY `transaksis_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `transaksis_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.transaksis: ~14 rows (approximately)
REPLACE INTO `transaksis` (`id`, `vendor_id`, `kode`, `total_qty`, `total_harga`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'TRX-20241209213646-1', 2, 2.00, 'transfer', 'success', '2024-12-09 14:36:46', '2024-12-09 14:36:46'),
	(2, 1, 'TRX-20241209213648-1', 2, 2.00, 'transfer', 'success', '2024-12-09 14:36:48', '2024-12-09 14:36:48'),
	(3, 1, 'TRX-20241209213719-1', 2, 2.00, 'transfer', 'pending', '2024-12-09 14:37:19', '2024-12-09 15:30:40'),
	(4, 1, 'TRX-20241209215200-1', 2, 2.00, 'cash', 'success', '2024-12-09 14:52:00', '2024-12-09 14:52:00'),
	(5, 1, 'TRX-20241209220201-1', 2, 2.00, 'qris', 'success', '2024-12-09 15:02:01', '2024-12-09 15:02:01'),
	(6, 1, 'TRX-20241209220531-1', 1, 1.00, 'cash', 'success', '2024-12-09 15:05:31', '2024-12-09 15:05:31'),
	(7, 1, 'TRX-20241209220810-1', 1, 1.00, 'cash', 'success', '2024-12-09 15:08:10', '2024-12-09 15:08:10'),
	(8, 1, 'TRX-20241209221158-1', 1, 1.00, 'cash', 'success', '2024-12-09 15:11:58', '2024-12-09 15:11:58'),
	(9, 1, 'TRX-20241209221340-1', 1, 1.00, 'cash', 'success', '2024-12-09 15:13:40', '2024-12-09 15:13:40'),
	(10, 1, 'TRX-20241209221755-1', 2, 2.00, 'cash', 'success', '2024-12-09 15:17:55', '2024-12-09 15:17:55'),
	(11, 1, 'TRX-20241209222001-1', 2, 2.00, 'cash', 'success', '2024-12-09 15:20:01', '2024-12-09 15:20:01'),
	(12, 1, 'TRX-20241209222053-1', 2, 2.00, 'cash', 'success', '2024-12-09 15:20:53', '2024-12-09 15:20:53'),
	(13, 1, 'TRX-20241209222507-1', 2, 2.00, 'cash', 'success', '2024-12-09 15:25:07', '2024-12-09 15:25:07'),
	(14, 1, 'TRX-20241210103835-1', 2, 2.00, 'transfer', 'pending', '2024-12-10 03:38:35', '2024-12-10 03:38:35');

-- Dumping structure for table modern-printing.transaksi_pelanggan
CREATE TABLE IF NOT EXISTS `transaksi_pelanggan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `pelanggan_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_pelanggan_transaksi_id_foreign` (`transaksi_id`),
  KEY `transaksi_pelanggan_pelanggan_id_foreign` (`pelanggan_id`),
  CONSTRAINT `transaksi_pelanggan_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_pelanggan_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.transaksi_pelanggan: ~14 rows (approximately)
REPLACE INTO `transaksi_pelanggan` (`id`, `transaksi_id`, `pelanggan_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 25, '2024-12-09 14:36:46', '2024-12-09 14:36:46'),
	(2, 2, 26, '2024-12-09 14:36:48', '2024-12-09 14:36:48'),
	(3, 3, 28, '2024-12-09 14:37:19', '2024-12-09 14:37:19'),
	(4, 4, 8, '2024-12-09 14:52:00', '2024-12-09 14:52:00'),
	(5, 5, 25, '2024-12-09 15:02:01', '2024-12-09 15:02:01'),
	(6, 6, 19, '2024-12-09 15:05:31', '2024-12-09 15:05:31'),
	(7, 7, 14, '2024-12-09 15:08:10', '2024-12-09 15:08:10'),
	(8, 8, 17, '2024-12-09 15:11:58', '2024-12-09 15:11:58'),
	(9, 9, 17, '2024-12-09 15:13:40', '2024-12-09 15:13:40'),
	(10, 10, 18, '2024-12-09 15:17:55', '2024-12-09 15:17:55'),
	(11, 11, 16, '2024-12-09 15:20:01', '2024-12-09 15:20:01'),
	(12, 12, 15, '2024-12-09 15:20:53', '2024-12-09 15:20:53'),
	(13, 13, 18, '2024-12-09 15:25:07', '2024-12-09 15:25:07'),
	(14, 14, 13, '2024-12-10 03:38:35', '2024-12-10 03:38:35');

-- Dumping structure for table modern-printing.transaksi_produk
CREATE TABLE IF NOT EXISTS `transaksi_produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_produk_transaksi_id_foreign` (`transaksi_id`),
  KEY `transaksi_produk_produk_id_foreign` (`produk_id`),
  CONSTRAINT `transaksi_produk_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_produk_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.transaksi_produk: ~22 rows (approximately)
REPLACE INTO `transaksi_produk` (`id`, `transaksi_id`, `produk_id`, `quantity`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 1, '2024-12-09 14:36:46', '2024-12-09 14:36:46'),
	(2, 1, 3, 1, '2024-12-09 14:36:46', '2024-12-09 14:36:46'),
	(3, 2, 2, 1, '2024-12-09 14:36:48', '2024-12-09 14:36:48'),
	(4, 2, 3, 1, '2024-12-09 14:36:48', '2024-12-09 14:36:48'),
	(5, 3, 1, 1, '2024-12-09 14:37:19', '2024-12-09 14:37:19'),
	(6, 3, 2, 1, '2024-12-09 14:37:19', '2024-12-09 14:37:19'),
	(7, 4, 3, 1, '2024-12-09 14:52:00', '2024-12-09 14:52:00'),
	(8, 4, 2, 1, '2024-12-09 14:52:00', '2024-12-09 14:52:00'),
	(9, 5, 3, 1, '2024-12-09 15:02:01', '2024-12-09 15:02:01'),
	(10, 5, 2, 1, '2024-12-09 15:02:01', '2024-12-09 15:02:01'),
	(11, 6, 3, 1, '2024-12-09 15:05:31', '2024-12-09 15:05:31'),
	(12, 7, 3, 1, '2024-12-09 15:08:10', '2024-12-09 15:08:10'),
	(13, 8, 3, 1, '2024-12-09 15:11:58', '2024-12-09 15:11:58'),
	(14, 9, 3, 1, '2024-12-09 15:13:40', '2024-12-09 15:13:40'),
	(15, 10, 2, 1, '2024-12-09 15:17:55', '2024-12-09 15:17:55'),
	(16, 10, 3, 1, '2024-12-09 15:17:55', '2024-12-09 15:17:55'),
	(17, 11, 3, 1, '2024-12-09 15:20:01', '2024-12-09 15:20:01'),
	(18, 11, 2, 1, '2024-12-09 15:20:01', '2024-12-09 15:20:01'),
	(19, 12, 2, 1, '2024-12-09 15:20:53', '2024-12-09 15:20:53'),
	(20, 12, 3, 1, '2024-12-09 15:20:53', '2024-12-09 15:20:53'),
	(21, 13, 2, 1, '2024-12-09 15:25:07', '2024-12-09 15:25:07'),
	(22, 13, 3, 1, '2024-12-09 15:25:07', '2024-12-09 15:25:07'),
	(23, 14, 3, 1, '2024-12-10 03:38:35', '2024-12-10 03:38:35'),
	(24, 14, 2, 1, '2024-12-10 03:38:35', '2024-12-10 03:38:35');

-- Dumping structure for table modern-printing.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usertype` enum('admin','user','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `theme_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.users: ~0 rows (approximately)
REPLACE INTO `users` (`id`, `profile_image`, `name`, `email`, `email_verified_at`, `password`, `usertype`, `remember_token`, `created_at`, `updated_at`, `theme`, `theme_color`) VALUES
	(1, 'avatars/01JEEVX2NBCCZH7QJRK3XNAQS3.png', 'Admin', 'admin@gmail.com', '2024-12-04 12:18:07', '$2y$12$FkXw6YNdLYhLcqQF1VeACuigmEeLDpLBrv5zxXUomiI443fh1t7/2', 'admin', 'grjp0Qj3Wt72nXlN7OHva5QIb46mr2wFinKMyU6P41UshCWKUYkc2cGOTQum', '2024-12-04 12:18:07', '2024-12-06 20:52:10', 'default', NULL);

-- Dumping structure for table modern-printing.user_vendor
CREATE TABLE IF NOT EXISTS `user_vendor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_vendor_vendor_id_user_id_unique` (`vendor_id`,`user_id`),
  KEY `user_vendor_user_id_foreign` (`user_id`),
  CONSTRAINT `user_vendor_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_vendor_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.user_vendor: ~0 rows (approximately)
REPLACE INTO `user_vendor` (`id`, `vendor_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, NULL);

-- Dumping structure for table modern-printing.vendors
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendors_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.vendors: ~0 rows (approximately)
REPLACE INTO `vendors` (`id`, `name`, `slug`, `email`, `website`, `address`, `phone`, `logo`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Jeanette Randolph', 'jeanette-randolph', 'lyfanukoxy@mailinator.com', 'https://www.lidasonag.ca', 'Aliquid veniam excepteur illo officiis consequuntur molestias suscipit blanditiis et aliquam animi possimus non vel ut magnam tempor', '0987654321', 'vendor/01JE8TCH3FQ2JE30R8DDMXKSBA.png', 'active', '2024-12-04 12:30:13', '2024-12-04 12:30:13');

-- Dumping structure for table modern-printing.vendor_activities
CREATE TABLE IF NOT EXISTS `vendor_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `changes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_activities_vendor_id_foreign` (`vendor_id`),
  KEY `vendor_activities_user_id_foreign` (`user_id`),
  CONSTRAINT `vendor_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vendor_activities_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.vendor_activities: ~0 rows (approximately)
REPLACE INTO `vendor_activities` (`id`, `vendor_id`, `user_id`, `action`, `description`, `changes`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'created', 'Created vendor Jeanette Randolph', '{"id": 1, "logo": "vendor/01JE8TCH3FQ2JE30R8DDMXKSBA.png", "name": "Jeanette Randolph", "slug": "jeanette-randolph", "email": "lyfanukoxy@mailinator.com", "phone": "0987654321", "address": "Aliquid veniam excepteur illo officiis consequuntur molestias suscipit blanditiis et aliquam animi possimus non vel ut magnam tempor", "website": "https://www.lidasonag.ca", "created_at": "2024-12-04T12:30:13.000000Z", "updated_at": "2024-12-04T12:30:13.000000Z"}', '2024-12-04 12:30:13', '2024-12-04 12:30:13');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
