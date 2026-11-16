<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
include ROOTPATH . '/config/config.php';

/** @var mysqli $koneksi */

$id = $_GET['id_produk'] ?? '';
$query = "SELECT * FROM produk WHERE id_produk = '$id'";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location.href='list.php';</script>";
    exit;
}
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="sb-card">
                <div class="sb-card-title pb-2 border-bottom" style="border-color: var(--sb-border);">
                    <span>Ubah Menu Produk #<?php echo $row['id_produk']; ?></span>
                    <a href="list.php" class="btn-sb-secondary" style="font-size: 0.78rem; padding: 0.2rem 0.55rem;">Kembali</a>
                </div>

                <form action="../../process/process_products.php" method="post" class="mt-3">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">

                    <div class="sb-form-group">
                        <label for="nama_produk" class="sb-form-label">Nama Menu</label>
                        <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($row['nama_produk']); ?>" class="sb-input" id="nama_produk" required>
                    </div>

                    <div class="sb-form-group mb-4">
                        <label for="harga" class="sb-form-label">Harga Satuan (Rp)</label>
                        <input type="number" step="0.001" name="harga" value="<?php echo $row['harga']; ?>" class="sb-input" id="harga" required>
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
