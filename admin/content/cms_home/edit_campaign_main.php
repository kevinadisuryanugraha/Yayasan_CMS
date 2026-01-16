<?php
// Edit Kampanye Utama
// Tabel: campaign_main (subtitle, title, background_image, amount_raised, goal_amount, button_text, button_link, is_active)

$query = mysqli_query($conn, "SELECT * FROM campaign_main WHERE id = 1 LIMIT 1");
$campaign = mysqli_fetch_assoc($query);

// Jika belum ada data, buat default
if (!$campaign) {
    mysqli_query($conn, "INSERT INTO campaign_main (id, subtitle, title, goal_amount) VALUES (1, 'Kampanye Mendesak', 'Bantu Mereka yang Membutuhkan', 100000000)");
    $query = mysqli_query($conn, "SELECT * FROM campaign_main WHERE id = 1 LIMIT 1");
    $campaign = mysqli_fetch_assoc($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Subtitle
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    if (strlen($subtitle) > 100) {
        $errors[] = 'Subjudul maksimal 100 karakter';
    }
    $subtitle = mysqli_real_escape_string($conn, $subtitle);

    // Validasi Judul (wajib)
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul kampanye wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul maksimal 255 karakter';
    } elseif (strlen($title) < 5) {
        $errors[] = 'Judul minimal 5 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Validasi Jumlah Terkumpul
    $amount_raised = isset($_POST['amount_raised']) ? floatval($_POST['amount_raised']) : 0;
    if ($amount_raised < 0) {
        $errors[] = 'Jumlah terkumpul tidak boleh negatif';
    }

    // Validasi Target Donasi (wajib)
    $goal_amount = isset($_POST['goal_amount']) ? floatval($_POST['goal_amount']) : 0;
    if ($goal_amount <= 0) {
        $errors[] = 'Target donasi harus lebih dari 0';
    }
    if ($amount_raised > $goal_amount) {
        $errors[] = 'Jumlah terkumpul tidak boleh melebihi target donasi';
    }

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

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $bg_image_path = $campaign['background_image'];

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
            $new_filename = 'campaign_bg_' . time() . '_' . uniqid() . '.' . $file_ext;
            if (!is_dir('../uploads/campaigns'))
                mkdir('../uploads/campaigns', 0755, true);
            if (move_uploaded_file($_FILES['background_image']['tmp_name'], '../uploads/campaigns/' . $new_filename)) {
                if ($campaign['background_image'] && file_exists('../' . $campaign['background_image'])) {
                    unlink('../' . $campaign['background_image']);
                }
                $bg_image_path = 'uploads/campaigns/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar latar';
            }
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $update = "UPDATE campaign_main SET 
                   subtitle = '$subtitle', title = '$title', background_image = '$bg_image_path',
                   amount_raised = $amount_raised, goal_amount = $goal_amount,
                   button_text = '$button_text', button_link = '$button_link', is_active = $is_active
                   WHERE id = 1";

        if (mysqli_query($conn, $update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Kampanye utama berhasil diperbarui'];
            header("Location: ?page=campaigns");
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
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', html: '" . addslashes($error_list) . "', confirmButtonText: 'Mengerti', confirmButtonColor: '#dc3545' }); });</script>";
}

// Hitung persentase progress
$progress = $campaign['goal_amount'] > 0 ? min(100, ($campaign['amount_raised'] / $campaign['goal_amount']) * 100) : 0;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=campaigns">Kampanye</a></li>
                        <li class="breadcrumb-item active">Edit Kampanye Utama</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Kampanye Utama</h4>
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
                                Kampanye Utama?</h5>
                            <p class="mb-0 text-muted">
                                <strong>Kampanye Utama</strong> adalah area donasi yang ditampilkan secara menonjol di
                                halaman utama website.
                                Di sini Anda dapat mengatur judul kampanye, gambar latar, target donasi, dan progress
                                donasi yang sudah terkumpul.
                                Bagian ini membantu pengunjung untuk langsung melihat kampanye donasi utama Anda.
                            </p>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <i class="mdi mdi-heart-circle text-danger" style="font-size: 60px;"></i>
                            <small class="d-block text-muted mt-2">Kampanye Donasi</small>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 header-title">Pengaturan Kampanye Utama</h4>
                            <p class="text-muted mb-0 font-14">Perbarui informasi kampanye donasi utama</p>
                        </div>
                        <span
                            class="badge badge-<?php echo ($campaign['is_active'] ?? 1) ? 'success' : 'secondary'; ?> p-2">
                            <?php echo ($campaign['is_active'] ?? 1) ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data" id="campaignForm">
                        <div class="form-group">
                            <label for="subtitle">Subjudul</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle"
                                value="<?php echo htmlspecialchars($campaign['subtitle'] ?? ''); ?>"
                                placeholder="Contoh: Kampanye Mendesak" maxlength="100">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Teks kecil di atas judul (opsional)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="title">Judul Kampanye <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?php echo htmlspecialchars($campaign['title'] ?? ''); ?>" required minlength="5"
                                maxlength="255" placeholder="Contoh: Bantu Saudara Kita yang Membutuhkan">
                            <small class="form-text text-muted">
                                <i class="mdi mdi-information-outline"></i> Judul utama kampanye (5-255 karakter)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="background_image">Gambar Latar</label>
                            <?php if (!empty($campaign['background_image'])): ?>
                                <div class="mb-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-2">
                                        <i class="mdi mdi-image mr-1"></i>Gambar Saat Ini:
                                    </small>
                                    <img src="<?php echo '../' . $campaign['background_image']; ?>"
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
                                <strong>Format:</strong> JPG, PNG, GIF, WEBP | <strong>Maks:</strong> 3MB |
                                <strong>Ideal:</strong> 1920x800px
                            </small>
                            <div id="imagePreview" class="mt-2" style="display:none;">
                                <small class="text-success d-block mb-1"><i class="mdi mdi-check-circle mr-1"></i>Gambar
                                    Baru (Preview):</small>
                                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded shadow-sm"
                                    style="max-height: 150px;">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cash-multiple mr-1"></i>Progress Donasi</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount_raised">Jumlah Terkumpul (Rp)</label>
                                    <input type="number" class="form-control" id="amount_raised" name="amount_raised"
                                        step="1" min="0" value="<?php echo $campaign['amount_raised'] ?? 0; ?>">
                                    <small class="form-text text-muted">
                                        <i class="mdi mdi-information-outline"></i> Dana yang sudah terkumpul
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="goal_amount">Target Donasi (Rp) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="goal_amount" name="goal_amount"
                                        step="1" min="1" required value="<?php echo $campaign['goal_amount'] ?? 0; ?>">
                                    <small class="form-text text-muted">
                                        <i class="mdi mdi-information-outline"></i> Total target yang ingin dicapai
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-gesture-tap-button mr-1"></i>Pengaturan Tombol</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_text">Teks Tombol</label>
                                    <input type="text" class="form-control" id="button_text" name="button_text"
                                        value="<?php echo htmlspecialchars($campaign['button_text'] ?? 'Donasi Sekarang'); ?>"
                                        maxlength="50" placeholder="Contoh: Donasi Sekarang">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_link">Link Tombol</label>
                                    <input type="text" class="form-control" id="button_link" name="button_link"
                                        value="<?php echo htmlspecialchars($campaign['button_link'] ?? '#donate'); ?>"
                                        maxlength="500" placeholder="Contoh: #donate, /donasi, https://...">
                                    <small class="form-text text-muted">
                                        Format: <code>#anchor</code>, <code>/halaman</code>, atau
                                        <code>https://...</code>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    <?php echo ($campaign['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="is_active">
                                    <span class="badge badge-success">Aktif</span> - Tampilkan kampanye di halaman depan
                                </label>
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
            <!-- Preview Progress -->
            <div class="card m-b-30 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-chart-line mr-2"></i>Preview Progress Donasi</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="text-success mb-0">Rp
                            <?php echo number_format($campaign['amount_raised'] ?? 0, 0, ',', '.'); ?>
                        </h3>
                        <small class="text-muted">terkumpul dari target Rp
                            <?php echo number_format($campaign['goal_amount'] ?? 0, 0, ',', '.'); ?></small>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                            role="progressbar" style="width: <?php echo $progress; ?>%;">
                            <strong><?php echo round($progress, 1); ?>%</strong>
                        </div>
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
                        <li class="mb-2"><strong>Subjudul:</strong> Teks pendek untuk menarik perhatian</li>
                        <li class="mb-2"><strong>Judul:</strong> Nama kampanye yang jelas dan mengena</li>
                        <li class="mb-2"><strong>Progress:</strong> Update jumlah terkumpul secara berkala</li>
                        <li class="mb-0"><strong>Gambar:</strong> Gunakan gambar yang emosional dan relevan</li>
                    </ul>
                </div>
            </div>

            <!-- Tips Gambar -->
            <div class="card m-b-30 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-image mr-2"></i>Tips Gambar Latar</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Ukuran ideal:</strong> 1920 x 800 piksel</li>
                        <li class="mb-2"><strong>Format:</strong> JPG atau WEBP untuk kompresi</li>
                        <li class="mb-2"><strong>Maks file:</strong> 3MB</li>
                        <li class="mb-0"><strong>Style:</strong> Gelap/blur untuk readability teks</li>
                    </ul>
                </div>
            </div>

            <!-- Navigasi Cepat -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-link-variant mr-2"></i>Navigasi Kampanye</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted font-14 mb-3">Akses cepat ke bagian kampanye lainnya</p>
                    <a href="?page=edit_campaign_sidebar" class="btn btn-outline-info btn-block mb-2">
                        <i class="mdi mdi-view-sidebar"></i> Edit Sidebar Kampanye
                    </a>
                    <a href="?page=programs" class="btn btn-outline-success btn-block">
                        <i class="mdi mdi-view-list"></i> Kelola Program Kampanye
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    // Preview gambar latar
    document.getElementById('background_image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const label = this.nextElementSibling;

        if (file) {
            label.textContent = file.name;

            if (file.size > 3145728) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal 3MB.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#dc3545'
                });
                this.value = '';
                label.textContent = 'Pilih gambar baru...';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';

                const img = new Image();
                img.onload = function () {
                    if (this.width < 800 || this.height < 400) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dimensi Gambar Kecil',
                            html: 'Dimensi: <strong>' + this.width + 'x' + this.height + '</strong> piksel.<br>Disarankan minimal <strong>1920x800</strong> piksel.',
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

    // Validasi form
    document.getElementById('campaignForm').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value.trim();
        const goalAmount = parseFloat(document.getElementById('goal_amount').value);
        const amountRaised = parseFloat(document.getElementById('amount_raised').value);

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

        if (goalAmount <= 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Target Tidak Valid!',
                text: 'Target donasi harus lebih dari 0.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        if (amountRaised > goalAmount) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Jumlah Melebihi Target!',
                text: 'Jumlah terkumpul tidak boleh melebihi target donasi.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#ffc107'
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

    // Toast status toggle
    document.getElementById('is_active').addEventListener('change', function () {
        const status = this.checked ? 'Aktif' : 'Nonaktif';
        Swal.fire({
            icon: this.checked ? 'success' : 'info',
            title: 'Status: ' + status,
            text: this.checked ? 'Kampanye akan ditampilkan setelah disimpan' : 'Kampanye akan disembunyikan setelah disimpan',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    });
</script>