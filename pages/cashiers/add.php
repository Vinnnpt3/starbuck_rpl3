<?php
// 1. Panggil file template header
include __DIR__ . '/../../include/header.php';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="sb-box">
                <!-- Header Box Form -->
                <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom" style="border-color: var(--sb-border);">
                    <div>
                        <h2 class="h5 m-0 font-weight-bold">Tambah Kasir Baru</h2>
                        <small class="text-muted">Daftarkan akun staf barista / kasir baru ke sistem POS</small>
                    </div>
                    <a href="list.php" class="sb-btn sb-btn-secondary" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Kembali</a>
                </div>

                <!-- Form Tambah Kasir -->
                <form action="../../process/process_cashiers.php" method="post" class="mt-3">
                    <input type="hidden" name="action" value="add">

                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <div class="sb-field">
                                <label for="id" class="sb-label">ID Kasir <small class="text-muted">(Opsional)</small></label>
                                <input type="number" name="id" class="sb-input-control" id="id" placeholder="Otomatis sistem">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="sb-field">
                                <label for="role" class="sb-label">Jabatan / Peran <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="sb-input-control" required>
                                    <option value="Barista">Barista</option>
                                    <option value="Shift Supervisor">Shift Supervisor</option>
                                    <option value="Head Cashier">Head Cashier</option>
                                    <option value="Store Manager">Store Manager</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="sb-field">
                        <label for="nama_kasir" class="sb-label">Nama Lengkap Petugas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kasir" class="sb-input-control" id="nama_kasir" placeholder="Contoh: Jessica Miller" required>
                    </div>

                    <div class="sb-field">
                        <label for="no_telp" class="sb-label">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="tel" name="no_telp" class="sb-input-control" id="no_telp" placeholder="Contoh: 081234567890" required>
                    </div>

                    <div class="sb-field">
                        <label for="alamat" class="sb-label">Alamat Domisili <span class="text-danger">*</span></label>
                        <input type="text" name="alamat" class="sb-input-control" id="alamat" placeholder="Contoh: Jl. Sunset Road No. 88" required>
                    </div>

                    <div class="sb-field mb-4">
                        <label for="email" class="sb-label">Alamat Email Kasir <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="sb-input-control" id="email" placeholder="jessica@starbucks.co.id" required>
                    </div>

                    <!-- Tombol Aksi Simpan -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="sb-btn sb-btn-green" style="flex: 1; justify-content: center;">
                            Simpan Data Kasir
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
