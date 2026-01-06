<?php
// Edit Kartu Fitur
// Cek apakah parameter ID ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'ID Tidak Valid!',
        'message' => 'ID fitur diperlukan'
    ];
    header("Location: ?page=features");
    exit;
}

$id = intval($_GET['id']);

// Ambil data fitur
$query = mysqli_query($conn, "SELECT * FROM feature_section WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Tidak Ditemukan!',
        'message' => 'Kartu fitur tidak ditemukan'
    ];
    header("Location: ?page=features");
    exit;
}

$feature = mysqli_fetch_assoc($query);

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul wajib diisi';
    } elseif (strlen($title) > 100) {
        $errors[] = 'Judul maksimal 100 karakter';
    } elseif (strlen($title) < 3) {
        $errors[] = 'Judul minimal 3 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Deskripsi (opsional)
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 500) {
        $errors[] = 'Deskripsi maksimal 500 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Teks Link (opsional)
    $link_text = isset($_POST['link_text']) ? trim($_POST['link_text']) : '';
    if (strlen($link_text) > 50) {
        $errors[] = 'Teks link maksimal 50 karakter';
    }
    $link_text = mysqli_real_escape_string($conn, $link_text);

    // Validasi URL Link
    $link_url = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
    if (!empty($link_text) && empty($link_url)) {
        $errors[] = 'URL link wajib diisi jika teks link ada';
    }
    if (strlen($link_url) > 500) {
        $errors[] = 'URL link maksimal 500 karakter';
    }
    if (!empty($link_url) && !preg_match('/^(#|\/|https?:\/\/)/', $link_url)) {
        $errors[] = 'Format URL tidak valid. Gunakan format: #anchor, /halaman, atau https://link';
    }
    $link_url = mysqli_real_escape_string($conn, $link_url);

    // Validasi Urutan
    $order_position = isset($_POST['order_position']) ? intval($_POST['order_position']) : 1;
    if ($order_position < 1 || $order_position > 100) {
        $errors[] = 'Urutan harus antara 1 - 100';
    }

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $icon_path = $feature['icon'];

    // Validasi dan upload ikon baru
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml');
        $filename = $_FILES['icon']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['icon']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format file tidak diizinkan. Hanya JPG, PNG, GIF, WEBP, SVG yang diperbolehkan';
        }

        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file tidak valid. Pastikan file adalah gambar yang benar';
        }

        if ($_FILES['icon']['size'] > 1048576) {
            $errors[] = 'Ukuran file terlalu besar. Maksimal 1MB untuk ikon';
        }

        if (empty($errors)) {
            $new_filename = 'feature_' . time() . '_' . uniqid() . '.' . $file_ext;
            $upload_path = '../uploads/features/' . $new_filename;

            if (!is_dir('../uploads/features')) {
                mkdir('../uploads/features', 0755, true);
            }

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $upload_path)) {
                if ($feature['icon'] && file_exists('../' . $feature['icon'])) {
                    unlink('../' . $feature['icon']);
                }
                $icon_path = 'uploads/features/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload file ikon. Silakan coba lagi';
            }
        }
    } elseif (isset($_FILES['icon']) && $_FILES['icon']['error'] != 4) {
        $upload_errors = [
            1 => 'Ukuran file melebihi batas maksimal server',
            2 => 'Ukuran file melebihi batas maksimal form',
            3 => 'File hanya terupload sebagian',
            6 => 'Folder temporary tidak ditemukan',
            7 => 'Gagal menulis file ke disk',
            8 => 'Upload dihentikan oleh ekstensi PHP'
        ];
        $errors[] = $upload_errors[$_FILES['icon']['error']] ?? 'Terjadi kesalahan saat upload';
    }

    // Update database jika tidak ada error
    if (empty($errors)) {
        $update_query = "UPDATE feature_section SET 
                         title = '$title',
                         description = '$description',
                         icon = '$icon_path',
                         link_text = '$link_text',
                         link_url = '$link_url',
                         order_position = $order_position,
                         is_active = $is_active,
                         updated_at = NOW()
                         WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Kartu fitur berhasil diperbarui'
            ];
            header("Location: ?page=features");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error) {
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $error_list .= '</ul>';
    $error_script = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '" . addslashes($error_list) . "',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>
    ";
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
                        <li class="breadcrumb-item"><a href="?page=features">Feature Section</a></li>
                        <li class="breadcrumb-item active">Ubah</li>
                    </ol>
                </div>
                <h4 class="page-title">Ubah Kartu Fitur</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Detail Kartu Fitur</h4>
                            <p class="text-muted mb-0 font-14">Perbarui konten kartu fitur</p>
                        </div>
                        <span class="badge badge-<?php echo $feature['is_active'] ? 'success' : 'secondary'; ?> p-2">
                            <?php echo $feature['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data" id="featureForm">
                        <!-- Judul -->
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">
                                Judul <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Contoh: Kajian Al-Quran"
                                    required minlength="3" maxlength="100"
                                    value="<?php echo htmlspecialchars($feature['title']); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Nama fitur (3-100 karakter)
                                </small>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Jelaskan singkat tentang fitur ini..."
                                    maxlength="500"><?php echo htmlspecialchars($feature['description'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Maksimal 500 karakter
                                </small>
                            </div>
                        </div>

                        <!-- Ikon -->
                        <div class="form-group row">
                            <label for="icon" class="col-sm-3 col-form-label">Ikon</label>
                            <div class="col-sm-9">
                                <?php if (!empty($feature['icon'])): ?>
                                        <div class="mb-3 p-3 bg-light rounded">
                                            <label class="text-muted small mb-2 d-block">
                                                <i class="mdi mdi-image mr-1"></i>Ikon Saat Ini:
                                            </label>
                                            <img src="<?php echo '../' . $feature['icon']; ?>" alt="Ikon Saat Ini"
                                                class="img-fluid rounded shadow-sm" style="max-height: 100px;">
                                        </div>
                                <?php endif; ?>
                                
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="icon" name="icon"
                                        accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                                    <label class="custom-file-label" for="icon">Pilih ikon baru...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> 
                                    Kosongkan jika tidak ingin mengubah | <strong>Maks:</strong> 1MB
                                </small>
                                
                                <div id="iconPreview" class="mt-2" style="display:none;">
                                    <label class="text-success small mb-2 d-block">
                                        <i class="mdi mdi-check-circle mr-1"></i>Ikon Baru (Preview):
                                    </label>
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 100px;">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-link mr-1"></i>Pengaturan Link</h5>

                        <!-- Teks Link -->
                        <div class="form-group row">
                            <label for="link_text" class="col-sm-3 col-form-label">Teks Link</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="link_text" name="link_text"
                                    placeholder="Contoh: Pelajari Lebih Lanjut" maxlength="50"
                                    value="<?php echo htmlspecialchars($feature['link_text'] ?? ''); ?>">
                            </div>
                        </div>

                        <!-- URL Link -->
                        <div class="form-group row">
                            <label for="link_url" class="col-sm-3 col-form-label">URL Link</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="link_url" name="link_url"
                                    placeholder="Contoh: #programs, /kajian" maxlength="500"
                                    value="<?php echo htmlspecialchars($feature['link_url'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    Format: <code>#anchor</code>, <code>/halaman</code>, atau <code>https://...</code>
                                </small>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cog mr-1"></i>Pengaturan Tampilan</h5>

                        <!-- Urutan -->
                        <div class="form-group row">
                            <label for="order_position" class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="order_position" name="order_position"
                                    value="<?php echo $feature['order_position'] ?? 1; ?>" 
                                    min="1" max="100" style="width: 100px;">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Posisi tampilan (1 = tampil pertama)
                                </small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        <?php echo $feature['is_active'] ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">
                                        <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Tombol Submit -->
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-content-save"></i> Simpan Perubahan
                                </button>
                                <a href="?page=features" class="btn btn-secondary btn-lg btn-cancel">
                                    <i class="mdi mdi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Informasi -->
        <div class="col-lg-4">
            <!-- Info Data -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">ID:</td>
                            <td><strong>#<?php echo $feature['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Urutan:</td>
                            <td><span class="badge badge-info"><?php echo $feature['order_position'] ?? 1; ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diperbarui:</td>
                            <td><?php echo isset($feature['updated_at']) ? date('d M Y, H:i', strtotime($feature['updated_at'])) : '-'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Ubah field yang ingin diperbarui</li>
                        <li class="mb-2">Kosongkan field ikon untuk tetap menggunakan yang ada</li>
                        <li class="mb-2">Klik "Simpan Perubahan" untuk menyimpan</li>
                        <li class="mb-0">Angka urutan kecil = tampil lebih awal</li>
                    </ul>
                </div>
            </div>

            <!-- Tips Ikon -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Ikon</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ukuran ideal:</strong> 200x200 piksel</li>
                        <li class="mb-2"><strong>Format:</strong> PNG/SVG untuk transparansi</li>
                        <li class="mb-0"><strong>Maks file:</strong> 1MB</li>
                    </ul>
                </div>
            </div>

            <!-- Contoh Fitur -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Nama Fitur</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-primary p-2 m-1">Kajian Al-Quran</span>
                        <span class="badge badge-primary p-2 m-1">Sejarah Islam</span>
                        <span class="badge badge-primary p-2 m-1">Kelas Tahfidz</span>
                        <span class="badge badge-primary p-2 m-1">Konsultasi Syariah</span>
                    </div>
                </div>
            </div>

            <!-- Contoh Teks Link -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-cursor-pointer mr-2"></i>Contoh Teks Link</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-secondary p-2 m-1">Pelajari Lebih Lanjut</span>
                        <span class="badge badge-secondary p-2 m-1">Selengkapnya</span>
                        <span class="badge badge-secondary p-2 m-1">Lihat Detail</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
// Preview ikon
document.getElementById('icon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('iconPreview');
    const previewImg = document.getElementById('previewImg');
    const label = document.querySelector('.custom-file-label');

    if (file) {
        label.textContent = file.name;

        if (file.size > 1048576) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar!',
                text: 'Ukuran file maksimal 1MB.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            this.value = '';
            label.textContent = 'Pilih ikon baru...';
            preview.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';

            const img = new Image();
            img.onload = function() {
                if (this.width < 100 || this.height < 100) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dimensi Ikon Kecil',
                        html: 'Dimensi: <strong>' + this.width + 'x' + this.height + '</strong> piksel.<br>Disarankan minimal <strong>200x200</strong> piksel.',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#ffc107'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ikon Siap!',
                        text: 'Dimensi: ' + this.width + 'x' + this.height + ' piksel',
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
        label.textContent = 'Pilih ikon baru...';
        preview.style.display = 'none';
    }
});

// Validasi form
document.getElementById('featureForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const linkText = document.getElementById('link_text').value.trim();
    const linkUrl = document.getElementById('link_url').value.trim();

    if (title.length < 3) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Judul Terlalu Pendek!',
            text: 'Judul harus minimal 3 karakter.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
        return false;
    }

    if (linkText && !linkUrl) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'URL Link Kosong!',
            text: 'Jika teks link diisi, URL link juga harus diisi.',
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

// Toast status toggle
document.getElementById('is_active').addEventListener('change', function() {
    const status = this.checked ? 'Aktif' : 'Nonaktif';
    Swal.fire({
        icon: this.checked ? 'success' : 'info',
        title: 'Status: ' + status,
        text: this.checked ? 'Akan ditampilkan setelah disimpan' : 'Akan disembunyikan setelah disimpan',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    });
});
</script>