<?php
// Edit Header Pilar Keimanan

$query = mysqli_query($conn, "SELECT * FROM faith_header WHERE id = 1 LIMIT 1");
$header = mysqli_fetch_assoc($query);

// Jika belum ada data, buat default
if (!$header) {
    mysqli_query($conn, "INSERT INTO faith_header (id, subtitle, title) VALUES (1, 'Pilar Keimanan', 'Lima Rukun Islam')");
    $query = mysqli_query($conn, "SELECT * FROM faith_header WHERE id = 1 LIMIT 1");
    $header = mysqli_fetch_assoc($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Subjudul
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    if (strlen($subtitle) > 150) {
        $errors[] = 'Subjudul maksimal 150 karakter';
    }
    $subtitle = mysqli_real_escape_string($conn, $subtitle);

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul maksimal 255 karakter';
    } elseif (strlen($title) < 5) {
        $errors[] = 'Judul minimal 5 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Simpan ke database
    if (empty($errors)) {
        $update = "UPDATE faith_header SET subtitle = '$subtitle', title = '$title' WHERE id = 1";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Header pilar keimanan berhasil diperbarui'];
            header("Location: ?page=edit_faith_header");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

// Handle alerts
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

$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error) $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', html: '" . addslashes($error_list) . "', confirmButtonText: 'Mengerti', confirmButtonColor: '#dc3545' }); });</script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=faith_pillars">Pilar Keimanan</a></li>
                        <li class="breadcrumb-item active">Edit Header</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Header Pilar Keimanan</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Header Pilar Keimanan?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Header Pilar Keimanan</strong> adalah teks judul dan subjudul yang ditampilkan di bagian atas 
                                section Pilar Keimanan pada halaman utama. Header ini membantu pengunjung memahami konten tab-tab pilar yang akan mereka lihat.
                            </p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-format-header-1 text-primary" style="font-size: 50px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Pengaturan Header</h4>
                    <p class="text-muted m-b-30 font-14">Ubah judul dan subjudul section pilar keimanan</p>

                    <form method="POST" action="" id="headerForm">
                        <div class="form-group">
                            <label for="subtitle">Subjudul</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle"
                                value="<?php echo htmlspecialchars($header['subtitle'] ?? ''); ?>"
                                placeholder="Contoh: Pilar Keimanan, Rukun Islam" maxlength="150">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Teks kecil di atas judul utama (opsional, maks 150 karakter)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="title">Judul Utama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?php echo htmlspecialchars($header['title'] ?? ''); ?>"
                                placeholder="Contoh: Lima Rukun Islam" required minlength="5" maxlength="255">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Judul besar section pilar keimanan (wajib, 5-255 karakter)
                            </small>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="?page=faith_pillars" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Preview -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview Header</h5>
                </div>
                <div class="card-body">
                    <div class="text-center p-4 bg-light rounded">
                        <p class="text-primary mb-2" style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($header['subtitle'] ?? 'Pilar Keimanan'); ?>
                        </p>
                        <h4 class="mb-0 font-weight-bold">
                            <?php echo htmlspecialchars($header['title'] ?? 'Lima Rukun Islam'); ?>
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Subjudul:</strong> Teks kecil di atas judul utama</li>
                        <li class="mb-2"><strong>Judul:</strong> Heading besar yang eye-catching</li>
                        <li class="mb-0"><strong>Tips:</strong> Gunakan kalimat singkat dan jelas</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Header</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-2 bg-light rounded small">
                        <strong class="text-primary">Subjudul:</strong> Pilar Keimanan<br>
                        <strong>Judul:</strong> Lima Rukun Islam
                    </div>
                    <div class="p-2 bg-light rounded small">
                        <strong class="text-primary">Subjudul:</strong> Dasar Keimanan<br>
                        <strong>Judul:</strong> Fondasi Agama Islam
                    </div>
                </div>
            </div>

            <!-- Navigasi -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-link-variant mr-2"></i>Navigasi</h5>
                </div>
                <div class="card-body">
                    <a href="?page=faith_pillars" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="mdi mdi-view-list"></i> Kelola Pilar
                    </a>
                    <a href="?page=add_faith_pillar" class="btn btn-outline-success btn-block">
                        <i class="mdi mdi-plus"></i> Tambah Pilar Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>
<?php echo $error_script; ?>

<script>
// Validasi form
document.getElementById('headerForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();

    if (title.length < 5) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Judul Terlalu Pendek!',
            text: 'Judul harus minimal 5 karakter.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
        return false;
    }

    Swal.fire({
        title: 'Menyimpan Perubahan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });

    return true;
});

// Konfirmasi batal
document.querySelector('.btn-cancel').addEventListener('click', function(e) {
    e.preventDefault();
    const link = this.href;
    
    Swal.fire({
        icon: 'question',
        title: 'Batalkan Perubahan?',
        text: 'Perubahan yang belum disimpan akan hilang.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjut Mengubah',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) window.location.href = link;
    });
});
</script>