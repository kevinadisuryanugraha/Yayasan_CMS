<?php
// Edit Sidebar Kampanye
// Tabel: campaign_sidebar (title, headline, background_image, description, button_text, button_link)

$query = mysqli_query($conn, "SELECT * FROM campaign_sidebar WHERE id = 1 LIMIT 1");
$sidebar = mysqli_fetch_assoc($query);

// Jika belum ada data, buat default
if (!$sidebar) {
    mysqli_query($conn, "INSERT INTO campaign_sidebar (id, title, headline, description) VALUES (1, 'Bantu Sesama', 'Donasi Untuk Kebaikan', 'Donasi Anda dapat membuat perbedaan.')");
    $query = mysqli_query($conn, "SELECT * FROM campaign_sidebar WHERE id = 1 LIMIT 1");
    $sidebar = mysqli_fetch_assoc($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Judul Kecil
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (strlen($title) > 100) {
        $errors[] = 'Judul kecil maksimal 100 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Headline (wajib)
    $headline = isset($_POST['headline']) ? trim($_POST['headline']) : '';
    if (empty($headline)) {
        $errors[] = 'Headline wajib diisi';
    } elseif (strlen($headline) > 255) {
        $errors[] = 'Headline maksimal 255 karakter';
    } elseif (strlen($headline) < 5) {
        $errors[] = 'Headline minimal 5 karakter';
    }
    $headline = mysqli_real_escape_string($conn, $headline);

    // Validasi Deskripsi
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    if (strlen($description) > 1000) {
        $errors[] = 'Deskripsi maksimal 1000 karakter';
    }
    $description = mysqli_real_escape_string($conn, $description);

    // Validasi Teks Tombol
    $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : '';
    if (strlen($button_text) > 50) {
        $errors[] = 'Teks tombol maksimal 50 karakter';
    }
    $button_text = mysqli_real_escape_string($conn, $button_text);

    // Validasi Link Tombol
    $button_link = isset($_POST['button_link']) ? trim($_POST['button_link']) : '';
    if (strlen($button_link) > 500) {
        $errors[] = 'Link tombol maksimal 500 karakter';
    }
    if (!empty($button_link) && !preg_match('/^(#|\/|https?:\/\/)/', $button_link)) {
        $errors[] = 'Format link tidak valid';
    }
    $button_link = mysqli_real_escape_string($conn, $button_link);

    $bg_image_path = $sidebar['background_image'];

    // Validasi dan upload gambar latar
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
            $errors[] = 'Ukuran gambar terlalu besar. Maksimal 3MB';
        }

        if (empty($errors)) {
            $new_filename = 'sidebar_bg_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/campaigns'))
                mkdir('../uploads/campaigns', 0755, true);
            if (move_uploaded_file($_FILES['background_image']['tmp_name'], '../uploads/campaigns/' . $new_filename)) {
                if ($sidebar['background_image'] && file_exists('../' . $sidebar['background_image'])) {
                    unlink('../' . $sidebar['background_image']);
                }
                $bg_image_path = 'uploads/campaigns/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar latar';
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $update = "UPDATE campaign_sidebar SET 
                   title = '$title', headline = '$headline', background_image = '$bg_image_path',
                   description = '$description', button_text = '$button_text', button_link = '$button_link'
                   WHERE id = 1";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Sidebar kampanye berhasil diperbarui'];
            header("Location: ?page=edit_campaign_sidebar");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

// Handle success alert from session
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

// Handle error alerts
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
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
                        <li class="breadcrumb-item"><a href="?page=campaigns">Kampanye</a></li>
                        <li class="breadcrumb-item active">Edit Sidebar</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Sidebar Kampanye</h4>
            </div>
        </div>
    </div>

    <!-- Kartu Petunjuk -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2"><i class="mdi mdi-help-circle-outline text-primary mr-2"></i>Apa itu
                                Sidebar Kampanye?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Sidebar Kampanye</strong> adalah panel informasi yang muncul di samping daftar
                                program kampanye.
                                Di sini Anda dapat menampilkan pesan ajakan, headline menarik, dan tombol aksi untuk
                                mengarahkan pengunjung
                                ke halaman donasi atau informasi lebih lanjut.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-page-layout-sidebar-right text-primary" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Panel Sidebar</small>
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
                    <h4 class="mt-0 header-title">Pengaturan Sidebar</h4>
                    <p class="text-muted m-b-30 font-14">Perbarui tampilan sidebar kampanye</p>

                    <form method="POST" action="" enctype="multipart/form-data" id="sidebarForm">
                        <div class="form-group">
                            <label for="title">Judul Kecil</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?php echo htmlspecialchars($sidebar['title'] ?? ''); ?>"
                                placeholder="Contoh: Bantu Sesama, Mari Berdonasi" maxlength="100">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Teks kecil di atas headline (opsional)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="headline">Headline <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="headline" name="headline"
                                value="<?php echo htmlspecialchars($sidebar['headline'] ?? ''); ?>"
                                placeholder="Contoh: Donasi Untuk Kebaikan Bersama" required minlength="5"
                                maxlength="255">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Judul utama sidebar (5-255 karakter)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                placeholder="Tuliskan pesan ajakan untuk berdonasi..."
                                maxlength="1000"><?php echo htmlspecialchars($sidebar['description'] ?? ''); ?></textarea>
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Maksimal 1000 karakter
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="background_image">Gambar Latar</label>
                            <?php if (!empty($sidebar['background_image'])): ?>
                                <div class="mb-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-2">
                                        <i class="mdi mdi-image mr-1"></i>Gambar Saat Ini:
                                    </small>
                                    <img src="<?php echo '../' . $sidebar['background_image']; ?>"
                                        class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                </div>
                            <?php endif; ?>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="background_image"
                                    name="background_image" accept="image/jpeg,image/png,image/gif,image/webp">
                                <label class="custom-file-label" for="background_image">Pilih gambar baru...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i>
                                <strong>Maks:</strong> 3MB | <strong>Ideal:</strong> 600x800px (portrait)
                            </small>
                            <div id="imagePreview" class="mt-2" style="display:none;">
                                <small class="text-success d-block mb-1"><i class="mdi mdi-check-circle mr-1"></i>Gambar
                                    Baru:</small>
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded shadow-sm"
                                    style="max-height: 150px;">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-gesture-tap-button mr-1"></i>Pengaturan Tombol</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_text">Teks Tombol</label>
                                    <input type="text" class="form-control" id="button_text" name="button_text"
                                        value="<?php echo htmlspecialchars($sidebar['button_text'] ?? 'Lihat Semua Program'); ?>"
                                        maxlength="50" placeholder="Contoh: Lihat Semua Program">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_link">Link Tombol</label>
                                    <input type="text" class="form-control" id="button_link" name="button_link"
                                        value="<?php echo htmlspecialchars($sidebar['button_link'] ?? '#programs'); ?>"
                                        maxlength="500" placeholder="Contoh: #programs, /donasi">
                                    <small class="form-text text-muted">
                                        Format: <code>#anchor</code>, <code>/halaman</code>, atau
                                        <code>https://...</code>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="?page=campaigns" class="btn btn-secondary btn-lg btn-cancel">
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
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Preview Sidebar</h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-light rounded">
                        <p class="text-primary mb-1"
                            style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($sidebar['title'] ?? 'Bantu Sesama'); ?>
                        </p>
                        <h5 class="mb-2 font-weight-bold">
                            <?php echo htmlspecialchars($sidebar['headline'] ?? 'Donasi Untuk Kebaikan'); ?>
                        </h5>
                        <p class="text-muted mb-3" style="font-size: 13px;">
                            <?php
                            $desc = htmlspecialchars($sidebar['description'] ?? 'Donasi Anda dapat membuat perbedaan.');
                            echo strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc;
                            ?>
                        </p>
                        <button class="btn btn-sm btn-primary">
                            <?php echo htmlspecialchars($sidebar['button_text'] ?? 'Lihat Semua Program'); ?>
                        </button>
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
                        <li class="mb-2"><strong>Judul Kecil:</strong> Label kategori atau ajakan singkat</li>
                        <li class="mb-2"><strong>Headline:</strong> Judul utama yang menarik perhatian</li>
                        <li class="mb-2"><strong>Deskripsi:</strong> Pesan singkat mengajak berdonasi</li>
                        <li class="mb-0"><strong>Tombol:</strong> CTA untuk aksi pengunjung</li>
                    </ul>
                </div>
            </div>

            <!-- Tips Gambar -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ukuran ideal:</strong> 600 x 800 piksel</li>
                        <li class="mb-2"><strong>Orientasi:</strong> Portrait lebih baik</li>
                        <li class="mb-0"><strong>Maks file:</strong> 3MB</li>
                    </ul>
                </div>
            </div>

            <!-- Navigasi -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-link-variant mr-2"></i>Navigasi Kampanye</h5>
                </div>
                <div class="card-body">
                    <a href="?page=edit_campaign_main" class="btn btn-outline-info btn-block mb-2">
                        <i class="mdi mdi-home"></i> Edit Kampanye Utama
                    </a>
                    <a href="?page=programs" class="btn btn-outline-success btn-block">
                        <i class="mdi mdi-view-list"></i> Kelola Program
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $alert_script; ?>
<?php echo $error_script; ?>

<script>
    // Preview gambar
    document.getElementById('background_image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const label = this.nextElementSibling;
        const inputElement = this;

        if (file) {
            label.textContent = file.name;

            // Validasi ukuran file
            if (file.size > 3145728) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal 3MB.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#dc3545'
                });
                inputElement.value = '';
                label.textContent = 'Pilih gambar baru...';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';

                // Validasi dimensi gambar
                const img = new Image();
                img.onload = function () {
                    const width = this.width;
                    const height = this.height;

                    if (width < 400 || height < 500) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dimensi Gambar Kecil!',
                            html: 'Dimensi gambar: <strong>' + width + ' x ' + height + '</strong> piksel.<br><br>Disarankan minimal <strong>600 x 800</strong> piksel untuk hasil terbaik.',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#ffc107'
                        });
                    } else if (width > height) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Orientasi Landscape',
                            html: 'Gambar sidebar disarankan menggunakan orientasi <strong>Portrait</strong> (tinggi lebih besar dari lebar).<br><br>Dimensi saat ini: <strong>' + width + ' x ' + height + '</strong> piksel.',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#17a2b8'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Gambar Siap!',
                            text: 'Dimensi: ' + width + ' x ' + height + ' piksel',
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

    // Validasi form
    document.getElementById('sidebarForm').addEventListener('submit', function (e) {
        const headline = document.getElementById('headline').value.trim();

        if (headline.length < 5) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Headline Terlalu Pendek!',
                text: 'Headline harus minimal 5 karakter.',
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
    document.querySelector('.btn-cancel').addEventListener('click', function (e) {
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