<?php
// Tambah Layanan Baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Kategori (opsional)
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    if (strlen($category) > 100) {
        $errors[] = 'Kategori maksimal 100 karakter';
    }
    $category = mysqli_real_escape_string($conn, $category);

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul wajib diisi';
    } elseif (strlen($title) > 150) {
        $errors[] = 'Judul maksimal 150 karakter';
    } elseif (strlen($title) < 3) {
        $errors[] = 'Judul minimal 3 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Deskripsi (opsional)
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 1000) {
        $errors[] = 'Deskripsi maksimal 1000 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Link URL (opsional)
    $link_url = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
    if (strlen($link_url) > 500) {
        $errors[] = 'Link URL maksimal 500 karakter';
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

    $main_image_path = '';
    $icon_path = '';

    // Validasi dan upload gambar utama
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $file_ext = strtolower(pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['main_image']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format gambar utama tidak diizinkan. Hanya JPG, PNG, GIF, WEBP';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file gambar utama tidak valid';
        }
        if ($_FILES['main_image']['size'] > 2097152) {
            $errors[] = 'Ukuran gambar utama terlalu besar. Maksimal 2MB';
        }

        if (empty($errors)) {
            $new_filename = 'main_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/services')) mkdir('../uploads/services', 0755, true);
            if (move_uploaded_file($_FILES['main_image']['tmp_name'], '../uploads/services/' . $new_filename)) {
                $main_image_path = 'uploads/services/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar utama';
            }
        }
    }

    // Validasi dan upload ikon
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0 && empty($errors)) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg');
        $allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml');
        $file_ext = strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['icon']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format ikon tidak diizinkan. Hanya JPG, PNG, GIF, WEBP, SVG';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file ikon tidak valid';
        }
        if ($_FILES['icon']['size'] > 1048576) {
            $errors[] = 'Ukuran ikon terlalu besar. Maksimal 1MB';
        }

        if (empty($errors)) {
            $new_filename = 'icon_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/services')) mkdir('../uploads/services', 0755, true);
            if (move_uploaded_file($_FILES['icon']['tmp_name'], '../uploads/services/' . $new_filename)) {
                $icon_path = 'uploads/services/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload ikon';
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $query = "INSERT INTO service_section (category, title, description, main_image, icon, link_url, order_position, is_active) 
                  VALUES ('$category', '$title', '$description', '$main_image_path', '$icon_path', '$link_url', $order_position, $is_active)";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Layanan berhasil ditambahkan'];
            header("Location: ?page=services");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}

$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
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
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=services">Layanan</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Layanan Baru</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Layanan</h4>
                    <p class="text-muted m-b-30 font-14">Buat layanan baru untuk ditampilkan di halaman utama</p>

                    <form method="POST" action="" enctype="multipart/form-data" id="serviceForm">
                        <div class="form-group row">
                            <label for="category" class="col-sm-3 col-form-label">Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="category" name="category"
                                    placeholder="Contoh: Pendidikan, Sosial, Ibadah" maxlength="100"
                                    value="<?php echo htmlspecialchars($form_data['category'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Kelompok jenis layanan (opsional)
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Judul <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Contoh: Kajian Rutin Mingguan" required minlength="3" maxlength="150"
                                    value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Nama layanan (3-150 karakter)
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Jelaskan tentang layanan ini..." maxlength="1000"><?php echo htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Maksimal 1000 karakter
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="main_image" class="col-sm-3 col-form-label">Gambar Utama</label>
                            <div class="col-sm-9">
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="main_image" name="main_image"
                                        accept="image/jpeg,image/png,image/gif,image/webp">
                                    <label class="custom-file-label" for="main_image">Pilih gambar...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Gambar besar layanan | <strong>Maks:</strong> 2MB
                                </small>
                                <div id="mainPreview" class="mt-2" style="display:none;">
                                    <img id="mainPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="icon" class="col-sm-3 col-form-label">Ikon</label>
                            <div class="col-sm-9">
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="icon" name="icon"
                                        accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                                    <label class="custom-file-label" for="icon">Pilih ikon...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Ikon kecil | <strong>Maks:</strong> 1MB | <strong>Ideal:</strong> 100x100px
                                </small>
                                <div id="iconPreview" class="mt-2" style="display:none;">
                                    <img id="iconPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 80px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="link_url" class="col-sm-3 col-form-label">Link URL</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="link_url" name="link_url"
                                    placeholder="Contoh: #layanan, /detail-kajian" maxlength="500"
                                    value="<?php echo htmlspecialchars($form_data['link_url'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    Format: <code>#anchor</code>, <code>/halaman</code>, atau <code>https://...</code>
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="order_position" class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="order_position" name="order_position"
                                    value="<?php echo htmlspecialchars($form_data['order_position'] ?? '1'); ?>" 
                                    min="1" max="100" style="width: 100px;">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Posisi tampilan (1 = tampil pertama)
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        <?php echo (!isset($form_data['is_active']) || isset($form_data['is_active'])) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">
                                        <span class="badge badge-success">Aktif</span> - Tampilkan di halaman depan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="mdi mdi-plus"></i> Tambah Layanan
                                </button>
                                <a href="?page=services" class="btn btn-secondary btn-lg btn-cancel">
                                    <i class="mdi mdi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Kategori</strong>
                        <p class="text-muted small mb-0">Kelompok layanan (Pendidikan, Sosial, dll)</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Judul (Wajib)</strong>
                        <p class="text-muted small mb-0">Nama layanan yang ditawarkan</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Deskripsi</strong>
                        <p class="text-muted small mb-0">Penjelasan singkat tentang layanan</p>
                    </div>
                    <div class="mb-0">
                        <strong><i class="mdi mdi-numeric-4-circle text-primary mr-1"></i>Gambar & Ikon</strong>
                        <p class="text-muted small mb-0">Visual untuk kartu layanan</p>
                    </div>
                </div>
            </div>

            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-lightbulb-on mr-2"></i>Contoh Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-primary p-2 m-1">Kajian Rutin</span>
                        <span class="badge badge-primary p-2 m-1">Konsultasi Syariah</span>
                        <span class="badge badge-primary p-2 m-1">Pendidikan Anak</span>
                        <span class="badge badge-primary p-2 m-1">Zakat & Infaq</span>
                        <span class="badge badge-primary p-2 m-1">Bimbingan Pernikahan</span>
                    </div>
                </div>
            </div>

            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Gambar utama:</strong> 800x600px, maks 2MB</li>
                        <li class="mb-2"><strong>Ikon:</strong> 100x100px, maks 1MB</li>
                        <li class="mb-0"><strong>Format:</strong> JPG, PNG, WEBP</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
// Preview gambar utama
document.getElementById('main_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('mainPreview');
    const previewImg = document.getElementById('mainPreviewImg');
    const label = this.nextElementSibling;
    
    if (file) {
        label.textContent = file.name;
        if (file.size > 2097152) {
            Swal.fire({ icon: 'error', title: 'File Terlalu Besar!', text: 'Maksimal 2MB untuk gambar utama.', confirmButtonColor: '#dc3545' });
            this.value = ''; label.textContent = 'Pilih gambar...'; preview.style.display = 'none';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) { previewImg.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else { label.textContent = 'Pilih gambar...'; preview.style.display = 'none'; }
});

// Preview ikon
document.getElementById('icon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('iconPreview');
    const previewImg = document.getElementById('iconPreviewImg');
    const label = this.nextElementSibling;
    
    if (file) {
        label.textContent = file.name;
        if (file.size > 1048576) {
            Swal.fire({ icon: 'error', title: 'File Terlalu Besar!', text: 'Maksimal 1MB untuk ikon.', confirmButtonColor: '#dc3545' });
            this.value = ''; label.textContent = 'Pilih ikon...'; preview.style.display = 'none';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) { previewImg.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else { label.textContent = 'Pilih ikon...'; preview.style.display = 'none'; }
});

// Validasi form
document.getElementById('serviceForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    if (title.length < 3) {
        e.preventDefault();
        Swal.fire({ icon: 'error', title: 'Judul Terlalu Pendek!', text: 'Judul harus minimal 3 karakter.', confirmButtonColor: '#dc3545' });
        return false;
    }
    Swal.fire({ title: 'Menyimpan Data...', html: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    return true;
});

// Konfirmasi batal
document.querySelector('.btn-cancel').addEventListener('click', function(e) {
    e.preventDefault();
    const link = this.href;
    const title = document.getElementById('title').value.trim();
    if (title) {
        Swal.fire({
            icon: 'warning', title: 'Batalkan Pengisian?', text: 'Data yang sudah diisi akan hilang.',
            showCancelButton: true, confirmButtonText: 'Ya, Batalkan', cancelButtonText: 'Lanjut Mengisi',
            confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then((result) => { if (result.isConfirmed) window.location.href = link; });
    } else { window.location.href = link; }
});
</script>