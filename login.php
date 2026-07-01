<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_login();

$page_title = 'Tambah Produk';
$errors = [];
$old = ['kode_produk'=>'', 'nama_produk'=>'', 'kategori'=>'', 'deskripsi'=>'', 'harga'=>'', 'stok'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Sesi form tidak valid, silakan coba lagi.';
    } else {
        $old['kode_produk']  = trim($_POST['kode_produk'] ?? '');
        $old['nama_produk']  = trim($_POST['nama_produk'] ?? '');
        $old['kategori']     = trim($_POST['kategori'] ?? '');
        $old['deskripsi']    = trim($_POST['deskripsi'] ?? '');
        $old['harga']        = trim($_POST['harga'] ?? '');
        $old['stok']         = trim($_POST['stok'] ?? '');

        // ==== Validasi ====
        if ($old['kode_produk'] === '') $errors[] = 'Kode produk wajib diisi.';
        if ($old['nama_produk'] === '') $errors[] = 'Nama produk wajib diisi.';
        if ($old['kategori'] === '') $errors[] = 'Kategori wajib diisi.';
        if (!is_numeric($old['harga']) || (float)$old['harga'] < 0) $errors[] = 'Harga tidak valid.';
        if (!ctype_digit((string)$old['stok']) ) $errors[] = 'Stok harus berupa angka bulat.';

        // ==== Cek duplikasi kode produk (prepared statement) ====
        if (empty($errors)) {
            $cek = $pdo->prepare('SELECT id FROM produk WHERE kode_produk = ?');
            $cek->execute([$old['kode_produk']]);
            if ($cek->fetch()) {
                $errors[] = 'Kode produk sudah digunakan, gunakan kode lain.';
            }
        }

        // ==== Upload gambar (opsional) ====
        $filenameToSave = null;
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
                    $filenameToSave = uniqid('mochi_', true) . '.' . $ext;
                    $destination = __DIR__ . '/uploads/' . $filenameToSave;
                    if (!move_uploaded_file($file['tmp_name'], $destination)) {
                        $errors[] = 'Gagal menyimpan gambar ke server.';
                        $filenameToSave = null;
                    }
                }
            }
        }

        // ==== Simpan ke database (prepared statement) ====
        if (empty($errors)) {
            $stmt = $pdo->prepare(
                'INSERT INTO produk (kode_produk, nama_produk, kategori, deskripsi, harga, stok, gambar)
                 VALUES (:kode, :nama, :kategori, :deskripsi, :harga, :stok, :gambar)'
            );
            $stmt->execute([
                'kode'      => $old['kode_produk'],
                'nama'      => $old['nama_produk'],
                'kategori'  => $old['kategori'],
                'deskripsi' => $old['deskripsi'],
                'harga'     => $old['harga'],
                'stok'      => $old['stok'],
                'gambar'    => $filenameToSave,
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk berhasil ditambahkan.'];
            header('Location: index.php');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<h4 class="section-title">➕ Tambah Produk</h4>

<div class="card card-mochi">
  <div class="card-body">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $err): ?><li><?php echo e($err); ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="create.php" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Kode Produk</label>
          <input type="text" name="kode_produk" class="form-control" maxlength="20" required
                 value="<?php echo e($old['kode_produk']); ?>" placeholder="RM-006">
        </div>
        <div class="col-md-6">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" maxlength="150" required
                 value="<?php echo e($old['nama_produk']); ?>" placeholder="Mochi Kelapa">
        </div>
        <div class="col-md-6">
          <label class="form-label">Kategori</label>
          <input type="text" name="kategori" class="form-control" maxlength="50" required
                 value="<?php echo e($old['kategori']); ?>" placeholder="Original / Premium / Ice Mochi">
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
          <label class="form-label">Gambar Produk</label>
          <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
          <small class="text-muted">Format JPG/PNG/WEBP, maksimal 2MB.</small>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-mochi"><i class="bi bi-save"></i> Simpan</button>
        <a href="index.php" class="btn btn-outline-mochi">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
