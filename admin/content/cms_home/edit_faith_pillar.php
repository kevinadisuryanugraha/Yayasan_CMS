<?php
// Edit Pilar Keimanan

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'ID Tidak Valid!', 'message' => 'ID pilar diperlukan'];
    header("Location: ?page=faith_pillars");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM faith_pillars WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Tidak Ditemukan!', 'message' => 'Pilar tidak ditemukan'];
    header("Location: ?page=faith_pillars");
    exit;
}

$pillar = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Nama Pilar (wajib)
    $pillar_name = isset($_POST['pillar_name']) ? trim($_POST['pillar_name']) : '';
    if (empty($pillar_name)) {
        $errors[] = 'Nama pilar wajib diisi';
    } elseif (strlen($pillar_name) > 100) {
        $errors[] = 'Nama pilar maksimal 100 karakter';
    } elseif (strlen($pillar_name) < 3) {
        $errors[] = 'Nama pilar minimal 3 karakter';
    }
    $pillar_name = mysqli_real_escape_string($conn, $pillar_name);

    // Validasi Subjudul
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    if (strlen($subtitle) > 100) {
        $errors[] = 'Subjudul maksimal 100 karakter';
    }
    $subtitle = mysqli_real_escape_string($conn, $subtitle);

    // Validasi Deskripsi
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 2000) {
        $errors[] = 'Deskripsi maksimal 2000 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Urutan
    $order_position = isset($_POST['order_position']) ? intval($_POST['order_position']) : 1;
    if ($order_position < 1 || $order_position > 100) {
        $errors[] = 'Urutan harus antara 1 - 100';
    }

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $main_image_path = $pillar['main_image'];
    $tab_icon_path = $pillar['tab_icon'];

    // Validasi dan upload gambar utama
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $file_ext = strtolower(pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['main_image']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format gambar utama tidak diizinkan';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file gambar utama tidak valid';
        }
        if ($_FILES['main_image']['size'] > 2097152) {
            $errors[] = 'Ukuran gambar utama maksimal 2MB';
        }

        if (empty($errors)) {
            $new_filename = 'faith_main_' . $id . '_' . time() . '.' . $file_ext;
            if (!is_dir('../uploads/faith')) mkdir('../uploads/faith', 0755, true);
            if (move_uploaded_file($_FILES['main_image']['tmp_name'], '../uploads/faith/' . $new_filename)) {
                if ($pillar['main_image'] && file_exists('../' . $pillar['main_image'])) unlink('../' . $pillar['main_image']);
                $main_image_path = 'uploads/faith/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar utama';
            }
        }
    }

    // Validasi dan upload ikon tab
    if (isset($_FILES['tab_icon']) && $_FILES['tab_icon']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml');
        $file_ext = strtolower(pathinfo($_FILES['tab_icon']['name'], PATHINFO_EXTENSION));
        
        if ($file_ext !== 'svg') {
            $file_mime = mime_content_type($_FILES['tab_icon']['tmp_name']);
            if (!in_array($file_mime, $allowed_mime)) {
                $errors[] = 'Tipe file ikon tab tidak valid';
            }
        }

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format ikon tab tidak diizinkan';
        }
        if ($_FILES['tab_icon']['size'] > 1048576) {
            $errors[] = 'Ukuran ikon tab maksimal 1MB';
        }

        if (empty($errors)) {
            $new_filename = 'faith_icon_' . $id . '_' . time() . '.' . $file_ext;
            if (!is_dir('../uploads/faith')) mkdir('../uploads/faith', 0755, true);
            if (move_uploaded_file($_FILES['tab_icon']['tmp_name'], '../uploads/faith/' . $new_filename)) {
                if ($pillar['tab_icon'] && file_exists('../' . $pillar['tab_icon'])) unlink('../' . $pillar['tab_icon']);
                $tab_icon_path = 'uploads/faith/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload ikon tab';
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $update = "UPDATE faith_pillars SET 
                   pillar_name = '$pillar_name', subtitle = '$subtitle', description = '$description',
                   main_image = '$main_image_path', tab_icon = '$tab_icon_path',
                   order_position = $order_position, is_active = $is_active
                   WHERE id = $id";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Pilar keimanan berhasil diperbarui'];
            header("Location: ?page=faith_pillars");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
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
                        <li class="breadcrumb-item active">Ubah</li>
                    </ol>
                </div>
                <h4 class="page-title">Ubah Pilar Keimanan</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Detail Pilar</h4>
                            <p class="text-muted mb-0 font-14">Perbarui informasi pilar keimanan</p>
                        </div>
                        <span class="badge badge-<?php echo $pillar['is_active'] ? 'success' : 'secondary'; ?> p-2">
                            <?php echo $pillar['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data" id="pillarForm">
                        <div class="form-group">
                            <label for="pillar_name">Nama Pilar <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pillar_name" name="pillar_name"
                                value="<?php echo htmlspecialchars($pillar['pillar_name']); ?>" required minlength="3" maxlength="100">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Nama tab pilar (3-100 karakter)</small>
                        </div>

                        <div class="form-group">
                            <label for="subtitle">Subjudul</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle"
                                value="<?php echo htmlspecialchars($pillar['subtitle'] ?? ''); ?>" maxlength="100">
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Keterangan tambahan (opsional)</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4" maxlength="2000"><?php echo htmlspecialchars($pillar['description'] ?? ''); ?></textarea>
                            <small class="form-text text-muted"><i class="mdi mdi-information-outline"></i> Penjelasan lengkap (maks 2000 karakter)</small>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-image mr-1"></i>Upload Gambar</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Utama</label>
                                    <?php if (!empty($pillar['main_image'])): ?>
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-2"><i class="mdi mdi-image mr-1"></i>Gambar Saat Ini:</small>
                                        <img src="<?php echo '../' . $pillar['main_image']; ?>" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                    </div>
                                    <?php endif; ?>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="main_image" name="main_image" accept="image/jpeg,image/png,image/gif,image/webp">
                                        <label class="custom-file-label" for="main_image">Pilih gambar baru...</label>
                                    </div>
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah | Maks 2MB</small>
                                    <div id="mainImagePreview" class="mt-2" style="display:none;">
                                        <small class="text-success d-block mb-1"><i class="mdi mdi-check-circle mr-1"></i>Gambar Baru:</small>
                                        <img id="mainPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 120px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ikon Tab</label>
                                    <?php if (!empty($pillar['tab_icon'])): ?>
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-2"><i class="mdi mdi-image mr-1"></i>Ikon Saat Ini:</small>
                                        <img src="<?php echo '../' . $pillar['tab_icon']; ?>" class="rounded shadow-sm" style="max-height: 60px;">
                                    </div>
                                    <?php endif; ?>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="tab_icon" name="tab_icon" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                                        <label class="custom-file-label" for="tab_icon">Pilih ikon baru...</label>
                                    </div>
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah | Maks 1MB</small>
                                    <div id="iconPreview" class="mt-2" style="display:none;">
                                        <small class="text-success d-block mb-1"><i class="mdi mdi-check-circle mr-1"></i>Ikon Baru:</small>
                                        <img id="iconPreviewImg" src="" alt="Preview" class="rounded" style="max-height: 60px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cog mr-1"></i>Pengaturan Tampilan</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_position">Urutan Tampilan</label>
                                    <input type="number" class="form-control" id="order_position" name="order_position"
                                        value="<?php echo $pillar['order_position'] ?? 1; ?>" min="1" max="100" style="width: 100px;">
                                    <small class="form-text text-muted">Nilai kecil tampil lebih dulu</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                            <?php echo $pillar['is_active'] ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_active">
                                            <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                        </label>
                                    </div>
                                </div>
                            </div>
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
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview Pilar</h5>
                </div>
                <div class="card-body">
                    <div class="text-center p-4 bg-light rounded">
                        <?php if (!empty($pillar['tab_icon'])): ?>
                            <img src="<?php echo '../' . $pillar['tab_icon']; ?>" style="max-width: 50px;" class="mb-2">
                        <?php else: ?>
                            <i class="mdi mdi-image-off text-muted mb-2" style="font-size: 40px;"></i>
                        <?php endif; ?>
                        <h5 class="mb-1"><?php echo htmlspecialchars($pillar['pillar_name']); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($pillar['subtitle'] ?? '-'); ?></small>
                    </div>
                </div>
            </div>

            <!-- Informasi Data -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">ID:</td>
                            <td><strong>#<?php echo $pillar['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Urutan:</td>
                            <td><span class="badge badge-info"><?php echo $pillar['order_position'] ?? 1; ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <span class="badge badge-<?php echo $pillar['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $pillar['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Tips -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Tips Update</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Kosongkan field gambar jika tidak ingin mengubah</li>
                        <li class="mb-2">Gambar lama akan dihapus otomatis saat upload baru</li>
                        <li class="mb-0">Nonaktifkan pilar jika tidak ingin ditampilkan</li>
                    </ul>
                </div>
            </div>

            <!-- Tips Gambar -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Gambar Utama:</strong> 600x400px, maks 2MB</li>
                        <li class="mb-2"><strong>Ikon Tab:</strong> 64x64px, maks 1MB</li>
                        <li class="mb-0"><strong>Format:</strong> JPG, PNG, GIF, WEBP, SVG</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

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
function truncateFilename(filename, maxLength = 25) {
    if (filename.length <= maxLength) return filename;
    const ext = filename.split('.').pop();
    const name = filename.substring(0, filename.lastIndexOf('.'));
    const truncatedName = name.substring(0, maxLength - ext.length - 4) + '...';
    return truncatedName + '.' + ext;
}

// Preview gambar utama
document.getElementById('main_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('mainImagePreview');
    const previewImg = document.getElementById('mainPreviewImg');
    const label = this.nextElementSibling;

    if (file) {
        label.textContent = truncateFilename(file.name);

        if (file.size > 2097152) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar!',
                text: 'Gambar utama maksimal 2MB.',
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

            // Validasi dimensi
            const img = new Image();
            img.onload = function() {
                if (this.width < 400 || this.height < 250) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dimensi Kecil!',
                        html: 'Dimensi: <strong>' + this.width + 'x' + this.height + '</strong>px<br>Disarankan minimal 600x400px',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#ffc107'
                    });
                } else {
                    Swal.fire({ icon: 'success', title: 'Gambar Siap!', text: 'Dimensi: ' + this.width + 'x' + this.height + 'px', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
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

// Preview ikon tab
document.getElementById('tab_icon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('iconPreview');
    const previewImg = document.getElementById('iconPreviewImg');
    const label = this.nextElementSibling;

    if (file) {
        label.textContent = truncateFilename(file.name);

        if (file.size > 1048576) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar!',
                text: 'Ikon tab maksimal 1MB.',
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
            Swal.fire({ icon: 'success', title: 'Ikon Siap!', toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        };
        reader.readAsDataURL(file);
    } else {
        label.textContent = 'Pilih ikon baru...';
        preview.style.display = 'none';
    }
});

// Validasi form
document.getElementById('pillarForm').addEventListener('submit', function(e) {
    const pillarName = document.getElementById('pillar_name').value.trim();

    if (pillarName.length < 3) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Nama Pilar Terlalu Pendek!',
            text: 'Nama pilar harus minimal 3 karakter.',
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