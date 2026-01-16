<?php
// Edit Pengaturan Background Kutipan

$query = mysqli_query($conn, "SELECT * FROM quote_settings WHERE id = 1 LIMIT 1");
$settings = mysqli_fetch_assoc($query);

// Jika belum ada data, buat default
if (!$settings) {
    mysqli_query($conn, "INSERT INTO quote_settings (id) VALUES (1)");
    $query = mysqli_query($conn, "SELECT * FROM quote_settings WHERE id = 1 LIMIT 1");
    $settings = mysqli_fetch_assoc($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $bg_image_path = $settings['background_image'];

    // Validasi dan upload gambar background
    if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $file_ext = strtolower(pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['background_image']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format gambar tidak diizinkan. Hanya JPG, PNG, GIF, WEBP';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file gambar tidak valid';
        }
        if ($_FILES['background_image']['size'] > 3145728) {
            $errors[] = 'Ukuran gambar maksimal 3MB';
        }

        if (empty($errors)) {
            $new_filename = 'quote_bg_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/quotes')) mkdir('../uploads/quotes', 0755, true);
            if (move_uploaded_file($_FILES['background_image']['tmp_name'], '../uploads/quotes/' . $new_filename)) {
                if ($settings['background_image'] && file_exists('../' . $settings['background_image'])) {
                    unlink('../' . $settings['background_image']);
                }
                $bg_image_path = 'uploads/quotes/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar';
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $update = "UPDATE quote_settings SET background_image = '$bg_image_path' WHERE id = 1";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Background kutipan berhasil diperbarui'];
            header("Location: ?page=edit_quote_settings");
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
                        <li class="breadcrumb-item"><a href="?page=quotes">Kutipan</a></li>
                        <li class="breadcrumb-item active">Pengaturan Background</li>
                    </ol>
                </div>
                <h4 class="page-title">Pengaturan Background Kutipan</h4>
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
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu Background Kutipan?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Background Kutipan</strong> adalah gambar latar belakang yang ditampilkan di belakang 
                                slider kutipan inspiratif di halaman utama. Gambar yang bagus akan membuat kutipan terlihat lebih menarik.
                            </p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-image-filter-hdr text-primary" style="font-size: 50px;"></i>
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
                    <h4 class="mt-0 header-title">Gambar Background</h4>
                    <p class="text-muted m-b-30 font-14">Atur gambar latar belakang untuk section kutipan</p>

                    <form method="POST" action="" enctype="multipart/form-data" id="settingsForm">
                        <div class="form-group">
                            <label>Background Saat Ini</label>
                            <?php if (!empty($settings['background_image'])): ?>
                            <div class="mb-3 p-3 bg-light rounded">
                                <img src="<?php echo '../' . $settings['background_image']; ?>"
                                    class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                            </div>
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="mdi mdi-alert mr-2"></i>Belum ada gambar background yang diatur
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="background_image">Upload Background Baru</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="background_image" name="background_image" accept="image/jpeg,image/png,image/gif,image/webp">
                                <label class="custom-file-label" for="background_image">Pilih gambar...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Format: JPG, PNG, GIF, WEBP | Maks: 3MB | Ideal: 1920×800px
                            </small>
                            <div id="imagePreview" class="mt-3" style="display:none;">
                                <small class="text-success d-block mb-2"><i class="mdi mdi-check-circle mr-1"></i>Preview Gambar Baru:</small>
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="?page=quotes" class="btn btn-secondary btn-lg btn-cancel">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Tips Gambar -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Gunakan gambar resolusi tinggi (1920×800px)</li>
                        <li class="mb-2">Gambar gelap lebih cocok dengan teks putih</li>
                        <li class="mb-2">Orientasi landscape (horizontal) disarankan</li>
                        <li class="mb-0">Hindari gambar yang terlalu ramai/banyak detail</li>
                    </ul>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Format:</strong> JPG, PNG, GIF, WEBP</li>
                        <li class="mb-2"><strong>Ukuran:</strong> Maksimal 3MB</li>
                        <li class="mb-2"><strong>Dimensi:</strong> 1920×800 piksel ideal</li>
                        <li class="mb-0"><strong>Orientasi:</strong> Landscape</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh Gambar -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-image-multiple mr-2"></i>Jenis Gambar Cocok</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-light p-2 m-1 border">Masjid</span>
                        <span class="badge badge-light p-2 m-1 border">Pemandangan</span>
                        <span class="badge badge-light p-2 m-1 border">Langit</span>
                        <span class="badge badge-light p-2 m-1 border">Abstrak</span>
                        <span class="badge badge-light p-2 m-1 border">Kaligrafi</span>
                    </div>
                </div>
            </div>

            <!-- Navigasi -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-link-variant mr-2"></i>Navigasi</h5>
                </div>
                <div class="card-body">
                    <a href="?page=quotes" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="mdi mdi-view-list"></i> Kelola Kutipan
                    </a>
                    <a href="?page=add_quote" class="btn btn-outline-success btn-block">
                        <i class="mdi mdi-plus"></i> Tambah Kutipan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>
<?php echo $error_script; ?>

<style>
/* Fix untuk nama file yang terlalu panjang */
.custom-file-label {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding-right: 90px;
}
.custom-file-label::after {
    content: "Telusuri";
}
</style>

<script>
// Fungsi untuk memotong nama file yang panjang
function truncateFilename(filename, maxLength = 30) {
    if (filename.length <= maxLength) return filename;
    const ext = filename.split('.').pop();
    const name = filename.substring(0, filename.lastIndexOf('.'));
    const truncatedName = name.substring(0, maxLength - ext.length - 4) + '...';
    return truncatedName + '.' + ext;
}

// Preview gambar
document.getElementById('background_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const label = this.nextElementSibling;

    if (file) {
        label.textContent = truncateFilename(file.name);

        if (file.size > 3145728) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar!',
                text: 'Ukuran gambar maksimal 3MB.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            this.value = '';
            label.textContent = 'Pilih gambar...';
            preview.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';

            // Validasi dimensi
            const img = new Image();
            img.onload = function() {
                const width = this.width;
                const height = this.height;

                if (width < 800 || height < 400) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dimensi Gambar Kecil!',
                        html: 'Dimensi: <strong>' + width + 'x' + height + '</strong>px<br><br>Disarankan minimal <strong>1920×800</strong>px untuk hasil terbaik.',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#ffc107'
                    });
                } else if (height > width) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Orientasi Portrait',
                        html: 'Background disarankan menggunakan orientasi <strong>Landscape</strong> (lebar lebih besar dari tinggi).<br><br>Dimensi saat ini: <strong>' + width + 'x' + height + '</strong>px',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#17a2b8'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Gambar Siap!',
                        text: 'Dimensi: ' + width + 'x' + height + 'px',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        label.textContent = 'Pilih gambar...';
        preview.style.display = 'none';
    }
});

// Loading saat submit
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    Swal.fire({
        title: 'Menyimpan Perubahan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });
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