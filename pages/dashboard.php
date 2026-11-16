<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
include ROOTPATH . '/config/config.php';

/** @var mysqli $koneksi */

// Hitung data statistik
$total_kasir_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kasir");
$total_kasir = mysqli_fetch_assoc($total_kasir_q)['total'] ?? 0;

$total_produk_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$total_produk = mysqli_fetch_assoc($total_produk_q)['total'] ?? 0;

$total_tx_q = mysqli_query($koneksi, "SELECT COUNT(*) as total, SUM(total) as revenue FROM transaksi");
$tx_stats = mysqli_fetch_assoc($total_tx_q);
$total_transaksi = $tx_stats['total'] ?? 0;
$total_revenue = $tx_stats['revenue'] ?? 0;

// Ambil 5 transaksi terbaru
$recent_tx_q = mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY id_transaksi DESC LIMIT 5");
?>

<div class="container my-4">
    <!-- Page Header -->
    <div class="sb-page-header">
        <div>
            <h1 class="sb-page-title">Dashboard Operasional</h1>
            <p class="sb-page-desc">Ringkasan aktivitas penjualan dan operasional gerai Starbucks</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="sb-pill"><?php echo date('d M Y'); ?></span>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-stat-card">
                <div>
                    <div class="sb-stat-label">Total Kasir Aktif</div>
                    <div class="sb-stat-value"><?php echo $total_kasir; ?></div>
                </div>
                <div class="sb-stat-sub">Petugas terdaftar</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-stat-card">
                <div>
                    <div class="sb-stat-label">Katalog Produk</div>
                    <div class="sb-stat-value"><?php echo $total_produk; ?></div>
                </div>
                <div class="sb-stat-sub">Varian menu aktif</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-stat-card">
                <div>
                    <div class="sb-stat-label">Jumlah Transaksi</div>
                    <div class="sb-stat-value"><?php echo $total_transaksi; ?></div>
                </div>
                <div class="sb-stat-sub">Nota terselesaikan</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-stat-card">
                <div>
                    <div class="sb-stat-label">Total Omset Penjualan</div>
                    <div class="sb-stat-value text-price" style="font-size: 1.45rem;">
                        Rp <?php echo number_format($total_revenue, 2, ',', '.'); ?>
                    </div>
                </div>
                <div class="sb-stat-sub">Akumulasi pendapatan</div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Quick Actions -->
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="sb-table-card">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color: var(--sb-border);">
                    <h2 class="h6 m-0 font-weight-bold">Transaksi Terbaru</h2>
                    <a href="/starbuck/pages/transactions/list.php" class="btn-sb-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.78rem;">
                        Lihat Semua
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="sb-table">
                        <thead>
                            <tr>
                                <th>No. Nota</th>
                                <th>Waktu</th>
                                <th>Total</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($recent_tx_q) > 0) {
                                while ($tx = mysqli_fetch_assoc($recent_tx_q)) { ?>
                                    <tr>
                                        <td>
                                            <span class="num-tabular font-weight-bold">#TRX-<?php echo str_pad($tx['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo date('d/m/Y', strtotime($tx['tanggal'])); ?></span>
                                            <small class="text-muted d-block"><?php echo $tx['waktu']; ?></small>
                                        </td>
                                        <td>
                                            <span class="num-tabular text-price">Rp <?php echo number_format($tx['total'], 2, ',', '.'); ?></span>
                                        </td>
                                        <td style="text-align: right;">
                                            <a href="/starbuck/pages/transactions/detail.php?id_transaksi=<?php echo $tx['id_transaksi']; ?>" class="btn-action-view">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                            <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="sb-card">
                <div class="sb-card-title">Pintasan Cepat</div>
                <div class="d-flex flex-column gap-2">
                    <a href="/starbuck/pages/transactions/add.php" class="btn-sb-primary justify-content-between p-3" style="font-size: 0.9rem;">
                        <span>Kasir Baru / Checkout</span>
                        <span>→</span>
                    </a>
                    <a href="/starbuck/pages/cashiers/add.php" class="btn-sb-secondary justify-content-between p-3">
                        <span>Tambah Data Kasir</span>
                        <span>+</span>
                    </a>
                    <a href="/starbuck/pages/products/add.php" class="btn-sb-secondary justify-content-between p-3">
                        <span>Tambah Menu Produk</span>
                        <span>+</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include ROOTPATH . '/include/footer.php';
?>
