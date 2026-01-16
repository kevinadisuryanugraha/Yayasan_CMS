<?php
// Team Members CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$upload_dir = 'uploads/team/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// 1. Cek & Buat Tabel Database (Auto-Seeding)
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'about_team_section'");
if (mysqli_num_rows($check_table) == 0) {
    // Tabel Section Header
    mysqli_query($conn, "CREATE TABLE `about_team_section` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `subtitle` varchar(255) DEFAULT NULL,
        `title` varchar(255) DEFAULT NULL,
        `description` text,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed Default Section
    mysqli_query($conn, "INSERT INTO about_team_section (id, subtitle, title, description) VALUES (1, 'Tim Kami', 'Pengurus Yayasan', 'Tim profesional dan berdedikasi yang menggerakkan visi dan misi yayasan.')");

    // Tabel Items
    mysqli_query($conn, "CREATE TABLE `about_team_items` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `role` varchar(255) NOT NULL,
        `image` varchar(255) DEFAULT NULL,
        `link_facebook` varchar(255) DEFAULT NULL,
        `link_twitter` varchar(255) DEFAULT NULL,
        `link_linkedin` varchar(255) DEFAULT NULL,
        `sort_order` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed Sample Items (Using placeholders if actual files don't exist yet)
    // Note: We won't seed actual items to avoid broken images, user should add them manually.
}

// Fetch Section Data
$section_query = mysqli_query($conn, "SELECT * FROM about_team_section WHERE id = 1");
$section_data = mysqli_fetch_assoc($section_query);

// Handle Delete Item
if (isset($_GET['delete_item'])) {
    $item_id = intval($_GET['delete_item']);

    // Get Image Path to delete file
    $img_query = mysqli_query($conn, "SELECT image FROM about_team_items WHERE id = $item_id");
    $img_data = mysqli_fetch_assoc($img_query);

    if ($img_data && !empty($img_data['image']) && file_exists($img_data['image'])) {
        unlink($img_data['image']);
    }

    $query = "DELETE FROM about_team_items WHERE id = $item_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Terhapus!', 'message' => 'Anggota tim berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'message' => 'Gagal menghapus item'];
    }
    echo "<script>window.location='?page=team_about';</script>";
    exit;
}

// Fetch Items
$items_query = mysqli_query($conn, "SELECT * FROM about_team_items ORDER BY sort_order ASC, id ASC");
$items = [];
while ($row = mysqli_fetch_assoc($items_query)) {
    $items[] = $row;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">About</a></li>
                        <li class="breadcrumb-item active">Tim (Team)</li>
                    </ol>
                </div>
                <h4 class="page-title">Kelola Tim & Pengurus</h4>
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
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <!-- Kartu Petunjuk (Header) -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Team
                                Section?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Team Section</strong> menampilkan profil singkat pengurus atau tim inti yayasan.
                                Menampilkan wajah-wajah di balik layar meningkatkan kepercayaan (trust) donatur dan
                                masyarakat.
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-white rounded shadow-sm">
                                <i class="icofont-users-social text-primary" style="font-size: 30px;"></i>
                                <h6 class="mt-2 mb-0">Tim Kami</h6>
                                <small class="text-muted">Ilustrasi Section</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Header Info -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-header bg-primary text-white row align-items-center m-0">
                    <div class="col-md-9">
                        <h5 class="m-0"><i class="mdi mdi-page-layout-header"></i> Header Section Data</h5>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="?page=edit_team_section&id=1" class="btn btn-light btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit Header
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Subtitle:</strong><br>
                            <span class="text-muted">
                                <?php echo htmlspecialchars($section_data['subtitle'] ?? '-'); ?>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <strong>Judul Utama:</strong><br>
                            <h3>
                                <?php echo htmlspecialchars($section_data['title'] ?? '-'); ?>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <strong>Deskripsi:</strong><br>
                            <p class="text-muted mb-0">
                                <?php echo htmlspecialchars($section_data['description'] ?? '-'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items List -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mt-0 header-title">Daftar Anggota Tim</h4>
                        <a href="?page=add_team" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Anggota
                        </a>
                    </div>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Foto</th>
                                <th width="20%">Nama Lengkap</th>
                                <th width="15%">Posisi/Jabatan</th>
                                <th width="20%">Sosial Media</th>
                                <th width="10%">Urutan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $index => $item): ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <?php echo $index + 1; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                                            <img src="<?php echo $item['image']; ?>" alt="Foto" class="rounded-circle"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="mdi mdi-account text-secondary"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle"><strong>
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </strong></td>
                                    <td class="align-middle"><span class="badge badge-info">
                                            <?php echo htmlspecialchars($item['role']); ?>
                                        </span></td>
                                    <td class="align-middle">
                                        <div class="btn-group btn-group-sm">
                                            <?php if ($item['link_facebook']): ?>
                                                <a href="<?php echo htmlspecialchars($item['link_facebook']); ?>"
                                                    target="_blank" class="btn btn-secondary text-primary"><i
                                                        class="mdi mdi-facebook"></i></a>
                                            <?php endif; ?>
                                            <?php if ($item['link_twitter']): ?>
                                                <a href="<?php echo htmlspecialchars($item['link_twitter']); ?>" target="_blank"
                                                    class="btn btn-secondary text-info"><i class="mdi mdi-twitter"></i></a>
                                            <?php endif; ?>
                                            <?php if ($item['link_linkedin']): ?>
                                                <a href="<?php echo htmlspecialchars($item['link_linkedin']); ?>"
                                                    target="_blank" class="btn btn-secondary text-primary"><i
                                                        class="mdi mdi-linkedin"></i></a>
                                            <?php endif; ?>
                                            <?php if (!$item['link_facebook'] && !$item['link_twitter'] && !$item['link_linkedin'])
                                                echo '<small class="text-muted">-</small>'; ?>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-light border">
                                            <?php echo $item['sort_order']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="?page=edit_team&id=<?php echo $item['id']; ?>"
                                            class="btn btn-primary btn-sm" title="Ubah">
                                            <i class="mdi mdi-pencil"></i> Ubah
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                                            data-id="<?php echo $item['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($item['name']); ?>" title="Hapus">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Panel Informasi Bawah -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Upload Foto:</strong><br>
                            <small class="text-muted">Gunakan foto rasio 1:1 (persegi) untuk hasil terbaik. Format
                                disarankan: JPG/PNG, maks 2MB.</small>
                        </li>
                        <li class="mb-2"><strong>Link Sosmed:</strong><br>
                            <small class="text-muted">Opsional. Masukkan link lengkap (https://...) jika ada.</small>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips & Praktik Terbaik</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2"><strong>Foto Profesional:</strong><br>
                            <small class="text-muted">Pastikan wajah terlihat jelas dan pencahayaan baik agar terlihat
                                profesional.</small>
                        </li>
                        <li class="mb-2"><strong>Urutan Jabatan:</strong><br>
                            <small class="text-muted">Urutkan dari jabatan tertinggi (Ketua/Pembina) menggunakan fitur
                                'Urutan' (Nomor 1 paling awal).</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Event Delegation for Delete (Vanilla JS)
        document.body.addEventListener('click', function (e) {
            var target = e.target.closest('.btn-delete');

            if (target) {
                var id = target.getAttribute('data-id');
                var title = target.getAttribute('data-title');

                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Data?',
                    html: 'Anda akan menghapus Anggota:<br><strong>"' + title + '"</strong>',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?page=team_about&delete_item=' + id;
                    }
                });
            }
        });
    });
</script>