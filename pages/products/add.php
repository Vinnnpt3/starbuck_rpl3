<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6 mt-5">
            <form action="../../process/process_products.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-floating mt-5">
                    <label for="id_produk">ID Produk</label>
                    <input type="number" name="id_produk" class="form-control" id="id_produk" placeholder="ID Produk" required>
                </div>
                <div class="form-floating mt-5">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" id="nama_produk" placeholder="Nama Produk" required>
                </div>
                <div class="form-floating mt-5">
                    <label for="harga">Harga</label>
                    <input type="number" step="0.01" name="harga" class="form-control" id="harga" placeholder="Harga" required>
                </div>
                <button type="submit" class="btn-solid theme-success mt-5">Tambah Data</button>
            </form>
        </div>
        <div class="col-3"></div>
    </div>
</div>

<?php
include ROOTPATH . '/include/footer.php';
?>

