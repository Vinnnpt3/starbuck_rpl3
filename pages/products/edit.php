<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../../include/header.php';

/** @var mysqli $koneksi */

// 2. Ambil parameter id_produk dari URL
$id = $_GET['id_produk'] ?? '';

// 3. Ambil data produk yang akan diubah
$query  = "SELECT * FROM produk WHERE id_produk = '$id'";
$result = mysqli_query($koneksi, $query);
$row    = mysqli_fetch_assoc($result);

// Jika produk tidak ada, kembali ke list
if (!$row) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location.href='list.php';</script>";
    exit;
}
$current_img = !empty($row['gambar']) ? $row['gambar'] : 'default.jpg';
$current_kat = !empty($row['kategori']) ? $row['kategori'] : 'Espresso & Coffee';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="sb-box">
                <!-- Header Box Form -->
                <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom" style="border-color: var(--sb-border);">
                    <div>
                        <h2 class="h5 m-0 font-weight-bold">Ubah Menu Produk #<?php echo $row['id_produk']; ?></h2>
                        <small class="text-muted">Perbarui foto, nama, kategori, atau harga menu Starbucks</small>
                    </div>
                    <a href="list.php" class="sb-btn sb-btn-secondary" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Kembali</a>
                </div>

                <!-- Form Edit Produk -->
                <form action="../../process/process_products.php" method="post" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">

                    <div class="sb-field">
                        <label for="kategori" class="sb-label">Kategori Menu <span class="text-danger">*</span></label>
                        <select name="kategori" id="kategori" class="sb-input-control" required>
                            <?php
                            $kats = [
                                'Espresso & Coffee', 'Brewed Coffee', 'Cold Brew', 
                                'Frappuccino', 'Tea & Chocolate', 'Refreshers', 
                                'Bakery & Pastry', 'Snacks & Appetizers', 'Food & Sandwiches'
                            ];
                            foreach ($kats as $k) {
                                $sel = ($current_kat == $k) ? 'selected' : '';
                                echo "<option value=\"$k\" $sel>$k</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="sb-field">
                        <label for="nama_produk" class="sb-label">Nama Menu Starbucks <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($row['nama_produk']); ?>" class="sb-input-control" id="nama_produk" required>
                    </div>

                    <div class="sb-field">
                        <label for="harga" class="sb-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                        <input type="number" step="0.001" name="harga" value="<?php echo $row['harga']; ?>" class="sb-input-control" id="harga" required>
                    </div>

                    <div class="sb-field mb-4">
                        <label class="sb-label">Foto Menu Saat Ini</label>
                        <div class="d-flex align-items-center gap-3 p-2 border rounded" style="border-color: var(--sb-border);">
                            <img src="/starbuck/assets/images/products/<?php echo htmlspecialchars($current_img); ?>" 
                                 alt="Preview" 
                                 class="sb-product-thumb" 
                                 style="width: 56px; height: 56px;"
                                 onerror="this.src='/starbuck/assets/images/products/default.jpg'">
                            <div>
                                <small class="text-muted d-block mb-1">Ganti foto (opsional):</small>
                                <input type="file" name="gambar_file" class="sb-input-control" accept="image/*" style="font-size: 0.8rem; padding: 0.35rem 0.6rem;">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="sb-btn sb-btn-green" style="flex: 1; justify-content: center;">
                            Simpan Perubahan
                        </button>
                        <a href="list.php" class="sb-btn sb-btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// 4. Panggil file template footer
include __DIR__ . '/../../include/footer.php';
?>
