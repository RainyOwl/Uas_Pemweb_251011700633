<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // Ambil nama file gambar dulu (prepared statement)
    $stmt = $pdo->prepare('SELECT gambar FROM produk WHERE id = ?');
    $stmt->execute([$id]);
    $produk = $stmt->fetch();

    if ($produk) {
        $del = $pdo->prepare('DELETE FROM produk WHERE id = ?');
        $del->execute([$id]);

        if (!empty($produk['gambar']) && file_exists(__DIR__ . '/uploads/' . $produk['gambar'])) {
            @unlink(__DIR__ . '/uploads/' . $produk['gambar']);
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk berhasil dihapus.'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Produk tidak ditemukan.'];
    }
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID produk tidak valid.'];
}

header('Location: index.php');
exit;
