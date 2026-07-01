-- =========================================================
-- Database: rainy_mochi
-- Aplikasi CRUD Produk - Brand: Rainy Mochi
-- =========================================================

CREATE DATABASE IF NOT EXISTS rainy_mochi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rainy_mochi;

-- =========================================================
-- Tabel users (untuk login)
-- =========================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin
-- username: admin
-- password: admin123
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2b$12$FNMst7hl1rvqgPj1kURBkOw4HXk3r4l86E/dXVFdC.08ju4P4GRPi', 'Admin Rainy Mochi');

-- =========================================================
-- Tabel produk
-- =========================================================
CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_produk VARCHAR(20) NOT NULL UNIQUE,
    nama_produk VARCHAR(150) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contoh data produk mochi
INSERT INTO produk (kode_produk, nama_produk, kategori, deskripsi, harga, stok, gambar) VALUES
('RM-001', 'Mochi Taro Ungu', 'Original', 'Mochi lembut isi krim taro ungu asli, manis dan legit.', 12000, 50, NULL),
('RM-002', 'Mochi Matcha', 'Original', 'Mochi dengan aroma matcha Jepang yang khas dan isian kacang merah.', 13000, 40, NULL),
('RM-003', 'Mochi Cokelat Lumer', 'Premium', 'Mochi isi cokelat leleh, favorit anak muda.', 15000, 35, NULL),
('RM-004', 'Mochi Strawberry Fresh', 'Fresh Fruit', 'Mochi isi buah strawberry segar dan krim vanilla.', 17000, 25, NULL),
('RM-005', 'Mochi Es Krim Vanilla', 'Ice Mochi', 'Mochi dingin isi es krim vanilla premium.', 18000, 30, NULL);
