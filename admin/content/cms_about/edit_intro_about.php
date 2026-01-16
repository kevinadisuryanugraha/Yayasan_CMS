<?php
// Edit Intro About
// Cek parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'ID Konten tidak valid atau tidak ditemukan.'
    ];
    header("Location: ?page=intro_section_about");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM about_section WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Tidak Ditemukan',
        'message' => 'Data Intro tidak ditemukan di database.'
    ];
    header("Location: ?page=intro_section_about");
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Validasi Judul
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    if (empty($title)) {
        $errors[] = 'Judul wajib diisi';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Judul maksimal 255 karakter';
    } elseif (strlen($title) < 3) {
        $errors[] = 'Judul minimal 3 karakter';
    }
    $title = mysqli_real_escape_string($conn, $title);

    // Filter input lain
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle'] ?? '');
    $sub_heading = mysqli_real_escape_string($conn, $_POST['sub_heading'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');

    $stat_number = mysqli_real_escape_string($conn, $_POST['stat_number'] ?? '');
    $stat_text = mysqli_real_escape_string($conn, $_POST['stat_text'] ?? '');
    $stat_icon = mysqli_real_escape_string($conn, $_POST['stat_icon'] ?? '');

    $button_text = trim($_POST['button_text'] ?? '');
    $button_link = trim($_POST['button_link'] ?? '');

    if (strlen($button_text) > 100) {
        $errors[] = 'Teks tombol maksimal 100 karakter';
    }
    if (!empty($button_text) && empty($button_link)) {
        $errors[] = 'Link tombol wajib diisi jika teks tombol ada';
    }
    if (strlen($button_link) > 500) {
        $errors[] = 'Link tombol maksimal 500 karakter';
    }

    $button_text = mysqli_real_escape_string($conn, $button_text);
    $button_link = mysqli_real_escape_string($conn, $button_link);

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle Image Upload
    $image_path = $data['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $allowed_mime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $file_mime = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Format file tidak diizinkan (JPG, PNG, WEBP, GIF).';
        }
        if (!in_array($file_mime, $allowed_mime)) {
            $errors[] = 'Tipe file tidak valid.';
        }
        if ($_FILES['image']['size'] > 3145728) {
            $errors[] = 'Ukuran file terlalu besar (Max 3MB).';
        }

        if (empty($errors)) {
            $new_filename = 'about_intro_' . time() . '_' . uniqid() . '.' . $file_ext;
            $target_dir = '../uploads/about/';
            if (!is_dir($target_dir))
                mkdir($target_dir, 0755, true);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_filename)) {
                // Delete old image
                if (!empty($data['image']) && file_exists('../' . $data['image'])) {
                    unlink('../' . $data['image']);
                }
                $image_path = 'uploads/about/' . $new_filename;
            } else {
                $errors[] = 'Gagal mengupload gambar baru.';
            }
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
        $errors[] = 'Terjadi kesalahan saat upload gambar.';
    }

    if (empty($errors)) {
        $update_query = "UPDATE about_section SET 
            title='$title', subtitle='$subtitle', sub_heading='$sub_heading', 
            description='$description', image='$image_path', 
            stat_number='$stat_number', stat_text='$stat_text', stat_icon='$stat_icon',
            button_text='$button_text', button_link='$button_link', is_active=$is_active,
            updated_at=NOW()
            WHERE id=$id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Data Intro berhasil diperbarui'];
            header("Location: ?page=intro_section_about");
            exit;
        } else {
            $errors[] = 'Database Error: ' . mysqli_error($conn);
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
}

// Error handling logic
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
$error_script = '';
if (!empty($form_errors)) {
    $error_list = '<ul style="text-align:left;margin:0;padding-left:20px;">';
    foreach ($form_errors as $error)
        $error_list .= '<li>' . htmlspecialchars($error) . '</li>';
    $error_list .= '</ul>';
    $error_script = "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '" . addslashes($error_list) . "',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">Intro About</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Konten Intro</h4>
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
                            <h4 class="mt-0 header-title">Detail Konten Intro</h4>
                            <p class="text-muted mb-0 font-14">
                                Update informasi dan statistik pada bagian intro.
                            </p>
                        </div>
                        <span
                            class="badge badge-<?php echo ($data['is_active'] == 1) ? 'success' : 'secondary'; ?> p-2">
                            <?php echo ($data['is_active'] == 1) ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>

                    <form method="POST" enctype="multipart/form-data" id="editIntroForm">

                        <!-- Judul -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul (Title) <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title" required
                                    placeholder="Contoh: Selamat Datang" minlength="3" maxlength="255"
                                    value="<?php echo htmlspecialchars($data['title']); ?>">
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Judul utama section (Wajib)
                                </small>
                            </div>
                        </div>

                        <!-- Subjudul -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Subjudul</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="subtitle"
                                    placeholder="Teks kecil di atas judul" maxlength="255"
                                    value="<?php echo htmlspecialchars($data['subtitle']); ?>">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="description" id="description" rows="5"
                                    placeholder="Deskripsi singkat..."><?php echo htmlspecialchars($data['description']); ?></textarea>
                            </div>
                        </div>

                        <!-- Gambar -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Gambar Utama</label>
                            <div class="col-sm-9">
                                <?php if ($data['image'] && file_exists('../' . $data['image'])): ?>
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <label class="text-muted small mb-2 d-block">
                                            <i class="mdi mdi-image mr-1"></i>Gambar Saat Ini:
                                        </label>
                                        <img src="../<?php echo $data['image']; ?>" class="img-fluid rounded shadow-sm mb-2"
                                            style="max-height: 200px;">
                                    </div>
                                <?php endif; ?>

                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input" id="image" name="image"
                                        accept="image/jpeg,image/png,image/gif,image/webp">
                                    <label class="custom-file-label" for="image">Ganti Gambar Baru...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information-outline"></i> Kosongkan jika tidak ingin mengubah
                                    gambar. Max 3MB.
                                </small>

                                <div id="imagePreview" class="mt-2" style="display:none;">
                                    <label class="text-success small mb-2 d-block">
                                        <i class="mdi mdi-check-circle mr-1"></i>Gambar Baru (Preview):
                                    </label>
                                    <img id="previewImg" src="" class="img-fluid rounded shadow-sm"
                                        style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-chart-bar mr-1"></i>Kartu Statistik</h5>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Angka Statistik</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="stat_number"
                                    value="<?php echo htmlspecialchars($data['stat_number'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Label Statistik</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="stat_text"
                                    value="<?php echo htmlspecialchars($data['stat_text'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Ikon</label>
                            <div class="col-sm-9">
                                <select class="form-control select2" id="stat_icon" name="stat_icon">
                                    <option value="">-- Pilih Ikon --</option>
                                    <?php
                                    $icons = [
                                        'icofont-calendar',
                                        'icofont-users',
                                        'icofont-star',
                                        'icofont-globe',
                                        'icofont-building-alt',
                                        'icofont-chart-growth',
                                        'icofont-award',
                                        'icofont-education'
                                    ];
                                    foreach ($icons as $ic) {
                                        $sel = ($data['stat_icon'] == $ic) ? 'selected' : '';
                                        echo "<option value='$ic' $sel>$ic</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3"><i class="mdi mdi-cursor-default-click mr-1"></i>Tombol Aksi</h5>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Teks Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_text" name="button_text"
                                    value="<?php echo htmlspecialchars($data['button_text'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Link Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="button_link" name="button_link"
                                    value="<?php echo htmlspecialchars($data['button_link'] ?? ''); ?>">
                                <small class="form-text text-muted">
                                    Wajib diisi jika teks tombol diisi.
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        <?php echo ($data['is_active'] == 1) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">Aktif - Tampilkan di
                                        Website</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row m-t-20">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-content-save-edit"></i> Simpan Perubahan
                                </button>
                                <a href="?page=intro_section_about" class="btn btn-secondary btn-lg btn-cancel">
                                    <i class="mdi mdi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Kanan -->
        <div class="col-lg-4">

            <!-- Info Data -->
            <div class="card m-b-30 border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-information mr-2"></i>Informasi Data</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">ID Konten:</td>
                            <td><strong>#<?php echo $data['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat:</td>
                            <td><?php echo isset($data['created_at']) ? date('d M Y, H:i', strtotime($data['created_at'])) : '-'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diupdate:</td>
                            <td><?php echo isset($data['updated_at']) ? date('d M Y, H:i', strtotime($data['updated_at'])) : '-'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <?php if ($data['is_active']): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Preview Statistik -->
            <div class="card m-b-30 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-eye mr-2"></i>Live Preview Statistik</h5>
                </div>
                <div class="card-body text-center">
                    <div class="p-3 border rounded bg-light">
                        <i id="preview-stat-icon"
                            class="<?php echo htmlspecialchars($data['stat_icon'] ?: 'icofont-calendar'); ?> display-4 text-primary"></i>
                        <h2 id="preview-stat-number" class="font-weight-bold mt-2 text-dark">
                            <?php echo htmlspecialchars($data['stat_number'] ?: '10+'); ?>
                        </h2>
                        <p id="preview-stat-text" class="mb-0 text-muted">
                            <?php echo htmlspecialchars($data['stat_text'] ?: 'Tahun Pengalaman'); ?>
                        </p>
                    </div>
                    <small class="text-muted mt-2 d-block">Perubahan statistik akan langsung terlihat di sini.</small>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card m-b-30 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle mr-2"></i>Panduan Edit</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-1-circle text-primary mr-1"></i>Judul</strong>
                        <p class="text-muted small mb-0">Pastikan judul tetap relevan dan menarik.</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-2-circle text-primary mr-1"></i>Gambar</strong>
                        <p class="text-muted small mb-0">Jika gambar tidak diubah, sistem akan tetap menggunakan gambar
                            lama.</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="mdi mdi-numeric-3-circle text-primary mr-1"></i>Tombol</strong>
                        <p class="text-muted small mb-0">Cek kembali link tombol agar tidak broken link.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php echo $error_script; ?>

<script>
    $(document).ready(function () {
        // Init Select2
        function formatIcon(state) {
            if (!state.id) return state.text;
            return $('<span><i class="' + state.element.value + ' mr-2"></i> ' + state.text + '</span>');
        }
        $('.select2').select2({
            templateResult: formatIcon,
            templateSelection: formatIcon,
            width: '100%'
        });

        // Image Preview
        $('#image').change(function (e) {
            const file = this.files[0];
            const label = $(this).next('.custom-file-label');
            const preview = $('#imagePreview');
            const previewImg = $('#previewImg');

            if (file) {
                label.text(file.name);

                if (file.size > 3145728) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Maksimal 3MB.',
                        confirmButtonColor: '#dc3545'
                    });
                    this.value = '';
                    label.text('Ganti Gambar Baru...');
                    preview.hide();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    previewImg.attr('src', event.target.result);
                    preview.show();

                    // Check Dimensions
                    const img = new Image();
                    img.onload = function () {
                        if (this.width < 800) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Resolusi Rendah',
                                text: 'Lebar gambar kurang dari 800px. Hasil mungkin pecah.',
                                confirmButtonColor: '#ffc107'
                            });
                        }
                    }
                    img.src = event.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                label.text('Ganti Gambar Baru...');
                preview.hide();
            }
        });

        // Live Preview Stats
        $('input[name="stat_number"]').on('input', function () {
            $('#preview-stat-number').text($(this).val() || '10+');
        });
        $('input[name="stat_text"]').on('input', function () {
            $('#preview-stat-text').text($(this).val() || 'Tahun Pengalaman');
        });
        $('#stat_icon').on('change select2:select', function () {
            var iconClass = $(this).val() || 'icofont-calendar';
            $('#preview-stat-icon').attr('class', iconClass + ' display-4 text-primary');
        });

        // Validation on Submit
        $('#editIntroForm').on('submit', function (e) {
            const title = $('#title').val().trim();
            const btnText = $('#button_text').val().trim();
            const btnLink = $('#button_link').val().trim();

            if (title.length < 3) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Judul Kurang Panjang',
                    text: 'Minimal 3 karakter.',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            if (btnText !== '' && btnLink === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Link Tombol Kosong',
                    text: 'Jika teks tombol diisi, link tombol juga harus diisi.',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            Swal.fire({
                title: 'Menyimpan Perubahan...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });

        // Cancel Confirmation
        $('.btn-cancel').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                icon: 'question',
                title: 'Batalkan Edit?',
                text: 'Perubahan yang belum disimpan akan hilang.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Lanjut Edit',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Status Toast
        $('#is_active').on('change', function () {
            const status = this.checked ? 'Aktif' : 'Nonaktif';
            const msg = this.checked ? 'Konten akan ditampilkan.' : 'Konten akan disembunyikan.';
            Swal.fire({
                icon: this.checked ? 'success' : 'info',
                title: 'Status: ' + status,
                text: msg,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        });
    });
</script>