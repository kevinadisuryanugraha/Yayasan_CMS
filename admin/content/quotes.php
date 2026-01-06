<?php
// Manajemen Kutipan
$query = mysqli_query($conn, "SELECT * FROM quotes ORDER BY order_position ASC, id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

$alert_script = '';
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    $alert_script = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '{$alert['type']}',
                title: '{$alert['title']}',
                text: '{$alert['message']}',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    ";
    unset($_SESSION['alert']);
}
?>

<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="#">Konten Home</a></li>
                        <li class="breadcrumb-item active">Kutipan</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Kutipan</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Petunjuk -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Kutipan?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Kutipan (Quotes)</strong> adalah kata-kata inspiratif yang ditampilkan dalam bentuk 
                                slider/carousel di halaman utama. Kutipan ini dapat berupa ayat Al-Quran, hadits, 
                                atau kata-kata bijak dari ulama dan tokoh Islam.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-format-quote-close text-primary" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Kata-kata Inspiratif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Konten -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Daftar Kutipan</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola kutipan inspiratif yang ditampilkan di slider
                            </p>
                        </div>
                        <div>
                            <a href="?page=edit_quote_settings" class="btn btn-info mr-2">
                                <i class="mdi mdi-settings"></i> Atur Background
                            </a>
                            <a href="?page=add_quote" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Tambah Kutipan Baru
                            </a>
                        </div>
                    </div>

                    <?php if (count($rows) > 0): ?>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="35%">Kutipan</th>
                                    <th width="15%">Penulis</th>
                                    <th width="20%">Sumber</th>
                                    <th width="8%">Urutan</th>
                                    <th width="8%">Status</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $key => $row): ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td>
                                            <i class="mdi mdi-format-quote-open text-primary"></i>
                                            <?php 
                                            $quote = htmlspecialchars($row['quote_text'] ?? '');
                                            echo strlen($quote) > 80 ? substr($quote, 0, 80) . '...' : $quote;
                                            ?>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['author'] ?? '-'); ?></strong></td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($row['source'] ?? '-'); ?></small></td>
                                        <td><span class="badge badge-info"><?php echo $row['order_position'] ?? 0; ?></span></td>
                                        <td>
                                            <?php if ($row['is_active']): ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="?page=edit_quote&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Ubah">
                                                <i class="mdi mdi-pencil"></i> Ubah
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus"
                                                data-id="<?php echo $row['id']; ?>"
                                                data-quote="<?php echo htmlspecialchars(substr($row['quote_text'] ?? '', 0, 50)); ?>">
                                                <i class="mdi mdi-delete"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="mdi mdi-format-quote-close text-muted" style="font-size: 48px;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Kutipan</h5>
                        <p class="text-muted">Klik "Tambah Kutipan Baru" untuk membuat kutipan pertama Anda.</p>
                        <a href="?page=add_quote" class="btn btn-success mt-2">
                            <i class="mdi mdi-plus"></i> Tambah Kutipan Baru
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Panduan -->
    <div class="row">
        <!-- Cara Penggunaan -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">
                            <strong>Menambah Kutipan:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Kutipan Baru".</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengubah Kutipan:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" untuk memperbarui.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengatur Urutan:</strong><br>
                            <small class="text-muted">Angka urutan menentukan posisi di slider.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Atur Background:</strong><br>
                            <small class="text-muted">Klik "Atur Background" untuk mengubah gambar latar.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tips & Contoh -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Contoh Kutipan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge badge-success p-2 m-1">Ayat Al-Quran</span>
                        <span class="badge badge-success p-2 m-1">Hadits Nabi</span>
                        <span class="badge badge-success p-2 m-1">Kata Ulama</span>
                    </div>
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Kutipan:</strong> Isi dengan kata-kata inspiratif</li>
                        <li class="mb-2"><strong>Penulis:</strong> Sumber kutipan (misal: Nabi Muhammad SAW)</li>
                        <li class="mb-0"><strong>Sumber:</strong> Referensi (misal: HR. Bukhari)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjelasan Kolom -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-table mr-2"></i>Penjelasan Kolom Tabel</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Kutipan</span></td>
                                    <td>Isi teks kutipan inspiratif</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Penulis</span></td>
                                    <td>Nama penulis/sumber kutipan</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Sumber</span></td>
                                    <td>Referensi atau kitab asal</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-info">Urutan</span></td>
                                    <td>Posisi di slider (kecil = duluan)</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>

<script>
// SweetAlert untuk konfirmasi hapus
document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const quote = this.dataset.quote;
        
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Kutipan?',
            html: 'Anda akan menghapus kutipan:<br><em>"' + quote + '..."</em><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
            showCancelButton: true,
            confirmButtonText: '<i class="mdi mdi-delete"></i> Ya, Hapus!',
            cancelButtonText: '<i class="mdi mdi-close"></i> Batal',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = '?page=delete_quote&id=' + id + '&confirm=yes';
            }
        });
    });
});
</script>