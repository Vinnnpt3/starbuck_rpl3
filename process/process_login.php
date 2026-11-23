<?php
session_start();
include __DIR__ . '/../config/config.php';

/** @var mysqli $koneksi */

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    header("Location: ../pages/login.php?error=1");
    exit;
}

$u = mysqli_real_escape_string($koneksi, $username);
$query = "SELECT * FROM kasir WHERE username = '$u' LIMIT 1";
$result = mysqli_query($koneksi, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    // Verifikasi password hash bcrypt atau pencocokan langsung
    $valid = false;
    if (password_verify($password, $user['password'])) {
        $valid = true;
    } elseif ($password === $user['password']) {
        // Fallback jika belum ter-hash
        $valid = true;
    }

    if ($valid) {
        // Set data sesi login
        $_SESSION['user_id']    = $user['id_kasir'];
        $_SESSION['nama_kasir'] = $user['nama_kasir'];
        $_SESSION['username']   = $user['username'];
        $_SESSION['role']       = $user['role'] ?? 'Barista';

        header("Location: ../pages/dashboard.php");
        exit;
    }
}

// Jika gagal
header("Location: ../pages/login.php?error=1");
exit;
