<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../../include/header.php';

/** @var mysqli $koneksi */

// 2. Ambil data kasir untuk pilihan dropdown petugas
$kasir_res = mysqli_query($koneksi, "SELECT * FROM kasir ORDER BY nama_kasir ASC");

// 3. Ambil data produk untuk katalog menu POS
$produk_res = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk ASC");
$produk_list = [];
while ($p = mysqli_fetch_assoc($produk_res)) {
    $produk_list[] = $p;
}

$my_kasir_id = $_SESSION['user_id'] ?? '';
?>

<div class="container my-4">
    <!-- Header Halaman -->
    <div class="sb-header-box">
        <div>
            <h1 class="sb-title">POS Kasir - Buat Transaksi Baru</h1>
            <p class="sb-desc">Pilih menu dari katalog visual, terapkan voucher promo, dan selesaikan transaksi</p>
        </div>
        <a href="list.php" class="sb-btn sb-btn-secondary">
            Batal
        </a>
    </div>

    <!-- Form Transaksi Penjualan Utama -->
    <form action="../../process/process_transactions.php" method="post" id="formTransaksi">
        <input type="hidden" name="action" value="add">

        <!-- Bagian 1: Informasi Petugas Kasir & Struk Nota -->
        <div class="sb-box mb-3 p-3">
            <div class="row g-3">
                <div class="col-12 col-md-3">
                    <label for="id_kasir" class="sb-label">Petugas Kasir / Barista <span class="text-danger">*</span></label>
                    <select name="id_kasir" id="id_kasir" class="sb-input-control" required>
                        <option value="">-- Pilih Kasir --</option>
                        <?php while ($k = mysqli_fetch_assoc($kasir_res)) { 
                            $sel = ($k['id_kasir'] == $my_kasir_id) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $k['id_kasir']; ?>" <?php echo $sel; ?>>
                                <?php echo htmlspecialchars($k['nama_kasir']); ?> (<?php echo htmlspecialchars($k['role'] ?? 'Barista'); ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="id_nota" class="sb-label">Nomor Check / Nota <span class="text-danger">*</span></label>
                    <input type="text" name="id_nota" id="id_nota" class="sb-input-control" value="Chk <?php echo rand(1000, 9999); ?>" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="tanggal" class="sb-label">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" id="tanggal" class="sb-input-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="waktu" class="sb-label">Waktu Transaksi</label>
                    <input type="time" name="waktu" id="waktu" class="sb-input-control" value="<?php echo date('H:i'); ?>" required>
                </div>
            </div>
        </div>

        <!-- Bagian 2: Tampilan 2 Kolom (Kiri: Katalog Menu Visual | Kanan: Keranjang Pesanan) -->
        <div class="row g-3">
            
            <!-- Kolom Kiri: Katalog Menu Visual -->
            <div class="col-12 col-lg-7">
                <div class="sb-box h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <h2 class="h6 m-0 font-weight-bold">Pilih Menu Starbucks</h2>
                        <input type="text" id="posSearch" class="sb-input-control" style="width: 220px; font-size: 0.82rem; padding: 0.35rem 0.65rem;" placeholder="Cari nama menu...">
                    </div>

                    <!-- Filter Kategori Tabs -->
                    <div class="d-flex flex-wrap gap-1 mb-3" id="posFilterTabs">
                        <button type="button" class="sb-filter-pill active" data-kat="all">Semua</button>
                        <button type="button" class="sb-filter-pill" data-kat="Espresso & Coffee">Espresso</button>
                        <button type="button" class="sb-filter-pill" data-kat="Frappuccino">Frappuccino</button>
                        <button type="button" class="sb-filter-pill" data-kat="Cold Brew">Cold Brew</button>
                        <button type="button" class="sb-filter-pill" data-kat="Tea & Chocolate">Tea & Choc</button>
                        <button type="button" class="sb-filter-pill" data-kat="Snacks & Appetizers">Snacks</button>
                        <button type="button" class="sb-filter-pill" data-kat="Bakery & Pastry">Bakery</button>
                        <button type="button" class="sb-filter-pill" data-kat="Food & Sandwiches">Food</button>
                    </div>

                    <!-- Grid Kartu Menu Produk -->
                    <div class="sb-pos-grid" id="posGrid">
                        <?php foreach ($produk_list as $prod) { 
                            $img = !empty($prod['gambar']) ? $prod['gambar'] : 'default.jpg';
                            $kat = !empty($prod['kategori']) ? $prod['kategori'] : 'Minuman';
                            $status = $prod['status'] ?? 'Tersedia';
                            $isHabis = ($status === 'Habis');
                        ?>
                        <div class="sb-pos-card <?php echo $isHabis ? 'sb-pos-disabled' : ''; ?>" 
                             data-id="<?php echo $prod['id_produk']; ?>" 
                             data-nama="<?php echo htmlspecialchars($prod['nama_produk']); ?>" 
                             data-harga="<?php echo $prod['harga']; ?>" 
                             data-kategori="<?php echo htmlspecialchars($kat); ?>"
                             data-img="<?php echo htmlspecialchars($img); ?>"
                             data-status="<?php echo $status; ?>">
                            <div style="position: relative;">
                                <img src="/starbuck/assets/images/products/<?php echo htmlspecialchars($img); ?>" 
                                     alt="<?php echo htmlspecialchars($prod['nama_produk']); ?>" 
                                     class="sb-pos-img"
                                     onerror="this.src='/starbuck/assets/images/products/default.jpg'">
                                <?php if ($isHabis) { ?>
                                    <span style="position: absolute; top: 6px; right: 6px; background: rgba(220, 38, 38, 0.9); color: #fff; font-size: 0.65rem; padding: 2px 6px; border-radius: 4px; font-weight: 700;">HABIS</span>
                                <?php } ?>
                            </div>
                            <div class="sb-pos-body">
                                <div class="sb-pos-name"><?php echo htmlspecialchars($prod['nama_produk']); ?></div>
                                <div class="sb-pos-meta">
                                    <span class="sb-price-tag">Rp <?php echo number_format($prod['harga'], 3, ',', '.'); ?></span>
                                    <span class="sb-tag sb-tag-muted" style="font-size: 0.68rem;"><?php echo htmlspecialchars($kat); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Keranjang Pesanan & Pembayaran -->
            <div class="col-12 col-lg-5">
                <div class="sb-box p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom" style="border-color: var(--sb-border);">
                            <h2 class="h6 m-0 font-weight-bold">Rincian Pesanan</h2>
                            <span class="sb-tag sb-tag-green" id="cartCount">0 Item</span>
                        </div>

                        <!-- Daftar Baris Item di Keranjang -->
                        <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                            <table class="sb-main-table" style="font-size: 0.82rem;">
                                <thead>
                                    <tr>
                                        <th style="width: 48%;">Menu</th>
                                        <th style="width: 22%; text-align: center;">Qty</th>
                                        <th style="width: 22%; text-align: right;">Subtotal</th>
                                        <th style="width: 8%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="cartBody">
                                    <!-- Baris item akan dimasukkan otomatis lewat Javascript saat kartu diklik -->
                                </tbody>
                            </table>
                        </div>

                        <div id="cartEmptyNotice" class="text-center text-muted py-4" style="font-size: 0.85rem;">
                            Klik salah satu menu di sebelah kiri untuk menambahkan ke pesanan.
                        </div>
                    </div>

                    <!-- Perhitungan & Pembayaran -->
                    <div class="mt-3 pt-3 border-top" style="border-color: var(--sb-border);">
                        
                        <!-- Pilihan Diskon / Voucher Promosi -->
                        <div class="mb-3">
                            <label for="promoSelect" class="sb-label mb-1">Voucher / Promo Starbucks</label>
                            <select id="promoSelect" class="sb-input-control" style="font-size: 0.82rem;">
                                <option value="0" data-type="nominal">Tanpa Promosi</option>
                                <option value="5000" data-type="nominal">Bawa Tumbler Sendiri (-Rp 5.000)</option>
                                <option value="10" data-type="percent">Member Starbucks Rewards (-10%)</option>
                                <option value="15" data-type="percent">Partner / Staff Discount (-15%)</option>
                            </select>
                        </div>

                        <!-- Kotak Kalkulasi -->
                        <div class="sb-receipt-calc p-2 mb-3">
                            <div class="sb-calc-row">
                                <span>Subtotal</span>
                                <span>Rp <span id="lblSubtotal">0.000</span></span>
                                <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
                            </div>
                            <div class="sb-calc-row" id="rowDiskon" style="display: none; color: #b45309;">
                                <span>Potongan Diskon</span>
                                <span>-Rp <span id="lblDiskon">0.000</span></span>
                                <input type="hidden" name="diskon" id="inputDiskon" value="0">
                            </div>
                            <div class="sb-calc-row">
                                <span>Pajak Restoran (11%)</span>
                                <span>Rp <span id="lblPajak">0.000</span></span>
                                <input type="hidden" name="pajak" id="inputPajak" value="0">
                            </div>
                            <div class="sb-calc-total pt-2">
                                <span>TOTAL PEMBAYARAN</span>
                                <span class="sb-price-tag" style="font-size: 1.15rem;">
                                    Rp <span id="lblTotal">0.000</span>
                                </span>
                                <input type="hidden" name="total" id="inputTotal" value="0">
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            <label class="sb-label mb-1">Metode Pembayaran</label>
                            <div class="d-flex gap-2">
                                <label class="flex-fill border rounded p-2 text-center" style="border-color: var(--sb-border); cursor: pointer; font-size: 0.82rem;">
                                    <input type="radio" name="metode_pembayaran" value="Tunai" checked id="payTunai"> Tunai
                                </label>
                                <label class="flex-fill border rounded p-2 text-center" style="border-color: var(--sb-border); cursor: pointer; font-size: 0.82rem;">
                                    <input type="radio" name="metode_pembayaran" value="QRIS" id="payQRIS"> QRIS / Card
                                </label>
                                <label class="flex-fill border rounded p-2 text-center" style="border-color: var(--sb-border); cursor: pointer; font-size: 0.82rem;">
                                    <input type="radio" name="metode_pembayaran" value="Debit" id="payDebit"> Debit/Kredit
                                </label>
                            </div>
                        </div>

                        <!-- Input Uang Diterima & Kembalian (Untuk Tunai) -->
                        <div id="cashSection" class="mb-3">
                            <label for="bayar" class="sb-label">Jumlah Uang Diterima (Rp)</label>
                            <input type="number" step="0.001" name="bayar" id="bayar" class="sb-input-control mb-1" placeholder="0.000">
                            
                            <!-- Preset Tombol Cepat -->
                            <div class="d-flex gap-1 mb-2">
                                <button type="button" class="sb-quick-cash-btn" id="btnUangPas">Uang Pas</button>
                                <button type="button" class="sb-quick-cash-btn" data-amt="50000">50.000</button>
                                <button type="button" class="sb-quick-cash-btn" data-amt="100000">100.000</button>
                                <button type="button" class="sb-quick-cash-btn" data-amt="200000">200.000</button>
                            </div>

                            <div class="d-flex justify-content-between font-weight-bold" style="font-size: 0.85rem;">
                                <span>Kembalian:</span>
                                <span class="sb-price-tag">Rp <span id="lblKembalian">0.000</span></span>
                                <input type="hidden" name="kembalian" id="inputKembalian" value="0">
                            </div>
                        </div>

                        <!-- Tombol Submit Simpan Transaksi -->
                        <button type="submit" class="sb-btn sb-btn-green w-100 py-2" id="btnSubmit" style="font-size: 0.95rem; font-weight: 600;" disabled>
                            Simpan Transaksi & Cetak Struk
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

<!-- ========================================================
     JAVASCRIPT: LOGIKA POS VISUAL, DISKON & KEMBALIAN
     ======================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cart = []; // { id, nama, harga, kategori, img, qty }
    const cartBody = document.getElementById('cartBody');
    const cartCount = document.getElementById('cartCount');
    const emptyNotice = document.getElementById('cartEmptyNotice');
    const btnSubmit = document.getElementById('btnSubmit');

    const promoSelect = document.getElementById('promoSelect');
    const rowDiskon   = document.getElementById('rowDiskon');
    const lblDiskon   = document.getElementById('lblDiskon');
    const inputDiskon = document.getElementById('inputDiskon');

    const lblSubtotal = document.getElementById('lblSubtotal');
    const lblPajak    = document.getElementById('lblPajak');
    const lblTotal    = document.getElementById('lblTotal');
    const inputSubtotal = document.getElementById('inputSubtotal');
    const inputPajak    = document.getElementById('inputPajak');
    const inputTotal    = document.getElementById('inputTotal');

    const payRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
    const cashSection = document.getElementById('cashSection');
    const inputBayar = document.getElementById('bayar');
    const lblKembalian = document.getElementById('lblKembalian');
    const inputKembalian = document.getElementById('inputKembalian');
    const btnUangPas = document.getElementById('btnUangPas');

    // 1. Render Keranjang
    function renderCart() {
        cartBody.innerHTML = '';

        if (cart.length === 0) {
            emptyNotice.style.display = 'block';
            btnSubmit.disabled = true;
            cartCount.innerText = '0 Item';
        } else {
            emptyNotice.style.display = 'none';
            btnSubmit.disabled = false;
            let totalQty = 0;

            cart.forEach((item, index) => {
                totalQty += item.qty;
                const sub = item.harga * item.qty;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="/starbuck/assets/images/products/${item.img}" 
                                 class="sb-product-thumb" style="width: 32px; height: 32px;" 
                                 onerror="this.src='/starbuck/assets/images/products/default.jpg'">
                            <div>
                                <strong>${item.nama}</strong>
                                <small class="text-muted d-block">Rp ${item.harga.toLocaleString('id-ID', {minimumFractionDigits: 3})}</small>
                            </div>
                        </div>
                        <input type="hidden" name="items[${index}][id_produk]" value="${item.id}">
                        <input type="hidden" name="items[${index}][kategori]" value="${item.kategori}">
                        <input type="hidden" name="items[${index}][harga]" value="${item.harga.toFixed(3)}">
                        <input type="hidden" name="items[${index}][kuantitas]" value="${item.qty}">
                        <input type="hidden" name="items[${index}][subtotal]" value="${sub.toFixed(3)}">
                    </td>
                    <td style="text-align: center;">
                        <div class="d-inline-flex align-items-center gap-1">
                            <button type="button" class="sb-qty-btn btn-minus" data-id="${item.id}">-</button>
                            <span style="font-weight: 600; width: 18px; text-align: center;">${item.qty}</span>
                            <button type="button" class="sb-qty-btn btn-plus" data-id="${item.id}">+</button>
                        </div>
                    </td>
                    <td style="text-align: right; font-weight: 600;">
                        Rp ${sub.toLocaleString('id-ID', {minimumFractionDigits: 3})}
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="sb-action-btn sb-btn-del btn-remove" data-id="${item.id}" style="padding: 0.15rem 0.4rem; font-size: 0.72rem;">Hapus</button>
                    </td>
                `;
                cartBody.appendChild(tr);
            });
            cartCount.innerText = `${totalQty} Item`;
        }
        calculateTotal();
    }

    // 2. Hitung Total & Diskon
    function calculateTotal() {
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.harga * item.qty;
        });

        const promoOpt  = promoSelect.options[promoSelect.selectedIndex];
        const promoVal  = parseFloat(promoOpt.value) || 0;
        const promoType = promoOpt.getAttribute('data-type') || 'nominal';
        let diskon = 0;

        if (subtotal > 0 && promoVal > 0) {
            if (promoType === 'percent') {
                diskon = subtotal * (promoVal / 100);
            } else {
                diskon = Math.min(promoVal / 1000, subtotal);
            }
        }

        const taxable = Math.max(0, subtotal - diskon);
        const pajak = taxable * 0.11;
        const total = taxable + pajak;

        lblSubtotal.innerText = subtotal.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });

        if (diskon > 0) {
            rowDiskon.style.display = 'flex';
            lblDiskon.innerText = diskon.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
        } else {
            rowDiskon.style.display = 'none';
        }

        lblPajak.innerText    = pajak.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
        lblTotal.innerText    = total.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });

        inputSubtotal.value = subtotal.toFixed(3);
        inputDiskon.value   = diskon.toFixed(3);
        inputPajak.value    = pajak.toFixed(3);
        inputTotal.value    = total.toFixed(3);

        calculateChange();
    }

    // 3. Hitung Kembalian
    function calculateChange() {
        const total = parseFloat(inputTotal.value) || 0;
        const bayar = parseFloat(inputBayar.value) || 0;
        let kembalian = 0;

        if (bayar >= total && total > 0) {
            kembalian = bayar - total;
        }

        lblKembalian.innerText = kembalian.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
        inputKembalian.value   = kembalian.toFixed(3);
    }

    // Event perubahan promo
    promoSelect.addEventListener('change', calculateTotal);

    // 4. Klik kartu menu di grid
    document.getElementById('posGrid').addEventListener('click', function(e) {
        const card = e.target.closest('.sb-pos-card');
        if (!card) return;

        if (card.getAttribute('data-status') === 'Habis') {
            alert('Menu ini saat ini sedang habis stok.');
            return;
        }

        const id    = card.getAttribute('data-id');
        const nama  = card.getAttribute('data-nama');
        const harga = parseFloat(card.getAttribute('data-harga')) || 0;
        const kat   = card.getAttribute('data-kategori');
        const img   = card.getAttribute('data-img');

        const existing = cart.find(item => item.id == id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, nama, harga, kategori: kat, img, qty: 1 });
        }
        renderCart();
    });

    // 5. Tombol plus, minus, hapus di keranjang
    cartBody.addEventListener('click', function(e) {
        const plusBtn  = e.target.closest('.btn-plus');
        const minusBtn = e.target.closest('.btn-minus');
        const rmBtn    = e.target.closest('.btn-remove');

        if (plusBtn) {
            const id = plusBtn.getAttribute('data-id');
            const item = cart.find(i => i.id == id);
            if (item) { item.qty++; renderCart(); }
        }
        if (minusBtn) {
            const id = minusBtn.getAttribute('data-id');
            const item = cart.find(i => i.id == id);
            if (item) {
                item.qty--;
                if (item.qty <= 0) {
                    cart = cart.filter(i => i.id != id);
                }
                renderCart();
            }
        }
        if (rmBtn) {
            const id = rmBtn.getAttribute('data-id');
            cart = cart.filter(i => i.id != id);
            renderCart();
        }
    });

    // 6. Pencarian Menu Visual
    const posSearch = document.getElementById('posSearch');
    const posCards  = document.querySelectorAll('.sb-pos-card');
    const filterTabs = document.querySelectorAll('#posFilterTabs .sb-filter-pill');
    let activePosKat = 'all';

    function filterPosGrid() {
        const q = posSearch.value.toLowerCase().trim();
        posCards.forEach(card => {
            const nama = card.getAttribute('data-nama').toLowerCase();
            const kat  = card.getAttribute('data-kategori');

            const matchSearch = (q === '' || nama.includes(q));
            const matchKat    = (activePosKat === 'all' || kat === activePosKat);

            if (matchSearch && matchKat) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    posSearch.addEventListener('input', filterPosGrid);

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activePosKat = this.getAttribute('data-kat');
            filterPosGrid();
        });
    });

    // 7. Metode Pembayaran
    payRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Tunai') {
                cashSection.style.display = 'block';
            } else {
                cashSection.style.display = 'none';
                inputBayar.value = inputTotal.value;
                calculateChange();
            }
        });
    });

    // 8. Input Uang
    inputBayar.addEventListener('input', calculateChange);

    btnUangPas.addEventListener('click', function() {
        const total = parseFloat(inputTotal.value) || 0;
        inputBayar.value = total.toFixed(3);
        calculateChange();
    });

    document.querySelectorAll('.sb-quick-cash-btn[data-amt]').forEach(btn => {
        btn.addEventListener('click', function() {
            const amt = parseFloat(this.getAttribute('data-amt')) / 1000;
            inputBayar.value = amt.toFixed(3);
            calculateChange();
        });
    });

    // 9. Validasi Submit
    document.getElementById('formTransaksi').addEventListener('submit', function(e) {
        if (cart.length === 0) {
            alert('Silakan pilih minimal satu menu pesanan.');
            e.preventDefault();
            return;
        }

        const isTunai = document.getElementById('payTunai').checked;
        const total = parseFloat(inputTotal.value) || 0;
        const bayar = parseFloat(inputBayar.value) || 0;

        if (isTunai && bayar < total) {
            alert('Uang yang dibayarkan kurang dari total pesanan!');
            inputBayar.focus();
            e.preventDefault();
        }
    });
});
</script>

<?php
// 4. Panggil file template footer
include __DIR__ . '/../../include/footer.php';
?>
