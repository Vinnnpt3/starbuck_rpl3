<?php
// 1. Panggil file template header
include __DIR__ . '/../../include/header.php';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="sb-box">
                <!-- Header Box Form -->
                <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom" style="border-color: var(--sb-border);">
                    <div>
                        <h2 class="h5 m-0 font-weight-bold">Tambah Menu Produk Baru</h2>
                        <small class="text-muted">Masukkan menu kopi, minuman, atau makanan Starbucks baru</small>
                    </div>
                    <a href="list.php" class="sb-btn sb-btn-secondary" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Kembali</a>
                </div>

                <!-- Form Tambah Produk -->
                <form action="../../process/process_products.php" method="post" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="action" value="add">

                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <div class="sb-field">
                                <label for="id_produk" class="sb-label">Kode / ID <span class="text-danger">*</span></label>
                                <input type="number" name="id_produk" class="sb-input-control" id="id_produk" placeholder="Contoh: 18" required>
                            </div>
                        </div>

                        <div class="col-12 col-sm-8">
                            <div class="sb-field">
                                <label for="kategori" class="sb-label">Kategori Menu <span class="text-danger">*</span></label>
                                <select name="kategori" id="kategori" class="sb-input-control" required>
                                    <option value="Espresso & Coffee">Espresso & Coffee</option>
                                    <option value="Brewed Coffee">Brewed Coffee</option>
                                    <option value="Cold Brew">Cold Brew</option>
                                    <option value="Frappuccino">Frappuccino</option>
                                    <option value="Tea & Chocolate">Tea & Chocolate</option>
                                    <option value="Refreshers">Refreshers</option>
                                    <option value="Bakery & Pastry">Bakery & Pastry</option>
                                    <option value="Snacks & Appetizers">Snacks & Appetizers</option>
                                    <option value="Food & Sandwiches">Food & Sandwiches</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="sb-field">
                        <label for="nama_produk" class="sb-label">Nama Menu Starbucks <span class="text-danger">*</span></label>
                        <input type="text" name="nama_produk" class="sb-input-control" id="nama_produk" placeholder="Contoh: Iced Caramel Cloud Macchiato" required>
                    </div>

                    <div class="sb-field">
                        <label for="harga" class="sb-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                        <input type="number" step="0.001" name="harga" class="sb-input-control" id="harga" placeholder="Contoh: 59.000" required>
                    </div>

                    <div class="sb-field mb-4">
                        <label for="gambar_file" class="sb-label">Upload Foto Menu <small class="text-muted">(JPG, PNG, atau WEBP)</small></label>
                        <input type="file" name="gambar_file" class="sb-input-control" id="gambar_file" accept="image/*">
                        <small class="text-muted mt-1 d-block">Bila tidak diupload, sistem akan menggunakan gambar default.</small>
                    </div>

                    <!-- Tombol Aksi Simpan -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="sb-btn sb-btn-green" style="flex: 1; justify-content: center;">
                            Simpan Menu Baru
                        </button>
                        <a href="list.php" class="sb-btn sb-btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// 2. Panggil file template footer
include __DIR__ . '/../../include/footer.php';
?>
