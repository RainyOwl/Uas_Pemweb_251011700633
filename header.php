<?php
/**
 * Koneksi Database - Rainy Mochi
 * Menggunakan PDO + prepared statements untuk mencegah SQL Injection
 */

$DB_HOST = 'localhost';
$DB_NAME = 'rainy_mochi';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Wajib: gunakan native prepared statement (bukan emulasi) agar aman dari SQL Injection
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()));
}
