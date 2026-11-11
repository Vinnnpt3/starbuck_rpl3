<?php
include '../config/config.php';

$id             = $_POST['id'];
$nama_kasir     = $_POST['nama_kasir'];
$no_telp        = $_POST['no_telp'];
$alamat         = $_POST['alamat'];
$email          = $_POST['email'];


if($_POST["action"] == "add"){
    // menambahkan data kasir ke table_kasir
    $hasil = mysqli_query($koneksi, "INSERT INTO Tabel_Kasir VALUES ('$id', '$nama_kasir', '$no_telp', '$alamat', '$email')");

    if ($hasil) {
        echo "<script>
        alert('Data berhasil ditambahkan!'); window.location.href='../pages/cashiers/add.php';
        </script>";
    } else {
        echo "<script>
        alert('Data gagal ditambahkan!'); window.location.href='../pages/cashiers/add.php';
        </script>";
    }
}else if($_POST["action"] == "edit"){
    $id_kasir = $_POST["id_kasir"];
    // mengupdate data kasir di table_kasir
    $hasil = mysqli_query($koneksi, "UPDATE Tabel_Kasir SET nama_kasir='$nama_kasir', no_telp='$no_telp', alamat='$alamat', email='$email' WHERE id_kasir='$id_kasir'");

    if(!$hasil){
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

}else if($_GET["action"] == "delete"){
    $id_kasir = $_GET["id_kasir"];
    // menghapus data kasir di table_kasir
    $hasil = mysqli_query($koneksi, "DELETE FROM Tabel_Kasir WHERE id_kasir='$id_kasir'");

    if(!$hasil){
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