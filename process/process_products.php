<?php
// 1. Hubungkan ke database
include __DIR__ . '/../config/config.php';

/** @var mysqli $koneksi */

// 2. Ambil parameter aksi (add / edit / delete) dari POST atau GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ========================================================
// AKSI 1: TAMBAH DATA PRODUK (ADD)
// ========================================================
if ($action == "add") {
    $id_produk   = mysqli_real_escape_string($koneksi, $_POST['id_produk']);
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $harga       = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $kategori    = mysqli_real_escape_string($koneksi, $_POST['kategori'] ?? 'Espresso & Coffee');
    $gambar      = 'default.jpg';

    // Cek upload file gambar baru
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $new_name = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $upload_path = __DIR__ . '/../assets/images/products/' . $new_name;
            if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $upload_path)) {
                $gambar = $new_name;
            }
        }
    } elseif (!empty($_POST['gambar'])) {
        $gambar = mysqli_real_escape_string($koneksi, $_POST['gambar']);
    }

    // Simpan ke tabel produk
    $query = "INSERT INTO produk (id_produk, nama_produk, harga, kategori, gambar) 
              VALUES ('$id_produk', '$nama_produk', '$harga', '$kategori', '$gambar')";
    $hasil = mysqli_query($koneksi, $query);

    // Respon alert
    if ($hasil) {
        echo "<script>alert('Menu produk berhasil ditambahkan!'); window.location.href='../pages/products/list.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan produk: " . mysqli_error($koneksi) . "'); window.location.href='../pages/products/add.php';</script>";
    }
}

// ========================================================
// AKSI 2: UBAH DATA PRODUK (EDIT)
// ========================================================
else if ($action == "edit") {
    $id_produk   = mysqli_real_escape_string($koneksi, $_POST['id_produk']);
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $harga       = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $kategori    = mysqli_real_escape_string($koneksi, $_POST['kategori'] ?? 'Espresso & Coffee');

    $update_img = "";
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $new_name = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $upload_path = __DIR__ . '/../assets/images/products/' . $new_name;
            if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $upload_path)) {
                $update_img = ", gambar='$new_name'";
            }
        }
    }

    // Update produk berdasarkan id_produk
    $query = "UPDATE produk SET nama_produk='$nama_produk', harga='$harga', kategori='$kategori' $update_img WHERE id_produk='$id_produk'";
    $hasil = mysqli_query($koneksi, $query);

    // Respon alert
    if ($hasil) {
        echo "<script>alert('Data produk berhasil diperbarui!'); window.location.href='../pages/products/list.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui produk: " . mysqli_error($koneksi) . "'); window.location.href='../pages/products/list.php';</script>";
    }
}

// ========================================================
// AKSI 3: HAPUS DATA PRODUK (DELETE)
// ========================================================
else if ($action == "delete") {
    $id_produk = mysqli_real_escape_string($koneksi, $_GET['id_produk'] ?? '');

    // Hapus produk berdasarkan id_produk
    $query = "DELETE FROM produk WHERE id_produk='$id_produk'";
    $hasil = mysqli_query($koneksi, $query);

    // Respon alert
    if ($hasil) {
        echo "<script>alert('Data produk berhasil dihapus!'); window.location.href='../pages/products/list.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk: " . mysqli_error($koneksi) . "'); window.location.href='../pages/products/list.php';</script>";
    }
}
