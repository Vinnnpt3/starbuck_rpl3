<?php
    include '../../include/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6 mt-5">
            <form action="../../process/process_cashiers.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-floating mt-5">
                    <label for="floatingInput">Id Kasir</label>
                    <input type="number" name="id" class="form-control" id="floatingInput" placeholder="Id Kasir">
                </div>
                <div class="form-floating mt-5">
                    <label for="floatingInput">Nama Kasir</label>
                    <input type="text" name="nama_kasir" class="form-control" id="floatingInput"
                        placeholder="Nama Kasir">
                </div>
                <div class="form-floating mt-5">
                    <label for="floatingInput">No Telp</label>
                    <input type="number" name="no_telp" class="form-control" id="floatingInput" placeholder="No Telp">
                </div>
                <div class="form-floating mt-5">
                    <label for="floatingInput">Alamat</label>
                    <input type="text" name="alamat" class="form-control" id="floatingInput" placeholder="Alamat">
                </div>
                <div class="form-floating mt-5">
                    <label for="floatingInput">Email</label>
                    <input type="email" name="email" class="form-control" id="floatingInput"
                        placeholder="nama@contoh.com">
                </div>
                <button type="submit" class="btn-solid theme-success mt-5">Tambah Data</button>
            </form>
        </div>
        <div class="col-3"></div>
    </div>
</div>


<?php
    include '../../include/footer.php';
?>