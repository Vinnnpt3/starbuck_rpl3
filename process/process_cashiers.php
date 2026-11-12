<?php
include __DIR__ . '/../config/config.php';

/** @var mysqli $koneksi */

// Ambil aksi baik dari metode POST maupun GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == "add") {
    $id         = $_POST['id'];
    $nama_kasir = $_POST['nama_kasir'];
    $no_telp    = $_POST['no_telp'];
    $alamat     = $_POST['alamat'];
    $email      = $_POST['email'];

    // Menambahkan data kasir ke table kasir (id_kasir auto increment jika kosong)
    if (empty($id)) {
        $hasil = mysqli_query($koneksi, "INSERT INTO kasir (nama_kasir, no_telp, alamat, email) VALUES ('$nama_kasir', '$no_telp', '$alamat', '$email')");
    } else {
        $hasil = mysqli_query($koneksi, "INSERT INTO kasir (id_kasir, nama_kasir, no_telp, alamat, email) VALUES ('$id', '$nama_kasir', '$no_telp', '$alamat', '$email')");
    }

    if ($hasil) {
        echo "<script>
        alert('Data berhasil ditambahkan!'); window.location.href='../pages/cashiers/list.php';
        </script>";
    } else {
        echo "<script>
        alert('Data gagal ditambahkan: " . mysqli_error($koneksi) . "'); window.location.href='../pages/cashiers/add.php';
        </script>";
    }
} else if ($action == "edit") {
    $id_kasir   = $_POST["id_kasir"];
    $nama_kasir = $_POST['nama_kasir'];
    $no_telp    = $_POST['no_telp'];
    $alamat     = $_POST['alamat'];
    $email      = $_POST['email'];

    // Mengupdate data kasir di table kasir
    $hasil = mysqli_query($koneksi, "UPDATE kasir SET nama_kasir='$nama_kasir', no_telp='$no_telp', alamat='$alamat', email='$email' WHERE id_kasir='$id_kasir'");

    if (!$hasil) {
        echo "
        <script>
        alert('Gagal mengupdate data kasir: " . mysqli_error($koneksi) . "');
        </script>";
    } else {
        echo "<script>
        alert('Data kasir berhasil diupdate.');
        window.location.href='../pages/cashiers/list.php';
        </script>";
    }
} else if ($action == "delete") {
    $id_kasir = $_GET["id_kasir"];

    // Menghapus data kasir di table kasir
    $hasil = mysqli_query($koneksi, "DELETE FROM kasir WHERE id_kasir='$id_kasir'");

    if (!$hasil) {
        echo "
        <script>
        alert('Gagal menghapus data kasir: " . mysqli_error($koneksi) . "');
        </script>";
    } else {
        echo "<script>
        alert('Data kasir berhasil dihapus.');
        window.location.href='../pages/cashiers/list.php';
        </script>";
    }
}
