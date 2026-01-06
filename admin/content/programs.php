<?php
// Manajemen Program Kampanye
$query = mysqli_query($conn, "SELECT * FROM campaign_programs ORDER BY order_position ASC, id DESC");
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
                        <li class="breadcrumb-item"><a href="?page=edit_campaign_main">Kampanye</a></li>
                        <li class="breadcrumb-item active">Program</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Program Kampanye</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Program Kampanye?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Program Kampanye</strong> adalah daftar program donasi yang ditampilkan di bagian kampanye pada halaman utama.
                                Setiap program memiliki kategori, judul, target donasi, dan progress donasi. Contoh program: Bantuan Yatim, 
                                Pembangunan Masjid, Sedekah Pangan, dll.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-charity text-success" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Program Donasi</small>
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
                            <h4 class="mt-0 header-title">Daftar Program</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola program donasi yang ditampilkan di bagian kampanye
                            </p>
                        </div>
                        <div>
                            <a href="?page=edit_campaign_main" class="btn btn-info mr-2">
                                <i class="mdi mdi-arrow-left"></i> Kembali ke Kampanye
                            </a>
                            <a href="?page=add_program" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Tambah Program Baru
                            </a>
                        </div>
                    </div>

                    <?php if (count($rows) > 0): ?>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Gambar</th>
                                    <th width="12%">Kategori</th>
                                    <th width="18%">Judul</th>
                                    <th width="20%">Progress Donasi</th>
                                    <th width="8%">Urutan</th>
                                    <th width="10%">Status</th>
                                    <th width="17%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $key => $row): 
                                    $progress = $row['goal_amount'] > 0 ? min(100, ($row['amount_raised'] / $row['goal_amount']) * 100) : 0;
                                ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td>
                                            <?php if (!empty($row['image'])): ?>
                                                <img src="<?php echo '../' . $row['image']; ?>" alt="Program"
                                                    style="max-width: 60px; height: auto; border-radius: 4px;">
                                            <?php else: ?>
                                                <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['category'] ?? '-'); ?></span></td>
                                        <td>
                                            <?php 
                                            $title = htmlspecialchars($row['title'] ?? '');
                                            echo strlen($title) > 40 ? substr($title, 0, 40) . '...' : $title;
                                            ?>
                                        </td>
                                        <td>
                                            <small class="d-block mb-1">
                                                <strong>Rp <?php echo number_format($row['amount_raised'], 0, ',', '.'); ?></strong> 
                                                <span class="text-muted">/ Rp <?php echo number_format($row['goal_amount'], 0, ',', '.'); ?></span>
                                            </small>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-success progress-bar-striped" style="width: <?php echo $progress; ?>%;" title="<?php echo round($progress, 1); ?>%"></div>
                                            </div>
                                            <small class="text-muted"><?php echo round($progress, 1); ?>% tercapai</small>
                                        </td>
                                        <td><span class="badge badge-info"><?php echo $row['order_position'] ?? 0; ?></span></td>
                                        <td>
                                            <?php if ($row['is_active']): ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="?page=edit_program&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Ubah">
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
                        <i class="mdi mdi-charity text-muted" style="font-size: 48px;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Program</h5>
                        <p class="text-muted">Klik "Tambah Program Baru" untuk membuat program donasi pertama Anda.</p>
                        <a href="?page=add_program" class="btn btn-success mt-2">
                            <i class="mdi mdi-plus"></i> Tambah Program Baru
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
                            <strong>Menambah Program Baru:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Program Baru", isi detail program donasi.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengubah Program:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" untuk memperbarui informasi program.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Update Progress:</strong><br>
                            <small class="text-muted">Perbarui jumlah terkumpul secara berkala untuk menunjukkan progress.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Menghapus Program:</strong><br>
                            <small class="text-muted">Klik tombol merah "Hapus" untuk menghapus program.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tips & Contoh -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Contoh Program</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-3">
                        <li class="mb-2">
                            <strong>Kategori:</strong><br>
                            <small class="text-muted">Kelompokkan program seperti: Sosial, Pendidikan, Kesehatan, Infrastruktur</small>
                        </li>
                        <li class="mb-2">
                            <strong>Target Realistis:</strong><br>
                            <small class="text-muted">Tentukan target donasi yang bisa dicapai dalam waktu tertentu.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Gambar Menarik:</strong><br>
                            <small class="text-muted">Gunakan foto yang menggambarkan program dengan baik.</small>
                        </li>
                    </ul>
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-success p-2 m-1">Bantuan Yatim Piatu</span>
                        <span class="badge badge-success p-2 m-1">Pembangunan Masjid</span>
                        <span class="badge badge-success p-2 m-1">Sedekah Pangan</span>
                        <span class="badge badge-success p-2 m-1">Beasiswa Pendidikan</span>
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
                                    <td width="30%"><span class="badge badge-dark">Gambar</span></td>
                                    <td>Foto/ilustrasi program donasi</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Kategori</span></td>
                                    <td>Jenis/kelompok program</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul</span></td>
                                    <td>Nama program donasi</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-success">Progress</span></td>
                                    <td>Persentase donasi tercapai</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-info">Urutan</span></td>
                                    <td>Posisi tampilan (kecil = duluan)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">Status</span></td>
                                    <td>Aktif/Nonaktif ditampilkan</td>
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
            title: 'Hapus Program?',
            html: 'Anda akan menghapus program:<br><strong>"' + title + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
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
                window.location.href = '?page=delete_program&id=' + id + '&confirm=yes';
            }
        });
    });
});
</script>