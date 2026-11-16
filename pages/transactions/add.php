<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/starbuck');
}
include ROOTPATH . '/include/header.php';
include ROOTPATH . '/config/config.php';

/** @var mysqli $koneksi */

// Ambil data kasir
$kasir_res = mysqli_query($koneksi, "SELECT * FROM kasir ORDER BY nama_kasir ASC");

// Ambil data produk
$produk_res = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY nama_produk ASC");
$produk_list = [];
while ($p = mysqli_fetch_assoc($produk_res)) {
    $produk_list[] = $p;
}
?>

<div class="container my-4">
    <div class="sb-page-header">
        <div>
            <h1 class="sb-page-title">POS Kasir - Buat Transaksi Baru</h1>
            <p class="sb-page-desc">Entri pesanan pelanggan dan cetak struk penjualan</p>
        </div>
        <a href="list.php" class="btn-sb-secondary">
            Batal
        </a>
    </div>

    <form action="../../process/process_transactions.php" method="post" id="formTransaksi">
        <input type="hidden" name="action" value="add">

        <!-- Info Petugas & Nota -->
        <div class="sb-card mb-4">
            <div class="row g-3">
                <div class="col-12 col-md-3">
                    <label for="id_kasir" class="sb-form-label">Petugas Kasir</label>
                    <select name="id_kasir" id="id_kasir" class="sb-input" required>
                        <option value="">-- Pilih Kasir --</option>
                        <?php while ($k = mysqli_fetch_assoc($kasir_res)) { ?>
                            <option value="<?php echo $k['id_kasir']; ?>"><?php echo htmlspecialchars($k['nama_kasir']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="id_nota" class="sb-form-label">Nomor Nota</label>
                    <input type="text" name="id_nota" id="id_nota" class="sb-input" value="Chk <?php echo rand(1000, 9999); ?>" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="tanggal" class="sb-form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="sb-input" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="waktu" class="sb-form-label">Waktu</label>
                    <input type="time" name="waktu" id="waktu" class="sb-input" value="<?php echo date('H:i'); ?>" required>
                </div>
            </div>
        </div>

        <!-- Daftar Menu Pesanan -->
        <div class="sb-table-card mb-4">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color: var(--sb-border);">
                <div class="font-weight-bold" style="color: #ffffff; font-size: 0.95rem;">Item Pesanan</div>
                <button type="button" class="btn-sb-secondary" id="btnTambahBaris" style="padding: 0.3rem 0.75rem; font-size: 0.8rem;">
                    + Tambah Baris
                </button>
            </div>

            <div class="table-responsive">
                <table class="sb-table" id="tabelItem">
                    <thead>
                        <tr>
                            <th style="width: 38%;">Pilihan Menu</th>
                            <th style="width: 18%;">Kategori</th>
                            <th style="width: 16%;">Harga Satuan</th>
                            <th style="width: 10%;">Qty</th>
                            <th style="width: 14%;">Subtotal</th>
                            <th style="width: 4%; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemBody">
                        <tr class="item-row">
                            <td>
                                <select name="items[0][id_produk]" class="sb-input select-produk" required>
                                    <option value="" data-harga="0">-- Pilih Produk --</option>
                                    <?php foreach ($produk_list as $prod) { ?>
                                        <option value="<?php echo $prod['id_produk']; ?>" data-harga="<?php echo $prod['harga']; ?>">
                                            <?php echo htmlspecialchars($prod['nama_produk']); ?> (Rp <?php echo number_format($prod['harga'], 3, ',', '.'); ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select name="items[0][kategori]" class="sb-input">
                                    <option value="Minuman">Minuman</option>
                                    <option value="Makanan">Makanan</option>
                                    <option value="Tambahan">Tambahan</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[0][harga]" class="sb-input input-harga num-tabular" readonly value="0.000">
                            </td>
                            <td>
                                <input type="number" min="1" name="items[0][kuantitas]" class="sb-input input-kuantitas text-center num-tabular" value="1" required>
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[0][subtotal]" class="sb-input input-subtotal num-tabular" readonly value="0.000">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn-action-delete btn-hapus-baris" style="cursor: pointer; border: none; padding: 0.3rem 0.55rem;">✕</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Checkout Summary Box -->
            <div class="p-3 border-top" style="border-color: var(--sb-border);">
                <div class="row justify-content-end">
                    <div class="col-12 col-md-5">
                        <div class="sb-receipt-summary">
                            <div class="sb-receipt-row">
                                <span>Subtotal</span>
                                <span class="num-tabular">Rp <span id="lblSubtotal">0.000</span></span>
                                <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
                            </div>
                            <div class="sb-receipt-row">
                                <span>Pajak Restoran (11%)</span>
                                <span class="num-tabular">Rp <span id="lblPajak">0.000</span></span>
                                <input type="hidden" name="pajak" id="inputPajak" value="0">
                            </div>
                            <div class="sb-receipt-total">
                                <span>Total Bayar</span>
                                <span class="num-tabular text-price">Rp <span id="lblTotal">0.000</span></span>
                                <input type="hidden" name="total" id="inputTotal" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="list.php" class="btn-sb-secondary" style="padding: 0.6rem 1.25rem;">Batal</a>
            <button type="submit" class="btn-sb-primary" style="padding: 0.6rem 1.5rem; font-size: 0.92rem;">
                Simpan & Selesaikan Transaksi
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    const itemBody = document.getElementById('itemBody');
    const btnTambah = document.getElementById('btnTambahBaris');

    function hitungTotal() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
            const qty = parseInt(row.querySelector('.input-kuantitas').value) || 0;
            const sub = harga * qty;
            row.querySelector('.input-subtotal').value = sub.toFixed(3);
            subtotal += sub;
        });

        const pajak = subtotal * 0.11;
        const total = subtotal + pajak;

        document.getElementById('lblSubtotal').innerText = subtotal.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
        document.getElementById('lblPajak').innerText = pajak.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
        document.getElementById('lblTotal').innerText = total.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });

        document.getElementById('inputSubtotal').value = subtotal.toFixed(3);
        document.getElementById('inputPajak').value = pajak.toFixed(3);
        document.getElementById('inputTotal').value = total.toFixed(3);
    }

    itemBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('select-produk')) {
            const selectedOpt = e.target.options[e.target.selectedIndex];
            const harga = selectedOpt.getAttribute('data-harga') || 0;
            const row = e.target.closest('.item-row');
            row.querySelector('.input-harga').value = parseFloat(harga).toFixed(3);
            hitungTotal();
        }
        if (e.target.classList.contains('input-kuantitas')) {
            hitungTotal();
        }
    });

    itemBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-kuantitas')) {
            hitungTotal();
        }
    });

    itemBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-hapus-baris')) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('.item-row').remove();
                hitungTotal();
            } else {
                alert('Transaksi minimal harus memiliki satu produk.');
            }
        }
    });

    btnTambah.addEventListener('click', function() {
        const firstRow = document.querySelector('.item-row');
        const clone = firstRow.cloneNode(true);
        
        clone.querySelector('.select-produk').name = `items[${rowIndex}][id_produk]`;
        clone.querySelector('.select-produk').selectedIndex = 0;
        
        clone.querySelector('select[name*="[kategori]"]').name = `items[${rowIndex}][kategori]`;
        
        clone.querySelector('.input-harga').name = `items[${rowIndex}][harga]`;
        clone.querySelector('.input-harga').value = '0.000';
        
        clone.querySelector('.input-kuantitas').name = `items[${rowIndex}][kuantitas]`;
        clone.querySelector('.input-kuantitas').value = '1';
        
        clone.querySelector('.input-subtotal').name = `items[${rowIndex}][subtotal]`;
        clone.querySelector('.input-subtotal').value = '0.000';
        
        itemBody.appendChild(clone);
        rowIndex++;
        hitungTotal();
    });
});
</script>

<?php
include ROOTPATH . '/include/footer.php';
?>
