<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
include ROOTPATH . '/config/config.php';

/** @var mysqli $koneksi */

$id = $_GET['id_produk'] ?? '';
$query = "SELECT * FROM produk WHERE id_produk = '$id'";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location.href='list.php';</script>";
    exit;
}
?>

<div class="container">
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6 mt-5">
            <form action="../../process/process_products.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
                <div class="form-floating mt-5">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" name="nama_produk" value="<?php echo $row['nama_produk']; ?>" class="form-control"
                        id="nama_produk" placeholder="Nama Produk" required>
                </div>
                <div class="form-floating mt-5">
                    <label for="harga">Harga</label>
                    <input type="number" step="0.01" name="harga" value="<?php echo $row['harga']; ?>" class="form-control"
                        id="harga" placeholder="Harga" required>
                </div>
                <button type="submit" class="btn-solid theme-warning mt-5">Edit Data</button>
            </form>
        </div>
        <div class="col-3"></div>
    </div>
</div>

<?php
include ROOTPATH . '/include/footer.php';
?>

