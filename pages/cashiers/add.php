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
                    <span>Tambah Kasir Baru</span>
                    <a href="list.php" class="btn-sb-secondary" style="font-size: 0.78rem; padding: 0.2rem 0.55rem;">Kembali</a>
                </div>

                <form action="../../process/process_cashiers.php" method="post" class="mt-3">
                    <input type="hidden" name="action" value="add">

                    <div class="sb-form-group">
                        <label for="id" class="sb-form-label">ID Kasir (Opsional)</label>
                        <input type="number" name="id" class="sb-input" id="id" placeholder="Kosongkan untuk otomatis">
                    </div>

                    <div class="sb-form-group">
                        <label for="nama_kasir" class="sb-form-label">Nama Lengkap</label>
                        <input type="text" name="nama_kasir" class="sb-input" id="nama_kasir" placeholder="Contoh: Jessica Miller" required>
                    </div>

                    <div class="sb-form-group">
                        <label for="no_telp" class="sb-form-label">Nomor Telepon</label>
                        <input type="tel" name="no_telp" class="sb-input" id="no_telp" placeholder="Contoh: 081234567890" required>
                    </div>

                    <div class="sb-form-group">
                        <label for="alamat" class="sb-form-label">Alamat</label>
                        <input type="text" name="alamat" class="sb-input" id="alamat" placeholder="Contoh: Jl. Sunset Road No. 88" required>
                    </div>

                    <div class="sb-form-group mb-4">
                        <label for="email" class="sb-form-label">Email Kasir</label>
                        <input type="email" name="email" class="sb-input" id="email" placeholder="jessica@starbucks.co.id" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-sb-primary" style="flex: 1; justify-content: center;">
                            Simpan Data Kasir
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
