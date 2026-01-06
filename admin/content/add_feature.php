<?php
// Tambah Kartu Fitur Baru
// Proses pengiriman form
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

    // Validasi dan upload ikon
    $icon_path = '';
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

    // Simpan ke database jika tidak ada error
    if (empty($errors)) {
        $query = "INSERT INTO feature_section (title, description, icon, link_text, link_url, order_position, is_active) 
                  VALUES ('$title', '$description', '$icon_path', '$link_text', '$link_url', $order_position, $is_active)";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Kartu fitur berhasil ditambahkan'
            ];
            header("Location: ?page=features");
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
    <!-- Judul Halaman -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=features">Feature Section</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Kartu Fitur Baru</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Detail Kartu Fitur</h4>
                    <p class="text-muted m-b-30 font-14">
                        Buat kartu fitur baru untuk ditampilkan di halaman utama
                    </p>

                    <form method="POST" action="" enctype="multipart/form-data" id="featureForm">
                        <!-- Judul -->
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">
                                Judul <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Contoh: Kajian Al-Quran, Sejarah Islam"
                                    required minlength="3" maxlength="100"
                                    value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Nama fitur/layanan (3-100 karakter)
                                </small>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Jelaskan singkat tentang fitur ini..."
                                    maxlength="500"><?php echo htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Maksimal 500 karakter
                                </small>
                            </div>
                        </div>

                        <!-- Ikon -->
                        <div class="form-group row">
                            <label for="icon" class="col-sm-3 col-form-label">Ikon</label>
                            <div class="col-sm-9">
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="icon" name="icon"
                                        accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                                    <label class="custom-file-label" for="icon">Pilih ikon...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> 
                                    <strong>Format:</strong> JPG, PNG, GIF, WEBP, SVG | <strong>Maks:</strong> 1MB | <strong>Ideal:</strong> 200x200px
                                </small>
                                <div id="iconPreview" class="mt-2" style="display:none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 100px;">
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
                                    placeholder="Contoh: Pelajari Lebih Lanjut, Selengkapnya"
                                    maxlength="50"
                                    value="<?php echo htmlspecialchars($form_data['link_text'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Teks yang muncul di tombol link (opsional)
                                </small>
                            </div>
                        </div>

                        <!-- URL Link -->
                        <div class="form-group row">
                            <label for="link_url" class="col-sm-3 col-form-label">URL Link</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="link_url" name="link_url"
                                    placeholder="Contoh: #programs, /kajian-quran, https://..."
                                    maxlength="500"
                                    value="<?php echo htmlspecialchars($form_data['link_url'] ?? ''); ?>">
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
                                    value="<?php echo htmlspecialchars($form_data['order_position'] ?? '1'); ?>" 
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
                                        <?php echo (!isset($form_data['is_active']) || isset($form_data['is_active'])) ? 'checked' : ''; ?>>
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
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="mdi mdi-plus"></i> Tambah Kartu Fitur
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
            <!-- Panduan Pengisian -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Pengisian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Judul (Wajib)</strong>
                        <p class="text-muted small mb-0">Nama fitur/layanan yang singkat dan jelas</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Deskripsi</strong>
                        <p class="text-muted small mb-0">Penjelasan singkat tentang fitur (1-2 kalimat)</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Ikon</strong>
                        <p class="text-muted small mb-0">Gambar ikon untuk visual kartu fitur</p>
                    </div>
                    <div class="mb-0">
                        <strong><i class="mdi mdi-numeric-4-circle text-primary mr-1"></i>Link & Urutan</strong>
                        <p class="text-muted small mb-0">Tombol aksi dan posisi tampilan</p>
                    </div>
                </div>
            </div>

            <!-- Tips Ikon -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Ikon</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ukuran ideal:</strong> 200 x 200 piksel (kotak)</li>
                        <li class="mb-2"><strong>Format terbaik:</strong> PNG atau SVG untuk transparansi</li>
                        <li class="mb-2"><strong>Ukuran file:</strong> Di bawah 100KB untuk loading cepat</li>
                        <li class="mb-0"><strong>Style:</strong> Konsisten dengan ikon fitur lainnya</li>
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
                        <span class="badge badge-primary p-2 m-1">Pendidikan Anak</span>
                        <span class="badge badge-primary p-2 m-1">Zakat & Infaq</span>
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
                        <span class="badge badge-secondary p-2 m-1">Daftar Sekarang</span>
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
                text: 'Ukuran file maksimal 1MB untuk ikon.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            this.value = '';
            label.textContent = 'Pilih ikon...';
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
        label.textContent = 'Pilih ikon...';
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
        title: 'Menyimpan Data...',
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
    const title = document.getElementById('title').value.trim();
    
    if (title) {
        Swal.fire({
            icon: 'warning',
            title: 'Batalkan Pengisian?',
            text: 'Data yang sudah diisi akan hilang.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjut Mengisi',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) window.location.href = link;
        });
    } else {
        window.location.href = link;
    }
});
</script>