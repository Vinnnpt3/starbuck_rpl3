<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
?>

<div class="container my-4">
    <div class="sb-page-header">
        <div>
            <h1 class="sb-page-title">Riwayat Transaksi Penjualan</h1>
            <p class="sb-page-desc">Rekapitulasi seluruh nota dan struk kasir Starbucks</p>
        </div>
        <a href="/starbuck/pages/transactions/add.php" class="btn-sb-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Transaksi Baru
        </a>
    </div>

    <div class="sb-table-card">
        <div class="table-responsive">
            <table class="sb-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 14%;">No. Nota</th>
                        <th style="width: 16%;">Tanggal & Waktu</th>
                        <th style="width: 15%;">Subtotal</th>
                        <th style="width: 15%;">Pajak (11%)</th>
                        <th style="width: 20%;">Total Pembayaran</th>
                        <th style="width: 15%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include ROOTPATH . '/config/config.php';
                    /** @var mysqli $koneksi */
                    $query = "SELECT * FROM transaksi ORDER BY id_transaksi DESC";
                    $result = mysqli_query($koneksi, $query);
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td class="text-muted num-tabular"><?php echo $no++; ?></td>
                        <td>
                            <span class="num-tabular font-weight-bold">#TRX-<?php echo str_pad($row['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></span>
                        </td>
                        <td>
                            <span class="num-tabular"><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></span>
                            <small class="text-muted d-block"><?php echo $row['waktu']; ?></small>
                        </td>
                        <td class="num-tabular">Rp <?php echo number_format($row['subtotal'], 3, ',', '.'); ?></td>
                        <td class="num-tabular">Rp <?php echo number_format($row['pajak'], 3, ',', '.'); ?></td>
                        <td>
                            <span class="num-tabular text-price">Rp <?php echo number_format($row['total'], 3, ',', '.'); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <a href='detail.php?id_transaksi=<?php echo $row['id_transaksi']; ?>' class='btn-action-view'>Detail</a>
                            <a href='/starbuck/process/process_transactions.php?action=delete&id_transaksi=<?php echo $row['id_transaksi']; ?>'
                                class='btn-action-delete'
                                onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
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
