<?php
// Vision & Mission CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

// Fetch Section Data
$section_query = mysqli_query($conn, "SELECT * FROM about_vision_mission_section WHERE id = 1");
$section_data = mysqli_fetch_assoc($section_query);

// Handle if no section data exists (should not happen due to seed)
if (!$section_data) {
    // Insert default if missing
    mysqli_query($conn, "INSERT INTO about_vision_mission_section (id, subtitle, title, description) VALUES (1, 'Panduan Kami', 'Visi & Misi', 'Komitmen kami...')");
    $section_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about_vision_mission_section WHERE id = 1"));
}

// Handle Delete Item
if (isset($_GET['delete_item'])) {
    $item_id = intval($_GET['delete_item']);
    $query = "DELETE FROM about_vision_mission_items WHERE id = $item_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Terhapus!', 'message' => 'Item berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'message' => 'Gagal menghapus item'];
    }
    echo "<script>window.location='?page=vision_mission_about';</script>";
    exit;
}

// Fetch Items
$items_query = mysqli_query($conn, "SELECT * FROM about_vision_mission_items ORDER BY sort_order ASC, id ASC");
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
                        <li class="breadcrumb-item active">Visi & Misi</li>
                    </ol>
                </div>
                <h4 class="page-title">Kelola Visi & Misi</h4>
            </div>
        </div>
    </div>

    <!-- Alert System (Standardized) -->
    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Visi & Misi Section?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Visi & Misi Section</strong> adalah komponen penting yang menjelaskan arah dan tujuan organisasi Anda.
                                Halaman ini terdiri dari <strong>Header Section</strong> (Judul Utama & Deskripsi) dan <strong>Daftar Item</strong> (Kartu-kartu Visi, Misi, atau Nilai Inti).
                                Pastikan konten ini singkat, padat, dan representatif.
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-white rounded shadow-sm">
                                <i class="icofont-flag-alt-1 text-primary" style="font-size: 30px;"></i>
                                <h6 class="mt-2 mb-0">Visi & Misi</h6>
                                <small class="text-muted">Ilustrasi Section</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Header Info (Existing Feature - Kept for functionality) -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-header bg-primary text-white row align-items-center m-0">
                    <div class="col-md-9">
                        <h5 class="m-0"><i class="mdi mdi-page-layout-header"></i> Header Section Data</h5>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="?page=edit_vision_mission_section&id=1" class="btn btn-light btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit Header
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Subtitle:</strong><br>
                            <span class="text-muted"><?php echo htmlspecialchars($section_data['subtitle'] ?? '-'); ?></span>
                        </div>
                        <div class="col-md-4">
                            <strong>Judul Utama:</strong><br>
                            <h3><?php echo htmlspecialchars($section_data['title'] ?? '-'); ?></h3>
                        </div>
                        <div class="col-md-4">
                            <strong>Deskripsi:</strong><br>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($section_data['description'] ?? '-'); ?></p>
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
                        <h4 class="mt-0 header-title">Daftar Item Visi & Misi</h4>
                        <a href="?page=add_vision_mission_item" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambah Item
                        </a>
                    </div>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Ikon</th>
                                <th width="20%">Judul</th>
                                <th width="35%">Deskripsi & List</th>
                                <th width="10%">Urutan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $index => $item): ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $index + 1; ?></td>
                                    <td class="text-center align-middle">
                                        <i class="<?php echo htmlspecialchars($item['icon']); ?> font-30 text-primary"></i><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($item['icon']); ?></small>
                                    </td>
                                    <td class="align-middle"><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                                    <td class="align-middle">
                                        <?php if($item['description']): ?>
                                            <p class="mb-1 text-muted small"><?php echo htmlspecialchars($item['description']); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        if ($item['list_items']) {
                                            $list = explode("\n", $item['list_items']);
                                            echo '<ul class="pl-3 mb-0 text-muted small bg-light p-2 rounded">';
                                            foreach (array_slice($list, 0, 3) as $li) {
                                                if(trim($li)) echo '<li><i class="mdi mdi-check-circle-outline text-success"></i> ' . htmlspecialchars(trim($li)) . '</li>';
                                            }
                                            if (count($list) > 3) echo '<li><em>... (' . (count($list)-3) . ' poin lainnya)</em></li>';
                                            echo '</ul>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-secondary p-2">
                                            urutan: <?php echo $item['sort_order']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="?page=edit_vision_mission_item&id=<?php echo $item['id']; ?>" class="btn btn-primary btn-sm" title="Ubah">
                                            <i class="mdi mdi-pencil"></i> Ubah
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="<?php echo $item['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($item['title']); ?>" 
                                            title="Hapus">
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
        <!-- Cara Penggunaan -->
        <div class="col-lg-6">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-book-open-page-variant mr-2"></i>Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Header Info:</strong><br>
                        <small class="text-muted">Gunakan tombol "Edit Header" di atas untuk mengubah Judul Utama dan Deskripsi Global Section ini.</small></li>
                        
                        <li class="mb-2"><strong>Menambah Item:</strong><br>
                        <small class="text-muted">Klik tombol hijau "Tambah Item" untuk membuat kartu baru (misal: Kartu Visi, Kartu Misi, Kartu Nilai).</small></li>
                        
                        <li class="mb-2"><strong>List Poin:</strong><br>
                        <small class="text-muted">Dalam form item, gunakan tombol "Enter" untuk memisahkan setiap poin. Ini akan muncul sebagai daftar checklist yang rapi.</small></li>
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
                        <li class="mb-2"><strong>Konsistensi Konten:</strong><br>
                        <small class="text-muted">Buat deskripsi Visi singkat dan inspiratif. Gunakan daftar poin untuk Misi agar mudah dibaca sekilas.</small></li>
                        
                        <li class="mb-2"><strong>Ikon Visual:</strong><br>
                        <small class="text-muted">Pilih ikon yang relevan. Contoh: 'Eye' untuk Visi (pandangan), 'Flag' untuk Misi (tujuan), atau 'Heart' untuk Values.</small></li>
                        
                        <li class="mb-2"><strong>Urutan Tampilan:</strong><br>
                        <small class="text-muted">Gunakan fitur 'Urutan' untuk mengatur posisi kartu. Angka 1 akan muncul paling kiri/atas.</small></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Kolom Tabel (Standardized from Alert to Card) -->
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
                                    <td>Simbol visual yang mewakili kartu (menggunakan library Icofont).</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Judul</span></td>
                                    <td>Nama kartu (Contoh: "Visi Kami", "Misi Kami").</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-dark">Urutan</span></td>
                                    <td>Angka prioritas tampilan (1 = Pertama).</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><span class="badge badge-dark">Deskripsi & List</span></td>
                                    <td>Konten teks dan poin-poin rincian yang ada di dalam kartu.</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">Aksi</span></td>
                                    <td>Tombol untuk mengubah konten atau menghapus item selamanya.</td>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Event Delegation for Delete (Vanilla JS for max compatibility)
        document.body.addEventListener('click', function(e) {
            // Check if clicked element is .btn-delete or inside it
            var target = e.target.closest('.btn-delete');
            
            if (target) {
                var id = target.getAttribute('data-id');
                var title = target.getAttribute('data-title');

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
                        window.location.href = '?page=vision_mission_about&delete_item=' + id;
                    }
                });
            }
        });
    });
</script>