<?php
// Manajemen About Section
$query = mysqli_query($conn, "SELECT * FROM about_section ORDER BY id DESC");
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
                        <li class="breadcrumb-item active">About Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen About Section</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu About Section?</h5>
                            <p class="mb-0 text-muted">
                                <strong>About Section</strong> adalah bagian yang menampilkan informasi tentang organisasi/lembaga Anda di halaman utama website. 
                                Bagian ini biasanya berisi gambar, judul, sub judul, dan deskripsi singkat yang menjelaskan siapa Anda, apa visi misi, 
                                dan mengapa pengunjung harus mengenal lebih jauh tentang organisasi Anda.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-information-outline text-primary" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Bagian "Tentang Kami"</small>
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
                            <h4 class="mt-0 header-title">Daftar Konten About</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola konten about section yang ditampilkan di halaman utama website
                            </p>
                        </div>
                        <a href="?page=add_about" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah About Baru
                        </a>
                    </div>

                    <?php if (count($rows) > 0): ?>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Gambar</th>
                                    <th width="15%">Sub Judul</th>
                                    <th width="20%">Judul</th>
                                    <th width="20%">Sub-Heading</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $key => $row): ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td>
                                            <?php if (!empty($row['image'])): ?>
                                                <img src="<?php echo '../' . $row['image']; ?>" alt="Gambar About"
                                                    style="max-width: 80px; height: auto; border-radius: 4px;">
                                            <?php else: ?>
                                                <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada Gambar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['subtitle'] ?? ''); ?></td>
                                        <td>
                                            <?php 
                                            $title = htmlspecialchars($row['title'] ?? '');
                                            echo strlen($title) > 50 ? substr($title, 0, 50) . '...' : $title;
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $subheading = htmlspecialchars($row['sub_heading'] ?? '');
                                            echo strlen($subheading) > 40 ? substr($subheading, 0, 40) . '...' : $subheading;
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($row['is_active']): ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="?page=edit_about&id=<?php echo $row['id']; ?>"
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
                        <i class="mdi mdi-information-outline text-muted" style="font-size: 48px;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Konten About</h5>
                        <p class="text-muted">Klik "Tambah About Baru" untuk membuat konten about section pertama Anda.</p>
                        <a href="?page=add_about" class="btn btn-success mt-2">
                            <i class="mdi mdi-plus"></i> Tambah About Baru
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
                            <strong>Menambah About Baru:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah About Baru", lalu isi formulir dengan gambar, judul, deskripsi, dan informasi lainnya.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengubah About:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" pada baris konten yang ingin diubah.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Menghapus About:</strong><br>
                            <small class="text-muted">Klik tombol merah "Hapus". Akan muncul konfirmasi sebelum dihapus.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Status Aktif/Nonaktif:</strong><br>
                            <small class="text-muted">Hanya konten dengan status "Aktif" yang ditampilkan di halaman depan website.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tips & Praktik Terbaik -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Praktik Terbaik</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">
                            <strong>Gambar Berkualitas:</strong><br>
                            <small class="text-muted">Gunakan gambar yang relevan dan berkualitas tinggi untuk menarik perhatian pengunjung.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Judul yang Menarik:</strong><br>
                            <small class="text-muted">Buat judul yang singkat, jelas, dan menggambarkan organisasi Anda.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Deskripsi Informatif:</strong><br>
                            <small class="text-muted">Jelaskan visi, misi, dan keunggulan organisasi secara singkat dan padat.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Satu Konten Aktif:</strong><br>
                            <small class="text-muted">Disarankan hanya mengaktifkan satu konten about untuk tampilan yang konsisten.</small>
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
                                    <td width="30%"><span class="badge badge-dark">Gambar</span></td>
                                    <td>Gambar ilustrasi untuk about section</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Sub Judul</span></td>
                                    <td>Teks kecil di atas judul utama (kategori/label)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul</span></td>
                                    <td>Judul utama section "Tentang Kami"</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Sub-Heading</span></td>
                                    <td>Tagline atau kalimat pendukung judul</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>Konten ditampilkan di halaman depan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">Nonaktif</span></td>
                                    <td>Konten tersimpan tapi tidak ditampilkan</td>
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
            title: 'Hapus Konten About?',
            html: 'Anda akan menghapus about section:<br><strong>"' + title + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
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
                window.location.href = '?page=delete_about&id=' + id + '&confirm=yes';
            }
        });
    });
});
</script>