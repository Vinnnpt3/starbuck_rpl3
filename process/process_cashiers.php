<?php
// 1. Hubungkan ke database
include __DIR__ . '/../config/config.php';

/** @var mysqli $koneksi */

// 2. Ambil parameter aksi (add / edit / delete) dari POST atau GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ========================================================
// AKSI 1: TAMBAH DATA KASIR (ADD)
// ========================================================
if ($action == "add") {
    $id         = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nama_kasir = mysqli_real_escape_string($koneksi, $_POST['nama_kasir']);
    $no_telp    = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role       = mysqli_real_escape_string($koneksi, $_POST['role'] ?? 'Barista');

    // Jika input ID kosong, biarkan MySQL mengisi ID otomatis (AUTO_INCREMENT)
    if (empty($id)) {
        $query = "INSERT INTO kasir (nama_kasir, no_telp, alamat, email, role) VALUES ('$nama_kasir', '$no_telp', '$alamat', '$email', '$role')";
    } else {
        $query = "INSERT INTO kasir (id_kasir, nama_kasir, no_telp, alamat, email, role) VALUES ('$id', '$nama_kasir', '$no_telp', '$alamat', '$email', '$role')";
    }

    $hasil = mysqli_query($koneksi, $query);

    // Respon keberhasilan
    if ($hasil) {
        echo "<script>alert('Data kasir berhasil ditambahkan!'); window.location.href='../pages/cashiers/list.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan kasir: " . mysqli_error($koneksi) . "'); window.location.href='../pages/cashiers/add.php';</script>";
    }
}

// ========================================================
// AKSI 2: UBAH DATA KASIR (EDIT)
// ========================================================
else if ($action == "edit") {
    $id_kasir   = mysqli_real_escape_string($koneksi, $_POST['id_kasir']);
    $nama_kasir = mysqli_real_escape_string($koneksi, $_POST['nama_kasir']);
    $no_telp    = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role       = mysqli_real_escape_string($koneksi, $_POST['role'] ?? 'Barista');

    // Update data kasir berdasarkan id_kasir
    $query = "UPDATE kasir SET nama_kasir='$nama_kasir', no_telp='$no_telp', alamat='$alamat', email='$email', role='$role' WHERE id_kasir='$id_kasir'";
    $hasil = mysqli_query($koneksi, $query);

    // Respon keberhasilan
    if ($hasil) {
        echo "<script>alert('Data kasir berhasil diperbarui!'); window.location.href='../pages/cashiers/list.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui kasir: " . mysqli_error($koneksi) . "'); window.location.href='../pages/cashiers/list.php';</script>";
    }
}

// ========================================================
// AKSI 3: HAPUS DATA KASIR (DELETE)
// ========================================================
else if ($action == "delete") {
    $id_kasir = mysqli_real_escape_string($koneksi, $_GET['id_kasir'] ?? '');

    // Hapus data kasir berdasarkan id_kasir
    $query = "DELETE FROM kasir WHERE id_kasir='$id_kasir'";
    $hasil = mysqli_query($koneksi, $query);

    // Respon keberhasilan
    if ($hasil) {
        echo "<script>alert('Data kasir berhasil dihapus!'); window.location.href='../pages/cashiers/list.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kasir: " . mysqli_error($koneksi) . "'); window.location.href='../pages/cashiers/list.php';</script>";
    }
}
