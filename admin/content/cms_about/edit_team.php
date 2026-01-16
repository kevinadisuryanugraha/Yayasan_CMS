<?php
// Edit Team Member CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$errors = [];

// Fetch Data
$query = "SELECT * FROM about_team_items WHERE id = $id";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Error!', 'message' => 'Data tidak ditemukan.'];
    echo "<script>window.location='?page=team_about';</script>";
    exit;
}

// Handle Update
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $facebook = mysqli_real_escape_string($conn, $_POST['link_facebook']);
    $twitter = mysqli_real_escape_string($conn, $_POST['link_twitter']);
    $linkedin = mysqli_real_escape_string($conn, $_POST['link_linkedin']);
    $sort_order = intval($_POST['sort_order']);

    if (empty($name))
        $errors[] = "Nama Lengkap wajib diisi.";
    if (empty($role))
        $errors[] = "Posisi/Jabatan wajib diisi.";

    // Image Handling
    $image_path = $item['image']; // Default to old image

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['image']['name'];
        $filetmploc = $_FILES['image']['tmp_name'];
        $filesize = $_FILES['image']['size'];
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($fileext, $allowed)) {
            $errors[] = "Format gambar harus JPG, JPEG, atau PNG.";
        }
        if ($filesize > 2097152) {
            $errors[] = "Ukuran gambar maksimal 2MB.";
        }

        if (empty($errors)) {
            // Upload New Image
            $new_filename = time() . "_" . rand(100, 999) . "." . $fileext;
            $upload_dir = 'uploads/team/';
            if (!file_exists($upload_dir))
                mkdir($upload_dir, 0777, true);

            $target_path = $upload_dir . $new_filename;

            if (move_uploaded_file($filetmploc, $target_path)) {
                // Delete Old Image if exists
                if (!empty($item['image']) && file_exists($item['image'])) {
                    unlink($item['image']);
                }
                $image_path = $target_path;
            } else {
                $errors[] = "Gagal mengupload gambar baru.";
            }
        }
    }

    if (empty($errors)) {
        $update_query = "UPDATE about_team_items SET 
            name = '$name', 
            role = '$role', 
            image = '$image_path', 
            link_facebook = '$facebook', 
            link_twitter = '$twitter', 
            link_linkedin = '$linkedin', 
            sort_order = '$sort_order' 
            WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil Update!', 'message' => 'Data anggota berhasil diperbarui.'];
            echo "<script>window.location='?page=team_about';</script>";
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Validation Persistence
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
}
$form_errors = $_SESSION['form_errors'] ?? [];
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
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Anggota Tim</h4>
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
                    title: 'Gagal Menyimpan!',
                    html: errorHtml,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data" id="editTeamForm">
        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-pencil-box mr-2"></i>Edit Data Anggota</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" id="name"
                                    value="<?php echo htmlspecialchars($_POST['name'] ?? $item['name']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Posisi / Jabatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="role" id="role"
                                    value="<?php echo htmlspecialchars($_POST['role'] ?? $item['role']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Foto Profil</label>
                            <div class="col-sm-9">
                                <div class="media mb-3">
                                    <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                                        <img src="<?php echo $item['image']; ?>" alt="Current" class="d-flex mr-3 rounded"
                                            height="64">
                                    <?php else: ?>
                                        <div class="d-flex mr-3 rounded bg-light align-items-center justify-content-center"
                                            style="width: 64px; height: 64px;">
                                            <i class="mdi mdi-image-off text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="media-body">
                                        <h6 class="mt-0">Foto Saat Ini</h6>
                                        <small class="text-muted">Biarkan input file kosong jika tidak ingin mengubah
                                            foto.</small>
                                    </div>
                                </div>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted mb-3"><i class="mdi mdi-share-variant"></i> Sosial Media</h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Facebook</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="mdi mdi-facebook"></i></span></div>
                                    <input type="url" class="form-control" name="link_facebook"
                                        value="<?php echo htmlspecialchars($_POST['link_facebook'] ?? $item['link_facebook']); ?>">
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
                                        value="<?php echo htmlspecialchars($_POST['link_twitter'] ?? $item['link_twitter']); ?>">
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
                                        value="<?php echo htmlspecialchars($_POST['link_linkedin'] ?? $item['link_linkedin']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="sort_order"
                                    value="<?php echo htmlspecialchars($_POST['sort_order'] ?? $item['sort_order']); ?>">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-right">
                                <a href="?page=team_about" class="btn btn-secondary btn-cancel">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="mdi mdi-content-save mr-1"></i> Simpan Perubahan</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Preview -->
            <div class="col-md-4">
                <div class="card m-b-30 bg-light border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="m-0"><i class="mdi mdi-eye mr-2"></i>Preview Perubahan</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="team-preview-container mb-3"
                            style="width: 150px; height: 150px; margin: 0 auto; overflow: hidden; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <img id="preview_image"
                                src="<?php echo !empty($item['image']) ? $item['image'] : 'assets/images/user-placeholder.png'; ?>"
                                alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h5 class="text-primary font-weight-bold mb-1" id="preview_name">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </h5>
                        <p class="text-muted mb-2" id="preview_role">
                            <?php echo htmlspecialchars($item['role']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Image Preview
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

        // Live Text Preview
        const inputs = {
            'name': 'preview_name',
            'role': 'preview_role'
        };

        Object.keys(inputs).forEach(inputId => {
            const inputEl = document.getElementById(inputId);
            const previewEl = document.getElementById(inputs[inputId]);

            if (inputEl) {
                inputEl.addEventListener('input', function () {
                    previewEl.textContent = this.value || '...';
                });
            }
        });

        // Cancel Confirmation
        const btnCancel = document.querySelector('.btn-cancel');
        if (btnCancel) {
            btnCancel.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');

                Swal.fire({
                    title: 'Batalkan Perubahan?',
                    text: "Perubahan yang belum disimpan akan hilang.",
                    icon: 'warning',
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
        }

        // Submit Loader
        const form = document.getElementById('editTeamForm');
        if (form) {
            form.addEventListener('submit', function () {
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memperbarui data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        }
    });
</script>