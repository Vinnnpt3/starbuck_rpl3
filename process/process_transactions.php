<?php
include __DIR__ . '/../config/config.php';

/** @var mysqli $koneksi */

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == "add") {
    $id_kasir = $_POST['id_kasir'];
    $id_nota  = mysqli_real_escape_string($koneksi, $_POST['id_nota']);
    $tanggal  = $_POST['tanggal'];
    $waktu    = $_POST['waktu'];
    $subtotal = floatval($_POST['subtotal']);
    $pajak    = floatval($_POST['pajak']);
    $total    = floatval($_POST['total']);
    $items    = $_POST['items'] ?? [];

    // Cari ID transaksi baru berikutnya
    $max_tx = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(id_transaksi) AS max_id FROM transaksi"));
    $id_transaksi = ($max_tx['max_id'] ? $max_tx['max_id'] + 1 : 1);

    // Simpan ke tabel transaksi
    $query_tx = "INSERT INTO transaksi (id_transaksi, subtotal, pajak, total, tanggal, waktu) 
                 VALUES ('$id_transaksi', '$subtotal', '$pajak', '$total', '$tanggal', '$waktu')";
    $hasil_tx = mysqli_query($koneksi, $query_tx);

    if ($hasil_tx) {
        // Ambil ID detail transaksi tertinggi
        $max_dt = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(id_detail_transaksi) AS max_id FROM detail_transaksi"));
        $next_dt_id = ($max_dt['max_id'] ? $max_dt['max_id'] + 1 : 1);

        foreach ($items as $item) {
            $id_produk = $item['id_produk'] ?? '';
            if (empty($id_produk)) {
                continue;
            }

            $kategori        = mysqli_real_escape_string($koneksi, $item['kategori']);
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
} else if ($action == "delete") {
    $id_transaksi = $_GET['id_transaksi'] ?? '';

    // Hapus detail terlebih dahulu
    mysqli_query($koneksi, "DELETE FROM detail_transaksi WHERE id_transaksi = '$id_transaksi'");

    // Hapus transaksi utama
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

