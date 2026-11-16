<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
?>

<div class="container my-4">
    <div class="sb-page-header">
        <div>
            <h1 class="sb-page-title">Katalog Menu & Produk</h1>
            <p class="sb-page-desc">Daftar harga dan item minuman/makanan Starbucks</p>
        </div>
        <a href="/starbuck/pages/products/add.php" class="btn-sb-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Produk
        </a>
    </div>

    <div class="sb-table-card">
        <div class="table-responsive">
            <table class="sb-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 15%;">Kode Produk</th>
                        <th style="width: 50%;">Nama Menu</th>
                        <th style="width: 18%;">Harga Satuan</th>
                        <th style="width: 12%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include ROOTPATH . '/config/config.php';
                    /** @var mysqli $koneksi */
                    $query = "SELECT * FROM produk ORDER BY id_produk ASC";
                    $result = mysqli_query($koneksi, $query);
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td class="text-muted num-tabular"><?php echo $no++; ?></td>
                        <td>
                            <span class="num-tabular font-weight-bold">PRD-<?php echo str_pad($row['id_produk'], 3, '0', STR_PAD_LEFT); ?></span>
                        </td>
                        <td>
                            <strong style="color: #ffffff;"><?php echo htmlspecialchars($row['nama_produk']); ?></strong>
                        </td>
                        <td>
                            <span class="num-tabular text-price">Rp <?php echo number_format($row['harga'], 3, ',', '.'); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <a href='edit.php?id_produk=<?php echo $row['id_produk']; ?>' class='btn-action-edit'>Edit</a>
                            <a href='/starbuck/process/process_products.php?action=delete&id_produk=<?php echo $row['id_produk']; ?>'
                                class='btn-action-delete'
                                onclick="return confirm('Hapus produk ini?')">Hapus</a>
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
