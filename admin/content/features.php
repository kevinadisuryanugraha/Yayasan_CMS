<?php
// Manajemen Feature Section
$query = mysqli_query($conn, "SELECT * FROM feature_section ORDER BY order_position ASC, id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Cek apakah ada alert dari session
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
                showConfirmButton: false,
                timer: 2000
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
                        <li class="breadcrumb-item active">Feature Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Feature Section</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Feature Section?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Feature Section</strong> adalah bagian yang menampilkan kartu-kartu fitur/layanan unggulan di halaman utama website. 
                                Setiap kartu biasanya berisi ikon, judul, dan deskripsi singkat. Contoh: Kajian Al-Quran, Sejarah Islam, 
                                Konsultasi Syariah, dll. Bagian ini membantu pengunjung mengetahui layanan apa saja yang tersedia.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-cards-outline text-primary" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Kartu Fitur/Layanan</small>
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
                            <h4 class="mt-0 header-title">Daftar Kartu Fitur</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola kartu fitur yang ditampilkan di halaman utama website
                            </p>
                        </div>
                        <a href="?page=add_feature" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Fitur Baru
                        </a>
                    </div>

                    <?php if (count($rows) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Ikon</th>
                                        <th width="20%">Judul</th>
                                        <th width="25%">Deskripsi</th>
                                        <th width="10%">Urutan</th>
                                        <th width="10%">Status</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $key => $row): ?>
                                            <tr>
                                                <td><?php echo $key + 1; ?></td>
                                                <td>
                                                    <?php if (!empty($row['icon'])): ?>
                                                            <img src="<?php echo '../' . $row['icon']; ?>" alt="Ikon Fitur"
                                                                style="max-width: 50px; height: auto; border-radius: 4px;">
                                                    <?php else: ?>
                                                            <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['title'] ?? ''); ?></td>
                                                <td>
                                                    <?php
                                                    $desc = htmlspecialchars($row['description'] ?? '');
                                                    echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo $row['order_position'] ?? 0; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($row['is_active']): ?>
                                                            <span class="badge badge-success">Aktif</span>
                                                    <?php else: ?>
                                                            <span class="badge badge-secondary">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="?page=edit_feature&id=<?php echo $row['id']; ?>"
                                                        class="btn btn-sm btn-primary" title="Ubah">
                                                        <i class="mdi mdi-pencil"></i> Ubah
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                        title="Hapus"
                                                        data-id="<?php echo $row['id']; ?>"
                                                        data-title="<?php echo htmlspecialchars($row['title'] ?? 'Tanpa Judul'); ?>">
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
                            <i class="mdi mdi-star-outline text-muted" style="font-size: 48px;"></i>
                            <h5 class="mt-3 text-muted">Belum Ada Kartu Fitur</h5>
                            <p class="text-muted">Klik "Tambah Fitur Baru" untuk membuat kartu fitur pertama Anda.</p>
                            <a href="?page=add_feature" class="btn btn-success mt-2">
                                <i class="mdi mdi-plus"></i> Tambah Fitur Baru
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Panduan & Tips -->
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
                            <strong>Menambah Fitur Baru:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Fitur Baru", lalu isi ikon, judul, dan deskripsi.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengubah Fitur:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" pada baris fitur yang ingin diubah.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Menghapus Fitur:</strong><br>
                            <small class="text-muted">Klik tombol merah "Hapus". Akan muncul konfirmasi sebelum dihapus.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Mengatur Urutan:</strong><br>
                            <small class="text-muted">Gunakan angka urutan untuk menentukan posisi tampilan kartu.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tips & Contoh -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Contoh Fitur</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">
                            <strong>Ikon Menarik:</strong><br>
                            <small class="text-muted">Gunakan ikon/gambar yang relevan dan berkualitas tinggi.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Judul Singkat:</strong><br>
                            <small class="text-muted">Maksimal 3-4 kata agar mudah dibaca. Contoh: "Kajian Al-Quran"</small>
                        </li>
                        <li class="mb-2">
                            <strong>Deskripsi Informatif:</strong><br>
                            <small class="text-muted">Jelaskan fitur dengan singkat, 1-2 kalimat saja.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Contoh Fitur:</strong><br>
                            <small class="text-muted">Kajian Quran, Sejarah Islam, Konsultasi Syariah, Kelas Tahfidz</small>
                        </li>
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
                                    <td width="30%"><span class="badge badge-dark">Ikon</span></td>
                                    <td>Gambar ikon untuk kartu fitur</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul</span></td>
                                    <td>Nama fitur/layanan yang ditawarkan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Deskripsi</span></td>
                                    <td>Penjelasan singkat tentang fitur</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-info">Urutan</span></td>
                                    <td>Posisi tampilan kartu (angka kecil = tampil duluan)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>Kartu ditampilkan di halaman depan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">Nonaktif</span></td>
                                    <td>Kartu tersimpan tapi tidak ditampilkan</td>
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
        const title = this.dataset.title;
        
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Kartu Fitur?',
            html: 'Anda akan menghapus fitur:<br><strong>"' + title + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
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
                window.location.href = '?page=delete_feature&id=' + id + '&confirm=yes';
            }
        });
    });
});
</script>