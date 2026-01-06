<?php
// Manajemen Pilar Keimanan
$query = mysqli_query($conn, "SELECT * FROM faith_pillars ORDER BY order_position ASC, id ASC");
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
                        <li class="breadcrumb-item active">Pilar Keimanan</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Pilar Keimanan</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Pilar Keimanan?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Pilar Keimanan</strong> adalah section yang menampilkan tab-tab pilar ibadah seperti 
                                <strong>Al-Quran, Shalat, Puasa, Zakat, dan Haji</strong>. Setiap pilar memiliki ikon, gambar utama, 
                                dan deskripsi yang ditampilkan dalam format tab interaktif di halaman depan.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-book-open-page-variant text-success" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Pilar Ibadah</small>
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
                            <h4 class="mt-0 header-title">Daftar Pilar</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola pilar-pilar keimanan yang ditampilkan dalam tab
                            </p>
                        </div>
                        <div>
                            <a href="?page=edit_faith_header" class="btn btn-info mr-2">
                                <i class="mdi mdi-settings"></i> Edit Header
                            </a>
                            <a href="?page=add_faith_pillar" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Tambah Pilar Baru
                            </a>
                        </div>
                    </div>

                    <?php if (count($rows) > 0): ?>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="8%">Ikon Tab</th>
                                    <th width="10%">Gambar</th>
                                    <th width="15%">Nama Pilar</th>
                                    <th width="12%">Subjudul</th>
                                    <th width="22%">Deskripsi</th>
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
                                            <?php if (!empty($row['tab_icon'])): ?>
                                                <img src="<?php echo '../' . $row['tab_icon']; ?>" alt="Ikon"
                                                    style="max-width: 40px; height: auto;">
                                            <?php else: ?>
                                                <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['main_image'])): ?>
                                                <img src="<?php echo '../' . $row['main_image']; ?>" alt="Gambar"
                                                    style="max-width: 60px; height: auto; border-radius: 4px;">
                                            <?php else: ?>
                                                <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['pillar_name'] ?? ''); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['subtitle'] ?? '-'); ?></td>
                                        <td>
                                            <?php 
                                            $desc = htmlspecialchars($row['description'] ?? '');
                                            echo strlen($desc) > 60 ? substr($desc, 0, 60) . '...' : $desc;
                                            ?>
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
                                            <a href="?page=edit_faith_pillar&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Ubah">
                                                <i class="mdi mdi-pencil"></i> Ubah
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus"
                                                data-id="<?php echo $row['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['pillar_name'] ?? 'Tanpa Nama'); ?>">
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
                        <i class="mdi mdi-book-open-page-variant text-muted" style="font-size: 48px;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Pilar</h5>
                        <p class="text-muted">Klik "Tambah Pilar Baru" untuk membuat pilar keimanan pertama Anda.</p>
                        <a href="?page=add_faith_pillar" class="btn btn-success mt-2">
                            <i class="mdi mdi-plus"></i> Tambah Pilar Baru
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
                            <strong>Menambah Pilar Baru:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Pilar Baru".</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengubah Pilar:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" untuk memperbarui informasi.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengatur Urutan:</strong><br>
                            <small class="text-muted">Angka urutan menentukan posisi tab di halaman depan.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Edit Header:</strong><br>
                            <small class="text-muted">Klik "Edit Header" untuk mengubah judul section.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tips & Contoh -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Pilar Keimanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap mb-3">
                        <span class="badge badge-success p-2 m-1"><i class="mdi mdi-book-open mr-1"></i>Al-Quran</span>
                        <span class="badge badge-success p-2 m-1"><i class="mdi mdi-account-multiple mr-1"></i>Shalat</span>
                        <span class="badge badge-success p-2 m-1"><i class="mdi mdi-food-off mr-1"></i>Puasa</span>
                        <span class="badge badge-success p-2 m-1"><i class="mdi mdi-hand-heart mr-1"></i>Zakat</span>
                        <span class="badge badge-success p-2 m-1"><i class="mdi mdi-mosque mr-1"></i>Haji</span>
                    </div>
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ikon Tab:</strong> Gambar kecil untuk tombol tab</li>
                        <li class="mb-0"><strong>Gambar Utama:</strong> Ditampilkan saat tab aktif</li>
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
                                    <td width="30%"><span class="badge badge-dark">Ikon Tab</span></td>
                                    <td>Gambar kecil untuk tombol tab</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Gambar</span></td>
                                    <td>Gambar utama saat tab aktif</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Nama Pilar</span></td>
                                    <td>Judul pilar keimanan</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Subjudul</span></td>
                                    <td>Keterangan singkat pilar</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-info">Urutan</span></td>
                                    <td>Posisi tab (kecil = duluan)</td>
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
        const name = this.dataset.name;
        
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Pilar?',
            html: 'Anda akan menghapus pilar:<br><strong>"' + name + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
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
                window.location.href = '?page=delete_faith_pillar&id=' + id + '&confirm=yes';
            }
        });
    });
});
</script>