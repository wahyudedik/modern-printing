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
  `merek` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spesifikasi_alat` text COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','maintenance','rusak') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pembelian` date NOT NULL,
  `kapasitas_cetak_per_jam` int NOT NULL,
  `tersedia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alats_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `alats_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.alats: ~3 rows (approximately)
REPLACE INTO `alats` (`id`, `vendor_id`, `nama_alat`, `merek`, `model`, `spesifikasi_alat`, `status`, `tanggal_pembelian`, `kapasitas_cetak_per_jam`, `tersedia`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Mesin Cetak Digital (100 lbr/jam)', 'Epson', '1123L', 'Mesin Cetak Digital (100 lbr/jam)', 'aktif', '2024-12-31', 100, 'ya', '2024-12-31 06:55:41', '2024-12-31 06:56:56'),
	(2, 1, 'Mesin Jilid (30 buku/jam)', 'Epson', '43fd', 'Mesin Jilid (30 buku/jam)', 'aktif', '2024-12-31', 30, 'ya', '2024-12-31 07:18:42', '2024-12-31 07:18:42'),
	(3, 1, 'Mesin Sablon (6 pcs/jam)', 'Epson', '34fdsf', 'Mesin Sablon (6 pcs/jam)', 'aktif', '2024-12-11', 6, 'ya', '2024-12-31 07:19:22', '2024-12-31 07:21:06');

-- Dumping structure for table modern-printing.bahans
CREATE TABLE IF NOT EXISTS `bahans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `nama_bahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hpp` decimal(10,2) NOT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bahans_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `bahans_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.bahans: ~8 rows (approximately)
REPLACE INTO `bahans` (`id`, `vendor_id`, `nama_bahan`, `hpp`, `satuan`, `stok`, `created_at`, `updated_at`) VALUES
	(1, 1, 'a4', 100.00, 'lembar', '4317', '2024-12-31 08:38:49', '2025-01-03 09:35:38'),
	(2, 1, 'a5', 200.00, 'lembar', '100', '2024-12-31 08:44:48', '2024-12-31 08:44:48'),
	(3, 1, 'warna', 1000.00, 'lembar', '34434', '2024-12-31 08:49:45', '2025-01-01 17:15:44'),
	(4, 1, 'hitam putih', 500.00, 'lembar', '534490', '2024-12-31 08:50:55', '2025-01-01 17:15:44'),
	(5, 1, 'Soft Cover', 5000.00, 'lembar', '993', '2024-12-31 08:52:31', '2025-01-03 09:35:38'),
	(6, 1, 'Hard Cover', 10000.00, 'lembar', '100', '2024-12-31 08:52:51', '2024-12-31 08:52:51'),
	(7, 2, 'dadasd', 43345.00, 'galon', '423423', '2025-01-02 11:11:24', '2025-01-02 11:11:24'),
	(8, 2, 'rrett', 5435.00, 'kaleng', '34535', '2025-01-02 11:15:22', '2025-01-02 11:15:22'),
	(9, 2, 'dfsdfsf', 43234.00, 'galon', '432234', '2025-01-02 11:20:00', '2025-01-02 11:20:00');

-- Dumping structure for table modern-printing.bahan_spesifikasi_produk
CREATE TABLE IF NOT EXISTS `bahan_spesifikasi_produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bahan_id` bigint unsigned NOT NULL,
  `spesifikasi_produk_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bahan_spek_unique` (`bahan_id`,`spesifikasi_produk_id`),
  KEY `bahan_spesifikasi_produk_spesifikasi_produk_id_foreign` (`spesifikasi_produk_id`),
  CONSTRAINT `bahan_spesifikasi_produk_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bahan_spesifikasi_produk_spesifikasi_produk_id_foreign` FOREIGN KEY (`spesifikasi_produk_id`) REFERENCES `spesifikasi_produks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.bahan_spesifikasi_produk: ~10 rows (approximately)
REPLACE INTO `bahan_spesifikasi_produk` (`id`, `bahan_id`, `spesifikasi_produk_id`, `created_at`, `updated_at`) VALUES
	(1, 5, 16, NULL, NULL),
	(2, 6, 16, NULL, NULL),
	(3, 1, 17, NULL, NULL),
	(4, 2, 17, NULL, NULL),
	(5, 4, 18, NULL, NULL),
	(6, 3, 19, NULL, NULL),
	(7, 1, 20, NULL, NULL),
	(8, 2, 20, NULL, NULL),
	(9, 5, 21, NULL, NULL),
	(10, 6, 21, NULL, NULL);

-- Dumping structure for table modern-printing.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.cache: ~7 rows (approximately)
REPLACE INTO `cache` (`key`, `value`, `expiration`) VALUES
	('356a192b7913b04c54574d18c28d46e6395428ab', 'i:4;', 1735630677),
	('356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1735630677;', 1735630677),
	('a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1735896703),
	('a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1735896703;', 1735896703),
	('da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1735767198),
	('da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1735767198;', 1735767198),
	('spatie.permission.cache', 'a:3:{s:5:"alias";a:5:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";s:1:"j";s:9:"vendor_id";}s:11:"permissions";a:107:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:9:"view_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:13:"view_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:11:"create_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:11:"update_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:12:"restore_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:16:"restore_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:14:"replicate_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:12:"reorder_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:11:"delete_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:15:"delete_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:17:"force_delete_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:11;a:4:{s:1:"a";i:12;s:1:"b";s:21:"force_delete_any_alat";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:12;a:4:{s:1:"a";i:13;s:1:"b";s:10:"view_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:13;a:4:{s:1:"a";i:14;s:1:"b";s:14:"view_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:14;a:4:{s:1:"a";i:15;s:1:"b";s:12:"create_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:15;a:4:{s:1:"a";i:16;s:1:"b";s:12:"update_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:16;a:4:{s:1:"a";i:17;s:1:"b";s:13:"restore_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:17;a:4:{s:1:"a";i:18;s:1:"b";s:17:"restore_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:18;a:4:{s:1:"a";i:19;s:1:"b";s:15:"replicate_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:19;a:4:{s:1:"a";i:20;s:1:"b";s:13:"reorder_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:20;a:4:{s:1:"a";i:21;s:1:"b";s:12:"delete_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:21;a:4:{s:1:"a";i:22;s:1:"b";s:16:"delete_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:22;a:4:{s:1:"a";i:23;s:1:"b";s:18:"force_delete_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:23;a:4:{s:1:"a";i:24;s:1:"b";s:22:"force_delete_any_bahan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:24;a:4:{s:1:"a";i:25;s:1:"b";s:21:"view_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:25;a:4:{s:1:"a";i:26;s:1:"b";s:25:"view_any_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:26;a:4:{s:1:"a";i:27;s:1:"b";s:23:"create_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:27;a:4:{s:1:"a";i:28;s:1:"b";s:23:"update_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:28;a:4:{s:1:"a";i:29;s:1:"b";s:24:"restore_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:29;a:4:{s:1:"a";i:30;s:1:"b";s:28:"restore_any_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:30;a:4:{s:1:"a";i:31;s:1:"b";s:26:"replicate_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:31;a:4:{s:1:"a";i:32;s:1:"b";s:24:"reorder_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:32;a:4:{s:1:"a";i:33;s:1:"b";s:23:"delete_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:33;a:4:{s:1:"a";i:34;s:1:"b";s:27:"delete_any_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:34;a:4:{s:1:"a";i:35;s:1:"b";s:29:"force_delete_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:35;a:4:{s:1:"a";i:36;s:1:"b";s:33:"force_delete_any_estimasi::produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:36;a:4:{s:1:"a";i:37;s:1:"b";s:14:"view_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:37;a:4:{s:1:"a";i:38;s:1:"b";s:18:"view_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:38;a:4:{s:1:"a";i:39;s:1:"b";s:16:"create_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:39;a:4:{s:1:"a";i:40;s:1:"b";s:16:"update_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:40;a:4:{s:1:"a";i:41;s:1:"b";s:17:"restore_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:41;a:4:{s:1:"a";i:42;s:1:"b";s:21:"restore_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:42;a:4:{s:1:"a";i:43;s:1:"b";s:19:"replicate_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:43;a:4:{s:1:"a";i:44;s:1:"b";s:17:"reorder_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:44;a:4:{s:1:"a";i:45;s:1:"b";s:16:"delete_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:45;a:4:{s:1:"a";i:46;s:1:"b";s:20:"delete_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:46;a:4:{s:1:"a";i:47;s:1:"b";s:22:"force_delete_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:47;a:4:{s:1:"a";i:48;s:1:"b";s:26:"force_delete_any_pelanggan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:48;a:4:{s:1:"a";i:49;s:1:"b";s:11:"view_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:49;a:4:{s:1:"a";i:50;s:1:"b";s:15:"view_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:50;a:4:{s:1:"a";i:51;s:1:"b";s:13:"create_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:51;a:4:{s:1:"a";i:52;s:1:"b";s:13:"update_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:52;a:4:{s:1:"a";i:53;s:1:"b";s:14:"restore_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:53;a:4:{s:1:"a";i:54;s:1:"b";s:18:"restore_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:54;a:4:{s:1:"a";i:55;s:1:"b";s:16:"replicate_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:55;a:4:{s:1:"a";i:56;s:1:"b";s:14:"reorder_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:56;a:4:{s:1:"a";i:57;s:1:"b";s:13:"delete_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:57;a:4:{s:1:"a";i:58;s:1:"b";s:17:"delete_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:58;a:4:{s:1:"a";i:59;s:1:"b";s:19:"force_delete_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:59;a:4:{s:1:"a";i:60;s:1:"b";s:23:"force_delete_any_produk";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:60;a:4:{s:1:"a";i:61;s:1:"b";s:9:"view_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:61;a:4:{s:1:"a";i:62;s:1:"b";s:13:"view_any_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:62;a:4:{s:1:"a";i:63;s:1:"b";s:11:"create_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:63;a:4:{s:1:"a";i:64;s:1:"b";s:11:"update_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:64;a:4:{s:1:"a";i:65;s:1:"b";s:11:"delete_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:65;a:4:{s:1:"a";i:66;s:1:"b";s:15:"delete_any_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:66;a:4:{s:1:"a";i:67;s:1:"b";s:16:"view_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:67;a:4:{s:1:"a";i:68;s:1:"b";s:20:"view_any_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:68;a:4:{s:1:"a";i:69;s:1:"b";s:18:"create_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:69;a:4:{s:1:"a";i:70;s:1:"b";s:18:"update_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:70;a:4:{s:1:"a";i:71;s:1:"b";s:19:"restore_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:71;a:4:{s:1:"a";i:72;s:1:"b";s:23:"restore_any_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:72;a:4:{s:1:"a";i:73;s:1:"b";s:21:"replicate_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:73;a:4:{s:1:"a";i:74;s:1:"b";s:19:"reorder_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:74;a:4:{s:1:"a";i:75;s:1:"b";s:18:"delete_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:75;a:4:{s:1:"a";i:76;s:1:"b";s:22:"delete_any_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:76;a:4:{s:1:"a";i:77;s:1:"b";s:24:"force_delete_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:77;a:4:{s:1:"a";i:78;s:1:"b";s:28:"force_delete_any_spesifikasi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:78;a:4:{s:1:"a";i:79;s:1:"b";s:14:"view_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:79;a:4:{s:1:"a";i:80;s:1:"b";s:18:"view_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:80;a:4:{s:1:"a";i:81;s:1:"b";s:16:"create_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:81;a:4:{s:1:"a";i:82;s:1:"b";s:16:"update_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:82;a:4:{s:1:"a";i:83;s:1:"b";s:17:"restore_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:83;a:4:{s:1:"a";i:84;s:1:"b";s:21:"restore_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:84;a:4:{s:1:"a";i:85;s:1:"b";s:19:"replicate_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:85;a:4:{s:1:"a";i:86;s:1:"b";s:17:"reorder_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:86;a:4:{s:1:"a";i:87;s:1:"b";s:16:"delete_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:87;a:4:{s:1:"a";i:88;s:1:"b";s:20:"delete_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:88;a:4:{s:1:"a";i:89;s:1:"b";s:22:"force_delete_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:89;a:4:{s:1:"a";i:90;s:1:"b";s:26:"force_delete_any_transaksi";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:90;a:4:{s:1:"a";i:91;s:1:"b";s:9:"view_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:91;a:4:{s:1:"a";i:92;s:1:"b";s:13:"view_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:92;a:4:{s:1:"a";i:93;s:1:"b";s:11:"create_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:93;a:4:{s:1:"a";i:94;s:1:"b";s:11:"update_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:94;a:4:{s:1:"a";i:95;s:1:"b";s:12:"restore_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:95;a:4:{s:1:"a";i:96;s:1:"b";s:16:"restore_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:96;a:4:{s:1:"a";i:97;s:1:"b";s:14:"replicate_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:97;a:4:{s:1:"a";i:98;s:1:"b";s:12:"reorder_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:98;a:4:{s:1:"a";i:99;s:1:"b";s:11:"delete_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:99;a:4:{s:1:"a";i:100;s:1:"b";s:15:"delete_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:100;a:4:{s:1:"a";i:101;s:1:"b";s:17:"force_delete_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:101;a:4:{s:1:"a";i:102;s:1:"b";s:21:"force_delete_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:102;a:4:{s:1:"a";i:103;s:1:"b";s:12:"page_Laporan";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:103;a:4:{s:1:"a";i:104;s:1:"b";s:11:"page_Themes";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:104;a:4:{s:1:"a";i:105;s:1:"b";s:29:"widget_DashboardStatsOverview";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:105;a:4:{s:1:"a";i:106;s:1:"b";s:30:"widget_DashboardTransaksiChart";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:106;a:4:{s:1:"a";i:107;s:1:"b";s:27:"widget_DashboardProdukChart";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}}s:5:"roles";a:1:{i:0;a:4:{s:1:"j";N;s:1:"a";i:1;s:1:"b";s:11:"super_admin";s:1:"c";s:3:"web";}}}', 1735899294);

-- Dumping structure for table modern-printing.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.cache_locks: ~0 rows (approximately)

-- Dumping structure for table modern-printing.estimasi_produks
CREATE TABLE IF NOT EXISTS `estimasi_produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `alat_id` bigint unsigned NOT NULL,
  `waktu_persiapan` int NOT NULL,
  `waktu_produksi_per_unit` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estimasi_produks_vendor_id_foreign` (`vendor_id`),
  KEY `estimasi_produks_produk_id_foreign` (`produk_id`),
  KEY `estimasi_produks_alat_id_foreign` (`alat_id`),
  CONSTRAINT `estimasi_produks_alat_id_foreign` FOREIGN KEY (`alat_id`) REFERENCES `alats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estimasi_produks_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estimasi_produks_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.estimasi_produks: ~3 rows (approximately)
REPLACE INTO `estimasi_produks` (`id`, `vendor_id`, `produk_id`, `alat_id`, `waktu_persiapan`, `waktu_produksi_per_unit`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 5, 1, '2024-12-31 07:30:22', '2024-12-31 07:30:22'),
	(2, 1, 2, 1, 5, 1, '2024-12-31 07:38:02', '2024-12-31 07:38:02'),
	(3, 1, 2, 2, 5, 10, '2024-12-31 07:38:19', '2024-12-31 07:38:19');

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

-- Dumping structure for table modern-printing.harga_grosir
CREATE TABLE IF NOT EXISTS `harga_grosir` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `bahan_id` bigint unsigned NOT NULL,
  `min_quantity` int NOT NULL,
  `max_quantity` int DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `harga_grosir_vendor_id_foreign` (`vendor_id`),
  KEY `harga_grosir_bahan_id_foreign` (`bahan_id`),
  CONSTRAINT `harga_grosir_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `harga_grosir_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.harga_grosir: ~12 rows (approximately)
REPLACE INTO `harga_grosir` (`id`, `vendor_id`, `bahan_id`, `min_quantity`, `max_quantity`, `harga`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 50, 100.00, '2024-12-31 08:42:29', '2024-12-31 08:42:29'),
	(2, 1, 1, 51, 100, 50.00, '2024-12-31 08:44:02', '2024-12-31 08:44:02'),
	(3, 1, 2, 1, 50, 200.00, '2024-12-31 08:45:09', '2024-12-31 08:45:09'),
	(4, 1, 2, 51, 100, 100.00, '2024-12-31 08:45:28', '2024-12-31 08:45:28'),
	(5, 1, 3, 1, 50, 1000.00, '2024-12-31 08:50:05', '2024-12-31 08:50:05'),
	(6, 1, 3, 51, 100, 500.00, '2024-12-31 08:50:19', '2024-12-31 08:50:19'),
	(7, 1, 4, 1, 50, 500.00, '2024-12-31 08:51:21', '2024-12-31 08:51:21'),
	(8, 1, 4, 51, 100, 250.00, '2024-12-31 08:51:34', '2024-12-31 08:51:34'),
	(9, 1, 6, 1, 50, 10000.00, '2024-12-31 08:53:15', '2024-12-31 08:53:15'),
	(10, 1, 6, 51, 100, 5000.00, '2024-12-31 08:53:25', '2024-12-31 08:53:25'),
	(11, 1, 5, 1, 50, 5000.00, '2024-12-31 08:53:56', '2024-12-31 08:53:56'),
	(12, 1, 5, 51, 100, 2500.00, '2024-12-31 08:54:08', '2024-12-31 08:54:08');

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

-- Dumping structure for table modern-printing.kategori_produks
CREATE TABLE IF NOT EXISTS `kategori_produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_produks_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `kategori_produks_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.kategori_produks: ~2 rows (approximately)
REPLACE INTO `kategori_produks` (`id`, `vendor_id`, `nama_kategori`, `slug`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Brosur', 'brosur', '2024-12-31 07:27:11', '2024-12-31 07:27:11'),
	(2, 1, 'Buku', 'buku', '2024-12-31 07:37:19', '2024-12-31 07:37:19');

-- Dumping structure for table modern-printing.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.migrations: ~0 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2024_11_24_205045_create_vendors_table', 1),
	(5, '2024_11_24_232157_create_vendor_activities_table', 1),
	(6, '2024_12_02_233239_create_permission_tables', 1),
	(7, '2024_12_03_153836_add_themes_settings_to_users_table', 1),
	(8, '2024_12_03_155819_create_alats_table', 1),
	(9, '2024_12_03_162701_create_kategori_produks_table', 1),
	(10, '2024_12_04_042349_create_produks_table', 1),
	(11, '2024_12_06_000036_create_pelanggans_table', 1),
	(12, '2024_12_18_042821_create_spesifikasis_table', 1),
	(13, '2024_12_18_042822_create_spesifikasi_produks_table', 1),
	(14, '2024_12_18_043646_create_estimasi_produks_table', 1),
	(15, '2024_12_26_112415_create_notifications_table', 1),
	(16, '2025_12_03_155801_create_bahans_table', 1),
	(17, '2025_12_23_092305_create_bahan_spesifikasi_produk_table', 1),
	(18, '2026_12_06_031123_create_transaksis_table', 1);

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
	(1, 'App\\Models\\User', 1),
	(1, 'App\\Models\\User', 2);

-- Dumping structure for table modern-printing.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.notifications: ~0 rows (approximately)

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.pelanggans: ~4 rows (approximately)
REPLACE INTO `pelanggans` (`id`, `vendor_id`, `kode`, `nama`, `alamat`, `no_telp`, `email`, `transaksi_terakhir`, `created_at`, `updated_at`) VALUES
	(1, 1, 'PLG-20250101062013', 'Wahyu Dedik Dwi Astono', 'erfdsfsf', '45345345345', 'admin@gmail.com', '2025-01-03 09:35:38', '2024-12-31 23:20:13', '2025-01-03 09:35:38'),
	(2, 1, 'PLG-20250101062514', 'dada', 'adad', '424234234234', 'admin@gmail.com', NULL, '2024-12-31 23:25:14', '2024-12-31 23:25:14'),
	(3, 1, 'PLG-20250101062640', 'dsad', 'dadasd', 'asdad', 'adasd@gmail.com', NULL, '2024-12-31 23:26:40', '2024-12-31 23:26:40'),
	(4, 1, 'PLG-20250101062737', 'dasd', 'adad', '784354355', 'admin@gmail.com', NULL, '2024-12-31 23:27:37', '2024-12-31 23:27:37');

-- Dumping structure for table modern-printing.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.permissions: ~107 rows (approximately)
REPLACE INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view_alat', 'web', '2024-12-31 06:34:28', '2024-12-31 06:34:28'),
	(2, 'view_any_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(3, 'create_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(4, 'update_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(5, 'restore_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(6, 'restore_any_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(7, 'replicate_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(8, 'reorder_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(9, 'delete_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(10, 'delete_any_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(11, 'force_delete_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(12, 'force_delete_any_alat', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(13, 'view_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(14, 'view_any_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(15, 'create_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(16, 'update_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(17, 'restore_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(18, 'restore_any_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(19, 'replicate_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(20, 'reorder_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(21, 'delete_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(22, 'delete_any_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(23, 'force_delete_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(24, 'force_delete_any_bahan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(25, 'view_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(26, 'view_any_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(27, 'create_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(28, 'update_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(29, 'restore_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(30, 'restore_any_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(31, 'replicate_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(32, 'reorder_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(33, 'delete_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(34, 'delete_any_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(35, 'force_delete_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(36, 'force_delete_any_estimasi::produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(37, 'view_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(38, 'view_any_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(39, 'create_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(40, 'update_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(41, 'restore_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(42, 'restore_any_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(43, 'replicate_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(44, 'reorder_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(45, 'delete_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(46, 'delete_any_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(47, 'force_delete_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(48, 'force_delete_any_pelanggan', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(49, 'view_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(50, 'view_any_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(51, 'create_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(52, 'update_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(53, 'restore_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(54, 'restore_any_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(55, 'replicate_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(56, 'reorder_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(57, 'delete_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(58, 'delete_any_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(59, 'force_delete_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(60, 'force_delete_any_produk', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(61, 'view_role', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(62, 'view_any_role', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(63, 'create_role', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(64, 'update_role', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(65, 'delete_role', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(66, 'delete_any_role', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(67, 'view_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(68, 'view_any_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(69, 'create_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(70, 'update_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(71, 'restore_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(72, 'restore_any_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(73, 'replicate_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(74, 'reorder_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(75, 'delete_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(76, 'delete_any_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(77, 'force_delete_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(78, 'force_delete_any_spesifikasi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(79, 'view_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(80, 'view_any_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(81, 'create_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(82, 'update_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(83, 'restore_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(84, 'restore_any_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(85, 'replicate_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(86, 'reorder_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(87, 'delete_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(88, 'delete_any_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(89, 'force_delete_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(90, 'force_delete_any_transaksi', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(91, 'view_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(92, 'view_any_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(93, 'create_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(94, 'update_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(95, 'restore_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(96, 'restore_any_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(97, 'replicate_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(98, 'reorder_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(99, 'delete_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(100, 'delete_any_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(101, 'force_delete_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(102, 'force_delete_any_user', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29'),
	(103, 'page_Laporan', 'web', '2024-12-31 06:34:30', '2024-12-31 06:34:30'),
	(104, 'page_Themes', 'web', '2024-12-31 06:34:30', '2024-12-31 06:34:30'),
	(105, 'widget_DashboardStatsOverview', 'web', '2024-12-31 06:34:30', '2024-12-31 06:34:30'),
	(106, 'widget_DashboardTransaksiChart', 'web', '2024-12-31 06:34:30', '2024-12-31 06:34:30'),
	(107, 'widget_DashboardProdukChart', 'web', '2024-12-31 06:34:30', '2024-12-31 06:34:30');

-- Dumping structure for table modern-printing.produks
CREATE TABLE IF NOT EXISTS `produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `gambar` json DEFAULT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produks_vendor_id_foreign` (`vendor_id`),
  KEY `produks_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `produks_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produks_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.produks: ~2 rows (approximately)
REPLACE INTO `produks` (`id`, `vendor_id`, `gambar`, `nama_produk`, `deskripsi`, `kategori_id`, `created_at`, `updated_at`) VALUES
	(1, 1, '["produk-images/01JGDSTSXJKJSGEW0M9HX45QWF.jpg", "produk-images/01JGDSTSXPBAMYQF268YW8GZ4A.jpg", "produk-images/01JGDSTSXS6HZDZPYF82Y2HNXG.png", "produk-images/01JGDSTSXVC0G7GKQ9KYKW01TP.png"]', 'Brosur', '<p>frsefsdf</p>', 1, '2024-12-31 07:28:08', '2024-12-31 07:28:08'),
	(2, 1, '["produk-images/01JGDTC21WE6ANSJ0JCPPBDHB4.jpg", "produk-images/01JGDTC2208N5QXF9ZMP58CX48.jpg", "produk-images/01JGDTC222ANN2440P1G3WANRV.png", "produk-images/01JGDTC225QQ415MPTW2WFPBV8.png"]', 'Cetak Buku', '<p>Buku</p>', 2, '2024-12-31 07:37:33', '2024-12-31 07:37:33');

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
	(NULL, 1, 'super_admin', 'web', '2024-12-31 06:34:29', '2024-12-31 06:34:29');

-- Dumping structure for table modern-printing.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.role_has_permissions: ~107 rows (approximately)
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
	(85, 1),
	(86, 1),
	(87, 1),
	(88, 1),
	(89, 1),
	(90, 1),
	(91, 1),
	(92, 1),
	(93, 1),
	(94, 1),
	(95, 1),
	(96, 1),
	(97, 1),
	(98, 1),
	(99, 1),
	(100, 1),
	(101, 1),
	(102, 1),
	(103, 1),
	(104, 1),
	(105, 1),
	(106, 1),
	(107, 1);

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
	('CKv8zuKEZePzFCFlVxy9B6NPSJrXWWGDm59TMqeD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiTFJhMERGeXlTb3JlNzY4OW9zQTdRRGVpSk1wYllyYUJIWHFCajBIRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcHAvbm9lbGxlLWtlbGx5L3Bvcy9jYXJ0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiQveGFtQ3ZTR21YajBmY2xoZEU1VTZla242Ly9udHBXVkdPaFM0amVHRVBZbFhTNnIvc1JwdSI7czo0OiJjYXJ0IjthOjI6e2k6MDthOjY6e3M6MTA6InByb2R1Y3RfaWQiO2k6MjtzOjEyOiJwcm9kdWN0X25hbWUiO3M6MTA6IkNldGFrIEJ1a3UiO3M6ODoicXVhbnRpdHkiO3M6MToiMSI7czoxNDoic3BlY2lmaWNhdGlvbnMiO2E6NDp7aToxNjthOjU6e3M6NToidmFsdWUiO3M6MToiNSI7czo4OiJiYWhhbl9pZCI7aTo1O3M6MTA6ImlucHV0X3R5cGUiO3M6Njoic2VsZWN0IjtzOjU6InByaWNlIjtkOjUwMDA7czoxNjoibmFtYV9zcGVzaWZpa2FzaSI7czo1OiJDb3ZlciI7fWk6MTc7YTo1OntzOjU6InZhbHVlIjtzOjE6IjEiO3M6ODoiYmFoYW5faWQiO2k6MTtzOjEwOiJpbnB1dF90eXBlIjtzOjY6InNlbGVjdCI7czo1OiJwcmljZSI7ZDoxMDA7czoxNjoibmFtYV9zcGVzaWZpa2FzaSI7czo2OiJLZXJ0YXMiO31pOjE4O2E6NTp7czo1OiJ2YWx1ZSI7aTo1NjtzOjg6ImJhaGFuX2lkIjtpOjQ7czoxMDoiaW5wdXRfdHlwZSI7czo2OiJudW1iZXIiO3M6NToicHJpY2UiO2Q6MTQwMDA7czoxNjoibmFtYV9zcGVzaWZpa2FzaSI7czoxMToiSGl0YW0gUHV0aWgiO31pOjE5O2E6NTp7czo1OiJ2YWx1ZSI7aTo0MztzOjg6ImJhaGFuX2lkIjtpOjM7czoxMDoiaW5wdXRfdHlwZSI7czo2OiJudW1iZXIiO3M6NToicHJpY2UiO2Q6NDMwMDA7czoxNjoibmFtYV9zcGVzaWZpa2FzaSI7czo1OiJXYXJuYSI7fX1zOjExOiJ0b3RhbF9wcmljZSI7ZDo2MjEwMDtzOjE0OiJlc3RpbWF0ZWRfdGltZSI7ZDo2O31pOjE7YTo2OntzOjEwOiJwcm9kdWN0X2lkIjtpOjE7czoxMjoicHJvZHVjdF9uYW1lIjtzOjY6IkJyb3N1ciI7czo4OiJxdWFudGl0eSI7czoxOiIxIjtzOjE0OiJzcGVjaWZpY2F0aW9ucyI7YToyOntpOjIwO2E6NTp7czo1OiJ2YWx1ZSI7czoxOiIxIjtzOjg6ImJhaGFuX2lkIjtpOjE7czoxMDoiaW5wdXRfdHlwZSI7czo2OiJzZWxlY3QiO3M6NToicHJpY2UiO2Q6MTAwO3M6MTY6Im5hbWFfc3Blc2lmaWthc2kiO3M6NjoiS2VydGFzIjt9aToyMTthOjU6e3M6NToidmFsdWUiO3M6MToiNSI7czo4OiJiYWhhbl9pZCI7aTo1O3M6MTA6ImlucHV0X3R5cGUiO3M6Njoic2VsZWN0IjtzOjU6InByaWNlIjtkOjUwMDA7czoxNjoibmFtYV9zcGVzaWZpa2FzaSI7czo1OiJDb3ZlciI7fX1zOjExOiJ0b3RhbF9wcmljZSI7ZDo1MTAwO3M6MTQ6ImVzdGltYXRlZF90aW1lIjtkOjY7fX19', 1735913119);

-- Dumping structure for table modern-printing.spesifikasis
CREATE TABLE IF NOT EXISTS `spesifikasis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `nama_spesifikasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_input` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spesifikasis_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `spesifikasis_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.spesifikasis: ~4 rows (approximately)
REPLACE INTO `spesifikasis` (`id`, `vendor_id`, `nama_spesifikasi`, `tipe_input`, `satuan`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Kertas', 'select', 'lembar', '2024-12-31 07:41:25', '2024-12-31 07:41:25'),
	(2, 1, 'Warna', 'number', 'lembar', '2024-12-31 07:45:06', '2024-12-31 07:45:06'),
	(3, 1, 'Hitam Putih', 'number', 'lembar', '2024-12-31 07:45:26', '2024-12-31 07:45:26'),
	(4, 1, 'Cover', 'select', 'lembar', '2024-12-31 07:45:43', '2024-12-31 07:45:43');

-- Dumping structure for table modern-printing.spesifikasi_produks
CREATE TABLE IF NOT EXISTS `spesifikasi_produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `spesifikasi_id` bigint unsigned NOT NULL,
  `wajib_diisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spesifikasi_produks_vendor_id_foreign` (`vendor_id`),
  KEY `spesifikasi_produks_produk_id_foreign` (`produk_id`),
  KEY `spesifikasi_produks_spesifikasi_id_foreign` (`spesifikasi_id`),
  CONSTRAINT `spesifikasi_produks_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `spesifikasi_produks_spesifikasi_id_foreign` FOREIGN KEY (`spesifikasi_id`) REFERENCES `spesifikasis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `spesifikasi_produks_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.spesifikasi_produks: ~6 rows (approximately)
REPLACE INTO `spesifikasi_produks` (`id`, `vendor_id`, `produk_id`, `spesifikasi_id`, `wajib_diisi`, `pilihan`, `created_at`, `updated_at`) VALUES
	(16, 1, 2, 4, '1', NULL, '2024-12-31 09:43:01', '2024-12-31 09:43:01'),
	(17, 1, 2, 1, '1', NULL, '2024-12-31 09:43:22', '2024-12-31 09:43:22'),
	(18, 1, 2, 3, '1', NULL, '2024-12-31 09:43:52', '2024-12-31 09:43:52'),
	(19, 1, 2, 2, '1', NULL, '2024-12-31 09:44:35', '2024-12-31 09:44:35'),
	(20, 1, 1, 1, '1', NULL, '2024-12-31 09:46:17', '2024-12-31 09:46:17'),
	(21, 1, 1, 4, '1', NULL, '2024-12-31 18:11:42', '2024-12-31 18:11:42');

-- Dumping structure for table modern-printing.transaksis
CREATE TABLE IF NOT EXISTS `transaksis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `pelanggan_id` bigint unsigned NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled','quality_check','processing') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimasi_selesai` timestamp NOT NULL,
  `tanggal_dibuat` date NOT NULL,
  `progress_percentage` int NOT NULL DEFAULT '0',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaksis_kode_unique` (`kode`),
  KEY `transaksis_vendor_id_foreign` (`vendor_id`),
  KEY `transaksis_user_id_foreign` (`user_id`),
  KEY `transaksis_pelanggan_id_foreign` (`pelanggan_id`),
  CONSTRAINT `transaksis_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksis_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.transaksis: ~17 rows (approximately)
REPLACE INTO `transaksis` (`id`, `vendor_id`, `kode`, `user_id`, `pelanggan_id`, `total_harga`, `status`, `payment_method`, `estimasi_selesai`, `tanggal_dibuat`, `progress_percentage`, `catatan`, `created_at`, `updated_at`) VALUES
	(3, 1, 'TRX-20250101-7590', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 01:19:18', '2025-01-01', 100, 'sdsad', '2025-01-01 01:13:18', '2025-01-01 05:24:56'),
	(4, 1, 'TRX-20250101-1291', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 01:25:18', '2025-01-01', 100, 'fdsfsdf', '2025-01-01 04:53:03', '2025-01-01 05:24:54'),
	(5, 1, 'TRX-20250101-7393', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 01:31:18', '2025-01-01', 100, 'dadad', '2025-01-01 04:59:58', '2025-01-01 05:24:51'),
	(6, 1, 'TRX-20250101-5061', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 01:37:18', '2025-01-01', 100, 'dasdasda', '2025-01-01 05:02:48', '2025-01-01 05:24:49'),
	(7, 1, 'TRX-20250101-5959', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 01:43:18', '2025-01-01', 100, 'sadasda', '2025-01-01 05:07:57', '2025-01-01 05:24:47'),
	(8, 1, 'TRX-20250101-2246', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 01:49:18', '2025-01-01', 100, 'dasd', '2025-01-01 05:17:54', '2025-01-01 05:24:45'),
	(10, 1, 'TRX-20250101-3957', 1, 1, 10200.00, 'completed', 'cash', '2025-01-01 06:10:44', '2025-01-01', 100, 'sdadad', '2025-01-01 05:58:44', '2025-01-01 06:00:26'),
	(11, 1, 'TRX-20250101-1492', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 06:10:09', '2025-01-01', 100, 'dasda', '2025-01-01 06:04:09', '2025-01-01 06:06:17'),
	(12, 1, 'TRX-20250101-6541', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 06:16:09', '2025-01-01', 100, 'dasdasd', '2025-01-01 06:05:22', '2025-01-01 06:06:15'),
	(13, 1, 'TRX-20250101-8735', 1, 1, 0.00, 'completed', 'cash', '2025-01-01 06:16:09', '2025-01-01', 100, 'dasdasd', '2025-01-01 06:05:29', '2025-01-01 06:06:10'),
	(14, 1, 'TRX-20250101-1326', 1, 1, 5100.00, 'completed', 'cash', '2025-01-01 06:15:20', '2025-01-01', 100, 'sdasd', '2025-01-01 06:09:20', '2025-01-01 06:13:02'),
	(15, 1, 'TRX-20250101-9489', 1, 1, 255000.00, 'completed', 'cash', '2025-01-01 08:00:20', '2025-01-01', 100, 'dsada', '2025-01-01 06:11:48', '2025-01-01 06:12:57'),
	(16, 1, 'TRX-20250101-5490', 1, 1, 268600.00, 'completed', 'cash', '2025-01-01 06:39:01', '2025-01-01', 100, 'easdsad', '2025-01-01 06:18:01', '2025-01-01 06:24:31'),
	(17, 1, 'TRX-20250101-5056', 1, 1, 61200.00, 'completed', 'cash', '2025-01-01 07:06:01', '2025-01-01', 100, 'dada', '2025-01-01 06:19:50', '2025-01-01 06:24:29'),
	(18, 1, 'TRX-20250101-6075', 1, 1, 3160000.00, 'completed', 'cash', '2025-01-02 03:21:01', '2025-01-01', 100, 'gfhhf', '2025-01-01 06:22:33', '2025-01-01 06:24:23'),
	(19, 1, 'TRX-20250101-8227', 1, 1, 260100.00, 'completed', 'cash', '2025-01-01 12:05:26', '2025-01-01', 100, 'jkkj', '2025-01-01 10:14:26', '2025-01-01 17:17:35'),
	(20, 1, 'TRX-20250102-8261', 1, 1, 32700.00, 'completed', 'cash', '2025-01-01 12:32:26', '2025-01-02', 100, 'dfdsf', '2025-01-01 17:15:32', '2025-01-01 17:17:30'),
	(21, 1, 'TRX-20250102-4631', 1, 1, 5100.00, 'pending', 'cash', '2025-01-01 21:00:29', '2025-01-02', 0, 'sfdsfd', '2025-01-01 20:54:29', '2025-01-01 20:54:29'),
	(22, 1, 'TRX-20250102-6844', 1, 1, 5100.00, 'pending', 'cash', '2025-01-01 21:06:29', '2025-01-02', 0, 'dsfsdf', '2025-01-01 21:21:58', '2025-01-01 21:21:58'),
	(23, 1, 'TRX-20250102-5979', 1, 1, 5100.00, 'pending', 'cash', '2025-01-01 21:12:29', '2025-01-02', 0, 'dsad', '2025-01-02 11:22:32', '2025-01-02 11:22:32'),
	(24, 1, 'TRX-20250102-6302', 1, 1, 5100.00, 'pending', 'cash', '2025-01-01 21:18:29', '2025-01-02', 0, 'dsad', '2025-01-02 16:26:59', '2025-01-02 16:26:59'),
	(27, 1, 'TRX-20250103-3735', 1, 1, 5100.00, 'pending', 'cash', '2025-01-01 21:24:29', '2025-01-03', 0, 'hghg', '2025-01-03 09:35:32', '2025-01-03 09:35:32');

-- Dumping structure for table modern-printing.transaksi_items
CREATE TABLE IF NOT EXISTS `transaksi_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `transaksi_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `kuantitas` int NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_items_vendor_id_foreign` (`vendor_id`),
  KEY `transaksi_items_transaksi_id_foreign` (`transaksi_id`),
  KEY `transaksi_items_produk_id_foreign` (`produk_id`),
  CONSTRAINT `transaksi_items_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_items_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_items_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.transaksi_items: ~21 rows (approximately)
REPLACE INTO `transaksi_items` (`id`, `vendor_id`, `transaksi_id`, `produk_id`, `kuantitas`, `harga_satuan`, `created_at`, `updated_at`) VALUES
	(3, 1, 3, 1, 1, 5100.00, '2025-01-01 01:13:22', '2025-01-01 01:13:22'),
	(4, 1, 4, 1, 1, 5100.00, '2025-01-01 04:53:08', '2025-01-01 04:53:08'),
	(5, 1, 5, 1, 1, 5100.00, '2025-01-01 05:00:02', '2025-01-01 05:00:02'),
	(6, 1, 6, 1, 1, 5100.00, '2025-01-01 05:02:53', '2025-01-01 05:02:53'),
	(7, 1, 7, 1, 1, 5100.00, '2025-01-01 05:08:02', '2025-01-01 05:08:02'),
	(8, 1, 8, 1, 1, 5100.00, '2025-01-01 05:17:58', '2025-01-01 05:17:58'),
	(9, 1, 10, 1, 1, 5100.00, '2025-01-01 05:58:48', '2025-01-01 05:58:48'),
	(10, 1, 10, 1, 1, 5100.00, '2025-01-01 05:58:48', '2025-01-01 05:58:48'),
	(11, 1, 11, 1, 1, 5100.00, '2025-01-01 06:04:13', '2025-01-01 06:04:13'),
	(12, 1, 12, 1, 1, 5100.00, '2025-01-01 06:05:27', '2025-01-01 06:05:27'),
	(13, 1, 14, 1, 1, 5100.00, '2025-01-01 06:09:24', '2025-01-01 06:09:24'),
	(14, 1, 15, 1, 100, 2550.00, '2025-01-01 06:11:52', '2025-01-01 06:11:52'),
	(15, 1, 16, 2, 1, 268600.00, '2025-01-01 06:18:05', '2025-01-01 06:18:05'),
	(16, 1, 17, 1, 1, 5100.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(17, 1, 17, 2, 1, 56100.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(18, 1, 18, 1, 100, 2550.00, '2025-01-01 06:22:37', '2025-01-01 06:22:37'),
	(19, 1, 18, 2, 100, 29050.00, '2025-01-01 06:22:38', '2025-01-01 06:22:38'),
	(20, 1, 19, 1, 1, 5100.00, '2025-01-01 10:14:30', '2025-01-01 10:14:30'),
	(21, 1, 19, 1, 100, 2550.00, '2025-01-01 10:14:31', '2025-01-01 10:14:31'),
	(22, 1, 20, 1, 1, 5100.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(23, 1, 20, 2, 1, 27600.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(24, 1, 21, 1, 1, 5100.00, '2025-01-01 20:54:33', '2025-01-01 20:54:33'),
	(25, 1, 22, 1, 1, 5100.00, '2025-01-01 21:22:02', '2025-01-01 21:22:02'),
	(26, 1, 23, 1, 1, 5100.00, '2025-01-02 11:22:41', '2025-01-02 11:22:41'),
	(27, 1, 24, 1, 1, 5100.00, '2025-01-02 16:27:04', '2025-01-02 16:27:04'),
	(28, 1, 27, 1, 1, 5100.00, '2025-01-03 09:35:38', '2025-01-03 09:35:38');

-- Dumping structure for table modern-printing.transaksi_item_specifications
CREATE TABLE IF NOT EXISTS `transaksi_item_specifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `transaksi_item_id` bigint unsigned NOT NULL,
  `spesifikasi_produk_id` bigint unsigned NOT NULL,
  `bahan_id` bigint unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_item_specifications_vendor_id_foreign` (`vendor_id`),
  KEY `transaksi_item_specifications_transaksi_item_id_foreign` (`transaksi_item_id`),
  KEY `transaksi_item_specifications_spesifikasi_produk_id_foreign` (`spesifikasi_produk_id`),
  KEY `transaksi_item_specifications_bahan_id_foreign` (`bahan_id`),
  CONSTRAINT `transaksi_item_specifications_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_item_specifications_spesifikasi_produk_id_foreign` FOREIGN KEY (`spesifikasi_produk_id`) REFERENCES `spesifikasi_produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_item_specifications_transaksi_item_id_foreign` FOREIGN KEY (`transaksi_item_id`) REFERENCES `transaksi_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_item_specifications_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.transaksi_item_specifications: ~52 rows (approximately)
REPLACE INTO `transaksi_item_specifications` (`id`, `vendor_id`, `transaksi_item_id`, `spesifikasi_produk_id`, `bahan_id`, `value`, `input_type`, `price`, `created_at`, `updated_at`) VALUES
	(1, 1, 3, 20, 1, '1', 'select', 100.00, '2025-01-01 01:13:22', '2025-01-01 01:13:22'),
	(2, 1, 3, 21, 5, '5', 'select', 5000.00, '2025-01-01 01:13:22', '2025-01-01 01:13:22'),
	(3, 1, 4, 20, 1, '1', 'select', 100.00, '2025-01-01 04:53:08', '2025-01-01 04:53:08'),
	(4, 1, 4, 21, 5, '5', 'select', 5000.00, '2025-01-01 04:53:08', '2025-01-01 04:53:08'),
	(5, 1, 5, 20, 1, '1', 'select', 100.00, '2025-01-01 05:00:02', '2025-01-01 05:00:02'),
	(6, 1, 5, 21, 5, '5', 'select', 5000.00, '2025-01-01 05:00:02', '2025-01-01 05:00:02'),
	(7, 1, 6, 20, 1, '1', 'select', 100.00, '2025-01-01 05:02:53', '2025-01-01 05:02:53'),
	(8, 1, 6, 21, 5, '5', 'select', 5000.00, '2025-01-01 05:02:53', '2025-01-01 05:02:53'),
	(9, 1, 7, 20, 1, '1', 'select', 100.00, '2025-01-01 05:08:02', '2025-01-01 05:08:02'),
	(10, 1, 7, 21, 5, '5', 'select', 5000.00, '2025-01-01 05:08:02', '2025-01-01 05:08:02'),
	(11, 1, 8, 20, 1, '1', 'select', 100.00, '2025-01-01 05:17:58', '2025-01-01 05:17:58'),
	(12, 1, 8, 21, 5, '5', 'select', 5000.00, '2025-01-01 05:17:58', '2025-01-01 05:17:58'),
	(13, 1, 9, 20, 1, '1', 'select', 100.00, '2025-01-01 05:58:48', '2025-01-01 05:58:48'),
	(14, 1, 9, 21, 5, '5', 'select', 5000.00, '2025-01-01 05:58:48', '2025-01-01 05:58:48'),
	(15, 1, 10, 20, 1, '1', 'select', 100.00, '2025-01-01 05:58:48', '2025-01-01 05:58:48'),
	(16, 1, 10, 21, 5, '5', 'select', 5000.00, '2025-01-01 05:58:48', '2025-01-01 05:58:48'),
	(17, 1, 11, 20, 1, '1', 'select', 100.00, '2025-01-01 06:04:13', '2025-01-01 06:04:13'),
	(18, 1, 11, 21, 5, '5', 'select', 5000.00, '2025-01-01 06:04:13', '2025-01-01 06:04:13'),
	(19, 1, 12, 20, 1, '1', 'select', 100.00, '2025-01-01 06:05:27', '2025-01-01 06:05:27'),
	(20, 1, 12, 21, 5, '5', 'select', 5000.00, '2025-01-01 06:05:27', '2025-01-01 06:05:27'),
	(21, 1, 13, 20, 1, '1', 'select', 100.00, '2025-01-01 06:09:24', '2025-01-01 06:09:24'),
	(22, 1, 13, 21, 5, '5', 'select', 5000.00, '2025-01-01 06:09:24', '2025-01-01 06:09:24'),
	(23, 1, 14, 20, 1, '1', 'select', 5000.00, '2025-01-01 06:11:52', '2025-01-01 06:11:52'),
	(24, 1, 14, 21, 5, '5', 'select', 250000.00, '2025-01-01 06:11:52', '2025-01-01 06:11:52'),
	(25, 1, 15, 16, 5, '5', 'select', 5000.00, '2025-01-01 06:18:05', '2025-01-01 06:18:05'),
	(26, 1, 15, 17, 1, '1', 'select', 100.00, '2025-01-01 06:18:05', '2025-01-01 06:18:05'),
	(27, 1, 15, 18, 4, '43', 'number', 21500.00, '2025-01-01 06:18:05', '2025-01-01 06:18:05'),
	(28, 1, 15, 19, 3, '242', 'number', 242000.00, '2025-01-01 06:18:05', '2025-01-01 06:18:05'),
	(29, 1, 16, 20, 1, '1', 'select', 100.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(30, 1, 16, 21, 5, '5', 'select', 5000.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(31, 1, 17, 16, 5, '5', 'select', 5000.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(32, 1, 17, 17, 1, '1', 'select', 100.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(33, 1, 17, 18, 4, '34', 'number', 17000.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(34, 1, 17, 19, 3, '34', 'number', 34000.00, '2025-01-01 06:19:54', '2025-01-01 06:19:54'),
	(35, 1, 18, 20, 1, '1', 'select', 5000.00, '2025-01-01 06:22:37', '2025-01-01 06:22:37'),
	(36, 1, 18, 21, 5, '5', 'select', 250000.00, '2025-01-01 06:22:38', '2025-01-01 06:22:38'),
	(37, 1, 19, 16, 5, '5', 'select', 250000.00, '2025-01-01 06:22:38', '2025-01-01 06:22:38'),
	(38, 1, 19, 17, 1, '1', 'select', 5000.00, '2025-01-01 06:22:38', '2025-01-01 06:22:38'),
	(39, 1, 19, 18, 4, '78', 'number', 1950000.00, '2025-01-01 06:22:38', '2025-01-01 06:22:38'),
	(40, 1, 19, 19, 3, '7', 'number', 700000.00, '2025-01-01 06:22:38', '2025-01-01 06:22:38'),
	(41, 1, 20, 20, 1, '1', 'select', 100.00, '2025-01-01 10:14:30', '2025-01-01 10:14:30'),
	(42, 1, 20, 21, 5, '5', 'select', 5000.00, '2025-01-01 10:14:31', '2025-01-01 10:14:31'),
	(43, 1, 21, 20, 1, '1', 'select', 5000.00, '2025-01-01 10:14:31', '2025-01-01 10:14:31'),
	(44, 1, 21, 21, 5, '5', 'select', 250000.00, '2025-01-01 10:14:31', '2025-01-01 10:14:31'),
	(45, 1, 22, 20, 1, '1', 'select', 100.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(46, 1, 22, 21, 5, '5', 'select', 5000.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(47, 1, 23, 16, 5, '5', 'select', 5000.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(48, 1, 23, 17, 1, '1', 'select', 100.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(49, 1, 23, 18, 4, '45', 'number', 22500.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(50, 1, 23, 19, 3, '0', 'number', 0.00, '2025-01-01 17:15:44', '2025-01-01 17:15:44'),
	(51, 1, 24, 20, 1, '1', 'select', 100.00, '2025-01-01 20:54:33', '2025-01-01 20:54:33'),
	(52, 1, 24, 21, 5, '5', 'select', 5000.00, '2025-01-01 20:54:33', '2025-01-01 20:54:33'),
	(53, 1, 25, 20, 1, '1', 'select', 100.00, '2025-01-01 21:22:02', '2025-01-01 21:22:02'),
	(54, 1, 25, 21, 5, '5', 'select', 5000.00, '2025-01-01 21:22:02', '2025-01-01 21:22:02'),
	(55, 1, 26, 20, 1, '1', 'select', 100.00, '2025-01-02 11:22:41', '2025-01-02 11:22:41'),
	(56, 1, 26, 21, 5, '5', 'select', 5000.00, '2025-01-02 11:22:41', '2025-01-02 11:22:41'),
	(57, 1, 27, 20, 1, '1', 'select', 100.00, '2025-01-02 16:27:04', '2025-01-02 16:27:04'),
	(58, 1, 27, 21, 5, '5', 'select', 5000.00, '2025-01-02 16:27:04', '2025-01-02 16:27:04'),
	(59, 1, 28, 20, 1, '1', 'select', 100.00, '2025-01-03 09:35:38', '2025-01-03 09:35:38'),
	(60, 1, 28, 21, 5, '5', 'select', 5000.00, '2025-01-03 09:35:38', '2025-01-03 09:35:38');

-- Dumping structure for table modern-printing.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usertype` enum('admin','user','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `theme_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.users: ~0 rows (approximately)
REPLACE INTO `users` (`id`, `profile_image`, `name`, `email`, `email_verified_at`, `password`, `usertype`, `is_active`, `remember_token`, `created_at`, `updated_at`, `theme`, `theme_color`) VALUES
	(1, NULL, 'Dev', 'dev@gmail.com', '2024-12-31 06:34:17', '$2y$12$/xamCvSGmXj0fclhdE5U6ekn6//ntpWVGOhS4jeGEPYlXS6r/sRpu', 'admin', 1, 'fG184NTLht', '2024-12-31 06:34:17', '2024-12-31 06:34:17', 'default', NULL),
	(2, NULL, 'Inga Moses', 'user@gmail.com', '2025-01-01 06:30:00', '$2y$12$yFeGKBSeLLqLhVyVNrE.z.DCmFs0gAxXNITlmBN81tsYIP35E5T02', 'user', 1, NULL, '2025-01-01 21:31:13', '2025-01-01 21:31:13', 'default', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.user_vendor: ~0 rows (approximately)
REPLACE INTO `user_vendor` (`id`, `vendor_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, NULL),
	(2, 2, 2, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.vendors: ~0 rows (approximately)
REPLACE INTO `vendors` (`id`, `name`, `slug`, `email`, `website`, `address`, `phone`, `logo`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Noelle Kelly', 'noelle-kelly', 'vohyme@mailinator.com', 'https://www.sicyxurebiruxi.co', 'Error cumque commodi molestiae quos culpa et qui dolore nemo recusandae Eius sit perferendis perferendis animi qui culpa quo in', '12299723849', 'vendor/01JGDQPP6QN820DSK79PYES0R9.png', 'active', '2024-12-31 06:50:56', '2024-12-31 06:50:56'),
	(2, 'Wahyu Dedik Dwi Astono', 'wahyu-dedik-dwi-astono', 'admin@gmail.com', 'https://www.fevan.co.uk', 'Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004', '42423423423', 'vendor/01JGHWHDQXDXQ7NXE10NRTSTP0.png', 'active', '2025-01-01 21:32:24', '2025-01-01 21:32:24');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table modern-printing.vendor_activities: ~4 rows (approximately)
REPLACE INTO `vendor_activities` (`id`, `vendor_id`, `user_id`, `action`, `description`, `changes`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'created', 'Created vendor Noelle Kelly', '{"id": 1, "logo": "vendor/01JGDQPP6QN820DSK79PYES0R9.png", "name": "Noelle Kelly", "slug": "noelle-kelly", "email": "vohyme@mailinator.com", "phone": "12299723849", "address": "Error cumque commodi molestiae quos culpa et qui dolore nemo recusandae Eius sit perferendis perferendis animi qui culpa quo in", "website": "https://www.sicyxurebiruxi.co", "created_at": "2024-12-31T06:50:56.000000Z", "updated_at": "2024-12-31T06:50:56.000000Z"}', '2024-12-31 06:50:56', '2024-12-31 06:50:56'),
	(2, 2, 2, 'created', 'Created vendor Wahyu Dedik Dwi Astono', '{"id": 2, "logo": "vendor/01JGHWHDQXDXQ7NXE10NRTSTP0.png", "name": "Wahyu Dedik Dwi Astono", "slug": "wahyu-dedik-dwi-astono", "email": "admin@gmail.com", "phone": "42423423423", "address": "Dsn.Sidowiryo Ds.Mojowiryo Kec.Kemlagi RT.002 RW.004", "website": "https://www.fevan.co.uk", "created_at": "2025-01-01T21:32:24.000000Z", "updated_at": "2025-01-01T21:32:24.000000Z"}', '2025-01-01 21:32:24', '2025-01-01 21:32:24'),
	(3, 2, 2, 'material_created', 'Created new material: dfsdfsf', '{"id": 9, "hpp": "43234.00", "stok": "432234", "satuan": "galon", "vendor_id": 2, "created_at": "2025-01-02T11:20:00.000000Z", "nama_bahan": "dfsdfsf", "updated_at": "2025-01-02T11:20:00.000000Z"}', '2025-01-02 11:20:00', '2025-01-02 11:20:00'),
	(4, 1, 1, 'transaction_created', 'Transaksi Baru: TRX-20250102-6302', '{"id": 24, "kode": "TRX-20250102-6302", "status": "pending", "vendor": {"id": 1, "logo": "vendor/01JGDQPP6QN820DSK79PYES0R9.png", "name": "Noelle Kelly", "slug": "noelle-kelly", "email": "vohyme@mailinator.com", "phone": "12299723849", "status": "active", "address": "Error cumque commodi molestiae quos culpa et qui dolore nemo recusandae Eius sit perferendis perferendis animi qui culpa quo in", "website": "https://www.sicyxurebiruxi.co", "created_at": "2024-12-31T06:50:56.000000Z", "updated_at": "2024-12-31T06:50:56.000000Z"}, "catatan": "dsad", "user_id": 1, "pelanggan": {"id": 1, "kode": "PLG-20250101062013", "nama": "Wahyu Dedik Dwi Astono", "email": "admin@gmail.com", "alamat": "erfdsfsf", "no_telp": "45345345345", "vendor_id": 1, "created_at": "2024-12-31T23:20:13.000000Z", "updated_at": "2025-01-02T11:22:41.000000Z", "transaksi_terakhir": "2025-01-02T11:22:41.000000Z"}, "vendor_id": 1, "created_at": "2025-01-02T16:26:59.000000Z", "updated_at": "2025-01-02T16:26:59.000000Z", "total_harga": "5100.00", "pelanggan_id": "1", "payment_method": "cash", "tanggal_dibuat": "2025-01-01T17:00:00.000000Z", "transaksi_item": [], "estimasi_selesai": "2025-01-01T21:18:29.000000Z", "progress_percentage": 0}', '2025-01-02 16:27:04', '2025-01-02 16:27:04'),
	(6, 1, 1, 'transaction_created', 'Transaksi Baru: TRX-20250103-3735', '{"id": 27, "kode": "TRX-20250103-3735", "status": "pending", "vendor": {"id": 1, "logo": "vendor/01JGDQPP6QN820DSK79PYES0R9.png", "name": "Noelle Kelly", "slug": "noelle-kelly", "email": "vohyme@mailinator.com", "phone": "12299723849", "status": "active", "address": "Error cumque commodi molestiae quos culpa et qui dolore nemo recusandae Eius sit perferendis perferendis animi qui culpa quo in", "website": "https://www.sicyxurebiruxi.co", "created_at": "2024-12-31T06:50:56.000000Z", "updated_at": "2024-12-31T06:50:56.000000Z"}, "catatan": "hghg", "user_id": 1, "pelanggan": {"id": 1, "kode": "PLG-20250101062013", "nama": "Wahyu Dedik Dwi Astono", "email": "admin@gmail.com", "alamat": "erfdsfsf", "no_telp": "45345345345", "vendor_id": 1, "created_at": "2024-12-31T23:20:13.000000Z", "updated_at": "2025-01-02T16:27:04.000000Z", "transaksi_terakhir": "2025-01-02T16:27:04.000000Z"}, "vendor_id": 1, "created_at": "2025-01-03T09:35:32.000000Z", "updated_at": "2025-01-03T09:35:32.000000Z", "total_harga": "5100.00", "pelanggan_id": "1", "payment_method": "cash", "tanggal_dibuat": "2025-01-02T17:00:00.000000Z", "transaksi_item": [], "estimasi_selesai": "2025-01-01T21:24:29.000000Z", "progress_percentage": 0}', '2025-01-03 09:35:38', '2025-01-03 09:35:38');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
