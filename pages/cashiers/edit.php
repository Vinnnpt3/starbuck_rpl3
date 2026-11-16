<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
include ROOTPATH . '/config/config.php';

/** @var mysqli $koneksi */

$id = $_GET['id_kasir'] ?? '';
$query = "SELECT * FROM kasir WHERE id_kasir = '$id'";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<script>alert('Data kasir tidak ditemukan!'); window.location.href='list.php';</script>";
    exit;
}
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="sb-card">
                <div class="sb-card-title pb-2 border-bottom" style="border-color: var(--sb-border);">
                    <span>Ubah Data Kasir #<?php echo $row['id_kasir']; ?></span>
                    <a href="list.php" class="btn-sb-secondary" style="font-size: 0.78rem; padding: 0.2rem 0.55rem;">Kembali</a>
                </div>

                <form action="../../process/process_cashiers.php" method="post" class="mt-3">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id_kasir" value="<?php echo $row['id_kasir']; ?>">

                    <div class="sb-form-group">
                        <label for="nama_kasir" class="sb-form-label">Nama Lengkap</label>
                        <input type="text" name="nama_kasir" value="<?php echo htmlspecialchars($row['nama_kasir']); ?>" class="sb-input" id="nama_kasir" required>
                    </div>

                    <div class="sb-form-group">
                        <label for="no_telp" class="sb-form-label">Nomor Telepon</label>
                        <input type="tel" name="no_telp" value="<?php echo htmlspecialchars($row['no_telp']); ?>" class="sb-input" id="no_telp" required>
                    </div>

                    <div class="sb-form-group">
                        <label for="alamat" class="sb-form-label">Alamat</label>
                        <input type="text" name="alamat" value="<?php echo htmlspecialchars($row['alamat']); ?>" class="sb-input" id="alamat" required>
                    </div>

                    <div class="sb-form-group mb-4">
                        <label for="email" class="sb-form-label">Email Kasir</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="sb-input" id="email" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-sb-primary" style="flex: 1; justify-content: center;">
                            Simpan Perubahan
                        </button>
                        <a href="list.php" class="btn-sb-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include ROOTPATH . '/include/footer.php';
?>
