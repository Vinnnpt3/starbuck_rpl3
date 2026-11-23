<?php
// 1. Hubungkan ke database
include __DIR__ . '/../config/config.php';

/** @var mysqli $koneksi */

// 2. Ambil parameter aksi dari POST atau GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ========================================================
// AKSI 1: TAMBAH TRANSAKSI BARU (ADD)
// ========================================================
if ($action == "add") {
    $id_kasir          = mysqli_real_escape_string($koneksi, $_POST['id_kasir']);
    $id_nota           = mysqli_real_escape_string($koneksi, $_POST['id_nota']);
    $tanggal           = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $waktu             = mysqli_real_escape_string($koneksi, $_POST['waktu']);
    $subtotal          = floatval($_POST['subtotal']);
    $diskon            = floatval($_POST['diskon'] ?? 0);
    $pajak             = floatval($_POST['pajak']);
    $total             = floatval($_POST['total']);
    $metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran'] ?? 'Tunai');
    $bayar             = floatval($_POST['bayar'] ?? 0);
    $kembalian         = floatval($_POST['kembalian'] ?? 0);
    $items             = $_POST['items'] ?? [];

    // Tentukan ID transaksi baru (ID tertinggi + 1)
    $max_tx_q     = mysqli_query($koneksi, "SELECT MAX(id_transaksi) AS max_id FROM transaksi");
    $max_tx       = mysqli_fetch_assoc($max_tx_q);
    $id_transaksi = ($max_tx['max_id'] ? $max_tx['max_id'] + 1 : 1);

    // Simpan data ke tabel utama `transaksi`
    $query_tx = "INSERT INTO transaksi (id_transaksi, subtotal, diskon, pajak, total, tanggal, waktu, metode_pembayaran, bayar, kembalian) 
                 VALUES ('$id_transaksi', '$subtotal', '$diskon', '$pajak', '$total', '$tanggal', '$waktu', '$metode_pembayaran', '$bayar', '$kembalian')";
    $hasil_tx = mysqli_query($koneksi, $query_tx);

    if ($hasil_tx) {
        // Tentukan ID detail transaksi tertinggi awal
        $max_dt_q    = mysqli_query($koneksi, "SELECT MAX(id_detail_transaksi) AS max_id FROM detail_transaksi");
        $max_dt      = mysqli_fetch_assoc($max_dt_q);
        $next_dt_id  = ($max_dt['max_id'] ? $max_dt['max_id'] + 1 : 1);

        // Simpan setiap item produk yang dibeli ke tabel `detail_transaksi`
        foreach ($items as $item) {
            $id_produk = $item['id_produk'] ?? '';
            if (empty($id_produk)) {
                continue;
            }

            $kategori        = mysqli_real_escape_string($koneksi, $item['kategori'] ?? 'Minuman');
            $harga           = floatval($item['harga']);
            $kuantitas       = intval($item['kuantitas']);
            $kuantitas_harga = $harga * $kuantitas;

            mysqli_query($koneksi, "INSERT INTO detail_transaksi 
                (id_detail_transaksi, id_kasir, id_produk, id_nota, id_transaksi, kategori, kuantitas, kuantitas_harga, harga) 
                VALUES 
                ('$next_dt_id', '$id_kasir', '$id_produk', '$id_nota', '$id_transaksi', '$kategori', '$kuantitas', '$kuantitas_harga', '$harga')");
            $next_dt_id++;
        }

        echo "<script>
        alert('Transaksi berhasil disimpan!'); 
        window.location.href='../pages/transactions/detail.php?id_transaksi=$id_transaksi';
        </script>";
    } else {
        echo "<script>
        alert('Gagal menyimpan transaksi: " . mysqli_error($koneksi) . "'); 
        window.location.href='../pages/transactions/add.php';
        </script>";
    }
}

// ========================================================
// AKSI 2: HAPUS TRANSAKSI (DELETE)
// ========================================================
else if ($action == "delete") {
    $id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id_transaksi'] ?? '');

    // Hapus rincian produk di tabel `detail_transaksi` terlebih dahulu
    mysqli_query($koneksi, "DELETE FROM detail_transaksi WHERE id_transaksi = '$id_transaksi'");

    // Hapus data transaksi utama di tabel `transaksi`
    $hasil = mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_transaksi = '$id_transaksi'");

    if ($hasil) {
        echo "<script>
        alert('Transaksi berhasil dihapus.'); 
        window.location.href='../pages/transactions/list.php';
        </script>";
    } else {
        echo "<script>
        alert('Gagal menghapus transaksi: " . mysqli_error($koneksi) . "'); 
        window.location.href='../pages/transactions/list.php';
        </script>";
    }
}
