<?php
// Manajemen Service Section
$query = mysqli_query($conn, "SELECT * FROM service_section ORDER BY order_position ASC, id DESC");
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
                        <li class="breadcrumb-item active">Service Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Layanan</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu
                                Service Section?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Service Section</strong> adalah bagian yang menampilkan berbagai layanan yang
                                ditawarkan oleh masjid/Islamic center.
                                Setiap kartu layanan berisi kategori, judul, deskripsi, dan ikon. Contoh layanan: Kajian
                                Rutin, Konsultasi Syariah,
                                Pendidikan Anak, Zakat & Infaq, dll. Bagian ini membantu pengunjung mengetahui program
                                apa saja yang tersedia.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-hand-heart-outline text-primary" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Kartu Layanan</small>
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
                            <h4 class="mt-0 header-title">Daftar Layanan</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola layanan yang ditampilkan di halaman utama website
                            </p>
                        </div>
                        <div>
                            <a href="?page=edit_service_header" class="btn btn-info mr-2">
                                <i class="mdi mdi-settings"></i> Edit CMS Header
                            </a>
                            <a href="?page=add_service" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Tambah Layanan Baru
                            </a>
                        </div>
                    </div>

                    <?php if (count($rows) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="8%">Ikon</th>
                                        <th width="12%">Kategori</th>
                                        <th width="18%">Judul</th>
                                        <th width="22%">Deskripsi</th>
                                        <th width="8%">Urutan</th>
                                        <th width="10%">Status</th>
                                        <th width="17%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $key => $row): ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td>
                                                <?php if (!empty($row['icon'])): ?>
                                                    <img src="<?php echo '../' . $row['icon']; ?>" alt="Ikon"
                                                        style="max-width: 50px; height: auto; border-radius: 4px;">
                                                <?php else: ?>
                                                    <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-primary"><?php echo htmlspecialchars($row['category'] ?? '-'); ?></span>
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
                                                <a href="?page=edit_service&id=<?php echo $row['id']; ?>"
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
                            <i class="mdi mdi-hand-heart-outline text-muted" style="font-size: 48px;"></i>
                            <h5 class="mt-3 text-muted">Belum Ada Layanan</h5>
                            <p class="text-muted">Klik "Tambah Layanan Baru" untuk membuat layanan pertama Anda.</p>
                            <a href="?page=add_service" class="btn btn-success mt-2">
                                <i class="mdi mdi-plus"></i> Tambah Layanan Baru
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
                            <strong>Menambah Layanan Baru:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Layanan Baru", lalu isi kategori, judul,
                                deskripsi, dan ikon.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengubah Layanan:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" pada baris layanan yang ingin
                                diubah.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Menghapus Layanan:</strong><br>
                            <small class="text-muted">Klik tombol merah "Hapus". Akan muncul konfirmasi sebelum
                                dihapus.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Edit CMS Header:</strong><br>
                            <small class="text-muted">Ubah judul dan deskripsi bagian layanan di header section.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tips & Contoh -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Contoh Layanan</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-3">
                        <li class="mb-2">
                            <strong>Kategori:</strong><br>
                            <small class="text-muted">Kelompokkan layanan seperti: Pendidikan, Sosial, Ibadah,
                                Konsultasi</small>
                        </li>
                        <li class="mb-2">
                            <strong>Judul Singkat:</strong><br>
                            <small class="text-muted">Maksimal 3-5 kata. Contoh: "Kajian Rutin Mingguan"</small>
                        </li>
                        <li class="mb-0">
                            <strong>Deskripsi Informatif:</strong><br>
                            <small class="text-muted">Jelaskan manfaat layanan dengan singkat dan menarik.</small>
                        </li>
                    </ul>
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-primary p-2 m-1">Kajian Rutin</span>
                        <span class="badge badge-primary p-2 m-1">Konsultasi Syariah</span>
                        <span class="badge badge-primary p-2 m-1">Pendidikan Anak</span>
                        <span class="badge badge-primary p-2 m-1">Zakat & Infaq</span>
                        <span class="badge badge-primary p-2 m-1">Bimbingan Pernikahan</span>
                    </div>
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
                                    <td>Gambar ikon untuk kartu layanan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Kategori</span></td>
                                    <td>Kelompok jenis layanan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul</span></td>
                                    <td>Nama layanan yang ditawarkan</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-info">Urutan</span></td>
                                    <td>Posisi tampilan (angka kecil = tampil duluan)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>Layanan ditampilkan di halaman depan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">Nonaktif</span></td>
                                    <td>Layanan tersimpan tapi tidak ditampilkan</td>
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
                title: 'Hapus Layanan?',
                html: 'Anda akan menghapus layanan:<br><strong>"' + title + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
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
                    window.location.href = '?page=delete_service&id=' + id + '&confirm=yes';
                }
            });
        });
    });
</script>