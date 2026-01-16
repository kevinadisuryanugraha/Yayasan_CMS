<?php
// Add Team Member CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$errors = [];

// Handle Form Submission
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $facebook = mysqli_real_escape_string($conn, $_POST['link_facebook']);
    $twitter = mysqli_real_escape_string($conn, $_POST['link_twitter']);
    $linkedin = mysqli_real_escape_string($conn, $_POST['link_linkedin']);
    $sort_order = intval($_POST['sort_order']);

    // Validasi Dasar
    if (empty($name))
        $errors[] = "Nama Lengkap wajib diisi.";
    if (empty($role))
        $errors[] = "Posisi/Jabatan wajib diisi.";

    // Handle Image Upload
    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['image']['name'];
        $filetmploc = $_FILES['image']['tmp_name'];
        $filesize = $_FILES['image']['size'];
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($fileext, $allowed)) {
            $errors[] = "Format gambar harus JPG, JPEG, atau PNG.";
        }
        if ($filesize > 2097152) { // 2MB
            $errors[] = "Ukuran gambar maksimal 2MB.";
        }

        if (empty($errors)) {
            // Create Unique Name
            $new_filename = time() . "_" . rand(100, 999) . "." . $fileext;
            $upload_dir = 'uploads/team/';
            if (!file_exists($upload_dir))
                mkdir($upload_dir, 0777, true);

            $target_path = $upload_dir . $new_filename;

            if (move_uploaded_file($filetmploc, $target_path)) {
                $image_path = $target_path;
            } else {
                $errors[] = "Gagal mengupload gambar ke server.";
            }
        }
    } else {
        $errors[] = "Foto anggota wajib diupload.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO about_team_items (name, role, image, link_facebook, link_twitter, link_linkedin, sort_order) 
                  VALUES ('$name', '$role', '$image_path', '$facebook', '$twitter', '$linkedin', '$sort_order')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Anggota tim baru berhasil ditambahkan.'];
            echo "<script>window.location='?page=team_about';</script>";
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Error Persistence
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
}

$form_data = $_SESSION['form_data'] ?? [];
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data']);
unset($_SESSION['form_errors']);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="?page=intro_section_about">About</a></li>
                        <li class="breadcrumb-item"><a href="?page=team_about">Tim</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Anggota Tim</h4>
            </div>
        </div>
    </div>

    <!-- Error Alert Script -->
    <?php if (!empty($form_errors)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let errorHtml = '<ul style="text-align:left;margin:0;padding-left:20px;">';
                <?php foreach ($form_errors as $error): ?>
                        errorHtml += '<li><?php echo $error; ?></li>';
                <?php endforeach; ?>
                    errorHtml += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errorHtml,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data" id="addTeamForm">
        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-account-plus mr-2"></i>Data Anggota</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Contoh: Dr. Ahmad Fauzi"
                                    value="<?php echo htmlspecialchars($form_data['name'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Posisi / Jabatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="role" id="role"
                                    placeholder="Contoh: Ketua Yayasan"
                                    value="<?php echo htmlspecialchars($form_data['role'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Foto Profil</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="image" id="image" accept="image/*"
                                    required>
                                <small class="text-muted">Format: JPG/PNG. Maks 2MB. Disarankan rasio 1:1.</small>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted mb-3"><i class="mdi mdi-share-variant"></i> Sosial Media (Opsional)</h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Facebook</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="mdi mdi-facebook"></i></span></div>
                                    <input type="url" class="form-control" name="link_facebook"
                                        value="<?php echo htmlspecialchars($form_data['link_facebook'] ?? ''); ?>"
                                        placeholder="https://facebook.com/username">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Twitter / X</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="mdi mdi-twitter"></i></span></div>
                                    <input type="url" class="form-control" name="link_twitter"
                                        value="<?php echo htmlspecialchars($form_data['link_twitter'] ?? ''); ?>"
                                        placeholder="https://twitter.com/username">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">LinkedIn</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="mdi mdi-linkedin"></i></span></div>
                                    <input type="url" class="form-control" name="link_linkedin"
                                        value="<?php echo htmlspecialchars($form_data['link_linkedin'] ?? ''); ?>"
                                        placeholder="https://linkedin.com/in/username">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="sort_order"
                                    value="<?php echo htmlspecialchars($form_data['sort_order'] ?? '0'); ?>">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-right">
                                <a href="?page=team_about" class="btn btn-secondary btn-cancel">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="mdi mdi-content-save mr-1"></i> Simpan Data</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Preview -->
            <div class="col-md-4">
                <div class="card m-b-30 bg-light border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="m-0"><i class="mdi mdi-eye mr-2"></i>Preview Hasil</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="team-preview-container mb-3"
                            style="width: 150px; height: 150px; margin: 0 auto; overflow: hidden; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <img id="preview_image" src="assets/images/user-placeholder.png" alt="Preview"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h5 class="text-primary font-weight-bold mb-1" id="preview_name">Nama Anggota</h5>
                        <p class="text-muted mb-2" id="preview_role">Jabatan</p>

                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-light text-primary"><i class="mdi mdi-facebook"></i></button>
                            <button class="btn btn-light text-info"><i class="mdi mdi-twitter"></i></button>
                            <button class="btn btn-light text-primary"><i class="mdi mdi-linkedin"></i></button>
                        </div>
                    </div>
                </div>

                <div class="card m-b-30 border-warning">
                    <div class="card-body">
                        <h6 class="mt-0 header-title text-warning">Panduan Foto</h6>
                        <ul class="pl-3 mb-0 text-muted small">
                            <li>Gunakan foto formal/semi-formal.</li>
                            <li>Pastikan wajah terlihat jelas.</li>
                            <li>Background foto netral atau blur disarankan.</li>
                            <li>Nyalakan fitur preview untuk melihat crop lingkaran.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Image Preview Logic
        const imgInput = document.getElementById('image');
        const imgPreview = document.getElementById('preview_image');

        if (imgInput) {
            imgInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imgPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Text Live Preview
        const inputs = {
            'name': 'preview_name',
            'role': 'preview_role'
        };

        Object.keys(inputs).forEach(inputId => {
            const inputEl = document.getElementById(inputId);
            const previewEl = document.getElementById(inputs[inputId]);

            if (inputEl) {
                inputEl.addEventListener('input', function () {
                    previewEl.textContent = this.value || (inputId === 'name' ? 'Nama Anggota' : 'Jabatan');
                });
            }
        });

        // Cancel Confirmation
        const btnCancel = document.querySelector('.btn-cancel');
        if (btnCancel) {
            btnCancel.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');

                // Cek input
                const hasInput = document.getElementById('name').value || document.getElementById('image').value;

                if (hasInput) {
                    Swal.fire({
                        title: 'Batalkan Pengisian?',
                        text: "Data yang sudah diisi akan hilang.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Isi',
                        confirmButtonColor: '#dc3545',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                } else {
                    window.location.href = href;
                }
            });
        }

        // Submit Loader
        const form = document.getElementById('addTeamForm');
        if (form) {
            form.addEventListener('submit', function () {
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang mengupload gambar...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        }
    });
</script>