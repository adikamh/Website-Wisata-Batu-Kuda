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

-- Dumping data for table wisata-batu-kuda.cache: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.cache_locks: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.email_logs: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.e_tickets: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.failed_jobs: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.gallery: ~6 rows (approximately)
INSERT INTO `gallery` (`id`, `judul_foto`, `deskripsi`, `gambar_url`, `created_at`, `updated_at`) VALUES
	(1, 'Sunset di Batu Kuda', 'Pemandangan matahari terbenam yang spektakuler dari puncak Batu Kuda. Warna jingga keemasan menghiasi langit.', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900&q=80', '2026-05-04 18:19:59', '2026-05-04 20:01:04'),
	(2, 'Spot Selfie Favorit', 'Spot selfie dengan latar belakang formasi batu unik yang menjadi ikon Wisata Batu Kuda.', 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=900&q=80', '2026-05-04 18:19:59', '2026-05-04 20:01:04'),
	(3, 'Area Camping', 'Area camping yang nyaman dengan pemandangan alam yang indah. Cocok untuk family gathering.', 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=900&q=80', '2026-05-04 18:19:59', '2026-05-04 20:01:04'),
	(4, 'Wisata Keluarga', 'Suasana wisata yang ramah keluarga. Banyak wahana permainan untuk anak-anak.', 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=900&q=80', '2026-05-04 18:19:59', '2026-05-04 20:01:04'),
	(5, 'Pemandangan Pagi', 'Kabut tipis menyelimuti kawasan Batu Kuda di pagi hari. Sangat Instagramable!', 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=900&q=80', '2026-05-04 18:19:59', '2026-05-04 20:01:04'),
	(6, 'Warung Makan Khas', 'Nikmati kuliner khas daerah sambil menikmati pemandangan alam.', 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=900&q=80', '2026-05-04 18:19:59', '2026-05-04 20:01:04'),
	(7, 'penemu MTK', 'orang prancis', 'gallery/XWvM7tuWXw4gvlsRWR06rWaKjuCAMrvL6haT9eFv.png', '2026-05-06 00:03:47', '2026-05-06 00:03:47');

-- Dumping data for table wisata-batu-kuda.jobs: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.job_batches: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.komentar: ~0 rows (approximately)
INSERT INTO `komentar` (`id`, `gallery_id`, `user_id`, `isi_komentar`, `created_at`, `updated_at`) VALUES
	(2, 1, 4, 'Keren', '2026-05-04 20:10:37', '2026-05-04 20:10:37');

-- Dumping data for table wisata-batu-kuda.like_foto: ~1 rows (approximately)
INSERT INTO `like_foto` (`id`, `gallery_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(2, 5, 4, '2026-05-04 20:08:23', '2026-05-04 20:08:23'),
	(3, 1, 4, '2026-05-04 20:10:19', '2026-05-04 20:10:19'),
	(4, 7, 8, '2026-05-06 00:03:52', '2026-05-06 00:03:52');

-- Dumping data for table wisata-batu-kuda.migrations: ~20 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_04_20_044311_add_columns_to_users_table', 1),
	(60, '2026_04_20_044312_create_wisata_table', 2),
	(61, '2026_04_20_044313_create_paket_wisata_table', 2),
	(62, '2026_04_20_044313_create_tiket_kategori_table', 2),
	(63, '2026_04_20_044314_create_paket_items_table', 2),
	(64, '2026_04_20_044315_create_transactions_table', 2),
	(65, '2026_04_20_044316_create_transaction_details_table', 2),
	(66, '2026_04_20_044316_create_transaction_extras_table', 2),
	(67, '2026_04_20_044317_create_e_tickets_table', 2),
	(68, '2026_04_20_044317_create_visitor_logs_table', 2),
	(69, '2026_04_20_044318_create_email_logs_table', 2),
	(70, '2026_04_20_044318_create_notifications_table', 2),
	(71, '2026_04_20_044335_create_reports_table', 2),
	(72, '2026_04_28_111500_update_users_table_for_otp_verification', 2),
	(73, '2026_05_05_004834_create_gallery_table', 2),
	(74, '2026_05_05_004852_create_komentar_table', 2),
	(75, '2026_05_05_004908_create_like_foto_table', 2),
	(76, '2026_05_05_180000_add_location_coordinates_to_users_table', 3);

-- Dumping data for table wisata-batu-kuda.notifications: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.paket_items: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.paket_wisata: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.reports: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('ARV5FsvFGn8MrQmllSek8VZaWuU0grR51mJ55tRW', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJGY0VBdGZhZE1uMk95VjFiWGtldTY2bzdaRnRZYkRPWXZSYmFOWmtWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC93ZWJzaXRlLXdpc2F0YS1iYXR1LWt1ZGEudGVzdFwvdGlrZXQiLCJyb3V0ZSI6InRpa2V0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjh9', 1778076217);

-- Dumping data for table wisata-batu-kuda.tiket_kategori: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.transactions: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.transaction_details: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.transaction_extras: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `username`, `name`, `email`, `email_verified_at`, `password`, `role`, `otp`, `otp_expired_at`, `is_verified`, `Phone`, `Address`, `latitude`, `longitude`, `remember_token`, `created_at`, `updated_at`) VALUES
	(2, 'user', 'User Biasa', 'user@example.com', NULL, '$2y$12$049MexTJw98xPxVg8f524.4/dRpxuSgDGQmTsQZLube/UwsWSDY3S', 'user', NULL, NULL, 1, '081234567890', 'Jl. Contoh No. 123, Kota Wisata', NULL, NULL, 'la0bwmCpqU6FDAg65J2NwZoatoeMylPKWJ0etRpdRWn9rD2ixuKtL1UQ2YPa', '2026-04-28 20:25:38', '2026-04-28 20:25:38'),
	(4, 'haikal', 'ADIKA MUHAMMAD HAIKAL', 'haikaladika272@gmail.com', '2026-04-29 18:49:01', '$2y$12$ZYrn/NXayhmrOenLQxV/4.tZ/H7g7otVaEoPPmwk62gdDunKkoO/O', 'user', NULL, NULL, 1, '+628996806320', 'Itenas Bandung, 23, Jalan PH. H. Mustofa, Neglasari, Cibeunying Kaler, Bandung City, West Java, Java, 64222, Indonesia', NULL, NULL, NULL, '2026-04-29 18:48:30', '2026-04-29 18:49:01'),
	(8, 'admin', 'Administrator', 'batukuda@gmail.com', NULL, '$2y$12$mGWE8oOYErQPqumPxmXwye71U2WfrgmTcaeSS/NBUerX7Iwgw.IE2', 'admin', NULL, NULL, 1, '081234567890', 'Jl. Contoh No. 123, Kota Bandung', NULL, NULL, 'yuJhHMxUZyEGIEe6eUYLKnPu76ODASptMEPi2b1xQpnEa1RO0WwyO3XKG3wW', '2026-05-04 18:10:18', '2026-05-04 18:10:18'),
	(9, 'joko', 'Joko Widodo', 'joko@example.com', NULL, '$2y$12$8zl0c4Tb4KfexoGhQN.OMuEPNGKlC.2XXXQwKIEkv.rGIM9WzBTM6', 'user', NULL, NULL, 0, '081234567893', 'Jl. Diponegoro No. 20', NULL, NULL, NULL, '2026-05-04 18:10:19', '2026-05-04 18:10:19');

-- Dumping data for table wisata-batu-kuda.visitor_logs: ~0 rows (approximately)

-- Dumping data for table wisata-batu-kuda.wisata: ~0 rows (approximately)
INSERT INTO `wisata` (`id`, `nama_wisata`, `deskripsi`, `lokasi`, `gambar_url`, `created_at`, `updated_at`) VALUES
	(1, 'Batu Kuda', 'Batu Kuda adalah kawasan wisata alam yang terletak di kawasan hutan Perhutani, Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung. Namanya berasal dari sebuah formasi batu besar yang konon menyerupai kuda yang sedang duduk — menjadi daya tarik utama yang penuh misteri dan legenda. Berada di ketinggian sekitar 1.200 mdpl di lereng Gunung Manglayang, kawasan ini menawarkan udara segar, hamparan pohon pinus yang rindang, serta jalur hiking yang cocok untuk semua kalangan.', 'Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung, Jawa Barat. Koordinat: -6.8567, 107.7178. Jarak ±25 km dari pusat Kota Bandung.', 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=900&q=80', '2026-05-04 18:21:53', '2026-05-04 18:21:53');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
