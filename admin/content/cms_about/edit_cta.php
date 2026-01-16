<?php
// Edit CTA Section CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$id = 1;
$errors = [];

// Fetch Existing Data
$query = "SELECT * FROM about_cta_section WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Handle Form Submission
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $btn_primary_text = mysqli_real_escape_string($conn, $_POST['btn_primary_text']);
    $btn_primary_link = mysqli_real_escape_string($conn, $_POST['btn_primary_link']);
    $btn_outline_text = mysqli_real_escape_string($conn, $_POST['btn_outline_text']);
    $btn_outline_link = mysqli_real_escape_string($conn, $_POST['btn_outline_link']);

    // Validasi Sederhana
    if (empty($title))
        $errors[] = "Judul CTA wajib diisi.";
    if (empty($btn_primary_text))
        $errors[] = "Teks Tombol Utama wajib diisi.";

    if (empty($errors)) {
        $update_query = "UPDATE about_cta_section SET 
            title = '$title', 
            description = '$description',
            btn_primary_text = '$btn_primary_text', 
            btn_primary_link = '$btn_primary_link',
            btn_outline_text = '$btn_outline_text', 
            btn_outline_link = '$btn_outline_link'
            WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Update Berhasil!', 'message' => 'Konten CTA berhasil diperbarui.'];
            echo "<script>window.location='?page=cta_about';</script>";
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
                        <li class="breadcrumb-item"><a href="?page=cta_about">CTA</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit CTA Section</h4>
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

    <form method="post" action="" id="editCtaForm">
        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-pencil-box mr-2"></i>Edit Konten</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul CTA</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title"
                                    value="<?php echo htmlspecialchars($_POST['title'] ?? $data['title']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="description" id="description"
                                    rows="3"><?php echo htmlspecialchars($_POST['description'] ?? $data['description']); ?></textarea>
                            </div>
                        </div>

                        <h6 class="mt-4 mb-3 text-muted border-bottom pb-2">Tombol Utama (Solid)</h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Label Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="btn_primary_text" id="btn_primary_text"
                                    value="<?php echo htmlspecialchars($_POST['btn_primary_text'] ?? $data['btn_primary_text']); ?>"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Link Tujuan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="btn_primary_link"
                                    value="<?php echo htmlspecialchars($_POST['btn_primary_link'] ?? $data['btn_primary_link']); ?>">
                                <small class="text-muted">Contoh: <code>?page=contact</code> atau
                                    <code>https://wa.me/...</code></small>
                            </div>
                        </div>

                        <h6 class="mt-4 mb-3 text-muted border-bottom pb-2">Tombol Kedua (Outline)</h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Label Tombol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="btn_outline_text" id="btn_outline_text"
                                    value="<?php echo htmlspecialchars($_POST['btn_outline_text'] ?? $data['btn_outline_text']); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Link Tujuan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="btn_outline_link"
                                    value="<?php echo htmlspecialchars($_POST['btn_outline_link'] ?? $data['btn_outline_link']); ?>">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-right">
                                <a href="?page=cta_about" class="btn btn-secondary btn-cancel">Batal</a>
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
                        <h5 class="m-0"><i class="mdi mdi-eye mr-2"></i>Live Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-4 rounded text-white text-center"
                            style="background: linear-gradient(135deg, #0a294a 0%, #00997d 100%);">
                            <h5 id="preview_title" class="mb-2">
                                <?php echo htmlspecialchars($data['title']); ?>
                            </h5>
                            <p id="preview_description" class="small text-white-50 mb-3">
                                <?php echo htmlspecialchars($data['description']); ?>
                            </p>

                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-sm btn-light rounded-pill font-weight-bold mb-2 disabled"
                                    id="preview_primary">
                                    <?php echo htmlspecialchars($data['btn_primary_text']); ?>
                                </button>
                                <button class="btn btn-sm btn-outline-light rounded-pill font-weight-bold disabled"
                                    id="preview_outline">
                                    <?php echo htmlspecialchars($data['btn_outline_text']); ?>
                                </button>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 text-center">Tampilan visual sederhana.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Live Text Preview
        const inputs = {
            'title': 'preview_title',
            'description': 'preview_description',
            'btn_primary_text': 'preview_primary',
            'btn_outline_text': 'preview_outline'
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
        const form = document.getElementById('editCtaForm');
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