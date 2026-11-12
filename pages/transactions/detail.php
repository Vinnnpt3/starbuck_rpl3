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

<div class="container mt-4">
    <div class="card p-4 mb-4">
        <h4>Detail Transaksi #TRX-<?php echo str_pad($tx['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></h4>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nomor Nota:</strong> <?php echo htmlspecialchars($tx['id_nota'] ?? '-'); ?></p>
                <p><strong>Kasir:</strong> <?php echo htmlspecialchars($tx['nama_kasir'] ?? '-'); ?></p>
                <p><strong>Tanggal:</strong> <?php echo date('d-m-Y', strtotime($tx['tanggal'])); ?></p>
                <p><strong>Waktu:</strong> <?php echo htmlspecialchars($tx['waktu']); ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <p><strong>Subtotal:</strong> Rp <?php echo number_format($tx['subtotal'], 3, ',', '.'); ?></p>
                <p><strong>Pajak:</strong> Rp <?php echo number_format($tx['pajak'], 3, ',', '.'); ?></p>
                <h3><strong>Total:</strong> Rp <?php echo number_format($tx['total'], 3, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h5>Rincian Produk Dibeli</h5>
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Satuan</th>
                    <th>Kuantitas</th>
                    <th>Subtotal Item</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($item = mysqli_fetch_assoc($result_items)) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($item['nama_produk'] ?? 'Produk #' . $item['id_produk']); ?></td>
                    <td><?php echo htmlspecialchars($item['kategori'] ?? '-'); ?></td>
                    <td>Rp <?php echo number_format($item['harga'], 3, ',', '.'); ?></td>
                    <td><?php echo $item['kuantitas']; ?></td>
                    <td>Rp <?php echo number_format($item['kuantitas_harga'], 3, ',', '.'); ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <div class="mt-4">
            <a href="list.php" class="btn-solid theme-warning">Kembali ke Daftar Transaksi</a>
        </div>
    </div>
</div>

<?php
include ROOTPATH . '/include/footer.php';
?>

