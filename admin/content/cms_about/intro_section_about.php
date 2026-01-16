<?php
// About Intro Section - Main List
$query = mysqli_query($conn, "SELECT * FROM about_section ORDER BY id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Alert handling
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
    <!-- Page Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Konten About</a></li>
                        <li class="breadcrumb-item active">Intro & Statistik</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Intro & Statistik</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Petunjuk -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Intro
                                & Statistik?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Intro & Statistik</strong> adalah bagian setelah Hero Banner yang menampilkan
                                informasi pengantar
                                dan kartu statistik (pencapaian/angka penting). Bagian ini berfungsi untuk memberikan
                                konteks lebih dalam
                                tentang organisasi serta bukti nyata melalui angka (misal: jumlah siswa, tahun
                                pengalaman).
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-white rounded shadow-sm">
                                <i class="icofont-chart-bar-graph text-success" style="font-size: 30px;"></i>
                                <h6 class="mt-2 mb-0">Statistik</h6>
                                <small class="text-muted">Info & Angka Penting</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Table -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mt-0 header-title">Daftar Konten Intro</h4>
                        <a href="?page=add_intro_about" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Baru
                        </a>
                    </div>

                    <p class="text-muted m-b-30 font-14">
                        Data di bawah ini yang berstatus <strong>Aktif</strong> akan ditampilkan di halaman depan.
                    </p>

                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Gambar</th>
                                    <th width="30%">Judul & Deskripsi</th>
                                    <th width="20%">Statistik</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Terakhir Update</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($rows) > 0): ?>
                                    <?php foreach ($rows as $key => $row): ?>
                                        <tr>
                                            <td class="text-center align-middle"><?php echo $key + 1; ?></td>
                                            <td class="text-center align-middle">
                                                <?php if (!empty($row['image']) && file_exists('../' . $row['image'])): ?>
                                                    <img src="../<?php echo $row['image']; ?>" alt="Intro Img" class="img-thumbnail"
                                                        style="height: 80px; width: auto; max-width: 120px; object-fit: cover;">
                                                <?php else: ?>
                                                    <span class="text-muted small"><i class="mdi mdi-image-off"></i> No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <h6 class="mt-0 mb-1 font-weight-bold text-primary">
                                                    <?php echo htmlspecialchars($row['title']); ?>
                                                </h6>
                                                <?php if ($row['subtitle']): ?>
                                                    <span class="d-block text-muted small mb-1">
                                                        <i class="mdi mdi-subtitles mr-1"></i>
                                                        <?php echo htmlspecialchars($row['subtitle']); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <p class="mb-0 text-muted small text-truncate" style="max-width: 250px;">
                                                    <?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?>
                                                </p>
                                            </td>
                                            <td class="align-middle">
                                                <?php if (!empty($row['stat_number'])): ?>
                                                    <div class="media">
                                                        <div class="mr-3 align-self-center">
                                                            <i
                                                                class="<?php echo htmlspecialchars($row['stat_icon'] ?? 'icofont-calendar'); ?> text-success font-24"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <h5 class="mt-0 mb-0 font-16">
                                                                <?php echo htmlspecialchars($row['stat_number']); ?></h5>
                                                            <small
                                                                class="text-muted"><?php echo htmlspecialchars($row['stat_text']); ?></small>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small font-italic">- Tidak ada statistik -</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($row['is_active'] == 1): ?>
                                                    <span class="badge badge-success px-2 py-1">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary px-2 py-1">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <small class="text-muted">
                                                    <?php echo date('d M Y', strtotime($row['updated_at'] ?? $row['created_at'])); ?>
                                                    <br>
                                                    <?php echo date('H:i', strtotime($row['updated_at'] ?? $row['created_at'])); ?>
                                                </small>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group" role="group">
                                                    <a href="?page=edit_intro_about&id=<?php echo $row['id']; ?>"
                                                        class="btn btn-primary btn-sm" title="Ubah">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                        data-id="<?php echo $row['id']; ?>"
                                                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                                        title="Hapus">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
                            <strong>Menambah Data:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Baru" untuk menginput konten intro dan
                                statistik.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengedit Data:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" (ikon pensil) untuk mengupdate konten yang
                                ada.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Menghapus Data:</strong><br>
                            <small class="text-muted">Klik tombol merah "Hapus" (ikon sampah) untuk menghapus
                                data.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Status Aktif:</strong><br>
                            <small class="text-muted">Pastikan status diset ke <strong>Aktif</strong> agar konten tampil
                                di website.</small>
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
                            <strong>Visulisasi:</strong><br>
                            <small class="text-muted">Gunakan gambar yang jernih (rekomendasi > 800px lebar). Mode
                                landscape lebih disukai.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Format Statistik:</strong><br>
                            <small class="text-muted">Gunakan angka singkat (misal: "150+", "10 Th") dan pilih ikon yang
                                relevan.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Kepadatan Teks:</strong><br>
                            <small class="text-muted">Hindari deskripsi yang terlalu panjang agar tampilan tetap estetis
                                di perangkat mobile.</small>
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
                                    <td>Thumbnail gambar yang diupload.</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul & Deskripsi</span></td>
                                    <td>Informasi utama teks intro.</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Statistik</span></td>
                                    <td>Preview tampilan ikon, angka, dan teks statistik.</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-primary">Status</span></td>
                                    <td>Indikator apakah data ini sedang ditampilkan (Aktif) atau disembunyikan.</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Aksi</span></td>
                                    <td>Tombol kontrol untuk mengubah atau menghapus data.</td>
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
    // Delete Confirmation
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const title = this.dataset.title;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Data?',
                html: 'Anda akan menghapus data intro:<br><strong>"' + title + '"</strong><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?page=delete_intro_about&id=' + id;
                }
            });
        });
    });
</script>