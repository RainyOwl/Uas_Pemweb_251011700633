<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_login();

$page_title = 'Edit Produk';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID produk tidak valid.'];
    header('Location: index.php');
    exit;
}

// Ambil data produk (prepared statement)
$stmt = $pdo->prepare('SELECT * FROM produk WHERE id = ?');
$stmt->execute([$id]);
$produk = $stmt->fetch();

if (!$produk) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Produk tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

$errors = [];
$old = $produk;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Sesi form tidak valid, silakan coba lagi.';
    } else {
        $old['kode_produk'] = trim($_POST['kode_produk'] ?? '');
        $old['nama_produk'] = trim($_POST['nama_produk'] ?? '');
        $old['kategori']    = trim($_POST['kategori'] ?? '');
        $old['deskripsi']   = trim($_POST['deskripsi'] ?? '');
        $old['harga']       = trim($_POST['harga'] ?? '');
        $old['stok']        = trim($_POST['stok'] ?? '');

        if ($old['kode_produk'] === '') $errors[] = 'Kode produk wajib diisi.';
        if ($old['nama_produk'] === '') $errors[] = 'Nama produk wajib diisi.';
        if ($old['kategori'] === '') $errors[] = 'Kategori wajib diisi.';
        if (!is_numeric($old['harga']) || (float)$old['harga'] < 0) $errors[] = 'Harga tidak valid.';
        if (!ctype_digit((string)$old['stok'])) $errors[] = 'Stok harus berupa angka bulat.';

        // Cek duplikasi kode produk selain milik sendiri
        if (empty($errors)) {
            $cek = $pdo->prepare('SELECT id FROM produk WHERE kode_produk = ? AND id != ?');
            $cek->execute([$old['kode_produk'], $id]);
            if ($cek->fetch()) {
                $errors[] = 'Kode produk sudah digunakan produk lain.';
            }
        }

        $filenameToSave = $produk['gambar']; // default: gambar lama tetap dipakai

        if (empty($errors) && isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['gambar'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Gagal mengunggah gambar.';
            } else {
                $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
                $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedMime, true)) {
                    $errors[] = 'Format gambar harus JPG, PNG, atau WEBP.';
                } elseif ($file['size'] > 2 * 1024 * 1024) {
                    $errors[] = 'Ukuran gambar maksimal 2MB.';
                } else {
                    $newFilename = uniqid('mochi_', true) . '.' . $ext;
                    $destination = __DIR__ . '/uploads/' . $newFilename;
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        // Hapus gambar lama jika ada
                        if (!empty($produk['gambar']) && file_exists(__DIR__ . '/uploads/' . $produk['gambar'])) {
                            @unlink(__DIR__ . '/uploads/' . $produk['gambar']);
                        }
                        $filenameToSave = $newFilename;
                    } else {
                        $errors[] = 'Gagal menyimpan gambar baru.';
                    }
                }
            }
        }

        // Hapus gambar (checkbox "hapus gambar")
        if (empty($errors) && isset($_POST['hapus_gambar']) && $_POST['hapus_gambar'] === '1') {
            if (!empty($produk['gambar']) && file_exists(__DIR__ . '/uploads/' . $produk['gambar'])) {
                @unlink(__DIR__ . '/uploads/' . $produk['gambar']);
            }
            $filenameToSave = null;
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare(
                'UPDATE produk SET kode_produk=:kode, nama_produk=:nama, kategori=:kategori,
                 deskripsi=:deskripsi, harga=:harga, stok=:stok, gambar=:gambar WHERE id=:id'
            );
            $stmt->execute([
                'kode'      => $old['kode_produk'],
                'nama'      => $old['nama_produk'],
                'kategori'  => $old['kategori'],
                'deskripsi' => $old['deskripsi'],
                'harga'     => $old['harga'],
                'stok'      => $old['stok'],
                'gambar'    => $filenameToSave,
                'id'        => $id,
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk berhasil diperbarui.'];
            header('Location: index.php');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<h4 class="section-title">✏️ Edit Produk</h4>

<div class="card card-mochi">
  <div class="card-body">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $err): ?><li><?php echo e($err); ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?php echo (int)$id; ?>" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Kode Produk</label>
          <input type="text" name="kode_produk" class="form-control" maxlength="20" required
                 value="<?php echo e($old['kode_produk']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" maxlength="150" required
                 value="<?php echo e($old['nama_produk']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Kategori</label>
          <input type="text" name="kategori" class="form-control" maxlength="50" required
                 value="<?php echo e($old['kategori']); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Harga (Rp)</label>
          <input type="number" step="0.01" min="0" name="harga" class="form-control" required
                 value="<?php echo e($old['harga']); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Stok</label>
          <input type="number" min="0" name="stok" class="form-control" required
                 value="<?php echo e($old['stok']); ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="3"><?php echo e($old['deskripsi']); ?></textarea>
        </div>
        <div class="col-md-12">
          <label class="form-label">Gambar Produk</label><br>
          <?php if (!empty($produk['gambar']) && file_exists(__DIR__ . '/uploads/' . $produk['gambar'])): ?>
            <img src="uploads/<?php echo e($produk['gambar']); ?>" class="preview-img mb-2"><br>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="hapus_gambar" value="1" id="hapusGambar">
              <label class="form-check-label" for="hapusGambar">Hapus gambar saat ini</label>
            </div>
          <?php endif; ?>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
          <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-mochi"><i class="bi bi-save"></i> Update</button>
        <a href="index.php" class="btn btn-outline-mochi">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
