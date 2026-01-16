<?php
// Add Gallery CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$errors = [];

// Handle Form Submission
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $sort_order = intval($_POST['sort_order']);

    // Validasi Dasar
    if (empty($title))
        $errors[] = "Judul Kegiatan wajib diisi.";
    if (empty($category))
        $errors[] = "Kategori wajib diisi.";

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
            $upload_dir = 'uploads/gallery/';
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
        $errors[] = "Foto kegiatan wajib diupload.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO about_gallery_items (title, category, image, sort_order) 
                  VALUES ('$title', '$category', '$image_path', '$sort_order')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Foto kegiatan baru berhasil ditambahkan.'];
            echo "<script>window.location='?page=gallery_about';</script>";
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
                        <li class="breadcrumb-item"><a href="?page=gallery_about">Galeri</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Foto Galeri</h4>
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

    <form method="post" action="" enctype="multipart/form-data" id="addGalleryForm">
        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-image-plus mr-2"></i>Data Foto</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul Kegiatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Contoh: Bakti Sosial 2024"
                                    value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="category" id="category"
                                    placeholder="Contoh: Sosial / Pendidikan"
                                    value="<?php echo htmlspecialchars($form_data['category'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Upload Foto</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="image" id="image" accept="image/*"
                                    required>
                                <small class="text-muted">Format: JPG/PNG. Maks 2MB. Resolusi Landscape
                                    disarankan.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Urutan</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="sort_order"
                                    value="<?php echo htmlspecialchars($form_data['sort_order'] ?? '0'); ?>">
                                <small class="text-muted">Urutan 0 akan menjadi prioritas (Featured Image).</small>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-right">
                                <a href="?page=gallery_about" class="btn btn-secondary btn-cancel">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="mdi mdi-content-save mr-1"></i> Simpan Galeri</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Preview -->
            <div class="col-md-4">
                <div class="card m-b-30 bg-light border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="m-0"><i class="mdi mdi-eye mr-2"></i>Preview Mini</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="gallery-preview-box mb-3"
                            style="width: 100%; height: 200px; background: #ddd; border-radius: 10px; overflow: hidden; position: relative;">
                            <img id="preview_image" src="assets/images/placeholder_landscape.jpg" alt="Preview Image"
                                style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6;">

                            <!-- Overlay Simulation -->
                            <div
                                style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 15px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); text-align: left;">
                                <h6 class="text-white mb-0" id="preview_title" style="text-shadow: 1px 1px 2px black;">
                                    Judul Kegiatan</h6>
                                <small class="text-warning font-weight-bold" id="preview_category"
                                    style="text-shadow: 1px 1px 2px black;">Kategori</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card m-b-30 border-warning">
                    <div class="card-body">
                        <h6 class="mt-0 header-title text-warning">Panduan Upload</h6>
                        <ul class="pl-3 mb-0 text-muted small">
                            <li>Foto orientasi <strong>Landscape</strong> lebih disarankan.</li>
                            <li>Judul sebaiknya pendek dan jelas (2-4 kata).</li>
                            <li>Foto pertama (Urutan terkecil) akan menjadi Highlight.</li>
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
                        imgPreview.style.opacity = '1';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Live Text Preview
        const inputs = {
            'title': 'preview_title',
            'category': 'preview_category'
        };

        Object.keys(inputs).forEach(inputId => {
            const inputEl = document.getElementById(inputId);
            const previewEl = document.getElementById(inputs[inputId]);

            if (inputEl) {
                inputEl.addEventListener('input', function () {
                    previewEl.textContent = this.value || (inputId === 'title' ? 'Judul Kegiatan' : 'Kategori');
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
                const hasInput = document.getElementById('title').value || document.getElementById('image').value;

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
        const form = document.getElementById('addGalleryForm');
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