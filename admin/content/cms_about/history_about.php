<?php
// History Timeline CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

// 1. Cek & Buat Tabel Database jika belum ada (Auto-Seeding)
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'about_history_section'");
if (mysqli_num_rows($check_table) == 0) {
    // Tabel Section Header
    mysqli_query($conn, "CREATE TABLE `about_history_section` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `subtitle` varchar(255) DEFAULT NULL,
        `title` varchar(255) DEFAULT NULL,
        `description` text,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed Default Section
    mysqli_query($conn, "INSERT INTO about_history_section (id, subtitle, title, description) VALUES (1, 'Perjalanan Kami', 'Sejarah Yayasan', 'Perjalanan panjang dalam membangun dan mengembangkan yayasan untuk kemaslahatan umat.')");

    // Tabel Items
    mysqli_query($conn, "CREATE TABLE `about_history_items` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `year` varchar(50) NOT NULL,
        `title` varchar(255) NOT NULL,
        `description` text,
        `sort_order` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed Sample Items
    mysqli_query($conn, "INSERT INTO about_history_items (year, title, description, sort_order) VALUES 
    ('2014', 'Pendirian Yayasan', 'Yayasan Indonesia Bijak Bestari didirikan oleh sekelompok tokoh masyarakat yang memiliki visi untuk memajukan pendidikan.', 1),
    ('2016', 'Program Pendidikan Pertama', 'Meluncurkan program beasiswa pendidikan untuk anak-anak kurang mampu di berbagai daerah Indonesia.', 2),
    ('2019', 'Perluasan Jaringan', 'Membuka cabang di 5 provinsi dan menjalin kerjasama dengan berbagai lembaga pendidikan dan sosial.', 3),
    ('2024', 'Era Digital & Ekspansi', 'Bertransformasi ke era digital dengan platform pembelajaran online dan memperluas jangkauan program.', 4);");
}

// Fetch Section Data
$section_query = mysqli_query($conn, "SELECT * FROM about_history_section WHERE id = 1");
$section_data = mysqli_fetch_assoc($section_query);

// Handle Delete Item
if (isset($_GET['delete_item'])) {
    $item_id = intval($_GET['delete_item']);
    $query = "DELETE FROM about_history_items WHERE id = $item_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Terhapus!', 'message' => 'History item berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'message' => 'Gagal menghapus item'];
    }
    echo "<script>window.location='?page=history_about';</script>";
    exit;
}

// Fetch Items
$items_query = mysqli_query($conn, "SELECT * FROM about_history_items ORDER BY sort_order ASC, year ASC");
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
                        <li class="breadcrumb-item active">Sejarah (History)</li>
                    </ol>
                </div>
                <h4 class="page-title">Kelola Sejarah Yayasan</h4>
            </div>
        </div>
    </div>

    <!-- Alert System (Standardized) -->
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

    <!-- Kartu Petunjuk (Header) - Standardized -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu
                                History Timeline?</h5>
                            <p class="mb-0 text-muted">
                                <strong>History Timeline</strong> menampilkan jejak langkah penting organisasi Anda dari
                                waktu ke waktu.
                                Fitur ini sangat baik untuk membangun kredibilitas dengan menunjukkan pengalaman dan
                                pertumbuhan yayasan.
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-white rounded shadow-sm">
                                <i class="icofont-history text-primary" style="font-size: 30px;"></i>
                                <h6 class="mt-2 mb-0">Timeline</h6>
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
                        <a href="?page=edit_history_section&id=1" class="btn btn-light btn-sm">
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
                        <h4 class="mt-0 header-title">Daftar Event Sejarah</h4>
                        <a href="?page=add_history" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Event
                        </a>
                    </div>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tahun</th>
                                <th width="25%">Judul Event</th>
                                <th width="35%">Deskripsi</th>
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
                                        <h5 class="text-primary m-0">
                                            <?php echo htmlspecialchars($item['year']); ?>
                                        </h5>
                                    </td>
                                    <td class="align-middle"><strong>
                                            <?php echo htmlspecialchars($item['title']); ?>
                                        </strong></td>
                                    <td class="align-middle">
                                        <?php
                                        $desc = htmlspecialchars($item['description']);
                                        echo (strlen($desc) > 80) ? substr($desc, 0, 80) . '...' : $desc;
                                        ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-secondary p-2">
                                            urutan:
                                            <?php echo $item['sort_order']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="?page=edit_history&id=<?php echo $item['id']; ?>"
                                            class="btn btn-primary btn-sm" title="Ubah">
                                            <i class="mdi mdi-pencil"></i> Ubah
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                                            data-id="<?php echo $item['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($item['title']); ?>" title="Hapus">
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

    <!-- Panel Informasi Bawah - Standardized -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Tambah Event:</strong><br>
                            <small class="text-muted">Klik "Tambah Event" untuk memasukkan milestone baru (Tahun &
                                Kejadian).</small>
                        </li>
                        <li class="mb-2"><strong>Urutan Kronologis:</strong><br>
                            <small class="text-muted">Secara default, data akan diurutkan berdasarkan kolom 'Urutan'
                                lalu 'Tahun'.</small>
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
                        <li class="mb-2"><strong>Jarak Tahun:</strong><br>
                            <small class="text-muted">Pilih momen-momen paling signifikan saja, jangan masukkan setiap
                                tahun agar timeline tidak terlalu padat.</small>
                        </li>
                        <li class="mb-2"><strong>Kalimat Singkat:</strong><br>
                            <small class="text-muted">Gunakan kalimat yang padat dan inspiratif pada deskripsi
                                event.</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Kolom Tabel (Standardized) -->
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
                                    <td width="30%"><span class="badge badge-dark">Tahun</span></td>
                                    <td>Tahun terjadinya peristiwa (Contoh: 2014).</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul Event</span></td>
                                    <td>Nama tonggak sejarah (Contoh: Pendirian Yayasan).</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Deskripsi</span></td>
                                    <td>Penjelasan singkat mengenai peristiwa tersebut.</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Aksi</span></td>
                                    <td>Tombol untuk mengubah atau menghapus data.</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Event Delegation for Delete (Vanilla JS for max compatibility)
        document.body.addEventListener('click', function (e) {
            // Check if clicked element is .btn-delete or inside it
            var target = e.target.closest('.btn-delete');

            if (target) {
                var id = target.getAttribute('data-id');
                var title = target.getAttribute('data-title');

                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Data?',
                    html: 'Anda akan menghapus History:<br><strong>"' + title + '"</strong>',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?page=history_about&delete_item=' + id;
                    }
                });
            }
        });
    });
</script>