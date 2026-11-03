<?php
include '../config/config.php';

/** @var mysqli $koneksi */

$id_kasir       = $_POST['id_kasir'];
$nama_kasir     = $_POST['nama_kasir'];
$no_telp        = $_POST['no_telp'];
$alamat         = $_POST['alamat'];
$email          = $_POST['email'];

$hasil = mysqli_query($koneksi, "INSERT INTO Tabel_Kasir VALUES ('$id_kasir', '$nama_kasir', '$no_telp', '$alamat', '$email')");

if ($hasil) {
    echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='../pages/cashiers/add.php';</script>";
} else {
    echo "<script>alert('Data gagal ditambahkan!'); window.location.href='../pages/cashiers/add.php';</script>";
}
