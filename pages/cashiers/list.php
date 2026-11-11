<?php
// Mendefinisikan ROOTPATH sebagai path ke direktori root proyek
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
include ROOTPATH . '/include/header.php';
?>
<center>
    <a href="/starbuck/pages/cashiers/add.php" class="btn-solid theme-success mb-3">Tambah
        Kasir</a>
</center>
<div class="container">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Kasir</th>
                <th scope="col">No Telepon</th>
                <th scope="col">Alamat</th>
                <th scope="col">Email</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include '../../config/config.php';
            $query = "SELECT * FROM Tabel_Kasir";
            $result = mysqli_query($koneksi, $query);
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <th scope="row"><?php echo $no++; ?></th>
                <td><?php echo $row['nama_kasir']; ?></td>
                <td><?php echo $row['no_telp']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <a href='edit.php?id_kasir=<?php echo $row['id_kasir']; ?>' class='btn-solid theme-warning'>Edit</a>
                    <a href='/starbuck/process/process_cashiers.php?action=delete&id_kasir=<?php echo $row['id_kasir']; ?>'
                        class='btn-solid theme-danger'
                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
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