<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_uri = $_SERVER['REQUEST_URI'] ?? '';

// Proteksi Halaman: Wajib login untuk mengakses sistem POS
if (!isset($_SESSION['user_id']) && strpos($current_uri, 'login.php') === false) {
    header("Location: /starbuck/pages/login.php");
    exit;
}

$logged_user = $_SESSION['nama_kasir'] ?? 'Petugas';
$logged_role = $_SESSION['role'] ?? 'Barista';
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starbucks Point of Sale</title>
    <!-- File CSS -->
    <link rel="stylesheet" href="/starbuck/assets/css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/starbuck/assets/css/custom.css?v=<?php echo time(); ?>">
</head>

<body>

    <!-- Header Navigasi Serba Putih Elegan -->
    <header class="sb-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
                
                <!-- Nama Aplikasi / Brand -->
                <a href="/starbuck/pages/dashboard.php" class="sb-brand-wrap">
                    <span class="sb-brand-title">Starbucks</span>
                    <span class="sb-brand-sub">POS</span>
                </a>

                <!-- Daftar Menu Navigasi (Teks Bersih Tanpa Icon) -->
                <nav>
                    <ul class="sb-nav-list">
                        <li class="sb-nav-item">
                            <a href="/starbuck/pages/dashboard.php" class="<?php echo (strpos($current_uri, 'dashboard.php') !== false) ? 'active' : ''; ?>">
                                Dashboard
                            </a>
                        </li>
                        <li class="sb-nav-item">
                            <a href="/starbuck/pages/cashiers/list.php" class="<?php echo (strpos($current_uri, '/cashiers/') !== false) ? 'active' : ''; ?>">
                                Kasir
                            </a>
                        </li>
                        <li class="sb-nav-item">
                            <a href="/starbuck/pages/products/list.php" class="<?php echo (strpos($current_uri, '/products/') !== false) ? 'active' : ''; ?>">
                                Produk
                            </a>
                        </li>
                        <li class="sb-nav-item">
                            <a href="/starbuck/pages/transactions/list.php" class="<?php echo (strpos($current_uri, '/transactions/') !== false) ? 'active' : ''; ?>">
                                Transaksi
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Tombol Transaksi Baru & Profil Petugas -->
                <div class="d-flex align-items-center gap-2">
                    <a href="/starbuck/pages/transactions/add.php" class="sb-btn sb-btn-green" style="padding: 0.38rem 0.85rem; font-size: 0.82rem;">
                        + Transaksi Baru
                    </a>

                    <div class="d-flex align-items-center gap-2 ps-2 border-start" style="border-color: var(--sb-border);">
                        <div class="text-end d-none d-sm-block">
                            <strong class="d-block" style="font-size: 0.82rem; line-height: 1.2;"><?php echo htmlspecialchars($logged_user); ?></strong>
                            <small class="text-muted" style="font-size: 0.72rem;"><?php echo htmlspecialchars($logged_role); ?></small>
                        </div>
                        <a href="/starbuck/process/process_logout.php" class="sb-action-btn sb-btn-del" title="Keluar dari sistem kasir" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                            Logout
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </header>
