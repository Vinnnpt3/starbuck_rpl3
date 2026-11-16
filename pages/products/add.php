<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="sb-card">
                <div class="sb-card-title pb-2 border-bottom" style="border-color: var(--sb-border);">
                    <span>Tambah Produk Baru</span>
                    <a href="list.php" class="btn-sb-secondary" style="font-size: 0.78rem; padding: 0.2rem 0.55rem;">Kembali</a>
                </div>

                <form action="../../process/process_products.php" method="post" class="mt-3">
                    <input type="hidden" name="action" value="add">

                    <div class="sb-form-group">
                        <label for="id_produk" class="sb-form-label">ID / Kode Produk</label>
                        <input type="number" name="id_produk" class="sb-input" id="id_produk" placeholder="Contoh: 101" required>
                    </div>

                    <div class="sb-form-group">
                        <label for="nama_produk" class="sb-form-label">Nama Menu</label>
                        <input type="text" name="nama_produk" class="sb-input" id="nama_produk" placeholder="Contoh: Iced Caramel Macchiato" required>
                    </div>

                    <div class="sb-form-group mb-4">
                        <label for="harga" class="sb-form-label">Harga Satuan (Rp)</label>
                        <input type="number" step="0.001" name="harga" class="sb-input" id="harga" placeholder="Contoh: 55.000" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-sb-primary" style="flex: 1; justify-content: center;">
                            Simpan Produk
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
