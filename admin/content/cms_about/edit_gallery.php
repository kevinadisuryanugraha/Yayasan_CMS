<?php
// Edit Gallery CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$errors = [];

// Fetch Data
$query = "SELECT * FROM about_gallery_items WHERE id = $id";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    $_SESSION['alert'] = ['type' => 'error', 'title' => 'Error!', 'message' => 'Data tidak ditemukan.'];
    echo "<script>window.location='?page=gallery_about';</script>";
    exit;
}

// Handle Update
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $sort_order = intval($_POST['sort_order']);

    if (empty($title))
        $errors[] = "Judul Kegiatan wajib diisi.";
    if (empty($category))
        $errors[] = "Kategori wajib diisi.";

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
            $upload_dir = 'uploads/gallery/';
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
        $update_query = "UPDATE about_gallery_items SET 
            title = '$title', 
            category = '$category', 
            image = '$image_path', 
            sort_order = '$sort_order' 
            WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Update Berhasil!', 'message' => 'Data foto kegiatan berhasil diperbarui.'];
            echo "<script>window.location='?page=gallery_about';</script>";
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Persistence
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
                        <li class="breadcrumb-item"><a href="?page=gallery_about">Galeri</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Foto Galeri</h4>
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

    <form method="post" action="" enctype="multipart/form-data" id="editGalleryForm">
        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-pencil-box mr-2"></i>Edit Data Foto</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul Kegiatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title"
                                    value="<?php echo htmlspecialchars($_POST['title'] ?? $item['title']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="category" id="category"
                                    value="<?php echo htmlspecialchars($_POST['category'] ?? $item['category']); ?>"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Update Foto</label>
                            <div class="col-sm-9">
                                <div class="media mb-3">
                                    <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                                        <img src="<?php echo $item['image']; ?>" alt="Current" class="img-thumbnail mr-3"
                                            style="width: 120px; height: 80px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="media-body align-self-center">
                                        <h6 class="mt-0">Foto Saat Ini</h6>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti.</small>
                                    </div>
                                </div>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
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
                                <a href="?page=gallery_about" class="btn btn-secondary btn-cancel">Batal</a>
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
                        <div class="gallery-preview-box mb-3"
                            style="width: 100%; height: 200px; background: #ddd; border-radius: 10px; overflow: hidden; position: relative;">
                            <img id="preview_image"
                                src="<?php echo !empty($item['image']) ? $item['image'] : 'assets/images/placeholder_landscape.jpg'; ?>"
                                alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">

                            <!-- Overlay -->
                            <div
                                style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 15px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); text-align: left;">
                                <h6 class="text-white mb-0" id="preview_title" style="text-shadow: 1px 1px 2px black;">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </h6>
                                <small class="text-warning font-weight-bold" id="preview_category"
                                    style="text-shadow: 1px 1px 2px black;">
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </small>
                            </div>
                        </div>
                        <p class="text-muted small">Preview tampilan di halaman depan.</p>
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

        // Live Text
        const inputs = {
            'title': 'preview_title',
            'category': 'preview_category'
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

        // Cancel
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

        // Loader
        const form = document.getElementById('editGalleryForm');
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