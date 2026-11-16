<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
?>

<div class="container my-4">
    <div class="sb-page-header">
        <div>
            <h1 class="sb-page-title">Data Petugas Kasir</h1>
            <p class="sb-page-desc">Daftar akun dan staf kasir operasional Starbucks</p>
        </div>
        <a href="/starbuck/pages/cashiers/add.php" class="btn-sb-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Kasir
        </a>
    </div>

    <div class="sb-table-card">
        <div class="table-responsive">
            <table class="sb-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 12%;">ID</th>
                        <th style="width: 25%;">Nama Kasir</th>
                        <th style="width: 20%;">Kontak</th>
                        <th style="width: 23%;">Alamat</th>
                        <th style="width: 15%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include ROOTPATH . '/config/config.php';
                    /** @var mysqli $koneksi */
                    $query = "SELECT * FROM kasir ORDER BY id_kasir ASC";
                    $result = mysqli_query($koneksi, $query);
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td class="text-muted num-tabular"><?php echo $no++; ?></td>
                        <td>
                            <span class="num-tabular font-weight-bold">#<?php echo $row['id_kasir']; ?></span>
                        </td>
                        <td>
                            <strong style="color: #ffffff;"><?php echo htmlspecialchars($row['nama_kasir']); ?></strong>
                            <small class="text-muted d-block"><?php echo htmlspecialchars($row['email']); ?></small>
                        </td>
                        <td class="num-tabular"><?php echo htmlspecialchars($row['no_telp']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td style="text-align: right;">
                            <a href='edit.php?id_kasir=<?php echo $row['id_kasir']; ?>' class='btn-action-edit'>Edit</a>
                            <a href='/starbuck/process/process_cashiers.php?action=delete&id_kasir=<?php echo $row['id_kasir']; ?>'
                                class='btn-action-delete'
                                onclick="return confirm('Hapus kasir ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include ROOTPATH . '/include/footer.php';
?>
