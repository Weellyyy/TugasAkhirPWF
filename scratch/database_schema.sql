-- =========================================================
-- CREATE DATABASE & INITIALIZE SCHEMA
-- Project: POS System (TugasAkhirPWF)
-- Dialect: MySQL / MariaDB (Compatible with Laragon / XAMPP)
-- =========================================================

-- 1. Create Database if not exists and select it
CREATE DATABASE IF NOT EXISTS `tugasakhir_pwf` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `tugasakhir_pwf`;

-- Drop tables in reverse order of dependencies if they exist
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `detail_transaksis`;
DROP TABLE IF EXISTS `transaksis`;
DROP TABLE IF EXISTS `produks`;
DROP TABLE IF EXISTS `kategoris`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------
-- 2. Table: users
-- ---------------------------------------------------------
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'kasir') DEFAULT 'kasir' NOT NULL,
  `status` ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif' NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 3. Table: kategoris
-- ---------------------------------------------------------
CREATE TABLE `kategoris` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama_kategori` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 4. Table: produks
-- ---------------------------------------------------------
CREATE TABLE `produks` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `kategori_id` BIGINT UNSIGNED NOT NULL,
  `sku` VARCHAR(255) NOT NULL UNIQUE,
  `nama_produk` VARCHAR(255) NOT NULL,
  `harga` INT NOT NULL,
  `stok` INT NOT NULL,
  `lokasi` VARCHAR(255) NOT NULL,
  `gambar` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_produks_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategoris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 5. Table: transaksis
-- ---------------------------------------------------------
CREATE TABLE `transaksis` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `total_harga` INT NOT NULL,
  `metode_pembayaran` ENUM('Cash', 'Qris') NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_transaksis_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 6. Table: detail_transaksis
-- ---------------------------------------------------------
CREATE TABLE `detail_transaksis` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `transaksi_id` BIGINT UNSIGNED NOT NULL,
  `produk_id` BIGINT UNSIGNED NOT NULL,
  `jumlah` INT NOT NULL,
  `harga` INT NOT NULL,
  `subtotal` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_detail_transaksis_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_transaksis_produk` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 7. Table: password_reset_tokens
-- ---------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) PRIMARY KEY,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 8. Table: sessions
-- ---------------------------------------------------------
CREATE TABLE `sessions` (
  `id` VARCHAR(255) PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- DML: SEED DATA (INITIAL DATA)
-- =========================================================

-- Insert Users (Password: 'password' hashed using Laravel standard bcrypt-like format for reference)
-- Admin: username 'admin', Kasir: username 'kasir'
INSERT INTO `users` (`nama`, `username`, `email`, `password`, `role`, `status`) VALUES
('Admin Toko', 'admin', 'admin@pos.com', '$2y$12$Z0bV1.9aE2s34g6h8j0k.uY5t4r3e2w1q0p9o8n7m6l5k4j3h2g1f', 'admin', 'Aktif'),
('Kevin Adrian', 'kasir', 'kasir@pos.com', '$2y$12$Z0bV1.9aE2s34g6h8j0k.uY5t4r3e2w1q0p9o8n7m6l5k4j3h2g1f', 'kasir', 'Aktif');

-- Insert Kategori
INSERT INTO `kategoris` (`id`, `nama_kategori`) VALUES
(1, 'Makanan'),
(2, 'Minuman');

-- Insert Produk
INSERT INTO `produks` (`kategori_id`, `sku`, `nama_produk`, `harga`, `stok`, `lokasi`, `gambar`) VALUES
(2, 'PRD-MUM-001', 'Coffe Latte', 15000, 49, 'Rak Minuman A', NULL),
(1, 'PRD-MAK-002', 'Nasi Goreng Spesial', 25000, 24, 'Dapur Utama', NULL),
(2, 'PRD-MUM-003', 'Es Teh Manis', 5000, 100, 'Rak Minuman B', NULL);

-- Insert Transaksi (Contoh data historis)
INSERT INTO `transaksis` (`id`, `user_id`, `total_harga`, `metode_pembayaran`, `created_at`) VALUES
(5236, 2, 40000, 'Cash', '2026-05-28 12:04:17');

-- Insert Detail Transaksi
INSERT INTO `detail_transaksis` (`transaksi_id`, `produk_id`, `jumlah`, `harga`, `subtotal`) VALUES
(5236, 1, 1, 15000, 15000),
(5236, 2, 1, 25000, 25000);


-- =========================================================
-- ANALYTICAL QUERIES FOR REPORTS
-- =========================================================

-- Query A: Mengambil detail nota untuk struk TRX-5236
SELECT 
    t.id AS transaksi_id,
    t.created_at AS tanggal_transaksi,
    u.nama AS nama_kasir,
    p.nama_produk,
    dt.jumlah,
    dt.harga,
    dt.subtotal,
    t.total_harga AS grand_total,
    t.metode_pembayaran
FROM transaksis t
JOIN users u ON t.user_id = u.id
JOIN detail_transaksis dt ON dt.transaksi_id = t.id
JOIN produks p ON dt.produk_id = p.id
WHERE t.id = 5236;

-- Query B: Rekap total pendapatan harian
SELECT 
    DATE(created_at) AS tanggal,
    COUNT(id) AS jumlah_transaksi,
    SUM(total_harga) AS total_pendapatan
FROM transaksis
GROUP BY DATE(created_at)
ORDER BY tanggal DESC;
