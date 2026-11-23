<?php
// ========================================================
// KONFIGURASI DATABASE MYSQL
// ========================================================
$host     = "127.0.0.1";     // Alamat server MySQL
$user     = "root";          // Username default XAMPP
$password = "";              // Password default XAMPP (kosong)
$database = "starbuck_rpl3";  // Nama database yang digunakan

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
