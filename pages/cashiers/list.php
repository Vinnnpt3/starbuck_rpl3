<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../../include/header.php';

/** @var mysqli $koneksi */

// 2. Query mengambil data seluruh kasir terdaftar
$query  = "SELECT * FROM kasir ORDER BY id_kasir ASC";
$result = mysqli_query($koneksi, $query);

$cashiers = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cashiers[] = $row;
}
?>

<div class="container my-4">
    <!-- Header Halaman -->
    <div class="sb-header-box">
        <div>
            <h1 class="sb-title">Data Petugas Kasir</h1>
            <p class="sb-desc">Daftar akun staf barista dan kasir yang memiliki akses POS di outlet</p>
        </div>
        <a href="/starbuck/pages/cashiers/add.php" class="sb-btn sb-btn-green">
            + Tambah Kasir Baru
        </a>
    </div>

    <!-- Kotak Pencarian Kasir -->
    <div class="sb-box mb-3 p-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <input type="text" id="cariKasir" class="sb-input-control" placeholder="Ketik nama, ID kasir, atau email petugas...">
            </div>
            <div class="col-12 col-md-6 text-md-end text-muted" style="font-size: 0.85rem;">
                Total: <strong><?php echo count($cashiers); ?></strong> staf terdaftar
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kasir -->
    <div class="sb-table-wrap">
        <div class="table-responsive">
            <table class="sb-main-table" id="tabelKasir">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 12%;">ID Kasir</th>
                        <th style="width: 25%;">Nama Petugas</th>
                        <th style="width: 16%;">Jabatan / Peran</th>
                        <th style="width: 16%;">No. HP / WA</th>
                        <th style="width: 16%;">Alamat Domisili</th>
                        <th style="width: 10%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kasirBody">
                    <?php
                    $no = 1;
                    foreach ($cashiers as $row) {
                        $role = !empty($row['role']) ? $row['role'] : 'Barista';
                        $roleClass = ($role === 'Store Manager' || $role === 'Shift Supervisor') ? 'sb-tag-caramel' : 'sb-tag-muted';
                    ?>
                    <tr class="kasir-row" data-search="<?php echo strtolower(htmlspecialchars($row['nama_kasir'] . ' ' . $row['email'] . ' ' . $row['id_kasir'] . ' ' . $role)); ?>">
                        <td class="text-muted"><?php echo $no++; ?></td>
                        <td>
                            <span class="sb-tag sb-tag-muted">KSR-<?php echo $row['id_kasir']; ?></span>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo htmlspecialchars($row['nama_kasir']); ?></strong>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($row['email']); ?></small>
                            </div>
                        </td>
                        <td>
                            <span class="sb-tag <?php echo $roleClass; ?>"><?php echo htmlspecialchars($role); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td style="text-align: right;">
                            <a href='edit.php?id_kasir=<?php echo $row['id_kasir']; ?>' class='sb-action-btn sb-btn-edit'>Edit</a>
                            <a href='/starbuck/process/process_cashiers.php?action=delete&id_kasir=<?php echo $row['id_kasir']; ?>'
                                class='sb-action-btn sb-btn-del'
                                onclick="return confirm('Apakah Anda yakin ingin menghapus kasir <?php echo htmlspecialchars($row['nama_kasir']); ?>?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('cariKasir');
    const rows = document.querySelectorAll('.kasir-row');

    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        rows.forEach(row => {
            const text = row.getAttribute('data-search');
            if (q === '' || text.includes(q)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>

<?php
// 4. Panggil file template footer
include __DIR__ . '/../../include/footer.php';
?>
