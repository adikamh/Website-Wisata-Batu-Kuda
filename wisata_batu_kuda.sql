-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table wisata_batu_kuda.cache: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.cache_locks: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.email_logs: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.e_tickets: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.failed_jobs: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.jobs: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.job_batches: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.migrations: ~1 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_04_20_044311_add_columns_to_users_table', 1),
	(5, '2026_04_20_044312_create_wisata_table', 1),
	(6, '2026_04_20_044313_create_paket_wisata_table', 1),
	(7, '2026_04_20_044313_create_tiket_kategori_table', 1),
	(8, '2026_04_20_044314_create_paket_items_table', 1),
	(9, '2026_04_20_044315_create_transactions_table', 1),
	(10, '2026_04_20_044316_create_transaction_details_table', 1),
	(11, '2026_04_20_044316_create_transaction_extras_table', 1),
	(12, '2026_04_20_044317_create_e_tickets_table', 1),
	(13, '2026_04_20_044317_create_visitor_logs_table', 1),
	(14, '2026_04_20_044318_create_email_logs_table', 1),
	(15, '2026_04_20_044318_create_notifications_table', 1),
	(16, '2026_04_20_044335_create_reports_table', 1);

-- Dumping data for table wisata_batu_kuda.notifications: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.paket_items: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.paket_wisata: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.reports: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.sessions: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.tiket_kategori: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.transactions: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.transaction_details: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.transaction_extras: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.users: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.visitor_logs: ~0 rows (approximately)

-- Dumping data for table wisata_batu_kuda.wisata: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
