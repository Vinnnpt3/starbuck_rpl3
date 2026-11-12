<?php
// Mendefinisikan ROOTPATH sebagai path ke direktori root proyek
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
?>
<center>
    <a href="/starbuck/pages/products/add.php" class="btn-solid theme-success mb-3">Tambah Produk</a>
</center>
<div class="container">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Produk</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Harga</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include ROOTPATH . '/config/config.php';
            /** @var mysqli $koneksi */
            $query = "SELECT * FROM produk";
            $result = mysqli_query($koneksi, $query);
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <th scope="row"><?php echo $no++; ?></th>
                <td><?php echo $row['id_produk']; ?></td>
                <td><?php echo $row['nama_produk']; ?></td>
                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                <td>
                    <a href='edit.php?id_produk=<?php echo $row['id_produk']; ?>' class='btn-solid theme-warning'>Edit</a>
                    <a href='/starbuck/process/process_products.php?action=delete&id_produk=<?php echo $row['id_produk']; ?>'
                        class='btn-solid theme-danger'
                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
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

