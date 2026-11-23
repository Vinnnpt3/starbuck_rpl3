<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika sudah login, langsung alihkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: /starbuck/pages/dashboard.php");
    exit;
}

$error = $_GET['error'] ?? '';
$logout = $_GET['logout'] ?? '';
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Starbucks Point of Sale</title>
    <link rel="stylesheet" href="/starbuck/assets/css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/starbuck/assets/css/custom.css?v=<?php echo time(); ?>">
</head>

<body style="background-color: #ffffff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">

    <div style="width: 100%; max-width: 420px;">
        
        <!-- Kartu Login Bersih & Elegan -->
        <div class="sb-box" style="padding: 2.25rem 2rem; box-shadow: 0 4px 20px -2px rgba(16, 24, 40, 0.08);">
            
            <!-- Logo & Brand Header -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-baseline gap-2 mb-1">
                    <span style="font-size: 1.6rem; font-weight: 800; color: var(--sb-green); letter-spacing: -0.02em;">Starbucks</span>
                    <span class="sb-brand-sub" style="font-size: 0.8rem; padding: 0.15rem 0.5rem;">POS</span>
                </div>
                <div class="text-muted" style="font-size: 0.86rem;">Sistem Kasir & Operasional Gerai #104</div>
            </div>

            <!-- Pesan Notifikasi Alert -->
            <?php if ($error == '1') { ?>
                <div class="p-2 mb-3 rounded text-center" style="background-color: var(--sb-red-bg); border: 1px solid var(--sb-red-border); color: var(--sb-red-text); font-size: 0.82rem;">
                    Username atau password yang Anda masukkan salah.
                </div>
            <?php } ?>

            <?php if ($logout == '1') { ?>
                <div class="p-2 mb-3 rounded text-center" style="background-color: var(--sb-green-soft); border: 1px solid rgba(0, 98, 65, 0.2); color: var(--sb-green); font-size: 0.82rem;">
                    Anda telah berhasil keluar dari sistem kasir.
                </div>
            <?php } ?>

            <!-- Form Autentikasi Login -->
            <form action="../process/process_login.php" method="post">
                
                <div class="sb-field mb-3">
                    <label for="username" class="sb-label">Username Petugas</label>
                    <input type="text" name="username" id="username" class="sb-input-control" placeholder="Contoh: sarah atau admin" required autofocus>
                </div>

                <div class="sb-field mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="sb-label m-0">Kata Sandi</label>
                    </div>
                    <input type="password" name="password" id="password" class="sb-input-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="sb-btn sb-btn-green w-100 py-2" style="font-size: 0.95rem; font-weight: 600;">
                    Masuk ke Sistem Kasir
                </button>
            </form>

            <!-- Bantuan Akun Login Demo -->
            <div class="mt-4 pt-3 border-top text-muted" style="border-color: var(--sb-border); font-size: 0.78rem;">
                <div class="font-weight-bold text-dark mb-1">Akun Demo Petugas:</div>
                <div>• Administrator: <code>admin</code> / <code>admin123</code></div>
                <div>• Shift Supervisor: <code>sarah</code> / <code>123456</code></div>
                <div>• Kasir / Barista: <code>dilla</code> / <code>123456</code></div>
            </div>

        </div>

        <div class="text-center text-muted mt-3" style="font-size: 0.76rem;">
            Starbucks Coffee Indonesia &copy; <?php echo date('Y'); ?> • Point of Sale System
        </div>

    </div>

</body>
</html>
