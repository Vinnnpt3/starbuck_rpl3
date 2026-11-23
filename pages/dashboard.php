<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../include/header.php';

/** @var mysqli $koneksi */

// 2. Data Ringkasan Metrik
// Total Kasir
$q_kasir     = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kasir");
$total_kasir = mysqli_fetch_assoc($q_kasir)['total'] ?? 0;

// Total Produk
$q_produk     = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$total_produk = mysqli_fetch_assoc($q_produk)['total'] ?? 0;

// Total Transaksi & Akumulasi Penjualan
$q_transaksi     = mysqli_query($koneksi, "SELECT COUNT(*) as total, SUM(total) as revenue FROM transaksi");
$data_transaksi  = mysqli_fetch_assoc($q_transaksi);
$total_transaksi = $data_transaksi['total'] ?? 0;
$total_revenue   = $data_transaksi['revenue'] ?? 0;

// Penjualan Hari Ini
$today = date('Y-m-d');
$q_today     = mysqli_query($koneksi, "SELECT COUNT(*) as total, SUM(total) as revenue FROM transaksi WHERE tanggal = '$today'");
$data_today  = mysqli_fetch_assoc($q_today);
$today_tx    = $data_today['total'] ?? 0;
$today_rev   = $data_today['revenue'] ?? 0;

// 3. Ambil 5 Menu Terlaris (Best Seller)
$q_bestseller = mysqli_query($koneksi, "
    SELECT p.nama_produk, p.gambar, p.kategori, SUM(dt.kuantitas) as total_terjual, SUM(dt.kuantitas_harga) as total_omzet 
    FROM detail_transaksi dt 
    JOIN produk p ON dt.id_produk = p.id_produk 
    GROUP BY dt.id_produk 
    ORDER BY total_terjual DESC 
    LIMIT 5
");

// 4. Ambil 5 Transaksi Terakhir (dengan nama kasir)
$recent_tx_q = mysqli_query($koneksi, "
    SELECT t.*, k.nama_kasir 
    FROM transaksi t
    LEFT JOIN (SELECT DISTINCT id_transaksi, id_kasir FROM detail_transaksi) dt ON t.id_transaksi = dt.id_transaksi
    LEFT JOIN kasir k ON dt.id_kasir = k.id_kasir
    ORDER BY t.id_transaksi DESC 
    LIMIT 5
");
?>

<div class="container my-4">
    <!-- Banner Sambutan Elegan -->
    <div class="sb-welcome-banner">
        <div>
            <h1 class="sb-welcome-title">Selamat Datang di Starbucks POS</h1>
            <p class="sb-welcome-sub">Sistem kasir cerdas gerai resmi Starbucks Indonesia - Outlet #104 Denpasar</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="sb-badge-store">Denpasar Store</span>
            <span class="sb-badge-store"><?php echo date('d M Y'); ?></span>
        </div>
    </div>

    <!-- 4 Kotak Ringkasan Penjualan -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-kpi-card">
                <div class="sb-kpi-label">Kasir Terdaftar</div>
                <div class="sb-kpi-val"><?php echo $total_kasir; ?></div>
                <div class="sb-kpi-sub">Staf barista & kasir aktif</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-kpi-card">
                <div class="sb-kpi-label">Katalog Menu</div>
                <div class="sb-kpi-val"><?php echo $total_produk; ?></div>
                <div class="sb-kpi-sub">Varian minuman & bakery</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-kpi-card">
                <div class="sb-kpi-label">Penjualan Hari Ini</div>
                <div class="sb-kpi-val sb-price-tag" style="font-size: 1.35rem;">
                    Rp <?php echo number_format($today_rev, 2, ',', '.'); ?>
                </div>
                <div class="sb-kpi-sub"><?php echo $today_tx; ?> transaksi hari ini</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="sb-kpi-card">
                <div class="sb-kpi-label">Total Omzet Keseluruhan</div>
                <div class="sb-kpi-val sb-price-tag" style="font-size: 1.35rem;">
                    Rp <?php echo number_format($total_revenue, 2, ',', '.'); ?>
                </div>
                <div class="sb-kpi-sub">Dari <?php echo $total_transaksi; ?> nota selesai</div>
            </div>
        </div>
    </div>

    <!-- 2 Kolom: 5 Transaksi Terakhir & Menu Terlaris (Best Seller) -->
    <div class="row g-4">
        
        <!-- Kolom Kiri: 5 Transaksi Terakhir -->
        <div class="col-12 col-lg-7">
            <div class="sb-table-wrap h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color: var(--sb-border);">
                        <h2 class="h6 m-0 font-weight-bold">Transaksi Kasir Terbaru</h2>
                        <a href="/starbuck/pages/transactions/list.php" class="sb-btn sb-btn-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.78rem;">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="sb-main-table">
                            <thead>
                                <tr>
                                    <th>No. Struk</th>
                                    <th>Kasir</th>
                                    <th>Waktu</th>
                                    <th>Metode</th>
                                    <th>Total Bayar</th>
                                    <th style="text-align: right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (mysqli_num_rows($recent_tx_q) > 0) {
                                    while ($tx = mysqli_fetch_assoc($recent_tx_q)) { 
                                        $pay = !empty($tx['metode_pembayaran']) ? $tx['metode_pembayaran'] : 'Tunai';
                                        $payClass = ($pay === 'Tunai') ? 'sb-tag-muted' : 'sb-tag-green';
                                ?>
                                    <tr>
                                        <td>
                                            <span class="sb-tag sb-tag-muted">#TRX-<?php echo str_pad($tx['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></span>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($tx['nama_kasir'] ?? 'Barista'); ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo date('d/m', strtotime($tx['tanggal'])); ?> • <?php echo $tx['waktu']; ?></small>
                                        </td>
                                        <td>
                                            <span class="sb-tag <?php echo $payClass; ?>" style="font-size: 0.7rem;"><?php echo htmlspecialchars($pay); ?></span>
                                        </td>
                                        <td>
                                            <strong class="sb-price-tag">Rp <?php echo number_format($tx['total'], 3, ',', '.'); ?></strong>
                                        </td>
                                        <td style="text-align: right;">
                                            <a href="/starbuck/pages/transactions/detail.php?id_transaksi=<?php echo $tx['id_transaksi']; ?>" class="sb-action-btn sb-btn-view">
                                                Struk
                                            </a>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else { 
                                ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada data transaksi.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-3 border-top" style="border-color: var(--sb-border);">
                    <a href="/starbuck/pages/transactions/add.php" class="sb-btn sb-btn-green w-100 py-2">
                        + Buka Transaksi POS Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Top 5 Menu Terlaris (Best Seller) -->
        <div class="col-12 col-lg-5">
            <div class="sb-box h-100">
                <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom" style="border-color: var(--sb-border);">
                    <h2 class="h6 m-0 font-weight-bold">Menu Terlaris (Best Seller)</h2>
                    <a href="/starbuck/pages/products/list.php" class="sb-btn sb-btn-secondary" style="padding: 0.2rem 0.55rem; font-size: 0.75rem;">
                        Katalog
                    </a>
                </div>

                <div class="d-flex flex-column gap-3">
                    <?php 
                    if ($q_bestseller && mysqli_num_rows($q_bestseller) > 0) {
                        $rank = 1;
                        while ($bs = mysqli_fetch_assoc($q_bestseller)) { 
                            $img = !empty($bs['gambar']) ? $bs['gambar'] : 'default.jpg';
                    ?>
                        <div class="d-flex align-items-center justify-content-between p-2 rounded border" style="border-color: var(--sb-border);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="sb-tag sb-tag-muted" style="font-weight: 700;"><?php echo $rank++; ?></span>
                                <img src="/starbuck/assets/images/products/<?php echo htmlspecialchars($img); ?>" 
                                     alt="<?php echo htmlspecialchars($bs['nama_produk']); ?>" 
                                     class="sb-product-thumb" 
                                     style="width: 40px; height: 40px;"
                                     onerror="this.src='/starbuck/assets/images/products/default.jpg'">
                                <div>
                                    <strong style="font-size: 0.86rem;"><?php echo htmlspecialchars($bs['nama_produk']); ?></strong>
                                    <small class="text-muted d-block"><?php echo htmlspecialchars($bs['kategori'] ?? 'Minuman'); ?></small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="sb-tag sb-tag-green" style="font-size: 0.75rem;"><?php echo $bs['total_terjual']; ?> Terjual</span>
                            </div>
                        </div>
                    <?php 
                        }
                    } else { 
                    ?>
                        <!-- Fallback jika belum ada data detail transaksi -->
                        <div class="text-center text-muted py-4" style="font-size: 0.85rem;">
                            Data penjualan terlaris akan otomatis terisi saat transaksi tercatat.
                        </div>
                    <?php } ?>
                </div>

                <!-- Pintasan Cepat -->
                <div class="mt-4 pt-3 border-top" style="border-color: var(--sb-border);">
                    <div class="d-flex gap-2">
                        <a href="/starbuck/pages/cashiers/add.php" class="sb-btn sb-btn-secondary flex-fill text-center" style="font-size: 0.82rem;">
                            + Tambah Kasir
                        </a>
                        <a href="/starbuck/pages/products/add.php" class="sb-btn sb-btn-secondary flex-fill text-center" style="font-size: 0.82rem;">
                            + Tambah Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
// 5. Panggil file template footer
include __DIR__ . '/../include/footer.php';
?>
