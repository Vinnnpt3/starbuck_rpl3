<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
?>
<center>
    <a href="/starbuck/pages/transactions/add.php" class="btn-solid theme-success mb-3">Tambah Transaksi</a>
</center>
<div class="container">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Transaksi</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Waktu</th>
                <th scope="col">Subtotal</th>
                <th scope="col">Pajak</th>
                <th scope="col">Total</th>
                <th scope="col">Aksi</th>
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
                <th scope="row"><?php echo $no++; ?></th>
                <td>TRX-<?php echo str_pad($row['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></td>
                <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                <td><?php echo $row['waktu']; ?></td>
                <td>Rp <?php echo number_format($row['subtotal'], 3, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['pajak'], 3, ',', '.'); ?></td>
                <td><strong>Rp <?php echo number_format($row['total'], 3, ',', '.'); ?></strong></td>
                <td>
                    <a href='detail.php?id_transaksi=<?php echo $row['id_transaksi']; ?>' class='btn-solid theme-warning'>Detail</a>
                    <a href='/starbuck/process/process_transactions.php?action=delete&id_transaksi=<?php echo $row['id_transaksi']; ?>'
                        class='btn-solid theme-danger'
                        onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</a>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
include ROOTPATH . '/include/footer.php';
?>

