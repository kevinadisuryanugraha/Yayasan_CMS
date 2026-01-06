<?php
// Manajemen Hero Section
$query = mysqli_query($conn, "SELECT * FROM hero_section ORDER BY id DESC");
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
                timer: 1500
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
                        <li class="breadcrumb-item active">Hero Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Hero Section</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Hero
                                Section?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Hero Section</strong> adalah bagian banner besar yang muncul pertama kali saat
                                pengunjung membuka website Anda.
                                Bagian ini sangat penting karena menjadi kesan pertama bagi pengunjung. Di sini Anda
                                bisa menampilkan gambar menarik,
                                judul utama, deskripsi singkat, dan tombol aksi (seperti "Donasi Sekarang" atau
                                "Pelajari Lebih Lanjut").
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <img src="../assets/images/banner/01.png" alt="Contoh Hero"
                                class="img-fluid rounded shadow-sm" style="max-height: 100px;">
                            <small class="d-block text-muted mt-2">Contoh tampilan Hero</small>
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
                            <h4 class="mt-0 header-title">Daftar Konten Hero</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola semua banner hero yang akan ditampilkan di halaman utama website
                            </p>
                        </div>
                        <a href="?page=add_hero" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Hero Baru
                        </a>
                    </div>

                    <?php if (count($rows) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Gambar</th>
                                        <th width="20%">Judul</th>
                                        <th width="15%">Sub Judul</th>
                                        <th width="15%">Tombol</th>
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
                                                    <img src="<?php echo '../' . $row['image']; ?>" alt="Gambar Hero"
                                                        style="max-width: 100px; height: auto; border-radius: 4px;">
                                                <?php else: ?>
                                                    <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada
                                                        Gambar</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['title'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($row['subtitle'] ?? ''); ?></td>
                                            <td>
                                                <?php if (!empty($row['button_text'])): ?>
                                                    <span
                                                        class="badge badge-info"><?php echo htmlspecialchars($row['button_text']); ?></span>
                                                    <br><small
                                                        class="text-muted"><?php echo htmlspecialchars($row['button_link'] ?? ''); ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['is_active']): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="?page=edit_hero&id=<?php echo $row['id']; ?>"
                                                    class="btn btn-sm btn-primary" title="Ubah">
                                                    <i class="mdi mdi-pencil"></i> Ubah
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus"
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
                            <i class="mdi mdi-image-area text-muted" style="font-size: 48px;"></i>
                            <h5 class="mt-3 text-muted">Belum Ada Konten Hero</h5>
                            <p class="text-muted">Klik "Tambah Hero Baru" untuk membuat banner hero pertama Anda.</p>
                            <a href="?page=add_hero" class="btn btn-success mt-2">
                                <i class="mdi mdi-plus"></i> Tambah Hero Baru
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
                            <strong>Menambah Hero Baru:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Hero Baru" di atas, lalu isi formulir
                                dengan gambar, judul, dan informasi lainnya.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengubah Hero:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" pada baris hero yang ingin diubah.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Menghapus Hero:</strong><br>
                            <small class="text-muted">Klik tombol merah "Hapus" untuk menghapus hero. Akan muncul
                                konfirmasi sebelum dihapus.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengaktifkan/Menonaktifkan:</strong><br>
                            <small class="text-muted">Ubah status "Aktif" atau "Nonaktif" melalui halaman edit. Hanya
                                hero "Aktif" yang tampil di website.</small>
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
                            <strong>Ukuran Gambar:</strong><br>
                            <small class="text-muted">Gunakan gambar dengan ukuran minimal <strong>1920 x 600
                                    piksel</strong> agar terlihat jelas di layar besar.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Format Gambar:</strong><br>
                            <small class="text-muted">Gunakan format JPG atau PNG. Usahakan ukuran file di bawah 2MB
                                agar loading cepat.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Judul yang Menarik:</strong><br>
                            <small class="text-muted">Buat judul singkat, jelas, dan menarik perhatian. Maksimal 5-8
                                kata.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Tombol Aksi:</strong><br>
                            <small class="text-muted">Gunakan teks tombol yang jelas seperti "Donasi Sekarang", "Daftar
                                Event", atau "Pelajari Lebih Lanjut".</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjelasan Kolom Tabel -->
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
                                    <td>Gambar banner yang akan ditampilkan di hero section</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul</span></td>
                                    <td>Teks utama/headline yang besar dan mencolok</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Sub Judul</span></td>
                                    <td>Teks pendukung di bawah judul utama</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Tombol</span></td>
                                    <td>Teks dan link untuk tombol aksi (CTA)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>Hero ditampilkan di halaman depan website</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">Nonaktif</span></td>
                                    <td>Hero disimpan tapi tidak ditampilkan</td>
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
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const title = this.dataset.title;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Konten Hero?',
                html: 'Anda akan menghapus hero:<br><strong>"' + title + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
                showCancelButton: true,
                confirmButtonText: '<i class="mdi mdi-delete"></i> Ya, Hapus!',
                cancelButtonText: '<i class="mdi mdi-close"></i> Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menghapus...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    // Redirect ke halaman delete dengan konfirmasi
                    window.location.href = '?page=delete_hero&id=' + id + '&confirm=yes';
                }
            });
        });
    });
</script>