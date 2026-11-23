<?php
// 1. Panggil file koneksi database dan template header
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../../include/header.php';

/** @var mysqli $koneksi */

// 2. Query mengambil seluruh produk Starbucks
$query  = "SELECT * FROM produk ORDER BY id_produk ASC";
$result = mysqli_query($koneksi, $query);

// Kumpulkan data ke array untuk rendering
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
?>

<div class="container my-4">
    <!-- Header Halaman -->
    <div class="sb-header-box">
        <div>
            <h1 class="sb-title">Katalog Menu & Produk</h1>
            <p class="sb-desc">Daftar minuman espresso, latte, cold brew, dan bakery Starbucks</p>
        </div>
        <a href="/starbuck/pages/products/add.php" class="sb-btn sb-btn-green">
            + Tambah Menu Baru
        </a>
    </div>

    <!-- Filter & Pencarian Cepat -->
    <div class="sb-box mb-3 p-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <input type="text" id="cariProduk" class="sb-input-control" placeholder="Ketik nama menu atau kode produk...">
            </div>
            <div class="col-12 col-md-7">
                <div class="d-flex flex-wrap gap-1" id="filterKategori">
                    <button type="button" class="sb-filter-pill active" data-kat="all">Semua</button>
                    <button type="button" class="sb-filter-pill" data-kat="Espresso & Coffee">Espresso</button>
                    <button type="button" class="sb-filter-pill" data-kat="Frappuccino">Frappuccino</button>
                    <button type="button" class="sb-filter-pill" data-kat="Cold Brew">Cold Brew</button>
                    <button type="button" class="sb-filter-pill" data-kat="Tea & Chocolate">Tea & Choc</button>
                    <button type="button" class="sb-filter-pill" data-kat="Bakery & Pastry">Bakery</button>
                    <button type="button" class="sb-filter-pill" data-kat="Snacks & Appetizers">Snacks & Appetizers</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Produk -->
    <div class="sb-table-wrap">
        <div class="table-responsive">
            <table class="sb-main-table" id="tabelProduk">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 12%;">Kode</th>
                        <th style="width: 10%;">Foto</th>
                        <th style="width: 33%;">Nama Menu</th>
                        <th style="width: 18%;">Kategori</th>
                        <th style="width: 12%;">Harga Satuan</th>
                        <th style="width: 10%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="produkBody">
                    <?php
                    $no = 1;
                    foreach ($products as $row) {
                        $img = !empty($row['gambar']) ? $row['gambar'] : 'default.jpg';
                        $kat = !empty($row['kategori']) ? $row['kategori'] : 'Minuman';
                    ?>
                    <tr class="produk-row" data-kategori="<?php echo htmlspecialchars($kat); ?>" data-nama="<?php echo strtolower(htmlspecialchars($row['nama_produk'] . ' ' . $row['id_produk'])); ?>">
                        <td class="text-muted"><?php echo $no++; ?></td>
                        <td>
                            <span class="sb-tag sb-tag-muted">PRD-<?php echo str_pad($row['id_produk'], 3, '0', STR_PAD_LEFT); ?></span>
                        </td>
                        <td>
                            <img src="/starbuck/assets/images/products/<?php echo htmlspecialchars($img); ?>" 
                                 alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" 
                                 class="sb-product-thumb"
                                 onerror="this.src='/starbuck/assets/images/products/default.jpg'">
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama_produk']); ?></strong>
                        </td>
                        <td>
                            <span class="sb-tag sb-tag-green"><?php echo htmlspecialchars($kat); ?></span>
                        </td>
                        <td>
                            <span class="sb-price-tag">Rp <?php echo number_format($row['harga'], 3, ',', '.'); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <a href='edit.php?id_produk=<?php echo $row['id_produk']; ?>' class='sb-action-btn sb-btn-edit'>Edit</a>
                            <a href='/starbuck/process/process_products.php?action=delete&id_produk=<?php echo $row['id_produk']; ?>'
                                class='sb-action-btn sb-btn-del'
                                onclick="return confirm('Apakah Anda yakin ingin menghapus menu <?php echo htmlspecialchars($row['nama_produk']); ?>?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="p-3 text-muted text-end border-top" style="font-size: 0.8rem; border-color: var(--sb-border);">
            Total: <strong><?php echo count($products); ?></strong> menu terdaftar
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('cariProduk');
    const filterPills = document.querySelectorAll('.sb-filter-pill');
    const rows = document.querySelectorAll('.produk-row');
    let activeKat = 'all';

    function filterTable() {
        const q = searchInput.value.toLowerCase().trim();

        rows.forEach(row => {
            const rowNama = row.getAttribute('data-nama');
            const rowKat  = row.getAttribute('data-kategori');

            const matchSearch = (q === '' || rowNama.includes(q));
            const matchKat    = (activeKat === 'all' || rowKat === activeKat);

            if (matchSearch && matchKat) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);

    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeKat = this.getAttribute('data-kat');
            filterTable();
        });
    });
});
</script>

<?php
// 4. Panggil file template footer
include __DIR__ . '/../../include/footer.php';
?>
