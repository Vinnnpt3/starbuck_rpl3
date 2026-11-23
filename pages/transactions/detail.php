<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../../include/header.php';

/** @var mysqli $koneksi */

// 2. Ambil parameter id_transaksi dari URL
$id_transaksi = $_GET['id_transaksi'] ?? '';

// 3. Query data transaksi utama (digabung dengan tabel kasir & nota)
$query_tx = "SELECT t.*, dt.id_nota, k.nama_kasir, k.role 
             FROM transaksi t
             LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
             LEFT JOIN kasir k ON dt.id_kasir = k.id_kasir
             WHERE t.id_transaksi = '$id_transaksi'
             LIMIT 1";
$result_tx = mysqli_query($koneksi, $query_tx);
$tx = mysqli_fetch_assoc($result_tx);

// Jika data tidak ada, kembali ke list
if (!$tx) {
    echo "<script>alert('Transaksi tidak ditemukan!'); window.location.href='list.php';</script>";
    exit;
}

// 4. Query rincian menu produk yang dipesan (digabung gambar dari tabel produk)
$query_items = "SELECT dt.*, p.nama_produk, p.gambar 
                FROM detail_transaksi dt
                LEFT JOIN produk p ON dt.id_produk = p.id_produk
                WHERE dt.id_transaksi = '$id_transaksi'";
$result_items = mysqli_query($koneksi, $query_items);
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7">
            
            <!-- Tombol Navigasi Atas -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="list.php" class="sb-btn sb-btn-secondary">
                    Kembali ke Riwayat Transaksi
                </a>
                <div class="d-flex gap-2">
                    <a href="add.php" class="sb-btn sb-btn-secondary">
                        + Transaksi Baru
                    </a>
                    <button onclick="window.print()" class="sb-btn sb-btn-green">
                        Cetak Struk Kasir
                    </button>
                </div>
            </div>

            <!-- Tampilan Struk Kasir Starbucks -->
            <div class="sb-receipt" id="receiptArea">
                
                <!-- Kop Struk -->
                <div class="sb-receipt-top">
                    <h2 class="h5 m-0 font-weight-bold" style="color: var(--sb-green);">Starbucks Coffee Indonesia</h2>
                    <div style="font-size: 0.82rem; color: var(--sb-text-muted); margin-top: 0.2rem;">Outlet #104 - Denpasar Store</div>
                    <small class="text-muted">Jl. Sunset Road No. 88, Kuta, Bali • Telp: (0361) 898-776</small>
                </div>

                <!-- Info Nota Kasir -->
                <div class="d-flex justify-content-between mb-3 text-muted" style="font-size: 0.85rem;">
                    <div>
                        <div>No. Struk: <strong>#TRX-<?php echo str_pad($tx['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></strong></div>
                        <div>Check Nota: <span class="sb-tag sb-tag-muted"><?php echo htmlspecialchars($tx['id_nota'] ?? '-'); ?></span></div>
                    </div>
                    <div class="text-end">
                        <div><?php echo date('d M Y', strtotime($tx['tanggal'])); ?> • <?php echo htmlspecialchars($tx['waktu']); ?></div>
                        <div>Kasir: <strong><?php echo htmlspecialchars($tx['nama_kasir'] ?? 'Barista'); ?></strong> (<?php echo htmlspecialchars($tx['role'] ?? 'Staff'); ?>)</div>
                    </div>
                </div>

                <!-- Tabel Item Menu yang Dibeli -->
                <div class="table-responsive mb-2">
                    <table class="sb-main-table">
                        <thead>
                            <tr>
                                <th>Menu Pesanan</th>
                                <th style="text-align: right;">Harga</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while ($item = mysqli_fetch_assoc($result_items)) { 
                                $img = !empty($item['gambar']) ? $item['gambar'] : 'default.jpg';
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/starbuck/assets/images/products/<?php echo htmlspecialchars($img); ?>" 
                                             class="sb-product-thumb" style="width: 36px; height: 36px;"
                                             onerror="this.src='/starbuck/assets/images/products/default.jpg'">
                                        <div>
                                            <strong><?php echo htmlspecialchars($item['nama_produk'] ?? 'Produk #' . $item['id_produk']); ?></strong>
                                            <small class="text-muted d-block"><?php echo htmlspecialchars($item['kategori'] ?? 'Minuman'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    Rp <?php echo number_format($item['harga'], 3, ',', '.'); ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo $item['kuantitas']; ?>x
                                </td>
                                <td style="text-align: right;">
                                    <strong>Rp <?php echo number_format($item['kuantitas_harga'], 3, ',', '.'); ?></strong>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Rincian Pembayaran -->
                <div class="sb-receipt-calc">
                    <div class="sb-calc-row">
                        <span>Subtotal Belanja</span>
                        <span>Rp <?php echo number_format($tx['subtotal'], 3, ',', '.'); ?></span>
                    </div>
                    <div class="sb-calc-row">
                        <span>Pajak Restoran PB1 (11%)</span>
                        <span>Rp <?php echo number_format($tx['pajak'], 3, ',', '.'); ?></span>
                    </div>
                    <div class="sb-calc-total">
                        <span>TOTAL AKHIR</span>
                        <span class="sb-price-tag" style="font-size: 1.25rem;">Rp <?php echo number_format($tx['total'], 3, ',', '.'); ?></span>
                    </div>
                    
                    <!-- Informasi Pembayaran & Kembalian -->
                    <div class="pt-2 mt-2 border-top" style="border-color: var(--sb-border); font-size: 0.85rem;">
                        <div class="sb-calc-row">
                            <span>Metode Pembayaran</span>
                            <strong><?php echo htmlspecialchars($tx['metode_pembayaran'] ?? 'Tunai'); ?></strong>
                        </div>
                        <?php if (($tx['metode_pembayaran'] ?? 'Tunai') === 'Tunai' && floatval($tx['bayar'] ?? 0) > 0) { ?>
                        <div class="sb-calc-row">
                            <span>Jumlah Uang Diterima</span>
                            <span>Rp <?php echo number_format($tx['bayar'], 3, ',', '.'); ?></span>
                        </div>
                        <div class="sb-calc-row">
                            <span>Uang Kembalian</span>
                            <strong style="color: var(--sb-green);">Rp <?php echo number_format($tx['kembalian'] ?? 0, 3, ',', '.'); ?></strong>
                        </div>
                        <?php } else { ?>
                        <div class="sb-calc-row">
                            <span>Status</span>
                            <span class="sb-tag sb-tag-green">LUNAS</span>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Ucapan Terima Kasih -->
                <div class="text-center text-muted mt-4 pt-2" style="font-size: 0.8rem; border-top: 1px dashed var(--sb-border);">
                    Terima kasih atas kunjungan Anda di Starbucks Coffee!<br>
                    Simpan struk ini sebagai bukti pembayaran yang sah.
                </div>

            </div>
        </div>
    </div>
</div>

<?php
// 5. Panggil file template footer
include __DIR__ . '/../../include/footer.php';
?>
