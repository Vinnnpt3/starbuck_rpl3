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

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-12">
            <h3>Tambah Transaksi Baru</h3>
            <hr>
            <form action="../../process/process_transactions.php" method="post" id="formTransaksi">
                <input type="hidden" name="action" value="add">

                <div class="card p-3 mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="id_kasir" class="form-label"><strong>Kasir</strong></label>
                            <select name="id_kasir" id="id_kasir" class="form-control" required>
                                <option value="">-- Pilih Kasir --</option>
                                <?php while ($k = mysqli_fetch_assoc($kasir_res)) { ?>
                                    <option value="<?php echo $k['id_kasir']; ?>"><?php echo htmlspecialchars($k['nama_kasir']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="id_nota" class="form-label"><strong>Nomor Nota</strong></label>
                            <input type="text" name="id_nota" id="id_nota" class="form-control" value="Chk <?php echo rand(1000, 9999); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tanggal" class="form-label"><strong>Tanggal</strong></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="waktu" class="form-label"><strong>Waktu</strong></label>
                            <input type="time" name="waktu" id="waktu" class="form-control" value="<?php echo date('H:i'); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="card p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Item Produk</h5>
                        <button type="button" class="btn-solid theme-success btn-sm" id="btnTambahBaris">+ Tambah Baris</button>
                    </div>

                    <table class="table table-bordered" id="tabelItem">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Produk</th>
                                <th style="width: 20%;">Kategori</th>
                                <th style="width: 15%;">Harga Satuan</th>
                                <th style="width: 10%;">Kuantitas</th>
                                <th style="width: 15%;">Subtotal</th>
                                <th style="width: 5%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemBody">
                            <tr class="item-row">
                                <td>
                                    <select name="items[0][id_produk]" class="form-control select-produk" required>
                                        <option value="" data-harga="0">-- Pilih Produk --</option>
                                        <?php foreach ($produk_list as $prod) { ?>
                                            <option value="<?php echo $prod['id_produk']; ?>" data-harga="<?php echo $prod['harga']; ?>">
                                                <?php echo htmlspecialchars($prod['nama_produk']); ?> (Rp <?php echo number_format($prod['harga'], 3, ',', '.'); ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="items[0][kategori]" class="form-control">
                                        <option value="Minuman">Minuman</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Tambahan">Tambahan</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="items[0][harga]" class="form-control input-harga" readonly value="0">
                                </td>
                                <td>
                                    <input type="number" min="1" name="items[0][kuantitas]" class="form-control input-kuantitas" value="1" required>
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="items[0][subtotal]" class="form-control input-subtotal" readonly value="0">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-hapus-baris">X</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row justify-content-end mt-3">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Subtotal:</strong>
                                <span>Rp <span id="lblSubtotal">0.000</span></span>
                                <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Pajak (11%):</strong>
                                <span>Rp <span id="lblPajak">0.000</span></span>
                                <input type="hidden" name="pajak" id="inputPajak" value="0">
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h4><strong>Total:</strong></h4>
                                <h4><strong>Rp <span id="lblTotal">0.000</span></strong></h4>
                                <input type="hidden" name="total" id="inputTotal" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-solid theme-success">Simpan Transaksi</button>
                    <a href="list.php" class="btn-solid theme-warning">Batal</a>
                </div>
            </form>
        </div>
    </div>
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
                alert('Transaksi minimal memiliki satu item produk.');
            }
        }
    });

    btnTambah.addEventListener('click', function() {
        const firstRow = document.querySelector('.item-row');
        const clone = firstRow.cloneNode(true);
        
        // Update names
        clone.querySelector('.select-produk').name = `items[${rowIndex}][id_produk]`;
        clone.querySelector('.select-produk').selectedIndex = 0;
        
        clone.querySelector('select[name*="[kategori]"]').name = `items[${rowIndex}][kategori]`;
        
        clone.querySelector('.input-harga').name = `items[${rowIndex}][harga]`;
        clone.querySelector('.input-harga').value = '0';
        
        clone.querySelector('.input-kuantitas').name = `items[${rowIndex}][kuantitas]`;
        clone.querySelector('.input-kuantitas').value = '1';
        
        clone.querySelector('.input-subtotal').name = `items[${rowIndex}][subtotal]`;
        clone.querySelector('.input-subtotal').value = '0';
        
        itemBody.appendChild(clone);
        rowIndex++;
        hitungTotal();
    });
});
</script>

<?php
include ROOTPATH . '/include/footer.php';
?>

