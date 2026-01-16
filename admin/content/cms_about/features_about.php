<?php
// Cek halaman
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

// Cek apakah tabel about_features ada
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'about_features'");
if (mysqli_num_rows($check_table) == 0) {
    // Buat tabel jika belum ada
    $create_table = "CREATE TABLE `about_features` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `sort_order` int(11) DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($conn, $create_table);
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM about_features WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Data fitur berhasil dihapus'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'message' => 'Gagal menghapus data: ' . mysqli_error($conn)];
    }
    echo "<script>window.location.href='?page=features_about';</script>";
    exit;
}

// Ambil data features
$query = "SELECT * FROM about_features ORDER BY sort_order ASC, id ASC";
$result = mysqli_query($conn, $query);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">Intro About</a></li>
                        <li class="breadcrumb-item active">Fitur Keunggulan</li>
                    </ol>
                </div>
                <h4 class="page-title">Kelola Fitur Keunggulan</h4>
            </div>
        </div>
    </div>

    <!-- Alert System -->
    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?php echo $_SESSION['alert']['type']; ?>',
                    title: '<?php echo $_SESSION['alert']['title']; ?>',
                    text: '<?php echo $_SESSION['alert']['message']; ?>',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-lg-8">
                                            <h4 class="mt-0 header-title">Daftar Fitur Keunggulan</h4>
                                            <p class="text-muted m-b-30 font-14">
                                                Kelola daftar poin-poin keunggulan yang akan ditampilkan di halaman
                                                About Us.
                                            </p>
                                        </div>
                                        <div class="col-lg-4 text-right">
                                            <a href="?page=add_feature_about" class="btn btn-primary btn-lg">
                                                <i class="mdi mdi-plus-circle-outline"></i> Tambah Fitur Baru
                                            </a>
                                        </div>
                                    </div>
                                    <div class="alert alert-info border-0" role="alert">
                                        <strong><i class="mdi mdi-information-outline"></i> Penjelasan Kolom
                                            Tabel:</strong>
                                        <ul class="mb-0">
                                            <li><strong>No:</strong> Nomor urut fitur.</li>
                                            <li><strong>Ikon:</strong> Ikon yang akan ditampilkan di samping teks
                                                keunggulan.</li>
                                            <li><strong>Teks Keunggulan:</strong> Deskripsi singkat poin keunggulan.
                                            </li>
                                            <li><strong>Urutan:</strong> Angka untuk menentukan posisi fitur (semakin
                                                kecil, semakin
                                                atas).</li>
                                            <li><strong>Aksi:</strong> Tombol untuk mengubah atau menghapus fitur.</li>
                                        </ul>
                                    </div>

                                    <table id="datatable-buttons"
                                        class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th width="10%">No</th>
                                                <th width="15%">Ikon</th>
                                                <th width="50%">Teks Keunggulan</th>
                                                <th width="10%">Urutan</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($rows) > 0): ?>
                                                <?php foreach ($rows as $key => $row): ?>
                                                    <tr>
                                                        <td class="text-center align-middle">
                                                            <?php echo $key + 1; ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <i
                                                                class="<?php echo htmlspecialchars($row['icon']); ?> font-30 text-primary"></i>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?php echo htmlspecialchars($row['icon']); ?>
                                                            </small>
                                                        </td>
                                                        <td class="align-middle">
                                                            <strong>
                                                                <?php echo htmlspecialchars($row['text']); ?>
                                                            </strong>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge badge-light border">
                                                                <?php echo $row['sort_order']; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <div class="btn-group" role="group">
                                                                <a href="?page=edit_feature_about&id=<?php echo $row['id']; ?>"
                                                                    class="btn btn-primary btn-sm" title="Ubah">
                                                                    <i class="mdi mdi-pencil"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                                    data-id="<?php echo $row['id']; ?>"
                                                                    data-title="<?php echo htmlspecialchars($row['text']); ?>">
                                                                    <i class="mdi mdi-delete"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Belum ada data fitur.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Petunjuk -->
                    <div class="row">
                        <!-- Cara Penggunaan -->
                        <div class="col-lg-6">
                            <div class="card m-b-30 border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ol class="mb-0">
                                        <li class="mb-2">
                                            <strong>Menambah Fitur:</strong><br>
                                            <small class="text-muted">Klik tombol biru "Tambah Fitur Baru" untuk
                                                menambahkan poin
                                                keunggulan baru.</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Mengedit Fitur:</strong><br>
                                            <small class="text-muted">Klik tombol biru "Ubah" (ikon pensil) di kolom
                                                aksi untuk
                                                mengubah teks atau ikon fitur.</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Menghapus Fitur:</strong><br>
                                            <small class="text-muted">Klik tombol merah (ikon sampah) untuk menghapus
                                                fitur yang tidak
                                                diinginkan.</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Urutan Tampilan:</strong><br>
                                            <small class="text-muted">Fitur akan diurutkan berdasarkan angka "Urutan"
                                                dari terkecil ke
                                                terbesar.</small>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Tips & Praktik Terbaik -->
                        <div class="col-lg-6">
                            <div class="card m-b-30 border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Praktik Terbaik
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li class="mb-2">
                                            <strong>Pemilihan Ikon:</strong><br>
                                            <small class="text-muted">Gunakan ikon yang relevan dengan teks untuk
                                                membantu visualisasi
                                                cepat bagi pengunjung.</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Teks Singkat:</strong><br>
                                            <small class="text-muted">Usahakan teks hanya 2-4 kata agar daftar terlihat
                                                rapi dan mudah
                                                dibaca sekilas.</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Jumlah Poin:</strong><br>
                                            <small class="text-muted">Disarankan menampilkan 3-6 poin keunggulan agar
                                                tidak memenuhi
                                                tampilan.</small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Delete Confirmation
                        const deleteButtons = document.querySelectorAll('.btn-delete');
                        deleteButtons.forEach(button => {
                            button.addEventListener('click', function () {
                                const id = this.getAttribute('data-id');
                                const title = this.getAttribute('data-title');

                                Swal.fire({
                                    title: 'Hapus Fitur?',
                                    text: `Anda yakin ingin menghapus fitur "${title}"?`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc3545',
                                    cancelButtonColor: '#6c757d',
                                    confirmButtonText: 'Ya, Hapus!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = `?page=features_about&action=delete&id=${id}`;
                                    }
                                });
                            });
                        });
                    });
                </script>