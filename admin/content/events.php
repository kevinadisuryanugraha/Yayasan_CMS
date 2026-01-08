<?php
// Manajemen Acara/Event
$query = mysqli_query($conn, "SELECT * FROM events ORDER BY order_position ASC, event_date DESC");
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
                        <li class="breadcrumb-item active">Acara</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Acara</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Acara?
                            </h5>
                            <p class="mb-0 text-muted">
                                <strong>Acara (Event)</strong> adalah kegiatan atau event yang akan datang yang ingin
                                Anda tampilkan
                                di halaman utama website. Pengunjung dapat melihat informasi tanggal, waktu, lokasi, dan
                                deskripsi acara.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-calendar-star text-primary" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Kelola Acara Mendatang</small>
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
                            <h4 class="mt-0 header-title">Daftar Acara</h4>
                            <p class="text-muted m-b-30 font-14">
                                Kelola acara yang ditampilkan di halaman utama
                            </p>
                        </div>
                        <div>
                            <a href="?page=edit_events_header" class="btn btn-info mr-2">
                                <i class="mdi mdi-settings"></i> Edit Header
                            </a>
                            <a href="?page=add_event" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Tambah Acara Baru
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
                                        <th width="8%">Gambar</th>
                                        <th width="20%">Judul Acara</th>
                                        <th width="12%">Tanggal</th>
                                        <th width="8%">Waktu</th>
                                        <th width="15%">Lokasi</th>
                                        <th width="8%">Unggulan</th>
                                        <th width="8%">Status</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $key => $row): ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td>
                                                <?php if (!empty($row['image'])): ?>
                                                    <img src="<?php echo '../' . $row['image']; ?>" alt="Event"
                                                        style="max-width: 50px; height: auto; border-radius: 4px;">
                                                <?php else: ?>
                                                    <span class="text-muted"><i class="mdi mdi-image-off"></i> Tidak Ada</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $title = htmlspecialchars($row['title'] ?? '');
                                                echo strlen($title) > 40 ? substr($title, 0, 40) . '...' : $title;
                                                ?>
                                            </td>
                                            <td>
                                                <i class="mdi mdi-calendar text-primary"></i>
                                                <?php echo date('d M Y', strtotime($row['event_date'])); ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($row['event_time'])): ?>
                                                    <i class="mdi mdi-clock text-info"></i>
                                                    <?php echo date('H:i', strtotime($row['event_time'])); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><small><?php echo htmlspecialchars($row['location'] ?? '-'); ?></small></td>
                                            <td>
                                                <?php if ($row['is_featured']): ?>
                                                    <span class="badge badge-warning"><i class="mdi mdi-star"></i> Ya</span>
                                                <?php else: ?>
                                                    <span class="badge badge-light">Tidak</span>
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
                                                <a href="?page=edit_event&id=<?php echo $row['id']; ?>"
                                                    class="btn btn-sm btn-primary" title="Ubah">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus"
                                                    data-id="<?php echo $row['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($row['title'] ?? ''); ?>">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="mdi mdi-calendar-blank text-muted" style="font-size: 48px;"></i>
                            <h5 class="mt-3 text-muted">Belum Ada Acara</h5>
                            <p class="text-muted">Klik "Tambah Acara Baru" untuk membuat acara pertama Anda.</p>
                            <a href="?page=add_event" class="btn btn-success mt-2">
                                <i class="mdi mdi-plus"></i> Tambah Acara Baru
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
        <div class="col-lg-4">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li class="mb-2">
                            <strong>Tambah Acara:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Acara Baru".</small>
                        </li>
                        <li class="mb-2">
                            <strong>Ubah Acara:</strong><br>
                            <small class="text-muted">Klik tombol biru edit untuk memperbarui.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Unggulan:</strong><br>
                            <small class="text-muted">Tandai acara penting sebagai unggulan.</small>
                        </li>
                        <li class="mb-0">
                            <strong>Edit Header:</strong><br>
                            <small class="text-muted">Ubah judul section acara.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Penjelasan Kolom -->
        <div class="col-lg-4">
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-table mr-2"></i>Penjelasan Kolom</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless small mb-0">
                        <tr>
                            <td><span class="badge badge-dark">Judul</span></td>
                            <td>Nama acara/event</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-primary">Tanggal</span></td>
                            <td>Kapan acara berlangsung</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-info">Waktu</span></td>
                            <td>Jam mulai acara</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-warning">Unggulan</span></td>
                            <td>Acara yang ditonjolkan</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contoh Acara -->
        <div class="col-lg-4">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Acara</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-light p-2 m-1 border">Pengajian Akbar</span>
                        <span class="badge badge-light p-2 m-1 border">Buka Puasa Bersama</span>
                        <span class="badge badge-light p-2 m-1 border">Shalat Idul Fitri</span>
                        <span class="badge badge-light p-2 m-1 border">Kajian Rutin</span>
                        <span class="badge badge-light p-2 m-1 border">Santunan Anak Yatim</span>
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
                title: 'Hapus Acara?',
                html: 'Anda akan menghapus acara:<br><strong>"' + title + '"</strong><br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
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
                    window.location.href = '?page=delete_event&id=' + id + '&confirm=yes';
                }
            });
        });
    });
</script>