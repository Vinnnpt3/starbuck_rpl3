<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
include ROOTPATH . '/config/config.php';

/** @var mysqli $koneksi */

$id_transaksi = $_GET['id_transaksi'] ?? '';
$query_tx = "SELECT t.*, dt.id_nota, k.nama_kasir 
             FROM transaksi t
             LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
             LEFT JOIN kasir k ON dt.id_kasir = k.id_kasir
             WHERE t.id_transaksi = '$id_transaksi'
             LIMIT 1";
$result_tx = mysqli_query($koneksi, $query_tx);
$tx = mysqli_fetch_assoc($result_tx);

if (!$tx) {
    echo "<script>alert('Transaksi tidak ditemukan!'); window.location.href='list.php';</script>";
    exit;
}

$query_items = "SELECT dt.*, p.nama_produk 
                FROM detail_transaksi dt
                LEFT JOIN produk p ON dt.id_produk = p.id_produk
                WHERE dt.id_transaksi = '$id_transaksi'";
$result_items = mysqli_query($koneksi, $query_items);
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="list.php" class="btn-sb-secondary">
                    ← Kembali ke Daftar Transaksi
                </a>
                <button onclick="window.print()" class="btn-sb-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak Struk
                </button>
            </div>

            <div class="sb-card p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4" style="border-color: var(--sb-border);">
                    <div>
                        <div class="text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; font-weight: 600;">Struk Transaksi</div>
                        <h2 class="h4 m-0 font-weight-bold" style="color: #ffffff;">#TRX-<?php echo str_pad($tx['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></h2>
                        <small class="text-muted">No. Nota: <?php echo htmlspecialchars($tx['id_nota'] ?? '-'); ?></small>
                    </div>
                    <div class="text-end">
                        <div class="num-tabular" style="color: #ffffff;"><?php echo date('d M Y', strtotime($tx['tanggal'])); ?> - <?php echo htmlspecialchars($tx['waktu']); ?></div>
                        <small class="text-muted">Petugas Kasir: <strong style="color: #e5e7eb;"><?php echo htmlspecialchars($tx['nama_kasir'] ?? 'Staf Kasir'); ?></strong></small>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="sb-table">
                        <thead>
                            <tr>
                                <th>Item Pesanan</th>
                                <th>Kategori</th>
                                <th style="text-align: right;">Harga</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($result_items)) { ?>
                            <tr>
                                <td>
                                    <strong style="color: #ffffff;"><?php echo htmlspecialchars($item['nama_produk'] ?? 'Produk #' . $item['id_produk']); ?></strong>
                                </td>
                                <td>
                                    <span class="sb-pill"><?php echo htmlspecialchars($item['kategori'] ?? 'Minuman'); ?></span>
                                </td>
                                <td style="text-align: right;" class="num-tabular">
                                    Rp <?php echo number_format($item['harga'], 3, ',', '.'); ?>
                                </td>
                                <td style="text-align: center;" class="num-tabular">
                                    <?php echo $item['kuantitas']; ?>
                                </td>
                                <td style="text-align: right;" class="num-tabular font-weight-bold">
                                    Rp <?php echo number_format($item['kuantitas_harga'], 3, ',', '.'); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-12 col-md-5">
                        <div class="sb-receipt-summary">
                            <div class="sb-receipt-row">
                                <span>Subtotal</span>
                                <span class="num-tabular">Rp <?php echo number_format($tx['subtotal'], 3, ',', '.'); ?></span>
                            </div>
                            <div class="sb-receipt-row">
                                <span>Pajak (11%)</span>
                                <span class="num-tabular">Rp <?php echo number_format($tx['pajak'], 3, ',', '.'); ?></span>
                            </div>
                            <div class="sb-receipt-total">
                                <span>Total Bayar</span>
                                <span class="num-tabular text-price">Rp <?php echo number_format($tx['total'], 3, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include ROOTPATH . '/include/footer.php';
?>
