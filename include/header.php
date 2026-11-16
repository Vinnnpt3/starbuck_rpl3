<?php
$current_uri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starbucks Point of Sale</title>
    <link rel="stylesheet" href="/starbuck/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/starbuck/assets/css/custom.css">
</head>

<body>

    <header class="sb-navbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <!-- Brand -->
                <a href="/starbuck/pages/dashboard.php" class="sb-brand">
                    <div class="sb-brand-logo">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                            <line x1="6" y1="1" x2="6" y2="4"></line>
                            <line x1="10" y1="1" x2="10" y2="4"></line>
                            <line x1="14" y1="1" x2="14" y2="4"></line>
                        </svg>
                    </div>
                    <span>Starbucks</span>
                    <span class="sb-brand-tag">POS</span>
                </a>

                <!-- Navigation Links -->
                <nav>
                    <ul class="sb-nav-menu">
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

                <!-- Quick Action Button in Header -->
                <div>
                    <a href="/starbuck/pages/transactions/add.php" class="btn-sb-primary" style="padding: 0.38rem 0.85rem; font-size: 0.82rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Transaksi Baru
                    </a>
                </div>
            </div>
        </div>
    </header>
