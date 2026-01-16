<?php
// About Hero Section - Main List
$query = mysqli_query($conn, "SELECT * FROM about_hero ORDER BY id DESC");
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
                        <li class="breadcrumb-item active">Hero Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Hero Section (About)</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Hero
                                About Section?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Hero About Section</strong> adalah banner utama yang terletak di bagian paling
                                atas halaman 'Tentang Kami'.
                                Bagian ini berfungsi untuk memberikan pengantar singkat dan visual mengenai identitas
                                organisasi sebelum masuk ke konten detail.
                                Komponen utamanya meliputi <strong>Badge</strong> (ikon & teks kecil) dan <strong>Judul
                                    Utama</strong>.
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-white rounded shadow-sm">
                                <i class="icofont-hat-alt text-primary" style="font-size: 30px;"></i>
                                <h6 class="mt-2 mb-0">Tentang Kami</h6>
                                <small class="text-muted">Contoh Tampilan Badge</small>
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
                        <h4 class="mt-0 header-title">Daftar Hero About</h4>
                        <a href="?page=add_hero_about" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Baru
                        </a>
                    </div>

                    <?php if (count($rows) > 0): ?>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Badge Icon</th>
                                        <th width="25%">Badge Text</th>
                                        <th width="30%">Judul Utama</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $key => $row): ?>
                                        <tr>
                                            <td>
                                                <?php echo $key + 1; ?>
                                            </td>
                                            <td class="text-center">
                                                <i class="<?php echo htmlspecialchars($row['badge_icon']); ?> text-primary"
                                                    style="font-size: 24px;"></i>
                                                <br>
                                                <small class="text-muted">
                                                    <?php echo htmlspecialchars($row['badge_icon']); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['badge_text']); ?>
                                            </td>
                                            <td><strong>
                                                    <?php echo htmlspecialchars($row['title']); ?>
                                                </strong></td>
                                            <td>
                                                <a href="?page=edit_hero_about&id=<?php echo $row['id']; ?>"
                                                    class="btn btn-sm btn-primary" title="Ubah">
                                                    <i class="mdi mdi-pencil"></i> Ubah
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    data-id="<?php echo $row['id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($row['title']); ?>">
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
                            <h5 class="text-muted">Belum ada data Hero</h5>
                            <a href="?page=add_hero_about" class="btn btn-primary mt-2">Buat Sekarang</a>
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
                            <strong>Menambah Data:</strong><br>
                            <small class="text-muted">Klik tombol hijau "Tambah Baru" untuk membuat konten hero
                                baru.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mengedit Data:</strong><br>
                            <small class="text-muted">Klik tombol biru "Ubah" untuk mengedit konten yang sudah
                                ada.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Menghapus Data:</strong><br>
                            <small class="text-muted">Klik tombol merah "Hapus" untuk membuang data yang tidak
                                diperlukan.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Logika Tampilan:</strong><br>
                            <small class="text-muted">Sistem akan otomatis menampilkan <strong>satu data
                                    terakhir</strong> yang Anda buat atau update. Anda bisa membiarkan data lama sebagai
                                arsip.</small>
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
                            <strong>Ikon Badge:</strong><br>
                            <small class="text-muted">Gunakan kelas ikon dari <strong>Icofont</strong> (misal:
                                <code>icofont-hat-alt</code>). Pilih ikon yang relevan dengan pendidikan atau
                                yayasan.</small>
                        </li>
                        <li class="mb-2">
                            <strong>Badge Text:</strong><br>
                            <small class="text-muted">Gunakan teks yang sangat singkat (1-3 kata) seperti "Tentang
                                Kami", "Profil", atau "Siapa Kami".</small>
                        </li>
                        <li class="mb-2">
                            <strong>Judul Utama:</strong><br>
                            <small class="text-muted">Judul harus kuat dan representatif. Contoh: "Membangun Generasi
                                Cerdas & Berkarakter".</small>
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
                                    <td width="30%"><span class="badge badge-dark">Badge Icon</span></td>
                                    <td>Ikon kecil yang muncul di atas judul (menggunakan Icofont)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Badge Text</span></td>
                                    <td>Label teks singkat yang mendampingi ikon</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Judul Utama</span></td>
                                    <td>Headline utama yang akan ditampilkan besar di banner</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Aksi</span></td>
                                    <td>Tombol untuk mengubah atau menghapus data</td>
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
                html: 'Anda akan menghapus data:<br><strong>"' + title + '"</strong>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?page=delete_hero_about&id=' + id + '&confirm=yes';
                }
            });
        });
    });
</script>