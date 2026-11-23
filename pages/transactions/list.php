<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../../include/header.php';

/** @var mysqli $koneksi */

// 2. Query mengambil seluruh riwayat transaksi penjualan
$query  = "SELECT t.*, dt.id_nota, k.nama_kasir 
           FROM transaksi t
           LEFT JOIN (SELECT DISTINCT id_transaksi, id_nota, id_kasir FROM detail_transaksi) dt ON t.id_transaksi = dt.id_transaksi
           LEFT JOIN kasir k ON dt.id_kasir = k.id_kasir
           ORDER BY t.id_transaksi DESC";
$result = mysqli_query($koneksi, $query);

$transactions = [];
$total_revenue = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $transactions[] = $row;
    $total_revenue += floatval($row['total']);
}
?>

<div class="container my-4">
    <!-- Header Halaman -->
    <div class="sb-header-box">
        <div>
            <h1 class="sb-title">Riwayat Transaksi Penjualan</h1>
            <p class="sb-desc">Rekapitulasi seluruh nota pesanan dan struk kasir Starbucks</p>
        </div>
        <a href="/starbuck/pages/transactions/add.php" class="sb-btn sb-btn-green">
            + Buka Kasir / Pesanan Baru
        </a>
    </div>

    <!-- Kotak Pencarian & Filter Cepat -->
    <div class="sb-box mb-3 p-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <input type="text" id="cariTransaksi" class="sb-input-control" placeholder="Cari nomor struk, nota, atau nama kasir...">
            </div>
            <div class="col-12 col-md-4">
                <input type="date" id="filterTanggal" class="sb-input-control">
            </div>
            <div class="col-12 col-md-3 text-md-end">
                <button type="button" id="btnResetFilter" class="sb-btn sb-btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="sb-table-wrap">
        <div class="table-responsive">
            <table class="sb-main-table" id="tabelTransaksi">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 14%;">No. Struk</th>
                        <th style="width: 16%;">Kasir / Barista</th>
                        <th style="width: 18%;">Tanggal & Waktu</th>
                        <th style="width: 13%;">Metode Bayar</th>
                        <th style="width: 18%;">Total Pembayaran</th>
                        <th style="width: 16%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transaksiBody">
                    <?php
                    $no = 1;
                    foreach ($transactions as $row) {
                        $pay = !empty($row['metode_pembayaran']) ? $row['metode_pembayaran'] : 'Tunai';
                        $payClass = ($pay === 'Tunai') ? 'sb-tag-muted' : (($pay === 'QRIS') ? 'sb-tag-green' : 'sb-tag-caramel');
                        $tgl_raw = $row['tanggal'];
                    ?>
                    <tr class="tx-row" data-tgl="<?php echo $tgl_raw; ?>" data-search="<?php echo strtolower(htmlspecialchars($row['id_transaksi'] . ' ' . ($row['id_nota'] ?? '') . ' ' . ($row['nama_kasir'] ?? '') . ' ' . $pay)); ?>">
                        <td class="text-muted"><?php echo $no++; ?></td>
                        <td>
                            <span class="sb-tag sb-tag-muted">#TRX-<?php echo str_pad($row['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></span>
                            <?php if (!empty($row['id_nota'])) { ?>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($row['id_nota']); ?></small>
                            <?php } ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama_kasir'] ?? 'Barista'); ?></strong>
                        </td>
                        <td>
                            <div><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></div>
                            <small class="text-muted"><?php echo $row['waktu']; ?></small>
                        </td>
                        <td>
                            <span class="sb-tag <?php echo $payClass; ?>"><?php echo htmlspecialchars($pay); ?></span>
                        </td>
                        <td>
                            <strong class="sb-price-tag">Rp <?php echo number_format($row['total'], 3, ',', '.'); ?></strong>
                        </td>
                        <td style="text-align: right;">
                            <a href='detail.php?id_transaksi=<?php echo $row['id_transaksi']; ?>' class='sb-action-btn sb-btn-view'>Lihat Struk</a>
                            <a href='/starbuck/process/process_transactions.php?action=delete&id_transaksi=<?php echo $row['id_transaksi']; ?>'
                                class='sb-action-btn sb-btn-del'
                                onclick="return confirm('Apakah Anda yakin ingin menghapus struk #TRX-<?php echo $row['id_transaksi']; ?>?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-between align-items-center border-top text-muted" style="font-size: 0.82rem; border-color: var(--sb-border);">
            <span>Total: <strong><?php echo count($transactions); ?></strong> transaksi tercatat</span>
            <span>Akumulasi Omzet: <strong class="sb-price-tag">Rp <?php echo number_format($total_revenue, 3, ',', '.'); ?></strong></span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('cariTransaksi');
    const tglInput    = document.getElementById('filterTanggal');
    const btnReset    = document.getElementById('btnResetFilter');
    const rows        = document.querySelectorAll('.tx-row');

    function filterTx() {
        const q   = searchInput.value.toLowerCase().trim();
        const tgl = tglInput.value;

        rows.forEach(row => {
            const rowText = row.getAttribute('data-search');
            const rowTgl  = row.getAttribute('data-tgl');

            const matchSearch = (q === '' || rowText.includes(q));
            const matchTgl    = (tgl === '' || rowTgl === tgl);

            if (matchSearch && matchTgl) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTx);
    tglInput.addEventListener('change', filterTx);

    btnReset.addEventListener('click', function() {
        searchInput.value = '';
        tglInput.value    = '';
        filterTx();
    });
});
</script>

<?php
// 4. Panggil file template footer
include __DIR__ . '/../../include/footer.php';
?>
