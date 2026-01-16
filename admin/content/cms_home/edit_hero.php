<?php
// Edit Konten Hero
// Cek apakah parameter ID ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'ID Tidak Valid!',
        'message' => 'ID konten hero diperlukan'
    ];
    header("Location: ?page=hero");
    exit;
}

$id = intval($_GET['id']);

// Ambil data hero
$query = mysqli_query($conn, "SELECT * FROM hero_section WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Tidak Ditemukan!',
        'message' => 'Konten hero tidak ditemukan'
    ];
    header("Location: ?page=hero");
    exit;
}

$hero = mysqli_fetch_assoc($query);

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul maksimal 255 karakter';
    } elseif (strlen($title) < 3) {
        $errors[] = 'Judul minimal 3 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Sub Judul (opsional)
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    if (strlen($subtitle) > 255) {
        $errors[] = 'Sub judul maksimal 255 karakter';
    }
    $subtitle = mysqli_real_escape_string($conn, $subtitle);

    // Validasi Deskripsi (opsional)
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 1000) {
        $errors[] = 'Deskripsi maksimal 1000 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Teks Tombol (opsional)
    $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : '';
    if (strlen($button_text) > 100) {
        $errors[] = 'Teks tombol maksimal 100 karakter';
    }
    $button_text = mysqli_real_escape_string($conn, $button_text);

    // Validasi Link Tombol
    $button_link = isset($_POST['button_link']) ? trim($_POST['button_link']) : '';
    if (!empty($button_text) && empty($button_link)) {
        $errors[] = 'Link tombol wajib diisi jika teks tombol ada';
    }
    if (strlen($button_link) > 500) {
        $errors[] = 'Link tombol maksimal 500 karakter';
    }
    if (!empty($button_link) && !preg_match('/^(#|\/|https?:\/\/)/', $button_link)) {
        $errors[] = 'Format link tombol tidak valid. Gunakan format: #anchor, /halaman, atau https://link';
    }
    $button_link = mysqli_real_escape_string($conn, $button_link);

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image_path = $hero['image']; // Gunakan gambar yang ada

    // Validasi dan upload gambar baru
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['image']['tmp_name']);

        // Validasi ekstensi file
        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF, WEBP yang diperbolehkan';
        }

        // Validasi MIME type
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file tidak valid. Pastikan file adalah gambar yang benar';
        }

        // Validasi ukuran file (max 3MB)
        if ($_FILES['image']['size'] > 3145728) {
            $errors[] = 'Ukuran file terlalu besar. Maksimal 3MB';
        }

        // Validasi dimensi gambar (minimal 800x400)
        if (empty($errors)) {
            $img_info = getimagesize($_FILES['image']['tmp_name']);
            if ($img_info) {
                $width = $img_info[0];
                $height = $img_info[1];
                if ($width < 800 || $height < 400) {
                    $errors[] = "Dimensi gambar terlalu kecil ({$width}x{$height}). Minimal 800x400 piksel";
                }
            }
        }

        // Upload jika tidak ada error
        if (empty($errors)) {
            $new_filename = 'hero_' . time() . '_' . uniqid() . '.' . $file_ext;
            $upload_path = '../uploads/hero/' . $new_filename;

            if (!is_dir('../uploads/hero')) {
                mkdir('../uploads/hero', 0755, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Hapus gambar lama jika ada
                if ($hero['image'] && file_exists('../' . $hero['image'])) {
                    unlink('../' . $hero['image']);
                }
                $image_path = 'uploads/hero/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload file gambar. Silakan coba lagi';
            }
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
        $upload_errors = [
            1 => 'Ukuran file melebihi batas maksimal server',
            2 => 'Ukuran file melebihi batas maksimal form',
            3 => 'File hanya terupload sebagian',
            6 => 'Folder temporary tidak ditemukan',
            7 => 'Gagal menulis file ke disk',
            8 => 'Upload dihentikan oleh ekstensi PHP'
        ];
        $errors[] = $upload_errors[$_FILES['image']['error']] ?? 'Terjadi kesalahan saat upload';
    }

    // Update database jika tidak ada error
    if (empty($errors)) {
        $update_query = "UPDATE hero_section SET 
                         title = '$title',
                         subtitle = '$subtitle',
                         description = '$description',
                         image = '$image_path',
                         button_text = '$button_text',
                         button_link = '$button_link',
                         is_active = $is_active,
                         updated_at = NOW()
                         WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Konten hero berhasil diperbarui'
            ];
            header("Location: ?page=hero");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
        }
    }

    // Set form errors
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

// Ambil form errors
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

// Siapkan script SweetAlert untuk error
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
                        <li class="breadcrumb-item"><a href="?page=hero">Hero Section</a></li>
                        <li class="breadcrumb-item active">Ubah</li>
                    </ol>
                </div>
                <h4 class="page-title">Ubah Konten Hero</h4>
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
                            <h4 class="mt-0 header-title">Detail Banner Hero</h4>
                            <p class="text-muted mb-0 font-14">
                                Perbarui konten banner hero yang ditampilkan di halaman utama
                            </p>
                        </div>
                        <span class="badge badge-<?php echo $hero['is_active'] ? 'success' : 'secondary'; ?> p-2">
                            <?php echo $hero['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data" id="heroForm">
                        <!-- Judul -->
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">
                                Judul <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Contoh: Selamat Datang di Hafsa Islamic Center"
                                    required minlength="3" maxlength="255"
                                    value="<?php echo htmlspecialchars($hero['title']); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks utama yang besar dan mencolok (3-255 karakter)
                                </small>
                            </div>
                        </div>

                        <!-- Sub Judul -->
                        <div class="form-group row">
                            <label for="subtitle" class="col-sm-3 col-form-label">Sub Judul</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="subtitle" name="subtitle"
                                    placeholder="Contoh: Bersama Membangun Umat"
                                    maxlength="255"
                                    value="<?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks pendukung di bawah judul (opsional)
                                </small>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Deskripsi singkat tentang hero banner ini (opsional)"
                                    maxlength="1000"><?php echo htmlspecialchars($hero['description'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Penjelasan tambahan, maksimal 1000 karakter
                                </small>
                            </div>
                        </div>

                        <!-- Gambar -->
                        <div class="form-group row">
                            <label for="image" class="col-sm-3 col-form-label">Gambar Hero</label>
                            <div class="col-sm-9">
                                <?php if (!empty($hero['image'])): ?>
                                        <div class="mb-3 p-3 bg-light rounded">
                                            <label class="text-muted small mb-2 d-block">
                                                <i class="mdi mdi-image mr-1"></i>Gambar Saat Ini:
                                            </label>
                                            <img src="<?php echo '../' . $hero['image']; ?>" alt="Gambar Hero Saat Ini"
                                                class="img-fluid rounded shadow-sm" style="max-height: 200px;" id="currentImage">
                                        </div>
                                <?php endif; ?>
                                
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="image" name="image"
                                        accept="image/jpeg,image/png,image/gif,image/webp">
                                    <label class="custom-file-label" for="image">Pilih gambar baru...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> 
                                    Kosongkan jika tidak ingin mengubah gambar | 
                                    <strong>Format:</strong> JPG, PNG, GIF, WEBP | 
                                    <strong>Maks:</strong> 3MB
                                </small>
                                
                                <!-- Preview gambar baru -->
                                <div id="imagePreview" class="mt-2" style="display:none;">
                                    <label class="text-success small mb-2 d-block">
                                        <i class="mdi mdi-check-circle mr-1"></i>Gambar Baru (Preview):
                                    </label>
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cursor-default-click mr-1"></i>Tombol Aksi (CTA)</h5>

                        <!-- Teks Tombol -->
                        <div class="form-group row">
                            <label for="button_text" class="col-sm-3 col-form-label">Teks Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_text" name="button_text"
                                    placeholder="Contoh: Donasi Sekarang, Daftar Event"
                                    maxlength="100"
                                    value="<?php echo htmlspecialchars($hero['button_text'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks yang muncul di tombol (opsional)
                                </small>
                            </div>
                        </div>

                        <!-- Link Tombol -->
                        <div class="form-group row">
                            <label for="button_link" class="col-sm-3 col-form-label">Link Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_link" name="button_link"
                                    placeholder="Contoh: #donasi, /kontak, https://link-tujuan.com"
                                    maxlength="500"
                                    value="<?php echo htmlspecialchars($hero['button_link'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> 
                                    URL tujuan saat tombol diklik. Format: <code>#anchor</code>, <code>/halaman</code>, atau <code>https://...</code>
                                </small>
                            </div>
                        </div>

                        <hr>

                        <!-- Status -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        <?php echo $hero['is_active'] ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">
                                        <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Hanya hero dengan status "Aktif" yang ditampilkan di website
                                </small>
                            </div>
                        </div>

                        <hr>

                        <!-- Tombol Submit -->
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-content-save"></i> Simpan Perubahan
                                </button>
                                <a href="?page=hero" class="btn btn-secondary btn-lg btn-cancel">
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
                            <td><strong>#<?php echo $hero['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat:</td>
                            <td><?php echo isset($hero['created_at']) ? date('d M Y, H:i', strtotime($hero['created_at'])) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diperbarui:</td>
                            <td><?php echo isset($hero['updated_at']) ? date('d M Y, H:i', strtotime($hero['updated_at'])) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <?php if ($hero['is_active']): ?>
                                        <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                        <span class="badge badge-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Panduan Pengisian -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Judul (Wajib)</strong>
                        <p class="text-muted small mb-0">Buat judul yang menarik dan singkat</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Sub Judul</strong>
                        <p class="text-muted small mb-0">Kalimat pendukung untuk memperjelas judul</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Gambar</strong>
                        <p class="text-muted small mb-0">Upload gambar baru atau biarkan kosong untuk tetap menggunakan yang ada</p>
                    </div>
                    <div class="mb-0">
                        <strong><i class="mdi mdi-numeric-4-circle text-primary mr-1"></i>Tombol Aksi</strong>
                        <p class="text-muted small mb-0">Arahkan pengunjung ke halaman penting</p>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Gambar ideal: <strong>1920 x 600 piksel</strong></li>
                        <li class="mb-2">Gunakan gambar berkualitas tinggi</li>
                        <li class="mb-2">Judul singkat lebih efektif (5-8 kata)</li>
                        <li class="mb-0">Status Nonaktif = tersimpan tapi tidak ditampilkan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
// Preview gambar sebelum upload
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const label = document.querySelector('.custom-file-label');

    if (file) {
        label.textContent = file.name;

        // Validasi ukuran file
        if (file.size > 3145728) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar!',
                text: 'Ukuran file maksimal 3MB. Silakan kompres atau pilih file lain.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            this.value = '';
            label.textContent = 'Pilih gambar baru...';
            preview.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';

            // Cek dimensi gambar
            const img = new Image();
            img.onload = function() {
                if (this.width < 800 || this.height < 400) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dimensi Gambar Kecil',
                        html: 'Dimensi gambar Anda <strong>' + this.width + 'x' + this.height + '</strong> piksel.<br>Disarankan minimal <strong>800x400</strong> piksel untuk hasil terbaik.',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#ffc107'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Gambar Siap!',
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
        label.textContent = 'Pilih gambar baru...';
        preview.style.display = 'none';
    }
});

// Validasi form sebelum submit
document.getElementById('heroForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const buttonText = document.getElementById('button_text').value.trim();
    const buttonLink = document.getElementById('button_link').value.trim();

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

    if (buttonText && !buttonLink) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Link Tombol Kosong!',
            text: 'Jika teks tombol diisi, link tombol juga harus diisi.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
        return false;
    }

    // Tampilkan loading saat submit
    Swal.fire({
        title: 'Menyimpan Perubahan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    return true;
});

// Konfirmasi batal dengan SweetAlert
document.querySelector('.btn-cancel').addEventListener('click', function(e) {
    e.preventDefault();
    const link = this.href;
    
    Swal.fire({
        icon: 'question',
        title: 'Batalkan Perubahan?',
        text: 'Perubahan yang belum disimpan akan hilang. Yakin ingin membatalkan?',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjut Mengubah',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link;
        }
    });
});

// Konfirmasi perubahan status
document.getElementById('is_active').addEventListener('change', function() {
    const status = this.checked ? 'Aktif' : 'Nonaktif';
    const icon = this.checked ? 'success' : 'info';
    const message = this.checked 
        ? 'Hero akan ditampilkan di halaman depan setelah disimpan' 
        : 'Hero akan disembunyikan dari halaman depan setelah disimpan';
    
    Swal.fire({
        icon: icon,
        title: 'Status: ' + status,
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });
});
</script>