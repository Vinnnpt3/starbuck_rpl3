<?php
include __DIR__ . '/../config/config.php';

/** @var mysqli $koneksi */

// Ambil aksi baik dari metode POST maupun GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == "add") {
    $id_produk   = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga       = $_POST['harga'];

    // Menambahkan data produk ke table produk
    $hasil = mysqli_query($koneksi, "INSERT INTO produk VALUES ('$id_produk', '$nama_produk', '$harga')");

    if ($hasil) {
        echo "<script>
        alert('Produk berhasil ditambahkan!'); window.location.href='../pages/products/list.php';
        </script>";
    } else {
        echo "<script>
        alert('Produk gagal ditambahkan: " . mysqli_error($koneksi) . "'); window.location.href='../pages/products/add.php';
        </script>";
    }
} else if ($action == "edit") {
    $id_produk   = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga       = $_POST['harga'];

    // Mengupdate data produk di table produk
    $hasil = mysqli_query($koneksi, "UPDATE produk SET nama_produk='$nama_produk', harga='$harga' WHERE id_produk='$id_produk'");

    if (!$hasil) {
        echo "<script>
        alert('Gagal mengupdate data produk: " . mysqli_error($koneksi) . "');
        window.location.href='../pages/products/list.php';
        </script>";
    } else {
        echo "<script>
        alert('Data produk berhasil diupdate.');
        window.location.href='../pages/products/list.php';
        </script>";
    }
} else if ($action == "delete") {
    $id_produk = $_GET['id_produk'];

    // Menghapus data produk di table produk
    $hasil = mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk='$id_produk'");

    if (!$hasil) {
        echo "<script>
        alert('Gagal menghapus data produk: " . mysqli_error($koneksi) . "');
        window.location.href='../pages/products/list.php';
        </script>";
    } else {
        echo "<script>
        alert('Data produk berhasil dihapus.');
        window.location.href='../pages/products/list.php';
        </script>";
    }
}

