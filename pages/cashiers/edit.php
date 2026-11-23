<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../../include/header.php';

/** @var mysqli $koneksi */

// 2. Ambil parameter id_kasir dari URL
$id = $_GET['id_kasir'] ?? '';

// 3. Cari data kasir lama
$query  = "SELECT * FROM kasir WHERE id_kasir = '$id'";
$result = mysqli_query($koneksi, $query);
$row    = mysqli_fetch_assoc($result);

// Jika kasir tidak ditemukan, kembalikan ke daftar
if (!$row) {
    echo "<script>alert('Data kasir tidak ditemukan!'); window.location.href='list.php';</script>";
    exit;
}
$current_role = $row['role'] ?? 'Barista';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="sb-box">
                <!-- Header Box Form -->
                <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom" style="border-color: var(--sb-border);">
                    <div>
                        <h2 class="h5 m-0 font-weight-bold">Ubah Data Kasir #<?php echo $row['id_kasir']; ?></h2>
                        <small class="text-muted">Perbarui data kontak, peran, atau nama petugas</small>
                    </div>
                    <a href="list.php" class="sb-btn sb-btn-secondary" style="font-size: 0.78rem; padding: 0.25rem 0.6rem;">Kembali</a>
                </div>

                <!-- Form Edit Data Kasir -->
                <form action="../../process/process_cashiers.php" method="post" class="mt-3">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id_kasir" value="<?php echo $row['id_kasir']; ?>">

                    <div class="sb-field">
                        <label for="role" class="sb-label">Jabatan / Peran <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="sb-input-control" required>
                            <?php 
                            $roles = ['Barista', 'Shift Supervisor', 'Head Cashier', 'Store Manager'];
                            foreach ($roles as $r) {
                                $sel = ($current_role === $r) ? 'selected' : '';
                                echo "<option value=\"$r\" $sel>$r</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="sb-field">
                        <label for="nama_kasir" class="sb-label">Nama Lengkap Petugas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kasir" value="<?php echo htmlspecialchars($row['nama_kasir']); ?>" class="sb-input-control" id="nama_kasir" required>
                    </div>

                    <div class="sb-field">
                        <label for="no_telp" class="sb-label">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="tel" name="no_telp" value="<?php echo htmlspecialchars($row['no_telp']); ?>" class="sb-input-control" id="no_telp" required>
                    </div>

                    <div class="sb-field">
                        <label for="alamat" class="sb-label">Alamat Domisili <span class="text-danger">*</span></label>
                        <input type="text" name="alamat" value="<?php echo htmlspecialchars($row['alamat']); ?>" class="sb-input-control" id="alamat" required>
                    </div>

                    <div class="sb-field mb-4">
                        <label for="email" class="sb-label">Alamat Email Kasir <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="sb-input-control" id="email" required>
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
