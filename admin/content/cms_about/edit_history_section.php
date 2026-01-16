<?php
// Edit History Section Header CMS
if (!defined('INDEX_AUTH')) {
    header("Location: index.php");
    exit;
}

$id = 1; // Fixed ID for single section
$errors = [];

// Fetch Existing Data
$query = "SELECT * FROM about_history_section WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Handle Form Submission
if (isset($_POST['submit'])) {
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (empty($title))
        $errors[] = "Judul Utama wajib diisi.";

    if (empty($errors)) {
        $update_query = "UPDATE about_history_section SET 
            subtitle = '$subtitle', 
            title = '$title', 
            description = '$description' 
            WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Header Section berhasil diperbarui.'];
            echo "<script>window.location='?page=history_about';</script>";
            exit;
        } else {
            $errors[] = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Error Handling Persistence
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
                        <li class="breadcrumb-item"><a href="?page=history_about">Sejarah</a></li>
                        <li class="breadcrumb-item active">Edit Header</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Header Sejarah</h4>
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

    <form method="post" action="" id="editHeaderForm">
        <div class="row">
            <!-- Left Column: Form Input -->
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary"><i class="mdi mdi-format-header-1 mr-2"></i>Form Global Header</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Subtitle (Kecil)</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="subtitle" id="subtitle"
                                    value="<?php echo htmlspecialchars($_POST['subtitle'] ?? $data['subtitle']); ?>"
                                    placeholder="Contoh: Perjalanan Kami">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Judul Utama</label>
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

                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-right">
                                <a href="?page=history_about" class="btn btn-secondary btn-cancel">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="mdi mdi-content-save mr-1"></i> Simpan Header</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Live Preview -->
            <div class="col-md-4">
                <div class="card m-b-30 bg-light border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="m-0"><i class="mdi mdi-eye mr-2"></i>Preview Header</h5>
                    </div>
                    <div class="card-body text-center">
                        <span class="text-primary text-uppercase font-weight-bold small" id="preview_subtitle">
                            <?php echo htmlspecialchars($data['subtitle']); ?>
                        </span>
                        <h3 class="mt-2 mb-3" id="preview_title">
                            <?php echo htmlspecialchars($data['title']); ?>
                        </h3>
                        <p class="text-muted" id="preview_description">
                            <?php echo htmlspecialchars($data['description']); ?>
                        </p>
                    </div>
                </div>

                <div class="card m-b-30 border-warning">
                    <div class="card-body">
                        <h6 class="mt-0 header-title text-warning">Catatan</h6>
                        <p class="text-muted mb-0 small">
                            Header ini akan tampil di bagian paling atas halaman "Sejarah Yayasan" di website utama.
                            Gunakan judul yang menarik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Live Preview Logic
        const inputs = {
            'subtitle': 'preview_subtitle',
            'title': 'preview_title',
            'description': 'preview_description'
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
                    text: "Perubahan header tidak akan disimpan.",
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
        const form = document.getElementById('editHeaderForm');
        if (form) {
            form.addEventListener('submit', function () {
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memperbarui header...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        }
    });
</script>